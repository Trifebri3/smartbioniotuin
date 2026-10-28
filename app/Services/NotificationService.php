<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class NotificationService
{
    /**
     * Kirim notifikasi sampah penuh ke WhatsApp dan Email dengan anti-spam cooldown.
     */
    public static function notifyBinFull(string $binName, string $category, float $capacityPct): bool
    {
        $cooldownKey = "notif_cooldown_" . md5($binName . '_' . $category);

        // Anti-spam cooldown: Hanya kirim 1 notifikasi per 30 menit per wadah
        if (Cache::has($cooldownKey)) {
            Log::info("[NOTIFICATION] Cooldown aktif untuk {$binName} ({$category}). Menghindari pengiriman berulang.");
            return false;
        }

        $adminPhone = env('WA_ADMIN_PHONE', '081234567890');
        $adminEmail = env('ADMIN_EMAIL', 'admin@bin.ihi.my.id');
        $fonnteToken = env('FONNTE_API_TOKEN');

        $message = "⚠️ *PERINGATAN SMARTBIN AI (SAMPAH PENUH)* ⚠️\n\n"
                 . "📍 *Lokasi*: {$binName}\n"
                 . "🗑️ *Kategori Wadah*: " . strtoupper($category) . "\n"
                 . "📊 *Tingkat Kapasitas*: {$capacityPct}%\n"
                 . "⏰ *Waktu*: " . now()->format('d M Y H:i:s') . " WIB\n\n"
                 . "Mohon petugas kebersihan segera melakukan pengosongan wadah agar sistem dapat terus menerima sampah.\n\n"
                 . "👉 *Monitoring Realtime*: " . url('/public-monitor');

        $notified = false;

        // 1. Kirim via WhatsApp (Fonnte API Gateway)
        if (!empty($fonnteToken)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $fonnteToken,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $adminPhone,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

                if ($response->successful()) {
                    Log::info("[NOTIFICATION WA SUCCESS] Terkirim ke {$adminPhone} untuk {$binName}");
                    $notified = true;
                } else {
                    Log::warning("[NOTIFICATION WA FAILED] " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error("[NOTIFICATION WA ERROR] " . $e->getMessage());
            }
        }

        // 2. Kirim via Email (Laravel Mailer)
        try {
            Mail::raw($message, function ($mail) use ($adminEmail, $binName, $category) {
                $mail->to($adminEmail)
                     ->subject("⚠️ [PERINGATAN PENUH] {$binName} - Wadah " . strtoupper($category));
            });
            Log::info("[NOTIFICATION EMAIL SUCCESS] Terkirim ke {$adminEmail}");
            $notified = true;
        } catch (\Exception $e) {
            Log::warning("[NOTIFICATION EMAIL NOTICE] Pengiriman email: " . $e->getMessage());
        }

        // Set cooldown selama 30 menit (1800 detik)
        Cache::put($cooldownKey, true, now()->addMinutes(30));

        return $notified;
    }
}

