@extends('layouts.app')

@section('title', 'Detail Kontak')

@section('content')
    <section class="space-y-6">
        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-3">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Detail kontak</p>
                    <h2 class="text-3xl font-semibold tracking-[-0.04em] text-white">{{ $massage->nama }}</h2>

                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="rounded-lg border border-white/8 bg-onyx px-3 py-1.5 font-mono text-champagne">
                            {{ $massage->id_laporan }}
                        </span>
                        <span class="rounded-lg border border-white/8 bg-onyx px-3 py-1.5 text-white">
                            {{ $massage->subjek }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('massages.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-white/5 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        Kembali
                    </a>

                    <a href="{{ $mailToUrl }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/8 bg-white/5 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        <i class="fa-solid fa-envelope text-xs"></i>
                        Balas Email
                    </a>

                    @if ($telUrl)
                        <a href="{{ $telUrl }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gold/20 bg-gold/10 px-4 py-3 text-sm font-medium text-gold-soft transition-colors hover:bg-gold/15">
                            <i class="fa-solid fa-phone text-xs"></i>
                            Telepon
                        </a>
                    @endif

                    @if ($whatsAppUrl)
                        <a href="{{ $whatsAppUrl }}" target="_blank" rel="noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
            <div class="space-y-6">
                <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                    <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
                    <div class="mt-4 grid gap-3">
                        <a href="{{ $mailToUrl }}"
                            class="inline-flex items-center justify-between rounded-xl border border-white/8 bg-onyx px-4 py-3 text-sm text-white transition-colors hover:bg-white/5">
                            <span class="inline-flex items-center gap-3">
                                <i class="fa-solid fa-envelope text-gold-soft"></i>
                                Email
                            </span>
                            <span class="text-smoke">Balas sekarang</span>
                        </a>

                        @if ($telUrl)
                            <a href="{{ $telUrl }}"
                                class="inline-flex items-center justify-between rounded-xl border border-white/8 bg-onyx px-4 py-3 text-sm text-white transition-colors hover:bg-white/5">
                                <span class="inline-flex items-center gap-3">
                                    <i class="fa-solid fa-phone text-gold-soft"></i>
                                    Telepon
                                </span>
                                <span class="text-smoke">Hubungi langsung</span>
                            </a>
                        @endif

                        @if ($whatsAppUrl)
                            <a href="{{ $whatsAppUrl }}" target="_blank" rel="noreferrer"
                                class="inline-flex items-center justify-between rounded-xl border border-white/8 bg-onyx px-4 py-3 text-sm text-white transition-colors hover:bg-white/5">
                                <span class="inline-flex items-center gap-3">
                                    <i class="fa-brands fa-whatsapp text-gold-soft"></i>
                                    WhatsApp
                                </span>
                                <span class="text-smoke">Kirim pesan</span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                    <h3 class="text-lg font-semibold text-white">Informasi Kontak</h3>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div>
                            <dt class="text-smoke">Nama</dt>
                            <dd class="mt-1 text-white">{{ $massage->nama }}</dd>
                        </div>
                        <div>
                            <dt class="text-smoke">Email</dt>
                            <dd class="mt-1 break-all text-white">{{ $massage->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-smoke">No. Telepon</dt>
                            <dd class="mt-1 text-white">{{ $massage->no_tlp }}</dd>
                        </div>
                        <div>
                            <dt class="text-smoke">Dikirim</dt>
                            <dd class="mt-1 text-white">{{ $massage->created_at?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-smoke">Diperbarui</dt>
                            <dd class="mt-1 text-white">{{ $massage->updated_at?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                    <h3 class="text-lg font-semibold text-white">Subjek</h3>
                    <p class="mt-4 text-base leading-7 text-smoke">{{ $massage->subjek }}</p>
                </div>

                <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-white">Isi Pesan</h3>
                        <span class="rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                            {{ $massage->id_laporan }}
                        </span>
                    </div>

                    <div class="mt-4 whitespace-pre-line text-sm leading-7 text-smoke">
                        {{ $massage->massage }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
