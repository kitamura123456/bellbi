@extends('layouts.app')

@section('title', 'スカウト用プロフィール | Bellbi')

@section('sidebar')
    <div class="sidebar-card">
        <div class="mypage-menu-header" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;" onclick="if(window.innerWidth <= 768) toggleMypageMenu()">
            <h3 class="sidebar-title" style="margin: 0;">メニュー</h3>
            <span class="mypage-toggle-icon" style="
                display: none;
                font-size: 16px;
                color: #1a1a1a;
                transition: transform 0.3s ease;
                user-select: none;
                flex-shrink: 0;
                margin-left: 8px;
            ">▼</span>
        </div>
        <ul class="sidebar-menu mypage-menu-list" id="mypageMenuList">
            <li><a href="{{ route('mypage') }}" class="sidebar-menu-link">応募履歴</a></li>
            <li><a href="{{ route('mypage.scouts.index') }}" class="sidebar-menu-link">スカウト受信</a></li>
            <li><a href="{{ route('mypage.messages.index') }}" class="sidebar-menu-link">メッセージ</a></li>
            <li><a href="{{ route('mypage.scout-profile.edit') }}" class="sidebar-menu-link active">スカウト用プロフィール</a></li>
            <li><a href="{{ route('mypage.reservations.index') }}" class="sidebar-menu-link">予約履歴</a></li>
            <li><a href="{{ route('mypage.orders.index') }}" class="sidebar-menu-link">注文履歴</a></li>
        </ul>
    </div>
    <style>
        /* デスクトップ版の固定メニュー */
        .sidebar {
            position: sticky !important;
            top: 0 !important;
            align-self: flex-start !important;
            z-index: 40 !important;
            max-height: 100vh !important;
            overflow-y: auto !important;
        }
        .sidebar-card {
            position: sticky !important;
            top: 0 !important;
        }
        .sidebar-menu,
        .mypage-menu-list {
            position: relative !important;
        }
        
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
            .sidebar-menu,
            .mypage-menu-list {
                position: relative !important;
            }
            .mypage-menu-header {
                padding: 4px 0 !important;
                margin-bottom: 0 !important;
            }
            .sidebar-title {
                font-size: 11px !important;
                margin-bottom: 0 !important;
            }
            .mypage-toggle-icon {
                display: block !important;
                font-size: 14px !important;
            }
            .mypage-menu-list {
                display: none;
                margin-top: 8px;
            }
            .mypage-menu-list.active {
                display: block !important;
            }
            .mypage-toggle-icon.active {
                transform: rotate(180deg);
            }
            .container.main-inner {
                flex-direction: column !important;
            }
            .sidebar {
                order: -1 !important;
            }
            .page-header {
                margin-top: 24px !important;
            }
        }
    </style>
    <script>
        function toggleMypageMenu() {
            const menu = document.getElementById('mypageMenuList');
            const icon = document.querySelector('.mypage-toggle-icon');
            
            if (menu && icon) {
                menu.classList.toggle('active');
                icon.classList.toggle('active');
            }
        }
    </script>
@endsection

@section('content')
    <div class="page-header">
        <p class="page-label">Scout Profile</p>
        <h1 class="page-title">スカウト用プロフィール</h1>
        <p class="page-lead">企業からスカウトを受けるためのプロフィール設定です。</p>
    </div>

    <div class="job-detail-card">
        <form action="{{ route('mypage.scout-profile.update') }}" method="POST" class="company-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="industry_type">希望業種 <span class="required">必須</span></label>
                <select id="industry_type" name="industry_type" required>
                    <option value="">選択してください</option>
                    <option value="1" {{ old('industry_type', $profile->industry_type) == 1 ? 'selected' : '' }}>美容</option>
                    <option value="2" {{ old('industry_type', $profile->industry_type) == 2 ? 'selected' : '' }}>医療</option>
                    <option value="3" {{ old('industry_type', $profile->industry_type) == 3 ? 'selected' : '' }}>歯科</option>
                </select>
                @error('industry_type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="desired_job_category">希望職種 <span class="required">必須</span></label>
                <select id="desired_job_category" name="desired_job_category" required>
                    <option value="">選択してください</option>
                    <option value="1" {{ old('desired_job_category', $profile->desired_job_category) == 1 ? 'selected' : '' }}>スタイリスト</option>
                    <option value="2" {{ old('desired_job_category', $profile->desired_job_category) == 2 ? 'selected' : '' }}>アシスタント</option>
                    <option value="3" {{ old('desired_job_category', $profile->desired_job_category) == 3 ? 'selected' : '' }}>エステティシャン</option>
                    <option value="4" {{ old('desired_job_category', $profile->desired_job_category) == 4 ? 'selected' : '' }}>看護師</option>
                    <option value="5" {{ old('desired_job_category', $profile->desired_job_category) == 5 ? 'selected' : '' }}>歯科衛生士</option>
                    <option value="99" {{ old('desired_job_category', $profile->desired_job_category) == 99 ? 'selected' : '' }}>その他</option>
                </select>
                @error('desired_job_category')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="experience_years">経験年数</label>
                <input type="number" id="experience_years" name="experience_years" value="{{ old('experience_years', $profile->experience_years) }}" min="0" max="50" placeholder="例：3">
                <small>年数を入力してください</small>
                @error('experience_years')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="desired_work_style">希望勤務形態</label>
                <select id="desired_work_style" name="desired_work_style">
                    <option value="">選択してください</option>
                    <option value="1" {{ old('desired_work_style', $profile->desired_work_style) == 1 ? 'selected' : '' }}>正社員</option>
                    <option value="2" {{ old('desired_work_style', $profile->desired_work_style) == 2 ? 'selected' : '' }}>パート・アルバイト</option>
                    <option value="3" {{ old('desired_work_style', $profile->desired_work_style) == 3 ? 'selected' : '' }}>業務委託</option>
                    <option value="4" {{ old('desired_work_style', $profile->desired_work_style) == 4 ? 'selected' : '' }}>時短勤務</option>
                </select>
                @error('desired_work_style')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="is_public">公開設定 <span class="required">必須</span></label>
                <select id="is_public" name="is_public" required>
                    <option value="0" {{ old('is_public', $profile->is_public) == 0 ? 'selected' : '' }}>非公開（スカウトを受けない）</option>
                    <option value="1" {{ old('is_public', $profile->is_public) == 1 ? 'selected' : '' }}>公開（スカウトを受ける）</option>
                </select>
                <small>公開にすると、企業からスカウトメッセージが届くようになります。</small>
                @error('is_public')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" style="
                    padding: 10px 24px;
                    background: #1a1a1a;
                    color: #ffffff;
                    border: none;
                    border-radius: 4px;
                    font-size: 13px;
                    font-weight: 500;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;
                    cursor: pointer;
                    transition: all 0.15s ease;
                " onmouseover="this.style.backgroundColor='#333333';" onmouseout="this.style.backgroundColor='#1a1a1a';">
                    保存する
                </button>
            </div>
        </form>
    </div>
@endsection


