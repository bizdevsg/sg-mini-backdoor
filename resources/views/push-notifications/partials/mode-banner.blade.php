@if ($isDryRun)
    <div class="flex items-start gap-3 rounded-2xl border border-blue-500/30 bg-blue-500/10 px-5 py-4 text-sm">
        <i class="fa-solid fa-flask mt-0.5 text-blue-700"></i>
        <div>
            <p class="font-semibold text-blue-700">Mode uji (dry run) aktif</p>
            <p class="mt-1 text-xs leading-5 text-smoke">
                Request dikirim ke <span class="font-mono text-champagne/80">{{ $targetHost }}</span> dengan
                <span class="font-mono text-champagne/80">?logOnly=true</span>: API key dan payload diperiksa server,
                tetapi notifikasi <span class="font-semibold text-ivory">tidak</span> sampai ke HP pengguna.
                Ubah <span class="font-mono text-champagne/80">MINI_MICRO_DRY_RUN=false</span> di .env untuk broadcast sungguhan.
            </p>
        </div>
    </div>
@else
    <div class="flex items-start gap-3 rounded-2xl border border-red-500/20 bg-red-500/5 px-5 py-4 text-sm">
        <i class="fa-solid fa-tower-broadcast mt-0.5 text-red-700"></i>
        <div>
            <p class="font-semibold text-red-700">Mode LIVE aktif</p>
            <p class="mt-1 text-xs leading-5 text-smoke">
                Broadcast dikirim ke <span class="font-mono text-champagne/80">{{ $targetHost }}</span> dan akan diterima
                <span class="font-semibold text-ivory">semua pengguna aplikasi</span>. Notifikasi yang sudah terkirim tidak bisa dibatalkan.
            </p>
        </div>
    </div>
@endif
