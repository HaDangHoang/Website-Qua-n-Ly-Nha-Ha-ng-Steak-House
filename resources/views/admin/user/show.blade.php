@extends('admin.master')

@section('title', $type == 'employee' ? 'Chi Tiết Nhân Viên' : 'Chi Tiết Người Dùng')

@section('content')
    @include('admin.layouts.messages')

    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card shadow-sm">

                        <div class="card-header d-flex justify-content-between align-items-center text-white">
                            <h5 class="card-title mb-3">
                                {{ $type == 'employee' ? 'Chi Tiết Nhân Viên' : 'Chi Tiết Người Dùng' }}
                            </h5>
                            <a href="{{ $type == 'employee' ? route('admin.user.employees') : route('admin.user.index') }}" 
                               class="btn btn-sm btn-secondary">Quay lại</a>
                        </div>

                        <div class="card-body">
                            <!-- Thông tin cơ bản -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">Tên</label>
                                    <p class="form-control">{{ $user->name }}</p>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <p class="form-control">{{ $user->email }}</p>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="phone" class="form-label">Số Điện Thoại</label>
                                    <p class="form-control">{{ $user->phone ?? 'Chưa có số điện thoại' }}</p>
                                </div>
                            </div>

                            <!-- Thông tin cá nhân (Chỉ cho nhân viên) -->
                            @if ($type == 'employee')
                            <div class="mt-4">
                                <h6 class="text-muted">Thông Tin Cá Nhân</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label">Ngày Sinh</label>
                                        <p class="form-control">{{ $user->date_of_birth ?? 'Chưa cập nhật' }}</p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">Giới Tính</label>
                                        <p class="form-control">
                                            @switch($user->gender)
                                                @case('male') Nam @break
                                                @case('female') Nữ @break
                                                @default Khác
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Thông tin công việc (Chỉ cho nhân viên) -->
                            @if ($type == 'employee')
                            <div class="mt-4">
                                <h6 class="text-muted">Thông Tin Công Việc</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="hire_date" class="form-label">Ngày Tuyển Dụng</label>
                                        <p class="form-control">{{ $user->hire_date ?? 'Chưa cập nhật' }}</p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="roles" class="form-label">Vai Trò</label>
                                        <p class="form-control">
                                            {{ $user->roles->pluck('name')->implode(', ') ?? 'Chưa cập nhật' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="text-center mt-3">
                                <a href="{{ route('admin.user.edit', ['user' => $user->id, 'type' => $type]) }}" 
                                   class="btn btn-primary">Sửa Thông Tin</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Row end -->

        </div>
        <!-- Content wrapper end -->

    </div>
    <!-- Content wrapper scroll end -->

@endsection
