@props(['name' => 'leaf'])
@php
    $paths = [
        'leaf' => 'M20 4c-9-1-16 2-16 9a7 7 0 0 0 7 7c7 0 10-7 9-16ZM4 20l10-10M10 14v-4m0 4h4',
        'dashboard' => 'M3 3h7v7H3Zm11 0h7v11h-7ZM3 14h7v7H3Zm11 4h7v3h-7Z',
        'box' => 'm3 7 9-4 9 4v10l-9 4-9-4Zm0 0 9 4 9-4m-9 4v10M7.5 5 17 9',
        'folder' => 'M3 7V5h7l2 3h9v12H3Zm0 1h9',
        'unit' => 'm3 16 13-13 5 5L8 21Zm5-5 2 2m2-6 2 2m2-6 2 2',
        'users' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m20 0v-2a4 4 0 0 0-3-4M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8m8-7a4 4 0 0 1 0 7',
        'search' => 'M21 21l-5-5m2-6a8 8 0 1 1-16 0 8 8 0 0 1 16 0',
        'plus' => 'M12 5v14M5 12h14',
        'arrow' => 'M5 12h14m-6-6 6 6-6 6',
        'back' => 'M19 12H5m6-6-6 6 6 6',
        'eye' => 'M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12m13 0a3 3 0 1 1-6 0 3 3 0 0 1 6 0',
        'edit' => 'm15 4 5 5M4 20l5-1L21 7a2 2 0 0 0-4-4L5 15Zm8 0h9',
        'trash' => 'M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7m4-7v7',
        'logout' => 'M9 4H4v16h5m6-13 5 5-5 5m-6-5h11',
        'menu' => 'M4 6h16M4 12h16M4 18h16',
        'check' => 'm5 12 4 4L19 6',
        'lock' => 'M5 10h14v11H5Zm3 0V6a4 4 0 0 1 8 0v4m-4 5v2',
    ];
@endphp
<svg {{ $attributes->class(['icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $paths[$name] ?? $paths['leaf'] }}" /></svg>
