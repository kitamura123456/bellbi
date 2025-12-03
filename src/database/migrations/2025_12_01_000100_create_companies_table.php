<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate();
            $table->string('name');
            // 1:美容, 2:医療, 3:歯科, 99:その他
            $table->unsignedTinyInteger('industry_type')->index();
            $table->string('postal_code', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('tel', 50)->nullable();
            $table->unsignedBigInteger('plan_id')->nullable()->index();
            // 1:トライアル, 2:有効, 3:遅延, 9:解約
            $table->unsignedTinyInteger('plan_status')->default(1)->index();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};


