<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\ScoutMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $scouts = ScoutMessage::where('to_user_id', $user->id)
            ->where('delete_flg', 0)
            ->with(['fromCompany', 'fromStore'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mypage.scouts.index', compact('scouts'));
    }

    public function show(ScoutMessage $scout)
    {
        $user = Auth::user();

        if ($scout->to_user_id !== $user->id) {
            abort(403);
        }

        // 未読の場合は既読にする
        if ($scout->status === ScoutMessage::STATUS_SENT) {
            $scout->status = ScoutMessage::STATUS_READ;
            $scout->save();
        }

        return view('mypage.scouts.show', compact('scout'));
    }

    public function reply(Request $request, ScoutMessage $scout)
    {
        $user = Auth::user();

        if ($scout->to_user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'reply_body' => ['required', 'string', 'max:2000'],
        ]);

        // 返信ありステータスに変更
        $scout->status = ScoutMessage::STATUS_REPLIED;
        $scout->body .= "\n\n--- 返信 ---\n" . $validated['reply_body'];
        $scout->save();

        return redirect()->route('mypage.scouts.show', $scout)->with('status', '返信を送信しました。');
    }
}


