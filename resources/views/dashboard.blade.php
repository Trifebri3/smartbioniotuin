<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Smart Waste Sorter Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="cameraStream()" x-init="initWebSocket()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Camera Configuration -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">ESP32 Camera (Public Stream)</h3>
                        <p class="mt-1 text-sm text-gray-600">Terhubung ke Relay WebSocket untuk melihat kamera dari mana saja.</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium" 
                              :class="connected ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                              x-text="connected ? 'Connected' : 'Disconnected'">
                        </span>
                    </div>
                </div>
            </div>

            <!-- Live Camera Feed -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Live Camera Stream</h3>
                    <div class="relative w-full rounded-lg bg-gray-900 flex items-center justify-center overflow-hidden" style="min-height: 480px;">
                        <img x-show="frameUrl" :src="frameUrl" alt="Live ESP32 Camera Stream" class="w-full max-w-3xl object-contain rounded" style="display: none;"/>
                        
                        <div x-show="!connected && !frameUrl" class="text-gray-400">
                            Menunggu koneksi dari kamera ESP32...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine Component for WebSocket -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cameraStream', () => ({
                connected: false,
                frameUrl: null,
                ws: null,
                
                initWebSocket() {
                    // GANTI ws://bin.ihi.my.id:8080 menjadi wss:// jika menggunakan HTTPS+SSL proxy
                    const serverUrl = 'ws://bin.ihi.my.id:8080';
                    
                    this.ws = new WebSocket(serverUrl);
                    this.ws.binaryType = 'blob';

                    this.ws.onopen = () => {
                        console.log('Connected to WebSocket Relay Server');
                        this.connected = true;
                    };

                    this.ws.onmessage = (event) => {
                        // Menerima frame berupa Blob dari server
                        if (event.data instanceof Blob) {
                            if (this.frameUrl) {
                                URL.revokeObjectURL(this.frameUrl); // Bersihkan URL lama
                            }
                            this.frameUrl = URL.createObjectURL(event.data);
                        }
                    };

                    this.ws.onclose = () => {
                        console.log('Disconnected from server. Retrying...');
                        this.connected = false;
                        setTimeout(() => this.initWebSocket(), 3000);
                    };
                    
                    this.ws.onerror = (err) => {
                        console.error('WebSocket Error:', err);
                        this.ws.close();
                    };
                }
            }))
        })
    </script>
</x-app-layout>
