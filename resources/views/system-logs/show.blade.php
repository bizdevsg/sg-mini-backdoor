@extends('layouts.app')

@section('title', 'System Log Monitoring')

@section('content')
    <section class="space-y-4">
        <div class="overflow-hidden rounded-xl border border-neutral-800 bg-[#0c0c0c] font-mono"
            style="box-shadow: 0 30px 80px rgba(0,0,0,0.9), 0 0 0 1px rgba(255,255,255,0.04);">

            <div class="select-none border-b border-neutral-800 bg-[#1a1a1c] px-4 py-2.5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <div class="h-3 w-3 cursor-pointer rounded-full border border-[#e0443e] bg-[#ff5f57] transition-all hover:brightness-110"
                                title="Close"></div>
                            <div class="h-3 w-3 cursor-pointer rounded-full border border-[#d4a012] bg-[#febc2e] transition-all hover:brightness-110"
                                title="Minimize"></div>
                            <div class="h-3 w-3 cursor-pointer rounded-full border border-[#1aab29] bg-[#28c840] transition-all hover:brightness-110"
                                title="Maximize"></div>
                        </div>

                        <div class="h-4 w-px bg-neutral-700"></div>

                        <div class="flex items-center gap-2 text-[11px]">
                            <i class="fa-solid fa-code-branch text-[#28c840]"></i>
                            <span class="font-semibold text-neutral-300">git log</span>
                            <span class="text-neutral-600">-</span>
                            <span class="text-[#febc2e]">sg-admin</span>
                            <span class="text-neutral-600">/</span>
                            <span class="font-bold text-[#57b6f9]">{{ $activeCategory }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 rounded border border-[#28c840]/30 bg-[#28c840]/10 px-2 py-0.5 text-[10px] font-bold text-[#28c840]">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-[#28c840]"></span>
                            LIVE
                        </span>
                        <button type="button" id="btn-copy-terminal"
                            class="inline-flex cursor-pointer items-center gap-1.5 rounded border border-neutral-700 bg-neutral-900/80 px-2.5 py-1 text-[11px] text-neutral-400 transition-all hover:border-neutral-500 hover:text-white active:scale-95">
                            <i class="fa-regular fa-copy text-[10px] text-[#febc2e]"></i>
                            <span id="copy-terminal-text">Copy</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-2 border-b border-neutral-800 bg-[#111113] px-4 py-2.5 sm:px-5">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-2">
                        <div class="text-[12px] leading-snug">
                            <div>
                                <span class="font-bold text-[#bf7fff]">User@DESKTOP-SG</span>
                                <span class="text-white"> </span>
                                <span class="font-bold text-[#26b9b9]">MINGW64</span>
                                <span class="text-white"> </span>
                                <span class="text-[#febc2e]">/c/laragon/www/sg-admin</span>
                                <span class="text-white"> </span>
                                <span class="text-[#26b9b9]">({{ $activeCategory }})</span>
                            </div>
                            <div>
                                <span class="text-white">$ </span>
                                <span class="text-white">git log --oneline --graph --decorate </span>
                                <span class="text-[#febc2e]">--branch={{ $activeCategory }}</span>
                            </div>
                        </div>

                        @if (in_array($activeCategory, ['api', 'data'], true))
                            <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                                <span class="shrink-0 font-bold text-neutral-600">--grep-module:</span>
                                <a href="{{ route('system-logs.show', ['category' => $activeCategory]) }}"
                                    class="inline-flex items-center gap-1 rounded border px-2 py-0.5 transition-all active:scale-95 {{ $activeModule === '' ? 'border-sky-500/40 bg-sky-500/10 font-bold text-sky-300' : 'border-neutral-800 text-neutral-500 hover:border-neutral-700 hover:text-neutral-300' }}">
                                    *all
                                </a>
                                @foreach ($moduleMeta as $module => $label)
                                    @php
                                        $moduleCount = (int) ($moduleCounts[$module] ?? 0);
                                    @endphp
                                    @if ($moduleCount > 0)
                                        <a href="{{ route('system-logs.show', ['category' => $activeCategory, 'module' => $module]) }}"
                                            class="inline-flex items-center gap-1 rounded border px-2 py-0.5 transition-all active:scale-95 {{ $activeModule === $module ? 'border-sky-500/40 bg-sky-500/10 font-bold text-sky-300' : 'border-neutral-800 text-neutral-500 hover:border-neutral-700 hover:text-neutral-300' }}">
                                            {{ $label }}
                                            <span class="text-[9px] text-neutral-700">({{ $moduleCount }})</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div
                        class="flex items-center gap-2 rounded border border-zinc-700 px-2 py-1 text-sm focus-within:border-yellow-500">
                        <i class="fa-solid fa-magnifying-glass text-xs text-yellow-500/50"></i>
                        <input type="text" id="terminal-filter-input" placeholder='Cari baris log "..."'
                            class="min-w-0 flex-1 bg-transparent text-[#febc2e] placeholder-neutral-700 caret-[#28c840] focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="min-h-svh bg-[#0c0c0c]" id="terminal-console-body">
                <div class="flex items-center justify-between border-b border-neutral-900 px-5 py-2 text-[10px]">
                    <div class="flex items-center gap-2.5">
                        <span class="font-bold text-[#febc2e]">commit HEAD</span>
                        <span class="text-neutral-700">&rarr;</span>
                        <span class="text-[#57b6f9]">refs/heads/{{ $activeCategory }}</span>
                        <span class="text-neutral-700">&bull;</span>
                        <span class="text-neutral-500">{{ $logs->total() }} entries</span>
                        <span class="text-neutral-700">&bull;</span>
                        <span class="text-neutral-500">page {{ $logs->currentPage() }}/{{ $logs->lastPage() }}</span>
                    </div>
                    <span class="hidden text-neutral-800 sm:inline">q to quit | space to expand</span>
                </div>

                <div class="select-text font-mono text-[12px] leading-normal" id="log-list-container">
                    @forelse ($logs as $log)
                        @php
                            $eventUpper = strtoupper((string) $log->event);
                            $commitHash = substr(md5($log->id . $log->created_at), 0, 7);
                            $hasContext = is_array($log->context) && !empty($log->context);
                            $logId = 'log-ctx-' . $log->id;

                            if (
                                str_contains($eventUpper, 'LOGIN') ||
                                str_contains($eventUpper, 'AUTH') ||
                                str_contains($eventUpper, 'SUCCESS')
                            ) {
                                $evColor = 'text-[#28c840] border-[#28c840]/25 bg-[#28c840]/5';
                                $graphColor = 'text-[#28c840]';
                                $borderAccent = 'hover:border-l-[#28c840]';
                            } elseif (
                                str_contains($eventUpper, 'UPDATE') ||
                                str_contains($eventUpper, 'PUT') ||
                                str_contains($eventUpper, 'EDIT') ||
                                str_contains($eventUpper, 'PATCH')
                            ) {
                                $evColor = 'text-[#febc2e] border-[#febc2e]/25 bg-[#febc2e]/5';
                                $graphColor = 'text-[#febc2e]';
                                $borderAccent = 'hover:border-l-[#febc2e]';
                            } elseif (
                                str_contains($eventUpper, 'DELETE') ||
                                str_contains($eventUpper, 'REMOVE') ||
                                str_contains($eventUpper, 'FAIL') ||
                                str_contains($eventUpper, 'ERROR') ||
                                str_contains($eventUpper, 'REJECT')
                            ) {
                                $evColor = 'text-[#ff5f57] border-[#ff5f57]/25 bg-[#ff5f57]/5';
                                $graphColor = 'text-[#ff5f57]';
                                $borderAccent = 'hover:border-l-[#ff5f57]';
                            } elseif (
                                str_contains($eventUpper, 'CREATE') ||
                                str_contains($eventUpper, 'ADD') ||
                                str_contains($eventUpper, 'INSERT') ||
                                str_contains($eventUpper, 'POST')
                            ) {
                                $evColor = 'text-[#57b6f9] border-[#57b6f9]/25 bg-[#57b6f9]/5';
                                $graphColor = 'text-[#57b6f9]';
                                $borderAccent = 'hover:border-l-[#57b6f9]';
                            } else {
                                $evColor = 'text-neutral-400 border-neutral-700/40 bg-neutral-900/30';
                                $graphColor = 'text-neutral-600';
                                $borderAccent = 'hover:border-l-neutral-600';
                            }

                            $moduleKey = null;
                            $statusCode = null;
                            $statusColor = 'text-[#28c840]';

                            if (in_array($activeCategory, ['api', 'data'], true) && is_array($log->context)) {
                                $moduleKey = (string) ($log->subject ?? ($log->context['module'] ?? 'unknown'));
                                $statusCode = (int) ($log->context['status_code'] ?? 200);

                                if ($statusCode >= 500) {
                                    $statusColor = 'text-[#ff5f57]';
                                } elseif ($statusCode >= 400) {
                                    $statusColor = 'text-[#febc2e]';
                                }
                            }
                        @endphp

                        <div class="log-entry-row group border-b border-b-neutral-900/80 border-l-2 border-neutral-900 transition-all duration-100 hover:bg-white/[0.012] {{ $borderAccent }}"
                            data-log-id="{{ $log->id }}">
                            <div class="px-3 py-2.5">
                                <button class="flex w-full items-start text-left" type="button"
                                    onclick="toggleLogContext('{{ $logId }}')">
                                    <div class="flex w-5 shrink-0 flex-col items-center gap-0 pt-0.5">
                                        <span class="{{ $graphColor }} text-[11px] font-bold leading-none">*</span>
                                        @if (!$loop->last)
                                            <span class="mt-0.5 text-[11px] leading-none text-neutral-800">|</span>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1 space-y-1.5 pl-2">
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                            <span class="text-[11px] font-bold tracking-wider text-[#febc2e]">{{ $commitHash }}</span>
                                            <span class="select-none text-[10px] text-neutral-700">|</span>
                                            <span class="text-[11px] text-neutral-500">{{ $log->created_at?->format('Y-m-d') }}</span>
                                            <span class="text-[10px] text-neutral-700">{{ $log->created_at?->format('H:i:s') }}</span>
                                            <span
                                                class="inline-flex items-center rounded border px-1.5 py-px text-[10px] font-bold uppercase leading-tight tracking-widest {{ $evColor }}">{{ $log->event }}</span>
                                            @if (in_array($activeCategory, ['api', 'data'], true) && $statusCode !== null)
                                                <span class="font-mono text-[11px] font-bold {{ $statusColor }}">HTTP {{ $statusCode }}</span>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-px text-[11px]">
                                            <span class="select-none text-neutral-600">Author:</span>
                                            <span class="font-semibold text-white">{{ $log->user?->name ?? 'system' }}</span>
                                            @if ($log->user?->email)
                                                <span class="text-neutral-600">&lt;{{ $log->user->email }}&gt;</span>
                                            @endif
                                            @if ($log->ip_address)
                                                <span class="select-none text-neutral-700">|</span>
                                                <span class="font-mono text-[10px] text-[#28c840]/70">{{ $log->ip_address }}</span>
                                            @endif
                                        </div>

                                        <div class="border-l border-neutral-800 pl-1 text-[11px] leading-snug text-neutral-300">
                                            {{ $log->description }}
                                        </div>

                                        @if ($activeCategory === 'data' && is_array($log->context))
                                            <div
                                                class="inline-flex flex-wrap items-center gap-x-3 gap-y-0.5 rounded border border-neutral-800/80 bg-neutral-900/50 px-3 py-1 text-[10px]">
                                                <span class="font-semibold uppercase tracking-wider text-neutral-600">ctx</span>
                                                <span class="select-none text-neutral-700">|</span>
                                                <span>module: <strong
                                                        class="text-[#57b6f9]">{{ $moduleMeta[$moduleKey] ?? $moduleKey }}</strong></span>
                                                @if (isset($log->context['action_label']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>action: <strong
                                                            class="text-sky-400">{{ strtoupper((string) $log->context['action_label']) }}</strong></span>
                                                @endif
                                                @if (isset($log->context['path']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>path: <strong
                                                            class="text-[#febc2e]">{{ $log->context['path'] }}</strong></span>
                                                @endif
                                                @if (isset($log->context['duration_ms']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>latency:
                                                        <strong
                                                            class="{{ (int) $log->context['duration_ms'] > 500 ? 'text-[#ff5f57]' : 'text-[#28c840]' }}">
                                                            {{ $log->context['duration_ms'] }}ms
                                                        </strong>
                                                    </span>
                                                @endif
                                                @if (isset($log->context['target']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>target: <strong
                                                            class="text-[#febc2e]">{{ strtoupper((string) $log->context['target']) }}</strong></span>
                                                @endif
                                                @if (array_key_exists('previous_status', $log->context) && array_key_exists('new_status', $log->context))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>
                                                        status:
                                                        <strong
                                                            class="text-[#ff5f57]">{{ $log->context['previous_status'] ?? false ? 'aktif' : 'nonaktif' }}</strong>
                                                        <span class="mx-0.5 text-neutral-600">&rarr;</span>
                                                        <strong
                                                            class="text-[#28c840]">{{ $log->context['new_status'] ?? false ? 'aktif' : 'nonaktif' }}</strong>
                                                    </span>
                                                @endif
                                                @if (isset($log->context['purpose']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>tujuan: <strong
                                                            class="text-[#e2c78f]">{{ $log->context['purpose'] }}</strong></span>
                                                @endif
                                                @if (isset($log->context['recipient']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>diberikan ke: <strong
                                                            class="text-[#e2c78f]">{{ $log->context['recipient'] }}</strong></span>
                                                @endif
                                            </div>
                                        @endif

                                        @if ($activeCategory === 'api' && is_array($log->context) && $moduleKey)
                                            <div
                                                class="inline-flex flex-wrap items-center gap-x-3 gap-y-0.5 rounded border border-neutral-800/80 bg-neutral-900/50 px-3 py-1 text-[10px]">
                                                <span class="font-semibold uppercase tracking-wider text-neutral-600">ctx</span>
                                                <span class="select-none text-neutral-700">|</span>
                                                <span>module: <strong
                                                        class="text-[#57b6f9]">{{ $moduleMeta[$moduleKey] ?? $moduleKey }}</strong></span>
                                                @if (isset($log->context['method']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>method: <strong
                                                            class="text-sky-400">{{ strtoupper($log->context['method']) }}</strong></span>
                                                @endif
                                                @if (isset($log->context['path']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>path: <strong
                                                            class="text-[#febc2e]">{{ $log->context['path'] }}</strong></span>
                                                @endif
                                                @if (isset($log->context['duration_ms']))
                                                    <span class="select-none text-neutral-700">|</span>
                                                    <span>latency:
                                                        <strong
                                                            class="{{ (int) $log->context['duration_ms'] > 500 ? 'text-[#ff5f57]' : 'text-[#28c840]' }}">
                                                            {{ $log->context['duration_ms'] }}ms
                                                        </strong>
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        @if ($hasContext)
                                            <div
                                                class="inline-flex items-center gap-1.5 text-[10px] text-neutral-700 transition-colors hover:text-[#febc2e]">
                                                <i class="fa-solid fa-chevron-right text-[8px] transition-transform duration-200"
                                                    id="{{ $logId }}-arrow"></i>
                                                <span class="font-mono">git show {{ $commitHash }}:context.json</span>
                                            </div>
                                        @endif
                                    </div>
                                </button>

                                @if ($hasContext)
                                    <div id="{{ $logId }}" class="ml-7 mt-1.5 hidden">
                                        <div class="overflow-hidden rounded border border-neutral-800 bg-[#060608]">
                                            <div
                                                class="flex items-center justify-between border-b border-neutral-800 bg-[#0e0e10] px-3 py-1.5">
                                                <div class="flex items-center gap-2 text-[10px]">
                                                    <i class="fa-solid fa-file-code text-[9px] text-neutral-700"></i>
                                                    <span class="text-neutral-500">context.json</span>
                                                    <span class="text-neutral-700">&bull;</span>
                                                    <span class="text-neutral-700">{{ count($log->context) }} keys</span>
                                                </div>
                                                <button type="button" onclick="copyJson('json-{{ $log->id }}')"
                                                    id="copy-json-btn-{{ $log->id }}"
                                                    class="cursor-pointer text-[9px] text-neutral-600 transition-colors hover:text-[#febc2e]">
                                                    <i class="fa-regular fa-copy"></i> copy
                                                </button>
                                            </div>
                                            <div class="overflow-x-auto p-3" id="json-{{ $log->id }}">
                                                <pre class="whitespace-pre-wrap break-all font-mono text-[11px] leading-relaxed text-[#28c840]">{{ json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center gap-3 py-20 text-center">
                            <div class="select-none font-mono text-4xl font-bold text-neutral-800">( )</div>
                            <p class="font-mono text-sm text-neutral-500">
                                <span class="text-[#ff5f57]">fatal:</span> no commits found in
                                <span class="text-[#57b6f9]">'{{ $activeCategory }}'</span>
                            </p>
                            <p class="text-xs text-neutral-700">Belum ada log aktivitas yang tercatat.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if ($logs->hasPages())
                <div class="border-t border-neutral-800 bg-[#111113] px-5 py-3">
                    <div class="git-terminal-pagination">
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script>
            function toggleLogContext(id) {
                const el = document.getElementById(id);
                const arrow = document.getElementById(id + '-arrow');

                if (!el) return;

                const willShow = el.classList.contains('hidden');

                el.classList.toggle('hidden', !willShow);

                if (arrow) {
                    arrow.classList.toggle('rotate-90', willShow);
                }
            }

            function copyJson(containerId) {
                const el = document.getElementById(containerId);
                const id = containerId.replace('json-', '');
                const btn = document.getElementById('copy-json-btn-' + id);

                if (!el) return;

                const text = el.querySelector('pre')?.textContent ?? '';

                navigator.clipboard?.writeText(text).then(() => {
                    if (!btn) return;

                    const original = btn.innerHTML;

                    btn.innerHTML = '<i class="fa-solid fa-check"></i> copied';
                    btn.classList.add('text-[#28c840]');

                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.classList.remove('text-[#28c840]');
                    }, 2000);
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const filterInput = document.getElementById('terminal-filter-input');
                const container = document.getElementById('log-list-container');

                if (filterInput && container) {
                    filterInput.addEventListener('input', (event) => {
                        const query = event.target.value.toLowerCase().trim();

                        container.querySelectorAll('.log-entry-row').forEach((row) => {
                            row.style.display = !query || row.textContent.toLowerCase().includes(query) ? '' : 'none';
                        });
                    });
                }

                const copyBtn = document.getElementById('btn-copy-terminal');
                const copyText = document.getElementById('copy-terminal-text');

                if (copyBtn) {
                    copyBtn.addEventListener('click', () => {
                        const rows = document.querySelectorAll('.log-entry-row');
                        const output = Array.from(rows)
                            .filter((row) => row.style.display !== 'none')
                            .map((row) => row.innerText.replace(/\n\s*\n/g, '\n').trim())
                            .join('\n' + '-'.repeat(60) + '\n');

                        navigator.clipboard?.writeText(output);

                        if (copyText) {
                            copyText.textContent = 'Copied!';
                            copyBtn.classList.add('text-[#28c840]');

                            setTimeout(() => {
                                copyText.textContent = 'Copy';
                                copyBtn.classList.remove('text-[#28c840]');
                            }, 2000);
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
