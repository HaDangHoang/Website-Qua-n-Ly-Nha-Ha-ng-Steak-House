<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            margin-top: 100px;
            max-width: 500px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2 class="text-center">Đăng Ký</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
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
    <form action="{{ route('register.form') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Họ và Tên</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Số Điện Thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="password">Mật Khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <small class="form-text text-muted">Mật khẩu phải ít nhất 8 ký tự.</small>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Xác Nhận Mật Khẩu</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Đăng Ký</button>
    </form>
    <p class="text-center mt-3">Đã có tài khoản? <a href="{{ route('login.form') }}">Đăng Nhập</a></p>

</div>

</body>
</html>
