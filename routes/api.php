<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('api.key')->group(function () {
    Route::post('/login-Hardware', [AuthController::class, 'loginHardware'])
    ->name('loginHardware');
    
    Route::post('/pay', [UserController::class, 'pay'])
    ->name('payment');
    
    Route::post('/downloadFile', [UserController::class, 'downloadFile'])
    ->name('downloadFile');
    
    Route::get('/harga-cetak/{tipe_file}', [UserController::class, 'getHarga']);
});