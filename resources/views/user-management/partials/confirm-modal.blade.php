<dialog
    data-confirm-modal
    class="w-full max-w-md rounded-2xl border border-white/10 bg-[#1b1511] p-0 text-champagne shadow-2xl backdrop:bg-black/70">
    <div class="space-y-6 p-6">
        <div class="space-y-2">
            <p class="text-xs font-medium uppercase tracking-[0.18em] text-gold-soft/80">Konfirmasi aksi</p>
            <h3 data-confirm-modal-title class="text-2xl font-semibold tracking-[-0.03em] text-white">
                Lanjutkan aksi ini?
            </h3>
            <p data-confirm-modal-message class="text-sm leading-7 text-smoke">
                Pastikan tindakan ini memang ingin dilakukan.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button type="button" data-confirm-cancel
                class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-white/5 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                Batal
            </button>
            <button type="button" data-confirm-accept
                class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                Ya, lanjutkan
            </button>
        </div>
    </div>
</dialog>
