<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng Nhập với OTP</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>

    <style>
        body {
            font-family: 'Merriweather', serif;
            margin: 0;
            padding: 0;
            background-image: url('/client/03_images/background/bg-side-11.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* Điều chỉnh độ sáng ở đây */
            z-index: 1;
            /* Đảm bảo overlay nằm dưới popup */
            pointer-events: none;
            /* Không ảnh hưởng đến thao tác người dùng */
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px 60px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 400px;
            position: relative;
            z-index: 2;
            /* Đảm bảo popup nằm trên overlay */
        }



        .login-container img {
            width: 100px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 700;
            color: #FFD700;
            text-transform: uppercase;
        }

        #timeout-message {
            color: #FF6347;
            margin-top: 20px;
            font-weight: bold;
            display: none;
        }

        input[type="text"],
        .otp-input {
            width: 100%;
            padding: 14px;
            margin: 15px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 18px;
            box-sizing: border-box;
        }

        .otp-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 20px 0;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #333;
            color: #fff;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #8B4513;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            font-size: 18px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #A0522D;
        }

        #error,
        #sentMessage {
            margin-top: 15px;
            font-size: 20px;
        }

        #error {
            color: #FF6347;
            display: none;
        }

        #sentMessage {
            color: #32CD32;
            display: none;
        }

        #countdown {
            font-size: 18px;
            color: #FFD700;
            margin-top: 20px;
        }

        #recaptcha-container-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            /* Hoặc chiều rộng bạn muốn */
            margin-top: 10px;
            /* Khoảng cách trên */
        }

        #recaptcha-container iframe {
            width: 100% !important;
            /* Đảm bảo chiếm hết chiều rộng */
            min-width: 100%;
            /* Đặt chiều rộng tối thiểu */
            height: auto !important;
            /* Đảm bảo chiều cao co dãn */
        }
    </style>
</head>

<body>

    <div class="background-overlay"></div>
    <div class="login-container">
        <img class="logo" src="client/03_images/logo.png" alt="">
        <h1>Đăng Nhập</h1>
        <div id="timeout-message">Hết thời gian nhập mã OTP hãy Đăng Nhập lại.</div>
        <!-- Thông báo đăng nhập lại ở đây -->

        <div id="phone-popup" style="display: block;">
            <form id="phone-form">
                <input type="text" id="number" name="phone" placeholder="Nhập số điện thoại" required>
                <div id="recaptcha-container-wrapper">
                    <div id="recaptcha-container"></div>
                </div>

                <button type="button" id="send-otp" onclick="sendOTP()">Gửi OTP</button>
            </form>
        </div>

        <div id="otp-popup" style="display: none;">
            <form id="otp-verification-form">
                <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                </div>
                <div id="countdown">Thời gian còn lại: <span id="time">90</span> giây</div>
                <div id="otp-expired-message" style="color: red; display: none;">OTP đã hết hạn. Vui lòng gửi lại mã
                    OTP.</div>
                <button type="button" onclick="verifyCode()">Xác thực</button>
                <div id="resend-otp" style="margin-top: 15px; display: none;">
                    <button type="button" onclick="resendOTP()">Gửi lại OTP</button>
                </div>
            </form>
        </div>


        <div id="error"></div>
        <div id="sentMessage"></div>
    </div>

    <script type="module" defer>
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

        window.onload = function() {
            renderRecaptcha();
        }

        function renderRecaptcha() {
            window.recaptchaVerifier = new RecaptchaVerifier('recaptcha-container', {
                'size': 'normal',
                'callback': function(response) {},
                'expired-callback': function() {}
            }, auth);

            recaptchaVerifier.render().then(function() {
                console.log('Recaptcha rendered');
            }).catch(function(error) {
                console.error("Lỗi recaptcha:", error);
            });
        }

        let countdownTimer;
        let timeRemaining = 60; // Thời gian bắt đầu


        window.sendOTP = function() {
            let phoneNumber = document.getElementById("number").value.trim();
            if (phoneNumber.startsWith("0")) {
                phoneNumber = '+84' + phoneNumber.slice(1);
            }

            if (!phoneNumber.match(/^\+\d{1,15}$/)) {
                document.getElementById("error").innerHTML =
                    "Số điện thoại không hợp lệ. ";
                document.getElementById("error").style.display = "block";
                return;
            }

            const appVerifier = window.recaptchaVerifier;

            signInWithPhoneNumber(auth, phoneNumber, appVerifier)
                .then((confirmationResult) => {
                    window.confirmationResult = confirmationResult;

                    document.getElementById("sentMessage").innerHTML = "OTP đã được gửi!";
                    document.getElementById("sentMessage").style.display = "block";
                    document.getElementById("error").style.display = "none";

                    document.getElementById("otp-popup").style.display = "block";
                    document.getElementById("phone-popup").style.display = "none";


                    startCountdown(); // Bắt đầu đếm ngược
                })
                .catch((error) => {
                    document.getElementById("error").innerHTML = "Có lỗi xảy ra! Vui lòng thử lại.";
                    document.getElementById("error").style.display = "block";
                });
        };


        // Vô hiệu hóa ô OTP khi hết thời gian
        function disableOTPInputs() {
            document.querySelectorAll('.otp-input').forEach(input => {
                input.disabled = true; // Vô hiệu hóa tất cả các ô nhập OTP
            });
        }

        // Khôi phục lại ô OTP khi gửi lại OTP
        function enableOTPInputs() {
            document.querySelectorAll('.otp-input').forEach(input => {
                input.disabled = false; // Kích hoạt lại các ô nhập OTP
                input.value = ''; // Xóa giá trị trong các ô OTP
            });
        }

        // Điều chỉnh lại countdown function để khi hết thời gian sẽ vô hiệu hóa các ô OTP
        // Ẩn nút xác nhận khi hết thời gian
        function hideVerifyButton() {
            document.querySelector('button[onclick="verifyCode()"]').style.display = "none"; // Ẩn nút xác nhận
        }

        // Hiển thị lại nút xác nhận khi gửi lại OTP
        function showVerifyButton() {
            document.querySelector('button[onclick="verifyCode()"]').style.display = "block"; // Hiển thị nút xác nhận
        }

        // Điều chỉnh lại countdown function để khi hết thời gian sẽ ẩn nút xác nhận
        function startCountdown(time = 60) {
            timeRemaining = time; // Đặt lại thời gian đếm ngược
            document.getElementById("time").innerText = timeRemaining;
            document.getElementById("countdown").style.display = "block";
            document.getElementById("timeout-message").style.display = "none";
            document.getElementById("otp-expired-message").style.display = "none";

            // Xóa bộ đếm trước đó (nếu có)
            if (countdownTimer) {
                clearInterval(countdownTimer);
            }

            // Bắt đầu đếm ngược
            countdownTimer = setInterval(() => {
                timeRemaining--;
                document.getElementById("time").innerText = timeRemaining;

                if (timeRemaining <= 0) {
                    clearInterval(countdownTimer);
                    document.getElementById("countdown").style.display = "none";
                    document.getElementById("otp-expired-message").style.display = "block";
                    document.getElementById("resend-otp").style.display = "block";

                    disableOTPInputs(); // Vô hiệu hóa ô OTP khi hết thời gian
                    hideVerifyButton(); // Ẩn nút xác nhận khi hết thời gian
                }
            }, 1000);
        }

        // Gửi lại OTP
        // Gửi lại OTP
        window.resendOTP = function() {
            // Ẩn thông báo lỗi và thông báo hết hạn OTP
            document.getElementById("error").style.display = "none";
            document.getElementById("otp-expired-message").style.display = "none";
            document.getElementById("sentMessage").style.display = "block"; // Hiển thị lại thông báo OTP đã được gửi
            document.getElementById("resend-otp").style.display = "none"; // Ẩn nút Gửi lại OTP

            enableOTPInputs(); // Khôi phục lại các ô OTP
            startCountdown(60); // Khởi động lại đếm ngược với thời gian 60 giây
            sendOTP(); // Gửi lại mã OTP

            showVerifyButton(); // Hiển thị lại nút xác nhận khi gửi lại OTP
        };





        window.verifyCode = function() {
            let otpCode = '';
            document.querySelectorAll('.otp-input').forEach(input => otpCode += input.value);

            console.log("Verifying OTP:", otpCode); // Ghi log mã OTP được xác minh

            window.confirmationResult.confirm(otpCode).then((result) => {
                const phoneNumber = document.getElementById("number").value.trim();
                fetch('{{ route('verify.code') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            phone: phoneNumber,
                            verificationCode: otpCode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect_url;
                        } else {
                            // Hiển thị lỗi nếu OTP không đúng
                            document.getElementById("error").innerHTML = data.message;
                            document.getElementById("error").style.display = "block";
                            document.getElementById("sentMessage").style.display =
                                "none"; // Ẩn thông báo OTP đã gửi
                            console.log("Verification failed:", data
                                .message); // Ghi log khi xác minh thất bại
                        }
                    })
                    .catch(error => {
                        document.getElementById("error").innerHTML = "Có lỗi xảy ra. Vui lòng thử lại.";
                        document.getElementById("error").style.display = "block";
                        document.getElementById("sentMessage").style.display =
                            "none"; // Ẩn thông báo OTP đã gửi
                        console.error("Error during verification:", error); // Ghi log lỗi khi xác minh
                    });
            }).catch((error) => {
                // Hiển thị lỗi nếu OTP không đúng
                document.getElementById("error").innerHTML = "Mã OTP không đúng!";
                document.getElementById("error").style.display = "block";
                document.getElementById("sentMessage").style.display = "none"; // Ẩn thông báo OTP đã gửi
                console.error("Invalid OTP:", error); // Ghi log khi mã OTP không đúng
            });
        };


        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace') {
                    if (input.value === '' && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                }
            });
        });
    </script>
</body>

</html>
