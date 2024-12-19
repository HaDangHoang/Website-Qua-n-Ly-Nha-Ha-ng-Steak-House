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
                            <a href="{{ route('admin.permission.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table v-middle m-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên Quyền Hạn</th>
                                        <th>Mô Tả</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $permission)
                                        <tr>
                                            <td>{{ $permission->id }}</td>
                                            <td>{{ $permission->permission_name }}</td>
                                            <td>{{ $permission->description }}</td>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('admin.permission.edit', $permission->id) }}">
                                                        <i class="bi bi-pencil-square text-warning"></i>
                                                    </a>
                                                    <a href="#">
                                                        <form
                                                            action="{{ route('admin.permission.destroy', $permission->id) }}"
                                                            method="POST" style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="border-0 bg-transparent"
                                                                onclick="return confirm('Bạn có muốn xóa quyền hạn này không?')">
                                                                <svg class="delete-svgIcon" viewBox="0 0 448 512">
                                                                    <path
                                                                        d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- <div class="mt-3">
                                {{ $permissions->links() }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
