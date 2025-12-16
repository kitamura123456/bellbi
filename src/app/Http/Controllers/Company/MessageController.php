<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\MessageAttachment;
use App\Models\JobApplication;
use App\Models\ScoutMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.dashboard');
        }

        // この会社が参加している会話を取得
        $conversations = Conversation::where('company_id', $company->id)
            ->where('delete_flg', 0)
            ->with(['user', 'jobApplication.jobPost', 'scoutMessage', 'latestMessage'])
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

        return view('company.messages.index', compact('company', 'conversationsWithInfo'));
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $conversation->company_id !== $company->id) {
            abort(403);
        }

        // メッセージを取得（添付のみEager Load）
        $messages = $conversation->messages()->with(['attachments'])->get();

        // 未読メッセージを既読にする
        $messages->where('sender_type', 'user')
            ->where('read_flg', 0)
            ->each(function ($message) {
                $message->markAsRead();
            });

        // 会話のタイトルを取得
        $title = '';
        if ($conversation->jobApplication) {
            $title = '応募: ' . $conversation->jobApplication->jobPost->title;
        } elseif ($conversation->scoutMessage) {
            $title = 'スカウト: ' . $conversation->scoutMessage->subject;
        }

        return view('company.messages.show', compact('company', 'conversation', 'messages', 'title'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $conversation->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'attachments.*' => ['nullable', 'file', 'max:10240'], // 最大10MB
        ]);

        // メッセージを作成
        $message = ConversationMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'company',
            'sender_id' => $company->id,
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

        return redirect()->route('company.messages.show', $conversation)
            ->with('status', 'メッセージを送信しました。');
    }

    /**
     * 応募から会話を開始
     */
    public function createFromApplication(JobApplication $application)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $application->jobPost->company_id !== $company->id) {
            abort(403);
        }

        $conversation = Conversation::getOrCreateForApplication($application);

        return redirect()->route('company.messages.show', $conversation);
    }

    /**
     * スカウトから会話を開始
     */
    public function createFromScout(ScoutMessage $scout)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $scout->from_company_id !== $company->id) {
            abort(403);
        }

        $conversation = Conversation::getOrCreateForScout($scout);

        return redirect()->route('company.messages.show', $conversation);
    }
}

