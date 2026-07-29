@extends('layouts.app')

@section('title', 'Dokumentasi API - ' . ($currentDocSection['label'] ?? 'Overview'))

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
        <div class="grid gap-6 xl:grid-cols-[220px_1fr]">
            @include('api-documentation.partials.sidebar-nav')

            <div class="min-w-0 space-y-8">
                <div class="flex flex-col gap-4 border-b border-white/10 pb-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-gold-soft/70">Dokumentasi API</p>
                        <h1 class="mt-2 text-2xl font-semibold text-white">{{ $currentDocSection['label'] }}</h1>
                        <p class="mt-1 text-sm text-smoke/70">
                            Halaman dokumentasi terpisah untuk {{ strtolower($currentDocSection['label']) }}.
                        </p>
                    </div>

                    <a href="{{ route('api-documentation.pdf') }}" target="_blank"
                        rel="noopener"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-medium text-gold-soft transition-colors hover:border-gold/40 hover:bg-gold/16 hover:text-white">
                        <i class="fa-solid fa-file-arrow-down text-xs"></i>
                        Download PDF Lengkap
                    </a>
                </div>

                @include($currentDocSection['partial'], [
                    'apiBaseUrl' => $apiBaseUrl,
                    'apiKeyHeader' => $apiKeyHeader,
                    'apiKeyValue' => $apiKeyValue,
                    'headerExample' => $headerExample,
                    'mobileClientHeader' => $mobileClientHeader,
                    'mobileClientValue' => $mobileClientValue,
                    'webOriginExample' => $webOriginExample,
                    'endpointGroups' => $endpointGroups,
                    'requestExamples' => $requestExamples,
                ])
            </div>
        </div>
    </section>
@endsection

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const copyText = async (text) => {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(text);
                    return;
                }

                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.setAttribute('readonly', '');
                textarea.style.position = 'absolute';
                textarea.style.left = '-9999px';

                document.body.appendChild(textarea);
                textarea.select();

                const copied = document.execCommand('copy');

                document.body.removeChild(textarea);

                if (!copied) {
                    throw new Error('Copy command failed.');
                }
            };

            document.querySelectorAll('[data-copy-button]').forEach((button) => {
                if (!(button instanceof HTMLButtonElement) || button.dataset.copyInitialized === 'true') {
                    return;
                }

                button.dataset.copyInitialized = 'true';

                button.addEventListener('click', async () => {
                    const text = button.dataset.copyText ?? '';

                    if (!text) {
                        return;
                    }

                    const label = button.querySelector('[data-copy-label]');
                    const defaultLabel = button.dataset.copyLabelDefault ?? 'Copy';
                    const successLabel = button.dataset.copyLabelSuccess ?? 'Copied';
                    const errorLabel = button.dataset.copyLabelError ?? 'Failed';

                    try {
                        await copyText(text);

                        if (label instanceof HTMLElement) {
                            label.textContent = successLabel;
                        }

                        button.classList.add('border-emerald-400/30', 'text-emerald-300');
                    } catch (error) {
                        console.error('Failed to copy text.', error);

                        if (label instanceof HTMLElement) {
                            label.textContent = errorLabel;
                        }

                        button.classList.add('border-red-400/30', 'text-red-300');
                    } finally {
                        window.setTimeout(() => {
                            if (label instanceof HTMLElement) {
                                label.textContent = defaultLabel;
                            }

                            button.classList.remove('border-emerald-400/30', 'text-emerald-300', 'border-red-400/30',
                                'text-red-300');
                        }, 1600);
                    }
                });
            });

        });
    </script>
@endPushOnce
