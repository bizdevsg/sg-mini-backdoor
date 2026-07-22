@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
    @php
        $complaintLink = $profile['complaint_link'] ?? '';
        $mapsEmbedUrl = $profile['maps_embed_url'] ?? '';
        $missionItems = old('mission', $profile['mission']);
        $missionEnItems = old('mission_en', $profile['mission_en'] ?? []);
        $visionItems = old('vision', $profile['vision']);
        $visionEnItems = old('vision_en', $profile['vision_en'] ?? []);

        $missionItems = is_array($missionItems) ? array_values($missionItems) : [];
        $missionEnItems = is_array($missionEnItems) ? array_values($missionEnItems) : [];
        $visionItems = is_array($visionItems) ? array_values($visionItems) : [];
        $visionEnItems = is_array($visionEnItems) ? array_values($visionEnItems) : [];

        $missionItems = array_pad($missionItems, max(count($missionItems), 3), '');
        $missionEnItems = array_pad($missionEnItems, max(count($missionEnItems), 3), '');
        $visionItems = array_pad($visionItems, max(count($visionItems), 3), '');
        $visionEnItems = array_pad($visionEnItems, max(count($visionEnItems), 3), '');
    @endphp

    <section class="space-y-6">

        {{-- ══════════════════════════════════════════════
             HERO HEADER
        ══════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-[28px] border border-white/8 bg-[radial-gradient(ellipse_70%_80%_at_0%_0%,rgba(199,161,90,0.15),transparent),linear-gradient(160deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0.01)_100%)] px-7 py-6 shadow-[0_24px_60px_rgba(0,0,0,0.3)] motion-safe:motion-preset-slide-down-sm lg:px-9 lg:py-8">
            <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-gold/8 blur-[64px]"></div>
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold/35 to-transparent"></div>

            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-3 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[60ms]">
                    <span class="inline-flex items-center gap-2 rounded-full border border-gold/20 bg-gold/8 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft/90">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold"></span>
                        Company Profile
                    </span>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                            Profil
                            <span class="bg-gradient-to-r from-gold-soft to-champagne bg-clip-text text-transparent">Perusahaan</span>
                        </h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-smoke">
                            Kelola identitas resmi perusahaan, visi & misi bilingual, lokasi peta, serta informasi kontak publik.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[100ms]">
                    @if (filled($complaintLink))
                        <a href="{{ $complaintLink }}" target="_blank" rel="noreferrer"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-white">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            Link Pengaduan
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Error Banner --}}
        @if ($errors->any())
            <div class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3 text-sm text-red-200 shadow-lg">
                <i class="fa-solid fa-triangle-exclamation text-base text-red-400"></i>
                <div>
                    <p class="font-medium text-red-300">Terdapat kesalahan pengisian:</p>
                    <p class="text-xs text-red-200/80">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        {{-- MAIN FORM --}}
        <form action="{{ route('company-profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_380px]">

                {{-- LEFT COLUMN: Identitas & Visi Misi --}}
                <div class="space-y-6 motion-safe:motion-preset-slide-right-sm motion-safe:motion-delay-[120ms]">

                    {{-- IDENTITAS PERUSAHAAN --}}
                    <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
                        <div class="flex items-center gap-3 border-b border-white/6 pb-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-gold/20 bg-gold/10 text-gold-soft">
                                <i class="fa-solid fa-building text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Identitas Utama</p>
                                <h3 class="text-base font-semibold text-white">Profil Perusahaan</h3>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="company_name" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Nama Perusahaan <span class="text-gold-soft">*</span>
                                </label>
                                <input type="text" id="company_name" name="company_name"
                                    value="{{ old('company_name', $profile['company_name']) }}"
                                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('company_name') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Masukkan nama resmi perusahaan" required>
                                @error('company_name')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Deskripsi Perusahaan (Bahasa Indonesia) <span class="text-gold-soft">*</span>
                                </label>
                                <textarea id="description" name="description" rows="6"
                                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm leading-relaxed text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('description') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Tuliskan deskripsi dan profil umum perusahaan..." required>{{ old('description', $profile['description']) }}</textarea>
                                @error('description')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description_en" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Company Description (English)
                                </label>
                                <textarea id="description_en" name="description_en" rows="6"
                                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm leading-relaxed text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('description_en') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Write company description in English...">{{ old('description_en', $profile['description_en'] ?? '') }}</textarea>
                                @error('description_en')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- MISI PERUSAHAAN --}}
                    <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
                        <div class="flex items-center gap-3 border-b border-white/6 pb-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-gold/20 bg-gold/10 text-gold-soft">
                                <i class="fa-solid fa-bullseye text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Tujuan Strategis</p>
                                <h3 class="text-base font-semibold text-white">Misi Perusahaan</h3>
                            </div>
                        </div>

                        <div class="grid gap-5 lg:grid-cols-2">
                            <div>
                                <div class="mb-3 flex items-center justify-between">
                                    <label class="text-xs font-semibold uppercase tracking-[0.14em] text-smoke">Daftar Misi (ID)</label>
                                    <span class="rounded-md border border-white/8 bg-onyx px-2 py-0.5 font-mono text-[10px] text-champagne/70">ID</span>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($missionItems as $index => $missionItem)
                                        <div class="relative">
                                            <input type="text" name="mission[]" value="{{ $missionItem }}"
                                                class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('mission') || $errors->has('mission.' . $index) ? 'border-red-400/60' : 'border-white/8' }}"
                                                placeholder="Misi {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                                @error('mission')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                                @foreach ($errors->get('mission.*') as $messages)
                                    @foreach ($messages as $message)
                                        <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                    @endforeach
                                @endforeach
                            </div>

                            <div>
                                <div class="mb-3 flex items-center justify-between">
                                    <label class="text-xs font-semibold uppercase tracking-[0.14em] text-smoke">Mission List (EN)</label>
                                    <span class="rounded-md border border-white/8 bg-onyx px-2 py-0.5 font-mono text-[10px] text-gold-soft">EN</span>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($missionEnItems as $index => $missionEnItem)
                                        <div class="relative">
                                            <input type="text" name="mission_en[]" value="{{ $missionEnItem }}"
                                                class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('mission_en') || $errors->has('mission_en.' . $index) ? 'border-red-400/60' : 'border-white/8' }}"
                                                placeholder="Mission {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                                @error('mission_en')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                                @foreach ($errors->get('mission_en.*') as $messages)
                                    @foreach ($messages as $message)
                                        <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- VISI PERUSAHAAN --}}
                    <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
                        <div class="flex items-center gap-3 border-b border-white/6 pb-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-gold/20 bg-gold/10 text-gold-soft">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Pandangan Masa Depan</p>
                                <h3 class="text-base font-semibold text-white">Visi Perusahaan</h3>
                            </div>
                        </div>

                        <div class="grid gap-5 lg:grid-cols-2">
                            <div>
                                <div class="mb-3 flex items-center justify-between">
                                    <label class="text-xs font-semibold uppercase tracking-[0.14em] text-smoke">Daftar Visi (ID)</label>
                                    <span class="rounded-md border border-white/8 bg-onyx px-2 py-0.5 font-mono text-[10px] text-champagne/70">ID</span>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($visionItems as $index => $visionItem)
                                        <div class="relative">
                                            <input type="text" name="vision[]" value="{{ $visionItem }}"
                                                class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('vision') || $errors->has('vision.' . $index) ? 'border-red-400/60' : 'border-white/8' }}"
                                                placeholder="Visi {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                                @error('vision')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                                @foreach ($errors->get('vision.*') as $messages)
                                    @foreach ($messages as $message)
                                        <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                    @endforeach
                                @endforeach
                            </div>

                            <div>
                                <div class="mb-3 flex items-center justify-between">
                                    <label class="text-xs font-semibold uppercase tracking-[0.14em] text-smoke">Vision List (EN)</label>
                                    <span class="rounded-md border border-white/8 bg-onyx px-2 py-0.5 font-mono text-[10px] text-gold-soft">EN</span>
                                </div>
                                <div class="space-y-3">
                                    @foreach ($visionEnItems as $index => $visionEnItem)
                                        <div class="relative">
                                            <input type="text" name="vision_en[]" value="{{ $visionEnItem }}"
                                                class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('vision_en') || $errors->has('vision_en.' . $index) ? 'border-red-400/60' : 'border-white/8' }}"
                                                placeholder="Vision {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                                @error('vision_en')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                                @foreach ($errors->get('vision_en.*') as $messages)
                                    @foreach ($messages as $message)
                                        <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN: Kontak & Alamat --}}
                <div class="space-y-6 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[140ms]">

                    {{-- KONTAK PERUSAHAAN --}}
                    <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-4">
                        <div class="flex items-center gap-3 border-b border-white/6 pb-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-gold/20 bg-gold/10 text-gold-soft">
                                <i class="fa-solid fa-address-book text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Kanal Publik</p>
                                <h3 class="text-base font-semibold text-white">Kontak Perusahaan</h3>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="phone" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Telepon Utama
                                </label>
                                <input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $profile['phone']) }}"
                                    class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('phone') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Contoh: (021) 555 1234">
                                @error('phone')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Email Resmi
                                </label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $profile['email']) }}"
                                    class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('email') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="info@perusahaan.com">
                                @error('email')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fax" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Nomor Fax
                                </label>
                                <input type="text" id="fax" name="fax"
                                    value="{{ old('fax', $profile['fax']) }}"
                                    class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('fax') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Contoh: (021) 555 5678">
                                @error('fax')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="complaint_link" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Link Layanan Pengaduan
                                </label>
                                <input type="url" id="complaint_link" name="complaint_link"
                                    value="{{ old('complaint_link', $profile['complaint_link']) }}"
                                    class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('complaint_link') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="https://perusahaan.com/pengaduan">
                                @error('complaint_link')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ALAMAT DAN MAPS --}}
                    <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-4">
                        <div class="flex items-center gap-3 border-b border-white/6 pb-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-gold/20 bg-gold/10 text-gold-soft">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Lokasi Fisik</p>
                                <h3 class="text-base font-semibold text-white">Alamat & Maps</h3>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="address" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Alamat Lengkap Perusahaan <span class="text-gold-soft">*</span>
                                </label>
                                <textarea id="address" name="address" rows="5"
                                    class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs leading-relaxed text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('address') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Tuliskan alamat domisili fisik kantor..." required>{{ old('address', $profile['address']) }}</textarea>
                                @error('address')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="maps_embed_url" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Maps Embed URL (Google Maps)
                                </label>
                                <input type="url" id="maps_embed_url" name="maps_embed_url"
                                    value="{{ old('maps_embed_url', $mapsEmbedUrl) }}"
                                    class="w-full rounded-xl border bg-onyx px-4 py-2.5 text-xs font-mono text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('maps_embed_url') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="https://www.google.com/maps/embed?...">
                                <p class="mt-1.5 text-[11px] text-smoke/60">Gunakan tautan HTML Embed dari Google Maps (bukan link biasa).</p>
                                @error('maps_embed_url')
                                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- PREVIEW MAPS --}}
                    <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3">
                        <div class="border-b border-white/6 px-6 py-4">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/60">Live Preview</p>
                            <h3 class="text-sm font-semibold text-white">Peta Lokasi Kantor</h3>
                        </div>

                        @if (filled($mapsEmbedUrl))
                            <iframe src="{{ $mapsEmbedUrl }}" class="h-64 w-full border-0" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                        @else
                            <div class="flex h-64 flex-col items-center justify-center gap-2 px-6 text-center text-xs text-smoke/50 bg-onyx/50">
                                <i class="fa-solid fa-map-location-dot text-xl text-smoke/30"></i>
                                Pratinjau peta akan ditampilkan di sini setelah URL Embed diisi.
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- BOTTOM ACTIONS --}}
            <div class="flex items-center justify-end gap-3 border-t border-white/6 pt-6">
                @if (filled($complaintLink))
                    <a href="{{ $complaintLink }}" target="_blank" rel="noreferrer"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-white">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        Buka Link Pengaduan
                    </a>
                @endif

                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-6 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_24px_rgba(199,161,90,0.4)]">
                    <i class="fa-solid fa-check text-xs"></i>
                    Simpan Profil Perusahaan
                </button>
            </div>
        </form>
    </section>
@endsection
