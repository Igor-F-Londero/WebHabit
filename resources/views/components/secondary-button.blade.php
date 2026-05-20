<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-full border border-cyan-300/10 bg-cyan-300/[0.04] px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-200 shadow-sm transition ease-in-out duration-150 hover:bg-cyan-300/[0.08] focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-0 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
