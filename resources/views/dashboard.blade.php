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
                        <p class="mt-1 text-sm text-gray-600">Terhubung ke Relay WebSocket untuk Video Super Mulus (Real-Time).</p>
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
                    
                    <div id="mixed-content-warning" class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700" style="display: none;">
                        <p class="font-bold">⚠️ Izin Keamanan Diperlukan!</p>
                        <p class="text-sm mt-1">
                            Browser Anda memblokir koneksi ke kamera karena menggunakan IP langsung (`ws://`). 
                            Untuk melihat kamera, klik ikon <strong>Gembok/Tanda Seru</strong> di sebelah URL alamat web (kiri atas), pilih <strong>Site Settings (Setelan Situs)</strong>, lalu ubah <strong>Insecure Content (Konten Tidak Aman)</strong> menjadi <strong>Allow (Izinkan)</strong>. Setelah itu <em>Refresh</em> halaman ini.
                        </p>
                    </div>

                    <div class="relative w-full rounded-lg bg-gray-900 flex items-center justify-center overflow-hidden" style="min-height: 480px;">
                        <img id="camera-stream-img" alt="Live ESP32 Camera Stream" class="w-full max-w-3xl object-contain rounded" style="display: none;"/>
                        
                        <div id="waiting-text" class="text-gray-400">
                            Menunggu koneksi dari kamera ESP32...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vanilla JS Component for Polling -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusBadge = document.getElementById('connection-status');
            const warningBox = document.getElementById('mixed-content-warning');
            const cameraImg = document.getElementById('camera-stream-img');
            const waitingText = document.getElementById('waiting-text');
            
            // Set status jadi Connected karena ini pakai HTTP biasa
            statusBadge.textContent = 'API Polling Active';
            statusBadge.className = 'inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium bg-blue-100 text-blue-800';
            waitingText.style.display = 'none';
            warningBox.style.display = 'none';
            
            // Tampilkan gambar
            cameraImg.style.display = 'block';

            // Memaksa browser merefresh gambar tiap 150 milidetik
            setInterval(() => {
                // Tambahkan timestamp di belakang URL agar browser tidak pakai cache
                cameraImg.src = '/camera.jpg?time=' + new Date().getTime();
            }, 150);
        });
    </script>
</x-app-layout>
