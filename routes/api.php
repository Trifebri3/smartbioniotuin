<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\ServoController;

Route::get('/servo/status', [ServoController::class, 'status']);
Route::post('/servo/{id}/set', [ServoController::class, 'setAngle']);
Route::post('/servo/rules', [ServoController::class, 'saveRule']);
