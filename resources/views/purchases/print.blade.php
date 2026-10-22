@extends('layouts.app')
@section('title', 'In '.$purchase->number)
@section('content')
<link rel="stylesheet" href="{{ asset('assets/sales-print.css') }}">
<div class="d-flex gap-2 mb-3 invoice-controls"><button class="btn btn-primary" type="button" data-print-invoice>In / Lưu PDF</button><a class="btn btn-light" href="{{ route('purchases.show',$purchase->id) }}">Quay lại phiếu</a></div>
<article class="surface p-3 p-md-4 invoice-paper"><p class="eyebrow">MẦM XANH · VẬT TƯ NÔNG NGHIỆP</p><h1>Phiếu nhập nội bộ {{ $purchase->number }}</h1><p>Ngày xác nhận (UTC): {{ $purchase->confirmed_at->format('d/m/Y H:i:s') }}</p><p>Nhân viên lập: {{ $purchase->user->username }}</p><p class="text-break">Nhà cung cấp: <strong>{{ $purchase->supplier_code }} · {{ $purchase->supplier_name }}</strong><br>{{ $purchase->supplier_phone }} · {{ $purchase->supplier_address }}</p>@include('purchases.table')<div class="invoice-summary"><p class="fs-4 text-end mt-3">Tổng tiền: <strong>{{ $purchase->total }} VND</strong></p>@if($purchase->note)<p class="text-break">Ghi chú: {{ $purchase->note }}</p>@endif<p class="small text-muted">Chứng từ nhập hàng nội bộ, không thay thế phiếu nhập điện tử/thuế. Đơn giá theo phiếu; không có dòng cộng thuế hoặc chiết khấu riêng.</p></div></article><script src="{{ asset('assets/purchases.js') }}" defer></script>
@endsection
