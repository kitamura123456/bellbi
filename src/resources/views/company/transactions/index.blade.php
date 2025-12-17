@extends('layouts.company')

@section('title', '売上・経費管理')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">売上・経費管理</h1>
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('company.account-items.index') }}" style="
            padding: 12px 24px;
            background: transparent;
            color: #5D535E;
            border: 1px solid #5D535E;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
            科目マスタ
        </a>
        <a href="{{ route('company.transactions.report') }}" style="
            padding: 12px 24px;
            background: transparent;
            color: #5D535E;
            border: 1px solid #5D535E;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
            月次レポート
        </a>
        <a href="{{ route('company.transactions.create') }}" style="
            padding: 12px 24px;
            background: #5D535E;
            color: #ffffff;
            border: none;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
            取引を登録
        </a>
    </div>
</div>

@if (session('success'))
    <div style="
        padding: 16px 20px;
        background: #f0fdf4;
        border: 1px solid #86efac;
        border-radius: 12px;
        margin-bottom: 24px;
        color: #166534;
        font-size: 14px;
    ">
        {{ session('success') }}
    </div>
@endif

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    margin-bottom: 24px;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">検索条件</h3>
    </div>
    <form action="{{ route('company.transactions.index') }}" method="GET" style="padding: 24px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
            <div>
                <label for="store_id" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">店舗</label>
                <select name="store_id" id="store_id" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                    <option value="">すべて</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="transaction_type" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">種別</label>
                <select name="transaction_type" id="transaction_type" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
                    <option value="">すべて</option>
                    <option value="1" {{ request('transaction_type') == 1 ? 'selected' : '' }}>売上</option>
                    <option value="2" {{ request('transaction_type') == 2 ? 'selected' : '' }}>経費</option>
                </select>
            </div>

            <div>
                <label for="start_date" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">開始日</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            </div>

            <div>
                <label for="end_date" style="
                    display: block;
                    margin-bottom: 8px;
                    font-size: 13px;
                    font-weight: 700;
                    color: #5D535E;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                ">終了日</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" style="
                    width: 100%;
                    padding: 12px 16px;
                    border: 1px solid #e8e8e8;
                    border-radius: 12px;
                    font-size: 14px;
                    font-family: inherit;
                    color: #2A3132;
                    background: #fafafa;
                    transition: all 0.2s ease;
                    box-sizing: border-box;
                " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: space-between; align-items: center;">
            <div style="display: flex; gap: 12px;">
                <button type="submit" style="
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
                    position: relative;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                    検索
                </button>
                <a href="{{ route('company.transactions.index') }}" style="
                    padding: 12px 24px;
                    background: transparent;
                    color: #5D535E;
                    border: 1px solid #5D535E;
                    border-radius: 24px;
                    font-size: 14px;
                    font-weight: 700;
                    font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    position: relative;
                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                    クリア
                </a>
            </div>
            <a href="{{ route('company.transactions.export') }}?{{ http_build_query(request()->all()) }}" style="
                padding: 12px 24px;
                background: transparent;
                color: #5D535E;
                border: 1px solid #5D535E;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                CSVエクスポート
            </a>
        </div>
    </form>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    overflow-x: auto;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">取引一覧</h3>
    </div>
    @if ($transactions->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 1px solid #e8e8e8;">
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                        white-space: nowrap;
                    ">日付</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">店舗</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: center;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">種別</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">科目</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: right;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">金額</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: right;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">税額</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: right;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">合計</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: left;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">備考</th>
                    <th style="
                        padding: 12px 16px;
                        text-align: center;
                        font-weight: 700;
                        color: #5D535E;
                        font-size: 13px;
                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                    ">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr style="border-bottom: 1px solid #f5f5f5;" onmouseover="this.style.background='#fafafa';" onmouseout="this.style.background='#ffffff';">
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px; font-family: 'Courier New', monospace;">{{ $transaction->date->format('Y/m/d') }}</td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $transaction->store->name }}</td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                background: {{ $transaction->transaction_type == 1 ? '#336B87' : '#763626' }};
                                color: #ffffff;
                                border-radius: 12px;
                                font-size: 12px;
                                font-weight: 500;
                            ">
                                {{ $transaction->type_name }}
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px;">{{ $transaction->accountItem->name }}</td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px; text-align: right; font-family: 'Courier New', monospace;">¥{{ number_format($transaction->amount) }}</td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px; text-align: right; font-family: 'Courier New', monospace;">¥{{ number_format($transaction->tax_amount) }}</td>
                        <td style="padding: 12px 16px; color: #336B87; font-size: 14px; text-align: right; font-weight: 600; font-family: 'Courier New', monospace;">¥{{ number_format($transaction->total_amount) }}</td>
                        <td style="padding: 12px 16px; color: #2A3132; font-size: 14px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Str::limit($transaction->note, 30) }}</td>
                        <td style="padding: 12px 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('company.transactions.edit', $transaction) }}" style="
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
                                    position: relative;
                                " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)'; this.style.background='#5D535E'; this.style.color='#ffffff';" onmouseout="this.style.boxShadow='none'; this.style.background='transparent'; this.style.color='#5D535E';">
                                    編集
                                </a>
                                <form action="{{ route('company.transactions.destroy', $transaction) }}" method="POST" style="display: inline;" onsubmit="return confirm('この取引を削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="
                                        padding: 6px 16px;
                                        background: #763626;
                                        color: #ffffff;
                                        border: none;
                                        border-radius: 16px;
                                        font-size: 12px;
                                        font-weight: 700;
                                        font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                                        cursor: pointer;
                                        transition: all 0.2s ease;
                                        position: relative;
                                    " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                                        削除
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding: 24px; border-top: 1px solid #e8e8e8;">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    @else
        <div style="padding: 60px 24px; text-align: center;">
            <p style="margin: 0 0 20px 0; color: #999999; font-size: 16px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">取引が登録されていません。</p>
            <a href="{{ route('company.transactions.create') }}" style="
                padding: 12px 24px;
                background: #5D535E;
                color: #ffffff;
                border: none;
                border-radius: 24px;
                font-size: 14px;
                font-weight: 700;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            " onmouseover="this.style.boxShadow='inset 0 0 0 1px rgba(255,255,255,0.3)';" onmouseout="this.style.boxShadow='none';">
                最初の取引を登録
            </a>
        </div>
    @endif
</div>

@endsection


