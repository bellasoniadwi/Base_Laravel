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
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/student', [FirebaseController::class, 'index'])->name('students');
});
//coba firebase authentication
// Route::get('/', function () {
//     return view('auth.login');
// });
// Route::get('/tables', [HomeController::class, 'tables'])->name('tables');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');

// Route::get('/home/customer', [App\Http\Controllers\HomeController::class, 'customer'])->middleware('user','fireauth');

// Route::get('/email/verify', [App\Http\Controllers\Auth\ResetController::class, 'verify_email'])->name('verify')->middleware('fireauth');

// Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');

// Route::resource('/home/profile', App\Http\Controllers\Auth\ProfileController::class)->middleware('user','fireauth');

// Route::resource('/password/reset', App\Http\Controllers\Auth\ResetController::class);

// Route::resource('/img', App\Http\Controllers\ImageController::class);
