@if ($paginator->lastPage() >1)
    <div class="fenye">
        <a href="{{ $paginator->previousPageUrl() }}">上一頁</a>
        <span class="new_page">{{$paginator->currentPage()}}/{{$paginator->lastPage()}}</span>
        <a href="{{ $paginator->nextPageUrl() }}">下一頁</a>
    </div>
@endif
