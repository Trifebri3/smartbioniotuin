<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servo;
use App\Models\ServoRule;
use Illuminate\Http\Request;

class ServoController extends Controller
{
    /**
     * Called by ESP32 via Polling to get current target angles
     */
    public function status()
    {
        $servos = Servo::all()->keyBy('id');
        
        return response()->json([
            'servo1' => $servos->has(1) ? $servos[1]->current_angle : 0,
            'servo2' => $servos->has(2) ? $servos[2]->current_angle : 0,
        ]);
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
