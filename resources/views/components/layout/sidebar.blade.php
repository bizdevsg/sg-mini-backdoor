@php
    $user = auth()->user();
    $produkSection = request()->route('section', 'spa');

    $navigationSections = [
        [
            'heading' => 'Dashboard',
            'items' => [
                [
                    'label' => 'Beranda',
                    'icon' => 'fa-solid fa-house',
                    'href' => route('dashboard'),
                    'active' => request()->routeIs('dashboard'),
                ],
            ],
        ],
        [
            'heading' => 'Konten',
            'items' => [
                [
                    'label' => 'Produk',
                    'icon' => 'fa-solid fa-box-archive',
                    'open' => request()->routeIs('produk.*'),
                    'children' => [
                        [
                            'label' => 'Multilateral',
                            'icon' => 'fa-solid fa-globe',
                            'href' => route('produk.index', ['section' => 'jfx']),
                            'active' => request()->routeIs('produk.*') && $produkSection === 'jfx',
                        ],
                        [
                            'label' => 'Bilateral',
                            'icon' => 'fa-solid fa-handshake',
                            'href' => route('produk.index', ['section' => 'spa']),
                            'active' => request()->routeIs('produk.*') && $produkSection === 'spa',
                        ],
                    ],
                ],
                [
                    'label' => 'Banner',
                    'icon' => 'fa-solid fa-image',
                    'href' => route('banner.index'),
                    'active' => request()->routeIs('banner.*'),
                ],
                [
                    'label' => 'Pengumuman',
                    'icon' => 'fa-solid fa-bullhorn',
                    'href' => route('pengumuman.index'),
                    'active' => request()->routeIs('pengumuman.*'),
                ],
                [
                    'label' => 'Ebook',
                    'icon' => 'fa-solid fa-book',
                    'href' => route('ebook-categories.index'),
                    'active' => request()->routeIs('ebook.*') || request()->routeIs('ebook-categories.*'),
                ],
                [
                    'label' => 'Penghargaan',
                    'icon' => 'fa-solid fa-award',
                    'href' => route('penghargaan.index'),
                    'active' => request()->routeIs('penghargaan.*'),
                ],
                [
                    'label' => 'Legalitas',
                    'icon' => 'fa-solid fa-building-columns',
                    'href' => route('legalitas.index'),
                    'active' => request()->routeIs('legalitas.*'),
                ],
                [
                    'label' => 'Profil Perusahaan',
                    'icon' => 'fa-solid fa-building',
                    'href' => route('company-profile.show'),
                    'active' => request()->routeIs('company-profile.*'),
                ],
            ],
        ],
        [
            'heading' => 'Sistem',
            'items' => [
                [
                    'label' => 'User Management',
                    'icon' => 'fa-solid fa-users-gear',
                    'href' => route('user-management.index'),
                    'active' => request()->routeIs('user-management.*'),
                ],
            ],
        ],
    ];
@endphp

<aside
    class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col justify-between overflow-y-auto border-r border-white/8 bg-noir/95 backdrop-blur-md lg:flex">

    <div>
        {{-- Header Logo Section --}}
        <div class="relative overflow-hidden border-b border-white/6 bg-onyx/50 px-6 py-5">
            <div class="pointer-events-none absolute -left-8 -top-8 h-24 w-24 rounded-full bg-gold/10 blur-xl"></div>
            <div class="flex flex-col items-center justify-center gap-2">
                <img src="{{ asset('assets/logo-utama.png') }}" alt="Logo SG"
                    class="h-10 w-fit object-contain transition-transform duration-300 hover:scale-105 mx-auto">
            </div>
        </div>

        {{-- Navigation Links --}}
        <nav class="space-y-1.5 p-3">
            @foreach ($navigationSections as $section)
                @if (!empty($section['heading']))
                    <div class="flex items-center gap-2.5 pt-4 pb-2">
                        <span class="text-[10px] font-bold uppercase tracking-[0.24em] text-gold-soft/75">
                            {{ $section['heading'] }}
                        </span>
                        <div class="h-px flex-1 bg-gradient-to-r from-white/10 to-transparent"></div>
                    </div>
                @endif

                @foreach ($section['items'] as $item)
                    @if (!empty($item['children']))
                        <details data-collapsible data-state="{{ $item['open'] ?? false ? 'open' : 'closed' }}"
                            class="group rounded-xl transition-all duration-300 ease-out"
                            @if ($item['open'] ?? false) open @endif>
                            <summary data-collapsible-trigger
                                class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-white transition-colors duration-300 select-none hover:bg-white/5 group-data-[state=open]:bg-white/4 [&::-webkit-details-marker]:hidden">
                                <span class="flex items-center gap-3">
                                    <i
                                        class="{{ $item['icon'] }} w-4 text-center text-gold-soft transition-transform duration-300 group-data-[state=open]:scale-110"></i>
                                    <span class="font-semibold">{{ $item['label'] }}</span>
                                </span>
                                <i
                                    class="fa-solid fa-chevron-down text-[10px] text-smoke/70 transition-transform duration-300 ease-out group-data-[state=open]:rotate-180"></i>
                            </summary>

                            <div data-collapsible-content
                                class="overflow-hidden transition-[height,opacity] duration-300 ease-out"
                                @if (!($item['open'] ?? false)) style="height: 0; opacity: 0;" @endif>
                                <div class="ml-5 space-y-1 border-l border-white/10 px-3 pb-1 pt-1.5">
                                    @foreach ($item['children'] as $child)
                                        <a href="{{ $child['href'] }}"
                                            class="flex translate-y-1 items-center gap-2.5 rounded-lg px-3 py-2 font-medium opacity-80 transition-all duration-300 ease-out group-data-[state=open]:translate-y-0 group-data-[state=open]:opacity-100 {{ $child['active'] ? 'border-l-2 border-gold bg-gold/15 text-gold-soft font-semibold' : 'text-smoke/80 hover:bg-white/5 hover:text-white hover:border-gold/30' }}">
                                            <i class="{{ $child['icon'] }} w-3.5 text-center text-[11px]"></i>
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                    @else
                        <a href="{{ $item['href'] }}"
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-medium transition-all duration-200 border-l-2 {{ $item['active'] ? 'border-gold bg-gradient-to-r from-gold/18 via-gold/8 to-transparent text-gold-soft font-semibold shadow-sm' : 'border-transparent text-smoke hover:bg-white/5 hover:text-white hover:border-gold/30' }}">
                            <i
                                class="{{ $item['icon'] }} w-4 text-center text-[13px] {{ $item['active'] ? 'text-gold-soft' : 'text-smoke/70' }}"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            @endforeach
        </nav>
    </div>

    {{-- Sidebar Footer: User Card --}}
    <div class="p-3 border-t border-white/6">
        <div
            class="flex items-center gap-3 rounded-xl border border-white/8 bg-white/3 p-3 transition-colors hover:bg-white/5">
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-gold/25 bg-gold/12 font-bold text-xs text-gold-soft">
                {{ mb_strtoupper(mb_substr($user->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-semibold text-white">{{ $user->name ?? 'Admin User' }}</p>
                <p class="truncate text-[10px] text-smoke/70">{{ $user->email ?? 'admin@sg.com' }}</p>
            </div>
        </div>
    </div>

</aside>
