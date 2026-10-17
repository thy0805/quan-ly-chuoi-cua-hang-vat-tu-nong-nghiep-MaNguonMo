@extends('layouts.app')
@section('title', 'Tài khoản')
@section('content')
    <h1>{{ $record ? 'Cập nhật tài khoản' : 'Thêm nhân viên' }}</h1>
    <form method="post" action="{{ $record ? route('users.update',['record'=>$record->id]) : route('users.store') }}">
        @csrf @if($record)@method('PUT')@endif
        <label>Tên đăng nhập<input name="username" value="{{ old('username',$record?->username) }}" maxlength="80" required></label>
        <p>Vai trò: {{ $record?->role ?? 'user' }}</p>
        <label>Mật khẩu {{ $record ? '(để trống để giữ nguyên)' : '' }}<input type="password" name="password" autocomplete="new-password" @required(!$record)></label>
        <label>Xác nhận mật khẩu<input type="password" name="password_confirmation" autocomplete="new-password" @required(!$record)></label>
        <label>Đang hoạt động<input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$record?->is_active ?? true))></label>
        <button type="submit">Lưu</button> <a href="{{ route('users.index') }}">Quay lại</a>
    </form>
@endsection
