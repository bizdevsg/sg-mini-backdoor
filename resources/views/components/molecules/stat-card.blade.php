<div
    class="{{ cn(
        'flex items-center gap-4 rounded-2xl p-5',
        $highlight ?? false
            ? 'border border-gold/20 bg-gold/16 shadow-lg shadow-black/10'
            : 'border border-white/8 bg-white/4',
        $class ?? null,
    ) }}">
    @include('components.atoms.icon-badge', [
        'icon' => $icon,
        'highlight' => $highlight ?? false,
    ])

    <div class="{{ cn('space-y-1') }}">
        @include('components.atoms.meta-label', ['text' => $title])

        <p class="{{ cn('text-3xl font-semibold text-white') }}">{{ $value }}</p>
        <p class="{{ cn('text-sm text-smoke') }}">{{ $description }}</p>
    </div>
</div>
