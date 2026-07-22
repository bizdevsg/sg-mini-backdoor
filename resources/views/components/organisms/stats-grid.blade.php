@php
    $motionDelayClasses = [
        'motion-safe:motion-delay-0',
        'motion-safe:motion-delay-[80ms]',
        'motion-safe:motion-delay-[160ms]',
        'motion-safe:motion-delay-[240ms]',
        'motion-safe:motion-delay-[320ms]',
        'motion-safe:motion-delay-[400ms]',
    ];
@endphp

<div class="{{ cn('grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3') }}">
    @foreach ($stats as $stat)
        @include('components.molecules.stat-card', [
            'icon' => $stat['icon'],
            'title' => $stat['title'],
            'value' => $stat['value'],
            'description' => $stat['description'],
            'highlight' => $stat['highlight'] ?? false,
            'class' => cn(
                'motion-safe:motion-preset-slide-up-sm',
                $motionDelayClasses[$loop->index] ?? 'motion-safe:motion-delay-[480ms]',
            ),
        ])
    @endforeach
</div>
