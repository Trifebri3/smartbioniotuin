<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key={$apiKey}", [
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
                $aiText = trim(strtoupper($resultData['candidates'][0]['content']['parts'][0]['text'] ?? 'TIDAK_DIKETAHUI'));
                
                // Hapus tanda kutip atau titik jika Gemini bandel
                $aiText = preg_replace('/[^A-Z_]/', '', $aiText);
                
                // Menerjemahkan jawaban Gemini ke pergerakan Servo
                $result = $this->executeServoMovement($aiText);

                return response()->json([
                    'status' => 'success',
                    'detection' => $aiText,
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
     * Gerakkan servo berdasarkan jenis sampah yang terdeteksi.
     */
    private function executeServoMovement($type)
    {
        $servo1Angle = 90; // Default Netral
        $servo2Angle = 90; // Default Netral
        $message = "Sistem standby menunggu objek yang jelas.";

        switch ($type) {
            case 'KERTAS':
                $servo1Angle = 90;
                $servo2Angle = 50;
                $message = "Memilah KERTAS ke depan.";
                break;
            case 'PLASTIK':
                $servo1Angle = 180;
                $servo2Angle = 50;
                $message = "Memilah PLASTIK ke kiri.";
                break;
            case 'ORGANIK':
                $servo1Angle = 0;
                $servo2Angle = 50;
                $message = "Memilah ORGANIK ke kanan.";
                break;
            case 'LOGAM':
                $servo1Angle = 90;
                $servo2Angle = 180;
                $message = "Memilah LOGAM ke belakang.";
                break;
            default:
                // Jika TIDAK_DIKETAHUI, biarkan netral
                $type = 'TIDAK_DIKETAHUI';
                break;
        }

        // Jika terdeteksi objek nyata, eksekusi servo
        if ($type !== 'TIDAK_DIKETAHUI') {
            $this->saveServoAngle(1, $servo1Angle);
            $this->saveServoAngle(2, $servo2Angle);
        }

        return [
            'message' => $message,
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
