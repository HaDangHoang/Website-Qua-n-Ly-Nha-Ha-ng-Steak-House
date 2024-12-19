<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Thanh Toán</title>
    <style>
        /* Reset CSS */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        /* Container Styles */
        .receipt-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h2 {
            font-size: 24px;
            margin: 0;
        }

        .receipt-header p {
            margin: 5px 0;
            color: #555;
        }

        .divider {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 15px 0;
        }

        /* Table Info */
        .info-section {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .info-section p {
            margin: 5px 0;
        }

        /* Table Styles */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .product-table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        /* Total Section */
        .total-section {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
        }

        /* Footer */
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .receipt-footer strong {
            display: block;
            margin-top: 5px;
            font-size: 16px;
            color: #000;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <h2>Hóa Đơn Thanh Toán</h2>
            <p>Mã hóa đơn: #{{ $order->id }}</p>
            <hr class="divider">
        </div>

        <!-- Table Info -->
        <div class="info-section">
            <p><strong>Bàn số:</strong> {{ $table->table_number }}</p>
            <p><strong>Ngày:</strong> {{ \Carbon\Carbon::parse($reservation_table->start_time)->format('d-m-Y') }}</p>
            <p><strong>Giờ vào:</strong> {{ \Carbon\Carbon::parse($reservation_table->start_time)->format('H:i:s') }}
            </p>
            <p><strong>Giờ ra:</strong> {{ $data }}</p>
        </div>

        <!-- Product Table -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>Số lượng</th>
                    <th>Tên món</th>
                    <th>Thành tiền (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->quantity }}</td>
                        @if ($item->item_type == 1)
                            <td>{{ $item->dish->name }}</td>
                        @else
                            <td>{{ $item->combo->name }}</td>
                        @endif
                        <td>{{ number_format($item->total_price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            Tổng tiền: {{ number_format($order->total_amount) }} VNĐ
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
            <strong>Hẹn gặp lại quý khách!</strong>
        </div>
    </div>
</body>

</html>
