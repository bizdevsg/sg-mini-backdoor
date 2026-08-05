@extends('layouts.app')

@section('title', 'Dokumentasi API - ' . ($currentDocSection['label'] ?? 'Overview'))

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'hero_bg' =>
                'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.15),transparent),linear-gradient(160deg,rgba(21,17,13,0.05)_0%,rgba(21,17,13,0.01)_100%)]',
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
                <div class="flex flex-col gap-4 border-b border-black/10 pb-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-gold-soft/70">Dokumentasi API</p>
                        <h1 class="mt-2 text-2xl font-semibold text-ivory">{{ $currentDocSection['label'] }}</h1>
                        <p class="mt-1 text-sm text-smoke/70">
                            Halaman dokumentasi terpisah untuk {{ strtolower($currentDocSection['label']) }}.
                        </p>
                    </div>

                    <button type="button" data-open-doc-download-modal
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-medium text-gold-soft transition-colors hover:border-gold/40 hover:bg-gold/16 hover:text-ivory">
                        <i class="fa-solid fa-file-arrow-down text-xs"></i>
                        Download PDF Lengkap
                    </button>
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

    <div data-doc-download-modal
        @class(['fixed inset-0 z-[120] items-center justify-center p-4', 'hidden' => !$errors->any(), 'flex' => $errors->any()])
        aria-hidden="{{ $errors->any() ? 'false' : 'true' }}">
        <div data-doc-download-backdrop class="absolute inset-0 bg-black/75 backdrop-blur-sm"></div>

        <div
            class="relative w-full max-w-lg overflow-hidden rounded-3xl border border-black/10 bg-[linear-gradient(180deg,_#faf8f4_0%,_#f2ede3_100%)] text-champagne shadow-[0_30px_80px_rgba(0,0,0,0.25)]">
            <div class="border-b border-black/8 px-6 py-5">
                <p class="text-xs font-medium uppercase tracking-[0.22em] text-gold-soft/75">Sebelum download</p>
                <h3 class="mt-2 text-xl font-semibold tracking-[-0.03em] text-ivory">
                    Konfirmasi Kebutuhan Dokumen
                </h3>
                <p class="mt-2 text-sm leading-6 text-smoke/70">
                    Isi tujuan dan penerima dokumen ini. Data ini dicatat di System Logs untuk membantu penelusuran
                    jika file dokumentasi bocor ke publik.
                </p>
            </div>

            <form method="POST" action="{{ route('api-documentation.pdf') }}" target="_blank"
                class="space-y-5 px-6 py-6">
                @csrf

                <div>
                    <label for="doc-download-purpose" class="text-xs font-medium uppercase tracking-[0.14em] text-gold-soft/70">
                        Tujuan / Kebutuhan
                    </label>
                    <textarea id="doc-download-purpose" name="purpose" rows="3" required maxlength="500"
                        placeholder="Contoh: Integrasi API untuk aplikasi mobile versi 2.0"
                        class="mt-2 w-full rounded-xl border border-black/10 bg-black/5 px-4 py-3 text-sm text-ivory placeholder-smoke/40 focus:border-gold/40 focus:outline-none focus:ring-1 focus:ring-gold/30">{{ old('purpose') }}</textarea>
                    @error('purpose')
                        <p class="mt-1.5 text-xs text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="doc-download-recipient" class="text-xs font-medium uppercase tracking-[0.14em] text-gold-soft/70">
                        Diberikan Kepada
                    </label>
                    <input type="text" id="doc-download-recipient" name="recipient" required maxlength="255"
                        value="{{ old('recipient') }}"
                        placeholder="Nama, email, atau tim/vendor penerima dokumen"
                        class="mt-2 w-full rounded-xl border border-black/10 bg-black/5 px-4 py-3 text-sm text-ivory placeholder-smoke/40 focus:border-gold/40 focus:outline-none focus:ring-1 focus:ring-gold/30">
                    @error('recipient')
                        <p class="mt-1.5 text-xs text-red-700">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 pt-1 sm:flex-row sm:justify-end">
                    <button type="button" data-doc-download-cancel
                        class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-black/5 px-5 py-3 text-sm font-medium text-ivory transition-colors hover:bg-black/10">
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-gold-soft hover:text-white">
                        <i class="fa-solid fa-file-arrow-down text-xs"></i>
                        Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
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

                        button.classList.add('border-emerald-400/30', 'text-emerald-700');
                    } catch (error) {
                        console.error('Failed to copy text.', error);

                        if (label instanceof HTMLElement) {
                            label.textContent = errorLabel;
                        }

                        button.classList.add('border-red-400/30', 'text-red-700');
                    } finally {
                        window.setTimeout(() => {
                            if (label instanceof HTMLElement) {
                                label.textContent = defaultLabel;
                            }

                            button.classList.remove('border-emerald-400/30', 'text-emerald-700', 'border-red-400/30',
                                'text-red-700');
                        }, 1600);
                    }
                });
            });

        });
    </script>
@endPushOnce

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.querySelector('[data-doc-download-modal]');
            const trigger = document.querySelector('[data-open-doc-download-modal]');
            const cancelButton = document.querySelector('[data-doc-download-cancel]');
            const backdrop = document.querySelector('[data-doc-download-backdrop]');

            if (!(modal instanceof HTMLElement)) {
                return;
            }

            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');
            };

            trigger?.addEventListener('click', openModal);
            cancelButton?.addEventListener('click', closeModal);
            backdrop?.addEventListener('click', closeModal);

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape' || modal.classList.contains('hidden')) {
                    return;
                }

                closeModal();
            });
        });
    </script>
@endpush
