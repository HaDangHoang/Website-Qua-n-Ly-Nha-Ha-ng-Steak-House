@extends('pos.layouts.master')

@section('title', 'Phiếu thanh toán')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-between">
            <!-- Phần bên trái: Thông tin bàn -->
            <div class="col-md-4">
                <div class="card shadow-lg border-0 p-4 bg-white rounded">
                    <h5 class="text-muted text-center mb-4">Thông tin đặt bàn</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user"></i> <strong>Tên khách hàng:</strong></span>
                            @if (isset($order->reservation->user_name))
                                <span>{{ $order->reservation->user_name }}</span>
                            @elseif (isset($order->reservation->customer->name))
                                <span>{{ $order->reservation->customer->name }}</span>
                            @elseif(isset($order->customer->name))
                                <span>{{ $order->customer->name }}</span>
                            @else
                                <span>Khách lẻ </span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-chair"></i> <strong>Số bàn:</strong></span>
                            <span>{{ $table->table_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-pen-to-square"></i> <strong>Mã đơn:</strong></span>
                            @if (isset($order->reservation->id))
                                <span>{{ $order->reservation->id }}</span>
                            @else
                                <span>{{ $order->id }}</span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-crown"></i> <strong>Loại bàn:</strong></span>
                            <span>{{ $table->table_type }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users"></i> <strong>Số lượng khách:</strong></span>
                            <span>{{ $order->reservation->guest_count ?? ($order->guest_count ?? 'Khách lẻ') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar-day"></i> <strong>Ngày:</strong></span>
                            <span>{{ $order->created_at->format('d-m-Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clock"></i> <strong>Giờ vào: </strong></span>
                            <span>{{ \Carbon\Carbon::parse($order_table->start_time)->format('H:i:s') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-clock"></i> <strong>Giờ ra:</strong></span>
                            <span id="currentTime"></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Phần bên phải: Thông tin đơn hàng và tổng tiền -->
            <div class="col-md-8">
                <div class="card shadow-lg p-4 bg-white rounded">
                    <h4 class="text-muted text-center">Thông tin món ăn</h4>
                    <table class="table mt-3 table-hover">
                        <thead class="thead-light bg-primary text-light">
                            <tr>
                                <th scope="col">Tên món ăn</th>
                                <th scope="col" class="text-center">Số lượng</th>
                                <th scope="col" class="text-end">Đơn giá</th>
                                <th scope="col" class="text-end">Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_items as $item)
                                <tr>
                                    @if ($item->item_type == 1)
                                        <td>{{ $item->dish->name }}</td>
                                    @else
                                        <td>{{ $item->combo->name }}</td>
                                    @endif
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price) }} VND</td>
                                    <td class="text-end">{{ number_format($item->total_price) }} VND</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @if (isset($order->reservation->deposit_amount))
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Khách đã cọc:</strong></td>
                                    <td class="text-end text-success">
                                        <strong>{{ number_format($order->reservation->deposit_amount ?? 0) }}
                                            VND</strong>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                <td class="text-end text-success">
                                    <strong>{{ number_format($order->total_amount - ($order->reservation->deposit_amount ?? 0)) >= 0 ? number_format($order->total_amount - ($order->reservation->deposit_amount ?? 0)) : 0 }}
                                        VND</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chọn phương thức thanh toán -->
        <div class="row justify-content-center ">
            <!-- Thẻ thanh toán tiền mặt -->
            <div class="col-lg-4 col-md-6">
                <div class="payment-method-card card shadow-lg border-0 text-center p-3" id="cashPaymentBtn"
                    style="cursor: pointer">
                    <!-- Icon thanh toán tiền mặt -->
                    <i class="fas fa-money-bill-wave fa-3x mb-2 text-success"></i>
                    <h5 class="text-success">Thanh toán tiền mặt</h5>
                </div>
            </div>

            <!-- Thẻ chuyển khoản ngân hàng -->
            <div class="col-lg-4 col-md-6">
                <div class="payment-method-card card shadow-lg border-0 text-center p-3" id="bankPaymentBtn"
                    style="cursor: pointer">
                    <!-- Icon chuyển khoản ngân hàng -->
                    <i class="fas fa-university fa-3x mb-2 text-info"></i>
                    <h5 class="text-info">Chuyển khoản ngân hàng</h5>
                </div>
            </div>
        </div>
        <form id="formm" action="{{ route('checkout.admin', $order->id) }}" method="POST">
            @csrf
            <input type="hidden" name="end_time" value="{{ date('H:i:s') }}">
        </form>
        @vite(['resources/js/notiRedirectAdmin.js'])

        <iframe id="printFrame" style="display:none;"></iframe>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            tableId = "{{ $table->id }}";
            document.addEventListener("DOMContentLoaded", function() {
                // Hiển thị ngày và giờ hiện tại
                function updateTime() {
                    const now = new Date();
                    const currentTime = now.toLocaleTimeString('vi-VN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                    document.getElementById('currentTime').textContent = currentTime;
                }

                setInterval(updateTime, 1000);
                const totalAmount = {{ $order->total_amount }}; // Tổng tiền hàng
                const depositAmount = {{ $order->reservation->deposit_amount ?? 0 }}; // Tiền cọc
                const denominationButtons = [10000, 20000, 50000, 100000, 200000, 500000]; // Mệnh giá tiền

                // Hàm kiểm tra và toggle trạng thái nút submit
                function toggleSubmitButton(cashGiven) {
                    const change = cashGiven - totalAmount + depositAmount; // Tiền thừa
                    return cashGiven > 0 && change >= 0;
                }

                // Khi bấm vào nút "Thanh toán tiền mặt"
                document.getElementById('cashPaymentBtn').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Thanh toán bằng tiền mặt',
                        html: `
                        <div class="text-muted mb-3">Tổng tiền: <strong id="deposit">${totalAmount.toLocaleString('vi-VN').replace(/\./g, ',')}</strong> VND</div>
                        <div class="text-muted mb-3">Tiền cọc: <strong id="deposit">${depositAmount.toLocaleString('vi-VN').replace(/\./g, ',')}</strong> VND</div>
                        <p>Khách cần trả: <strong id="total-amount">${(totalAmount-depositAmount)>=0?(totalAmount-depositAmount).toLocaleString('vi-VN').replace(/\./g, ','):0}</strong> VND</p>
                        <label>Số tiền khách trả: </label>
                        <input type="text" class="form-control text-dark border border-secondary form-control-lg mb-3" id="cashGiven" placeholder="Nhập số tiền..." maxlength="12">
                        <div id="denominationButtons" class="d-flex justify-content-center flex-wrap"></div>
                        <div id="cashChange" class="text-muted mb-3">Tiền thừa trả khách: <strong id="changeAmount">${(depositAmount-totalAmount)>=0?(depositAmount-totalAmount).toLocaleString('vi-VN').replace(/\./g, ','):0}</strong>VND</div>`,
                        showCancelButton: true,
                        confirmButtonText: 'Xác nhận thanh toán',
                        didOpen: () => {
                            const cashGivenInput = document.getElementById('cashGiven');
                            const changeAmount = document.getElementById('changeAmount');
                            // Tạo các nút mệnh giá
                            const buttonContainer = document.getElementById('denominationButtons');

                            // Nút đầu tiên bằng số tiền khách cần trả
                            let nextDenomination = Math.ceil((totalAmount - depositAmount) / 1000) *
                                1000 >= 0 ? Math.ceil((totalAmount - depositAmount) / 1000) *
                                1000 : 0; // Làm tròn lên số chia hết cho 1,000
                            const firstButton = document.createElement('button');
                            firstButton.classList.add('btn', 'btn-outline-primary', 'm-1');
                            firstButton.textContent = nextDenomination.toLocaleString('vi-VN') +
                                ' VND';

                            // Lưu giá trị của nextDenomination vào một biến cục bộ
                            const firstButtonValue = nextDenomination;

                            firstButton.addEventListener('click', () => {
                                cashGivenInput.value = firstButtonValue.toLocaleString(
                                    'vi-VN'); // Cập nhật ô input
                                let cashGiven =
                                    firstButtonValue; // Lấy giá trị từ nút mệnh giá
                                let change = (cashGiven + depositAmount) -
                                    totalAmount; // Tính tiền thừa (cộng tiền cọc vào)
                                changeAmount.textContent = change >= 0 ? change
                                    .toLocaleString('vi-VN') :
                                    "0"; // Cập nhật hiển thị tiền thừa
                                toggleSubmitButton(
                                    cashGiven); // Kích hoạt hoặc vô hiệu hóa nút xác nhận
                            });

                            buttonContainer.appendChild(firstButton);
                            // Các nút tiếp theo tăng dần với bước 100,000
                            nextDenomination = Math.ceil((nextDenomination + 100000) / 100000) *
                                100000;

                            for (let i = 0; i < 5; i++) {
                                // Lưu giá trị hiện tại của nextDenomination vào một biến cục bộ
                                const denominationValue = nextDenomination;

                                const button = document.createElement('button');
                                button.classList.add('btn', 'btn-outline-primary', 'm-1');
                                button.textContent = denominationValue.toLocaleString('vi-VN')
                                    .replace(/\./g, ',') +
                                    ' VND';

                                button.addEventListener('click', () => {
                                    cashGivenInput.value = denominationValue.toLocaleString(
                                        'vi-VN').replace(/\./g,
                                        ','); // Sử dụng giá trị cục bộ
                                    let cashGiven =
                                        denominationValue; // Lấy giá trị từ biến cục bộ
                                    let change = (cashGiven + depositAmount) -
                                        totalAmount; // Tính tiền thừa
                                    changeAmount.textContent = change >= 0 ? change
                                        .toLocaleString('vi-VN').replace(/\./g, ',') :
                                        "0"; // Cập nhật hiển thị tiền thừa
                                    toggleSubmitButton(
                                        cashGiven
                                    ); // Kích hoạt hoặc vô hiệu hóa nút xác nhận
                                });

                                buttonContainer.appendChild(button);
                                nextDenomination += 100000; // Tăng giá trị cho nút tiếp theo
                            }


                            // Xử lý nhập tay vào ô input
                            cashGivenInput.addEventListener('input', function() {
                                let cashGiven = parseInt(cashGivenInput.value.replace(/\D/g,
                                    '')) || 0;
                                let change = (cashGiven + depositAmount) - totalAmount;

                                // Cập nhật tiền thừa
                                changeAmount.textContent = change >= 0 ? change
                                    .toLocaleString('vi-VN') : "0";

                                // Định dạng số khi nhập
                                cashGivenInput.value = cashGiven.toLocaleString();

                                // Tắt hoặc bật nút xác nhận
                                toggleSubmitButton(cashGiven);
                            });

                            // Bắt đầu với nút "Xác nhận thanh toán" bị tắt
                            Swal.getConfirmButton().disabled = true;
                        },
                        preConfirm: () => {
                            const cashGiven = parseInt(document.getElementById('cashGiven').value
                                .replace(/\D/g, '')) || 0;
                            if ((cashGiven + depositAmount) < totalAmount) {
                                Swal.showValidationMessage('Số tiền không hợp lệ!');
                                return false;
                            }

                            // Giả lập quá trình thanh toán thành công và submit form
                            Swal.fire({
                                title: 'Xác nhận thanh toán',
                                text: 'Thanh toán thành công',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                var end_time = new Date()
                                    .toLocaleTimeString('vi-VN');
                                var url =
                                    "{{ route('print.page', $order->id) }}?end_time=" +
                                    encodeURIComponent(end_time);
                                $.ajax({
                                    url: url,
                                    method: 'GET',
                                    success: function(response) {
                                        var printFrame = document
                                            .getElementById('printFrame')
                                            .contentWindow;
                                        printFrame.document.open();
                                        printFrame.document.write(response);
                                        printFrame.document.close();

                                        printFrame.onafterprint = function() {
                                            document.getElementById('formm')
                                                .submit();
                                        };

                                        printFrame.focus();
                                        printFrame.print();
                                    },
                                    error: function() {
                                        alert('Lỗi khi tải nội dung in.');
                                    }
                                });

                            })
                        }
                    });
                });

                // Hàm kiểm tra để bật/tắt nút submit
                function toggleSubmitButton(cashGiven) {
                    const submitButton = Swal.getConfirmButton();
                    const change = (cashGiven + depositAmount) - totalAmount;
                    if (cashGiven >= 0 && change >= 0) {
                        submitButton.disabled = false; // Kích hoạt nút submit
                    } else {
                        submitButton.disabled = true; // Vô hiệu hóa nút submit
                    }
                }
                $('#bankPaymentBtn').click(function() {
                    if ({{ $order->total_amount - ($order->reservation->deposit_amount ?? 0) }} > 0) {
                        Swal.fire({
                            title: 'Đang chờ thanh toán',
                            html: 'Vui lòng quét mã thanh toán...',
                            imageUrl: 'https://img.vietqr.io/image/MB-0964236835-compact2.png?amount={{ $order->total_amount - ($order->reservation->deposit_amount ?? 0) }}&addInfo=Thanh Toan Don Hang {{ $order->id }}',
                            imageWidth: 400,
                            imageHeight: 450,
                            showConfirmButton: false,
                            showCloseButton: true,
                            didOpen: () => {
                                Swal.showLoading();
                                setTimeout(() => {}, 3000);
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Phương thức thanh toán không khả dụng",
                            text: "Tổng tiền cần lớn hơn 0.",
                            icon: "warning",
                            timer: 4000,
                            timerProgressBar: true,
                        });
                    }


                    var checkInterval = 1000;
                    var delayBeforeStart = 5000;
                    var desiredAmount = totalAmount - depositAmount;
                    var desiredDescription = 'Thanh Toan Don Hang ' + {{ $order->id }};
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
                        $.ajax({
                            url: 'https://script.google.com/macros/s/AKfycbykL1FhIB2kEaReIq9wzGqfY1SY5cRxOvsmB7hVNc_IY3wqz_sDgVJVQCJCEWsn2CPE/exec',
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                var transactions = data.data;
                                var foundTransaction = false;
                                transactions.forEach(function(transaction) {
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
                                            var end_time = new Date()
                                                .toLocaleTimeString('vi-VN');
                                            var url =
                                                "{{ route('print.page', $order->id) }}?end_time=" +
                                                encodeURIComponent(end_time);
                                            $.ajax({
                                                url: url,
                                                method: 'GET',
                                                success: function(
                                                    response) {
                                                    var printFrame =
                                                        document
                                                        .getElementById(
                                                            'printFrame'
                                                        )
                                                        .contentWindow;
                                                    printFrame.document
                                                        .open();
                                                    printFrame.document
                                                        .write(
                                                            response);
                                                    printFrame.document
                                                        .close();
                                                    printFrame
                                                        .onafterprint =
                                                        function() {
                                                            submitForm
                                                                ();
                                                        };
                                                    printFrame.focus();
                                                    printFrame.print();
                                                },
                                                error: function() {
                                                    alert(
                                                        'Lỗi khi tải nội dung in.'
                                                    );
                                                }
                                            });
                                        });
                                        transactionFound = true;
                                        clearInterval(intervalId);
                                    } else {
                                        // console.log('Chưa tìm thấy giao dịch phù hợp.');
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                $('#status').text('Lỗi khi lấy dữ liệu: ' + error);
                            }
                        });
                    }

                    function submitForm() {
                        let form = $('<form>', {
                            action: "{{ route('checkout.admin', $order->id) }}",
                            method: 'POST'
                        });
                        form.append($('<input>', {
                            type: 'hidden',
                            name: '_token',
                            value: '{{ csrf_token() }}'
                        }));
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'end_time',
                            value: new Date().toLocaleTimeString('vi-VN')
                        }));
                        form.append($('<input>', {
                            type: 'hidden',
                            name: 'payment_method',
                            value: 'bank'
                        }));
                        var orderItems = @json($order_items);
                        $.each(orderItems, function(index, item) {
                            if (item) {
                                form.append($('<input>', {
                                    type: 'hidden',
                                    name: 'item_name[]',
                                    value: item.id
                                }));
                                form.append($('<input>', {
                                    type: 'hidden',
                                    name: 'quantity[]',
                                    value: 1
                                }));
                            }
                        });
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        </script>
    @endsection
