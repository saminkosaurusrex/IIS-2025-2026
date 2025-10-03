<?php

namespace App\Http\Controllers;

use App\Models\ShowType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(){
        return Inertia::render('Home');
    }
}
