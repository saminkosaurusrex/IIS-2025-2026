<?php

namespace App\Http\Controllers;

use App\Models\ShowType;
class ShowTypesController extends CrudController
{
    protected $modelClass = ShowType::class;
    protected $viewPath = 'show_types';
    protected $returnMessage = 'Typ predstavenia';
}
