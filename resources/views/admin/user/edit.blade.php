@extends('admin.master')

@section('title', $type === 'employee' ? 'Chỉnh Sửa Nhân Viên' : 'Chỉnh Sửa Người Dùng')

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
                                {{ $type === 'employee' ? 'Chỉnh Sửa Nhân Viên' : 'Chỉnh Sửa Người Dùng' }}
                            </h5>
                            <a href="{{ $type === 'employee' ? route('admin.user.employees') : route('admin.user.index') }}" 
                               class="btn btn-sm btn-secondary">Quay lại</a>
                        </div>
                        <div class="card-body">
                           <form id="editUserForm" method="POST" 
                                  action="{{ route('admin.user.update', ['user' => $user->id, 'type' => $type]) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên</label>
                                    <input value="{{ old('name', $user->name) }}" type="text" id="name"
                                        name="name" class="form-control" required>
                                    <div class="invalid-feedback">Vui lòng nhập tên người dùng.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input value="{{ old('email', $user->email) }}" type="email" id="email"
                                        name="email" class="form-control" required>
                                    <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input value="{{ old('phone', $user->phone) }}" type="text" id="phone"
                                        name="phone" class="form-control">
                                    <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ.</div>
                                </div>

                                @if ($type === 'employee') <!-- Chỉ hiển thị nếu là nhân viên -->
                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Ngày sinh</label>
                                    <input value="{{ old('date_of_birth', $user->date_of_birth) }}" type="date" 
                                           id="date_of_birth" name="date_of_birth" class="form-control">
                                </div>
                            
                                <div class="mb-3">
                                    <label for="hire_date" class="form-label">Ngày tuyển dụng</label>
                                    <input value="{{ old('hire_date', $user->hire_date) }}" type="date" 
                                           id="hire_date" name="hire_date" class="form-control">
                                </div>
                            
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Giới tính</label>
                                    <select id="gender" name="gender" class="form-control">
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                </div>
                            @endif
                            

                               

                                @if ($type === 'employee') <!-- Chỉ hiển thị checkbox vai trò nếu là nhân viên -->
                                <div class="grid grid-cols-4 mb-3">
                                    @if ($roles->isNotEmpty())
                                        @foreach ($roles as $role)
                                            <div class="mt-3">
                                                <input {{ $hasRoles->contains($role->id) ? 'checked' : '' }} 
                                                       type="checkbox" 
                                                       name="role[]" 
                                                       value="{{ $role->name }}" 
                                                       id="role-{{ $role->id }}" 
                                                       class="rounded">
                                                <label for="role-{{ $role->id }}">{{ $role->name }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="invalid-feedback">Vui lòng chọn ít nhất một vai trò.</div>
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                            @endif

                                <div class="mb-3">
                                    <label for="created_at" class="form-label">Ngày tạo</label>
                                    <input value="{{ $user->created_at }}" type="text" id="created_at" 
                                        name="created_at" class="form-control" disabled>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary">Cập Nhật</button>
                                <a href="{{ $type === 'employee' ? route('admin.user.employees') : route('admin.user.index') }}" 
                                   class="btn btn-sm btn-secondary">Hủy</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editUserForm');
            const inputs = form.querySelectorAll('input:not([type="checkbox"]), select, textarea');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    validateInput(input);
                });
                input.addEventListener('change', function() {
                    validateInput(input);
                });
            });

            form.addEventListener('submit', function(event) {
                inputs.forEach(input => {
                    validateInput(input);
                });

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });

            function validateInput(input) {
                if (input.checkValidity()) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }
        });
    </script>
@endsection
