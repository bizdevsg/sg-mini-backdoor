<div
    class="{{ cn(
        'flex h-12 w-12 items-center justify-center rounded-2xl text-gold-soft',
        $highlight ?? false
            ? 'border border-gold/35 bg-gold/18'
            : 'border border-gold/25 bg-gold/12',
        $class ?? null,
    ) }}">
    <i class="{{ $icon }}"></i>
</div>
