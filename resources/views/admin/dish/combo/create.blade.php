@extends('admin.master')

@section('title', 'Thêm Combo Mới')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <style>
        th {
            font-weight: 400 !important;
            color: #555
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .table thead th {
            white-space: nowrap;
        }

        .table td input {
            text-align: center;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            -webkit-appearance: none;
            appearance: none;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection
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
                            <div class="card-title text-primary">Thêm Combo Mới</div>


                        </div>
                        <div class="card-body">

                            <form id="comboForm" action="{{ route('admin.combo.store') }}" method="POST"
                                enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mb-3">
                                            <label class="form-label">Tên Combo <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="bi bi-egg-fried text-white"></i>
                                                    <!-- Biểu tượng combo (hoặc món ăn) -->
                                                </span>
                                                <input type="text" id="combo-name" name="name" class="form-control"
                                                    placeholder="Tên Combo" required>
                                            </div>
                                            <!-- Các thông báo lỗi sẽ được hiển thị qua JS -->
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Giá Combo <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success text-white">₫</span>
                                                <input type="number" name="price" id="price" class="form-control"
                                                    placeholder="Giá Combo" required min="1" max="100000000"
                                                    step="0.01">
                                            </div>
                                            <div class="invalid-feedback">Vui lòng nhập giá combo hợp lệ (từ 1 đến
                                                100.000.000 VND).</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Ảnh Combo <span
                                                    class="text-danger required">*</span></label>
                                            <input type="file" name="image" class="form-control" accept="image/*"
                                                required>
                                            <div class="invalid-feedback">Vui lòng chọn ảnh combo.</div>

                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Số Lượng Món Ăn <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success">
                                                    <i class="bi bi-stack text-white"></i>
                                                    <!-- Biểu tượng số lượng món ăn -->
                                                </span>
                                                <input type="number" name="quantity_dishes" id="quantity_dishes"
                                                    class="form-control" placeholder="Số lượng món ăn trong combo" required
                                                    readonly>
                                            </div>
                                            <div class="invalid-feedback">Vui lòng nhập số lượng món ăn.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="editor" class="form-label">Mô tả món ăn</label>
                                            <textarea name="description" id="editor" class="form-control"></textarea>

                                        </div>

                                    </div>
                                    <div class=" col-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="dishes">Chọn Món Ăn: <span
                                                    class="text-danger required">*</span></label>
                                            <select name="dishes[]" class="form-select" id="dishes" multiple="multiple"
                                                style="width: 100%;">
                                                @foreach ($dishes as $dish)
                                                    <option value="{{ $dish->id }}">{{ $dish->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="table-responsive">
                                            <label class="form-label">Danh sách món ăn</label>
                                            <table class="table " id="selected-dishes-table">
                                                <thead class="">
                                                    <tr>
                                                        <th class="w-50">Món ăn</th>
                                                        <th class="w-25">Số lượng</th>
                                                        {{-- <th class="w-25">Hành Động</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center align-middle">
                                                    <!-- Các món ăn được thêm vào đây -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    <a href="{{ route('admin.combo.index') }}" class="btn btn-sm btn-secondary">Quay lại</a>
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
    <!-- Load jQuery first -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <!-- Load Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#dishes').select2({
                placeholder: 'Chọn món ăn',
                tags: false,
            });

            const selectedDishesTable = $('#selected-dishes-table tbody');

            $('#dishes').on('change', function() {
                const selectedDishes = $(this).val();

                if (selectedDishes) {
                    selectedDishes.forEach(function(dishId) {
                        const dishName = $('#dishes option[value="' + dishId + '"]').text();

                        if (!selectedDishesTable.find('tr[data-dish-id="' + dishId + '"]').length) {
                            selectedDishesTable.append(`
                                <tr data-dish-id="${dishId}">
                                    <td class="text-start">${dishName}</td>
                                    <td>
                                        <input type="number" name="dish_quantities[${dishId}]" 
                                               class="form-control text-center mx-auto" 
                                               min="1" value="1" style="max-width: 100px;">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-alt btn-sm remove-dish">
                                             <i class="fa fa-fw fa-times text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            `);
                        }
                    });
                }
            });
            $('#dishes').on('select2:unselect', function(e) {
                const deselectedDishId = e.params.data.id;

                selectedDishesTable.find(`tr[data-dish-id="${deselectedDishId}"]`).remove();
            });

            $(document).on('click', '.remove-dish', function() {
                const row = $(this).closest('tr');
                const dishId = row.data('dish-id');
                row.remove();

                const selectedDishes = $('#dishes').val();
                const updatedDishes = selectedDishes.filter(id => id != dishId);
                $('#dishes').val(updatedDishes).trigger('change');
            });
        });
    </script>

    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        // Initialize CKEditor for the description field
        CKEDITOR.replace('editor');

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('comboForm');
            const inputs = form.querySelectorAll('input, textarea');

            // Check validity of each input on input event
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

            // Bỏ kiểm tra CKEditor nội dung (mô tả không bắt buộc)
            // Validate the entire form on submit
            form.addEventListener('submit', function(event) {
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                    }
                });

                if (!form.checkValidity()) {
                    event.preventDefault(); // Ngăn việc submit nếu có trường không hợp lệ
                    event.stopPropagation();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');

            // Kiểm tra giá trị nhập vào (chặn số âm và giá trị vượt quá 100.000.000)
            priceInput.addEventListener('input', function() {
                // Chuyển giá trị sang số nguyên
                const value = parseInt(priceInput.value, 10);

                // Nếu giá trị không hợp lệ hoặc nhỏ hơn 1
                if (value < 1) {
                    priceInput.value = ''; // Reset giá trị nếu nhỏ hơn 1
                    priceInput.classList.add('is-invalid');
                }
                // Nếu giá trị vượt quá 10.000.000
                else if (value > 10000000) {
                    priceInput.value = 10000000; // Giới hạn giá trị tối đa
                    priceInput.classList.add('is-invalid');
                }
                // Nếu giá trị hợp lệ
                else {
                    priceInput.classList.remove('is-invalid');
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const comboNameInput = document.getElementById('combo-name');
            const maxLengthWarning = 'Tên combo dài quá 50 ký tự. Vui lòng rút ngắn.';
            const nameExistsError = 'Tên combo đã tồn tại. Vui lòng chọn tên khác.';
            const comboNameParent = comboNameInput.closest('.mb-3'); // Lấy phần tử cha để thêm lỗi

            // Lấy danh sách tên combo từ server
            const existingComboNames = window.existingComboNames || []; // Danh sách tên combo có sẵn

            comboNameInput.addEventListener('input', function() {
                const name = comboNameInput.value.trim();

                // Xóa các thông báo lỗi cũ
                removeErrorMessages();

                // Kiểm tra độ dài tên combo
                if (name.length > 50) {
                    showError(maxLengthWarning);
                    comboNameInput.value = name.substring(0, 50); // Cắt chuỗi về 50 ký tự
                    return; // Dừng lại ở đây nếu dài quá 50 ký tự
                }

                // Kiểm tra tên combo có bị trùng không
                if (existingComboNames.includes(name)) {
                    showError(nameExistsError);
                }
            });

            // Hàm hiển thị lỗi
            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'd-block', 'mt-1');
                errorDiv.textContent = message;
                comboNameParent.appendChild(errorDiv);
            }

            // Hàm xóa các thông báo lỗi cũ
            function removeErrorMessages() {
                const existingErrorMessages = comboNameParent.querySelectorAll('.text-danger');
                existingErrorMessages.forEach(error => error.remove());
            }
        });
    </script>

@endsection
