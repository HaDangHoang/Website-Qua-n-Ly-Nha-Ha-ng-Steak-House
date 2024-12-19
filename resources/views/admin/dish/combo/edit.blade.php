@extends('admin.master')

@section('title', 'Chỉnh Sửa Combo')

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

    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title text-primary">Chỉnh Sửa Combo</div>

                        </div>
                        <div class="card-body">
                            <form id="comboForm" action="{{ route('admin.combo.update', $combo->id) }}" method="POST"
                                enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PUT')

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
                                                    value="{{ $combo->name }}" placeholder="Tên Combo" required>
                                            </div>
                                            <div class="invalid-feedback">Vui lòng nhập tên combo hợp lệ.</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Giá Combo <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success text-white">₫</span>
                                                <input type="number" name="price" id="price" class="form-control"
                                                    value="{{ number_format($combo->price, 0, ',', '.') }}"
                                                    placeholder="Giá Combo" required min="1" max="100000000"
                                                    aria-label="Giá Combo" aria-describedby="basic-addon1">
                                            </div>
                                            <div class="invalid-feedback">Vui lòng nhập giá combo hợp lệ (từ 1 đến
                                                100.000.000 VND).</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Ảnh Combo</label>
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                            <img src="{{ asset('storage/' . $combo->image) }}" alt="{{ $combo->name }}"
                                                width="100" class="mt-2">
                                        </div>

                                        {{-- <div class="mb-3">
                                            <label class="form-label">Số Lượng Món Ăn</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success">
                                                    <i class="bi bi-stack text-white"></i>
                                                    <!-- Biểu tượng số lượng món ăn -->
                                                </span>
                                                <input type="number" name="quantity_dishes"
                                                    class="form-control text-primary" value="{{ $combo->quantity_dishes }}"
                                                    placeholder="Số lượng món ăn trong combo" required readonly>
                                            </div>
                                            <div class="invalid-feedback">Vui lòng nhập số lượng món ăn.</div>
                                        </div> --}}

                                        <div class="mb-3">
                                            <label for="editor" class="form-label">Mô tả món ăn</label>
                                            <textarea name="description" id="editor" class="form-control">{{ $combo->description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="dishes">Chọn Món Ăn: <span
                                                    class="text-danger required">*</span></label>
                                            <select name="dishes[]" class="form-select" id="dishes" multiple="multiple"
                                                style="width: 100%;">
                                                @foreach ($dishes as $dish)
                                                    <option value="{{ $dish->id }}"
                                                        {{ in_array($dish->id, $combo->dishes->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                        {{ $dish->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="table-responsive">
                                            <label class="form-label">Danh sách món ăn</label>
                                            <table class="table" id="selected-dishes-table">
                                                <thead class="text-muted">
                                                    <tr>
                                                        <th class="w-50 text-muted">Món ăn</th>
                                                        <th class="w-25 text-muted">Số lượng</th>
                                                        {{-- <th class="w-25 text-muted">Hành Động</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody class="text-center align-middle">
                                                    @foreach ($combo->dishes as $dish)
                                                        <tr data-dish-id="{{ $dish->id }}">
                                                            <td class="text-start">{{ $dish->name }}</td>
                                                            <td>
                                                                <input type="number"
                                                                    name="dish_quantities[{{ $dish->id }}]"
                                                                    class="form-control text-center mx-auto" min="1"
                                                                    value="{{ $dish->pivot->quantity ?? 1 }}"
                                                                    style="max-width: 100px;">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-alt btn-sm remove-dish">
                                                                    <i class="fa fa-fw fa-times text-danger"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 d-flex justify-content-end">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Cập Nhật Combo</button>
                                        <a href="{{ route('admin.combo.index') }}" class="btn btn-secondary">Quay
                                            lại</a>
                                    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <script>
        $(document).ready(function() {
            // $('#dishes').select2({
            //     placeholder: 'Chọn món ăn',
            //     tags: false
            // });

            $('#dishes').on('change', function() {
                const selectedDishesCount = $(this).val() ? $(this).val().length : 0;
                $('input[name="quantity_dishes"]').val(selectedDishesCount);
            });

            // Initialize CKEditor
            CKEDITOR.replace('editor');

            const priceInput = document.getElementById('price');
            priceInput.addEventListener('input', function() {
                const value = parseInt(priceInput.value, 10);
                if (value < 1 || value > 10000000) {
                    priceInput.classList.add('is-invalid');
                } else {
                    priceInput.classList.remove('is-invalid');
                }
            });

            const comboNameInput = document.getElementById('combo-name');
            const comboNameParent = comboNameInput.closest('.mb-3');
            const existingComboNames = window.existingComboNames || [];

            comboNameInput.addEventListener('input', function() {
                const name = comboNameInput.value.trim();
                removeErrorMessages();
                if (name.length > 50) {
                    showError('Tên combo dài quá 50 ký tự. Vui lòng rút ngắn.');
                    comboNameInput.value = name.substring(0, 50);
                    return;
                }

                if (existingComboNames.includes(name)) {
                    showError('Tên combo đã tồn tại. Vui lòng chọn tên khác.');
                }
            });

            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger', 'd-block', 'mt-1');
                errorDiv.textContent = message;
                comboNameParent.appendChild(errorDiv);
            }

            function removeErrorMessages() {
                const existingErrorMessages = comboNameParent.querySelectorAll('.text-danger');
                existingErrorMessages.forEach(error => error.remove());
            }
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

    <script>
        $(document).ready(function() {
            $('#dishes').select2({
                placeholder: 'Chọn món ăn',
                tags: false,
            });

            const selectedDishesTable = $('#selected-dishes-table tbody');

            $('#dishes').on('select2:select', function(e) {
                const selectedDishId = e.params.data.id;
                const dishName = e.params.data.text;

                if (!selectedDishesTable.find(`tr[data-dish-id="${selectedDishId}"]`).length) {
                    selectedDishesTable.append(`
        <tr data-dish-id="${selectedDishId}">
          <td class="text-start">${dishName}</td>
          <td>
            <input type="number" name="dish_quantities[${selectedDishId}]" class="form-control text-center mx-auto" min="1" value="1" style="max-width: 100px;">
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
@endsection
