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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('viewed_at')->nullable()->after('status');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->timestamp('viewed_at')->nullable()->after('status');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->timestamp('viewed_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });
    }
};
