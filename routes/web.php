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
Route::group(['middleware' => ['auth', 'notsiswa']], function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/siswa', [FirebaseController::class, 'index'])->name('siswa');
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/create-user', [UserController::class, 'create_form'])->name('user.form');
    Route::post('/create-user', [UserController::class, 'create'])->name('user.create');
    Route::get('/export-siswa', [FirebaseController::class, 'exportExcel'])->name('export.siswa');
    Route::get('/export-rekap', [HomeController::class, 'exportExcel'])->name('export.rekap');
    Route::get('/export-kehadiran', [HomeController::class, 'exportExcelKehadiran'])->name('export.kehadiran');

    Route::get('/create-siswa', [FirebaseController::class, 'create_form'])->name('siswa.form');
    Route::post('/create-siswa', [FirebaseController::class, 'create'])->name('siswa.create');
    // Route::get('/delete/{id}', [FirebaseController::class, 'delete'])->name('siswa.delete');
    Route::delete('/delete-siswa/{id}', [FirebaseController::class, 'delete'])->name('siswa.delete');
});


Route::get('/not-authorize', [HomeController::class, 'notauthorize'])->name('notauthorize');