@php
    $theme = auth()->user()?->roleTheme() ?? [
        'badge_border' => 'border-gold/25',
        'badge_bg' => 'bg-gold/12',
        'text' => 'text-gold-soft',
    ];
@endphp
<div
    class="{{ cn(
        'flex h-12 w-12 items-center justify-center rounded-2xl border',
        $theme['badge_border'],
        $theme['badge_bg'],
        $theme['text'],
        $class ?? null,
    ) }}">
    <i class="{{ $icon }}"></i>
</div>
