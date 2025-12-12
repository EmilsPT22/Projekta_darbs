<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\DailyEntryController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::resource('internships', InternshipController::class);

Route::post('internships/{internship}/addStudent/{id}', 
    [InternshipController::class, 'addStudent']
)->name('internships.addStudent');

Route::delete('internships/{internship}/removeStudent/{id}', 
    [InternshipController::class, 'removeStudent']
)->name('internships.removeStudent');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('internships/{internship}')->group(function () {

    Route::get('/entries', [DailyEntryController::class, 'index'])->name('entries.index');
    Route::get('/entries/create', [DailyEntryController::class, 'create'])->name('entries.create');
    Route::post('/entries', [DailyEntryController::class, 'store'])->name('entries.store');

    Route::get('/entries/student/{student}', [DailyEntryController::class, 'studentEntries'])
        ->name('entries.student');

     Route::get('/themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::get('/themes/create', [ThemeController::class, 'create'])->name('themes.create');
    Route::post('/themes', [ThemeController::class, 'store'])->name('themes.store');
    Route::get('/themes/{theme}/edit', [ThemeController::class, 'edit'])->name('themes.edit');
    Route::put('/themes/{theme}', [ThemeController::class, 'update'])->name('themes.update');
    Route::delete('/themes/{theme}', [ThemeController::class, 'destroy'])->name('themes.destroy');
});
});

require __DIR__.'/auth.php';
