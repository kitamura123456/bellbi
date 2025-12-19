@if ($paginator->hasPages())
    <nav>
        <ul class="pagination" style="display: flex; justify-content: center; align-items: center; gap: 8px; list-style: none; padding: 0; margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Hiragino Sans', 'Yu Gothic', 'Noto Sans JP', sans-serif;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')" style="display: inline-block; margin: 0;">
                    <span aria-hidden="true" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 12px; font-size: 12px; font-weight: 400; color: #ccc; text-decoration: none; border: 1px solid #e0e0e0; background-color: #fafafa; cursor: not-allowed; letter-spacing: 0.02em;">&lsaquo;</span>
                </li>
            @else
                <li style="display: inline-block; margin: 0;">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 12px; font-size: 12px; font-weight: 400; color: #1a1a1a; text-decoration: none; border: 1px solid #e0e0e0; background-color: #ffffff; transition: all 0.2s ease; letter-spacing: 0.02em;" onmouseover="this.style.backgroundColor='#f5f5f5'; this.style.borderColor='#1a1a1a';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#e0e0e0';">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true" style="display: inline-block; margin: 0;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 10px; font-size: 12px; font-weight: 400; color: #ccc; text-decoration: none; border: 1px solid #e0e0e0; background-color: #fafafa; cursor: not-allowed; letter-spacing: 0.02em;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page" style="display: inline-block; margin: 0;">
                                <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 10px; font-size: 12px; font-weight: 400; color: #ffffff; text-decoration: none; border: 1px solid #1a1a1a; background-color: #1a1a1a; letter-spacing: 0.02em;">{{ $page }}</span>
                            </li>
                        @else
                            <li style="display: inline-block; margin: 0;">
                                <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 10px; font-size: 12px; font-weight: 400; color: #1a1a1a; text-decoration: none; border: 1px solid #e0e0e0; background-color: #ffffff; transition: all 0.2s ease; letter-spacing: 0.02em;" onmouseover="this.style.backgroundColor='#f5f5f5'; this.style.borderColor='#1a1a1a';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#e0e0e0';">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li style="display: inline-block; margin: 0;">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 12px; font-size: 12px; font-weight: 400; color: #1a1a1a; text-decoration: none; border: 1px solid #e0e0e0; background-color: #ffffff; transition: all 0.2s ease; letter-spacing: 0.02em;" onmouseover="this.style.backgroundColor='#f5f5f5'; this.style.borderColor='#1a1a1a';" onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#e0e0e0';">&rsaquo;</a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')" style="display: inline-block; margin: 0;">
                    <span aria-hidden="true" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 12px; font-size: 12px; font-weight: 400; color: #ccc; text-decoration: none; border: 1px solid #e0e0e0; background-color: #fafafa; cursor: not-allowed; letter-spacing: 0.02em;">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

