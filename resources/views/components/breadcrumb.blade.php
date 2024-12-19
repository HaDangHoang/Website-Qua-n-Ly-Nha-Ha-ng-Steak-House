<ol class="breadcrumb d-md-flex d-none">
    <li class="breadcrumb-item">
        <i class="bi bi-house"></i>
        <a href="{{ url('admin') }}">Trang Chủ</a>
    </li>
    @if (!empty($title))
        <li class="breadcrumb-item breadcrumb-active" aria-current="page">{{ $title }}</li>
    @endif
</ol>
