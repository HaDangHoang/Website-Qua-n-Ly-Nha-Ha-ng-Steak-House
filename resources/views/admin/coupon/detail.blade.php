@extends('admin.master')

@section('title', 'Chi Tiết Coupon')

@section('content')
    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .swal2-timer-progress-bar {
            background: linear-gradient(90deg, #34eb4f, #00bcd4, #ffa726, #ffeb3b, #f44336);
            /* Gradient màu */
            background-size: 300% 300%;
            /* Kích thước gradient lớn để tạo hiệu ứng động */
            animation: gradientMove 2s ease infinite;
            /* Hiệu ứng lăn tăn */
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hiển thị thông báo lỗi
            @if ($errors->any())
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    toast: true,
                    title: "{{ $errors->first() }}",
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 3000
                });
            @endif

            // Hiển thị thông báo thành công
            @if (session('success'))
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    toast: true,
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 3000
                });
            @endif
        });
    </script>
    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card shadow-lg">
                        <div class="card-header d-flex justify-content-between align-items-center text-white">
                            <h4 class="card-title mb-3 text-secondary">Chi Tiết Phiếu Giảm Giá</h4>
                            <a href="{{ route('admin.coupon.index') }}" class="btn btn-sm btn-light  mb-3">Quay Lại</a>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Thông Tin Phiếu Giảm Giá</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Mã Giảm Giá:</strong> {{ $coupon->code }}</li>
                                        <li class="list-group-item"><strong>Mô Tả:</strong> {{ $coupon->description }}</li>
                                        <li class="list-group-item"><strong>Số Lần Sử Dụng Tối Đa:</strong>
                                            {{ $coupon->max_uses }}</li>
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary">Chi Tiết Thời Gian</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Thời Gian Bắt Đầu:</strong>
                                            {{ $coupon->start_time }}</li>
                                        <li class="list-group-item"><strong>Thời Gian Kết Thúc:</strong>
                                            {{ $coupon->end_time }}</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Chi Tiết Giảm Giá</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Loại Giảm Giá:</strong>
                                            {{ $coupon->discount_type === 'Fixed' ? 'Giảm Giá Cố Định' : 'Giảm Giá Phần Trăm' }}
                                        </li>
                                        @if ($coupon->discount_type === 'Fixed')
                                            <li class="list-group-item"><strong>Số Tiền Giảm Giá:</strong>
                                                {{ number_format($coupon->discount_amount, 0, ',', '.') }} VND
                                            </li>
                                        @elseif ($coupon->discount_type === 'Percentage')
                                            <li class="list-group-item"><strong>Tỷ Lệ Giảm Giá:</strong>
                                                {{ $coupon->discount_amount }}%
                                            </li>
                                        @endif
                                    </ul>
                                </div>


                                <div class="col-md-6">
                                    <h5 class="text-primary">Trạng Thái</h5>

                                    @if ($coupon->status == 'active')
                                        <span class="badge shade-green">Hoạt Động</span>
                                    @elseif ($coupon->status == 'inactive')
                                        <span class="badge shade-yellow">Ngừng Hoạt Động</span>
                                    @else
                                        <span class="badge shade-red">Hết Hạn</span>
                                    @endif

                                </div>
                            </div>

                            <a href="{{ route('admin.coupon.index') }}" class="btn btn-primary mt-3">Quay Lại Danh Sách</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content wrapper end -->
    </div>

@endsection
