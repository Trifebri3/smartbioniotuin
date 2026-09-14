<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('SmartBin AI Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-white min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Camera Configuration -->
            <div class="border border-gray-100 rounded-2xl p-5 md:p-6 flex flex-col md:flex-row items-start md:items-center justify-between bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Live Camera Stream
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Kamera cerdas pendeteksi jenis sampah secara real-time.</p>
                </div>
                <div>
                    <span id="connection-status" class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-semibold bg-gray-100 text-gray-600 transition-colors">
                        Menghubungkan...
                    </span>
                </div>
            </div>

            <!-- Live Camera Feed -->
            <div class="border border-gray-100 rounded-3xl overflow-hidden bg-white shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="p-1">
                    <div id="mixed-content-warning" class="m-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800" style="display: none;">
                        <p class="font-bold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Izin Keamanan Diperlukan
                        </p>
                        <p class="text-sm mt-2">
                            Mohon izinkan <strong>Insecure Content</strong> di pengaturan situs pada browser Anda untuk melihat *Live Stream*.
                        </p>
                    </div>

                    <div class="relative w-full bg-slate-50 flex items-center justify-center overflow-hidden rounded-2xl" style="min-height: 300px; md:min-height: 480px;">
                        <!-- Efek grid background saat loading -->
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNFMkU4RjAiLz48L3N2Zz4=')] opacity-50"></div>
                        
                        <!-- Menggunakan Canvas agar berperilaku persis seperti Video -->
                        <canvas id="camera-canvas" width="640" height="480" class="w-full h-full object-cover relative z-10" style="display: none;"></canvas>
                        
                        <div id="waiting-text" class="text-slate-400 font-medium relative z-10 flex flex-col items-center gap-3 animate-pulse">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Mencari Sinyal Kamera...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sensor Data -->
            <div class="border border-gray-100 rounded-2xl p-5 md:p-6 bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="mb-6 border-b border-gray-100 pb-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Status Tempat Sampah
                    </h3>
                    <span id="sensor-last-update" class="text-xs text-gray-400 font-medium bg-gray-50 px-2 py-1 rounded-md border border-gray-100">Loading...</span>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Sensor 1 -->
                    <div class="relative overflow-hidden rounded-xl bg-slate-50 border border-slate-100 p-4 transition-all hover:bg-slate-100 group shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold text-slate-600">Bin 1</span>
                            <span id="s1-status" class="flex h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                        </div>
                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <span id="s1-val" class="text-3xl font-bold text-slate-800 tracking-tight">--</span>
                                <span class="text-sm font-medium text-slate-500 ml-1">cm</span>
                            </div>
                            <div class="text-right">
                                <span id="s1-pct" class="text-sm font-bold text-blue-600">--%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 mt-3 overflow-hidden shadow-inner">
                            <div id="s1-bar" class="bg-blue-500 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Sensor 2 -->
                    <div class="relative overflow-hidden rounded-xl bg-slate-50 border border-slate-100 p-4 transition-all hover:bg-slate-100 group shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold text-slate-600">Bin 2</span>
                            <span id="s2-status" class="flex h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                        </div>
                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <span id="s2-val" class="text-3xl font-bold text-slate-800 tracking-tight">--</span>
                                <span class="text-sm font-medium text-slate-500 ml-1">cm</span>
                            </div>
                            <div class="text-right">
                                <span id="s2-pct" class="text-sm font-bold text-blue-600">--%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 mt-3 overflow-hidden shadow-inner">
                            <div id="s2-bar" class="bg-blue-500 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Sensor 3 -->
                    <div class="relative overflow-hidden rounded-xl bg-slate-50 border border-slate-100 p-4 transition-all hover:bg-slate-100 group shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold text-slate-600">Bin 3</span>
                            <span id="s3-status" class="flex h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                        </div>
                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <span id="s3-val" class="text-3xl font-bold text-slate-800 tracking-tight">--</span>
                                <span class="text-sm font-medium text-slate-500 ml-1">cm</span>
                            </div>
                            <div class="text-right">
                                <span id="s3-pct" class="text-sm font-bold text-blue-600">--%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 mt-3 overflow-hidden shadow-inner">
                            <div id="s3-bar" class="bg-blue-500 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Sensor 4 -->
                    <div class="relative overflow-hidden rounded-xl bg-slate-50 border border-slate-100 p-4 transition-all hover:bg-slate-100 group shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold text-slate-600">Bin 4</span>
                            <span id="s4-status" class="flex h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                        </div>
                        <div class="mt-4 flex items-end justify-between">
                            <div>
                                <span id="s4-val" class="text-3xl font-bold text-slate-800 tracking-tight">--</span>
                                <span class="text-sm font-medium text-slate-500 ml-1">cm</span>
                            </div>
                            <div class="text-right">
                                <span id="s4-pct" class="text-sm font-bold text-blue-600">--%</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5 mt-3 overflow-hidden shadow-inner">
                            <div id="s4-bar" class="bg-blue-500 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Canvas Rendering Component -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusBadge = document.getElementById('connection-status');
            const warningBox = document.getElementById('mixed-content-warning');
            const canvas = document.getElementById('camera-canvas');
            const ctx = canvas.getContext('2d');
            const waitingText = document.getElementById('waiting-text');
            
            // Set status jadi Connected 
            statusBadge.textContent = 'API Polling Active';
            statusBadge.className = 'inline-flex items-center rounded-full px-4 py-1.5 text-sm font-semibold bg-emerald-100 text-emerald-700 transition-colors';
            waitingText.style.display = 'none';
            warningBox.style.display = 'none';
            
            // Tampilkan Canvas
            canvas.style.display = 'block';

            // Fungsi rendering ke Canvas (Persis seperti Video Player)
            function fetchNextFrame() {
                const img = new Image();
                img.crossOrigin = "Anonymous"; // Hindari CORS issues di Canvas
                img.src = '/camera.jpg?time=' + new Date().getTime();
                
                img.onload = () => {
                    // Gambar sukses dimuat -> Lukis ke layar seketika! (0 kedip)
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    
                    // Segera tarik frame selanjutnya (10 FPS = 100ms)
                    setTimeout(fetchNextFrame, 100);
                };
                
                img.onerror = () => {
                    // Jika gambar gagal dimuat (misal file sedang ditimpa ESP32), abaikan dan coba lagi
                    setTimeout(fetchNextFrame, 100);
                };
            }

            // Nyalakan Mesin Video!
            fetchNextFrame();
        });
    </script>

    <!-- Sensor Polling Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const MAX_DISTANCE = 40; // cm (kosong)
            const MIN_DISTANCE = 5;  // cm (penuh)

            function updateSensorUI(id, distance) {
                const valEl = document.getElementById(`s${id}-val`);
                const pctEl = document.getElementById(`s${id}-pct`);
                const barEl = document.getElementById(`s${id}-bar`);
                const statusEl = document.getElementById(`s${id}-status`);

                if (distance === -1 || distance === null || distance === undefined) {
                    valEl.textContent = '--';
                    pctEl.textContent = 'Off';
                    barEl.style.width = '0%';
                    barEl.className = 'bg-slate-300 h-2.5 rounded-full transition-all duration-500';
                    statusEl.className = 'flex h-2.5 w-2.5 rounded-full bg-slate-300';
                    return;
                }

                // Format jarak
                valEl.textContent = parseFloat(distance).toFixed(1);

                // Hitung persentase kepenuhan (semakin dekat, semakin penuh)
                let pct = 0;
                if (distance <= MIN_DISTANCE) {
                    pct = 100;
                } else if (distance >= MAX_DISTANCE) {
                    pct = 0;
                } else {
                    pct = 100 - ((distance - MIN_DISTANCE) / (MAX_DISTANCE - MIN_DISTANCE) * 100);
                }
                
                pct = Math.round(pct);
                pctEl.textContent = `${pct}%`;
                barEl.style.width = `${pct}%`;

                // Tentukan warna berdasarkan persentase
                let colorClass = 'bg-blue-500';
                let dotClass = 'bg-blue-500';
                let textClass = 'text-blue-600';
                
                if (pct > 85) {
                    colorClass = 'bg-rose-500';
                    dotClass = 'bg-rose-500 animate-pulse';
                    textClass = 'text-rose-600';
                } else if (pct > 60) {
                    colorClass = 'bg-amber-500';
                    dotClass = 'bg-amber-500';
                    textClass = 'text-amber-600';
                } else {
                    colorClass = 'bg-emerald-500';
                    dotClass = 'bg-emerald-500';
                    textClass = 'text-emerald-600';
                }

                barEl.className = `${colorClass} h-2.5 rounded-full transition-all duration-500 shadow-sm`;
                statusEl.className = `flex h-2.5 w-2.5 rounded-full ${dotClass} shadow-sm`;
                pctEl.className = `text-sm font-bold ${textClass}`;
            }

            function fetchSensors() {
                fetch('/api/sensors/latest')
                    .then(response => response.json())
                    .then(data => {
                        updateSensorUI(1, data.sensor1);
                        updateSensorUI(2, data.sensor2);
                        updateSensorUI(3, data.sensor3);
                        updateSensorUI(4, data.sensor4);
                        
                        const now = new Date();
                        document.getElementById('sensor-last-update').textContent = 
                            `Diperbarui: ${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}:${now.getSeconds().toString().padStart(2, '0')}`;
                    })
                    .catch(error => {
                        console.error('Error fetching sensor data:', error);
                        document.getElementById('sensor-last-update').textContent = 'Koneksi Terputus';
                        document.getElementById('sensor-last-update').classList.add('text-rose-500', 'bg-rose-50');
                    });
            }

            // Polling setiap 2 detik
            setInterval(fetchSensors, 2000);
            fetchSensors(); // Fetch pertama kali
        });
    </script>
</x-app-layout>
