<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SG Admin') - Solid Gold Berjangka</title>
    <meta name="theme-color" content="#15110d">

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

<body class="min-h-screen overflow-x-hidden bg-[radial-gradient(ellipse_120%_80%_at_50%_-20%,#2c2217_0%,#15110d_55%,#0d0a08_100%)] text-champagne antialiased selection:bg-gold selection:text-obsidian">
    @php($user = auth()->user())

    {{-- Ambient Ambient Light Effects --}}
    <div class="pointer-events-none fixed inset-0 z-0 overflow-hidden">
        <div class="absolute -left-40 -top-40 h-[500px] w-[500px] rounded-full bg-gold/5 blur-[120px]"></div>
        <div class="absolute -right-40 top-1/3 h-[600px] w-[600px] rounded-full bg-gold/4 blur-[140px]"></div>
    </div>

    <div class="relative z-10 min-h-screen lg:pl-72">
        @include('components.layout.sidebar')

        <div class="min-h-screen flex flex-col justify-between">
            <div>
                @include('components.layout.topbar')

                <main class="px-4 pb-8 pt-32 lg:px-7 lg:pb-10 lg:pt-24">
                    {{-- Flash Notification Status --}}
                    @if (session('status'))
                        <div
                            data-auto-dismiss
                            data-auto-dismiss-delay="5000"
                            role="status"
                            class="group relative mb-6 overflow-hidden rounded-2xl border border-emerald-400/25 bg-[linear-gradient(135deg,_rgba(16,70,52,0.95)_0%,_rgba(10,42,31,0.98)_100%)] text-emerald-50 shadow-[0_20px_50px_rgba(16,185,129,0.25)] backdrop-blur-md transition-all duration-300 motion-safe:motion-preset-slide-down-sm"
                        >
                            <div class="flex items-center justify-between gap-4 px-5 py-4">
                                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-emerald-300/30 bg-emerald-400/15 text-emerald-300 shadow-sm">
                                        <i class="fa-solid fa-circle-check text-base"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-emerald-300/80">
                                            Berhasil
                                        </p>
                                        <p class="mt-0.5 text-xs sm:text-sm font-medium leading-relaxed text-emerald-50">
                                            {{ session('status') }}
                                        </p>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    data-auto-dismiss-close
                                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-emerald-200/80 transition-all hover:border-white/20 hover:bg-white/12 hover:text-white focus:outline-none cursor-pointer"
                                    aria-label="Tutup notifikasi"
                                >
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            </div>

                            <div class="h-0.5 w-full bg-black/20">
                                <div
                                    data-auto-dismiss-progress
                                    class="h-full origin-left bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-200"
                                    style="transform: scaleX(1);"
                                ></div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>

            {{-- Footer info --}}
            <footer class="border-t border-white/6 px-4 py-4 text-center text-xs text-smoke/50 lg:px-7">
                &copy; {{ date('Y') }} PT Solid Gold Berjangka. All rights reserved.
            </footer>
        </div>
    </div>

    @include('components.modals.confirm-submit')

    <script src="{{ asset('js/confirm-submit.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const initializeAutoDismiss = () => {
                document.querySelectorAll('[data-auto-dismiss]').forEach((element) => {
                    if (!(element instanceof HTMLElement) || element.dataset.autoDismissInitialized === 'true') {
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
                    if (!(element instanceof HTMLDetailsElement) || element.dataset.collapsibleInitialized === 'true') {
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
