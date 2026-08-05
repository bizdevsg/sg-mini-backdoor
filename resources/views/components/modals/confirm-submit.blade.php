@php
    $theme = auth()->user()?->roleTheme() ?? [
        'text_subtle' => 'text-gold-soft/75',
    ];
@endphp
<div data-confirm-modal
    class="fixed inset-0 z-[120] hidden items-center justify-center p-4"
    aria-hidden="true">
    <div data-confirm-backdrop class="absolute inset-0 bg-black/75 backdrop-blur-sm"></div>

    <div
        class="relative w-full max-w-md overflow-hidden rounded-3xl border border-black/10 bg-[linear-gradient(180deg,_#faf8f4_0%,_#f2ede3_100%)] text-champagne shadow-[0_30px_80px_rgba(0,0,0,0.25)]">
        <div class="border-b border-black/8 px-6 py-5">
            <p class="text-xs font-medium uppercase tracking-[0.22em] {{ $theme['text_subtle'] }}">Konfirmasi aksi</p>
            <h3 data-confirm-modal-title class="mt-2 text-2xl font-semibold tracking-[-0.03em] text-ivory">
                Lanjutkan aksi ini?
            </h3>
        </div>

        <div class="space-y-6 px-6 py-6">
            <p data-confirm-modal-message class="text-sm leading-7 text-smoke">
                Pastikan tindakan ini memang ingin dilakukan.
            </p>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" data-confirm-cancel
                    class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-black/5 px-5 py-3 text-sm font-medium text-ivory transition-colors hover:bg-black/10">
                    Batal
                </button>
                <button type="button" data-confirm-accept
                    class="inline-flex items-center justify-center rounded-xl bg-obsidian px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-champagne">
                    Ya, lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>
