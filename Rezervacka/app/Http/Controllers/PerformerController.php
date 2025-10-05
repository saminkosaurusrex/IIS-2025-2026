<?php

namespace App\Http\Controllers;

use App\Models\Performer;

class PerformerController extends CrudController
{
    protected $modelClass = Performer::class;
    protected $viewPath = 'performers';
    protected $returnMessage = 'Účinkujúci';
    
}

