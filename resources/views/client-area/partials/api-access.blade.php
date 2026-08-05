<div id="api-access" class="rounded-lg border border-black/10 bg-onyx">
    <details class="group">
        <summary
            class="flex cursor-pointer list-none items-center justify-between px-5 py-4 text-sm font-semibold text-ivory">
            <span>
                API Access
                <span class="ml-2 text-xs font-normal text-smoke/50">Untuk frontend web dan mobile app</span>
            </span>
            <i class="fa-solid fa-chevron-down text-xs text-smoke/50 transition-transform group-open:rotate-180"></i>
        </summary>

        <div class="space-y-4 border-t border-black/10 px-5 py-4">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">Endpoint</span>
                    <button type="button" data-copy-text="{{ $apiBaseUrl }}"
                        class="text-xs text-smoke/60 hover:text-ivory cursor-pointer">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <p class="mt-1 break-all rounded-md bg-zinc-500 px-3 py-2 font-mono text-xs text-onyx">
                    {{ $apiBaseUrl }}
                </p>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">Header Auth</span>
                    <button type="button" data-copy-text="{{ $apiKeyHeader }}"
                        class="text-xs text-smoke/60 hover:text-ivory">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <p class="mt-1 break-all rounded-md bg-zinc-500 px-3 py-2 font-mono text-xs text-onyx">
                    {{ $apiKeyHeader }}
                </p>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">Response Preview</span>
                    <button type="button" data-copy-text="{{ $payloadPreview }}"
                        class="text-xs text-smoke/60 hover:text-ivory">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <pre class="mt-1 break-all rounded-md bg-zinc-500 px-3 py-2 font-mono text-xs text-onyx">{{ $payloadPreview }}</pre>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">Web cURL Example</span>
                    <button type="button" data-copy-text="{{ $curlExample }}"
                        class="text-xs text-smoke/60 hover:text-ivory">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <pre class="mt-1 break-all rounded-md bg-zinc-500 px-3 py-2 font-mono text-xs text-onyx">{{ $curlExample }}</pre>
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium uppercase tracking-wide text-smoke/50">Mobile App
                        Example</span>
                    <button type="button" data-copy-text="{{ $mobileCurlExample }}"
                        class="text-xs text-smoke/60 hover:text-ivory">
                        <i class="fa-regular fa-copy mr-1"></i><span data-copy-label>Salin</span>
                    </button>
                </div>
                <pre class="mt-1 break-all rounded-md bg-zinc-500 px-3 py-2 font-mono text-xs text-onyx">{{ $mobileCurlExample }}</pre>
            </div>

            <div class="rounded-md border border-black/8 bg-black/50 px-3 py-3 text-xs text-white">
                <p>`Allowed Origin Frontend` dipakai untuk web. Untuk mobile app tanpa `Origin`, kirim header
                    `X-Client-Type: mobile-app` bersama API key.</p>
                <p class="mt-2">Header tambahan `X-API-Key-Rotation-Notice` akan muncul jika notice rotasi API key
                    diisi pada section `API &amp; Security`.</p>
            </div>
        </div>
    </details>
</div>
