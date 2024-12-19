@extends('admin.master')

@section('title', 'Thống kê Kho')

@section('content')
    <div class="content-wrapper-scroll">
        <!-- Content wrapper start -->
        <div class="content-wrapper">



            {{-- <div class="row">
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-blue" style="font-size: 20px; padding: 10px;">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-blue" style="font-size: 18px;">{{ $supplierCount }}</h3>
                            <p style="font-size: 12px;">Nhà Cung Cấp</p>
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
                            <h3 class="text-blue" style="font-size: 18px;">{{ $ingredientCount }}</h3>
                            <p style="font-size: 12px;">Nguyên Liệu</p>
                        </div>
                        <div class="sale-graph" style="height: 50px;">
                            <div id="sparklineLine2"></div>
                        </div>
                    </div>
                </div>


                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-yellow" style="font-size: 20px; padding: 10px;">
                            <i class="fas fa-pizza-slice"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-yellow" style="font-size: 18px;">{{ $transactionCount }}</h3>
                            <p style="font-size: 12px;">Phiếu Nhập Kho</p>
                        </div>
                        <div class="sale-graph" style="height: 50px;">
                            <div id="sparklineLine3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon shade-yellow" style="font-size: 20px; padding: 10px;">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                        <div class="sale-details">
                            <h3 class="text-yellow" style="font-size: 18px;">{{ $stockCount }}</h3>
                            <p style="font-size: 12px;">Hàng Tồn Kho</p>
                        </div>
                        <div class="sale-graph" style="height: 50px;">
                            <div id="sparklineLine4"></div>
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="row">
                <!-- Nhà Cung Cấp -->
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr blue">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                        <div class="sale-details">
                            <h6>Nhà Cung Cấp</h6>
                            <h3 class="text-blue" style="font-size: 16px">{{ $supplierCount }}</h3>
                            <p class="growth text-blue">
                                Tăng trưởng: {{ number_format($supplierChangePercent, 2) }}%
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Nguyên Liệu -->
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr green">
                            <i class="bi bi-box"></i>
                        </div>
                        <div class="sale-details">
                            <h6>Nguyên Liệu</h6>
                            <h3 class="text-green" style="font-size: 16px">{{ $ingredientCount }}</h3>
                            <p class="growth text-green">
                                Tăng trưởng: {{ number_format($ingredientChangePercent, 2) }}%
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Phiếu Nhập Kho -->
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr yellow">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <div class="sale-details">
                            <h6>Phiếu Nhập Kho</h6>
                            <h3 class="text-yellow" style="font-size: 16px">{{ $transactionCount }}</h3>
                            <p class="growth text-yellow">
                                Tăng trưởng: {{ number_format($transactionChangePercent, 2) }}%
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Hàng Tồn Kho -->
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr red">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div class="sale-details">
                            <h6>Hàng Tồn Kho</h6>
                            <h3 class="text-red" style="font-size: 16px">{{ $stockCount }}</h3>
                            <p class="growth text-red">
                                Tăng trưởng: {{ number_format($stockChangePercent, 2) }}%
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tổng Thể -->
                <div class="col-xxl-3 col-sm-6 col-12">
                    <div class="stats-tile">
                        <div class="sale-icon-bdr purple">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="sale-details">
                            <h6>Tổng Thể</h6>
                            <h3 class="text-purple" style="font-size: 16px">{{ $totalCount }}</h3>
                            <p class="growth text-purple">
                                Tăng trưởng: {{ number_format($totalChangePercent, 2) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="font-size: 14px">Biểu đồ nhập kho</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-2 col-lg-3 col-sm-12 col-12">
                            <div class="card-border m-0 h-100">
                                <div class="monthly-stats">
                                    <h5>Theo tuần</h5>
                                    <div class="avg-block">
                                        <h4 class="avg-total text-blue">{{ number_format($weeklyCompleted) }}</h4>
                                        <h6 class="avg-label">Hoàn thành</h6>
                                    </div>
                                    <div class="avg-block">
                                        <h4 class="avg-total text-red">{{ number_format($weeklyCanceled) }}</h4>
                                        <h6 class="avg-label">Đã Hủy</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-sm-12 col-12">
                            <!-- Phần tử chứa biểu đồ -->
                            <div id="basic-bar-graph"></div>
                        </div>
                        <div class="col-xxl-2 col-lg-3 col-sm-12 col-12">
                            <div class="card-border m-0 h-100">
                                <div class="monthly-stats">
                                    <h5>Theo tháng</h5>
                                    <div class="avg-block">
                                        <h4 class="avg-total text-blue">{{ number_format($monthlyCompleted) }}</h4>
                                        <h6 class="avg-label">Hoàn thành</h6>
                                    </div>
                                    <div class="avg-block">
                                        <h4 class="avg-total text-red">{{ number_format($monthlyCanceled) }}</h4>
                                        <h6 class="avg-label">Đã Hủy</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Kiểm tra xem transactionData có dữ liệu hợp lệ không
                var transactionData = @json($transactionData);
                console.log(transactionData); // Kiểm tra dữ liệu trong console

                // Nếu dữ liệu hợp lệ, tiến hành vẽ biểu đồ chỉ một lần khi trang được tải
                window.onload = function() {
                    if (transactionData && transactionData.length > 0) {
                        var options = {
                            chart: {
                                height: 300,
                                type: 'bar', // Biểu đồ cột
                                toolbar: {
                                    show: false
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                width: 2
                            },
                            series: transactionData.map(function(item) {
                                return {
                                    name: item.name,
                                    data: item.data.map(function(subItem) {
                                        return subItem.y; // Dữ liệu y cho biểu đồ
                                    })
                                };
                            }),
                            grid: {
                                borderColor: '#ffffff',
                                strokeDashArray: 5,
                                xaxis: {
                                    lines: {
                                        show: true
                                    }
                                },
                                yaxis: {
                                    lines: {
                                        show: false
                                    }
                                },
                                padding: {
                                    top: 0,
                                    right: 0,
                                    bottom: 0,
                                    left: 30
                                }
                            },
                            xaxis: {
                                type: 'datetime',
                                categories: transactionData[0].data.map(function(item) {
                                    return item.x; // Dữ liệu x cho trục hoành
                                })
                            },
                            theme: {
                                monochrome: {
                                    enabled: true,
                                    colors: ['#435EEF', '#59a2fb', '#8ec0fd', '#c7e0ff'],
                                    shadeIntensity: 0.1
                                }
                            },
                            markers: {
                                size: 0,
                                opacity: 0.2,
                                colors: ['#435EEF', '#59a2fb', '#8ec0fd', '#c7e0ff'],
                                strokeColor: "#fff",
                                strokeWidth: 2,
                                hover: {
                                    size: 7
                                }
                            },
                            tooltip: {
                                x: {
                                    format: 'dd/MM/yy'
                                }
                            }
                        };

                        // Vẽ biểu đồ
                        var chart = new ApexCharts(
                            document.querySelector("#basic-bar-graph"), // Thay đổi ID để phù hợp với phần tử chứa biểu đồ
                            options
                        );

                        chart.render();
                    } else {
                        console.log("Dữ liệu không hợp lệ hoặc không có dữ liệu");
                    }
                };
            </script>









            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="font-size: 14px">Biểu đồ xu hướng sử dụng nguyên liệu</div>
                </div>
                <div class="card-body">
                    <!-- Phần tử chứa biểu đồ -->
                    <div id="line-chart"></div>
                </div>
            </div>

            <script>
                // Lấy dữ liệu từ controller qua Blade
                var dailyData = @json($dailyData);
                var weeklyData = @json($weeklyData);

                // Kiểm tra dữ liệu
                console.log(dailyData, weeklyData);

                // Kiểm tra nếu có dữ liệu và xử lý lỗi NaN
                if (dailyData && weeklyData) {
                    weeklyData = weeklyData.map(function(item) {
                        return {
                            x: item.x,
                            y: isNaN(item.y) ? 0 : item.y // Kiểm tra nếu y là NaN, thay bằng 0
                        };
                    });

                    var options = {
                        chart: {
                            height: 350,
                            type: 'line', // Biểu đồ đường
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false // Tắt thanh công cụ, nếu bạn không muốn thanh công cụ trên biểu đồ
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth', // Đường cong mượt
                            width: 2
                        },
                        series: [{
                                name: 'Sử dụng hàng ngày',
                                data: dailyData.map(function(item) {
                                    return item.y;
                                })
                            },
                            {
                                name: 'Sử dụng hàng tuần',
                                data: weeklyData.map(function(item) {
                                    return item.y;
                                })
                            }
                        ],
                        xaxis: {
                            type: 'category', // Sử dụng 'category' để phù hợp với dữ liệu dạng chuỗi
                            categories: dailyData.map(function(item) {
                                return item.x;
                            }),
                            labels: {
                                rotate: -45, // Quay nhãn trục hoành nếu cần thiết để tránh bị chồng lấn
                                style: {
                                    colors: ['#000'], // Màu sắc cho nhãn trục
                                    fontSize: '12px',
                                    fontWeight: 'normal',
                                    fontFamily: 'Helvetica, Arial, sans-serif'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: ['#000'], // Màu sắc cho nhãn trục y
                                    fontSize: '12px',
                                    fontWeight: 'normal',
                                    fontFamily: 'Helvetica, Arial, sans-serif'
                                }
                            }
                        },
                        grid: {
                            borderColor: '#ffffff',
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
                        theme: {
                            monochrome: {
                                enabled: true,
                                colors: ['#435EEF', '#FF4500'],
                                shadeIntensity: 0.1
                            }
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM/yyyy'
                            }
                        }
                    };

                    // Vẽ biểu đồ
                    var chart = new ApexCharts(
                        document.querySelector("#line-chart"),
                        options
                    );

                    chart.render();
                } else {
                    console.log("Dữ liệu không hợp lệ hoặc không có dữ liệu");
                }
            </script>



            {{-- <div class="row">
                <div class="col-xxl-6 col-12" style="width: 65%;">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0" style="font-size: 14px">Biểu đồ số lượng sản phẩm nhập kho</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="basic-pie-graph-gradient1" height="420"></canvas>
                        </div>

                    </div>

                </div>

                <div class="col-xxl-6 col-12" style="width: 35%;">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="basic-pie-graph-gradient"></div>
                        </div>
                    </div>
                </div>
            </div> --}}



            <!-- Biểu đồ Sản Phẩm nhập kho nhiều nhất-->
            <div class="row">
                <div class="col-xxl-6 col-12" style="width: 50%;">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0" style="font-size: 14px">Biểu đồ số lượng sản phẩm nhập kho</h6>
                        </div>
                        <div class="card-body">
                            <!-- Phần tử chứa biểu đồ -->
                            <div id="basic-pie-graph-gradient1"></div>
                        </div>

                    </div>

                </div>

                <div class="col-xxl-6 col-12" style="width: 50%;">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0" style="font-size: 14px">Thống Kê Nguyên Liệu đồ tươi và đồ
                                đóng hộp</h6>
                        </div>
                        <div class="card-body">
                            <div id="basic-pie-graph" class="auto-align-graph"></div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                var options = {
                    chart: {
                        width: 435,
                        type: 'pie',
                    },
                    labels: @json($labels), // Dữ liệu tên sản phẩm
                    series: @json($series), // Dữ liệu số lượng tồn kho
                    responsive: [{
                        breakpoint: 480,
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
                    colors: ['#435EEF', '#2b86f5', '#63a9ff', '#95c5ff', '#c6e0ff'],
                }
                var chart = new ApexCharts(
                    document.querySelector("#basic-pie-graph-gradient1"),
                    options
                );
                chart.render();
            </script>

            <script>
                // Lấy dữ liệu từ controller qua Blade
                var categoryData = @json($categoryData);

                // Kiểm tra dữ liệu
                console.log(categoryData);

                if (categoryData) {
                    var categories = categoryData.map(function(item) {
                        return item.category; // Lấy danh sách category
                    });

                    var totals = categoryData.map(function(item) {
                        return item.total; // Lấy tổng số lượng cho mỗi category
                    });

                    // Chọn màu sắc dựa trên category
                    var colors = categoryData.map(function(item) {
                        return item.category === 'Đồ tươi' ? '#28a745' :
                            '#007bff'; // Màu xanh lá đậm và sáng cho Đồ tươi, màu xanh nước biển đậm cho Đồ đóng hộp
                    });

                    var options = {
                        chart: {
                            width: 300,
                            type: 'pie', // Biểu đồ tròn
                        },
                        labels: categories, // Các nhãn cho từng phần của biểu đồ
                        series: totals, // Dữ liệu series cho biểu đồ
                        legend: {
                            position: 'bottom',
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            width: 0,
                        },
                        colors: colors, // Màu sắc cho từng phần trong biểu đồ
                    };

                    // Vẽ biểu đồ
                    var chart = new ApexCharts(
                        document.querySelector("#basic-pie-graph"),
                        options
                    );
                    chart.render();
                } else {
                    console.log("Dữ liệu không hợp lệ hoặc không có dữ liệu");
                }
            </script>



            <!-- Biểu đồ Sô lượng tồn kho các sản phẩm -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="font-size: 14px">Biểu đồ Sô lượng tồn kho các sản phẩm</div>
                </div>
                <div class="card-body">
                    <div class="row" style="display: flex; justify-content: center;">
                        <div class="col-xxl-8 col-lg-6 col-sm-12 col-12">
                            <!-- Phần tử chứa biểu đồ -->
                            <div id="inventory-stock-bar-chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                var options = {
                    chart: {
                        type: 'bar',
                        height: 350,
                    },
                    series: [{
                        name: 'Số lượng tồn kho',
                        data: @json($allSeries), // Dữ liệu số lượng tồn kho
                    }],
                    xaxis: {
                        categories: @json($allLabels), // Các tên nguyên liệu
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 10,
                            horizontal: false,
                        },
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    fill: {
                        opacity: 1,
                    },
                    colors: ['#435EEF'], // Màu sắc cột biểu đồ
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " sản phẩm";
                            }
                        }
                    }
                };


                var chart = new ApexCharts(
                    document.querySelector("#inventory-stock-bar-chart"),
                    options
                );

                chart.render();
            </script>
        </div>
    </div>
@endsection
