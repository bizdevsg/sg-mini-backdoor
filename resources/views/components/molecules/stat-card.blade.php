@php
    $theme = auth()->user()?->roleTheme() ?? [
        'badge_border' => 'border-gold/20',
        'badge_bg' => 'bg-gold/16',
    ];
@endphp
<div
    class="{{ cn(
        'flex items-center gap-4 rounded-2xl p-5 transition-transform duration-300 hover:-translate-y-1',
        $highlight ?? false
            ? cn('border shadow-lg shadow-black/10', $theme['badge_border'], $theme['badge_bg'])
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
