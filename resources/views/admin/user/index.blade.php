@extends('admin.master')

@section('content')
    @include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">
                                {{ $type == 'employee' ? 'Danh sách nhân viên' : 'Danh sách người dùng' }}
                            </div>
                            <div>
                                <div>
                                    <a href="{{ route('admin.user.create', ['type' => $type]) }}" class="btn btn-sm btn-primary d-flex align-i">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        {{ $type == 'employee' ? 'Thêm Nhân Viên' : 'Thêm Người Dùng' }}
                                    </a>
                                    
                                </div>
                                
                            </div>
                        </div>
                        

                        <div class="card-body">
                            <!-- Tìm kiếm người dùng -->
                            <form method="GET"
                                action="{{ $type == 'employee' ? route('admin.user.employees') : route('admin.user.index') }}"
                                class="mb-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <input type="text" name="search" class="form-control form-control-sm"
                                            placeholder="Tìm kiếm">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Tìm kiếm
                                        </button>
                                    </div>
                                    @if (!empty($request->search))
                                        <div class="col-auto">
                                            <a href="{{ $type == 'employee' ? route('admin.user.employees') : route('admin.user.index') }}"
                                                class="btn btn-sm btn-secondary">
                                                Xóa bộ lọc
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </form>

                            <!-- Bảng danh sách -->
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Email</th>
                                            <th>Số Điện Thoại</th>
                                            @if ($type == 'employee')
                                                <th>Vai Trò</th>
                                            @endif
                                            <th>Ngày tạo</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($users->isNotEmpty())
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>{{ $user->id }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->phone ?? 'Chưa có số điện thoại' }}</td>
                                                    @if ($type == 'employee')
                                                        <td>
                                                            {{ $user->roles ? $user->roles->pluck('name')->implode(', ') : 'Không có vai trò' }}
                                                        </td>
                                                    @endif
                                                    <td>{{ $user->created_at }}</td>
                                                    
                                                    <td>
                                                        <div class="actions">
                                                            
                                                                <a href="{{ route('admin.user.show', $user->id) }}"  class="viewRow" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Chi tiết">
                                                                <i class="bi bi-list text-green"></i></a>
                                                         
                                                            
                                                            <!-- Nút Sửa -->
                                                            <a href="{{ route('admin.user.edit', ['user' => $user->id, 'type' => $type]) }}"
                                                                class="text-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Sửa">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                    
                                                            <!-- Nút Xóa -->
                                                            <a href="" style="padding-bottom: 5px; padding-left: 3px;">
                                                                <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
                                                                    style="display: inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
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
                                        @else
                                            <tr>
                                                <td colspan="{{ $type == 'employee' ? 6 : 5 }}" class="text-center">
                                                    Không có dữ liệu.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center">

                                {{ $users->links('pagination::client-paginate') }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
