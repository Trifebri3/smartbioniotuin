<?php

namespace App\Http\Controllers;

use App\Models\Servo;
use Illuminate\Http\Request;

class ServoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('servos.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Servo $servo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servo $servo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servo $servo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servo $servo)
    {
        //
    }
}
