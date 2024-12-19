@extends('admin.master')

@section('title', 'Chi Tiết Thanh Toán')

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
                            <h4 class="card-title mb-3 text-secondary text-primary">Chi Tiết Thanh Toán</h4>
                            <a href="{{ route('admin.payment.index') }}" class="btn btn-sm btn-light mb-3">Quay Lại</a>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Thông Tin Thanh Toán</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Mã Thanh Toán:</strong> {{ $payment->id }}</li>
                                        <li class="list-group-item"><strong>Mã Hóa Đơn:</strong> {{ $payment->bill_id }}
                                        </li>
                                        <li class="list-group-item"><strong>Số Tiền:</strong>
                                            {{ number_format($payment->transaction_amount, 0, ',', '.') }} VND</li>
                                        <li class="list-group-item"><strong>Số Tiền Hoàn Lại:</strong>
                                            {{ number_format($payment->refund_amount, 0, ',', '.') }} VND</li>
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary">Chi Tiết Đặt Chỗ</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Mã Đặt Chỗ:</strong>
                                            {{ $payment->reservation_id }}</li>
                                        <li class="list-group-item"><strong>Phương Thức Thanh Toán:</strong>
                                            {{ $payment->payment_method }}</li>
                                        <li class="list-group-item"><strong>Trạng Thái Giao Dịch:</strong>
                                            {{ $payment->transaction_status }}</li>
                                        <li class="list-group-item"><strong>Trạng Thái:</strong> {{ $payment->status }}</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-primary">Chi Tiết Thời Gian</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Ngày Tạo:</strong>
                                            {{ $payment->created_at->format('Y-m-d H:i') }}</li>
                                        <li class="list-group-item"><strong>Ngày Cập Nhật:</strong>
                                            {{ $payment->updated_at->format('Y-m-d H:i') }}</li>
                                    </ul>
                                </div>
                            </div>

                            <a href="{{ route('admin.payment.index') }}" class="btn btn-primary mt-3">Quay Lại Danh
                                Sách</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content wrapper end -->
    </div>

@endsection
