<article
    class="flex h-full flex-col rounded-2xl border border-white/8 bg-onyx p-5 transition-colors duration-200 hover:border-gold/20 hover:bg-white/5">
    <div class="flex items-start justify-between gap-3">
        <div class="flex min-w-0 items-center gap-3">
            <div
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-sm font-semibold text-obsidian">
                {{ mb_strtoupper(mb_substr($massage->nama ?: '?', 0, 1)) }}
            </div>

            <div class="min-w-0">
                <p class="truncate font-medium text-white">{{ $massage->nama }}</p>
                <p class="mt-1 truncate text-xs uppercase tracking-[0.18em] text-smoke">
                    {{ $massage->id_laporan }}
                </p>
            </div>
        </div>

        <span
            class="shrink-0 rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
            {{ $massage->created_at?->format('d M Y') ?? '-' }}
        </span>
    </div>

    <div class="mt-5">
        <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Subjek</p>
        <h3 class="mt-2 text-lg font-semibold text-white">{{ $massage->subjek }}</h3>
        <p class="mt-3 text-sm leading-7 line-clamp-2 text-smoke">
            {{ $massage->massage }}
        </p>
    </div>

    <div class="mt-5 grid gap-3 sm:grid-cols-2">
        <div class="rounded-xl border border-white/8 bg-white/4 p-3">
            <p class="text-xs font-medium uppercase tracking-[0.16em] text-smoke">Email</p>
            <p class="mt-2 break-all text-sm text-white">{{ $massage->email }}</p>
        </div>

        <div class="rounded-xl border border-white/8 bg-white/4 p-3">
            <p class="text-xs font-medium uppercase tracking-[0.16em] text-smoke">No. Telepon</p>
            <p class="mt-2 text-sm text-white">{{ $massage->no_tlp }}</p>
        </div>
    </div>

    <div class="mt-5 flex items-center justify-between gap-3 border-t border-white/8 pt-4">
        <div class="text-sm text-smoke">
            <span>Dikirim</span>
            <span class="ml-2 text-white">{{ $massage->created_at?->format('d M Y, H:i') ?? '-' }}</span>
        </div>

        <a href="{{ route('massages.show', $massage->id) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
            Detail
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>
</article>
