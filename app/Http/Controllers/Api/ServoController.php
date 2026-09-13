<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servo;
use App\Models\ServoRule;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class ServoController extends Controller
{
    /**
     * Called by ESP32 via Polling to get current target angles
     */
    public function status()
    {
        $servos = Servo::all()->keyBy('id');
        
        $response = [
            'servo1' => $servos->has(1) ? $servos[1]->current_angle : 0,
            'servo2' => $servos->has(2) ? $servos[2]->current_angle : 0,
        ];

        // Check if there is a pending WiFi update
        if (Cache::has('pending_wifi_ssid')) {
            $response['new_wifi'] = [
                'ssid' => Cache::get('pending_wifi_ssid'),
                'password' => Cache::get('pending_wifi_password', '')
            ];
            // Clear the cache so it only sends this instruction once
            Cache::forget('pending_wifi_ssid');
            Cache::forget('pending_wifi_password');
        }

        return response()->json($response);
    }

    public function updateWifi(Request $request)
    {
        $request->validate([
            'ssid' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
        ]);

        // Store in cache for 5 minutes (ESP32 polls every 1.5s, so it will get it quickly)
        Cache::put('pending_wifi_ssid', $request->ssid, now()->addMinutes(5));
        Cache::put('pending_wifi_password', $request->password ?? '', now()->addMinutes(5));

        return response()->json(['status' => 'success', 'message' => 'Perintah ganti WiFi dikirim ke ESP32.']);
    }

    public function setAngle(Request $request, $id)
    {
        $request->validate([
            'angle' => 'required|integer|min:0|max:180'
        ]);

        $servo = Servo::find($id) ?? new Servo();
        if (!$servo->exists) {
            $servo->id = $id;
            $servo->name = "Servo {$id}";
            $servo->pin = $id == 1 ? 26 : 27;
        }

        $servo->current_angle = $request->angle;
        $servo->save();

        // In HTTP Polling mode, we don't send anything to ESP32.
        // We just save to DB, and ESP32 will pick it up on its next poll.
        return response()->json(['status' => 'success', 'message' => "Servo {$id} target angle updated to {$request->angle} degrees. Waiting for ESP32 to poll."]);
    }

    public function saveRule(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'servo_id' => 'required|integer',
            'actions' => 'required|array',
        ]);

        $rule = new ServoRule();
        $rule->name = $request->name;
        $rule->servo_id = $request->servo_id;
        $rule->actions = $request->actions;
        $rule->save();

        return response()->json(['status' => 'success', 'message' => 'Rule saved successfully', 'data' => $rule]);
    }
}
