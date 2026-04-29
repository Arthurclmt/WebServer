<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppareilController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DeviceController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\DeviceConfigController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MembresController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DashboardController;

//Page d'accueil
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

//Routes connexion et inscription
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);


//Route profil
Route::get('/profil', function () {
    return view('profil');
});

//Route dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


//Routes appareils
Route::middleware('auth')->group(function () {
    Route::get('/rechercheAppareil',            [AppareilController::class, 'index'])        ->name('appareil.index');
    Route::get('/appareil/create',              [AppareilController::class, 'create'])       ->name('appareil.create');
    Route::post('/appareil',                    [AppareilController::class, 'store'])        ->name('appareil.store');
    Route::get('/appareil/{id}',                [AppareilController::class, 'show'])         ->name('appareil.show');
    Route::get('/appareil/{id}/edit',           [AppareilController::class, 'edit'])         ->name('appareil.edit');
    Route::put('/appareil/{id}',                [AppareilController::class, 'update'])       ->name('appareil.update');
    Route::delete('/appareil/{id}',             [AppareilController::class, 'destroy'])      ->name('appareil.destroy');
    Route::post('/appareil/{id}/toggle-status', [AppareilController::class, 'toggleStatus'])->name('appareil.toggleStatus');
    Route::post('/appareil/{id}/request-delete', [AppareilController::class, 'requestDelete'])->name('appareil.requestDelete');
    Route::get('/appareil/{id}/config', [AppareilController::class, 'editConfig'])->name('appareil.editConfig');
    Route::put('/appareil/{id}/config', [AppareilController::class, 'updateConfig'])->name('appareil.updateConfig');
    Route::get('/appareils/{id}/export', [AppareilController::class, 'exportCSV'])->name('appareil.export');
});


//Déconnexion
Route::get('logout', function (){
    Auth::logout(); // déconnecte l'utilisateur
    return redirect('/login'); // redirige après déconnexion
})->name('logout');

Route::post('/logout', function () {
    Auth::logout(); // déconnecte l'utilisateur
    return redirect('/login'); // redirige après déconnexion
})->name('logout');

//Routes membres
Route::get('/membres', [MembresController::class, 'index'])->middleware('auth')->name('membres.index');
Route::get('/membres/{user}', [MembresController::class, 'show'])->middleware('auth')->name('membres.show');

//Routes news et events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

//Routes utilisateur authentifié events/admin/news
Route::middleware(['auth'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/admin/users/{user}/email', [AdminController::class, 'updateEmail'])->name('admin.users.updateEmail');
    Route::get('/statistiques', [App\Http\Controllers\StatsController::class, 'index'])->name('stats.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
});

//Routes events
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

//Routes profils
Route::get('/profile', [AuthController::class, 'showProfile']);
Route::post('/profile', [AuthController::class, 'updateProfile']);

//Routes admin
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/users/{user}/promote', [AdminController::class, 'promote'])->name('admin.promote');
    Route::post('/users/{user}/demote', [AdminController::class, 'demote'])->name('admin.demote');
    Route::post('/users/{user}/ban', [AdminController::class, 'ban'])->name('admin.ban');
    Route::post('/users/{user}/unban', [AdminController::class, 'unban'])->name('admin.unban');

    Route::post('/allowed-emails', [AdminController::class, 'storeAllowedEmail'])->name('admin.allowed.store');
    Route::delete('/allowed-emails/{allowedMember}', [AdminController::class, 'destroyAllowedEmail'])->name('admin.allowed.destroy');
});

//Routes salles
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');



