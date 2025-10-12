<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        if($user){
            $data = $request->validate([
                'event_id' => 'required|exists:events,id',
                'selectedSeats' => 'required|array|min:1|max:6',
            ]);
        }else{
            $data = $request->validate([
                'event_id' => 'required|exists:events,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'selectedSeats' => 'required|array|min:1|max:6',
            ]);
        }



        $event = Event::where('id', $data['event_id'])->firstOrFail();

        if (Carbon::parse($event->starting_at)->isPast()) {
            return back()->withErrors([
                'event_id' => 'Túto udalosť už nie je možné rezervovať – začala sa alebo už prebehla.',
            ])->withInput();
        }

        $takenSeats = Reservation::where('event_id', $data['event_id'])
            ->whereNull('canceled_at')
            ->whereNotNull('reserved_at')
            ->get(['row', 'column'])
            ->toArray();

        if ($user) {
            $registered_seats_by_user = Reservation::where('event_id', $data['event_id'])
                ->where('user_id', $user->id)
                ->count();
        }else{
            $registered_seats_by_user = Reservation::where('event_id', $data['event_id'])
                ->where('email', $data['email'])
                ->count();
        }

        if($registered_seats_by_user + count($data['selectedSeats']) > 6){
            return back()->withErrors([
                'selectedSeatsTooMany' => "Maximálny počet rezervácii na osobu je 6. Aktuálny počet rezervácii: " .$registered_seats_by_user
            ])->withInput();
        }




        $seats = "";
        foreach ($data['selectedSeats'] as $seat) {
            foreach ($takenSeats as $taken) {
                if ($seat['row'] == $taken['row'] && $seat['column'] == $taken['column']) {
                    $seats .= "[".$seat['row']. ",". $seat['column'] . "] ";
                }
            }
        }


        if (strlen($seats) > 0) {
            return back()->withErrors([
                'selectedSeats' => "Tieto miesta sú už zabraté: ". $seats
            ])->withInput();
        }

        $accessCodes = [];
        DB::transaction(function () use ($data, &$accessCodes,$user) {
            foreach ($data['selectedSeats'] as $seat) {
                $access_code = Str::uuid();

                if($user){
                    Reservation::create([
                        'event_id' => $data['event_id'],
                        'user_id' => $user->id,
                        'row' => $seat['row'],
                        'column' => $seat['column'],
                        'reserved_at' => now(),
                        'access_code' => $access_code
                    ]);
                }else{
                    Reservation::create([
                        'event_id' => $data['event_id'],
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'row' => $seat['row'],
                        'column' => $seat['column'],
                        'reserved_at' => now(),
                        'access_code' => $access_code
                    ]);
                }
                $accessCodes[] = $access_code;
            }
        });
        if($user){
            return redirect()->to('/my-reservations');

        }else{
            return redirect()->to('/public/reservations/' . $data['event_id'] . '/?accesscodes=' . implode(',', $accessCodes));

        }

    }

    public function show($event_id,Request $request)
    {

        $accessCodesParam = $request->query('accesscodes');
        $accessCodes = $accessCodesParam ? explode(',', $accessCodesParam) : [];

        $reservations = Reservation::with(["event.hall","event.show.tags"])
            ->whereIn("access_code", $accessCodes)
            ->orderBy('id','desc')
            ->get();

        return Inertia::render('user/reservations/Index',['reservations'=>$reservations]);
    }
}
