<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'SG Admin') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        referrerpolicy="no-referrer">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-obsidian text-champagne">
    <div class="min-h-screen border-b border-white/5 bg-[linear-gradient(180deg,_#0b0d12_0%,_#0f131a_100%)]">
        <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-6 lg:px-10">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-white/5 text-sm font-semibold text-white">
                    SG
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">SG Admin</p>
                    <p class="text-xs text-smoke">Admin workspace</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        Login
                    </a>
                @endauth
            </div>
        </header>

        <main class="mx-auto flex w-full max-w-7xl flex-col gap-16 px-6 pb-16 pt-10 lg:px-10 lg:pb-24">
            <section class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div class="space-y-8">
                    <div class="inline-flex rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                        Minimal enterprise interface
                    </div>

                    <div class="space-y-4">
                        <h1 class="max-w-4xl text-5xl font-semibold tracking-[-0.04em] text-white sm:text-6xl lg:text-7xl">
                            Panel admin yang bersih, tegas, dan mudah dipakai.
                        </h1>
                        <p class="max-w-2xl text-base leading-8 text-smoke sm:text-lg">
                            SG Admin dirancang untuk kebutuhan operasional harian: memantau pengguna, konten, antrian review,
                            dan aktivitas sistem dalam satu workspace yang ringkas dan jelas.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                                Masuk ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                                Login Admin
                            </a>
                        @endauth
                        <a href="#overview"
                            class="inline-flex items-center justify-center rounded-lg border border-white/10 bg-white/5 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                            Lihat Overview
                        </a>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-white/8 bg-white/4 p-5">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Monitoring</p>
                            <p class="mt-3 text-3xl font-semibold text-white">24/7</p>
                            <p class="mt-2 text-sm leading-6 text-smoke">Visibilitas status dan performa sistem setiap saat.</p>
                        </div>
                        <div class="rounded-xl border border-white/8 bg-white/4 p-5">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Publishing</p>
                            <p class="mt-3 text-3xl font-semibold text-white">567</p>
                            <p class="mt-2 text-sm leading-6 text-smoke">Konten aktif yang bisa dikelola dan dipantau cepat.</p>
                        </div>
                        <div class="rounded-xl border border-white/8 bg-white/4 p-5">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">User Growth</p>
                            <p class="mt-3 text-3xl font-semibold text-white">+12%</p>
                            <p class="mt-2 text-sm leading-6 text-smoke">Pertumbuhan pengguna yang tetap terkontrol.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/8 bg-[linear-gradient(180deg,_rgba(255,255,255,0.04)_0%,_rgba(255,255,255,0.02)_100%)] p-5 shadow-2xl shadow-black/20">
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Workspace Snapshot</p>
                                <h2 class="mt-2 text-2xl font-semibold text-white">Admin overview</h2>
                                <p class="mt-2 text-sm leading-6 text-smoke">Ringkasan operasional yang mudah dipindai dalam beberapa detik.</p>
                            </div>
                            <span class="rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                                Live
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-xl border border-white/8 bg-white/4 p-4">
                                <p class="text-sm text-smoke">Published content</p>
                                <p class="mt-2 text-4xl font-semibold text-white">567</p>
                                <p class="mt-2 text-sm text-gold-soft">+8% bulan lalu</p>
                            </div>
                            <div class="rounded-xl border border-white/8 bg-white/4 p-4">
                                <p class="text-sm text-smoke">Review queue</p>
                                <p class="mt-2 text-4xl font-semibold text-white">18</p>
                                <p class="mt-2 text-sm text-smoke">Perlu tindak lanjut hari ini</p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-xl border border-white/8 bg-white/4 p-4">
                            <div class="mb-3 flex items-center justify-between text-sm">
                                <span class="text-smoke">Publishing momentum</span>
                                <span class="text-gold-soft">74%</span>
                            </div>
                            <div class="h-2 rounded-full bg-white/8">
                                <div class="h-2 w-[74%] rounded-full bg-gold"></div>
                            </div>
                        </div>

                        <div class="mt-5 space-y-3">
                            <div class="flex items-center justify-between rounded-xl border border-white/6 bg-white/4 px-4 py-3">
                                <span class="text-sm text-champagne">Pengguna baru berhasil dibuat</span>
                                <span class="text-xs text-smoke">2 menit</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-white/6 bg-white/4 px-4 py-3">
                                <span class="text-sm text-champagne">Post diperbarui dan tersimpan</span>
                                <span class="text-xs text-smoke">15 menit</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-white/6 bg-white/4 px-4 py-3">
                                <span class="text-sm text-champagne">Komentar baru menunggu review</span>
                                <span class="text-xs text-smoke">1 jam</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="overview" class="grid gap-5 lg:grid-cols-3">
                <article class="rounded-xl border border-white/8 bg-white/4 p-6">
                    <h2 class="text-xl font-semibold text-white">Cepat dipahami</h2>
                    <p class="mt-3 text-sm leading-7 text-smoke">
                        Informasi utama disusun dengan hierarki yang jelas supaya operator langsung tahu apa yang perlu dikerjakan.
                    </p>
                </article>
                <article class="rounded-xl border border-white/8 bg-white/4 p-6">
                    <h2 class="text-xl font-semibold text-white">Tenang dipakai</h2>
                    <p class="mt-3 text-sm leading-7 text-smoke">
                        Kontras tetap kuat, tapi visualnya lebih kalem dan tidak terasa dekoratif berlebihan.
                    </p>
                </article>
                <article class="rounded-xl border border-white/8 bg-white/4 p-6">
                    <h2 class="text-xl font-semibold text-white">Siap berkembang</h2>
                    <p class="mt-3 text-sm leading-7 text-smoke">
                        Struktur halamannya modular, jadi gampang diteruskan ke modul admin lain tanpa ganti bahasa desain lagi.
                    </p>
                </article>
            </section>
        </main>
    </div>
</body>

</html>
