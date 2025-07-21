<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;

Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::post('/', [CustomerController::class, 'store']);
    Route::put('{customer}', [CustomerController::class, 'update']);

    Route::get('{customer}/contacts', [ContactController::class, 'index']);
    Route::post('{customer}/contacts', [ContactController::class, 'store']);
    Route::delete('{customer}', [CustomerController::class, 'destroy']);
});

Route::put('contacts/{contact}', [ContactController::class, 'update']);
