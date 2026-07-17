<div class="{{ cn('flex items-center justify-between border-b border-white/8 px-6 py-5') }}">
    <div>
        @include('components.atoms.meta-label', ['text' => $eyebrow])

        <h2 class="{{ cn('mt-2 text-2xl font-semibold tracking-[-0.03em] text-white') }}">
            {{ $title }}
        </h2>
    </div>

    @if (!empty($actions))
        <div class="flex items-center gap-4">
            @foreach ($actions as $action)
                @include('components.atoms.action-link', [
                    'href' => $action['href'],
                    'label' => $action['label'],
                ])
            @endforeach
        </div>
    @endif
</div>
