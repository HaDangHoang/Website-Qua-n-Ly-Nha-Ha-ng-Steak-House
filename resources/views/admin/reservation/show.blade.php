@extends('admin.master')

@section('title', 'Chi Tiết Đặt Bàn')

@section('content')
    @include('admin.layouts.messages')
    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">

        <!-- Content wrapper start -->
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card shadow-lg">
                        <div class="card-header d-flex justify-content-between align-items-center  text-white">
                            <h4 class="card-title mb-3 text-secondary">Chi Tiết Đặt Bàn</h4>
                            <a href="{{ route('admin.reservation.index') }}" class="btn btn-sm btn-light  mb-3">Quay Lại</a>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Thông Tin Khách Hàng</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Tên Khách Hàng:</strong>
                                            {{ $reservation->customer->name }}</li>
                                        <li class="list-group-item"><strong>Số Điện Thoại:</strong>
                                            {{ $reservation->customer->phone }}</li>
                                        <li class="list-group-item"><strong>Email:</strong>
                                            {{ $reservation->customer->email }}</li>
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary">Chi Tiết Đặt Bàn</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Thời Gian Đặt Bàn:</strong>
                                            {{ $reservation->reservation_time }}</li>
                                        <li class="list-group-item"><strong>Số Khách:</strong>
                                            {{ $reservation->guest_count }}</li>
                                        <li class="list-group-item"><strong>Trạng Thái:</strong>
                                            @if ($reservation->status === 'Confirmed')
                                                <span class="badge shade-green min-70">Đã xác nhận</span>
                                            @elseif ($reservation->status === 'Pending')
                                                <span class="badge shade-yellow min-70">Chờ xử lý</span>
                                            @elseif ($reservation->status === 'Cancelled')
                                                <span class="badge shade-red min-70">Đã hủy</span>
                                            @elseif ($reservation->status === 'Refund')
                                                <span class="badge bg-info">Đã hoàn cọc</span>
                                            @elseif($reservation->status === 'Completed')
                                                <span class="badge shade-primary min-70">Hoàn thành</span>
                                            @else
                                                <span class="badge shade-gray min-70">Không rõ</span>
                                            @endif
                                        </li>

                                    </ul>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Chi Tiết Thanh Toán</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Số Tiền Đặt Cọc:</strong>
                                            {{ number_format($reservation->deposit_amount, 0, ',', '.') }} VND</li>
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary">Ghi Chú và Lý Do Hủy (Nếu Có)</h5>
                                    <ul class="list-group list-group-flush">
                                        @if ($reservation->status == 'Cancelled')
                                            <li class="list-group-item"><strong>Lý Do Hủy:</strong>
                                                {{ $reservation->cancelled_reason }}</li>
                                        @endif
                                        <li class="list-group-item"><strong>Ghi Chú:</strong> {{ $reservation->note }}</li>
                                    </ul>
                                </div>
                            </div>

                            <a href="{{ route('admin.reservation.index') }}" class="btn btn-primary mt-3">Quay Lại Danh
                                Sách</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content wrapper end -->
    </div>

@endsection
