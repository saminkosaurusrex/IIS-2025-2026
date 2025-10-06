<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\Show;
use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show($id){



        $event = Event::with(['hall', 'show', 'reservations'])->where("id", $id)->FirstOrFail();

        return Inertia::render('Event',[
            'event' => $event,
            'reservedSeats' => $event->reservations->map(fn($r) => ['row' => $r->row, 'column' => $r->column])
        ]);

    }

    public function index(){
        $events = Event::with(['hall', 'show'])->get();
        $events->map(function ($event){
            $event->hallName = $event->hall->name;
            $event->showName = $event->show->name;
            return $event;
        });
        return Inertia::render('admin/events/Index',[
            'events' => $events
        ]);
    }

    public function create(){
        $events = Event::all();
        $halls = Hall::all();
        $shows = Show::all();
        return Inertia::render('admin/events/Create', compact(['events','halls', 'shows']));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'hall' => 'required|exists:halls,id',
            'show' => 'required|exists:shows,id',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'price' => 'required|numeric'
        ]);

        $starting_at = $validated['date']." ".$validated['start_time'];
        $ending_at = $validated['date']." ".$validated['end_time'];

        $event = Event::create([
            'hall_id' => $validated['hall'],
            'show_id' => $validated['show'],
            'price' => $validated['price'],
            'starting_at' => $starting_at,
            'ending_at' => $ending_at
        ]);

        $event->hall()->associate($validated['hall']);
        $event->show()->associate($validated['show']);

        return redirect('/events')->with('success', 'Kultúrna udalosť. bola úspešne vytvorená.');

    }

    public function edit(Event $event){
        $event->load('hall', 'show');
        $events = Event::all();
        $shows = Show::all();
        $halls = Hall::all();
        return Inertia::render('admin/events/Edit', compact(['event', 'shows', 'halls', 'events']));
    }

    public function update(Request $request, Event $event){
        $validated = $request->validate([
            'hall' => 'required|exists:halls,id',
            'show' => 'required|exists:shows,id',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'price' => 'required|numeric'
        ]);

        $starting_at = $validated['date']." ".$validated['start_time'];
        $ending_at = $validated['date']." ".$validated['end_time'];

        $event->update([
            'hall_id' => $validated['hall'],
            'show_id' => $validated['show'],
            'price' => $validated['price'],
            'starting_at' => $starting_at,
            'ending_at' => $ending_at
        ]);

        $event->hall()->associate($validated['hall']);
        $event->show()->associate($validated['show']);

        return redirect('/events')->with('success', 'Kultúrna udalosť bola úspešne upravené.');
    }

    public function destroy(event $event){
        $event->delete();
        return redirect('/events')->with('success', 'Kultúrna udalosť bola úspešne zmazaná.');
    }
}
