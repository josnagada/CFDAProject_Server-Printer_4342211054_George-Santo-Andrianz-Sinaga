<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CRUDController;
use App\Http\Middleware\CekRole;
use App\Http\Middleware\CekStatusAkun;
use App\Http\Controllers\ControllerFile;
use App\Http\Controllers\NotActiveAccountController;

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

Route::middleware('guest')->group(function(){
    Route::get('/', ([UserController::class,'welcomePage']))
    ->name('welcomePage');
    
    Route::get('/login', ([AuthController::class,'showLoginPage']))
    ->name('loginPage');

    Route::get('/loginRFID', ([AuthController::class,'showLoginRFIDPage']))
    ->name('loginRFIDPage');

    Route::post('/proses-login', ([AuthController::class, 'loginProcess']))
    ->name('loginProcess');

    Route::post('/login-RFID', [AuthController::class, 'loginRFID'])
    ->name('loginMahasiswa');
});

Route::middleware('auth')->group(function(){
    Route::get('/logout', ([AuthController::class, 'logout']))
    ->name('logout');

    Route::get('/akun-belum-aktif',([NotActiveAccountController::class,'notActivePage']))
    ->name('accountNotActive');

    Route::get('/tes', ([UserController::class,'tes']))
    ->name('tes');
});

Route::middleware(CekRole::class . ':admin')->group(function(){
    Route::post('/tambahAkunUser', ([CRUDController::class,'tambahUser']))
    ->name('tambahAkunUser');

    Route::post('/resetPassword', ([AuthController::class,'resetPassword']))
    ->name('resetPassword');

    Route::put('/dashboard/admin/{id_user}/edit-status', ([AuthController::class,'updateStatus']))
    ->name('editStatusPenyedia');

    Route::put('/update-harga-cetak/{id}', [CRUDController::class, 'update'])->name('updateHargaCetak');
});

Route::middleware(CekRole::class . ':karyawan')->group(function(){
    Route::put('/dashboard/karyawan/{id_user}/tambahsaldo', ([UserController::class,'tambahSaldoMahasiswa']))
    ->name('tambahSaldoMahasiswa');

    
});

Route::middleware(CekRole::class . ':pelanggan')->group(function(){
    Route::put('/dashboard/pelanggan/{id_user}/uploadFIle', ([UserController::class,'uploadFile']))
    ->name('uploadFile');

    Route::post('/convert-to-pdf-api', [ControllerFile::class, 'convertToPdfApi'])->name('convertToPdfApi');

    Route::post('/redeem-token', [UserController::class, 'redeemToken'])->name('redeem.token');
});