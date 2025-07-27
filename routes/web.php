<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EsewaController;
Route::get('/esewa', [EsewaController::class, 'initiatePayment'])->name('esewa.initiate');
Route::get('/esewa/success', [EsewaController::class, 'paymentSuccess'])->name('esewa.success');
Route::get('/esewa/failure', [EsewaController::class, 'paymentFailure'])->name('esewa.failure');