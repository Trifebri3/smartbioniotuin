<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/servos/status', [ServoController::class, 'status']);
Route::post('/servos/wifi', [ServoController::class, 'updateWifi']);
Route::post('/servos/{id}/angle', [ServoController::class, 'setAngle']);
Route::post('/servo-rules', [ServoController::class, 'saveRule']);

// Endpoint untuk menerima gambar dari ESP32
Route::post('/camera/upload', function (Request $request) {
    $image = $request->getContent();
    if ($image) {
        // Simpan langsung ke folder public agar bisa diakses browser
        file_put_contents(public_path('stream.jpg'), $image);
    }
    return response()->json(['status' => 'success']);
});
Route::post('/wifi/config', [ServoController::class, 'updateWifi']);
