<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-full bg-cyan-300 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-950 shadow-[0_0_24px_rgba(34,211,238,0.18)] transition hover:bg-cyan-200 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-0']) }}>
    {{ $slot }}
</button>
