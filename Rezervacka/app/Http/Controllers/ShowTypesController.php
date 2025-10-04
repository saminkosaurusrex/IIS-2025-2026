<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ShowType;
use Illuminate\Http\Request;

class ShowTypesController extends Controller
{
    public function index(){
        $show_types = ShowType::all();
        return Inertia::render('admin/show_types/Index', compact('show_types'));
    }

    public function create(){
        return Inertia::render('admin/show_types/Create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:show_types,name',
        ]);

        ShowType::create($request->only('name'));

        return redirect('/show_types')->with('success', 'Typ predstavenia bol úspešne vytvorený.');
    }

    public function edit(ShowType $show_type){
        return Inertia::render('admin/show_types/Edit', compact('show_type'));
    }

    public function update(Request $request, ShowType $show_type){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $show_type->update($request->only('name'));

        return redirect('/show_types')->with('success', 'Typ predstavenia bol úspešne upravený.');
    }

    public function destroy(ShowType $show_type){
        $show_type->delete();
        return redirect('/show_types')->with('success', 'Typ predstavenia bol úspešne vymazaný.');
    }
}
