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
        Schema::create('smart_bins', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('location_name');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('capacity_organik', 5, 1)->default(0.0);
            $table->decimal('capacity_plastik', 5, 1)->default(0.0);
            $table->decimal('capacity_kertas', 5, 1)->default(0.0);
            $table->decimal('capacity_logam', 5, 1)->default(0.0);
            $table->enum('status', ['active', 'full', 'maintenance'])->default('active');
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_bins');
    }
};

