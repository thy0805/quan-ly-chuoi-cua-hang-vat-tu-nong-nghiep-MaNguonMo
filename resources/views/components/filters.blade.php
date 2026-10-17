@props(['catalog' => 'users'])
@php
    $size = (int) request('per_page', 20);
    $sizes = array_unique(array_merge(in_array($size, [10,20,50], true) ? [] : [$size], [10,20,50]));
    $status = request()->boolean('active_only') ? '1' : (string) request('is_active', '');
@endphp
<form method="get" action="{{ route($catalog.'.index') }}" class="filter-form">
    <div class="filter-search"><label class="form-label small" for="filter-q">{{ $catalog === 'users' ? 'Tìm tài khoản' : 'Tìm kiếm' }}</label><div class="input-group"><span class="input-group-text"><x-icon name="search" /></span><input class="form-control" id="filter-q" name="q" value="{{ request('q') }}" maxlength="160" placeholder="{{ $catalog === 'users' ? 'Tên đăng nhập…' : 'Nhập mã hoặc tên…' }}"></div></div>
    @if($catalog === 'products')
        <div><label class="form-label small" for="filter-category">Danh mục</label><select id="filter-category" class="form-select" name="category_id"><option value="">Tất cả danh mục</option>@foreach(\App\Models\ProductCategory::orderBy('name')->orderBy('id')->get(['id','name']) as $option)<option value="{{ $option->id }}" @selected((string)request('category_id') === $option->id)>{{ $option->name }}</option>@endforeach</select></div>
        <div><label class="form-label small" for="filter-unit">Đơn vị tính</label><select id="filter-unit" class="form-select" name="unit_id"><option value="">Tất cả đơn vị</option>@foreach(\App\Models\Unit::orderBy('name')->orderBy('id')->get(['id','name']) as $option)<option value="{{ $option->id }}" @selected((string)request('unit_id') === $option->id)>{{ $option->name }}</option>@endforeach</select></div>
    @endif
    <div><label class="form-label small" for="filter-status">Trạng thái</label><select class="form-select" id="filter-status" name="is_active"><option value="">Tất cả trạng thái</option><option value="1" @selected($status === '1')>Hoạt động</option><option value="0" @selected($status === '0')>Ngừng hoạt động</option></select></div>
    <div class="filter-size"><label class="form-label small" for="filter-size">Số dòng</label><select class="form-select" id="filter-size" name="per_page">@foreach($sizes as $value)<option value="{{ $value }}" @selected($size === $value)>{{ $value }}</option>@endforeach</select></div>
    <div class="filter-buttons"><button class="btn btn-primary" type="submit">Tìm</button><a class="btn btn-light" href="{{ route($catalog.'.index') }}">Đặt lại</a></div>
</form>
