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
                            <form action="{{ route('admin.permissions.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Tên Quyền Hạn</label>
                                    <input value="{{ old('name') }}" type="text" name="name" class="form-control"
                                        placeholder="Nhập tên quyền hạn">
                                    @error('name')
                                        <p class="text-danger"> {{ $message }}</p>
                                    @enderror
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
