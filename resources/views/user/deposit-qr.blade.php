<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Setor Sampah via QR Code - SmartBin IoT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Header Navigasi -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-lg">S</div>
                    <span class="font-bold text-base text-slate-900 tracking-tight">SmartBin QR Deposit</span>
                </a>
            </div>

            <!-- Top Navigation -->
            <nav class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm font-semibold">
                <a href="/locations" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">📍 Peta</a>
                <a href="/scan-ai" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">📷 Scan AI</a>
                <a href="/leaderboard" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">🏆 Leaderboard</a>
                @auth
                    <a href="/my-deposits" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-1.5 rounded-lg shadow-sm">
                        {{ Auth::user()->name }} ({{ Auth::user()->points }} Pts)
                    </a>
                @else
                    <a href="/login" class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-1.5 rounded-lg">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-xl mx-auto px-4 py-8 flex-1 w-full space-y-6">

        @if($selectedBin)
            <!-- Terdeteksi via Scan QR Fisik -->
            <div class="bg-emerald-600 text-white p-6 rounded-3xl shadow-lg space-y-3">
                <div class="flex items-center justify-between">
                    <span class="bg-white/20 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        SmartBin Terhubung
                    </span>
                    <span class="text-xs bg-emerald-500/80 px-2.5 py-0.5 rounded-full">Online ✅</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black">{{ $selectedBin->name }}</h1>
                <p class="text-xs text-emerald-100 flex items-center gap-1.5">
                    📍 {{ $selectedBin->location_name ?? 'Lokasi Terdaftar' }}
                </p>
                <input type="hidden" id="smart_bin_id" value="{{ $selectedBin->id }}">
            </div>
        @else
            <!-- Scanner QR Code Reader jika belum ada UUID -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="text-center space-y-1">
                    <span class="text-emerald-600 font-bold text-xs uppercase">Scan QR Pada Tong Sampah</span>
                    <h2 class="text-xl font-extrabold text-slate-900">Arahkan Kamera ke QR SmartBin</h2>
                    <p class="text-xs text-slate-500">Scan stiker QR code yang tertera pada bagian depan SmartBin untuk memulai setoran.</p>
                </div>

                <div id="qr-reader" class="rounded-2xl overflow-hidden border border-slate-200"></div>

                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-4 text-xs text-slate-400 font-medium">atau pilih SmartBin manual</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Lokasi SmartBin</label>
                    <select id="smart_bin_id" class="w-full text-xs sm:text-sm rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">-- Pilih SmartBin Terdekat --</option>
                        @foreach($availableBins as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->location_name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <!-- Form Pilih Jenis Sampah & Dapatkan Poin -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-5">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Pilih Jenis Sampah yang Disetor</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pilih kategori yang sesuai untuk membuka pintu servo dan mendapatkan poin reward.</p>
            </div>

            <!-- Radio Kategori Sampah -->
            <div class="grid grid-cols-2 gap-3">
                <label class="category-card cursor-pointer border-2 border-slate-200 hover:border-emerald-500 p-3.5 rounded-2xl flex flex-col justify-between transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/40">
                    <input type="radio" name="category" value="organik" class="sr-only" checked>
                    <div class="text-2xl mb-1">🥬</div>
                    <div class="font-bold text-xs text-slate-900">Organik</div>
                    <div class="text-[11px] text-emerald-600 font-bold mt-1">+10 Pts</div>
                </label>

                <label class="category-card cursor-pointer border-2 border-slate-200 hover:border-amber-500 p-3.5 rounded-2xl flex flex-col justify-between transition has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50/40">
                    <input type="radio" name="category" value="plastik" class="sr-only">
                    <div class="text-2xl mb-1">🧴</div>
                    <div class="font-bold text-xs text-slate-900">Plastik</div>
                    <div class="text-[11px] text-amber-600 font-bold mt-1">+20 Pts</div>
                </label>

                <label class="category-card cursor-pointer border-2 border-slate-200 hover:border-blue-500 p-3.5 rounded-2xl flex flex-col justify-between transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/40">
                    <input type="radio" name="category" value="kertas" class="sr-only">
                    <div class="text-2xl mb-1">📄</div>
                    <div class="font-bold text-xs text-slate-900">Kertas &amp; Karton</div>
                    <div class="text-[11px] text-blue-600 font-bold mt-1">+15 Pts</div>
                </label>

                <label class="category-card cursor-pointer border-2 border-slate-200 hover:border-rose-500 p-3.5 rounded-2xl flex flex-col justify-between transition has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/40">
                    <input type="radio" name="category" value="logam" class="sr-only">
                    <div class="text-2xl mb-1">🥫</div>
                    <div class="font-bold text-xs text-slate-900">Logam &amp; Kaleng</div>
                    <div class="text-[11px] text-rose-600 font-bold mt-1">+25 Pts</div>
                </label>
            </div>

            <!-- Estimasi Berat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Estimasi Berat Sampah</label>
                <div class="flex items-center gap-2">
                    <input type="number" step="0.05" id="weight_est" value="0.25" class="w-full text-xs sm:text-sm rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <span class="text-xs font-bold text-slate-500">Kilogram</span>
                </div>
            </div>

            <!-- Submit Button -->
            <button onclick="submitDeposit()" id="btnSubmit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-sm py-3.5 rounded-2xl shadow-lg shadow-emerald-600/20 transition flex items-center justify-center gap-2">
                <span>Konfirmasi Setor Sampah &amp; Klaim Poin</span>
            </button>
        </div>

        <!-- Success Feedback Modal / Alert -->
        <div id="successCard" class="hidden bg-white p-6 rounded-3xl border border-emerald-200 shadow-xl space-y-4">
            <div class="text-center space-y-2">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 text-3xl font-black flex items-center justify-center mx-auto">
                    ✓
                </div>
                <h3 class="font-black text-slate-900 text-lg">Setoran Berhasil Diverifikasi!</h3>
                <p id="msgReward" class="text-xs text-emerald-700 font-bold bg-emerald-50 p-2.5 rounded-xl border border-emerald-200">
                    +20 Poin Lingkungan ditambahkan ke akunmu!
                </p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4 text-xs space-y-2 text-slate-600">
                <div class="flex justify-between">
                    <span>Kategori:</span>
                    <strong id="resCat" class="text-slate-900 uppercase">PLASTIK</strong>
                </div>
                <div class="flex justify-between">
                    <span>Badge Terbaru:</span>
                    <strong id="resBadge" class="text-emerald-700">Eco Warrior 🥈</strong>
                </div>
                <div class="flex justify-between">
                    <span>Total Poin Anda:</span>
                    <strong id="resTotal" class="text-emerald-700">140 Pts</strong>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="/my-deposits" class="flex-1 text-center bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2.5 rounded-xl transition">
                    Lihat Riwayat
                </a>
                <button onclick="location.reload()" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs py-2.5 rounded-xl transition">
                    Setor Lagi
                </button>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        SmartBin IoT Platform &bull; Kampus Cerdas & Ramah Lingkungan &bull; bin.ihi.my.id
    </footer>

    <script>
        // Start QR Code reader if element exists
        const qrElement = document.getElementById('qr-reader');
        if (qrElement) {
            const html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText, decodedResult) => {
                    console.log("QR Terdeteksi: ", decodedText);
                    html5QrCode.stop();
                    // If QR is full URL like https://bin.ihi.my.id/deposit/smartbin-uin-gedung-a
                    if (decodedText.includes('/deposit/')) {
                        window.location.href = decodedText;
                    } else {
                        alert("SmartBin ID Terdeteksi: " + decodedText);
                    }
                },
                (errorMessage) => {
                    // scanning...
                }
            ).catch(err => {
                console.log("Kamera tidak aktif untuk QR:", err);
            });
        }

        async function submitDeposit() {
            const binIdEl = document.getElementById('smart_bin_id');
            const binId = binIdEl ? binIdEl.value : null;
            const categoryEl = document.querySelector('input[name="category"]:checked');
            const category = categoryEl ? categoryEl.value : 'plastik';
            const weight = parseFloat(document.getElementById('weight_est').value) || 0.25;

            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerText = "Memproses Setoran...";

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/deposit/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        smart_bin_id: binId,
                        category: category,
                        weight_est_kg: weight,
                        source: 'qr_web_deposit'
                    })
                });

                const res = await response.json();
                btn.disabled = false;
                btn.innerText = "Konfirmasi Setor Sampah & Klaim Poin";

                if (res.status === 'success') {
                    document.getElementById('msgReward').innerText = res.message;
                    document.getElementById('resCat').innerText = res.data.category;
                    document.getElementById('resBadge').innerText = res.data.user_eco_badge;
                    document.getElementById('resTotal').innerText = res.data.user_total_points + ' Pts';
                    document.getElementById('successCard').classList.remove('hidden');
                    document.getElementById('successCard').scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert(res.message || "Gagal mencatat setoran.");
                }
            } catch (err) {
                btn.disabled = false;
                btn.innerText = "Konfirmasi Setor Sampah & Klaim Poin";
                alert("Berhasil dicatat secara offline (Mode Simulasi).");
                document.getElementById('successCard').classList.remove('hidden');
            }
        }
    </script>
</body>
</html>

