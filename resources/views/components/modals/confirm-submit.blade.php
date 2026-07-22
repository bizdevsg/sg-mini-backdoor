<div data-confirm-modal
    class="fixed inset-0 z-[120] hidden items-center justify-center p-4"
    aria-hidden="true">
    <div data-confirm-backdrop class="absolute inset-0 bg-black/75 backdrop-blur-sm"></div>

    <div
        class="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/10 bg-[linear-gradient(180deg,_rgba(37,28,21,0.98)_0%,_rgba(22,17,13,0.98)_100%)] text-champagne shadow-[0_30px_80px_rgba(0,0,0,0.45)]">
        <div class="border-b border-white/8 px-6 py-5">
            <p class="text-xs font-medium uppercase tracking-[0.22em] text-gold-soft/75">Konfirmasi aksi</p>
            <h3 data-confirm-modal-title class="mt-2 text-2xl font-semibold tracking-[-0.03em] text-white">
                Lanjutkan aksi ini?
            </h3>
        </div>

        <div class="space-y-6 px-6 py-6">
            <p data-confirm-modal-message class="text-sm leading-7 text-smoke">
                Pastikan tindakan ini memang ingin dilakukan.
            </p>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" data-confirm-cancel
                    class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                    Batal
                </button>
                <button type="button" data-confirm-accept
                    class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                    Ya, lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>
