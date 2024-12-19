@extends('admin.master')

@section('title', 'Danh Sách Lịch Sử Đặt Bàn')

@section('content')
    @include('admin.layouts.messages')

    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh Sách Lịch Sử Đặt Bàn</div>

                        </div>
                        <div class="card-body">


                            <form method="GET" action="{{ route('admin.reservationHistory.index') }}" class="mb-3">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <input type="text" id="search-customer" name="customer_name"
                                            class="form-control form-control-sm" placeholder="Tìm kiếm ">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>Mã Lịch Sử</th>
                                            <th>Mã Đặt Chỗ</th>
                                            <th>Tên Khách Hàng</th>
                                            <th>Thời Gian Thay Đổi</th>
                                            <th>Trạng Thái</th>
                                            <th>Ghi Chú</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reservationHistories as $history)
                                            <tr>
                                                <td>{{ $history->id }}</td>
                                                <td>{{ optional($history->reservation)->id ?? 'Không rõ' }}</td>
                                                <td>{{ optional(optional($history->reservation)->customer)->name ?? 'Không rõ' }}
                                                </td>
                                                <td>

                                                    {{ \Carbon\Carbon::parse($history->reservation_date . ' ' . $history->change_time)->format('H:i:s') }}<br>
                                                    {{ \Carbon\Carbon::parse($history->change_date)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    @switch($history->status)
                                                        @case('pending')
                                                            <span class="badge shade-yellow min-70">Chờ xử lý</span>
                                                        @break

                                                        @case('confirmed')
                                                            <span class="badge shade-green min-70">Đã xác nhận</span>
                                                        @break

                                                        @case('cancelled')
                                                            <span class="badge shade-red min-70">Đã hủy</span>
                                                        @break

                                                        @case('completed')
                                                            <span class="badge shade-blue min-70">Hoàn thành</span>
                                                        @break

                                                        @default
                                                            <span class="badge shade-gray min-70">Không rõ</span>
                                                    @endswitch
                                                </td>

                                                <td>{{ $history->note }}</td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="#" class="viewRow" data-bs-toggle="modal"
                                                            data-bs-target="#viewRow" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Sửa">
                                                            <i class="bi bi-list text-green"></i>
                                                        </a>
                                                        <a href="#" class="deleteRow" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Xoá">
                                                            <button type="submit" class="btn btn-link p-0">
                                                                <i class="bi bi-trash text-red"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8">Không có lịch sử đặt chỗ nào được tìm thấy.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>



                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="pagination justify-content-center mt-3">
                                    {{-- {{ $reservationHistory->links() }} --}}
                                </div>
                                <!-- Kết thúc Pagination -->

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row end -->

            </div>
            <!-- Content wrapper end -->

        </div>
        <!-- Content wrapper scroll end -->

        <!-- Modal hiển thị chi tiết -->
        <div class="modal fade" id="viewRow" tabindex="-1" aria-labelledby="viewRowLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewRowLabel">Chi Tiết Lịch Sử Đặt Chỗ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="historyId" class="form-label">Mã Lịch Sử</label>
                                    <input type="text" class="form-control" id="historyId" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reservationId" class="form-label">Mã Đặt Chỗ</label>
                                    <input type="text" class="form-control" id="reservationId" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Tên Khách Hàng</label>
                            <input type="text" class="form-control" id="customerName" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="changeTime" class="form-label">Thời Gian Thay Đổi</label>
                            <input type="text" class="form-control" id="changeTime" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="Status" class="form-label">Trạng Thái </label>
                            <input type="text" class="form-control" id="Status" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi Chú</label>
                            <textarea class="form-control" id="note" rows="3" readonly></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript để xử lý sự kiện nhấp vào hàng và hiển thị chi tiết trong modal -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rows = document.querySelectorAll('.viewRow');

                rows.forEach(function(row) {
                    row.addEventListener('click', function() {
                        const historyId = this.closest('tr').querySelector('td:nth-child(1)')
                            .textContent;
                        const reservationId = this.closest('tr').querySelector('td:nth-child(2)')
                            .textContent;
                        const customerName = this.closest('tr').querySelector('td:nth-child(3)')
                            .textContent;
                        const changeTime = this.closest('tr').querySelector('td:nth-child(4)')
                            .textContent;
                        const newStatus = this.closest('tr').querySelector('td:nth-child(5)')
                            .textContent.trim();
                        const note = this.closest('tr').querySelector('td:nth-child(6)').textContent;

                        document.getElementById('historyId').value = historyId;
                        document.getElementById('reservationId').value = reservationId;
                        document.getElementById('customerName').value = customerName;
                        document.getElementById('changeTime').value = changeTime;
                        document.getElementById('Status').value = newStatus; // Update here
                        document.getElementById('note').value = note;
                    });
                });
            });
        </script>

    @endsection
