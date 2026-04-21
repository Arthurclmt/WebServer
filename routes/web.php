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


Route::middleware('auth')->group(function () {
    Route::get('/rechercheAppareil',            [AppareilController::class, 'index'])        ->name('appareils.index');
    Route::get('/appareil/create',              [AppareilController::class, 'create'])       ->name('appareil.create');
    Route::post('/appareil',                    [AppareilController::class, 'store'])        ->name('appareil.store');
    Route::get('/appareil/{id}',                [AppareilController::class, 'show'])         ->name('appareil.show');
    Route::get('/appareil/{id}/edit',           [AppareilController::class, 'edit'])         ->name('appareil.edit');
    Route::put('/appareil/{id}',                [AppareilController::class, 'update'])       ->name('appareil.update');
    Route::delete('/appareil/{id}',             [AppareilController::class, 'destroy'])      ->name('appareil.destroy');
    Route::post('/appareil/{id}/toggle-status', [AppareilController::class, 'toggleStatus'])->name('appareil.toggleStatus');
});



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

