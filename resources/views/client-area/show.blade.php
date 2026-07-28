@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
    @php
        $clientAreaDev = (bool) ($settings['client_area_dev'] ?? false);
        $clientAreaProd = (bool) ($settings['client_area_prod'] ?? false);
        $tawkToDev = (bool) ($settings['tawk_to_dev'] ?? false);
        $tawkToProd = (bool) ($settings['tawk_to_prod'] ?? false);
        $activeCount =
            ($clientAreaDev ? 1 : 0) + ($clientAreaProd ? 1 : 0) + ($tawkToDev ? 1 : 0) + ($tawkToProd ? 1 : 0);

        $sections = [
            [
                'key' => 'website-access',
                'title' => 'Website Access',
                'description' => 'Kontrol siapa yang bisa mengakses client area di masing-masing environment.',
                'items' => [
                    [
                        'key' => 'dev',
                        'label' => 'Client Area Development',
                        'description' => 'Buka client area untuk keperluan development atau staging.',
                        'enabled' => $clientAreaDev,
                        'icon' => 'fa-code-branch',
                    ],
                    [
                        'key' => 'prod',
                        'label' => 'Client Area Production',
                        'description' => 'Buka client area untuk website live yang diakses pengguna.',
                        'enabled' => $clientAreaProd,
                        'icon' => 'fa-globe',
                    ],
                ],
            ],
            [
                'key' => 'integrations',
                'title' => 'Integrations',
                'description' => 'Tampilkan atau sembunyikan widget live chat Tawk.to tanpa perlu deploy ulang.',
                'items' => [
                    [
                        'key' => 'tawk_to_dev',
                        'label' => 'Tawk.to Development',
                        'description' => 'Tampilkan widget live chat di environment development.',
                        'enabled' => $tawkToDev,
                        'icon' => 'fa-headset',
                    ],
                    [
                        'key' => 'tawk_to_prod',
                        'label' => 'Tawk.to Production',
                        'description' => 'Tampilkan widget live chat di environment production.',
                        'enabled' => $tawkToProd,
                        'icon' => 'fa-comments',
                    ],
                ],
            ],
        ];

        $payloadPreview = json_encode(
            [
                'data' => [
                    'dev' => $clientAreaDev,
                    'prod' => $clientAreaProd,
                    'tawk_to_dev' => $tawkToDev,
                    'tawk_to_prod' => $tawkToProd,
                ],
            ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
        );

        $curlExample = "curl -X GET \"{$apiBaseUrl}\" \\\n  -H \"{$apiKeyHeader}: {YOUR_API_KEY}\"";
    @endphp

    <section class="mx-auto space-y-8">
        <div class="border-b border-white/10 pb-6">
            <h1 class="text-xl font-semibold text-white sm:text-2xl">Website Settings</h1>
            <p class="mt-1 text-sm text-smoke/70">
                Atur visibilitas Client Area, integrasi Tawk.to, dan pengamanan API publik untuk environment development dan production.
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[200px_minmax(0,1fr)]">
            @include('client-area.partials.settings-nav', ['sections' => $sections])

            <div class="min-w-0 space-y-8">
                @include('client-area.partials.status-strip', ['activeCount' => $activeCount])

                @foreach ($sections as $section)
                    @include('client-area.partials.settings-section', ['section' => $section])
                @endforeach

                @include('client-area.partials.api-security-section', ['settings' => $settings])

                @include('client-area.partials.api-access', [
                    'apiBaseUrl' => $apiBaseUrl,
                    'apiKeyHeader' => $apiKeyHeader,
                    'payloadPreview' => $payloadPreview,
                    'curlExample' => $curlExample,
                ])
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const copyText = async (text) => {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(text);
                    return;
                }

                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            };

            document.querySelectorAll('[data-copy-text]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const label = button.querySelector('[data-copy-label]');
                    const originalLabel = label ? label.textContent : '';

                    try {
                        await copyText(button.getAttribute('data-copy-text') || '');

                        if (label) {
                            label.textContent = 'Tersalin';
                        }
                    } catch (error) {
                        if (label) {
                            label.textContent = 'Gagal';
                        }
                    } finally {
                        if (label) {
                            window.setTimeout(() => {
                                label.textContent = originalLabel;
                            }, 1500);
                        }
                    }
                });
            });

            document.querySelectorAll('.js-toggle-form').forEach((form) => {
                form.addEventListener('submit', function() {
                    const button = this.querySelector('button[type="submit"]');
                    const knob = this.querySelector('.js-switch-knob');

                    if (button) {
                        button.disabled = true;
                        button.classList.add('opacity-60');
                    }

                    if (knob) {
                        knob.classList.add('animate-pulse');
                    }
                });
            });
        });
    </script>
@endpush
