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
        if (!Schema::hasTable('store_images')) {
            Schema::create('store_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained()->cascadeOnDelete();
                $table->string('path');
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedTinyInteger('delete_flg')->default(0)->index();
                $table->timestamps();
            });
        } else {
            // 既存テーブルに不足しているカラムを追加
            Schema::table('store_images', function (Blueprint $table) {
                if (!Schema::hasColumn('store_images', 'store_id')) {
                    $table->foreignId('store_id')->after('id')->constrained()->cascadeOnDelete();
                }
                if (!Schema::hasColumn('store_images', 'path')) {
                    $table->string('path')->after('store_id');
                }
                if (!Schema::hasColumn('store_images', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0)->after('path');
                }
                if (!Schema::hasColumn('store_images', 'delete_flg')) {
                    $table->unsignedTinyInteger('delete_flg')->default(0)->index()->after('sort_order');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_images');
    }
};
