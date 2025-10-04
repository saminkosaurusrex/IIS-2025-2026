<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Performer;
use Illuminate\Http\Request;

class PerformerController extends Controller
{
    public function index(){
        $performers = Performer::all();
        return Inertia::render('admin/performers/Index', compact('performers'));
    }
}

