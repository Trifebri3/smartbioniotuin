const http = require('http');
const fs = require('fs');
const path = require('path');

const PORT = 8880;
const IMAGE_PATH = path.join(__dirname, 'public', 'camera.jpg');

const server = http.createServer((req, res) => {
    if (req.method === 'POST' && req.url === '/upload') {
        let body = [];
        
        req.on('data', chunk => {
            body.push(chunk);
        });
        
        req.on('end', () => {
            const imageBuffer = Buffer.concat(body);
            
            // Simpan gambar langsung ke folder public Laravel!
            fs.writeFile(IMAGE_PATH, imageBuffer, (err) => {
                if (err) {
                    console.error('Gagal menyimpan gambar:', err);
                    res.writeHead(500);
                    res.end('Error');
                } else {
                    res.writeHead(200);
                    res.end('OK');
                }
            });
        });
    } else {
        res.writeHead(404);
        res.end();
    }
});

server.listen(PORT, () => {
    console.log(`[+] SERVER KAMERA JALAN DI PORT ${PORT}`);
    console.log(`[+] Menyimpan gambar ke: ${IMAGE_PATH}`);
});
