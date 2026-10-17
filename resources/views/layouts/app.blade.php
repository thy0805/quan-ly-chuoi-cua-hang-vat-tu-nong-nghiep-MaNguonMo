<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Quản lý vật tư nông nghiệp')</title>
    <style>
        body { font: 16px/1.5 system-ui, sans-serif; max-width: 1100px; margin: 32px auto; padding: 0 16px; color: #18352a; }
        nav, .actions { display: flex; gap: 16px; flex-wrap: wrap; align-items: center; }
        a { color: #176147; } table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        td, th { border: 1px solid #c4d3cb; text-align: left; padding: 8px; }
        label { display: block; margin: 14px 0; } input:not([type=checkbox]), select { display: block; padding: 8px; max-width: 100%; min-width: 220px; }
        button { padding: 8px 14px; cursor: pointer; } .errors { color: #8c2020; } .status { background: #e2f1e8; padding: 12px; }
    </style>
</head>
<body>
    @auth
        <nav aria-label="Điều hướng">
            <a href="{{ route('products.index') }}">Vật tư</a>
            <a href="{{ route('categories.index') }}">Danh mục</a>
            <a href="{{ route('units.index') }}">Đơn vị tính</a>
            @if(auth()->user()->role === 'admin')<a href="{{ route('users.index') }}">Tài khoản</a>@endif
            <span>{{ auth()->user()->username }}</span>
            <form method="post" action="{{ route('logout') }}">@csrf<button type="submit">Đăng xuất</button></form>
        </nav>
    @endauth
    @if(session('status'))<p class="status" role="status">{{ session('status') }}</p>@endif
    @if($errors->any())
        <ul class="errors" role="alert">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    @endif
    <main>@yield('content')</main>
</body>
</html>
