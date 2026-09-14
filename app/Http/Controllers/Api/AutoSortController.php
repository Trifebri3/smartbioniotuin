<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servo;
use App\Models\DetectionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class AutoSortController extends Controller
{
    /**
     * Tampilkan halaman Auto Sort.
     */
    public function index()
    {
        return view('auto.index');
    }

    /**
     * Jalankan scan gambar terbaru ke Gemini API
     */
    public function scan(Request $request)
    {
        $imagePath = public_path('camera.jpg');
        
        if (!file_exists($imagePath)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gambar kamera belum tersedia. Harap nyalakan ESP32 Camera terlebih dahulu.'
            ], 404);
        }

        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'GEMINI_API_KEY belum dikonfigurasi di file .env server.'
            ], 500);
        }

        // Ambil gambar dan encode ke base64
        $imageData = base64_encode(file_get_contents($imagePath));

        // Prompt super ketat untuk Gemini agar hanya membalas dengan 1 kata
        $prompt = "Tugasmu adalah menganalisis gambar ini yang diambil dari tempat sampah pintar. " . 
                  "Tentukan jenis objek paling menonjol (sampah) dalam gambar ini. " .
                  "Kamu HANYA BOLEH membalas dengan SATU KATA dari daftar berikut secara mutlak: " .
                  "ORGANIK, PLASTIK, KERTAS, LOGAM, atau TIDAK_DIKETAHUI. " .
                  "Jangan memberikan penjelasan apapun, cukup satu kata saja. " .
                  "Misal jika kamu melihat daun/sisa makanan, jawab ORGANIK. Jika melihat botol, jawab PLASTIK. Jika kosong, jawab TIDAK_DIKETAHUI.";

        // Hit Gemini API (Vision model)
        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => 'image/jpeg',
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1, // Suhu rendah agar akurat dan tidak kreatif
                    'maxOutputTokens' => 10,
                ]
            ]);

            if ($response->successful()) {
                $resultData = $response->json();
                $aiText = strtoupper($resultData['candidates'][0]['content']['parts'][0]['text'] ?? 'TIDAK_DIKETAHUI');
                
                // Menerjemahkan jawaban Gemini ke pergerakan Servo dengan pencarian kata kunci cerdas
                $result = $this->executeServoMovement($aiText);

                // Simpan gambar dan log jika terdeteksi sampah
                if ($result['category'] !== 'TIDAK_DIKETAHUI') {
                    $timestamp = now()->format('Y-m-d_H-i-s');
                    $newFileName = "detections/{$timestamp}.jpg";
                    
                    // Buat folder detections jika belum ada
                    if (!File::exists(public_path('detections'))) {
                        File::makeDirectory(public_path('detections'), 0755, true);
                    }
                    
                    File::copy($imagePath, public_path($newFileName));
                    
                    DetectionLog::create([
                        'category' => $result['category'],
                        'image_path' => $newFileName,
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'detection' => $aiText, // Tampilkan Teks ASLI dari Gemini agar kita tahu dia jawab apa
                    'action' => $result['message'],
                    'servo1' => $result['servo1'],
                    'servo2' => $result['servo2']
                ]);

            } else {
                $errorBody = $response->body();
                Log::error('Gemini API Error: ' . $errorBody);
                return response()->json([
                    'status' => 'error',
                    'message' => 'API Error: ' . $errorBody
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Exception in AutoSort: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat memproses AI.'
            ], 500);
        }
    }

    /**
     * Gerakkan servo berdasarkan jenis sampah yang terdeteksi secara pintar.
     */
    private function executeServoMovement($text)
    {
        $servo1Angle = 90; // Default Netral
        $servo2Angle = 90; // Default Netral
        $message = "Sistem standby menunggu objek yang jelas.";
        $detectedCategory = 'TIDAK_DIKETAHUI';

        // Deteksi Cerdas (Mengenali berbagai macam kata)
        if (str_contains($text, 'KERTAS') || str_contains($text, 'PAPER') || str_contains($text, 'KARDUS')) {
            $detectedCategory = 'KERTAS';
            $servo1Angle = 90;
            $servo2Angle = 50;
            $message = "Memilah KERTAS ke depan.";
        } 
        elseif (str_contains($text, 'PLASTIK') || str_contains($text, 'PLASTIC') || str_contains($text, 'BOTOL') || str_contains($text, 'PL')) {
            $detectedCategory = 'PLASTIK';
            $servo1Angle = 180;
            $servo2Angle = 50;
            $message = "Memilah PLASTIK ke kiri.";
        } 
        elseif (str_contains($text, 'ORGANIK') || str_contains($text, 'ORGANIC') || str_contains($text, 'DAUN') || str_contains($text, 'MAKANAN')) {
            $detectedCategory = 'ORGANIK';
            $servo1Angle = 0;
            $servo2Angle = 50;
            $message = "Memilah ORGANIK ke kanan.";
        } 
        elseif (str_contains($text, 'LOGAM') || str_contains($text, 'METAL') || str_contains($text, 'KALENG') || str_contains($text, 'Besi')) {
            $detectedCategory = 'LOGAM';
            $servo1Angle = 90;
            $servo2Angle = 180;
            $message = "Memilah LOGAM ke belakang.";
        }

        // Jika terdeteksi objek nyata, eksekusi servo
        if ($detectedCategory !== 'TIDAK_DIKETAHUI') {
            $this->saveServoAngle(1, $servo1Angle);
            $this->saveServoAngle(2, $servo2Angle);
        }

        return [
            'message' => $message,
            'category' => $detectedCategory,
            'servo1' => $servo1Angle,
            'servo2' => $servo2Angle
        ];
    }

    /**
     * Simpan sudut servo ke database
     */
    private function saveServoAngle($id, $angle)
    {
        $servo = Servo::find($id) ?? new Servo();
        if (!$servo->exists) {
            $servo->id = $id;
            $servo->name = "Servo {$id}";
            $servo->pin = $id == 1 ? 26 : 27;
        }
        $servo->current_angle = $angle;
        $servo->save();
    }
}
