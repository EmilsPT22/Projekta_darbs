<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\DailyEntryController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\InternshipApplicationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('internships', InternshipController::class);

    Route::post('internships/{internship}/addStudent/{id}',
        [InternshipController::class, 'addStudent']
    )->name('internships.addStudent');

    Route::delete('internships/{internship}/removeStudent/{id}',
        [InternshipController::class, 'removeStudent']
    )->name('internships.removeStudent');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/password', function () {
        return view('profile.password');
        })->name('profile.password');

    // Admin only routes
    Route::prefix('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit-roles', [UserController::class, 'editRoles'])->name('admin.users.edit-roles');
        Route::post('/users/{user}/update-roles', [UserController::class, 'updateRoles'])->name('admin.users.update-roles');
        Route::post('/users/{user}/remove-role', [UserController::class, 'removeRole'])->name('admin.users.remove-role');
        Route::get('/users/{user}/edit-grade', [UserController::class, 'editGrade'])->name('admin.users.edit-grade');
        Route::post('/users/{user}/update-grade', [UserController::class, 'updateGrade'])->name('admin.users.update-grade');
        Route::get('/students-grade', [UserController::class, 'studentsForGrade'])->name('admin.students-grade');

        // Class group management
        Route::resource('classgroups', ClassGroupController::class);
        Route::post('classgroups/{classgroup}/assign-students', [ClassGroupController::class, 'assignStudents'])
            ->name('classgroups.assign-students');
    });

    // Teacher routes
    Route::prefix('teacher')->group(function () {
        Route::get('/my-classes', [ClassGroupController::class, 'myClasses'])->name('teacher.my-classes');
        Route::get('/my-students', [UserController::class, 'myStudents'])->name('teacher.my-students');
    });

    Route::prefix('internships/{internship}')->group(function () {

    Route::post('addClassGroup/{classgroup}',
        [InternshipController::class, 'addClassGroup']
    )->name('internships.addClassGroup');

    Route::get('/entries', [DailyEntryController::class, 'index'])->name('entries.index');
    Route::get('/entries/calendar/{student}', [DailyEntryController::class, 'calendar'])->name('entries.calendar');
    Route::get('/entries/create', [DailyEntryController::class, 'create'])->name('entries.create');
    Route::post('/entries', [DailyEntryController::class, 'store'])->name('entries.store');

    Route::get('/entries/{entry}/edit', [DailyEntryController::class, 'edit'])->name('entries.edit');
    Route::patch('/entries/{entry}', [DailyEntryController::class, 'update'])->name('entries.update');

    Route::get('/entries/student/{student}', [DailyEntryController::class, 'studentEntries'])
        ->name('entries.student');

    // Application routes
    Route::get('/apply', [InternshipApplicationController::class, 'create'])
        ->name('applications.create');
    Route::post('/apply', [InternshipApplicationController::class, 'store'])
        ->name('applications.store');
    Route::get('/applications', [InternshipApplicationController::class, 'index'])
        ->name('applications.index');
    Route::get('/applications/{application}', [InternshipApplicationController::class, 'show'])
        ->name('applications.show');
    Route::get('/my-application', [InternshipApplicationController::class, 'studentView'])
        ->name('applications.student-view');
    Route::post('/applications/{application}/approve', [InternshipApplicationController::class, 'approve'])
        ->name('applications.approve');
    Route::post('/applications/{application}/reject', [InternshipApplicationController::class, 'reject'])
        ->name('applications.reject');

     Route::get('/themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::get('/themes/create', [ThemeController::class, 'create'])->name('themes.create');
    Route::post('/themes', [ThemeController::class, 'store'])->name('themes.store');
    Route::get('/themes/{theme}/edit', [ThemeController::class, 'edit'])->name('themes.edit');
    Route::put('/themes/{theme}', [ThemeController::class, 'update'])->name('themes.update');
    Route::delete('/themes/{theme}', [ThemeController::class, 'destroy'])->name('themes.destroy');
});
});

require __DIR__.'/auth.php';
