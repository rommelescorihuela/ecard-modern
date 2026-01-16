<?php

use App\Http\Controllers\VCardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/vcards/{slug}', [VCardController::class, 'show']);