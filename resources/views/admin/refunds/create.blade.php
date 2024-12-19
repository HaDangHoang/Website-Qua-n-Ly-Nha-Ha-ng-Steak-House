@extends('admin.master')

@section('title', 'Kiểm Tra Đơn Đặt Bàn')

@section('content')
    @include('admin.layouts.messages')
    <h3>Hoàn tiền cho đặt chỗ #{{ $reservation->id }}</h3>
    <p>Tên khách hàng: {{ $reservation->user_name }}</p>
    <p>Số tiền đặt cọc: {{ $reservation->deposit_amount }}</p>

    <form action="{{ route('refunds.store') }}" method="POST">
        @csrf
        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

        <div class="mb-3">
            <label for="account_name" class="form-label">Tên tài khoản</label>
            <input type="text" class="form-control @error('account_name') is-invalid @enderror" id="account_name"
                name="account_name" required>
            @error('account_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="account_number" class="form-label">Số tài khoản</label>
            <input type="number" class="form-control @error('account_number') is-invalid @enderror" id="account_number"
                name="account_number" required>
            @error('account_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="refund_amount" class="form-label">Số tiền hoàn</label>
            <input type="number" class="form-control @error('refund_amount') is-invalid @enderror" id="refund_amount"
                name="refund_amount" required value="{{ $reservation->deposit_amount }}">
            @error('refund_amount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="user_phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control @error('user_phone') is-invalid @enderror" id="user_phone"
                name="user_phone" required>
            @error('user_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="bank_name" class="form-label">Ngân hàng</label>
            <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name"
                name="bank_name" required>
            @error('bank_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Lý do hoàn tiền</label>
            <textarea class="form-control" id="reason" name="reason"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Xác nhận hoàn tiền</button>
    </form>

@endsection
