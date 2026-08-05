@php
    $navLabel = 'Documentation Pages';
@endphp

<div class="hidden xl:block">
    <div class="sticky top-25 space-y-1">
        <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/50">{{ $navLabel }}</p>

        @foreach ($docSections as $section)
            <a href="{{ route('api-documentation.section', ['section' => $section['key']]) }}"
                class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm transition-colors hover:bg-black/5 hover:text-ivory {{ $currentDocSection['key'] === $section['key'] ? 'bg-black/5 text-ivory' : 'text-smoke/70' }}">
                <span class="h-1.5 w-1.5 shrink-0 rounded-full {{ $section['dot_class'] }}"></span>
                {{ $section['short_label'] }}
            </a>
        @endforeach
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-black/8 bg-black/3 xl:hidden">
    <div class="border-b border-black/6 px-4 py-3">
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/50">{{ $navLabel }}</p>
    </div>
    <div class="flex gap-2 overflow-x-auto px-4 py-3">
        @foreach ($docSections as $section)
            <a href="{{ route('api-documentation.section', ['section' => $section['key']]) }}"
                class="inline-flex shrink-0 items-center gap-2 rounded-full border px-3 py-2 text-xs font-medium transition-colors {{ $currentDocSection['key'] === $section['key'] ? 'border-gold/30 bg-gold/10 text-gold-soft' : 'border-black/10 bg-black/5 text-smoke/80 hover:border-black/20 hover:text-ivory' }}">
                <span class="h-1.5 w-1.5 rounded-full {{ $section['dot_class'] }}"></span>
                {{ $section['short_label'] }}
            </a>
        @endforeach
    </div>
</div>
