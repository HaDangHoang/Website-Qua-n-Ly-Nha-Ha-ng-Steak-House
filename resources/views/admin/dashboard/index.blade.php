@extends('admin.master')

@section('title', 'Thống kê Dashboard')

@section('content')
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row">
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-red">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-red">{{ $tableCount }}</h3>
                            <p><a href="{{ route('admin.table.index') }}">Bàn</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-blue">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-blue">{{ $userCount }}</h3>
                            <p><a href="{{ route('admin.user.index') }}">Tài Khoản</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-yellow">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-yellow">{{ $categoryCount }}</h3>
                            <p><a href="{{ route('admin.category.index') }}">Thực Đơn</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-green">
                            <i class="bi bi-handbag"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-green">{{ $orderCount }}</h3>
                            <p><a href="{{ route('admin.order.index') }}">Đơn Hàng</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->

            <!-- Row start -->
            <div class="row">
                <div class="col-xxl-9 col-sm-12 col-12">
                    <div class="card" style="width: 1230px;">
                        <div class="card-body">
                            <!-- Bắt đầu dòng -->
                            <div class="row">
                                <!-- Tóm tắt báo cáo -->
                                <div class="col-xxl-3 col-sm-4 col-12">
                                    <div class="reports-summary">
                                        <!-- Doanh số bán hàng tổng quan -->
                                        <div class="reports-summary-block">
                                            <i class="bi bi-circle-fill text-primary me-2"></i>
                                            <div class="d-flex flex-column">
                                                <h6>Số Lượng Đơn Đặt Bàn</h6>
                                                <h5>{{ number_format($reservationCount) }} Đơn</h5>

                                                <!-- Hiển thị doanh thu -->
                                            </div>
                                        </div>
                                        <!-- Thu nhập tổng quan -->
                                        <div class="reports-summary-block">
                                            <i class="bi bi-circle-fill text-success me-2"></i>
                                            <div class="d-flex flex-column">
                                                <h6>Doanh Thu Từ Đặt Bàn</h6>
                                                <h5>{{ number_format($totalRevenue ?? 0, 0, '.', ',') }} VND</h5>
                                            </div>
                                        </div>

                                        <!-- Doanh thu tổng quan -->
                                        <div class="reports-summary-block">
                                            <i class="bi bi-circle-fill text-danger me-2"></i>
                                            <div class="d-flex flex-column">
                                                <h6>Tỉ Lệ Hủy Đặt Bàn</h6>
                                                <h5>{{ number_format($cancellationRate ?? 0, 2, '.', ',') }}%</h5>
                                                <!-- Doanh thu đã sửa -->
                                            </div>
                                        </div>
                                        <!-- Khách hàng mới -->
                                        <div class="reports-summary-block">
                                            <i class="bi bi-circle-fill text-warning me-2"></i>
                                            <div class="d-flex flex-column">
                                                <h6>Số Lượng Khách</h6>
                                                <h5>{{ number_format($totalCustomers ?? 0) }} Khách</h5>
                                            </div>
                                        </div>
                                        <!-- Nút xem báo cáo -->
                                        <a href="{{ route('admin.report.index') }}" class="btn btn-info download-reports"
                                            onclick="downloadReport()">Xem Báo
                                            Cáo</a>
                                    </div>
                                </div>

                                <!-- Khu vực biểu đồ -->
                                <div class="col-xxl-9 col-sm-8 col-12">
                                    <div class="row">
                                        <!-- Chọn khoảng thời gian -->
                                        <div class="col-12">
                                            <div class="graph-day-selection mt-2" role="group">
                                                <button type="button" class="btn active" data-period="today">Hôm
                                                    Nay</button>
                                                <button type="button" class="btn" data-period="yesterday">Hôm
                                                    Qua</button>
                                                <button type="button" class="btn" data-period="7days">7 Ngày</button>
                                                <button type="button" class="btn" data-period="15days">15 Ngày</button>
                                                <button type="button" class="btn" data-period="30days">30 Ngày</button>
                                            </div>
                                        </div>
                                        <!-- Biểu đồ doanh thu -->
                                        <div class="col-12">
                                            <div id="revenueGraph" class="graph-container"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Kết thúc dòng -->
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Tùy chọn biểu đồ
                                var options = {
                                    chart: {
                                        height: 317,
                                        type: 'area',
                                        toolbar: {
                                            show: false, // Ẩn thanh công cụ
                                        },
                                        events: {
                                            resize: function(chart) {
                                                chart.updateOptions({
                                                    chart: {
                                                        height: 317, // Duy trì kích thước ổn định khi thay đổi kích thước
                                                    }
                                                });
                                            }
                                        }
                                    },
                                    dataLabels: {
                                        enabled: false // Tắt nhãn dữ liệu
                                    },
                                    stroke: {
                                        curve: 'smooth',
                                        width: 3
                                    },
                                    series: [{
                                            name: 'Số Lượng Đơn Đặt Bàn',
                                            data: [{{ implode(',', $bookingData) }}], // Dữ liệu đặt bàn
                                        },
                                        {
                                            name: 'Doanh Thu Từ Đặt Bàn',
                                            data: [{{ implode(',', $revenueData) }}], // Dữ liệu doanh thu
                                        }
                                    ],
                                    grid: {
                                        borderColor: '#e0e6ed',
                                        strokeDashArray: 5,
                                    },
                                    xaxis: {
                                        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov",
                                            "Dec"
                                        ], // Các tháng
                                        labels: {
                                            style: {
                                                colors: '#6c757d',
                                                fontSize: '12px',
                                            }
                                        }
                                    },
                                    yaxis: [{
                                            show: false // Ẩn trục Y "Số Lượng"
                                        },
                                        {
                                            opposite: true,
                                            show: false // Ẩn trục Y "Doanh Thu (VND)"
                                        }
                                    ],
                                    colors: ['#4267cd', '#32b2fa'], // Màu sắc cho chuỗi dữ liệu
                                    markers: {
                                        size: 0, // Ẩn hoàn toàn các chấm tròn
                                    },
                                    tooltip: {
                                        shared: true, // Tooltip chia sẻ giữa các chuỗi dữ liệu
                                        x: {
                                            format: 'dd-MM-yyyy' // Định dạng ngày tháng
                                        },
                                        y: {
                                            formatter: function(value) {
                                                return value.toLocaleString('vi-VN', {
                                                    style: 'currency',
                                                    currency: 'VND'
                                                });
                                            }
                                        }
                                    },
                                    legend: {
                                        position: 'bottom',
                                        horizontalAlign: 'center',
                                    }
                                };

                                // Khởi tạo biểu đồ
                                var chart = new ApexCharts(document.querySelector("#revenueGraph"), options);
                                chart.render();

                                // Xử lý sự kiện thay đổi khoảng thời gian
                                document.querySelectorAll('.graph-day-selection .btn').forEach(button => {
                                    button.addEventListener('click', function() {
                                        document.querySelectorAll('.graph-day-selection .btn').forEach(btn => btn
                                            .classList.remove('active'));
                                        this.classList.add('active');

                                        var period = this.getAttribute('data-period');

                                        // Fetch dữ liệu mới
                                        fetch('/getDataForPeriod', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    period: period
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                chart.updateSeries([{
                                                        name: 'Số Lượng Đơn Đặt Bàn',
                                                        data: data.bookingData
                                                    },
                                                    {
                                                        name: 'Doanh Thu Từ Đặt Bàn',
                                                        data: data.revenueData
                                                    }
                                                ]);
                                            })
                                            .catch(error => console.error('Error:', error));
                                    });
                                });
                            });
                        </script>


                    </div>

                </div>


            </div>
            <!-- Row end -->


            <div class="row">
                <div class="card" style="width: 1230px;
                    margin-left: 14px;">
                    <div class="card-header">
                        <div class="card-title"></div>
                    </div>
                    <div class="card-body">
                        <div id="salesGraph" class="auto-align-graph"></div>
                        <div class="num-stats text-center">
                            <h5 id="totalRevenue" style="color: #f14f22; font-size: 20px; font-weight: 600;">0₫.00</h5>
                            <!-- Tổng doanh thu -->
                        </div>
                        <p class="text-center mt-1" style="color: #555e5e">Các Tháng Trong Năm 2024</p>
                    </div>
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Dữ liệu được nhúng từ server
                        const data = @json($revenues);

                        console.log('Dữ liệu từ server:', data); // Log dữ liệu kiểm tra

                        // Kiểm tra dữ liệu và xử lý
                        if (Array.isArray(data) && data.length > 0) {
                            // Sắp xếp dữ liệu theo thứ tự tháng tăng dần
                            const sortedData = data.sort((a, b) => a.month - b.month);

                            // Tạo danh sách các tháng và doanh thu theo thứ tự
                            const months = sortedData.map(item => {
                                const monthNames = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
                                    "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
                                ];
                                return monthNames[item.month - 1]; // Chuyển số tháng thành tên tháng
                            });
                            const revenues = sortedData.map(item => parseFloat(item.total_revenue) ||
                                0); // Đảm bảo giá trị là số

                            // Cập nhật tổng doanh thu
                            const totalRevenue = revenues.reduce((a, b) => a + b, 0);
                            document.getElementById('totalRevenue').textContent = `${totalRevenue.toLocaleString()}₫`;

                            // Tính toán các giá trị trục Y
                            const maxRevenue = Math.max(...revenues);
                            const roundedMaxRevenue = Math.ceil(maxRevenue / 50000000) *
                                50000000; // Làm tròn lên bội số 50 triệu

                            // Tạo biểu đồ
                            renderChart(months, revenues, roundedMaxRevenue);
                        } else {
                            // Không có dữ liệu
                            document.getElementById('totalRevenue').textContent = 'Chưa có dữ liệu';
                            renderChart([], []); // Tạo biểu đồ rỗng
                        }
                    });

                    // Hàm tạo biểu đồ cột
                    function renderChart(months, revenues, roundedMaxRevenue) {
                        const options = {
                            chart: {
                                height: 350,
                                type: 'bar',
                                toolbar: {
                                    show: false
                                },
                                animations: {
                                    enabled: true,
                                    easing: 'easeinout',
                                    speed: 1500,
                                    animateGradually: {
                                        enabled: true,
                                        delay: 300
                                    }
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false, // Chế độ dọc cho biểu đồ cột
                                    columnWidth: '60%', // Độ rộng cột
                                    endingShape: 'rounded' // Tạo đường viền mượt cho cột
                                }
                            },
                            series: [{
                                name: 'Doanh Thu',
                                data: revenues
                            }],
                            xaxis: {
                                categories: months,
                                title: {
                                    style: {
                                        fontSize: '14px',
                                        fontWeight: 'bold',
                                        color: '#6c757d'
                                    }
                                },
                                labels: {
                                    rotate: -45,
                                    style: {
                                        fontSize: '12px',
                                        fontWeight: '400',
                                        colors: '#6c757d'
                                    }
                                }
                            },
                            yaxis: {
                                title: {
                                    style: {
                                        fontSize: '14px',
                                        fontWeight: 'bold',
                                        color: '#6c757d'
                                    }
                                },
                                labels: {
                                    show: true,
                                    formatter: function(val) {
                                        return `${Math.round(val / 1000000)}Triệu`; // Hiển thị giá trị đã làm tròn (triệu VND)
                                    }
                                },
                                min: 0,
                                max: roundedMaxRevenue, // Sử dụng giá trị đã làm tròn
                                tickAmount: Math.ceil(roundedMaxRevenue /
                                    10000000), // Cập nhật số lượng mốc tick (ví dụ 10M, 20M, 30M,...)
                            },
                            tooltip: {
                                enabled: true,
                                y: {
                                    formatter: function(val) {
                                        return `${val.toLocaleString()}₫`; // Hiển thị giá trị tiền tệ khi nhấp vào cột
                                    }
                                },
                                theme: 'dark',
                                style: {
                                    fontSize: '12px',
                                    fontWeight: '500'
                                }
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'dark',
                                    type: 'horizontal',
                                    shadeIntensity: 0.5,
                                    gradientToColors: ['#FF7F50'], // Màu gradient chuyển đến
                                    inverseColors: false,
                                    opacityFrom: 1, // Màu sắc mạnh mẽ
                                    opacityTo: 0.5, // Màu sắc nhạt dần
                                    stops: [0, 100]
                                }
                            },
                            colors: ['#FF4500'], // Màu sắc đậm cho các cột
                            grid: {
                                show: true,
                                borderColor: '#e0e6ed',
                                strokeDashArray: 5,
                                xaxis: {
                                    lines: {
                                        show: true
                                    }
                                },
                                yaxis: {
                                    lines: {
                                        show: true
                                    }
                                }
                            },
                            responsive: [{
                                breakpoint: 768,
                                options: {
                                    chart: {
                                        height: 250
                                    },
                                    xaxis: {
                                        labels: {
                                            rotate: -30
                                        }
                                    }
                                }
                            }],
                            dataLabels: {
                                enabled: false // Tắt hiển thị số trên cột
                            }
                        };

                        // Khởi tạo ApexCharts
                        const chart = new ApexCharts(document.querySelector("#salesGraph"), options);
                        chart.render();
                    }
                </script>
            </div>


            <div class="row">
                <div class="col-sm-6 col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            {{-- <h5 class="card-title text-center text-secondary">Thống Kê Đặt Bàn</h5> --}}
                        </div>
                        <div class="card-body" style="height: 350px;">
                            <div class="row justify-content-center text-center">
                                <!-- Biểu đồ tỷ lệ khách cọc và không cọc -->
                                <div class="col-md-6">
                                    <h6>Khách Cọc và Không Cọc</h6>
                                    <canvas id="depositStatusChart"
                                        style="max-width: 300px; width: 100%; display: block; margin: 0 auto;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biểu đồ trạng thái đặt bàn - Pie Chart mới -->
                <div class="col-sm-6 col-12">
                    <div class="card shadow">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row text-center justify-content-center">
                                <div class="col-md-6">
                                    <h6>Trạng Thái Đặt Bàn</h6>
                                    <div id="basic-pie-graph-gradient"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Gradient helper function
                function createGradient(ctx, color1, color2) {
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, color1);
                    gradient.addColorStop(1, color2);
                    return gradient;
                }

                const ctx1 = document.getElementById('depositStatusChart').getContext('2d');
                new Chart(ctx1, {
                    type: 'polarArea', // Thay đổi loại biểu đồ thành Polar Area
                    data: {
                        labels: ['Khách Cọc', 'Khách Không Cọc'],
                        datasets: [{
                            data: [{{ $coc }}, {{ $khongCoc }}],
                            backgroundColor: [
                                createGradient(ctx1, '#8E2DE2', '#4A00E0'),
                                createGradient(ctx1, '#F953C6', '#B91D73')
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => `${context.label}: ${context.raw}%`
                                }
                            }
                        }
                    }
                });
            </script>
            <script>
                // Dữ liệu từ controller
                var confirmed = {!! json_encode($confirmed) !!};
                var pending = {!! json_encode($pending) !!};
                var cancelled = {!! json_encode($cancelled) !!};
                var refund = {!! json_encode($refund) !!};

                var options = {
                    chart: {
                        width: 420,
                        type: 'pie',
                    },
                    labels: ['Confirmed', 'Pending', 'Cancelled', 'Refund'],
                    series: [confirmed, pending, cancelled, refund], // Dữ liệu biểu đồ từ controller
                    responsive: [{
                        breakpoint: 450,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }],
                    stroke: {
                        width: 0,
                    },
                    fill: {
                        type: 'gradient',
                    },
                    colors: ['#435EEF', '#2b86f5', '#63a9ff', '#95c5ff'], // Màu sắc cho các phần trong biểu đồ
                };

                var chart = new ApexCharts(
                    document.querySelector("#basic-pie-graph-gradient"),
                    options
                );
                chart.render();
            </script>
            <!-- Row end -->


            <!-- Row start -->
            <div class="row">
                <div class="col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Giao Dịch</div>
                        </div>
                        <div class="card-body" style="height: 425px">
                            <div class="scroll370">
                                <div class="transactions-container">
                                    <!-- Giao Dịch MoMo -->
                                    <div class="transaction-block">
                                        <div class="transaction-icon ">
                                            <img src="data:image/png;base64,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"
                                                alt="MoMo" style="width: 37px; height: 37px;">
                                            <!-- Thay bằng logo MoMo -->
                                        </div>
                                        <div class="transaction-details">
                                            <h4>MoMo</h4>
                                            <p class="text-truncate">Thanh Toán Đơn Hàng</p>
                                        </div>

                                    </div>

                                    <!-- Giao Dịch VNPay -->
                                    <div class="transaction-block">
                                        <div class="transaction-icon ">
                                            <img src="data:image/png;base64,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"
                                                alt="VNPay" style="width: 37px; height: 37px;">
                                            <!-- Thay bằng logo VNPay -->
                                        </div>
                                        <div class="transaction-details">
                                            <h4>VNPay</h4>
                                            <p class="text-truncate">Thanh Toán Thực Phẩm</p>
                                        </div>

                                    </div>


                                    <!-- Giao Dịch Thẻ Tín Dụng -->
                                    <div class="transaction-block">
                                        <div class="transaction-icon ">
                                            <img src="https://banner2.cleanpng.com/20180810/uqi/9d06e2fa1413a8a2e29f61ef3afd67b9.webp"
                                                alt="Thẻ Tín Dụng" style="width: 37px; height: 37px;">
                                            <!-- Thay bằng logo Thẻ Tín Dụng -->
                                        </div>
                                        <div class="transaction-details">
                                            <h4>Thẻ Tín Dụng</h4>
                                            <p class="text-truncate">Thanh Toán Đơn Hàng</p>
                                        </div>

                                    </div>

                                    <!-- Giao Dịch VietQR -->
                                    <div class="transaction-block">
                                        <div class="transaction-icon ">
                                            <img src="https://taichinhvisa.vn/wp-content/uploads/2024/04/tao-ma-qr-tren-viet-qr.jpeg"
                                                alt="VietQR" style="width: 40px; height: 37px;">
                                            <!-- Thay bằng logo VietQR -->
                                        </div>
                                        <div class="transaction-details">
                                            <h4>VietQR</h4>
                                            <p class="text-truncate">Thanh Toán Thực Phẩm</p>
                                        </div>

                                    </div>
                                    <!-- Giao Dịch QR Code -->
                                    <div class="transaction-block">
                                        <div class="transaction-icon ">
                                            <img src="https://generator.1qr.fr/?id=25ko0&size=7&date=201907&cache=1"
                                                alt="QR Code" style="width: 37px; height: 37px;">
                                            <!-- Thay bằng logo QR Code -->
                                        </div>
                                        <div class="transaction-details">
                                            <h4>QR Code</h4>
                                            <p class="text-truncate">Thanh Toán Qua QR</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Đơn Hàng Trong Bếp</div>
                        </div>
                        <div class="card-body">
                            <div id="taskGraph"></div>
                            <ul class="task-list-container">
                                <li class="task-list-item">
                                    <div class="task-icon shade-blue">
                                        <i class="bi bi-clipboard-plus"></i>
                                    </div>
                                    <div class="task-info">
                                        <h5 class="task-title">Mới</h5>
                                        <p class="amount-spend" id="newOrders">{{ $newOrders }}</p>
                                    </div>
                                </li>
                                <li class="task-list-item">
                                    <div class="task-icon shade-yellow">
                                        <i class="bi bi-hourglass-split"></i>
                                    </div>
                                    <div class="task-info">
                                        <h5 class="task-title">Đang Chế Biến</h5>
                                        <p class="amount-spend" id="cookingOrders">{{ $cookingOrders }}
                                        </p>
                                    </div>
                                </li>
                                <li class="task-list-item">
                                    <div class="task-icon shade-green">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                    <div class="task-info">
                                        <h5 class="task-title">Hoàn Thành</h5>
                                        <p class="amount-spend" id="completedOrders">
                                            {{ $completedOrders }}</p>
                                    </div>
                                </li>
                            </ul>
                            <script>
                                var options = {
                                    chart: {
                                        height: 300,
                                        type: 'radialBar',
                                        toolbar: {
                                            show: false,
                                        },
                                    },
                                    plotOptions: {
                                        radialBar: {
                                            dataLabels: {
                                                name: {
                                                    fontSize: '12px',
                                                    fontColor: 'black',
                                                    fontFamily: 'Merriweather',
                                                },
                                                value: {
                                                    fontSize: '21px',
                                                    fontFamily: 'Merriweather',
                                                },
                                                total: {
                                                    show: true,
                                                    label: 'Tổng số đơn hàng',
                                                    formatter: function(w) {
                                                        // Hiển thị tổng số đơn hàng
                                                        return '{{ $newOrders + $cookingOrders + $completedOrders }}'; // Tổng số đơn hàng
                                                    }
                                                }
                                            }
                                        }
                                    },
                                    // Dữ liệu động từ PHP
                                    series: [{{ $newOrders }}, {{ $cookingOrders }}, {{ $completedOrders }}],
                                    labels: ['Mới', 'Đang Chế Biến', 'Hoàn Thành'],
                                    colors: ['#4267cd', '#32b2fa', '#f87957'],
                                };

                                var chart = new ApexCharts(
                                    document.querySelector("#taskGraph"),
                                    options
                                );
                                chart.render();
                            </script>
                        </div>
                    </div>


                </div>
            </div>


        </div>

        <!-- Row end -->

    @endsection
