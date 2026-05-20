@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-4 py-3 text-sm font-medium text-cyan-100']) }}>
        {{ $status }}
    </div>
@endif
