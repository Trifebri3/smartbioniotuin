<?php

namespace App\Http\Controllers;

use App\Models\SmartBin;
use App\Models\User;
use App\Models\WasteDeposit;
use App\Http\Controllers\Api\CaptureApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WasteDepositController extends Controller
{
    /**
     * Halaman Scan QR Code SmartBin via Kamera HP
     */
    public function scanQrView($uuid = null)
    {
        $selectedBin = null;
        if ($uuid) {
            $selectedBin = SmartBin::where('uuid', $uuid)->first();
        }
        $availableBins = SmartBin::all();
        return view('user.deposit-qr', compact('selectedBin', 'availableBins', 'uuid'));
    }

    /**
     * Halaman Scanner Kamera AI Langsung di Web App Browser
     */
    public function scanAiView()
    {
        return view('user.ai-scanner');
    }

    /**
     * Proses Setor Sampah, Analisis AI, dan Pemberian Poin Gamifikasi
     */
    public function processDeposit(Request $request)
    {
        $request->validate([
            'smart_bin_id' => 'nullable',
            'category' => 'nullable|string',
            'image_base64' => 'nullable|string',
            'weight_est_kg' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $category = $request->input('category');
        $confidence = 90.0;
        $photoFilename = null;

        // Jika user mengunggah foto kamera via web app
        if ($request->filled('image_base64')) {
            $base64 = $request->input('image_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64)) {
                $base64 = substr($base64, strpos($base64, ',') + 1);
            }
            $imageBinary = base64_decode($base64);

            $dir = public_path('deposits');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $timestamp = now()->format('Ymd_His');
            $rand = Str::random(5);
            $photoFilename = "deposit_{$timestamp}_{$rand}.jpg";
            $photoPath = $dir . DIRECTORY_SEPARATOR . $photoFilename;
            file_put_contents($photoPath, $imageBinary);

            // Jika kategori belum ditentukan manual, jalankan deteksi AI
            if (empty($category) || $category === 'auto') {
                $captureApi = new CaptureApiController();
                // Manfaatkan engine AI tier CaptureApiController
                $reflection = new \ReflectionClass($captureApi);
                $method = $reflection->getMethod('classifyImage');
                $method->setAccessible(true);
                $aiResult = $method->invoke($captureApi, $photoPath);

                $category = strtolower($aiResult['class'] ?? 'organik');
                $confidence = (float)($aiResult['confidence'] ?? 88.0);
            }
        }

        if (empty($category)) {
            $category = 'plastik'; // Default fallback
        }

        $weightKg = (float)($request->input('weight_est_kg', 0.25));

        // 1. Berikan Poin Gamifikasi ke User (jika login)
        $pointsEarned = 15;
        $newBadge = 'Eco Starter 🥉';
        $totalPoints = 0;

        if ($user) {
            $pointsEarned = $user->awardPoints($category, $weightKg);
            $newBadge = $user->eco_badge;
            $totalPoints = $user->points;
        }

        // 2. Catat Riwayat Transaksi Setor Sampah
        $deposit = WasteDeposit::create([
            'user_id' => $user ? $user->id : null,
            'smart_bin_id' => $request->input('smart_bin_id'),
            'category' => $category,
            'points_earned' => $pointsEarned,
            'weight_est_kg' => $weightKg,
            'photo_path' => $photoFilename,
            'confidence' => $confidence,
            'source' => $request->input('source', 'web_scanner'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Sampah berhasil disetor! Anda mendapatkan +{$pointsEarned} Poin Lingkungan.",
            'data' => [
                'deposit_id' => $deposit->id,
                'category' => strtoupper($category),
                'confidence' => $confidence,
                'points_earned' => $pointsEarned,
                'user_total_points' => $totalPoints,
                'user_eco_badge' => $newBadge,
            ]
        ]);
    }

    /**
     * Riwayat Setor Sampah Pengguna
     */
    public function myHistory()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $deposits = WasteDeposit::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return view('user.history', compact('user', 'deposits'));
    }
}

