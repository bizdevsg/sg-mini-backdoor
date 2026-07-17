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
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-red-500/20 cursor-pointer">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
