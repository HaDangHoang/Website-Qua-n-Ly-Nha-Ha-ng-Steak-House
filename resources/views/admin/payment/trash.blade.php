@extends('admin.master')

@section('title', 'Thùng Rác Thanh Toán')

@section('content')
    @include('admin.layouts.messages')

    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Thùng Rác Thanh Toán</div>
                            <a href="{{ route('admin.payment.index') }}" class="btn btn-sm btn-primary">
                                Quay lại danh sách thanh toán
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>Mã Thanh Toán</th>
                                            <th>Mã Đặt Chỗ</th>
                                            <th>Mã Hóa Đơn</th>
                                            <th>Số Tiền Giao Dịch</th>
                                            <th>Trạng Thái Giao Dịch</th>
                                            <th>Hình Thức Thanh Toán</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->id }}</td>
                                                <td>{{ $payment->reservation_id }}</td>
                                                <td>{{ $payment->bill_id }}</td>
                                                <td>{{ number_format($payment->transaction_amount, 2, ',', '.') }} VND</td>
                                                <td>
                                                    <span
                                                        class="badge
                                                    @if ($payment->transaction_status == 'completed') bg-success
                                                    @elseif ($payment->transaction_status == 'failed') bg-danger
                                                    @elseif ($payment->transaction_status == 'pending') bg-warning @endif">
                                                        {{ ucfirst($payment->transaction_status) }}
                                                    </span>
                                                </td>
                                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                                <td>
                                                    <div class="actions">
                                                        <!-- Khôi phục -->
                                                        <form action="{{ route('admin.payment.restore', $payment->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn khôi phục không?');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <a href="#">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <i class="bi bi-arrow-clockwise text-green"></i>
                                                                </button></a>
                                                        </form>

                                                        <!-- Xóa vĩnh viễn -->
                                                        <form
                                                            action="{{ route('admin.payment.forceDelete', $payment->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn không?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <i class="bi bi-trash text-red"></i>
                                                                </button></a>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có bản ghi thanh toán nào trong
                                                    thùng rác.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination justify-content-center mt-3">
                                {{ $payments->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="notification" class="notification d-none">
        <div class="notification-icon">
            <i class="bi"></i>
        </div>
        <div class="notification-content">
            <strong id="notification-title"></strong>
            <p id="notification-message"></p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hàm hiển thị thông báo
            function showNotification(message, type = "success") {
                const notification = document.getElementById("notification");
                const notificationIcon = notification.querySelector(".notification-icon i");
                const notificationTitle = document.getElementById("notification-title");
                const notificationMessage = document.getElementById("notification-message");

                // Cập nhật nội dung thông báo
                notificationMessage.textContent = message;

                // Đặt kiểu thông báo
                if (type === "success") {
                    notification.style.background = "linear-gradient(90deg, #58ade8, #48d1cc)";
                    notification.style.color = "#ffffff";
                    notificationIcon.className = "bi bi-check-circle-fill icon-animate";
                    notificationTitle.textContent = "Thành công!";
                } else if (type === "error") {
                    notification.style.background = "linear-gradient(90deg, #f44336, #ff6347)";
                    notification.style.color = "#ffffff";
                    notificationIcon.className = "bi bi-x-circle-fill icon-animate";
                    notificationTitle.textContent = "Lỗi!";
                }

                // Hiển thị thông báo
                notification.classList.remove("d-none");
                notification.classList.add("show");

                // Ẩn thông báo sau 3 giây
                setTimeout(() => {
                    notification.classList.remove("show");
                    notification.classList.add("hide");

                    // Reset sau khi ẩn
                    setTimeout(() => {
                        notification.classList.add("d-none");
                        notification.classList.remove("hide");
                        notificationIcon.classList.remove("icon-animate");
                    }, 300);
                }, 3000);
            }

            // Hiển thị thông báo từ session
            @if (session('success'))
                showNotification("{{ session('success') }}", "success");
            @endif

            @if (session('error'))
                showNotification("{{ session('error') }}", "error");
            @endif
        });
    </script>

@endsection

<style>
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        max-width: 300px;
        background: #ffffff;
        color: #333;
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        opacity: 0;
        pointer-events: none;
        transform: translateY(-10px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .notification.show {
        opacity: 1;
        pointer-events: all;
        transform: translateY(0);
    }

    .notification.hide {
        opacity: 0;
        pointer-events: none;
        transform: translateY(-10px);
    }

    .notification i {
        font-size: 20px;
        color: inherit;
    }
</style>
