@extends('admin.master')

@section('title', 'Danh Sách Quyền Hạn')

@section('content')
    @include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh Sách Quyền Hạn</div>   
                            {{-- <form action="{{ route('admin.permissions.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control form-control-sm"
                                    placeholder="Tìm kiếm quyền hạn..." value="{{ request()->get('search') }}">
                                <button type="submit" class="btn btn-sm btn-secondary ms-2">
                                    <i class=" mb-2"></i> Tìm kiếm
                                </button>
                            </form> --}}
                            <a href="{{ route('admin.permissions.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="filter-form">
                                <form method="GET" action="{{ route('admin.permissions.index') }}" class="mb-3">
                                    <div class="row g-2">
                                        <div class="col-auto">
                                            <input type="text" id="search" name="search"
                                                class="form-control form-control-sm" placeholder="Tìm kiếm tên quyền hạn"
                                                value="{{ request('search') }}">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                        </div>
                                    </div>
                                </form>

                                        <table class="table v-middle m-0">
                                            <thead>
                                                <tr>
                                                    <th> <a
                                                        href="{{ route('admin.permissions.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                        Mã Quyền Hạn
                                                        <i
                                                            class="bi bi-arrow-{{ request('sort') === 'id' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                                    </a></th>
                                                    <th> <a
                                                        href="{{ route('admin.permissions.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                        Tên Quyền Hạn
                                                        <i
                                                            class="bi bi-arrow-{{ request('sort') === 'name' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                                    </a></th>
                                                    <th>Ngày tạo</th>
                                                    <th>Hành Động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($permissions->isNotEmpty())
                                                    @foreach ($permissions as $permission)
                                                        <tr>
                                                            <td>{{ $permission->id }}</td>
                                                            <td>{{ $permission->name }}</td>
                                                            <td>
                                                                <span class="text-success">
                                                                    {{ \Carbon\Carbon::parse($permission->change_date . ' ' . $permission->change_time)->format('H:i:s') }}</span><br>
                                                                    {{ \Carbon\Carbon::parse($permission->change_date)->format('d/m/Y') }}
                                                            </td>
                                                            <td>
                                                                <div class="actions">
                                                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}">
                                                                        <i class="bi bi-pencil-square text-warning"></i>
                                                                    </a>
                                                                    <a href="" style="padding-bottom: 5px; padding-left: 3px;">
                                                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" style="display: inline-block; padding-bottom: 3px">
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
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            
                                        </table>


                                    </div>
                                    <div class="d-flex justify-content-center">

                                        {{ $permissions->links('pagination::client-paginate') }}

                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-slot name="script">
                <script type="text/javascript">
                    function deletePermission(id) {
                        event.preventDefault(); // Ngăn chặn hành động gửi form
                        if (confirm("Are you sure you want to delete?")) {
                            $.ajax({
                                url: '{{ route('admin.permissions.destroy', ':id') }}'.replace(':id', id), // Cập nhật ở đây
                                type: 'DELETE',
                                dataType: 'json',
                                headers: {
                                    'x-csrf-token': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.status) {
                                        window.location.href =
                                            "{{ route('admin.permissions.index') }}"; // Cập nhật đường dẫn cho đúng
                                    } else {
                                        alert("Error deleting permission: " + response.message);
                                    }
                                },
                                error: function(xhr) {
                                    alert("Error occurred: " + xhr.statusText);
                                }
                            });
                        }
                    }
                </script>

            </x-slot>
        @endsection
