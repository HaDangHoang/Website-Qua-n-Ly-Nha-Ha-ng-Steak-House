<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Các món đã chọn</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('client/03_images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #2c2c2c;
            color: #f8f9fa;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }

        .order-itemm.active {
            background-color: #ff8c00;
            color: black;
            font-weight: bold;
        }

        .delete-button {
            background-color: #DC3545;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 5px;
            transition: background-color 0.3s ease;
        }

        .delete-button:hover {
            background-color: #C82333;
        }

        .order-header {
            padding: 10px 20px;
            background-color: #3b3b3b;
            border-bottom: 1px solid #444;
            display: flex;
            align-items: center;
        }

        .order-header h5 {
            margin: 0;
            color: #ff8c00;
            font-size: 18px;
            font-weight: bold;
        }

        .order-headerr {
            padding: 10px 20px;
            background-color: #3b3b3b;
            border-bottom: 1px solid #444;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .order-headerr h5 {
            margin: 0;
            color: #ff8c00;
        }

        .order-list {
            padding: 15px;
            background-color: #3b3b3b;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 10px;
            background-color: #444;
            transition: transform 0.2s ease;
        }

        .order-item:hover {
            transform: scale(1.02);
        }

        .order-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .order-item .item-details {
            flex-grow: 1;
            margin-left: 10px;
        }

        .order-item .item-details p {
            color: #f8f9fa;
        }

        .order-item .item-controls {
            text-align: center;
            min-width: 50px;
        }

        .order-item .item-controls button {
            font-size: 14px;
        }

        .order-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #3b3b3b;
            border-top: 1px solid #444;
            padding: 10px 15px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }

        .order-footer .total {
            font-weight: bold;
            color: #ff8c00;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(90deg, #ff8c00, #e07a00);
            border-color: #ff8c00;
            color: #fff;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(90deg, #e07a00, #ff8c00);
        }

        .btn-outline-secondary {
            color: #f8f9fa;
            border-color: #f8f9fa;
        }

        .btn-outline-secondary:hover {
            background-color: #ff8c00;
            border-color: #ff8c00;
            color: #fff;
        }

        .icon-btn {
            background: none;
            border: none;
            color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
            background-color: #444;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .icon-btn:hover {
            background-color: #ff8c00;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .btn-add {
            background-color: #ff8c00;
            color: #fff;
        }

        .btn-add:hover {
            background-color: #e07a00;
        }

        .order-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-details p {
            font-size: 14px;
            font-weight: bold;
        }

        .item-details small {
            color: #666;
        }


        .item-controls span {
            display: inline-block;
            width: 120px;
            /* Chiều rộng cố định */
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="order-header">
        <a href="{{ $url }}" class="icon-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h5>Lịch sử gọi món</h5>
    </div>
    <div class="order-headerr">
        <h5>Bàn {{ $table->table_number }}</h5>
        <a href="{{ $url }}" class="icon-btn btn">
            Thêm món
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
    <div class="order-list row d-flex justify-content-center">
        <div class="order-item order-itemm col-5 mx-3" data-status="chờ xử lý">Chờ xác nhận <i
                class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="order-item order-itemm col-5 mx-3" data-status="đang xử lý">Đã duyệt <i
                class="fa-solid fa-check-double"></i>
        </div>
        <div class="order-item order-itemm col-5 mx-3" data-status="hoàn thành">Đã hoàn thành <i
                class="fa-regular fa-circle-check"></i></div>
        <div class="order-item order-itemm col-5 mx-3" data-status="hủy">Đã hủy <i class="fa-solid fa-ban"></i></div>
    </div>
    <div class="order-list" id="order-container">
        @foreach ($item as $item)
            @php
                $statusText = '';
                $statusColor = '';
                switch ($item->status) {
                    case 'chờ xử lý':
                        $statusText = 'Chờ xác nhận';
                        $statusColor = '#FFA500';
                        break;
                    case 'đang xử lý':
                        $statusText = 'Đã duyệt';
                        $statusColor = '#007BFF';
                        break;
                    case 'hoàn thành':
                        $statusText = 'Đã hoàn thành';
                        $statusColor = '#28A745';
                        break;
                    case 'hủy':
                        $statusText = 'Đã hủy';
                        $statusColor = '#DC3545';
                        break;
                    default:
                        $statusText = 'Không xác định';
                        $statusColor = '#6C757D';
                        break;
                }
            @endphp
            <div class="order-item" data-id="{{ $item->item_id }}"
                data-type="{{ $item->item_type == 1 ? 'dish' : 'combo' }}" data-status="{{ $item->status }}">
                <img src="{{ asset('storage/' . ($item->item_type == 1 ? $item->dish->image : $item->combo->image)) }}"
                    alt="{{ $item->item_type == 1 ? $item->dish->name : $item->combo->name }}">
                <div class="item-details">
                    <p class="mb-0">{{ $item->item_type == 1 ? $item->dish->name : $item->combo->name }}</p>
                    <div class="row">
                        <div class="col-2">
                            <small>{{ number_format($item->item_type == 1 ? $item->dish->price : $item->combo->price) }}đ</small>
                        </div>
                        <div class="col-2">
                            @if ($item->status == 'chờ xử lý')
                                <button class="delete-button">Hủy</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="item-controls">
                    <span class="mx-2 quantity" style="color: {{ $statusColor }}">{{ $statusText }}</span>
                    <br>
                    <span class="mx-2 quantity">X {{ $item->quantity }}</span>
                </div>
            </div>
        @endforeach

    </div>

    <div class="order-footer">
        <div class="d-flex justify-content-between mb-2">
            <span>Tổng tiền</span>
            <span class="total" id="btn-subb">{{ number_format($total, 0, ',', '.') }} đ</span>
        </div>
    </div>
    @vite(['resources/js/menuHistoryUpdated.js', 'resources/js/notiRedirect.js', 'resources/js/notiRedirectUsers.js'])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        const tableId = "{{ $table->id }}";
        $(document).ready(function() {
            // Mặc định hiển thị các món "chờ xử lý"
            const defaultStatus = "chờ xử lý";
            filterOrders(defaultStatus);

            // Thay đổi CSS cho nút mặc định
            $(`.order-item[data-status='${defaultStatus}']`).addClass("active");

            // Lắng nghe sự kiện nhấn vào các nút trạng thái
            $(".order-list .order-item").on("click", function() {
                // Lấy trạng thái từ nút được nhấn
                const status = $(this).attr("data-status");

                // Thay đổi CSS cho nút được chọn
                $(".order-list .order-item").removeClass("active");
                $(this).addClass("active");

                // Gọi hàm lọc danh sách món
                filterOrders(status);
            });

            // Hàm lọc danh sách món theo trạng thái
            function filterOrders(status) {
                $("#order-container .order-item").each(function() {
                    const itemStatus = $(this).attr("data-status");
                    if (itemStatus === status) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.delete-button').on('click', function() {
                var orderItem = $(this).closest('.order-item');
                var itemId = orderItem.data('id');
                var itemType = orderItem.data('type') == 'dish' ? 1 : 2;
                var tableId = tableId;
                var url = orderItem.data('type') == 'dish' ? '/deleteItem' : '/deleteItemm';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        table_id: "{{ $table->id }}",
                        dish_id: itemId,
                        dishType: itemType,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotification('Hủy món thành công!', 'success');
                            location.reload();
                        } else {
                            showNotification('Lỗi khi xóa', 'error');
                        }
                    },
                    error: function(xhr) {
                        alert('Không thể kết nối đến server. Vui lòng thử lại sau!');
                    }
                });
            });
        });

        function showNotification(message, type = 'success') {
            Swal.fire({
                icon: type,
                title: 'Thông báo',
                text: message,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    </script>
</body>

</html>
