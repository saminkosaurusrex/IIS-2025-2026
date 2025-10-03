<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HallController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\EventController;

// public routes (accessible for both registered and unregistered user)





Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes only for admin
Route::group(['middleware' => ['auth', 'role:admin'],],function (){

});

// routes only for cashier
Route::group(['middleware' => ['auth', 'role:cashier'],],function (){

});

// routes only for editor
Route::group(['middleware' => ['auth', 'role:editor'],],function (){
    Route::get('/halls', [HallController::class, 'show']);
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/{show_type}', [ShowController::class, 'show']);
Route::get('udalost/{id}', [EventController::class, 'show']);
