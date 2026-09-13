const http = require('http');
const WebSocket = require('ws');

// 1. Buat HTTP Server biasa untuk menerima gambar dari ESP32
const server = http.createServer((req, res) => {
    if (req.method === 'POST' && req.url === '/upload') {
        let body = [];
        req.on('data', chunk => body.push(chunk));
        req.on('end', () => {
            const imageBuffer = Buffer.concat(body);
            // Broadcast ke semua browser via WebSocket!
            wss.clients.forEach(client => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(imageBuffer);
                }
            });
            res.writeHead(200);
            res.end('OK');
        });
    } else {
        res.writeHead(404);
        res.end();
    }
});

// 2. Tumpangkan WebSocket Server di atas HTTP Server untuk Browser
const wss = new WebSocket.Server({ server });

wss.on('connection', (ws) => {
    console.log('[+] Dashboard Browser Terhubung');
});

server.listen(8880, () => {
    console.log('Server Pamungkas Berjalan di Port 8880!');
});
