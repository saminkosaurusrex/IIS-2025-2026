<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\Show;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function index(){
        $user = auth()->user();
        if($user->roles->pluck('name')->contains('admin')){
            $reservations = Reservation::with(['user', 'event.show', 'event.hall'])->orderByRaw("
                    CASE
                        WHEN canceled_at IS NULL AND confirmed_at IS NULL THEN 1  
                        WHEN confirmed_at IS NOT NULL AND canceled_at IS NULL THEN 2                     
                        WHEN canceled_at IS NOT NULL THEN 3                       
                    END
                ")->orderBy('reserved_at')->get()->makeHidden(['email', 'name']);
        }else{
            $reservations = Reservation::with(['user', 'event.show', 'event.hall'])->whereHas(
                'event.hall', function ($query) use ($user){
                    $query->whereIn('halls.id', $user->managed_halls()->pluck('halls.id'));
                })->orderByRaw("
                    CASE
                        WHEN canceled_at IS NULL AND confirmed_at IS NULL THEN 1  
                        WHEN confirmed_at IS NOT NULL AND canceled_at IS NULL THEN 2                     
                        WHEN canceled_at IS NOT NULL THEN 3                       
                    END
                ")->orderBy('reserved_at')->get()->makeHidden(['email', 'name']);
        }
        $reservations->map(function ($reservation){
            $reservation->name = $reservation->user->name ?? $reservation->name;
            $reservation->email = $reservation->user->email ?? $reservation->email;
            $reservation->showName = $reservation->event->show->name;
            $reservation->hallName = $reservation->event->hall->name;
            $reservation->starting_at = $reservation->event->starting_at;
            $reservation->price = $reservation->event->price;
            return $reservation;
        });
        return Inertia::render('admin/reservations/Index', compact('reservations'));
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

    public function show($id){
        $reservation = Reservation::select('event_id','access_code' ,'confirmed_at', 'canceled_at', 'row', 'column')->with(['event.show', 'event.hall'])->where('id', $id)->orderBy('reserved_at', 'desc')->first();
        return Inertia::render('admin/reservations/Show', ['reservation' =>[
            'showName' => $reservation->event->show->name,
            'address' => $reservation->event->hall->address,
            'hallName' => $reservation->event->hall->name,
            'starting_at' => $reservation->event->starting_at,
            'code' => $reservation->access_code,
            'price' => $reservation->event->price,
            'confirmed_at' => $reservation->confirmed_at ?? null,
            'canceled_at' => $reservation->canceled_at ?? null,
            'row' => $reservation->row,
            'column' => $reservation->column
        ]]);
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
