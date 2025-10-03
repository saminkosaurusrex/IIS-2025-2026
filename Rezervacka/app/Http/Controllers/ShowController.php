<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowController extends Controller
{
    public function show($show_name){
        $shows = Show::with(['show_type', 'events.hall'])
            ->whereHas('show_type', function ($query) use ($show_name) {
                $query->where('name', $show_name);
            })
            ->get();

        if ($shows->isEmpty()) {
            abort(404, 'Žiadne predstavenia podľa typu neboli nájdené');
        }

        return Inertia::render('ShowType',['shows' => $shows]);
    }
}
