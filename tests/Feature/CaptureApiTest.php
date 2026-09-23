<?php

namespace Tests\Feature;

use App\Models\DetectionLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CaptureApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_monitor_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SmartBin');
        $response->assertSee('bin.ihi.my.id');
    }

    public function test_public_summary_api_returns_structured_data(): void
    {
        $response = $this->getJson('/api/public/summary');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'latest',
            'recentCaptures',
            'bins' => [
                '1', '2', '3', '4'
            ],
            'servos' => ['servo1', 'servo2'],
            'stats' => ['total', 'today', 'avg_confidence', 'categories'],
            'server_time',
            'system_online',
        ]);
    }

    public function test_capture_upload_endpoint_records_detection(): void
    {
        $file = UploadedFile::fake()->image('test_waste.jpg', 640, 480);

        $payload = [
            'image' => $file,
            'category' => 'plastik',
            'confidence' => 98.4,
            'suggestion' => 'Wadah BIRU (Botol, Kantong, Gelas Plastik)',
            'latency_ms' => 65,
            'source' => 'webcam_hybrid',
        ];

        $response = $this->postJson('/api/captures/upload', $payload);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'servo' => [
                'servo1' => 180,
                'servo2' => 50,
            ]
        ]);

        $this->assertDatabaseHas('detection_logs', [
            'category' => 'plastik',
            'confidence' => 98.4,
        ]);
    }

    public function test_recent_captures_api_returns_list(): void
    {
        DetectionLog::create([
            'category' => 'kertas',
            'confidence' => 95.0,
            'suggestion' => 'Wadah Kuning',
            'image_path' => 'detections/test.jpg',
        ]);

        $response = $this->getJson('/api/captures/recent');
        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $response->assertJsonCount(1, 'data');
    }
}
