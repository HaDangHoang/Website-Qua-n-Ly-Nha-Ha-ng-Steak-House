@extends('admin.master')

@section('title', 'Thêm Mới Món Ăn')

@section('content')

    @include('admin.layouts.messages')

    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title text-primary">Thêm Mới Món Ăn</div>

                        </div>
                        <div class="card-body">
                            <form id="addDishForm" method="POST" action="{{ route('admin.dishes.store') }}"
                                enctype="multipart/form-data" novalidate>
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dish-name" class="form-label">
                                            Tên Món Ăn <span class="text-danger required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="bi bi-patch-check text-white"></i>
                                                <!-- Icon món ăn từ Bootstrap Icons -->
                                            </span>
                                            <input type="text" id="dish-name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Nhập tên món ăn" required maxlength="50"
                                                value="{{ old('name') }}">
                                        </div>

                                        <!-- Thông báo lỗi -->
                                        <div id="name-exists-warning" class="invalid-feedback" style="display: none;">
                                            Tên món ăn đã tồn tại.
                                        </div>
                                        <div id="max-length-warning" class="invalid-feedback" style="display: none;">
                                            Tên món ăn không được vượt quá 50 ký tự.
                                        </div>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập tên món ăn.
                                        </div>
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="dish-category" class="form-label">Loại Món Ăn <span
                                                class="text-danger required">*</span></label>
                                        <select id="dish-category" name="category_id" class="form-select" required>
                                            <option value="0" selected disabled>--- Chọn Loại ---</option>
                                            <!-- Mặc định là lựa chọn chưa chọn -->
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn loại món ăn.</div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dish-price" class="form-label">
                                            Giá <span class="text-danger required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">₫</span>
                                            </span>
                                            <input type="number" id="dish-price" name="price"
                                                class="form-control @error('price') is-invalid @enderror"
                                                placeholder="Nhập giá món ăn" required min="1" max="5000000"
                                                step="0.01" value="{{ old('price') }}">
                                        </div>

                                        <!-- Thông báo lỗi -->
                                        <div class="invalid-feedback">
                                            Vui lòng nhập giá món ăn trong khoảng từ 1 đến 5.000.000.
                                        </div>
                                        @error('price')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="dish-description" class="form-label">Mô tả Món Ăn</label>
                                        <textarea id="dish-description" name="description" class="form-control" placeholder="Nhập mô tả món ăn"></textarea>
                                        <div class="invalid-feedback">Vui lòng nhập mô tả món ăn.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dish-status" class="form-label">Trạng Thái</label>
                                        <select id="dish-status" name="status" class="form-select" required>
                                            <option value="available">Có sẵn</option>
                                            <option value="out_of_stock">Hết hàng</option>
                                            <option value="reserved">Đã đặt trước</option>
                                            <option value="in_use">Đang sử dụng</option>
                                            <option value="completed">Hoàn thành</option>
                                            <option value="cancelled">Đã hủy</option>
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn trạng thái món ăn.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="dish-image" class="form-label">Hình Ảnh <span
                                                class="text-danger required">*</span></label>
                                        <input type="file" id="dish-image" name="image" class="form-control"
                                            accept="image/*" required>
                                        <div class="invalid-feedback">Vui lòng chọn ảnh món ăn.</div>
                                    </div>
                                </div>

                                <div id="ingredientsSection">
                                    <div id="ingredientsContainer">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <div class="card-title text-primary">Thêm Mới Công Thức Món Ăn</div>
                                        </div>
                                        <!-- Chọn Nguyên Liệu (multiple) -->
                                        <div class="mb-4">
                                            <label for="ingredients" class="form-label fw-bold">Nguyên Liệu <span
                                                    class="text-danger required">*</span></label>
                                            <select id="ingredients" name="ingredient_id[]" class="form-select" multiple
                                                required>
                                                @foreach ($ingredients as $ingredient)
                                                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Định lượng cho từng Nguyên Liệu -->
                                        <div id="ingredient-quantities" class="mb-4"></div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-sm btn-primary">Thêm Mới</button>
                                    <a href="{{ route('admin.dishes.index') }}" class="btn btn-sm btn-secondary">Quay
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
            const form = document.getElementById('addDishForm');
            const dishTypeSelect = document.getElementById('dish-category');
            const ingredientsSection = document.getElementById('ingredientsSection');
            const ingredientSelect = document.getElementById('ingredients');
            const ingredientQuantitiesDiv = document.getElementById('ingredient-quantities');

            // Ẩn phần nguyên liệu ban đầu
            ingredientsSection.style.display = 'none';

            dishTypeSelect.addEventListener('change', function() {
                const selectedValue = this.value;
                // Hiển thị phần nguyên liệu nếu là loại món ăn có id là 1, 2, hoặc 3
                if (selectedValue === "1" || selectedValue === "2" || selectedValue === "3") {
                    ingredientsSection.style.display = 'block';

                    // Thêm thuộc tính required cho các trường bên trong ingredientsSection
                    ingredientSelect.setAttribute('required', 'true');
                    Array.from(ingredientQuantitiesDiv.querySelectorAll('input')).forEach(input => {
                        input.setAttribute('required', 'true');
                    });
                } else {
                    // Ẩn phần nguyên liệu nếu không phải loại đã chỉ định
                    ingredientsSection.style.display = 'none';

                    // Xóa thuộc tính required cho các trường bên trong ingredientsSection
                    ingredientSelect.removeAttribute('required');
                    Array.from(ingredientQuantitiesDiv.querySelectorAll('input')).forEach(input => {
                        input.removeAttribute('required');
                    });
                }
            });

            ingredientSelect.addEventListener('change', () => {
                ingredientQuantitiesDiv.innerHTML = '';

                Array.from(ingredientSelect.selectedOptions).forEach(option => {
                    const ingredientId = option.value;
                    const ingredientName = option.text;

                    const quantityDiv = document.createElement('div');
                    quantityDiv.classList.add('mb-3');

                    const label = document.createElement('label');
                    label.classList.add('form-label', 'fw-bold');
                    label.textContent = `Định lượng cho ${ingredientName}`;

                    const input = document.createElement('input');
                    input.type = 'number';
                    input.name = `ingredient_quantity[${ingredientId}]`;
                    input.classList.add('form-control');
                    input.placeholder = `Nhập định lượng cho ${ingredientName}`;
                    input.required = true;
                    input.min = 0.01;
                    input.step = 0.01;

                    input.addEventListener('input', () => {
                        if (input.checkValidity()) {
                            input.classList.remove('is-invalid');
                            input.classList.add('is-valid');
                        } else {
                            input.classList.remove('is-valid');
                            input.classList.add('is-invalid');
                        }
                    });

                    quantityDiv.append(label, input);
                    ingredientQuantitiesDiv.appendChild(quantityDiv);
                });
            });

            form.addEventListener('submit', function(event) {
                let isValid = true;

                // Kiểm tra tất cả các trường bắt buộc
                Array.from(form.querySelectorAll('input, select, textarea')).forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                });

                if (!isValid) {
                    event.preventDefault(); // Ngăn biểu mẫu gửi nếu không hợp lệ
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('dish-price');

            // Giới hạn giá trị khi người dùng nhập
            priceInput.addEventListener('input', function() {
                const value = parseFloat(priceInput.value);

                // Nếu giá trị nhỏ hơn 0 hoặc không hợp lệ
                if (value < 1) {
                    priceInput.value = ''; // Reset giá trị
                    priceInput.classList.add('is-invalid');
                }
                // Nếu giá trị vượt quá 5.000.000
                else if (value > 5000000) {
                    priceInput.value = 5000000; // Giới hạn giá trị tối đa
                    priceInput.classList.add('is-invalid');
                }
                // Nếu giá trị hợp lệ
                else {
                    priceInput.classList.remove('is-invalid');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const dishNameInput = document.getElementById('dish-name');
            const nameExistsWarning = document.getElementById('name-exists-warning');
            const maxLengthWarning = document.getElementById('max-length-warning');

            // Hàm kiểm tra tên món ăn đã tồn tại (có thể thay bằng AJAX để kiểm tra trong cơ sở dữ liệu)
            function checkDishNameExists(name) {
                const existingDishNames = ['Món ăn 1',
                    'Món ăn 2'
                ]; // Danh sách tên món ăn đã tồn tại trong CSDL (dữ liệu mẫu)
                return existingDishNames.includes(name);
            }

            dishNameInput.addEventListener('input', function() {
                let value = dishNameInput.value;

                // Kiểm tra nếu tên món ăn dài quá 50 ký tự
                if (value.length > 50) {
                    dishNameInput.value = value.substring(0, 50); // Cắt chuỗi về 50 ký tự
                    maxLengthWarning.style.display = 'block'; // Hiển thị cảnh báo
                } else {
                    maxLengthWarning.style.display = 'none'; // Ẩn cảnh báo khi dưới 50 ký tự
                }

                // Kiểm tra nếu tên món ăn đã tồn tại trong cơ sở dữ liệu
                if (checkDishNameExists(value)) {
                    nameExistsWarning.style.display =
                        'block'; // Hiển thị cảnh báo nếu tên món ăn đã tồn tại
                } else {
                    nameExistsWarning.style.display = 'none'; // Ẩn cảnh báo nếu tên món ăn không trùng
                }
            });
        });
    </script>
@endsection
