@extends('layouts.company')

@section('title', 'スタッフ管理')

@section('content')
<div class="company-header">
    <h1 class="company-title">スタッフ管理</h1>
    <a href="{{ route('company.staffs.create') }}" class="btn-primary">スタッフ追加</a>
</div>

@foreach($stores as $store)
<div class="company-card" style="margin-bottom: 24px;">
    <h3 style="margin-top: 0;">{{ $store->name }}</h3>
    
    @if($store->staffs->isEmpty())
        <p class="empty-message">スタッフが登録されていません。</p>
    @else
        <table class="company-table">
            <thead>
                <tr>
                    <th style="width: 80px;">写真</th>
                    <th>スタッフ名</th>
                    <th style="width: 80px;">表示順</th>
                    <th style="width: 100px;">ステータス</th>
                    <th style="width: 160px;">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($store->staffs as $staff)
                <tr>
                    <td>
                        @if($staff->image_path)
                            <img src="{{ asset('storage/' . $staff->image_path) }}" alt="{{ $staff->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                        @else
                            <div style="width: 60px; height: 60px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 10px;">
                                No Image
                            </div>
                        @endif
                    </td>
                    <td>{{ $staff->name }}</td>
                    <td>{{ $staff->display_order }}</td>
                    <td>
                        @if($staff->is_active)
                            <span class="badge badge-success">公開中</span>
                        @else
                            <span class="badge">非公開</span>
                        @endif
                    </td>
                    <td class="company-actions">
                        <a href="{{ route('company.staffs.edit', $staff) }}" class="btn-secondary btn-sm">編集</a>
                        <form action="{{ route('company.staffs.destroy', $staff) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger btn-sm" onclick="return confirm('このスタッフを削除してもよろしいですか？')">削除</button>
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

