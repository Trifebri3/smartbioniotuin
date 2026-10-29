<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <img src="/images/logo-uin.png" alt="Logo UIN SGD" class="h-10 w-auto object-contain">
                <div>
                    <h2 class="font-extrabold text-xl text-slate-900 leading-tight">
                        {{ __('Layar Pemilah Otomatis (AI Vision & Ekoteologi Islam)') }}
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">UIN Sunan Gunung Djati Bandung &bull; Sistem Pemilahan Cerdas 4 Wadah</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="/education" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-bold transition">
                    🌱 Layar Edukasi Lengkap
                </a>
                <a href="/" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg text-xs font-semibold transition">
                    Live Monitor
                </a>
            </div>
        </div>
    </x-slot>

    <div id="auto-sort-container" class="py-6 bg-slate-50 min-h-screen" x-data="autoSortController()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Banner Ekoteologi Islam -->
            <div class="bg-gradient-to-r from-emerald-800 via-teal-800 to-slate-900 text-white rounded-2xl p-4 sm:p-5 shadow-sm border border-emerald-700/50 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-xl shrink-0">
                        🕌
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-300">Prinsip Ekoteologi Islam</span>
                            <span class="text-white/40">&bull;</span>
                            <span class="text-xs text-emerald-100">QS. Al-A'raf: 56</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-100 mt-0.5 italic">
                            "وَلَا تُفْسِدُوا فِي الْأَرْضِ بَعْدَ إِصْلَاحِهَا — Dan janganlah kamu berbuat kerusakan di bumi setelah (diciptakan) dengan baik."
                        </p>
                    </div>
                </div>
                <div class="shrink-0 text-right hidden lg:block">
                    <span class="text-[11px] font-bold px-2.5 py-1 bg-white/10 rounded-full border border-white/20 text-emerald-200">
                        UIN SGD KAMPUS HIJAU
                    </span>
                </div>
            </div>

            <!-- Notifikasi Error/Success -->
            <div x-show="alert.show" x-transition class="rounded-xl p-4 border" :class="alert.type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800'" style="display: none;">
                <p class="font-medium flex items-center gap-2 text-sm">
                    <span x-text="alert.message"></span>
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kiri: Kamera & Kontrol -->
                <div class="lg:col-span-8 space-y-5">
                    <!-- Camera Feed -->
                    <div class="border border-gray-200 rounded-3xl overflow-hidden bg-white shadow-sm">
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/70">
                            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                                Live Feed Area Deteksi Sampah
                            </h3>
                            
                            <!-- Toggle Auto Pilot -->
                            <div class="flex items-center gap-3 bg-white px-3.5 py-1.5 rounded-full border border-gray-200 shadow-sm">
                                <span class="text-xs font-bold text-gray-700">Mode Auto-Pilot</span>
                                <button @click="toggleAutoPilot()" 
                                        class="relative inline-flex h-5 w-10 items-center rounded-full transition-colors focus:outline-none"
                                        :class="isAutoPilot ? 'bg-emerald-600' : 'bg-gray-300'">
                                    <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition-transform"
                                          :class="isAutoPilot ? 'translate-x-5' : 'translate-x-1'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="p-2 bg-slate-950">
                            <div class="relative w-full aspect-[4/3] bg-black flex items-center justify-center overflow-hidden rounded-2xl">
                                <!-- Canvas untuk Video Mulus -->
                                <canvas id="camera-canvas" width="640" height="480" class="w-full h-full object-cover relative z-10"></canvas>
                                
                                <!-- Scanner Reticle Overlay -->
                                <div class="absolute inset-8 border-2 border-dashed border-emerald-400/40 rounded-2xl pointer-events-none z-15 flex flex-col justify-between p-3">
                                    <div class="flex justify-between">
                                        <span class="w-4 h-4 border-t-2 border-l-2 border-emerald-400"></span>
                                        <span class="w-4 h-4 border-t-2 border-r-2 border-emerald-400"></span>
                                    </div>
                                    <div class="text-center">
                                        <span class="bg-black/60 backdrop-blur-md px-3 py-1 rounded-full text-[11px] font-mono text-emerald-300 border border-emerald-500/30">
                                            AREA PENEMPATAN SAMPAH
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="w-4 h-4 border-b-2 border-l-2 border-emerald-400"></span>
                                        <span class="w-4 h-4 border-b-2 border-r-2 border-emerald-400"></span>
                                    </div>
                                </div>

                                <!-- Overlay Scanner (Animasi saat scan) -->
                                <div x-show="isScanning" class="absolute inset-0 z-20 pointer-events-none">
                                    <div class="w-full h-1 bg-emerald-400 shadow-[0_0_20px_5px_rgba(16,185,129,0.8)] absolute top-0 left-0 animate-scan"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Manual & Status -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="text-xs text-gray-500">
                            <span class="font-bold text-gray-700">Petunjuk:</span> Letakkan sampah di area latar hitam, sistem akan memindai secara otomatis.
                        </div>
                        <button @click="scanNow()" :disabled="isScanning || isAutoPilot"
                                class="inline-flex items-center justify-center px-6 py-3 font-bold text-white transition-all bg-emerald-600 rounded-xl hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg text-xs sm:text-sm w-full sm:w-auto">
                            
                            <svg x-show="!isScanning" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <svg x-show="isScanning" class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            
                            <span x-text="isScanning ? 'AI Sedang Menganalisis...' : 'Pindai Sampah Manual'"></span>
                        </button>
                    </div>
                </div>

                <!-- Kanan: Hasil & Status 4 Bins -->
                <div class="lg:col-span-4 space-y-5">
                    <!-- Status Deteksi Terakhir -->
                    <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hasil Klasifikasi AI</h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                                MobileNetV2 + XGBoost
                            </span>
                        </div>
                        
                        <div class="my-3">
                            <span class="text-3xl font-black text-gray-900 tracking-tight" x-text="lastDetection || 'MENUNGGU SAMPAH...'"></span>
                        </div>
                        
                        <div class="bg-emerald-50 text-emerald-800 rounded-xl p-3 text-xs font-semibold border border-emerald-100 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span x-text="lastAction || 'Letakkan objek sampah pada alas deteksi.'"></span>
                        </div>
                    </div>

                    <!-- Status 4 Wadah Pemilahan -->
                    <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-3">
                        <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider">Wadah Aktuator Terhubung</h3>
                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="p-3 rounded-2xl border text-center transition-all"
                                 :class="lastDetection === 'ORGANIK' ? 'bg-emerald-100 border-emerald-500 scale-105 shadow-sm' : 'bg-slate-50 border-slate-200'">
                                <div class="text-lg">🥬</div>
                                <div class="text-xs font-extrabold text-emerald-800 mt-1">BIN 1</div>
                                <div class="text-[10px] text-slate-500 font-medium">Organik</div>
                            </div>
                            <div class="p-3 rounded-2xl border text-center transition-all"
                                 :class="lastDetection === 'PLASTIK' ? 'bg-amber-100 border-amber-500 scale-105 shadow-sm' : 'bg-slate-50 border-slate-200'">
                                <div class="text-lg">🧴</div>
                                <div class="text-xs font-extrabold text-amber-800 mt-1">BIN 2</div>
                                <div class="text-[10px] text-slate-500 font-medium">Plastik</div>
                            </div>
                            <div class="p-3 rounded-2xl border text-center transition-all"
                                 :class="lastDetection === 'KERTAS' ? 'bg-blue-100 border-blue-500 scale-105 shadow-sm' : 'bg-slate-50 border-slate-200'">
                                <div class="text-lg">📄</div>
                                <div class="text-xs font-extrabold text-blue-800 mt-1">BIN 3</div>
                                <div class="text-[10px] text-slate-500 font-medium">Kertas</div>
                            </div>
                            <div class="p-3 rounded-2xl border text-center transition-all"
                                 :class="(lastDetection === 'LOGAM' || lastDetection === 'LOGAM_KACA') ? 'bg-rose-100 border-rose-500 scale-105 shadow-sm' : 'bg-slate-50 border-slate-200'">
                                <div class="text-lg">🥫</div>
                                <div class="text-xs font-extrabold text-rose-800 mt-1">BIN 4</div>
                                <div class="text-[10px] text-slate-500 font-medium">Logam/Kaca</div>
                            </div>
                        </div>
                    </div>

                    <!-- Log Aktivitas -->
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col" style="height: 280px;">
                        <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="font-bold text-xs text-gray-800 uppercase tracking-wider">Riwayat Pemilahan</h3>
                            <span class="text-[10px] text-gray-400 font-mono" x-text="logs.length + ' item'"></span>
                        </div>
                        <div class="flex-1 p-4 overflow-y-auto space-y-3" id="log-container">
                            <template x-for="log in logs" :key="log.id">
                                <div class="flex items-start gap-2.5 text-xs animate-fade-in-up">
                                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" :class="getDotColor(log.type)"></div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-bold text-gray-800" x-text="log.type"></span>
                                            <span class="text-[10px] text-gray-400 font-mono" x-text="log.time"></span>
                                        </div>
                                        <p class="text-gray-500 mt-0.5 text-[11px]" x-text="log.message"></p>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="logs.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-8 h-8 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span class="text-xs">Belum ada riwayat pemilahan</span>
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

    <!-- Script Video Player & Motion Detection -->
    <script>
        let lastMotionData = null;
        let motionState = 'IDLE';
        let stabilizeTimer = null;

        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('camera-canvas');
            const ctx = canvas.getContext('2d');
            
            const motionCanvas = document.createElement('canvas');
            motionCanvas.width = 64;
            motionCanvas.height = 48;
            const motionCtx = motionCanvas.getContext('2d', { willReadFrequently: true });

            function checkMotion() {
                const el = document.getElementById('auto-sort-container');
                if (!el || !el.__x) return;
                const alpineData = el.__x.$data;
                
                if (!alpineData.isAutoPilot || alpineData.isScanning) {
                    motionState = 'IDLE';
                    return;
                }

                motionCtx.drawImage(canvas, 0, 0, motionCanvas.width, motionCanvas.height);
                const currentData = motionCtx.getImageData(0, 0, motionCanvas.width, motionCanvas.height).data;

                if (lastMotionData) {
                    let diffCount = 0;
                    for (let i = 0; i < currentData.length; i += 4) {
                        const rDiff = Math.abs(currentData[i] - lastMotionData[i]);
                        const gDiff = Math.abs(currentData[i+1] - lastMotionData[i+1]);
                        const bDiff = Math.abs(currentData[i+2] - lastMotionData[i+2]);
                        if (rDiff + gDiff + bDiff > 60) diffCount++;
                    }

                    const changePercentage = (diffCount / (motionCanvas.width * motionCanvas.height)) * 100;

                    if (changePercentage > 3) { 
                        if (motionState === 'IDLE' || motionState === 'STABILIZING') {
                            motionState = 'MOVING';
                            clearTimeout(stabilizeTimer);
                            alpineData.lastAction = "Terdeteksi pergerakan objek sampah...";
                        }
                    } else {
                        if (motionState === 'MOVING') {
                            motionState = 'STABILIZING';
                            alpineData.lastAction = "Membidik objek (Tahan 1 detik)...";
                            
                            stabilizeTimer = setTimeout(() => {
                                if (motionState === 'STABILIZING') {
                                    motionState = 'IDLE';
                                    alpineData.scanNow();
                                }
                            }, 1000); 
                        }
                    }
                }
                lastMotionData = new Uint8ClampedArray(currentData);
            }

            function fetchNextFrame() {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                img.src = '/camera.jpg?time=' + new Date().getTime();
                
                img.onload = () => {
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    checkMotion();
                    setTimeout(fetchNextFrame, 100); 
                };
                
                img.onerror = () => {
                    setTimeout(fetchNextFrame, 150);
                };
            }
            fetchNextFrame();
        });

        function autoSortController() {
            return {
                isScanning: false,
                isAutoPilot: false,
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
                        this.showAlert('success', 'Mode Auto-Pilot Aktif. Letakkan sampah di depan kamera.');
                        this.lastAction = "Menunggu objek masuk kamera...";
                    } else {
                        this.showAlert('success', 'Mode Auto-Pilot Dinonaktifkan.');
                        this.lastAction = "Auto-Pilot mati.";
                    }
                },

                async scanNow() {
                    if (this.isScanning) return;
                    this.isScanning = true;
                    this.lastAction = "Mengekstrak fitur citra & inferensi AI...";
                    
                    try {
                        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                        const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

                        const response = await fetch('/api/ai/scan', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.status === 'success') {
                            this.lastDetection = data.detection;
                            this.lastAction = data.action;
                            
                            this.logs.unshift({
                                id: Date.now(),
                                type: data.detection,
                                message: data.action,
                                time: new Date().toLocaleTimeString()
                            });
                            
                            if (this.logs.length > 20) this.logs.pop();
                            
                            if (this.isAutoPilot) {
                                setTimeout(() => {
                                    if (this.isAutoPilot) this.lastAction = "Menunggu objek masuk kamera...";
                                }, 3000);
                            }
                        } else {
                            this.showAlert('error', data.message || 'Terjadi kesalahan sistem.');
                            if (this.isAutoPilot) this.toggleAutoPilot();
                        }
                    } catch (error) {
                        this.showAlert('error', 'Gagal menghubungi server AI.');
                        if (this.isAutoPilot) this.toggleAutoPilot();
                    } finally {
                        this.isScanning = false;
                    }
                },
                
                getDotColor(type) {
                    const colors = {
                        'KERTAS': 'bg-blue-500',
                        'PLASTIK': 'bg-amber-500',
                        'ORGANIK': 'bg-emerald-500',
                        'LOGAM': 'bg-rose-500',
                        'LOGAM_KACA': 'bg-rose-500',
                        'TIDAK_DIKETAHUI': 'bg-yellow-400'
                    };
                    return colors[type] || 'bg-indigo-500';
                }
            }
        }
    </script>
</x-app-layout>
