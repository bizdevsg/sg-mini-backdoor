@extends('layouts.app')

@section('title', 'System Log Monitoring')

@section('content')
    <section class="space-y-6">
        {{-- Terminal Window Box (Pure Black Git Terminal Style) --}}
        <div class="overflow-hidden rounded-2xl border border-neutral-800 bg-black shadow-[0_25px_70px_rgba(0,0,0,0.85)] font-mono">
            
            {{-- Git Bash Titlebar --}}
            <div class="flex flex-col gap-3 border-b border-neutral-800 bg-[#161719] px-4 py-3 sm:flex-row sm:items-center sm:justify-between select-none">
                <div class="flex items-center gap-3">
                    {{-- Window buttons --}}
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-red-500 inline-block cursor-pointer hover:opacity-80" title="Close"></span>
                        <span class="h-3 w-3 rounded-full bg-yellow-500 inline-block cursor-pointer hover:opacity-80" title="Minimize"></span>
                        <span class="h-3 w-3 rounded-full bg-green-500 inline-block cursor-pointer hover:opacity-80" title="Maximize"></span>
                    </div>

                    <div class="h-4 w-px bg-neutral-700"></div>

                    {{-- Git Bash Title Path --}}
                    <div class="flex items-center gap-2 text-xs text-neutral-300">
                        <i class="fa-solid fa-code-branch text-emerald-400"></i>
                        <span class="font-bold text-white">System Log Terminal:</span>
                        <span class="text-yellow-400">/c/laragon/www/sg-admin</span>
                        <span class="text-cyan-400">({{ $activeCategory }})</span>
                    </div>
                </div>

                {{-- Git Bash Terminal Controls --}}
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-500/40 bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-400">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        GIT-LOG DAEMON
                    </span>

                    {{-- Copy Terminal Logs Button --}}
                    <button type="button" id="btn-copy-terminal"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-neutral-700 bg-neutral-900 px-3 py-1 text-xs text-neutral-300 hover:border-neutral-500 hover:bg-neutral-800 hover:text-white transition-all cursor-pointer active:scale-95">
                        <i class="fa-regular fa-copy text-xs text-yellow-400"></i>
                        <span id="copy-terminal-text">Copy Terminal</span>
                    </button>
                </div>
            </div>

            {{-- Git Log Commands Bar (Category Selection) --}}
            <div class="border-b border-neutral-800 bg-[#0d0d0d] px-4 py-3 sm:px-6">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-neutral-400">
                            <span class="text-emerald-400 font-bold">user@sg-admin</span>
                            <span class="text-purple-400 font-bold">MINGW64</span>
                            <span class="text-yellow-400 font-bold">/var/log/system</span>
                            <span class="text-cyan-400 font-bold">({{ $activeCategory }})</span>
                        </div>
                        <p class="mt-1 text-xs text-emerald-400 font-bold">
                            $ git log --category={{ $activeCategory }} --graph --decorate
                        </p>
                    </div>

                    {{-- Git Command Category Buttons --}}
                    <div class="flex flex-wrap gap-2">
                        @foreach ($categoryMeta as $key => $meta)
                            <a href="{{ route('system-logs.show', ['category' => $key]) }}"
                                class="inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-xs font-bold transition-all duration-200 cursor-pointer active:scale-95 {{
                                    $activeCategory === $key 
                                        ? 'border-yellow-500/60 bg-yellow-500/20 text-yellow-300 shadow-[0_0_12px_rgba(234,179,8,0.2)]' 
                                        : 'border-neutral-800 bg-black text-neutral-400 hover:border-neutral-700 hover:text-white'
                                }}">
                                <i class="fa-solid {{ $meta['icon'] }} text-xs"></i>
                                <span>git checkout {{ $key }}</span>
                                <span class="rounded bg-neutral-900 border border-neutral-700 px-1.5 py-0.5 text-[10px] text-neutral-300">
                                    {{ $counts[$key] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- API Module Flags Bar (If API Category) --}}
                @if ($activeCategory === 'api')
                    <div class="mt-3 pt-3 border-t border-neutral-800">
                        <div class="flex items-center gap-2 text-xs text-neutral-400 mb-2">
                            <span class="text-cyan-400 font-bold">$</span>
                            <span>git log --grep-module:</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('system-logs.show', ['category' => 'api']) }}"
                                class="inline-flex items-center gap-1.5 rounded border px-2.5 py-1 text-xs transition-all duration-150 active:scale-95 {{
                                    $activeModule === '' 
                                        ? 'border-cyan-500/50 bg-cyan-500/20 text-cyan-300 font-bold' 
                                        : 'border-neutral-800 bg-black text-neutral-400 hover:border-neutral-700 hover:text-white'
                                }}">
                                <span>--all-modules</span>
                                <span class="text-[10px] text-neutral-400">({{ $counts['api'] }})</span>
                            </a>

                            @foreach ($apiModuleMeta as $module => $label)
                                @php
                                    $moduleCount = (int) ($apiModuleCounts[$module] ?? 0);
                                @endphp

                                @if ($moduleCount > 0)
                                    <a href="{{ route('system-logs.show', ['category' => 'api', 'module' => $module]) }}"
                                        class="inline-flex items-center gap-1.5 rounded border px-2.5 py-1 text-xs transition-all duration-150 active:scale-95 {{
                                            $activeModule === $module 
                                                ? 'border-cyan-500/50 bg-cyan-500/20 text-cyan-300 font-bold' 
                                                : 'border-neutral-800 bg-black text-neutral-400 hover:border-neutral-700 hover:text-white'
                                        }}">
                                        <span>--module={{ $module }}</span>
                                        <span class="text-[10px] text-neutral-400">({{ $moduleCount }})</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Interactive Git Grep Search Bar --}}
                <div class="mt-3 pt-3 border-t border-neutral-800 flex items-center gap-2">
                    <span class="text-emerald-400 font-bold text-xs">$</span>
                    <span class="text-neutral-400 text-xs font-bold shrink-0">git log --grep=</span>
                    <div class="relative flex-1">
                        <input type="text" id="terminal-filter-input" 
                            placeholder='"cari user, ip, event..."' 
                            class="w-full rounded border border-neutral-800 bg-black px-3 py-1 text-xs text-yellow-300 placeholder-neutral-600 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                    </div>
                </div>
            </div>

            {{-- Pure Black Git Terminal Log Output Body --}}
            <div class="p-4 sm:p-6 text-xs sm:text-sm leading-relaxed text-neutral-200 bg-black space-y-4 min-h-[400px]" id="terminal-console-body">
                
                {{-- Git Log Header Notice --}}
                <div class="text-neutral-500 text-xs flex items-center justify-between pb-2 border-b border-neutral-900">
                    <div>
                        <span class="text-emerald-400 font-bold">commit HEAD -&gt; refs/heads/{{ $activeCategory }}</span>
                        <span class="ml-2 text-neutral-400">({{ $logs->total() }} commits found)</span>
                    </div>
                    <span>Press 'q' or click details to inspect</span>
                </div>

                {{-- Log Lines Loop formatted as raw Git Log Stream --}}
                <div class="space-y-3 font-mono text-xs sm:text-sm select-text" id="log-list-container">
                    @forelse ($logs as $index => $log)
                        @php
                            $eventUpper = strtoupper((string) $log->event);
                            $commitHash = substr(md5($log->id . $log->created_at), 0, 7);

                            $badgeColor = 'text-blue-400 border-blue-500/30 bg-blue-950/20';
                            if (str_contains($eventUpper, 'LOGIN') || str_contains($eventUpper, 'AUTH') || str_contains($eventUpper, 'SUCCESS')) {
                                $badgeColor = 'text-emerald-400 border-emerald-500/30 bg-emerald-950/20';
                            } elseif (str_contains($eventUpper, 'UPDATE') || str_contains($eventUpper, 'PUT') || str_contains($eventUpper, 'EDIT')) {
                                $badgeColor = 'text-yellow-400 border-yellow-500/30 bg-yellow-950/20';
                            } elseif (str_contains($eventUpper, 'DELETE') || str_contains($eventUpper, 'REMOVE') || str_contains($eventUpper, 'FAIL') || str_contains($eventUpper, 'ERROR')) {
                                $badgeColor = 'text-red-400 border-red-500/30 bg-red-950/20';
                            }

                            $hasContext = is_array($log->context) && !empty($log->context);
                            $logId = 'log-context-' . $log->id;
                        @endphp

                        <div class="log-entry-row group py-2.5 px-3 rounded hover:bg-[#0e0e0e] transition-colors border-l-2 border-transparent hover:border-emerald-500">
                            {{-- Line 1: Git Graph & Commit Hash --}}
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                <span class="text-emerald-400 font-bold select-none">*</span>
                                <span class="text-yellow-400 font-bold">commit {{ $commitHash }}</span>
                                <span class="text-cyan-400">[{{ $log->created_at?->format('Y-m-d H:i:s') ?? '-' }}]</span>
                                <span class="text-emerald-500 font-bold text-xs">(HEAD -&gt; {{ $activeCategory }})</span>
                                <span class="inline-flex items-center rounded border px-1.5 py-0.2 text-[11px] font-bold tracking-wider {{ $badgeColor }}">
                                    {{ $log->event }}
                                </span>
                            </div>

                            {{-- Line 2: Author --}}
                            <div class="flex items-center gap-2 pl-3 text-xs text-neutral-400 mt-1">
                                <span class="text-neutral-600 font-bold select-none">|</span>
                                <span class="text-neutral-500 font-bold">Author:</span>
                                <span class="text-white font-bold">{{ $log->user?->name ?? 'System/API Client' }}</span>
                                @if ($log->user?->email)
                                    <span class="text-neutral-500">&lt;{{ $log->user->email }}&gt;</span>
                                @endif
                                @if ($log->ip_address)
                                    <span class="text-emerald-400 font-mono">@ {{ $log->ip_address }}</span>
                                @endif
                            </div>

                            {{-- Line 3: Description Message --}}
                            <div class="flex items-start gap-2 pl-3 mt-1">
                                <span class="text-neutral-600 font-bold select-none">|</span>
                                <span class="text-neutral-500 font-bold text-xs shrink-0">Message:</span>
                                <span class="text-white font-medium break-all">{{ $log->description }}</span>
                            </div>

                            {{-- Line 4: Context metadata details --}}
                            @if ($activeCategory === 'data' && is_array($log->context))
                                <div class="flex flex-wrap items-center gap-2 pl-3 mt-1.5 text-xs">
                                    <span class="text-neutral-600 font-bold select-none">|</span>
                                    <span class="text-neutral-500 font-bold">Context:</span>
                                    <span class="text-neutral-300">
                                        target: <strong class="text-yellow-300">{{ strtoupper((string) ($log->context['target'] ?? '-')) }}</strong>
                                    </span>
                                    <span class="text-neutral-300">
                                        status: <strong class="text-amber-400">{{ ($log->context['previous_status'] ?? false) ? 'Aktif' : 'Nonaktif' }}</strong> &rarr; <strong class="text-emerald-400">{{ ($log->context['new_status'] ?? false) ? 'Aktif' : 'Nonaktif' }}</strong>
                                    </span>
                                </div>
                            @endif

                            @if ($activeCategory === 'api' && is_array($log->context))
                                @php
                                    $moduleKey = (string) ($log->subject ?? $log->context['module'] ?? 'unknown');
                                    $statusCode = (int) ($log->context['status_code'] ?? 200);
                                    $statusClass = $statusCode >= 200 && $statusCode < 300 ? 'text-emerald-400' : 'text-red-400';
                                @endphp

                                <div class="flex flex-wrap items-center gap-2 pl-3 mt-1.5 text-xs">
                                    <span class="text-neutral-600 font-bold select-none">|</span>
                                    <span class="text-neutral-500 font-bold">Context:</span>
                                    <span class="text-neutral-300">
                                        module: <strong class="text-cyan-300">{{ $apiModuleMeta[$moduleKey] ?? $moduleKey }}</strong>
                                    </span>
                                    <span class="text-neutral-300">
                                        method: <strong class="text-purple-300">{{ strtoupper((string) ($log->context['method'] ?? '-')) }}</strong>
                                    </span>
                                    <span class="text-neutral-300">
                                        path: <strong class="text-yellow-300">{{ (string) ($log->context['path'] ?? '-') }}</strong>
                                    </span>
                                    <span class="text-neutral-300">
                                        status: <strong class="{{ $statusClass }}">{{ $statusCode }}</strong>
                                    </span>
                                    @if (isset($log->context['duration_ms']))
                                        <span class="text-neutral-300">
                                            latency: <strong class="text-yellow-400">{{ (string) $log->context['duration_ms'] }} ms</strong>
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- Line 5: Collapsible Git Show raw JSON --}}
                            @if ($hasContext)
                                <div class="pl-3 mt-1.5 flex items-center gap-2">
                                    <span class="text-neutral-600 font-bold select-none">|</span>
                                    <button type="button" 
                                        onclick="toggleLogContext('{{ $logId }}')"
                                        class="inline-flex items-center gap-1.5 text-xs text-yellow-400 hover:text-white transition-colors cursor-pointer bg-neutral-900 border border-neutral-800 hover:border-yellow-500/50 px-2 py-0.5 rounded">
                                        <i class="fa-solid fa-code text-[10px]"></i>
                                        <span>$ git show {{ $commitHash }}:context.json</span>
                                        <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-200" id="{{ $logId }}-arrow"></i>
                                    </button>
                                </div>

                                <div id="{{ $logId }}" class="hidden pl-6 mt-2">
                                    <div class="rounded border border-neutral-800 bg-[#050505] p-3 text-xs">
                                        <pre class="overflow-x-auto text-emerald-300 font-mono leading-relaxed">{{ json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 text-center font-mono">
                            <i class="fa-solid fa-code-commit text-3xl text-neutral-600 mb-3 block"></i>
                            <p class="text-sm font-bold text-white">fatal: no commits found for category '{{ $activeCategory }}'</p>
                            <p class="mt-1 text-xs text-neutral-500">Belum ada log aktivitas tercatat.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Git Bash Terminal Prompt Footer --}}
                <div class="pt-4 border-t border-neutral-800 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400 font-bold">user@sg-admin</span>
                        <span class="text-purple-400 font-bold">MINGW64</span>
                        <span class="text-yellow-400 font-bold">/var/log/system</span>
                    </div>

                    <div class="text-neutral-500">
                        Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }} (Total {{ $logs->total() }} entries)
                    </div>
                </div>

                <div class="flex items-center gap-2 text-xs">
                    <span class="text-emerald-400 font-bold">$</span>
                    <span class="animate-pulse text-white font-bold">_</span>
                </div>
            </div>

            {{-- Git Terminal Footer Pagination --}}
            @if ($logs->hasPages())
                <div class="border-t border-neutral-800 bg-[#161719] p-4 sm:px-6">
                    <div class="terminal-pagination">
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script>
            // Toggle Context JSON viewer
            function toggleLogContext(id) {
                const el = document.getElementById(id);
                const arrow = document.getElementById(id + '-arrow');
                if (el) {
                    if (el.classList.contains('hidden')) {
                        el.classList.remove('hidden');
                        if (arrow) arrow.classList.add('rotate-180');
                    } else {
                        el.classList.add('hidden');
                        if (arrow) arrow.classList.remove('rotate-180');
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                // Real-time DOM Grep Filter for terminal rows
                const filterInput = document.getElementById('terminal-filter-input');
                const logRows = document.querySelectorAll('.log-entry-row');

                if (filterInput) {
                    filterInput.addEventListener('input', (e) => {
                        const query = e.target.value.toLowerCase().trim();

                        logRows.forEach((row) => {
                            const text = row.textContent.toLowerCase();
                            if (query === '' || text.includes(query)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                }

                // Copy Terminal Output
                const copyBtn = document.getElementById('btn-copy-terminal');
                const copyText = document.getElementById('copy-terminal-text');

                if (copyBtn) {
                    copyBtn.addEventListener('click', () => {
                        const logRows = document.querySelectorAll('.log-entry-row');
                        const textToCopy = Array.from(logRows)
                            .filter(row => row.style.display !== 'none')
                            .map(row => row.innerText.replace(/\n+/g, ' '))
                            .join('\n');

                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(textToCopy);
                        } else {
                            const textarea = document.createElement('textarea');
                            textarea.value = textToCopy;
                            document.body.appendChild(textarea);
                            textarea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textarea);
                        }

                        if (copyText) {
                            const original = copyText.textContent;
                            copyText.textContent = 'Copied!';
                            copyBtn.classList.add('text-emerald-400');
                            setTimeout(() => {
                                copyText.textContent = original;
                                copyBtn.classList.remove('text-emerald-400');
                            }, 2000);
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
