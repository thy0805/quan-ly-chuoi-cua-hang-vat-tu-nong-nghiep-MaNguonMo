@extends('layouts.app')
@section('title', 'Lịch sử kho')
@section('content')
<div class="page-heading"><div><p class="eyebrow">SỔ BIẾN ĐỘNG</p><h1>Lịch sử nhập / xuất kho</h1><p class="page-description">Mỗi dòng là một giao dịch đã ghi nhận. Lịch sử không được sửa hoặc xóa; thời gian hiển thị theo UTC.</p></div><a class="btn btn-light" href="{{ route('inventory.index') }}">Xem tồn kho</a></div>
<section class="surface" aria-label="Lịch sử nhập xuất">
    <div class="surface-heading"><h2>Giao dịch kho</h2><span>{{ $records->total() }} giao dịch</span></div>
    @include('inventory.filters', ['history' => true])
    @include('partials.table-hint')
    <div class="table-responsive" tabindex="0" role="region" aria-label="Bảng lịch sử kho, cuộn ngang để xem thêm"><table class="table align-middle mb-0"><thead><tr><th>Phiếu / Thời gian UTC</th><th>Vật tư</th><th>Loại</th><th class="text-end">Số lượng</th><th class="text-end">Tồn trước</th><th class="text-end">Tồn sau</th><th>Người thực hiện</th><th>Ghi chú / Nguồn</th></tr></thead><tbody>
    @forelse($records as $movement)<tr><td>#{{ $movement->id }}<small class="d-block text-nowrap text-muted">{{ $movement->created_at->format('d/m/Y H:i:s') }}</small></td><td><span class="record-code">{{ $movement->product->code }}</span><strong class="d-block">{{ $movement->product->name }}</strong><small>{{ $movement->product->unit->name }}</small></td><td><span class="badge {{ $movement->direction === 'in' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $movement->direction === 'in' ? 'Nhập kho' : 'Xuất kho' }}</span></td><td class="text-end text-nowrap">{{ $movement->quantity }}</td><td class="text-end text-nowrap">{{ $movement->quantity_before }}</td><td class="text-end text-nowrap fw-semibold">{{ $movement->quantity_after }}</td><td>{{ $movement->user->username }}</td><td><span class="d-block text-break">{{ $movement->note ?? '—' }}</span><small class="text-muted">{{ $movement->source_type === 'manual' ? 'Thủ công' : $movement->source_type.' #'.$movement->source_id }}</small></td></tr>
    @empty<tr><td colspan="8" class="empty-state"><strong>Chưa có giao dịch phù hợp.</strong><p>Nhập/xuất thành công sẽ được ghi tại đây.</p></td></tr>@endforelse
    </tbody></table></div><x-pagination :records="$records" />
</section>
@endsection
