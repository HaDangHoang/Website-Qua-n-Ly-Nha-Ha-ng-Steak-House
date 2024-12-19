@extends('pos.layouts.master')

@section('title', 'POS | Trang chủ')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<style>
    .swal2-container .select2-dropdown {
        z-index: 1060 !important;
    }

    .swal2-container .select2-container--default .select2-selection--multiple {
        width: 100%;
    }

    .table-container {
        position: relative;
        max-height: 600px;
        overflow-y: auto;
        z-index: 1;
    }

    .table-card {
        position: relative;
        padding: 15px;
        margin: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        transition: transform 0.2s;
        z-index: 2;
    }

    .table-card:hover {
        transform: scale(1.05);
        z-index: 3;
    }

    .qr-code-container {
        display: none;
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 4;
    }

    .table-card:hover .qr-code-container {
        display: block;
    }
</style>
@section('content')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Thông báo',
                text: session('error'),
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif
    <header class="navbar navbar-expand-lg p-2" style="background: linear-gradient(90deg, #004a89, #007bb5);">
        <div class="container-fluid">
            <!-- Left Section: Tabs for Phòng bàn and Thực đơn -->
            <div class="header-left d-flex align-items-center">
                <a class="nav-link active" href="#" id="table-view-button" aria-label="Xem Bàn">
                    <i class="fas fa-border-all"></i> Phòng bàn
                </a>
                <a class="nav-link" href="#" id="menu-view-button" aria-label="Xem Thực đơn">
                    <i class="material-icons">restaurant</i> Thực đơn
                </a>
                <input class="form-control1 me-2" id="searchTable" type="search" placeholder="Tìm theo bàn, mã đơn"
                    aria-label="Tìm bàn">
                <input class="form-control1 me-2" id="searchInput" type="search" placeholder="Tìm theo món, giá"
                    aria-label="Tìm món" style="display: none;">
            </div>



            <!-- Right Section: Icons -->
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <li class="nav-item">
                    <!-- Hamburger Menu -->
                    <button class="btn btn-link text-white" id="hamburgerMenu">
                        <i class="fas fa-bars"></i>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Modal Popup Danh Sách Đặt Bàn-->
        <div class="modal fade" id="reservationListModal" tabindex="-1" role="dialog"
            aria-labelledby="reservationListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationListModalLabel">Danh sách đặt bàn</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="search-filter">
                            <div class="input-group">
                                <label for="search">Tìm kiếm</label>
                                <input type="text" id="search" placeholder="Theo mã phiếu đặt">
                            </div>
                            <div class="input-group">
                                <label for="roomTable">Phòng/bàn</label>
                                <select id="roomTable">
                                    <option value="">Chọn phòng bàn</option>
                                    @foreach ($availableTables as $table)
                                        <option value="{{ $table->table_id }}">{{ $table->table_number }}</option>
                                    @endforeach
                                    <!-- Các tùy chọn khác -->
                                </select>
                            </div>
                        </div>
                        <div class="time-group" style="flex-basis: 100%;">
                            <label for="fromDate">Thời Gian</label>
                            <input type="text" id="fromDate" placeholder="Từ ngày" onfocus="(this.type='date')"
                                onblur="if(!this.value){this.type='text'}">
                            <input type="text" id="toDate" placeholder="Đến ngày" onfocus="(this.type='date')"
                                onblur="if(!this.value){this.type='text'}">
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Mã đặt bàn</th>
                                    {{-- <th scope="col">Phòng/bàn<//th> --}}
                                    <th scope="col">Giờ đến</th>
                                    <th scope="col">Khách hàng</th>
                                    <th scope="col">Số điện thoại</th>
                                    <th scope="col">Số khách</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dữ liệu bảng-->
                                @forelse ($reservations as $reservation)
                                    <tr id="reservation-{{ $reservation->id }}">
                                        <td class="text-center"><button type="button" class="transparent-button"
                                                data-toggle="modal"
                                                data-target="#orderDetailModal">{{ $reservation->id }}</button></td>

                                        <td class="text-center">{{ $reservation->reservation_date }} <br>
                                            {{ $reservation->reservation_time }}</td>
                                        <td class="text-center">{{ $reservation->user_name ?? 'Không rõ' }}</td>
                                        <td class="text-center">{{ $reservation->user_phone ?? 'Không rõ' }}</td>
                                        <td class="text-center">{{ $reservation->guest_count ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if ($reservation->status === 'Confirmed')
                                                <span class="badge bg-success">Đã xác nhận</span>
                                            @elseif ($reservation->status === 'Pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                            @elseif ($reservation->status === 'Cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @elseif ($reservation->status === 'checked-in')
                                                <span class="badge bg-primary">Đã nhận bàn</span>
                                            @else
                                                <span class="badge bg-secondary">Không rõ</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="actions">
                                                <a href="{{ route('ReToOr', $reservation->id) }}">Khách nhận bàn</a>
                                                <!-- Các hành động khác như Xem, Sửa, Hủy đơn đặt bàn -->
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Không có đặt bàn nào được tìm thấy.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Chi Tiết -->
        <div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog"
            aria-labelledby="orderDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailModalLabel">Nguyễn Bá Thư - 0283982424</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="row">
                                <!-- Cột trái -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="customerName">Khách hàng</label>
                                        <input type="text" class="form-control" id="customerName"
                                            value="Nguyễn Bá Thư">
                                    </div>
                                    <div class="input-group">
                                        <label for="orderCode">Mã đặt bàn</label>
                                        <input type="text" class="form-control" id="orderCode" value="DB0000004"
                                            readonly>
                                    </div>
                                    <div class="input-group">
                                        <label for="arrivalTime">Giờ đến</label>
                                        <input type="text" class="form-control" id="arrivalTime"
                                            value="14/10/2024 21:30">
                                    </div>
                                    <div class="input-group">
                                        <label for="duration">Thời lượng</label>
                                        <select class="form-control" id="duration">
                                            <option selected>1 Giờ</option>
                                            <option>2 Giờ</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label for="numGuests">Số khách</label>
                                        <input type="number" class="form-control" id="numGuests" value="1"
                                            min="1">
                                    </div>
                                </div>
                                <!-- Cột phải -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="employee">Nhân viên nhận đặt</label>
                                        <select class="form-control" id="employee">
                                            <option>Nguyễn Văn Quang</option>
                                            <option>Nguyễn Văn A</option>
                                            <option>Nguyễn Văn B</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label for="status">Trạng thái</label>
                                        <select class="form-control" id="status">
                                            <option selected>Chờ xếp bàn</option>
                                            <option>Đã xếp bàn</option>
                                            <option>Đã hủy</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label for="tableInfo">Phòng/Bàn</label>
                                        <select class="form-control" id="tableInfo">
                                            <option selected>Chờ xếp bàn</option>
                                            <option>Bàn 1</option>
                                            <option>Bàn 2</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input class="form-control" id="notes" placeholder="Ghi Chú">
                                    </div>
                                </div>
                            </div>
                            <div class="btnGroup text-right mt-3">
                                <button type="button" class="btnEdit btn btn-danger">Xóa</button>
                                <button type="button" class="btnEdit btn btn-warning">Hủy đặt</button>
                                <button type="button" class="btnEdit btn btn-primary">Lưu & In</button>
                                <button type="button" class="btnEdit btn btn-success">Lưu</button>
                                <button type="button" class="btnEdit btn btn-secondary" data-dismiss="modal">Bỏ
                                    qua</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="wrapper">
        <div class="container-fluid d-flex flex-grow-1 px-0">
            <!-- Phần bên trái: Bàn và Thực đơn -->
            <div class="col-md-8 bg-light-gray p-4">
                <!-- Phần hiển thị Bàn -->
                <div class="table-section transition-section" id="table-section">
                    <!-- Lọc Bàn theo Trạng thái -->
                    <div class="filter-section mb-4 d-flex justify-content-start flex-nowrap">
                        <button class="btn btn-outline-primary filter-btn me-2 active" data-status="all">
                            Tất cả ({{ $totalTablesCount }})
                        </button>
                        <button class="btn btn-outline-success filter-btn me-2" data-status="available">
                            Trống ({{ $availableTablesCount }})
                        </button>
                        <button class="btn btn-outline-danger filter-btn" data-status="occupied">
                            Đang sử dụng ({{ $occupiedTablesCount }})
                        </button>
                    </div>
                    <div class="table-container d-flex flex-wrap justify-content-start"
                        style="max-height: 600px; overflow-y: auto;" id="layoutTable">
                        @foreach ($tables as $table)
                            <div class="table-card {{ strtolower(trim($table->status)) }}"
                                data-table-id="{{ $table->id }}" data-status="{{ $table->status }}"
                                data-order-id="@foreach ($table->orders as $column) {{ $column->reservation?->user_name ?? ($column->customer?->name ?? null) }} @endforeach">
                                <span class="table-number">Bàn {{ $table->table_number }}</span>
                                <div class="table-o">
                                    @foreach ($table->orders as $column)
                                        <span><i class="fa-solid fa-id-card"></i>
                                            {{ $column->reservation->id ?? ($column->id ?? null) }}</span>
                                    @endforeach
                                </div>
                                <div class="qr-code-container">
                                    <h5 class="text-dark">Bàn {{ $table->table_number }}</h5>
                                    <p>
                                        <b
                                            class="text-dark">{{ $table->status == 'Available' ? 'Bàn mở' : 'Đang sử dụng' }}</b>
                                    </p>
                                    {!! $qrCodes[$table->id] ?? '' !!}
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

                <!-- Phần hiển thị Thực đơn -->
                <div class="menu-section transition-section" id="menu-section" style="display: none;">
                    <div class="filter-section mb-4 d-flex justify-content-start flex-nowrap">
                        <button class="btn btn-outline-primary filter-btnn me-2 active" data-category="all">Tất
                            cả</button>
                        <button class="btn btn-outline-primary filter-btnn me-2"
                            data-category="combo">Combo({{ $combo->count() }})</button>
                        @foreach ($cate as $cate)
                            <button class="btn btn-outline-primary filter-btnn me-2"
                                data-category="{{ $cate->id }}">{{ $cate->name }}({{ $cate->dishes->count() }})</button>
                        @endforeach
                    </div>

                    <div class="row" id="dish-list" style="max-height: 600px; overflow-y: auto;">
                        @foreach ($dishes as $dish)
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 dish-item {{ $dish->is_active == 0 || $dish->status == 'out_of_stock' ? 'disabled' : '' }}"
                                data-category="{{ $dish->category->id }}" style="padding: 10px;"
                                @if ($dish->is_active && $dish->status != 'out_of_stock') data-dish-id="{{ $dish->id }}" 
                                data-dish-price="{{ $dish->price }}" @endif>

                                <div class="card menu-item col">
                                    <img class="btn btn-add-dish" data-dish-id="{{ $dish->id }}"
                                        src="{{ asset($dish->image ? 'storage/' . $dish->image : 'images/placeholder.jpg') }}"
                                        alt="{{ $dish->name }}" class="img-fluid rounded"
                                        style="object-fit: cover; height: 200px; width: 100%; max-height: 200px; {{ $dish->is_active == 0 || $dish->status == 'out_of_stock' ? 'filter: grayscale(100%); opacity: 0.6;' : '' }}" />
                                    <div class="card-body text-center">
                                        @if ($dish->is_active == 0 || $dish->status == 'out_of_stock')
                                            <p class="text-danger">Không khả dụng</p>
                                        @else
                                            <h5 class="card-price text-primary">
                                                {{ number_format($dish->price, 0, ',', '.') }}
                                                VND</h5>
                                        @endif
                                        <p class="card-title">{{ \Str::limit($dish->name, 20, '...') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @foreach ($combo as $combo)
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 dish-combo {{ $combo->is_active == 0 || $combo->status == 'out_of_stock' ? 'disabled' : '' }}"
                                data-category="combo"
                                @if ($combo->is_active) data-combo-id="{{ $combo->id }}"
                                data-combo-price="{{ $combo->price }}" @endif
                                style="padding: 10px;">
                                <div class="card menu-item col">
                                    <img class="btn btn-add-dish"
                                        src="{{ asset($combo->image ? 'storage/' . $combo->image : 'images/placeholder.jpg') }}"
                                        alt="{{ $combo->name }}" class="img-fluid rounded"
                                        style="object-fit: cover; height: 200px; width: 100%; max-height: 200px; {{ $combo->is_active == 0 || $combo->status == 'out_of_stock' ? 'filter: grayscale(100%); opacity: 0.6;' : '' }}" />
                                    <div class="card-body text-center">
                                        @if ($combo->is_active == 0 || $combo->status == 'out_of_stock')
                                            <p class="text-danger">Không khả dụng</p>
                                        @else
                                            <h5 class="card-price text-primary">
                                                {{ number_format($combo->price, 0, ',', '.') }} VND
                                            </h5>
                                        @endif
                                        <p class="card-title">{{ \Str::limit($combo->name, 20, '...') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <!-- Phần bên phải: Đơn hàng -->
            <div class="col-md-4 p-0 order-section">
                <nav class="navbar">
                </nav>
                <div class="order-content-container" style="padding-left: 20px;">
                    <div id="order-details" class="order-content-container">
                        <div class="empty-order">
                            <p>Chưa chọn bàn</p>
                            <p>Vui lòng chọn bàn tại phía bên trái màn hình</p>
                        </div>
                    </div>
                </div>
                <div class="total mt-4">Tổng tiền: <span id="totalAmount">0</span></div>
                <div class="btn-group">
                    <button class="btn btn-outline-primary border border-primary" disabled id="notification-button"
                        aria-label="Thông báo">
                        <i class="fas fa-bell"></i> Thông báo
                    </button>
                    <button class="btn btn-primary" id="payment-button" aria-label="Thanh toán" disabled>
                        <i class="fas fa-dollar-sign"></i> Thanh toán
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    let selectedTableId = null;
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('#layoutTable').addEventListener('click', function(event) {
            const card = event.target.closest('.table-card');
            if (!card) return;
            selectedTableId = card.dataset.tableId;
            const tableId = card.dataset.tableId;
            const tableStatus = card.dataset.status;
            tableStatus === 'Occupied' ? showOrderDetails(tableId) : createOrder(tableId);
        });
        document.querySelector('#dish-list').addEventListener('click', function(event) {
            const card = event.target.closest('.dish-item');
            const combo = event.target.closest('.dish-combo');

            if (card && !card.classList.contains('disabled')) {
                const dishId = card.dataset.dishId;
                addDishToOrder(dishId, selectedTableId);
            }

            if (combo && !combo.classList.contains('disabled')) {
                const comboId = combo.dataset.comboId;
                addComboToOrder(comboId, selectedTableId);
            }
        });
        document.querySelector('#notification-button').addEventListener('click', function(event) {
            notificationButton(selectedTableId);
        });
        document.querySelector('#payment-button').addEventListener('click', function(event) {
            const orderDetails = document.getElementById('order-details');
            if (selectedTableId != null) {
                fetch('/check-payment-condition', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            table_id: selectedTableId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const url = `/viewCheckOut/${selectedTableId}`;
                            window.location.href = url;
                        } else {
                            Swal.fire({
                                title: 'Các món sẽ bị hủy do chưa hoàn thành!',
                                html: data.message,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Tiếp tục',
                                cancelButtonText: 'Hủy',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const url = `/Ppayment/${selectedTableId}`;
                                    fetch(url, {
                                            method: 'GET',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector(
                                                        'meta[name="csrf-token"]')
                                                    .getAttribute('content'),
                                                'Content-Type': 'application/json',
                                            },
                                        })
                                        .then((response) => {
                                            if (!response.ok) {
                                                throw new Error(
                                                    `HTTP error! Status: ${response.status}`
                                                );
                                            }
                                            return response.json();
                                        })
                                        .then((json) => {
                                            if (json.redirect_url && json.redirect_url
                                                .includes('checkoutt')) {
                                                return fetch(json.redirect_url, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document
                                                            .querySelector(
                                                                'meta[name="csrf-token"]'
                                                            )
                                                            .getAttribute(
                                                                'content'),
                                                        'Content-Type': 'application/json',
                                                    },
                                                });
                                            } else {
                                                const url =
                                                    `/viewCheckOut/${selectedTableId}`;
                                                window.location.href = url;
                                            }
                                        })
                                        .then((response) => {
                                            if (!response.ok) {
                                                throw new Error(
                                                    `HTTP error! Status: ${response.status}`
                                                );
                                            }
                                            return response.json();
                                        })
                                        .then((finalJson) => {
                                            if (finalJson.success) {
                                                Swal.fire({
                                                    title: 'Thành công!',
                                                    text: finalJson.message,
                                                    icon: 'success',
                                                })
                                            } else {
                                                Swal.fire({
                                                    title: 'Lỗi!',
                                                    text: finalJson.message,
                                                    icon: 'error',
                                                });
                                            }
                                        })
                                }
                            });

                        }
                    })
                    .catch(error => console.error('Error:', error));

            } else {
                showNotification('Vui lòng chọn bàn trước khi thanh toán', 'warning')
            }
        });

        function notificationButton(selectedTableId) {
            fetch('/notification-button/' + selectedTableId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                }).then(data => {
                    if (data.success) {} else {
                        showNotification('Thông báo bếp thành công')
                    }
                })
        }

        function createOrder(tableId) {

            fetch('/checkAvailableTables', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch available tables');
                    return response.json();
                })
                .then(data => {
                    const availableTables = data.tables || [];
                    const users = data.users || [];
                    Swal.fire({
                        title: 'Nhận gọi món',
                        html: `
          <div class="container">
            <div class="mb-3">
                <label for="customer" class="form-label">Khách hàng</label>
                <select id="customer" class="form-select">
                    <option value="" disabled selected hidden>Tìm khách hàng</option>
                    ${users.map(user => `
                        <option value="${user.name}" data-phone="${user.phone}">
                            ${user.name}
                        </option>
                        `).join('')}
                </select>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" id="phone" class="form-control" placeholder="Số điện thoại">
            </div>
            <div class="mb-3">
              <label for="tableRoom" class="form-label">Phòng/Bàn</label><br>
              <select id="tableRoom" class="form-select" multiple>
                    ${availableTables.map(table => `
                        <option value="${table.id}" ${table.id == tableId ? 'selected' : ''}>
                            Bàn ${table.table_number}
                        </option>
                        `).join('')}
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Số khách</label>
                <input type="number" id="adults" class="form-control" placeholder="Số khách" min="1" max="6" value="1">
            </div>
          </div>
        `,
                        showCancelButton: true,
                        confirmButtonText: 'Xác nhận',
                        cancelButtonText: 'Hủy',
                        didOpen: () => {
                            const MAX_GUESTS_PER_TABLE = 4;
                            const $tableRoom = $('#tableRoom');
                            const $adults = $('#adults');
                            const $customer = $('#customer');
                            const $phone = $('#phone');

                            $customer.select2({
                                placeholder: 'Tìm khách hàng',
                                tags: true,
                                dropdownParent: $('.swal2-container'),
                                createTag: function(params) {
                                    const term = $.trim(params.term);
                                    if (term === '') {
                                        return null;
                                    }
                                    return {
                                        id: term,
                                        text: term,
                                        isNew: true
                                    };
                                }
                            });

                            $tableRoom.select2({
                                placeholder: 'Chọn Phòng/Bàn',
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });

                            $customer.on('change', function() {
                                const selectedOption = $(this).find(':selected');
                                const phone = selectedOption.data('phone') || '';
                                $phone.val(phone);
                            });

                            $phone.on('input', function() {
                                const newPhone = $(this).val();
                                let matchedCustomer = null;
                                $customer.find('option').each(function() {
                                    if ($(this).data('phone') === newPhone) {
                                        matchedCustomer = $(this).val();
                                        return false;
                                    }
                                });

                                if (matchedCustomer) {
                                    $customer.val(matchedCustomer).trigger('change');
                                }
                            });

                            const updateGuestLimits = () => {
                                const selectedTables = $tableRoom.val().length;
                                if (selectedTables === 0) {
                                    $adults.attr('min', 1);
                                    $adults.attr('max', MAX_GUESTS_PER_TABLE);
                                    $adults.val(
                                        1);
                                    return;
                                }

                                const maxGuests = selectedTables *
                                    MAX_GUESTS_PER_TABLE;
                                const minGuests = selectedTables === 1 ? 1 : (
                                        selectedTables - 1) * MAX_GUESTS_PER_TABLE +
                                    1;

                                $adults.attr('max', maxGuests);
                                $adults.attr('min', minGuests);

                                const currentValue = parseInt($adults.val(), 10);
                                if (currentValue < minGuests) {
                                    $adults.val(minGuests);
                                } else if (currentValue > maxGuests) {
                                    $adults.val(maxGuests);
                                }
                            };
                            $tableRoom.on('change', updateGuestLimits);
                            $adults.on('input', () => {
                                const maxGuests = parseInt($adults.attr('max'), 10);
                                const minGuests = parseInt($adults.attr('min'), 10);
                                const currentValue = parseInt($adults.val(), 10);

                                if (currentValue > maxGuests) {
                                    $adults.val(maxGuests);
                                } else if (currentValue < minGuests || isNaN(
                                        currentValue)) {
                                    $adults.val(minGuests);
                                }
                            });
                            updateGuestLimits();
                        },
                        preConfirm: () => {
                            const customer = $('#customer').val();
                            const phone = $('#phone').val();
                            const tableRoom = $('#tableRoom').val();
                            const adults = $('#adults').val();
                            if (!customer || !tableRoom.length || !phone) {
                                Swal.showValidationMessage('Vui lòng nhập đầy đủ thông tin');
                                return false;
                            }
                            return {
                                customer,
                                phone,
                                tableRoom,
                                adults,
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showNotification('Tạo đơn thành công');
                            fetch('/create-order', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        table_id: result.value.tableRoom,
                                        phone: result.value.phone,
                                        user: result.value.customer,
                                        quantity: result.value.adults,
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error(
                                        'Network response was not ok');
                                    return response.json();
                                })
                                .then(data => showOrderDetails(tableId))
                                .catch(() => showNotification('Lỗi khi tạo đơn', 'error'));
                        } else if (result.isDenied) {
                            showNotification('Tạo đơn thất bại', 'error');
                        }
                    });
                })
        }
        const orderDetails = document.getElementById('order-details');
        orderDetails.addEventListener("click", function(event) {
            if (event.target.id === 'editInformation') {
                editInformation(selectedTableId);
            }
            if (event.target.id === 'combineTables') {
                combineTables(selectedTableId);
            }
            const dishElement = event.target.closest(".item-list");
            if (dishElement) {
                const dishId = dishElement.dataset.dishId;
                const dishStatus = dishElement.dataset.dishStatus;
                const dishType = dishElement.dataset.dishType;
                const dishOrder = dishElement.dataset.dishOrder;
                if (event.target.classList.contains("plus-item")) {
                    if (dishType == 1) {
                        increaseQuantity(dishId, selectedTableId);
                    } else {
                        increaseQuantityy(dishId, selectedTableId, dishType);
                    }
                }
                if (event.target.classList.contains("minus-item")) {
                    if (dishStatus == 'chờ xử lý') {
                        if (dishType == 1) {
                            decreaseQuantity(dishId, selectedTableId);
                        } else {
                            decreaseQuantityy(dishId, selectedTableId, dishType);
                        }
                    } else {
                        const dishInformed = dishElement.dataset.dishInformed;
                        const dishProcessing = dishElement.dataset.dishProcessing;
                        const dishQuantity = dishElement.dataset.dishQuantity;
                        if (dishInformed > dishProcessing || dishQuantity > dishProcessing) {
                            if (dishType == 1) {
                                decreaseQuantity(dishId, selectedTableId);
                            } else {
                                decreaseQuantityy(dishId, selectedTableId, dishType);
                            }
                        } else {
                            if (dishType == 1) {
                                canelItem(dishId, selectedTableId, dishOrder)
                            } else {
                                canelItemm(dishId, selectedTableId, dishOrder, dishType)
                            }
                        }
                    }
                }
                if (event.target.classList.contains("delete-item")) {
                    if (dishType == 1) {
                        deleteItem(dishId, selectedTableId);
                    } else {
                        deleteItemm(dishId, selectedTableId, dishType);
                    }
                }
                if (event.target.classList.contains("delette-item")) {
                    if (dishType == 1) {
                        deletteItem(dishId, selectedTableId);
                    } else {
                        deletteItemm(dishId, selectedTableId, dishType);
                    }
                }
            }
        });

        function canelItem(dishId, selectedTableId, dishOrder) {
            Swal.fire({
                title: 'Nhập lý do hủy',
                input: 'text',
                inputPlaceholder: 'Nhập lý do...',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy',
                preConfirm: () => {
                    const reason = Swal.getInput().value.trim();
                    if (!reason) {
                        Swal.showValidationMessage(
                            'Vui lòng nhập lý do hủy');
                        return false;
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;
                    fetch(`/canelItem`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                table_id: selectedTableId,
                                dish_id: dishId,
                                reason: reason,
                                dishOrder: dishOrder
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Hủy món thành công!', 'success');
                            } else {
                                showNotification('Lỗi khi xóa', 'error');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    showNotification('Hủy món thất bại', 'info');
                }
            });
        }

        function canelItemm(dishId, selectedTableId, dishOrder, dishType) {
            Swal.fire({
                title: 'Nhập lý do hủy',
                input: 'text',
                inputPlaceholder: 'Nhập lý do...',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy',
                preConfirm: () => {
                    const reason = Swal.getInput().value.trim();
                    if (!reason) {
                        Swal.showValidationMessage(
                            'Vui lòng nhập lý do hủy');
                        return false;
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;
                    fetch(`/canelItemm`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                table_id: selectedTableId,
                                dish_id: dishId,
                                reason: reason,
                                dishOrder: dishOrder,
                                dishType: dishType
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Hủy món thành công!', 'success');
                            } else {
                                showNotification('Lỗi khi xóa', 'error');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    showNotification('Hủy món thất bại', 'info');
                }
            });
        }

        function increaseQuantity(dishId, selectedTableId) {
            fetch(`/increaseQuantity`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table_id: selectedTableId,
                        dish_id: dishId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {} else {
                        showNotification('Món đã hết nguyên liệu', 'error')
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function increaseQuantityy(dishId, selectedTableId, dishType) {
            fetch(`/increaseQuantityy`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table_id: selectedTableId,
                        dish_id: dishId,
                        dishType: dishType
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {} else {
                        console.log(data);

                        showNotification('Món đã hết nguyên liệu', 'error')
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function decreaseQuantity(dishId, selectedTableId) {
            fetch(`/decreaseQuantity`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table_id: selectedTableId,
                        dish_id: dishId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {} else {
                        showNotification('Món đã hết nguyên liệu', 'error')
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function decreaseQuantityy(dishId, selectedTableId, dishType) {
            fetch(`/decreaseQuantityy`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table_id: selectedTableId,
                        dish_id: dishId,
                        dishType: dishType
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {} else {
                        showNotification('Món đã hết nguyên liệu', 'error')
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function deleteItem(dishId, selectedTableId) {
            Swal.fire({
                title: 'Nhập lý do hủy',
                input: 'text',
                inputPlaceholder: 'Nhập lý do...',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy',
                preConfirm: () => {
                    const reason = Swal.getInput().value.trim();
                    if (!reason) {
                        Swal.showValidationMessage(
                            'Vui lòng nhập lý do hủy');
                        return false;
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;
                    fetch(`/deleteItem`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                table_id: selectedTableId,
                                dish_id: dishId,
                                reason: reason
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Hủy món thành công!', 'success');
                            } else {
                                showNotification('Lỗi khi xóa', 'error');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    showNotification('Hủy món thất bại', 'info');
                }
            });
        }

        function deleteItemm(dishId, selectedTableId, dishType) {
            Swal.fire({
                title: 'Nhập lý do hủy',
                input: 'text',
                inputPlaceholder: 'Nhập lý do...',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy',
                preConfirm: () => {
                    const reason = Swal.getInput().value.trim();
                    if (!reason) {
                        Swal.showValidationMessage(
                            'Vui lòng nhập lý do hủy');
                        return false;
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;
                    fetch(`/deleteItemm`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                table_id: selectedTableId,
                                dish_id: dishId,
                                reason: reason,
                                dishType: dishType
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Hủy món thành công!', 'success');
                            } else {
                                showNotification('Lỗi khi xóa', 'error');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    showNotification('Hủy món thất bại', 'info');
                }
            });
        }


        function deletteItem(dishId, selectedTableId) {
            fetch(`/deleteItem`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table_id: selectedTableId,
                        dish_id: dishId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Hủy món thành công!', 'success');
                    } else {
                        showNotification('Lỗi khi xóa', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function deletteItemm(dishId, selectedTableId, dishType) {
            fetch(`/deleteItemm`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table_id: selectedTableId,
                        dish_id: dishId,
                        dishType: dishType
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Hủy món thành công!', 'success');
                    } else {
                        showNotification('Lỗi khi xóa', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        }




        function addDishToOrder(dishId, selectedTableId) {
            if (selectedTableId) {
                fetch(`/add-dish-to-order`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            table_id: selectedTableId,
                            dish_id: dishId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Thêm món thành công')
                        } else {
                            showNotification('Món đã hết nguyên liệu', 'error')
                        }
                    })
                    .catch(error => console.log('Error:', error));
            } else {
                showNotification('Hãy chọn bàn trước khi thêm món', 'error')
            }
        }

        function addComboToOrder(comboId, selectedTableId) {
            if (selectedTableId) {
                fetch(`/add-combo-to-order`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            table_id: selectedTableId,
                            combo_id: comboId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Thêm món thành công')
                        } else {
                            console.log(data);

                            showNotification('Món đã hết nguyên liệu', 'error')
                        }
                    })
                    .catch(error => console.log('Error:', error));
            } else {
                showNotification('Hãy chọn bàn trước khi thêm món', 'error')
            }
        }

        function editInformation(selectedTableId) {
            fetch('/checkTables', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table_id: selectedTableId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch available tables');
                    return response.json();
                })
                .then(data => {
                    const availableTables = data.tables || [];
                    const tableIds = data.tableIds || [];
                    const users = data.users || [];
                    const user = data.user || [];
                    const phone = data.phone || [];
                    const quantity = data.quantity || [];
                    Swal.fire({
                        title: 'Chỉnh sửa thông tin bàn',
                        html: `
          <div class="container">
            <div class="mb-3">
                <label for="customer" class="form-label">Khách hàng</label>
                <select id="customer" class="form-select">
                    <option value="${user}" selected>${user}</option>
                    ${users.map(user => `
                        <option value="${user.name}" data-phone="${user.phone}">
                            ${user.name}
                        </option>
                        `).join('')}
                </select>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="text" value="${phone}" id="phone" class="form-control" placeholder="Số điện thoại">
            </div>
            <div class="mb-3">
              <label for="tableRoom" class="form-label">Phòng/Bàn</label><br>
              <select id="tableRoom" class="form-select" multiple>
                    ${availableTables.map(table => `
                        <option value="${table.id}" ${tableIds.includes(table.id) ? 'selected' : ''}>
                            Bàn ${table.table_number}
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Số khách</label>
                <input type="number" id="adults" class="form-control" placeholder="Số khách" min="1" max="6" value="${quantity}">
            </div>
          </div>
        `,
                        showCancelButton: true,
                        confirmButtonText: 'Xác nhận',
                        cancelButtonText: 'Hủy',
                        didOpen: () => {
                            const MAX_GUESTS_PER_TABLE = 4;
                            const $tableRoom = $('#tableRoom');
                            const $adults = $('#adults');
                            const $customer = $('#customer');
                            const $phone = $('#phone');

                            $customer.select2({
                                placeholder: 'Tìm khách hàng',
                                tags: true,
                                dropdownParent: $('.swal2-container'),
                                createTag: function(params) {
                                    const term = $.trim(params.term);
                                    if (term === '') {
                                        return null;
                                    }
                                    return {
                                        id: term,
                                        text: term,
                                        isNew: true
                                    };
                                }
                            });

                            $tableRoom.select2({
                                placeholder: 'Chọn Phòng/Bàn',
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });

                            $customer.on('change', function() {
                                const selectedOption = $(this).find(':selected');
                                const phone = selectedOption.data('phone') || '';
                                $phone.val(phone);
                            });

                            $phone.on('input', function() {
                                const newPhone = $(this).val();
                                let matchedCustomer = null;
                                $customer.find('option').each(function() {
                                    if ($(this).data('phone') === newPhone) {
                                        matchedCustomer = $(this).val();
                                        return false;
                                    }
                                });

                                if (matchedCustomer) {
                                    $customer.val(matchedCustomer).trigger('change');
                                }
                            });

                            const updateGuestLimits = () => {
                                const selectedTables = $tableRoom.val().length;
                                if (selectedTables === 0) {
                                    $adults.attr('min', 1);
                                    $adults.attr('max', MAX_GUESTS_PER_TABLE);
                                    $adults.val(
                                        1);
                                    return;
                                }

                                const maxGuests = selectedTables *
                                    MAX_GUESTS_PER_TABLE;
                                const minGuests = selectedTables === 1 ? 1 : (
                                        selectedTables - 1) * MAX_GUESTS_PER_TABLE +
                                    1;

                                $adults.attr('max', maxGuests);
                                $adults.attr('min', minGuests);

                                const currentValue = parseInt($adults.val(), 10);
                                if (currentValue < minGuests) {
                                    $adults.val(minGuests);
                                } else if (currentValue > maxGuests) {
                                    $adults.val(maxGuests);
                                }
                            };
                            $tableRoom.on('change', updateGuestLimits);
                            $adults.on('input', () => {
                                const maxGuests = parseInt($adults.attr('max'), 10);
                                const minGuests = parseInt($adults.attr('min'), 10);
                                const currentValue = parseInt($adults.val(), 10);

                                if (currentValue > maxGuests) {
                                    $adults.val(maxGuests);
                                } else if (currentValue < minGuests || isNaN(
                                        currentValue)) {
                                    $adults.val(minGuests);
                                }
                            });
                            updateGuestLimits();
                        },
                        preConfirm: () => {
                            const customer = $('#customer').val();
                            const phone = $('#phone').val();
                            const tableRoom = $('#tableRoom').val();
                            const adults = $('#adults').val();
                            if (!customer || !tableRoom.length || !phone) {
                                Swal.showValidationMessage('Vui lòng nhập đầy đủ thông tin');
                                return false;
                            }
                            return {
                                customer,
                                phone,
                                tableRoom,
                                adults,
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showNotification('Chỉnh sửa thành công');
                            fetch(`/edit-order/${selectedTableId}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        table_id: result.value.tableRoom,
                                        phone: result.value.phone,
                                        user: result.value.customer,
                                        quantity: result.value.adults,
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error(
                                        'Network response was not ok');
                                    return response.json();
                                })
                                .then(data => {
                                    selectedTableId = data.tableId;
                                    console.log(selectedTableId + '1');
                                    showOrderDetails(data.tableId);
                                })
                                .catch(() => showNotification('Lỗi khi tạo đơn', 'error'));
                        } else if (result.isDenied) {
                            showNotification('Tạo đơn thất bại', 'error');
                        }
                    });
                })
        }

        function showOrderDetails(tableId) {
            selectedTableId = tableId;
            fetch('/order-details/' + tableId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    highlightTables(data);
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }

        function highlightTables(data) {
            const tableCards = document.querySelectorAll('.table-card');
            const selectedTableIds = data.table.tables.map(table => String(table.table_number));
            tableCards.forEach(card => {
                const isSelected = selectedTableIds.includes(card.getAttribute('data-table-id'));

                if (isSelected) {
                    card.style.backgroundColor = '#007bff';
                    card.style.color = '#ffffff';
                    const childElements = card.querySelectorAll('*');
                    childElements.forEach(child => {
                        child.style.color = '#ffffff';
                    });
                } else {
                    if (card.getAttribute('data-status') == 'Occupied') {
                        card.style.backgroundColor = '#f8c1a5';
                        card.style.color = '#333';
                        const childElements = card.querySelectorAll('*');
                        childElements.forEach(child => {
                            child.style.color = '#535f6b';
                        });
                    } else {
                        card.style.backgroundColor = '';
                        card.style.color = '';
                        const childElements = card.querySelectorAll('*');
                        childElements.forEach(child => {
                            child.style.color = '';
                        });
                    }
                }
            });
        }

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

        function combineTables() {
            fetch('/checkOrders', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')
                            .getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        table_id: selectedTableId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch available tables');
                    return response.json();
                })
                .then(data => {
                    const availableTables = data.tables || [];
                    const tableIds = data.tableIds || [];
                    const users = data.users || [];
                    const user = data.user || [];
                    const phone = data.phone || [];
                    const quantity = data.quantity || [];
                    Swal.fire({
                        title: `Bàn ${tableIds}`,
                        html: `
          <div class="container">
            <div class="mb-3">
              <label for="tableRoom" class="form-label">Chọn bàn muốn gộp</label><br>
              <select id="tableRoom" class="form-select" multiple>
                    ${availableTables.map(table => `
                        <option value="${table.id}" ${tableIds.includes(table.id) ? 'selected' : ''}>
                            Bàn ${table.table_number}
                        </option>
                    `).join('')}
                </select>
            </div>
          </div>
        `,
                        showCancelButton: true,
                        confirmButtonText: 'Xác nhận',
                        cancelButtonText: 'Hủy',
                        didOpen: () => {
                            const $tableRoom = $('#tableRoom');
                            $tableRoom.select2({
                                placeholder: 'Chọn Phòng/Bàn',
                                allowClear: true,
                                dropdownParent: $('.swal2-container')
                            });
                        },
                        preConfirm: () => {
                            const tableRoom = $('#tableRoom').val();
                            if (!tableRoom.length) {
                                Swal.showValidationMessage('Vui lòng nhập đầy đủ thông tin');
                                return false;
                            }
                            return {
                                tableRoom,
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showNotification('Chỉnh sửa thành công');
                            fetch(`/mergeTables/${selectedTableId}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        table_id: result.value.tableRoom,
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) throw new Error(
                                        'Network response was not ok');
                                    return response.json();
                                })
                                .then(data => {
                                    selectedTableId = data.tableId;
                                    showOrderDetails(data.tableId);
                                })
                                .catch(() => showNotification('Lỗi khi tạo đơn', 'error'));
                        } else if (result.isDenied) {
                            showNotification('Tạo đơn thất bại', 'error');
                        }
                    });
                })
        }


    });
</script>
@vite(['resources/js/posTable.js', 'resources/js/orderItem.js', 'resources/js/DishStatusUpdated.js'])
<style>
    .card {
        height: 320px !important;
    }

    .wrapper {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        padding: 5px;
        background-image: url('https://apac-marketing.webbeds.com/wp-content/uploads/2018/10/Marco-Polo-Hotel-2.jpg');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        min-height: 100vh;

        /* Đặt chiều cao tối đa cho wrapper */
        overflow-y: hidden;
        /* Tránh việc hiển thị thanh cuộn không cần thiết */
    }

    .table-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        /* Căn đều các thẻ */
        max-height: calc(100vh - 150px);
        /* Điều chỉnh chiều cao tối đa cho phần chứa bàn để vừa với màn hình */
        overflow-y: auto;
        padding-bottom: 20px;
        /* Thêm khoảng cách dưới để tránh quá sát viền */
    }

    .table-card {
        position: relative;
        padding: 8px;
        margin: 8px;
        border-radius: 8px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100px;
        /* Giảm kích thước thẻ */
        height: 120px;
        /* Giảm kích thước thẻ */
        text-align: center;
        transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    .dish-item {
        height: 100%;
    }

    .dish-combo {
        height: 100%;
    }

    /* Responsive cho các màn hình nhỏ */
    @media (max-width: 768px) {
        .table-card {
            width: 80px;
            height: 100px;
        }

        .card {
            height: 420px;
        }
    }

    @media (max-width: 576px) {
        .table-card {
            width: 60px;
            height: 80px;
        }

        .navbar .form-control {
            width: 100%;
        }

        .order-section {
            padding: 5px;
        }

        .table-container {
            max-height: calc(100vh - 200px);
            /* Giảm chiều cao cho màn hình nhỏ hơn để đảm bảo không bị tràn */
        }

        .progress {
            height: 6px;
            /* Giảm chiều cao của thanh tiến trình */
        }

        .nav-link {
            font-size: 14px;
            /* Giảm kích thước font trên thiết bị nhỏ */
        }

        .btn {
            padding: 8px 10px;
            font-size: 12px;
            /* Giảm kích thước nút trên thiết bị nhỏ */
        }

        .card {
            height: 330px;
        }
    }


    /* Responsive cho các màn hình nhỏ */
    @media (max-width: 768px) {
        .table-card {
            width: 80px;
            height: 100px;
        }
    }

    @media (max-width: 576px) {
        .table-card {
            width: 60px;
            height: 80px;
        }

        .navbar .form-control {
            width: 100%;
        }

        .order-section {
            padding: 5px;
        }

        .table-container {
            max-height: calc(100vh - 200px);
            /* Giảm chiều cao cho màn hình nhỏ hơn để đảm bảo không bị tràn */
        }

        .progress {
            height: 6px;
            /* Giảm chiều cao của thanh tiến trình */
        }

        .nav-link {
            font-size: 14px;
            /* Giảm kích thước font trên thiết bị nhỏ */
        }

        .btn {
            padding: 8px 10px;
            font-size: 12px;
            /* Giảm kích thước nút trên thiết bị nhỏ */
        }
    }


    .table-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        max-height: 85vh;
        overflow-y: auto;
        padding-bottom: 20px;
        /* Thêm khoảng trống phía dưới để tránh hiển thị chạm đáy */
    }

    .bin-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background-color: rgb(255, 95, 95);
        cursor: pointer;
        border: 2px solid rgb(255, 201, 201);
        transition-duration: 0.3s;
        position: relative;
        overflow: hidden;
    }

    .bin-bottom {
        width: 15px;
        z-index: 2;
    }

    .bin-top {
        width: 17px;
        transform-origin: right;
        transition-duration: 0.3s;
        z-index: 2;
    }

    .bin-button:hover .bin-top {
        transform: rotate(45deg);
    }

    .bin-button:hover {
        background-color: rgb(255, 0, 0);
    }

    .bin-button:active {
        transform: scale(0.9);
    }



    .garbage {
        position: absolute;
        width: 14px;
        height: auto;
        z-index: 1;
        opacity: 0;
        transition: all 0.3s;
    }

    .bin-button:hover .garbage {
        animation: throw 0.4s linear;
    }

    @keyframes throw {
        from {
            transform: translate(-400%, -700%);
            opacity: 0;
        }

        to {
            transform: translate(0%, 0%);
            opacity: 1;
        }
    }


    .scene {
        width: 10em;
        justify-content: center;
        align-items: center;
    }

    .cube {
        color: #ccc;
        cursor: pointer;
        font-family: 'Roboto', sans-serif;
        transition: all 0.85s cubic-bezier(.17, .67, .14, .93);
        transform-style: preserve-3d;
        transform-origin: 100% 50%;
        width: 10em;
        height: 4em;
    }

    .cube:hover {
        transform: rotateX(-90deg);
    }

    .side {
        box-sizing: border-box;
        position: absolute;
        display: inline-block;
        height: 4em;
        width: 10em;
        text-align: center;
        text-transform: uppercase;
        padding-top: 1.5em;
        font-weight: bold;
    }

    .top {
        background: wheat;
        color: #222229;
        transform: rotateX(90deg) translate3d(0, 0, 2em);
        box-shadow: inset 0 0 0 5px #fff;
    }

    .front {
        background: #222229;
        color: #fff;
        box-shadow: inset 0 0 0 5px #fff;
        transform: translate3d(0, 0, 2em);
    }

    .table-o {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }


    #dish-list .dish-item {
        flex: 0 0 50%;
        max-width: 50%;
    }

    #dish-list .dish-combo {
        flex: 0 0 50%;
        max-width: 50%;
    }

    @media (min-width: 850px) and (max-width: 1199px) {
        #dish-list .dish-item {
            flex: 0 0 33.3333%;
            max-width: 33.3333%;
        }

        #dish-list .dish-combo {
            flex: 0 0 33.3333%;
            max-width: 33.3333%;
        }
    }

    @media (min-width: 1200px) {
        #dish-list .dish-item {
            flex: 0 0 25%;
            max-width: 25%;
        }

        #dish-list .dish-combo {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const notificationButton = document.getElementById('notificationButton');
        const printButton = document.getElementById('printButton');

        // Notification Badge Animation
        notificationButton.addEventListener('click', function() {
            alert("Bạn có 3 thông báo mới!");
        });

        // Print Button
        printButton.addEventListener('click', function() {
            alert("Đang in...");
        });

        // Search Bar
        const searchInput = document.getElementById('searchInput');
        const dishItems = document.querySelectorAll('.dish-item');

        searchInput.addEventListener('input', function() {
            const searchTerm = searchInput.value.toLowerCase();

            dishItems.forEach(function(dish) {
                const dishName = dish.querySelector('.menu-item p').textContent.toLowerCase();

                if (dishName.includes(searchTerm)) {
                    dish.style.display = 'block';
                } else {
                    dish.style.display = 'none';
                }
            });
        });
    });

    // Modal danh sách đặt bàn
    // document.getElementById('modalListReservation').addEventListener('click', function() {
    //     $('#reservationListModal').modal('show');
    // });
</script>
