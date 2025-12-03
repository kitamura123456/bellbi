<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('store_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->string('title');
            $table->text('description');
            $table->unsignedSmallInteger('job_category')->index();
            $table->unsignedTinyInteger('employment_type')->index();
            $table->unsignedInteger('min_salary')->nullable();
            $table->unsignedInteger('max_salary')->nullable();
            $table->string('work_location')->nullable();
            // 0:下書き, 1:公開, 2:停止
            $table->unsignedTinyInteger('status')->default(0)->index();
            $table->timestamp('publish_start_at')->nullable();
            $table->timestamp('publish_end_at')->nullable();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};


