<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TagController extends CrudController
{
    protected $modelClass = Tag::class;
    protected $viewPath = 'tags';
    protected $returnMessage = 'Žáner';
    
}
