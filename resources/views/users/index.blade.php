@extends('layouts.app')
@section('title', 'Quản lý tài khoản')
@section('content')
    <h1>Quản lý tài khoản</h1>
    <form method="get"><label>Tìm tài khoản<input name="q" value="{{ request('q') }}" maxlength="160"></label><button type="submit">Tìm</button></form>
    <p><a href="{{ route('users.create') }}">Thêm nhân viên</a></p>
    <table><thead><tr><th>Tên đăng nhập</th><th>Vai trò</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>
        @foreach($records as $row)
            <tr><td>{{ $row->username }}</td><td>{{ $row->role }}</td><td>{{ $row->is_active ? 'Hoạt động' : 'Ngừng' }}</td><td>
                <a href="{{ route('users.show',['record'=>$row->id]) }}">Xem</a> <a href="{{ route('users.edit',['record'=>$row->id]) }}">Sửa</a>
                @if($row->role !== 'admin')<form method="post" action="{{ route('users.destroy',['record'=>$row->id]) }}">@csrf @method('DELETE')<button type="submit">Xóa</button></form>@endif
            </td></tr>
        @endforeach
    </tbody></table>
    <nav aria-label="Phân trang">@if($records->previousPageUrl())<a href="{{ $records->previousPageUrl() }}">Trang trước</a>@endif <span>Trang {{ $records->currentPage() }} / {{ $records->lastPage() }}</span> @if($records->nextPageUrl())<a href="{{ $records->nextPageUrl() }}">Trang sau</a>@endif</nav>
@endsection
