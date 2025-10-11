<?php

use App\Http\Controllers\Public\RegisterAssignController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PerformerController;
use App\Http\Controllers\ShowTypesController;
use App\Http\Controllers\ReservationController;

// public routes (accessible for registered and unregistered user)

Route::get('dashboard', [ReservationController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('dashboard/{reservation}/show', [ReservationController::class, 'show'])->middleware(['auth', 'verified']);
// routes only for admin
Route::group(['middleware' => ['auth', 'role:admin'],],function (){
    // routes for users
    Route::resource('users', UserController::class)->except(['show']);
});

// routes only for cashier
Route::group(['middleware' => ['auth', 'role:cashier'],],function (){

});

// routes only for editor
Route::group(['middleware' => ['auth', 'role:editor'],],function (){

    // routes for halls
    Route::resource('halls', HallController::class)->except(['show']); // done
    // routes for show types
    Route::resource('show_types', ShowTypesController::class)->except(['show']); // done
    // routes for tags
    Route::resource('tags', TagController::class)->except(['show']); // done
    // routes for performers
    Route::resource('performers', PerformerController::class)->except(['show']); // done
    // routes for shows
    Route::resource('shows', ShowController::class)->except(['show']); // done
    // routes for events
    Route::resource('events', EventController::class)->except(['show']);
    // routes for reservations
    Route::resource('reservations', ReservationController::class)->except(['show']);

});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
Route::get('/',function () {
    return redirect()->to("/Filmy");
})->name('home');


Route::get('/', [ShowController::class, 'home'])->name('home');
Route::get('show/{id}', [ShowController::class, 'showSpec'])->name('show.spec');


Route::get('/{show_type}', [ShowController::class, 'show']);
Route::get('udalost/{id}', [EventController::class, 'show']);
Route::post('/public/reservations', [\App\Http\Controllers\Public\ReservationController::class, 'store']);
Route::get('/public/reservations/{event_id}', [\App\Http\Controllers\Public\ReservationController::class, 'show']);

