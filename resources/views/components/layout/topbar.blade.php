@php
    $serverNow = now();
    $user = auth()->user();
    $theme = $user?->roleTheme() ?? [
        'topbar_shimmer' => 'via-gold/30',
        'topbar_dot' => 'bg-gold',
        'topbar_text' => 'text-gold-soft/80',
        'topbar_box' => 'border-gold/30 bg-gold/15 text-gold-soft',
        'role_label' => 'Superadmin',
        'role_badge_bg' => 'bg-gold/15 text-gold-soft border-gold/30',
    ];
@endphp

<header
    class="fixed h-20.25 inset-x-0 top-0 z-30 border-b border-white/8 bg-noir/80 px-4 py-3.5 backdrop-blur-xl lg:left-72 lg:px-7">
    {{-- Subtle top shimmer border --}}
    <div
        class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $theme['topbar_shimmer'] }} to-transparent">
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between w-full h-full">

        {{-- Left: Page Title & Welcome --}}
        <div class="space-y-0.5">
            <div class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-full {{ $theme['topbar_dot'] }} animate-pulse"></span>
                <span class="text-[10px] font-semibold uppercase tracking-[0.2em] {{ $theme['topbar_text'] }}">Portal Admin</span>
                <span class="ml-1 inline-flex items-center rounded-full border px-2 py-0.5 text-[9px] font-semibold uppercase tracking-wider {{ $theme['role_badge_bg'] }}">
                    {{ $theme['role_label'] }}
                </span>
            </div>
            <h1 class="text-lg font-semibold tracking-tight text-white lg:text-xl">
                @yield('title', 'SG Admin')
            </h1>
        </div>

        {{-- Right: Search & Quick Actions --}}
        <div class="flex items-center gap-3">
            {{-- User Badge & Logout --}}
            <div class="flex items-center gap-2.5">
                {{-- Server Time --}}
                <div class="flex items-center gap-2.5 rounded-xl border border-white/8 bg-white/4 px-3 py-1.5 text-xs">
                    <div
                        class="flex h-6 w-6 items-center justify-center rounded-lg border {{ $theme['topbar_box'] }}">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div class="leading-tight">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-smoke/70">
                            {{ config('app.timezone') }}</p>
                        <p class="font-medium text-champagne tracking-wide">
                            <span data-server-clock data-server-now="{{ $serverNow->toIso8601String() }}"
                                data-server-timezone="{{ config('app.timezone') }}">
                                {{ $serverNow->format('H:i:s') }}
                            </span>
                        </p>
                    </div>
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

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-server-clock]').forEach((element) => {
                if (!(element instanceof HTMLElement) || element.dataset.serverClockInitialized ===
                    'true') {
                    return;
                }

                const serverNow = element.dataset.serverNow;
                const timezone = element.dataset.serverTimezone || 'UTC';
                const serverTimestamp = Date.parse(serverNow ?? '');

                if (Number.isNaN(serverTimestamp)) {
                    return;
                }

                element.dataset.serverClockInitialized = 'true';

                const formatter = new Intl.DateTimeFormat('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                    timeZone: timezone,
                });

                const clientStartedAt = Date.now();

                const renderClock = () => {
                    const elapsed = Date.now() - clientStartedAt;
                    element.textContent = formatter.format(new Date(serverTimestamp + elapsed));
                };

                renderClock();
                window.setInterval(renderClock, 1000);
            });
        });
    </script>
@endPushOnce
