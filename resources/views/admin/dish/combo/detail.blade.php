@extends('admin.master')

@section('title', 'Chi Tiết Combo')

@section('content')
    @include('admin.layouts.messages')
    <!-- Content wrapper scroll start -->
    <div class="content-wrapper-scroll">
        <!-- Content wrapper start -->
        <div class="content-wrapper">

            <!-- Row start -->
            <div class="row">
                <div class="col-md-10 col-12 mx-auto">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-primary">Chi Tiết Combo</h5>
                            <div>
                                <a href="{{ route('admin.combo.edit', $combo->id) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="bi bi-pencil-square"></i> Chỉnh Sửa
                                </a>
                                <a href="{{ route('admin.combo.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                                </a>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4 col-12">
                                    <div class="text-center">
                                        <img class="img-fluid rounded shadow-sm"
                                            src="{{ $combo->image ? asset('storage/' . $combo->image) : 'https://via.placeholder.com/150' }}"
                                            alt="{{ $combo->name }}"
                                            style="max-width: 100%; height: auto; max-height: 250px;">
                                    </div>
                                </div>

                                <div class="col-md-8 col-12">
                                    <h5 class="fw-bold">{{ $combo->name }}</h5>
                                    <p><strong>Giá Combo:</strong> {{ number_format($combo->price, 0, ',', '.') }} VND</p>
                                    <p><strong>Số Lượng Món Ăn:</strong> {{ $combo->quantity_dishes }} món</p>
                                    <p><strong>Tên Món Ăn:</strong>
                                        @foreach ($dishes as $item)
                                            {{ $item->name }}
                                        @endforeach
                                    </p>
                                    <hr>
                                    <h6>Mô tả Combo:</h6>
                                    <div style="word-wrap: break-word; max-width: 100%; white-space: normal;">
                                        <p class="text-muted">
                                            {!! str_replace(
                                                '<img',
                                                '<img style="max-width: 100%; height: auto;padding-left: 20%; max-height: 250px;"',
                                                $combo->description,
                                            ) !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- End Row -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->

        </div>
        <!-- Content wrapper end -->
    </div>
    <!-- Content wrapper scroll end -->
@endsection
