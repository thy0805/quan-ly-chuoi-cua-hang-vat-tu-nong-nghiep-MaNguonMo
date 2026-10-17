@extends('layouts.app')
@section('title', 'Đăng nhập')
@section('content')
    <h1>Đăng nhập</h1>
    <form method="post" action="{{ route('login.store') }}">
        @csrf
        <label>Tên đăng nhập<input name="username" value="{{ old('username') }}" maxlength="80" autocomplete="username" required autofocus></label>
        <label>Mật khẩu<input type="password" name="password" autocomplete="current-password" required></label>
        <button type="submit">Đăng nhập</button>
    </form>
@endsection
