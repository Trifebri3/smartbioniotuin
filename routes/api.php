<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\ServoController;
use App\Models\SensorLog;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ROUTE PALING MUDAH UNTUK ESP32 CAMERA
Route::post('/upload-frame', function (Request $request) {
    // Menerima raw binary image dari ESP32
    $imageBytes = $request->getContent();
    if (!empty($imageBytes)) {
        File::put(public_path('camera.jpg'), $imageBytes);
        return response()->json(['status' => 'ok']);
    }
    return response()->json(['status' => 'error'], 400);
});

Route::get('/servos/status', [ServoController::class, 'status']);
Route::get('/servo/status', [ServoController::class, 'status']); // Alias untuk ESP32

Route::post('/servos/wifi', [ServoController::class, 'updateWifi']);

Route::post('/servos/{id}/angle', [ServoController::class, 'setAngle']);
Route::post('/servo/{id}/set', [ServoController::class, 'setAngle']); // Alias untuk Web UI

Route::post('/servo-rules', [ServoController::class, 'saveRule']);
Route::post('/servo/rules', [ServoController::class, 'saveRule']); // Alias untuk Web UI

// Endpoint untuk menerima gambar dari ESP32
Route::post('/camera/upload', function (Request $request) {
    $image = $request->getContent();
    if ($image) {
        // Simpan langsung ke folder public agar bisa diakses browser
        file_put_contents(public_path('camera.jpg'), $image);
    }
    return response()->json(['status' => 'success']);
});

// Endpoint untuk menjalankan AI Scan (Gemini Vision)
Route::post('/ai/scan', [\App\Http\Controllers\Api\AutoSortController::class, 'scan']);
Route::post('/wifi/config', [ServoController::class, 'updateWifi']);

// Endpoint untuk update data sensor dari ESP32
Route::post('/sensors/update', function (Request $request) {
    $data = $request->validate([
        'sensor1' => 'nullable|numeric',
        'sensor2' => 'nullable|numeric',
        'sensor3' => 'nullable|numeric',
        'sensor4' => 'nullable|numeric',
    ]);
    
    // Simpan data di Cache selama 10 menit untuk realtime dashboard
    Cache::put('sensor_data', $data, now()->addMinutes(10));
    
    // Logika Simpan Riwayat ke DB (Maksimal per 10 menit)
    $lastLog = SensorLog::latest()->first();
    if (!$lastLog || $lastLog->created_at->diffInMinutes(now()) >= 10) {
        SensorLog::create([
            'sensor1' => $data['sensor1'] ?? null,
            'sensor2' => $data['sensor2'] ?? null,
            'sensor3' => $data['sensor3'] ?? null,
            'sensor4' => $data['sensor4'] ?? null,
        ]);
    }
    
    return response()->json(['status' => 'success', 'message' => 'Data saved']);
});

// Endpoint untuk mengambil data sensor terbaru
Route::get('/sensors/latest', function () {
    $data = Cache::get('sensor_data', [
        'sensor1' => -1,
        'sensor2' => -1,
        'sensor3' => -1,
        'sensor4' => -1,
    ]);
    
    return response()->json($data);
});
