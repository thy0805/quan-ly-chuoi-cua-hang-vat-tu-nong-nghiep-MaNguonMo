@props(['active'])
<span class="status-badge {{ $active ? 'status-active' : 'status-inactive' }}"><span class="status-dot" aria-hidden="true"></span>{{ $active ? 'Hoạt động' : 'Ngừng hoạt động' }}</span>
