<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Course routes
    Route::resource('courses', CourseController::class);
    Route::get('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my-courses');
    Route::get('/courses/{course}/content', [CourseController::class, 'content'])->name('courses.content');

    // Quiz routes
    Route::prefix('quizzes')->group(function () {
        // Public quiz routes (for enrolled students)
        Route::get('/course/{course}', [QuizController::class, 'courseQuizzes'])->name('quizzes.course');
        Route::get('/{quiz}/take', [QuizController::class, 'start'])->name('quizzes.take');
        Route::post('/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
        Route::get('/{quiz}/results', [QuizController::class, 'results'])->name('quizzes.results');
        Route::get('/{quiz}/review', [QuizController::class, 'review'])->name('quizzes.review');
        
        // Quiz Management Routes (for instructors and admins)
        Route::middleware(['role:instructor,admin'])->group(function () {
            Route::get('/', [QuizController::class, 'index'])->name('quizzes.index');
            Route::get('/create', [QuizController::class, 'create'])->name('quizzes.create');
            Route::post('/', [QuizController::class, 'store'])->name('quizzes.store');
            Route::get('/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
            Route::put('/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
            Route::delete('/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
        });
    });

    // Notes routes
    Route::prefix('notes')->group(function () {
        Route::get('/course/{course}', [NoteController::class, 'courseNotes'])->name('notes.course');
        Route::post('/{note}/pin', [NoteController::class, 'pin'])->name('notes.pin');
        Route::post('/{note}/unpin', [NoteController::class, 'unpin'])->name('notes.unpin');
    });
    Route::resource('notes', NoteController::class);

    // Enrollment routes
    Route::post('/enroll/{course}', [EnrollmentController::class, 'enroll'])->name('enroll');
    Route::post('/unenroll/{course}', [EnrollmentController::class, 'unenroll'])->name('unenroll');
    Route::patch('/enrollments/{enrollment}/progress', [EnrollmentController::class, 'updateProgress'])->name('enrollments.progress');
});

require __DIR__.'/auth.php';
