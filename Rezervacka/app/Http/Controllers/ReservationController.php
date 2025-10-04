<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(){
        $reservations = Reservation::with(['user', 'event.show', 'event.hall'])->latest()->take(10)->get();
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
}
