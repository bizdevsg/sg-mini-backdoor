@php($managedUser = $managedUser ?? null)
@php($confirmTitle = $confirmTitle ?? 'Simpan data?')
@php($confirmMessage = $confirmMessage ?? 'Pastikan data yang diisi sudah benar sebelum dilanjutkan.')
@php($confirmActionLabel = $confirmActionLabel ?? 'Ya, simpan')

@if ($errors->any())
    <div class="flex items-center gap-3 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3 text-sm text-red-200 shadow-lg">
        <i class="fa-solid fa-triangle-exclamation text-base text-red-400"></i>
        <div>
            <p class="font-medium text-red-300">Terdapat kesalahan pengisian:</p>
            <p class="text-xs text-red-200/80">{{ $errors->first() }}</p>
        </div>
    </div>
@endif

<div class="space-y-6">

    {{-- Main Profile Card --}}
    <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
        <div class="border-b border-white/6 pb-4">
            <h3 class="text-base font-semibold text-white">Informasi Akun User</h3>
            <p class="mt-0.5 text-xs text-smoke">Lengkapi nama lengkap, email resmi, dan tingkat kewenangan (role).</p>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            {{-- Name --}}
            <div>
                <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Nama Lengkap <span class="text-gold-soft">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $managedUser?->name) }}"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('name') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="Contoh: Admin Operasional" required>
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Alamat Email <span class="text-gold-soft">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $managedUser?->email) }}"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('email') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="admin@perusahaan.com" required>
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Role / Peran System <span class="text-gold-soft">*</span>
                </label>
                <select id="role" name="role"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('role') ? 'border-red-400/60' : 'border-white/8' }}"
                    required>
                    <option value="" disabled @selected(old('role', $managedUser?->role?->value) === null)>Pilih role</option>
                    @foreach ($availableRoles as $roleOption)
                        <option value="{{ $roleOption->value }}" @selected(old('role', $managedUser?->role?->value) === $roleOption->value)>
                            {{ $roleOption->label() }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- Password & Security Card --}}
    <div class="rounded-2xl border border-white/8 bg-white/3 p-6 space-y-5">
        <div class="border-b border-white/6 pb-4">
            <h3 class="text-base font-semibold text-white">Keamanan & Password</h3>
            <p class="mt-0.5 text-xs text-smoke">
                {{ $managedUser ? 'Biarkan kolom password kosong jika tidak ingin mengganti password user.' : 'Atur password awal untuk akun user baru.' }}
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            {{-- Password --}}
            <div>
                <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    {{ $managedUser ? 'Password Baru (Opsional)' : 'Password' }} {{ !$managedUser ? '*' : '' }}
                </label>
                <input type="password" id="password" name="password"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors {{ $errors->has('password') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="{{ $managedUser ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                    @required(!$managedUser)>
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-red-300">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-smoke">
                    Konfirmasi Password {{ !$managedUser ? '*' : '' }}
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full rounded-xl border bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 border-white/8 transition-colors"
                    placeholder="Ulangi password" @required(!$managedUser)>
            </div>
        </div>

        {{-- Security Note Box --}}
        <div class="rounded-xl border border-gold/20 bg-gold/8 p-4 text-xs leading-6 text-gold-soft/90">
            <div class="flex items-center gap-2 font-semibold text-gold-soft mb-0.5">
                <i class="fa-solid fa-lock"></i> Pengamanan Akses Akun
            </div>
            Setiap password akan di-hash secara aman menggunakan algoritma standar Laravel (Bcrypt/Argon2).
        </div>
    </div>
</div>

{{-- Bottom Action Buttons --}}
<div class="flex items-center justify-end gap-3 border-t border-white/6 pt-6">
    <a href="{{ $cancelUrl }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/18 hover:bg-white/8 hover:text-white">
        Batal
    </a>
    <button type="submit"
        data-confirm-submit
        data-confirm-intent="save"
        data-confirm-title="{{ $confirmTitle }}"
        data-confirm-message="{{ $confirmMessage }}"
        data-confirm-action-label="{{ $confirmActionLabel }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold px-6 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_24px_rgba(199,161,90,0.4)]">
        <i class="fa-solid fa-check text-xs"></i>
        {{ $submitLabel }}
    </button>
</div>
