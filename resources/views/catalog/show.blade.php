@extends('layouts.app')
@section('title', $definition['title'])
@section('content')
    <h1>{{ $record->name }}</h1>
    <dl>
        @foreach($definition['fields'] as $field => $label)
            <dt>{{ $label }}</dt><dd>{{ $field === 'is_active' ? ($record->is_active ? 'Hoạt động' : 'Ngừng') : $record->{$field} }}</dd>
        @endforeach
    </dl>
    <a href="{{ route($catalog.'.index') }}">Quay lại</a>
@endsection
