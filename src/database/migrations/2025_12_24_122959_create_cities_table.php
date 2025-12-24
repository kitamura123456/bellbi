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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->integer('todofuken_code')->comment('都道府県コード');
            $table->string('todofuken_name')->comment('都道府県名');
            $table->string('city_code')->nullable()->comment('市区町村コード');
            $table->string('city_name')->comment('市区町村名');
            $table->string('city_kana')->nullable()->comment('市区町村名カナ');
            $table->timestamps();
            
            $table->index('todofuken_code');
            $table->index('city_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
