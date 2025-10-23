<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\Show;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{

    public function index(Request $request){

        $start_datetime = null;
        $end_datetime = null;

        try {
            if ($request->has('start_date') && $request->query('start_date') != '') {
                $start_datetime = Carbon::parse($request->query('start_date'));
            }

            if ($request->has('end_date')&& $request->query('end_date') != '') {
                $end_datetime = Carbon::parse($request->query('end_date'));
            }
        } catch (InvalidFormatException $e) {
        }

        if ($start_datetime && $end_datetime) {
            $start_datetime = $start_datetime->startOfDay();
            $end_datetime   = $end_datetime->endOfDay();
        }

        if (!$start_datetime && !$end_datetime) {
            $start_datetime = now();
        }

        $hallsFilter = $request->query('halls', []);
        $showsFilter = $request->query('shows', []);
        $selectedRow = $request->query('selectedRow');
        $selectedCol = $request->query('selectedCol');

        $user = auth()->user();

        $reservationsQuery = Reservation::with(['user', 'event.show', 'event.hall.managed_by_users'])
            ->orderByRaw("
        CASE
            WHEN canceled_at IS NULL AND confirmed_at IS NULL THEN 1
            WHEN confirmed_at IS NOT NULL AND canceled_at IS NULL THEN 2
            WHEN canceled_at IS NOT NULL THEN 3
        END
    ")->orderBy('reserved_at');

        if($selectedRow){
            $reservationsQuery->where("row",$selectedRow);
        }
        if($selectedCol){
            $reservationsQuery->where("column",$selectedCol);
        }

        if ($start_datetime && $end_datetime) {
            $reservationsQuery->whereHas('event', function ($q) use ($start_datetime, $end_datetime) {
                $q->where('starting_at', '>=', $start_datetime)
                    ->where('ending_at', '<=', $end_datetime);
            });
        }else if ($start_datetime) {
            $reservationsQuery->whereHas('event', function ($q) use ($start_datetime) {
                $q->where('starting_at', '>=', $start_datetime);
            });
        }else if ($end_datetime) {
            $reservationsQuery->whereHas('event', function ($q) use ($end_datetime) {
                $q->where('ending_at', '<=', $end_datetime);
            });
        }

        if ($user->roles->pluck('name')->contains('admin')) {
            // admin môže filtrovať podľa vybraných sál
            if (!empty($hallsFilter)) {
                $reservationsQuery->whereHas('event.hall', function ($q) use ($hallsFilter) {
                    $q->whereIn('id', $hallsFilter);
                });

                $shows = Show::whereHas('events', function ($q) use ($hallsFilter) {
                    $q->whereIn('hall_id', $hallsFilter);
                })->select('id', 'name')
                    ->orWhereIn('id', $showsFilter ?? [])
                    ->select('id', 'name')
                    ->distinct()
                    ->get();
            }else{
                $shows = Show::all();
            }

            if (!empty($showsFilter)) {
                $reservationsQuery->whereHas('event.show', function ($q) use ($showsFilter) {
                    $q->whereIn('id', $showsFilter);
                });
                $halls = Hall::whereHas('events', function ($q) use ($showsFilter) {
                    $q->whereIn('show_id', $showsFilter);
                }) ->orWhereIn('id', $hallsFilter ?? [])
                    ->distinct()
                    ->get();
            }else{
              $halls = Hall::all();
            }



        } else {
            $managedHallIds = $user->managed_halls()->pluck('halls.id')->toArray();

            $filterHallIds = !empty($hallsFilter)
                ? array_intersect($managedHallIds, $hallsFilter)
                : $managedHallIds;

            $managedHallShowIds = Show::whereHas('events', function ($q) use ($filterHallIds, $showsFilter) {
                $q->whereIn('hall_id', $filterHallIds);
                if (!empty($showsFilter)) {
                    $q->whereIn('show_id', $showsFilter);
                }
            })
                ->pluck('id')
                ->toArray();


            if (!empty($hallsFilter)) {

                $reservationsQuery->whereHas('event.hall', function ($q) use ($filterHallIds) {
                    $q->whereIn('id', $filterHallIds);
                });
            }
            if (!empty($showsFilter)) {
                $reservationsQuery->whereHas('event.show', function ($q) use ($managedHallShowIds) {
                    $q->whereIn('id', $managedHallShowIds);
                });

            }
            $halls = $user->managed_halls;
            $shows = Show::whereHas('events', function ($q) use ($managedHallIds) {
                $q->whereIn('hall_id', $managedHallIds);
            })->select('id', 'name')
                ->get();


        }



        // paginácia s query stringom
        $reservations = $reservationsQuery->paginate(30)->withQueryString();


        return Inertia::render('admin/reservations/Index', [
            'reservations' => $reservations,
            'shows' => $shows,
            'selectedShows' => $showsFilter,
            'halls' => $halls,
            'selectedHalls' => $hallsFilter,
            'selectedRow' => $selectedRow,
            'selectedCol' => $selectedCol,
            'startDatetime' => $start_datetime?->format('Y-m-d\TH:i:s'),
            'endDatetime' => $end_datetime?->format('Y-m-d\TH:i:s')]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            "reservation_id" => "required|exists:reservations,id",
            "action" => "required|numeric|in:1,2,3,4",
        ]);
        $user = auth()->user();


        // kontrola ak je cashier cize nie je admin tak ci upravuje rezerváciu ktorá spadá pod jeho spravované haly
        if (!$user->roles()->pluck('name')->contains('admin')){
            $reservation = Reservation::whereHas('event.hall.managed_by_users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('id', $data['reservation_id'])
                ->first();

            if (!$reservation) {
                abort(403, 'Unauthorized action.');
            }
        }else{
            $reservation = Reservation::where('id', $data['reservation_id'])->first();
        }

        if ($reservation->canceled_at){
            return back()->withErrors([
                'reservation_id' => 'Rezerváciu ktorá bola zrušená nie je možné upravovať',
            ])->withInput();
        }

        switch ($data['action']) {
            case 1:
            {
                if ($reservation->confirmed_at || $reservation->paid_at) {
                    return back()->withErrors([
                        'reservation_id' => 'Rezervácia už bola potvrdená',
                    ])->withInput();
                }else{
                    $reservation->confirmed_at = now();
                }
                break;
            }
            case 2:
            {
                if ($reservation->confirmed_at || $reservation->paid_at) {
                    return back()->withErrors([
                        'reservation_id' => 'Rezervácia už bola potvrdená alebo zaplatená',
                    ])->withInput();
                }else{
                    $reservation->confirmed_at = now();
                    $reservation->paid_at = now();
                }
                break;
            }
            case 3:
            {
                if (!$reservation->confirmed_at || $reservation->paid_at) {
                    return back()->withErrors([
                        'reservation_id' => 'Rezervácia nebola potvrdená alebo je už zaplatená ',
                    ])->withInput();
                }else{
                    $reservation->paid_at = now();
                }
                break;
            }
            case 4:{
                $reservation->canceled_at = now();
                break;
            }
        }
        $reservation->save();
        return redirect()->back()->withInput();
    }

    public function dashboard(){
        $reservations = Reservation::select('id','event_id', 'confirmed_at', 'canceled_at')->with(['event.show', 'event.hall'])->where('user_id', auth()->id())->orderBy('reserved_at', 'desc')->get();
        $reservations->map(function ($reservation){
            $reservation->showName = $reservation->event->show->name;
            $reservation->hallName = $reservation->event->hall->name;
            $reservation->starting_at = $reservation->event->starting_at;
            $reservation->image = $reservation->event->show->image;
            return $reservation;
        });
        return Inertia::render('Dashboard', compact('reservations'));
    }


    public function create(){
        $user = auth()->user();

        if($user->roles->pluck('name')->contains('admin')){
            $halls = Hall::all();

        }else{
            $halls = Hall::whereIn('id', $user->managed_halls()->pluck('halls.id'))->get();
        }

        $events = Event::all();
        $shows = Show::all();
        return Inertia::render('admin/reservations/Create', compact(['events', 'halls', 'shows']));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'hall' => 'required|exists:halls,id',
            'show' => 'required|exists:shows,id',
            'price' => 'required|numeric',
            'date' => 'required',
            'time' => 'required',
            'seats' => 'required|array',
            'event_id' => 'required'
        ]);
        foreach($validated['seats'] as $seat){
            $access_code = Str::uuid();
            Reservation::create([
                'event_id' => $validated['event_id'],
                'row' => $seat['row'],
                'column' => $seat['column'],
                'reserved_at' => now(),
                'confirmed_at' => now(),
                'access_code' => $access_code
            ]);
        }

        return redirect('/reservations')->with('success', 'Rezervácia bola vztvorená.');
    }

    // Confirm reservation
    public function edit($id){
        $reservation = Reservation::where('id', $id)->first();
        $reservation->update(['confirmed_at' => now()]);
    }
    // Cancel reservation
    public function destroy($id){
        $reservation = Reservation::where('id', $id)->first();
        $reservation->update(['canceled_at' => now()]);
    }
}
