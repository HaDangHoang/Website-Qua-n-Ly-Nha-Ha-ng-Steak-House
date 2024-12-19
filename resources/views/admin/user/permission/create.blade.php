@extends('admin.master')

@section('title', 'Thêm Quyền Hạn Mới')

@section('content')

@include('admin.layouts.messages')

    <div class="content-wrapper-scroll">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Thêm Quyền Hạn Mới</div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.permission.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Tên Quyền Hạn</label>
                                    <input type="text" name="permission_name" class="form-control"
                                        placeholder="Nhập tên quyền hạn" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô Tả</label>
                                    <textarea name="description" class="form-control" placeholder="Nhập mô tả quyền hạn (tuỳ chọn)"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Lưu Quyền Hạn</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
