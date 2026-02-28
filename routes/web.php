<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Homepage;
use App\Livewire\Program;
use App\Livewire\Venues;
use App\Livewire\VenueDetail;
use App\Livewire\PageDetail;
use App\Livewire\UserProfile;
use App\Http\Controllers\AuthController;

Route::get('/', Homepage::class);
Route::get('/program', Program::class);
Route::get('/mista', Venues::class);
Route::get('/misto/{venue}', VenueDetail::class)->name('venue.detail');
Route::get('/stranka/{page:slug}', PageDetail::class);

Route::middleware('auth')->group(function () {
    Route::get('/profil', UserProfile::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerView']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider']);
    Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
});
