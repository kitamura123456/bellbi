@extends('layouts.company')

@section('title', '取引編集')

@section('content')
<div class="content-header">
    <h1 class="content-title">取引編集</h1>
</div>

<div class="form-container">
    <form action="{{ route('company.transactions.update', $transaction) }}" method="POST" class="standard-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="transaction_type" class="form-label">種別 <span class="required">*</span></label>
            <select name="transaction_type" id="transaction_type" class="form-control" required>
                <option value="">選択してください</option>
                <option value="1" {{ old('transaction_type', $transaction->transaction_type) == 1 ? 'selected' : '' }}>売上</option>
                <option value="2" {{ old('transaction_type', $transaction->transaction_type) == 2 ? 'selected' : '' }}>経費</option>
            </select>
            @error('transaction_type')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="store_id" class="form-label">店舗 <span class="required">*</span></label>
            <select name="store_id" id="store_id" class="form-control" required>
                <option value="">選択してください</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id', $transaction->store_id) == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
            @error('store_id')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="date" class="form-label">日付 <span class="required">*</span></label>
            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required>
            @error('date')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="account_item_id" class="form-label">科目 <span class="required">*</span></label>
            <select name="account_item_id" id="account_item_id" class="form-control" required>
                <option value="">選択してください</option>
                <optgroup label="売上科目" id="revenue-items" style="display: none;">
                    @foreach ($revenueItems as $item)
                        <option value="{{ $item->id }}" data-tax-rate="{{ $item->default_tax_rate }}" {{ old('account_item_id', $transaction->account_item_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </optgroup>
                <optgroup label="経費科目" id="expense-items" style="display: none;">
                    @foreach ($expenseItems as $item)
                        <option value="{{ $item->id }}" data-tax-rate="{{ $item->default_tax_rate }}" {{ old('account_item_id', $transaction->account_item_id) == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </optgroup>
            </select>
            @error('account_item_id')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="amount" class="form-label">金額（税抜） <span class="required">*</span></label>
            <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $transaction->amount) }}" required min="0">
            @error('amount')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="tax_amount" class="form-label">税額</label>
            <input type="number" name="tax_amount" id="tax_amount" class="form-control" value="{{ old('tax_amount', $transaction->tax_amount) }}" min="0">
            @error('tax_amount')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <small class="form-help">科目のデフォルト税率から自動計算されます</small>
        </div>

        <div class="form-group">
            <label class="form-label">合計金額（税込）</label>
            <div class="total-display" id="total-amount">¥{{ number_format($transaction->total_amount) }}</div>
        </div>

        <div class="form-group">
            <label for="note" class="form-label">備考</label>
            <textarea name="note" id="note" class="form-control" rows="3" maxlength="1000">{{ old('note', $transaction->note) }}</textarea>
            @error('note')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('company.transactions.index') }}" class="btn btn-secondary">キャンセル</a>
            <button type="submit" class="btn btn-primary">更新する</button>
        </div>
    </form>
</div>

<style>
.form-container {
    background: white;
    border-radius: 8px;
    padding: 32px;
    max-width: 700px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.standard-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}

.required {
    color: #e53e3e;
}

.form-control {
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-help {
    margin-top: 4px;
    font-size: 13px;
    color: #6b7280;
}

.error-message {
    color: #e53e3e;
    font-size: 13px;
    margin-top: 4px;
}

.total-display {
    font-size: 24px;
    font-weight: 700;
    color: #059669;
    padding: 16px;
    background: #f0fdf4;
    border-radius: 4px;
    text-align: center;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 8px;
}
</style>

<script>
$(document).ready(function() {
    // 種別に応じて科目の選択肢を切り替え
    function updateAccountItems() {
        var type = $('#transaction_type').val();
        $('#revenue-items').hide();
        $('#expense-items').hide();
        
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
@endsection


