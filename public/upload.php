<?php
// Menerima raw binary image dari ESP32
$data = file_get_contents('php://input');
if ($data) {
    // Simpan langsung sebagai camera.jpg
    file_put_contents('camera.jpg', $data);
    echo "OK";
} else {
    http_response_code(400);
    echo "Error";
}
?>
