<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('staff_id')->nullable()->constrained('store_staffs')->nullOnDelete();
            $table->date('block_date')->index();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('reason')->nullable();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();

            $table->index(['store_id', 'block_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_blocks');
    }
};

