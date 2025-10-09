<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(){
        $reservations = Reservation::with(['user', 'event.show', 'event.hall'])->orderBy('reserved_at', 'desc')->take(10)->get();
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
}
