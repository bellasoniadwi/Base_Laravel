<?php

use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Auth::routes();

Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/tables', [HomeController::class, 'tables'])->name('tables');
Route::get('/signin', [HomeController::class, 'signin'])->name('signin');
// Route::get('/welcome', [FirebaseController::class, 'index'])->name('index');
// Route::get('/welcome/show', [FirebaseController::class, 'show'])->name('show');
// // Route::get('/welcome', [FirebaseController::class, 'store'])->name('store');

// Route::post('/student', [FirebaseController::class, 'create']);
Route::get('/student', [FirebaseController::class, 'index'])->name('students');
// Route::put('/student', [FirebaseController::class, 'edit']);
// Route::delete('/student', [FirebaseController::class, 'delete']);
