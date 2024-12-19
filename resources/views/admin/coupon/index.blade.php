@extends('admin.master')

@section('title', 'Danh Sách Phiếu Giảm Giá')

@section('content')
    @include('admin.layouts.messages')



    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3">
        @if ($UpcomingCouponEvent->count() > 0)
            <div class="toast coupon-alert" data-ids="{{ $UpcomingCouponEvent->pluck('id')->implode(',') }}" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="toast-header bg-info text-white">
                    <strong class="me-auto">Thông báo</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Có {{ $UpcomingCouponEvent->count() }} mã giảm giá sắp hết hạn sau 24 giờ nữa
                </div>
            </div>
        @endif

        @if ($OverdueCouponEvent->count() > 0)
            <div class="toast coupon-alert" data-ids="{{ $OverdueCouponEvent->pluck('id')->implode(',') }}" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="toast-header bg-danger text-white">
                    <strong class="me-auto">Cảnh báo</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Có {{ $OverdueCouponEvent->count() }} mã giảm giá đã quá hạn và bị huỷ
                </div>
            </div>
        @endif
    </div>

    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh sách phiếu giảm giá</div>
                            <!-- Các nút thêm mới, khôi phục -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.coupon.create') }}"
                                    class="btn btn-sm btn-primary d-flex align-items-center">
                                    <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                                </a>
                                <a href="{{ route('admin.coupon.trash') }}"
                                    class="btn btn-sm btn-secondary d-flex align-items-center">
                                    <i class="bi bi-trash3 me-2"></i> Khôi Phục
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Form tìm kiếm và lọc -->
                            <form method="GET" action="{{ route('admin.coupon.index') }}" class="mb-3">
                                <div class="row g-2">
                                    <!-- Tìm kiếm theo mã coupon -->
                                    <div class="col-auto">
                                        <input type="text" id="search-code" name="code"
                                            class="form-control form-control-sm" placeholder="Tìm kiếm mã Giảm Giá"
                                            value="{{ request('code') }}">
                                    </div>

                                    <!-- Lọc theo trạng thái -->
                                    <div class="col-auto">
                                        <select name="status" id="status" class="form-control form-control-sm">
                                            <option value="">Tất Cả</option>
                                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                                Hoạt Động</option>
                                            <option value="inactive"
                                                {{ request('status') === 'inactive' ? 'selected' : '' }}>Ngừng Hoạt Động
                                            </option>
                                            <option value="expired"
                                                {{ request('status') === 'expired' ? 'selected' : '' }}>Hết Hạn</option>
                                        </select>
                                    </div>

                                    <!-- Nút tìm kiếm -->
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>



                            <!-- Bảng danh sách coupons -->
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <a
                                                    href="{{ route('admin.coupon.index', array_merge(request()->query(), ['sort' => 'code', 'direction' => request('direction') === 'asc' && request('sort') === 'code' ? 'desc' : 'asc'])) }}">
                                                    Mã Giảm Giá
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'code' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}">
                                                    </i>
                                                </a>
                                            </th>
                                            <th class="text-center">Mô Tả</th>
                                            <th>
                                                <a
                                                    href="{{ route('admin.coupon.index', array_merge(request()->query(), ['sort' => 'max_uses', 'direction' => request('direction') === 'asc' && request('sort') === 'max_uses' ? 'desc' : 'asc'])) }}">
                                                    Số Lượt Sử Dụng
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'max_uses' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}">
                                                    </i>
                                                </a>
                                            </th>
                                            <th class="text-center">Loại Giảm Giá</th>
                                            <th class="text-center">Số Tiền Giảm</th>
                                            <th class="text-center">Bắt Đầu</th>
                                            <th class="text-center">Kết Thúc</th>
                                            <th class="text-center">Trạng Thái</th>
                                            <th class="text-center">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($coupons as $coupon)
                                            <tr>
                                                <td class="text-center">{{ $coupon->code }}</td>
                                                <td class="text-center">{{ $coupon->description }}</td>
                                                <td class="text-center">{{ $coupon->max_uses }}</td>
                                                <td class="text-center">
                                                    @if ($coupon->discount_type == 'Percentage')
                                                        <span class="badge border border-warning text-warning">Phần
                                                            trăm
                                                            (%)
                                                        </span>
                                                    @else
                                                        <span class="badge border border-success text-success">Giá
                                                            Tiền
                                                            (VND)</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($coupon->discount_type == 'Percentage')
                                                        {{ (int) $coupon->discount_amount }}%
                                                    @else
                                                        {{ number_format($coupon->discount_amount, 0, ',', '.') }} VND
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="text-success">{{ \Carbon\Carbon::parse($coupon->start_time)->format('H:i:s') }}</span><br>
                                                    <span
                                                        class="text-secondary">{{ \Carbon\Carbon::parse($coupon->start_time)->format('d/m/Y') }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="text-danger">{{ \Carbon\Carbon::parse($coupon->end_time)->format('H:i:s') }}</span><br>
                                                    <span
                                                        class="text-secondary">{{ \Carbon\Carbon::parse($coupon->end_time)->format('d/m/Y') }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($coupon->status == 'active')
                                                        <span class="badge shade-green">Hoạt Động</span>
                                                    @elseif ($coupon->status == 'inactive')
                                                        <span class="badge shade-yellow">Ngừng Hoạt Động</span>
                                                    @else
                                                        <span class="badge shade-red">Hết Hạn</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="actions">
                                                        <a href="{{ route('admin.coupon.show', $coupon->id) }}"
                                                            class="viewRow" data-id="{{ $coupon->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Chi tiết">
                                                            <i class="bi bi-list text-green"></i>
                                                        </a>
                                                        <a href="{{ route('admin.coupon.edit', $coupon->id) }}"
                                                            class="editRow" data-id="{{ $coupon->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Sửa">
                                                            <i class="bi bi-pencil-square text-warning"></i>
                                                        </a>
                                                        <a href="#" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Xoá">
                                                            <form
                                                                action="{{ route('admin.coupon.destroy', $coupon->id) }}"
                                                                method="POST" style="display:inline-block;"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <i class="bi bi-trash text-danger"
                                                                        style="font-size: 1.2rem;"></i>
                                                                </button>
                                                            </form>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Không có phiếu giảm giá nào
                                                    được tìm
                                                    thấy.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-center">

                        {{ $coupons->links('pagination::client-paginate') }}

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content wrapper scroll end -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastElList = [].slice.call(document.querySelectorAll('.toast'));
            const toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl);
            });

            toastList.forEach(toast => toast.show());

            const alerts = document.querySelectorAll('.coupon-alert');
            alerts.forEach(alert => {
                alert.addEventListener('click', function() {
                    const ids = this.getAttribute('data-ids').split(',');
                    ids.forEach(id => {
                        const row = document.getElementById('coupon-' + id);
                        if (row) {
                            row.classList.add('highlight-row');
                            row.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    });
                });
            });

            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');

            statusFilter.addEventListener('change', function() {
                console.log("Trạng thái đã được thay đổi: ", this.value);
            });

            dateFilter.addEventListener('change', function() {
                console.log("Ngày đã được thay đổi: ", this.value);

            });
        });
    </script>

@endsection
