@extends('admin.master')

@section('title', 'Chi Tiết Món Ăn')

@section('content')
    @include('admin.layouts.messages')
    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row justify-content-center">
                <div class="col-md-10 col-12">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-3 text-white">Chi Tiết Món Ăn</h5>
                        </div>
                        <div class="card-body bg-light p-4">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="detail-box p-4 shadow-sm rounded bg-white">
                                        <h6 class="text-primary">Mã Món Ăn:</h6>
                                        <p class="h5">{{ $dish->id }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-box p-4 shadow-sm rounded bg-white">
                                        <h6 class="text-primary">Tên Món Ăn:</h6>
                                        <p class="h5">{{ $dish->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="detail-box p-4 shadow-sm rounded bg-white">
                                        <h6 class="text-primary">Loại Món Ăn:</h6>
                                        <p class="h5">{{ $dish->category->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-box p-4 shadow-sm rounded bg-white">
                                        <h6 class="text-primary">Giá:</h6>
                                        <p class="h5">{{ number_format($dish->price, 0, ',', '.') }} VND</p>
                                    </div>
                                </div>
                            </div>






                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="detail-box p-4 shadow-sm rounded bg-white">
                                        <h6 class="text-primary">Nguyên Liệu và Định Lượng:</h6>
                                        @if ($dish->recipes && $dish->recipes->isNotEmpty())
                                            <ul class="list-unstyled">
                                                @foreach ($dish->recipes as $recipe)
                                                    <li class="mb-2">
                                                        <strong>{{ $recipe->ingredient->name ?? 'N/A' }}:</strong>
                                                        {{ $recipe->quantity_need }} | {{ $recipe->ingredient->unit ?? '' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <button class="btn btn-primary"
                                                onclick="window.location='{{ route('admin.dishes.updateIngredients', $dish->id) }}'">
                                                <i class="bi bi-pencil-square me-1"></i> Cập Nhật
                                            </button>
                                        @else
                                            <p>Không có nguyên liệu cho món ăn này.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="detail-box p-4 shadow-sm rounded bg-white">
                                    <h6 class="text-primary">Trạng Thái:</h6>
                                    <span>
                                        @if ($dish->status == 'available')
                                            <span class="badge bg-success">Có sẵn</span>
                                        @elseif($dish->status == 'out_of_stock')
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @elseif($dish->status == 'reserved')
                                            <span class="badge bg-warning">Đã đặt trước</span>
                                        @elseif($dish->status == 'in_use')
                                            <span class="badge bg-info">Đang sử dụng</span>
                                        @elseif($dish->status == 'completed')
                                            <span class="badge bg-primary">Hoàn thành</span>
                                        @elseif($dish->status == 'cancelled')
                                            <span class="badge bg-secondary">Đã hủy</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <div class="detail-box p-4 shadow-sm rounded bg-white">
                                    <h6 class="text-primary">Hình Ảnh:</h6>
                                    @if ($dish->image)
                                        <img src="{{ asset('storage/' . $dish->image) }}" alt="Hình Ảnh"
                                            class="img-fluid shadow-lg rounded" width="250">
                                    @else
                                        <img src="https://via.placeholder.com/150" alt="No Image"
                                            class="img-fluid shadow-lg rounded" width="150">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="detail-box p-4 shadow-sm rounded bg-white">
                                    <h6 class="text-primary">Mô tả:</h6>
                                    <p class="h5">{{ $dish->description ?? 'Không có mô tả' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('admin.dishes.edit', $dish->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-2"></i> Chỉnh Sửa
                        </a>
                        <a href="{{ route('admin.dishes.index') }}" class="btn btn-sm btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row end -->


        {{-- <!-- Modal để cập nhật nguyên liệu -->
        <div class="modal fade" id="updateIngredientsModal" tabindex="-1" aria-labelledby="updateIngredientsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 rounded shadow-sm">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title text-primary" id="updateIngredientsModalLabel">Cập Nhật Nguyên Liệu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-secondary mb-3">Danh Sách Nguyên Liệu và Định Lượng:</h6>
                        @if ($dish->recipes && $dish->recipes->isNotEmpty())
                            <form id="updateIngredientsForm">
                                <ul class="list-group" id="ingredientList">
                                    @foreach ($dish->recipes as $recipe)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="w-100">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control"
                                                        value="{{ $recipe->ingredient->name ?? 'N/A' }}" disabled>
                                                    <input type="number" class="form-control"
                                                        value="{{ $recipe->quantity_need }}"
                                                        data-recipe-id="{{ $recipe->id }}" min="0">
                                                    <span
                                                        class="input-group-text">{{ $recipe->ingredient->unit ?? '' }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-outline-secondary btn-sm edit-recipe"
                                                    title="Chỉnh sửa">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-sm delete-recipe"
                                                    title="Xóa">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-success" id="addIngredientButton">Thêm Mới
                                        Nguyên Liệu</button>
                                </div>
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                                </div>
                            </form>
                        @else
                            <p class="text-muted">Không có nguyên liệu cho món ăn này.</p>
                        @endif
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div> --}}



        {{-- <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dishId = @json($dish->id); // Truyền dishId từ Blade vào JavaScript

                // Bật/tắt chế độ chỉnh sửa cho nguyên liệu
                document.querySelectorAll('.edit-recipe').forEach(button => {
                    button.addEventListener('click', function() {
                        const listItem = this.closest('.list-group-item');
                        if (!listItem) return;

                        const inputs = listItem.querySelectorAll('input[type="text"], input[type="number"]');
                        inputs.forEach(input => {
                            input.disabled = !input.disabled; // Bật/tắt trường nhập liệu
                        });
                        this.innerHTML = inputs[0].disabled ? '<i class="bi bi-pencil-fill"></i>' : '<i class="bi bi-check-circle-fill"></i>';
                    });
                });

                // Xử lý khi gửi form lưu thay đổi
                const updateForm = document.getElementById('updateIngredientsForm');
                if (updateForm) {
                    updateForm.addEventListener('submit', function(event) {
                        event.preventDefault();
                        const updatedRecipes = [];

                        // Lấy danh sách nguyên liệu đã cập nhật
                        document.querySelectorAll('.list-group-item').forEach(item => {
                            const quantityInput = item.querySelector('input[type="number"]');
                            if (!quantityInput) return;

                            const recipeId = quantityInput.dataset.recipeId;
                            const quantity = quantityInput.value.trim();

                            // Chỉ thêm nguyên liệu nếu có định lượng
                            if (quantity) {
                                updatedRecipes.push({
                                    id: recipeId,
                                    quantity: parseFloat(quantity) // Chuyển đổi thành số
                                });
                            }
                        });

                        // Nếu không có nguyên liệu nào được cập nhật, thông báo cho người dùng
                        if (updatedRecipes.length === 0) {
                            alert("Vui lòng nhập ít nhất một định lượng.");
                            return;
                        }

                        // Lấy CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        // Sử dụng AJAX để gửi dữ liệu cập nhật
                        fetch(`/dishes/${dishId}/update-ingredients`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken // Sử dụng CSRF token
                            },
                            body: JSON.stringify({ ingredients: updatedRecipes })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.message || 'Có lỗi xảy ra.');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            alert(data.message);
                            location.reload(); // Tải lại trang để cập nhật hiển thị
                        })
                        // .catch(error => {
                        //     console.error('Lỗi:', error);
                        //     alert('Đã xảy ra lỗi: ' + error.message);
                        // });
                    });
                }

                // Xử lý xóa nguyên liệu
                document.getElementById('ingredientList').addEventListener('click', function(event) {
                    if (event.target.classList.contains('delete-recipe')) {
                        const listItem = event.target.closest('.list-group-item');
                        if (!listItem) return;

                        const quantityInput = listItem.querySelector('input[type="number"]');
                        if (!quantityInput) return;

                        const recipeId = quantityInput.dataset.recipeId;

                        // Hiện popup xác nhận trước khi xóa
                        if (confirm('Bạn có chắc chắn muốn xóa nguyên liệu này?')) {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Lấy CSRF token

                            fetch(`/recipes/${recipeId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(data => {
                                        throw new Error(data.message || 'Có lỗi xảy ra.');
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                alert(data.message);
                                listItem.remove(); // Xóa nguyên liệu khỏi danh sách
                            })
                            // .catch(error => {
                            //     console.error('Lỗi:', error);
                            //     alert('Đã xảy ra lỗi: ' + error.message);
                            // });
                        }
                    }
                });

                // Xử lý thêm mới nguyên liệu
                const addIngredientButton = document.getElementById('addIngredientButton');
                if (addIngredientButton) {
                    addIngredientButton.addEventListener('click', function() {
                        const newIngredientItem = document.createElement('li');
                        newIngredientItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                        newIngredientItem.innerHTML = `
                            <div class="w-100">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" placeholder="Tên nguyên liệu" required>
                                    <input type="number" class="form-control" placeholder="Định lượng" min="0" required>
                                    <span class="input-group-text">đơn vị</span>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-danger btn-sm delete-recipe" title="Xóa">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        `;
                        document.getElementById('ingredientList').appendChild(newIngredientItem);

                        // Thêm sự kiện cho nút xóa mới
                        newIngredientItem.querySelector('.delete-recipe').addEventListener('click', function() {
                            newIngredientItem.remove();
                        });
                    });
                }
            });
        </script>

 --}}






        <style>
            .modal-content {
                border-radius: 10px;
            }

            .modal-header {
                background-color: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
                /* Thêm đường viền dưới cho header */
            }

            .modal-title {
                font-weight: bold;
                font-size: 1.25rem;
                /* Tăng kích thước chữ cho tiêu đề */
            }

            .list-group-item {
                background-color: #ffffff;
                /* Đặt màu nền cho mỗi item */
                border: 1px solid #dee2e6;
                /* Thêm đường viền cho item */
                border-radius: 5px;
                /* Bo tròn góc cho các nguyên liệu */
                transition: background-color 0.2s;
                /* Hiệu ứng chuyển đổi cho nền */
            }

            .list-group-item:hover {
                background-color: #e9ecef;
                /* Đổi màu nền khi hover */
            }

            .btn {
                border-radius: 5px;
                padding: 8px 12px;
                /* Thay đổi padding để nút không quá to */
            }

            .btn-danger {
                background-color: #dc3545;
                /* Màu nền cho nút xóa */
                border: none;
                /* Xóa viền của nút */
            }

            .btn-danger:hover {
                background-color: #c82333;
                /* Đổi màu khi hover */
            }

            .btn-primary {
                background-color: #007bff;
                /* Màu nền cho nút lưu */
                border: none;
                /* Xóa viền của nút */
            }

            .btn-primary:hover {
                background-color: #0056b3;
                /* Đổi màu khi hover */
            }

            .btn-success {
                background-color: #28a745;
                /* Màu nền cho nút thêm mới */
                border: none;
                /* Xóa viền của nút */
            }

            .btn-success:hover {
                background-color: #218838;
                /* Đổi màu khi hover */
            }

            .btn-secondary {
                background-color: #6c757d;
                /* Màu nền cho nút đóng */
                border: none;
                /* Xóa viền của nút */
            }

            .btn-secondary:hover {
                background-color: #5a6268;
                /* Đổi màu khi hover */
            }
        </style>



    @endsection
