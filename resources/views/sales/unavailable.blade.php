@extends('layouts.app')
@section('title', 'Bán hàng chưa được cài đặt')
@section('content')<section class="surface p-4"><h1>Bán hàng chưa sẵn sàng</h1><p>{{ $message }}</p><a class="btn btn-light" href="{{ route('products.index') }}">Về danh sách vật tư</a></section>@endsection
