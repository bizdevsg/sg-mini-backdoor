<div
    class="{{ cn(
        'rounded-2xl border border-white/8 bg-white/4 p-6',
        ! empty($action ?? null) ? 'flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between' : 'flex flex-col gap-3',
    ) }}">
    <div>
        @include('components.atoms.meta-label', ['text' => $eyebrow])

        <h2 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-white">{{ $title }}</h2>

        @if (! empty($description ?? null))
            <p class="mt-2 max-w-2xl text-sm leading-7 text-smoke">{{ $description }}</p>
        @endif
    </div>

    @if (! empty($action ?? null))
        <a href="{{ $action['href'] }}"
            class="{{ $action['class'] }}">
            @if (! empty($action['icon'] ?? null))
                <i class="{{ $action['icon'] }}"></i>
            @endif
            {{ $action['label'] }}
        </a>
    @endif
</div>
