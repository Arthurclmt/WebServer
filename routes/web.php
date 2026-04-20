<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppareilController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DeviceController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);


Route::get('/profil', function () {
    return view('profil');
});


Route::get('/dashboard', function () {
    return view('dashboard');
});


Route::get('/rechercheAppareil', [AppareilController::class, 'index'])
    ->middleware('auth')
    ->name('appareils.index'); // On lui donne un petit nom
Route::get('/appareil/{id}', [AppareilController::class, 'show'])->name('appareil.show');

Route::get('logout', function (){
    Auth::logout(); // déconnecte l'utilisateur
    return redirect('/login'); // redirige après déconnexion
})->name('logout');

Route::post('/logout', function () {
    Auth::logout(); // déconnecte l'utilisateur
    return redirect('/login'); // redirige après déconnexion
})->name('logout');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/profile', [AuthController::class, 'showProfile']);
Route::post('/profile', [AuthController::class, 'updateProfile']);

