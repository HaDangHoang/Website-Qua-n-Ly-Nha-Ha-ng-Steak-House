@extends('admin.master')

@section('title', 'Thùng Rác')

@section('content')

    @include('admin.layouts.messages')

    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Thùng Rác</div>
                            <a href="{{ route('admin.table.index') }}" class="btn btn-sm btn-primary">
                                Quay lại danh sách bàn
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>Khu Vực</th>
                                            <th>Số Bàn</th>
                                            <th>Loại Bàn</th>
                                            <th>Trạng Thái</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tables as $table)
                                            <tr>
                                                <td>{{ $table->area }}</td>
                                                <td>{{ $table->table_number }}</td>
                                                <td>{{ $table->table_type }}</td>
                                                <td><span class="badge shade-red">Đã Xóa</span></td>
                                                <td>
                                                    <div class="actions">
                                                        <!-- Restore -->
                                                        <form action="{{ route('admin.table.restore', $table->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn khôi phục không?');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-link p-0"
                                                                title="Khôi phục">
                                                                <i class="bi bi-arrow-clockwise text-green"></i>
                                                            </button>
                                                        </form>
                                                        <!-- Permanently delete -->
                                                        <form action="{{ route('admin.table.forceDelete', $table->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link p-0"
                                                                title="Xóa vĩnh viễn">
                                                                <i class="bi bi-trash text-red"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Không có bàn nào trong thùng rác.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="pagination justify-content-center mt-3">
                                {{ $tables->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
