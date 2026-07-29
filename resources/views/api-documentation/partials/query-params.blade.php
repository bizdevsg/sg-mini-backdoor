<div>
    <div class="mb-5 overflow-hidden rounded-2xl border border-white/8 bg-white/3">
        <div class="border-b border-white/6 px-5 py-4">
            <p class="text-sm font-semibold text-white">Cara Memakai Query Parameter</p>
            <p class="mt-1 text-xs leading-5 text-smoke">
                Query parameter dipakai untuk mengubah hasil list endpoint tanpa mengubah path utama. Parameter ini
                diletakkan setelah tanda <span class="font-mono text-champagne">?</span> pada URL dan bisa digabung
                sesuai kebutuhan endpoint.
            </p>
        </div>
        <div class="px-5 py-4">
            <div class="rounded-xl border border-white/8 bg-black/20 px-4 py-3 font-mono text-[11px] text-champagne">
                {{ $apiBaseUrl }}/ebook?page=1&per_page=10&search=trading&category=edukasi
            </div>
            <p class="mt-3 text-xs leading-5 text-smoke">
                Contoh di atas menunjukkan satu endpoint list yang memakai pagination, search, dan filter kategori
                sekaligus.
            </p>
        </div>
    </div>

    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-purple-500/25 bg-purple-500/15 text-xs font-bold text-purple-300">
            <i class="fa-solid fa-sliders text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-white">Query Parameters</h2>
        <div class="h-px flex-1 bg-white/6"></div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
        <div class="grid divide-y divide-white/6 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <div class="px-5 py-4">
                <p class="mb-1 font-mono text-xs text-champagne">?page=1&per_page=10</p>
                <p class="text-[11px] leading-5 text-smoke">Pagination - halaman dan jumlah item per halaman.</p>
                <p class="mt-2 text-[11px] leading-5 text-smoke/80">
                    Pakai saat endpoint menampilkan banyak data dan frontend perlu membagi hasil per halaman.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="mb-1 font-mono text-xs text-champagne">?search=keyword</p>
                <p class="text-[11px] leading-5 text-smoke">Pencarian full-text pada list resource.</p>
                <p class="mt-2 text-[11px] leading-5 text-smoke/80">
                    Biasanya dipakai untuk fitur search bar pada daftar berita, signal, atau ebook.
                </p>
            </div>
            <div class="px-5 py-4">
                <p class="mb-1 font-mono text-xs text-champagne">?category={slug}</p>
                <p class="text-[11px] leading-5 text-smoke">Filter resource berdasarkan kategori / slug.</p>
                <p class="mt-2 text-[11px] leading-5 text-smoke/80">
                    Gunakan slug kategori yang valid agar hasil filter sesuai dengan data master kategori.
                </p>
            </div>
        </div>

        <div class="border-t border-white/6 bg-white/3 px-5 py-4">
            <div class="flex items-start gap-3">
                <div
                    class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg border border-orange-400/20 bg-orange-400/10">
                    <i class="fa-solid fa-box-open text-[11px] text-orange-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Postman</p>
                    <p class="mt-1 text-xs leading-5 text-smoke">
                        Isi header <span class="font-mono text-champagne">{{ $apiKeyHeader }}</span> dengan API key aktif.
                        Untuk web tambahkan <span class="font-mono text-champagne">Origin: {{ $webOriginExample }}</span>.
                        Untuk mobile app tambahkan
                        <span class="font-mono text-champagne">{{ $mobileClientHeader }}: {{ $mobileClientValue }}</span>.
                        Query params bisa diisi di tab <span class="font-medium text-champagne">Params</span>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
            <p class="text-sm font-semibold text-white">Praktik yang Disarankan</p>
            <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                <p>Gunakan default pagination di frontend agar request tidak terlalu besar.</p>
                <p>URL encode keyword pencarian kalau ada spasi atau karakter khusus.</p>
                <p>Gabungkan filter hanya bila endpoint memang mendukung kombinasi tersebut.</p>
                <p>Simpan state query di URL frontend agar halaman bisa di-refresh tanpa kehilangan filter.</p>
            </div>
        </div>

        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4">
            <p class="text-sm font-semibold text-white">Hal yang Perlu Diperhatikan</p>
            <div class="mt-3 space-y-3 text-xs leading-5 text-smoke">
                <p>Tidak semua endpoint memakai seluruh query parameter yang sama.</p>
                <p>Endpoint detail umumnya tidak memerlukan query parameter list.</p>
                <p>Kalau hasil kosong, cek ulang slug kategori atau keyword yang dikirim.</p>
                <p>Pastikan parameter dikirim sebagai query string, bukan dimasukkan ke header request.</p>
            </div>
        </div>
    </div>
</div>
