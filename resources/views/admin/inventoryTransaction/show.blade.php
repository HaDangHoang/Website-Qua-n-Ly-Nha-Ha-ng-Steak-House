@extends('admin.master')

@section('title', 'Chi Tiết Phiếu Nhập Kho')

@section('content')
@include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg rounded-4">
                        <div
                            class="card-header d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                            <h4 class="card-title mb-0">Chi Tiết Phiếu Nhập Kho #{{ $transaction->id }}</h4>

                        </div>
                        <div class="card-body">
                            <!-- Thông Tin Phiếu Nhập -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="text-blue font-weight-bold">Thông Tin Phiếu Nhập</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Loại Giao Dịch:</strong>
                                            <span>{{ ucfirst($transaction->transaction_type) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Nhà Cung Cấp:</strong>
                                            <span>{{ $transaction->supplier->name ?? 'Không rõ' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Nhân Viên:</strong> <span>{{ $staff->name ?? 'Không rõ' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Tổng Số Tiền:</strong> <span
                                                class="font-weight-bold text-primary">{{ number_format($transaction->total_amount, 0, ',', '.') }}
                                                VND</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Mô Tả:</strong>
                                            <span>{{ $transaction->description ?? 'Không rõ' }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Trạng Thái:</strong>
                                            <span
                                                class="badge
                                            @if ($transaction->status === 'hoàn thành') bg-success
                                            @elseif ($transaction->status === 'chờ xử lý') bg-warning text-dark
                                            @elseif ($transaction->status === 'Hủy') bg-danger
                                            @else bg-secondary @endif">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Thời Gian -->
                                <div class="col-md-6">
                                    <h5 class="text-blue font-weight-bold">Thời Gian</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <strong>Ngày Tạo:</strong>
                                            <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Danh Sách Nguyên Liệu -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-blue font-weight-bold">Danh Sách Nguyên Liệu</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr class="table-primary">
                                                    <th>ID Nguyên Liệu</th>
                                                    <th>Tên Nguyên Liệu</th>
                                                    <th>Số Lượng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transaction->inventoryItems as $item)
                                                    <tr>
                                                        <td>{{ $item->ingredient->id }}</td>
                                                        <td>{{ $item->ingredient->name }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Cập Nhật Trạng Thái -->
                            @if ($transaction->status !== 'Hủy')
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h5 class="text-blue font-weight-bold">Cập Nhật Trạng Thái</h5>
                                        <form action="{{ route('transactions.update.status', $transaction->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-group">
                                                <label for="status">Trạng Thái</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="chờ xử lý"
                                                        {{ $transaction->status == 'chờ xử lý' ? 'selected' : '' }}>Chờ xử
                                                        lý</option>
                                                    <option value="hoàn thành"
                                                        {{ $transaction->status == 'hoàn thành' ? 'selected' : '' }}>Hoàn
                                                        thành</option>
                                                    <option value="Hủy"
                                                        {{ $transaction->status == 'Hủy' ? 'selected' : '' }}>Hủy</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-success mt-2">Cập Nhật Trạng Thái</button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <div class="text-center">
                                <a href="{{ route('transactions.index') }}" class="btn btn-secondary mt-3 px-5 py-2">Quay lại</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
