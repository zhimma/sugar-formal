@if ($paginator->lastPage() >1)
    <div class="fenye">
        <a href="{{ $paginator->previousPageUrl() }}">上一頁</a>
        <span class="new_page">第 {{ $paginator->currentPage() }} 頁</span>
        <a href="{{ $paginator->nextPageUrl() }}">下一頁</a>
    </div>
@endif
