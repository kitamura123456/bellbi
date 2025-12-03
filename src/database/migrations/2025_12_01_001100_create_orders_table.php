<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->unsignedInteger('total_amount');
            // 1:新規, 2:入金確認済, 3:発送済, 4:完了, 9:キャンセル
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};


