@extends('client.layouts.master')
@section('title', 'Login')

@section('content')
 
@include('client.layouts.component.subheader', [
    'backgroundImage' => 'client/03_images/background/bg-6.jpg',
    'subtitle' => 'Get in',
    'title' => 'Touch',
    'currentPage' => 'Login',
])

<div id="content" class="">
    <div class="login-container">
        <h3 class="">Welcome Back</h3>


        <form id="loginForm" method="POST" action="">
            @csrf
            <div class="form-group mb-3">
                <label for="phoneNumber">Họ tên </label>
                <div class="input-group">
                    <input type="text" class="form-control" id="phoneNumber" name="user_name" placeholder="Nhập họ tên" required>
                </div>
                <label for="phoneNumber">Số điện thoại</label>
                <div class="input-group">
                    <span class="input-group-text">🇺🇸 +1</span>
                    <input type="text" class="form-control" id="phoneNumber" name="user_name" placeholder="Nhập số điện thoại" required>
                </div>
            </div>

            <button type="submit" class="btn btn-warning w-100">Submit</button>
        </form>

        <form id="otpForm" method="POST" action="" style="">
            @csrf
            <div class="form-group text-center my-3">
                <label>One Time Password</label>
                <div>
                    <input type="text" maxlength="1" class="otp-input" name="otp1" id="otp1">
                    <input type="text" maxlength="1" class="otp-input" name="otp2" id="otp2">
                    <input type="text" maxlength="1" class="otp-input" name="otp3" id="otp3">
                    <input type="text" maxlength="1" class="otp-input" name="otp4" id="otp4">
                </div>
            </div>
        
            <div class="d-flex justify-content-between mb-3">
                <small><a href="#" id="resendOtp">Resend OTP in <span id="timer">05:15</span></a></small>
            </div>
        
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="terms">
                <label class="form-check-label" for="terms">
                    I agree to <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                </label>
            </div>
        
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
        

        <div class="text-center my-3">
            <p>Don't have an account? <a href="#">Sign Up</a></p>
            <a href="#" class="btn btn-outline-secondary w-100">Continue as Guest</a>
        </div>
    </div>
</div>
<script>
    // Lấy tất cả các input của OTP
    const inputs = document.querySelectorAll('.otp-input');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (event) => {
            const currentInput = event.target;
            const nextInput = inputs[index + 1];

            // Kiểm tra nếu có ký tự và độ dài chính xác
            if (currentInput.value.length === 1 && nextInput) {
                nextInput.focus();
            }
        });

        input.addEventListener('keydown', (event) => {
            const currentInput = event.target;
            const prevInput = inputs[index - 1];

            // Xử lý phím backspace: Nếu ô hiện tại rỗng, quay lại ô trước đó
            if (event.key === 'Backspace' && currentInput.value === '') {
                if (prevInput) {
                    prevInput.focus();
                }
            }
        });
    });
</script>
@endsection
