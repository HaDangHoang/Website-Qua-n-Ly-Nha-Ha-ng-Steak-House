@extends('admin.master')

@section('title', 'Danh Sách Nguyên Liệu')

@section('content')
@include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh Sách Nguyên Liệu</div>
                            <form id="search-form" class="d-flex">
                                <input type="text" id="search-input" name="search" class="form-control me-2"
                                    placeholder="Tìm nguyên liệu" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary" id="search-button">
                                    <i class="bi bi-search me-2"></i>
                                </button>
                            </form>

                            <a href="{{ route('admin.ingredient.import') }}">
                                <button type="button" id="import-button"
                                    class="btn btn-sm btn-success d-flex align-items-center">
                                    <i class="bi bi-file-earmark-arrow-up me-2"></i> Import Excel
                                </button>
                            </a>
                            <a href="{{ route('admin.ingredient.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                            </a>
                        </div>

                        <div class="card-body">

                            <!-- Bảng Đồ Tươi -->
                            <h5 class="mb-3 text-success">Đồ Tươi</h5>
                            <table class="table v-middle m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <a
                                                href="{{ route('admin.ingredient.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                ID
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'id' && request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            </a>
                                        </th>
                                        <th>Tên Nguyên Liệu</th>
                                        <th>
                                            <a
                                                href="{{ route('admin.ingredient.index', array_merge(request()->query(), ['sort' => 'price', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Giá
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'price' && request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            </a>
                                        </th>
                                        <th>
                                            <a
                                                href="{{ route('admin.ingredient.index', array_merge(request()->query(), ['sort' => 'unit', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Đơn Vị
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'unit' && request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            </a>
                                        </th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($freshIngredients as $ingredient)
                                        <tr>
                                            <td>{{ $ingredient->id }}</td>
                                            <td>{{ $ingredient->name }}</td>
                                            <td>{{ number_format($ingredient->price, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $ingredient->unit }}</td>
                                            <td>
                                                <div class="actions d-flex gap-2">
                                                    <a href="{{ route('admin.ingredient.show', $ingredient->id) }}"
                                                        class="text-success" title="Xem">
                                                        <i class="bi bi-list"></i>
                                                    </a>
                                                    <a href="{{ route('admin.ingredient.edit', $ingredient->id) }}"
                                                        class="text-warning" title="Chỉnh sửa">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <a href="">
                                                        <form
                                                            action="{{ route('admin.ingredient.destroy', $ingredient->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="border-0 bg-transparent text-danger"
                                                                title="Xóa"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $freshIngredients->appends(['cannedPage' => request('cannedPage')])->links() }}
                            </div>

                            <!-- Bảng Đồ Đóng Hộp -->
                            <h5 class="mt-5 mb-3 text-primary">Đồ Đóng Hộp</h5>
                            <table class="table v-middle m-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <a
                                                href="{{ route('admin.ingredient.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                ID
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'id' && request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            </a>
                                        </th>
                                        <th>Tên Nguyên Liệu</th>
                                        <th>
                                            <a
                                                href="{{ route('admin.ingredient.index', array_merge(request()->query(), ['sort' => 'price', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Giá
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'price' && request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            </a>
                                        </th>
                                        <th>
                                            <a
                                                href="{{ route('admin.ingredient.index', array_merge(request()->query(), ['sort' => 'unit', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                Đơn Vị
                                                <i
                                                    class="bi bi-arrow-{{ request('sort') === 'unit' && request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                            </a>
                                        </th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cannedIngredients as $ingredient)
                                        <tr>
                                            <td>{{ $ingredient->id }}</td>
                                            <td>{{ $ingredient->name }}</td>
                                            <td>{{ number_format($ingredient->price, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $ingredient->unit }}</td>
                                            <td>
                                                <div class="actions d-flex gap-2">
                                                    <a href="{{ route('admin.ingredient.show', $ingredient->id) }}"
                                                        class="text-primary" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Chi tiết">
                                                        <i class="bi bi-list"></i>
                                                    </a>
                                                    <a href="{{ route('admin.ingredient.edit', $ingredient->id) }}"
                                                        class="text-warning" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Sửa">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <a href="">
                                                        <form
                                                            action="{{ route('admin.ingredient.destroy', $ingredient->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="border-0 bg-transparent text-danger"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Xoá"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $cannedIngredients->appends(['freshPage' => request('freshPage')])->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('search-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Ngừng gửi form mặc định

            const searchTerm = document.getElementById('search-input').value;

            // Tạo URL mới với tham số tìm kiếm
            const url = new URL(window.location);
            url.searchParams.set('search', searchTerm); // Cập nhật tham số tìm kiếm

            // Chuyển hướng đến URL mới
            window.location = url;
        });
    </script>

    <style>
        #search-input {
            width: 180px;
            /* Điều chỉnh chiều rộng ô input */
            font-size: 14px;
            /* Điều chỉnh kích thước chữ trong ô input */
        }

        #search-button {
            font-size: 14px;
            /* Điều chỉnh kích thước chữ trong nút button */
            padding: 6px 12px;
            /* Điều chỉnh padding để nút nhỏ hơn */
        }
    </style>


@endsection
