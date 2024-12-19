@extends('client.layouts.master')
@section('title', 'Member')
@section('css')
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Nhúng Summernote CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
@section('content')

    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-2.jpg',
        'subtitle' => 'Get in',
        'title' => 'Touch',
        'currentPage' => 'Member',
    ])
    <div id="content" class="">
        <div class="container ">

            <!-- Phần thông tin tài khoản -->

            <div class="container-fluid w-100">
                <div class="row mb-4">
                    <!-- Side Menu -->
                    <div class=" profile col-lg-3  side-menu p-3 bg-dark text-light">
                        <div class="d-flex align-items-center mb-4">
                            <div class="profile-circle bg-secondary text-white">
                                <!-- Profile Icon Placeholder -->
                            </div>
                        </div>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white hover-text" href="#"
                                    onclick="showSection('reservationSection')">Đặt chỗ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white hover-text" href="#"
                                    onclick="showSection('accountDetailsSection')">Chi tiết tài khoản</a>
                            </li>
                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: transparent; border: none;"
                                        class="nav-link  text-white hover-text">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>


                    <!-- Main Content -->
                    <div class="col-lg-9 col-md-8 p-4">
                        <div id="reservationSection" class="content-section">
                            <h3>Đặt chỗ sắp tới</h3>
                            <!-- Reservation Details -->
                            @foreach ($bookingData as $reservation)
                                <div
                                    class="reservation-card details-{{ $reservation->id }}  mb-3 p-3 bg-dark text-light rounded">

                                    <h5>Mã đặt bàn: {{ $reservation->id }} - {{ $reservation->user_name }}</h5>
                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-white"><i class="bi bi-person"></i>Số người:
                                                {{ $reservation->guest_count }} người</p>
                                            <p class="text-white"><i class="bi bi-calendar-date"></i>
                                                Ngày:{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}
                                                | {{ $reservation->reservation_time }}</p>
                                        </div>
                                        <div class="col-4">
                                            <p class="text-white"><i class="bi bi-telephone"></i> Số điện
                                                thoại:{{ $reservation->user_phone }}</p>
                                            <p class="text-white"><i class="bi bi-cash"></i>Cọc:
                                                {{ number_format($reservation->deposit_amount ?? 0, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                        @if ($reservation->status == 'Refund')
                                            <div class="col-4">
                                                <p class="text-white"><i style="color:#3ca4ff" class="bi bi-cash"></i>Hoàn
                                                    tiền:
                                                    {{ number_format($reservation->refund->refund_amount ?? 0, 0, ',', '.') }}
                                                    VNĐ</p>
                                                <p class="text-white"><i class="bi bi-stickies"></i>Lý do
                                                    hủy:{{ $reservation->refund->reason ?? '' }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    @php
                                        $statusClasses = [
                                            'Confirmed' => 'status-confirmed',
                                            'Pending' => 'status-pending',
                                            'Cancelled' => 'status-cancelled',
                                            'Refund' => 'status-refund',
                                            'Completed' => 'status-completed',
                                            'Pending Refund' => 'status-pending-refund', // Thêm class cho trạng thái 'Pending Refund'
                                        ];

                                        $statusLabels = [
                                            'Confirmed' => 'Đã xác nhận',
                                            'Pending' => 'Chờ xử lý',
                                            'Cancelled' => 'Đã hủy',
                                            'Refund' => 'Đang hoàn tiền',
                                            'Completed' => 'Đã hoàn thành',
                                            'Pending Refund' => 'Chờ hoàn cọc', // Thêm nhãn cho trạng thái 'Pending Refund'
                                        ];
                                    @endphp
                                    <strong
                                        class="text-right {{ $statusClasses[$reservation->status] ?? 'status-pending' }}">
                                        {{ $statusLabels[$reservation->status] ?? 'Chờ xử lý' }}
                                    </strong>

                                    <div class=" mt-3">


                                        @if ($reservation->status == 'Pending' || $reservation->status == 'Confirmed')
                                            <button class="btn btn-secondary edit-reservation-btn"
                                                data-id="{{ $reservation->id }}">Chỉnh sửa</button>
                                            @if ($reservation->deposit_amount > 0)
                                                <button class="text-danger cancel-btn-new" data-toggle="modal"
                                                    data-target="#cancelModal"
                                                    style="background: transparent; border: none;" data-bs-toggle="modal"
                                                    data-bs-target="#cancelModal"
                                                    data-reservation-id="{{ $reservation->id }}"
                                                    data-deposit-amount="{{ $reservation->deposit_amount }}"
                                                    data-reservation-time="{{ $reservation->reservation_time }}"
                                                    data-reservation-date="{{ \Carbon\Carbon::parse($reservation->reservation_date)->toIso8601String() }}">
                                                    Hủy
                                                </button>
                                            @else
                                                <button style="background: transparent; border: none;"
                                                    class="text-danger cancel-btn-new deleteButton" id="deleteButton"
                                                    data-id ="{{ $reservation->id }}">Hủy</button>
                                            @endif
                                        @elseif($reservation->status == 'Completed')
                                            @if (isset($reservation->feedback->content))
                                                {{-- <p class="text-success">Đánh giá của bạn:
                                                    {{ $reservation->feedback->content }}</p> --}}
                                                <p>
                                                    <strong class="text-white">Đánh giá của bạn: </strong>
                                                    <span class="stars-summary" style="font-size: 20px; color: #ffc107;">
                                                        {{-- Hiển thị số sao đã đánh giá --}}
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $reservation->feedback->rating)
                                                                ★
                                                            @else
                                                                ☆
                                                            @endif
                                                        @endfor
                                                    </span>
                                                    {{-- (<span class="rating-number-summary"></span> sao) --}}
                                                </p>
                                                <p><strong class="text-white">Nội dung:</strong> <span
                                                        class="review-content-summary text-white">{{ $reservation->feedback->content }}</span>
                                                </p>
                                                {{-- <button style="background: transparent; border: none;" class="text-warning cancel-btn-new deleteButton" id="deleteButton" data-id ="{{$reservation->id}}">Chỉnh sửa</button>  --}}
                                            @else
                                                <button button class="rate-reservation-btn  btn-primary"
                                                    data-id ="{{ $reservation->id }}">Đánh
                                                    giá</button>
                                            @endif
                                        @endif


                                    </div>



                                    <!-- Review Input -->
                                    <div class="reservation-rating-form reservation-rating-form-{{ $reservation->id }}"
                                        style="display: none;">
                                        <form class="rating-form" data-id="{{ $reservation->id }}">
                                            @csrf
                                            <div class="form-group">
                                                <label>Đánh giá sao</label>
                                                <div class="rating-stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star star-rating"
                                                            data-value="{{ $i }}"></i>
                                                    @endfor
                                                </div>
                                                <input type="hidden" class="rating-value" name="rating" value="0">
                                                <input type="hidden" class="customer_id" name="customer_id"
                                                    value="{{ $member->id }}">
                                                <span id="error-rating" class="text-danger"></span>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="review">Đánh giá</label>
                                                <textarea name="review" class="review form-control" rows="3" placeholder="Nhập nội dung đánh giá..."></textarea>
                                                <span id="error-review" class="text-danger"></span>
                                            </div>
                                            <button type="submit" class=" btn-success mt-3">Gửi đánh giá</button>
                                            <button type="button" class="rating-cancel btn-secondary"
                                                data-id="{{ $reservation->id }}">Hủy</button>

                                        </form>

                                        {{-- <div class="reservation-review-details review-details-{{ $reservation->id }}" style="display: none;">
                                            <p class="text-warning">
                                                <strong>Số sao: </strong>
                                                <span class="review-stars"></span> <!-- Nơi hiển thị số sao -->
                                            </p>
                                            <p>
                                                <strong>Lời đánh giá: </strong>
                                                <span class="review-content"></span> <!-- Nơi hiển thị nội dung đánh giá -->
                                            </p>
                                        </div> --}}
                                    </div>
                                    <!-- Hiển thị đánh giá sau khi gửi -->
                                    <div class="review-summary review-summary-{{ $reservation->id }}"
                                        style="display: none;">
                                        <p>
                                            <strong class="text-white">Đánh giá của bạn: </strong>
                                            <span class="stars-summary" style="font-size: 20px; color: #ffc107;"></span>
                                            (<span class="rating-number-summary"></span> sao)
                                        </p>
                                        <p><strong class="text-white">Nội dung:</strong><span
                                                class="review-content-summary text-white"></span></p>
                                    </div>


                                    <!-- Form chỉnh sửa thông tin đặt bàn -->
                                    <div id="reservation-edit-form"
                                        class="modal modal-custom reservation-edit-form reservation-edit-form-{{ $reservation->id }}"
                                        style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-black"
                                                        id="reservationEditLabel-{{ $reservation->id }}">Chỉnh sửa đặt bàn
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form data-id="{{ $reservation->id }}" class="edit-reservation">
                                                        @csrf
                                                        <div class="form-group mb-3">
                                                            <label class="text-black" for="customer_name">Tên khách
                                                                hàng</label>
                                                            <input type="text" class="form-control user_name"
                                                                name="user_name" value="{{ $reservation->user_name }}">
                                                            <span id="error-customer_name" class="text-danger"></span>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="text-black" for="user_phone">Số điện
                                                                thoại</label>
                                                            <input type="text" class="form-control user_phone"
                                                                name="user_phone" value="{{ $reservation->user_phone }}">
                                                            <span id="error-user_phone" class="text-danger"></span>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="text-black" for="guest_count">Số lượng
                                                                khách</label>
                                                            <input type="number" class="form-control guest_count"
                                                                name="guest_count"
                                                                value="{{ $reservation->guest_count }}" min="1"
                                                                max="50">
                                                            <span id="error-guest_count" class="text-danger"></span>
                                                        </div>

                                                        <div class="deposit-section" style="display: none;">
                                                            <div class="form-group mb-3">
                                                                <label class="text-black" for="deposit_amount">Tiền
                                                                    cọc</label>
                                                                <input type="number" class="form-control deposit_amount"
                                                                    name="deposit_amount" readonly>
                                                            </div>
                                                            <p class="text-warning deposit-warning">
                                                                @if ($reservation->guest_count >= 6)
                                                                    Số lượng khách của bạn cần đặt cọc
                                                                    {{-- {{ number_format($reservation->guest_count * 100000, 0, ',', '.') }} VNĐ. --}}
                                                                @endif
                                                            </p>
                                                            <div class="qr-section"
                                                                style="display: none; text-align: center;">
                                                                <p>Quét mã QR để thanh toán:</p>
                                                                <img src="" alt="QR Code" class="qr-code"
                                                                    style="max-width: 200px; height: auto;">
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="text-black" for="reservation_date">Ngày đặt
                                                                bàn</label>
                                                            <input type="date" class="form-control reservation_date"
                                                                name="reservation_date"
                                                                value="{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('Y-m-d') }}">
                                                            <span id="error-reservation_date" class="text-danger"></span>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="text-black" for="reservation_time">Giờ đặt
                                                                bàn</label>
                                                            <input type="time" class="form-control reservation_time"
                                                                name="reservation_time"
                                                                value="{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}">
                                                            <span id="error-reservation_time" class="text-danger"></span>
                                                        </div>

                                                        <button type="submit" class="edit-form btn-primary">Lưu thay
                                                            đổi</button>

                                                        <button type="button" class="cancel-edit btn-secondary"
                                                            data-id="{{ $reservation->id }}">Hủy</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal hiển thị thông báo -->
                                    <div id="alertModal" class="modal alertModal" tabindex="-1">
                                        {{-- <div class="modal-dialog modal-dialog-centered"> --}}
                                        <div class="modal-dialog modal-content">

                                            <div class="modal-footer">

                                                <p class="alertMessage"></p>

                                                <button type="button" id="closePopup"
                                                    class=" bg-secondary text-white closePopup"
                                                    style="border:black">Đóng</button>
                                                <button type="button" class="btn-primary confirm-submit">Xác
                                                    nhận</button>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Popup lỗi khi chọn giờ không hợp lệ -->
                                    {{-- <div id="errorPopup" class="modal" style="display: none;">
                                        <div class="modal-content">
                                            <span class="close" onclick="closePopup()">&times;</span>
                                            <p>Giờ đặt bàn phải trong khoảng từ 11:00 đến 20:30. Vui lòng chọn lại.</p>
                                        </div>
                                    </div> --}}

                                </div>
                            @endforeach

                            <div class="justify-content-center mt-3">
                                {{ $bookingData->links('pagination::client-paginate') }}
                            </div>
                        </div>







                        <div id="accountDetailsSection" class="content-section" style="display:none;">
                            <h3>Thông tin cá nhân</h3>
                            <form action="{{ route('member.update') }}" method="POST"
                                onsubmit="return handleFormSubmit(event)">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $member->name }}" oninput="showSaveButton()">
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ $member->phone }}" oninput="showSaveButton()">
                                    </div>
                                </div>
                                <button type="submit" style="display:none;" id="saveButton" class="btn-line">Lưu thay
                                    đổi</button>
                                <button type="button" onclick="toggleEdit()" id="editButton" class="btn-line">Chỉnh sửa
                                    thông tin</button>

                            </form>
                        </div>
                    </div>

                    <!-- Modal -->
                    <!-- Modal -->
                    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelModalLabel">Xác nhận hủy đặt chỗ</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-info">
                                        <strong>Chính sách hủy bàn:</strong>
                                        <ul>
                                            {{-- <li>Hủy trước giờ nhận bàn 1 giờ: hoàn 100% tiền cọc.</li> --}}
                                            <li> Hủy trong 1 tiếng trước giờ nhận bàn: hoàn 50% tiền cọc.</li>
                                            <li>Khách hàng không yêu cầu hủy hoặc không đến: không được hoàn cọc.</li>
                                        </ul>
                                    </div>
                                    <form action="{{ route('refunds.cancel') }}" method="POST">
                                        @csrf
                                        <input type="hidden" id="reservation_id" name="reservation_id">
                                        <p id="refundAmountDisplay" style="color: #ff0000;"></p>
                                        <input type="hidden" id="refund_amount" name="refund_amount">
                                        <!-- Tài khoản và thông tin liên hệ -->
                                        <div class="form-group">
                                            <label for="account_name" class="form-label">Tên tài khoản</label>
                                            <input type="text"
                                                class="form-control @error('account_name') is-invalid @enderror"
                                                id="account_name" name="account_name">
                                            @error('account_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="account_number" class="form-label">Số tài khoản</label>
                                            <input type="number"
                                                class="form-control @error('account_number') is-invalid @enderror"
                                                id="account_number" name="account_number">
                                            @error('account_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Chọn ngân hàng -->
                                        <div class="form-group">
                                            <label for="bankSelect">Chọn Ngân hàng:</label>
                                            <select id="bankSelect" name="bankSelect" class="form-control">
                                                <option value="">Chọn ngân hàng</option>
                                                @foreach ($bankList as $bank)
                                                    <option value="{{ $bank['code'] }}">{{ $bank['name'] }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="bankSelectError" style="display: none;">
                                                Vui lòng chọn ngân hàng.</div>
                                        </div>

                                        <!-- Thông tin liên hệ khác -->
                                        <div class="form-group">
                                            <label for="user_phone" class="form-label">Số điện thoại</label>
                                            <input type="text"
                                                class="form-control @error('user_phone') is-invalid @enderror"
                                                id="user_phone" name="user_phone">
                                            @error('user_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" id="email"
                                                name="email">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Lý do hủy -->
                                        <div class="form-group">
                                            <label for="reason" class="form-label">Lý do hoàn tiền</label>
                                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason"></textarea>
                                            @error('reason')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Nút Xác Nhận Hủy -->
                                        <br>
                                        <div class="form-group text-end">
                                            <button type="submit" class="btn-danger">Xác Nhận Hủy</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form hủy (ẩn mặc định) -->
                    <div id="cancelFormContainer" style="display: none;">
                        <form id="cancelForm" method="POST" action="{{ route('client.cancel.reservation') }}">
                            @csrf
                            <input type="hidden" name="reservation_id" id="reservation_id">
                            <label for="cancelled_reason">Lý do hủy:</label>
                            <textarea name="cancelled_reason" id="cancelled_reason" rows="4" required></textarea>
                            <br>
                            <button type="submit">Xác nhận hủy</button>
                            <button type="button" id="cancelButton">Hủy</button>
                        </form>
                    </div>





                </div>

                {{-- <div id="paymentSection" class="content-section" style="display:none;">
                        <h3>Phương thức thanh toán</h3>
                    </div> --}}
            </div>
        </div>
    </div>
    </div>
@endsection
@section('js')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
    // Xử lý khi nhấn vào nút "Hủy"
 

    // Khởi tạo Select2 cho danh mục
    $('#bankSelect').select2({
        placeholder: "Chọn ngân hàng",
        allowClear: true
    });
}); --}}


    {{-- </script>


                    document.getElementById('saveButton').style.display = 'none';

                    alert('Cập nhật thành công!');
                } else {
                    alert('Cập nhật thất bại, vui lòng thử lại!');
                }
            } catch (error) {
                console.error('Lỗi:', error);
                alert('Đã xảy ra lỗi, vui lòng thử lại sau.');
            }
        }
    </script> --}}
    {{-- edit thông tin đặt chỗ  --}}
    <script>
        $(document).ready(function() {
            var token = $('meta[name="csrf-token"]').attr('content');
            // Cấu hình cho AJAX để sử dụng CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            // Khi người dùng thay đổi số lượng khách
            $(".guest_count").on("input", function() {
                let guestCount = $(this).val();
                let depositAmount = 0;
                let depositSection = $(".deposit-section");
                let depositMessage = $(".deposit-warning");
                let depositInput = $(".deposit_amount");
                const qrSection = depositSection.find(".qr-section");
                const qrCode = qrSection.find(".qr-code");

                // Tạo URL QR Code (thay đổi URL theo dịch vụ QR bạn sử dụng)
                const qrPaymentURL =
                    `https://api.qrserver.com/v1/create-qr-code/?data=Thanh+toan+${depositAmount}+VND&size=200x200`;
                qrCode.attr("src", qrPaymentURL);

                // Hiển thị QR Code
                qrSection.show();

                // Nếu số lượng khách >= 6, hiển thị tiền cọc và thông báo
                if (guestCount >= 6) {
                    depositAmount = guestCount * 100000;
                    // depositWarning.textContent =
                    //         `Số lượng khách của bạn cần đặt cọc ${depositAmount.toLocaleString('vi-VN')} VNĐ.`;
                    //     depositSection.style.display = 'block';
                    depositMessage.text(
                        `Số lượng khách của bạn cần đặt cọc ${depositAmount.toLocaleString('vi-VN')} VNĐ.`
                    );
                    depositSection.show(); // Hiển thị ô tiền cọc
                    depositInput.val(depositAmount);
                } else {
                    depositSection.hide(); // Ẩn ô tiền cọc
                    depositMessage.text(''); // Xóa thông báo
                    qrSection.hide();
                    depositInput.val('');
                }
            });
            $(".edit-reservation-btn").on("click", function() {
                $("#reservation-edit-form").modal("show");
                const reservationId = $(this).data('id');
                // $(".reservation-edit-form").hide();
                // $(".details-" + reservationId).hide(); // Ẩn thông tin chi tiết
                $(".reservation-edit-form-" + reservationId).show();
            });

            // Hành động khi submit

            $(document).on("submit", ".edit-reservation", function(e) {
                e.preventDefault(); // Ngăn form gửi ngay lập tức
                const form = $(this);
                const reservationId = form.data('id'); // Lấy ID đơn đặt bàn
                const oldPeopleCount = form.data('old-people-count'); // Số lượng người ban đầu
                const guest_count = parseInt(form.find('input[name="guest_count"]')
                    .val()); // Số lượng khách mới
                const user_name = form.find('input[name="user_name"]').val(); // Tên khách hàng
                const user_phone = form.find('input[name="user_phone"]').val(); // Số điện thoại
                const reservation_date = form.find('input[name="reservation_date"]').val(); // Ngày đặt bàn
                const reservation_time = form.find('input[name="reservation_time"]').val(); // Giờ đặt bàn
                const deposit_amount = parseInt(form.find('input[name="deposit_amount"]').val());
                let depositSection = $(".deposit-section");
                const qrSection = depositSection.find(".qr-section");
                const qrCode = qrSection.find(".qr-code");
                let reservation = {
                    reservationId,
                    guest_count,
                    user_name,
                    user_phone,
                    reservation_date,
                    reservation_time,
                    deposit_amount
                };

                let oldDeposit = oldPeopleCount * 100000; // Cọc ban đầu
                let newDeposit = guest_count * 100000; // Cọc mới
                // Kiểm tra số lượng khách và yêu cầu cọc

                if ((guest_count >= 6 && oldPeopleCount < 6) || guest_count >= 6) {
                    reservation.deposit_amount = newDeposit;
                    Swal.fire({
                        title: 'Đang chờ thanh toán',
                        html: 'Vui lòng quét mã thanh toán...',
                        imageUrl: `https://img.vietqr.io/image/MB-0964236835-compact2.png?amount=${reservation.deposit_amount}&addInfo=Thanh Toan Coc Don Dat Bat ${reservationId}`,
                        imageWidth: 400,
                        imageHeight: 450,
                        showConfirmButton: false,
                        showCloseButton: true,
                        didOpen: () => {
                            Swal.showLoading();
                            setTimeout(() => {}, 3000);
                        }
                    });
                    var checkInterval = 1000;
                    var delayBeforeStart = 5000;
                    var desiredAmount = reservation.deposit_amount;
                    var desiredDescription = `Thanh Toan Coc Don Dat Bat ${reservationId}`;
                    var transactionFound = false;
                    var intervalId;

                    setTimeout(function() {
                        intervalId = setInterval(function() {
                            if (!transactionFound) {
                                checkTransaction();
                            }
                        }, checkInterval);
                    }, delayBeforeStart);

                    function checkTransaction() {
                        const url =
                            'https://script.google.com/macros/s/AKfycbwsNblgurg5Wig7qUO0TNmDmwlJocExVGzMR5wCacLO1vJvRe9Zq9MW4sjrY0fdIdFv/exec';

                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                const transactions = data.data;
                                let foundTransaction = false;
                                transactions.forEach(transaction => {
                                    if (transaction['Giá trị'] == desiredAmount &&
                                        transaction['Mô tả'].includes(desiredDescription)) {
                                        foundTransaction = true;
                                        Swal.fire({
                                            position: "center",
                                            icon: "success",
                                            title: "Thanh toán thành công",
                                            showConfirmButton: false,
                                            timer: 2000
                                        }).then(() => {
                                            UpdateReser(reservation);
                                        });
                                        clearInterval(intervalId);
                                    }
                                });

                                if (!foundTransaction) {
                                    console.log('Chưa tìm thấy giao dịch phù hợp.');
                                }
                            })
                            .catch(error => {
                                console.error('Có lỗi xảy ra:', error);
                            });
                    }

                } else if (guest_count < 5 && oldPeopleCount >= 6) {
                    // Nếu số lượng khách giảm xuống dưới 5 và ban đầu >= 6, xử lý hoàn trả cọc
                    alert('Số lượng khách giảm xuống dưới 5, bạn sẽ được hoàn trả toàn bộ cọc: ' +
                        oldDeposit + ' VNĐ.');
                    // form.find('.deposit-section').hide();  // Ẩn phần cọc vì không còn yêu cầu cọc
                    reservation.deposit_amount = 0; // Cập nhật cọc mới
                    UpdateReser(reservation);
                    // console.log(reservation);
                } else if (guest_count >= 6 && oldPeopleCount >= 6) {
                    // Nếu số lượng khách thay đổi từ >= 6 xuống <= 6, điều chỉnh cọc
                    if (guest_count !== oldPeopleCount) {
                        let refundAmount = oldDeposit - newDeposit;
                        if (refundAmount > 0) {
                            alert('Số lượng khách giảm xuống, bạn sẽ được hoàn lại cọc: ' + refundAmount +
                                ' VNĐ.');
                            reservation.deposit_amount = oldDeposit - refundAmount; // Cập nhật cọc mới
                            // console.log(reservation);
                        }

                    }
                } else {
                    // Nếu số lượng khách không thay đổi (>= 6 hoặc dưới 5)
                    // alert('ko cọc')
                    reservation.deposit_amount = 0; // Cập nhật cọc mới
                    UpdateReser(reservation);
                }

                // hàm nhận thông tin sau khi kiểm tra
                function UpdateReser(reservation) {
                    // CSRF Token từ meta tag
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        .getAttribute("content");

                    // Gửi request bằng Fetch API
                    fetch(`/member/reservation/update`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                            body: JSON.stringify(reservation)
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response.success) {
                                location.reload();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    text: "Có lỗi xảy ra vui lòng thử lại!"
                                });
                            }
                        })
                        .catch(error => {
                            console.log("Lỗi:", error);
                            Swal.fire({
                                icon: "error",
                                text: "Có lỗi xảy ra vui lòng thử lại!"
                            });
                        });
                }

                // đóng popup cọc
                $(document).ready(function() {
                    $('.closePopup').click(function() {
                        $('.alertModal').hide();
                        $('.modal-backdrop').hide();
                    });
                });


            });




            document.addEventListener('DOMContentLoaded', function() {
                const guestCountInput = document.getElementById('guest_count');
                const depositSection = document.getElementById('deposit-section');
                const depositAmountInput = document.getElementById('deposit_amount');
                const depositWarning = document.getElementById('deposit-warning');

                guestCountInput.addEventListener('input', function() {
                    const guestCount = parseInt(guestCountInput.value, 10);

                    if (guestCount >= 6) {
                        const depositAmount = guestCount * 100000;
                        depositAmountInput.value = depositAmount.toLocaleString('vi-VN') + ' VNĐ';
                        depositWarning.textContent =
                            `Số lượng khách của bạn cần đặt cọc ${depositAmount.toLocaleString('vi-VN')} VNĐ.`;
                        depositSection.style.display = 'block';
                    } else {
                        depositAmountInput.value = '';
                        depositWarning.textContent = '';
                        depositSection.style.display = 'none';
                    }
                });

            });



            // Nút "Hủy" để thoát chỉnh sửa
            $(".cancel-edit").on("click", function() {
                $("#reservation-edit-form").modal("hide");
                const reservationId = $(this).data(
                    'id');
                // $(".reservation-edit-form-" + reservationId).hide();

                // $(".details-" + reservationId).show();
            });
        });
    </script>
    {{-- validate --}}
    <script>
        $(document).ready(function() {
            $(".edit-reservation").on("submit", function(e) {
                e.preventDefault();

                let isValid = true;

                // Validate Tên khách hàng
                const userName = $(this).find(".user_name").val().trim();
                const nameRegex = /^[a-zA-ZÀ-ỹ\s]+$/;
                if (!userName) {
                    isValid = false;
                    $("#error-customer_name").text("Tên khách hàng không được bỏ trống.");
                } else {
                    $("#error-customer_name").text("Tên khách hàng phải bằng chữ");
                }

                // Validate Số điện thoại
                const userPhone = $(this).find(".user_phone").val().trim();
                const phoneRegex = /^\+?\d{10,15}$/;
                if (!userPhone) {
                    isValid = false;
                    $("#error-user_phone").text("Số điện thoại không được bỏ trống.");
                } else if (!phoneRegex.test(userPhone)) {
                    isValid = false;
                    $("#error-user_phone").text("Số điện thoại không đúng định dạng.");
                } else {
                    $("#error-user_phone").text("");
                }
                // Validate Số lượng khách
                const guestCount = $(this).find(".guest_count").val();
                if (!guestCount || guestCount < 1 || guestCount > 50) {
                    isValid = false;
                    $("#error-guest_count").text("Số lượng khách phải từ 1 đến 50.");
                } else {
                    $("#error-guest_count").text("");
                }

                // Validate Ngày đặt bàn
                const reservationDate = $(this).find(".reservation_date").val();
                if (!reservationDate) {
                    isValid = false;
                    $("#error-reservation_date").text("Ngày đặt bàn không được bỏ trống.");
                } else {
                    $("#error-reservation_date").text("");
                }

                // Validate Giờ đặt bàn
                const reservationTime = $(this).find(".reservation_time").val();
                if (!reservationTime) {
                    isValid = false;
                    $("#error-reservation_time").text("Giờ đặt bàn không được bỏ trống.");
                } else {
                    const [hour, minute] = reservationTime.split(":").map(Number);
                    const isValidTime =
                        (hour > 11 || (hour === 11 && minute >= 0)) &&
                        (hour < 20 || (hour === 20 && minute <= 30));
                    if (!isValidTime) {
                        isValid = false;
                        $("#error-reservation_time").text("Giờ đặt bàn phải từ 11:00 đến 20:30.");
                    } else {
                        $("#error-reservation_time").text("");
                    }
                }

                if (isValid) {
                    this.submit();
                }
            });
        });
    </script>

    {{-- rating --}}
    <script>
        $(document).ready(function() {
            // Hiển thị form đánh giá khi nhấn nút "Đánh giá"
            $(".rate-reservation-btn").on("click", function() {
                const reservationId = $(this).data("id");

                // Đóng tất cả các form đang mở
                $(".reservation-rating-form").hide();
                $(".reservation-review-details").hide();

                // Hiển thị form tương ứng với reservationId
                $(".reservation-rating-form-" + reservationId).show();
            });



            // Xử lý đánh giá sao
            $(".star-rating")
                .on("mouseover", function() {
                    let ratingValue = $(this).data("value");
                    const parentForm = $(this).closest(".rating-form");
                    parentForm.find(".star-rating").each(function() {
                        if ($(this).data("value") <= ratingValue) {
                            $(this).removeClass("bi-star").addClass("bi-star-fill text-warning");
                        } else {
                            $(this).removeClass("bi-star-fill text-warning").addClass("bi-star");
                        }
                    });
                })
                .on("click", function() {
                    let ratingValue = $(this).data("value");
                    $(this).closest(".rating-form").find(".rating-value").val(
                        ratingValue); // Gán giá trị rating
                });

            // Gửi đánh giá qua AJAX
            $(".rating-form").on("submit", function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let reservationId = $(this).data("id");

                $.ajax({
                    url: `/member/reservation/${reservationId}/rate`,
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        // alert("Đánh giá thành công!");
                        // location.reload();
                        // alert("Đánh giá thành công!");

                        alert("Đánh giá thành công!");

                        // Hiển thị lại đánh giá
                        const reviewSummary = $(".review-summary-" + reservationId);
                        reviewSummary.find(".stars-summary").html(
                            "★".repeat(response.rating) + "☆".repeat(5 - response.rating)
                        );
                        reviewSummary.find(".rating-number-summary").text(response
                            .rating); // Hiển thị số sao
                        reviewSummary.find(".review-content-summary").text(response.content);

                        // Ẩn form đánh giá
                        $(".reservation-rating-form-" + reservationId).hide();

                        // Hiển thị đánh giá và ngăn đánh giá lại
                        reviewSummary.show();
                        $(".rate-reservation-btn[data-id='" + reservationId + "']").hide();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $(".text-danger").text("");
                        for (let key in errors) {
                            $(`#error-${key}`).text(errors[key][0]);
                        }
                    },
                });
            });

            // Đóng form khi nhấn nút hủy
            $(".rating-cancel").on("click", function() {
                const reservationId = $(this).data("id");
                $(".reservation-rating-form-" + reservationId).hide();
            });
        });
    </script>
    <script>
        function calculateRefund(depositAmount, reservationDateStr, reservationTimeStr) {
            const currentTime = new Date();
            const vietnamTime = new Date(currentTime.toLocaleString("en-US", {
                timeZone: "Asia/Ho_Chi_Minh"
            }));

            const reservationDate = new Date(reservationDateStr);
            const reservationTime = reservationTimeStr.split(":");
            reservationDate.setHours(reservationTime[0], reservationTime[1], 0, 0);


            if (isNaN(reservationDate)) {
                console.log("Lỗi: Thời gian đặt chỗ không hợp lệ.");
                return 0;
            }


            const diffMs = reservationDate - vietnamTime;
            const diffHours = diffMs / (1000 * 60 * 60);

            let refundAmount = 0;

            if (diffHours >= 1) {
                refundAmount = depositAmount; // Hoàn 100% nếu hủy trước 1 giờ
            } else if (diffHours >= -1) {
                refundAmount = depositAmount * 0.5; // Hoàn 50% nếu hủy trong vòng 1 giờ sau giờ ăn
            } else {
                refundAmount = 0; // Không hoàn tiền nếu hủy quá 1 giờ sau giờ ăn
            }

            return refundAmount;

        }
        $(document).ready(function() {
            $('.deleteButton').click(function() {
                var id = this.getAttribute('data-id'); // Lấy ID từ thuộc tính 'data-id'

                Swal.fire({
                    icon: "question",
                    title: "Xác nhận hủy",
                    text: "Bạn có chắc chắn muốn hủy đặt bàn không?",
                    input: 'text', // Thêm input vào SweetAlert
                    inputPlaceholder: 'Lý do hủy (bắt buộc)', // Placeholder cho input
                    showCancelButton: true,
                    confirmButtonText: "Xác nhận",
                    cancelButtonText: "Hủy",
                    preConfirm: (inputValue) => {
                        // Kiểm tra xem lý do hủy có trống không
                        if (!inputValue || inputValue.trim() === "") {
                            Swal.showValidationMessage(
                                'Vui lòng nhập lý do hủy!'); // Hiển thị thông báo lỗi
                            return false; // Dừng lại nếu không nhập lý do
                        }
                        return inputValue; // Trả về lý do hủy hợp lệ
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        var reason = result.value; // Lấy lý do từ input

                        // Gọi AJAX hoặc thực hiện hành động xóa
                        $.ajax({
                            url: '{{ route('client.cancel.reservationpopup') }}',
                            type: 'POST',
                            data: {
                                id: id,
                                reason: reason, // Gửi lý do hủy trong data
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: response.icon,
                                    title: response.title,
                                    text: response.message
                                }).then(() => {
                                    if (response.success) {
                                        location.reload(); // Tải lại trang
                                    }
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Lỗi",
                                    text: "Có lỗi xảy ra!"
                                });
                            }
                        });
                    } else {
                        // Người dùng hủy bỏ hành động
                        Swal.fire({
                            icon: "info",
                            title: "Thông báo",
                            text: "Hành động đã bị hủy."
                        });
                    }
                });
            });
        });




        //         $(document).ready(function () {
        //     $('.deleteButton').click(function () {
        //         // Lấy ID đặt bàn từ thuộc tính 'data-id'
        //         var id = this.getAttribute('data-id');

        //         // Hiển thị form và gán ID đặt bàn vào input hidden
        //         $('#reservation_id').val(id); // Gán ID vào input hidden
        //         $('#cancelFormContainer').show(); // Hiển thị form
        //     });

        //     // Sự kiện nút Hủy trong form (ẩn form khi không muốn hủy nữa)
        //     $('#cancelButton').click(function () {
        //         $('#cancelFormContainer').hide(); // Ẩn form
        //     });
        // });


        // modal hủy
        function openCancelModal(reservationId, depositAmount, reservationDateStr, reservationTimeStr) {
            const refundAmount = calculateRefund(depositAmount, reservationDateStr, reservationTimeStr);

            document.getElementById('reservation_id').value = reservationId;
            document.getElementById('refund_amount').value = refundAmount;

            document.getElementById('refundAmountDisplay').innerText =
                `Số tiền hoàn lại: ${refundAmount.toLocaleString()} VNĐ`;
        }

        document.querySelectorAll('.cancel-btn-new').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.getAttribute('data-reservation-id');
                const depositAmount = parseFloat(this.getAttribute('data-deposit-amount'));
                const reservationTime = this.getAttribute('data-reservation-time');
                const reservationDate = this.getAttribute('data-reservation-date');

                // console.log("Reservation ID:", reservationId);
                // console.log("Deposit Amount:", depositAmount);
                // console.log("Reservation Time:", reservationTime);
                // console.log("Reservation Date:", reservationDate);

                openCancelModal(reservationId, depositAmount, reservationDate, reservationTime);
            });
        });
    </script>
@endsection
