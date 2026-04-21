<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppareilController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;

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

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/users/{user}/promote', [AdminController::class, 'promote'])->name('admin.promote');
    Route::post('/users/{user}/demote', [AdminController::class, 'demote'])->name('admin.demote');
    Route::post('/users/{user}/ban', [AdminController::class, 'ban'])->name('admin.ban');
    Route::post('/users/{user}/unban', [AdminController::class, 'unban'])->name('admin.unban');
});

