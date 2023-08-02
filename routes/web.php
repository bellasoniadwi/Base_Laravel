<?php

use App\Http\Controllers\Auth\RegisterController;
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
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/student', [FirebaseController::class, 'index'])->name('students');
    Route::get('/rekap', [HomeController::class, 'rekap'])->name('rekap');
});
