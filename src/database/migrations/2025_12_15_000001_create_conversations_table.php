<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('company_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('job_application_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->foreignId('scout_message_id')->nullable()->constrained()->cascadeOnUpdate();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
            
            // 応募またはスカウトのどちらか一方のみが設定されることを保証
            $table->index(['user_id', 'company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

