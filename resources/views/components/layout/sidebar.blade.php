@php
    $produkSection = request()->route('section', 'spa');

    $navigationSections = [
        [
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
                    'label' => 'Inbox',
                    'icon' => 'fa-regular fa-envelope',
                    'href' => route('massages.index'),
                    'active' => request()->routeIs('massages.*'),
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

<aside class="fixed inset-y-0 left-0 z-40 hidden w-72 overflow-y-auto border-r border-white/8 bg-noir lg:block">
    <div class="border-b border-white/6 px-6 py-5">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/logo-utama.png') }}" alt="Logo SG" class="h-10 object-cover mx-auto">
        </div>
    </div>

    <nav class="space-y-1 px-4 py-6">
        @foreach ($navigationSections as $section)
            @if (!empty($section['heading']))
                <div class="flex items-center gap-3 px-4 py-3">
                    <span
                        class="text-[11px] font-semibold uppercase tracking-[0.24em] text-gold-soft/80">{{ $section['heading'] }}</span>
                    <div class="h-px flex-1 bg-white/8"></div>
                </div>
            @endif

            @foreach ($section['items'] as $item)
                @if (!empty($item['children']))
                    <details class="group rounded-lg border border-white/6 bg-white/3 px-4 py-3"
                        @if ($item['open'] ?? false) open @endif>
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between gap-3 text-sm font-medium text-white [&::-webkit-details-marker]:hidden">
                            <span class="flex items-center gap-3">
                                <i class="{{ $item['icon'] }} w-5 text-center text-gold-soft"></i>
                                {{ $item['label'] }}
                            </span>
                            <i
                                class="fa-solid fa-chevron-down text-xs text-smoke transition-transform group-open:rotate-180"></i>
                        </summary>

                        <div class="mt-3 space-y-1 border-l border-white/8 pl-4">
                            @foreach ($item['children'] as $child)
                                <a href="{{ $child['href'] }}"
                                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors hover:bg-white/5 hover:text-white {{ $child['active'] ? 'bg-white/8 text-white' : 'text-smoke' }}">
                                    <i class="{{ $child['icon'] }} w-4 text-center text-xs"></i>
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </details>
                @else
                    <a href="{{ $item['href'] }}"
                        class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm transition-colors {{ $item['active'] ? 'bg-white text-obsidian' : 'text-smoke hover:bg-white/5 hover:text-white' }}">
                        <i class="{{ $item['icon'] }} w-5 text-center"></i>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        @endforeach
    </nav>
</aside>
