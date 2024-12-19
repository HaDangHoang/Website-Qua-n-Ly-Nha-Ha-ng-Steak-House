<script>
        document.addEventListener("DOMContentLoaded", function() {
            const depositForm = document.getElementById('depositForm');
            const vnpayOption = document.getElementById('vnpay');
            const payUrlOption = document.getElementById('payUrl');

            depositForm.addEventListener('submit', function(event) {
                if (vnpayOption.checked) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Đang chờ thanh toán...',
                        html: 'Vui lòng thực hiện quét mã thanh toán...',
                        imageUrl: 'https://img.vietqr.io/image/MB-0964236835-compact2.png?amount={{ $deposit }}&addInfo=Thanh Toan Don Hang {{ $orderId }}',
                        imageWidth: 400,
                        imageHeight: 400,
                        allowOutsideClick: false,
                        showCancelButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            handleVnpayTask().then(() => {
                                depositForm.submit();
                            }).catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: error.message,
                                    confirmButtonText: 'OK'
                                });
                            });
                        }
                    });
                }
            });

            function handleVnpayTask() {
                return new Promise((resolve, reject) => {
                    $(document).ready(function() {
                        var checkInterval = 1000;
                        var desiredAmount = {{ $deposit }};
                        var desiredDescription =
                            'Thanh Toan Don Hang {{ $orderId }}';
                        var intervalId = setInterval(checkTransaction, checkInterval);

                        function checkTransaction() {
                            $.ajax({
                                url: 'https://script.google.com/macros/s/AKfycbykL1FhIB2kEaReIq9wzGqfY1SY5cRxOvsmB7hVNc_IY3wqz_sDgVJVQCJCEWsn2CPE/exec',
                                type: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    var lastTransaction = data.data[data.data.length -
                                        1];
                                    if (lastTransaction['Giá trị'] == desiredAmount &&
                                        lastTransaction['Mô tả'].includes(
                                            desiredDescription)) {
                                        Swal.fire({
                                            position: "center",
                                            icon: "success",
                                            title: "Thanh toán thành công",
                                            showConfirmButton: false,
                                            timer: 2000
                                        }).then(() => {
                                            clearInterval(
                                                intervalId);
                                            resolve();
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    reject(new Error('Lỗi khi lấy dữ liệu: ' +
                                        error));
                                }
                            });
                        }
                    });
                });
            }

        });
    </script>