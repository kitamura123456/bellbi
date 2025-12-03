@extends('layouts.company')

@section('title', '店舗管理')

@section('content')
<div class="company-header">
    <h1 class="company-title">店舗管理</h1>
    <a href="{{ route('company.stores.create') }}" class="btn-primary">新規店舗追加</a>
</div>

<div class="company-card">
    <table class="company-table">
        <thead>
            <tr>
                <th>店舗名</th>
                <th>種別</th>
                <th>住所</th>
                <th>電話番号</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stores as $store)
            <tr>
                <td>{{ $store->name }}</td>
                <td>
                    @if($store->store_type === 1) 美容室
                    @elseif($store->store_type === 2) エステ
                    @elseif($store->store_type === 3) 医科
                    @elseif($store->store_type === 4) 歯科
                    @else その他
                    @endif
                </td>
                <td>{{ $store->address ?? '未登録' }}</td>
                <td>{{ $store->tel ?? '未登録' }}</td>
                <td class="company-actions">
                    <a href="{{ route('company.stores.edit', $store) }}" class="btn-secondary btn-sm">編集</a>
                    <form action="{{ route('company.stores.destroy', $store) }}" method="POST" style="display:inline;" onsubmit="return confirm('この店舗を削除してもよろしいですか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="empty-message">店舗が登録されていません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

