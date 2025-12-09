<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnUpdate();
            $table->unsignedTinyInteger('day_of_week')->index();
            $table->unsignedTinyInteger('is_open')->default(1);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();

            $table->unique(['store_id', 'day_of_week', 'delete_flg']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_schedules');
    }
};

