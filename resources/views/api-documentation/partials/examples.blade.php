<div id="examples" class="scroll-mt-6">
    <div class="mb-4 flex items-center gap-3">
        <div
            class="flex h-7 w-7 items-center justify-center rounded-lg border border-orange-500/25 bg-orange-500/15 text-xs font-bold text-orange-300">
            <i class="fa-solid fa-terminal text-[11px]"></i>
        </div>
        <h2 class="text-base font-semibold text-white">cURL Examples</h2>
        <div class="h-px flex-1 bg-white/6"></div>
    </div>

    <div class="space-y-4">
        @foreach ($requestExamples as $example)
            <div class="overflow-hidden rounded-2xl border border-white/8 bg-[#120f0c]">
                <div class="flex items-center justify-between gap-3 border-b border-white/6 bg-[#1b1611] px-4 py-2.5">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f56]"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-[#ffbd2e]"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-[#27c93f]"></span>
                    </div>
                    <p class="min-w-0 flex-1 truncate text-sm font-medium text-white">{{ $example['label'] }}</p>
                    <button type="button" data-copy-button data-copy-text="{{ $example['command'] }}"
                        data-copy-label-default="Copy" data-copy-label-success="Copied" data-copy-label-error="Failed"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-[11px] font-medium text-champagne/80 transition-colors hover:border-white/18 hover:bg-white/8 hover:text-white">
                        <i class="fa-regular fa-copy text-[10px]"></i>
                        <span data-copy-label>Copy</span>
                    </button>
                </div>
                <pre class="overflow-x-auto whitespace-pre-wrap break-all px-4 py-4 font-mono text-[11px] leading-6 text-champagne"><code>{{ $example['command'] }}</code></pre>
            </div>
        @endforeach
    </div>
</div>
