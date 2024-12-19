@extends('admin.master')

@section('title', 'Tạo giao dịch nhập kho')

@section('content')
@include('admin.layouts.messages')

    <div class="card mb-4">
        <div class="card-body">

            <form action="{{ route('transactions.store') }}" method="POST" id="transaction-form">
                @csrf

                <div class="row mb-3">
                    <h5 class="mb-4 text-primary">Tạo giao dịch nhập kho</h5>
                    <div class="col-md-6 mb-2">
                        <label for="total_amount">Tổng số tiền:</label>
                        <input type="text" name="total_amount" id="total_amount" class="form-control" readonly>
                        @error('total_amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="description">Mô tả:</label>
                        <input type="text" name="description" id="description" class="form-control" placeholder="Nhập mô tả..." value="{{ old('description') }}">
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-2">
                        <label for="supplier_id">Nhà cung cấp:</label>
                        <select class="form-select" name="supplier_id" >
                            <option value="">Chọn nhà cung cấp</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="staff_id">Nhân viên:</label>
                        <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                        <input type="hidden" name="staff_id" value="{{ $user->id }}">
                    </div>
                </div>

                <button type="button" class="btn btn-success mb-3 me-2" onclick="addIngredientForm()">
                    <i class="fas fa-plus"></i> Thêm nguyên liệu
                </button>

                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#importIngredientsModal">
                    <i class="fas fa-file-import"></i> Import nguyên liệu
                </button>

                <div id="ingredient-container">
                    <!-- Các form nguyên liệu sẽ được thêm vào đây -->
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Tạo phiếu nhập</button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Trở về danh sách</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importIngredientsModal" tabindex="-1" aria-labelledby="importIngredientsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importIngredientsModalLabel">Import nguyên liệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="excel_file" class="form-label">Chọn file Excel:</label>
                    <input type="file" class="form-control" id="excel_file" accept=".xlsx,.xls" required>
                </div>
                <div class="alert alert-info">
                    <small>* File Excel cần có các cột: Tên nguyên liệu, Số lượng, Đơn giá</small>
                    <br>
                    <small>* Định dạng file: .xlsx, .xls</small>
                    <br>
                    <small>* Kích thước tối đa: 5MB</small>
                </div>
                <button type="button" id="import-btn" class="btn btn-primary">Import</button>
            </div>
        </div>
    </div>
</div>




<script>
    let ingredientCount = 0;
    const ingredientData = @json($ingredients->map(function($ingredient) {
        return [
            'id' => $ingredient->id,
            'name' => $ingredient->name,
            'price' => $ingredient->price
        ];
    }));

    // Xử lý sự kiện submit form
    document.getElementById('transaction-form').addEventListener('submit', function() {
        let totalAmountField = document.getElementById('total_amount');
        // Xóa dấu chấm, khoảng trắng, và ký tự đ trước khi gửi
        totalAmountField.value = totalAmountField.value.replace(/[^\d]/g, '');
    });

    function validateQuantity(inputElement, index) {
        const maxQuantity = 100000000; // Giới hạn số lượng tối đa
        const errorElement = inputElement.parentElement.querySelector('.quantity-error');

        if (inputElement.value < 1 || isNaN(inputElement.value)) {
            errorElement.textContent = 'Số lượng phải lớn hơn 0.';
            errorElement.style.display = 'block';
            inputElement.classList.add('is-invalid');
        } else if (parseInt(inputElement.value) > maxQuantity) {
            errorElement.textContent = `Số lượng không được vượt quá ${maxQuantity.toLocaleString('vi-VN')}.`;
            errorElement.style.display = 'block';
            inputElement.classList.add('is-invalid');
            inputElement.value = maxQuantity;
        } else {
            errorElement.style.display = 'none';
            inputElement.classList.remove('is-invalid');
        }

        calculateTotalAmount();
        toggleCreateButton(); // Kiểm tra lại tình trạng nút "Tạo phiếu nhập"
    }

    function toggleCreateButton() {
        let isValid = true;
        document.querySelectorAll('.quantity-input').forEach(function(input) {
            if (input.classList.contains('is-invalid')) {
                isValid = false;
            }
        });

        // Kiểm tra tổng tiền
        let totalAmount = 0;
        document.querySelectorAll('.ingredient-item').forEach(function(item) {
            const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(item.querySelector('.unit-price').value) || 0;
            totalAmount += quantity * unitPrice;
        });

        // Nếu có lỗi hoặc tổng tiền vượt quá giới hạn, ẩn nút "Tạo phiếu nhập"
        if (totalAmount > 1000000000 || totalAmount <= 0 || !isValid) {
            document.querySelector('button[type="submit"]').disabled = true;
        } else {
            document.querySelector('button[type="submit"]').disabled = false;
        }

        updateTotalAmountDisplay(totalAmount); // Cập nhật tổng tiền hiển thị
    }

    // Kiểm tra tất cả các trường khi nhấn submit
    document.getElementById('transaction-form').addEventListener('submit', function(event) {
        let isValid = true;
        let totalAmount = 0;
        const maxQuantity = 100000000;
        const maxTotalAmount = 1000000000;

        document.querySelectorAll('.quantity-input').forEach(function(input) {
            const quantity = parseFloat(input.value) || 0;
            if (quantity < 1 || isNaN(quantity)) {
                isValid = false;
                input.classList.add('is-invalid');
                input.parentElement.querySelector('.quantity-error').textContent = 'Số lượng phải lớn hơn 0.';
                input.parentElement.querySelector('.quantity-error').style.display = 'block';
            } else if (quantity > maxQuantity) {
                isValid = false;
                input.classList.add('is-invalid');
                input.parentElement.querySelector('.quantity-error').textContent = `Số lượng không được vượt quá ${maxQuantity.toLocaleString('vi-VN')}.`;
                input.parentElement.querySelector('.quantity-error').style.display = 'block';
            } else {
                input.classList.remove('is-invalid');
                input.parentElement.querySelector('.quantity-error').style.display = 'none';
            }
        });

        document.querySelectorAll('.ingredient-item').forEach(function(item) {
            const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(item.querySelector('.unit-price').value) || 0;
            totalAmount += quantity * unitPrice;
        });

        if (totalAmount > maxTotalAmount) {
            isValid = false;
            alert(`Tổng tiền không được vượt quá ${maxTotalAmount.toLocaleString('vi-VN')} VND.`);
        }

        if (!isValid) {
            event.preventDefault();
            document.getElementById('transaction-form').scrollIntoView({ behavior: 'smooth' });
        }

        toggleCreateButton(); // Kiểm tra lại nút "Tạo phiếu nhập"
    });

    function addIngredientForm() {
        const container = document.getElementById('ingredient-container');

        const newIngredientItem = `
            <div class="ingredient-item mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select ingredient-select" name="ingredients[${ingredientCount}][ingredient_id]"
                                onchange="updateUnitPrice(this, ${ingredientCount})" required>
                            <option value="">Chọn nguyên liệu</option>
                            @foreach($ingredients as $ingredient)
                                <option value="{{ $ingredient->id }}" data-price="{{ $ingredient->price }}">
                                    {{ $ingredient->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="ingredients[${ingredientCount}][quantity]"
                               class="form-control quantity-input"
                               required min="1"
                               placeholder="Số lượng"
                               onchange="validateQuantity(this, ${ingredientCount})">
                        <div class="invalid-feedback quantity-error" style="display: none;">
                            Số lượng phải lớn hơn 0.
                        </div>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="ingredients[${ingredientCount}][unit_price]"
                               class="form-control unit-price"
                               required min="0"
                               placeholder="Đơn giá"
                               readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeIngredient(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', newIngredientItem);
        ingredientCount++;
        toggleCreateButton(); // Kiểm tra lại nút "Tạo phiếu nhập"
    }

    function updateUnitPrice(selectElement, index) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const price = selectedOption.dataset.price;
        const row = selectElement.closest('.ingredient-item');
        const unitPriceInput = row.querySelector('.unit-price');
        unitPriceInput.value = price;
        calculateRowTotal(index);
    }

    function calculateRowTotal(index) {
        const row = document.querySelector(`[name="ingredients[${index}][ingredient_id]"]`).closest('.ingredient-item');
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        calculateTotalAmount();
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    function calculateTotalAmount() {
        let total = 0;
        document.querySelectorAll('.ingredient-item').forEach(item => {
            const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
            const unitPrice = parseFloat(item.querySelector('.unit-price').value) || 0;
            total += quantity * unitPrice;
        });

        updateTotalAmountDisplay(total); // Cập nhật tổng tiền hiển thị
        toggleCreateButton(); // Kiểm tra lại nút "Tạo phiếu nhập"
    }

    function updateTotalAmountDisplay(amount) {
        const totalAmountField = document.getElementById('total_amount');
        totalAmountField.value = formatCurrency(amount);
    }

    function removeIngredient(button) {
        button.closest('.ingredient-item').remove();
        calculateTotalAmount();
        toggleCreateButton(); // Kiểm tra lại nút "Tạo phiếu nhập"
    }

    // Xử lý import Excel
    document.getElementById('import-btn').addEventListener('click', function() {
        const fileInput = document.getElementById('excel_file');
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("transactions.import") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.ingredients.forEach(ingredient => {
                    addIngredientFormWithData(ingredient);
                });
                $('#importIngredientsModal').modal('hide');
                calculateTotalAmount(); // Cập nhật tổng tiền sau khi nhập dữ liệu
            }
        })
        .catch(error => console.error('Error:', error));
    });

    function addIngredientFormWithData(ingredient) {
        const container = document.getElementById('ingredient-container');

        const newIngredientItem = `
            <div class="ingredient-item mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-select ingredient-select" name="ingredients[${ingredientCount}][ingredient_id]"
                                onchange="updateUnitPrice(this, ${ingredientCount})" required>
                            <option value="">Chọn nguyên liệu</option>
                            @foreach($ingredients as $ing)
                                <option value="{{ $ing->id }}"
                                        ${ingredient.ingredient_id == {{ $ing->id }} ? 'selected' : ''}>
                                    {{ $ing->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="ingredients[${ingredientCount}][quantity]"
                               class="form-control quantity-input"
                               required min="1"
                               value="${ingredient.quantity}"
                               onchange="validateQuantity(this, ${ingredientCount})">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="ingredients[${ingredientCount}][unit_price]"
                               class="form-control unit-price"
                               required min="0"
                               value="${ingredient.unit_price}"
                               readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeIngredient(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', newIngredientItem);
        ingredientCount++;
        toggleCreateButton(); // Kiểm tra lại nút "Tạo phiếu nhập"
    }
    </script>

@endsection
