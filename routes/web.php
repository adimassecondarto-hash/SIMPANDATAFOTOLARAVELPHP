<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataSemesterController;
use App\Http\Controllers\FotoRekapDanUjianController;
// Halaman login
Route::get('/datadims/login', [LoginController::class, 'lihatLogin'])->name('login');
Route::post('/datadims/login', [LoginController::class, 'Login'])->name('login.post');



Route::get('/datadims/dasboard', [DataSemesterController::class, 'dasboard'])->name('dasboard');

Route::post('/datadims/logout', [LoginController::class, 'Logout'])->name('logout');

Route::get('/datadims/dasboarddua',[FotoRekapDanUjianController::class, 'dasboarddua'])->name('dasboarddua');

Route::post('/datadims/kartukhs/store', [DataSemesterController::class, 'kartukhs'])->name('kartukhs.store');
Route::post('/datadims/rekapstudidanujian/store',[FotoRekapDanUjianController::class, 'rekapstudidanujian'])->name('rekapstudidanujian.store');

