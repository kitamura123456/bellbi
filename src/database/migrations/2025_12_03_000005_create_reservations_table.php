<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('staff_id')->nullable()->constrained('store_staffs')->nullOnDelete();
            $table->date('reservation_date')->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('total_duration_minutes')->default(0);
            $table->unsignedInteger('total_price')->default(0);
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->text('customer_note')->nullable();
            $table->text('store_note')->nullable();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();

            $table->index(['store_id', 'reservation_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

