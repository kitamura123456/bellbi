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
        if (!Schema::hasTable('job_post_images')) {
            Schema::create('job_post_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_post_id')->constrained()->cascadeOnDelete();
                $table->string('path');
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedTinyInteger('delete_flg')->default(0)->index();
                $table->timestamps();
            });
        } else {
            // 既存テーブルに不足しているカラムを追加
            Schema::table('job_post_images', function (Blueprint $table) {
                if (!Schema::hasColumn('job_post_images', 'job_post_id')) {
                    $table->foreignId('job_post_id')->after('id')->constrained()->cascadeOnDelete();
                }
                if (!Schema::hasColumn('job_post_images', 'path')) {
                    $table->string('path')->after('job_post_id');
                }
                if (!Schema::hasColumn('job_post_images', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0)->after('path');
                }
                if (!Schema::hasColumn('job_post_images', 'delete_flg')) {
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
        Schema::dropIfExists('job_post_images');
    }
};
