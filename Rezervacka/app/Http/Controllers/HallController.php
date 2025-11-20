<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index(){
        $halls = Hall::oldest()->get();
        return Inertia::render('admin/halls/Index', compact('halls'));
    }

    public function create(){
        return Inertia::render('admin/halls/Create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'required|string',
            'rows' => 'required|integer|min:1',
            'columns' => 'required|integer|min:1',
        ]);

        Hall::create([
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
            'rows' => $request->rows,
            'columns' => $request->columns,
        ]);

        return redirect('/halls')->with('success', 'Sála bola úspešne vytvorená.');
    }

    public function edit(Hall $hall){
        return Inertia::render('admin/halls/Edit', compact('hall'));
    }

    public function update(Request $request, Hall $hall){
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'required|string',
            'rows' => 'required|integer|min:1',
            'columns' => 'required|integer|min:1',
        ]);

        $errors = [];
        if ($request->rows < $hall->rows){

            $hasRowReservations = Hall::whereId($hall->id)->whereHas('events.reservations', function ($query) use ($hall, $request) {
                $query->where(function($q) use ($hall, $request) {
                    $q->where('row', '>', $request->rows);
                });
            })->exists();

            if($hasRowReservations){
                $errors['rows'] = 'Niektoré existujúce rezervácie sú v radoch nad novým limitom.';

            }

        }
        if($request->columns < $hall->columns) {
            $hasColReservations = Hall::whereId($hall->id)->whereHas('events.reservations', function ($query) use ($hall, $request) {
                $query->where(function($q) use ($hall, $request) {
                    $q->where('column', '>', $request->columns);
                });

            })->exists();


            if($hasColReservations){
                $errors['columns'] = 'Niektoré existujúce rezervácie sú v stĺpcoch nad novým limitom.';
            }

        }

        if(!empty($errors)){
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        $hall->update([
            'name' => $request->name,
            'address' => $request->address,
            'description' => $request->description,
            'rows' => $request->rows,
            'columns' => $request->columns,
        ]);



        return redirect('/halls')->with('success', 'Sála bola úspešne upravená.');
    }

    public function destroy(Hall $hall){
        $hall->delete();
        return redirect('/halls')->with('success', 'Sála bola úspešne zmazaná.');
    }
}
