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
        <div class="grid gap-6 xl:grid-cols-[220px_1fr]">
            @include('api-documentation.partials.sidebar-nav')

            <div class="min-w-0 space-y-8">
                @include('api-documentation.partials.getting-started', [
                    'apiBaseUrl' => $apiBaseUrl,
                    'apiKeyHeader' => $apiKeyHeader,
                    'apiKeyValue' => $apiKeyValue,
                ])

                @include('api-documentation.partials.authentication', [
                    'apiKeyHeader' => $apiKeyHeader,
                    'apiKeyValue' => $apiKeyValue,
                    'headerExample' => $headerExample,
                ])

                @include('api-documentation.partials.endpoints', [
                    'endpointGroups' => $endpointGroups,
                ])

                @include('api-documentation.partials.query-params', [
                    'apiKeyHeader' => $apiKeyHeader,
                ])

                @include('api-documentation.partials.examples', [
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

            const sections = document.querySelectorAll('[id]');
            const navLinks = document.querySelectorAll('.doc-nav-link');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        navLinks.forEach((link) => {
                            const active = link.getAttribute('href') === '#' + entry.target.id;
                            link.classList.toggle('bg-white/5', active);
                            link.classList.toggle('text-white', active);
                            link.classList.toggle('text-smoke/70', !active);
                        });
                    }
                });
            }, {
                threshold: 0.4
            });

            sections.forEach((section) => observer.observe(section));
        });
    </script>
@endPushOnce
