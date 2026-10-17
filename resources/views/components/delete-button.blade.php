@props(['action', 'label'])
<form method="post" action="{{ $action }}" data-confirm-delete="{{ $label }}" class="d-inline">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Xóa {{ $label }}"><x-icon name="trash" /><span>Xóa</span></button>
</form>
