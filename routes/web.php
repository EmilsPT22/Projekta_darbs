<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternshipController;

Route::get('/', function () {
    return view('welcome');
});



Route::resource('internships', InternshipController::class);
Route::post('internships/{internship}/addStudent/{id}', [InternshipController::class, 'addStudent'])->name('internships.addStudent');
Route::delete('internships/{internship}/removeStudent/{id}', [InternshipController::class, 'removeStudent'])->name('internships.removeStudent');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
