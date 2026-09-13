<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 leading-tight drop-shadow-sm">
                {{ __('Kontrol & Debug Servo') }}
            </h2>
            <div class="flex items-center space-x-2">
                <span class="flex h-3 w-3 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-sm font-medium text-gray-500">Sistem Aktif</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen" x-data="servoController()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Dashboard Panels -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Servo 1 Panel -->
                <div class="bg-white/80 backdrop-blur-xl border border-gray-100 overflow-hidden shadow-xl shadow-indigo-100/50 sm:rounded-2xl p-8 relative group hover:shadow-2xl hover:shadow-indigo-200/50 transition-all duration-500 transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-green-400 to-emerald-600"></div>
                    
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-green-50 text-green-600 rounded-xl shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Servo 1</h3>
                        </div>
                        <span class="px-4 py-1.5 bg-green-50 text-green-700 rounded-full text-xs font-bold uppercase tracking-wider border border-green-200 shadow-sm">GPIO D26</span>
                    </div>
                    
                    <div class="text-center py-10 relative">
                        <div class="absolute inset-0 flex items-center justify-center opacity-5">
                            <svg class="w-48 h-48 text-green-900" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle></svg>
                        </div>
                        <div class="text-7xl font-black text-transparent bg-clip-text bg-gradient-to-br from-gray-700 to-gray-900 tracking-tighter drop-shadow-sm transition-all duration-300" x-text="servo1Angle + '°'">0°</div>
                    </div>

                    <div class="relative pt-1">
                        <input type="range" min="0" max="180" x-model="servo1Angle" @change="setServo(1, servo1Angle)" 
                            class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-500 hover:accent-green-600 transition-all shadow-inner">
                        <div class="flex justify-between text-xs text-gray-400 font-medium mt-2 px-1">
                            <span>0°</span>
                            <span>90°</span>
                            <span>180°</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3 mt-8">
                        <template x-for="angle in [0, 45, 90, 135, 180]">
                            <button @click="servo1Angle = angle; setServo(1, angle)" 
                                class="bg-white border border-gray-200 hover:border-green-400 hover:bg-green-50 text-gray-600 hover:text-green-700 font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-all duration-200 active:scale-95" 
                                x-text="angle + '°'"></button>
                        </template>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-center space-x-2 text-sm font-medium transition-colors duration-300" 
                         :class="{'text-green-600': servo1Status === 'Ready', 'text-yellow-600': servo1Status !== 'Ready'}">
                        <svg x-show="servo1Status === 'Ready'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <svg x-show="servo1Status !== 'Ready'" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span x-text="servo1Status">Ready</span>
                    </div>
                </div>

                <!-- Servo 2 Panel -->
                <div class="bg-white/80 backdrop-blur-xl border border-gray-100 overflow-hidden shadow-xl shadow-purple-100/50 sm:rounded-2xl p-8 relative group hover:shadow-2xl hover:shadow-purple-200/50 transition-all duration-500 transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-400 to-indigo-600"></div>
                    
                    <div class="flex justify-between items-center mb-6">
                         <div class="flex items-center space-x-3">
                            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 tracking-tight">Servo 2</h3>
                        </div>
                        <span class="px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold uppercase tracking-wider border border-indigo-200 shadow-sm">GPIO D27</span>
                    </div>
                    
                    <div class="text-center py-10 relative">
                        <div class="absolute inset-0 flex items-center justify-center opacity-5">
                            <svg class="w-48 h-48 text-indigo-900" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle></svg>
                        </div>
                        <div class="text-7xl font-black text-transparent bg-clip-text bg-gradient-to-br from-gray-700 to-gray-900 tracking-tighter drop-shadow-sm transition-all duration-300" x-text="servo2Angle + '°'">0°</div>
                    </div>

                    <div class="relative pt-1">
                        <input type="range" min="0" max="180" x-model="servo2Angle" @change="setServo(2, servo2Angle)" 
                            class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-500 hover:accent-indigo-600 transition-all shadow-inner">
                        <div class="flex justify-between text-xs text-gray-400 font-medium mt-2 px-1">
                            <span>0°</span>
                            <span>90°</span>
                            <span>180°</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3 mt-8">
                        <template x-for="angle in [0, 45, 90, 135, 180]">
                            <button @click="servo2Angle = angle; setServo(2, angle)" 
                                class="bg-white border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 text-gray-600 hover:text-indigo-700 font-semibold py-2.5 px-4 rounded-xl shadow-sm transition-all duration-200 active:scale-95" 
                                x-text="angle + '°'"></button>
                        </template>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-center space-x-2 text-sm font-medium transition-colors duration-300"
                         :class="{'text-indigo-600': servo2Status === 'Ready', 'text-yellow-600': servo2Status !== 'Ready'}">
                        <svg x-show="servo2Status === 'Ready'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <svg x-show="servo2Status !== 'Ready'" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span x-text="servo2Status">Ready</span>
                    </div>
                </div>
            </div>

            <!-- Rules / Sequence Builder -->
            <div class="bg-white/90 backdrop-blur-xl border border-gray-100 overflow-hidden shadow-xl shadow-gray-200/50 sm:rounded-2xl p-8 relative">
                <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                     <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>

                <div class="mb-8">
                    <h3 class="text-2xl font-extrabold text-gray-800 flex items-center">
                        <span class="bg-gradient-to-r from-indigo-500 to-purple-600 text-transparent bg-clip-text">Pembuat Aturan (Sequence Builder)</span>
                    </h3>
                    <p class="text-sm text-gray-500 mt-2 font-medium">Buat urutan pergerakan cerdas untuk servo Anda. Atur sudut, beri jeda waktu, dan uji coba secara real-time.</p>
                </div>
                
                <div class="flex flex-col md:flex-row gap-6 mb-8 relative z-10">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Target Servo</label>
                        <select x-model="ruleServoId" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 text-gray-700 font-medium py-3">
                            <option value="1">Servo 1 (D26)</option>
                            <option value="2">Servo 2 (D27)</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Nama Aturan</label>
                        <input type="text" x-model="ruleName" placeholder="Contoh: Buka Tutup Cepat" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 py-3">
                    </div>
                </div>

                <!-- Sequence Timeline -->
                <div class="bg-gray-50/50 p-6 rounded-2xl mb-8 border border-gray-100 min-h-[120px] shadow-inner relative z-10">
                    <div class="flex flex-wrap items-center gap-3">
                        <template x-for="(action, index) in ruleActions" :key="index">
                            <div class="flex items-center">
                                <div class="group relative flex items-center bg-white border border-gray-200 rounded-xl pl-4 pr-2 py-2 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all duration-300">
                                    <div class="mr-3 text-indigo-500">
                                        <svg x-show="action.type === 'angle'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <svg x-show="action.type === 'delay'" class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 mr-3" x-text="action.type === 'angle' ? action.value + '°' : action.value + 'ms'"></span>
                                    <button @click="removeAction(index)" class="text-gray-300 hover:text-red-500 p-1 bg-gray-50 hover:bg-red-50 rounded-lg transition-colors focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <div x-show="index < ruleActions.length - 1" class="mx-3 text-indigo-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="ruleActions.length === 0" class="flex flex-col items-center justify-center w-full py-6 text-gray-400">
                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span class="text-sm font-medium">Timeline kosong. Tambahkan aksi di bawah.</span>
                        </div>
                    </div>
                </div>

                <!-- Action Controls -->
                <div class="flex flex-wrap md:flex-nowrap gap-4 items-end relative z-10 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Pilih Aksi</label>
                        <select x-model="tempActionType" class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                            <option value="angle">Ubah Sudut (°)</option>
                            <option value="delay">Tunda Waktu (ms)</option>
                        </select>
                    </div>
                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">Nilai</label>
                        <input type="number" x-model="tempActionValue" class="w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 md:w-28 text-center" placeholder="0">
                    </div>
                    <button @click="addAction" class="w-full md:w-auto bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-2.5 px-5 rounded-lg border border-indigo-200 transition-all duration-200 active:scale-95 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah
                    </button>
                    
                    <div class="flex-grow hidden md:block"></div>
                    
                    <div class="w-full md:w-auto flex gap-3 mt-4 md:mt-0">
                        <button @click="executeRule" class="flex-1 md:flex-none bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-500 hover:to-orange-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg shadow-orange-200 transition-all duration-200 active:scale-95 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Uji Coba
                        </button>
                        <button @click="saveRule" class="flex-1 md:flex-none bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg shadow-indigo-200 transition-all duration-200 active:scale-95 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Notification Toast -->
    <div id="toast" class="fixed bottom-5 right-5 transform transition-all duration-300 translate-y-20 opacity-0 bg-gray-900 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center z-50">
        <div id="toast-icon" class="mr-3"></div>
        <div id="toast-message" class="font-medium text-sm"></div>
    </div>

    <!-- Alpine.js script for logic -->
    <script>
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');
            
            toastMessage.textContent = message;
            if (type === 'success') {
                toastIcon.innerHTML = '<svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            } else {
                toastIcon.innerHTML = '<svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }

            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('servoController', () => ({
                servo1Angle: 0,
                servo2Angle: 0,
                servo1Status: 'Ready',
                servo2Status: 'Ready',

                ruleServoId: '1',
                ruleName: '',
                ruleActions: [],
                tempActionType: 'angle',
                tempActionValue: 0,

                async setServo(servoId, angle) {
                    if(servoId === 1) this.servo1Status = 'Menyimpan...';
                    if(servoId === 2) this.servo2Status = 'Menyimpan...';
                    
                    try {
                        const response = await fetch(`/api/servo/${servoId}/set`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ angle: angle })
                        });
                        
                        if(response.ok) {
                            if(servoId === 1) this.servo1Status = 'Tersimpan';
                            if(servoId === 2) this.servo2Status = 'Tersimpan';
                        } else {
                            if(servoId === 1) this.servo1Status = 'Gagal';
                            if(servoId === 2) this.servo2Status = 'Gagal';
                        }
                    } catch (error) {
                        if(servoId === 1) this.servo1Status = 'Error Koneksi';
                        if(servoId === 2) this.servo2Status = 'Error Koneksi';
                    }
                    
                    setTimeout(() => {
                        if(servoId === 1) this.servo1Status = 'Ready';
                        if(servoId === 2) this.servo2Status = 'Ready';
                    }, 2000);
                },

                addAction() {
                    if (this.tempActionValue === '' || this.tempActionValue === null) return;
                    this.ruleActions.push({
                        type: this.tempActionType,
                        value: parseInt(this.tempActionValue)
                    });
                    this.tempActionValue = 0;
                },

                removeAction(index) {
                    this.ruleActions.splice(index, 1);
                },

                async executeRule() {
                    if (this.ruleActions.length === 0) return showToast('Tambahkan aksi terlebih dahulu!', 'error');
                    
                    for (let action of this.ruleActions) {
                        if (action.type === 'angle') {
                            await this.setServo(parseInt(this.ruleServoId), action.value);
                            if (this.ruleServoId === '1') this.servo1Angle = action.value;
                            if (this.ruleServoId === '2') this.servo2Angle = action.value;
                        } else if (action.type === 'delay') {
                            if (this.ruleServoId === '1') this.servo1Status = `Tunda ${action.value}ms...`;
                            if (this.ruleServoId === '2') this.servo2Status = `Tunda ${action.value}ms...`;
                            await new Promise(resolve => setTimeout(resolve, action.value));
                        }
                    }
                    showToast('Uji coba sequence selesai!');
                },

                async saveRule() {
                    if (!this.ruleName) return showToast('Nama aturan tidak boleh kosong!', 'error');
                    if (this.ruleActions.length === 0) return showToast('Tambahkan aksi terlebih dahulu!', 'error');

                    try {
                        const response = await fetch('/api/servo/rules', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                name: this.ruleName,
                                servo_id: this.ruleServoId,
                                actions: this.ruleActions
                            })
                        });
                        
                        if (response.ok) {
                            showToast('Aturan berhasil disimpan!');
                            this.ruleName = '';
                            this.ruleActions = [];
                        } else {
                            showToast('Gagal menyimpan aturan.', 'error');
                        }
                    } catch (error) {
                        showToast('Error koneksi ke server.', 'error');
                    }
                }
            }));
        });
    </script>
</x-app-layout>
