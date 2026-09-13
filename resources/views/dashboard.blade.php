<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Smart Waste Sorter Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Camera Configuration -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">ESP32 Camera (Public Stream)</h3>
                        <p class="mt-1 text-sm text-gray-600">Sistem Kamera Tahan Banting (Anti-Blokir Firewall & Cloudflare).</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span id="connection-status" class="inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-800">
                            Disconnected
                        </span>
                    </div>
                </div>
            </div>

            <!-- Live Camera Feed -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Live Camera Stream</h3>

                    <div class="relative w-full rounded-lg bg-gray-900 flex items-center justify-center overflow-hidden" style="min-height: 480px;">
                        <!-- Menggunakan Image Polling -->
                        <img id="camera-stream-img" src="/stream.jpg" alt="Live ESP32 Camera Stream" class="w-full max-w-3xl object-contain rounded" style="display: none;"/>
                        
                        <div id="waiting-text" class="text-gray-400">
                            Menunggu koneksi dari kamera ESP32...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Polling JS Component -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusBadge = document.getElementById('connection-status');
            const cameraImg = document.getElementById('camera-stream-img');
            const waitingText = document.getElementById('waiting-text');
            
            let isConnected = false;
            let lastSuccessTime = 0;

            function updateStatus(connected) {
                if (connected !== isConnected) {
                    isConnected = connected;
                    if (connected) {
                        statusBadge.textContent = 'Connected';
                        statusBadge.className = 'inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium bg-green-100 text-green-800';
                        waitingText.style.display = 'none';
                        cameraImg.style.display = 'block';
                    } else {
                        statusBadge.textContent = 'Disconnected';
                        statusBadge.className = 'inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium bg-red-100 text-red-800';
                        waitingText.style.display = 'block';
                        cameraImg.style.display = 'none';
                    }
                }
            }

            // Fungsi untuk merefresh gambar secepat mungkin (sekitar 12 FPS)
            setInterval(() => {
                const imgUrl = '/stream.jpg?t=' + new Date().getTime();
                
                // Buat object gambar sementara untuk mengecek apakah gambar berhasil dimuat
                const tempImg = new Image();
                tempImg.onload = () => {
                    cameraImg.src = imgUrl;
                    lastSuccessTime = Date.now();
                    updateStatus(true);
                };
                tempImg.onerror = () => {
                    // Jika gambar gagal dimuat, biarkan saja.
                };
                tempImg.src = imgUrl;

                // Jika sudah lebih dari 3 detik tidak ada gambar baru yang berhasil dimuat, set status ke disconnected
                if (Date.now() - lastSuccessTime > 3000) {
                    updateStatus(false);
                }
            }, 80); // Refresh setiap 80ms (sangat cepat)
        });
    </script>
</x-app-layout>
