@extends('admin.master')

@section('title', 'Danh Sách Món Ăn')

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
                            <div class="card-title">Danh sách món ăn</div>

                            <!-- Nút Thêm Mới và Khôi Phục -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.dishes.create') }}"
                                    class="btn btn-sm btn-primary d-flex align-items-center">
                                    <i class="bi bi-plus-circle me-2"></i> Thêm Mới
                                </a>
                                <a href="{{ route('admin.dishes.trash') }}"
                                    class="btn btn-sm btn-secondary d-flex align-items-center">
                                    <i class="bi bi-trash3 me-2"></i> Khôi Phục
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.dishes.index') }}" class="mb-3">
                                <div class="row g-2">
                                    <div class="col-auto">
                                        <input type="text" id="search-dish" name="dish_name"
                                            class="form-control form-control-sm" placeholder="Tìm kiếm theo tên món ăn"
                                            value="{{ request('dish_name') }}">
                                    </div>
                                    <div class="col-auto">
                                        <select name="category_id" class="form-control form-control-sm">
                                            <option value="">Tất Cả</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="">Chọn Trạng Thái</option>
                                            <option value="available"
                                                {{ request('status') == 'available' ? 'selected' : '' }}>Có sẵn</option>
                                            <option value="out_of_stock"
                                                {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng
                                            </option>
                                            <option value="reserved"
                                                {{ request('status') == 'reserved' ? 'selected' : '' }}>Đã đặt trước
                                            </option>
                                            <option value="inactive"
                                                {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                        <a href="{{ route('admin.dishes.index') }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>



                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <a
                                                    href="{{ route('admin.dishes.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') === 'asc' && request('sort') === 'name' ? 'desc' : 'asc'])) }}">
                                                    Tên Món Ăn
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'name' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}"></i>
                                                </a>
                                            </th>

                                            <th>Loại Món Ăn</th>
                                            <th>
                                                <a
                                                    href="{{ route('admin.dishes.index', array_merge(request()->query(), ['sort' => 'price', 'direction' => request('direction') === 'asc' && request('sort') === 'price' ? 'desc' : 'asc'])) }}">
                                                    Giá Món Ăn
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'price' ? (request('direction') === 'asc' ? 'up' : 'down') : 'up-down' }}">
                                                    </i>
                                                </a>
                                            </th>


                                            <th>Hình Ảnh</th>
                                            <th>Trạng Thái</th>
                                            <th>Tính Năng</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dishes as $dish)
                                            <tr>
                                                <td>{{ $dish->name }}</td>
                                                <td>{{ $dish->category->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($dish->price, 0, ',', '.') }} VND</td>
                                                <td class="text-center">
                                                    @if ($dish->image)
                                                        <img src="{{ asset('storage/' . $dish->image) }}"
                                                            alt="{{ $dish->name }}" width="50">
                                                    @else
                                                        <img src="https://via.placeholder.com/50" alt="No Image"
                                                            width="50">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($dish->status == 'available')
                                                        <span class="badge bg-success">Có sẵn</span>
                                                    @elseif($dish->status == 'out_of_stock')
                                                        <span class="badge bg-danger">Hết hàng</span>
                                                    @elseif($dish->status == 'reserved')
                                                        <span class="badge bg-warning">Đã đặt trước</span>
                                                    @elseif($dish->status == 'in_use')
                                                        <span class="badge bg-info">Đang sử dụng</span>
                                                    @elseif($dish->status == 'completed')
                                                        <span class="badge bg-primary">Hoàn thành</span>
                                                    @elseif($dish->status == 'cancelled')
                                                        <span class="badge bg-secondary">Đã hủy</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status"
                                                            data-id="{{ $dish->id }}"
                                                            {{ $dish->is_active ? 'checked' : '' }}>
                                                        <div class="slider">
                                                            <div class="circle">
                                                                <svg class="cross" xml:space="preserve"
                                                                    viewBox="0 0 365.696 365.696" y="0" x="0" height="6"
                                                                    width="6"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                                    <g>
                                                                        <path data-original="#000000" fill="currentColor"
                                                                            d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0">
                                                                        </path>
                                                                    </g>
                                                                </svg>
                                                                <svg class="checkmark" xml:space="preserve"
                                                                    viewBox="0 0 24 24" y="0" x="0" height="10"
                                                                    width="10"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                                    <g>
                                                                        <path class="" data-original="#000000"
                                                                            fill="currentColor"
                                                                            d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z">
                                                                        </path>
                                                                    </g>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="actions">
                                                        <a href="{{ route('admin.dishes.show', $dish->id) }}"
                                                            class="viewRow" data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="Chi tiết">
                                                            <i class="bi bi-list text-green"></i>
                                                        </a>

                                                        <a href="{{ route('admin.dishes.edit', $dish->id) }}"
                                                            class="" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Sửa">
                                                            <i class="bi bi-pencil-square text-warning"></i>
                                                        </a>
                                                        <a href="#" class="deleteRow" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Xoá">
                                                            <form action="{{ route('admin.dishes.destroy', $dish->id) }}"
                                                                method="POST" style="display:inline-block;"
                                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link p-0"
                                                                    style="margin-top: 15px;">
                                                                    <i class="bi bi-trash text-danger"
                                                                        style="font-size: 1.2rem;"></i>
                                                                </button>
                                                            </form>
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">Không có món ăn nào được tìm thấy.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>



                        </div>
                        <div class="d-flex justify-content-center">

                            {{ $dishes->links('pagination::client-paginate') }}

                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->

        </div>
    </div>

    <script>
        $(document).on('change', '.toggle-status', function() {
            const dishId = $(this).data('id'); // Lấy ID dish
            const isActive = $(this).is(':checked') ? 1 : 0; // Trạng thái mới

            $.ajax({
                url: '/admin/dishes/' + dishId +
                    '/toggle-status', // URL động trùng với route khai báo trong controller
                type: 'POST',
                data: {
                    is_active: isActive,
                    _token: '{{ csrf_token() }}' // CSRF token để bảo mật
                },
                success: function(response) {
                    if (response.success) {
                        alert('Cập nhật trạng thái thành công!'); // Thông báo thành công
                    } else {
                        alert('Cập nhật trạng thái thất bại!'); // Thông báo lỗi
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseJSON);
                    alert(xhr.responseJSON?.message ||
                        'Có lỗi xảy ra! Vui lòng thử lại.'); // Thông báo lỗi từ server
                }
            });
        });
    </script>

@endsection
<style>
    /* From Uiverse.io by Galahhad */
    .switch {
        /* switch */
        --switch-width: 46px;
        --switch-height: 24px;
        --switch-bg: rgb(131, 131, 131);
        --switch-checked-bg: rgb(0, 218, 80);
        --switch-offset: calc((var(--switch-height) - var(--circle-diameter)) / 2);
        --switch-transition: all .2s cubic-bezier(0.27, 0.2, 0.25, 1.51);
        /* circle */
        --circle-diameter: 18px;
        --circle-bg: #fff;
        --circle-shadow: 1px 1px 2px rgba(146, 146, 146, 0.45);
        --circle-checked-shadow: -1px 1px 2px rgba(163, 163, 163, 0.45);
        --circle-transition: var(--switch-transition);
        /* icon */
        --icon-transition: all .2s cubic-bezier(0.27, 0.2, 0.25, 1.51);
        --icon-cross-color: var(--switch-bg);
        --icon-cross-size: 6px;
        --icon-checkmark-color: var(--switch-checked-bg);
        --icon-checkmark-size: 10px;
        /* effect line */
        --effect-width: calc(var(--circle-diameter) / 2);
        --effect-height: calc(var(--effect-width) / 2 - 1px);
        --effect-bg: var(--circle-bg);
        --effect-border-radius: 1px;
        --effect-transition: all .2s ease-in-out;
    }

    .switch input {
        display: none;
    }

    .switch {
        display: inline-block;
    }

    .switch svg {
        -webkit-transition: var(--icon-transition);
        -o-transition: var(--icon-transition);
        transition: var(--icon-transition);
        position: absolute;
        height: auto;
    }

    .switch .checkmark {
        width: var(--icon-checkmark-size);
        color: var(--icon-checkmark-color);
        -webkit-transform: scale(0);
        -ms-transform: scale(0);
        transform: scale(0);
    }

    .switch .cross {
        width: var(--icon-cross-size);
        color: var(--icon-cross-color);
    }

    .slider {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        width: var(--switch-width);
        height: var(--switch-height);
        background: var(--switch-bg);
        border-radius: 999px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        position: relative;
        -webkit-transition: var(--switch-transition);
        -o-transition: var(--switch-transition);
        transition: var(--switch-transition);
        cursor: pointer;
    }

    .circle {
        width: var(--circle-diameter);
        height: var(--circle-diameter);
        background: var(--circle-bg);
        border-radius: inherit;
        -webkit-box-shadow: var(--circle-shadow);
        box-shadow: var(--circle-shadow);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-transition: var(--circle-transition);
        -o-transition: var(--circle-transition);
        transition: var(--circle-transition);
        z-index: 1;
        position: absolute;
        left: var(--switch-offset);
    }

    .slider::before {
        content: "";
        position: absolute;
        width: var(--effect-width);
        height: var(--effect-height);
        left: calc(var(--switch-offset) + (var(--effect-width) / 2));
        background: var(--effect-bg);
        border-radius: var(--effect-border-radius);
        -webkit-transition: var(--effect-transition);
        -o-transition: var(--effect-transition);
        transition: var(--effect-transition);
    }

    /* actions */

    .switch input:checked+.slider {
        background: var(--switch-checked-bg);
    }

    .switch input:checked+.slider .checkmark {
        -webkit-transform: scale(1);
        -ms-transform: scale(1);
        transform: scale(1);
    }

    .switch input:checked+.slider .cross {
        -webkit-transform: scale(0);
        -ms-transform: scale(0);
        transform: scale(0);
    }

    .switch input:checked+.slider::before {
        left: calc(100% - var(--effect-width) - (var(--effect-width) / 2) - var(--switch-offset));
    }

    .switch input:checked+.slider .circle {
        left: calc(100% - var(--circle-diameter) - var(--switch-offset));
        -webkit-box-shadow: var(--circle-checked-shadow);
        box-shadow: var(--circle-checked-shadow);
    }

    /* Làm nổi bật mũi tên khi được chọn */
    .bi-arrow-up-short,
    .bi-arrow-down-short {
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .bi-arrow-up-short.text-primary {
        color: #007bff;
        /* Màu sắc nổi bật cho mũi tên lên */
    }

    .bi-arrow-down-short.text-primary {
        color: #dc3545;
        /* Màu sắc nổi bật cho mũi tên xuống */
    }

    /* Thêm hiệu ứng mượt mà khi nhấn vào mũi tên */
    .bi-arrow-up-short:hover,
    .bi-arrow-down-short:hover {
        transform: scale(1.2);
    }
</style>
