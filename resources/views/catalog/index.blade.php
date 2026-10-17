@extends('layouts.app')
@section('title', $definition['title'])
@section('content')
    <h1>{{ $definition['title'] }}</h1>
    <form method="get" action="{{ route($catalog.'.index') }}">
        <label>Tìm kiếm<input name="q" value="{{ request('q') }}" maxlength="160"></label>
        <label>Số dòng<select name="per_page">@foreach([10,20,50] as $size)<option value="{{ $size }}" @selected((int)request('per_page',20) === $size)>{{ $size }}</option>@endforeach</select></label>
        <button type="submit">Tìm</button>
    </form>
    @if(auth()->user()->role === 'admin')<p><a href="{{ route($catalog.'.create') }}">Thêm mới</a></p>@endif
    <p>Tổng số: {{ $records->total() }}</p>
    <table>
        <thead><tr><th>Mã</th><th>Tên</th>@if($catalog === 'products')<th>Danh mục / Đơn vị</th><th>Giá bán</th>@endif<th>Trạng thái</th><th>Thao tác</th></tr></thead>
        <tbody>
            @forelse($records as $row)
                <tr>
                    <td>{{ $row->code }}</td><td>{{ $row->name }}</td>
                    @if($catalog === 'products')<td>{{ $row->category->name }} / {{ $row->unit->name }}</td><td>{{ $row->sale_price }}</td>@endif
                    <td>{{ $row->is_active ? 'Hoạt động' : 'Ngừng' }}</td>
                    <td><a href="{{ route($catalog.'.show', ['record' => $row->id]) }}">Xem</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route($catalog.'.edit', ['record' => $row->id]) }}">Sửa</a>
                            <form method="post" action="{{ route($catalog.'.destroy', ['record' => $row->id]) }}">@csrf @method('DELETE')<button type="submit">Xóa</button></form>
                        @endif
                    </td>
                </tr>
            @empty<tr><td colspan="6">Chưa có dữ liệu phù hợp.</td></tr>@endforelse
        </tbody>
    </table>
    <nav aria-label="Phân trang">
        @if($records->previousPageUrl())<a href="{{ $records->previousPageUrl() }}">Trang trước</a>@endif
        <span>Trang {{ $records->currentPage() }} / {{ $records->lastPage() }}</span>
        @if($records->nextPageUrl())<a href="{{ $records->nextPageUrl() }}">Trang sau</a>@endif
    </nav>
@endsection
