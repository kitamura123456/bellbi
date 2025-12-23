@extends('layouts.app')

@section('title', 'ショップ | Bellbi')

@section('sidebar')
    <div class="sidebar-card" style="background: #ffffff; border-radius: 0; padding: 32px 24px; box-shadow: none; border: none; border-bottom: 1px solid #f0f0f0;">
        <div class="sidebar-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;" onclick="if(window.innerWidth <= 768) toggleSearchForm()">
            <div style="flex: 1; min-width: 0;">
                <h3 class="sidebar-title" style="font-size: 14px; font-weight: 600; color: #1a1a1a; letter-spacing: 0.05em; text-transform: uppercase; margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">条件でさがす</h3>
                <div class="selected-conditions" id="selectedConditions" style="
                    display: none;
                    margin-top: 4px;
                    font-size: 10px;
                    color: #666;
                    font-weight: 400;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                "></div>
            </div>
            <span class="toggle-icon" style="
                display: none;
                font-size: 16px;
                color: #1a1a1a;
                transition: transform 0.3s ease;
                user-select: none;
                cursor: pointer;
                flex-shrink: 0;
                margin-left: 8px;
            ">▼</span>
        </div>
        <style>
            /* スマホでサイドバーを固定 */
            @media (max-width: 768px) {
                .sidebar {
                    position: sticky !important;
                    top: 0 !important;
                    z-index: 50 !important;
                    background: #ffffff !important;
                    margin-bottom: 0 !important;
                }
                .sidebar-card {
                    position: sticky !important;
                    top: 0 !important;
                    z-index: 50 !important;
                    background: #ffffff !important;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05) !important;
                    margin-bottom: 0 !important;
                    padding: 8px 12px !important;
                }
                .sidebar-header {
                    margin-bottom: 0 !important;
                    cursor: pointer !important;
                    padding: 4px 0;
                    align-items: flex-start !important;
                }
                .sidebar-title {
                    margin-bottom: 0 !important;
                    font-size: 11px !important;
                }
                .selected-conditions {
                    display: block !important;
                }
                .toggle-icon {
                    display: block !important;
                    font-size: 14px !important;
                }
                .search-form {
                    display: none;
                    margin-top: 8px;
                }
                .search-form.active {
                    display: block !important;
                }
                .toggle-icon.active {
                    transform: rotate(180deg);
                }
                .container.main-inner {
                    flex-direction: column !important;
                }
                .sidebar {
                    order: -1 !important;
                }
            }
            @media (max-width: 1024px) {
                .sidebar-card {
                    padding: 24px 20px !important;
                }
                .sidebar-title {
                    font-size: 13px !important;
                    margin-bottom: 20px !important;
                }
                .form-group {
                    margin-bottom: 20px !important;
                }
                .form-group label {
                    font-size: 11px !important;
                    margin-bottom: 6px !important;
                }
                .form-group input[type="text"],
                .form-group select {
                    padding: 10px 14px !important;
                    font-size: 13px !important;
                }
                button[type="submit"] {
                    padding: 12px 20px !important;
                    font-size: 12px !important;
                }
            }
            @media (max-width: 768px) {
                .sidebar-card {
                    padding: 20px 16px !important;
                }
                .sidebar-title {
                    font-size: 12px !important;
                    margin-bottom: 16px !important;
                }
                .form-group {
                    margin-bottom: 16px !important;
                }
                .form-group label {
                    font-size: 10px !important;
                    margin-bottom: 6px !important;
                }
                .form-group input[type="text"],
                .form-group select {
                    padding: 10px 12px !important;
                    font-size: 12px !important;
                }
                button[type="submit"] {
                    padding: 12px 16px !important;
                    font-size: 11px !important;
                }
            }
            @media (max-width: 480px) {
                .sidebar-card {
                    padding: 16px 12px !important;
                }
                .sidebar-title {
                    font-size: 11px !important;
                    margin-bottom: 12px !important;
                }
                .form-group {
                    margin-bottom: 12px !important;
                }
                .form-group label {
                    font-size: 9px !important;
                    margin-bottom: 4px !important;
                }
                .form-group input[type="text"],
                .form-group select {
                    padding: 8px 10px !important;
                    font-size: 11px !important;
                }
                button[type="submit"] {
                    padding: 10px 14px !important;
                    font-size: 10px !important;
                }
            }
        </style>
        <form class="search-form" id="searchForm" method="GET" action="{{ route('shops.index') }}">
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
    <script>
        function toggleSearchForm() {
            const form = document.getElementById('searchForm');
            const icon = document.querySelector('.toggle-icon');
            
            if (form && icon) {
                form.classList.toggle('active');
                icon.classList.toggle('active');
            }
        }
        
        // 検索条件を取得して表示する関数
        function updateSelectedConditions() {
            if (window.innerWidth > 768) return; // スマホ版のみ
            
            const conditions = [];
            const conditionsDiv = document.getElementById('selectedConditions');
            
            // キーワード
            const keyword = document.getElementById('keyword')?.value?.trim();
            if (keyword) {
                conditions.push('キーワード: ' + keyword);
            }
            
            // カテゴリ
            const category = document.getElementById('category')?.value;
            if (category) {
                const categoryText = document.getElementById('category').options[document.getElementById('category').selectedIndex].text;
                conditions.push('カテゴリ: ' + categoryText);
            }
            
            // 並び替え
            const sort = document.getElementById('sort')?.value;
            if (sort && sort !== 'random') {
                const sortText = document.getElementById('sort').options[document.getElementById('sort').selectedIndex].text;
                conditions.push('並び替え: ' + sortText);
            }
            
            // 条件を表示
            if (conditions.length > 0 && conditionsDiv) {
                conditionsDiv.textContent = conditions.join(' / ');
                conditionsDiv.style.display = 'block';
            } else if (conditionsDiv) {
                conditionsDiv.style.display = 'none';
            }
        }
        
        // 検索フォーム送信時に条件を表示して折りたたむ
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    if (window.innerWidth <= 768) {
                        // 送信前に条件を更新
                        updateSelectedConditions();
                        // フォームを折りたたむ
                        const form = document.getElementById('searchForm');
                        const icon = document.querySelector('.toggle-icon');
                        if (form && icon) {
                            form.classList.remove('active');
                            icon.classList.remove('active');
                        }
                    }
                });
            }
            
            // 入力の変更を監視
            const keywordInput = document.getElementById('keyword');
            if (keywordInput) {
                keywordInput.addEventListener('input', updateSelectedConditions);
            }
            
            const categorySelect = document.getElementById('category');
            if (categorySelect) {
                categorySelect.addEventListener('change', updateSelectedConditions);
            }
            
            const sortSelect = document.getElementById('sort');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    updateSelectedConditions();
                    // 並び替えは自動送信されるので、折りたたむ
                    if (window.innerWidth <= 768) {
                        const form = document.getElementById('searchForm');
                        const icon = document.querySelector('.toggle-icon');
                        if (form && icon) {
                            form.classList.remove('active');
                            icon.classList.remove('active');
                        }
                    }
                });
            }
            
            // ページ読み込み時に現在の検索条件を表示
            const urlParams = new URLSearchParams(window.location.search);
            const hasSearchParams = urlParams.has('keyword') || urlParams.has('category') || urlParams.has('sort');
            
            if (hasSearchParams && window.innerWidth <= 768) {
                // 少し遅延させてから条件を更新（フォームが読み込まれた後）
                setTimeout(function() {
                    updateSelectedConditionsFromURL();
                }, 300);
            } else if (window.innerWidth <= 768) {
                // 検索条件がない場合も、フォームの状態を確認
                setTimeout(function() {
                    updateSelectedConditions();
                }, 200);
            }
        });
        
        // URLパラメータから検索条件を読み取って表示
        function updateSelectedConditionsFromURL() {
            if (window.innerWidth > 768) return; // スマホ版のみ
            
            const conditions = [];
            const conditionsDiv = document.getElementById('selectedConditions');
            const urlParams = new URLSearchParams(window.location.search);
            
            // キーワード
            const keyword = urlParams.get('keyword');
            if (keyword && keyword.trim()) {
                conditions.push('キーワード: ' + keyword.trim());
            }
            
            // カテゴリ
            const category = urlParams.get('category');
            if (category) {
                const categorySelect = document.getElementById('category');
                if (categorySelect) {
                    const categoryText = categorySelect.options[categorySelect.selectedIndex]?.text || category;
                    conditions.push('カテゴリ: ' + categoryText);
                }
            }
            
            // 並び替え
            const sort = urlParams.get('sort');
            if (sort && sort !== 'random') {
                const sortSelect = document.getElementById('sort');
                if (sortSelect) {
                    const sortText = sortSelect.options[sortSelect.selectedIndex]?.text || sort;
                    conditions.push('並び替え: ' + sortText);
                }
            }
            
            // 条件を表示
            if (conditions.length > 0 && conditionsDiv) {
                conditionsDiv.textContent = conditions.join(' / ');
                conditionsDiv.style.display = 'block';
            } else if (conditionsDiv) {
                conditionsDiv.style.display = 'none';
            }
        }
    </script>
@endsection

@section('content')
    <div style="margin-top: 24px;">
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
    </div>
@endsection

