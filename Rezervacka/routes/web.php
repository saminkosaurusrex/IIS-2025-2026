<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// public routes (accessible for both registered and unregistered user)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/{show_type}', [ShowController::class, 'show']);
Route::get('udalost/{id}', [EventController::class, 'show']);





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

});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
