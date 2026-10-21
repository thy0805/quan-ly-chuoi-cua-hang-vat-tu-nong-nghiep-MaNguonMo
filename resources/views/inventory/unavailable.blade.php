@extends('layouts.app')
@section('title', 'Tồn kho chưa được cài đặt')
@section('content')
<section class="surface p-4"><h1 class="h3">Tồn kho chưa được cài đặt trên database này</h1><p>Chức năng kho cần migration mới được triển khai sau khi nghiệm thu. Các chức năng quản lý vật tư hiện có vẫn sử dụng bình thường.</p><p>Không tự chạy migration hoặc tạo dữ liệu để bỏ qua thông báo này.</p><a class="btn btn-primary" href="{{ route('products.index') }}">Về danh sách vật tư</a></section>
@endsection
