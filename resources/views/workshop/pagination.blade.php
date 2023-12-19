@if ($paginator->hasPages())
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">{{ __('newsandupdates.prev_btn') }}</a>
            </li>
        @else
            <li class=""><a class="page-link" href="{{ $paginator->previousPageUrl() }}">{{ __('newsandupdates.prev_btn') }}</a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a class="page-link">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item ">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('newsandupdates.next_btn') }}</a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#">{{ __('newsandupdates.next_btn') }}</a>
            </li>
        @endif
@endif
