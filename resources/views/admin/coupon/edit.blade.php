@extends('admin.master')

@section('title', 'Chỉnh Sửa Phiếu Giảm Giá')

@section('content')
    @include('admin.layouts.messages')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Lỗi!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif




    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title text-primary">Chỉnh Sửa Phiếu Giảm Giá</div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <!-- Cột Trái -->
                                    <div class="col-md-6">
                                        <!-- Mã Coupon -->
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Mã Giảm Giá </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-info text-white"><i
                                                        class="bi bi-upc-scan"></i></span>
                                                <input type="text" class="form-control" id="code" name="code"
                                                    placeholder="Nhập mã giảm giá" value="{{ old('code', $coupon->code) }}"
                                                    required>
                                            </div>
                                            @error('code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Mô Tả -->
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Mô Tả</label>
                                            <textarea class="form-control" id="description" name="description" placeholder="Nhập mô tả">{{ old('description', $coupon->description) }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Số Lượt Sử Dụng Tối Đa -->
                                        <div class="mb-3">
                                            <label for="max_uses" class="form-label">Số Lượt Sử Dụng Tối Đa </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-warning text-white"><i
                                                        class="bi bi-person-check"></i></span>
                                                <input type="number" class="form-control" id="max_uses" name="max_uses"
                                                    min="1" max="100"
                                                    value="{{ old('max_uses', $coupon->max_uses) }}" required>
                                            </div>
                                            @error('max_uses')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Trạng Thái -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Trạng Thái</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="active"
                                                    {{ old('status', $coupon->status) == 'active' ? 'selected' : '' }}>Hoạt
                                                    Động</option>
                                                <option value="inactive"
                                                    {{ old('status', $coupon->status) == 'inactive' ? 'selected' : '' }}>
                                                    Ngừng Hoạt Động</option>
                                                <option value="expired"
                                                    {{ old('status', $coupon->status) == 'expired' ? 'selected' : '' }}>Hết
                                                    Hạn</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Cột Phải -->
                                    <div class="col-md-6">
                                        <!-- Thời Gian Bắt Đầu -->
                                        <div class="mb-3">
                                            <label for="start_time" class="form-label">Thời Gian Bắt Đầu </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-info"><i
                                                        class="bi bi-calendar-date text-white"></i></span>
                                                <input type="datetime-local" class="form-control" id="start_time"
                                                    name="start_time" value="{{ old('start_time', $coupon->start_time) }}"
                                                    required>
                                            </div>
                                            @error('start_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Thời Gian Kết Thúc -->
                                        <div class="mb-3">
                                            <label for="end_time" class="form-label">Thời Gian Kết Thúc </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-danger"><i
                                                        class="bi bi-calendar-x text-white"></i></span>
                                                <input type="datetime-local" class="form-control" id="end_time"
                                                    name="end_time" value="{{ old('end_time', $coupon->end_time) }}"
                                                    required>
                                            </div>
                                            @error('end_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Loại Giảm Giá -->
                                        <div class="mb-3">
                                            <label for="discount_type" class="form-label">Loại Giảm Giá</label>
                                            <select class="form-control" id="discount_type" name="discount_type"
                                                required>
                                                <option value="Percentage"
                                                    {{ old('discount_type', $coupon->discount_type) == 'Percentage' ? 'selected' : '' }}>
                                                    Phần Trăm</option>
                                                <option value="Fixed"
                                                    {{ old('discount_type', $coupon->discount_type) == 'Fixed' ? 'selected' : '' }}>
                                                    Cố Định</option>
                                            </select>
                                            @error('discount_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Giá Trị Giảm -->
                                        <div class="mb-3" id="discount_amount_div" style="display: none;">
                                            <label for="discount_amount" class="form-label">Số Tiền Giảm Giá </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white">₫</span>
                                                <input type="number" class="form-control" id="discount_amount"
                                                    name="discount_amount" step="0.01"
                                                    placeholder="Nhập số tiền giảm giá"
                                                    value="{{ old('discount_amount', $coupon->discount_amount) }}">
                                            </div>
                                            @error('discount_amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <!-- Giảm Giá (%) -->
                                        <div class="mb-3" id="discount_percentage_div" style="display: none;">
                                            <label for="discount_percentage" class="form-label">Giảm Giá (%) </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success text-white">%</span>
                                                <select class="form-control" id="discount_percentage"
                                                    name="discount_percentage">
                                                    @for ($i = 5; $i <= 100; $i += 5)
                                                        <option value="{{ $i }}"
                                                            {{ old('discount_percentage', $coupon->discount_percentage) == $i ? 'selected' : '' }}>
                                                            {{ $i }}%</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            @error('discount_percentage')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <!-- Nút Lưu -->
                                <div class="text-end">
                                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary">Hủy</a>
                                    <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('discount_type').addEventListener('change', function() {
            const discountType = this.value;
            document.getElementById('discount_amount_div').style.display = discountType === 'Fixed' ? 'block' :
                'none';
            document.getElementById('discount_percentage_div').style.display = discountType === 'Percentage' ?
                'block' : 'none';
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('discount_type').dispatchEvent(new Event('change'));
        });
    </script>
@endsection
