@extends('client.layouts.master')
@section('title', 'Thanh toán cọc')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-2.jpg',
        'subtitle' => 'Thanh toán cọc',
        'title' => 'Chờ thanh toán cọc',
        'currentPage' => 'Booking',
    ])
    <div id="content" class="no-bottom no-top">
        <section id="section-book-form">
            <form id="depositForm" action="{{ route('MOMOCheckout.client') }}" method="POST">
                @csrf
                <div class="container">
                    <div class="row">
                        <div class="col-7 p-5 border-color-1 rounded">
                            <div class="row">
                                <h3 class="text-center">Quét mã dưới đây bằng ứng dụng Internet Banking để thanh toán</h3>
                            </div>
                            <div class="row d-flex justify-content-center m-4">
                                <img src="https://img.vietqr.io/image/MB-0964236835-compact2.png?amount={{ $data['deposit'] }}&addInfo=Thanh toan coc don dat ban {{ $data['orderId'] }}"
                                    alt="" style="width:300px;height:350px">
                            </div>
                        </div>
                        <div class="col-5">
                            <!-- Chi tiết cọc -->
                            <div class="row">
                                <div class="col">
                                    <div class="p-4 bg-dark rounded">
                                        <h3 class="">Thông tin thanh toán</h3>
                                        <div class="row pt-2">
                                            <div class="col-5">
                                                <p>Số tiền thanh toán:</p>
                                                <p>Nội dung:</p>
                                            </div>
                                            <div class="col-7">
                                                <p>{{ number_format($data['deposit']) }} đ</p>
                                                <p>Thanh toán cọc đơn hàng {{ $data['orderId'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                {{-- <img src="client/03_images/background/28fd6562f18548db1194.jpg" alt=""> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-between mt-4">
                        <a href="{{ route('customer.information', ['date' => $data['reservation_date'], 'time' => $data['reservation_time'], 'note' => $data['note'] ?? null, 'guest_count' => $data['guest_count'], 'user_phone' => $data['user_phone'], 'user_name' => $data['user_name']]) }}"
                            class="text-secondary d-block text-center">Quay lại trang đặt bàn</a>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                var checkInterval = 1000; // khoảng thời gian lặp lại (1 giây)
                var desiredAmount = {{ $data['deposit'] }}; // số tiền cần tìm
                var desiredDescription =
                    'Thanh toan coc don dat ban {{ $data['orderId'] }}'; // mô tả cần tìm
                var intervalId = setInterval(checkTransaction, checkInterval);

                function checkTransaction() {
                    $.ajax({
                        url: 'https://script.google.com/macros/s/AKfycbwsNblgurg5Wig7qUO0TNmDmwlJocExVGzMR5wCacLO1vJvRe9Zq9MW4sjrY0fdIdFv/exec',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var transactions = data.data;
                            var foundTransaction = false;

                            // Duyệt qua tất cả các giao dịch để tìm giao dịch phù hợp
                            transactions.forEach(function(transaction) {
                                if (transaction['Giá trị'] == desiredAmount &&
                                    transaction['Mô tả'].includes(desiredDescription)) {

                                    foundTransaction = true;

                                    // Hiển thị thông báo thành công nếu tìm thấy giao dịch hợp lệ
                                    Swal.fire({
                                        position: "center",
                                        icon: "success",
                                        title: "Thanh toán thành công",
                                        showConfirmButton: false,
                                        timer: 2000
                                    }).then(() => {
                                        var reservationDate =
                                            '{{ $data['reservation_date'] }}';
                                        var reservationTime =
                                            '{{ $data['reservation_time'] }}';
                                        var guestCount =
                                            '{{ $data['guest_count'] }}';
                                        var userPhone =
                                            '{{ $data['user_phone'] }}';
                                        var userName =
                                            '{{ $data['user_name'] }}';
                                        var orderId = '{{ $data['orderId'] }}';
                                        var deposit = '{{ $data['deposit'] }}';

                                        var redirectUrl =
                                            "{{ route('createReservationWithMomo.client') }}?reservation_date=" +
                                            reservationDate +
                                            "&reservation_time=" + reservationTime +
                                            "&guest_count=" + guestCount +
                                            "&user_phone=" + userPhone +
                                            "&user_name=" + userName +
                                            "&deposit=" + deposit +
                                            "&orderId=" + orderId;

                                        // Điều hướng đến URL mới
                                        window.location.href = redirectUrl;
                                    });

                                    // Dừng kiểm tra giao dịch sau khi tìm thấy giao dịch hợp lệ
                                    clearInterval(intervalId);
                                }
                            });

                            if (!foundTransaction) {
                                console.log('Chưa tìm thấy giao dịch phù hợp.');
                            }
                        }
                    });
                }

            });
        })
    </script>
@endsection
