@extends('admin.master')

@section('title', 'Thêm Mới Bàn')

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
                        <div class="card-header">
                            <div class="card-title text-primary">Thêm Mới Bàn</div>
                        </div>
                        <div class="card-body">
                            <form id="tableForm" action="{{ route('admin.table.store') }}" method="POST" novalidate>
                                @csrf

                                <!-- Khu Vực và Số Bàn -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="area" class="form-label">Khu Vực <span
                                                class="text-danger required">*</span></label>
                                        <select class="form-control" id="area" name="area" required>
                                            <option value="Tầng 1" {{ old('area') == 'Tầng 1' ? 'selected' : '' }}>Tầng 1
                                            </option>
                                            <option value="Tầng 2" {{ old('area') == 'Tầng 2' ? 'selected' : '' }}>Tầng 2
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn khu vực.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="table_number" class="form-label">Số Bàn <span
                                                class="text-danger required">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="bi bi-door-open text-white"></i>
                                            </span>
                                            <input type="number"
                                                class="form-control @error('table_number') is-invalid @enderror"
                                                id="table_number" name="table_number" value="{{ old('table_number') }}"
                                                required min="1" max="100" placeholder="Nhập số bàn">
                                        </div>
                                        <div class="invalid-feedback">
                                            @error('table_number')
                                                {{ $message }}
                                            @else
                                                Vui lòng nhập số bàn từ 1 đến 100.
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Trạng Thái với radio button -->
                                <div class="mb-3">
                                    <label class="form-label">Trạng Thái <span class="text-danger required">*</span></label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="statusAvailable" value="Available"
                                                {{ old('status') == 'Available' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="statusAvailable">Có Sẵn</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="statusReserved" value="Reserved"
                                                {{ old('status') == 'Reserved' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="statusReserved">Đã Đặt</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status"
                                                id="statusOccupied" value="Occupied"
                                                {{ old('status') == 'Occupied' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="statusOccupied">Đang Sử Dụng</label>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">Vui lòng chọn trạng thái bàn.</div>
                                </div>

                                <!-- Các nút ở bên phải -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Thêm Bàn</button>
                                    <a href="{{ route('admin.table.index') }}" class="btn btn-sm btn-secondary">Quay
                                        lại</a>
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
            const form = document.getElementById('tableForm');
            const inputs = form.querySelectorAll('input, select');
            const tableNumberInput = document.getElementById('table_number');
            const errorMessage = "{{ $errors->first('table_number') }}";

            // Hiển thị lỗi nếu có
            if (errorMessage) {
                tableNumberInput.classList.add('is-invalid');
            }

            // Thêm sự kiện kiểm tra cho từng input
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Kiểm tra riêng cho trường "Số Bàn"
                    if (input.id === 'table_number') {
                        const value = parseInt(input.value);
                        if (value < 1 || value > 100 || isNaN(value)) {
                            input.setCustomValidity("Số bàn phải nằm trong khoảng từ 1 đến 100.");
                        } else {
                            input.setCustomValidity(""); // Xóa lỗi nếu hợp lệ
                        }
                    }

                    // Kiểm tra tính hợp lệ chung
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                });
            });

            // Kiểm tra khi submit form
            form.addEventListener('submit', function(event) {
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                    }
                });

                if (!form.checkValidity()) {
                    event.preventDefault(); // Ngăn biểu mẫu gửi nếu không hợp lệ
                    event.stopPropagation();
                }
            });
        });
    </script>

@endsection
