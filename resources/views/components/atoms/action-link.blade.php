@php
    $theme = auth()->user()?->roleTheme() ?? [
        'btn_primary' => 'bg-gold text-obsidian hover:bg-gold-soft',
    ];
@endphp
<a href="{{ $href }}"
    class="{{ cn('rounded px-3 py-1 text-sm font-semibold transition-all duration-300', $theme['btn_primary'], $class ?? null) }}">
    {{ $label }}
</a>
