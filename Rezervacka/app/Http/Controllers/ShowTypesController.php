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
}
