@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'block px-4 py-2 text-sm font-medium bg-indigo-500 rounded-md'
            : 'block px-4 py-2 text-sm font-medium hover:bg-indigo-500 rounded-md';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i class="{{ $icon }} mr-2"></i>
    {{ $slot }}
</a>
