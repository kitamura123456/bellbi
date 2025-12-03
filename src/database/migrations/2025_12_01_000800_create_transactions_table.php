<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('store_id')->constrained()->cascadeOnUpdate();
            $table->date('date');
            $table->foreignId('account_item_id')->constrained('account_items')->cascadeOnUpdate();
            $table->integer('amount');
            $table->integer('tax_amount')->default(0);
            // 1:売上, 2:経費
            $table->unsignedTinyInteger('transaction_type')->index();
            // 1:手入力, 2:EC連携, 3:外部API 等
            $table->unsignedTinyInteger('source_type')->default(1)->index();
            $table->text('note')->nullable();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};


