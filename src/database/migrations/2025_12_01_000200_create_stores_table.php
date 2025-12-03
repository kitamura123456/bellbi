<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnUpdate();
            $table->string('name');
            // 1:美容室, 2:エステ, 3:医科, 4:歯科 等
            $table->unsignedTinyInteger('store_type')->index();
            $table->string('postal_code', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('tel', 50)->nullable();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};


