<?php

namespace App\Http\Controllers;

use App\Models\DetectionLog;
use App\Models\Servo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PublicMonitorController extends Controller
{
    /**
     * Tampilkan halaman Pemantauan Publik SmartBin AI (https://bin.ihi.my.id/)
     */
    public function index()
    {
        $summary = $this->buildSummaryData();
        return view('public-monitor', $summary);
    }

    /**
     * Endpoint API JSON untuk pembaruan polling real-time tanpa refresh halaman
     */
    public function getSummary(Request $request)
    {
        $summary = $this->buildSummaryData();
        return response()->json($summary);
    }

    /**
     * Kompilasi data ringkasan sistem, sensor, dan deteksi
     */
    private function buildSummaryData(): array
    {
        // 1. Deteksi paling mutakhir
        $latest = DetectionLog::latest('id')->first();

        // 2. Daftar 12 hasil capture terakhir
        $recentCaptures = DetectionLog::latest('id')
            ->limit(12)
            ->get();

        // 3. Sensor Kapasitas 4 Bin (Ambil dari Cache atau fallback)
        $cachedSensors = Cache::get('sensor_data', [
            'sensor1' => -1,
            'sensor2' => -1,
            'sensor3' => -1,
            'sensor4' => -1,
        ]);

        $bins = [
            1 => $this->calculateBinStatus(1, 'Organik', '#10B981', $cachedSensors['sensor1'] ?? -1),
            2 => $this->calculateBinStatus(2, 'Plastik', '#0284C7', $cachedSensors['sensor2'] ?? -1),
            3 => $this->calculateBinStatus(3, 'Kertas', '#F59E0B', $cachedSensors['sensor3'] ?? -1),
            4 => $this->calculateBinStatus(4, 'Logam & Kaca', '#E11D48', $cachedSensors['sensor4'] ?? -1),
        ];

        // 4. Statistik Keseluruhan & Hari Ini
        $totalAllTime = DetectionLog::count();
        $totalToday = DetectionLog::whereDate('created_at', now()->today())->count();

        // Hitung per kategori
        $categoryCounts = DetectionLog::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        $organikCount = $categoryCounts['organik'] ?? 0;
        $plastikCount = $categoryCounts['plastik'] ?? 0;
        $kertasCount = $categoryCounts['kertas'] ?? 0;
        $logamCount = ($categoryCounts['logam'] ?? 0) + ($categoryCounts['logam_kaca'] ?? 0);

        $avgConfidence = DetectionLog::whereNotNull('confidence')
            ->avg('confidence');

        // 5. Data Servos
        $servos = Servo::orderBy('id')->get()->keyBy('id');

        return [
            'latest' => $latest,
            'recentCaptures' => $recentCaptures,
            'bins' => $bins,
            'servos' => [
                'servo1' => $servos->get(1)->current_angle ?? 90,
                'servo2' => $servos->get(2)->current_angle ?? 90,
            ],
            'stats' => [
                'total' => $totalAllTime,
                'today' => $totalToday,
                'avg_confidence' => $avgConfidence ? round($avgConfidence, 1) : 96.5,
                'categories' => [
                    'organik' => [
                        'count' => $organikCount,
                        'pct' => $totalAllTime > 0 ? round(($organikCount / $totalAllTime) * 100, 1) : 0,
                    ],
                    'plastik' => [
                        'count' => $plastikCount,
                        'pct' => $totalAllTime > 0 ? round(($plastikCount / $totalAllTime) * 100, 1) : 0,
                    ],
                    'kertas' => [
                        'count' => $kertasCount,
                        'pct' => $totalAllTime > 0 ? round(($kertasCount / $totalAllTime) * 100, 1) : 0,
                    ],
                    'logam' => [
                        'count' => $logamCount,
                        'pct' => $totalAllTime > 0 ? round(($logamCount / $totalAllTime) * 100, 1) : 0,
                    ],
                ],
            ],
            'server_time' => now()->format('H:i:s, d M Y'),
            'system_online' => true,
        ];
    }

    /**
     * Hitung persentase kapasitas tempat sampah berdasarkan jarak sensor ultrasonik
     */
    private function calculateBinStatus(int $id, string $name, string $color, $distance): array
    {
        $maxDist = 40.0; // cm (kosong)
        $minDist = 5.0;  // cm (penuh)

        if ($distance === null || $distance < 0) {
            return [
                'id' => $id,
                'name' => $name,
                'color' => $color,
                'distance' => null,
                'percentage' => 0,
                'is_active' => false,
                'status_label' => 'Offline / Standby',
                'status_color' => 'slate',
            ];
        }

        $dist = (float)$distance;
        if ($dist <= $minDist) {
            $pct = 100;
        } elseif ($dist >= $maxDist) {
            $pct = 0;
        } else {
            $pct = 100 - (($dist - $minDist) / ($maxDist - $minDist) * 100);
        }

        $pct = (int)round(max(0, min(100, $pct)));

        if ($pct >= 90) {
            $label = 'Hampir Penuh';
            $badgeColor = 'rose';
        } elseif ($pct >= 70) {
            $label = 'Siap Dikosongkan';
            $badgeColor = 'amber';
        } elseif ($pct >= 30) {
            $label = 'Normal';
            $badgeColor = 'sky';
        } else {
            $label = 'Kapasitas Longgar';
            $badgeColor = 'emerald';
        }

        return [
            'id' => $id,
            'name' => $name,
            'color' => $color,
            'distance' => round($dist, 1),
            'percentage' => $pct,
            'is_active' => true,
            'status_label' => $label,
            'status_color' => $badgeColor,
        ];
    }
}
