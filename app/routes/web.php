<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/simulate', fn () => Inertia::render('SimulateMatch'))->name('simulate');
    Route::get('/matches', fn () => Inertia::render('MatchHistory'))->name('matches');
    Route::get('/rankings', fn () => Inertia::render('Rankings'))->name('rankings');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
