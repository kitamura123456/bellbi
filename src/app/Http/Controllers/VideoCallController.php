<?php

namespace App\Http\Controllers;

use App\Events\VideoCallAccepted;
use App\Events\VideoCallDeclined;
use App\Events\VideoCallEnded;
use App\Events\VideoCallInitiated;
use App\Events\VideoCallSignal;
use App\Models\Conversation;
use App\Models\VideoCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VideoCallController extends Controller
{
    /**
     * ビデオ通話を開始
     */
    public function store(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        // 会話へのアクセス権限を確認
        $this->authorizeConversationAccess($user, $conversation);

        // 既にアクティブな通話があるか確認
        $activeCall = $conversation->activeVideoCall;
        if ($activeCall) {
            return response()->json([
                'message' => '既にアクティブな通話があります',
                'video_call_id' => $activeCall->id,
            ], 409);
        }

        // 受信者を特定
        $recipientId = $this->getRecipientId($user, $conversation);
        
        if (!$recipientId) {
            return response()->json([
                'message' => '受信者を特定できませんでした',
            ], 400);
        }

        // ビデオ通話を作成
        $videoCall = VideoCall::create([
            'conversation_id' => $conversation->id,
            'initiator_id' => $user->id,
            'recipient_id' => $recipientId,
            'status' => 'pending',
        ]);

        // WebSocketイベントを送信
        broadcast(new VideoCallInitiated($videoCall))->toOthers();

        return response()->json([
            'message' => 'ビデオ通話を開始しました',
            'video_call' => [
                'id' => $videoCall->id,
                'conversation_id' => $videoCall->conversation_id,
                'status' => $videoCall->status,
            ],
        ], 201);
    }

    /**
     * ビデオ通話を受ける
     */
    public function accept(VideoCall $videoCall)
    {
        $user = Auth::user();

        if ($videoCall->recipient_id !== $user->id) {
            abort(403, 'この通話に応答する権限がありません');
        }

        if ($videoCall->status !== 'pending') {
            return response()->json([
                'message' => 'この通話は既に処理されています',
            ], 400);
        }

        $videoCall->start();

        // WebSocketイベントを送信
        broadcast(new VideoCallAccepted($videoCall))->toOthers();

        return response()->json([
            'message' => 'ビデオ通話を開始しました',
            'video_call' => [
                'id' => $videoCall->id,
                'status' => $videoCall->status,
            ],
        ]);
    }

    /**
     * ビデオ通話を拒否
     */
    public function reject(VideoCall $videoCall)
    {
        $user = Auth::user();

        if ($videoCall->recipient_id !== $user->id) {
            abort(403, 'この通話を拒否する権限がありません');
        }

        if ($videoCall->status !== 'pending') {
            return response()->json([
                'message' => 'この通話は既に処理されています',
            ], 400);
        }

        $videoCall->decline();

        // WebSocketイベントを送信
        broadcast(new VideoCallDeclined($videoCall))->toOthers();

        return response()->json([
            'message' => 'ビデオ通話を拒否しました',
            'video_call' => [
                'id' => $videoCall->id,
                'status' => $videoCall->status,
            ],
        ]);
    }

    /**
     * ビデオ通話を終了
     */
    public function end(VideoCall $videoCall)
    {
        $user = Auth::user();

        // 通話の参加者のみが終了できる
        if ($videoCall->initiator_id !== $user->id && $videoCall->recipient_id !== $user->id) {
            abort(403, 'この通話を終了する権限がありません');
        }

        if ($videoCall->status === 'ended' || $videoCall->status === 'declined') {
            return response()->json([
                'message' => 'この通話は既に終了しています',
            ], 400);
        }

        $videoCall->end();

        // WebSocketイベントを送信
        broadcast(new VideoCallEnded($videoCall))->toOthers();

        return response()->json([
            'message' => 'ビデオ通話を終了しました',
            'video_call' => [
                'id' => $videoCall->id,
                'status' => $videoCall->status,
                'duration_seconds' => $videoCall->duration_seconds,
            ],
        ]);
    }

    /**
     * WebRTCシグナルを送信
     */
    public function signal(Request $request, VideoCall $videoCall)
    {
        $user = Auth::user();

        // 通話の参加者のみがシグナルを送信できる
        if ($videoCall->initiator_id !== $user->id && $videoCall->recipient_id !== $user->id) {
            abort(403, 'この通話にシグナルを送信する権限がありません');
        }

        $validated = $request->validate([
            'signal' => ['required'],
        ]);

        // WebSocketイベントを送信（送信者自身には送らない）
        broadcast(new VideoCallSignal($videoCall, $user->id, $validated['signal']))->toOthers();

        return response()->json([
            'message' => 'シグナルを送信しました',
        ]);
    }

    /**
     * 会話へのアクセス権限を確認
     */
    private function authorizeConversationAccess($user, Conversation $conversation): void
    {
        // 個人ユーザーの場合
        if ($user->role === \App\Models\User::ROLE_PERSONAL) {
            if ($conversation->user_id !== $user->id) {
                abort(403, 'この会話にアクセスする権限がありません');
            }
        }
        // 事業者の場合
        elseif ($user->role === \App\Models\User::ROLE_COMPANY) {
            $company = $user->company;
            if (!$company || $conversation->company_id !== $company->id) {
                abort(403, 'この会話にアクセスする権限がありません');
            }
        }
        else {
            abort(403, 'この会話にアクセスする権限がありません');
        }
    }

    /**
     * 受信者のIDを取得
     */
    private function getRecipientId($user, Conversation $conversation): ?int
    {
        // 個人ユーザーが開始する場合、受信者は会社に関連するユーザー
        if ($user->role === \App\Models\User::ROLE_PERSONAL) {
            if ($conversation->company_id) {
                // 会社に関連するユーザーを取得
                $company = \App\Models\Company::with('user')->find($conversation->company_id);
                if ($company && $company->user) {
                    return $company->user->id;
                }
            }
        }
        // 事業者が開始する場合、受信者は個人ユーザー
        elseif ($user->role === \App\Models\User::ROLE_COMPANY) {
            return $conversation->user_id;
        }

        return null;
    }
}

