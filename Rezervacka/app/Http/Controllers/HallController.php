<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Inertia\Inertia;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function show(){
        $halls = Hall::oldest()->get();
        return Inertia::render('admin/halls/Index', compact('halls'));
    }
}
