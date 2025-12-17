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
        // job_post_imagesテーブルに不足しているカラムを追加
        if (Schema::hasTable('job_post_images')) {
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

        // store_imagesテーブルに不足しているカラムを追加
        if (Schema::hasTable('store_images')) {
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

        // company_imagesテーブルに不足しているカラムを追加
        if (Schema::hasTable('company_images')) {
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
        // カラムの削除は行わない（データ保護のため）
    }
};
