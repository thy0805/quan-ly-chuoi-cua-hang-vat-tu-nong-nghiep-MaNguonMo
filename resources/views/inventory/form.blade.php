@extends('layouts.app')
@section('title', $direction === 'in' ? 'Nhập kho thủ công' : 'Xuất kho thủ công')
@section('content')
<div class="page-heading"><div><p class="eyebrow">PHIẾU KHO THỦ CÔNG</p><h1>{{ $direction === 'in' ? 'Nhập kho' : 'Xuất kho' }}</h1><p class="page-description">{{ $direction === 'in' ? 'Ghi tăng tồn và lưu một dòng lịch sử.' : 'Ghi giảm tồn khi số lượng hiện có đáp ứng yêu cầu.' }}</p></div><a class="btn btn-light" href="{{ route('inventory.index') }}">Quay lại tồn kho</a></div>
<section class="surface p-3 p-md-4">
    <div class="alert alert-info">Đây là phiếu kho thủ công, không phải hóa đơn bán hàng hay phiếu nhập nhà cung cấp. Số tồn trong danh sách có thể thay đổi; hệ thống kiểm tra lại khi xác nhận.</div>
    <form method="post" action="{{ route('inventory.'.$direction.'.store') }}">
        @csrf
        <input type="hidden" name="idempotency_key" value="{{ old('idempotency_key', $idempotencyKey) }}">
        <div class="row g-3">
            <div class="col-12 col-lg-8"><label class="form-label" for="product_id">Vật tư đang hoạt động <span class="text-danger">*</span></label><select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required><option value="">Chọn vật tư</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id', request('product_id')) === $product->id)>{{ $product->code }} · {{ $product->name }} · Tồn {{ $product->stock?->quantity ?? '0.000' }} {{ $product->unit->name }}</option>@endforeach</select><x-field-error field="product_id" /></div>
            <div class="col-12 col-lg-4"><label class="form-label" for="quantity">Số lượng <span class="text-danger">*</span></label><input class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" inputmode="decimal" maxlength="16" placeholder="Ví dụ: 20 hoặc 1.125" required aria-describedby="quantity-help"><x-field-error field="quantity" /><div id="quantity-help" class="form-text">Lớn hơn 0, tối đa 3 số lẻ; dùng dấu chấm.</div></div>
            <div class="col-12"><label class="form-label" for="note">Ghi chú</label><textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" maxlength="1000" rows="3" placeholder="Lý do nhập/xuất kho">{{ old('note') }}</textarea><x-field-error field="note" /></div>
            <div class="col-12"><x-field-error field="idempotency_key" /><p class="small text-muted">Xác nhận sẽ cập nhật tồn và ghi lịch sử ngay. Nếu gửi lại cùng phiếu, hệ thống không ghi trùng. Muốn tạo giao dịch khác, mở phiếu mới.</p><div class="d-flex gap-2 flex-wrap"><button class="btn btn-primary" type="submit" @disabled($products->isEmpty())>{{ $direction === 'in' ? 'Xác nhận nhập kho' : 'Xác nhận xuất kho' }}</button><a class="btn btn-light" href="{{ route('inventory.index') }}">Hủy</a><a class="btn btn-outline-secondary" href="{{ route('inventory.'.$direction.'.create') }}">Mở phiếu mới</a></div></div>
        </div>
    </form>
</section>
@endsection
