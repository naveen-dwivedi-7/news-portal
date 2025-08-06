<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthenticationController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){
    Route::get('login', [AdminAuthenticationController::class, 'login'])->name('login');
     Route::post('login', [AdminAuthenticationController::class, 'handleLogin'])->name('handle-login');
    Route::post('logout', [AdminAuthenticationController::class, 'logout'])->name('logout');

});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['admin']], function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});









// Route::prefix('admin')->as('admin.')->group(function () {
//     Route::get('login', [AdminAuthenticationController::class, 'login'])->name('login');


// });
// Route::prefix('admin')->as('admin.')->group(function () {
//     Route::get('dashboard', [DashboardController::class, 'index'])
//         ->middleware('admin')
//         ->name('dashboard');
// });

require __DIR__.'/auth.php';
