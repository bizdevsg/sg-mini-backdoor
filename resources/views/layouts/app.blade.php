<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SG Admin')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        referrerpolicy="no-referrer">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen overflow-x-hidden bg-[linear-gradient(180deg,_#0b0d12_0%,_#10141b_100%)] text-champagne">
    @php($user = auth()->user())
    @php($produkMenuOpen = request()->routeIs('produk.*'))
    @php($produkSection = request()->route('section', 'spa'))
    @php($pengumumanMenuOpen = request()->routeIs('pengumuman.*'))
    @php($penghargaanMenuOpen = request()->routeIs('penghargaan.*'))
    @php($userManagementMenuOpen = request()->routeIs('user-management.*'))

    <div class="min-h-screen lg:pl-72">
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-72 overflow-y-auto border-r border-white/8 bg-noir lg:block">
            <div class="border-b border-white/6 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/8 bg-white/5 text-sm font-semibold text-white">
                        SG
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">SG Admin</p>
                        <p class="text-xs text-smoke">Admin panel</p>
                    </div>
                </div>
            </div>

            <nav class="space-y-1 px-4 py-6">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-white text-obsidian' : 'text-smoke hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-house w-5 text-center"></i>
                    Beranda
                </a>

                <div class="flex items-center gap-3 px-4 py-3">
                    <span class="text-[11px] font-semibold uppercase tracking-[0.24em] text-gold-soft/80">Konten</span>
                    <div class="h-px flex-1 bg-white/8"></div>
                </div>

                <details class="group rounded-lg border border-white/6 bg-white/3 px-4 py-3" @if ($produkMenuOpen) open @endif>
                    <summary
                        class="flex cursor-pointer list-none items-center justify-between gap-3 text-sm font-medium text-white [&::-webkit-details-marker]:hidden">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid fa-box-archive w-5 text-center text-gold-soft"></i>
                            Produk
                        </span>
                        <i class="fa-solid fa-chevron-down text-xs text-smoke transition-transform group-open:rotate-180"></i>
                    </summary>
                    <div class="mt-3 space-y-1 border-l border-white/8 pl-4">
                        <a href="{{ route('produk.index', ['section' => 'spa']) }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors hover:bg-white/5 hover:text-white {{ request()->routeIs('produk.*') && $produkSection === 'spa' ? 'bg-white/8 text-white' : 'text-smoke' }}">
                            <i class="fa-solid fa-globe w-4 text-center text-xs"></i>
                            Multilateral
                        </a>
                        <a href="{{ route('produk.index', ['section' => 'jfx']) }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors hover:bg-white/5 hover:text-white {{ request()->routeIs('produk.*') && $produkSection === 'jfx' ? 'bg-white/8 text-white' : 'text-smoke' }}">
                            <i class="fa-solid fa-handshake w-4 text-center text-xs"></i>
                            Bilateral
                        </a>
                    </div>
                </details>
                <a href="{{ route('pengumuman.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm transition-colors {{ $pengumumanMenuOpen ? 'bg-white text-obsidian' : 'text-smoke hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-bullhorn w-5 text-center"></i>
                    Pengumuman
                </a>
                <a href="{{ route('penghargaan.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm transition-colors {{ $penghargaanMenuOpen ? 'bg-white text-obsidian' : 'text-smoke hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-award w-5 text-center"></i>
                    Penghargaan
                </a>
                <a href="#"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-smoke transition-colors hover:bg-white/5 hover:text-white">
                    <i class="fa-solid fa-building w-5 text-center"></i>
                    Profil Perusahaan
                </a>

                <div class="flex items-center gap-3 px-4 py-3">
                    <span class="text-[11px] font-semibold uppercase tracking-[0.24em] text-gold-soft/80">Sistem</span>
                    <div class="h-px flex-1 bg-white/8"></div>
                </div>

                <a href="{{ route('user-management.index') }}"
                    class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm transition-colors {{ $userManagementMenuOpen ? 'bg-white text-obsidian' : 'text-smoke hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center"></i>
                    User Management
                </a>
            </nav>
        </aside>

        <div class="min-h-screen">
            <header class="fixed inset-x-0 top-0 z-30 border-b border-white/8 bg-noir/95 px-4 py-4 backdrop-blur lg:left-72 lg:px-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl font-semibold text-white">@yield('title', 'SG Admin')</h1>
                        <p class="text-sm text-smoke">Selamat datang kembali, {{ $user->name }}</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <label class="relative block sm:w-72">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-smoke">
                                <i class="fa-solid fa-magnifying-glass text-sm"></i>
                            </span>
                            <input type="text" placeholder="Cari..."
                                class="w-full rounded-lg border border-white/8 bg-white/4 py-2.5 pl-11 pr-4 text-sm text-champagne placeholder:text-smoke focus:border-gold/20 focus:outline-none focus:ring-2 focus:ring-gold/10">
                        </label>

                        <div class="flex items-center gap-3">
                            <div class="hidden items-center gap-3 sm:flex">
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-sm font-semibold text-obsidian">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium text-white">{{ $user->name }}</p>
                                    <p class="truncate text-xs text-smoke">{{ $user->email }}</p>
                                </div>
                            </div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="rounded-lg border border-white/8 bg-white/4 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-white/8">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="px-4 pb-4 pt-36 lg:px-6 lg:pb-6 lg:pt-28">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
