<form action="{{ route('Ppayment', '1') }}" method="GET">
    @csrf
    <input type="hidden" name="total_amount" value="3000">
    <input type="hidden" name="order_item[]" value="1">
    <input type="hidden" name="order_item[]" value="2">
    <input type="hidden" name="quantity[]" value="1">
    <input type="hidden" name="quantity[]" value="2">
    <input type="hidden" name="price[]" value="1000">
    <input type="hidden" name="price[]" value="1000">
    <input type="submit" value="1">
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php
$banksCode = '970414';
$accountNumber = '0964236835';
$total_amount = 1;
$orderId = 1;
?>


<form action="" id="actionForm">
    <select name="" id="">
        <option value="">Không phải là chờ hoàn cọc</option>
        <option value="" id="refund" selected>Chờ hoàn cọc</option>
    </select>
    <input type="submit" id="bankPaymentBtn" value="Xác nhận">
</form>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const actionForm = document.getElementById('actionForm');
        const refundOption = document.getElementById('refund');
        actionForm.addEventListener('submit', function(event) {
            if (refundOption.selected) {
                event.preventDefault();
                Swal.fire({
                    title: 'Đang chờ thanh toán...',
                    html: 'Vui lòng thực hiện quét mã thanh toán...',
                    imageUrl: 'https://img.vietqr.io/image/{{ $banksCode }}-{{ $accountNumber }}-compact2.png?amount={{ $total_amount }}&addInfo=Thanh Toan Hoan Coc Don Dat Ban {{ $orderId }}',
                    imageWidth: 400,
                    imageHeight: 450,
                    showCloseButton: true,
                    showCancelButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        handleVnpayTask().then(() => {
                            actionForm.submit();
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
                    var checkInterval = 1000; // khoảng thời gian lặp lại (1 giây)
                    var desiredAmount = -{{ $total_amount }}; // số tiền cần tìm
                    var desiredDescription =
                        'Thanh Toan Hoan Coc Don Dat Ban {{ $orderId }}'; // mô tả cần tìm
                    var intervalId = setInterval(checkTransaction, checkInterval);

                    function checkTransaction() {
                        $.ajax({
                            url: 'https://script.google.com/macros/s/AKfycbwsNblgurg5Wig7qUO0TNmDmwlJocExVGzMR5wCacLO1vJvRe9Zq9MW4sjrY0fdIdFv/exec',
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                var transactions = data
                                    .data; // toàn bộ các giao dịch

                                var foundTransaction =
                                    false; // biến để xác định có tìm thấy giao dịch hợp lệ hay không

                                // Duyệt qua tất cả các giao dịch để tìm giao dịch phù hợp
                                transactions.forEach(function(transaction) {
                                    if (transaction['Giá trị'] ==
                                        desiredAmount &&
                                        transaction['Mô tả'].includes(
                                            desiredDescription)) {
                                        foundTransaction = true;

                                        // Hiển thị thông báo thành công nếu tìm thấy giao dịch hợp lệ
                                        Swal.fire({
                                            position: "center",
                                            icon: "success",
                                            title: "Thanh toán thành công",
                                            showConfirmButton: false,
                                            timer: 2000
                                        }).then(() => {
                                            clearInterval(
                                                intervalId
                                            ); // Dừng kiểm tra
                                            resolve
                                                (); // Hoàn thành Promise
                                        });
                                    }
                                });

                                // Nếu không tìm thấy giao dịch nào phù hợp, tiếp tục kiểm tra sau
                                if (!foundTransaction) {
                                    console.log('Chưa tìm thấy giao dịch phù hợp.');
                                }
                            },
                            error: function(xhr, status, error) {
                                reject(new Error('Lỗi khi lấy dữ liệu: ' +
                                    error)); // Xử lý lỗi nếu có
                            }
                        });
                    }
                });
            });
        }



        // $(document).ready(function() {
        //     var checkInterval = 1000; // Kiểm tra mỗi giây
        //     var delayBeforeStart = 1; // Bắt đầu kiểm tra sau 20 giây
        //     var desiredAmount = -{{ $total_amount }};
        //     var desiredDescription = 'Hoan Coc Don Hang ' + {{ $orderId }};
        //     var transactionFound = false;
        //     var intervalId;

        //     // Hàm kiểm tra giao dịch
        //     function checkTransaction() {
        //         $.ajax({
        //             url: 'https://script.google.com/macros/s/AKfycbykL1FhIB2kEaReIq9wzGqfY1SY5cRxOvsmB7hVNc_IY3wqz_sDgVJVQCJCEWsn2CPE/exec',
        //             type: 'GET',
        //             dataType: 'json',
        //             success: function(data) {
        //                 var transactions = data.data; // Toàn bộ các giao dịch

        //                 // Duyệt qua tất cả các giao dịch để kiểm tra
        //                 transactions.forEach(function(transaction) {
        //                     if (transaction['Giá trị'] == desiredAmount &&
        //                         transaction['Mô tả'].includes(desiredDescription)) {

        //                         // Hiển thị thông báo thành công với ảnh QR
        //                         Swal.fire({
        //                             title: "Thông tin chuyển khoản",
        //                             html: '<img src="https://img.vietqr.io/image/{{ $banksCode }}-{{ $accountNumber }}-compact2.png?amount={{ $total_amount }}&addInfo=Thanh Toan Don Hang {{ $orderId }}" alt="QR Code" style="width: 200px; height: 200px;">',
        //                             showConfirmButton: false,
        //                             timer: 5000
        //                         }).then(() => {
        //                             transactionFound = true;
        //                             clearInterval(
        //                                 intervalId); // Dừng kiểm tra
        //                             $('form').submit(); // Gửi form
        //                         });
        //                     }
        //                 });
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error('Lỗi khi lấy dữ liệu: ' + error);
        //             }
        //         });
        //     }

        //     // Bắt đầu kiểm tra khi nhấn nút "Xác nhận"
        //     $('#bankPaymentBtn').click(function(event) {
        //         event.preventDefault();

        //         // Bắt đầu tìm kiếm sau một thời gian chờ
        //         setTimeout(function() {
        //             intervalId = setInterval(function() {
        //                 if (!transactionFound) {
        //                     checkTransaction();
        //                 }
        //             }, checkInterval);
        //         }, delayBeforeStart);
        //     });
        // });
    });
</script>
