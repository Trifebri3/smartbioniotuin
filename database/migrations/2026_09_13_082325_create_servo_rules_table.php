<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servo_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servo_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->json('actions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servo_rules');
    }
};
