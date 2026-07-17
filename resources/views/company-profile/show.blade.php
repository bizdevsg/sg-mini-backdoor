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
        @include('components.molecules.page-header', [
            'eyebrow' => 'Company profile',
            'title' => 'Profil Perusahaan',
            'description' =>
                'Kelola identitas perusahaan, visi misi, alamat, peta, dan seluruh kanal kontak publik.',
        ])

        @if ($errors->any())
            <div class="rounded-xl border border-red-500/30 bg-red-950/30 px-4 py-3 text-sm text-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('company-profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_360px]">
                <div class="space-y-6">
                    <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                        @include('components.atoms.meta-label', ['text' => 'Identitas'])

                        <div class="mt-5 space-y-4">
                            <div class="lg:col-span-2">
                                <label for="company_name" class="mb-2 block text-sm font-medium text-white">Nama
                                    Perusahaan</label>
                                <input type="text" id="company_name" name="company_name"
                                    value="{{ old('company_name', $profile['company_name']) }}"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('company_name') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Masukkan nama perusahaan" required>
                                @error('company_name')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="mb-2 block text-sm font-medium text-white">Deskripsi
                                    Perusahaan</label>
                                <textarea id="description" name="description" rows="8"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('description') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Tulis deskripsi singkat perusahaan" required>{{ old('description', $profile['description']) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description_en" class="mb-2 block text-sm font-medium text-white">Company
                                    Description (English)</label>
                                <textarea id="description_en" name="description_en" rows="8"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('description_en') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Write the company description in English">{{ old('description_en', $profile['description_en'] ?? '') }}</textarea>
                                @error('description_en')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                            <div class="flex items-center gap-4">
                                @include('components.atoms.icon-badge', [
                                    'icon' => 'fa-solid fa-bullseye',
                                    'highlight' => true,
                                ])
                                <div>
                                    @include('components.atoms.meta-label', ['text' => 'Misi'])
                                    <h3 class="mt-1 text-xl font-semibold text-white">Misi Perusahaan</h3>
                                </div>
                            </div>

                            <div class="mt-5 grid gap-5 lg:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-white">Daftar Misi</label>
                                    <div class="grid gap-3">
                                        @foreach ($missionItems as $index => $missionItem)
                                            <input type="text" name="mission[]" value="{{ $missionItem }}"
                                                class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('mission') || $errors->has('mission.' . $index) ? 'border-red-400/60' : 'border-white/8' }}"
                                                placeholder="Misi {{ $index + 1 }}">
                                        @endforeach
                                    </div>
                                    @error('mission')
                                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                    @enderror
                                    @foreach ($errors->get('mission.*') as $messages)
                                        @foreach ($messages as $message)
                                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                        @endforeach
                                    @endforeach
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-white">Mission List (English)</label>
                                    <div class="grid gap-3">
                                        @foreach ($missionEnItems as $index => $missionEnItem)
                                            <input type="text" name="mission_en[]" value="{{ $missionEnItem }}"
                                                class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('mission_en') || $errors->has('mission_en.' . $index) ? 'border-red-400/60' : 'border-white/8' }}"
                                                placeholder="Mission {{ $index + 1 }}">
                                        @endforeach
                                    </div>
                                    @error('mission_en')
                                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                    @enderror
                                    @foreach ($errors->get('mission_en.*') as $messages)
                                        @foreach ($messages as $message)
                                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                            <div class="flex items-center gap-4">
                                @include('components.atoms.icon-badge', [
                                    'icon' => 'fa-solid fa-eye',
                                    'highlight' => true,
                                ])
                                <div>
                                    @include('components.atoms.meta-label', ['text' => 'Visi'])
                                    <h3 class="mt-1 text-xl font-semibold text-white">Visi Perusahaan</h3>
                                </div>
                            </div>

                            <div class="mt-5 grid gap-5 lg:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-white">Daftar Visi</label>
                                    <div class="grid gap-3">
                                        @foreach ($visionItems as $index => $visionItem)
                                            <input type="text" name="vision[]" value="{{ $visionItem }}"
                                                class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('vision') || $errors->has('vision.' . $index) ? 'border-red-400/60' : 'border-white/8' }}"
                                                placeholder="Visi {{ $index + 1 }}">
                                        @endforeach
                                    </div>
                                    @error('vision')
                                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                    @enderror
                                    @foreach ($errors->get('vision.*') as $messages)
                                        @foreach ($messages as $message)
                                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                        @endforeach
                                    @endforeach
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-white">Vision List (English)</label>
                                    <div class="grid gap-3">
                                        @foreach ($visionEnItems as $index => $visionEnItem)
                                            <input type="text" name="vision_en[]" value="{{ $visionEnItem }}"
                                                class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('vision_en') || $errors->has('vision_en.' . $index) ? 'border-red-400/60' : 'border-white/8' }}"
                                                placeholder="Vision {{ $index + 1 }}">
                                        @endforeach
                                    </div>
                                    @error('vision_en')
                                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                    @enderror
                                    @foreach ($errors->get('vision_en.*') as $messages)
                                        @foreach ($messages as $message)
                                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                        <div class="flex items-center gap-4">
                            @include('components.atoms.icon-badge', [
                                'icon' => 'fa-solid fa-address-book',
                                'highlight' => true,
                            ])
                            <div>
                                @include('components.atoms.meta-label', ['text' => 'Kontak'])
                                <h3 class="mt-1 text-xl font-semibold text-white">Kontak Perusahaan</h3>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5">
                            <div>
                                <label for="phone" class="mb-2 block text-sm font-medium text-white">Telepon</label>
                                <input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $profile['phone']) }}"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('phone') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Contoh: (021) 555 1234">
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="mb-2 block text-sm font-medium text-white">Email</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $profile['email']) }}"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('email') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="company@example.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fax" class="mb-2 block text-sm font-medium text-white">Fax</label>
                                <input type="text" id="fax" name="fax"
                                    value="{{ old('fax', $profile['fax']) }}"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('fax') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Masukkan nomor fax">
                                @error('fax')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="complaint_link" class="mb-2 block text-sm font-medium text-white">Link
                                    Pengaduan</label>
                                <input type="url" id="complaint_link" name="complaint_link"
                                    value="{{ old('complaint_link', $profile['complaint_link']) }}"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('complaint_link') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="https://example.com/pengaduan">
                                @error('complaint_link')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                        <div class="flex items-center gap-4">
                            @include('components.atoms.icon-badge', ['icon' => 'fa-solid fa-location-dot'])
                            <div>
                                @include('components.atoms.meta-label', ['text' => 'Alamat'])
                                <h3 class="mt-1 text-xl font-semibold text-white">Alamat dan Maps</h3>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5">
                            <div>
                                <label for="address" class="mb-2 block text-sm font-medium text-white">Alamat
                                    Perusahaan</label>
                                <textarea id="address" name="address" rows="7"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('address') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="Tulis alamat lengkap perusahaan" required>{{ old('address', $profile['address']) }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="maps_embed_url" class="mb-2 block text-sm font-medium text-white">Maps Embed
                                    URL</label>
                                <input type="url" id="maps_embed_url" name="maps_embed_url"
                                    value="{{ old('maps_embed_url', $mapsEmbedUrl) }}"
                                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('maps_embed_url') ? 'border-red-400/60' : 'border-white/8' }}"
                                    placeholder="https://www.google.com/maps/embed?...">
                                <p class="mt-2 text-xs text-smoke">Gunakan URL embed dari Google Maps, bukan link share
                                    biasa.</p>
                                @error('maps_embed_url')
                                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/4">
                        <div class="border-b border-white/8 px-6 py-5">
                            @include('components.atoms.meta-label', ['text' => 'Preview Maps'])
                            <h3 class="mt-2 text-xl font-semibold text-white">Preview Lokasi</h3>
                        </div>

                        @if (filled($mapsEmbedUrl))
                            <iframe src="{{ $mapsEmbedUrl }}" class="h-80 w-full" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                        @else
                            <div class="flex h-80 items-center justify-center px-6 text-center text-sm text-smoke">
                                Preview maps akan muncul setelah URL embed diisi.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                @if (filled($complaintLink))
                    <a href="{{ $complaintLink }}" target="_blank" rel="noreferrer"
                        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-white/5 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        Buka Link Pengaduan
                    </a>
                @endif

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
                    Simpan Profil
                </button>
            </div>
        </form>
    </section>
@endsection
