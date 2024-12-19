@extends('admin.master')

@section('title', 'Thêm Nhà Cung Cấp Mới')

@section('content')
@include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title text-primary">Thêm Nhà Cung Cấp Mới</div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.supplier.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên Nhà Cung Cấp <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white"><i
                                                        class="bi bi-shop text-white"></i></span>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    placeholder="Nhập tên nhà cung cấp" required>
                                            </div>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Số Điện Thoại <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success text-white"><i
                                                        class="bi bi-telephone text-white"></i></span>
                                                <input type="number" name="phone" id="phone" class="form-control"
                                                    placeholder="Nhập số điện thoại" required maxlength="10">
                                            </div>
                                            <div class="invalid-feedback">Số điện thoại phải có 10 số và bắt đầu bằng số 0.
                                            </div>
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-info text-white"><i
                                                        class="bi bi-envelope text-white"></i></span>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    placeholder="Nhập địa chỉ email">
                                            </div>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Địa Chỉ <span
                                                    class="text-danger required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-danger"><i
                                                        class="bi bi-geo-alt text-white"></i></span>
                                                <input type="text" name="address" id="address" class="form-control"
                                                    placeholder="Nhập địa chỉ nhà cung cấp">
                                            </div>
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                                    <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary">Quay lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phone');

            phoneInput.addEventListener('input', function() {
                let value = phoneInput.value;

                // Kiểm tra số đầu tiên phải là 0
                if (!value.startsWith('0') && value.length > 0) {
                    value = '0' + value.replace(/^0+/, ''); // Đảm bảo luôn có số 0 đầu tiên
                }

                // Giới hạn độ dài tối đa 10 ký tự
                if (value.length > 10) {
                    value = value.slice(0, 10);
                }

                phoneInput.value = value;

                // Kiểm tra hợp lệ: bắt buộc đủ 10 số và bắt đầu bằng số 0
                if (value.length === 10 && value.startsWith('0')) {
                    phoneInput.classList.remove('is-invalid');
                    phoneInput.classList.add('is-valid');
                } else {
                    phoneInput.classList.remove('is-valid');
                    phoneInput.classList.add('is-invalid');
                }
            });

            // Kiểm tra khi submit form
            phoneInput.closest('form').addEventListener('submit', function(event) {
                const value = phoneInput.value;

                if (value.length !== 10 || !value.startsWith('0')) {
                    phoneInput.classList.add('is-invalid');
                    event.preventDefault(); // Ngăn không cho form submit
                } else {
                    phoneInput.classList.remove('is-invalid');
                }
            });
        });
    </script>
@endsection
