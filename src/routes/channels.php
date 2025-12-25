<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::find($conversationId);
    
    if (!$conversation) {
        return false;
    }
    
    // 会話の参加者（ユーザーまたは会社のメンバー）かどうかを確認
    if ($conversation->user_id === $user->id) {
        return true;
    }
    
    if ($conversation->company_id && $user->company && $user->company->id === $conversation->company_id) {
        return true;
    }
    
    return false;
});

