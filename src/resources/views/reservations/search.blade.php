@extends('layouts.app')

@section('title', '予約可能な店舗を探す | Bellbi')

@section('content')
    <div class="page-header">
        <p class="page-label">Reservation</p>
        <h1 class="page-title">予約可能な店舗</h1>
        <p class="page-lead">お気に入りのサロンを見つけて予約しましょう。</p>
    </div>

    @forelse($stores as $store)
    <div class="job-card" style="margin-bottom: 20px; display: flex; gap: 16px;">
        <div style="flex-shrink: 0;">
            @if($store->thumbnail_image)
                @if(strpos($store->thumbnail_image, 'templates/') === 0)
                    <img src="{{ asset('images/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                @elseif(file_exists(public_path('storage/' . $store->thumbnail_image)))
                    <img src="{{ asset('storage/' . $store->thumbnail_image) }}" alt="{{ $store->name }}" style="width: 180px; height: 180px; object-fit: cover; border-radius: 12px;">
                @else
                    <div style="width: 180px; height: 180px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px;">
                        No Image
                    </div>
                @endif
            @else
                <div style="width: 180px; height: 180px; background: #f3f4f6; border: 2px dashed #d1d5db; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 14px;">
                    No Image
                </div>
            @endif
        </div>
        <div style="flex: 1; display: flex; flex-direction: column;">
            <div class="job-card-body" style="flex: 1;">
                <span class="job-card-tag">予約可能</span>
                <h3 class="job-card-title">
                    <a href="{{ route('reservations.store', $store) }}">{{ $store->name }}</a>
                </h3>
                <p class="job-card-salon">{{ $store->company->name }}</p>
                @if($store->description)
                    <p style="margin-top: 8px; font-size: 14px; color: #6b7280; line-height: 1.6;">
                        {{ Str::limit($store->description, 120) }}
                    </p>
                @endif
                @if($store->address)
                    <p class="job-card-location" style="margin-top: 8px;">
                        <svg style="display: inline-block; width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $store->address }}
                    </p>
                @endif
            </div>
            <div class="job-card-footer">
                <a href="{{ route('reservations.store', $store) }}" class="btn-primary btn-sm">予約する</a>
            </div>
        </div>
    </div>
    @empty
    <p class="empty-message">現在予約可能な店舗はありません。</p>
    @endforelse

    @if($stores->hasPages())
    <div class="pagination-wrapper">
        {{ $stores->links() }}
    </div>
    @endif
@endsection

