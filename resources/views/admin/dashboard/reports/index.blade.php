@extends('admin.master')

@section('title', 'Báo Cáo Thống kê')

@section('content')
    <div class="content-wrapper-scroll">
        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <div class="row">
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-blue" style="font-size: 20px; padding: 10px;">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-blue" style="font-size: 18px;">{{ number_format($totalReservations) }}</h3>
                            <p style="font-size: 12px;">Đơn Đặt Bàn</p>
                        </div>
                        <div class="sale-graph" style="height: 50px;">
                            <div id="sparklineLine1"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-blue" style="font-size: 20px; padding: 10px;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-blue" style="font-size: 18px;">{{ number_format($totalGuests) }}</h3>
                            <p style="font-size: 12px;">Số Lượng Khách</p>
                        </div>
                        <div class="sale-graph" style="height: 50px;">
                            <div id="sparklineLine2"></div>
                        </div>
                    </div>
                </div>


                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-green" style="font-size: 20px; padding: 10px;">
                            <i class="fas fa-pizza-slice"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-green" style="font-size: 18px;">{{ $dishCount }}</h3>
                            <p style="font-size: 12px;">Món Ăn</p>
                        </div>
                        <div class="sale-graph" style="height: 50px;">
                            <div id="sparklineLine3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-green" style="font-size: 20px; padding: 10px;">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-green" style="font-size: 18px;">{{ $orderCount }}</h3>
                            <p style="font-size: 12px;">Hoá Đơn</p>
                        </div>
                        <div class="sale-graph" style="height: 50px;">
                            <div id="sparklineLine4"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr" style="font-size: 20px; padding: 10px;">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <div class="sale-details">
                            <h5 style="font-size: 14px;">Tổng Tiền Cọc</h5>
                            <h3 class="text-blue" style="font-size: 16px;">
                                {{ number_format($totalDeposit, 0, ',', '.') }} VND
                            </h3>
                            <p class="growth text-blue" style="font-size: 12px;">
                                @if ($totalDepositAll > 0)
                                    Tỷ lệ cọc: {{ number_format($depositPercentage, 2) }}%
                                @else
                                    Không có đơn đặt cọc
                                @endif
                            </p>
                        </div>
                    </div>
                </div>




                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr" style="font-size: 20px; padding: 10px;">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </div>
                        <div class="sale-details">
                            <h5 style="font-size: 14px;">Tổng Tiền Hoàn Lại</h5>
                            <h3 class="text-blue" style="font-size: 16px;">
                                {{ number_format($totalRefund, 0, ',', '.') }} VND
                            </h3>
                            <p class="growth text-blue" style="font-size: 12px;">
                                Tỷ lệ hoàn lại: {{ number_format($refundPercentage, 2) }}%
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr" style="font-size: 20px; padding: 10px;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="sale-details">
                            <h5 style="font-size: 14px;">Lợi Nhuận</h5>
                            <h3 class="text-blue" style="font-size: 16px;">
                                @if ($totalProfit < 0)
                                    <span class="text-red">{{ number_format(abs($totalProfit), 0, '.', ',') }} VND</span>
                                @else
                                    {{ number_format($totalProfit, 0, '.', ',') }} VND
                                @endif
                            </h3>
                            <p class="growth {{ $profitChangePercentage < 0 ? 'text-red' : 'text-green' }}"
                                style="font-size: 12px;">
                                {{ $profitChangePercentage < 0 ? 'Giảm' : 'Tăng' }}
                                {{ number_format(abs($profitChangePercentage), 1) }}%
                            </p>
                        </div>
                    </div>
                </div>


                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr blue" style="font-size: 20px; padding: 10px;">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="sale-details">
                            <h5 style="font-size: 14px;">Doanh Thu</h5>
                            <h3 class="text-blue" style="font-size: 16px;">
                                @if ($totalRevenue < 0)
                                    <span class="text-red">{{ number_format(abs($totalRevenue), 0, '.', ',') }} VND</span>
                                @else
                                    {{ number_format($totalRevenue, 0, '.', ',') }} VND
                                @endif
                            </h3>
                            <p class="growth {{ $revenueChangePercentage < 0 ? 'text-red' : 'text-green' }}"
                                style="font-size: 12px;">
                                {{ $revenueChangePercentage < 0 ? 'Giảm' : 'Tăng' }}
                                {{ number_format(abs($revenueChangePercentage), 1) }}%
                            </p>
                        </div>
                    </div>
                </div>


            </div>




            <div class="row">
                <div class="col-xxl-6 col-12" style="width: 65%;">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0" style="font-size: 14px">Thống Kê Số Lượng</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="layeredMountainChart" height="420"></canvas>
                        </div>

                    </div>

                </div>

                <div class="col-xxl-6 col-12" style="width: 35%;">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="basic-pie-graph-gradient"></div>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="basic-pie-graph-monochrome-gradient"></div>
                        </div>
                    </div>
                </div>
            </div>




            <div class="row">
                <div class="col-xxl-6 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            {{-- <h5 class="card-title mb-0">Biểu đồ </h5> --}}
                        </div>
                        <div class="card-body">
                            <div id="horizontalChart"></div>
                        </div>
                    </div>

                </div>

                <div class="col-xxl-6 col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="pieChart"></div>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="barChart"></div>
                        </div>
                    </div>

                </div>
                <script>
                    // Giả sử bạn đã có các biến totalRefund và totalRefundAll từ PHP
                    var totalRefund = @json($totalRefund); // Tổng tiền hoàn lại
                    var totalRefundAll = @json($totalRefundAll); // Tổng tiền hoàn lại cho cả hai trạng thái

                    // Tính tỷ lệ phần trăm hoàn lại
                    var refundPercentage = 0;
                    if (totalRefundAll > 0) {
                        refundPercentage = (totalRefund / totalRefundAll) * 100;
                    }

                    // Dữ liệu cho biểu đồ tròn
                    var options = {
                        chart: {
                            type: 'pie',
                            height: 170
                        },
                        series: [refundPercentage, 100 - refundPercentage], // Phần hoàn lại và phần chưa hoàn lại
                        labels: ['Hoàn lại', 'Chưa hoàn lại'],
                        colors: ['#00E396', '#F4511E'], // Màu sắc cho các phần trong biểu đồ

                        title: {
                            text: 'Tỷ lệ hoàn lại (%)',
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold',
                                textAlign: 'left', // Căn trái
                            }

                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function(val) {
                                return `${val.toFixed(2)}%`; // Định dạng tỷ lệ phần trăm
                            }
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#pieChart"), options);
                    chart.render();
                </script>
                <script>
                    // Giả sử bạn đã có các biến totalRefund, totalPendingRefund và totalRefundAll từ PHP
                    var totalRefund = @json($totalRefund); // Tổng tiền hoàn lại thực tế
                    var totalPendingRefund = @json($totalPendingRefund); // Tổng tiền chưa hoàn lại
                    var totalRefundAll = @json($totalRefundAll); // Tổng số tiền hoàn lại cho cả hai trạng thái

                    // Dữ liệu cho biểu đồ cột
                    var chartData = {
                        series: [{
                            name: 'Hoàn lại',
                            data: [totalRefund]
                        }, {
                            name: 'Chưa hoàn lại',
                            data: [totalPendingRefund]
                        }],
                        labels: ['Tổng số tiền hoàn lại'],
                        colors: ['#00E396', '#F4511E'],
                        dataLabelsFormatter: function(val) {
                            return val.toLocaleString('vi-VN') + ' VNĐ'; // Định dạng số tiền
                        }
                    };

                    // Cấu hình cho biểu đồ cột
                    var barOptions = {
                        chart: {
                            type: 'bar',
                            height: 285
                        },
                        series: chartData.series,
                        labels: chartData.labels,
                        colors: chartData.colors,
                        title: {
                            text: 'Tổng số tiền hoàn lại',
                            align: 'center',
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: chartData.dataLabelsFormatter
                        }
                    };

                    // Tạo biểu đồ cột
                    var barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);
                    barChart.render();

                    // Hàm để cập nhật dữ liệu khi nhấp vào nút "Hoàn lại" hoặc "Chưa hoàn lại"
                    function updateChartData(refundType) {
                        if (refundType === 'refund') {
                            // Chọn "Hoàn lại"
                            barChart.updateSeries([{
                                name: 'Hoàn lại',
                                data: [totalRefund]
                            }, {
                                name: 'Chưa hoàn lại',
                                data: [totalRefundAll - totalRefund]
                            }]);
                        } else if (refundType === 'pending') {
                            // Chọn "Chưa hoàn lại"
                            barChart.updateSeries([{
                                name: 'Hoàn lại',
                                data: [0] // Không hiển thị "Hoàn lại"
                            }, {
                                name: 'Chưa hoàn lại',
                                data: [totalPendingRefund]
                            }]);
                        }
                    }

                    // Sự kiện nhấn vào nút "Hoàn lại"
                    document.querySelector("#btnRefund").addEventListener('click', function() {
                        updateChartData('refund');
                    });

                    // Sự kiện nhấn vào nút "Chưa hoàn lại"
                    document.querySelector("#btnPendingRefund").addEventListener('click', function() {
                        updateChartData('pending');
                    });
                </script>

            </div>


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>



            {{-- <div class="row">
                <div class="col-xxl-6 col-12" style="width: 65%;">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4" style="height: 510px;">
                            <!-- Biểu đồ doanh thu theo tháng -->
                            <canvas id="monthlyRevenueChart2"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-12" style="width: 35%;">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="basic-donut-graph" class="auto-align-graph"></div>
                        </div>

                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="basic-pie-graph" class="auto-align-graph"></div>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <!--Doanh thu combo theo tháng-->
            <script>
                // Dữ liệu từ controller
                const months = {!! json_encode($monthLabels) !!};
                const revenues = {!! json_encode($monthData) !!};

                // Lấy ngữ cảnh vẽ biểu đồ cho mỗi biểu đồ riêng biệt
                const chartContext2 = document.getElementById('monthlyRevenueChart2').getContext('2d');

                const revenueChart2 = new Chart(chartContext2, {
                    type: 'line', // Loại biểu đồ đường
                    data: {
                        labels: months, // Dữ liệu cho trục x (tháng)
                        datasets: [{
                            label: 'Doanh thu (VND)', // Tiêu đề của bộ dữ liệu
                            data: revenues, // Dữ liệu cho trục y (doanh thu)
                            borderColor: 'rgba(75, 192, 192, 1)', // Màu đường biểu đồ (màu xanh ngọc)
                            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Màu nền dưới đường (màu xanh ngọc nhạt)
                            borderWidth: 3, // Độ dày đường
                            tension: 0.4, // Độ cong của đường
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom', // Di chuyển legend xuống dưới biểu đồ
                                labels: {
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Doanh thu Combo theo tháng',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                },
                                align: 'left', // Căn trái cho tiêu đề
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,

                                    font: {
                                        size: 14
                                    }
                                },
                                ticks: {
                                    callback: function(value) {
                                        // Định dạng số tiền thành dạng "0 Triệu", "5 Triệu", "10 Triệu"...
                                        return value >= 1000000 ? (value / 1000000) + ' Triệu' : value;
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,


                                }
                            }
                        }
                    }
                });
            </script>
            <!--combo-->
            <script>
                // Dữ liệu từ controller
                const activeComboCount = {!! json_encode($activeComboCount) !!};
                const inactiveComboCount = {!! json_encode($inactiveComboCount) !!};

                // Cấu hình biểu đồ Donut
                var options = {
                    chart: {
                        width: 250,
                        type: 'donut', // Biểu đồ donut
                    },
                    labels: ['Combo Đang Hoạt Động', 'Combo Đã Ngừng Bán'], // Tiếng Việt cho nhãn
                    series: [activeComboCount, inactiveComboCount], // Dữ liệu số lượng combo
                    legend: {
                        position: 'bottom',
                    },
                    dataLabels: {
                        enabled: false // Tắt dữ liệu hiển thị trong các phần của donut
                    },
                    stroke: {
                        width: 0, // Không có viền xung quanh các phần trong donut
                    },
                    colors: ['#36a2eb', '#ff6384'], // Màu cho các phần Combo đang hoạt động và Combo đã ngừng bán
                }

                var chart = new ApexCharts(
                    document.querySelector("#basic-donut-graph"),
                    options
                );

                chart.render();
            </script>
           <!--Món-->
            <script>
                // Dữ liệu từ controller
                const activeDishesCount = {!! json_encode($activeDishesCount) !!};
                const inactiveDishesCount = {!! json_encode($inactiveDishesCount) !!};

                // Cấu hình biểu đồ Pie
                var options = {
                    chart: {
                        width: 250,
                        type: 'pie', // Biểu đồ Pie
                    },
                    labels: ['Món Ăn Đang Hoạt Động', 'Món Ăn Đã Ngừng Bán'], // Nhãn hiển thị
                    series: [activeDishesCount, inactiveDishesCount], // Dữ liệu số lượng món ăn
                    legend: {
                        position: 'bottom', // Vị trí chú thích
                    },
                    dataLabels: {
                        enabled: false, // Tắt hiển thị dữ liệu trên biểu đồ
                    },
                    stroke: {
                        width: 0, // Không có viền
                    },
                    colors: ['#4caf50', '#f44336'], // Màu sắc cho Active và Inactive
                };

                // Render biểu đồ Pie
                var chart = new ApexCharts(
                    document.querySelector("#basic-pie-graph"),
                    options
                );

                chart.render();
            </script> --}}





            <div class="row">
                <div class="card"
                    style="width: 1230px; margin-left: 14px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="card-header" style="background-color: #f8f9fa; border-radius: 10px 10px 0 0;">
                        <div class="card-title" style="font-size: 14px; font-weight: bold; color: #333;">Biểu Đồ Doanh Thu
                            Năm 2024</div>
                    </div>
                    <div class="card-body" style="background-color: #ffffff; border-radius: 0 0 10px 10px;">
                        <div id="salesGraph" class="auto-align-graph" style="border-radius: 10px;"></div>
                        <div class="num-stats text-center">
                            <h5 id="totalRevenue" style="color: #34db63; font-size: 22px; font-weight: 600;">0₫.00</h5>
                            <!-- Tổng doanh thu -->
                        </div>
                        <p class="text-center mt-2" style="color: #888888; font-size: 14px;"> Các Tháng Trong Năm 2024</p>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const data = @json($revenues); // Dữ liệu từ server
                        console.log('Dữ liệu từ server:', data); // Log dữ liệu kiểm tra

                        if (Array.isArray(data) && data.length > 0) {
                            const sortedData = data.sort((a, b) => a.month - b.month); // Sắp xếp theo tháng
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
                            document.getElementById('totalRevenue').textContent = 'Chưa có dữ liệu';
                            renderChart([], []); // Tạo biểu đồ rỗng
                        }
                    });

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
                                    columnWidth: '55%', // Độ rộng cột mỏng hơn
                                    endingShape: 'rounded' // Đường viền mượt cho cột
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
                                        fontSize: '16px',
                                        fontWeight: 'bold',
                                        color: '#333333'
                                    }
                                },
                                labels: {
                                    rotate: -45,
                                    style: {
                                        fontSize: '13px',
                                        fontWeight: '400',
                                        colors: '#333333'
                                    }
                                }
                            },
                            yaxis: {
                                title: {
                                    style: {
                                        fontSize: '16px',
                                        fontWeight: 'bold',
                                        color: '#333333'
                                    }
                                },
                                labels: {
                                    show: true,
                                    formatter: function(val) {
                                        return `${Math.round(val / 1000000)} Triệu`; // Hiển thị giá trị làm tròn
                                    }
                                },
                                min: 0,
                                max: roundedMaxRevenue,
                                tickAmount: Math.ceil(roundedMaxRevenue / 10000000), // Mốc tick theo triệu VND
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
                                    fontSize: '14px',
                                    fontWeight: '600'
                                }
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'light',
                                    type: 'horizontal',
                                    shadeIntensity: 0.6,
                                    gradientToColors: ['#34db63'], // Màu xanh nước biển
                                    inverseColors: false,
                                    opacityFrom: 1,
                                    opacityTo: 0.7,
                                    stops: [0, 100]
                                }
                            },
                            colors: ['#34db63'], // Màu xanh nước biển cho các cột
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
                                enabled: false
                            } // Tắt hiển thị số trên cột
                        };

                        // Khởi tạo ApexCharts
                        const chart = new ApexCharts(document.querySelector("#salesGraph"), options);
                        chart.render();
                    }
                </script>
            </div>
























            {{-- <div class="row">
                <h5 class="fw-bold py-3 mb-4"> Thống Kê Kho</h5>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-blue">
                            <i class="bi bi-pie-chart"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-blue">250k</h3>
                            <p>Nhà Cung Cấp</p>
                        </div>
                        <div class="sale-graph">
                            <div id="sparklineLine1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-blue">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-blue">24m</h3>
                            <p>Nguyên Liệu</p>
                        </div>
                        <div class="sale-graph">
                            <div id="sparklineLine2"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-blue">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-blue">15k</h3>
                            <p>Phiếu Nhập Kho</p>
                        </div>
                        <div class="sale-graph">
                            <div id="sparklineLine3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-green">
                            <i class="bi bi-handbag"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-green">180m</h3>
                            <p>Hàng Tồn Kho</p>
                        </div>
                        <div class="sale-graph">
                            <div id="sparklineLine4"></div>
                        </div>
                    </div>
                </div>
            </div> --}}


































        </div>
    </div>

    <script>
        const reservationsData = @json(array_values($reservations));
        const guestsData = @json(array_values($guests));

        const ctx = document.getElementById('layeredMountainChart').getContext('2d');

        // Hàm tạo gradient
        const createGradient = (ctx, color1, color2) => {
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        };

        const gradient1 = createGradient(ctx, 'rgba(0, 123, 255, 0.9)', 'rgba(0, 123, 255, 0.1)');
        const gradient2 = createGradient(ctx, 'rgba(0, 181, 204, 0.8)', 'rgba(0, 181, 204, 0.1)');

        // Cấu hình biểu đồ
        new Chart(ctx, {
            type: 'bar', // Biểu đồ cột
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                ],

                datasets: [{
                        label: 'Số lượng đặt bàn',
                        data: reservationsData,
                        backgroundColor: gradient1,
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Số lượng khách',
                        data: guestsData,
                        backgroundColor: gradient2,
                        borderColor: 'rgba(0, 181, 204, 1)',
                        borderWidth: 1
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom', // Di chuyển các ô ra dưới biểu đồ
                        labels: {
                            color: '#333',
                            font: {
                                size: 14,
                                family: "'Poppins', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#eee',
                        cornerRadius: 5,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#666',
                            font: {
                                size: 12, // Cỡ chữ nhỏ cho nhãn tháng

                            },
                            // Đảm bảo nhãn tháng không bị nghiêng
                            maxRotation: 0, // Không xoay nhãn
                            minRotation: 0, // Không xoay nhãn
                            autoSkip: false, // Đảm bảo hiển thị tất cả nhãn tháng
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#666',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(200, 200, 200, 0.5)',
                            lineWidth: 0.5
                        }
                    }
                }
            }
        });
    </script>







    <!--Bieu do coc -->
    <script>
        // Dữ liệu từ Controller PHP
        var depositData = {
            coc: {{ $coc }},
            khongCoc: {{ $khongCoc }}
        };

        // Cấu hình biểu đồ ApexCharts
        var options = {
            chart: {
                width: 370,
                type: 'pie',
            },
            labels: ['Khách Cọc', 'Khách Không Cọc'], // Nhãn cho biểu đồ
            series: [depositData.coc, depositData.khongCoc], // Truyền dữ liệu động vào series
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'right' // Đặt legend bên phải biểu đồ
                    }
                }
            }],
            stroke: {
                width: 0, // Không có viền cho biểu đồ
            },
            title: {
                text: "So sánh ", // Tiêu đề của biểu đồ
            },
            fill: {
                type: 'gradient', // Hiệu ứng gradient cho phần pie
            },
            colors: ['#2b86f5', '#95c5ff'], // Màu gradient cho các phần cọc và không cọc
            legend: {
                position: 'right', // Đặt legend bên phải biểu đồ
                fontSize: '14px',
                labels: {
                    colors: '#000',
                }
            }
        };

        // Khởi tạo biểu đồ
        var chart = new ApexCharts(
            document.querySelector("#basic-pie-graph-gradient"), // Vị trí render biểu đồ
            options
        );

        chart.render();
    </script>

    <script>
        // Lấy dữ liệu từ Controller (PHP) và chuyển thành mảng JavaScript
        var dishCounts = @json($dishCounts); // Dữ liệu từ Controller

        // Chuyển đổi danh mục và số lượng món ăn thành mảng cho biểu đồ
        var categories = dishCounts.map(function(category) {
            return category.dish_count;
        });

        var categoryNames = dishCounts.map(function(category) {
            return category.name;
        });

        // Cấu hình biểu đồ ApexCharts
        var options = {
            chart: {
                width: 338, // Độ rộng của biểu đồ
                type: 'pie', // Loại biểu đồ
            },
            series: categories, // Dữ liệu cho biểu đồ
            labels: categoryNames, // Nhãn cho các phần trong biểu đồ
            fill: {
                type: 'gradient', // Hiệu ứng gradient cho phần pie
            },
            theme: {
                monochrome: {
                    enabled: true, // Bật chế độ màu đơn sắc
                    color: '#435EEF', // Màu sắc cho phần biểu đồ
                }
            },
            title: {
                text: "Thống kê Loại Món", // Tiêu đề của biểu đồ
            },
            responsive: [{
                breakpoint: 480, // Khi màn hình nhỏ hơn 480px
                options: {
                    chart: {
                        width: 200 // Giảm chiều rộng biểu đồ
                    },
                    legend: {
                        position: 'bottom' // Đặt legend dưới biểu đồ
                    }
                }
            }],
            stroke: {
                width: 0, // Không có viền cho các phần trong biểu đồ
            },
        }

        // Khởi tạo và hiển thị biểu đồ
        var chart = new ApexCharts(
            document.querySelector("#basic-pie-graph-monochrome-gradient"), // Vị trí render biểu đồ
            options // Cấu hình biểu đồ
        );
        chart.render(); // Hiển thị biểu đồ
    </script>

    {{-- Cọc và số lượng khách cọc --}}
    <script>
        // Chuyển dữ liệu PHP sang JavaScript
        var chartData = @json($data);

        // Kiểm tra dữ liệu chartData
        console.log(chartData); // In ra để kiểm tra dữ liệu

        // Chuyển đổi dữ liệu thành mảng x-axis (tháng) và series (tổng số khách)
        var months = chartData.map(item => `Tháng ${item.month}`);
        var totalGuests = chartData.map(item => item.total_guests);
        var totalDeposits = chartData.map(item => item.total_deposits || 0); // Nếu không có tiền cọc, gán giá trị mặc định

        // Cấu hình biểu đồ chỉ hiển thị số lượng khách
        var options = {
            chart: {
                type: 'bar',
                height: 500,
                stacked: false,
                toolbar: {
                    show: true
                }
            },

            series: [{
                name: 'Tổng Kêt',
                data: totalGuests
            }],
            colors: ['#1E88E5'], // Màu sắc cho cột
            plotOptions: {
                bar: {
                    horizontal: false, // Biểu đồ cột thẳng
                    barHeight: '50%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            xaxis: {
                categories: months,
                title: {
                    text: 'Các Tháng Trong Năm 2024',
                    style: {
                        fontWeight: 'bold'
                    }
                }
            },
            yaxis: {
                title: {

                    style: {
                        fontWeight: 'bold'
                    }
                }
            },
            title: {
                text: 'Thống kê Số Lượng Khách và Đặt Cọc',
                align: 'center',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold'
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toLocaleString(); // Định dạng số lượng khách có dấu phân cách
                },
                style: {
                    colors: ['#304758']
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center'
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 5
            },
            tooltip: {
                theme: 'light',
                shared: true, // Hiển thị tất cả series khi hover
                intersect: false, // Hiển thị tooltip khi hover vào bất kỳ phần nào của cột
                y: {
                    formatter: function(val, {
                        seriesIndex,
                        series,
                        dataPointIndex
                    }) {
                        // Lấy dữ liệu từ series
                        var monthYear =
                            `Năm ${chartData[dataPointIndex].year}`;
                        var guests = series[seriesIndex][dataPointIndex].toLocaleString();

                        // Dùng một điều kiện để kiểm tra nếu seriesIndex là 0 (tức là series "Tổng số khách")
                        var deposits = chartData[dataPointIndex].total_deposits ? chartData[dataPointIndex]
                            .total_deposits.toLocaleString() : 'Chưa có cọc';

                        return `<strong>${monthYear}</strong><br>
                    <strong>Tổng số khách: </strong>${guests}<br>
                  <strong>Tổng số tiền cọc: </strong>${new Intl.NumberFormat('vi-VN').format(deposits)} VNĐ`;

                    }
                }
            }

        };

        var chart = new ApexCharts(document.querySelector("#horizontalChart"), options);
        chart.render();
    </script>






@endsection
