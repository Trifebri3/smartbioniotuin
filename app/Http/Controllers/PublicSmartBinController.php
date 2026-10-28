<?php

namespace App\Http\Controllers;

use App\Models\SmartBin;
use App\Models\User;
use App\Models\WasteDeposit;
use Illuminate\Http\Request;

class PublicSmartBinController extends Controller
{
    /**
     * Halaman Publik: Peta Lokasi Smart Bin Terdekat & Status Kapasitas
     */
    public function index()
    {
        $smartBins = SmartBin::all();
        
        // Data default jika database belum memiliki data fisik
        if ($smartBins->isEmpty()) {
            $smartBins = collect([
                (object) [
                    'id' => 1,
                    'uuid' => 'smartbin-uin-gedung-a',
                    'name' => 'SmartBin UIN - Gedung Fakultas Sains & Teknologi',
                    'location_name' => 'Lantai 1 Depan Lift Gedung FST',
                    'latitude' => -6.3053,
                    'longitude' => 106.7570,
                    'capacity_organik' => 45.0,
                    'capacity_plastik' => 78.0,
                    'capacity_kertas' => 30.0,
                    'capacity_logam' => 15.0,
                    'status' => 'active',
                ],
                (object) [
                    'id' => 2,
                    'uuid' => 'smartbin-uin-perpustakaan',
                    'name' => 'SmartBin UIN - Perpustakaan Utama',
                    'location_name' => 'Lobby Depan Perpustakaan',
                    'latitude' => -6.3060,
                    'longitude' => 106.7582,
                    'capacity_organik' => 92.0,
                    'capacity_plastik' => 60.0,
                    'capacity_kertas' => 85.0,
                    'capacity_logam' => 20.0,
                    'status' => 'full',
                ]
            ]);
        }

        return view('public.bins-map', compact('smartBins'));
    }

    /**
     * Halaman Publik: Edukasi Pemilahan Sampah 3R
     */
    public function education()
    {
        return view('public.education');
    }

    /**
     * Halaman Leaderboard Peringkat Gamifikasi
     */
    public function leaderboard()
    {
        $topUsers = User::orderBy('points', 'desc')
            ->limit(10)
            ->get();

        $totalRecycledKg = WasteDeposit::sum('weight_est_kg');
        $totalDeposits = WasteDeposit::count();

        return view('public.leaderboard', compact('topUsers', 'totalRecycledKg', 'totalDeposits'));
    }
}

