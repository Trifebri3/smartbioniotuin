<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edukasi Ekoteologi Islam & SmartBin AI - UIN Sunan Gunung Djati Bandung</title>
    
    <!-- Fonts: Inter & Amiri Quran -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-quran { font-family: 'Amiri', serif; }
        .bg-pattern {
            background-image: radial-gradient(rgba(16, 185, 129, 0.08) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }
        @keyframes float-gentle {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .animate-float { animation: float-gentle 4s ease-in-out infinite; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen bg-pattern" x-data="educationApp()">

    <!-- Header Navigasi Resmi UIN SGD -->
    <header class="bg-white/95 backdrop-blur-md border-b border-emerald-100 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <a href="/" class="flex items-center gap-3">
                    <img src="/images/logo-uin.png" alt="Logo UIN Sunan Gunung Djati Bandung" class="h-12 w-auto object-contain drop-shadow-sm">
                    <div class="border-l border-slate-200 pl-3">
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight">SmartBin AI IoT</span>
                            <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Ekoteologi</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">UIN Sunan Gunung Djati Bandung</p>
                    </div>
                </a>
            </div>

            <!-- Top Navigation -->
            <nav class="flex items-center gap-1.5 sm:gap-3 text-xs sm:text-sm font-semibold">
                <a href="/" class="text-slate-600 hover:text-emerald-700 px-2.5 py-1.5 rounded-lg transition hover:bg-slate-50">Monitoring</a>
                <a href="/auto-sort" class="text-slate-600 hover:text-emerald-700 px-2.5 py-1.5 rounded-lg transition hover:bg-slate-50 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Pemilah Otomatis
                </a>
                <a href="/education" class="text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg font-bold">🌱 Layar Edukasi</a>
                <a href="/scan-ai" class="text-slate-600 hover:text-emerald-700 px-2.5 py-1.5 rounded-lg transition hover:bg-slate-50">📷 Scan AI</a>
                <a href="/locations" class="text-slate-600 hover:text-emerald-700 px-2.5 py-1.5 rounded-lg transition hover:bg-slate-50">📍 Peta</a>
                <a href="/leaderboard" class="text-slate-600 hover:text-emerald-700 px-2.5 py-1.5 rounded-lg transition hover:bg-slate-50">🏆 Poin</a>
                @auth
                    <a href="/dashboard" class="bg-emerald-700 hover:bg-emerald-800 text-white px-3.5 py-1.5 rounded-lg shadow-sm">Dashboard</a>
                @else
                    <a href="/login" class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-1.5 rounded-lg">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full space-y-10">

        <!-- Hero Banner: Ekoteologi Islam & Khilafah Fil Ardh -->
        <div class="bg-gradient-to-br from-emerald-800 via-teal-800 to-slate-900 rounded-3xl p-6 sm:p-10 text-white shadow-xl relative overflow-hidden border border-emerald-700/40">
            <!-- Background Ornamen Islami -->
            <div class="absolute -right-10 -bottom-10 opacity-10 text-white text-[240px] font-black pointer-events-none select-none">
                ۞
            </div>
            
            <div class="relative z-10 max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 bg-emerald-500/20 border border-emerald-400/30 backdrop-blur-md px-3.5 py-1.5 rounded-full text-emerald-200 text-xs font-bold uppercase tracking-wider">
                    <span>🕌 Fiqih Lingkungan (Hifzh al-Bi'ah)</span>
                    <span class="text-emerald-400">&bull;</span>
                    <span>Eco-Theology Islam</span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold leading-tight tracking-tight text-white">
                    Memuliakan Bumi Sebagai Amanah Khilafah Bersama Kecerdasan Buatan
                </h1>

                <!-- Ayat Suci Al-Qur'an -->
                <div class="bg-black/30 backdrop-blur-md border border-emerald-500/30 rounded-2xl p-4 sm:p-5 my-4">
                    <p class="font-quran text-right text-xl sm:text-2xl text-emerald-300 leading-relaxed font-bold" dir="rtl">
                        وَلَا تُفْسِدُوا فِي الْأَرْضِ بَعْدَ إِصْلَاحِهَا وَادْعُوهُ خَوْفًا وَطَمَعًا ۚ إِنَّ رَحْمَتَ اللَّهِ قَرِيبٌ مِّنَ الْمُحْسِنِينَ
                    </p>
                    <p class="text-xs sm:text-sm text-slate-200 mt-2.5 italic">
                        "Dan janganlah kamu berbuat kerusakan di bumi setelah (diciptakan) dengan baik. Berdoalah kepada-Nya dengan rasa takut dan penuh harap. Sesungguhnya rahmat Allah sangat dekat kepada orang-orang yang berbuat kebaikan."
                    </p>
                    <div class="text-[11px] font-bold text-emerald-400 mt-1 uppercase tracking-wider">
                        — QS. Al-A'raf: 56
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-slate-200 leading-relaxed">
                    Setiap sampah yang dibuang sembarangan adalah bentuk kelalaian terhadap amanah menjaga ciptaan Allah. Melalui sistem <strong>SmartBin AI UIN Sunan Gunung Djati Bandung</strong>, teknologi computer vision dan IoT hadir sebagai ikhtiar modern untuk menegakkan nilai-nilai kesucian (*thaharah*) dan kelestarian alam secara otomatis.
                </p>

                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="/auto-sort" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold px-5 py-3 rounded-xl shadow-lg hover:shadow-emerald-500/25 transition text-sm flex items-center gap-2">
                        🤖 Buka Layar Pemilah Otomatis (Live)
                    </a>
                    <a href="/scan-ai" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold px-5 py-3 rounded-xl backdrop-blur-sm transition text-sm flex items-center gap-2">
                        📷 Scan AI Lewat Kamera HP
                    </a>
                </div>
            </div>
        </div>

        <!-- Carousel Edukasi Otomatis (Berganti Tiap 6 Detik) -->
        <section class="bg-white rounded-3xl p-6 sm:p-8 border border-emerald-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></div>
                    <h2 class="font-extrabold text-base sm:text-lg text-slate-900">Pilar Edukasi Ekoteologi Islam</h2>
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400">
                    <span>Otomatis berpindah setiap 6 detik</span>
                    <div class="flex gap-1 ml-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="currentSlide = index" 
                                    class="w-2.5 h-2.5 rounded-full transition-all" 
                                    :class="currentSlide === index ? 'bg-emerald-600 w-6' : 'bg-slate-200'">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Slide Content -->
            <div class="min-h-[160px] flex items-center">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="currentSlide === index" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-x-4"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         class="w-full grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <div class="md:col-span-2 flex justify-center">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-4xl shadow-inner border border-emerald-100"
                                 :class="slide.badgeBg">
                                <span x-text="slide.icon"></span>
                            </div>
                        </div>
                        <div class="md:col-span-10 space-y-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700" x-text="slide.category"></span>
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900" x-text="slide.title"></h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed" x-text="slide.desc"></p>
                            <div class="text-xs font-semibold text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block border border-emerald-200/60" x-text="slide.quote"></div>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        <!-- 4 Kategori Pemilahan Sampah Berbasis Syariah & Ekologi -->
        <section class="space-y-6">
            <div class="text-center max-w-2xl mx-auto space-y-1">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest">Klasifikasi 4 Wadah Cerdas</span>
                <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900">4 Kategori Pemilahan SmartBin UIN</h2>
                <p class="text-xs sm:text-sm text-slate-500">Masing-masing wadah dikendalikan aktuator motor servo terpisah sesuai prediksi AI.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <!-- BIN 1: Organik -->
                <div class="bg-white rounded-3xl p-5 border border-emerald-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group hover:border-emerald-400">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl font-black group-hover:scale-105 transition">
                                🥬
                            </div>
                            <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                BIN 1 • ORGANIK
                            </span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-base">Sampah Organik</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Bahan hayati yang dapat terurai alami. Mengolahnya menjadi kompos adalah bentuk syukur atas rezeki pangan.
                        </p>
                        
                        <div class="mt-4 bg-emerald-50/60 rounded-xl p-3 border border-emerald-100 text-xs space-y-1">
                            <div class="font-bold text-emerald-900">Dalil &amp; Hikmah:</div>
                            <p class="text-slate-600 italic">"Lalu Kami tumbuhkan padanya biji-bijian, dan anggur serta sayur-sayuran..." (QS. Abasa: 27-28)</p>
                        </div>

                        <div class="mt-3 text-xs space-y-1 text-slate-600">
                            <div class="font-bold text-slate-800">Contoh:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                                <li>Sisa makanan &amp; buah</li>
                                <li>Kulit pisang &amp; ampas kopi</li>
                                <li>Dedaunan kampus</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Dekomposisi:</span>
                        <strong class="text-emerald-700">2 - 4 Minggu</strong>
                    </div>
                </div>

                <!-- BIN 2: Plastik -->
                <div class="bg-white rounded-3xl p-5 border border-amber-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group hover:border-amber-400">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-2xl font-black group-hover:scale-105 transition">
                                🧴
                            </div>
                            <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                BIN 2 • PLASTIK
                            </span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-base">Sampah Plastik</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Polimer sintesis yang sangat sulit terurai. Menahan tercecernya plastik mencegah mafsadah kerusakan air &amp; laut.
                        </p>
                        
                        <div class="mt-4 bg-amber-50/60 rounded-xl p-3 border border-amber-100 text-xs space-y-1">
                            <div class="font-bold text-amber-900">Dalil &amp; Hikmah:</div>
                            <p class="text-slate-600 italic">"Telah tampak kerusakan di darat dan di laut akibat ulah tangan manusia..." (QS. Ar-Rum: 41)</p>
                        </div>

                        <div class="mt-3 text-xs space-y-1 text-slate-600">
                            <div class="font-bold text-slate-800">Contoh:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                                <li>Botol air mineral (PET)</li>
                                <li>Gelas kopi &amp; sedotan</li>
                                <li>Kantong kresek &amp; sachet</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Dekomposisi:</span>
                        <strong class="text-amber-700">100 - 500 Tahun</strong>
                    </div>
                </div>

                <!-- BIN 3: Kertas -->
                <div class="bg-white rounded-3xl p-5 border border-blue-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group hover:border-blue-400">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center text-2xl font-black group-hover:scale-105 transition">
                                📄
                            </div>
                            <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                BIN 3 • KERTAS
                            </span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-base">Kertas &amp; Karton</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Bahan selulosa pohon. Mendaur ulangnya menyelamatkan hutan yang bertasbih memuji Sang Pencipta.
                        </p>
                        
                        <div class="mt-4 bg-blue-50/60 rounded-xl p-3 border border-blue-100 text-xs space-y-1">
                            <div class="font-bold text-blue-900">Dalil &amp; Hikmah:</div>
                            <p class="text-slate-600 italic">"Dan janganlah kamu berlebih-lebihan. Sesungguhnya Allah tidak menyukai orang yang berlebih-lebihan." (QS. Al-An'am: 141)</p>
                        </div>

                        <div class="mt-3 text-xs space-y-1 text-slate-600">
                            <div class="font-bold text-slate-800">Contoh:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                                <li>Kertas skripsi &amp; HVS</li>
                                <li>Kardus kemasan paket</li>
                                <li>Buku catatan bekas</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Dekomposisi:</span>
                        <strong class="text-blue-700">2 - 6 Minggu</strong>
                    </div>
                </div>

                <!-- BIN 4: Logam & Kaca -->
                <div class="bg-white rounded-3xl p-5 border border-rose-200 shadow-sm hover:shadow-md transition flex flex-col justify-between group hover:border-rose-400">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center text-2xl font-black group-hover:scale-105 transition">
                                🥫
                            </div>
                            <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                                BIN 4 • LOGAM &amp; KACA
                            </span>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-base">Logam &amp; Kaca</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Mineral bernilai tinggi yang diturunkan Allah untuk kemaslahatan manusia. Daur ulang tanpa batas.
                        </p>
                        
                        <div class="mt-4 bg-rose-50/60 rounded-xl p-3 border border-rose-100 text-xs space-y-1">
                            <div class="font-bold text-rose-900">Dalil &amp; Hikmah:</div>
                            <p class="text-slate-600 italic">"Dan Kami ciptakan besi yang padanya terdapat kekuatan yang hebat dan berbagai manfaat bagi manusia..." (QS. Al-Hadid: 25)</p>
                        </div>

                        <div class="mt-3 text-xs space-y-1 text-slate-600">
                            <div class="font-bold text-slate-800">Contoh:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                                <li>Kaleng minuman bersoda</li>
                                <li>Tutup botol &amp; seng</li>
                                <li>Botol beling &amp; wadah selai</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Dekomposisi:</span>
                        <strong class="text-rose-700">50 - 200+ Tahun</strong>
                    </div>
                </div>

            </div>
        </section>

        <!-- Cara Kerja Pemilahan Otomatis SmartBin AI -->
        <section class="bg-gradient-to-r from-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white border border-emerald-800/40 shadow-xl space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
                <div>
                    <span class="text-emerald-400 text-xs font-bold uppercase tracking-widest">Sistem Cerdas Edge Computing</span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white">Bagaimana SmartBin AI Memilah Otomatis?</h2>
                </div>
                <a href="/auto-sort" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-bold px-4 py-2 rounded-xl text-xs transition">
                    <span>Lihat Layar Pemilah Otomatis</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 font-black flex items-center justify-center text-sm">1</div>
                    <h3 class="font-bold text-sm text-white">Deteksi Objek Sampah</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Letakkan sampah pada latar hitam. Kamera USB membaca kontur objek secara real-time.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 font-black flex items-center justify-center text-sm">2</div>
                    <h3 class="font-bold text-sm text-white">Hitung Mundur 3-2-1</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Sistem memberi jeda 3 detik agar posisi tangan dan sampah stabil sebelum dianalisis.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 font-black flex items-center justify-center text-sm">3</div>
                    <h3 class="font-bold text-sm text-white">Inferensi Hybrid AI</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">MobileNetV2 mengekstrak 1280 fitur citra, XGBoost memprediksi kategori dalam hitungan milidetik.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 font-black flex items-center justify-center text-sm">4</div>
                    <h3 class="font-bold text-sm text-white">Aktuasi Servo Otomatis</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">Perintah serial dikirim ke mikrokontroler untuk membuka pintu wadah yang tepat.</p>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer Resmi -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <img src="/images/logo-uin.png" alt="UIN SGD" class="h-6 w-auto">
                <span class="font-semibold text-slate-700">SmartBin AI IoT &bull; UIN Sunan Gunung Djati Bandung</span>
            </div>
            <p>Laboratorium Riset Kecerdasan Buatan &amp; Internet of Things &bull; Ekoteologi Islam Berkelanjutan</p>
        </div>
    </footer>

    <!-- Alpine Script untuk Carousel Edukasi Otomatis -->
    <script>
        function educationApp() {
            return {
                currentSlide: 0,
                slides: [
                    {
                        category: "Konsep Dasar",
                        title: "Khilafah Fil Ardh: Amanah Memakmurkan Bumi",
                        desc: "Manusia diutus ke muka bumi bukan sebagai perusak, melainkan khalifah pengelola yang bertanggung jawab menjaga keharmonisan ekosistem ciptaan Allah SWT.",
                        quote: "«إِنِّي جَاعِلٌ فِي الْأَرْضِ خَلِيفَةً» — Ingatlah ketika Tuhanmu berfirman: 'Aku hendak menjadikan khalifah di bumi' (QS. Al-Baqarah: 30)",
                        icon: "🌍",
                        badgeBg: "bg-emerald-100 text-emerald-800"
                    },
                    {
                        category: "Fiqih Lingkungan",
                        title: "Thaharah (Kesucian) Bukan Sekadar Bersuci Diri",
                        desc: "Dalam tradisi Islam, thaharah mencakup kesucian tempat tinggal, ruang belajar, dan lingkungan sekitar dari kotoran dan limbah yang membawa penyakit.",
                        quote: "«الطُّهُورُ شَطْرُ الْإِيمَانِ» — Kesucian dan kebersihan adalah sebagian dari iman (HR. Muslim No. 223)",
                        icon: "💧",
                        badgeBg: "bg-blue-100 text-blue-800"
                    },
                    {
                        category: "Etika Konsumsi",
                        title: "Larangan Tabdzir & Israf (Mubazir)",
                        desc: "Membeli kemasan sekali pakai berlebihan tanpa mengolahnya termasuk pemborosan sumber daya bumi yang bertentangan dengan prinsip kesederhanaan.",
                        quote: "«إِنَّ الْمُبَذِّرِينَ كَانُوا إِخْوَانَ الشَّيَاطِينِ» — Sesungguhnya para pemboros itu adalah saudara-saudara setan (QS. Al-Isra: 27)",
                        icon: "⚖️",
                        badgeBg: "bg-amber-100 text-amber-800"
                    },
                    {
                        category: "Amal Jariyah",
                        title: "Daur Ulang dan Menyingkirkan Duri dari Jalan",
                        desc: "Menyingkirkan sampah dari ruang publik dan memilahnya agar tidak mencemari lingkungan terhitung sebagai sedekah dan cabang keimanan.",
                        quote: "«إِمَاطَةُ الْأَذَى عَنِ الطَّرِيقِ صَدَقَةٌ» — Menyingkirkan gangguan dari jalan adalah sedekah (HR. Bukhari & Muslim)",
                        icon: "🌱",
                        badgeBg: "bg-teal-100 text-teal-800"
                    }
                ],
                init() {
                    setInterval(() => {
                        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                    }, 6000);
                }
            }
        }
    </script>
</body>
</html>
