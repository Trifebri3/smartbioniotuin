<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Lokasi SmartBin Terdekat - SmartBin IoT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: calc(100vh - 140px); min-height: 480px; width: 100%; border-radius: 12px; }
    </style>
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
                <a href="/locations" class="text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-md border border-emerald-200">📍 Peta Terdekat</a>
                <a href="/scan-ai" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">📷 Scan AI</a>
                <a href="/education" class="text-slate-600 hover:text-emerald-600 px-3 py-1.5 rounded-md">🌱 Edukasi</a>
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex-1 w-full flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                    📍 Peta Lokasi SmartBin Terdekat & Monitoring Kapasitas
                </h1>
                <p class="text-xs sm:text-sm text-slate-500">
                    Cari titik tempat sampah pintar di sekitarmu, cek apakah wadah masih tersedia, lalu scan QR untuk setor sampah dan raih poin!
                </p>
            </div>
            <button onclick="locateUser()" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition">
                🎯 Temukan Lokasi Saya
            </button>
        </div>

        <!-- Leaflet Map Container -->
        <div class="bg-white p-2 sm:p-3 rounded-2xl border border-slate-200 shadow-sm">
            <div id="map"></div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
        SmartBin IoT Platform &bull; Kampus Cerdas & Ramah Lingkungan &bull; bin.ihi.my.id
    </footer>

    <script>
        const smartBinsData = @json($smartBins);

        // Inisialisasi Peta Leaflet (Default Center: Kampus UIN Jakarta)
        const map = L.map('map').setView([-6.3053, 106.7570], 15);

        // Tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let userMarker = null;

        function getStatusBadge(bin) {
            const maxCap = Math.max(bin.capacity_organik, bin.capacity_plastik, bin.capacity_kertas, bin.capacity_logam);
            if (maxCap >= 90) return '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-red-100 text-red-700">PENUH</span>';
            if (maxCap >= 75) return '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-100 text-amber-700">HAMPIR PENUH</span>';
            return '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-700">TERSEDIA</span>';
        }

        // Tambahkan Marker SmartBin
        smartBinsData.forEach(bin => {
            const marker = L.marker([bin.latitude, bin.longitude]).addTo(map);
            
            const popupHtml = `
                <div class="p-1 max-w-[260px] text-xs font-sans">
                    <div class="flex justify-between items-center gap-2 mb-1.5">
                        <strong class="text-sm font-bold text-slate-900">${bin.name}</strong>
                        ${getStatusBadge(bin)}
                    </div>
                    <p class="text-slate-500 text-[11px] mb-2">${bin.location_name}</p>
                    
                    <div class="space-y-1.5 bg-slate-50 p-2 rounded-lg border border-slate-200 mb-2.5">
                        <div class="flex justify-between text-[11px]">
                            <span class="text-emerald-700 font-semibold">Organik:</span>
                            <span class="font-bold">${bin.capacity_organik}%</span>
                        </div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full" style="width: ${bin.capacity_organik}%"></div>
                        </div>

                        <div class="flex justify-between text-[11px]">
                            <span class="text-blue-700 font-semibold">Plastik:</span>
                            <span class="font-bold">${bin.capacity_plastik}%</span>
                        </div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full" style="width: ${bin.capacity_plastik}%"></div>
                        </div>

                        <div class="flex justify-between text-[11px]">
                            <span class="text-amber-700 font-semibold">Kertas:</span>
                            <span class="font-bold">${bin.capacity_kertas}%</span>
                        </div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full" style="width: ${bin.capacity_kertas}%"></div>
                        </div>

                        <div class="flex justify-between text-[11px]">
                            <span class="text-red-700 font-semibold">Logam/Kaca:</span>
                            <span class="font-bold">${bin.capacity_logam}%</span>
                        </div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-red-500 h-full" style="width: ${bin.capacity_logam}%"></div>
                        </div>
                    </div>

                    <a href="/deposit/${bin.uuid}" class="block text-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-1.5 px-3 rounded-md text-xs shadow-sm transition">
                        📲 Scan QR & Setor Sampah
                    </a>
                </div>
            `;
            marker.bindPopup(popupHtml);
        });

        // Geolocation Lokasi Saya
        function locateUser() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(position => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    if (userMarker) map.removeLayer(userMarker);

                    userMarker = L.circleMarker([lat, lng], {
                        radius: 8,
                        fillColor: "#0284c7",
                        color: "#fff",
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    }).addTo(map).bindPopup("<b>📍 Lokasi Anda Saat Ini</b>").openPopup();

                    map.setView([lat, lng], 16);
                }, () => {
                    alert("Gagal membaca koordinat GPS Anda. Pastikan izin lokasi telah aktif.");
                });
            } else {
                alert("Browser tidak mendukung geolokasi GPS.");
            }
        }
    </script>
</body>
</html>

