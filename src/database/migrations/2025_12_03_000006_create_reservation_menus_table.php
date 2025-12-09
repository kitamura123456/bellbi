<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_menu_id')->constrained()->cascadeOnUpdate();
            $table->string('menu_name');
            $table->unsignedSmallInteger('duration_minutes');
            $table->unsignedInteger('price');
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_menus');
    }
};

