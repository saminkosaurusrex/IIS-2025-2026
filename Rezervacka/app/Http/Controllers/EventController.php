<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function show($id){



        $event = Event::with(['hall', 'show', 'reservations'])->where("id", $id)->FirstOrFail();

        return Inertia::render('Event',[
            'event' => $event,
            'reservedSeats' => $event->reservations->map(fn($r) => ['row' => $r->row, 'column' => $r->column])
        ]);

    }
}
