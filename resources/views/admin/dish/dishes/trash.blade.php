@extends('admin.master')

@section('title', 'Thùng Rác Món Ăn')

@section('content')
    @include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Thùng Rác Món Ăn</div>
                            <a href="{{ route('admin.dishes.index') }}" class="btn btn-sm btn-primary">
                                Quay lại danh sách món ăn
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>Hình Ảnh</th>
                                            <th>Tên Món Ăn</th>
                                            <th>Loại Món Ăn</th>
                                            <th>Giá</th>
                                            <th>Trạng Thái</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dishes as $dish)
                                            <tr>
                                                <td>{{ $dish->name }}</td>
                                                <td>{{ $dish->category->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($dish->price, 0, ',', '.') }} VND</td>
                                                <td>
                                                    @if ($dish->image)
                                                        <img src="{{ asset('storage/' . $dish->image) }}"
                                                            alt="{{ $dish->name }}" class="img-fluid" width="50">
                                                    @else
                                                        <img src="https://via.placeholder.com/50" alt="No Image"
                                                            class="img-fluid">
                                                    @endif
                                                </td>
                                                <td><span class="badge bg-secondary">Đã Xóa</span></td>
                                                <td>
                                                    <div class="actions">
                                                        <!-- Khôi phục -->
                                                        <form action="{{ route('admin.dishes.restore', $dish->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn khôi phục món ăn này không?');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <a href="#">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <i class="bi bi-arrow-clockwise text-green"></i>
                                                                </button></a>
                                                        </form>

                                                        <!-- Xóa vĩnh viễn -->
                                                        <form action="{{ route('admin.dishes.forceDelete', $dish->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn món ăn này không?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <i class="bi bi-trash text-red"></i>
                                                                </button></a>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có món ăn nào trong thùng rác.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination justify-content-center mt-3">
                                {{ $dishes->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
