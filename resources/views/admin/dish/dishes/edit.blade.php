@extends('admin.master')

@section('title', 'Chỉnh Sửa Món Ăn')

@section('content')
    @include('admin.layouts.messages')

    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title text-primary">Chỉnh Sửa Món Ăn</div>
                        </div>
                        <div class="card-body">

                            <form id="editDishForm" method="POST" action="{{ route('admin.dishes.update', $dish->id) }}"
                                enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dish-name" class="form-label">
                                            Tên Món Ăn
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="bi bi-patch-check text-white"></i>
                                            </span>
                                            <input type="text" id="dish-name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ $dish->name ?? '' }}" required placeholder="Nhập tên món ăn">
                                        </div>
                                        <small id="dish-name-error" class="text-danger d-block mt-1" style="display: none;">
                                            Tên món ăn đã tồn tại. Vui lòng chọn tên khác.
                                        </small>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập tên món ăn hợp lệ (từ 3 đến 50 ký tự).
                                        </div>
                                    </div>


                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const dishNameInput = document.getElementById('dish-name');
                                            const dishNameError = document.getElementById('dish-name-error');

                                            // Lấy danh sách tên món ăn từ server
                                            const existingDishNames = window.existingDishNames || [];

                                            dishNameInput.addEventListener('input', function() {
                                                const name = dishNameInput.value.trim();

                                                // Kiểm tra tên món ăn có bị trùng hay không
                                                if (name.length >= 3 && name.length <= 50 && existingDishNames.includes(name)) {
                                                    showError(true); // Tên bị trùng
                                                } else {
                                                    showError(false); // Tên hợp lệ
                                                }
                                            });

                                            // Hàm hiển thị lỗi
                                            function showError(isError) {
                                                if (isError) {
                                                    dishNameInput.classList.add('is-invalid'); // Đánh dấu input không hợp lệ
                                                    dishNameError.style.display = 'block'; // Hiển thị lỗi dưới ô input
                                                } else {
                                                    dishNameInput.classList.remove('is-invalid'); // Loại bỏ đánh dấu không hợp lệ
                                                    dishNameError.style.display = 'none'; // Ẩn thông báo lỗi
                                                }
                                            }
                                        });
                                    </script>


                                    <!-- Loại Món Ăn -->
                                    <div class="col-md-6 mb-3">
                                        <label for="dish-category" class="form-label">Loại Món Ăn</label>
                                        <select id="dish-category" name="category_id" class="form-select" required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $dish->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn loại món ăn.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Giá -->
                                    <div class="col-md-6 mb-3">
                                        <label for="dish-price" class="form-label">
                                            Giá
                                        </label>
                                        <div class="input-group">

                                            <span class="input-group-text bg-success text-white">₫</span>

                                            <input type="number" id="dish-price" name="price"
                                                class="form-control @error('price') is-invalid @enderror"
                                                value="{{ $dish->price }}" required min="1" max="5000000"
                                                step="0.01" placeholder="Nhập giá món ăn">
                                        </div>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập giá món ăn hợp lệ (1 - 5.000.000).
                                        </div>
                                    </div>


                                    <!-- Trạng Thái -->
                                    <div class="col-md-6 mb-3">
                                        <label for="dish-status" class="form-label">Trạng Thái</label>
                                        <select id="dish-status" name="status" class="form-select" required>
                                            <option value="available" {{ $dish->status == 'available' ? 'selected' : '' }}>
                                                Có sẵn</option>
                                            <option value="out_of_stock"
                                                {{ $dish->status == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                                            <option value="reserved" {{ $dish->status == 'reserved' ? 'selected' : '' }}>Đã
                                                đặt trước</option>
                                            <option value="inactive" {{ $dish->status == 'inactive' ? 'selected' : '' }}>
                                                Không hoạt động</option>
                                        </select>
                                        <div id="status-error" class="text-danger d-block mt-1" style="display: none;">
                                        </div>
                                        <div class="invalid-feedback">Vui lòng chọn trạng thái món ăn.</div>
                                    </div>


                                    <div class="row">
                                        <!-- Mô tả Món Ăn -->
                                        <div class="col-md-12 mb-3">
                                            <label for="dish-description" class="form-label">Mô tả Món Ăn</label>
                                            <textarea id="dish-description" name="description" class="form-control" placeholder="Nhập mô tả món ăn">{{ old('description', $dish->description) }}</textarea>
                                        </div>

                                        <!-- Hình Ảnh Món Ăn -->
                                        <div class="col-md-12 mb-3">
                                            <label for="dish-image" class="form-label">Hình Ảnh Món Ăn</label>
                                            <input type="file" id="dish-image" name="image" class="form-control"
                                                accept="image/*">
                                            @if ($dish->image)
                                                <img src="{{ asset('storage/' . $dish->image) }}" alt="Hình ảnh món ăn"
                                                    width="150" class="mt-3">
                                            @endif
                                            <div class="invalid-feedback">Vui lòng chọn ảnh món ăn.</div>
                                        </div>
                                    </div>

                                    <div class="mb-3 d-flex justify-content-end">
                                        <!-- Nút Cập Nhật -->
                                        <div class="text-end">
                                            <button id="update-dish-btn" type="submit" class="btn btn-primary">Cập Nhật Món
                                                Ăn</button>
                                            <a href="{{ route('admin.dishes.index') }}"
                                                class="btn btn-sm btn-secondary">Quay lại</a>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->

        </div>
        <!-- Content wrapper end -->

    </div>
    <!-- Content wrapper scroll end -->

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editDishForm');
            const inputs = form.querySelectorAll('input, select, textarea');

            // Kiểm tra tính hợp lệ khi có sự kiện 'input' hoặc 'change'
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    validateInput(input);
                });
                input.addEventListener('change', function() {
                    validateInput(input);
                });
            });

            // Kiểm tra tính hợp lệ của toàn bộ form khi submit
            form.addEventListener('submit', function(event) {
                inputs.forEach(input => {
                    validateInput(input);
                });

                if (!form.checkValidity()) {
                    event.preventDefault(); // Ngăn chặn việc gửi form nếu có lỗi
                    event.stopPropagation();
                }
            });

            // Hàm kiểm tra tính hợp lệ của từng trường
            function validateInput(input) {
                if (input.checkValidity()) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('dish-price');

            // Giới hạn giá trị khi người dùng nhập
            priceInput.addEventListener('input', function() {
                const value = parseFloat(priceInput.value);

                // Nếu giá trị nhỏ hơn 0
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

            // Đảm bảo kiểm tra khi form được submit
            priceInput.closest('form').addEventListener('submit', function(event) {
                const value = parseFloat(priceInput.value);

                // Kiểm tra lại giá trị khi submit
                if (isNaN(value) || value < 1 || value > 5000000) {
                    priceInput.classList.add('is-invalid');
                    event.preventDefault(); // Ngăn form submit nếu không hợp lệ
                } else {
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dishStatusSelect = document.getElementById('dish-status');
            const updateDishBtn = document.getElementById('update-dish-btn');
            const statusError = document.getElementById('status-error');

            // Trạng thái hiện tại của món ăn (từ server)
            const currentStatus = "{{ $dish->status }}";

            // Danh sách các trạng thái được phép chuyển đổi
            const statusRules = {
                available: ['out_of_stock', 'reserved'], // Có sẵn -> Chuyển sang Hết hàng, Đã đặt trước
                out_of_stock: ['available'], // Hết hàng -> Chuyển sang Có sẵn
                reserved: ['in_use'], // Đã đặt trước -> Chuyển sang Đang sử dụng
                in_use: ['completed', 'cancelled'], // Đang sử dụng -> Chuyển sang Hoàn thành, Đã hủy
                completed: [], // Hoàn thành -> Không thể chuyển ngược
                cancelled: [] // Đã hủy -> Không thể chuyển ngược
            };

            // Lắng nghe sự kiện thay đổi trạng thái
            dishStatusSelect.addEventListener('change', function() {
                const selectedStatus = dishStatusSelect.value;

                // Kiểm tra trạng thái có hợp lệ không
                if (statusRules[currentStatus] && !statusRules[currentStatus].includes(selectedStatus)) {
                    // Trạng thái không hợp lệ
                    showError(true);
                    updateDishBtn.disabled = true; // Vô hiệu hóa nút Cập Nhật
                } else {
                    // Trạng thái hợp lệ
                    showError(false);
                    updateDishBtn.disabled = false; // Kích hoạt nút Cập Nhật
                }
            });

            // Hàm hiển thị hoặc ẩn lỗi
            function showError(isError) {
                if (isError) {
                    statusError.style.display = 'block'; // Hiển thị lỗi
                } else {
                    statusError.style.display = 'none'; // Ẩn lỗi
                }
            }
        });
    </script>
@endsection
