@extends('layouts.app')

@section('title', 'Client Area')

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'hero_bg' => 'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.15),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)]',
            'hero_glow' => 'bg-gold/8',
            'hero_shimmer' => 'via-gold/35',
            'badge_border' => 'border-gold/20',
            'badge_bg' => 'bg-gold/8',
            'badge_text' => 'text-gold-soft/90',
            'dot' => 'bg-gold',
            'gradient_text' => 'from-gold-soft to-champagne',
            'btn_primary' => 'bg-gold text-obsidian hover:bg-gold-soft shadow-[0_4px_18px_rgba(199,161,90,0.28)]',
        ];

        $clientAreaDev = (bool) ($settings['client_area_dev'] ?? false);
        $clientAreaProd = (bool) ($settings['client_area_prod'] ?? false);
        $activeCount = ($clientAreaDev ? 1 : 0) + ($clientAreaProd ? 1 : 0);
        $cards = [
            [
                'key' => 'dev',
                'label' => 'Development',
                'description' => 'Dipakai untuk pengujian fitur baru dan staging client area secara terisolasi.',
                'enabled' => $clientAreaDev,
                'accent' => 'blue',
                'icon' => 'fa-code-branch',
            ],
            [
                'key' => 'prod',
                'label' => 'Production',
                'description' => 'Dipakai untuk mengontrol akses aplikasi client area live yang diakses pengguna publik.',
                'enabled' => $clientAreaProd,
                'accent' => 'gold',
                'icon' => 'fa-globe',
            ],
        ];
    @endphp

    <section class="space-y-8">
        {{-- Hero Banner --}}
        <div class="relative overflow-hidden rounded-[28px] border border-white/10 {{ $theme['hero_bg'] }} p-6 sm:p-8 shadow-[0_24px_60px_rgba(0,0,0,0.35)] backdrop-blur-xl">
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full {{ $theme['hero_glow'] }} blur-[80px]"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $theme['hero_shimmer'] }} to-transparent"></div>

            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-3.5 py-1 text-[10px] font-bold uppercase tracking-[0.22em] {{ $theme['badge_text'] }} shadow-sm">
                            <span class="h-2 w-2 animate-pulse rounded-full {{ $theme['dot'] }}"></span>
                            Feature Toggle System
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-medium text-smoke/90">
                            <i class="fa-solid fa-layer-group text-[10px] text-gold-soft"></i>
                            {{ $activeCount }}/2 Environment Aktif
                        </span>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-4xl">
                            Client <span class="bg-gradient-to-r {{ $theme['gradient_text'] }} bg-clip-text text-transparent">Area Controller</span>
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-smoke/90 sm:text-base">
                            Kelola visibilitas & status operasional Client Area secara mandiri untuk environment <strong class="text-white">Development</strong> dan <strong class="text-white">Production</strong>.
                        </p>
                    </div>
                </div>

                {{-- Interactive Quick Info Cards --}}
                <div class="grid gap-3 sm:grid-cols-2 shrink-0">
                    {{-- API URL Card --}}
                    <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-4 transition-all duration-300 hover:border-white/20 hover:bg-white/8 shadow-inner">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-smoke/70">API Status URL</span>
                            <button type="button" 
                                data-copy-text="{{ $apiBaseUrl }}"
                                data-copy-target="url-copy-badge"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-2.5 py-1 text-[11px] font-semibold text-gold-soft hover:bg-gold/15 hover:text-white transition-all cursor-pointer active:scale-95 shadow-sm"
                                title="Salin API URL">
                                <i class="fa-regular fa-copy text-xs transition-transform duration-200 group-hover:scale-110"></i>
                                <span id="url-copy-badge">Salin</span>
                            </button>
                        </div>
                        <p class="mt-1.5 break-all font-mono text-xs font-medium text-white transition-colors group-hover:text-champagne">
                            {{ $apiBaseUrl }}
                        </p>
                    </div>

                    {{-- Auth Header Card --}}
                    <div class="group relative overflow-hidden rounded-2xl border border-gold/20 bg-gold/8 p-4 transition-all duration-300 hover:border-gold/35 hover:bg-gold/12 shadow-inner">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gold-soft/80">Header Auth</span>
                            <button type="button" 
                                data-copy-text="{{ $apiKeyHeader }}"
                                data-copy-target="header-copy-badge"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/10 px-2.5 py-1 text-[11px] font-semibold text-gold-soft hover:bg-gold/20 hover:text-white transition-all cursor-pointer active:scale-95 shadow-sm"
                                title="Salin Header Key">
                                <i class="fa-regular fa-copy text-xs transition-transform duration-200 group-hover:scale-110"></i>
                                <span id="header-copy-badge">Salin</span>
                            </button>
                        </div>
                        <p class="mt-1.5 break-all font-mono text-xs font-semibold text-gold-soft">
                            {{ $apiKeyHeader }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cards Grid --}}
        <div class="grid gap-6 xl:grid-cols-2">
            @foreach ($cards as $card)
                @php
                    $isBlue = $card['accent'] === 'blue';
                    $enabled = $card['enabled'];
                    
                    $cardBorder = $enabled
                        ? ($isBlue ? 'border-blue-500/30 hover:border-blue-500/50 shadow-[0_8px_30px_rgba(59,130,246,0.12)]' : 'border-gold/30 hover:border-gold/50 shadow-[0_8px_30px_rgba(199,161,90,0.12)]')
                        : 'border-white/10 hover:border-white/20 shadow-lg';

                    $cardBg = $isBlue
                        ? 'bg-[radial-gradient(ellipse_100%_100%_at_0%_0%,rgba(59,130,246,0.12),rgba(21,17,13,0.6))]'
                        : 'bg-[radial-gradient(ellipse_100%_100%_at_0%_0%,rgba(199,161,90,0.12),rgba(21,17,13,0.6))]';

                    $statusBadge = $enabled
                        ? 'border-emerald-400/30 bg-emerald-500/15 text-emerald-300 shadow-[0_0_15px_rgba(52,211,153,0.2)]'
                        : 'border-rose-500/30 bg-rose-500/15 text-rose-300 shadow-none';
                @endphp

                <article class="group relative flex flex-col justify-between overflow-hidden rounded-3xl border {{ $cardBorder }} {{ $cardBg }} p-6 sm:p-7 backdrop-blur-md transition-all duration-300">
                    <div class="pointer-events-none absolute -right-12 -bottom-12 h-40 w-40 rounded-full {{ $isBlue ? 'bg-blue-500/10' : 'bg-gold/10' }} blur-[50px] transition-all duration-500 group-hover:scale-125"></div>

                    <div class="space-y-6 relative z-10">
                        {{-- Top Card Bar --}}
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border {{ $isBlue ? 'border-blue-400/25 bg-blue-500/10 text-blue-400' : 'border-gold/25 bg-gold/10 text-gold-soft' }} shadow-inner">
                                    <i class="fa-solid {{ $card['icon'] }} text-lg"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-smoke/60">Environment</span>
                                        <span class="h-1 w-1 rounded-full bg-smoke/40"></span>
                                        <span class="text-[11px] font-medium text-smoke/80 capitalize">{{ $card['key'] }}</span>
                                    </div>
                                    <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">{{ $card['label'] }}</h2>
                                </div>
                            </div>

                            {{-- Live Badge --}}
                            <span class="inline-flex items-center gap-2 rounded-full border {{ $statusBadge }} px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider transition-all duration-300">
                                <span class="relative flex h-2 w-2">
                                    @if ($enabled)
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                                    @else
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-400"></span>
                                    @endif
                                </span>
                                {{ $enabled ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        {{-- Card Description --}}
                        <p class="text-sm leading-relaxed text-smoke/90">
                            {{ $card['description'] }}
                        </p>

                        {{-- Status Box --}}
                        <div class="rounded-2xl border border-white/8 bg-black/25 p-4 sm:p-5 backdrop-blur-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-smoke/60">Status Saat Ini</p>
                                    <div class="mt-1 flex items-center gap-2">
                                        <i class="fa-solid {{ $enabled ? 'fa-circle-check text-emerald-400' : 'fa-circle-xmark text-rose-400' }} text-base"></i>
                                        <span class="text-base font-semibold text-white">
                                            Client Area {{ $enabled ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-white/6 bg-white/5 px-3 py-2 text-left sm:text-right">
                                    <p class="text-[10px] font-medium text-smoke/70">Frontend Target</p>
                                    <p class="text-xs font-semibold text-champagne">{{ strtolower($card['label']) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form with High-Interactive Switch Button --}}
                    <div class="mt-8 border-t border-white/8 pt-5 relative z-10">
                        <form action="{{ route('client-area.update') }}" method="POST" class="js-toggle-form flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="target" value="{{ $card['key'] }}">
                            <input type="hidden" name="enabled" value="{{ $enabled ? '0' : '1' }}">

                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <button type="submit" data-enabled="{{ $enabled ? '1' : '0' }}"
                                    class="group/toggle relative inline-flex items-center justify-between gap-4 rounded-2xl border px-4 py-3 text-sm font-semibold transition-all duration-300 cursor-pointer w-full sm:w-auto shadow-lg hover:shadow-2xl focus:outline-none active:scale-[0.97] {{
                                        $enabled 
                                            ? 'border-emerald-500/40 bg-emerald-950/20 text-emerald-200 hover:border-emerald-400/60 hover:bg-emerald-900/35 shadow-[0_4px_25px_rgba(16,185,129,0.2)]' 
                                            : ($isBlue 
                                                ? 'border-blue-500/30 bg-blue-950/20 text-blue-100 hover:border-blue-400/50 hover:bg-blue-900/35 hover:shadow-[0_4px_25px_rgba(59,130,246,0.2)]' 
                                                : 'border-gold/30 bg-gold/10 text-champagne hover:border-gold/50 hover:bg-gold/20 hover:shadow-[0_4px_25px_rgba(199,161,90,0.2)]')
                                    }}">
                                    
                                    {{-- Interactive Animated Switch Track --}}
                                    <span class="js-switch-track relative inline-flex h-8 w-14 shrink-0 items-center rounded-full p-1 transition-all duration-300 ease-in-out {{
                                        $enabled 
                                            ? 'bg-gradient-to-r from-emerald-500 to-teal-400 shadow-[0_0_14px_rgba(16,185,129,0.6)]' 
                                            : 'bg-zinc-800 border border-white/10'
                                    }}">
                                        {{-- Sliding Knob --}}
                                        <span class="js-switch-knob inline-flex h-6 w-6 transform rounded-full bg-white shadow-xl transition-transform duration-300 ease-out items-center justify-center text-xs font-bold {{
                                            $enabled ? 'translate-x-6 text-emerald-600' : 'translate-x-0 text-zinc-400'
                                        }}">
                                            <i class="js-btn-icon fa-solid {{ $enabled ? 'fa-check' : 'fa-power-off' }}"></i>
                                        </span>
                                    </span>

                                    {{-- Button Dynamic Label --}}
                                    <div class="text-left pr-2">
                                        <span class="block text-[10px] font-bold uppercase tracking-wider {{ $enabled ? 'text-emerald-400' : 'text-smoke/60' }}">
                                            Status: {{ $enabled ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        <span class="js-btn-text block text-xs sm:text-sm font-bold text-white transition-colors group-hover/toggle:text-champagne">
                                            {{ $enabled ? 'Matikan Client Area' : 'Aktifkan Client Area' }}
                                        </span>
                                    </div>
                                </button>
                            </div>

                            <p class="text-xs text-smoke/70 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-info text-[11px] {{ $enabled ? 'text-emerald-400' : 'text-smoke/50' }}"></i>
                                <span>{{ $enabled ? 'Frontend environment ini sedang terbuka.' : 'Frontend environment ini masih tertutup.' }}</span>
                            </p>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Developer Console & Interactive API Section --}}
        <div class="rounded-3xl border border-white/10 bg-black/40 p-6 sm:p-8 backdrop-blur-xl shadow-2xl space-y-6">
            {{-- Header & Tab Navigation --}}
            <div class="flex flex-col gap-4 border-b border-white/8 pb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-gold"></span>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gold-soft">Developer Console</p>
                    </div>
                    <h2 class="mt-1 text-xl font-bold text-white tracking-tight">API Status & Live Playground</h2>
                </div>

                {{-- Interactive Tabs --}}
                <div class="flex items-center gap-1.5 rounded-2xl border border-white/8 bg-white/4 p-1.5">
                    <button type="button" data-tab-btn="json" class="js-tab-btn rounded-xl border border-gold/30 bg-gold/20 px-4 py-2 text-xs font-semibold text-gold-soft transition-all duration-200 cursor-pointer active:scale-95 shadow-sm">
                        <i class="fa-solid fa-code mr-1.5"></i> JSON Response
                    </button>
                    <button type="button" data-tab-btn="tester" class="js-tab-btn rounded-xl border border-transparent px-4 py-2 text-xs font-semibold text-smoke hover:text-white transition-all duration-200 cursor-pointer active:scale-95">
                        <i class="fa-solid fa-vial mr-1.5"></i> Live Tester
                    </button>
                    <button type="button" data-tab-btn="curl" class="js-tab-btn rounded-xl border border-transparent px-4 py-2 text-xs font-semibold text-smoke hover:text-white transition-all duration-200 cursor-pointer active:scale-95">
                        <i class="fa-solid fa-terminal mr-1.5"></i> cURL Example
                    </button>
                </div>
            </div>

            {{-- Tab 1: JSON Response --}}
            <div id="tab-panel-json" class="js-tab-panel space-y-3">
                <div class="flex items-center justify-between text-xs text-smoke">
                    <span>Response JSON terkini dari API endpoint:</span>
                    <button type="button" 
                        data-copy-text='{"data":{"dev":{{ $clientAreaDev ? "true" : "false" }},"prod":{{ $clientAreaProd ? "true" : "false" }}}}' 
                        data-copy-target="json-copy-badge"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-champagne hover:bg-white/10 transition-all cursor-pointer active:scale-95 shadow-sm">
                        <i class="fa-regular fa-copy text-xs"></i>
                        <span id="json-copy-badge">Salin JSON</span>
                    </button>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-[#0d0a08] p-4 sm:p-5">
                    <pre class="font-mono text-xs text-champagne leading-relaxed overflow-x-auto"><code>{
  <span class="text-gold-soft">"data"</span>: {
    <span class="text-blue-400">"dev"</span>: <span class="{{ $clientAreaDev ? 'text-emerald-400 font-bold' : 'text-rose-400' }}">{{ $clientAreaDev ? 'true' : 'false' }}</span>,
    <span class="text-gold-soft">"prod"</span>: <span class="{{ $clientAreaProd ? 'text-emerald-400 font-bold' : 'text-rose-400' }}">{{ $clientAreaProd ? 'true' : 'false' }}</span>
  }
}</code></pre>
                </div>
            </div>

            {{-- Tab 2: Live Endpoint Tester --}}
            <div id="tab-panel-tester" class="js-tab-panel space-y-4 hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-2xl border border-white/8 bg-white/4 p-4">
                    <div>
                        <p class="text-xs font-semibold text-white">Uji Langsung Endpoint Status Client Area</p>
                        <p class="text-xs text-smoke">Kirim GET Request ke <code class="text-gold-soft">{{ $apiBaseUrl }}</code></p>
                    </div>
                    <button type="button" id="btn-test-endpoint"
                        class="inline-flex items-center gap-2 rounded-xl border border-gold/30 bg-gold/15 px-4 py-2.5 text-xs font-semibold text-champagne hover:bg-gold/25 transition-all duration-200 cursor-pointer active:scale-95 shadow-md">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Jalankan HTTP Test</span>
                    </button>
                </div>

                <div id="tester-result-container" class="space-y-3 hidden">
                    <div class="flex items-center gap-3 text-xs">
                        <span class="font-semibold text-smoke">HTTP Status:</span>
                        <span id="tester-status-badge" class="rounded-md px-2 py-0.5 font-mono text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            200 OK
                        </span>
                        <span id="tester-latency" class="text-smoke/60"></span>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-[#0d0a08] p-4 sm:p-5">
                        <pre id="tester-output-code" class="font-mono text-xs text-emerald-300 leading-relaxed overflow-x-auto"></pre>
                    </div>
                </div>
            </div>

            {{-- Tab 3: cURL Command Example --}}
            <div id="tab-panel-curl" class="js-tab-panel space-y-3 hidden">
                <div class="flex items-center justify-between text-xs text-smoke">
                    <span>Gunakan perintah cURL berikut di terminal atau API client:</span>
                    <button type="button" 
                        data-copy-text='curl -X GET "{{ $apiBaseUrl }}" -H "{{ $apiKeyHeader }}: {YOUR_API_KEY}"' 
                        data-copy-target="curl-copy-badge"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-champagne hover:bg-white/10 transition-all cursor-pointer active:scale-95 shadow-sm">
                        <i class="fa-regular fa-copy text-xs"></i>
                        <span id="curl-copy-badge">Salin cURL</span>
                    </button>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-[#0d0a08] p-4 sm:p-5">
                    <pre class="font-mono text-xs text-gold-soft leading-relaxed overflow-x-auto">curl -X GET "{{ $apiBaseUrl }}" \
  -H "{{ $apiKeyHeader }}: {YOUR_API_KEY}"</pre>
                </div>
            </div>
        </div>

    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Copy to Clipboard logic with animation feedback
                document.querySelectorAll('[data-copy-text]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const text = btn.getAttribute('data-copy-text');
                        const targetId = btn.getAttribute('data-copy-target');
                        const targetEl = document.getElementById(targetId);

                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(text);
                        } else {
                            const textarea = document.createElement('textarea');
                            textarea.value = text;
                            document.body.appendChild(textarea);
                            textarea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textarea);
                        }

                        if (targetEl) {
                            const originalText = targetEl.textContent;
                            targetEl.textContent = 'Tersalin!';
                            targetEl.classList.add('text-emerald-400');
                            setTimeout(() => {
                                targetEl.textContent = originalText;
                                targetEl.classList.remove('text-emerald-400');
                            }, 2000);
                        }
                    });
                });

                // Tab Switcher logic
                const tabButtons = document.querySelectorAll('.js-tab-btn');
                const tabPanels = document.querySelectorAll('.js-tab-panel');

                tabButtons.forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const targetTab = btn.getAttribute('data-tab-btn');

                        tabButtons.forEach((b) => {
                            b.classList.remove('border-gold/30', 'bg-gold/20', 'text-gold-soft', 'shadow-sm');
                            b.classList.add('border-transparent', 'text-smoke');
                        });

                        btn.classList.remove('border-transparent', 'text-smoke');
                        btn.classList.add('border-gold/30', 'bg-gold/20', 'text-gold-soft', 'shadow-sm');

                        tabPanels.forEach((panel) => {
                            panel.classList.add('hidden');
                        });

                        const activePanel = document.getElementById(`tab-panel-${targetTab}`);
                        if (activePanel) {
                            activePanel.classList.remove('hidden');
                        }
                    });
                });

                // Live Endpoint Tester logic
                const testBtn = document.getElementById('btn-test-endpoint');
                const resultContainer = document.getElementById('tester-result-container');
                const outputCode = document.getElementById('tester-output-code');
                const latencyEl = document.getElementById('tester-latency');

                if (testBtn) {
                    testBtn.addEventListener('click', async () => {
                        const icon = testBtn.querySelector('i');
                        const textSpan = testBtn.querySelector('span');

                        if (icon) icon.className = 'fa-solid fa-spinner fa-spin text-xs';
                        if (textSpan) textSpan.textContent = 'Menguji Endpoint...';
                        testBtn.disabled = true;

                        const startTime = performance.now();
                        const apiUrl = @json($apiBaseUrl);

                        try {
                            const res = await fetch(apiUrl, {
                                headers: {
                                    'Accept': 'application/json',
                                    @json($apiKeyHeader): 'test'
                                }
                            });
                            const endTime = performance.now();
                            const latency = Math.round(endTime - startTime);

                            const data = await res.json();
                            outputCode.textContent = JSON.stringify(data, null, 2);
                            latencyEl.textContent = `Latency: ${latency} ms`;
                            resultContainer.classList.remove('hidden');
                        } catch (err) {
                            const endTime = performance.now();
                            const latency = Math.round(endTime - startTime);

                            outputCode.textContent = JSON.stringify({
                                data: {
                                    dev: {{ $clientAreaDev ? 'true' : 'false' }},
                                    prod: {{ $clientAreaProd ? 'true' : 'false' }}
                                }
                            }, null, 2);
                            latencyEl.textContent = `Latency: ${latency} ms (Local View)`;
                            resultContainer.classList.remove('hidden');
                        } finally {
                            if (icon) icon.className = 'fa-solid fa-paper-plane text-xs';
                            if (textSpan) textSpan.textContent = 'Jalankan HTTP Test';
                            testBtn.disabled = false;
                        }
                    });
                }

                // Interactive Form Toggle animation on click
                document.querySelectorAll('.js-toggle-form').forEach((form) => {
                    form.addEventListener('submit', function (e) {
                        const button = this.querySelector('button[type="submit"]');
                        const isEnabled = button ? button.getAttribute('data-enabled') === '1' : false;
                        const track = this.querySelector('.js-switch-track');
                        const knob = this.querySelector('.js-switch-knob');
                        const btnIcon = this.querySelector('.js-btn-icon');
                        const btnText = this.querySelector('.js-btn-text');

                        // Instant switch knob sliding animation
                        if (knob) {
                            if (isEnabled) {
                                knob.classList.remove('translate-x-6');
                                knob.classList.add('translate-x-0');
                            } else {
                                knob.classList.remove('translate-x-0');
                                knob.classList.add('translate-x-6');
                            }
                        }

                        // Track color shift
                        if (track) {
                            if (isEnabled) {
                                track.classList.remove('bg-gradient-to-r', 'from-emerald-500', 'to-teal-400', 'shadow-[0_0_14px_rgba(16,185,129,0.6)]');
                                track.classList.add('bg-zinc-800');
                            } else {
                                track.classList.add('bg-gradient-to-r', 'from-emerald-500', 'to-teal-400', 'shadow-[0_0_14px_rgba(16,185,129,0.6)]');
                                track.classList.remove('bg-zinc-800');
                            }
                        }

                        // Spinner in knob
                        if (btnIcon) {
                            btnIcon.className = 'js-btn-icon fa-solid fa-spinner fa-spin text-xs';
                        }

                        if (btnText) {
                            btnText.textContent = 'Memproses...';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
