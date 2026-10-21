@extends('layouts.app')
@section('title', 'In '.$sale->number)
@section('content')
<link rel="stylesheet" href="{{ asset('assets/sales-print.css') }}">
<div class="d-flex gap-2 mb-3 invoice-controls"><button class="btn btn-primary" type="button" data-print-invoice>In / Lưu PDF</button><a class="btn btn-light" href="{{ route('sales.show',$sale->id) }}">Quay lại phiếu</a></div>
<article class="surface p-3 p-md-4 invoice-paper"><p class="eyebrow">MẦM XANH · VẬT TƯ NÔNG NGHIỆP</p><h1>Hóa đơn nội bộ {{ $sale->number }}</h1><p>Ngày xác nhận (UTC): {{ $sale->confirmed_at->format('d/m/Y H:i:s') }}</p><p>Nhân viên lập: {{ $sale->user->username }}</p>@include('sales.table')<p class="fs-4 text-end mt-3">Tổng tiền: <strong>{{ $sale->total }} VND</strong></p>@if($sale->note)<p class="text-break">Ghi chú: {{ $sale->note }}</p>@endif<p class="small text-muted">Chứng từ bán hàng tại quầy, không thay thế hóa đơn điện tử/thuế. Đơn giá theo phiếu; không có dòng cộng thuế hoặc chiết khấu riêng.</p></article><script src="{{ asset('assets/sales.js') }}" defer></script>
@endsection
