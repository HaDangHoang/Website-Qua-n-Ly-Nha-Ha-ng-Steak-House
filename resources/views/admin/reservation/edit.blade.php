@extends('admin.master')

@section('title', 'Chỉnh Sửa Đặt Bàn')

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
                            <div class="card-title text-primary">Chỉnh Sửa Đặt Bàn</div>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('admin.reservation.update', $reservation->id) }}" method="POST"
                                id="reservationForm">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Cột trái -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_name" class="form-label">Khách hàng</label>
                                            <input type="text" class="form-control text-primary" id="customer_name"
                                                name="customer_name" value="{{ $reservation->customer->name ?? '' }}"
                                                required readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label for="reservation_date" class="form-label">Ngày Đặt</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-warning text-white">
                                                    <i class="bi bi-calendar-date text-white"></i>
                                                    <!-- Icon lịch từ Bootstrap Icons -->
                                                </span>
                                                <input type="date" class="form-control" id="reservation_date"
                                                    name="reservation_date"
                                                    value="{{ old('reservation_date', $reservationDate) }}" required>
                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <label for="reservation_time" class="form-label">Giờ Đặt</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-danger text-white">
                                                    <i class="bi bi-alarm text-white"></i>
                                                    <!-- Icon đồng hồ từ Bootstrap Icons -->
                                                </span>
                                                <input type="time" class="form-control" id="reservation_time"
                                                    name="reservation_time"
                                                    value="{{ old('reservation_time', $reservationTime) }}" required>
                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <label for="guest_count" class="form-label">Số Lượng Khách</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white">
                                                    <i class="bi bi-people text-white"></i>
                                                    <!-- Icon người từ Bootstrap Icons -->
                                                </span>
                                                <input type="number" class="form-control text-primary" id="guest_count"
                                                    name="guest_count" value="{{ $reservation->guest_count }}"
                                                    min="1" max="50" readonly>
                                                <div class="invalid-feedback">Số lượng khách phải nằm trong khoảng từ 1 đến
                                                    50.</div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Cột phải -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deposit_amount" class="form-label">Số Tiền Đặt Cọc</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success text-white">₫</span>
                                                <input type="text" class="form-control text-primary" id="deposit_amount"
                                                    name="deposit_amount"
                                                    value="{{ number_format($reservation->deposit_amount ?? 0, 0, ',', '.') }}"
                                                    readonly data-raw-value="{{ $reservation->deposit_amount }}">
                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <label for="status" class="form-label">Trạng thái</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="Confirmed"
                                                    {{ $reservation->status == 'Confirmed' ? 'selected' : '' }}>Đã xác nhận
                                                </option>
                                                <option value="Pending"
                                                    {{ $reservation->status == 'Pending' ? 'selected' : '' }}>Chờ xử lý
                                                </option>
                                                <option value="Cancelled"
                                                    {{ $reservation->status == 'Cancelled' ? 'selected' : '' }}>Đã hủy
                                                </option>
                                            </select>
                                            <div class="text-danger" id="status-error" style="display: none;">
                                                Bạn không thể thay đổi trạng thái từ "Đã hủy" sang trạng thái khác.
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="note" class="form-label">Ghi Chú</label>
                                            <div class="input-group">

                                                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Nhập ghi chú...">{{ $reservation->note }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary" id="updateButton">Cập Nhật</button>
                                    <a href="{{ route('admin.reservation.index') }}" class="btn btn-secondary">Quay
                                        lại</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->

        </div>
    </div>
    <!-- Content wrapper scroll end -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const updateButton = document.getElementById('updateButton');
            const statusError = document.getElementById('status-error');
            const currentStatus = "{{ $reservation->status }}";

            function validateStatus() {
                const selectedStatus = statusSelect.value;

                // Nếu trạng thái hiện tại là "Đã hủy" và cố gắng thay đổi sang trạng thái khác
                if (currentStatus === 'Cancelled' && selectedStatus !== 'Cancelled') {
                    statusError.style.display = 'block'; // Hiển thị lỗi
                    updateButton.disabled = true; // Vô hiệu hóa nút cập nhật
                } else {
                    statusError.style.display = 'none'; // Ẩn lỗi
                    updateButton.disabled = false; // Bật lại nút cập nhật
                }
            }

            // Kiểm tra trạng thái khi người dùng thay đổi lựa chọn
            statusSelect.addEventListener('change', validateStatus);

            // Kiểm tra ngay khi trang tải xong
            validateStatus();
        });
    </script>

@endsection
