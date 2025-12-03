<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scout_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate();
            $table->unsignedTinyInteger('industry_type')->index();
            $table->unsignedSmallInteger('desired_job_category')->nullable()->index();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->unsignedTinyInteger('desired_work_style')->nullable();
            $table->unsignedTinyInteger('is_public')->default(1)->index();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scout_profiles');
    }
};


