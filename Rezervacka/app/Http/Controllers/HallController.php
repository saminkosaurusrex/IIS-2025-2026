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
