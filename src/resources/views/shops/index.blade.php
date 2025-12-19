@extends('layouts.app')

@section('title', 'ショップ | Bellbi')

@section('sidebar')
    <div class="sidebar-card" style="background: #ffffff; border-radius: 0; padding: 32px 24px; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        <h3 class="sidebar-title" style="font-size: 14px; font-weight: 600; color: #1a1a1a; letter-spacing: 0.05em; text-transform: uppercase; margin: 0 0 24px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">条件でさがす</h3>
        <form class="search-form" method="GET" action="{{ route('shops.index') }}">
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="keyword" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">キーワード</label>
                <input type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="商品名・説明など" style="width: 100%; padding: 12px 16px; border: 1px solid #e0e0e0; border-radius: 0; font-size: 14px; background: #ffffff; color: #1a1a1a; transition: all 0.3s ease; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;" onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="category" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">カテゴリ</label>
                <select id="category" name="category" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e0e0e0;
                    border-radius: 0;
                    font-size: 14px;
                    background-color: #ffffff;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    transition: all 0.3s ease;
                " onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
                    <option value="">すべて</option>
                    <option value="1" {{ request('category') == '1' ? 'selected' : '' }}>美容用品</option>
                    <option value="2" {{ request('category') == '2' ? 'selected' : '' }}>医療用品</option>
                    <option value="3" {{ request('category') == '3' ? 'selected' : '' }}>その他</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="sort" style="display: block; font-size: 12px; color: #666; margin-bottom: 8px; font-weight: 500; letter-spacing: 0.02em;">並び替え</label>
                <select id="sort" name="sort" onchange="this.form.submit();" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e0e0e0;
                    border-radius: 0;
                    font-size: 14px;
                    background-color: #ffffff;
                    color: #1a1a1a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    transition: all 0.3s ease;
                " onfocus="this.style.borderColor='#1a1a1a'; this.style.outline='none';" onblur="this.style.borderColor='#e0e0e0';">
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
                padding: 14px 24px;
                background: #1a1a1a;
                color: #ffffff;
                border: 1px solid #1a1a1a;
                border-radius: 0;
                font-size: 13px;
                font-weight: 500;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                cursor: pointer;
                transition: all 0.3s ease;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            " onmouseover="this.style.background='#000000'; this.style.borderColor='#000000';" onmouseout="this.style.background='#1a1a1a'; this.style.borderColor='#1a1a1a';">
                この条件で検索
            </button>
        </form>
    </div>
@endsection

@section('content')
    <header class="page-header" style="margin-bottom: 48px; padding-bottom: 32px; border-bottom: 1px solid #f0f0f0;">
        <p class="page-label" style="font-size: 11px; color: #999; letter-spacing: 0.15em; text-transform: uppercase; margin: 0 0 12px 0; font-weight: 500; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">Marketplace</p>
        <h2 class="page-title" style="font-size: 32px; font-weight: 400; color: #1a1a1a; margin: 0 0 16px 0; letter-spacing: -0.02em; line-height: 1.3; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">ショップ</h2>
        <p class="page-lead" style="font-size: 14px; color: #666; line-height: 1.7; margin: 0; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            美容・医療・歯科関連の商品を探せます。
        </p>
    </header>

    @if ($products->isEmpty())
        <p class="empty-message" style="font-size: 14px; color: #999; text-align: center; padding: 64px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">商品が見つかりませんでした。</p>
    @else
        <div class="job-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 32px 24px;">
            @foreach ($products as $product)
                <article class="job-card" style="
                    background: #ffffff;
                    border: 1px solid transparent;
                    padding: 0;
                    transition: border-color 0.3s ease;
                    display: flex;
                    flex-direction: column;
                    position: relative;
                " onmouseover="this.style.borderColor='#1a1a1a';" onmouseout="this.style.borderColor='transparent';">
                    @if($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0)
                    <div style="
                        position: absolute;
                        top: 12px;
                        right: 12px;
                        background-color: rgba(0, 0, 0, 0.7);
                        color: #ffffff;
                        padding: 4px 8px;
                        font-size: 9px;
                        font-weight: 400;
                        z-index: 10;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                        letter-spacing: 0.1em;
                    ">在庫切れ</div>
                    @endif
                    <a href="{{ route('shops.show', $product) }}" style="text-decoration: none; color: inherit; display: block;">
                        <div class="job-card-body" style="padding: 0;">
                            <div style="width: 100%; height: 280px; overflow: hidden; background: #fafafa; position: relative;">
                                @if($product->status === \App\Models\Product::STATUS_OUT_OF_STOCK || $product->stock <= 0)
                                    <div style="width: 100%; height: 100%; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                        <div style="text-align: center;">
                                            <div style="font-size: 48px; opacity: 0.3; margin-bottom: 8px;">×</div>
                                            <div style="font-size: 12px; font-weight: 400;">在庫切れ</div>
                                        </div>
                                    </div>
                                @else
                                    <div style="width: 100%; height: 100%; background: #fafafa; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                        商品画像
                                    </div>
                                @endif
                            </div>
                            <div style="padding: 20px 12px 0 12px;">
                                <h3 class="job-card-title" style="margin: 0 0 6px 0; font-size: 15px; font-weight: 400; color: #1a1a1a; line-height: 1.5; letter-spacing: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                    {{ $product->name }}
                                </h3>
                                <p style="margin: 0 0 8px 0; font-size: 12px; color: #999; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                    @if($product->shop && $product->shop->company)
                                        {{ $product->shop->company->name }}
                                    @endif
                                </p>
                                <p style="margin: 0 0 12px 0; font-size: 14px; color: #1a1a1a; font-weight: 400; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
                                    ¥{{ number_format($product->price) }}
                                </p>
                                @if($product->stock < 10 && $product->stock > 0)
                                    <p style="margin: 0; font-size: 11px; color: #999; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">残り{{ $product->stock }}点</p>
                                @endif
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrapper" style="margin-top: 64px; padding-top: 32px; border-top: 1px solid #f0f0f0;">
            {{ $products->links() }}
        </div>
    @endif
@endsection

