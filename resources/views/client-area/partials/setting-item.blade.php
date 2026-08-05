@php
    $statusLabel = str_starts_with($item['label'], 'Tawk.to')
        ? ($item['enabled'] ? 'Tawk.to Aktif' : 'Tawk.to Nonaktif')
        : ($item['enabled'] ? 'Client Area Aktif' : 'Client Area Nonaktif');
@endphp

<li class="flex items-start justify-between gap-4 px-5 py-4">
    <div class="flex min-w-0 items-start gap-3">
        <div
            class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-black/10 bg-black/5 text-smoke/70">
            <i class="fa-solid {{ $item['icon'] }} text-xs"></i>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-medium text-ivory">{{ $item['label'] }}</p>
            <p class="mt-0.5 text-xs leading-relaxed text-smoke/60">{{ $item['description'] }}</p>
            <p class="mt-1 text-[11px] font-medium {{ $item['enabled'] ? 'text-emerald-700' : 'text-smoke/55' }}">
                {{ $statusLabel }}
            </p>
        </div>
    </div>

    <form action="{{ route('client-area.update') }}" method="POST" class="js-toggle-form shrink-0 pt-1">
        @csrf
        @method('PUT')
        <input type="hidden" name="target" value="{{ $item['key'] }}">
        <input type="hidden" name="enabled" value="{{ $item['enabled'] ? '0' : '1' }}">

        <button type="submit" data-enabled="{{ $item['enabled'] ? '1' : '0' }}"
            aria-pressed="{{ $item['enabled'] ? 'true' : 'false' }}"
            class="js-switch-track relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold/50 {{ $item['enabled'] ? 'bg-emerald-500' : 'bg-zinc-700' }}">
            <span class="sr-only">Toggle {{ $item['label'] }}</span>
            <span
                class="js-switch-knob inline-block h-4.5 w-4.5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $item['enabled'] ? 'translate-x-6' : 'translate-x-1' }}"
                style="height:1.125rem;width:1.125rem;"></span>
        </button>
    </form>
</li>
