@extends('layouts.app')
@section('title', 'Đăng nhập')
@section('content')
<div class="login-shell">
    <section class="login-story" aria-labelledby="login-story-title">
        <a class="brand brand-light" href="{{ route('login') }}"><span class="brand-mark"><x-icon name="leaf" /></span><span>Mầm Xanh<small>VẬT TƯ NÔNG NGHIỆP</small></span></a>
        <div class="login-story-copy"><span class="eyebrow">GIEO NIỀM TIN · GẶT GIÁ TRỊ</span><h1 id="login-story-title">Vững từng bước.<br>Xanh từng mùa.</h1><p>Một nơi để quản lý vật tư,<br>kết nối những mùa vụ tốt lành.</p>
            <div class="plant-scene" aria-hidden="true"><div class="plant-orbit"></div><svg viewBox="0 0 480 270" fill="none"><path d="M35 230Q180 180 440 230M75 251Q230 205 407 247" stroke="#bad38a" stroke-width="2"/><path d="M240 222V90" stroke="#dbedb9" stroke-width="7" stroke-linecap="round"/><path d="M240 158C157 155 142 116 151 83c52-1 87 22 89 75Z" fill="#9ec977"/><path d="M241 124c3-59 41-87 89-83 4 48-26 78-89 83Z" fill="#dbe9b0"/><path d="M239 196c-48 0-81-23-81-54 44-3 77 15 81 54Z" fill="#d7e8aa"/><path d="M241 180c3-39 33-62 70-56 0 32-24 55-70 56Z" fill="#7eaf63"/><path d="M207 222h66l-9 34h-49Z" fill="#eac98b"/><circle cx="367" cy="92" r="7" fill="#eac98b"/><circle cx="113" cy="184" r="4" fill="#eac98b"/></svg></div>
        </div><span class="login-story-footer">Giải pháp quản lý cửa hàng vật tư nông nghiệp</span>
    </section>
    <section class="login-panel" aria-labelledby="login-title">
        <div class="login-form-wrap"><span class="login-symbol"><x-icon name="leaf" /></span><p class="eyebrow text-success mb-2">CHÀO MỪNG TRỞ LẠI</p><h2 id="login-title">Đăng nhập</h2><p class="text-muted mb-4">Bắt đầu một ngày làm việc hiệu quả.</p>
            @include('partials.feedback')
            <form method="post" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-3"><label class="form-label" for="username">Tên đăng nhập</label><input class="form-control form-control-lg @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" maxlength="80" autocomplete="username" placeholder="Nhập tên đăng nhập" required autofocus @error('username') aria-invalid="true" aria-describedby="username-error" @enderror><x-field-error field="username" /></div>
                <div class="mb-4"><label class="form-label" for="password">Mật khẩu</label><div class="input-group"><input class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" type="password" name="password" autocomplete="current-password" placeholder="Nhập mật khẩu" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror><button class="btn btn-outline-secondary" type="button" data-toggle-password="password" aria-label="Hiện mật khẩu" aria-pressed="false"><x-icon name="eye" /></button></div><x-field-error field="password" /></div>
                <button class="btn btn-primary btn-lg w-100 justify-content-center" type="submit">Đăng nhập<x-icon name="arrow" /></button>
            </form><p class="login-help"><x-icon name="lock" />Dùng tài khoản do quản trị viên cung cấp.</p>
        </div><footer class="login-panel-footer">Lập trình mã nguồn mở · Nhóm 6</footer>
    </section>
</div>
@endsection
