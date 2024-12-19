@extends('admin.master')

@section('title', 'Thêm Mới Danh Mục Thực Đơn')

@section('content')
@include('admin.layouts.messages')
    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title text-primary">Thêm Mới Danh Mục Thực Đơn</div>
                        </div>
                        <div class="card-body">

                            <form id="addCategoryForm" method="POST" action="{{ route('admin.category.store') }}"
                                novalidate>
                                @csrf

                                <!-- Row for Tên Danh Mục and Mô Tả -->
                                <div class="row">
                                    <!-- Tên Danh Mục -->
                                    <div class="col-md-6 mb-3">
                                        <label for="category-name" class="form-label">
                                            Tên Danh Mục <span class="text-danger required">*</span>
                                        </label>
                                        <div class="input-group">
                                            {{-- <span class="input-group-text bg-primary text-white">
                                                <i class="bi bi-list"></i> <!-- Icon danh mục từ Bootstrap Icons -->
                                            </span> --}}
                                            <input type="text" id="category-name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Nhập tên danh mục" required value="{{ old('name') }}">
                                        </div>

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Vui lòng nhập tên danh mục.</div>
                                            @endif
                                        </div>

                                        <!-- Mô Tả -->
                                        <div class="col-md-6 mb-3">
                                            <label for="category-description" class="form-label">Mô Tả</label>
                                            <textarea id="category-description" name="description" class="form-control" placeholder="Nhập mô tả danh mục"
                                                rows="4">{{ old('description') }}</textarea>
                                            <div class="invalid-feedback">Vui lòng nhập mô tả danh mục.</div>
                                        </div>
                                    </div>

                                    <!-- Nút Thêm Mới -->
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-sm btn-primary">Thêm Mới</button>
                                        <a href="{{ route('admin.category.index') }}" class="btn btn-sm btn-secondary">Quay
                                            Lại</a>
                                    </div>
                                </form>

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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('addCategoryForm');
            const inputs = form.querySelectorAll('input, textarea');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                });
            });

            form.addEventListener('submit', function(event) {
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                    }
                });

                if (!form.checkValidity()) {
                    event.preventDefault(); // Ngăn biểu mẫu gửi nếu không hợp lệ
                    event.stopPropagation();
                }
            });
        });
    </script>
@endsection
