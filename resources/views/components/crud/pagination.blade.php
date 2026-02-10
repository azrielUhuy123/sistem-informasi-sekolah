@if($rows instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="d-flex justify-content-between mt-3">
    <div>
        Menampilkan {{ $rows->firstItem() }} - {{ $rows->lastItem() }} dari {{ $rows->total() }} data
    </div>

    <div class="simple-pagination">
        @if($rows->onFirstPage())
            <span class="disabled-page">«</span>
        @else
            <a class="page-link" href="{{ $rows->previousPageUrl() }}">«</a>
        @endif

        @foreach($rows->getUrlRange(1, $rows->lastPage()) as $page => $url)
            @if($page == $rows->currentPage())
                <span class="active-page">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
            @endif
        @endforeach

        @if($rows->hasMorePages())
            <a class="page-link" href="{{ $rows->nextPageUrl() }}">»</a>
        @else
            <span class="disabled-page">»</span>
        @endif
    </div>
</div>
@endif