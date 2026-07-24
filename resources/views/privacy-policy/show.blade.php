@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@section('content')
    @php
        $theme = auth()->user()?->roleTheme() ?? [
            'hero_bg' => 'bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.15),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)]',
            'hero_glow' => 'bg-gold/8',
            'hero_shimmer' => 'via-gold/35',
            'badge_border' => 'border-gold/20',
            'badge_bg' => 'bg-gold/8',
            'badge_text' => 'text-gold-soft/90',
            'dot' => 'bg-gold',
            'gradient_text' => 'from-gold-soft to-champagne',
        ];
        $content = old('content', $policy?->content ?? '');
        $contentEn = old('content_en', $policy?->content_en ?? '');
    @endphp

    <section class="space-y-6">
        <div
            class="relative overflow-hidden rounded-[28px] border border-white/8 {{ $theme['hero_bg'] }} px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)] motion-safe:motion-preset-slide-down-sm lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full {{ $theme['hero_glow'] }} blur-[64px]"></div>
            <div
                class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent {{ $theme['hero_shimmer'] }} to-transparent">
            </div>

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[60ms]">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border {{ $theme['badge_border'] }} {{ $theme['badge_bg'] }} px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] {{ $theme['badge_text'] }}">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full {{ $theme['dot'] }}"></span>
                        Privacy Document
                    </span>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                            Kebijakan
                            <span
                                class="bg-gradient-to-r {{ $theme['gradient_text'] }} bg-clip-text text-transparent">Privasi</span>
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-smoke">
                            Kelola satu dokumen utama kebijakan privasi perusahaan dalam format panjang. Halaman ini
                            menyimpan satu record terpusat.
                        </p>
                    </div>
                </div>

                <div
                    class="flex items-center gap-3 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[100ms]">
                    <span class="rounded-xl border border-white/8 bg-white/5 px-4 py-2.5 text-sm text-smoke">
                        {{ filled(strip_tags($content)) ? 'Dokumen tersedia' : 'Belum diisi' }}
                    </span>
                    @if ($policy?->updated_at)
                        <span class="rounded-xl border border-gold/20 bg-gold/8 px-4 py-2.5 text-sm text-gold-soft">
                            Update {{ $policy->updated_at->format('d M Y H:i') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div
                class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3 text-sm text-red-200 shadow-lg">
                <i class="fa-solid fa-triangle-exclamation text-base text-red-400"></i>
                <div>
                    <p class="font-medium text-red-300">Terdapat kesalahan pengisian:</p>
                    <p class="text-xs text-red-200/80">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('privacy-policy.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-6 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[100ms]">
                <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
                    <div class="flex items-center gap-3 border-b border-white/6 pb-4">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-gold/20 bg-gold/10 text-gold-soft">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Dokumen Utama
                            </p>
                            <h3 class="text-base font-semibold text-white">Isi Kebijakan Privasi</h3>
                        </div>
                    </div>

                    <div class="grid gap-6">
                        <div>
                            <label for="content"
                                class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                Konten Kebijakan Privasi (ID) <span class="text-gold-soft">*</span>
                            </label>
                            <x-forms.tinymce-editor id="content" name="content" :value="$content" :height="750"
                                placeholder="Masukkan seluruh isi kebijakan privasi..."
                                helper="Konten utama dalam Bahasa Indonesia." required />
                        </div>

                        <div>
                            <label for="content_en"
                                class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                Privacy Policy Content (EN)
                            </label>
                            <x-forms.tinymce-editor id="content_en" name="content_en" :value="$contentEn" :height="750"
                                placeholder="Write the English version of the privacy policy..."
                                helper="Versi Bahasa Inggris untuk kebutuhan bilingual." />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-white/6 pt-6">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-6 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_24px_rgba(199,161,90,0.4)]">
                    <i class="fa-solid fa-check text-xs"></i>
                    Simpan Kebijakan Privasi
                </button>
            </div>
        </form>
    </section>
@endsection
