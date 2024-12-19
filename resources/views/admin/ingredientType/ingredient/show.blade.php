@extends('admin.master')

@section('title', 'Chi Tiết Nguyên Liệu')

@section('content')
@include('admin.layouts.messages')
    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card shadow-lg">
                        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                            <h4 class="card-title mb-3 text-white">Chi Tiết Nguyên Liệu</h4>
                            <a href="{{ route('admin.ingredient.index') }}" class="btn btn-sm btn-light mb-3">Quay Lại</a>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Thông Tin Nguyên Liệu</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Tên Nguyên Liệu:</strong>
                                            {{ $ingredient->name }}</li>
                                        {{-- <li class="list-group-item"><strong>Nhà Cung Cấp:</strong> {{ $ingredient->supplier_id }}</li>
                                        <li class="list-group-item"><strong>Loại Nguyên Liệu:</strong> {{ $ingredient->ingredient_type_id }}</li> --}}
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary">Chi Tiết Giá Cả</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Giá:</strong>
                                            {{ number_format($ingredient->price, 0, ',', '.') }} VND</li>
                                        <li class="list-group-item"><strong>Đơn Vị:</strong> {{ $ingredient->unit }}</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Thông Tin Thời Gian</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Ngày Tạo:</strong> {{ $ingredient->created_at }}
                                        </li>
                                        <li class="list-group-item"><strong>Ngày Cập Nhật:</strong>
                                            {{ $ingredient->updated_at }}</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <a href="{{ route('admin.ingredient.edit', $ingredient->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil-square me-2"></i> Chỉnh Sửa
                                </a>
                                <a href="{{ route('admin.ingredient.index', $ingredient->id) }}"
                                    class="btn btn-sm btn-light">
                                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content wrapper end -->
    </div>

@endsection
