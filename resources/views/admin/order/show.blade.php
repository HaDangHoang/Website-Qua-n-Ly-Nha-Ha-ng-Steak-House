@extends('admin.master')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')
    @include('admin.layouts.messages')
    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">
        <!-- Content wrapper start -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg rounded-4">
                        <div
                            class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                            <h4 class="card-title mb-0 ">Chi Tiết Hoá Đơn </h4>
                            <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-secondary text-dark mb-0">Quay
                                Lại</a>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <!-- Thông Tin Đơn Hàng -->
                                <div class="col-md-6">
                                    <h5 class="text-blue font-weight-bold">Thông Tin Hoá Đơn</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Mã Hoá Đơn:</strong> <span
                                                class="font-weight-bold text-primary">{{ $order->id }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Mã Đặt Bàn:</strong>
                                            <span>{{ $order->reservation->id ?? 'Không rõ' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Nhân Viên:</strong> <span>{{ $order->staff->name ?? 'Không rõ' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Bàn:</strong> <span>{{ $order->table->id ?? 'Không rõ' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Khách Hàng:</strong>
                                            <span>{{ $order->customer->name ?? 'Không rõ' }}</span>
                                        </li>
                                        {{-- <!-- Thêm phần hiển thị loại đơn hàng -->
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Loại Đơn Hàng:</strong>
                                            <span>{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                                        </li> --}}
                                    </ul>
                                </div>

                                <!-- Chi Tiết Thanh Toán -->
                                <div class="col-md-6">
                                    <h5 class="text-blue font-weight-bold">Chi Tiết Thanh Toán</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Tổng Tiền:</strong> <span
                                                class="font-weight-bold text-primary">{{ number_format($order->total_amount, 0, ',', '.') }}
                                                VND</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Số Tiền Cuối Cùng:</strong> <span
                                                class="font-weight-bold text-success">{{ number_format($order->final_amount, 0, ',', '.') }}
                                                VND</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Trạng Thái:</strong>
                                            <span>
                                                @if ($order->status === 'completed')
                                                    <span class="badge bg-success">Hoàn thành</span>
                                                @elseif ($order->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Đang xử lý</span>
                                                @elseif ($order->status === 'cancelled')
                                                    <span class="badge bg-danger">Đã hủy</span>
                                                @else
                                                    <span class="badge bg-secondary">Không rõ</span>
                                                @endif
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Thời Gian -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-blue font-weight-bold">Thời Gian</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Ngày Tạo:</strong>
                                            <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Ngày Cập Nhật:</strong>
                                            <span>{{ $order->updated_at->format('Y-m-d H:i') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Chi Tiết Món Ăn -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-blue font-weight-bold">Chi Tiết Món Ăn</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>Tên Món</th>
                                                    <th>Số Lượng</th>
                                                    <th>Đơn Giá</th>
                                                    <th>Thành Tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->items as $item)
                                                    <tr>
                                                        <td>{{ $item->dish->name }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->unit_price, 0, ',', '.') }} VND</td>
                                                        <td>{{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}
                                                            VND</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('admin.order.edit', $order->id) }}"
                                    class="btn btn-warning mt-3 px-5 py-2">Chỉnh Sửa Đơn Hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content wrapper end -->

    </div>

@endsection
