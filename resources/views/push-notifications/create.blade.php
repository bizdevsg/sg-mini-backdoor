@extends('layouts.app')

@section('title', 'Buat Notifikasi Push')

@section('content')
    @php
        $source = $draft['source'] ?? null;
        $lockedTopic = $draft['topic'] ?? null;
        $selectedTopic = old('topic', $lockedTopic?->value ?? $topics[0]->value);
        $titleValue = old('title', $draft['title'] ?? '');
        $descriptionValue = old('description', $draft['description'] ?? '');
        $sourceUrl = match (true) {
            $source instanceof \App\Models\Berita => route('berita.show', ['beritaCategory' => $source->category, 'berita' => $source]),
            $source instanceof \App\Models\Banner => route('banner.edit', $source),
            default => null,
        };
        $sourceTitle = $source instanceof \App\Models\Berita ? $source->title_id : $source?->title;
        $inputClasses = 'w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12';
    @endphp

    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6 lg:px-9 lg:py-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft">Broadcast ke semua pengguna</span>
                    <h1 class="mt-2 text-2xl font-semibold tracking-[-0.04em] text-ivory lg:text-3xl">Buat Notifikasi Push</h1>
                    <p class="mt-2 text-sm text-smoke">
                        @if ($source)
                            Judul dan isi diambil dari konten sumber. Silakan rapikan sebelum dikirim.
                        @else
                            Tulis pengumuman atau promosi singkat yang akan muncul di HP pengguna.
                        @endif
                    </p>
                </div>
                <a href="{{ $sourceUrl ?? route('push-notifications.index') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl border border-black/10 bg-black/5 px-4 py-2.5 text-sm text-smoke hover:text-ivory">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                </a>
            </div>
        </div>

        @include('push-notifications.partials.mode-banner')

        @if ($cooldownSeconds > 0)
            <div class="flex items-center gap-3 rounded-2xl border border-black/10 bg-black/5 px-5 py-4 text-sm text-smoke">
                <i class="fa-solid fa-hourglass-half text-gold-soft"></i>
                Broadcast terakhir baru saja dikirim. Broadcast berikutnya bisa dilakukan sekitar
                <span class="font-semibold text-ivory">{{ max(1, (int) ceil($cooldownSeconds / 60)) }} menit</span> lagi.
            </div>
        @endif

        @if ($errors->any())
            <div class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-50/40 px-4 py-3 text-sm text-red-800 shadow-lg">
                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                <div>
                    <p class="font-medium text-red-700">Notifikasi belum dikirim:</p>
                    <p class="text-xs text-red-800/80">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('push-notifications.store') }}" method="POST" class="space-y-6" data-push-notification-form>
            @csrf
            @if ($source)
                <input type="hidden" name="source_type" value="{{ $draft['source_type'] }}">
                <input type="hidden" name="source_id" value="{{ $draft['source_id'] }}">
            @endif

            <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
                <div class="space-y-6">
                    @if ($source)
                        <div class="rounded-2xl border border-black/8 bg-black/3 p-5">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Konten Sumber</p>
                            <div class="mt-3 flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-ivory">{{ $sourceTitle }}</p>
                                    <p class="mt-1 text-xs text-smoke">
                                        {{ $draft['source_type'] === 'berita' ? 'Berita' : 'Banner' }} #{{ $draft['source_id'] }}
                                        · dikirim sebagai
                                        <span class="font-mono text-champagne/80">{{ $draft['source_type'] === 'berita' ? 'news_id' : 'promotion_id' }}={{ $draft['source_id'] }}</span>
                                        agar aplikasi membuka item ini saat notifikasi di-tap.
                                    </p>
                                    @if ($source instanceof \App\Models\Banner && ! $source->is_active)
                                        <p class="mt-2 text-xs font-medium text-red-700">Banner ini sedang nonaktif, sehingga mungkin tidak bisa dibuka di aplikasi.</p>
                                    @endif
                                    @unless ($source->is_notification)
                                        <p class="mt-2 text-xs font-medium text-red-700">"Tandai untuk Notifikasi" belum dicentang pada konten ini.</p>
                                    @endunless
                                </div>
                                @if ($sourceUrl)
                                    <a href="{{ $sourceUrl }}" class="shrink-0 text-xs text-gold-soft hover:underline">Lihat konten</a>
                                @endif
                            </div>

                            @if ($lastLiveSend)
                                <div class="mt-4 rounded-xl border border-black/10 bg-onyx/60 p-4">
                                    <p class="text-xs text-smoke">
                                        <i class="fa-solid fa-circle-check text-emerald-700"></i>
                                        Sudah di-broadcast {{ $lastLiveSend->created_at?->format('d M Y H:i') }}
                                        oleh {{ $lastLiveSend->user?->name ?? '-' }}.
                                    </p>
                                    <label class="mt-3 flex cursor-pointer items-start gap-3">
                                        <input type="hidden" name="resend" value="0">
                                        <input type="checkbox" name="resend" value="1" @checked(old('resend'))
                                            class="mt-0.5 h-4 w-4 rounded border-black/20 bg-onyx text-gold focus:ring-gold/30">
                                        <span class="text-xs leading-5 text-ivory">Kirim ulang. Saya paham semua pengguna akan menerima notifikasi ini lagi.</span>
                                    </label>
                                    @error('resend')
                                        <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            @error('source_id')
                                <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="rounded-2xl border border-black/8 bg-black/3 p-6">
                        <div class="border-b border-black/6 pb-4">
                            <h3 class="text-base font-semibold text-ivory">Isi Notifikasi</h3>
                            <p class="mt-1 text-xs text-smoke">Taruh informasi terpenting di awal; layar kunci biasanya hanya menampilkan 1–2 baris.</p>
                        </div>

                        <div class="mt-5 space-y-5">
                            <div>
                                <label for="topic" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                    Topic <span class="text-gold-soft">*</span>
                                </label>
                                @if ($lockedTopic)
                                    <input type="hidden" name="topic" value="{{ $lockedTopic->value }}">
                                    <div class="{{ $inputClasses }} border-black/8 text-smoke">{{ $lockedTopic->label() }}</div>
                                @else
                                    <select id="topic" name="topic" class="{{ $inputClasses }} {{ $errors->has('topic') ? 'border-red-400/60' : 'border-black/8' }}">
                                        @foreach ($topics as $topic)
                                            <option value="{{ $topic->value }}" @selected($selectedTopic === $topic->value)>{{ $topic->label() }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('topic')
                                    <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label for="title" class="block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                        Judul <span class="text-gold-soft">*</span>
                                    </label>
                                    <span class="font-mono text-[11px] text-smoke" data-char-counter="title" data-max="{{ $titleMax }}">0/{{ $titleMax }}</span>
                                </div>
                                <input id="title" name="title" type="text" maxlength="{{ $titleMax }}" value="{{ $titleValue }}"
                                    placeholder="Contoh: Emas Tembus Rekor Baru Hari Ini" required
                                    class="{{ $inputClasses }} {{ $errors->has('title') ? 'border-red-400/60' : 'border-black/8' }}">
                                @error('title')
                                    <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between">
                                    <label for="description" class="block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                                        Isi Pesan <span class="text-gold-soft">*</span>
                                    </label>
                                    <span class="font-mono text-[11px] text-smoke" data-char-counter="description" data-max="{{ $descriptionMax }}">0/{{ $descriptionMax }}</span>
                                </div>
                                <textarea id="description" name="description" rows="4" maxlength="{{ $descriptionMax }}" required
                                    placeholder="Contoh: Harga emas dunia naik 2% hari ini. Cek analisis lengkapnya di aplikasi."
                                    class="{{ $inputClasses }} {{ $errors->has('description') ? 'border-red-400/60' : 'border-black/8' }}">{{ $descriptionValue }}</textarea>
                                @error('description')
                                    <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <p class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">Target</p>
                                <div class="{{ $inputClasses }} border-black/8 text-smoke">
                                    <i class="fa-solid fa-users text-xs"></i> Semua pengguna aplikasi (broadcast)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-black/8 bg-black/3 p-5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Preview di HP</p>
                        <div class="mt-3 rounded-[28px] border border-black/10 bg-obsidian p-4">
                            <p class="text-center font-mono text-xs text-white/60">{{ now()->format('H:i') }}</p>
                            <div class="mt-4 rounded-2xl bg-white/90 p-3 shadow-lg">
                                <div class="flex items-center gap-2 text-[10px] uppercase tracking-[0.16em] text-smoke">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-md bg-gold text-obsidian">
                                        <i class="fa-solid fa-bell text-[9px]"></i>
                                    </span>
                                    Solid Gold · sekarang
                                </div>
                                <p class="mt-2 text-sm font-semibold text-ivory" data-preview="title">{{ $titleValue ?: 'Judul notifikasi' }}</p>
                                <p class="mt-1 text-xs leading-5 text-smoke" data-preview="description">{{ $descriptionValue ?: 'Isi pesan notifikasi akan tampil di sini.' }}</p>
                            </div>
                        </div>
                        <p class="mt-3 text-[11px] leading-5 text-smoke/70">Tampilan sebenarnya bisa sedikit berbeda tergantung jenis HP.</p>
                    </div>

                    <div class="rounded-2xl border border-black/8 bg-black/3 p-5">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-smoke/70">Konfirmasi</p>
                        <label for="confirmation" class="mt-3 block text-xs leading-5 text-smoke">
                            @if ($isDryRun)
                                Mode uji aktif, jadi notifikasi tidak sampai ke pengguna. Ketik
                            @else
                                Notifikasi akan diterima <span class="font-semibold text-ivory">semua pengguna</span> dan tidak bisa dibatalkan. Ketik
                            @endif
                            <span class="font-mono font-semibold text-ivory">{{ $confirmationWord }}</span> untuk melanjutkan.
                        </label>
                        <input id="confirmation" name="confirmation" type="text" autocomplete="off" required
                            placeholder="{{ $confirmationWord }}"
                            class="mt-3 {{ $inputClasses }} font-mono {{ $errors->has('confirmation') ? 'border-red-400/60' : 'border-black/8' }}">
                        @error('confirmation')
                            <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-black/6 pt-6">
                <a href="{{ $sourceUrl ?? route('push-notifications.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-black/5 px-5 py-2.5 text-sm font-medium text-smoke transition hover:bg-black/8 hover:text-ivory">
                    Batal
                </a>
                <button type="submit" data-confirm-submit data-confirm-intent="save"
                    data-confirm-title="{{ $isDryRun ? 'Jalankan dry run broadcast?' : 'Broadcast ke semua pengguna?' }}"
                    data-confirm-message="{{ $isDryRun ? 'Server akan memeriksa API key dan payload tanpa mengirim notifikasi ke pengguna.' : 'Notifikasi akan langsung dikirim ke semua pengguna aplikasi dan tidak bisa ditarik kembali.' }}"
                    data-confirm-action-label="{{ $isDryRun ? 'Ya, jalankan dry run' : 'Ya, broadcast sekarang' }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-6 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition hover:bg-gold-soft">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    {{ $isDryRun ? 'Kirim (Dry Run)' : 'Broadcast Sekarang' }}
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.querySelector('[data-push-notification-form]');

            if (!form) {
                return;
            }

            const placeholders = {
                title: 'Judul notifikasi',
                description: 'Isi pesan notifikasi akan tampil di sini.',
            };

            ['title', 'description'].forEach((name) => {
                const field = form.querySelector(`[name="${name}"]`);
                const counter = form.querySelector(`[data-char-counter="${name}"]`);
                const preview = form.querySelector(`[data-preview="${name}"]`);

                if (!field) {
                    return;
                }

                const sync = () => {
                    const length = [...field.value].length;
                    const max = Number(counter?.dataset.max || 0);

                    if (counter) {
                        counter.textContent = `${length}/${max}`;
                        counter.classList.toggle('text-red-700', max > 0 && length >= max);
                    }

                    if (preview) {
                        preview.textContent = field.value.trim() || placeholders[name];
                    }
                };

                field.addEventListener('input', sync);
                sync();
            });
        })();
    </script>
@endpush
