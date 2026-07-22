<header
    class="fixed h-20.25 inset-x-0 top-0 z-30 border-b border-white/8 bg-noir/80 px-4 py-3.5 backdrop-blur-xl lg:left-72 lg:px-7">
    {{-- Subtle top shimmer border --}}
    <div
        class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/30 to-transparent">
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between w-full h-full">

        {{-- Left: Page Title & Welcome --}}
        <div class="space-y-0.5">
            <div class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-full bg-gold animate-pulse"></span>
                <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gold-soft/80">Portal Admin</span>
            </div>
            <h1 class="text-lg font-semibold tracking-tight text-white lg:text-xl">
                @yield('title', 'SG Admin')
            </h1>
        </div>

        {{-- Right: Search & Quick Actions --}}
        <div class="flex items-center gap-3">
            {{-- User Badge & Logout --}}
            <div class="flex items-center gap-2.5">
                {{-- User Avatar Pill --}}
                <div class="flex items-center gap-2.5 rounded-xl border border-white/8 bg-white/4 px-3 py-1.5 text-xs">
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-lg border border-gold/30 bg-gold/15 font-bold text-[11px] text-gold-soft">
                        {{ mb_strtoupper(mb_substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="hidden font-medium text-champagne md:inline-block max-w-[120px] truncate">
                        {{ $user->name }}
                    </span>
                </div>

                {{-- Logout Button --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar dari akun"
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-red-500/25 bg-red-500/10 text-red-300 transition-all duration-200 hover:border-red-400/40 hover:bg-red-500/20 hover:text-red-100 cursor-pointer shadow-sm">
                        <i class="fa-solid fa-power-off text-xs"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</header>
