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
        if (!Schema::hasTable('company_images')) {
            Schema::create('company_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained()->cascadeOnDelete();
                $table->string('path');
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedTinyInteger('delete_flg')->default(0)->index();
                $table->timestamps();
            });
        } else {
            // 既存テーブルに不足しているカラムを追加
            Schema::table('company_images', function (Blueprint $table) {
                if (!Schema::hasColumn('company_images', 'company_id')) {
                    $table->foreignId('company_id')->after('id')->constrained()->cascadeOnDelete();
                }
                if (!Schema::hasColumn('company_images', 'path')) {
                    $table->string('path')->after('company_id');
                }
                if (!Schema::hasColumn('company_images', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0)->after('path');
                }
                if (!Schema::hasColumn('company_images', 'delete_flg')) {
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
        Schema::dropIfExists('company_images');
    }
};
