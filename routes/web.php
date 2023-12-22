<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\MixifyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaybackController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::resource('/mixify', MixifyController::class)->only(['index', 'store'])
    ->middleware(['auth', 'verified']);
Route::get('/auth/spotify', 'App\Http\Controllers\Auth\LoginController@redirectToSpotify')->middleware(['auth', 'verified']);
Route::get('/auth/spotify/callback', 'App\Http\Controllers\Auth\LoginController@handleSpotifyCallback')->middleware(['auth', 'verified']);
Route::get('/playback/', [PlaybackController::class, 'index'])->middleware(['auth', 'verified'])->name('playback.index');
Route::post('/playback/play', [PlaybackController::class, 'play'])->middleware(['auth', 'verified'])->name('playback.play');
Route::post('/playback/pause', [PlaybackController::class, 'pause'])->middleware(['auth', 'verified'])->name('playback.pause');
Route::post('/playback/previous', [PlaybackController::class, 'previous'])->middleware(['auth', 'verified'])->name('playback.previous');
Route::post('/playback/next', [PlaybackController::class, 'next'])->middleware(['auth', 'verified'])->name('playback.next');
Route::delete('/playback/destroy', [PlaybackController::class, 'destroy'])->middleware(['auth', 'verified'])->name('playback.destroy');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
