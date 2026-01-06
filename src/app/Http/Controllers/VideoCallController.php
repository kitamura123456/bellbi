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
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => '認証が必要です',
                ], 401);
            }

            // 会話へのアクセス権限を確認
            try {
                $this->authorizeConversationAccess($user, $conversation);
            } catch (\Exception $e) {
                Log::warning('Unauthorized conversation access attempt', [
                    'user_id' => $user->id,
                    'conversation_id' => $conversation->id,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'message' => 'この会話にアクセスする権限がありません',
                ], 403);
            }

            // 既にアクティブな通話があるか確認
            try {
                $activeCall = $conversation->activeVideoCall;
                if ($activeCall) {
                    return response()->json([
                        'message' => '既にアクティブな通話があります',
                        'video_call_id' => $activeCall->id,
                    ], 409);
                }
            } catch (\Exception $e) {
                Log::error('Failed to check active video call', [
                    'conversation_id' => $conversation->id,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'message' => '通話状態の確認に失敗しました',
                ], 500);
            }

            // 受信者を特定
            try {
                $recipientId = $this->getRecipientId($user, $conversation);
                
                if (!$recipientId) {
                    return response()->json([
                        'message' => '受信者を特定できませんでした',
                    ], 400);
                }
            } catch (\Exception $e) {
                Log::error('Failed to get recipient ID', [
                    'user_id' => $user->id,
                    'conversation_id' => $conversation->id,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'message' => '受信者の特定に失敗しました',
                ], 500);
            }

            // ビデオ通話を作成
            try {
                $videoCall = VideoCall::create([
                    'conversation_id' => $conversation->id,
                    'initiator_id' => $user->id,
                    'recipient_id' => $recipientId,
                    'status' => 'pending',
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create video call', [
                    'conversation_id' => $conversation->id,
                    'initiator_id' => $user->id,
                    'recipient_id' => $recipientId,
                    'error' => $e->getMessage(),
                ]);
                return response()->json([
                    'message' => 'ビデオ通話の作成に失敗しました',
                ], 500);
            }

            // WebSocketイベントを送信（エラーが発生しても処理を続行）
            try {
                broadcast(new VideoCallInitiated($videoCall))->toOthers();
            } catch (\Exception $e) {
                Log::error('Failed to broadcast VideoCallInitiated event', [
                    'video_call_id' => $videoCall->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            return response()->json([
                'message' => 'ビデオ通話を開始しました',
                'video_call' => [
                    'id' => $videoCall->id,
                    'conversation_id' => $videoCall->conversation_id,
                    'status' => $videoCall->status,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Unexpected error in VideoCallController::store', [
                'conversation_id' => $conversation->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => '予期しないエラーが発生しました',
            ], 500);
        }
    }

    /**
     * ビデオ通話を受ける
     */
    public function accept(VideoCall $videoCall)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => '認証が必要です',
                ], 401);
            }

            if ($videoCall->recipient_id !== $user->id) {
                Log::warning('Unauthorized video call accept attempt', [
                    'user_id' => $user->id,
                    'video_call_id' => $videoCall->id,
                    'recipient_id' => $videoCall->recipient_id,
                ]);
                return response()->json([
                    'message' => 'この通話に応答する権限がありません',
                ], 403);
            }

            if ($videoCall->status !== 'pending') {
                return response()->json([
                    'message' => 'この通話は既に処理されています',
                    'status' => $videoCall->status,
                ], 400);
            }

            // ビデオ通話を開始
            try {
                $videoCall->start();
            } catch (\Exception $e) {
                Log::error('Failed to start video call', [
                    'video_call_id' => $videoCall->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json([
                    'message' => 'ビデオ通話の開始に失敗しました',
                ], 500);
            }

            // WebSocketイベントを送信（エラーが発生しても処理を続行）
            try {
                broadcast(new VideoCallAccepted($videoCall))->toOthers();
            } catch (\Exception $e) {
                Log::error('Failed to broadcast VideoCallAccepted event', [
                    'video_call_id' => $videoCall->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            return response()->json([
                'message' => 'ビデオ通話を開始しました',
                'video_call' => [
                    'id' => $videoCall->id,
                    'status' => $videoCall->status,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error in VideoCallController::accept', [
                'video_call_id' => $videoCall->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => '予期しないエラーが発生しました',
            ], 500);
        }
    }

    /**
     * ビデオ通話を拒否
     */
    public function reject(VideoCall $videoCall)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => '認証が必要です',
                ], 401);
            }

            if ($videoCall->recipient_id !== $user->id) {
                Log::warning('Unauthorized video call reject attempt', [
                    'user_id' => $user->id,
                    'video_call_id' => $videoCall->id,
                    'recipient_id' => $videoCall->recipient_id,
                ]);
                return response()->json([
                    'message' => 'この通話を拒否する権限がありません',
                ], 403);
            }

            if ($videoCall->status !== 'pending') {
                return response()->json([
                    'message' => 'この通話は既に処理されています',
                    'status' => $videoCall->status,
                ], 400);
            }

            // ビデオ通話を拒否
            try {
                $videoCall->decline();
            } catch (\Exception $e) {
                Log::error('Failed to decline video call', [
                    'video_call_id' => $videoCall->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json([
                    'message' => 'ビデオ通話の拒否に失敗しました',
                ], 500);
            }

            // WebSocketイベントを送信（エラーが発生しても処理を続行）
            try {
                broadcast(new VideoCallDeclined($videoCall))->toOthers();
            } catch (\Exception $e) {
                Log::error('Failed to broadcast VideoCallDeclined event', [
                    'video_call_id' => $videoCall->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            return response()->json([
                'message' => 'ビデオ通話を拒否しました',
                'video_call' => [
                    'id' => $videoCall->id,
                    'status' => $videoCall->status,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error in VideoCallController::reject', [
                'video_call_id' => $videoCall->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => '予期しないエラーが発生しました',
            ], 500);
        }
    }

    /**
     * ビデオ通話を終了
     */
    public function end(VideoCall $videoCall)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => '認証が必要です',
                ], 401);
            }

            // 通話の参加者のみが終了できる
            if ($videoCall->initiator_id !== $user->id && $videoCall->recipient_id !== $user->id) {
                Log::warning('Unauthorized video call end attempt', [
                    'user_id' => $user->id,
                    'video_call_id' => $videoCall->id,
                    'initiator_id' => $videoCall->initiator_id,
                    'recipient_id' => $videoCall->recipient_id,
                ]);
                return response()->json([
                    'message' => 'この通話を終了する権限がありません',
                ], 403);
            }

            if ($videoCall->status === 'ended' || $videoCall->status === 'declined') {
                return response()->json([
                    'message' => 'この通話は既に終了しています',
                    'status' => $videoCall->status,
                ], 400);
            }

            // ビデオ通話を終了
            try {
                $videoCall->end();
            } catch (\Exception $e) {
                Log::error('Failed to end video call', [
                    'video_call_id' => $videoCall->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json([
                    'message' => 'ビデオ通話の終了に失敗しました',
                ], 500);
            }

            // WebSocketイベントを送信（エラーが発生しても処理を続行）
            try {
                broadcast(new VideoCallEnded($videoCall))->toOthers();
            } catch (\Exception $e) {
                Log::error('Failed to broadcast VideoCallEnded event', [
                    'video_call_id' => $videoCall->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            return response()->json([
                'message' => 'ビデオ通話を終了しました',
                'video_call' => [
                    'id' => $videoCall->id,
                    'status' => $videoCall->status,
                    'duration_seconds' => $videoCall->duration_seconds,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error in VideoCallController::end', [
                'video_call_id' => $videoCall->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => '予期しないエラーが発生しました',
            ], 500);
        }
    }

    /**
     * WebRTCシグナルを送信
     */
    public function signal(Request $request, VideoCall $videoCall)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'message' => '認証が必要です',
                ], 401);
            }

            // 通話の参加者のみがシグナルを送信できる
            if ($videoCall->initiator_id !== $user->id && $videoCall->recipient_id !== $user->id) {
                Log::warning('Unauthorized video call signal attempt', [
                    'user_id' => $user->id,
                    'video_call_id' => $videoCall->id,
                    'initiator_id' => $videoCall->initiator_id,
                    'recipient_id' => $videoCall->recipient_id,
                ]);
                return response()->json([
                    'message' => 'この通話にシグナルを送信する権限がありません',
                ], 403);
            }

            // バリデーション
            try {
                $validated = $request->validate([
                    'signal' => ['required'],
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::warning('Invalid signal data', [
                    'video_call_id' => $videoCall->id,
                    'errors' => $e->errors(),
                ]);
                return response()->json([
                    'message' => 'シグナルデータが無効です',
                    'errors' => $e->errors(),
                ], 422);
            }

            // WebSocketイベントを送信（送信者自身には送らない、エラーが発生しても処理を続行）
            try {
                broadcast(new VideoCallSignal($videoCall, $user->id, $validated['signal']))->toOthers();
            } catch (\Exception $e) {
                Log::error('Failed to broadcast VideoCallSignal event', [
                    'video_call_id' => $videoCall->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // シグナル送信の失敗は致命的ではないため、エラーを返さず成功レスポンスを返す
            }

            return response()->json([
                'message' => 'シグナルを送信しました',
            ]);
        } catch (\Exception $e) {
            Log::error('Unexpected error in VideoCallController::signal', [
                'video_call_id' => $videoCall->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => '予期しないエラーが発生しました',
            ], 500);
        }
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
        try {
            // 個人ユーザーが開始する場合、受信者は会社に関連するユーザー
            if ($user->role === \App\Models\User::ROLE_PERSONAL) {
                if ($conversation->company_id) {
                    try {
                        // 会社に関連するユーザーを取得
                        $company = \App\Models\Company::with('user')->find($conversation->company_id);
                        if ($company && $company->user) {
                            return $company->user->id;
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to get company user for recipient', [
                            'company_id' => $conversation->company_id,
                            'error' => $e->getMessage(),
                        ]);
                        return null;
                    }
                }
            }
            // 事業者が開始する場合、受信者は個人ユーザー
            elseif ($user->role === \App\Models\User::ROLE_COMPANY) {
                return $conversation->user_id;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get recipient ID', [
                'user_id' => $user->id ?? null,
                'user_role' => $user->role ?? null,
                'conversation_id' => $conversation->id ?? null,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
