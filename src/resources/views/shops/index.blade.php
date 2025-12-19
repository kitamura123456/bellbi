@extends('layouts.app')

@section('title', 'ショップ | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <h3 class="sidebar-title">条件でさがす</h3>
        <form class="search-form" method="GET" action="{{ route('shops.index') }}">
            <div class="form-group">
                <label for="keyword">キーワード</label>
                <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="商品名・説明など">
            </div>
            <div class="form-group">
                <label for="category">カテゴリ</label>
                <select id="category" name="category" style="
                    width: 100%;
                    padding: 8px 12px;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    font-size: 14px;
                    background-color: #fff;
                ">
                    <option value="">すべて</option>
                    <option value="1" {{ request('category') == '1' ? 'selected' : '' }}>美容用品</option>
                    <option value="2" {{ request('category') == '2' ? 'selected' : '' }}>医療用品</option>
                    <option value="3" {{ request('category') == '3' ? 'selected' : '' }}>その他</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sort">並び替え</label>
                <select id="sort" name="sort" onchange="this.form.submit();" style="
                    width: 100%;
                    padding: 8px 12px;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    font-size: 14px;
                    background-color: #fff;
                ">
                    <option value="random" {{ request('sort', 'random') == 'random' ? 'selected' : '' }}>ランダム</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>価格：安い順</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>価格：高い順</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>人気順</option>
                    <option value="stock_available" {{ request('sort') == 'stock_available' ? 'selected' : '' }}>在庫あり優先</option>
                    <option value="stock_unavailable" {{ request('sort') == 'stock_unavailable' ? 'selected' : '' }}>在庫なし優先</option>
                </select>
            </div>
            <button type="submit" style="
                width: 100%;
                padding: 12px 24px;
                background: #5D535E;
                color: #ffffff;
                border: none;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                cursor: pointer;
                transition: all 0.2s ease;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                この条件で検索
            </button>
        </form>
    </div>
@endsection

@section('content')
    <header class="page-header">
        <p class="page-label">Marketplace</p>
        <h2 class="page-title">ショップ</h2>
        <p class="page-lead">
            美容・医療・歯科関連の商品を探せます。
        </p>
    </header>

    @if ($products->isEmpty())
        <p class="empty-message">商品が見つかりませんでした。</p>
    @else
        <div class="job-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
            @foreach ($products as $product)
                <article class="job-card" style="
                    background: #ffffff;
                    border: 1px solid {{ ($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0) ? '#dc2626' : '#e5e7eb' }};
                    border-radius: 12px;
                    overflow: hidden;
                    transition: all 0.2s ease;
                    display: flex;
                    flex-direction: column;
                    position: relative;
                " onmouseover="this.style.boxShadow='0 4px 12px rgba(93, 83, 94, 0.15)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                    @if($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0)
                    <div style="
                        position: absolute;
                        top: 12px;
                        right: 12px;
                        background: #dc2626;
                        color: #ffffff;
                        padding: 6px 16px;
                        border-radius: 20px;
                        font-size: 12px;
                        font-weight: 700;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        z-index: 10;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    ">在庫切れ</div>
                    @endif
                    <div class="job-card-body" style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                        <div style="width: 100%; height: 200px; background: {{ ($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0) ? '#fee2e2' : '#f3f4f6' }}; border-radius: 8px; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; color: {{ ($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0) ? '#dc2626' : '#9ca3af' }}; font-size: 14px; position: relative;">
                            @if($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0)
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                    <div style="font-size: 48px; opacity: 0.3; margin-bottom: 8px;">×</div>
                                    <div style="font-size: 14px; font-weight: 700;">在庫切れ</div>
                                </div>
                            @else
                                商品画像
                            @endif
                        </div>
                        <h3 class="job-card-title" style="
                            margin: 0 0 8px 0;
                            font-size: 16px;
                            font-weight: 700;
                            color: #2A3132;
                            line-height: 1.4;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        ">
                            <a href="{{ route('shops.show', $product) }}" style="color: #2A3132; text-decoration: none;">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p style="
                            margin: 0 0 8px 0;
                            font-size: 13px;
                            color: #6b7280;
                            line-height: 1.5;
                            flex: 1;
                        ">
                            @if($product->shop && $product->shop->company)
                                {{ $product->shop->company->name }}
                            @endif
                        </p>
                        <p style="
                            margin: 0;
                            font-size: 20px;
                            font-weight: 700;
                            color: #5D535E;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        ">
                            ¥{{ number_format($product->price) }}
                        </p>
                        @if($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0)
                            <div style="
                                margin: 12px 0 0 0;
                                padding: 8px 12px;
                                background: #fee2e2;
                                border: 2px solid #dc2626;
                                border-radius: 8px;
                                text-align: center;
                            ">
                                <p style="
                                    margin: 0;
                                    font-size: 14px;
                                    color: #dc2626;
                                    font-weight: 700;
                                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                ">在庫切れ</p>
                            </div>
                        @elseif($product->stock < 10)
                            <p style="
                                margin: 8px 0 0 0;
                                font-size: 12px;
                                color: #f59e0b;
                                font-weight: 600;
                            ">残り{{ $product->stock }}点</p>
                        @endif
                    </div>
                    <div class="job-card-footer" style="padding: 16px; border-top: 1px solid #f3f4f6;">
                        <a href="{{ route('shops.show', $product) }}" style="
                            display: block;
                            padding: 12px 24px;
                            background: {{ ($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0) ? '#9ca3af' : '#5D535E' }};
                            color: #ffffff;
                            border: none;
                            border-radius: 24px;
                            font-size: 14px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            text-decoration: none;
                            text-align: center;
                            cursor: pointer;
                            transition: all 0.2s ease;
                        " onmouseover="if({{ ($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0) ? 'false' : 'true' }}) this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                            詳細を見る
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper" style="margin-top: 32px;">
            {{ $products->links() }}
        </div>
    @endif
@endsection

