<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\MessageAttachment;
use App\Models\JobApplication;
use App\Models\ScoutMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // ユーザーが参加している会話を取得
        $conversations = Conversation::where('user_id', $user->id)
            ->where('delete_flg', 0)
            ->with(['company', 'jobApplication.jobPost', 'scoutMessage', 'latestMessage'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // 会話のタイトルと関連情報を取得
        $conversationsWithInfo = $conversations->map(function ($conversation) {
            $title = '';
            $relatedInfo = null;
            
            if ($conversation->jobApplication) {
                $title = '応募: ' . $conversation->jobApplication->jobPost->title;
                $relatedInfo = [
                    'type' => 'application',
                    'id' => $conversation->jobApplication->id,
                ];
            } elseif ($conversation->scoutMessage) {
                $title = 'スカウト: ' . $conversation->scoutMessage->subject;
                $relatedInfo = [
                    'type' => 'scout',
                    'id' => $conversation->scoutMessage->id,
                ];
            }
            
            return [
                'conversation' => $conversation,
                'title' => $title,
                'relatedInfo' => $relatedInfo,
            ];
        });

        return view('mypage.messages.index', compact('conversationsWithInfo'));
    }

    public function show(Conversation $conversation)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'ログインが必要です');
            }

            if ($conversation->user_id !== $user->id) {
                abort(403, 'この会話にアクセスする権限がありません');
            }

            // リレーションをEager Load（null参照を防ぐため）
            try {
                $conversation->load(['company', 'user', 'jobApplication.jobPost', 'scoutMessage']);
            } catch (\Exception $e) {
                Log::error('Failed to load conversation relations', [
                    'conversation_id' => $conversation->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // メッセージを取得（添付のみEager Load）
            try {
                $messages = $conversation->messages()->with(['attachments'])->get();
            } catch (\Exception $e) {
                Log::error('Failed to load messages', [
                    'conversation_id' => $conversation->id,
                    'error' => $e->getMessage(),
                ]);
                $messages = collect([]);
            }

            // 未読メッセージを既読にする
            try {
                $messages->where('sender_type', 'company')
                    ->where('read_flg', 0)
                    ->each(function ($message) {
                        try {
                            $message->markAsRead();
                        } catch (\Exception $e) {
                            Log::error('Failed to mark message as read', [
                                'message_id' => $message->id ?? null,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    });
            } catch (\Exception $e) {
                Log::error('Failed to mark messages as read', [
                    'conversation_id' => $conversation->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // 会話のタイトルを取得（null チェックを追加）
            $title = '';
            try {
                if ($conversation->jobApplication && $conversation->jobApplication->jobPost) {
                    $title = '応募: ' . ($conversation->jobApplication->jobPost->title ?? '応募情報');
                } elseif ($conversation->scoutMessage) {
                    $title = 'スカウト: ' . ($conversation->scoutMessage->subject ?? 'スカウトメッセージ');
                }
            } catch (\Exception $e) {
                Log::error('Failed to get conversation title', [
                    'conversation_id' => $conversation->id,
                    'error' => $e->getMessage(),
                ]);
                $title = 'メッセージ';
            }

            return view('mypage.messages.show', compact('conversation', 'messages', 'title'));
        } catch (\Exception $e) {
            Log::error('Unexpected error in MessageController::show', [
                'conversation_id' => $conversation->id ?? null,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('mypage.messages.index')
                ->with('error', 'メッセージの読み込み中にエラーが発生しました');
        }
    }

    public function store(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'attachments.*' => ['nullable', 'file', 'max:10240'], // 最大10MB
        ]);

        // メッセージを作成
        $message = ConversationMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'sender_id' => $user->id,
            'body' => $validated['body'],
            'read_flg' => 0,
            'delete_flg' => 0,
        ]);

        // ファイルアップロード処理
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments', 'public');
                
                MessageAttachment::create([
                    'conversation_message_id' => $message->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'delete_flg' => 0,
                ]);
            }
        }

        // 会話の更新日時を更新
        $conversation->touch();

        return redirect()->route('mypage.messages.show', $conversation)
            ->with('status', 'メッセージを送信しました。');
    }

    /**
     * 応募から会話を開始
     */
    public function createFromApplication(JobApplication $application)
    {
        $user = Auth::user();

        if ($application->user_id !== $user->id) {
            abort(403);
        }

        $conversation = Conversation::getOrCreateForApplication($application);

        return redirect()->route('mypage.messages.show', $conversation);
    }

    /**
     * スカウトから会話を開始
     */
    public function createFromScout(ScoutMessage $scout)
    {
        $user = Auth::user();

        if ($scout->to_user_id !== $user->id) {
            abort(403);
        }

        $conversation = Conversation::getOrCreateForScout($scout);

        return redirect()->route('mypage.messages.show', $conversation);
    }
}

