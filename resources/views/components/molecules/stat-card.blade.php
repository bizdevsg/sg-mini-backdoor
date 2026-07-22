<div
    class="{{ cn(
        'flex items-center gap-4 rounded-2xl p-5 transition-transform duration-300 hover:-translate-y-1',
        $highlight ?? false
            ? 'border border-gold/20 bg-gold/16 shadow-lg shadow-black/10 hover:shadow-[0_18px_40px_rgba(199,161,90,0.12)]'
            : 'border border-white/8 bg-white/4 hover:border-white/12 hover:bg-white/6',
        $class ?? null,
    ) }}">
    @include('components.atoms.icon-badge', [
        'icon' => $icon,
        'highlight' => $highlight ?? false,
        'class' => 'motion-safe:motion-preset-pop motion-safe:motion-delay-[120ms]',
    ])

    <div class="{{ cn('space-y-1') }}">
        @include('components.atoms.meta-label', ['text' => $title])

        <p class="{{ cn('text-3xl font-semibold text-white motion-safe:motion-preset-fade-sm') }}">{{ $value }}</p>
        <p class="{{ cn('text-sm text-smoke') }}">{{ $description }}</p>
    </div>
</div>
