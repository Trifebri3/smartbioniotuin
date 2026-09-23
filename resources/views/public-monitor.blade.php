<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SmartBin - Monitoring Pemilah Sampah IoT | bin.ihi.my.id</title>
    <meta name="description" content="Monitoring tempat sampah pintar dan pemilahan otomatis AI secara real-time.">

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased flex flex-col">

    <!-- Header Bersih & Minimalis -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            
            <!-- Logo & Brand -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-lg">
                    S
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-base text-slate-900 tracking-tight">SmartBin IoT</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                            Online
                        </span>
                    </div>
                    <span class="text-xs text-slate-500 font-mono">bin.ihi.my.id</span>
                </div>
            </div>

            <!-- Jam & Menu -->
            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <span id="header-clock" class="text-xs font-mono text-slate-600 font-medium block">--:--:-- WIB</span>
                    <span class="text-[11px] text-slate-400">Waktu Server</span>
                </div>

                <a href="/login" class="px-3.5 py-1.5 rounded-lg border border-slate-300 hover:border-slate-400 hover:bg-slate-50 text-xs font-semibold text-slate-700 transition-colors">
                    Admin Login
                </a>
            </div>

        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-6 space-y-6">

        <!-- Grid Baris 1: Live Kamera & Hasil Deteksi Terakhir -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Kolom Kiri: Live Camera Feed (Col 7) -->
            <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-5 shadow-sm space-y-4">
                
                <!-- Judul & Pilihan Sumber Kamera -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-base text-slate-900 flex items-center gap-2">
                            <span>Live Kamera & Pemantau</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Pantau area sampah langsung secara real-time</p>
                    </div>

                    <!-- Tombol Ganti Mode Kamera -->
                    <div class="inline-flex p-1 bg-slate-100 rounded-lg border border-slate-200 text-xs font-medium self-start sm:self-auto">
                        <button id="btn-mode-live" onclick="setFeedMode('live')" class="px-3 py-1 rounded bg-white text-slate-900 shadow-sm transition-all">
                            Live Stream
                        </button>
                        <button id="btn-mode-browser" onclick="setFeedMode('browser')" class="px-3 py-1 rounded text-slate-600 hover:text-slate-900 transition-all flex items-center gap-1">
                            <span>Kamera Laptop/HP</span>
                        </button>
                        <button id="btn-mode-capture" onclick="setFeedMode('capture')" class="px-3 py-1 rounded text-slate-600 hover:text-slate-900 transition-all">
                            Foto Terakhir
                        </button>
                    </div>
                </div>

                <!-- Frame Layar Video / Canvas -->
                <div class="relative w-full aspect-[4/3] bg-slate-900 rounded-lg overflow-hidden flex items-center justify-center">
                    
                    <!-- 1. Canvas untuk Live Stream dari Cloud / Python / ESP32 -->
                    <canvas id="camera-canvas" width="640" height="480" class="w-full h-full object-cover block"></canvas>

                    <!-- 2. Video untuk Webcam Browser Langsung (WebRTC) -->
                    <video id="browser-webcam-video" autoplay playsinline muted class="w-full h-full object-cover hidden"></video>

                    <!-- 3. Foto Tangkapan AI Terakhir -->
                    <img id="capture-img-view" src="{{ $latest ? $latest->image_url : asset('camera.jpg') }}" alt="Foto Terakhir" class="w-full h-full object-cover hidden">

                    <!-- Overlay Label Status -->
                    <div class="absolute top-3 left-3 px-2 py-1 rounded bg-black/70 text-white font-mono text-[11px] flex items-center gap-1.5 z-10">
                        <span id="live-dot" class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span id="camera-mode-label">LIVE STREAM</span>
                    </div>

                    <div class="absolute bottom-3 right-3 px-2 py-1 rounded bg-black/70 text-slate-300 font-mono text-[11px] z-10">
                        <span id="camera-fps-pill">10 FPS</span>
                    </div>
                </div>

                <!-- Kontrol Khusus jika Memilih Mode Kamera Laptop/HP -->
                <div id="browser-webcam-controls" class="hidden p-3.5 bg-slate-50 border border-slate-200 rounded-lg flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-slate-700 font-medium">Kamera browser aktif di perangkat Anda</span>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button onclick="scanBrowserWebcamNow()" id="btn-scan-webcam" class="w-full sm:w-auto px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            <span>Pindai Sampah Ini (AI)</span>
                        </button>
                    </div>
                </div>

                <!-- Keterangan Cara Kerja Kamera -->
                <div class="pt-2 text-xs text-slate-500 flex items-center justify-between border-t border-slate-100">
                    <span>Sumber Stream: <strong id="stream-source-name" class="text-slate-800">Python infer_webcam.py / ESP32</strong></span>
                    <span id="stream-sync-badge" class="text-slate-400">Sinkron otomatis</span>
                </div>

                <!-- Banner Informatif jika Stream Python Belum Dijalankan -->
                <div id="stream-inactive-banner" class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-900 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div class="leading-relaxed">
                        <strong class="font-bold text-amber-950">Ingin Mengalirkan Live Webcam Komputer?</strong>
                        <p class="mt-0.5 text-amber-900">
                            Gambar di atas saat ini adalah *frame* terakhir karena script AI belum dinyalakan di terminal.
                        </p>
                        <div class="mt-2 space-y-1">
                            <p><strong>Opsi 1 (Webcam Python):</strong> Buka terminal Anda dan ketik:</p>
                            <code class="block bg-amber-100/80 p-1.5 rounded font-mono font-bold text-amber-950 border border-amber-300/50">py infer_webcam.py --server http://127.0.0.1:8000</code>
                            <p class="pt-1"><strong>Opsi 2 (Langsung di Browser):</strong> Cukup klik tab <button onclick="setFeedMode('browser')" class="font-bold underline text-blue-700 hover:text-blue-900 cursor-pointer">"Kamera Laptop/HP"</button> di bagian atas untuk langsung menyalakan webcam dari browser ini tanpa membuka terminal.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Hasil Deteksi Terakhir (Col 5) -->
            <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 p-5 shadow-sm space-y-4">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[11px] font-mono uppercase tracking-wider text-slate-400 block font-semibold">Hasil AI Terakhir</span>
                        <h3 class="font-bold text-base text-slate-900">Klasifikasi Objek</h3>
                    </div>
                    <span id="latest-time-badge" class="text-xs font-mono text-slate-500 bg-slate-100 px-2 py-1 rounded">
                        {{ $latest ? $latest->formatted_time : 'Standby' }}
                    </span>
                </div>

                <!-- Box Kategori Sampah -->
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <span class="text-xs text-slate-500 font-medium block">Jenis Sampah Terdeteksi:</span>
                    <div class="flex items-baseline justify-between mt-1">
                        <h1 id="latest-category-name" class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                            {{ $latest ? strtoupper($latest->category_label) : 'STANDBY' }}
                        </h1>
                        <div class="text-right">
                            <span id="latest-confidence-val" class="text-xl font-bold text-emerald-600">
                                {{ $latest && $latest->confidence ? number_format($latest->confidence, 1) : '98.5' }}%
                            </span>
                            <span class="text-[11px] text-slate-400 block">Keyakinan</span>
                        </div>
                    </div>

                    <!-- Progress Bar Akurasi -->
                    <div class="w-full bg-slate-200 rounded-full h-2 mt-3 overflow-hidden">
                        <div id="latest-confidence-bar" class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $latest && $latest->confidence ? $latest->confidence : 95 }}%"></div>
                    </div>
                </div>

                <!-- Rincian Aksi & Saran Wadah -->
                <div class="space-y-2.5 text-xs">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <span class="text-slate-500 font-medium block">Wadah Pembuangan:</span>
                        <p id="latest-suggestion-text" class="text-sm font-semibold text-slate-900 mt-0.5">
                            {{ $latest ? $latest->suggestion : 'Letakkan sampah di latar hitam untuk pemilahan otomatis.' }}
                        </p>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <span class="text-slate-500 font-medium block">Pergerakan Servo Otomatis:</span>
                        <p id="latest-servo-action" class="text-sm font-semibold text-emerald-700 mt-0.5">
                            {{ $latest ? $latest->servo_action : 'Servo Standby (90° / 90°)' }}
                        </p>
                    </div>
                </div>

                <!-- Thumbnail Foto Capture Terakhir -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img id="latest-thumb-preview" src="{{ $latest ? $latest->image_url : asset('camera.jpg') }}" alt="Preview" class="w-12 h-12 rounded-lg object-cover border border-slate-200 shadow-sm cursor-pointer hover:opacity-90" onclick="openLightbox(this.src, document.getElementById('latest-category-name').textContent, document.getElementById('latest-confidence-val').textContent)">
                        <div>
                            <span class="text-xs font-semibold text-slate-800 block">Foto Tangkapan Terakhir</span>
                            <span id="latest-source-text" class="text-[11px] text-slate-400">Sumber: {{ $latest ? $latest->source : 'Webcam' }}</span>
                        </div>
                    </div>
                    <button onclick="fetchSummaryData(true)" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-medium text-slate-700 transition-colors">
                        Refresh
                    </button>
                </div>

            </div>

        </div>

        <!-- Grid Baris 2: Status 4 Kompartemen Tong Sampah (Ultrasonic) -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-bold text-base text-slate-900">Kapasitas 4 Kompartemen Tempat Sampah</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Data tingkat kepenuhan dari sensor ultrasonik per wadah</p>
                </div>
                <span class="text-xs font-mono text-slate-400">Sensor Jarak Otomatis</span>
            </div>

            <!-- 4 Kolom Sederhana & Rapi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- BIN 1: ORGANIK -->
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-emerald-700">1. ORGANIK</span>
                        <span id="bin1-badge" class="text-[10px] font-semibold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                            {{ $bins[1]['status_label'] }}
                        </span>
                    </div>
                    <div class="flex items-baseline justify-between pt-1">
                        <span class="text-xs text-slate-500">Tingkat Isi:</span>
                        <span id="bin1-pct" class="text-2xl font-bold text-slate-900">{{ $bins[1]['percentage'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div id="bin1-bar" class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $bins[1]['percentage'] }}%"></div>
                    </div>
                    <div class="text-[11px] text-slate-400 flex justify-between pt-1">
                        <span>Jarak Sensor:</span>
                        <strong id="bin1-dist" class="text-slate-700">{{ $bins[1]['distance'] !== null ? $bins[1]['distance'] . ' cm' : '-- cm' }}</strong>
                    </div>
                </div>

                <!-- BIN 2: PLASTIK -->
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-blue-700">2. PLASTIK</span>
                        <span id="bin2-badge" class="text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                            {{ $bins[2]['status_label'] }}
                        </span>
                    </div>
                    <div class="flex items-baseline justify-between pt-1">
                        <span class="text-xs text-slate-500">Tingkat Isi:</span>
                        <span id="bin2-pct" class="text-2xl font-bold text-slate-900">{{ $bins[2]['percentage'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div id="bin2-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $bins[2]['percentage'] }}%"></div>
                    </div>
                    <div class="text-[11px] text-slate-400 flex justify-between pt-1">
                        <span>Jarak Sensor:</span>
                        <strong id="bin2-dist" class="text-slate-700">{{ $bins[2]['distance'] !== null ? $bins[2]['distance'] . ' cm' : '-- cm' }}</strong>
                    </div>
                </div>

                <!-- BIN 3: KERTAS -->
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-amber-700">3. KERTAS</span>
                        <span id="bin3-badge" class="text-[10px] font-semibold px-2 py-0.5 rounded bg-amber-100 text-amber-800">
                            {{ $bins[3]['status_label'] }}
                        </span>
                    </div>
                    <div class="flex items-baseline justify-between pt-1">
                        <span class="text-xs text-slate-500">Tingkat Isi:</span>
                        <span id="bin3-pct" class="text-2xl font-bold text-slate-900">{{ $bins[3]['percentage'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div id="bin3-bar" class="bg-amber-600 h-2 rounded-full transition-all duration-500" style="width: {{ $bins[3]['percentage'] }}%"></div>
                    </div>
                    <div class="text-[11px] text-slate-400 flex justify-between pt-1">
                        <span>Jarak Sensor:</span>
                        <strong id="bin3-dist" class="text-slate-700">{{ $bins[3]['distance'] !== null ? $bins[3]['distance'] . ' cm' : '-- cm' }}</strong>
                    </div>
                </div>

                <!-- BIN 4: LOGAM & KACA -->
                <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-rose-700">4. LOGAM & KACA</span>
                        <span id="bin4-badge" class="text-[10px] font-semibold px-2 py-0.5 rounded bg-rose-100 text-rose-800">
                            {{ $bins[4]['status_label'] }}
                        </span>
                    </div>
                    <div class="flex items-baseline justify-between pt-1">
                        <span class="text-xs text-slate-500">Tingkat Isi:</span>
                        <span id="bin4-pct" class="text-2xl font-bold text-slate-900">{{ $bins[4]['percentage'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div id="bin4-bar" class="bg-rose-600 h-2 rounded-full transition-all duration-500" style="width: {{ $bins[4]['percentage'] }}%"></div>
                    </div>
                    <div class="text-[11px] text-slate-400 flex justify-between pt-1">
                        <span>Jarak Sensor:</span>
                        <strong id="bin4-dist" class="text-slate-700">{{ $bins[4]['distance'] !== null ? $bins[4]['distance'] . ' cm' : '-- cm' }}</strong>
                    </div>
                </div>

            </div>
        </div>

        <!-- Grid Baris 3: Galeri Hasil Auto-Capture AI -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-bold text-base text-slate-900">Riwayat Hasil Capture Otomatis AI</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Seluruh foto tangkapan sampah otomatis yang terekam di cloud</p>
                </div>
                <span class="text-xs text-slate-400">Klik foto untuk melihat ukuran penuh</span>
            </div>

            <div id="captures-gallery-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @forelse($recentCaptures as $cap)
                    <div class="rounded-lg border border-slate-200 overflow-hidden bg-white hover:border-slate-400 transition-all cursor-pointer flex flex-col" onclick="openLightbox('{{ $cap->image_url }}', '{{ $cap->category_label }}', '{{ $cap->confidence ? number_format($cap->confidence, 1).'%' : 'AI' }}', '{{ $cap->formatted_time }}', '{{ $cap->suggestion }}')">
                        <div class="w-full aspect-square bg-slate-100 relative overflow-hidden">
                            <img src="{{ $cap->image_url }}" alt="{{ $cap->category }}" class="w-full h-full object-cover">
                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-white/90 text-slate-900 border border-slate-200">
                                {{ $cap->category }}
                            </span>
                        </div>
                        <div class="p-2 text-left">
                            <span class="text-xs font-semibold text-slate-800 block truncate">{{ $cap->category_label }}</span>
                            <span class="text-[10px] text-slate-400 font-mono block">{{ $cap->formatted_time }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                        Belum ada foto sampah yang terekam.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Grid Baris 4: Statistik Sederhana -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">Total Dipilah</span>
                <span id="stat-total-val" class="text-2xl font-bold text-slate-900 mt-1 block">{{ $stats['total'] }}</span>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">Hari Ini</span>
                <span id="stat-today-val" class="text-2xl font-bold text-emerald-600 mt-1 block">{{ $stats['today'] }}</span>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">Rata-rata Akurasi</span>
                <span id="stat-acc-val" class="text-2xl font-bold text-slate-900 mt-1 block">{{ $stats['avg_confidence'] }}%</span>
            </div>
            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">Status Servo</span>
                <span id="stat-servo-status" class="text-xs font-semibold text-slate-700 mt-2 block font-mono">S1: 90° | S2: 90°</span>
            </div>
        </div>

    </main>

    <!-- Footer Bersih -->
    <footer class="bg-white border-t border-slate-200 py-5 text-center text-xs text-slate-500 mt-auto">
        <div class="max-w-6xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>© {{ date('Y') }} SmartBin IoT. Host: <strong class="text-slate-700">bin.ihi.my.id</strong></span>
            <div class="flex items-center gap-3">
                <a href="/" class="hover:text-slate-900">Monitor Publik</a>
                <span>•</span>
                <a href="/login" class="hover:text-slate-900">Login Admin</a>
            </div>
        </div>
    </footer>

    <!-- LIGHTBOX MODAL SEDERHANA -->
    <div id="lightbox-modal" class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4 hidden" onclick="closeLightbox()">
        <div class="bg-white rounded-xl max-w-lg w-full overflow-hidden shadow-xl" onclick="event.stopPropagation()">
            <div class="p-3 border-b border-slate-200 flex items-center justify-between">
                <h4 id="modal-title" class="font-bold text-sm text-slate-900">Detail Foto</h4>
                <button onclick="closeLightbox()" class="text-slate-400 hover:text-slate-700 text-sm font-bold px-2 py-1">✕</button>
            </div>
            <div class="bg-slate-100 w-full aspect-[4/3] flex items-center justify-center overflow-hidden">
                <img id="modal-img" src="" alt="Detail" class="max-w-full max-h-full object-contain">
            </div>
            <div class="p-3 text-xs text-slate-600 space-y-1">
                <div class="flex justify-between">
                    <span>Keyakinan:</span>
                    <strong id="modal-conf" class="text-slate-900">--%</strong>
                </div>
                <div class="flex justify-between">
                    <span>Waktu:</span>
                    <span id="modal-time" class="font-mono text-slate-800">--</span>
                </div>
                <div class="pt-1 text-slate-800 font-medium" id="modal-sugg"></div>
            </div>
        </div>
    </div>

    <!-- LOGIKA LIVE CAMERA & REAL-TIME SINKRONISASI -->
    <script>
        // State
        let feedMode = 'live'; // 'live', 'browser', 'capture'
        let browserStream = null;
        let lastDetectionId = {{ $latest ? $latest->id : 0 }};

        // Jam Header
        function updateClock() {
            const now = new Date();
            document.getElementById('header-clock').textContent = now.toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Ganti Mode Kamera
        async function setFeedMode(mode) {
            feedMode = mode;
            const btnLive = document.getElementById('btn-mode-live');
            const btnBrowser = document.getElementById('btn-mode-browser');
            const btnCapture = document.getElementById('btn-mode-capture');

            const canvas = document.getElementById('camera-canvas');
            const video = document.getElementById('browser-webcam-video');
            const img = document.getElementById('capture-img-view');
            const modeLabel = document.getElementById('camera-mode-label');
            const sourceName = document.getElementById('stream-source-name');
            const browserControls = document.getElementById('browser-webcam-controls');

            // Reset tombol
            btnLive.className = 'px-3 py-1 rounded text-slate-600 hover:text-slate-900 transition-all';
            btnBrowser.className = 'px-3 py-1 rounded text-slate-600 hover:text-slate-900 transition-all flex items-center gap-1';
            btnCapture.className = 'px-3 py-1 rounded text-slate-600 hover:text-slate-900 transition-all';

            // Stop browser stream jika pindah dari browser
            if (mode !== 'browser' && browserStream) {
                browserStream.getTracks().forEach(t => t.stop());
                browserStream = null;
                video.srcObject = null;
            }

            if (mode === 'live') {
                btnLive.className = 'px-3 py-1 rounded bg-white text-slate-900 shadow-sm transition-all font-semibold';
                canvas.classList.remove('hidden');
                canvas.classList.add('block');
                video.classList.add('hidden');
                img.classList.add('hidden');
                browserControls.classList.add('hidden');

                modeLabel.textContent = 'LIVE CLOUD STREAM';
                sourceName.textContent = 'Python infer_webcam.py / ESP32';

            } else if (mode === 'browser') {
                btnBrowser.className = 'px-3 py-1 rounded bg-white text-slate-900 shadow-sm transition-all font-semibold flex items-center gap-1';
                canvas.classList.add('hidden');
                video.classList.remove('hidden');
                video.classList.add('block');
                img.classList.add('hidden');
                browserControls.classList.remove('hidden');

                modeLabel.textContent = 'KAMERA BROWSER (LOKAL)';
                sourceName.textContent = 'Webcam Perangkat Ini';

                // Nyalakan Kamera Browser
                try {
                    browserStream = await navigator.mediaDevices.getUserMedia({
                        video: { width: 640, height: 480 },
                        audio: false
                    });
                    video.srcObject = browserStream;
                } catch (err) {
                    alert('Tidak dapat membuka kamera browser: ' + err.message);
                    setFeedMode('live');
                }

            } else if (mode === 'capture') {
                btnCapture.className = 'px-3 py-1 rounded bg-white text-slate-900 shadow-sm transition-all font-semibold';
                canvas.classList.add('hidden');
                video.classList.add('hidden');
                img.classList.remove('hidden');
                img.classList.add('block');
                browserControls.classList.add('hidden');

                modeLabel.textContent = 'FOTO TERAKHIR';
                sourceName.textContent = 'Arsip AI Snapshot';
            }
        }

        // Live Canvas Video Stream Loop (No flicker) dari /camera.jpg
        const canvas = document.getElementById('camera-canvas');
        const ctx = canvas.getContext('2d');
        let frameCount = 0;
        let lastFpsUpdate = Date.now();

        function loopCanvasFrame() {
            if (feedMode !== 'live') {
                setTimeout(loopCanvasFrame, 300);
                return;
            }

            const img = new Image();
            img.crossOrigin = "Anonymous";
            img.src = '/camera.jpg?t=' + Date.now();

            img.onload = () => {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                frameCount++;
                const now = Date.now();
                if (now - lastFpsUpdate >= 1000) {
                    const fps = (frameCount / ((now - lastFpsUpdate) / 1000)).toFixed(0);
                    document.getElementById('camera-fps-pill').textContent = `${fps} FPS`;
                    frameCount = 0;
                    lastFpsUpdate = now;
                }
                setTimeout(loopCanvasFrame, 100);
            };

            img.onerror = () => {
                setTimeout(loopCanvasFrame, 200);
            };
        }
        loopCanvasFrame();

        // Scan AI langsung dari Kamera Browser (jika mode browser webcam aktif)
        async function scanBrowserWebcamNow() {
            const video = document.getElementById('browser-webcam-video');
            if (!video || !video.videoWidth) {
                alert('Kamera browser belum siap.');
                return;
            }

            const btn = document.getElementById('btn-scan-webcam');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span>Memproses AI...</span>';
            btn.disabled = true;

            try {
                // Ambil snapshot dari video
                const snapCanvas = document.createElement('canvas');
                snapCanvas.width = video.videoWidth;
                snapCanvas.height = video.videoHeight;
                const snapCtx = snapCanvas.getContext('2d');
                snapCtx.drawImage(video, 0, 0);

                const base64Data = snapCanvas.toDataURL('image/jpeg', 0.85);

                const response = await fetch('/api/captures/upload', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        image_base64: base64Data,
                        category: 'auto',
                        source: 'browser_webcam',
                    })
                });

                if (response.ok) {
                    await fetchSummaryData(true);
                } else {
                    alert('Gagal memproses AI');
                }
            } catch (err) {
                alert('Error: ' + err.message);
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        // Real-Time Polling Engine
        async function fetchSummaryData(force = false) {
            try {
                const res = await fetch('/api/public/summary');
                if (!res.ok) return;
                const data = await res.json();

                // Update Status Keaktifan Stream Kamera
                const streamBanner = document.getElementById('stream-inactive-banner');
                const liveDot = document.getElementById('live-dot');
                const modeLabel = document.getElementById('camera-mode-label');
                const syncBadge = document.getElementById('stream-sync-badge');

                if (feedMode === 'live') {
                    if (data.camera && data.camera.is_streaming) {
                        if (streamBanner) streamBanner.classList.add('hidden');
                        if (liveDot) liveDot.className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
                        if (modeLabel) modeLabel.textContent = 'LIVE STREAM AKTIF';
                        if (syncBadge) {
                            syncBadge.textContent = 'Stream Terhubung (Real-Time)';
                            syncBadge.className = 'text-emerald-600 font-semibold text-xs';
                        }
                    } else {
                        if (streamBanner) streamBanner.classList.remove('hidden');
                        if (liveDot) liveDot.className = 'w-2 h-2 rounded-full bg-amber-500';
                        if (modeLabel) modeLabel.textContent = 'FRAME TERAKHIR (STANDBY)';
                        if (syncBadge) {
                            syncBadge.textContent = 'Menunggu Stream Python/ESP32';
                            syncBadge.className = 'text-amber-600 font-medium text-xs';
                        }
                    }
                } else if (streamBanner) {
                    streamBanner.classList.add('hidden');
                }

                // 1. Update Deteksi Terakhir
                if (data.latest) {
                    document.getElementById('latest-category-name').textContent = data.latest.category_label.toUpperCase();
                    document.getElementById('latest-confidence-val').textContent = (data.latest.confidence || 98).toFixed(1) + '%';
                    document.getElementById('latest-confidence-bar').style.width = (data.latest.confidence || 95) + '%';
                    document.getElementById('latest-suggestion-text').textContent = data.latest.suggestion || '-';
                    document.getElementById('latest-servo-action').textContent = data.latest.servo_action || '-';
                    document.getElementById('latest-time-badge').textContent = data.latest.formatted_time || '-';
                    document.getElementById('latest-source-text').textContent = 'Sumber: ' + (data.latest.source || 'Webcam');
                    
                    if (data.latest.image_url) {
                        document.getElementById('latest-thumb-preview').src = data.latest.image_url;
                        document.getElementById('capture-img-view').src = data.latest.image_url;
                    }
                }

                // 2. Update 4 Ultrasonic Bins
                if (data.bins) {
                    for (let i = 1; i <= 4; i++) {
                        const bin = data.bins[i];
                        if (bin) {
                            document.getElementById(`bin${i}-pct`).textContent = bin.percentage + '%';
                            document.getElementById(`bin${i}-bar`).style.width = bin.percentage + '%';
                            document.getElementById(`bin${i}-dist`).textContent = bin.distance !== null ? bin.distance + ' cm' : '-- cm';
                            document.getElementById(`bin${i}-badge`).textContent = bin.status_label;
                        }
                    }
                }

                // 3. Update Statistik & Servos
                if (data.stats) {
                    document.getElementById('stat-total-val').textContent = data.stats.total;
                    document.getElementById('stat-today-val').textContent = data.stats.today;
                    document.getElementById('stat-acc-val').textContent = (data.stats.avg_confidence || 96.5).toFixed(1) + '%';
                }

                if (data.servos) {
                    document.getElementById('stat-servo-status').textContent = `S1: ${data.servos.servo1}° | S2: ${data.servos.servo2}°`;
                }

                // 4. Update Galeri Riwayat
                if (data.recentCaptures && data.recentCaptures.length > 0) {
                    renderGallery(data.recentCaptures);
                }

            } catch (err) {
                console.error("Gagal sinkronisasi:", err);
            }
        }

        function renderGallery(captures) {
            const grid = document.getElementById('captures-gallery-grid');
            if (!grid) return;

            let html = '';
            captures.forEach(cap => {
                const confText = cap.confidence ? Math.round(cap.confidence) + '%' : 'AI';
                html += `
                    <div class="rounded-lg border border-slate-200 overflow-hidden bg-white hover:border-slate-400 transition-all cursor-pointer flex flex-col" onclick="openLightbox('${cap.image_url}', '${cap.category_label}', '${confText}', '${cap.formatted_time}', '${cap.suggestion || ''}')">
                        <div class="w-full aspect-square bg-slate-100 relative overflow-hidden">
                            <img src="${cap.image_url}" alt="${cap.category}" class="w-full h-full object-cover">
                            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-white/90 text-slate-900 border border-slate-200">
                                ${cap.category}
                            </span>
                        </div>
                        <div class="p-2 text-left">
                            <span class="text-xs font-semibold text-slate-800 block truncate">${cap.category_label}</span>
                            <span class="text-[10px] text-slate-400 font-mono block">${cap.formatted_time}</span>
                        </div>
                    </div>
                `;
            });

            grid.innerHTML = html;
        }

        // Lightbox
        function openLightbox(imgSrc, title, conf, time, sugg) {
            const modal = document.getElementById('lightbox-modal');
            document.getElementById('modal-img').src = imgSrc;
            document.getElementById('modal-title').textContent = title || 'Detail Foto';
            document.getElementById('modal-conf').textContent = conf || '--%';
            document.getElementById('modal-time').textContent = time || '--';
            document.getElementById('modal-sugg').textContent = sugg ? 'Saran: ' + sugg : '';
            modal.classList.remove('hidden');
        }

        function closeLightbox() {
            document.getElementById('lightbox-modal').classList.add('hidden');
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLightbox();
        });

        // Polling setiap 2.5 detik
        setInterval(fetchSummaryData, 2500);
    </script>
</body>
</html>
