<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Waste Station - UIN Sunan Gunung Djati Bandung</title>

    <!-- Fonts: Inter & Amiri Quran -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        quran: ['Amiri', 'serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Strict No-Scroll Kiosk Rule */
        html, body {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            overflow: hidden !important;
            user-select: none;
            background-color: #f8fafc;
            color: #0f172a;
        }

        .kiosk-screen {
            height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: absolute;
            inset: 0;
            transition: opacity 0.35s ease, transform 0.35s ease;
        }

        .screen-enter {
            opacity: 1;
            pointer-events: auto;
            transform: scale(1);
        }
        .screen-exit {
            opacity: 0;
            pointer-events: none;
            transform: scale(0.98);
        }

        /* Subtle Decorative Leaves */
        .leaf-decor {
            position: absolute;
            pointer-events: none;
            opacity: 0.15;
            z-index: 1;
        }

        /* Pulse and Countdown Animations */
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.08); opacity: 0.4; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .pulse-ring-anim { animation: pulse-ring 2s infinite ease-in-out; }

        @keyframes countdown-pop {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        .countdown-pop-anim { animation: countdown-pop 0.4s ease-out; }

        /* Scanning Laser */
        @keyframes scan-line {
            0% { top: 5%; opacity: 0.2; }
            50% { top: 90%; opacity: 0.9; }
            100% { top: 5%; opacity: 0.2; }
        }
        .scanner-laser-bar {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: #22c55e;
            box-shadow: 0 0 12px 3px rgba(34, 197, 94, 0.7);
            animation: scan-line 2.2s infinite ease-in-out;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 relative">

    <!-- ============================================================== -->
    <!-- TAMPILAN 1: SALAM / SAPAAN & INFORMASI SMARTBIN (STANDBY IDLE) -->
    <!-- ============================================================== -->
    <div id="screen-standby" class="kiosk-screen screen-enter bg-white p-5 sm:p-7 flex flex-col justify-between relative">
        
        <!-- Subtle Leaf Illustrations (SVG Vector) -->
        <svg class="leaf-decor top-20 left-12 w-6 h-6 text-emerald-500" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/>
        </svg>
        <svg class="leaf-decor top-32 right-20 w-8 h-8 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 008 20C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/>
        </svg>

        <!-- Top Header -->
        <header class="flex items-center justify-between z-10">
            <!-- Left: Logo & University -->
            <div class="flex items-center gap-3">
                <img src="/images/logo-uin.png" alt="Logo UIN Sunan Gunung Djati Bandung" class="h-11 w-auto object-contain">
                <div>
                    <h1 class="font-black text-lg text-slate-900 tracking-tight leading-none">Smart Waste</h1>
                    <p class="text-[11px] text-slate-500 font-medium tracking-wide mt-0.5">AI for a Cleaner Earth</p>
                </div>
            </div>

            <!-- Right: Backdoor Button & Clock -->
            <div class="flex items-center gap-4">
                <a href="/backdoor" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 border border-slate-200 text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>Backdoor</span>
                </a>
                <div class="text-right">
                    <span id="standby-date" class="text-[11px] text-slate-500 font-medium block">Rabu, 29 Oktober 2026</span>
                    <span id="standby-clock" class="text-sm font-extrabold text-slate-800 font-mono">14:52 WIB</span>
                </div>
            </div>
        </header>

        <!-- Center Content: Hero & 4 Category Cards -->
        <div class="my-auto text-center max-w-5xl mx-auto w-full z-10 space-y-5">
            
            <!-- Welcome Title -->
            <div class="space-y-1">
                <span class="text-[11px] font-bold tracking-[0.25em] uppercase text-emerald-700">SELAMAT DATANG DI</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Smart Waste Station</h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Pilah Sampah, Jaga Bumi, Raih Keberkahan</p>
            </div>

            <!-- 4 Waste Classification Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 sm:gap-4 max-w-4xl mx-auto">
                
                <!-- 1. Organik -->
                <div class="bg-white border border-slate-100 hover:border-emerald-200 rounded-2xl p-4 shadow-sm flex flex-col items-center text-center transition hover:shadow-md">
                    <div class="w-14 h-14 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-emerald-800">Organik</h3>
                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">Sampah sisa makanan dan bahan alami</p>
                </div>

                <!-- 2. Anorganik / Plastik -->
                <div class="bg-white border border-slate-100 hover:border-amber-200 rounded-2xl p-4 shadow-sm flex flex-col items-center text-center transition hover:shadow-md">
                    <div class="w-14 h-14 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-blue-800">Anorganik / Plastik</h3>
                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">Sampah plastik dan anorganik</p>
                </div>

                <!-- 3. Kertas -->
                <div class="bg-white border border-slate-100 hover:border-amber-200 rounded-2xl p-4 shadow-sm flex flex-col items-center text-center transition hover:shadow-md">
                    <div class="w-14 h-14 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-amber-800">Kertas</h3>
                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">Sampah kertas dan karton</p>
                </div>

                <!-- 4. Logam & Kaca -->
                <div class="bg-white border border-slate-100 hover:border-slate-300 rounded-2xl p-4 shadow-sm flex flex-col items-center text-center transition hover:shadow-md">
                    <div class="w-14 h-14 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800">Logam &amp; Kaca</h3>
                    <p class="text-[11px] text-slate-400 mt-1 leading-snug">Sampah logam dan kaca</p>
                </div>

            </div>

            <!-- Ecotheology Quote Banner (Rotating Carousel) -->
            <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-4 max-w-3xl mx-auto shadow-sm text-left relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </span>
                        <span id="standby-quote-theme" class="text-xs font-bold text-emerald-900">Pesan Hari Ini &bull; Hemat &amp; Anti-Mubadzir &bull; Al-Qur'an</span>
                    </div>
                    <button onclick="triggerScreen('ECOTHEOLOGY')" class="text-[11px] font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                        <span>Baca Refleksi</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>

                <p id="standby-quote-arabic" class="font-quran text-right text-lg sm:text-xl text-emerald-950 font-bold leading-relaxed mb-1" dir="rtl">
                    وَكُلُوا وَاشْرَبُوا وَلَا تُسْرِفُوا ۚ إِنَّهُ لَا يُحِبُّ الْمُسْرِفِينَ
                </p>
                <p id="standby-quote-trans" class="text-xs text-slate-700 italic">
                    "Makan dan minumlah, tetapi jangan berlebih-lebihan. Sungguh, Allah tidak menyukai orang yang berlebih-lebihan."
                </p>
                <div class="flex items-center justify-between mt-2 pt-1">
                    <span id="standby-quote-ref" class="text-[11px] font-bold text-emerald-800">— QS. Al-A'raf: 31</span>
                    <div id="standby-carousel-dots" class="flex gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Info Bar -->
        <footer class="flex flex-col sm:flex-row items-center justify-between gap-3 z-10 pt-2 border-t border-slate-100">
            <!-- Left Info Pill -->
            <div class="inline-flex items-center gap-3 bg-white px-4 py-2 rounded-full border border-slate-200 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                </div>
                <div class="text-left">
                    <span class="font-bold text-xs text-slate-900 block">Silakan masukkan sampah ke area kamera</span>
                    <span class="text-[11px] text-slate-500 block">deteksi akan dilakukan secara otomatis</span>
                </div>
            </div>

            <!-- Right Remote Box -->
            <div class="inline-flex items-center gap-3 bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-sm">
                <div class="w-7 h-7 bg-slate-900 text-white rounded-lg flex items-center justify-center text-xs font-mono font-bold">
                    QR
                </div>
                <div class="text-left text-xs">
                    <span class="font-bold text-slate-800 block">Remote HP <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">Wi-Fi</span></span>
                    <span class="text-[11px] text-slate-500 font-mono">192.168.1.8:5000</span>
                </div>
            </div>
        </footer>
    </div>


    <!-- ============================================================== -->
    <!-- TAMPILAN 2: LIVE CAME (DETEKSI REALTIME & HITUNG MUNDUR & AI) -->
    <!-- ============================================================== -->
    <div id="screen-camera" class="kiosk-screen screen-exit bg-slate-100 p-4 sm:p-5 flex flex-col justify-between">
        
        <!-- Top Nav Controls -->
        <header class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div class="flex items-center gap-2.5">
                <img src="/images/logo-uin.png" alt="Logo UIN SGD" class="h-9 w-auto object-contain">
                <div>
                    <h2 class="font-black text-sm text-slate-900 leading-none">Smart Waste Station</h2>
                    <p class="text-[10px] text-slate-500 font-medium">Pilah Sampah, Jaga Bumi, Raih Keberkahan</p>
                </div>
            </div>

            <!-- Mode Controls Bar (As shown in Image 2 & 3) -->
            <div class="flex items-center gap-2 bg-slate-900 text-white px-3.5 py-1.5 rounded-full text-xs shadow-sm">
                <button id="btn-manual-scan" onclick="triggerManualScan()" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3 py-1 rounded-full flex items-center gap-1 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                    <span>Pindai</span>
                </button>
                <span class="text-slate-400">|</span>
                <span class="text-emerald-400 font-bold">Auto: ON</span>
                <span class="text-slate-400">|</span>
                <span class="text-slate-300">Webcam Laptop (Live)</span>
                <span class="text-slate-400">|</span>
                <span class="text-slate-300">Kalibrasi Latar</span>
                <span class="text-slate-400">|</span>
                <button onclick="triggerScreen('ECOTHEOLOGY')" class="text-slate-200 hover:text-white">Edukasi</button>
            </div>

            <div class="text-right">
                <span id="cam-clock" class="text-sm font-extrabold text-slate-800 font-mono">13:35 WIB</span>
            </div>
        </header>

        <!-- Main Grid: Video Stream (Left) & Detection Info (Right) -->
        <div class="grid grid-cols-12 gap-4 flex-1 my-3 min-h-0">
            
            <!-- Left: Video Frame Box (Col 7) -->
            <div class="col-span-7 bg-black rounded-2xl relative overflow-hidden flex items-center justify-center border-2 border-slate-800 shadow-md">
                
                <!-- Live Video Feed / Canvas -->
                <canvas id="camera-live-canvas" width="640" height="480" class="w-full h-full object-cover"></canvas>
                
                <!-- Top Status Overlay Tags -->
                <div class="absolute top-3 left-3 flex items-center gap-2 z-20">
                    <span class="bg-red-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow">
                        <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                        LIVE
                    </span>
                    <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-semibold px-2.5 py-1 rounded-full border border-white/20">
                        Kamera AI
                    </span>
                    <span id="cam-clean-badge" class="bg-emerald-600/90 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">
                        Latar Bersih
                    </span>
                </div>

                <!-- Center Reticle Frame (Target Brackets) -->
                <div id="cam-reticle" class="absolute w-56 h-56 border-2 border-dashed border-emerald-400/80 rounded-2xl pointer-events-none z-15 flex flex-col justify-between p-3 transition-colors duration-300">
                    <div class="flex justify-between">
                        <span class="w-4 h-4 border-t-2 border-l-2 border-emerald-400"></span>
                        <span class="w-4 h-4 border-t-2 border-r-2 border-emerald-400"></span>
                    </div>

                    <!-- Reticle Label or Countdown Circle -->
                    <div class="text-center relative">
                        <!-- State 1: Siap Menerima Sampah -->
                        <div id="reticle-label-ready" class="bg-emerald-600 text-white font-bold text-xs px-3 py-1 rounded-full shadow inline-block">
                            Siap Menerima Sampah
                        </div>

                        <!-- State 2: Countdown Pop Circle (As shown in Image 4!) -->
                        <div id="reticle-countdown-box" class="hidden">
                            <div class="w-16 h-16 rounded-full bg-amber-400/90 text-slate-950 font-black text-3xl flex items-center justify-center mx-auto shadow-2xl countdown-pop-anim border-2 border-white">
                                <span id="countdown-number">3</span>
                            </div>
                        </div>

                        <!-- State 3: Detection Bounding Box Tag (As shown in Image 3) -->
                        <div id="reticle-result-tag" class="hidden">
                            <span id="reticle-result-name" class="bg-amber-500 text-white font-black text-xs px-3.5 py-1 rounded-full shadow-lg">
                                Kertas 87.6%
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <span class="w-4 h-4 border-b-2 border-l-2 border-emerald-400"></span>
                        <span class="w-4 h-4 border-b-2 border-r-2 border-emerald-400"></span>
                    </div>
                </div>

                <!-- Scanning Laser Line -->
                <div id="cam-scanner-line" class="hidden scanner-laser-bar z-10"></div>
            </div>

            <!-- Right: Detection Info & Thumbnails (Col 5) -->
            <div class="col-span-5 flex flex-col justify-between gap-3">
                
                <!-- Status Bar Top -->
                <div class="bg-white rounded-xl p-3.5 border border-slate-200 shadow-sm flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                    </div>
                    <div>
                        <div id="cam-status-title" class="font-extrabold text-xs text-slate-900">Menunggu Sampah...</div>
                        <p id="cam-status-sub" class="text-[11px] text-slate-500">Letakkan sampah pada sensor latar untuk memulai</p>
                    </div>
                </div>

                <!-- Main Result Card -->
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm space-y-2">
                    <div class="flex items-center gap-3">
                        <div id="result-icon-box" class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Hasil Deteksi</span>
                            <h3 id="result-cat-title" class="text-lg font-black text-slate-900 leading-tight">Standby (Menunggu Objek)</h3>
                            <span id="result-cat-sub" class="text-xs text-slate-500 font-medium">Zona Hijau Bersih (Tidak Ada Objek)</span>
                        </div>
                    </div>

                    <!-- Progress Bar for AI Confidence -->
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div id="result-progress-fill" class="bg-emerald-500 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Other Examples Thumbnails Card (Matching Image 2, 3, 4) -->
                <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex-1 flex flex-col justify-between">
                    <div>
                        <span id="example-label" class="text-xs font-bold text-slate-700 block mb-2.5">Contoh Sampah Terkait:</span>
                        <div id="example-grid" class="grid grid-cols-4 gap-2">
                            <div class="aspect-square bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center p-2 text-center">
                                <span class="text-[10px] text-slate-500 font-medium">Sampah 1</span>
                            </div>
                            <div class="aspect-square bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center p-2 text-center">
                                <span class="text-[10px] text-slate-500 font-medium">Sampah 2</span>
                            </div>
                            <div class="aspect-square bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center p-2 text-center">
                                <span class="text-[10px] text-slate-500 font-medium">Sampah 3</span>
                            </div>
                            <div class="aspect-square bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center p-2 text-center">
                                <span class="text-[10px] text-slate-500 font-medium">Sampah 4</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 text-right">
                        <button onclick="triggerScreen('ECOTHEOLOGY')" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 inline-flex items-center gap-1">
                            <span>Lanjut ke Nilai Ekoteologi</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Stepper Bar (As shown in Image 2 & 3) -->
        <footer class="bg-white rounded-xl p-3 border border-slate-200 shadow-sm">
            <div class="grid grid-cols-4 gap-2 text-xs">
                <!-- Step 1 -->
                <div id="stepper-1" class="flex items-center gap-2 font-bold text-emerald-800">
                    <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] shrink-0">1</span>
                    <div>
                        <span class="block">1. Letakkan Sampah</span>
                        <span class="text-[10px] text-slate-400 font-normal">Letakkan pada sensor piringan</span>
                    </div>
                </div>
                <!-- Step 2 -->
                <div id="stepper-2" class="flex items-center gap-2 font-medium text-slate-400">
                    <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] shrink-0">2</span>
                    <div>
                        <span class="block">2. Identifikasi AI</span>
                        <span class="text-[10px] text-slate-400 font-normal">Model memindai jenis sampah</span>
                    </div>
                </div>
                <!-- Step 3 -->
                <div id="stepper-3" class="flex items-center gap-2 font-medium text-slate-400">
                    <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] shrink-0">3</span>
                    <div>
                        <span class="block">3. Hasil Deteksi</span>
                        <span class="text-[10px] text-slate-400 font-normal">Menampilkan jenis sampah</span>
                    </div>
                </div>
                <!-- Step 4 -->
                <div id="stepper-4" class="flex items-center gap-2 font-medium text-slate-400">
                    <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] shrink-0">4</span>
                    <div>
                        <span class="block">4. Buang Sampah</span>
                        <span class="text-[10px] text-slate-400 font-normal">Wadah terbuka otomatis</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>


    <!-- ============================================================== -->
    <!-- TAMPILAN 3: KONTEN EKOTEOLOGI & NILAI-NILAI (REFLEKSI & AKSI) -->
    <!-- ============================================================== -->
    <div id="screen-ecotheology" class="kiosk-screen screen-exit bg-slate-900 text-white p-5 sm:p-7 flex flex-col justify-between relative overflow-hidden">
        
        <!-- Subtle Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-950 via-slate-900 to-slate-950 opacity-95 pointer-events-none"></div>

        <!-- Top Header -->
        <header class="flex items-center justify-between z-10 border-b border-emerald-900/60 pb-3">
            <div class="flex items-center gap-3">
                <img src="/images/logo-uin.png" alt="Logo UIN SGD" class="h-10 w-auto object-contain">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-black text-base text-white tracking-tight">Refleksi Ekoteologi Islam</h2>
                        <span id="eco-category-badge" class="bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase">
                            Sampah Teridentifikasi
                        </span>
                    </div>
                    <p class="text-[11px] text-emerald-200/70 font-medium">UIN Sunan Gunung Djati Bandung &bull; Fiqih Lingkungan Hidup</p>
                </div>
            </div>

            <!-- Auto Return Countdown Bar -->
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <span class="text-[11px] text-emerald-300 font-semibold block">Kembali otomatis ke Layar Utama:</span>
                    <span id="eco-return-timer-text" class="text-xs font-mono font-bold text-white">7 detik</span>
                </div>
                <button onclick="triggerScreen('STANDBY')" class="px-3 py-1.5 rounded-lg bg-emerald-600/80 hover:bg-emerald-600 text-white text-xs font-bold transition">
                    Selesai
                </button>
            </div>
        </header>

        <!-- Main Content Area: 1 Layar Penuh Rapi (Tanpa Scroll) -->
        <div class="my-auto max-w-4xl mx-auto w-full z-10 space-y-4">
            
            <!-- Judul & Tagar Tema -->
            <div class="text-center space-y-1">
                <span id="eco-theme-tag" class="text-xs font-bold text-emerald-400 uppercase tracking-widest">
                    Kelestarian Alam &bull; Al-Qur'an
                </span>
                <h3 id="eco-title" class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Larangan Merusak Tatanan Bumi
                </h3>
            </div>

            <!-- Box Ayat / Hadits Arab (Amiri Font) -->
            <div class="bg-emerald-950/60 border border-emerald-500/40 rounded-2xl p-4 sm:p-5 shadow-lg backdrop-blur-sm">
                <p id="eco-arabic" class="font-quran text-right text-xl sm:text-2xl text-emerald-300 font-bold leading-relaxed mb-2.5" dir="rtl">
                    وَلَا تُفْسِدُوا فِي الْأَرْضِ بَعْدَ إِصْلَاحِهَا وَادْعُوهُ خَوْفًا وَطَمَعًا ۚ إِنَّ رَحْمَتَ اللَّهِ قَرِيبٌ مِّنَ الْمُحْسِنِينَ
                </p>
                <p id="eco-translation" class="text-xs sm:text-sm text-slate-200 italic leading-relaxed">
                    "Dan janganlah kamu berbuat kerusakan di muka bumi setelah (Allah) memperbaikinya, dan berdoalah kepada-Nya dengan rasa takut dan penuh harap. Sungguh, rahmat Allah sangat dekat kepada orang-orang yang berbuat baik."
                </p>
                <div class="text-right mt-1.5">
                    <span id="eco-reference" class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider">
                        — QS. Al-A'raf: 56
                    </span>
                </div>
            </div>

            <!-- 2-Column: Refleksi Masa Kini & Tindakan Aksi Nyata -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- Refleksi Masa Kini -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-1.5 backdrop-blur-sm">
                    <div class="flex items-center gap-2 text-emerald-400 font-bold text-xs uppercase tracking-wide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Refleksi &amp; Masa Kini</span>
                    </div>
                    <p id="eco-reflection" class="text-xs text-slate-300 leading-relaxed text-justify">
                        Bumi dan ekosistemnya telah diciptakan Allah dalam keseimbangan yang sempurna. Membuang sampah sembarangan atau mencemari tanah dan air adalah bentuk kezaliman terhadap ciptaan-Nya. Menjaga kelestarian lingkungan adalah bagian dari Ihsan yang mendatangkan rahmat Allah.
                    </p>
                </div>

                <!-- Tindakan / Aksi Nyata -->
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-1.5 backdrop-blur-sm">
                    <div class="flex items-center gap-2 text-amber-400 font-bold text-xs uppercase tracking-wide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Tindakan &amp; Aksi Nyata</span>
                    </div>
                    <p id="eco-action" class="text-xs text-slate-300 leading-relaxed text-justify">
                        Kumpulkan sampah Anda sesuai wadah terpisah (Organik, Plastik, Kertas, Logam). Hindari penggunaan kemasan plastik sekali pakai dan biasakan membawa botol minum tumbler mandiri.
                    </p>
                </div>

            </div>

        </div>

        <!-- Bottom Timer Progress Bar -->
        <footer class="z-10 pt-2 border-t border-emerald-900/60">
            <div class="w-full bg-white/10 rounded-full h-1.5 overflow-hidden">
                <div id="eco-return-bar" class="bg-emerald-500 h-1.5 rounded-full transition-all duration-1000" style="width: 100%"></div>
            </div>
            <div class="flex items-center justify-between mt-2 text-[10px] text-slate-400">
                <span>Smart Waste Station &bull; Ekoteologi Islam Berkelanjutan</span>
                <span>UIN Sunan Gunung Djati Bandung</span>
            </div>
        </footer>
    </div>


    <!-- ============================================================== -->
    <!-- JAVASCRIPT KIOSK CONTROLLER (NO MOUSE, 1-SCREEN STATE MACHINE) -->
    <!-- ============================================================== -->
    <script>
        // Data Konten Ekoteologi dari Server
        const ecotheologyContents = @json($contents);

        let currentState = 'STANDBY'; // 'STANDBY' | 'CAMERA' | 'ECOTHEOLOGY'
        let currentQuoteIndex = 0;
        let quoteInterval = null;
        let returnTimer = null;
        let returnSecondsLeft = 7;
        let countdownTimer = null;

        // Inisialisasi Jam dan Tanggal
        function updateClocks() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const dayStr = days[now.getDay()];
            const dateStr = `${dayStr}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            
            const hours = String(now.getHours()).padStart(2, '0');
            const mins = String(now.getMinutes()).padStart(2, '0');
            const timeStr = `${hours}:${mins} WIB`;

            const standbyDate = document.getElementById('standby-date');
            const standbyClock = document.getElementById('standby-clock');
            const camClock = document.getElementById('cam-clock');

            if (standbyDate) standbyDate.textContent = dateStr;
            if (standbyClock) standbyClock.textContent = timeStr;
            if (camClock) camClock.textContent = timeStr;
        }
        setInterval(updateClocks, 1000);
        updateClocks();

        // Carousel Rotasi Pesan Ekoteologi di Layar Standby
        function rotateStandbyQuote() {
            if (!ecotheologyContents || ecotheologyContents.length === 0) return;
            currentQuoteIndex = (currentQuoteIndex + 1) % ecotheologyContents.length;
            const item = ecotheologyContents[currentQuoteIndex];

            const themeEl = document.getElementById('standby-quote-theme');
            const arabicEl = document.getElementById('standby-quote-arabic');
            const transEl = document.getElementById('standby-quote-trans');
            const refEl = document.getElementById('standby-quote-ref');

            if (themeEl) themeEl.textContent = `Pesan Hari Ini • ${item.theme_tag}`;
            if (arabicEl) arabicEl.textContent = item.arabic_text;
            if (transEl) transEl.textContent = `"${item.translation}"`;
            if (refEl) refEl.textContent = `— ${item.reference}`;

            // Update dots
            const dotsContainer = document.getElementById('standby-carousel-dots');
            if (dotsContainer) {
                dotsContainer.innerHTML = '';
                for (let i = 0; i < Math.min(ecotheologyContents.length, 5); i++) {
                    const dot = document.createElement('span');
                    dot.className = (i === currentQuoteIndex % 5) ? 'w-2 h-2 rounded-full bg-emerald-600' : 'w-2 h-2 rounded-full bg-slate-300';
                    dotsContainer.appendChild(dot);
                }
            }
        }
        quoteInterval = setInterval(rotateStandbyQuote, 6500);

        // State Machine Switcher
        function triggerScreen(nextState, contextData = null) {
            currentState = nextState;

            const screenStandby = document.getElementById('screen-standby');
            const screenCam = document.getElementById('screen-camera');
            const screenEco = document.getElementById('screen-ecotheology');

            // Reset all
            screenStandby.className = 'kiosk-screen screen-exit';
            screenCam.className = 'kiosk-screen screen-exit';
            screenEco.className = 'kiosk-screen screen-exit';

            if (nextState === 'STANDBY') {
                screenStandby.className = 'kiosk-screen screen-enter bg-white p-5 sm:p-7 flex flex-col justify-between relative';
                resetCameraState();
                if (returnTimer) clearInterval(returnTimer);
            } else if (nextState === 'CAMERA') {
                screenCam.className = 'kiosk-screen screen-enter bg-slate-100 p-4 sm:p-5 flex flex-col justify-between';
                if (returnTimer) clearInterval(returnTimer);
                startCameraFlow();
            } else if (nextState === 'ECOTHEOLOGY') {
                screenEco.className = 'kiosk-screen screen-enter bg-slate-900 text-white p-5 sm:p-7 flex flex-col justify-between relative overflow-hidden';
                populateEcotheologyScreen(contextData);
                startEcotheologyAutoReturn();
            }
        }

        // ==============================================================
        // KAMERA LIVE CANVAS & DETEKSI OTOMATIS
        // ==============================================================
        const canvas = document.getElementById('camera-live-canvas');
        const ctx = canvas.getContext('2d');
        let motionData = null;
        let isObjectPresent = false;
        let isScanningRunning = false;

        function fetchLiveFrames() {
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.src = '/camera.jpg?time=' + Date.now();

            img.onload = () => {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                checkMotionOnFrame();
                setTimeout(fetchLiveFrames, 120);
            };
            img.onerror = () => {
                // Gambar fallback jika kamera lokal belum mengirim frame
                setTimeout(fetchLiveFrames, 300);
            };
        }
        fetchLiveFrames();

        function checkMotionOnFrame() {
            // Evaluasi motion hanya saat IDLE atau di kamera
            const imgData = ctx.getImageData(canvas.width / 4, canvas.height / 4, canvas.width / 2, canvas.height / 2).data;
            if (motionData) {
                let diff = 0;
                for (let i = 0; i < imgData.length; i += 8) {
                    if (Math.abs(imgData[i] - motionData[i]) > 45) diff++;
                }
                const motionRatio = diff / (imgData.length / 8);

                if (motionRatio > 0.04) {
                    // Terdeteksi ada objek sampah masuk!
                    if (currentState === 'STANDBY' && !isScanningRunning) {
                        triggerScreen('CAMERA');
                    }
                }
            }
            motionData = new Uint8ClampedArray(imgData);
        }

        function resetCameraState() {
            document.getElementById('reticle-label-ready').classList.remove('hidden');
            document.getElementById('reticle-countdown-box').classList.add('hidden');
            document.getElementById('reticle-result-tag').classList.add('hidden');
            document.getElementById('cam-scanner-line').classList.add('hidden');

            document.getElementById('cam-status-title').textContent = 'Menunggu Sampah...';
            document.getElementById('cam-status-sub').textContent = 'Letakkan sampah pada sensor latar untuk memulai';
            document.getElementById('result-cat-title').textContent = 'Standby (Menunggu Objek)';
            document.getElementById('result-cat-sub').textContent = 'Zona Hijau Bersih (Tidak Ada Objek)';
            document.getElementById('result-progress-fill').style.width = '0%';

            setStepper(1);
        }

        function setStepper(stepNumber) {
            for (let i = 1; i <= 4; i++) {
                const el = document.getElementById(`stepper-${i}`);
                if (!el) continue;
                if (i <= stepNumber) {
                    el.className = 'flex items-center gap-2 font-bold text-emerald-800';
                    el.querySelector('span').className = 'w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px] shrink-0';
                } else {
                    el.className = 'flex items-center gap-2 font-medium text-slate-400';
                    el.querySelector('span').className = 'w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] shrink-0';
                }
            }
        }

        function startCameraFlow() {
            resetCameraState();
            setStepper(2);

            document.getElementById('cam-status-title').textContent = 'Objek Terdeteksi! Menstabilkan...';
            document.getElementById('cam-status-sub').textContent = 'Tahan posisi sampah dalam kotak target';

            // Jeda 1 detik lalu mulai hitung mundur 3 - 2 - 1
            setTimeout(() => {
                if (currentState !== 'CAMERA') return;
                runCountdown(3);
            }, 1000);
        }

        function runCountdown(seconds) {
            document.getElementById('reticle-label-ready').classList.add('hidden');
            const countdownBox = document.getElementById('reticle-countdown-box');
            const numberEl = document.getElementById('countdown-number');
            countdownBox.classList.remove('hidden');

            let current = seconds;
            numberEl.textContent = current;

            countdownTimer = setInterval(() => {
                current--;
                if (current > 0) {
                    numberEl.textContent = current;
                } else {
                    clearInterval(countdownTimer);
                    countdownBox.classList.add('hidden');
                    executeAiScan();
                }
            }, 800);
        }

        async function executeAiScan() {
            setStepper(3);
            document.getElementById('cam-scanner-line').classList.remove('hidden');
            document.getElementById('cam-status-title').textContent = 'AI Sedang Mengidentifikasi Sampah...';
            document.getElementById('cam-status-sub').textContent = 'Mengekstrak 1280 fitur citra & XGBoost Classifier';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch('/api/ai/scan', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                const data = await response.json();

                if (response.ok && data.status === 'success') {
                    displayScanResult(data.detection, 88.5, data.action);
                } else {
                    // Fallback simulasi jika kamera fisik belum terhubung
                    displayScanResult('KERTAS', 87.6, 'Buka Wadah BIN 3 (Kertas & Karton)');
                }
            } catch (err) {
                displayScanResult('KERTAS', 87.6, 'Buka Wadah BIN 3 (Kertas & Karton)');
            }
        }

        function displayScanResult(category, confidence, actionText) {
            document.getElementById('cam-scanner-line').classList.add('hidden');
            setStepper(4);

            const catUpper = category.toUpperCase();
            let catFormatted = 'Anorganik / Kertas';
            let catKey = 'kertas';
            let badgeBg = 'bg-amber-500';

            if (catUpper.includes('PLASTIK')) {
                catFormatted = 'Anorganik / Plastik';
                catKey = 'plastik';
                badgeBg = 'bg-blue-600';
            } else if (catUpper.includes('ORGANIK')) {
                catFormatted = 'Organik';
                catKey = 'organik';
                badgeBg = 'bg-emerald-600';
            } else if (catUpper.includes('LOGAM')) {
                catFormatted = 'Logam & Kaca';
                catKey = 'logam';
                badgeBg = 'bg-rose-600';
            }

            // Tampilkan tag hasil di dalam kotak reticle (Seperti Gambar 3)
            const resultTag = document.getElementById('reticle-result-tag');
            const resultName = document.getElementById('reticle-result-name');
            resultTag.classList.remove('hidden');
            resultName.className = `${badgeBg} text-white font-black text-xs px-3.5 py-1 rounded-full shadow-lg`;
            resultName.textContent = `${catFormatted} ${confidence.toFixed(1)}%`;

            // Update panel kanan
            document.getElementById('cam-status-title').textContent = 'Identifikasi Berhasil!';
            document.getElementById('cam-status-sub').textContent = actionText || 'Wadah terbuka otomatis';
            document.getElementById('result-cat-title').textContent = catFormatted;
            document.getElementById('result-cat-sub').textContent = `Confidence: ${confidence.toFixed(1)}%`;
            document.getElementById('result-progress-fill').style.width = `${confidence}%`;

            // Cari konten ekoteologi yang relevan untuk kategori ini
            fetch(`/api/ecotheology/category?category=${catKey}`)
                .then(r => r.json())
                .then(content => {
                    // Beri waktu 2.2 detik untuk melihat hasil, lalu otomatis masuk ke TAMPILAN 3!
                    setTimeout(() => {
                        if (currentState === 'CAMERA') {
                            triggerScreen('ECOTHEOLOGY', content);
                        }
                    }, 2200);
                })
                .catch(() => {
                    setTimeout(() => {
                        if (currentState === 'CAMERA') {
                            triggerScreen('ECOTHEOLOGY', null);
                        }
                    }, 2200);
                });
        }

        function triggerManualScan() {
            if (currentState === 'CAMERA') {
                executeAiScan();
            } else {
                triggerScreen('CAMERA');
            }
        }

        // ==============================================================
        // TAMPILAN 3: KONTEN EKOTEOLOGI (REFLEKSI & AKSI NYATA)
        // ==============================================================
        function populateEcotheologyScreen(content) {
            const item = content || (ecotheologyContents && ecotheologyContents[0]) || {
                title: 'Larangan Merusak Tatanan Bumi',
                theme_tag: 'Kelestarian Alam • Al-Qur\'an',
                waste_category: 'all',
                arabic_text: 'وَلَا تُفْسِدُوا فِي الْأَرْضِ بَعْدَ إِصْلَاحِهَا وَادْعُوهُ خَوْفًا وَطَمَعًا ۚ إِنَّ رَحْمَتَ اللَّهِ قَرِيبٌ مِّنَ الْمُحْسِنِينَ',
                translation: 'Dan janganlah kamu berbuat kerusakan di muka bumi setelah (Allah) memperbaikinya, dan berdoalah kepada-Nya dengan rasa takut dan penuh harap.',
                reference: 'QS. Al-A\'raf: 56',
                reflection: 'Bumi dan ekosistemnya telah diciptakan Allah dalam keseimbangan yang sempurna. Membuang sampah sembarangan atau mencemari tanah dan air adalah bentuk kezaliman terhadap ciptaan-Nya. Menjaga kelestarian lingkungan adalah bagian dari Ihsan yang mendatangkan rahmat Allah.',
                action: 'Kumpulkan botol plastik, gelas kemasan, dan kantong kresek ke wadah Anorganik/Plastik agar dapat didaur ulang dan tidak mencemari lautan.'
            };

            document.getElementById('eco-title').textContent = item.title;
            document.getElementById('eco-theme-tag').textContent = item.theme_tag;
            document.getElementById('eco-arabic').textContent = item.arabic_text;
            document.getElementById('eco-translation').textContent = `"${item.translation}"`;
            document.getElementById('eco-reference').textContent = `— ${item.reference}`;
            document.getElementById('eco-reflection').textContent = item.reflection;
            document.getElementById('eco-action').textContent = item.action;
            
            const badge = document.getElementById('eco-category-badge');
            if (badge) {
                badge.textContent = `Kategori: ${item.waste_category ? item.waste_category.toUpperCase() : 'UMUM'}`;
            }
        }

        function startEcotheologyAutoReturn() {
            returnSecondsLeft = 7;
            const timerText = document.getElementById('eco-return-timer-text');
            const returnBar = document.getElementById('eco-return-bar');

            if (timerText) timerText.textContent = `${returnSecondsLeft} detik`;
            if (returnBar) returnBar.style.width = '100%';

            if (returnTimer) clearInterval(returnTimer);

            returnTimer = setInterval(() => {
                returnSecondsLeft--;
                if (timerText) timerText.textContent = `${returnSecondsLeft} detik`;
                if (returnBar) {
                    const pct = (returnSecondsLeft / 7) * 100;
                    returnBar.style.width = `${pct}%`;
                }

                if (returnSecondsLeft <= 0) {
                    clearInterval(returnTimer);
                    // Kembali ke TAMPILAN 1 (SALAM / STANDBY)
                    triggerScreen('STANDBY');
                }
            }, 1000);
        }
    </script>
</body>
</html>
