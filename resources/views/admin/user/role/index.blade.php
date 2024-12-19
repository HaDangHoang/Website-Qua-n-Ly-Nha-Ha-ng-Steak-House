@extends('admin.master')

@section('title', 'Danh Sách Vai Trò')

@section('content')
    @include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh sách vai trò</div>

                            <!-- Nút Thêm Mới và Khôi Phục -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.role.create') }}"
                                    class="btn btn-sm btn-primary d-flex align-items-center">
                                    <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                                </a>
                                <a href="{{ route('admin.role.trash') }}"
                                    class="btn btn-sm btn-secondary d-flex align-items-center">
                                    <i class="bi bi-trash3 me-2"></i> Khôi Phục
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table v-middle m-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên vai trò</th>
                                        <th>Quyền hạn</th>
                                        <th>Ngày tạo</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($roles->isNotEmpty())
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td>{{ $role->id }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>{{ $role->permissions->pluck('name')->implode(' , ') }}</td>
                                                <td>

                                                    {{ \Carbon\Carbon::parse($role->change_date . ' ' . $role->change_time)->format('H:i:s') }}<br>
                                                    {{ \Carbon\Carbon::parse($role->change_date)->format('d/m/Y') }}
                                                </td>
                                                <td>


                                                    <div class="actions">
                                                        @can('Sửa vai trò')
                                                            <a href="{{ route('admin.role.edit', $role->id) }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Sửa">
                                                                <i class="bi bi-pencil-square text-warning"></i>
                                                            </a>
                                                        @endcan

                                                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Xoá">
                                                            <form action="{{ route('admin.role.destroy', $role->id) }}"
                                                                method="POST" style="display: inline-block;">
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
                                        @endforeach
                                    @endif




                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center">

                                {{ $roles->links('pagination::client-paginate') }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <x-slot name="script">
        <script type="text/javascript">
            function deleteRole(id) {
                if (confirm("Are you sure you want to delete?")) {
                    $.ajax({
                        url: '{{ route('admin.role.destroy', ':id') }}'.replace(':id', id), // Thay thế :id
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'x-csrf-token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status) {
                                window.location.href = "{{ route('admin.role.index') }}"; // Chuyển hướng
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Xóa không thành công!');
                        }
                    });
                }
            }
        </script>
    </x-slot>


@endsection
