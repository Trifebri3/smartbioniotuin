const WebSocket = require('ws');

// Jalankan WebSocket server di port 8880
const wss = new WebSocket.Server({ port: 8880 }, () => {
    console.log('WebSocket Video Relay Server berjalan di port 8880');
});

wss.on('connection', (ws, req) => {
    console.log(`[+] Client Terhubung: ${req.socket.remoteAddress}`);

    ws.on('message', (message) => {
        // Jika data yang diterima berbentuk Buffer (Binary Gambar dari ESP32)
        if (Buffer.isBuffer(message)) {
            // Broadcast (Kirim ulang) gambar ini ke SEMUA client (browser) yang terhubung
            wss.clients.forEach((client) => {
                if (client !== ws && client.readyState === WebSocket.OPEN) {
                    client.send(message);
                }
            });
        }
    });

    ws.on('close', () => {
        console.log(`[-] Client Terputus: ${req.socket.remoteAddress}`);
    });
});
