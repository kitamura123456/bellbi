@extends('layouts.company')

@section('title', '応募管理')

@section('content')
<style>
    @media (max-width: 768px) {
        .applications-header h1 {
            font-size: 20px !important;
            margin-bottom: 12px !important;
        }
        
        .applications-table-header {
            flex-direction: column !important;
            gap: 12px !important;
            padding: 16px !important;
        }
        
        .applications-table-actions {
            flex-direction: column !important;
            width: 100% !important;
            gap: 8px !important;
        }
        
        .applications-table-actions button {
            width: 100% !important;
            font-size: 12px !important;
            padding: 10px 16px !important;
        }
        
        .applications-table {
            display: none;
        }
        
        .applications-cards {
            display: block;
            padding: 16px;
        }
        
        .application-card {
            background: #ffffff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }
        
        .application-card.unread {
            background: #fef3c7;
            border-color: #fbbf24;
        }
        
        .application-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e8e8e8;
        }
        
        .application-card-body {
            display: grid;
            gap: 8px;
            font-size: 13px;
        }
        
        .application-card-row {
            display: flex;
            justify-content: space-between;
        }
        
        .application-card-label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .application-card-value {
            color: #2A3132;
            font-weight: 600;
        }
        
        .application-card-actions {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e8e8e8;
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .application-card-actions a {
            flex: 1;
            text-align: center;
            padding: 8px 16px;
            background: #5D535E;
            color: #ffffff;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }
    }
    
    @media (min-width: 769px) {
        .applications-cards {
            display: none;
        }
    }
</style>

<div class="applications-header" style="margin-bottom: 24px; margin-top: 48px;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">応募管理</h1>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div class="applications-table-header" style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">応募一覧</h3>
        @if($applications->isNotEmpty())
        <div class="applications-table-actions" style="display: flex; gap: 12px; align-items: center;">
            <button type="button" onclick="markSelectedApplicationsAsViewed()" style="
                padding: 8px 16px;
                background: #2271b1;
                color: #ffffff;
                border: none;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
            ">選択した項目を既読にする</button>
            <span id="unread-count" style="font-size: 13px; color: #666;">
                未読: <strong>{{ $applications->where('viewed_at', null)->count() }}</strong>件
            </span>
        </div>
        @endif
    </div>
    <table class="applications-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    width: 40px;
                ">
                    <input type="checkbox" id="select-all-applications" onchange="toggleAllApplications(this)">
                </th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">応募日</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">求人タイトル</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">応募者</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">ステータス</th>
                <th style="
                    padding: 12px 16px;
                    text-align: left;
                    font-weight: 700;
                    color: #5D535E;
                    font-size: 13px;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">操作</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $application)
            <tr class="application-row {{ $application->isUnread() ? 'unread' : '' }}" style="border-bottom: 1px solid #f5f5f5; {{ $application->isUnread() ? 'background: #fef3c7;' : '' }}" onmouseover="this.style.background='{{ $application->isUnread() ? '#fef3c7' : '#fafafa' }}';" onmouseout="this.style.background='{{ $application->isUnread() ? '#fef3c7' : '#ffffff' }}';">
                <td style="padding: 12px 16px;">
                    <input type="checkbox" class="application-checkbox" value="{{ $application->id }}" data-application-id="{{ $application->id }}">
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">
                    {{ $application->created_at->format('Y-m-d H:i') }}
                    @if($application->isUnread())
                        <span style="display: inline-block; margin-left: 8px; padding: 2px 8px; background: #d63638; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 600;">NEW</span>
                    @endif
                </td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $application->jobPost->title }}</td>
                <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $application->user->name }}</td>
                <td style="padding: 12px 16px;">
                    @if($application->status === 1)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #5D535E;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">応募済</span>
                    @elseif($application->status === 2)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #90AFC5;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">書類選考中</span>
                    @elseif($application->status === 3)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #90AFC5;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">面接中</span>
                    @elseif($application->status === 4)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #336B87;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">内定</span>
                    @elseif($application->status === 5)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #763626;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">不採用</span>
                    @elseif($application->status === 9)
                        <span style="
                            display: inline-block;
                            padding: 4px 12px;
                            background: #999999;
                            color: #ffffff;
                            border-radius: 12px;
                            font-size: 12px;
                            font-weight: 500;
                        ">キャンセル</span>
                    @endif
                </td>
                <td style="padding: 12px 16px;">
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('company.applications.show', $application) }}" style="
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
                            詳細
                        </a>
                        <a href="{{ route('company.messages.create-from-application', $application) }}" style="
                            padding: 6px 16px;
                            background: #5D535E;
                            color: #ffffff;
                            border: none;
                            border-radius: 16px;
                            font-size: 12px;
                            font-weight: 700;
                            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                            text-decoration: none;
                            transition: all 0.2s ease;
                            position: relative;
                        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                            メッセージ
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">応募がありません。</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- スマホ用カードレイアウト -->
    <div class="applications-cards">
        @forelse($applications as $application)
        <div class="application-card {{ $application->isUnread() ? 'unread' : '' }}" data-application-id="{{ $application->id }}">
            <div class="application-card-header">
                <div>
                    <div style="font-size: 16px; font-weight: 700; color: #5D535E;">
                        {{ $application->created_at->format('Y-m-d H:i') }}
                        @if($application->isUnread())
                            <span style="display: inline-block; margin-left: 8px; padding: 2px 8px; background: #d63638; color: #ffffff; border-radius: 10px; font-size: 10px; font-weight: 600;">NEW</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="application-card-body">
                <div class="application-card-row">
                    <span class="application-card-label">求人</span>
                    <span class="application-card-value">{{ $application->jobPost->title }}</span>
                </div>
                <div class="application-card-row">
                    <span class="application-card-label">応募者</span>
                    <span class="application-card-value">{{ $application->user->name }}</span>
                </div>
                <div class="application-card-row">
                    <span class="application-card-label">ステータス</span>
                    <span class="application-card-value">
                        @if($application->status === 1)
                            <span style="display: inline-block; padding: 4px 12px; background: #5D535E; color: #ffffff; border-radius: 12px; font-size: 11px; font-weight: 500;">応募済</span>
                        @elseif($application->status === 2)
                            <span style="display: inline-block; padding: 4px 12px; background: #90AFC5; color: #ffffff; border-radius: 12px; font-size: 11px; font-weight: 500;">書類選考中</span>
                        @elseif($application->status === 3)
                            <span style="display: inline-block; padding: 4px 12px; background: #90AFC5; color: #ffffff; border-radius: 12px; font-size: 11px; font-weight: 500;">面接中</span>
                        @elseif($application->status === 4)
                            <span style="display: inline-block; padding: 4px 12px; background: #336B87; color: #ffffff; border-radius: 12px; font-size: 11px; font-weight: 500;">内定</span>
                        @elseif($application->status === 5)
                            <span style="display: inline-block; padding: 4px 12px; background: #d1fae5; color: #059669; border-radius: 12px; font-size: 11px; font-weight: 500;">採用</span>
                        @elseif($application->status === 9)
                            <span style="display: inline-block; padding: 4px 12px; background: #fee2e2; color: #dc2626; border-radius: 12px; font-size: 11px; font-weight: 500;">不採用</span>
                        @endif
                    </span>
                </div>
            </div>
            <div class="application-card-actions">
                <input type="checkbox" class="application-checkbox" value="{{ $application->id }}" data-application-id="{{ $application->id }}" style="width: 20px; height: 20px;">
                <a href="{{ route('company.applications.show', $application) }}">詳細</a>
            </div>
        </div>
        @empty
        <div style="padding: 40px 16px; text-align: center; color: #999999; font-size: 14px;">応募がありません。</div>
        @endforelse
    </div>
</div>

<script>
function toggleAllApplications(checkbox) {
    const checkboxes = document.querySelectorAll('.application-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

function markSelectedApplicationsAsViewed() {
    const checked = document.querySelectorAll('.application-checkbox:checked');
    if (checked.length === 0) {
        alert('既読にする項目を選択してください。');
        return;
    }
    
    const applicationIds = Array.from(checked).map(cb => parseInt(cb.value));
    
    fetch('{{ route("company.applications.mark-multiple-viewed") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ application_ids: applicationIds })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                try {
                    const err = JSON.parse(text);
                    throw err;
                } catch (e) {
                    throw { message: text || 'Unknown error', status: response.status };
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            checked.forEach(cb => {
                const row = cb.closest('tr');
                if (row) {
                    row.classList.remove('unread');
                    row.style.background = '#ffffff';
                    row.setAttribute('onmouseover', "this.style.background='#fafafa';");
                    row.setAttribute('onmouseout', "this.style.background='#ffffff';");
                    const newBadge = row.querySelector('span[style*="background: #d63638"]');
                    if (newBadge) {
                        newBadge.remove();
                    }
                }
                cb.checked = false;
            });
            const selectAll = document.getElementById('select-all-applications');
            if (selectAll) {
                selectAll.checked = false;
            }
            updateUnreadCount();
            updateSidebarBadge('applications');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('既読の更新に失敗しました: ' + (error.message || error.error || 'Unknown error'));
    });
}

function updateUnreadCount() {
    const unreadRows = document.querySelectorAll('.application-row.unread');
    const countElement = document.getElementById('unread-count');
    if (countElement) {
        countElement.innerHTML = `未読: <strong>${unreadRows.length}</strong>件`;
    }
}

function updateSidebarBadge(type) {
    // サーバーから最新の統計情報を取得
    fetch('{{ route("company.sidebar-stats") }}', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // 受注管理のバッジを更新
        const ordersBadge = document.getElementById('sidebar-orders-badge');
        if (ordersBadge) {
            if (data.newOrdersCount > 0) {
                ordersBadge.textContent = data.newOrdersCount;
                ordersBadge.style.display = 'inline-block';
            } else {
                ordersBadge.style.display = 'none';
            }
        }
        
        // 応募管理のバッジを更新
        const applicationsBadge = document.getElementById('sidebar-applications-badge');
        if (applicationsBadge) {
            if (data.newApplicationsCount > 0) {
                applicationsBadge.textContent = data.newApplicationsCount;
                applicationsBadge.style.display = 'inline-block';
            } else {
                applicationsBadge.style.display = 'none';
            }
        }
        
        // 予約管理のバッジを更新
        const reservationsBadge = document.getElementById('sidebar-reservations-badge');
        if (reservationsBadge) {
            if (data.upcomingReservationsCount > 0) {
                reservationsBadge.textContent = data.upcomingReservationsCount;
                reservationsBadge.style.display = 'inline-block';
            } else {
                reservationsBadge.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error updating sidebar badges:', error);
    });
}
</script>
@endsection

