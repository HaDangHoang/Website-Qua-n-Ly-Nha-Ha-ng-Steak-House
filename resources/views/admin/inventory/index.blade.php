@extends('admin.master')

@section('title', 'Danh Sách Kho')

@section('content')
    @include('admin.layouts.messages')
    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">
        <!-- Content wrapper start -->
        <div class="content-wrapper">
            <!-- Toast notifications container -->
            <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3">
                @foreach ($outOfStock as $item)
                    @if ($item->ingredient)
                        <div class="toast inventory-alert" data-id="{{ $item->id }}" role="alert" aria-live="assertive"
                            aria-atomic="true" data-bs-delay="5000">
                            <div class="toast-header bg-danger text-white">
                                <strong class="me-auto">Cảnh báo tồn kho</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                Mặt hàng: {{ $item->ingredient->name }}, với ID: {{ $item->id }} đã hết hàng.
                            </div>
                        </div>
                    @endif
                @endforeach

                @foreach ($lowStock as $item)
                    @if ($item->ingredient)
                        <div class="toast inventory-alert" data-id="{{ $item->id }}" role="alert"
                            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                            <div class="toast-header bg-warning text-white">
                                <strong class="me-auto">Cảnh báo tồn kho</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                Mặt hàng: {{ $item->ingredient->name }}, với ID: {{ $item->id }} có số lượng thấp
                                ({{ $item->quantity_stock }})
                                .
                            </div>
                        </div>
                    @endif
                @endforeach

                @foreach ($highStock as $item)
                    @if ($item->ingredient)
                        <div class="toast inventory-alert" data-id="{{ $item->id }}" role="alert"
                            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                            <div class="toast-header bg-success text-white">
                                <strong class="me-auto">Thông báo tồn kho</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                Mặt hàng: {{ $item->ingredient->name }}, với ID: {{ $item->id }} có số lượng lớn cần ưu
                                tiên sử dụng ({{ $item->quantity_stock }}).
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>



            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh Sách Kho</div>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Search form -->
                            <form method="GET" action="{{ route('admin.inventory.index') }}" class="mb-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input type="text" name="search" class="form-control form-control-sm"
                                            placeholder="Tìm kiếm theo ID hoặc tên" value="{{ request('search') }}">
                                    </div>
                                    <div class="col-auto">
                                        <select name="status" class="form-select form-select-sm" id="statusFilter">
                                            <option value="">Chọn trạng thái tồn kho</option>
                                            <option value="out_of_stock"
                                                {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng
                                            </option>
                                            <option value="low_stock"
                                                {{ request('status') == 'low_stock' ? 'selected' : '' }}>Sắp hết</option>
                                            <option value="high_stock"
                                                {{ request('status') == 'high_stock' ? 'selected' : '' }}>Nhiều hàng tồn
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <!-- Table list of inventory stocks -->
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th> <a
                                                    href="{{ route('admin.inventory.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                    ID
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'id' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                                </a></th>
                                            <th>Tên Nguyên Liệu</th>
                                            <th>Đơn Vị</th>
                                            <th> <a
                                                    href="{{ route('admin.inventory.index', array_merge(request()->query(), ['sort' => 'quantity_stock', 'direction' => request('sort') === 'quantity_stock' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                    Số Lượng Tồn Kho
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'quantity_stock' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                                </a></th>

                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($inventoryStocks as $stock)
                                            <tr id="inventory-stock-{{ $stock->id }}">
                                                <td>{{ $stock->id }}</td>
                                                <td>{{ $stock->ingredient->name ?? 'Không rõ' }}</td>
                                                <td>{{ $stock->ingredient->unit }}</td>
                                                <td>{{ $stock->quantity_stock }}</td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="{{ route('admin.inventory.edit', $stock->id) }}"
                                                            class="editRow" data-id="{{ $stock->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Sửa">
                                                            <i class="bi bi-pencil-square text-warning"></i>
                                                        </a>
                                                        <form action="{{ route('admin.inventory.destroy', $stock->id) }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" style="margin-top: 15px;"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Xoá">
                                                                <button type="submit" class="btn btn-link p-0"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                                    <i class="bi bi-trash text-red"></i>
                                                                </button>
                                                            </a>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">Không có kho nào được tìm thấy.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>


                        </div>

                    </div>
                    <div class="d-flex justify-content-center">

                        {{ $inventoryStocks->links('pagination::client-paginate') }}

                    </div>
                </div>
            </div>
            <!-- Row end -->
        </div>
        <!-- Row end -->
    </div>
    <!-- Content wrapper scroll end -->
@endsection

<style>
    .highlight-row {
        border: 2px solid #28a745;
        transition: all 0.3s ease;
    }

    .form-control,
    .form-select {
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn {
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .badge {
        font-weight: bold;
        padding: 0.5em 1em;
        border-radius: 0.25rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastElements = document.querySelectorAll('.toast');
        toastElements.forEach(function(toastElement) {
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        });
    });
</script>
