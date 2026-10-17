@extends('layouts.app')
@section('title', 'Thông tin tài khoản')
@section('content')
<nav aria-label="Đường dẫn"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('users.index') }}">Tài khoản</a></li><li class="breadcrumb-item active" aria-current="page">Chi tiết</li></ol></nav>
<div class="page-heading"><div><p class="eyebrow">THÔNG TIN TÀI KHOẢN</p><h1>{{ $record->username }}</h1><p class="page-description">Thông tin truy cập và trạng thái tài khoản.</p></div><div class="d-flex gap-2 flex-wrap"><a class="btn btn-primary" href="{{ route('users.edit',['record'=>$record->id]) }}"><x-icon name="edit" />Sửa</a>@if($record->role !== 'admin')<x-delete-button :action="route('users.destroy',['record'=>$record->id])" :label="$record->username" />@endif<a class="btn btn-light" href="{{ route('users.index') }}">Quay lại</a></div></div>
<section class="surface form-surface"><dl class="detail-grid"><div><dt>Tên đăng nhập</dt><dd>{{ $record->username }}</dd></div><div><dt>Vai trò</dt><dd><span class="role-badge">{{ $record->role === 'admin' ? 'Quản trị viên' : 'Nhân viên' }}</span></dd></div><div><dt>Trạng thái</dt><dd><x-status :active="$record->is_active" /></dd></div><div><dt>Quyền truy cập</dt><dd>{{ $record->role === 'admin' ? 'Quản lý vật tư, danh mục, đơn vị tính và tài khoản.' : 'Xem, tìm kiếm và tra cứu thông tin vật tư.' }}</dd></div></dl></section>
@endsection
