@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-b-2 border-cyan-300 px-1 pt-1 text-sm font-medium leading-5 text-cyan-100 focus:border-cyan-200 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-slate-400 transition duration-150 ease-in-out hover:border-cyan-300/40 hover:text-slate-100 focus:border-cyan-300/40 focus:outline-none focus:text-slate-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
