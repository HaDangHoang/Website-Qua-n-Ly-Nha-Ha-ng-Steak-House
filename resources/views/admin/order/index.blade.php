@extends('admin.master')

@section('title', 'Danh Sách Hoá Đơn')

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
                            <div class="card-title">Danh sách hoá đơn</div>

                            {{-- <!-- Nút Thêm Mới và Khôi Phục -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.order.trash') }}"
                                    class="btn btn-sm btn-secondary d-flex align-items-center">
                                    <i class="bi bi-trash3 me-2"></i> Khôi Phục
                                </a>
                            </div> --}}
                        </div>

                        <div class="card-body">

                            <form method="GET" action="{{ route('admin.order.index') }}" class="mb-3">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <input type="text" id="search-id" name="id"
                                            class="form-control form-control-sm" placeholder="Tìm kiếm theo mã đơn hàng">
                                    </div>
                                    <div class="col-auto">
                                        <select name="status" id="status" class="form-control form-control-sm">
                                            <option value="">Tất Cả</option>
                                            <option value="waiting" {{ request('status') === 'waiting' ? 'selected' : '' }}>
                                                Đang Chờ</option>
                                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                                Chờ Xử Lý</option>
                                            <option value="completed"
                                                {{ request('status') === 'completed' ? 'selected' : '' }}>Đã Hoàn Thành
                                            </option>
                                            <option value="cancelled"
                                                {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã Hủy</option>
                                        </select>
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
                                            <th>
                                                <a
                                                    href="{{ route('admin.order.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('direction') === 'asc' && request('sort') === 'id' ? 'desc' : 'asc'])) }}">
                                                    Mã Hoá Đơn
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'id' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}">
                                                    </i>
                                                </a>
                                            </th>
                                            <th>
                                                <a
                                                    href="{{ route('admin.order.index', array_merge(request()->query(), ['sort' => 'reservation_id', 'direction' => request('direction') === 'asc' && request('sort') === 'reservation_id' ? 'desc' : 'asc'])) }}">
                                                    Mã Đặt Bàn
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'reservation_id' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}">
                                                    </i>
                                                </a>
                                            </th>
                                            <th>Nhân Viên</th>
                                            <th>
                                                <a
                                                    href="{{ route('admin.order.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('direction') === 'asc' && request('sort') === 'id' ? 'desc' : 'asc'])) }}">
                                                    Bàn
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'id' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}">
                                                    </i>
                                                </a>
                                            </th>
                                            <th>
                                                <a
                                                    href="{{ route('admin.order.index', array_merge(request()->query(), ['sort' => 'total_amount', 'direction' => request('direction') === 'asc' && request('sort') === 'total_amount' ? 'desc' : 'asc'])) }}">
                                                    Tổng Tiền
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'total_amount' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}">
                                                    </i>
                                                </a>
                                            </th>

                                            <th>
                                                <a
                                                    href="{{ route('admin.order.index', array_merge(request()->query(), ['sort' => 'final_amount', 'direction' => request('direction') === 'asc' && request('sort') === 'final_amount' ? 'desc' : 'asc'])) }}">
                                                    Số Tiền Cuối Cùng
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'final_amount' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}">
                                                    </i>
                                                </a>
                                            </th>
                                            <th>Trạng Thái</th>
                                            <th>Ngày Tạo</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->reservation_id }}</td>
                                                <td>{{ $order->staff->name ?? 'Không rõ' }}</td>
                                                <td>{{ $order->tables['0']->id ?? 'Không rõ' }}</td>
                                                <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>


                                                <td>{{ number_format($order->final_amount, 0, ',', '.') }} VND</td>

                                                <td>
                                                    @if ($order->status === 'completed')
                                                        <span class="badge bg-success">Hoàn thành</span>
                                                    @elseif ($order->status === 'pending')
                                                        <span class="badge bg-warning text-dark">Đang xử lý</span>
                                                    @elseif ($order->status === 'cancelled')
                                                        <span class="badge bg-danger">Đã hủy</span>
                                                    @else
                                                        <span class="badge bg-secondary">Không rõ</span>
                                                    @endif
                                                </td>

                                                {{-- <td>{{ $order->created_at->format('Y-m-d H:i') }}</td> --}}
                                                <td style="text-align: center">

                                                    <span
                                                        class="text-success">{{ \Carbon\Carbon::parse($order->change_date . ' ' . $order->change_time)->format('H:i:s') }}</span><br>
                                                    {{ \Carbon\Carbon::parse($order->change_date)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="{{ route('admin.order.show', $order->id) }}"
                                                            class="viewRow" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Chi tiết">
                                                            <i class="bi bi-list text-green"></i>
                                                        </a>
                                                        <a href="{{ route('admin.order.edit', $order->id) }}"
                                                            class="editRow" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Sửa">
                                                            <i class="bi bi-pencil-square text-warning"></i>
                                                        </a>
                                                        {{-- <a href="" class="viewRow" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Xoá">
                                                            <form action="{{ route('admin.order.destroy', $order->id) }}"
                                                                method="POST" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link p-0"
                                                                   >
                                                                    <i class="bi bi-trash text-danger"
                                                                        style="font-size: 1.2rem;"></i>
                                                                </button>
                                                            </form>
                                                        </a> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Không có đơn hàng nào được tìm thấy.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>



                        </div> <!-- Pagination -->
                        <div class="d-flex justify-content-center">

                            {{ $orders->links('pagination::client-paginate') }}

                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->
        </div>
        <!-- Content wrapper end -->

    </div>

@endsection
