<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SG Admin') - Solid Gold Berjangka</title>
    <meta name="theme-color" content="#faf8f4">

    <link rel="icon" type="image/png" href="{{ asset('favicon/favicon-96x96.png') }}" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <meta name="apple-mobile-web-app-title" content="SG Admin">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body
    class="min-h-screen overflow-x-hidden bg-[radial-gradient(ellipse_120%_80%_at_50%_-20%,#fffdf9_0%,#faf8f4_55%,#f2ede3_100%)] text-champagne antialiased selection:bg-gold selection:text-obsidian">
    @php
        $user = auth()->user();
        $theme = $user?->roleTheme() ?? [
            'bg_glow_1' => 'bg-gold/5',
            'bg_glow_2' => 'bg-gold/4',
        ];
    @endphp

    {{-- Ambient Ambient Light Effects --}}
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <div class="absolute -left-40 -top-40 h-[500px] w-[500px] rounded-full {{ $theme['bg_glow_1'] }} blur-[120px]">
        </div>
        <div class="absolute -right-40 top-1/3 h-[600px] w-[600px] rounded-full {{ $theme['bg_glow_2'] }} blur-[140px]">
        </div>
    </div>

    <div class="relative z-10 min-h-screen lg:pl-72">
        @include('components.layout.sidebar')

        <div class="min-h-screen flex flex-col justify-between">
            <div>
                @include('components.layout.topbar')

                <main class="px-4 pb-8 pt-32 lg:px-7 lg:pb-10 lg:pt-32">
                    @yield('content')
                </main>
            </div>

            {{-- Footer info --}}
            <footer class="border-t border-black/6 px-4 py-4 text-center text-xs text-smoke/50 lg:px-7">
                &copy; {{ date('Y') }} PT Solid Gold Berjangka. All rights reserved.
            </footer>
        </div>
    </div>

    @php
        $undoNotification = session('undo_notification');
        $statusMessage = $undoNotification['message'] ?? session('status');
        $isErrorStatus = session('status_type') === 'error';
        $dismissDelay = (int) (($undoNotification['expires_in'] ?? 5) * 1000);
    @endphp

    @if ($statusMessage)
        <div class="pointer-events-none fixed inset-x-4 bottom-4 z-50 flex justify-end sm:inset-x-auto sm:right-6 sm:bottom-6"
            aria-live="polite" aria-atomic="true">
            <div data-auto-dismiss data-auto-dismiss-delay="{{ $dismissDelay }}" role="status"
                class="pointer-events-auto w-full max-w-md overflow-hidden rounded-2xl border {{ $isErrorStatus ? 'border-red-400/30 bg-[linear-gradient(135deg,_rgba(92,24,24,0.97)_0%,_rgba(54,16,16,0.99)_100%)] text-red-50 shadow-[0_20px_50px_rgba(239,68,68,0.22)]' : 'border-emerald-400/25 bg-[linear-gradient(135deg,_rgba(16,70,52,0.97)_0%,_rgba(10,42,31,0.99)_100%)] text-emerald-50 shadow-[0_20px_50px_rgba(16,185,129,0.25)]' }} backdrop-blur-md transition-all duration-300 motion-safe:motion-preset-slide-up-sm">
                <div class="flex items-start gap-3.5 px-4 py-4 sm:px-5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border {{ $isErrorStatus ? 'border-red-300/30 bg-red-400/15 text-red-200' : 'border-emerald-300/30 bg-emerald-400/15 text-emerald-300' }}">
                        <i class="fa-solid {{ $isErrorStatus ? 'fa-circle-exclamation' : 'fa-circle-check' }} text-base"></i>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.24em] {{ $isErrorStatus ? 'text-red-200/80' : 'text-emerald-300/80' }}">
                            {{ $isErrorStatus ? 'Tidak dapat diproses' : 'Pemberitahuan' }}
                        </p>
                        <p class="mt-0.5 text-xs font-medium leading-relaxed sm:text-sm">
                            {{ $statusMessage }}
                        </p>

                        @if ($undoNotification)
                            <form method="POST" action="{{ route('crud-undo.perform') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="token" value="{{ $undoNotification['token'] }}">
                                <button type="submit"
                                    class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white transition hover:border-white/35 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/40">
                                    <i class="fa-solid fa-rotate-left text-[10px]"></i>
                                    Undo
                                </button>
                            </form>
                        @endif
                    </div>

                    <button type="button" data-auto-dismiss-close
                        class="inline-flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-white/10 bg-white/5 text-white/75 transition hover:border-white/20 hover:bg-white/12 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/30"
                        aria-label="Tutup pemberitahuan">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>

                <div class="h-0.5 w-full bg-black/20">
                    <div data-auto-dismiss-progress
                        class="h-full origin-left {{ $isErrorStatus ? 'bg-gradient-to-r from-red-400 via-orange-300 to-red-200' : 'bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-200' }}"
                        style="transform: scaleX(1);"></div>
                </div>
            </div>
        </div>
    @endif

    @include('components.modals.confirm-submit')

    <script src="{{ asset('js/confirm-submit.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const initializeAutoDismiss = () => {
                document.querySelectorAll('[data-auto-dismiss]').forEach((element) => {
                    if (!(element instanceof HTMLElement) || element.dataset.autoDismissInitialized ===
                        'true') {
                        return;
                    }

                    element.dataset.autoDismissInitialized = 'true';

                    const delay = Number.parseInt(element.dataset.autoDismissDelay ?? '5000', 10);
                    const duration = Number.isNaN(delay) ? 5000 : delay;
                    const closeButton = element.querySelector('[data-auto-dismiss-close]');
                    const progressBar = element.querySelector('[data-auto-dismiss-progress]');
                    let dismissed = false;

                    if (progressBar instanceof HTMLElement) {
                        progressBar.style.transition = `transform ${duration}ms linear`;

                        window.requestAnimationFrame(() => {
                            progressBar.style.transform = 'scaleX(0)';
                        });
                    }

                    const dismiss = () => {
                        if (dismissed) {
                            return;
                        }

                        dismissed = true;
                        element.classList.add('pointer-events-none', '-translate-y-2', 'opacity-0');

                        window.setTimeout(() => {
                            element.remove();
                        }, 320);
                    };

                    if (closeButton instanceof HTMLElement) {
                        closeButton.addEventListener('click', dismiss);
                    }

                    window.setTimeout(dismiss, duration);
                });
            };

            const initializeCollapsibleDetails = () => {
                document.querySelectorAll('details[data-collapsible]').forEach((element) => {
                    if (!(element instanceof HTMLDetailsElement) || element.dataset
                        .collapsibleInitialized === 'true') {
                        return;
                    }

                    const trigger = element.querySelector('[data-collapsible-trigger]');
                    const content = element.querySelector('[data-collapsible-content]');

                    if (!(trigger instanceof HTMLElement) || !(content instanceof HTMLElement)) {
                        return;
                    }

                    element.dataset.collapsibleInitialized = 'true';

                    const syncState = (isOpen) => {
                        element.dataset.state = isOpen ? 'open' : 'closed';
                    };

                    if (element.open) {
                        syncState(true);
                        content.style.height = 'auto';
                        content.style.opacity = '1';
                    } else {
                        syncState(false);
                        content.style.height = '0px';
                        content.style.opacity = '0';
                    }

                    trigger.addEventListener('click', (event) => {
                        event.preventDefault();

                        if (element.dataset.collapsibleAnimating === 'true') {
                            return;
                        }

                        const shouldOpen = element.dataset.state !== 'open';
                        element.dataset.collapsibleAnimating = 'true';
                        syncState(shouldOpen);

                        if (shouldOpen) {
                            element.open = true;
                            content.style.height = '0px';
                            content.style.opacity = '0';

                            window.requestAnimationFrame(() => {
                                content.style.height = `${content.scrollHeight}px`;
                                content.style.opacity = '1';
                            });

                            return;
                        }

                        content.style.height = `${content.scrollHeight}px`;
                        content.style.opacity = '1';

                        window.requestAnimationFrame(() => {
                            content.style.height = '0px';
                            content.style.opacity = '0';
                        });
                    });

                    content.addEventListener('transitionend', (event) => {
                        if (event.target !== content || event.propertyName !== 'height') {
                            return;
                        }

                        const isOpen = element.dataset.state === 'open';

                        if (isOpen) {
                            content.style.height = 'auto';
                            content.style.opacity = '1';
                        } else {
                            element.open = false;
                            content.style.height = '0px';
                            content.style.opacity = '0';
                        }

                        element.dataset.collapsibleAnimating = 'false';
                    });
                });
            };

            initializeAutoDismiss();
            initializeCollapsibleDetails();
        });
    </script>
    @stack('scripts')
</body>

</html>
