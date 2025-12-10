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
        Schema::table('store_schedules', function (Blueprint $table) {
            $table->integer('max_concurrent_reservations')->default(1)->after('close_time')->comment('同時対応可能予約数');
        });
        
        // storesテーブルからmax_concurrent_reservationsを削除
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('max_concurrent_reservations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // storesテーブルにmax_concurrent_reservationsを戻す
        Schema::table('stores', function (Blueprint $table) {
            $table->integer('max_concurrent_reservations')->default(1)->after('cancel_deadline_hours');
        });
        
        Schema::table('store_schedules', function (Blueprint $table) {
            $table->dropColumn('max_concurrent_reservations');
        });
    }
};
