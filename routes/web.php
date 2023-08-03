<?php

use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
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
Route::group(['middleware' => ['auth', 'notpeserta']], function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/student', [FirebaseController::class, 'index'])->name('students');
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/create-form', [UserController::class, 'create_form'])->name('user.form');
    Route::post('/create-form', [UserController::class, 'create'])->name('user.create');
    // Route::get('/ceksaya', [HomeController::class, 'ceksaya'])->name('ceksaya');
    Route::get('/export-students', [FirebaseController::class, 'exportExcel'])->name('export.students');
    Route::get('/export-rekap', [HomeController::class, 'exportExcel'])->name('export.rekap');
    Route::get('/export-kehadiran', [HomeController::class, 'exportExcelKehadiran'])->name('export.kehadiran');
});


Route::get('/not-authorize', [HomeController::class, 'notauthorize'])->name('notauthorize');