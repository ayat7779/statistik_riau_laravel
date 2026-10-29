<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\KodeUrutController;
use App\Http\Controllers\KodeUrut13Controller;
use App\Http\Controllers\KodeCatatanController;
use App\Http\Controllers\JenisApbdController;
use App\Http\Controllers\KodeLevelController;
use App\Http\Controllers\ApbdLampiran1Controller;
use App\Http\Controllers\ApbdInputController;
use App\Http\Controllers\LkpdInputController;

Route::get('/', [StatistikController::class, 'apbd']);
Route::get('/lkpd-apbd', [StatistikController::class, 'lkpdApbd'])->name('lkpd-apbd');
Route::get('/kinerja-keuangan', [StatistikController::class, 'kinerjaKeuangan']);
Route::get('/kinerja-keuangan-detail', [StatistikController::class, 'kinerjaKeuanganDetail']);

Route::resource('kode-urut', KodeUrutController::class);
Route::resource('kode-urut-13', KodeUrut13Controller::class);
Route::resource('kode-catatan', KodeCatatanController::class);
Route::resource('jenis-apbd', JenisApbdController::class);
Route::resource('level-kode', KodeLevelController::class);
Route::get('apbd-lampiran-1', [ApbdLampiran1Controller::class, 'index'])->name('apbd-lampiran-1.index');
Route::resource('input-apbd', ApbdInputController::class);
Route::resource('input-lkpd', LkpdInputController::class);
