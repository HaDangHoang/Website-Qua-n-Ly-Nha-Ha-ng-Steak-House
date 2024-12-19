@extends('admin.master')

@section('title', 'Thùng Rác Combo')

@section('content')
    @include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Thùng Rác Combo</div>
                            <a href="{{ route('admin.combo.index') }}" class="btn btn-sm btn-primary">
                                Quay lại danh sách combo
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>Tên Combo</th>
                                            <th>Giá</th>
                                            <th>Mô Tả</th>
                                            <th>Hình Ảnh</th>
                                            <th>Số Lượng Món Ăn</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($combos as $combo)
                                            <tr>
                                                <td>{{ $combo->name }}</td>
                                                <td>{{ number_format($combo->price, 0, ',', '.') }} VND</td>
                                                <td>{!! $combo->description !!}</>
                                                </td>
                                                <td class="text-center">
                                                    @if ($combo->image)
                                                        <img src="{{ asset('storage/' . $combo->image) }}"
                                                            alt="{{ $combo->name }}" width="50">
                                                    @else
                                                        <img src="https://via.placeholder.com/50" alt="No Image"
                                                            width="50">
                                                    @endif
                                                </td>
                                                <td>{{ $combo->quantity_dishes }}</td>
                                                <td>
                                                    <div class="actions">
                                                        <!-- Khôi phục -->
                                                        <form action="{{ route('admin.combo.restore', $combo->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn khôi phục combo này không?');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <a href="#">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <i class="bi bi-arrow-clockwise text-green"></i>
                                                                </button></a>
                                                        </form>

                                                        <!-- Xóa vĩnh viễn -->
                                                        <form action="{{ route('admin.combo.forceDelete', $combo->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn combo này không?');">
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
                                                <td colspan="6" class="text-center">Không có combo nào trong thùng rác.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination justify-content-center mt-3">
                                {{ $combos->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
