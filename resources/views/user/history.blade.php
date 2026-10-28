<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Setor Sampah - SmartBin IoT</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Header Navigasi -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-lg">S</div>
                    <span class="font-bold text-base text-slate-900 tracking-tight">SmartBin IoT</span>
                </a>
            </div>

            <!-- Top Navigation -->
            <nav class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm font-semibold">
                <a href="/locations" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">📍 Peta</a>
                <a href="/scan-ai" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">📷 Scan AI</a>
                <a href="/education" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">🌱 Edukasi</a>
                <a href="/leaderboard" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">🏆 Leaderboard</a>
                <a href="/my-deposits" class="text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-md border border-emerald-200">Riwayat Saya</a>
                @auth
                    <a href="/dashboard" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-1.5 rounded-lg shadow-sm">Dashboard</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full space-y-6">

        <!-- User Profile Eco Summary Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $user->name }}</h1>
                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                    <div class="mt-2 inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <span>{{ $user->eco_badge }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 w-full sm:w-auto text-center sm:text-right">
                <div class="bg-slate-50 p-3 sm:p-4 rounded-2xl border border-slate-100">
                    <div class="text-xs text-slate-400 font-bold uppercase">Poin Anda</div>
                    <div class="text-2xl font-black text-emerald-600 mt-0.5">{{ number_format($user->points) }}</div>
                </div>
                <div class="bg-slate-50 p-3 sm:p-4 rounded-2xl border border-slate-100">
                    <div class="text-xs text-slate-400 font-bold uppercase">Total Setoran</div>
                    <div class="text-2xl font-black text-slate-900 mt-0.5">{{ $deposits->total() }}</div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex justify-between items-center">
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Catatan Aktivitas Setor Sampah</h2>
            <div class="flex items-center gap-2">
                <a href="/scan-ai" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-sm transition flex items-center gap-1.5">
                    <span>📷 Scan AI Baru</span>
                </a>
                <a href="/deposit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-3.5 py-2 rounded-xl transition flex items-center gap-1.5">
                    <span>📱 Scan QR Bin</span>
                </a>
            </div>
        </div>

        <!-- History Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Jenis Sampah</th>
                            <th class="py-3 px-4">Metode Setor</th>
                            <th class="py-3 px-4">Estimasi Berat</th>
                            <th class="py-3 px-4 text-right">Poin Diperoleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @forelse($deposits as $d)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 text-slate-500">
                                    {{ $d->created_at ? $d->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="py-3.5 px-4 font-bold">
                                    @php
                                        $cat = strtolower($d->category);
                                        $badgeClass = 'bg-slate-100 text-slate-700';
                                        $icon = '📦';
                                        if (str_contains($cat, 'organik')) {
                                            $badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                                            $icon = '🥬';
                                        } elseif (str_contains($cat, 'plastik')) {
                                            $badgeClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                                            $icon = '🧴';
                                        } elseif (str_contains($cat, 'kertas')) {
                                            $badgeClass = 'bg-blue-50 text-blue-700 border border-blue-200';
                                            $icon = '📄';
                                        } elseif (str_contains($cat, 'logam')) {
                                            $badgeClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                                            $icon = '🥫';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs {{ $badgeClass }}">
                                        <span>{{ $icon }}</span>
                                        <span>{{ ucfirst($d->category) }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-xs">
                                    @if($d->source === 'web_ai_scanner')
                                        📷 Web AI Scanner ({{ number_format($d->confidence ?? 90, 1) }}%)
                                    @elseif($d->source === 'qr_web_deposit')
                                        📱 QR SmartBin
                                    @else
                                        {{ $d->source ?? 'Hardware Auto' }}
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ number_format($d->weight_est_kg ?? 0.25, 2) }} kg
                                </td>
                                <td class="py-3.5 px-4 text-right font-black text-emerald-600">
                                    +{{ $d->points_earned }} Pts
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-400">
                                    Belum ada catatan setoran sampah. Scan sampah pertamamu sekarang!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($deposits->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $deposits->links() }}
                </div>
            @endif
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        SmartBin IoT Platform &bull; Kampus Cerdas & Ramah Lingkungan &bull; bin.ihi.my.id
    </footer>

</body>
</html>

