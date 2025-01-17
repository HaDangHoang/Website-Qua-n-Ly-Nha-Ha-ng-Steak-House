@extends('admin.master')

@section('title', 'Danh Sách Nguyên Liệu')

@section('content')
@include('admin.layouts.messages')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">Import Nguyên Liệu</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif


                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- @if ($errors->any())
                            <div>
                                <h4>Thông báo lỗi:</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}

                        <form action="{{ route('admin.ingredient.import.post') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="file">Chọn file Excel</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Import</button>
                            <!-- Nút Download Excel Mẫu -->
                            <a href="{{ route('admin.ingredient.download-template') }}"
                                class="btn btn-secondary mb-3">Download Excel Mẫu</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
