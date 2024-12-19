@extends('admin.master')

@section('title', 'Thêm Mới Phiếu Giảm Giá')

@section('content')
    @include('admin.layouts.messages')
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title text-primary">Thêm Mới Phiếu Giảm Giá</div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.coupon.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Cột Trái -->
                                    <div class="col-md-6">
                                        <!-- Mã Coupon -->
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Mã Giảm Giá <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-info text-white"><i
                                                        class="bi bi-upc-scan"></i></span>
                                                <input type="text" class="form-control" id="code" name="code"
                                                    placeholder="Nhập mã giảm giá" required>
                                            </div>
                                            @error('code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Mô Tả -->
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Mô Tả</label>
                                            <textarea class="form-control" id="description" name="description" placeholder="Nhập mô tả"></textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Số Lượt Sử Dụng Tối Đa -->
                                        <div class="mb-3">
                                            <label for="max_uses" class="form-label">Số Lượt Sử Dụng Tối Đa <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-warning text-white">
                                                    <i class="bi bi-person-check"></i>
                                                </span>
                                                <input type="number" class="form-control" id="max_uses" name="max_uses"
                                                    min="1" max="100" value="1" required>
                                            </div>
                                            @error('max_uses')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <!-- Trạng Thái -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Trạng Thái</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="active">Hoạt Động</option>
                                                <option value="inactive">Ngừng Hoạt Động</option>
                                                <option value="expired">Hết Hạn</option>
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
                                            <label for="start_time" class="form-label">Thời Gian Bắt Đầu <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-info "><i
                                                        class="bi bi-calendar-date text-white"></i></span>
                                                <input type="datetime-local" class="form-control" id="start_time"
                                                    name="start_time" required>
                                            </div>
                                            @error('start_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Thời Gian Kết Thúc -->
                                        <div class="mb-3">
                                            <label for="end_time" class="form-label">Thời Gian Kết Thúc <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-danger "><i
                                                        class="bi bi-calendar-x text-white"></i></span>
                                                <input type="datetime-local" class="form-control" id="end_time"
                                                    name="end_time" required>
                                            </div>
                                            @error('end_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Loại Giảm Giá -->
                                        <div class="mb-3">
                                            <label for="discount_type" class="form-label">Loại Giảm Giá</label>
                                            <select class="form-control" id="discount_type" name="discount_type" required>
                                                <option value="Percentage">Phần Trăm</option>
                                                <option value="Fixed">Cố Định</option>
                                            </select>
                                            @error('discount_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Giá Trị Giảm -->
                                        <div class="mb-3" id="discount_amount_div" style="display:none;">
                                            <label for="discount_amount" class="form-label">Số Tiền Giảm Giá <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-primary text-white">₫</span>
                                                <input type="number" class="form-control" id="discount_amount"
                                                    name="discount_amount" step="0.01"
                                                    placeholder="Nhập số tiền giảm giá">
                                            </div>
                                            @error('discount_amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Giảm Giá (%) -->
                                        <div class="mb-3" id="discount_percentage_div" style="display:none;">
                                            <label for="discount_percentage" class="form-label">Giảm Giá (%) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-success text-white">%</span>
                                                <select class="form-control" id="discount_percentage"
                                                    name="discount_percentage">
                                                    @for ($i = 5; $i <= 100; $i += 5)
                                                        <option value="{{ $i }}">{{ $i }}%</option>
                                                    @endfor
                                                </select>
                                                @error('discount_percentage')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-2">Thêm Mới</button>
                                        <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary">Quay
                                            Lại</a>
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
            const form = document.getElementById('tableForm');
            const inputs = form.querySelectorAll('input, select');
            const maxUsesInput = document.getElementById('max_uses');
            const errorMessage = "{{ $errors->first('max_uses') }}"; // Lấy thông báo lỗi từ Blade

            // Kiểm tra nếu có lỗi khi trang được tải
            if (errorMessage) {
                maxUsesInput.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.classList.add('text-danger');
                errorDiv.innerText = errorMessage; // Thêm thông báo lỗi vào
                maxUsesInput.parentElement.appendChild(errorDiv); // Thêm vào dưới input
            }

            // Thêm sự kiện kiểm tra cho từng input
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Kiểm tra riêng cho trường "Số Lượt Sử Dụng Tối Đa"
                    if (input.id === 'max_uses') {
                        const value = parseInt(input.value);

                        // Kiểm tra giá trị nhập vào có hợp lệ hay không
                        if (value < 1 || value > 100 || isNaN(value)) {
                            input.setCustomValidity(
                                "Số lượt sử dụng phải nằm trong khoảng từ 1 đến 100.");
                        } else {
                            input.setCustomValidity(""); // Xóa lỗi
                        }
                    }

                    // Kiểm tra tính hợp lệ chung
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                });
            });
        });

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
