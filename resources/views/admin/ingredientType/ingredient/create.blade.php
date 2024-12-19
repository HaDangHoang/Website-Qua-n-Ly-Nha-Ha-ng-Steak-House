@extends('admin.master')

@section('title', 'Thêm Nguyên Liệu Mới')

@section('content')
@include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title text-primary">Thêm Nguyên Liệu Mới</div>
                        </div>
                        <div class="card-body">
                            <form id="ingredientForm" action="{{ route('admin.ingredient.store') }}" method="POST"
                                novalidate>
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">
                                                Tên Nguyên Liệu <span class="text-danger required">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="bi bi-apple text-white"></i>
                                                </span>
                                                <input type="text" name="name" class="form-control"
                                                    id="ingredientName" placeholder="Nhập tên nguyên liệu" required
                                                    maxlength="50">
                                            </div>
                                            <small id="charCount" class="form-text text-muted"></small>
                                            <div class="invalid-feedback">Vui lòng nhập tên nguyên liệu (tối đa 50 ký tự).
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Giá <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success text-white">₫</span>
                                                <input type="number" step="0.01" name="price" class="form-control"
                                                    placeholder="Nhập giá" min="1" max="5000000" required>
                                            </div>
                                            <div class="invalid-feedback">Giá phải nằm trong khoảng từ 1 đến 5.000.000 VNĐ.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="unit" class="form-label">Đơn Vị <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-warning"><i
                                                        class="bi bi-modem text-white"></i></span>
                                                <input type="text" name="unit" class="form-control"
                                                    placeholder="Nhập đơn vị" required>
                                            </div>
                                            <div class="invalid-feedback">Vui lòng nhập đơn vị.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phân Loại <span
                                                    class="text-danger required">*</span></label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="category"
                                                        id="categoryFresh" value="Đồ tươi" required>
                                                    <label class="form-check-label" for="categoryFresh">
                                                        Đồ tươi
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="category"
                                                        id="categoryCanned" value="Đồ đóng hộp" required>
                                                    <label class="form-check-label" for="categoryCanned">
                                                        Đồ đóng hộp
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback">Vui lòng chọn phân loại.</div>
                                        </div>
                                    </div>

                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    <a href="{{ route('admin.ingredient.index') }}" class="btn btn-sm btn-secondary">Quay
                                        lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ingredientForm');
            const inputs = form.querySelectorAll('input, select');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                });
            });

            form.addEventListener('submit', function(event) {
                let isValid = true;
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    }
                });

                if (!isValid) {
                    event.preventDefault(); // Ngăn biểu mẫu gửi nếu không hợp lệ
                    event.stopPropagation();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.querySelector('input[name="price"]');

            priceInput.addEventListener('input', function() {
                let value = parseFloat(priceInput.value);

                // Kiểm tra nếu giá trị nhỏ hơn 1 hoặc lớn hơn 100.000.000
                if (value < 1 || value > 1000000 || isNaN(value)) {
                    priceInput.setCustomValidity('Giá phải nằm trong khoảng từ 1 đến 100.000.000.');
                    priceInput.classList.add('is-invalid');
                } else {
                    priceInput.setCustomValidity('');
                    priceInput.classList.remove('is-invalid');
                }

                // Đảm bảo rằng giá trị không âm và bắt đầu từ 1
                if (value < 1) {
                    priceInput.value = 1; // Đặt lại giá trị về 1 nếu nhỏ hơn 1
                }

                // Giới hạn không cho phép nhập giá trị lớn hơn 100.000.000
                if (value > 1000000) {
                    priceInput.value = 1000000; // Đặt lại giá trị về 100.000.000 nếu lớn hơn 100.000.000
                }
            });
        });
        const ingredientInput = document.getElementById('ingredientName');
        const charCount = document.getElementById('charCount');

        ingredientInput.addEventListener('input', function() {
            const maxLength = 50;
            const currentLength = this.value.length;

            if (currentLength >= 40 && currentLength < 49) {
                charCount.textContent = `Bạn đã nhập ${currentLength}/${maxLength} ký tự. Tối đa 50 ký tự.`;
                charCount.style.color = 'rgb(255, 193, 7)'; // Màu vàng
                charCount.style.fontWeight = 'bold'; // Làm đậm chữ
            } else if (currentLength >= 49) {
                charCount.textContent = `Bạn đã nhập ${currentLength}/${maxLength} ký tự. Tối đa 50 ký tự.`;
                charCount.style.color = 'rgb(220, 53, 69)'; // Màu đỏ
                charCount.style.fontWeight = 'bold';
            }
        });
    </script>
@endsection
