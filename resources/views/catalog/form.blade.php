@extends('layouts.app')
@section('title', ($record ? 'Cập nhật' : 'Thêm').' · '.$definition['title'])
@section('content')
<nav aria-label="Đường dẫn"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route($catalog.'.index') }}">{{ $definition['title'] }}</a></li><li class="breadcrumb-item active" aria-current="page">{{ $record ? 'Cập nhật' : 'Thêm mới' }}</li></ol></nav>
<div class="page-heading"><div><h1>{{ $record ? 'Cập nhật' : 'Thêm' }} — {{ $definition['title'] }}</h1><p class="page-description">Điền thông tin bên dưới. Trường có dấu * là bắt buộc.</p></div></div>
<div class="row g-4"><div class="col-xl-8"><form method="post" action="{{ $record ? route($catalog.'.update',['record'=>$record->id]) : route($catalog.'.store') }}" class="surface form-surface">
    @csrf @if($record)@method('PUT')@endif
    <h2 class="form-section-title">Thông tin {{ mb_strtolower($definition['title']) }}</h2><div class="row g-4">
    @foreach($definition['fields'] as $field => $label)
        <div class="{{ in_array($field,['name','is_active','active_ingredient'],true) ? 'col-12' : 'col-md-6' }}">
        @if($field === 'is_active')
            <div class="form-check form-switch"><input type="hidden" name="is_active" value="0"><input class="form-check-input @error($field) is-invalid @enderror" id="{{ $field }}" type="checkbox" role="switch" name="is_active" value="1" @checked(old($field,$record?->{$field} ?? true))><label class="form-check-label" for="{{ $field }}">Đang hoạt động</label></div><div class="form-text">Trạng thái sử dụng của dữ liệu trong cửa hàng.</div>
        @else
            <label class="form-label" for="{{ $field }}">{{ $label }}@if(in_array($field,['code','name','sale_price','category_id','unit_id'],true)) <span class="text-danger" aria-hidden="true">*</span>@endif</label>
            @if(in_array($field,['parent_id','category_id','unit_id'],true))
                <select class="form-select @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" @required(!$record && $field !== 'parent_id') @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
                    <option value="">{{ $field === 'parent_id' ? 'Không có danh mục cha' : 'Chọn dữ liệu' }}</option>
                    @foreach($field === 'unit_id' ? $units : $categories as $option)<option value="{{ $option->id }}" @selected((string)old($field,$record?->{$field}) === $option->id) @disabled(!$option->is_active && $field !== 'parent_id')>{{ $option->name }}{{ $option->is_active ? '' : ' (ngừng)' }}</option>@endforeach
                </select>
                @if($record && $field !== 'parent_id')<div class="form-text">Tham chiếu đã ngừng được giữ khi không thay đổi; chỉ chọn mới dữ liệu đang hoạt động.</div>@endif
            @else
                <input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" type="{{ $field === 'expiry_warning_days' ? 'number' : 'text' }}" value="{{ old($field,$record?->{$field} ?? ($field === 'sale_price' ? '0.00' : '')) }}" @required(in_array($field,['code','name','sale_price'],true)) @if($field === 'code') maxlength="{{ $catalog === 'products' ? 60 : 40 }}" @elseif($field === 'name') maxlength="{{ match($catalog) {'products'=>200,'categories'=>160,default=>100} }}" @elseif($field === 'active_ingredient') maxlength="200" @elseif($field === 'expiry_warning_days') min="0" max="3650" step="1" @endif @if(in_array($field,['sale_price','tax_rate'],true)) inputmode="decimal" @endif @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
                @if($field === 'sale_price')<div class="form-text">Đơn vị: đồng. Tối đa 2 chữ số thập phân, ví dụ 125000.00.</div>@elseif($field === 'tax_rate')<div class="form-text">Từ 0 đến 100, tối đa 2 chữ số thập phân.</div>@elseif($field === 'expiry_warning_days')<div class="form-text">Từ 0 đến 3650 ngày.</div>@endif
            @endif
        @endif
        <x-field-error :field="$field" />
        </div>
    @endforeach</div>
    <div class="form-actions"><a class="btn btn-light" href="{{ route($catalog.'.index') }}">Quay lại</a><button class="btn btn-primary" type="submit"><x-icon name="check" />Lưu</button></div>
</form></div><aside class="col-xl-4"><div class="info-card"><x-icon name="leaf" /><h2>Thông tin rõ ràng,<br>quản lý dễ dàng.</h2><p>Kiểm tra mã và tên trước khi lưu. Mỗi mã phải là duy nhất.</p><p>{{ $catalog === 'categories' ? 'Danh mục cha phải phù hợp với cấu trúc hiện có và không tạo vòng lặp.' : ($catalog === 'products' ? 'Vật tư cần danh mục và đơn vị tính hợp lệ. Giá bán được giữ chính xác đến hai chữ số thập phân.' : 'Đơn vị tính đã được vật tư sử dụng sẽ được bảo vệ khi xóa.') }}</p></div></aside></div>
@endsection
