<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scout_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_company_id')->constrained('companies')->cascadeOnUpdate();
            $table->foreignId('from_store_id')->nullable()->constrained('stores')->cascadeOnUpdate();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnUpdate();
            $table->foreignId('scout_profile_id')->constrained('scout_profiles')->cascadeOnUpdate();
            // 1:送信, 2:既読, 3:返信あり, 9:クローズ
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->string('subject');
            $table->text('body');
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scout_messages');
    }
};


