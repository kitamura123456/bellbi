@extends('layouts.company')

@section('title', 'メニュー管理')

@section('content')
<div class="company-header">
    <h1 class="company-title">メニュー管理</h1>
    <a href="{{ route('company.menus.create') }}" class="btn-primary">メニュー追加</a>
</div>

@foreach($stores as $store)
<div class="company-card" style="margin-bottom: 24px;">
    <h3 style="margin-top: 0;">{{ $store->name }}</h3>
    
    @if($store->serviceMenus->isEmpty())
        <p class="empty-message">メニューが登録されていません。</p>
    @else
        <table class="company-table">
            <thead>
                <tr>
                    <th>メニュー名</th>
                    <th>所要時間</th>
                    <th>料金</th>
                    <th>ステータス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($store->serviceMenus as $menu)
                <tr>
                    <td>{{ $menu->name }}</td>
                    <td>{{ $menu->duration_minutes }}分</td>
                    <td>{{ number_format($menu->price) }}円</td>
                    <td>
                        @if($menu->is_active)
                            <span class="badge badge-success">公開中</span>
                        @else
                            <span class="badge">非公開</span>
                        @endif
                    </td>
                    <td class="company-actions">
                        <a href="{{ route('company.menus.edit', $menu) }}" class="btn-secondary btn-sm">編集</a>
                        <form action="{{ route('company.menus.destroy', $menu) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm" onclick="return confirm('このメニューを削除してもよろしいですか？')">削除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endforeach

@if($stores->isEmpty())
    <div class="company-card">
        <p class="empty-message">店舗が登録されていません。先に店舗を登録してください。</p>
        <a href="{{ route('company.stores.create') }}" class="btn-primary">店舗を登録する</a>
    </div>
@endif
@endsection

