@extends('admin.master')

@section('title', 'Chỉnh Sửa Vai Trò')

@section('content')

    @include('admin.layouts.messages')
    <style>
        .permission-group {
            margin-bottom: 30px;
        }

        .permission-group-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
            text-decoration: underline;
        }

        .permissions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            /* Đặt cột tự động với chiều rộng tối thiểu */
            gap: 15px;
            /* Khoảng cách giữa các checkbox */
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
        }

        .custom-checkbox input {
            margin-right: 10px;
            transform: scale(1.2);
            /* Tăng kích thước checkbox */
        }
    </style>

    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title text-primary">Chỉnh Sửa Vai Trò</div>
                        </div>
                        <div class="card-body">
                            <form id="editRoleForm" method="POST" action="{{ route('admin.role.update', $role->id) }}"
                                novalidate>
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="name" class="form-label">Tên Vai Trò</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white"><i
                                                class="bi bi-person text-white"></i></span>
                                        <input value="{{ old('name', $role->name) }}" type="text" id="name"
                                            name="name" class="form-control" required>
                                    </div>
                                    <div class="invalid-feedback">Vui lòng nhập tên vai trò.</div>
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>


                                <!-- Hiển thị nhóm quyền -->
                                <div class="permission-group">
                                    <div class="permission-group-title text-primary">Xem</div>
                                    <div class="permissions">
                                        @foreach ($permissions as $permission)
                                            @if (Str::startsWith($permission->name, 'Xem'))
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" name="permission[]"
                                                        value="{{ $permission->name }}"
                                                        id="permission-{{ $permission->id }}"
                                                        {{ $hasPermissions->contains($permission->name) ? 'checked' : '' }}>
                                                    <label
                                                        for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="permission-group">
                                    <div class="permission-group-title text-primary">Tạo mới</div>
                                    <div class="permissions">
                                        @foreach ($permissions as $permission)
                                            @if (Str::startsWith($permission->name, 'Tạo mới'))
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" name="permission[]"
                                                        value="{{ $permission->name }}"
                                                        id="permission-{{ $permission->id }}"
                                                        {{ $hasPermissions->contains($permission->name) ? 'checked' : '' }}>
                                                    <label
                                                        for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="permission-group">
                                    <div class="permission-group-title text-primary">Sửa</div>
                                    <div class="permissions">
                                        @foreach ($permissions as $permission)
                                            @if (Str::startsWith($permission->name, 'Sửa'))
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" name="permission[]"
                                                        value="{{ $permission->name }}"
                                                        id="permission-{{ $permission->id }}"
                                                        {{ $hasPermissions->contains($permission->name) ? 'checked' : '' }}>
                                                    <label
                                                        for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="permission-group">
                                    <div class="permission-group-title text-primary">Xóa</div>
                                    <div class="permissions">
                                        @foreach ($permissions as $permission)
                                            @if (Str::startsWith($permission->name, 'Xóa'))
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" name="permission[]"
                                                        value="{{ $permission->name }}"
                                                        id="permission-{{ $permission->id }}"
                                                        {{ $hasPermissions->contains($permission->name) ? 'checked' : '' }}>
                                                    <label
                                                        for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="permission-group">
                                    <div class="permission-group-title text-primary">Pos</div>
                                    <div class="permissions">
                                        @foreach ($permissions as $permission)
                                            @if (Str::startsWith($permission->name, 'access'))
                                                <div class="custom-checkbox">
                                                    <input type="checkbox" name="permission[]"
                                                        value="{{ $permission->name }}"
                                                        id="permission-{{ $permission->id }}"
                                                        {{ $hasPermissions->contains($permission->name) ? 'checked' : '' }}>
                                                    <label
                                                        for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary">Cập Nhật</button>
                                <a href="{{ route('admin.role.index') }}" class="btn btn-sm btn-secondary">Hủy</a>
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
            const form = document.getElementById('editRoleForm');
            const inputs = form.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                input.addEventListener('input', () => validateInput(input));
                input.addEventListener('change', () => validateInput(input));
            });

            form.addEventListener('submit', function(event) {
                inputs.forEach(input => validateInput(input));
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
