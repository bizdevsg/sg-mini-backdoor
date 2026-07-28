<div id="{{ $section['key'] }}" class="rounded-lg border border-white/10 bg-white/[0.02]">
    <div class="border-b border-white/10 px-5 py-4">
        <h2 class="text-sm font-semibold text-white">{{ $section['title'] }}</h2>
        <p class="mt-0.5 text-xs text-smoke/60">{{ $section['description'] }}</p>
    </div>

    <ul class="divide-y divide-white/8">
        @foreach ($section['items'] as $item)
            @include('client-area.partials.setting-item', ['item' => $item])
        @endforeach
    </ul>
</div>
