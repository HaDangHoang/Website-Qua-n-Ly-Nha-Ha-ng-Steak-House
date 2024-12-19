@extends('admin.master')

@section('title', 'Danh Sách Phản Hồi')

@section('content')

    @include('admin.layouts.messages')



    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="card-title">Danh sách phản hồi</div>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-auto">
                                    <!-- Input with search icon inside -->
                                    <div class="input-group">
                                        <form method="GET" action="{{ route('admin.feedback.index') }}" class="mb-3">
                                            <div class="row g-2">
                                                <div class="col-auto">
                                                    <input type="text" id="search" name="search"
                                                        class="form-control form-control-sm"
                                                        placeholder="Tìm kiếm tên khách hàng"
                                                        value="{{ request('search') }}">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                                <div class="col-auto">

                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table v-middle m-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <a
                                                    href="{{ route('admin.feedback.index', array_merge(request()->query(), ['sort' => 'reservation_id', 'direction' => request('sort') === 'reservation_id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                    Mã Đơn Hàng
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'reservation_id' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                                </a>
                                            </th>
                                            <th>Tên Khách Hàng</th>
                                            <th>Nội Dung</th>
                                            <th> <a
                                                    href="{{ route('admin.feedback.index', array_merge(request()->query(), ['sort' => 'rating', 'direction' => request('sort') === 'rating' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                                                    Xếp Hạng
                                                    <i
                                                        class="bi bi-arrow-{{ request('sort') === 'rating' ? (request('direction') === 'asc' ? 'up' : 'down') : '' }}"></i>
                                                </a></th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($feedbacks as $feedback)
                                            <tr>
                                                <td>{{ $feedback->reservation_id }}</td>
                                                <td>{{ $feedback->customer->name ?? 'Khách hàng không tồn tại' }}</td>
                                                <td id="content_{{ $feedback->id }}">{{ $feedback->content }}</td>
                                                <td id="rating_{{ $feedback->id }}">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $feedback->rating)
                                                            <i class="fa fa-star" style="color: gold;"></i>
                                                        @else
                                                            <i class="fa fa-star-o" style="color: gold;"></i>
                                                        @endif
                                                    @endfor
                                                </td>
                                                <td>
                                                    <div class="actions d-flex">
                                                        <a href="{{ route('admin.feedback.show', $feedback->id) }}"
                                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">
                                                            <i class="bi bi-list text-green"></i>
                                                        </a>
                                                        <a href="">
                                                            <form
                                                                action="{{ route('admin.feedback.destroy', $feedback->id) }}"
                                                                method="POST" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link p-0"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                                    <i class="bi bi-trash text-red"></i>
                                                                </button>
                                                            </form>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">Không có feedback nào được tìm thấy.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">

                    {{ $feedbacks->links('pagination::client-paginate') }}

                </div>
            </div>
        </div>
    </div>
    </div>
    </div>


@endsection
