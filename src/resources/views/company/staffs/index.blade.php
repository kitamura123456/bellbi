@extends('layouts.company')

@section('title', 'スタッフ管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">スタッフ管理</h1>
    <a href="{{ route('company.staffs.create') }}" style="
        padding: 12px 24px;
        background: #5D535E;
        color: #ffffff;
        border: none;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
        スタッフ追加
    </a>
</div>

@foreach($stores as $store)
<div class="store-staffs-container" data-store-id="{{ $store->id }}">
    <div style="
        padding: 0;
        border: none;
        box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
        border-radius: 0;
        background: #ffffff;
        margin-bottom: 24px;
        overflow-x: auto;
    ">
        <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">{{ $store->name }}</h3>
        </div>
        @if($store->staffs->isEmpty())
            <div style="padding: 40px 24px; text-align: center; color: #999999;">
                <p style="margin: 0; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">スタッフが登録されていません。</p>
            </div>
        @else
            <table class="staffs-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                        <th style="
                            width: 80px;
                            padding: 12px 16px;
                            text-align: left;
                            font-weight: 700;
                            color: #5D535E;
                            font-size: 13px;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        ">写真</th>
                        <th style="
                            padding: 12px 16px;
                            text-align: left;
                            font-weight: 700;
                            color: #5D535E;
                            font-size: 13px;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        ">スタッフ名</th>
                        <th style="
                            width: 80px;
                            padding: 12px 16px;
                            text-align: left;
                            font-weight: 700;
                            color: #5D535E;
                            font-size: 13px;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        ">表示順</th>
                        <th style="
                            width: 100px;
                            padding: 12px 16px;
                            text-align: left;
                            font-weight: 700;
                            color: #5D535E;
                            font-size: 13px;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        ">ステータス</th>
                        <th style="
                            width: 160px;
                            padding: 12px 16px;
                            text-align: left;
                            font-weight: 700;
                            color: #5D535E;
                            font-size: 13px;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        ">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($store->staffs as $staff)
                    <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                        <td style="padding: 12px 16px;">
                            @if($staff->image_path)
                                <img src="{{ asset('storage/' . $staff->image_path) }}" alt="{{ $staff->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                            @else
                                <div style="width: 60px; height: 60px; background: #f5f5f5; border: 1px solid #e8e8e8; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #999999; font-size: 10px;">
                                    No Image
                                </div>
                            @endif
                        </td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $staff->name }}</td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $staff->display_order }}</td>
                        <td style="padding: 12px 16px;">
                            @if($staff->is_active)
                                <span style="
                                    display: inline-block;
                                    padding: 4px 12px;
                                    background: #336B87;
                                    color: #ffffff;
                                    border-radius: 12px;
                                    font-size: 12px;
                                    font-weight: 500;
                                ">公開中</span>
                            @else
                                <span style="
                                    display: inline-block;
                                    padding: 4px 12px;
                                    background: #999999;
                                    color: #ffffff;
                                    border-radius: 12px;
                                    font-size: 12px;
                                    font-weight: 500;
                                ">非公開</span>
                            @endif
                        </td>
                        <td style="padding: 12px 16px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('company.staffs.edit', $staff) }}" style="
                                    padding: 6px 16px;
                                    background: transparent;
                                    color: #5D535E;
                                    border: 1px solid #5D535E;
                                    border-radius: 16px;
                                    font-size: 12px;
                                    font-weight: 700;
                                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                    text-decoration: none;
                                    transition: all 0.2s ease;
                                    position: relative;
                                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                                    編集
                                </a>
                                <form action="{{ route('company.staffs.destroy', $staff) }}" method="POST" style="display: inline;" onsubmit="return confirm('このスタッフを削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="
                                        padding: 6px 16px;
                                        background: #763626;
                                        color: #ffffff;
                                        border: none;
                                        border-radius: 16px;
                                        font-size: 12px;
                                        font-weight: 700;
                                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                        cursor: pointer;
                                        transition: all 0.2s ease;
                                        position: relative;
                                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                                        削除
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- スマホ用カードレイアウト -->
    <div class="staffs-cards">
        @if($store->staffs->isEmpty())
            <div class="staff-card-empty">
                <p>スタッフが登録されていません。</p>
            </div>
        @else
            @foreach($store->staffs as $staff)
            <div class="staff-card">
                <div class="staff-card-header">
                    <div class="staff-card-photo">
                        @if($staff->image_path)
                            <img src="{{ asset('storage/' . $staff->image_path) }}" alt="{{ $staff->name }}">
                        @else
                            <div class="staff-card-photo-placeholder">No Image</div>
                        @endif
                    </div>
                    <div class="staff-card-info">
                        <div class="staff-card-name">{{ $staff->name }}</div>
                        <div>
                            @if($staff->is_active)
                                <span class="staff-badge staff-badge-active">公開中</span>
                            @else
                                <span class="staff-badge staff-badge-inactive">非公開</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="staff-card-body">
                    <div class="staff-card-row">
                        <span class="staff-card-label">表示順</span>
                        <span class="staff-card-value">{{ $staff->display_order }}</span>
                    </div>
                </div>
                <div class="staff-card-actions">
                    <a href="{{ route('company.staffs.edit', $staff) }}" class="staff-card-btn staff-card-btn-edit">
                        編集
                    </a>
                    <form action="{{ route('company.staffs.destroy', $staff) }}" method="POST" class="staff-card-form" onsubmit="return confirm('このスタッフを削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="staff-card-btn staff-card-btn-delete">
                            削除
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endforeach

@if($stores->isEmpty())
    <div style="
        padding: 0;
        border: none;
        box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
        border-radius: 0;
        background: #ffffff;
    ">
        <div style="padding: 40px 24px; text-align: center;">
            <p style="margin: 0 0 20px 0; color: #999999; font-size: 14px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗が登録されていません。先に店舗を登録してください。</p>
            <a href="{{ route('company.stores.create') }}" style="
                padding: 12px 24px;
                background: #5D535E;
                color: #ffffff;
                border: none;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                店舗を登録する
            </a>
        </div>
    </div>
@endif

<style>
/* スマホ用カードレイアウト（デフォルトは非表示） */
.staffs-cards {
    display: none;
}

.staff-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.staff-card-header {
    display: flex;
    gap: 16px;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
}

.staff-card-photo {
    flex-shrink: 0;
}

.staff-card-photo img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
}

.staff-card-photo-placeholder {
    width: 80px;
    height: 80px;
    background: #f5f5f5;
    border: 1px solid #e8e8e8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999999;
    font-size: 11px;
}

.staff-card-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.staff-card-name {
    font-size: 18px;
    font-weight: 700;
    color: #5D535E;
    margin-bottom: 8px;
}

.staff-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

.staff-badge-active {
    background: #336B87;
    color: #ffffff;
}

.staff-badge-inactive {
    background: #999999;
    color: #ffffff;
}

.staff-card-body {
    display: grid;
    gap: 10px;
    margin-bottom: 12px;
}

.staff-card-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    padding: 4px 0;
}

.staff-card-label {
    color: #6b7280;
    font-weight: 500;
}

.staff-card-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
    flex: 1;
    margin-left: 12px;
}

.staff-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
}

.staff-card-btn {
    flex: 1;
    padding: 10px 16px;
    border-radius: 16px;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.staff-card-btn-edit {
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
}

.staff-card-btn-delete {
    background: #763626;
    color: #ffffff;
}

.staff-card-form {
    flex: 1;
    margin: 0;
}

.staff-card-empty {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 40px 16px;
    text-align: center;
    color: #999999;
    font-size: 14px;
}

/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-top: 48px"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-top: 48px"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-top: 48px"] > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    .staffs-table {
        display: none;
    }

    .staffs-cards {
        display: block;
    }

    .staff-card {
        margin-bottom: 16px;
    }

    .staff-card-name {
        font-size: 20px;
    }

    .staff-card-row {
        font-size: 15px;
    }

    .staff-card-btn {
        font-size: 14px;
        padding: 12px 16px;
    }
}

@media (max-width: 480px) {
    .staff-card {
        padding: 12px;
    }

    .staff-card-photo img,
    .staff-card-photo-placeholder {
        width: 60px;
        height: 60px;
    }

    .staff-card-name {
        font-size: 18px;
    }

    .staff-card-row {
        font-size: 14px;
    }

    .staff-card-btn {
        font-size: 13px;
        padding: 10px 12px;
    }
}
</style>
@endsection

