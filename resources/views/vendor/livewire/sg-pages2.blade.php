<div>
@if ($paginator->hasPages())
    <div class="fenye">
        @if ($paginator->onFirstPage())
{{--            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">--}}
{{--                <span class="page-link" aria-hidden="true">&lsaquo;</span>--}}
{{--            </li>--}}
            <a class="disabled page-link" aria-disabled="true">上一頁</a>
        @else
{{--            <li class="page-item">--}}
{{--                <a type="button" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev" aria-label="@lang('pagination.previous')">上一頁</a>--}}
{{--            </li>--}}

            @if(method_exists($paginator,'getCursorName'))
                <a type="button" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="setPage('{{$paginator->previousCursor()->encode()}}','{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" rel="prev">上一頁</a>

            @else
                <a type="button" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev">上一頁</a>
            @endif
        @endif

{{--        <a href="{{ $paginator->previousPageUrl() }}">上一頁</a>--}}

        <span class="new_page">第 {{ $paginator->currentPage() }} 頁</span>
{{--        <a href="{{ $paginator->nextPageUrl() }}">下一頁</a>--}}
        @if ($paginator->hasMorePages())
{{--            <li class="page-item">--}}
{{--                <a dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next" aria-label="@lang('pagination.next')">下一頁</a>--}}
{{--            </li>--}}
                @if(method_exists($paginator,'getCursorName'))
{{--                    <li class="page-item">--}}
{{--                        <button dusk="nextPage{{ $paginator->getCursorName() == 'page' ? '' : '.' . $paginator->getPageName() }}" type="button" class="page-link" wire:click="setPage('{{$paginator->nextCursor()->encode()}}','{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" rel="next">@lang('pagination.next')</button>--}}
{{--                    </li>--}}
                    <a dusk="nextPage{{ $paginator->getCursorName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="setPage('{{$paginator->nextCursor()->encode()}}','{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" rel="next">下一頁</a>
                @else
{{--                    <li class="page-item">--}}
{{--                        <button type="button" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next">@lang('pagination.next')</button>--}}
{{--                    </li>--}}
                    <a dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next">下一頁</a>
                @endif
        @else
{{--            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">--}}
{{--                <span class="page-link" aria-hidden="true">&rsaquo;</span>--}}
{{--            </li>--}}
            <a class="disabled page-link" aria-disabled="true">下一頁</a>
        @endif
    </div>
@endif
</div>
