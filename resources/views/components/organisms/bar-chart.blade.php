@php
    $barItems = collect($items ?? [])->values();
    $max = max($barItems->max('value') ?? 0, 1);
@endphp
<div class="space-y-3.5">
    @forelse ($barItems as $item)
        @php $pct = round((($item['value'] ?? 0) / $max) * 100, 1); @endphp
        <div>
            <div class="mb-1.5 flex items-center justify-between gap-3">
                <span class="flex min-w-0 items-center gap-2 text-xs font-medium text-champagne">
                    <span class="h-2 w-2 shrink-0 rounded-full" style="background-color: {{ $item['color'] ?? '#78716c' }}"></span>
                    <span class="truncate">{{ $item['label'] }}</span>
                </span>
                <span class="shrink-0 text-xs font-semibold text-ivory">{{ $item['value'] }}</span>
            </div>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-black/6">
                <div class="h-full rounded-full transition-all duration-500"
                    style="width: {{ $pct }}%; background-color: {{ $item['color'] ?? '#78716c' }}"></div>
            </div>
        </div>
    @empty
        <p class="text-xs text-smoke">Belum ada data untuk ditampilkan.</p>
    @endforelse
</div>
