<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetectionLog;
use App\Models\Servo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CaptureApiController extends Controller
{
    /**
     * Menerima upload hasil capture otomatis beserta hasil inferensi AI dari Python/ESP32/Browser.
     */
    public function upload(Request $request)
    {
        try {
            $inputCategory = $request->input('category');
            $confidence = $request->input('confidence') !== null ? (float)$request->input('confidence') : null;
            $suggestion = $request->input('suggestion');
            $latencyMs = $request->input('latency_ms') !== null ? (int)$request->input('latency_ms') : null;
            $source = $request->input('source', 'webcam_hybrid');
            $rawData = $request->input('raw_data');

            if (is_string($rawData)) {
                $decoded = json_decode($rawData, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $rawData = $decoded;
                }
            }

            // Siapkan direktori penyimpanan di public/detections
            $detectionDir = public_path('detections');
            if (!File::exists($detectionDir)) {
                File::makeDirectory($detectionDir, 0755, true);
            }

            $timestamp = now()->format('Ymd_His');
            $random = Str::random(5);
            $tempCategory = !empty($inputCategory) ? strtolower(trim($inputCategory)) : 'temp';
            $filename = "smartbin_{$tempCategory}_{$timestamp}_{$random}.jpg";
            $targetPath = $detectionDir . DIRECTORY_SEPARATOR . $filename;
            $cameraJpgPath = public_path('camera.jpg');

            $imageSaved = false;

            // 1. Cek apakah diupload via file multipart (form-data)
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file->move($detectionDir, $filename);
                @copy($targetPath, $cameraJpgPath);
                $imageSaved = true;
            }
            // 2. Cek apakah dikirim sebagai base64 string
            elseif ($request->filled('image_base64')) {
                $base64 = $request->input('image_base64');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64)) {
                    $base64 = substr($base64, strpos($base64, ',') + 1);
                }
                $decodedImage = base64_decode($base64);
                if ($decodedImage !== false) {
                    file_put_contents($targetPath, $decodedImage);
                    file_put_contents($cameraJpgPath, $decodedImage);
                    $imageSaved = true;
                }
            }
            // 3. Cek apakah dikirim sebagai raw binary body
            elseif (!empty($request->getContent())) {
                $binary = $request->getContent();
                file_put_contents($targetPath, $binary);
                file_put_contents($cameraJpgPath, $binary);
                $imageSaved = true;
            }

            // Jika tidak ada gambar yang diupload, gunakan frame camera.jpg yang sudah ada
            if (!$imageSaved) {
                if (File::exists($cameraJpgPath)) {
                    @copy($cameraJpgPath, $targetPath);
                    $imageSaved = true;
                } else {
                    $filename = null;
                }
            }

            // JALANKAN AI CLASSIFIER SECARA NYATA JIKA:
            // - Kategori kosong atau 'auto'
            // - Atau diunggah dari browser webcam
            $needAiInference = empty($inputCategory) || 
                               $inputCategory === 'auto' || 
                               $source === 'browser_webcam';

            if ($needAiInference && $imageSaved && file_exists($targetPath)) {
                $t0 = microtime(true);
                $aiResult = $this->classifyImage($targetPath);
                $latencyMs = (int)round((microtime(true) - $t0) * 1000);

                if ($aiResult && isset($aiResult['class'])) {
                    $category = strtolower($aiResult['class']);
                    $confidence = $aiResult['confidence'] ?? 95.0;
                    $rawData = $aiResult['probs'] ?? null;

                    // Rename file ke nama kategori yang benar
                    $correctFilename = "smartbin_{$category}_{$timestamp}_{$random}.jpg";
                    $correctPath = $detectionDir . DIRECTORY_SEPARATOR . $correctFilename;
                    if (@rename($targetPath, $correctPath)) {
                        $filename = $correctFilename;
                        $targetPath = $correctPath;
                    }
                } else {
                    $category = !empty($inputCategory) ? strtolower(trim($inputCategory)) : 'organik';
                }
            } else {
                $category = !empty($inputCategory) ? strtolower(trim($inputCategory)) : 'organik';
            }

            // Tentukan saran wadah
            $suggestion = match ($category) {
                'organik' => 'Wadah HIJAU (Sisa Makanan, Daun, Kompos)',
                'plastik' => 'Wadah BIRU (Botol, Kantong, Gelas Plastik)',
                'kertas' => 'Wadah KUNING (Kardus, Kertas Kering, Karton)',
                'logam', 'logam_kaca' => 'Wadah MERAH / ABU (Kaleng, Kaca, Logam)',
                default => 'Pemeriksaan manual diperlukan',
            };

            // Gerakkan servo sesuai kategori yang terdeteksi
            $servoResult = $this->executeServoMovement($category);

            // Simpan ke database DetectionLog
            $log = DetectionLog::create([
                'category' => $category,
                'confidence' => $confidence,
                'suggestion' => $suggestion,
                'servo_action' => $servoResult['action'],
                'latency_ms' => $latencyMs,
                'source' => $source,
                'image_path' => $filename ? 'detections/' . $filename : null,
                'raw_data' => $rawData,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Hasil capture dan klasifikasi AI berhasil direkam ke cloud!',
                'data' => $log,
                'servo' => [
                    'servo1' => $servoResult['servo1'],
                    'servo2' => $servoResult['servo2'],
                    'action' => $servoResult['action'],
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error in CaptureApiController@upload: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal merekam capture: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menjalankan inferensi model AI MobileNetV2 + XGBoost melalui script Python
     */
    private function classifyImage(string $imageFullPath): ?array
    {
        if (!file_exists($imageFullPath)) {
            return null;
        }

        // Cari lokasi infer_hybrid.py secara fleksibel (Windows lokal atau Linux VPS/hosting)
        $candidates = [
            base_path('../infer_hybrid.py'),
            base_path('infer_hybrid.py'),
            'c:\\PHYTON\\smartbin\\infer_hybrid.py',
            env('PYTHON_INFER_SCRIPT'),
        ];

        $scriptPath = null;
        foreach ($candidates as $cand) {
            if (!empty($cand) && file_exists($cand)) {
                $scriptPath = $cand;
                break;
            }
        }

        if (!$scriptPath) {
            return null;
        }

        $pythonBin = PHP_OS_FAMILY === 'Windows' ? 'py' : 'python3';
        if (env('PYTHON_BIN')) {
            $pythonBin = env('PYTHON_BIN');
        }

        try {
            $cmd = $pythonBin . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($imageFullPath) . ' --json 2>&1';
            $output = shell_exec($cmd);

            if (!empty($output)) {
                $lines = explode("\n", trim($output));
                foreach (array_reverse($lines) as $line) {
                    $line = trim($line);
                    if (str_starts_with($line, '{') && str_ends_with($line, '}')) {
                        $decoded = json_decode($line, true);
                        if (isset($decoded['status']) && $decoded['status'] === 'success') {
                            return $decoded;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Gagal menjalankan infer_hybrid.py: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Mengambil daftar riwayat capture terbaru (untuk galeri/feed).
     */
    public function recent(Request $request)
    {
        $limit = min((int)$request->input('limit', 12), 50);

        $captures = DetectionLog::latest('id')
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'count' => $captures->count(),
            'data' => $captures,
        ]);
    }

    /**
     * Menggerakkan servo secara otomatis berdasarkan kategori
     */
    private function executeServoMovement(string $category): array
    {
        $servo1Angle = 90; // Netral
        $servo2Angle = 90; // Netral
        $action = "Standby";

        switch ($category) {
            case 'organik':
                $servo1Angle = 0;
                $servo2Angle = 50;
                $action = "Memilah ORGANIK ke kanan (Wadah Hijau)";
                break;
            case 'plastik':
                $servo1Angle = 180;
                $servo2Angle = 50;
                $action = "Memilah PLASTIK ke kiri (Wadah Biru)";
                break;
            case 'kertas':
                $servo1Angle = 90;
                $servo2Angle = 50;
                $action = "Memilah KERTAS ke depan (Wadah Kuning)";
                break;
            case 'logam':
            case 'logam_kaca':
                $servo1Angle = 90;
                $servo2Angle = 180;
                $action = "Memilah LOGAM/KACA ke belakang (Wadah Merah)";
                break;
        }

        $this->saveServoAngle(1, $servo1Angle);
        $this->saveServoAngle(2, $servo2Angle);

        return [
            'servo1' => $servo1Angle,
            'servo2' => $servo2Angle,
            'action' => $action,
        ];
    }

    private function saveServoAngle(int $id, int $angle): void
    {
        try {
            $servo = Servo::find($id) ?? new Servo();
            if (!$servo->exists) {
                $servo->id = $id;
                $servo->name = "Servo {$id}";
                $servo->pin = ($id === 1) ? 26 : 27;
            }
            $servo->current_angle = $angle;
            $servo->save();
        } catch (\Exception $e) {
            Log::warning("Gagal memperbarui servo {$id}: " . $e->getMessage());
        }
    }
}
