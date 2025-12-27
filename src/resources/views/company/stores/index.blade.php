@extends('layouts.company')

@section('title', '店舗管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗管理</h1>
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
        新規店舗追加
    </a>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗一覧</h3>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">店舗名</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">種別</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">住所</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">電話番号</th>
                <th style="
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
            @forelse($stores as $store)
            <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $store->name }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    @if($store->store_type === 1) 美容室
                    @elseif($store->store_type === 2) エステ
                    @elseif($store->store_type === 3) 医科
                    @elseif($store->store_type === 4) 歯科
                    @else その他
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $store->address ?? '未登録' }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $store->tel ?? '未登録' }}</td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('company.stores.edit', $store) }}" style="
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
                        <form action="{{ route('company.stores.destroy', $store) }}" method="POST" style="display:inline;" onsubmit="return confirm('この店舗を削除してもよろしいですか？');">
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
            @empty
            <tr>
                <td colspan="5" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">店舗が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- スマホ用カードレイアウト -->
<div class="stores-cards">
    @forelse($stores as $store)
    <div class="store-card">
        <div class="store-card-header">
            <div class="store-card-name">{{ $store->name }}</div>
            <div class="store-card-type">
                @if($store->store_type === 1) 美容室
                @elseif($store->store_type === 2) エステ
                @elseif($store->store_type === 3) 医科
                @elseif($store->store_type === 4) 歯科
                @else その他
                @endif
            </div>
        </div>
        <div class="store-card-body">
            <div class="store-card-row">
                <span class="store-card-label">住所</span>
                <span class="store-card-value">{{ $store->address ?? '未登録' }}</span>
            </div>
            <div class="store-card-row">
                <span class="store-card-label">電話番号</span>
                <span class="store-card-value">{{ $store->tel ?? '未登録' }}</span>
            </div>
        </div>
        <div class="store-card-actions">
            <a href="{{ route('company.stores.edit', $store) }}" class="store-card-btn store-card-btn-edit">
                編集
            </a>
            <form action="{{ route('company.stores.destroy', $store) }}" method="POST" class="store-card-form" onsubmit="return confirm('この店舗を削除してもよろしいですか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="store-card-btn store-card-btn-delete">
                    削除
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="store-card-empty">
        <p>店舗が登録されていません。</p>
    </div>
    @endforelse
</div>

<style>
/* スマホ用カードレイアウト（デフォルトは非表示） */
.stores-cards {
    display: none;
}

.store-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.store-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
}

.store-card-name {
    font-size: 18px;
    font-weight: 700;
    color: #5D535E;
}

.store-card-type {
    font-size: 13px;
    color: #6b7280;
    padding: 4px 12px;
    background: #f3f4f6;
    border-radius: 12px;
}

.store-card-body {
    display: grid;
    gap: 10px;
    margin-bottom: 12px;
}

.store-card-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    padding: 4px 0;
}

.store-card-label {
    color: #6b7280;
    font-weight: 500;
}

.store-card-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
    flex: 1;
    margin-left: 12px;
}

.store-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
}

.store-card-btn {
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

.store-card-btn-edit {
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
}

.store-card-btn-delete {
    background: #763626;
    color: #ffffff;
}

.store-card-form {
    flex: 1;
    margin: 0;
}

.store-card-empty {
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

    div[style*="overflow-x: auto"] {
        display: none;
    }

    .stores-cards {
        display: block;
    }

    .store-card {
        margin-bottom: 16px;
    }

    .store-card-name {
        font-size: 20px;
    }

    .store-card-row {
        font-size: 15px;
    }

    .store-card-btn {
        font-size: 14px;
        padding: 12px 16px;
    }
}

@media (max-width: 480px) {
    .store-card {
        padding: 12px;
    }

    .store-card-name {
        font-size: 18px;
    }

    .store-card-row {
        font-size: 14px;
    }

    .store-card-btn {
        font-size: 13px;
        padding: 10px 12px;
    }
}
</style>
@endsection

