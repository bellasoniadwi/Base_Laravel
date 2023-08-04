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
    Route::get('/create-user', [UserController::class, 'create_form'])->name('user.form');
    Route::post('/create-user', [UserController::class, 'create'])->name('user.create');
    Route::get('/export-students', [FirebaseController::class, 'exportExcel'])->name('export.students');
    Route::get('/export-rekap', [HomeController::class, 'exportExcel'])->name('export.rekap');
    Route::get('/export-kehadiran', [HomeController::class, 'exportExcelKehadiran'])->name('export.kehadiran');

    Route::get('/create-student', [FirebaseController::class, 'create_form'])->name('student.form');
    Route::post('/create-student', [FirebaseController::class, 'create'])->name('student.create');
});


Route::get('/not-authorize', [HomeController::class, 'notauthorize'])->name('notauthorize');