@extends('admin.master')

@section('title', 'Chỉnh Sửa Thanh Toán')

@section('content')
    @include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title text-primary">Chỉnh Sửa Thanh Toán</div>
                        </div>
                        <div class="card-body">
                            <form id="payment-form" action="{{ route('admin.payment.update', $payment->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <!-- Thông tin thanh toán -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Mã Đặt Bàn (Reservation ID)</label>
                                        <input type="text" name="reservation_id" class="form-control text-primary"
                                            value="{{ $payment->reservation_id }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mã Hóa Đơn (Bill ID)</label>
                                        <input type="text" name="bill_id" class="form-control text-primary"
                                            value="{{ $payment->bill_id }}" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Số Tiền (Amount)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">₫</span>
                                            <input type="number" name="amount" class="form-control text-primary"
                                                id="amount"
                                                value="{{ number_format($payment->transaction_amount, 0, ',', '.') }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phương Thức Thanh Toán (Payment Method)</label>
                                        <div class="d-flex justify-content-start">
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    value="Cash" id="cash"
                                                    {{ $payment->payment_method == 'Cash' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cash">
                                                    Tiền mặt
                                                </label>
                                            </div>
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    value="Credit Card" id="credit-card"
                                                    {{ $payment->payment_method == 'Credit Card' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="credit-card">
                                                    Thẻ tín dụng
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    value="Online" id="online"
                                                    {{ $payment->payment_method == 'Online' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="online">
                                                    Thanh toán trực tuyến
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Trạng thái giao dịch -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Trạng Thái Giao Dịch (Transaction Status)</label>
                                        <select name="transaction_status" id="transaction-status" class="form-select"
                                            required>
                                            <option value="pending"
                                                {{ $payment->transaction_status == 'pending' ? 'selected' : '' }}>
                                                Đang xử lý
                                            </option>
                                            <option value="completed"
                                                {{ $payment->transaction_status == 'completed' ? 'selected' : '' }}>
                                                Hoàn thành
                                            </option>
                                            <option value="failed"
                                                {{ $payment->transaction_status == 'failed' ? 'selected' : '' }}>
                                                Thất bại
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Trạng Thái (Status)</label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="Completed"
                                                {{ $payment->status == 'Completed' ? 'selected' : '' }}>
                                                Hoàn thành
                                            </option>
                                            <option value="Pending" {{ $payment->status == 'Pending' ? 'selected' : '' }}>
                                                Đang xử lý
                                            </option>
                                            <option value="Failed" {{ $payment->status == 'Failed' ? 'selected' : '' }}>
                                                Thất bại
                                            </option>
                                        </select>
                                        <!-- Cảnh báo -->
                                        <span id="warning-message" class="text-danger d-none">
                                            Trạng thái không hợp lệ. Vui lòng chọn lại.
                                        </span>
                                    </div>
                                </div>

                                <!-- Nút hành động -->
                                <div class="text-end">
                                    <button type="submit" id="submit-btn" class="btn btn-primary">Cập nhật</button>
                                    <a href="{{ route('admin.payment.index') }}" class="btn btn-secondary">Quay lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const statusField = document.getElementById("status");
            const transactionStatusField = document.getElementById("transaction-status");
            const submitButton = document.getElementById("submit-btn");
            const warningMessage = document.getElementById("warning-message");

            // Trạng thái ban đầu
            const initialStatus = "{{ $payment->status }}";
            const initialTransactionStatus = "{{ $payment->transaction_status }}";

            // Kiểm tra và xử lý trạng thái khi thay đổi
            function checkStatus() {
                const selectedStatus = statusField.value;
                const selectedTransactionStatus = transactionStatusField.value;

                // Kiểm tra điều kiện hợp lệ
                if (selectedTransactionStatus === "completed" && selectedStatus !== "Completed") {
                    warningMessage.classList.remove("d-none");
                    submitButton.disabled = true; // Disable nút cập nhật
                } else if (selectedTransactionStatus === "failed" && selectedStatus !== "Failed") {
                    warningMessage.classList.remove("d-none");
                    submitButton.disabled = true; // Disable nút cập nhật
                } else {
                    warningMessage.classList.add("d-none");
                    submitButton.disabled = false; // Kích hoạt lại nút cập nhật
                }
            }

            // Xử lý khi thay đổi trạng thái
            statusField.addEventListener("change", checkStatus);
            transactionStatusField.addEventListener("change", checkStatus);

            // Khởi động kiểm tra trạng thái ngay khi trang load
            checkStatus();
        });
    </script>

@endsection
