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
        Schema::create('ecotheology_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('theme_tag')->default('Ekoteologi Islam');
            $table->string('waste_category')->default('all'); // all, organik, plastik, kertas, logam
            $table->text('arabic_text');
            $table->text('translation');
            $table->string('reference');
            $table->text('reflection');
            $table->text('action');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecotheology_contents');
    }
};
