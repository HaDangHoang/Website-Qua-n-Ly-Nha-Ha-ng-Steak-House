<!-- Javascript Files
    ================================================== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Modal POPUP xác nhận hủy --}}
<script>
    $(document).ready(function() {
        // Khi người dùng nhấn nút "Hủy" trên từng đặt chỗ
        $('.cancel-btn').on('click', function() {
            var reservationId = $(this).data('reservation-id'); // Lấy ID đặt chỗ từ data-reservation-id
            $('#cancelModal').data('reservation-id', reservationId).modal('show');
        });

        // Đóng modal khi nhấn nút đóng
        $('.closeCancalledModal').on('click', function() {
            $('#cancelModal').modal('hide'); // Ẩn modal
        });

        $('#cancelForm').on('submit', function(event) {
            event.preventDefault(); // Ngăn chặn gửi form

            // Xóa thông báo lỗi cũ
            $('.invalid-feedback').hide();

            let isValid = true; // Biến kiểm tra tính hợp lệ của form

            // Kiểm tra từng trường
            if (!$('#fullName').val()) {
                $('#fullNameError').show();
                isValid = false;
            }
            if (!$('#bankSelect').val()) {
                $('#bankSelectError').show();
                isValid = false;
            }
            if (!$('#accountNumber').val()) {
                $('#accountNumberError').show();
                isValid = false;
            }
            if (!$('#email').val()) {
                $('#emailError').show();
                isValid = false;
            } else if (!validateEmail($('#email').val())) {
                $('#emailError').text('Vui lòng nhập email hợp lệ.').show();
                isValid = false;
            }
            if (!$('#reason').val()) {
                $('#reasonError').show();
                isValid = false;
            }

            // Nếu tất cả các trường hợp lệ, gửi form
            if (isValid) {
                this.submit(); // Gửi form
            }
        });

        // Khi người dùng bấm vào ngày
        $('.day-selector').click(function() {
            var index = $(this).data('index'); // Lấy chỉ số của ngày được bấm

            // Ẩn tất cả các khung thời gian
            $('.time-slots').hide();

            // Hiển thị khung thời gian của ngày được chọn
            $('#day-' + index).show();

            // Đổi màu chữ cho ngày đã chọn
            $('.day-selector .fw-bold').removeClass('text-warning').addClass('text-light');
            $(this).find('.fw-bold').removeClass('text-light').addClass('text-warning');
        });

        // Khi người dùng chọn khung giờ
        $('.time-slot').click(function() {
            // Bỏ chọn khung giờ trước đó
            $('.time-slot').removeClass('bg-warning');
            $('.time-slot p').removeClass('text-light').addClass(
                'text-warning');

            // Đánh dấu khung giờ được chọn
            $(this).addClass('bg-warning');
            $(this).find('p').removeClass('text-warning').addClass(
                'text-light');

            // Lưu lại khung giờ và ngày được chọn
            selectedTime = $(this).data('time');
            selectedDate = $(this).data('date');
        });

        $('#confirm-button').click(function() {

            function convertDateFormat(dateStr) {
                const [year, month, day] = dateStr.split("-");
                return `${day}/${month}/${year}`;
            }
            if (selectedTime && selectedDate) {
                Swal.fire({
                    icon: "question",
                    html: 'Bạn đã chọn thời gian dùng bữa lúc <b>' + selectedTime +
                        '</b> vào ngày <b>' + convertDateFormat(selectedDate) + '</b>',
                    title: 'Vui lòng xác nhận!',
                    showCancelButton: true,
                    confirmButtonText: "Xác nhận",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = '/customerInformation?date=' + encodeURIComponent(
                                selectedDate) +
                            '&time=' +
                            encodeURIComponent(selectedTime);
                        window.location.href = url;
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    text: "Vui lòng chọn khung giờ!",
                    timer: 4000
                });
            }
        });

        // Hàm kiểm tra định dạng email
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }


    });
</script>


{{-- // Hiệp --}}

<script>
    function toggleReviewInput(reservationId, customerId) {
        const reviewInput = document.getElementById(`review-input-${reservationId}`);
        reviewInput.style.display = reviewInput.style.display === 'none' ? 'block' : 'none';
    }

    function submitReview(reservationId, customerId) {
        // Lấy giá trị từ textarea
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');

        if (!csrfTokenElement) {
            console.error('CSRF token meta tag is missing!');
            alert('CSRF token không tồn tại trong trang. Vui lòng kiểm tra.');
            return;
        }
        const csrfToken = csrfTokenElement.content;

        const textarea = document.getElementById(`review-text-${reservationId}`);
        console.log(textarea.value)
        if (!textarea) {
            console.error(`Textarea with ID review-text-${reservationId} not found.`);
            alert('Có lỗi xảy ra, không tìm thấy ô nhập đánh giá.');
            return;
        }

        const reviewText = textarea.value.trim(); // Lấy giá trị, đồng thời xóa khoảng trắng
        console.log(reviewText)
        // Kiểm tra xem người dùng đã nhập đánh giá chưa
        if (!reviewText) {
            alert('Vui lòng nhập nội dung đánh giá trước khi gửi.');
            return;
        }

        // Gửi request đến server
        fetch('/submit-feedback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        .content // CSRF token cho Laravel
                },
                body: JSON.stringify({
                    reservation_id: reservationId,
                    customer_id: customerId,
                    content: reviewText
                })
            })
            .then(response => {
                // Kiểm tra nếu không phải JSON hợp lệ
                if (!response.ok) {
                    throw new Error(`HTTP status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log(data);

                // Kiểm tra nếu đánh giá được gửi thành công
                if (data.success) {
                    alert('Gửi đánh giá thành công!');

                    // Hiển thị đánh giá trong phần container (nếu có)
                    const reviewContainer = document.getElementById(`review-container-${reservationId}`);
                    if (reviewContainer) {
                        reviewContainer.innerHTML = `<p class="text-success">Đánh giá của bạn: ${reviewText}</p>`;
                    } else {
                        console.warn(`Review container with ID review-container-${reservationId} not found.`);
                    }

                    // Ẩn ô nhập đánh giá
                    toggleReviewInput(reservationId, customerId);

                    // Reset textarea
                    textarea.value = '';
                } else {
                    alert(data.message || 'Không thể gửi đánh giá. Vui lòng thử lại.');
                }
            })
            .catch(error => {
                console.error('Error occurred while submitting review:', error);
                alert('Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
            });
    }
</script>

<script>
    function showSection(sectionId) {
        // Hide all sections first
        document.querySelectorAll('.content-section').forEach(section => {
            section.style.display = 'none';
        });

        // Show the clicked section
        document.getElementById(sectionId).style.display = 'block';
    }

    // edit thông tin

    function toggleEdit() {
        // Ẩn các phần hiển thị và hiện các input để chỉnh sửa
        document.getElementById('displayName').style.display = 'none';
        document.getElementById('inputName').style.display = 'inline';

        document.getElementById('displayPhone').style.display = 'none';
        document.getElementById('inputPhone').style.display = 'inline';

        document.getElementById('displayEmail').style.display = 'none';
        document.getElementById('inputEmail').style.display = 'inline';

        document.getElementById('displayLocation').style.display = 'none';
        document.getElementById('inputLocation').style.display = 'inline';

        // Ẩn nút "Chỉnh sửa thông tin" và hiện nút "Lưu thay đổi"
        document.getElementById('editButton').style.display = 'none';
        document.getElementById('saveButton').style.display = 'inline';
    }

    function saveChanges() {
        // Lấy giá trị từ các input sau khi chỉnh sửa
        const name = document.getElementById('inputName').value;
        const phone = document.getElementById('inputPhone').value;
        const email = document.getElementById('inputEmail').value;
        const location = document.getElementById('inputLocation').value;

        // Gửi dữ liệu đến server bằng Ajax hoặc submit form
        // Dưới đây là ví dụ với Ajax
        fetch('/update-profile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Đừng quên token CSRF
                },
                body: JSON.stringify({
                    name: name,
                    phone: phone,
                    email: email,
                    location: location
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật các giá trị mới vào phần hiển thị
                    document.getElementById('displayName').innerText = name;
                    document.getElementById('displayPhone').innerText = phone;
                    document.getElementById('displayEmail').innerText = email;
                    document.getElementById('displayLocation').innerText = location;

                    // Ẩn các input và hiển thị lại phần thông tin sau khi lưu
                    document.getElementById('inputName').style.display = 'none';
                    document.getElementById('displayName').style.display = 'inline';

                    document.getElementById('inputPhone').style.display = 'none';
                    document.getElementById('displayPhone').style.display = 'inline';

                    document.getElementById('inputEmail').style.display = 'none';
                    document.getElementById('displayEmail').style.display = 'inline';

                    document.getElementById('inputLocation').style.display = 'none';
                    document.getElementById('displayLocation').style.display = 'inline';

                    // Ẩn nút "Lưu thay đổi" và hiển thị lại nút "Chỉnh sửa thông tin"
                    document.getElementById('saveButton').style.display = 'none';
                    document.getElementById('editButton').style.display = 'inline';
                } else {
                    alert('Đã xảy ra lỗi khi cập nhật thông tin.');
                }
            }).catch(error => console.error('Lỗi:', error));
    }


    // login
    document.getElementById('phoneOption').addEventListener('click', function() {
        document.getElementById('phoneOption').classList.add('active');
        document.getElementById('emailOption').classList.remove('active');
    });

    document.getElementById('emailOption').addEventListener('click', function() {
        document.getElementById('phoneOption').classList.remove('active');
        document.getElementById('emailOption').classList.add('active');
    });

    // Optional: Countdown timer for resending OTP
    let timeLeft = 315; // In seconds
    let timerElement = document.getElementById('timer');
    setInterval(function() {
        if (timeLeft <= 0) {
            document.getElementById('resendOtp').style.pointerEvents = 'auto';
        } else {
            timeLeft--;
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            timerElement.innerHTML = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        }
    }, 1000);


    $(document).ready(function() {
        var selectedTime = null;
        var selectedDate = null;

        // Khi người dùng bấm vào ngày
        $('.day-selector').click(function() {
            var index = $(this).data('index'); // Lấy chỉ số của ngày được bấm

            // Ẩn tất cả các khung thời gian
            $('.time-slots').hide();


            // Hiển thị khung thời gian của ngày được chọn
            $('#day-' + index).show();

            // Đổi màu chữ cho ngày đã chọn
            $('.day-selector .fw-bold').removeClass('text-warning').addClass('text-light');
            $(this).find('.fw-bold').removeClass('text-light').addClass('text-warning');
        });

        // Khi người dùng chọn khung giờ
        $('.time-slot').click(function() {
            // Bỏ chọn khung giờ trước đó
            $('.time-slot').removeClass('bg-warning');
            $('.time-slot p').removeClass('text-light').addClass(
                'text-warning');

            // Đánh dấu khung giờ được chọn
            $(this).addClass('bg-warning');
            $(this).find('p').removeClass('text-warning').addClass(
                'text-light');

            // Lưu lại khung giờ và ngày được chọn
            selectedTime = $(this).data('time');
            selectedDate = $(this).data('date');
        });

        $('#confirm-button').click(function() {

            function convertDateFormat(dateStr) {
                const [year, month, day] = dateStr.split("-");
                return `${day}-${month}-${year}`;
            }
            if (selectedTime && selectedDate) {
                Swal.fire({
                    icon: "question",
                    html: 'Bạn đã chọn thời gian dùng bữa lúc <b>' + selectedTime +
                        '</b> vào ngày <b>' + convertDateFormat(selectedDate) + '</b>',
                    title: 'Vui lòng xác nhận!',
                    showCancelButton: true,
                    confirmButtonText: "Xác nhận",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = '/customerInformation?date=' + encodeURIComponent(
                                selectedDate) +
                            '&time=' +
                            encodeURIComponent(selectedTime);
                        window.location.href = url;
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi",
                    text: "Vui lòng chọn khung giờ!",
                    timer: 4000
                });
            }
        });

    });
</script>

<script src="client/js/plugins.js"></script>
<script src="client/js/designesia.js"></script>

<!-- RS5.0 Core JS Files -->
<script src="client/revolution/js/jquery.themepunch.tools.min838f.js?rev=5.0"></script>
<script src="client/revolution/js/jquery.themepunch.revolution.min838f.js?rev=5.0"></script>

<!-- RS5.0 Extensions Files -->
<script src="client/revolution/js/extensions/revolution.extension.video.min.js"></script>
<script src="client/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
<script src="client/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
<script src="client/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
<script src="client/revolution/js/extensions/revolution.extension.actions.min.js"></script>
<script src="client/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
<script src="client/revolution/js/extensions/revolution.extension.migration.min.js"></script>
<script src="client/revolution/js/extensions/revolution.extension.parallax.min.js"></script>



<script>
    jQuery(document).ready(function() {
        // revolution slider
        jQuery("#revolution-slider").revolution({
            sliderType: "standard",
            sliderLayout: "fullscreen",
            delay: 3500,
            navigation: {
                arrows: {
                    enable: true
                }
            },
            parallax: {
                type: "mouse",
                origo: "slidercenter",
                speed: 2000,
                levels: [2, 3, 4, 5, 6, 7, 12, 16, 10, 50],
            },
            spinner: "off",
            gridwidth: 1140,
            gridheight: 600,
            disableProgressBar: "on"
        });
    });
</script>



<!-- Lộc -->
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
        const guestCountInput = document.getElementById('guest_count');

        guestCountInput.addEventListener('input', function() {
            let value = parseInt(guestCountInput.value, 10);

            // Nếu giá trị âm, nhỏ hơn 1 hoặc lớn hơn 50, sửa lại giá trị
            if (value < 1) {
                guestCountInput.value = 1;
                guestCountInput.setCustomValidity('Số người đặt bàn không được nhỏ hơn 1.');
            } else if (value > 50) {
                guestCountInput.value = 50;
                guestCountInput.setCustomValidity('Số người đặt bàn không được lớn hơn 50.');
            } else {
                guestCountInput.setCustomValidity('');
            }

            guestCountInput.classList.toggle('is-invalid', !guestCountInput.checkValidity());
        });
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
    function startOTPTimer() {
        let timeLeft = 60;
        document.getElementById("otp-timer").innerText = `Thời gian còn lại: ${timeLeft} giây`;

        otpTimer = setInterval(() => {
            timeLeft--;
            document.getElementById("otp-timer").innerText = `Thời gian còn lại: ${timeLeft} giây`;

            if (timeLeft <= 0) {
                clearInterval(otpTimer);
                document.getElementById("otp-timer").innerText = "Hết thời gian! Vui lòng gửi lại mã OTP.";
                document.getElementById("resendOtpButton").style.display =
                "block"; // Hiển thị nút "Gửi lại mã OTP"
                document.querySelector(".btn-success").style.display = "none"; // Ẩn nút "Xác thực OTP"
                disableOTPInputs(true); // Vô hiệu hóa các ô nhập OTP
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

                document.getElementById("booking-form")
                    .submit(); // Sau khi xác thực thành công, submit form

            });
        }).catch(() => {
            showOTPError("Mã OTP không đúng! Vui lòng thử lại.");
        });
    };

    // Đóng popup OTP
    function closePopup() {
        clearInterval(otpTimer); // Dừng bộ đếm thời gian nếu popup bị đóng
        document.getElementById("otp-popup").style.display = "none";
    }

    // Tự động chuyển ô khi nhập OTP
    document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace' && index > 0 && input.value === '') {
                inputs[index - 1].focus();
            }
        });
    });
</script>


{{-- <script>
    // Chặn click chuột phải
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Chặn F12 (mở console)
    document.onkeydown = function(e) {
        if (e.key == 'F12') {
            e.preventDefault();
            return false;
        }
    };
</script> --}}
