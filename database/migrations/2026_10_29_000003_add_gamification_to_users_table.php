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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email'); // 'user', 'admin'
            }
            if (!Schema::hasColumn('users', 'points')) {
                $table->integer('points')->default(0)->after('role');
            }
            if (!Schema::hasColumn('users', 'total_deposits')) {
                $table->integer('total_deposits')->default(0)->after('points');
            }
            if (!Schema::hasColumn('users', 'eco_badge')) {
                $table->string('eco_badge')->default('Eco Starter')->after('total_deposits');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('eco_badge');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'points', 'total_deposits', 'eco_badge', 'phone_number']);
        });
    }
};

