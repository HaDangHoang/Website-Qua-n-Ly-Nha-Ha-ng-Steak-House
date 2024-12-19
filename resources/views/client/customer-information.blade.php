@extends('client.layouts.master')
@section('title', 'Booking')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-2.jpg',
        'subtitle' => 'Make a',
        'title' => 'Reservation',
        'currentPage' => 'Booking',
    ])
    @if (session('err'))
        <script>
            Swal.fire({
                title: "Có lỗi xảy ra!",
                text: "{{ session('err') }}",
                icon: "warning",
                showCloseButton: true,
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
            })
        </script>
    @endif
    <div id="content" class="no-bottom no-top">
        <section id="section-book-form">
            <div class="container border">
                <div class="row text-center">
                    <div class="col bg-secondary p-2 text-uppercase">
                        <h6><a href="{{ route('booking.client') }}">Bước 1 - Chọn giờ ăn</a></h6>
                    </div>
                    <div class="col bg-warning p-2 text-uppercase">
                        <h6>Bước 2 - Thông tin khách hàng</h6>
                    </div>
                </div>
                <div class="row bg-dark ">
                    <h6 class="text-center mt-4 mb-2">Mời bạn nhập thông tin để đặt bàn</h6>
                    <form id="booking-form" action="{{ route('createReservation.client') }}" method="POST">
                        @csrf
                        <div class="row m-4 d-flex justify-content-center">
                            <div class="col">
                                <label for="user_name">Tên khách hàng*</label>
                                <input class="form-control" type="text" name="user_name" id="user_name"
                                    placeholder="Nhập tên khách hàng"
                                    value="{{ old('user_name') ?? ($data['user_name'] ?? null) }}">
                                @error('user_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="user_phone">Số điện thoại*</label>
                                <input class="form-control" type="text" name="user_phone" id="user_phone"
                                    placeholder="Nhập số điện thoại"
                                    value="{{ old('user_phone') ?? ($data['user_phone'] ?? null) }}">
                                @error('user_phone')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror


                            </div>

                            <div class="col position-relative">
                                <label for="guest_count">Số người đặt bàn*</label>
                                <input type="number" class="form-control" id="guest_count" name="guest_count"
                                    value="{{ old('guest_count') ?? ($data['guest_count'] ?? 1) }}" min="1"
                                    max="50" required>
                                <div class="invalid-feedback position-absolute">Số người phải từ 1
                                    đến 50 và không được âm.</div>
                                @error('guest_count')
                                    <div class="text-danger position-absolute">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="row m-4 d-flex justify-content-center">
                            <div>
                                <label for="note">Ghi chú thêm</label>
                                <input class="form-control" type="text" name="note" id="note"
                                    placeholder="Nhập ghi chú" value="{{ old('note') ?? ($data['note'] ?? null) }}">
                            </div>
                        </div>
                        @if (isset($date) && isset($time))
                            <input type="hidden" name="reservation_date" value="{{ $date }}">
                            <input type="hidden" name="reservation_time" value="{{ $time }}">
                        @else
                            <div class="alert alert-danger">Không có ngày hoặc giờ đặt bàn! Vui lòng quay lại bước trước.
                            </div>
                        @endif
                        <!-- Căn chỉnh reCAPTCHA ra giữa -->
                        <div id="recaptcha-container" class="mt-3"></div>


                        <div class="row m-4 d-flex justify-content-center">
                            <div class="col-2">
                                <a href="{{ route('booking.client') }}" class="text-secondary">Quay lại</a>
                            </div>
                            <div class="col-2">


                                <button type="button" class="btn-line" onclick="sendOTP()">Xác nhận</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    @include('client.layouts.partials.customer-information')

    <!-- Popup OTP -->
    <!-- Popup OTP -->
    <div id="otp-popup" class="overlay" style="display:none;">
        <div class="popup1">
            <h4 style="color: rgb(0, 154, 250)">Nhập mã OTP</h4>
            <div id="otp-timer" class="text-danger"></div> <!-- Hiển thị bộ đếm thời gian -->
            <div class="otp-container">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
                <input type="text" class="otp-input" maxlength="1">
            </div>
            <div id="otp-error-message" class="text-danger" style="margin-top: 10px; display: none;"></div>
            <!-- Thông báo lỗi -->
            <div class="button-container">
                <button id="closePopupButton" class="btn-dark">Đóng</button>
                <button type="button" class="btn-success" onclick="verifyCode()">Xác thực OTP</button>
                <button id="resendOtpButton" class="btn-primary" onclick="resendOTP()" style="display: none;">Gửi lại mã
                    OTP</button>
            </div>
        </div>
    </div>


    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .button-container {
            display: flex;
            justify-content: space-around;
            /* Đảm bảo các nút được căn đều */
            gap: 15px;
            /* Khoảng cách giữa các nút */
            margin-top: 20px;
        }

        .button-container button {
            padding: 10px 25px;
            /* Đồng nhất kích thước nút */
            border-radius: 5px;
            font-size: 16px;
            /* Đảm bảo kích thước chữ đồng đều */
            cursor: pointer;
            border: none;
            /* Loại bỏ viền mặc định */
            color: white;
            transition: background-color 0.3s ease;
        }

        .button-container .btn-primary {
            background-color: #007bff;
        }

        .button-container .btn-primary:hover {
            background-color: #0056b3;
            /* Màu khi hover */
        }

        .button-container .btn-success {
            background-color: #28a745;
        }

        .button-container .btn-success:hover {
            background-color: #1e7e34;
            /* Màu khi hover */
        }

        .button-container .btn-danger {
            background-color: #dc3545;
        }

        .button-container .btn-danger:hover {
            background-color: #b21f2d;
            /* Màu khi hover */
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake-element {
            animation: shake 0.5s;
        }

        #recaptcha-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .popup1 {
            background: white;
            padding: 30px;
            /* Tăng khoảng padding */
            border-radius: 15px;
            /* Tăng độ bo góc */
            text-align: center;
            max-width: 500px;
            /* Tăng chiều rộng tối đa */
            width: 90%;
            /* Điều chỉnh phù hợp với kích thước màn hình */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            /* Tạo hiệu ứng nổi */
        }

        .popup1 h4 {
            color: rgb(0, 154, 250);
            font-size: 1.5rem;
            /* Tăng kích thước chữ */
            margin-bottom: 20px;
        }

        .otp-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            /* Tăng khoảng cách giữa các ô nhập */
            margin-bottom: 20px;
        }

        .otp-input {
            width: 50px;
            /* Tăng chiều rộng */
            height: 50px;
            /* Tăng chiều cao */
            font-size: 24px;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 5px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            /* Căn giữa các nút */
            gap: 15px;
            /* Khoảng cách giữa các nút */
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-success {
            background-color: #28a745;
            padding: 10px 20px;
            border-radius: 5px;
        }
    </style>

@endsection

<script type="module">
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
    import {
        getAuth,
        RecaptchaVerifier,
        signInWithPhoneNumber
    } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-auth.js";

    const firebaseConfig = {
        apiKey: "AIzaSyDRiOTYCQgDDemeF7QCunNMvlhPwmhh9Tc",
        authDomain: "datn-5b062.firebaseapp.com",
        projectId: "datn-5b062",
        storageBucket: "datn-5b062.appspot.com",
        messagingSenderId: "630325973482",
        appId: "1:630325973482:web:18498f0416b4123f05e293",
        measurementId: "G-HRQ5XG4ELN"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    let otpTimer; // Bộ đếm thời gian OTP

    window.onload = function() {
        renderRecaptcha();
        document.getElementById('closePopupButton').onclick = closePopup;
    }

    // Render reCAPTCHA
    function renderRecaptcha() {
        window.recaptchaVerifier = new RecaptchaVerifier('recaptcha-container', {
            'size': 'normal',
            'callback': function(response) {},
            'expired-callback': function() {}
        }, auth);

        recaptchaVerifier.render().then(() => {
            console.log('Recaptcha rendered');
        }).catch(error => console.error("Error rendering recaptcha:", error));
    }
    // Hiển thị thông báo lỗi dưới các ô nhập OTP
    function showOTPError(message) {
        const errorDiv = document.getElementById('otp-error-message');
        errorDiv.innerText = message;
        errorDiv.style.display = 'block';

        // Tự động ẩn thông báo sau 5 giây
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 3000);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
    const userNameInput = document.getElementById('user_name');
    const userPhoneInput = document.getElementById('user_phone');
    const guestCountInput = document.getElementById('guest_count');
    const confirmButton = document.querySelector('button[type="button"][onclick="sendOTP()"]');

    // Validation cho tên
    function validateName() {
        const nameValue = userNameInput.value.trim();
        let errorMessage = '';

        if (nameValue === '') {
            errorMessage = 'Tên khách hàng không được để trống.';
        } else if (nameValue.length < 2) {
            errorMessage = 'Tên khách hàng phải có ít nhất 2 ký tự.';
        } else if (nameValue.length > 50) {
            errorMessage = 'Tên khách hàng không được vượt quá 50 ký tự.';
        } else if (!/^[a-zA-ZÀ-ỹ\s]+$/.test(nameValue)) {
            errorMessage = 'Tên khách hàng chỉ được chứa chữ cái và khoảng trắng.';
        }

        // Tạo hoặc cập nhật thông báo lỗi
        let errorElement = userNameInput.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
            errorElement = document.createElement('div');
            errorElement.classList.add('invalid-feedback', 'position-absolute');
            userNameInput.parentNode.insertBefore(errorElement, userNameInput.nextSibling);
        }

        if (errorMessage) {
            userNameInput.classList.add('is-invalid', 'shake-input');
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
            return false;
        } else {
            userNameInput.classList.remove('is-invalid', 'shake-input');
            errorElement.style.display = 'none';
            return true;
        }
    }

    // Validation cho số điện thoại
    function validatePhone() {
        const phoneValue = userPhoneInput.value.trim();
        let errorMessage = '';

        if (phoneValue === '') {
            errorMessage = 'Số điện thoại không được để trống.';
        } else if (!/^(0[1-9][0-9]{8})$/.test(phoneValue)) {
            errorMessage = 'Số điện thoại. Phải là 10 chữ số và bắt đầu bằng 0.';
        }

        // Tạo hoặc cập nhật thông báo lỗi
        let errorElement = userPhoneInput.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
            errorElement = document.createElement('div');
            errorElement.classList.add('invalid-feedback', 'position-absolute');
            userPhoneInput.parentNode.insertBefore(errorElement, userPhoneInput.nextSibling);
        }

        if (errorMessage) {
            userPhoneInput.classList.add('is-invalid', 'shake-input');
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
            return false;
        } else {
            userPhoneInput.classList.remove('is-invalid', 'shake-input');
            errorElement.style.display = 'none';
            return true;
        }
    }

    // Validation số lượng khách
    function validateGuestCount() {
        const guestCount = parseInt(guestCountInput.value, 10);
        
        if (guestCount < 1 || guestCount > 50) {
            guestCountInput.classList.add('is-invalid', 'shake-input');
            return false;
        } else {
            guestCountInput.classList.remove('is-invalid', 'shake-input');
            return true;
        }
    }

    // Validation khi nhấn nút Xác nhận
window.sendOTP = function() {
    const isNameValid = validateName();
    const isPhoneValid = validatePhone();
    const isGuestCountValid = validateGuestCount();

    // Nếu có bất kỳ trường nào không hợp lệ
    if (!isNameValid) {
        userNameInput.focus();
        return;
    }

    if (!isPhoneValid) {
        userPhoneInput.focus();
        return;
    }

    if (!isGuestCountValid) {
        guestCountInput.focus();
        return;
    }

    // Kiểm tra xem reCAPTCHA đã được verify chưa
    if (grecaptcha && grecaptcha.getResponse().length === 0) {
        // Xóa bất kỳ thông báo lỗi hiện tại nào
        const existingErrorElement = document.getElementById('recaptcha-error-message');
        if (existingErrorElement) {
            existingErrorElement.remove();
        }

        // Tạo phần tử thông báo lỗi mới
        const recaptchaContainer = document.getElementById('recaptcha-container');
        const errorElement = document.createElement('div');
        errorElement.id = 'recaptcha-error-message';
        errorElement.classList.add('text-danger', 'text-center', 'mb-2', 'shake-element');
        errorElement.textContent = 'Vui lòng xác thực reCAPTCHA trước khi tiếp tục.';
        
        // Chèn thông báo lỗi ngay trước hoặc sau container reCAPTCHA
        recaptchaContainer.parentNode.insertBefore(errorElement, recaptchaContainer);

        // Thêm hiệu ứng nhấp nháy
        setTimeout(() => {
            errorElement.classList.remove('shake-element');
        }, 500);
        
        return;
    }

    // Tiếp tục logic gửi OTP
    let phoneNumber = userPhoneInput.value.trim();
    phoneNumber = phoneNumber.startsWith('0') ? '+84' + phoneNumber.slice(1) : phoneNumber;
    
    const appVerifier = window.recaptchaVerifier;

    signInWithPhoneNumber(auth, phoneNumber, appVerifier)
        .then((confirmationResult) => {
            // Xóa thông báo lỗi nếu có
            const existingErrorElement = document.getElementById('recaptcha-error-message');
            if (existingErrorElement) {
                existingErrorElement.remove();
            }

            window.confirmationResult = confirmationResult;
            document.getElementById("otp-popup").style.display = "flex";
            startOTPTimer();
        }).catch(error => {
            console.error("Error sending OTP:", error);
            showOTPError("Có lỗi xảy ra khi gửi OTP! Vui lòng thử lại.");
        });
};

    // Thêm sự kiện input để validation liên tục
    userNameInput.addEventListener('input', validateName);
    userPhoneInput.addEventListener('input', validatePhone);
    guestCountInput.addEventListener('input', validateGuestCount);
});

    window.sendOTP = function() {
        let phoneNumber = document.getElementById("user_phone").value.trim();

        if (phoneNumber.startsWith("0")) {
            phoneNumber = '+84' + phoneNumber.slice(1); // Chuyển đổi '0' thành '+84'
        }

        if (!phoneNumber.match(/^\+\d{1,15}$/)) {
            showOTPError("Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại đúng định dạng.");
            return;
        }

        const appVerifier = window.recaptchaVerifier;

        signInWithPhoneNumber(auth, phoneNumber, appVerifier)
            .then((confirmationResult) => {
                window.confirmationResult = confirmationResult;
                document.getElementById("otp-popup").style.display = "flex"; // Hiển thị popup OTP
                startOTPTimer(); // Bắt đầu bộ đếm thời gian
            }).catch(error => {
                console.error("Error sending OTP:", error);
                showOTPError("Có lỗi xảy ra khi gửi OTP! Vui lòng thử lại.");
            });
    };

    // Khởi động bộ đếm thời gian OTP
    
    let otpStartTime; 

function startOTPTimer() {
    // Lưu thời điểm bắt đầu
    otpStartTime = Date.now();
    let timeLeft = 60;
    document.getElementById("otp-timer").innerText = `Thời gian còn lại: ${timeLeft} giây`;

    // Clear timer cũ nếu tồn tại
    if (otpTimer) {
        clearInterval(otpTimer);
    }

    otpTimer = setInterval(() => {
        // Tính toán thời gian còn lại dựa trên thời gian bắt đầu
        const currentTime = Date.now();
        const elapsedTime = Math.floor((currentTime - otpStartTime) / 1000);
        timeLeft = Math.max(60 - elapsedTime, 0);

        document.getElementById("otp-timer").innerText = `Thời gian còn lại: ${timeLeft} giây`;

        if (timeLeft <= 0) {
            clearInterval(otpTimer);
            document.getElementById("otp-timer").innerText = "Hết thời gian! Vui lòng gửi lại mã OTP.";
            document.getElementById("resendOtpButton").style.display = "block";
            document.querySelector(".btn-success").style.display = "none";
            disableOTPInputs(true);
        }
    }, 1000);
}

    // Vô hiệu hóa hoặc kích hoạt lại các ô nhập OTP
    function disableOTPInputs(disable) {
        document.querySelectorAll('.otp-input').forEach(input => {
            input.disabled = disable;
        });
    }

    // Gửi lại mã OTP và làm mới popup
    window.resendOTP = function() {
        // Làm mới trạng thái popup
        document.getElementById("resendOtpButton").style.display = "none"; // Ẩn nút "Gửi lại mã OTP"

        // Reset các ô nhập mã OTP
        document.querySelectorAll('.otp-input').forEach(input => {
            input.value = ''; // Làm mới các ô nhập OTP
            input.disabled = false; // Bật lại các ô nhập liệu
        });

        // Đảm bảo nút "Xác thực OTP" luôn có thể nhấn
        document.querySelector(".btn-success").disabled = false;
        document.querySelector(".btn-success").style.display = "inline-block"; // Hiển thị lại nút xác thực

        // Xóa thời gian cũ
        document.getElementById("otp-timer").innerText = "";

        // Gửi lại mã OTP
        sendOTP();

        // Khởi động lại bộ đếm thời gian
        startOTPTimer();

        // Hiển thị lại popup ban đầu (bao gồm 6 ô nhập mã trống và nút gửi lại mã)
        document.getElementById("otpPopup").style.display = "block"; // Hiển thị lại popup
    }

    // Xác thực mã OTP
    window.verifyCode = function() {
        let otpCode = '';
        document.querySelectorAll('.otp-input').forEach(input => otpCode += input.value);

        if (!otpCode || otpCode.length !== 6) {
            showOTPError("Mã OTP không hợp lệ. Vui lòng nhập đủ 6 ký tự.");
            return;
        }

        window.confirmationResult.confirm(otpCode).then((result) => {
            fetch('{{ route('storeOtpSession') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    otpVerified: true
                })
            }).then(() => {
                document.getElementById("booking-form").submit();
            });
        }).catch(() => {
            showOTPError("Mã OTP không đúng! Vui lòng thử lại.");
        });
    };

    // Đóng popup OTP
   // Đóng popup OTP và reset toàn bộ trạng thái
function closePopup() {
    // Dừng bộ đếm thời gian
    if (otpTimer) {
        clearInterval(otpTimer);
    }

    // Ẩn popup
    document.getElementById("otp-popup").style.display = "none";

    // Reset các ô nhập OTP
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach(input => {
        input.value = ''; // Xóa giá trị
        input.disabled = false; // Bảo đảm các ô input được kích hoạt
    });

    // Reset thời gian và thông báo
    const otpTimerElement = document.getElementById("otp-timer");
    if (otpTimerElement) {
        otpTimerElement.innerText = ""; // Xóa thời gian
    }

    // Ẩn thông báo lỗi
    const errorMessageElement = document.getElementById('otp-error-message');
    if (errorMessageElement) {
        errorMessageElement.style.display = 'none';
        errorMessageElement.innerText = '';
    }

    // Khôi phục trạng thái ban đầu của các nút
    const resendOtpButton = document.getElementById('resendOtpButton');
    const verifyButton = document.querySelector('.btn-success');
    
    if (resendOtpButton) {
        resendOtpButton.style.display = 'none';
    }
    
    if (verifyButton) {
        verifyButton.style.display = 'inline-block';
        verifyButton.disabled = false;
    }
}

// Thêm sự kiện cho nút Đóng
document.getElementById('closePopupButton').addEventListener('click', closePopup);
</script>
