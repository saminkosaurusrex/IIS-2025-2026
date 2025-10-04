<?php

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

// public routes (accessible for both registered and unregistered user)





Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes only for admin
Route::group(['middleware' => ['auth', 'role:admin'],],function (){
    Route::get('/users', [UserController::class, 'index']); // done
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}/edit', [UserController::class, 'edit']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});

// routes only for cashier
Route::group(['middleware' => ['auth', 'role:cashier'],],function (){

});

// routes only for editor
Route::group(['middleware' => ['auth', 'role:editor'],],function (){
    Route::get('/halls', [HallController::class, 'index']); // done
    Route::get('/shows', [ShowController::class, 'index']);
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/show_types', [ShowTypesController::class, 'index']); // done
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/performers', [PerformerController::class, 'index']);
    Route::get('/reservations', [ReservationController::class, 'index']);

    Route::get('/halls/create', [HallController::class, 'create']);
    Route::post('/halls', [HallController::class, 'store']);
    Route::get('/halls/{hall}/edit', [HallController::class, 'edit']);
    Route::put('/halls/{hall}', [HallController::class, 'update']);
    Route::delete('/halls/{hall}', [HallController::class, 'destroy']);

    Route::get('/show_types/create', [ShowTypesController::class, 'create']);
    Route::post('/show_types', [ShowTypesController::class, 'store']);
    Route::get('/show_types/{show_type}/edit', [ShowTypesController::class, 'edit']);
    Route::put('/show_types/{show_type}', [ShowTypesController::class, 'update']);
    Route::delete('/show_types/{show_type}', [ShowTypesController::class, 'destroy']);


});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/{show_type}', [ShowController::class, 'show']);
Route::get('udalost/{id}', [EventController::class, 'show']);
