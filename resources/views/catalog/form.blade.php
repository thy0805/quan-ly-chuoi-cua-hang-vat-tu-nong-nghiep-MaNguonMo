@extends('layouts.app')
@section('title', $definition['title'])
@section('content')
    <h1>{{ $record ? 'Cập nhật' : 'Thêm' }} — {{ $definition['title'] }}</h1>
    <form method="post" action="{{ $record ? route($catalog.'.update', ['record' => $record->id]) : route($catalog.'.store') }}">
        @csrf
        @if($record)@method('PUT')@endif
        @foreach($definition['fields'] as $field => $label)
            <label>{{ $label }}
                @if($field === 'is_active')
                    <input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old($field, $record?->{$field} ?? true))>
                @elseif(in_array($field, ['parent_id','category_id','unit_id'], true))
                    <select name="{{ $field }}" @required(!$record && $field !== 'parent_id')>
                        <option value="">{{ $field === 'parent_id' ? 'Không có danh mục cha' : 'Chọn dữ liệu' }}</option>
                        @foreach($field === 'unit_id' ? $units : $categories as $option)
                            <option value="{{ $option->id }}" @selected((string)old($field, $record?->{$field}) === $option->id) @disabled(!$option->is_active && $field !== 'parent_id')>{{ $option->name }}{{ $option->is_active ? '' : ' (ngừng)' }}</option>
                        @endforeach
                    </select>
                @else
                    <input name="{{ $field }}" value="{{ old($field, $record?->{$field} ?? ($field === 'sale_price' ? '0.00' : '')) }}" @required(in_array($field,['code','name','sale_price'],true)) @if(in_array($field,['sale_price','tax_rate'],true)) inputmode="decimal" @endif>
                @endif
            </label>
        @endforeach
        <button type="submit">Lưu</button> <a href="{{ route($catalog.'.index') }}">Quay lại</a>
    </form>
@endsection
