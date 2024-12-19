@extends('admin.master')

@section('title', 'Chỉnh Sửa Bàn')

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
                            <div class="card-title text-primary">Chỉnh Sửa Bàn</div>
                        </div>

                        <div class="card-body">
                            <form id="editTableForm" action="{{ route('admin.table.update', $table->id) }}" method="POST"
                                novalidate>
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Khu Vực -->
                                    <div class="col-md-6 mb-3">
                                        <label for="area" class="form-label">Khu Vực</label>
                                        <select name="area" id="area" class="form-select" required>
                                            <option value="Tầng 1" {{ $table->area == 'Tầng 1' ? 'selected' : '' }}>Tầng 1
                                            </option>
                                            <option value="Tầng 2" {{ $table->area == 'Tầng 2' ? 'selected' : '' }}>Tầng 2
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn khu vực.</div>

                                        @error('area')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Số Bàn -->
                                    <div class="col-md-6 mb-3">
                                        <label for="table_number" class="form-label">Số Bàn</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="bi bi-door-open text-white"></i>
                                            </span>
                                            <input type="number" name="table_number" id="table_number"
                                                class="form-control {{ $errors->has('table_number') ? 'is-invalid' : '' }}"
                                                value="{{ old('table_number', $table->table_number) }}" required
                                                min="1" max="100" placeholder="Nhập số bàn"
                                                oninput="toggleErrorMessages()">
                                        </div>
                                        <div class="invalid-feedback" id="invalid-feedback-1">
                                            Vui lòng nhập số bàn hợp lệ (từ 1 đến 100).
                                        </div>
                                        @error('table_number')
                                            <div class="text-danger" id="invalid-feedback-2">Số bàn này đã tồn tại. Vui lòng
                                                nhập số
                                                khác.</div>
                                        @enderror
                                    </div>

                                    <!-- Trạng Thái -->
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Trạng Thái</label>
                                        <select name="status" id="status" class="form-select" required
                                            data-current-status="{{ $table->status }}">
                                            <option value="Available" {{ $table->status == 'Available' ? 'selected' : '' }}>
                                                Có
                                                sẵn</option>
                                            <option value="Reserved" {{ $table->status == 'Reserved' ? 'selected' : '' }}>
                                                Đã
                                                đặt trước</option>
                                            <option value="Occupied" {{ $table->status == 'Occupied' ? 'selected' : '' }}>
                                                Đang
                                                sử dụng</option>
                                        </select>
                                        <div class="invalid-feedback">Trạng thái không hợp lệ. Vui lòng chọn lại.</div>

                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nút Lưu và Hủy -->
                                <div class="mb-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="updateButton">Cập Nhật Bàn</button>
                                    <a href="{{ route('admin.table.index') }}" class="btn btn-secondary ms-2">Hủy</a>
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
            const tableNumberInput = document.getElementById('table_number');

            // Giới hạn giá trị khi người dùng nhập
            tableNumberInput.addEventListener('input', function() {
                const value = parseInt(tableNumberInput.value);

                // Nếu giá trị nhỏ hơn 1 hoặc không hợp lệ
                if (isNaN(value) || value < 1) {
                    tableNumberInput.value = ''; // Reset giá trị
                    tableNumberInput.classList.add('is-invalid');
                }
                // Nếu giá trị vượt quá 100
                else if (value > 100) {
                    tableNumberInput.value = 100; // Giới hạn giá trị tối đa
                    tableNumberInput.classList.add('is-invalid');
                }
                // Nếu giá trị hợp lệ
                else {
                    tableNumberInput.classList.remove('is-invalid');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editTableForm');
            const statusSelect = document.getElementById('status');
            const currentStatus = "{{ $table->status }}"; // Trạng thái hiện tại
            const submitButton = document.getElementById('updateButton');

            // Lắng nghe sự kiện change để kiểm tra trạng thái
            statusSelect.addEventListener('change', function() {
                const selectedStatus = statusSelect.value;

                // Kiểm tra trạng thái hợp lệ
                if (
                    (currentStatus === "Đã hủy" && selectedStatus !== "Chờ xử lý") &&
                    (currentStatus === "Đã xác nhận" && selectedStatus !== "Đã xác nhận")
                ) {
                    submitButton.disabled = true; // Vô hiệu hóa nút submit
                    statusSelect.classList.add('is-invalid'); // Thêm lớp để báo lỗi
                } else {
                    submitButton.disabled = false; // Kích hoạt nút submit
                    statusSelect.classList.remove('is-invalid'); // Xóa lớp lỗi
                }
            });

            // Kiểm tra trước khi gửi form
            form.addEventListener('submit', function(event) {
                const selectedStatus = statusSelect.value;

                // Nếu trạng thái không hợp lệ, ngừng gửi form
                if (
                    (currentStatus === "Đã hủy" && selectedStatus !== "Chờ xử lý") &&
                    (currentStatus === "Đã xác nhận" && selectedStatus !== "Đã xác nhận")
                ) {
                    event.preventDefault(); // Ngừng gửi form
                    alert("Không thể thay đổi trạng thái từ 'Đã hủy' sang 'Đã xác nhận' hoặc ngược lại.");
                }
            });
        });
    </script>

@endsection
