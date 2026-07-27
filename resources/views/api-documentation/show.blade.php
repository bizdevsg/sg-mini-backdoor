@extends('layouts.app')

@section('title', 'Dokumentasi API')

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'hero_bg' =>
                'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.15),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)]',
            'hero_glow' => 'bg-gold/8',
            'hero_shimmer' => 'via-gold/35',
            'badge_border' => 'border-gold/20',
            'badge_bg' => 'bg-gold/8',
            'badge_text' => 'text-gold-soft/90',
            'dot' => 'bg-gold',
            'gradient_text' => 'from-gold-soft to-champagne',
        ];
        $headerExample = $apiKeyHeader . ': ' . (filled($apiKeyValue) ? $apiKeyValue : 'isi-api-key-di-env');
    @endphp

    <section class="space-y-6">
        <div
            class="relative overflow-hidden rounded-[28px] border border-white/8 {{ $theme['hero_bg'] }} px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)] lg:px-9 lg:py-8">
            <div
                class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full {{ $theme['hero_glow'] }} blur-[64px]">
            </div>
            <div
                class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $theme['hero_shimmer'] }} to-transparent">
            </div>

            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-3">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] {{ $theme['badge_text'] }}">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full {{ $theme['dot'] }}"></span>
                        API Reference
                    </span>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                            Dokumentasi
                            <span
                                class="bg-gradient-to-r {{ $theme['gradient_text'] }} bg-clip-text text-transparent">API</span>
                        </h1>
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-smoke">
                            Halaman ini merangkum cara akses endpoint `/api/v1`, header autentikasi, daftar endpoint,
                            dan contoh request untuk kebutuhan frontend, Postman, atau integrasi pihak ketiga.
                        </p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:max-w-xl">
                    <div class="min-w-0 rounded-2xl border border-white/8 bg-white/5 px-4 py-3 text-sm">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Base URL</p>
                        <p class="mt-1 break-all font-medium text-white">{{ $apiBaseUrl }}</p>
                    </div>
                    <div class="min-w-0 rounded-2xl border border-gold/20 bg-gold/8 px-4 py-3 text-sm">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-gold-soft/70">Header Auth
                        </p>
                        <p class="mt-1 break-all font-medium text-gold-soft">{{ $apiKeyHeader }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="min-w-0 space-y-6">
                <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3 p-6">
                    <div class="flex items-center gap-3 border-b border-white/6 pb-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-gold/20 bg-gold/10 text-gold-soft">
                            <i class="fa-solid fa-terminal text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Autentikasi</p>
                            <h2 class="text-lg font-semibold text-white">Header API Key</h2>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        <p class="text-sm leading-6 text-smoke">
                            Semua endpoint `api/v1` saat ini diproteksi menggunakan custom header
                            <span class="font-medium text-champagne">{{ $apiKeyHeader }}</span>. Bukan Bearer token,
                            bukan JWT, dan tidak memerlukan login user frontend.
                        </p>

                        @if (filled($apiKeyValue))
                            <div class="overflow-hidden rounded-2xl border border-white/8 bg-[#120f0c]">
                                <div
                                    class="flex items-center justify-between border-b border-white/6 bg-[#1b1611] px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f56]"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#ffbd2e]"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#27c93f]"></span>
                                    </div>
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/55">
                                        terminal
                                    </p>
                                </div>

                                <div class="space-y-4 p-4">
                                    <div class="font-mono text-[12px] leading-6 text-champagne">
                                        <div class="flex gap-3">
                                            <span class="select-none text-gold-soft/65">$</span>
                                            <span class="break-all text-gold-soft">{{ $apiKeyHeader }}</span>
                                        </div>
                                        <div class="flex gap-3">
                                            <span class="select-none text-gold-soft/65">&gt;</span>
                                            <span class="break-all text-white/90">{{ $apiKeyValue }}</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <input id="api-doc-key" type="text" readonly value="{{ $apiKeyValue }}"
                                            class="min-w-0 flex-1 rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-mono text-sm text-champagne outline-none selection:bg-gold selection:text-obsidian">
                                        <button type="button" data-copy-text="{{ $apiKeyValue }}"
                                            data-copy-label-default="Copy API Key" data-copy-label-success="Copied"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gold/30 bg-gold/12 px-4 py-3 text-sm font-semibold text-gold-soft transition-all duration-200 hover:border-gold/45 hover:bg-gold/18">
                                            <i class="fa-regular fa-copy text-xs"></i>
                                            <span>Copy API Key</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div
                                class="rounded-2xl border border-amber-400/20 bg-amber-400/8 px-4 py-4 text-sm text-amber-100">
                                <p class="font-medium">API key belum dikonfigurasi.</p>
                                <p class="mt-1 text-amber-100/80">
                                    Isi `API_KEY` di file `.env` agar semua request ke `/api/v1` bisa diautentikasi.
                                </p>
                            </div>
                        @endif

                        <div class="overflow-hidden rounded-2xl border border-white/8 bg-[#120f0c]">
                            <div class="flex items-center justify-between border-b border-white/6 bg-[#1b1611] px-4 py-3">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/55">
                                    raw header
                                </p>
                                <button type="button" data-copy-text="{{ $headerExample }}"
                                    data-copy-label-default="Copy Header" data-copy-label-success="Copied"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-[11px] font-medium text-champagne/80 transition-colors hover:border-white/18 hover:bg-white/8 hover:text-white">
                                    <i class="fa-regular fa-copy text-[10px]"></i>
                                    <span>Copy</span>
                                </button>
                            </div>
                            <pre class="overflow-x-auto px-4 py-4 font-mono text-[11px] leading-6 text-champagne whitespace-pre-wrap break-all"><code>{{ $headerExample }}</code></pre>
                        </div>
                    </div>
                </div>

                @foreach ($endpointGroups as $group)
                    <div class="rounded-2xl border border-white/8 bg-white/3 p-6">
                        <div class="border-b border-white/6 pb-4">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">
                                {{ $group['title'] }}</p>
                            <h2 class="mt-1 text-lg font-semibold text-white">{{ $group['description'] }}</h2>
                        </div>

                        <div class="mt-4 overflow-hidden rounded-2xl border border-white/6">
                            <table class="min-w-full">
                                <thead class="bg-noir/50">
                                    <tr
                                        class="text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                                        <th class="px-4 py-3">Method</th>
                                        <th class="px-4 py-3">Endpoint</th>
                                        <th class="px-4 py-3">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/6">
                                    @foreach ($group['endpoints'] as $endpoint)
                                        <tr class="align-top">
                                            <td class="px-4 py-3">
                                                <span
                                                    class="{{ $endpoint['method'] === 'POST' ? 'border-emerald-400/25 bg-emerald-400/10 text-emerald-200' : 'border-blue-400/25 bg-blue-400/10 text-blue-200' }} inline-flex rounded-full border px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.16em]">
                                                    {{ $endpoint['method'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 font-mono text-xs text-champagne">{{ $endpoint['path'] }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-smoke">{{ $endpoint['notes'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="min-w-0 space-y-6">
                <div class="rounded-2xl border border-white/8 bg-white/3 p-6">
                    <div class="border-b border-white/6 pb-4">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Testing</p>
                        <h2 class="mt-1 text-lg font-semibold text-white">Postman & Integrasi</h2>
                    </div>

                    <div class="mt-4 space-y-4 text-sm leading-6 text-smoke">
                        <div class="rounded-2xl border border-white/8 bg-white/4 p-4">
                            <p class="font-medium text-white">Postman</p>
                            <p class="mt-2">
                                Pakai tab `Headers`, lalu isi
                                <span class="font-medium text-champagne">{{ $apiKeyHeader }}</span>
                                dengan value API key aktif.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/8 bg-white/4 p-4">
                            <p class="font-medium text-white">Query yang umum dipakai</p>
                            <ul class="mt-2 space-y-2 text-sm text-smoke">
                                <li><span class="font-mono text-champagne">?page=1&per_page=10</span> untuk pagination</li>
                                <li><span class="font-mono text-champagne">?search=keyword</span> untuk pencarian list</li>
                                <li><span class="font-mono text-champagne">?category={slug}</span> untuk filter ebook per
                                    kategori</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/8 bg-white/3 p-6">
                    <div class="border-b border-white/6 pb-4">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Request Example
                        </p>
                        <h2 class="mt-1 text-lg font-semibold text-white">cURL</h2>
                    </div>

                    <div class="mt-4 space-y-4">
                        @foreach ($requestExamples as $example)
                            <div class="overflow-hidden rounded-2xl border border-white/8 bg-[#120f0c]">
                                <div
                                    class="flex items-center justify-between gap-3 border-b border-white/6 bg-[#1b1611] px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f56]"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#ffbd2e]"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-[#27c93f]"></span>
                                    </div>
                                    <p class="min-w-0 flex-1 truncate text-sm font-medium text-white">
                                        {{ $example['label'] }}
                                    </p>
                                    <button type="button" data-copy-text="{{ $example['command'] }}"
                                        data-copy-label-default="Copy" data-copy-label-success="Copied"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-[11px] font-medium text-champagne/80 transition-colors hover:border-white/18 hover:bg-white/8 hover:text-white">
                                        <i class="fa-regular fa-copy text-[10px]"></i>
                                        <span>Copy</span>
                                    </button>
                                </div>
                                <pre class="overflow-x-auto px-4 py-4 font-mono text-[11px] leading-6 text-champagne whitespace-pre-wrap break-all"><code>{{ $example['command'] }}</code></pre>
                                kas
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-copy-text]').forEach((button) => {
                if (!(button instanceof HTMLButtonElement) || button.dataset.copyInitialized === 'true') {
                    return;
                }

                button.dataset.copyInitialized = 'true';

                button.addEventListener('click', async () => {
                    const text = button.dataset.copyText ?? '';

                    if (!text) {
                        return;
                    }

                    const label = button.querySelector('span');
                    const defaultLabel = button.dataset.copyLabelDefault ?? 'Copy';
                    const successLabel = button.dataset.copyLabelSuccess ?? 'Copied';

                    try {
                        await navigator.clipboard.writeText(text);

                        if (label instanceof HTMLElement) {
                            label.textContent = successLabel;
                        }

                        window.setTimeout(() => {
                            if (label instanceof HTMLElement) {
                                label.textContent = defaultLabel;
                            }
                        }, 1600);
                    } catch (error) {
                        console.error('Failed to copy text.', error);
                    }
                });
            });
        });
    </script>
@endPushOnce
