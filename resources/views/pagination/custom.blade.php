@if ($paginator->total() > 0)
    <nav class="pagination-container" aria-label="Điều hướng phân trang">
        <div class="pagination-info">
            Hiển thị <strong>{{ $paginator->firstItem() }}</strong> - <strong>{{ $paginator->lastItem() }}</strong> trong tổng số <strong>{{ $paginator->total() }}</strong> cuốn sách
        </div>

        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true"><span>&laquo; Trước</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Trước</a></li>
            @endif

            {{-- Pagination Elements --}}
            @if ($paginator->hasPages())
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @else
                <li class="active" aria-current="page"><span>1</span></li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Sau &raquo;</a></li>
            @else
                <li class="disabled" aria-disabled="true"><span>Sau &raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
