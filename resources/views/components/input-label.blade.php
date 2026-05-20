@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium uppercase tracking-[0.18em] text-stone-400']) }}>
    {{ $value ?? $slot }}
</label>
