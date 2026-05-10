@props(['value'])

@php
    $styles = [
        'ebook' => 'badge badge-ebook',
        'template' => 'badge badge-template',
        'software' => 'badge badge-software',
        'course' => 'badge badge-course',
    ];
@endphp

<span {{ $attributes->class([$styles[$value] ?? 'badge']) }}>
    {{ $value }}
</span>
