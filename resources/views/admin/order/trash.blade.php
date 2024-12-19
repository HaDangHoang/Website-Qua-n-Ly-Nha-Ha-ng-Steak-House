@extends('admin.master')

@section('title', 'Thùng Rác Đơn Hàng')

@section('content')
@include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title ">Thùng Rác Đơn Hàng</h5>
                            <a href="{{ route('admin.order.index') }}" class="btn btn-sm btn-primary">
                                Quay lại
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>ID Đơn Hàng</th>
                                            <th>ID Đặt Chỗ</th>
                                            <th>ID Nhân Viên</th>
                                            <th>ID Khách Hàng</th>
                                            <th>Tổng Tiền</th>
                                            <th>Trạng Thái</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->reservation_id }}</td>
                                                <td>{{ $order->staff_id }}</td>
                                                <td>{{ $order->customer_id }}</td>
                                                <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                                                <td>{{ $order->status }}</td>
                                                <td>
                                                    <div class="actions">
                                                        <!-- Khôi phục -->
                                                        <form action="{{ route('admin.order.restore', $order->id) }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <a href="#"> <button type="submit"
                                                                    class="btn btn-link p-0"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục đơn hàng này không?');">
                                                                    <i class="bi bi-arrow-clockwise text-green"></i>
                                                                </button></a>
                                                        </form>

                                                        <!-- Xóa vĩnh viễn -->
                                                        <form action="{{ route('admin.order.forceDelete', $order->id) }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#">
                                                                <button type="submit" class="btn btn-link p-0"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn đơn hàng này không?');">
                                                                    <i class="bi bi-trash text-red"></i>
                                                                </button></a>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có đơn hàng nào trong thùng
                                                    rác.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination justify-content-center mt-3">
                                {{ $orders->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
