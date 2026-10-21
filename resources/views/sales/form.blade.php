@extends('layouts.app')
@section('title', $sale ? 'Sửa phiếu nháp' : 'Lập phiếu bán')
@section('content')
<div class="page-heading"><div><p class="eyebrow">BÁN HÀNG TẠI QUẦY</p><h1>{{ $sale ? 'Sửa nháp '.$sale->number : 'Lập phiếu bán' }}</h1><p class="page-description">Lưu nháp để tính tổng tiền. Kho chỉ giảm khi xác nhận ở trang chi tiết.</p></div><a class="btn btn-light" href="{{ route('sales.index') }}">Danh sách phiếu</a></div>
<section class="surface p-3 p-md-4"><form method="post" action="{{ $sale ? route('sales.update',$sale->id) : route('sales.store') }}" id="sale-form">@csrf @if($sale) @method('PATCH')<input type="hidden" name="version" value="{{ old('version',$sale->version) }}">@endif<input type="hidden" name="client_key" value="{{ old('client_key',$clientKey) }}">
<p class="small text-muted">Tối đa 50 dòng, mỗi vật tư một dòng. Số lượng tối đa 3 số lẻ; đơn giá tối đa 2 số lẻ, dùng dấu chấm. Thành tiền làm tròn half-up đến 0.01 VND mỗi dòng; tổng là cộng các dòng, không cộng thuế/chiết khấu riêng.</p>
<div id="sale-lines" class="d-grid gap-3">@php($rows = old('lines', $sale?->lines->map(fn($line) => ['product_id'=>$line->product_id,'quantity'=>$line->quantity,'unit_price'=>$line->unit_price])->all() ?? [['product_id'=>'','quantity'=>'1','unit_price'=>'']]))
@foreach($rows as $i=>$line) @include('sales.line', ['i'=>$i,'line'=>$line]) @endforeach
</div><button class="btn btn-outline-secondary mt-3" type="button" id="add-sale-line">Thêm vật tư</button><div class="mt-4"><label class="form-label" for="note">Ghi chú</label><textarea class="form-control" rows="3" id="note" name="note" maxlength="1000">{{ old('note',$sale?->note) }}</textarea></div><div class="mt-4 d-flex gap-2 flex-wrap"><button class="btn btn-primary" type="submit">Lưu nháp và tính tổng</button><a class="btn btn-light" href="{{ $sale ? route('sales.show',$sale->id) : route('sales.index') }}">Hủy</a></div></form></section>
<template id="sale-line-template">@include('sales.line', ['i'=>'__INDEX__','line'=>['product_id'=>'','quantity'=>'1','unit_price'=>'']])</template>
<script src="{{ asset('assets/sales.js') }}" defer></script>
@endsection
