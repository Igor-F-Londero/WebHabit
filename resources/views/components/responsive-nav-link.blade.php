@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-cyan-300 bg-cyan-300/10 py-2 ps-3 pe-4 text-start text-base font-medium text-cyan-100 transition duration-150 ease-in-out focus:border-cyan-200 focus:bg-cyan-300/15 focus:outline-none'
            : 'block w-full border-l-4 border-transparent py-2 ps-3 pe-4 text-start text-base font-medium text-slate-400 transition duration-150 ease-in-out hover:border-cyan-300/40 hover:bg-white/[0.04] hover:text-slate-100 focus:border-cyan-300/40 focus:bg-white/[0.04] focus:text-slate-100 focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
