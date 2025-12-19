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
        Schema::create('subsidies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // 補助金種別（1:設備投資, 2:人材確保, 3:事業継続, 4:その他）
            $table->unsignedSmallInteger('category')->nullable()->index();
            // 対象地域（都道府県コード、NULLは全国対象）
            $table->unsignedSmallInteger('target_region')->nullable()->index();
            // 対象業種（1:美容, 2:医療, 3:歯科, NULLは全業種）
            $table->unsignedSmallInteger('applicable_industry_type')->nullable()->index();
            $table->dateTime('application_start_at')->nullable();
            $table->dateTime('application_end_at')->nullable();
            // 1:募集中, 2:締切, 3:未開始
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->text('summary')->nullable();
            $table->string('detail_url')->nullable();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsidies');
    }
};
