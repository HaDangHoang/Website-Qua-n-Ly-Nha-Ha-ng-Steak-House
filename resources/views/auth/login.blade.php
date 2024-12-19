<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- Các file CSS -->
    <link rel="stylesheet" href="{{ asset('adminn/assets/css/css_login/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminn/assets/css/css_login/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminn/assets/css/css_login/style.css') }}">
</head>

<body>
    <section class="fxt-template-animation fxt-template-layout6"
        data-bg-image="{{ asset('adminn/assets/images/img/figure/e741496597ca9b434b6b989bdb1e41f5.jpg') }}">
        <div class="fxt-header">
            <a href="{{ route('login/admin') }}" class="fxt-logo">
                <img src="{{ asset('adminn/assets/images/img/logo-6.png') }}" alt="Logo">
            </a>
        </div>
        <div class="fxt-content">
            <div class="fxt-form">
                <h2>Login</h2>

                <!-- Hiển thị lỗi nếu có -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form đăng nhập -->
                <form method="POST" action="{{ route('login/admin') }}">
                    @csrf
                    <div class="form-group">
                        <input id="email" type="email" class="form-control" name="email"
                            placeholder="Email Address" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <input id="password" type="password" class="form-control" name="password"
                            placeholder="Password" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="fxt-btn-fill">Log in</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Các file JS -->
    <script src="{{ asset('adminn/assets/js/js_login/jquery.min.js') }}"></script>
    <script src="{{ asset('adminn/assets/js/js_login/bootstrap.min.js') }}"></script>
    <script src="{{ asset('adminn/assets/js/js_login/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('adminn/assets/js/js_login/validator.min.js') }}"></script>
    <script src="{{ asset('adminn/assets/js/js_login/main.js') }}"></script>
</body>

</html>
