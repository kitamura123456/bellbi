@extends('layouts.company')

@section('title', 'ECショップ管理')

@section('content')
<div style="margin-bottom: 24px; margin-top: 48px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ECショップ管理</h1>
    <a href="{{ route('company.shops.create') }}" style="
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
    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
        新規ショップ開設
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
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ショップ一覧</h3>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ショップ名</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">ステータス</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">店舗</th>
                <th style="padding: 12px 16px; text-align: left; font-weight: 700; color: #5D535E; font-size: 13px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shops as $shop)
            <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $shop->name }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    @if($shop->status === \App\Models\Shop::STATUS_PREPARING)
                        <span style="padding: 4px 12px; background: #f3f4f6; color: #6b7280; border-radius: 12px; font-size: 12px; font-weight: 600;">準備中</span>
                    @elseif($shop->status === \App\Models\Shop::STATUS_PUBLIC)
                        <span style="padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 12px; font-weight: 600;">公開中</span>
                    @elseif($shop->status === \App\Models\Shop::STATUS_STOPPED)
                        <span style="padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 12px; font-size: 12px; font-weight: 600;">停止</span>
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    @if($shop->store)
                        {{ $shop->store->name }}
                    @else
                        -
                    @endif
                </td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('company.shops.edit', $shop) }}" style="
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
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                            編集
                        </a>
                        <form action="{{ route('company.shops.destroy', $shop) }}" method="POST" style="display:inline;" onsubmit="return confirm('このショップを削除してもよろしいですか？');">
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
                            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                                削除
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">ショップが登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- スマホ用カードレイアウト -->
<div class="shops-cards">
    @forelse($shops as $shop)
    <div class="shop-card">
        <div class="shop-card-header">
            <div class="shop-card-name">{{ $shop->name }}</div>
            <div>
                @if($shop->status === \App\Models\Shop::STATUS_PREPARING)
                    <span class="shop-badge shop-badge-preparing">準備中</span>
                @elseif($shop->status === \App\Models\Shop::STATUS_PUBLIC)
                    <span class="shop-badge shop-badge-public">公開中</span>
                @elseif($shop->status === \App\Models\Shop::STATUS_STOPPED)
                    <span class="shop-badge shop-badge-stopped">停止</span>
                @endif
            </div>
        </div>
        <div class="shop-card-body">
            <div class="shop-card-row">
                <span class="shop-card-label">店舗</span>
                <span class="shop-card-value">{{ $shop->store ? $shop->store->name : '-' }}</span>
            </div>
        </div>
        <div class="shop-card-actions">
            <a href="{{ route('company.shops.edit', $shop) }}" class="shop-card-btn shop-card-btn-edit">
                編集
            </a>
            <form action="{{ route('company.shops.destroy', $shop) }}" method="POST" class="shop-card-form" onsubmit="return confirm('このショップを削除してもよろしいですか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="shop-card-btn shop-card-btn-delete">
                    削除
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="shop-card-empty">
        <p>ショップが登録されていません。</p>
    </div>
    @endforelse
</div>

<style>
/* スマホ用カードレイアウト（デフォルトは非表示） */
.shops-cards {
    display: none;
}

.shop-card {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

.shop-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e8e8e8;
    gap: 12px;
}

.shop-card-name {
    font-size: 18px;
    font-weight: 700;
    color: #5D535E;
    flex: 1;
}

.shop-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.shop-badge-preparing {
    background: #f3f4f6;
    color: #6b7280;
}

.shop-badge-public {
    background: #d1fae5;
    color: #059669;
}

.shop-badge-stopped {
    background: #fee2e2;
    color: #dc2626;
}

.shop-card-body {
    display: grid;
    gap: 10px;
    margin-bottom: 12px;
}

.shop-card-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    padding: 4px 0;
}

.shop-card-label {
    color: #6b7280;
    font-weight: 500;
}

.shop-card-value {
    color: #111827;
    font-weight: 600;
    text-align: right;
    flex: 1;
    margin-left: 12px;
}

.shop-card-actions {
    display: flex;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #e8e8e8;
}

.shop-card-btn {
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

.shop-card-btn-edit {
    background: transparent;
    color: #5D535E;
    border: 1px solid #5D535E;
}

.shop-card-btn-delete {
    background: #763626;
    color: #ffffff;
}

.shop-card-form {
    flex: 1;
    margin: 0;
}

.shop-card-empty {
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

    .shops-cards {
        display: block;
    }

    .shop-card {
        margin-bottom: 16px;
    }

    .shop-card-name {
        font-size: 20px;
    }

    .shop-card-row {
        font-size: 15px;
    }

    .shop-card-btn {
        font-size: 14px;
        padding: 12px 16px;
    }
}

@media (max-width: 480px) {
    .shop-card {
        padding: 12px;
    }

    .shop-card-name {
        font-size: 18px;
    }

    .shop-card-row {
        font-size: 14px;
    }

    .shop-card-btn {
        font-size: 13px;
        padding: 10px 12px;
    }
}
</style>
@endsection

