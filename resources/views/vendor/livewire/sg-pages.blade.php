<div>
    @if ($paginator->hasPages())
        <div class="fenye">
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : $this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1)
        
{{--        <nav>--}}
{{--            <ul class="pagination">--}}
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
{{--                    <li class="page-item disabled" aria-disabled="true" aria-label="上一頁">--}}
                        <span class="page-link" aria-hidden="true" style="float:left; margin:0 20px; border:#fd5678 1px solid; text-align: center; color:#fd5678; padding:0px 27px; border-radius:200px; line-height:35px; font-size:16px; text-align: center">上一頁</span>
{{--                    </li>--}}
                @else
{{--                    <li class="page-item">--}}
                        <button type="button" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="prev" aria-label="上一頁">上一頁</button>
{{--                    </li>--}}
                @endif

            <span class="new_page">第 {{ $paginator->currentPage() }} 頁</span>
                {{-- Pagination Elements --}}
{{--                @foreach ($elements as $element)--}}
{{--                    --}}{{-- "Three Dots" Separator --}}
{{--                    @if (is_string($element))--}}
{{--                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>--}}
{{--                    @endif--}}

{{--                    --}}{{-- Array Of Links --}}
{{--                    @if (is_array($element))--}}
{{--                        @foreach ($element as $page => $url)--}}
{{--                            @if ($page == $paginator->currentPage())--}}
{{--                                <li class="page-item active" wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page-{{ $page }}" aria-current="page"><span class="page-link">{{ $page }}</span></li>--}}
{{--                            @else--}}
{{--                                <li class="page-item" wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page-{{ $page }}"><button type="button" class="page-link" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">{{ $page }}</button></li>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                @endforeach--}}

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
{{--                    <li class="page-item">--}}
                        <button type="button" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" rel="next" aria-label="下一頁">下一頁</button>
{{--                    </li>--}}
                @else
{{--                    <li class="page-item disabled" aria-disabled="true" aria-label="下一頁">--}}
                        <span class="page-link" aria-hidden="true" style="float:left; margin:0 20px; border:#fd5678 1px solid; text-align: center; color:#fd5678; padding:0px 27px; border-radius:200px; line-height:35px; font-size:16px; text-align: center">下一頁</span>
{{--                    </li>--}}
                @endif
{{--            </ul>--}}
{{--        </nav>--}}
        </div>
    @endif
</div>
