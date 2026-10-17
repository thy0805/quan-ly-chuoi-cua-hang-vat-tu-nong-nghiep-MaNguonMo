@extends('layouts.app')
@section('title', 'Thông tin tài khoản')
@section('content')
    <h1>{{ $record->username }}</h1>
    <p>Vai trò: {{ $record->role }}</p><p>Trạng thái: {{ $record->is_active ? 'Hoạt động' : 'Ngừng' }}</p>
    <a href="{{ route('users.index') }}">Quay lại</a>
@endsection
