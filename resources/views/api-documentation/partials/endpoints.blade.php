<div id="endpoints" class="scroll-mt-6">
    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-blue-500/25 bg-blue-500/15 text-xs font-bold text-blue-300">
            <i class="fa-solid fa-route text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-white">Endpoints</h2>
        <div class="h-px flex-1 bg-white/6"></div>
    </div>

    <div class="space-y-4">
        @foreach ($endpointGroups as $group)
            <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
                <div class="flex items-center gap-3 border-b border-white/6 px-5 py-3.5">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">{{ $group['title'] }}</p>
                        <p class="text-sm font-semibold text-white">{{ $group['description'] }}</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-white/6 bg-black/20 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/50">
                                <th class="w-20 px-5 py-3">Method</th>
                                <th class="px-5 py-3">Endpoint</th>
                                <th class="px-5 py-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach ($group['endpoints'] as $endpoint)
                                <tr class="align-top transition-colors hover:bg-white/3">
                                    <td class="px-5 py-3">
                                        @php
                                            $methodColor = match ($endpoint['method']) {
                                                'POST' => 'border-emerald-400/25 bg-emerald-400/10 text-emerald-300',
                                                'PUT', 'PATCH' => 'border-yellow-400/25 bg-yellow-400/10 text-yellow-300',
                                                'DELETE' => 'border-red-400/25 bg-red-400/10 text-red-300',
                                                default => 'border-blue-400/25 bg-blue-400/10 text-blue-200',
                                            };
                                        @endphp
                                        <span
                                            class="{{ $methodColor }} inline-flex rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.16em]">
                                            {{ $endpoint['method'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 font-mono text-xs text-champagne">{{ $endpoint['path'] }}</td>
                                    <td class="px-5 py-3 text-sm text-smoke">{{ $endpoint['notes'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
