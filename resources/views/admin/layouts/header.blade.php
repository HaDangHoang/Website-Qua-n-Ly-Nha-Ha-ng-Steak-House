<div class="page-header">
    <div class="toggle-sidebar" id="toggle-sidebar"><i class="bi bi-list"></i></div>


    @if (isset($title))
        @include('components.breadcrumb', ['title' => $title ?? ''])
    @endif

    <div class="header-actions-container">

        <ul class="header-actions">
            <li class="dropdown">

                <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                    <span class="">{{ Auth::user()->name }}
                        ({{ Auth::user()->roles->pluck('name')->implode(' , ') }})</span>
                    <span class="avatar">
                        <img src="{{ asset('adminn/assets/images/user2.png') }}" alt="Admin Templates">
                        <span class="status online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userSettings">
                    <div class="header-profile-actions">
                        @can('access pos')
                            <a href="{{ route('pos.index') }}">Pos</a>
                        @endcan
                        {{-- <a href="{{ route('admin.accountSetting.index') }}">Settings</a>
                        </a> --}}

                        <!-- Form Logout -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="{{ url('/') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </div>
                </div>

            </li>
        </ul>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Thư viện jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Thư viện OverlayScrollbars CSS và JS -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/js/jquery.overlayScrollbars.min.js">
</script>

<!-- Tệp custom-scrollbar.js -->
<script src="path/to/custom-scrollbar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
