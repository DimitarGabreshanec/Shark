@if ($paginator->hasPages())

    <div class="table_head_wrap table_foot_wrap">
        <div class="pager">
            <span class="pager_text">全{{ $paginator->total() }}件中 {{ ($paginator->currentPage()-1) * $paginator->perPage() + 1 }}件〜 @if ( $paginator->currentPage() * $paginator->perPage() > $paginator->total()) {{ $paginator->total() }} @else {{ $paginator->currentPage() * $paginator->perPage() }} @endif 件を表示</span>
            <ul>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li aria-label="@lang('pagination.previous')">
                        <a href="#" onclick="event.preventDefault();">&lsaquo;</a>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li >{{ $element }}</li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li ><a class="current" href="#" onclick="event.preventDefault();">{{ $page }}</a></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <a href="#" onclick="event.preventDefault();">&rsaquo;</a>
                    </li>
                @endif

            </ul>
        </div>
    </div>

@endif
