<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#215c45">
    <title>@yield('title', 'Tổng quan') · Mầm Xanh</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}">
    <script defer src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ asset('assets/app.js') }}"></script>
</head>
<body class="{{ auth()->check() ? 'app-body' : 'guest-body' }}">
<a class="skip-link" href="#main-content">Đến nội dung chính</a>
@auth
    <aside class="offcanvas-lg offcanvas-start app-sidebar" tabindex="-1" id="app-sidebar" aria-labelledby="sidebar-title">
        <div class="offcanvas-header"><span class="fw-semibold" id="sidebar-title">Điều hướng</span><button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#app-sidebar" aria-label="Đóng menu"></button></div>
        <div class="offcanvas-body sidebar-inner">
            <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark"><x-icon name="leaf" /></span><span>Mầm Xanh<small>VẬT TƯ NÔNG NGHIỆP</small></span></a>
            <p class="nav-caption">KHÔNG GIAN LÀM VIỆC</p>
            <nav class="sidebar-nav" aria-label="Điều hướng chính">
                @foreach(['dashboard' => ['Tổng quan','dashboard'], 'products' => ['Vật tư','box'], 'categories' => ['Danh mục','folder'], 'units' => ['Đơn vị tính','unit']] as $section => [$label,$icon])
                    <a href="{{ route($section === 'dashboard' ? 'dashboard' : $section.'.index') }}" class="{{ request()->routeIs($section === 'dashboard' ? 'dashboard' : $section.'.*') ? 'is-current' : '' }}" @if(request()->routeIs($section === 'dashboard' ? 'dashboard' : $section.'.*')) aria-current="page" @endif><x-icon :name="$icon" />{{ $label }}</a>
                @endforeach
                @if(auth()->user()->role === 'admin')<a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'is-current' : '' }}" @if(request()->routeIs('users.*')) aria-current="page" @endif><x-icon name="users" />Tài khoản</a>@endif
            </nav>
            <div class="sidebar-note"><x-icon name="leaf" /><strong>Chăm từng mùa vụ.</strong><span>Quản lý vật tư rõ ràng,<br>đồng hành cùng nhà nông.</span></div>
            <div class="sidebar-footer">Lập trình mã nguồn mở<span>Nhóm 6 · Laravel & MySQL</span></div>
        </div>
    </aside>
    <div class="app-workspace">
        <header class="app-header">
            <div class="d-flex align-items-center gap-3 min-width-0"><button class="btn btn-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#app-sidebar" aria-controls="app-sidebar" aria-label="Mở menu"><x-icon name="menu" /></button><span class="header-caption">Quản lý cửa hàng<span class="d-none d-md-inline"> / Vật tư nông nghiệp</span></span></div>
            <div class="header-account"><span class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->username,0,1)) }}</span><span class="account-name">{{ auth()->user()->username }}<small>{{ auth()->user()->role === 'admin' ? 'Quản trị viên' : 'Nhân viên · Chỉ xem' }}</small></span><form method="post" action="{{ route('logout') }}">@csrf<button class="btn btn-light logout-button" type="submit" aria-label="Đăng xuất"><x-icon name="logout" /><span class="d-none d-xl-inline">Đăng xuất</span></button></form></div>
        </header>
        <main id="main-content" class="app-main" tabindex="-1">@include('partials.feedback') @yield('content')</main>
        <footer class="app-footer"><span>Mầm Xanh · Quản lý vật tư nông nghiệp</span><span>Nhóm 6</span></footer>
    </div>
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="delete-title" aria-describedby="delete-description" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h2 class="modal-title fs-5" id="delete-title">Xác nhận xóa</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button></div><div class="modal-body"><p id="delete-description">Xóa <strong id="delete-record-name"></strong>?</p><p class="small text-muted mb-0">Thao tác này không thể hoàn tác. Dữ liệu đang được sử dụng sẽ được hệ thống bảo vệ.</p></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button><button type="button" class="btn btn-danger" id="delete-confirm">Xóa dữ liệu</button></div></div></div>
    </div>
@else
    <main id="main-content" tabindex="-1">@yield('content')</main>
@endauth
</body>
</html>
