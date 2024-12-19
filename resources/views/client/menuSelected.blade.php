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
    <style>
        body {
            background-color: #2c2c2c;
            color: #f8f9fa;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
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
            margin-bottom: 100px;
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

        .item-controls {
            display: flex;
            align-items: center;
            gap: 5px;
            /* Thêm khoảng cách giữa các nút */
        }

        .item-controls span {
            display: inline-block;
            width: 30px;
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
        <h5>CÁC MÓN ĐÃ CHỌN</h5>
    </div>
    <div class="order-headerr">
        <a href="{{ $url }}" class="icon-btn btn">
            Thêm món
            <i class="fa-solid fa-plus"></i>
        </a>
        <h5>Bàn {{ $table->table_number }}</h5>
        <a href="{{ route('menuHistory', $table->id) }}" class="icon-btn btn">
            Lịch sử
            <i class="fa-solid fa-clock"></i>
        </a>
    </div>
    <div class="order-list" id="order-container">
        @foreach ($item as $item)
            <div class="order-item" data-id="{{ $item->item_id }}"
                data-type="{{ $item->item_type == 1 ? 'dish' : 'combo' }}">
                <img src="{{ asset('storage/' . ($item->item_type == 1 ? $item->dish->image : $item->combo->image)) }}"
                    alt="{{ $item->item_type == 1 ? $item->dish->name : $item->combo->name }}">
                <div class="item-details">
                    <p class="mb-0">{{ $item->item_type == 1 ? $item->dish->name : $item->combo->name }}</p>
                    <small>{{ number_format($item->item_type == 1 ? $item->dish->price : $item->combo->price) }}đ</small>
                </div>
                <div class="item-controls">
                    <button class="btn btn-sm btn-outline-secondary decrease">-</button>
                    <span class="mx-2 quantity">{{ $item->quantity }}</span>
                    <button class="btn btn-sm btn-outline-secondary increase">+</button>
                    <button class="btn btn-sm btn-outline-danger remove">Xóa</button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="order-footer">
        <div class="d-flex justify-content-between mb-2">
            <span>Tạm tính</span>
            <span class="total" id="btn-subb">{{ number_format($total, 0, ',', '.') }} đ</span>
        </div>
        <a href="{{ route('requestToOrder.menuOrder', $table->id) }}" class="btn btn-primary btn-submit">Gửi yêu cầu
            gọi
            món</a>
    </div>
    <script>
        tableId = "{{ $table->id }}";
        document.getElementById('order-container').addEventListener('click', function(event) {
            const target = event.target;
            const orderItem = target.closest('.order-item');
            if (!orderItem) return;
            const itemId = orderItem.getAttribute('data-id');
            const itemType = orderItem.getAttribute('data-type');
            let action = '';
            if (target.classList.contains('increase')) {
                action = 'increase';
            } else if (target.classList.contains('decrease')) {
                action = 'decrease';
            } else if (target.classList.contains('remove')) {
                action = 'remove';
            }
            if (action) {
                sendUpdate(itemId, itemType, action);
            }
        });

        function sendUpdate(itemId, itemType, action) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/menuOrder/updatee', {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        item_type: itemType,
                        action: action,
                        table: "{{ $table->id }}"
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.success) {
                        console.log('Update success:', result);
                    } else {
                        console.log('Update !success:', result);
                    }
                })
                .catch(error => {
                    alert('Đã xảy ra lỗi khi gửi yêu cầu.');
                    console.error('Error:', error);
                });
        }
    </script>
    @vite(['resources/js/updateItemMenuOrder.js', 'resources/js/DishStatus.js', 'resources/js/notiRedirect.js', 'resources/js/notiRedirectUsers.js'])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
