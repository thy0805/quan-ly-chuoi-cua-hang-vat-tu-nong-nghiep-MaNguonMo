@extends('layouts.app')
@section('title', 'Quản lý tồn kho')
@section('content')
<div class="page-heading"><div><p class="eyebrow">KHO CỬA HÀNG</p><h1>Quản lý tồn kho</h1><p class="page-description">Số lượng theo từng vật tư. Chưa phát sinh nhập/xuất được hiển thị bằng 0.</p></div><div class="d-flex gap-2 flex-wrap"><a class="btn btn-light" href="{{ route('inventory.history') }}">Lịch sử kho</a>@if(auth()->user()->role === 'admin')<a class="btn btn-primary" href="{{ route('inventory.in.create') }}">Nhập kho</a><a class="btn btn-outline-secondary" href="{{ route('inventory.out.create') }}">Xuất kho</a>@endif</div></div>
<section class="surface" aria-label="Danh sách tồn kho">
    <div class="surface-heading"><h2>Tồn theo vật tư</h2><span>{{ $records->total() }} vật tư</span></div>
    @include('inventory.filters', ['history' => false])
    @include('partials.table-hint')
    <div class="table-responsive" tabindex="0" role="region" aria-label="Bảng tồn kho, cuộn ngang để xem thêm"><table class="table align-middle mb-0"><thead><tr><th>Mã / Vật tư</th><th>Danh mục</th><th>Đơn vị tính</th><th class="text-end">Số lượng tồn</th><th>Trạng thái</th><th>Tra cứu</th></tr></thead><tbody>
    @forelse($records as $product)<tr><td><span class="record-code">{{ $product->code }}</span><a class="record-name d-block" href="{{ route('products.show', ['record' => $product->id]) }}">{{ $product->name }}</a></td><td>{{ $product->category->name }}</td><td>{{ $product->unit->name }}</td><td class="text-end text-nowrap fw-semibold">{{ $product->stock?->quantity ?? '0.000' }}</td><td><x-status :active="$product->is_active" /></td><td><a class="btn btn-sm btn-light" href="{{ route('inventory.history', ['product_id' => $product->id]) }}">Lịch sử</a></td></tr>
    @empty<tr><td colspan="6" class="empty-state"><strong>Không tìm thấy vật tư phù hợp.</strong><p>Thử thay đổi từ khóa hoặc bộ lọc.</p></td></tr>@endforelse
    </tbody></table></div><x-pagination :records="$records" />
</section>
@endsection
