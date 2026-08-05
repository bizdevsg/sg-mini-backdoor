@php
    $size = $size ?? 148;
    $thickness = $thickness ?? 16;
    $gapDeg = $gapDeg ?? 3;
    $radius = ($size - $thickness) / 2;
    $circumference = 2 * M_PI * $radius;

    $visibleSegments = collect($segments ?? [])->filter(fn($s) => ($s['value'] ?? 0) > 0)->values();
    $total = max($visibleSegments->sum('value'), 1);
    $cumulativeDeg = 0;
    $segmentCount = $visibleSegments->count();
@endphp
<div class="relative shrink-0" style="width: {{ $size }}px; height: {{ $size }}px;">
    <svg viewBox="0 0 {{ $size }} {{ $size }}" class="h-full w-full -rotate-90">
        <circle cx="{{ $size / 2 }}" cy="{{ $size / 2 }}" r="{{ $radius }}" fill="none"
            class="text-black/6" stroke="currentColor" stroke-width="{{ $thickness }}" />

        @foreach ($visibleSegments as $segment)
            @php
                $fraction = $segment['value'] / $total;
                $segDeg = $fraction * 360;
                $drawDeg = max($segDeg - ($segmentCount > 1 ? $gapDeg : 0), 0.001);
                $dash = ($drawDeg / 360) * $circumference;
                $offset = -($cumulativeDeg / 360) * $circumference;
                $cumulativeDeg += $segDeg;
            @endphp
            <circle cx="{{ $size / 2 }}" cy="{{ $size / 2 }}" r="{{ $radius }}" fill="none"
                stroke="{{ $segment['color'] }}" stroke-width="{{ $thickness }}"
                stroke-dasharray="{{ $dash }} {{ max($circumference - $dash, 0) }}"
                stroke-dashoffset="{{ $offset }}" stroke-linecap="round">
                <title>{{ $segment['label'] }}: {{ $segment['value'] }}</title>
            </circle>
        @endforeach
    </svg>

    @if (!empty($centerValue) || !empty($centerLabel))
        <div class="absolute inset-0 flex flex-col items-center justify-center px-3 text-center">
            @if (!empty($centerValue))
                <p class="text-2xl font-semibold leading-none text-ivory">{{ $centerValue }}</p>
            @endif
            @if (!empty($centerLabel))
                <p class="mt-1.5 text-[9px] font-semibold uppercase tracking-[0.16em] text-smoke">{{ $centerLabel }}</p>
            @endif
        </div>
    @endif
</div>
