@extends('layouts.app')

@section('title', $store->name . ' - 予約 | Bellbi')

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $store->name }}</h1>
        <p class="page-lead">{{ $store->company->name }}</p>
    </div>

    @if($store->thumbnail_image)
        @if(strpos($store->thumbnail_image, 'templates/') === 0)
        <div class="job-detail-card">
            <img src="{{ asset('images/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 12px;">
        </div>
        @elseif(file_exists(public_path('storage/' . $store->thumbnail_image)))
        <div class="job-detail-card">
            <img src="{{ asset('storage/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 12px;">
        </div>
        @else
        <div class="job-detail-card">
            <div style="width: 100%; height: 300px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 18px;">
                No Image
            </div>
        </div>
        @endif
    @endif

    @if($store->description)
    <div class="job-detail-card">
        <h3 style="margin-top: 0;">店舗について</h3>
        <p style="white-space: pre-wrap; line-height: 1.8;">{{ $store->description }}</p>
    </div>
    @endif

    <div class="job-detail-card">
        <h3 style="margin-top: 0;">店舗情報</h3>
        <table class="company-table">
            @if($store->address)
            <tr>
                <th style="width: 120px;">住所</th>
                <td>{{ $store->address }}</td>
            </tr>
            @endif
            @if($store->tel)
            <tr>
                <th>電話番号</th>
                <td>{{ $store->tel }}</td>
            </tr>
            @endif
            @if($store->cancel_deadline_hours)
            <tr>
                <th>キャンセル</th>
                <td>予約の{{ $store->cancel_deadline_hours }}時間前までキャンセル可能</td>
            </tr>
            @endif
        </table>
    </div>

    <form action="{{ route('reservations.booking', $store) }}" method="POST" id="reservation-form">
        @csrf

        <div class="job-detail-card">
            <h3 style="margin-top: 0;">メニュー選択 <span class="required">必須</span></h3>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px;">ご希望のメニューを選択してください（複数選択可）</p>
            
            @if($menus->isEmpty())
                <p class="empty-message">メニューが登録されていません。</p>
            @else
                @foreach($menus as $menu)
                <label style="display: flex; gap: 12px; padding: 12px; background-color: #f9fafb; border-radius: 8px; margin-bottom: 12px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;" onmouseover="this.style.borderColor='#fb7185'" onmouseout="this.style.borderColor='transparent'">
                    <input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}" style="margin-top: 4px;">
                    <div style="flex-shrink: 0;">
                        @if($menu->thumbnail_image)
                            @if(strpos($menu->thumbnail_image, 'templates/') === 0)
                                <img src="{{ asset('images/' . $menu->thumbnail_image) }}" alt="{{ $menu->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            @elseif(file_exists(public_path('storage/' . $menu->thumbnail_image)))
                                <img src="{{ asset('storage/' . $menu->thumbnail_image) }}" alt="{{ $menu->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            @else
                                <div style="width: 80px; height: 80px; background: #f3f4f6; border: 1px dashed #d1d5db; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 11px;">
                                    No Image
                                </div>
                            @endif
                        @else
                            <div style="width: 80px; height: 80px; background: #f3f4f6; border: 1px dashed #d1d5db; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 11px;">
                                No Image
                            </div>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <div>
                            <strong style="font-size: 15px;">{{ $menu->name }}</strong>
                            <span style="color: #fb7185; margin-left: 12px; font-weight: 600;">{{ number_format($menu->price) }}円</span>
                            <span style="color: #6b7280; margin-left: 8px; font-size: 14px;">（{{ $menu->duration_minutes }}分）</span>
                        </div>
                        @if($menu->description)
                            <div style="font-size: 13px; color: #6b7280; margin-top: 6px; line-height: 1.6;">{{ $menu->description }}</div>
                        @endif
                    </div>
                </label>
                @endforeach
            @endif

            @error('menu_ids')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        @if($staffs->isNotEmpty())
        <div class="job-detail-card">
            <h3 style="margin-top: 0;">スタッフ指名（任意）</h3>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 16px;">ご希望のスタッフを選択してください</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px;">
                <label style="display: flex; flex-direction: column; align-items: center; padding: 12px; background-color: #f9fafb; border-radius: 8px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;" class="staff-option">
                    <input type="radio" name="staff_id" value="" checked style="display: none;">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fb7185; font-size: 12px; margin-bottom: 8px;">
                        指名なし
                    </div>
                    <span style="font-size: 14px; font-weight: 500; text-align: center;">指名なし</span>
                </label>
                @foreach($staffs as $staff)
                    <label style="display: flex; flex-direction: column; align-items: center; padding: 12px; background-color: #f9fafb; border-radius: 8px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;" class="staff-option">
                        <input type="radio" name="staff_id" value="{{ $staff->id }}" style="display: none;">
                        @if($staff->image_path)
                            <img src="{{ asset('storage/' . $staff->image_path) }}" alt="{{ $staff->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; margin-bottom: 8px;">
                        @else
                            <div style="width: 80px; height: 80px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 11px; margin-bottom: 8px;">
                                No Image
                            </div>
                        @endif
                        <span style="font-size: 14px; font-weight: 500; text-align: center;">{{ $staff->name }}</span>
                    </label>
                @endforeach
            </div>
            
            <style>
            .staff-option:hover {
                border-color: #fb7185 !important;
                background-color: #fef2f2 !important;
            }
            .staff-option input:checked + div,
            .staff-option input:checked ~ img,
            .staff-option:has(input:checked) {
                border-color: #fb7185 !important;
                background-color: #fef2f2 !important;
            }
            </style>
            
            @error('staff_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="btn-primary">日時を選択する</button>
            <a href="{{ route('reservations.search') }}" class="btn-secondary">戻る</a>
        </div>
    </form>
@endsection

