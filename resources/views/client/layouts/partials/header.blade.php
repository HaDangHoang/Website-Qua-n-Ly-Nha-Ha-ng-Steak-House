<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <!-- logo begin -->
            <div id="logo">
                <a href="03_luxury-index.html">
                    <img class="logo logo_dark_bg" src="client/03_images/logo.png" alt="">
                    <img class="logo logo_light_bg" src="client/03_images/logo.png" alt="">
                </a>
            </div>
            <!-- logo close -->

            <!-- small button begin -->
            <span id="menu-btn"></span>
            <!-- small button close -->

            <!-- mainmenu begin -->
            <nav>
                <ul id="mainmenu">
                    <li><a href="{{ route('client.index') }}">TRANG CHỦ</a></li>
                    <li><a href="{{ route('menu') }}">THỰC ĐƠN</a></li>
                    <li><a href="{{ route('booking.client') }}">ĐẶT BÀN</a></li>
                    <li><a href="{{ route('about.client') }}">VỀ CHÚNG TÔI</a></li>

                    @auth
                    <li><a href="{{ route('client.member') }}">Profile</a></li>
                    {{-- <li>
                        <form action="{{ route('client.logout') }}" method="POST">
                            @csrf
                            <button type="submit">Đăng xuất</button>
                        </form>
                    </li> --}}
                @else
                    <li><a href="{{ route('login.form') }}"">Đăng nhập</a>
                        {{-- <ul>
                            <li><a href=""">Đăng nhập</a></li>
                        </ul> --}}
                    </li>
                @endauth

                    <li><a href="{{ route('contact.client') }}">LIÊN HỆ</a></li>
                    {{-- <li><a href="{{ route('client.member') }}"><i class="bi bi-person-fill"></i></a>
                        <ul>
                            <li><a href="{{ route('login.client') }}">Login</a></li>
                        </ul>
                    </li> --}}

                </ul>
            </nav>

            {{-- Debugging --}}
            <div style="display: none;">
                Is logged in: {{ var_export(auth()->check(), true) }}
                User: {{ auth()->user() ? auth()->user()->email : 'Not logged in' }}
            </div>


            <div class="clearfix"></div>
        </div>
        <!-- mainmenu close -->
    </div>
</div>


</div>
