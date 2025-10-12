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

        $event = Event::with(['hall', 'show.tags','reservations' => function ($query) {
            $query->select('id', 'event_id', 'row', 'user_id','column','reserved_at','confirmed_at','paid_at');
        }])->where("id", $id)->FirstOrFail();


        $user = auth()->user();
        $ownReservedSeats = [];
        $ownTakenSeats = [];
        if($user){
            $ownReservedSeats = $event->reservations
                ->filter(fn($r) => $r->reserved_at !== null && $r->confirmed_at == null
                    && $r->paid_at == null && $r->canceled_at == null && $r->user_id == $user->id)
                ->map(fn($r) => ['row' => $r->row, 'column' => $r->column])
                ->values()
                ->toArray();

            $ownTakenSeats = $event->reservations
                ->filter(fn($r) => $r->confirmed_at !== null && $r->reserved_at !== null && $r->canceled_at == null
                    && $r->user_id == $user->id)
                ->map(fn($r) => ['row' => $r->row, 'column' => $r->column])
                ->values()
                ->toArray();
        }


        $reservedSeats = $event->reservations
            ->filter(fn($r) => $r->reserved_at !== null && $r->confirmed_at == null
                && $r->paid_at == null && $r->canceled_at == null)
            ->map(fn($r) => ['row' => $r->row, 'column' => $r->column])
            ->values()
            ->toArray();

        $takenSeats = $event->reservations
            ->filter(fn($r) => $r->confirmed_at !== null && $r->reserved_at !== null && $r->canceled_at == null)
            ->map(fn($r) => ['row' => $r->row, 'column' => $r->column])
            ->values()
            ->toArray();

        return Inertia::render('Event',[
            'event' => $event,
            'reservedSeats' => $reservedSeats,
            'takenSeats' => $takenSeats,
            'ownReservedSeats' => $ownReservedSeats,
            'ownTakenSeats' => $ownTakenSeats
        ]);

    }

    public function showApi($id){

        $event = Event::with(['hall', 'show.tags','reservations' => function ($query) {
            $query->select('id', 'event_id', 'row', 'user_id','column','reserved_at','confirmed_at','paid_at');
        }])->where("id", $id)->FirstOrFail();

        $reservedSeats = $event->reservations
            ->filter(fn($r) => $r->reserved_at !== null && $r->confirmed_at == null
                && $r->paid_at == null && $r->canceled_at == null)
            ->map(fn($r) => ['row' => $r->row, 'column' => $r->column])
            ->values()
            ->toArray();

        $takenSeats = $event->reservations
            ->filter(fn($r) => $r->confirmed_at !== null && $r->reserved_at !== null && $r->canceled_at == null)
            ->map(fn($r) => ['row' => $r->row, 'column' => $r->column])
            ->values()
            ->toArray();

        return response()->json([
            'event' => $event,
            'reservedSeats' => $reservedSeats,
            'takenSeats' => $takenSeats,
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
