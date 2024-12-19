@extends('admin.master')

@section('title', 'Chỉnh sửa Phiếu Nhập Kho')

@section('content')
    @include('admin.layouts.messages')
    <div class="container">

        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card shadow-sm mb-4">
                <div class="card-header  text-white">
                    <h5 class="mb-0 text-primary">Chỉnh sửa phiếu nhập kho</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_amount_display" class="form-label">Tổng số tiền</label>
                            <input type="hidden" id="total_amount" name="total_amount"
                                value="{{ old('total_amount', $transaction->total_amount) }}" required>
                            <input type="text" id="total_amount_display" class="form-control"
                                value="{{ number_format($transaction->total_amount, 0, ',', '.') }} đ" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="supplier_id" class="form-label">Nhà cung cấp</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">Chọn nhà cung cấp</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $supplier->id == $transaction->supplier_id ? 'selected' : '' }}>
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Ghi chú</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $transaction->description) }}</textarea>
                    </div>

                    <h5 class="mt-4 mb-3 text-primary">Nguyên liệu</h5>
                    <div id="ingredients-container">
                        @foreach ($transaction->inventoryItems as $index => $item)
                            <div class="ingredient-item mb-3 p-3 border rounded bg-light shadow-sm">
                                <div class="row">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label for="ingredient_id" class="form-label">Nguyên liệu:</label>
                                        <select name="ingredients[{{ $index }}][ingredient_id]" class="form-select"
                                            required
                                            data-price="{{ $ingredients->firstWhere('id', $item->ingredient_id)->price ?? 0 }}">
                                            <option value="">Chọn nguyên liệu</option>
                                            @foreach ($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}"
                                                    {{ $ingredient->id == $item->ingredient_id ? 'selected' : '' }}>
                                                    {{ $ingredient->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="quantity" class="form-label">Số lượng:</label>
                                        <input type="number" name="ingredients[{{ $index }}][quantity]"
                                            class="form-control quantity-input" value="{{ $item->quantity }}" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 float-end">
                        <i class="fas fa-save me-1"></i> Cập nhật
                    </button>
                </div>
            </div>
        </form>

        <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left me-1"></i> Trở về
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const totalAmountInput = document.getElementById('total_amount');
            const totalAmountDisplay = document.getElementById('total_amount_display');

            function updateTotalAmount() {
                let totalAmount = 0;
                quantityInputs.forEach(input => {
                    const price = parseFloat(input.closest('.ingredient-item').querySelector('select')
                        .dataset.price || 0);
                    const quantity = parseFloat(input.value || 0);
                    if (!isNaN(price) && !isNaN(quantity)) {
                        totalAmount += price * quantity;
                    }
                });
                totalAmountInput.value = totalAmount;
                totalAmountDisplay.value = totalAmount.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
            }

            quantityInputs.forEach(input => {
                input.addEventListener('input', updateTotalAmount);
            });

            updateTotalAmount();
        });
    </script>
@endsection
