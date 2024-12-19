@extends('admin.master')

@section('title', 'Danh Sách Nhà Cung Cấp')

@section('content')
    @include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh Sách Nhà Cung Cấp</div>
                            <a href="{{ route('admin.supplier.import') }}">

                                {{-- <input type="file" name="file" accept=".xlsx, .xls" class="form-control form-control-sm d-none" id="import-file"> --}}
                                <button type="button" id="import-button"
                                    class="btn btn-sm btn-success d-flex align-items-center">
                                    <i class="bi bi-file-earmark-arrow-up me-2"></i> Import Excel
                                </button>
                            </a>
                            <a href="{{ route('admin.supplier.create') }}"
                                class="btn btn-sm btn-primary d-flex align-items-center">
                                <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                            </a>
                        </div>
                        <div class="card-body">

                            <form method="GET" action="{{ route('admin.supplier.index') }}" class="mb-3">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <input type="text" id="search-name" name="name"
                                            class="form-control form-control-sm"
                                            placeholder="Tìm kiếm theo tên nhà cung cấp" value="{{ request('name') }}">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                        <a href="{{ route('admin.supplier.index') }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th> <a
                                                href="{{ route('admin.supplier.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                ID
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'id' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                            </a></th>
                                            <th> <a
                                                href="{{ route('admin.supplier.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Tên Nhà Cung Cấp
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'name' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                            </a></th>
                                            <th> <a
                                                href="{{ route('admin.supplier.index', array_merge(request()->query(), ['sort' => 'phone', 'direction' => request('sort') === 'phone' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Số Điện Thoại
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'phone' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                            </a></th>
                                            <th> <a
                                                href="{{ route('admin.supplier.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => request('sort') === 'email' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Email
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'email' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                            </a></th>
                                            <th>Địa Chỉ</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($suppliers as $supplier)
                                            <tr>
                                                <td>{{ $supplier->id }}</td>
                                                <td>{{ $supplier->name }}</td>
                                                <td>{{ $supplier->phone }}</td>
                                                <td>{{ $supplier->email }}</td>
                                                <td>{{ $supplier->address }}</td>
                                                <td>
                                                    <div class="actions d-flex">
                                                        <a href="{{ route('admin.supplier.edit', $supplier->id) }}"
                                                            class="viewRow" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Sửa">
                                                            <i class="bi bi-pencil-square text-warning"></i>
                                                        </a>
                                                        <a href="" class="viewRow" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Xoá">
                                                            <form
                                                                action="{{ route('admin.supplier.destroy', $supplier->id) }}"
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
                                                <td colspan="5" class="text-center">Không có nhà cung cấp nào được tìm
                                                    thấy.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>


                        </div>
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">

                            {{ $suppliers->links('pagination::client-paginate') }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
