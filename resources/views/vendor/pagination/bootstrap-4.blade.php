@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Nút trang trước --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">&laquo;</a>
                </li>
            @endif

            {{-- Số trang --}}
            @foreach ($elements as $element)
                {{-- "Dấu ba chấm" --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Các liên kết đến các trang --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link" style="border-radius: 50%; background-color: #ff0000; color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Nút trang sau --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
