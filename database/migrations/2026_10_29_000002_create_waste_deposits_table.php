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
        Schema::create('waste_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('smart_bin_id')->nullable()->constrained('smart_bins')->nullOnDelete();
            $table->string('category'); // organik, plastik, kertas, logam_kaca
            $table->integer('points_earned')->default(10);
            $table->decimal('weight_est_kg', 6, 2)->default(0.25);
            $table->string('photo_path')->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->string('source')->default('mobile_qr'); // mobile_qr, direct_bin, web_ai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_deposits');
    }
};

