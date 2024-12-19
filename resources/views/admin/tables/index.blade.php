@extends('admin.master')

@section('title', 'Danh Mục Bàn')

@section('content')
    @include('admin.layouts.messages')


    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title ">Danh Mục Bàn</div>

                            <!-- Thêm nút Thêm Mới -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.table.create') }}"
                                    class="btn btn-sm btn-primary d-flex align-items-center">
                                    <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                                </a>

                                <!-- Thêm nút Khôi Phục -->
                                <a href="{{ route('admin.tables.trash') }}"
                                    class="btn btn-sm btn-secondary d-flex align-items-center">
                                    <i class="bi bi-trash3 me-2"></i> Khôi Phục
                                </a>
                            </div>
                        </div>


                        <div class="card-body">
                            <!-- Form tìm kiếm -->
                            <div class="filter-form">
                                <form method="GET" action="{{ route('admin.table.index') }}" class="mb-3">
                                    <div class="row g-2">
                                        <!-- Tìm kiếm theo số bàn -->
                                        <div class="col-auto">
                                            <input type="text" id="search-name" name="name"
                                                class="form-control form-control-sm" placeholder="Tìm kiếm bàn"
                                                value="{{ request('name') }}">
                                        </div>

                                        <!-- Lọc theo trạng thái bàn -->
                                        <div class="col-auto">
                                            <select name="status" id="search-status" class="form-select form-select-sm">
                                                <option value="">Tất Cả</option>
                                                <option value="Available"
                                                    {{ request('status') == 'Available' ? 'selected' : '' }}>Có sẵn</option>
                                                <option value="Reserved"
                                                    {{ request('status') == 'Reserved' ? 'selected' : '' }}>Đã đặt trước
                                                </option>
                                                <option value="Occupied"
                                                    {{ request('status') == 'Occupied' ? 'selected' : '' }}>Đang sử dụng
                                                </option>
                                            </select>
                                        </div>



                                        <!-- Nút Tìm kiếm và làm mới -->
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                            <a href="{{ route('admin.table.index') }}" class="btn btn-sm btn-success">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>



                            <div class="table-responsive">
                                <table class="table v-middle table-hover m-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <a
                                                    href="{{ route('admin.table.index', array_merge(request()->query(), ['sort' => 'area', 'direction' => request('sort') === 'area' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                    Khu Vực
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'area' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                                </a>
                                            </th>

                                            <th>
                                                <a
                                                    href="{{ route('admin.table.index', array_merge(request()->query(), ['sort' => 'table_number', 'direction' => request('sort') === 'table_number' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                    Số Bàn
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'table_number' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                                </a>
                                            </th>

                                            <th>Trạng Thái</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tables as $table)
                                            <tr>
                                                <td>{{ $table->area }}</td>
                                                <td>{{ $table->table_number }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $table->status == 'Available' ? 'bg-success' : ($table->status == 'Reserved' ? 'bg-warning' : 'bg-danger') }} min-70">
                                                        {{ $table->status == 'Available' ? 'Có sẵn' : ($table->status == 'Reserved' ? 'Đã đặt trước' : 'Đang sử dụng') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="{{ route('admin.table.edit', $table->id) }}"
                                                            class="text-warning" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Sửa">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="" style=" padding-bottom: 5px;padding-left: 3px;">
                                                            <form action="{{ route('admin.table.destroy', $table->id) }} "
                                                                method="POST" style="display:inline-block;"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <i class="bi bi-trash text-red"></i>
                                                                </button>
                                                            </form>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Không có bàn nào được tìm thấy.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">

                            {{ $tables->links('pagination::client-paginate') }}

                        </div>

                    </div>


                </div>

            </div>
            <!-- Row end -->


        </div>
        <!-- Content wrapper end -->

    </div>
    <!-- Content wrapper scroll end -->

@endsection
