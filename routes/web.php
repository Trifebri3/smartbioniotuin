<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicMonitorController;
use App\Http\Controllers\PublicSmartBinController;
use App\Http\Controllers\WasteDepositController;
use App\Http\Controllers\ServoController;
use App\Http\Controllers\EcotheologyController;

// Smart Waste Station - 3-State Fullscreen Kiosk & Backdoor Admin
Route::get('/kiosk', [EcotheologyController::class, 'kiosk'])->name('kiosk');
Route::get('/backdoor', [EcotheologyController::class, 'backdoor'])->name('backdoor');
Route::post('/backdoor/store', [EcotheologyController::class, 'store'])->name('backdoor.store');
Route::put('/backdoor/update/{id}', [EcotheologyController::class, 'update'])->name('backdoor.update');
Route::delete('/backdoor/delete/{id}', [EcotheologyController::class, 'destroy'])->name('backdoor.destroy');
Route::get('/api/ecotheology/contents', [EcotheologyController::class, 'apiContents'])->name('api.ecotheology.contents');
Route::get('/api/ecotheology/category', [EcotheologyController::class, 'apiCategoryContent'])->name('api.ecotheology.category');

// Layar Tampilan Publik SmartBin (bin.ihi.my.id)
Route::get('/', [PublicMonitorController::class, 'index'])->name('home');
Route::get('/monitor', [PublicMonitorController::class, 'index'])->name('public.monitor');
Route::get('/public-monitor', [PublicMonitorController::class, 'index'])->name('public.monitor.alias');

// FITUR PUBLIK: Peta Lokasi, Edukasi, & Leaderboard Poin
Route::get('/locations', [PublicSmartBinController::class, 'index'])->name('public.locations');
Route::get('/education', [PublicSmartBinController::class, 'education'])->name('public.education');
Route::get('/leaderboard', [PublicSmartBinController::class, 'leaderboard'])->name('public.leaderboard');

// FITUR PENGGUNA: Scan QR Code, Web App AI Camera, & Setor Sampah
Route::get('/deposit/{uuid?}', [WasteDepositController::class, 'scanQrView'])->name('user.deposit.qr');
Route::get('/scan-ai', [WasteDepositController::class, 'scanAiView'])->name('user.ai.scanner');
Route::post('/deposit/process', [WasteDepositController::class, 'processDeposit'])->name('user.deposit.process');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auto-sort', [\App\Http\Controllers\Api\AutoSortController::class, 'index'])->name('auto.sort');

Route::middleware('auth')->group(function () {
    Route::resource('servos', ServoController::class);
    
    // Riwayat Setor Sampah Pribadi
    Route::get('/my-deposits', [WasteDepositController::class, 'myHistory'])->name('user.my.deposits');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
