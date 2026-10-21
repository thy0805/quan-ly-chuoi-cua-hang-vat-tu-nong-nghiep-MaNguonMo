<form method="get" class="row g-3 p-3 align-items-end">
    @if(request('product_id'))<input type="hidden" name="product_id" value="{{ request('product_id') }}">@endif
    <div class="col-12 col-md-4"><label class="form-label" for="q">Tìm vật tư</label><input class="form-control" id="q" name="q" value="{{ request('q') }}" maxlength="160" placeholder="Nhập mã hoặc tên vật tư"></div>
    <div class="col-12 col-md-3"><label class="form-label" for="category_id">Danh mục</label><select class="form-select" id="category_id" name="category_id"><option value="">Tất cả danh mục</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category_id') === $category->id)>{{ $category->name }}</option>@endforeach</select></div>
    @if($history)
        <div class="col-6 col-md-2"><label class="form-label" for="direction">Loại giao dịch</label><select class="form-select" id="direction" name="direction"><option value="">Nhập và xuất</option><option value="in" @selected(request('direction') === 'in')>Nhập kho</option><option value="out" @selected(request('direction') === 'out')>Xuất kho</option></select></div>
        <div class="col-6 col-md-3"><label class="form-label" for="from">Từ ngày (UTC)</label><input class="form-control" type="date" id="from" name="from" value="{{ request('from') }}"></div>
        <div class="col-6 col-md-3"><label class="form-label" for="to">Đến ngày (UTC)</label><input class="form-control" type="date" id="to" name="to" value="{{ request('to') }}"></div>
    @else
        <div class="col-6 col-md-2"><label class="form-label" for="stock_status">Tình trạng tồn</label><select class="form-select" id="stock_status" name="stock_status"><option value="">Tất cả</option><option value="positive" @selected(request('stock_status') === 'positive')>Còn tồn</option><option value="zero" @selected(request('stock_status') === 'zero')>Hết tồn / Chưa phát sinh</option></select></div>
    @endif
    <div class="col-6 col-md-2"><label class="form-label" for="per_page">Số dòng</label><input class="form-control" type="number" id="per_page" name="per_page" value="{{ request('per_page', 20) }}" min="1" max="50"></div>
    <div class="col-12 d-flex gap-2 flex-wrap"><button class="btn btn-primary" type="submit">Tìm kiếm</button><a class="btn btn-light" href="{{ route($history ? 'inventory.history' : 'inventory.index') }}">Đặt lại bộ lọc</a></div>
</form>
