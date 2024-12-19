@extends('admin.master')

@section('title', 'Thùng Rác Loại Danh Mục')

@section('content')
    @include('admin.layouts.messages')

    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Thùng Rác Danh Mục</div>
                            <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-primary">
                                Quay lại danh sách danh mục
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>Tên Danh Mục</th>
                                            <th>Mô Tả</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories as $category)
                                            <tr>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $category->description }}</td>
                                                <td>
                                                    <div class="actions ">
                                                        <!-- Restore -->
                                                        <a href="">
                                                            <form
                                                                action="{{ route('admin.category.restore', $category->id) }}"
                                                                method="POST" style="display:inline-block;"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn khôi phục danh mục này không?');">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-link p-0 ">
                                                                    <i class="bi bi-arrow-clockwise text-green"></i>
                                                                </button>
                                                            </form>
                                                        </a>
                                                        <!-- Permanently delete -->
                                                        <a href="">
                                                            <form
                                                                action="{{ route('admin.category.forceDelete', $category->id) }}"
                                                                method="POST" style="display:inline-block;"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn danh mục này?');">
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
                                                <td colspan="3" class="text-center">Không có danh mục nào trong thùng
                                                    rác.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination justify-content-center mt-3">
                                {{ $categories->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
