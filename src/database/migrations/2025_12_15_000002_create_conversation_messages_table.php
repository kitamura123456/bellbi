<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            // sender_type: 'user' または 'company'
            $table->string('sender_type', 20);
            $table->unsignedBigInteger('sender_id');
            $table->text('body');
            $table->unsignedTinyInteger('read_flg')->default(0)->index();
            $table->unsignedTinyInteger('delete_flg')->default(0)->index();
            $table->timestamps();
            
            $table->index(['conversation_id', 'created_at']);
            $table->index(['sender_type', 'sender_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_messages');
    }
};

