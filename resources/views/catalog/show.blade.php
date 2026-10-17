@extends('layouts.app')
@section('title', $record->name)
@section('content')
<nav aria-label="Đường dẫn"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route($catalog.'.index') }}">{{ $definition['title'] }}</a></li><li class="breadcrumb-item active" aria-current="page">Chi tiết</li></ol></nav>
<div class="page-heading"><div><p class="eyebrow">THÔNG TIN CHI TIẾT</p><h1>{{ $record->name }}</h1><p class="page-description">{{ $record->code }}</p></div><div class="d-flex gap-2 flex-wrap">@if(auth()->user()->role === 'admin')<a class="btn btn-primary" href="{{ route($catalog.'.edit',['record'=>$record->id]) }}"><x-icon name="edit" />Sửa</a><x-delete-button :action="route($catalog.'.destroy',['record'=>$record->id])" :label="$record->name" />@endif<a class="btn btn-light" href="{{ route($catalog.'.index') }}">Quay lại</a></div></div>
<section class="surface form-surface"><h2 class="form-section-title">Thông tin {{ mb_strtolower($definition['title']) }}</h2><dl class="detail-grid">
    @foreach($definition['fields'] as $field => $label)<div><dt>{{ $label }}</dt><dd>
        @if($field === 'is_active')<x-status :active="$record->is_active" />
        @elseif($field === 'parent_id')@if($record->parent)<a href="{{ route('categories.show',['record'=>$record->parent->id]) }}">{{ $record->parent->name }}</a>@else Không có danh mục cha @endif
        @elseif($field === 'category_id')<a href="{{ route('categories.show',['record'=>$record->category->id]) }}">{{ $record->category->name }}</a>
        @elseif($field === 'unit_id')<a href="{{ route('units.show',['record'=>$record->unit->id]) }}">{{ $record->unit->name }}</a>
        @else{{ $record->{$field} ?? '—' }}{{ $field === 'sale_price' ? ' đ' : ($field === 'tax_rate' && $record->tax_rate !== null ? ' %' : ($field === 'expiry_warning_days' && $record->expiry_warning_days !== null ? ' ngày' : '')) }}@endif
    </dd></div>@endforeach
</dl></section>
@endsection
