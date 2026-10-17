@props(['records'])
<div class="list-footer">
    <p class="small text-muted mb-0">Hiển thị {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} / {{ $records->total() }} kết quả</p>
    <nav aria-label="Phân trang" class="d-flex align-items-center gap-2 flex-wrap">
        @if($records->previousPageUrl())<a class="btn btn-sm btn-outline-secondary" href="{{ $records->previousPageUrl() }}">Trang trước</a>@else<span class="btn btn-sm btn-outline-secondary disabled" aria-disabled="true">Trang trước</span>@endif
        <span class="small">Trang {{ $records->currentPage() }} / {{ $records->lastPage() }}</span>
        @if($records->nextPageUrl())<a class="btn btn-sm btn-outline-secondary" href="{{ $records->nextPageUrl() }}">Trang sau</a>@else<span class="btn btn-sm btn-outline-secondary disabled" aria-disabled="true">Trang sau</span>@endif
    </nav>
</div>
