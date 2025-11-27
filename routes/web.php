<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InternshipController;

Route::get('/', function () {
    return view('home'); // Homepage for everyone
});

// Internships routes (Accessible without login)
Route::resource('internships', InternshipController::class);
Route::post('internships/{internship}/addStudent/{id}', [InternshipController::class, 'addStudent'])->name('internships.addStudent');
Route::delete('internships/{internship}/removeStudent/{id}', [InternshipController::class, 'removeStudent'])->name('internships.removeStudent');

// Authenticated routes (Dashboard and Profile)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Dashboard page for authenticated users
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
