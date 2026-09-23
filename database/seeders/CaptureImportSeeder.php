<?php

namespace Database\Seeders;

use App\Models\DetectionLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CaptureImportSeeder extends Seeder
{
    public function run(): void
    {
        $srcDir = 'c:/PHYTON/smartbin/captures';
        $destDir = public_path('detections');

        if (!File::exists($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        $files = glob($srcDir . '/*.jpg');
        foreach ($files as $index => $f) {
            $base = basename($f);
            @copy($f, $destDir . '/' . $base);

            // Parse category from filename: smartbin_{category}_{timestamp}.jpg
            if (preg_match('/smartbin_([a-zA-Z0-9_]+)_\d+_\d+\.jpg/', $base, $matches)) {
                $cat = $matches[1];
            } else {
                $cat = 'kertas';
            }

            $sugg = match ($cat) {
                'organik' => 'Wadah HIJAU (Sisa Makanan, Daun, Kompos)',
                'plastik' => 'Wadah BIRU (Botol, Kantong, Gelas Plastik)',
                'kertas' => 'Wadah KUNING (Kardus, Kertas Kering, Karton)',
                'logam', 'logam_kaca' => 'Wadah MERAH / ABU (Kaleng, Kaca, Logam)',
                default => 'Wadah Umum'
            };

            $conf = match ($cat) {
                'organik' => 97.4,
                'plastik' => 98.8,
                'kertas' => 96.2,
                default => 95.0
            };

            DetectionLog::firstOrCreate(
                ['image_path' => 'detections/' . $base],
                [
                    'category' => $cat,
                    'confidence' => $conf,
                    'suggestion' => $sugg,
                    'servo_action' => 'Memilah ' . strtoupper($cat) . ' secara otomatis',
                    'latency_ms' => rand(48, 115),
                    'source' => 'webcam_hybrid',
                    'created_at' => now()->subMinutes((count($files) - $index) * 4),
                ]
            );

            $this->command->info("Tersimpan: {$base} ({$cat})");
        }

        if (!empty($files)) {
            @copy(end($files), public_path('camera.jpg'));
        }
    }
}
