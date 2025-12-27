@extends('layouts.company')

@section('title', '取引登録')

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="margin: 0; font-size: 24px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">取引登録</h1>
</div>

<div style="
    padding: 0;
    border: none;
    box-shadow: 0 1px 2px rgba(93, 83, 94, 0.1);
    border-radius: 0;
    background: #ffffff;
    max-width: 700px;
">
    <div style="padding: 20px 24px; border-bottom: 1px solid #e8e8e8;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #5D535E; letter-spacing: 0.3px; font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;">取引情報</h3>
    </div>
    <form action="{{ route('company.transactions.store') }}" method="POST" style="padding: 24px; display: flex; flex-direction: column; gap: 24px;">
        @csrf

        <div style="display: flex; flex-direction: column;">
            <label for="transaction_type" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                種別 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select name="transaction_type" id="transaction_type" required style="
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
                <option value="">選択してください</option>
                <option value="1" {{ old('transaction_type', $transactionType) == 1 ? 'selected' : '' }}>売上</option>
                <option value="2" {{ old('transaction_type', $transactionType) == 2 ? 'selected' : '' }}>経費</option>
            </select>
            @error('transaction_type')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="store_id" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                店舗 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select name="store_id" id="store_id" required style="
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
                <option value="">選択してください</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            @error('store_id')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="date" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                日付 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required style="
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
            @error('date')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="account_item_id" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                科目 <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <select name="account_item_id" id="account_item_id" required style="
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
                <option value="">選択してください</option>
                <optgroup label="売上科目" id="revenue-items" style="display: none;">
                    @foreach ($revenueItems as $item)
                        <option value="{{ $item->id }}" data-tax-rate="{{ $item->default_tax_rate }}" {{ old('account_item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </optgroup>
                <optgroup label="経費科目" id="expense-items" style="display: none;">
                    @foreach ($expenseItems as $item)
                        <option value="{{ $item->id }}" data-tax-rate="{{ $item->default_tax_rate }}" {{ old('account_item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </optgroup>
            </select>
            @error('account_item_id')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
            <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">
                科目が見つからない場合は、<a href="{{ route('company.account-items.index') }}" target="_blank" style="color: #90AFC5; text-decoration: underline;">科目マスタ</a>から追加してください
            </small>
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="amount" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">
                金額（税抜） <span style="color: #763626; font-size: 11px; font-weight: 400;">必須</span>
            </label>
            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="0" style="
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
            @error('amount')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="tax_amount" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">税額</label>
            <input type="number" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', 0) }}" min="0" style="
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
            @error('tax_amount')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
            <small style="display: block; margin-top: 6px; color: #999999; font-size: 12px;">科目のデフォルト税率から自動計算されます</small>
        </div>

        <div style="display: flex; flex-direction: column;">
            <label style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">合計金額（税込）</label>
            <div id="total-amount" style="
                font-size: 24px;
                font-weight: 700;
                color: #059669;
                padding: 16px;
                background: #f0fdf4;
                border-radius: 12px;
                text-align: center;
            ">¥0</div>
        </div>

        <div style="display: flex; flex-direction: column;">
            <label for="note" style="
                display: block;
                margin-bottom: 8px;
                font-size: 13px;
                font-weight: 700;
                color: #5D535E;
                font-family: 'Hiragino Sans', 'Yu Gothic', 'Meiryo', sans-serif;
            ">備考</label>
            <textarea name="note" id="note" rows="3" maxlength="1000" style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                font-size: 14px;
                font-family: inherit;
                line-height: 1.5;
                color: #2A3132;
                background: #fafafa;
                transition: all 0.2s ease;
                resize: vertical;
                box-sizing: border-box;
            " onfocus="this.style.borderColor='#90AFC5'; this.style.background='#ffffff';" onblur="this.style.borderColor='#e8e8e8'; this.style.background='#fafafa';">{{ old('note') }}</textarea>
            @error('note')
                <span style="display: block; margin-top: 6px; color: #763626; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
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
                キャンセル
            </a>
            <button type="submit" style="
                padding: 12px 32px;
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
                登録する
            </button>
        </div>
    </form>
</div>


<script>
$(document).ready(function() {
    // 種別に応じて科目の選択肢を切り替え
    function updateAccountItems() {
        var type = $('#transaction_type').val();
        $('#revenue-items').hide();
        $('#expense-items').hide();
        $('#account_item_id').val('');
        
        if (type == '1') {
            $('#revenue-items').show();
        } else if (type == '2') {
            $('#expense-items').show();
        }
    }

    // 税額と合計金額を自動計算
    function calculateTax() {
        var amount = parseFloat($('#amount').val()) || 0;
        var selectedOption = $('#account_item_id option:selected');
        var taxRate = parseFloat(selectedOption.data('tax-rate')) || 0;
        
        var taxAmount = Math.floor(amount * taxRate / 100);
        $('#tax_amount').val(taxAmount);
        
        updateTotal();
    }

    function updateTotal() {
        var amount = parseFloat($('#amount').val()) || 0;
        var taxAmount = parseFloat($('#tax_amount').val()) || 0;
        var total = amount + taxAmount;
        
        $('#total-amount').text('¥' + total.toLocaleString());
    }

    // イベントハンドラー
    $('#transaction_type').on('change', updateAccountItems);
    $('#account_item_id').on('change', calculateTax);
    $('#amount').on('input', calculateTax);
    $('#tax_amount').on('input', updateTotal);

    // 初期化
    updateAccountItems();
    updateTotal();
});
</script>

<style>
/* スマホ用レスポンシブデザイン */
@media (max-width: 768px) {
    div[style*="margin-bottom: 24px; display: flex"] {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] h1 {
        font-size: 20px !important;
        margin-bottom: 0 !important;
    }

    div[style*="margin-bottom: 24px; display: flex"] > a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 10px 16px;
    }

    .form-container {
        padding: 16px !important;
    }

    .form-actions {
        flex-direction: column !important;
        gap: 8px !important;
    }

    .form-actions button,
    .form-actions a {
        width: 100%;
        text-align: center;
        font-size: 13px;
        padding: 12px 16px;
    }

    .form-control {
        font-size: 16px !important;
        padding: 10px 12px !important;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 12px !important;
    }
}
</style>
@endsection


