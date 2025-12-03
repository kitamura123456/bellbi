<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->unsignedTinyInteger('prefecture_code')->nullable()->after('employment_type')->index();
            $table->string('city', 100)->nullable()->after('prefecture_code');
            // work_location は互換性のため残す（後で削除してもOK）
        });
    }

    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropColumn(['prefecture_code', 'city']);
        });
    }
};

