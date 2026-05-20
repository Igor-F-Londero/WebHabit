<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-full border border-rose-400/20 bg-rose-500/15 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-rose-200 transition hover:bg-rose-500/25 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-0']) }}>
    {{ $slot }}
</button>
