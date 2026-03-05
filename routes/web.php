<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DestinationController as AdminDestinationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Home (publik)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk destinasi (publik)
Route::prefix('destinasi')->name('destinations.')->group(function () {
    Route::get('/', [DestinationController::class, 'index'])->name('index');
    Route::get('/search', [DestinationController::class, 'search'])->name('search');
    Route::get('/{slug}', [DestinationController::class, 'show'])->name('show');
});

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    // Bookmark (sementara redirect)
    Route::get('/bookmarks', function () {
        return redirect('/');
    })->name('bookmarks.index');

// Route untuk rating & komentar (harus login)
Route::middleware('auth')->group(function () {
    // Rating
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::get('/ratings/user', [RatingController::class, 'getUserRating'])->name('ratings.user');
    
    // Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
});
    
    // Profile (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('destinations', DestinationController::class);
});

// Route dashboard Breeze (optional, bisa diarahkan ke home)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes (login, register, dll)
require __DIR__.'/auth.php';