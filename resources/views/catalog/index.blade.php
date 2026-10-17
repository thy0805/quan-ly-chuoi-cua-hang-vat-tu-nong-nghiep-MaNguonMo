@extends('layouts.app')
@section('title', $definition['title'])
@section('content')
<div class="page-heading"><div><p class="eyebrow">DỮ LIỆU CỬA HÀNG</p><h1>{{ $definition['title'] }}</h1><p class="page-description">{{ $catalog === 'products' ? 'Tra cứu và quản lý thông tin vật tư nông nghiệp.' : ($catalog === 'categories' ? 'Sắp xếp vật tư theo nhóm và danh mục cha.' : 'Danh sách đơn vị tính sử dụng cho vật tư.') }}</p></div>@if(auth()->user()->role === 'admin')<a class="btn btn-primary" href="{{ route($catalog.'.create') }}"><x-icon name="plus" />Thêm mới</a>@endif</div>
<section class="surface" aria-label="Danh sách {{ $definition['title'] }}">
    <div class="surface-heading"><h2>Tất cả {{ mb_strtolower($definition['title']) }}</h2><span class="small text-muted">Tổng số: {{ $records->total() }}</span></div>
    <x-filters :catalog="$catalog" />
    @include('partials.table-hint')<div class="table-responsive" tabindex="0" role="region" aria-label="Bảng dữ liệu, cuộn ngang để xem thêm"><table class="table align-middle mb-0"><thead><tr><th scope="col">Mã</th><th scope="col">Tên</th>@if($catalog === 'products')<th scope="col">Danh mục / Đơn vị</th><th scope="col" class="text-end">Giá bán (đ)</th>@elseif($catalog === 'categories')<th scope="col">Danh mục cha</th>@endif<th scope="col">Trạng thái</th><th scope="col">Thao tác</th></tr></thead><tbody>
        @forelse($records as $row)<tr><td><span class="record-code">{{ $row->code }}</span></td><td><a class="record-name" href="{{ route($catalog.'.show',['record'=>$row->id]) }}">{{ $row->name }}</a>@if($catalog === 'products' && $row->active_ingredient)<small class="d-block text-muted">{{ $row->active_ingredient }}</small>@endif</td>
            @if($catalog === 'products')<td>{{ $row->category->name }}<small class="d-block text-muted">{{ $row->unit->name }}</small></td><td class="money text-end text-nowrap">{{ $row->sale_price }}</td>@elseif($catalog === 'categories')<td>{{ $row->parent?->name ?? '—' }}</td>@endif
            <td><x-status :active="$row->is_active" /></td><td><div class="row-actions"><a class="btn btn-sm btn-light" href="{{ route($catalog.'.show',['record'=>$row->id]) }}" aria-label="Xem {{ $row->name }}"><x-icon name="eye" />Xem</a>@if(auth()->user()->role === 'admin')<a class="btn btn-sm btn-outline-secondary" href="{{ route($catalog.'.edit',['record'=>$row->id]) }}" aria-label="Sửa {{ $row->name }}"><x-icon name="edit" />Sửa</a><x-delete-button :action="route($catalog.'.destroy',['record'=>$row->id])" :label="$row->name" />@endif</div></td></tr>
        @empty<tr><td colspan="{{ $catalog === 'products' ? 6 : ($catalog === 'categories' ? 5 : 4) }}" class="empty-state"><x-icon name="search" /><strong>Chưa có dữ liệu phù hợp.</strong><p>Thử đổi từ khóa hoặc đặt lại bộ lọc.</p></td></tr>@endforelse
    </tbody></table></div><x-pagination :records="$records" />
</section>
@endsection
