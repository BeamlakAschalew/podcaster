<?php

use App\Http\Controllers\PodcastController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('podcasts', [PodcastController::class, 'index'])->name('podcasts.index');
    Route::get('podcasts/create', [PodcastController::class, 'create'])->name('podcasts.create');
    Route::post('podcasts', [PodcastController::class, 'store'])->name('podcasts.store');
    Route::get('podcasts/{podcast:slug}', [PodcastController::class, 'show'])->name('podcasts.show');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
