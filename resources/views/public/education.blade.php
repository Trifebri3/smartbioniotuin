<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edukasi Pemilahan Sampah & 3R - SmartBin IoT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                <a href="/education" class="text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-md border border-emerald-200">🌱 Edukasi</a>
                <a href="/leaderboard" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">🏆 Poin & Peringkat</a>
                @auth
                    <a href="/dashboard" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3.5 py-1.5 rounded-lg shadow-sm">Dashboard</a>
                @else
                    <a href="/login" class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-1.5 rounded-lg">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex-1 w-full space-y-10">

        <!-- Hero Banner -->
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 rounded-3xl p-6 sm:p-10 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="bg-white/20 backdrop-blur-md text-emerald-100 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">
                    Edukasi Lingkungan &amp; Zero Waste
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold mt-3 leading-tight">
                    Pilah Sampah dari Sumbernya, Selamatkan Bumi Bersama AI
                </h1>
                <p class="mt-3 text-sm sm:text-base text-emerald-50 leading-relaxed">
                    Lebih dari 60% sampah di TPA masih bercampur baur. Dengan memilah sampah ke dalam 4 kategori SmartBin, kamu membantu proses daur ulang sirkular dan mendapatkan poin reward!
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="/scan-ai" class="bg-white text-emerald-800 font-bold px-5 py-2.5 rounded-xl shadow hover:bg-emerald-50 transition text-sm flex items-center gap-2">
                        📷 Coba Scan AI Sekarang
                    </a>
                    <a href="/locations" class="bg-emerald-700/60 backdrop-blur-sm border border-white/20 text-white font-semibold px-5 py-2.5 rounded-xl hover:bg-emerald-700 transition text-sm">
                        📍 Cari Titik SmartBin
                    </a>
                </div>
            </div>
            <div class="absolute -right-8 -bottom-8 opacity-10 text-white text-9xl font-black pointer-events-none select-none">
                ♻️
            </div>
        </div>

        <!-- 4 Kategori Pemilahan Sampah -->
        <section class="space-y-4">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">4 Kategori Pemilahan di SmartBin AI</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Kenali jenis sampahmu agar sensor dan aktuator servo SmartBin dapat memilah secara akurat.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Organik -->
                <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold mb-4">
                            🥬
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-base">Sampah Organik</h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">+10 Pts</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Bahan yang berasal dari makhluk hidup dan dapat terurai secara alami oleh mikroorganisme.
                        </p>
                        <div class="mt-4 text-xs space-y-1.5 text-slate-600">
                            <div class="font-semibold text-slate-800">Contoh:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                                <li>Sisa makanan &amp; sayur</li>
                                <li>Kulit buah &amp; ampas kopi</li>
                                <li>Dedaunan &amp; ranting kering</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100 text-xs text-emerald-600 font-medium">
                        ⏱ Terurai: <strong>2 - 4 Minggu</strong>
                    </div>
                </div>

                <!-- Plastik -->
                <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold mb-4">
                            🧴
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-base">Sampah Plastik</h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">+20 Pts</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Bahan polimer sintesis yang sangat sulit terurai secara alami dan berisiko menjadi mikroplastik.
                        </p>
                        <div class="mt-4 text-xs space-y-1.5 text-slate-600">
                            <div class="font-semibold text-slate-800">Contoh:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                                <li>Botol minuman (PET)</li>
                                <li>Gelas kopi plastik (PP)</li>
                                <li>Kantong kresek &amp; bungkus sachet</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100 text-xs text-amber-600 font-medium">
                        ⏱ Terurai: <strong>20 - 500 Tahun</strong>
                    </div>
                </div>

                <!-- Kertas -->
                <div class="bg-white rounded-2xl p-5 border border-blue-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl font-bold mb-4">
                            📄
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-base">Kertas &amp; Karton</h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200">+15 Pts</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Serat selulosa dari kayu yang memiliki nilai daur ulang tinggi bila tidak tercampur air dan minyak.
                        </p>
                        <div class="mt-4 text-xs space-y-1.5 text-slate-600">
                            <div class="font-semibold text-slate-800">Contoh:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                                <li>Kertas HVS &amp; majalah</li>
                                <li>Kardus kemasan paket</li>
                                <li>Paper bag bersih</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100 text-xs text-blue-600 font-medium">
                        ⏱ Terurai: <strong>2 - 6 Minggu</strong>
                    </div>
                </div>

                <!-- Logam -->
                <div class="bg-white rounded-2xl p-5 border border-rose-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold mb-4">
                            🥫
                        </div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-base">Logam &amp; Kaleng</h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">+25 Pts</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            Material mineral bernilai ekonomi tinggi yang dapat didaur ulang tanpa batas tanpa menurunkan kualitas.
                        </p>
                        <div class="mt-4 text-xs space-y-1.5 text-slate-600">
                            <div class="font-semibold text-slate-800">Contoh:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                                <li>Kaleng minuman bersoda</li>
                                <li>Tutup botol &amp; foil makanan</li>
                                <li>Peralatan seng &amp; besi bekas</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-5 pt-3 border-t border-slate-100 text-xs text-rose-600 font-medium">
                        ⏱ Terurai: <strong>50 - 200 Tahun</strong>
                    </div>
                </div>
            </div>
        </section>

        <!-- Garis Waktu Dekomposisi Sampah di Alam -->
        <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                ⏳ Berapa Lama Sampah Bertahan di Lingkungan?
            </h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-emerald-700">Kulit Pisang / Apel (2-4 Minggu)</span>
                        <span class="text-slate-400">Mudah terurai</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-emerald-500 h-3 rounded-full" style="width: 5%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-blue-700">Kertas &amp; Karton (2 Bulan)</span>
                        <span class="text-slate-400">Mudah didaur ulang</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: 15%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-amber-700">Kantong Plastik Kresek (20 - 50 Tahun)</span>
                        <span class="text-slate-500">Bahaya mikroplastik</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-amber-500 h-3 rounded-full" style="width: 50%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-rose-700">Kaleng Minuman Aluminium (80 - 200 Tahun)</span>
                        <span class="text-slate-500">Karat &amp; racun tanah</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-rose-500 h-3 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-red-700">Botol Minuman Plastik PET (450 - 500 Tahun)</span>
                        <span class="text-red-600 font-bold">Sangat lama bertahan!</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3">
                        <div class="bg-red-600 h-3 rounded-full" style="width: 95%"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Prinsip 3R: Tips Praktis -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 font-extrabold flex items-center justify-center text-lg">1</div>
                <h3 class="font-bold text-slate-900 text-lg">Reduce (Kurangi)</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Batasi penggunaan barang sekali pakai. Bawa tumbler minuman, tas belanja kain, dan wadah makan sendiri saat berkegiatan di kampus.
                </p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center text-lg">2</div>
                <h3 class="font-bold text-slate-900 text-lg">Reuse (Gunakan Kembali)</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Manfaatkan kembali wadah atau wadah plastik untuk keperluan lain sebelum dibuang. Kertas bekas satu sisi dapat digunakan sebagai memo draft.
                </p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 font-extrabold flex items-center justify-center text-lg">3</div>
                <h3 class="font-bold text-slate-900 text-lg">Recycle (Daur Ulang)</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Setorkan sampah yang telah dipilah ke SmartBin AI terdekat. Sampah terpilah bersih siap diolah kembali menjadi produk baru bernilai tinggi.
                </p>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        SmartBin IoT Platform &bull; Kampus Cerdas & Ramah Lingkungan &bull; bin.ihi.my.id
    </footer>

</body>
</html>

