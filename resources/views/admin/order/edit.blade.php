@extends('admin.master')

@section('title', 'Chỉnh Sửa Đơn Hàng')

@section('content')
    @include('admin.layouts.messages')


    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <h4 class="card-title mb-0 text-primary">Chỉnh Sửa Hoá Đơn</h4>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.order.update', $order->id) }}" method="POST"
                                onsubmit="return validateForm()">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="reservation_id" class="form-label">Mã Đặt Bàn</label>
                                        <input type="number" class="form-control text-primary" id="reservation_id"
                                            name="reservation_id"
                                            value="{{ old('reservation_id', $order->reservation_id) }}" required
                                            placeholder="Nhập mã đặt chỗ" min="1" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="staff_id" class="form-label">Nhân Viên</label>
                                        <input type="number" class="form-control text-primary" id="staff_id"
                                            name="staff_id" value="{{ old('staff_id', $order->staff_id) }}" required
                                            placeholder="Nhập mã nhân viên" min="1" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="customer_id" class="form-label">Mã Khách Hàng</label>
                                        <input type="number" class="form-control text-primary" id="customer_id"
                                            name="customer_id" value="{{ old('customer_id', $order->customer_id) }}"
                                            required placeholder="Nhập mã khách hàng" min="1" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="total_amount" class="form-label">Tổng Tiền</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">₫</span>
                                            <input type="text" class="form-control rounded-end" id="total_amount"
                                                name="total_amount"
                                                value="{{ number_format(old('total_amount', $order->total_amount) ?? 0, 0, ',', '.') }}"
                                                placeholder="Nhập tổng tiền" oninput="formatCurrency(this)"
                                                data-raw-value="{{ old('total_amount', $order->total_amount) }}">
                                        </div>
                                        <span id="total_amount_error" class="text-danger small"></span>
                                    </div>





                                    <div class="col-md-6 mb-3">
                                        <label for="order_type" class="form-label">Loại Đơn Hàng</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="order_type"
                                                    id="dine_in" value="dine_in"
                                                    {{ old('order_type', $order->order_type) === 'dine_in' ? 'checked' : '' }}
                                                    required>
                                                <label class="form-check-label" for="dine_in">Dùng tại chỗ</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="order_type"
                                                    id="take_away" value="take_away"
                                                    {{ old('order_type', $order->order_type) === 'take_away' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="take_away">Mang về</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="order_type"
                                                    id="delivery" value="delivery"
                                                    {{ old('order_type', $order->order_type) === 'delivery' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="delivery">Giao hàng</label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">Trạng Thái</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="completed"
                                                {{ old('status', $order->status) === 'completed' ? 'selected' : '' }}>
                                                Hoàn thành
                                            </option>
                                            <option value="pending"
                                                {{ old('status', $order->status) === 'pending' ? 'selected' : '' }}>
                                                Đang xử lý
                                            </option>
                                            <option value="cancelled"
                                                {{ old('status', $order->status) === 'cancelled' ? 'selected' : '' }}>
                                                Đã hủy
                                            </option>
                                        </select>
                                        <div id="status-error" class="text-danger mt-2 d-none">Trạng thái không hợp lệ. Vui
                                            lòng chọn lại.</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="discount_amount" class="form-label">Số Tiền Giảm Giá</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">₫</span>
                                            <input type="text" class="form-control rounded-end" id="discount_amount"
                                                name="discount_amount"
                                                value="{{ number_format(old('discount_amount', $order->discount_amount) ?? 0, 0, ',', '.') }}"
                                                placeholder="Nhập số tiền giảm giá" oninput="formatCurrency(this)"
                                                data-raw-value="{{ old('discount_amount', $order->discount_amount) }}">
                                        </div>
                                        <span id="discount_amount_error" class="text-danger small"></span>
                                    </div>



                                    <div class="col-md-6 mb-3">
                                        <label for="final_amount" class="form-label">Số Tiền Cuối Cùng</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">₫</span>
                                            <input type="text" class="form-control rounded-end" id="final_amount"
                                                name="final_amount"
                                                value="{{ number_format(old('final_amount', $order->final_amount) ?? 0, 0, ',', '.') }}"
                                                placeholder="Nhập số tiền cuối cùng" oninput="formatCurrency(this)"
                                                data-raw-value="{{ old('final_amount', $order->final_amount) }}">
                                        </div>
                                        <span id="final_amount_error" class="text-danger small"></span>
                                    </div>



                                    <!-- Nút cập nhật đơn hàng -->
                                    <div class="text-end">
                                        <button type="submit" id="update-order-btn" class="btn btn-primary">Cập
                                            nhật</button>
                                        <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-secondary ">Quay
                                            Lại</a>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content wrapper end -->

    </div>

@endsection

<script>
    // Hàm kiểm tra và thông báo lỗi nếu giá trị nhập vào không hợp lệ
    function validateAmount(input) {
        const value = parseFloat(input.value);
        const maxValue = parseFloat(input.getAttribute('max'));

        // Kiểm tra nếu giá trị vượt quá giới hạn
        if (value > maxValue) {
            alert("Số tiền không được vượt quá " + maxValue);
            input.setCustomValidity("Giá trị không hợp lệ!");
            input.value = maxValue; // Đặt lại giá trị về giới hạn tối đa
        } else {
            input.setCustomValidity(""); // Hủy bỏ thông báo lỗi nếu giá trị hợp lệ
        }
    }

    // Hàm chặn việc nhập thêm số vào ô input nếu đã đạt giới hạn max
    function blockExtraInput(input) {
        const value = input.value;
        const maxValue = input.getAttribute('max');

        if (value.length > 0 && parseFloat(value) > parseFloat(maxValue)) {
            alert("Số tiền không được vượt quá " + maxValue);
            input.value = maxValue; // Đặt lại giá trị về giới hạn tối đa
        }
    }

    // Lắng nghe sự kiện keyup và chặn khi giá trị vượt quá max
    document.querySelectorAll('input[type="number"]').forEach(function(input) {
        input.addEventListener('input', function() {
            validateAmount(input);
            blockExtraInput(input);
        });


        function validateAmount(input, errorElementId) {
            const value = parseFloat(input.value);
            const minValue = parseFloat(input.getAttribute('min'));
            const maxValue = parseFloat(input.getAttribute('max'));
            const errorElement = document.getElementById(errorElementId);

            // Reset lỗi
            errorElement.textContent = "";

            if (isNaN(value)) {
                errorElement.textContent = "Giá trị phải là số hợp lệ.";
                input.classList.add("is-invalid");
            } else if (value < minValue) {
                errorElement.textContent = `Số tiền phải lớn hơn hoặc bằng ${minValue}.`;
                input.classList.add("is-invalid");
            } else if (value > maxValue) {
                errorElement.textContent = `Số tiền không được vượt quá ${maxValue}.`;
                input.classList.add("is-invalid");
            } else if (value < 0) {
                errorElement.textContent = "Số tiền không được âm.";
                input.classList.add("is-invalid");
            } else {
                input.classList.remove("is-invalid");
            }
        }

    });

    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const updateButton = document.getElementById('update-order-btn');
        const statusError = document.getElementById('status-error');

        // Lấy trạng thái ban đầu từ server
        const initialStatus = "{{ $order->status }}";

        // Theo dõi thay đổi trạng thái
        statusSelect.addEventListener('change', function() {
            const selectedStatus = statusSelect.value;

            // Kiểm tra trạng thái ngược
            if (
                (initialStatus === 'completed' && selectedStatus !== 'completed') ||
                (initialStatus === 'cancelled' && selectedStatus === 'pending')
            ) {
                // Hiển thị lỗi và vô hiệu hóa nút
                statusError.classList.remove('d-none');
                updateButton.disabled = true;
            } else {
                // Ẩn lỗi và kích hoạt nút
                statusError.classList.add('d-none');
                updateButton.disabled = false;
            }
        });
    });

    function validateAmount(input, errorId) {
        const errorElement = document.getElementById(errorId);
        const value = parseFloat(input.value);

        // Kiểm tra nếu giá trị không hợp lệ
        if (isNaN(value) || value < 1) {
            errorElement.textContent = "Tổng tiền không được nhỏ hơn 1.";
            input.value = ""; // Reset giá trị không hợp lệ
        } else if (value > 50000000) {
            errorElement.textContent = "Tổng tiền không được lớn hơn 50.000.000";
            input.value = ""; // Reset giá trị không hợp lệ
        } else {
            // Xóa lỗi nếu giá trị hợp lệ
            errorElement.textContent = "";
        }
    }
</script>
