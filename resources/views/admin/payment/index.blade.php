@extends('admin.master')

@section('title', 'Danh Sách Thanh Toán')

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
                            <div class="card-title">Danh sách Thanh toán</div>

                            <!-- Nút Thêm Mới và Khôi Phục -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.payment.trash') }}"
                                    class="btn btn-sm btn-secondary d-flex align-items-center">
                                    <i class="bi bi-trash3 me-2"></i> Khôi Phục
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.payment.index') }}" class="mb-3">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <input type="text" id="search-id" name="id"
                                            class="form-control form-control-sm" placeholder="Tìm kiếm theo mã thanh toán">
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
                                            <th>Mã Thanh Toán</th>
                                            <th>Mã Đặt Bàn</th>
                                            <th>Mã Hóa Đơn</th>
                                            <th>Số Tiền</th>
                                            <th>Số Tiền Hoàn Lại</th>
                                            <th>Trạng Thái Giao Dịch</th>
                                            <th>Phương Thức Thanh Toán</th>
                                            <th>Trạng Thái</th>
                                            <th>Ngày Tạo</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->id }}</td>
                                                <td>{{ $payment->reservation_id }}</td>
                                                <td>{{ $payment->bill_id }}</td>
                                                <td>{{ number_format($payment->transaction_amount, 0, ',', '.') }} VND</td>
                                                <td>{{ number_format($payment->refund_amount, 0, ',', '.') }} VND</td>

                                                <td>
                                                    @if ($payment->transaction_status === 'completed')
                                                        <span class="badge rounded-pill shade-primary">Hoàn thành</span>
                                                    @elseif ($payment->transaction_status === 'pending')
                                                        <span class="badge rounded-pill shade-yellow">Đang xử lý</span>
                                                    @elseif ($payment->transaction_status === 'failed')
                                                        <span class="badge rounded-pill shade-red">Thất bại</span>
                                                    @else
                                                        Không rõ
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($payment->payment_method === 'Cash')
                                                        <span class="badge rounded-pill shade-primary">Tiền mặt</span>
                                                    @elseif ($payment->payment_method === 'Credit Card')
                                                        <span class="badge rounded-pill shade-secondary">Thẻ tín dụng</span>
                                                    @elseif ($payment->payment_method === 'Online')
                                                        <span class="badge rounded-pill shade-red">Thanh toán trực
                                                            tuyến</span>
                                                    @else
                                                        Không rõ
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($payment->status === 'Completed')
                                                        <span class="badge bg-success">Hoàn thành</span>
                                                    @elseif ($payment->status === 'Pending')
                                                        <span class="badge bg-warning text-dark">Đang xử lý</span>
                                                    @elseif ($payment->status === 'Failed')
                                                        <span class="badge bg-danger">Thất bại</span>
                                                    @else
                                                        <span class="badge bg-secondary">Không rõ</span>
                                                    @endif
                                                </td>


                                                <td style="text-align: center">

                                                    {{ \Carbon\Carbon::parse($payment->change_date . ' ' . $payment->change_time)->format('H:i:s') }}<br>
                                                    {{ \Carbon\Carbon::parse($payment->change_date)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="{{ route('admin.payment.show', $payment->id) }}"
                                                            class="viewRow" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Chi tiết">
                                                            <i class="bi bi-list text-green"></i>
                                                        </a>
                                                        <a href="{{ route('admin.payment.edit', $payment->id) }}"
                                                            class="editRow" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Sửa">
                                                            <i class="bi bi-pencil-square text-warning"></i>
                                                        </a>

                                                        <form action="{{ route('admin.payment.destroy', $payment->id) }} "
                                                            style="margin-top: 15px;" method="POST"
                                                            style="display:inline-block; " data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="xoá">
                                                            @csrf
                                                            @method('DELETE') <a href="#">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <i class="bi bi-trash text-danger"
                                                                        style="font-size: 1.2rem;"></i>
                                                                </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Không có thanh toán nào được tìm thấy.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>



                        </div>
                        <div class="d-flex justify-content-center">

                            {{ $payments->links('pagination::client-paginate') }}

                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->
        </div>
        <!-- Content wrapper end -->

    </div>



@endsection
