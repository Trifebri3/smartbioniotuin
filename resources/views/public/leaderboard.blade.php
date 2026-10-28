<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papan Peringkat Eco Gamifikasi - SmartBin IoT</title>
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
                <a href="/" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">Monitoring</a>
                <a href="/locations" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">📍 Peta Terdekat</a>
                <a href="/scan-ai" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">📷 Scan AI</a>
                <a href="/education" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">🌱 Edukasi</a>
                <a href="/leaderboard" class="text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-md border border-emerald-200">🏆 Poin & Peringkat</a>
                @auth
                    <a href="/dashboard" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-1.5 rounded-lg shadow-sm">Dashboard</a>
                @else
                    <a href="/login" class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-1.5 rounded-lg">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full space-y-8">

        <!-- Title & Impact Stats -->
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                🏆 Gamifikasi Peduli Lingkungan
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                Papan Peringkat Eco Hero
            </h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Setiap sampah yang kamu pilah dan setor ke SmartBin AI bernilai poin lingkungan. Bersaing secara positif untuk menjaga kelestarian kampus!
            </p>
        </div>

        <!-- Metric Badges -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl font-bold">
                    ⚖️
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ number_format($totalRecycledKg ?? 0, 1) }} kg</div>
                    <div class="text-xs text-slate-500 font-medium">Sampah Berhasil Dipilah</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl font-bold">
                    📦
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ number_format($totalDeposits ?? 0) }}</div>
                    <div class="text-xs text-slate-500 font-medium">Total Transaksi Setor</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-2xl font-bold">
                    👥
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ count($topUsers) }}</div>
                    <div class="text-xs text-slate-500 font-medium">Eco Warrior Aktif</div>
                </div>
            </div>
        </div>

        <!-- Podium Top 3 (if users >= 3) -->
        @if(count($topUsers) >= 3)
            <div class="bg-gradient-to-b from-slate-900 to-slate-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
                <h2 class="text-center font-bold text-sm uppercase tracking-widest text-emerald-400 mb-6">
                    🌟 3 Besar Pahlawan Lingkungan Terbaik
                </h2>
                <div class="grid grid-cols-3 gap-2 sm:gap-6 items-end text-center max-w-lg mx-auto">
                    <!-- Rank 2 (Silver) -->
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div class="w-14 sm:w-20 h-14 sm:h-20 rounded-full border-4 border-slate-300 bg-slate-700 flex items-center justify-center text-xl sm:text-2xl font-black shadow-lg">
                                🥈
                            </div>
                            <span class="absolute -bottom-2 bg-slate-300 text-slate-900 font-bold text-xs px-2 py-0.5 rounded-full">#2</span>
                        </div>
                        <h3 class="font-bold text-xs sm:text-sm mt-3 truncate max-w-[90px] sm:max-w-[130px]">{{ $topUsers[1]->name }}</h3>
                        <p class="text-emerald-400 font-extrabold text-xs sm:text-sm mt-0.5">{{ number_format($topUsers[1]->points) }} Pts</p>
                        <span class="text-[10px] sm:text-xs text-slate-400 mt-0.5">{{ $topUsers[1]->eco_badge }}</span>
                        <div class="w-full h-16 sm:h-24 bg-slate-700/60 rounded-t-xl mt-3 flex items-center justify-center font-bold text-slate-400">2nd</div>
                    </div>

                    <!-- Rank 1 (Gold) -->
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div class="w-18 sm:w-24 h-18 sm:h-24 rounded-full border-4 border-amber-400 bg-amber-500/20 flex items-center justify-center text-2xl sm:text-4xl font-black shadow-xl ring-4 ring-amber-400/30">
                                👑
                            </div>
                            <span class="absolute -bottom-2 bg-amber-400 text-amber-950 font-black text-xs px-2.5 py-0.5 rounded-full shadow">#1</span>
                        </div>
                        <h3 class="font-black text-sm sm:text-base mt-3 text-amber-300 truncate max-w-[100px] sm:max-w-[150px]">{{ $topUsers[0]->name }}</h3>
                        <p class="text-amber-400 font-black text-sm sm:text-base mt-0.5">{{ number_format($topUsers[0]->points) }} Pts</p>
                        <span class="text-[10px] sm:text-xs text-amber-200 mt-0.5">{{ $topUsers[0]->eco_badge }}</span>
                        <div class="w-full h-24 sm:h-36 bg-amber-500/30 border-t border-amber-400/50 rounded-t-xl mt-3 flex items-center justify-center font-black text-amber-300 text-lg sm:text-xl">1st</div>
                    </div>

                    <!-- Rank 3 (Bronze) -->
                    <div class="flex flex-col items-center">
                        <div class="relative">
                            <div class="w-14 sm:w-20 h-14 sm:h-20 rounded-full border-4 border-amber-700 bg-slate-700 flex items-center justify-center text-xl sm:text-2xl font-black shadow-lg">
                                🥉
                            </div>
                            <span class="absolute -bottom-2 bg-amber-700 text-amber-100 font-bold text-xs px-2 py-0.5 rounded-full">#3</span>
                        </div>
                        <h3 class="font-bold text-xs sm:text-sm mt-3 truncate max-w-[90px] sm:max-w-[130px]">{{ $topUsers[2]->name }}</h3>
                        <p class="text-emerald-400 font-extrabold text-xs sm:text-sm mt-0.5">{{ number_format($topUsers[2]->points) }} Pts</p>
                        <span class="text-[10px] sm:text-xs text-slate-400 mt-0.5">{{ $topUsers[2]->eco_badge }}</span>
                        <div class="w-full h-12 sm:h-18 bg-slate-700/60 rounded-t-xl mt-3 flex items-center justify-center font-bold text-slate-400">3rd</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Full Table Leaderboard -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-900 text-sm sm:text-base">Top 10 Kontributor Peduli Lingkungan</h3>
                <span class="text-xs text-slate-500">Pembaruan Real-Time</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-[11px] font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 w-16 text-center">Peringkat</th>
                            <th class="py-3 px-4">Nama Pengguna</th>
                            <th class="py-3 px-4">Badge / Gelar</th>
                            <th class="py-3 px-4 text-right">Poin Terkumpul</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @forelse($topUsers as $index => $u)
                            <tr class="hover:bg-slate-50/80 transition {{ $index < 3 ? 'bg-amber-50/20' : '' }}">
                                <td class="py-3 px-4 text-center font-bold">
                                    @if($index === 0) 🥇 1
                                    @elseif($index === 1) 🥈 2
                                    @elseif($index === 2) 🥉 3
                                    @else {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-900 flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-700">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $u->name }}</span>
                                    @if(Auth::id() === $u->id)
                                        <span class="text-[10px] bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-bold">Anda</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-semibold">
                                        {{ $u->eco_badge }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right font-black text-emerald-600">
                                    +{{ number_format($u->points) }} Pts
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">
                                    Belum ada transaksi setor sampah. Jadilah yang pertama mendapatkan poin!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action Card -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h4 class="font-bold text-emerald-900 text-sm sm:text-base">Ingin naik peringkat ke Top Eco Hero?</h4>
                <p class="text-xs text-emerald-700 mt-0.5">Bawa sampah daur ulangmu ke SmartBin AI, scan QR kode di tong sampah dan dapatkan poin instan!</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="/scan-ai" class="w-full sm:w-auto text-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-sm transition">
                    📷 Buka Web Scanner AI
                </a>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        SmartBin IoT Platform &bull; Kampus Cerdas & Ramah Lingkungan &bull; bin.ihi.my.id
    </footer>

</body>
</html>

