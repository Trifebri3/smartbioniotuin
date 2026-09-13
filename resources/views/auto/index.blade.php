<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Pemilah Otomatis (AI Vision)') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" x-data="autoSortController()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Notifikasi Error/Success -->
            <div x-show="alert.show" x-transition class="rounded-xl p-4 border" :class="alert.type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800'" style="display: none;">
                <p class="font-medium flex items-center gap-2">
                    <span x-text="alert.message"></span>
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Kiri: Kamera & Kontrol -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Camera Feed -->
                    <div class="border border-gray-200 rounded-3xl overflow-hidden bg-white shadow-sm">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                AI Vision Stream
                            </h3>
                            
                            <!-- Toggle Auto Pilot -->
                            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full border border-gray-200 shadow-sm">
                                <span class="text-sm font-semibold text-gray-700">Mode Auto-Pilot</span>
                                <button @click="toggleAutoPilot()" 
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        :class="isAutoPilot ? 'bg-indigo-600' : 'bg-gray-200'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                          :class="isAutoPilot ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="p-1">
                            <div class="relative w-full bg-slate-900 flex items-center justify-center overflow-hidden rounded-2xl" style="min-height: 480px;">
                                <!-- Canvas untuk Video Mulus -->
                                <canvas id="camera-canvas" width="640" height="480" class="w-full h-full object-cover relative z-10"></canvas>
                                
                                <!-- Overlay Scanner (Animasi saat scan) -->
                                <div x-show="isScanning" class="absolute inset-0 z-20 pointer-events-none">
                                    <div class="w-full h-1 bg-indigo-500/80 shadow-[0_0_15px_3px_rgba(99,102,241,0.5)] absolute top-0 left-0 animate-scan"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Manual -->
                    <div class="flex justify-center">
                        <button @click="scanNow()" :disabled="isScanning || isAutoPilot"
                                class="group relative inline-flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-200 bg-indigo-600 border border-transparent rounded-full hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl w-full md:w-auto">
                            
                            <svg x-show="!isScanning" class="w-6 h-6 mr-3 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            
                            <svg x-show="isScanning" class="w-6 h-6 mr-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            
                            <span x-text="isScanning ? 'AI Sedang Menganalisis...' : 'Mulai Pindai Sampah (AI)'"></span>
                        </button>
                    </div>
                </div>

                <!-- Kanan: Hasil & Log -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Status Deteksi Terakhir -->
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
                        <!-- Hiasan Background -->
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full opacity-50 blur-xl"></div>
                        
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2 relative z-10">Hasil Deteksi AI</h3>
                        
                        <div class="flex items-end gap-3 relative z-10 mb-4">
                            <span class="text-4xl font-black text-gray-900" x-text="lastDetection || '-'"></span>
                        </div>
                        
                        <div class="bg-indigo-50 text-indigo-700 rounded-xl p-4 text-sm font-medium border border-indigo-100" x-text="lastAction || 'Sistem menunggu instruksi...'"></div>
                    </div>

                    <!-- Log Aktivitas -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col" style="height: 400px;">
                        <div class="p-5 border-b border-gray-50">
                            <h3 class="font-bold text-gray-800">Riwayat Pemilahan</h3>
                        </div>
                        <div class="flex-1 p-5 overflow-y-auto space-y-4" id="log-container">
                            <template x-for="log in logs" :key="log.id">
                                <div class="flex items-start gap-3 text-sm animate-fade-in-up">
                                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" :class="getDotColor(log.type)"></div>
                                    <div>
                                        <span class="font-bold text-gray-700" x-text="log.type"></span>
                                        <p class="text-gray-500 mt-0.5" x-text="log.message"></p>
                                        <span class="text-xs text-gray-400 mt-1 block" x-text="log.time"></span>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="logs.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Belum ada riwayat scan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes scan {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan {
            animation: scan 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
        }
    </style>

    <!-- Script Video Player Canvas -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('camera-canvas');
            const ctx = canvas.getContext('2d');
            
            function fetchNextFrame() {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                img.src = '/camera.jpg?time=' + new Date().getTime();
                
                img.onload = () => {
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    setTimeout(fetchNextFrame, 100); // 10 FPS
                };
                
                img.onerror = () => {
                    setTimeout(fetchNextFrame, 100);
                };
            }
            fetchNextFrame();
        });

        // Alpine.js Logic
        function autoSortController() {
            return {
                isScanning: false,
                isAutoPilot: false,
                autoPilotInterval: null,
                lastDetection: '',
                lastAction: '',
                logs: [],
                alert: { show: false, type: '', message: '' },
                
                showAlert(type, message) {
                    this.alert = { show: true, type, message };
                    setTimeout(() => this.alert.show = false, 5000);
                },

                toggleAutoPilot() {
                    this.isAutoPilot = !this.isAutoPilot;
                    if (this.isAutoPilot) {
                        this.showAlert('success', 'Mode Auto-Pilot Diaktifkan. Memindai setiap 5 detik.');
                        // Langsung scan 1x
                        this.scanNow();
                        // Lalu jalankan interval 5 detik
                        this.autoPilotInterval = setInterval(() => {
                            if(!this.isScanning) this.scanNow();
                        }, 5000);
                    } else {
                        clearInterval(this.autoPilotInterval);
                        this.showAlert('success', 'Mode Auto-Pilot Dinonaktifkan.');
                    }
                },

                async scanNow() {
                    if (this.isScanning) return;
                    this.isScanning = true;
                    
                    try {
                        const response = await fetch('/api/ai/scan', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.status === 'success') {
                            this.lastDetection = data.detection;
                            this.lastAction = data.action;
                            
                            // Tambah ke log
                            this.logs.unshift({
                                id: Date.now(),
                                type: data.detection,
                                message: data.action,
                                time: new Date().toLocaleTimeString()
                            });
                            
                            // Simpan max 20 log
                            if(this.logs.length > 20) this.logs.pop();
                            
                        } else {
                            this.showAlert('error', data.message || 'Terjadi kesalahan sistem.');
                            if (this.isAutoPilot) this.toggleAutoPilot(); // Matikan auto jika error (misal API key habis)
                        }
                    } catch (error) {
                        this.showAlert('error', 'Gagal menghubungi server.');
                        if (this.isAutoPilot) this.toggleAutoPilot();
                    } finally {
                        this.isScanning = false;
                    }
                },
                
                getDotColor(type) {
                    const colors = {
                        'KERTAS': 'bg-blue-500',
                        'PLASTIK': 'bg-red-500',
                        'ORGANIK': 'bg-green-500',
                        'LOGAM': 'bg-gray-500',
                        'TIDAK_DIKETAHUI': 'bg-yellow-400'
                    };
                    return colors[type] || 'bg-indigo-500';
                }
            }
        }
    </script>
</x-app-layout>
