<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('trades', TradeController::class);
