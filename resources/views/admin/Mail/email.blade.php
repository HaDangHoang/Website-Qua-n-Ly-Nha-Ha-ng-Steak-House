<!DOCTYPE html>
<html>
<head>
    <title>Hóa đơn của bạn</title>
</head>
<body>
    <div class="container">
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Biên lai chuyển tiền</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f5f5f5;
                }
                .container {
                    width: 600px;
                    margin: 20px auto;
                    background-color: #fff;
                    /* border: 1px solid #ddd; */
                    padding: 20px;
                    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #4CAF50;
                    padding-bottom: 10px;
                }
                .header img {
                  height: 92px;
                  width: 95px;
                  margin-right: 10px;
        
                }
                .header h2 {
                    margin: 0;
                    font-size: 20px;
                    /* color: #333; */
                }
                .header p {
                    font-size: 12px;
                    color: #666;
                }
                .content-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                .content-table th, .content-table td {
                    padding: 8px;
                    border: 1px solid #ddd;
                    font-size: 14px;
                    vertical-align: top;
                }
                .content-table th {
                    background-color: #f8f8f8;
                    font-weight: bold;
                    text-align: left;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 12px;
                    color: #666;
                    text-align: center;
                }
            </style>
        </head>
        <body>
        
        <div class="container">
            <div class="header">
                <img src="{{ asset('adminn/assets/images/z5810215888836_3b5d6e0322d902e91e874be27cb2e5e8.jpg') }}" alt="Logo">
                <h2>Biên lai chuyển tiền qua tài khoản</h2>
                <p>(Payment Receipt)</p>
            </div>
            @if ($refund)
              
            <table class="content-table">
                <tr>
                    <th>Tên tài khoản<br><span style="font-weight: normal;"></span></th>
                    <td>{{ $refund->account_name }}</td>
                </tr>
                <tr>
                    <th>Số tài khoản<br><span style="font-weight: normal;"></span></th>
                    <td>{{ $refund->account_number }}</td>
                </tr>
                <tr>
                    <th>Số tiền hoàn lại<br><span style="font-weight: normal;"></span></th>
                    <td> {{ number_format($refund->refund_amount, 2) }} VND</td>
                </tr>
               
                <tr>
                    <th>Tên ngân hàng <br><span style="font-weight: normal;"></span></th>
                    <td>{{ $refund->bank_name }}</td>
                </tr>
                <tr>
                    <th>Email liên hệ<br><span style="font-weight: normal;"></span></th>
                    <td> {{ $refund->email }}</td>
                </tr>
                <tr>
                    <th>Lý do hủy bàn<br><span style="font-weight: normal;"> </span></th>
                    <td>{{ $refund->reason }}</td>
                </tr>
                <tr>
                    <th>Trạng thái hoàn tiền<br><span style="font-weight: normal;"> </span></th>
                    <td>Đã hoàn tiền</td>
                </tr>
                       
            </table>
              
            @else
            <p class="no-refund">Không có thông tin hoàn tiền cho hóa đơn này.</p>
        @endif
            <div class="footer">
                <p>Hotline: 0363 486 472 | http://datn-restaurantreservation.test/</p>
            </div>
        </div>
        
        </body>
        </html>
        
</body>
</html>
