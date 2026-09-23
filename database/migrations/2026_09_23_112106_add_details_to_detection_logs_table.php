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
        Schema::table('detection_logs', function (Blueprint $table) {
            $table->decimal('confidence', 5, 2)->nullable()->after('category');
            $table->string('suggestion')->nullable()->after('confidence');
            $table->string('servo_action')->nullable()->after('suggestion');
            $table->integer('latency_ms')->nullable()->after('servo_action');
            $table->string('source', 50)->default('webcam_hybrid')->after('latency_ms');
            $table->json('raw_data')->nullable()->after('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detection_logs', function (Blueprint $table) {
            $table->dropColumn(['confidence', 'suggestion', 'servo_action', 'latency_ms', 'source', 'raw_data']);
        });
    }
};
