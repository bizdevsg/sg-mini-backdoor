<div id="api-access" class="rounded-lg border border-white/10 bg-white/[0.02]">
    <details class="group">
        <summary
            class="flex cursor-pointer list-none items-center justify-between px-5 py-4 text-sm font-semibold text-white">
            <span>
                API Access
                <span class="ml-2 text-xs font-normal text-smoke/50">Untuk konsumsi status toggle dari frontend</span>
            </span>
            <i class="fa-solid fa-chevron-down text-xs text-smoke/50 transition-transform group-open:rotate-180"></i>
        </summary>

        <div class="space-y-4 border-t border-white/10 px-5 py-4">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">Endpoint</span>
                    <button type="button" data-copy-text="{{ $apiBaseUrl }}" class="text-xs text-smoke/60 hover:text-white">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <p class="mt-1 break-all rounded-md bg-black/30 px-3 py-2 font-mono text-xs text-smoke/80">
                    {{ $apiBaseUrl }}
                </p>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">Header Auth</span>
                    <button type="button" data-copy-text="{{ $apiKeyHeader }}" class="text-xs text-smoke/60 hover:text-white">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <p class="mt-1 break-all rounded-md bg-black/30 px-3 py-2 font-mono text-xs text-smoke/80">
                    {{ $apiKeyHeader }}
                </p>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">Response Preview</span>
                    <button type="button" data-copy-text="{{ $payloadPreview }}" class="text-xs text-smoke/60 hover:text-white">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <pre
                    class="mt-1 overflow-x-auto whitespace-pre-wrap break-all rounded-md bg-black/30 px-3 py-2 font-mono text-xs text-smoke/80">{{ $payloadPreview }}</pre>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">cURL Example</span>
                    <button type="button" data-copy-text="{{ $curlExample }}" class="text-xs text-smoke/60 hover:text-white">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <pre
                    class="mt-1 overflow-x-auto whitespace-pre-wrap break-all rounded-md bg-black/30 px-3 py-2 font-mono text-xs text-smoke/80">{{ $curlExample }}</pre>
            </div>

            <div class="rounded-md border border-white/8 bg-black/20 px-3 py-3 text-xs text-smoke/65">
                <p>Header tambahan `X-API-Key-Rotation-Notice` akan muncul jika notice rotasi API key diisi pada section `API &amp; Security`.</p>
            </div>
        </div>
    </details>
</div>
