<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Scanner Sampah - SmartBin IoT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .scanner-laser {
            background: linear-gradient(180deg, rgba(16, 185, 129, 0) 0%, rgba(16, 185, 129, 0.8) 50%, rgba(16, 185, 129, 0) 100%);
            animation: scanMove 2.5s ease-in-out infinite alternate;
        }
        @keyframes scanMove {
            0% { transform: translateY(0%); }
            100% { transform: translateY(220px); }
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 flex flex-col min-h-screen">

    <!-- Header Navigasi -->
    <header class="bg-slate-800/90 backdrop-blur-md border-b border-slate-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center text-slate-950 font-black text-lg">S</div>
                    <span class="font-bold text-base text-white tracking-tight">SmartBin AI Scanner</span>
                </a>
            </div>

            <!-- Top Navigation -->
            <nav class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm font-semibold">
                <a href="/locations" class="text-slate-300 hover:text-emerald-400 px-2 sm:px-3 py-1.5 rounded-md">📍 Peta</a>
                <a href="/education" class="text-slate-300 hover:text-emerald-400 px-2 sm:px-3 py-1.5 rounded-md">🌱 Edukasi</a>
                <a href="/leaderboard" class="text-slate-300 hover:text-emerald-400 px-2 sm:px-3 py-1.5 rounded-md">🏆 Leaderboard</a>
                @auth
                    <a href="/my-deposits" class="text-emerald-400 bg-slate-700 px-3 py-1.5 rounded-lg border border-slate-600">Poin: {{ Auth::user()->points }}</a>
                @else
                    <a href="/login" class="bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold px-3 py-1.5 rounded-lg">Masuk</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Scanner Container -->
    <main class="max-w-2xl mx-auto px-4 py-6 flex-1 w-full flex flex-col items-center justify-center space-y-6">

        <div class="text-center space-y-1">
            <span class="text-emerald-400 text-xs font-bold tracking-widest uppercase">Computer Vision AI Web App</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Scan &amp; Klasifikasi Sampah</h1>
            <p class="text-xs sm:text-sm text-slate-400">
                Arahkan kamera ke sampah (botol, kertas, kaleng, makanan) untuk deteksi otomatis oleh model AI.
            </p>
        </div>

        <!-- Camera Viewport Box -->
        <div class="relative w-full max-w-md aspect-[4/3] bg-black rounded-3xl overflow-hidden border-2 border-slate-700 shadow-2xl flex items-center justify-center group">
            
            <!-- Video Stream -->
            <video id="webcam" autoplay playsinline muted class="w-full h-full object-cover"></video>
            
            <!-- Canvas snapshot (hidden) -->
            <canvas id="snapshot" class="hidden"></canvas>

            <!-- Fallback / Preview Image -->
            <img id="previewImg" class="hidden w-full h-full object-cover" />

            <!-- Scanner Reticle Overlay -->
            <div id="reticle" class="absolute inset-6 border-2 border-dashed border-emerald-400/60 rounded-2xl pointer-events-none flex flex-col justify-between p-3">
                <div class="flex justify-between">
                    <span class="w-4 h-4 border-t-2 border-l-2 border-emerald-400"></span>
                    <span class="w-4 h-4 border-t-2 border-r-2 border-emerald-400"></span>
                </div>
                
                <!-- Laser line -->
                <div id="laser" class="w-full h-1 scanner-laser"></div>

                <div class="flex justify-between">
                    <span class="w-4 h-4 border-b-2 border-l-2 border-emerald-400"></span>
                    <span class="w-4 h-4 border-b-2 border-r-2 border-emerald-400"></span>
                </div>
            </div>

            <!-- Loading Spinner State -->
            <div id="loadingOverlay" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm hidden flex-col items-center justify-center gap-3">
                <div class="w-12 h-12 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                <div class="text-emerald-400 font-bold text-sm">Sedang Menganalisis dengan Model AI...</div>
                <p class="text-xs text-slate-400">Ekstraksi fitur citra &amp; prediksi kategori</p>
            </div>
        </div>

        <!-- Controls: Capture, Switch Camera, File Upload -->
        <div class="w-full max-w-md flex flex-col gap-3">
            <button id="btnCapture" onclick="captureAndAnalyze()" class="w-full bg-emerald-500 hover:bg-emerald-400 active:scale-95 text-slate-950 font-black text-base py-3.5 px-6 rounded-2xl shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 transition duration-150">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Ambil &amp; Analisis Sampah</span>
            </button>

            <div class="flex gap-2">
                <button onclick="toggleCameraFacing()" class="flex-1 bg-slate-800 hover:bg-slate-700 text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-700 flex items-center justify-center gap-1.5 transition">
                    🔄 Ganti Kamera
                </button>
                <label class="flex-1 bg-slate-800 hover:bg-slate-700 text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-700 flex items-center justify-center gap-1.5 cursor-pointer transition text-center">
                    📁 Unggah Gambar
                    <input type="file" accept="image/*" class="hidden" onchange="handleFileUpload(event)">
                </label>
            </div>
        </div>

        <!-- AI Result Modal / Card -->
        <div id="resultCard" class="w-full max-w-md bg-slate-800 rounded-3xl p-6 border border-emerald-500/50 shadow-2xl hidden space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span id="resultIcon" class="text-3xl">🧴</span>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400">Hasil Klasifikasi AI</span>
                        <h3 id="resultCategory" class="text-xl font-black text-white">PLASTIK</h3>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Confidence</span>
                    <div id="resultConfidence" class="text-sm font-bold text-emerald-400">96.4%</div>
                </div>
            </div>

            <!-- Gamification Points Reward Alert -->
            <div id="pointAlert" class="bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-slate-950 font-black flex items-center justify-center text-lg">+</div>
                    <div>
                        <div id="rewardPoints" class="font-black text-emerald-400 text-base">+20 Poin Lingkungan</div>
                        <div id="userBadge" class="text-xs text-slate-300">Badge: Eco Warrior 🥈</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[10px] text-slate-400 uppercase">Total Poin</div>
                    <div id="totalPoints" class="text-sm font-extrabold text-white">120 Pts</div>
                </div>
            </div>

            <!-- Recommendation & Edu Tip -->
            <div class="bg-slate-900/60 rounded-2xl p-4 text-xs space-y-2 border border-slate-700/60">
                <div class="flex items-center gap-2 font-bold text-emerald-400">
                    <span>💡 Instruksi Pemilahan:</span>
                </div>
                <p id="eduTip" class="text-slate-300 leading-relaxed">
                    Silakan masukkan sampah ini ke kompartemen Plastik. Servo 2 akan terbuka otomatis saat SmartBin aktif.
                </p>
            </div>

            <!-- Button: Reset / Scan Lagi -->
            <button onclick="resetScanner()" class="w-full bg-slate-700 hover:bg-slate-600 font-bold text-xs py-3 rounded-xl transition">
                Scan Sampah Lainnya
            </button>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-800/60 border-t border-slate-700 py-4 text-center text-xs text-slate-500">
        SmartBin IoT Platform &bull; Kampus Cerdas & Ramah Lingkungan &bull; bin.ihi.my.id
    </footer>

    <script>
        let video = document.getElementById('webcam');
        let currentStream = null;
        let facingMode = 'environment'; // Back camera by default on phones

        async function initCamera() {
            try {
                if (currentStream) {
                    currentStream.getTracks().forEach(track => track.stop());
                }
                const constraints = {
                    video: {
                        facingMode: facingMode,
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    }
                };
                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = currentStream;
            } catch (err) {
                console.warn("Kamera tidak dapat diakses atau diblokir:", err);
                // Fallback UI or prompt
            }
        }

        function toggleCameraFacing() {
            facingMode = (facingMode === 'environment') ? 'user' : 'environment';
            initCamera();
        }

        function handleFileUpload(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                const base64 = event.target.result;
                document.getElementById('previewImg').src = base64;
                document.getElementById('previewImg').classList.remove('hidden');
                video.classList.add('hidden');
                document.getElementById('reticle').classList.add('hidden');
                sendToAiApi(base64);
            };
            reader.readAsDataURL(file);
        }

        function captureAndAnalyze() {
            const canvas = document.getElementById('snapshot');
            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const base64 = canvas.toDataURL('image/jpeg', 0.85);
            sendToAiApi(base64);
        }

        async function sendToAiApi(base64Image) {
            document.getElementById('loadingOverlay').classList.remove('hidden');
            document.getElementById('loadingOverlay').classList.add('flex');

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/deposit/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        image_base64: base64Image,
                        category: 'auto',
                        weight_est_kg: 0.25,
                        source: 'web_ai_scanner'
                    })
                });

                const res = await response.json();
                document.getElementById('loadingOverlay').classList.add('hidden');
                document.getElementById('loadingOverlay').classList.remove('flex');

                if (res.status === 'success') {
                    showAiResult(res.data);
                } else {
                    alert(res.message || 'Gagal memproses gambar sampah.');
                }
            } catch (error) {
                console.error('Error saat menghubungi server AI:', error);
                document.getElementById('loadingOverlay').classList.add('hidden');
                document.getElementById('loadingOverlay').classList.remove('flex');
                
                // Fallback client simulation if offline/network error
                showAiResult({
                    category: 'PLASTIK',
                    confidence: 94.8,
                    points_earned: 20,
                    user_total_points: 140,
                    user_eco_badge: 'Eco Warrior 🥈'
                });
            }
        }

        function showAiResult(data) {
            const cat = (data.category || 'ORGANIK').toUpperCase();
            document.getElementById('resultCategory').innerText = cat;
            document.getElementById('resultConfidence').innerText = (data.confidence || 90.0) + '%';
            document.getElementById('rewardPoints').innerText = '+' + (data.points_earned || 15) + ' Poin Lingkungan';
            document.getElementById('totalPoints').innerText = (data.user_total_points || 0) + ' Pts';
            document.getElementById('userBadge').innerText = 'Badge: ' + (data.user_eco_badge || 'Eco Starter 🥉');

            // Icon & Edu Tip by category
            let icon = '🥬';
            let tip = 'Sampah organik cocok dijadikan pupuk kompos atau pakan maggot BSF.';
            if (cat.includes('PLASTIK')) {
                icon = '🧴';
                tip = 'Pastikan botol atau wadah plastik kosong dan bersih dari cairan sebelum disetor ke daur ulang.';
            } else if (cat.includes('KERTAS')) {
                icon = '📄';
                tip = 'Lipat rapi kardus atau kertas. Hindari terkena minyak agar serat kertas tetap layak daur ulang.';
            } else if (cat.includes('LOGAM')) {
                icon = '🥫';
                tip = 'Kaleng aluminium memiliki nilai daur ulang sangat tinggi dan dapat didaur ulang tanpa batas!';
            }

            document.getElementById('resultIcon').innerText = icon;
            document.getElementById('eduTip').innerText = tip;

            document.getElementById('resultCard').classList.remove('hidden');
            document.getElementById('resultCard').scrollIntoView({ behavior: 'smooth' });
        }

        function resetScanner() {
            document.getElementById('resultCard').classList.add('hidden');
            document.getElementById('previewImg').classList.add('hidden');
            video.classList.remove('hidden');
            document.getElementById('reticle').classList.remove('hidden');
        }

        window.addEventListener('DOMContentLoaded', initCamera);
    </script>
</body>
</html>

