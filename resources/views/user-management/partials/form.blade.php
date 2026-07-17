@php($managedUser = $managedUser ?? null)
@php($confirmTitle = $confirmTitle ?? 'Simpan data?')
@php($confirmMessage = $confirmMessage ?? 'Pastikan data yang diisi sudah benar sebelum dilanjutkan.')
@php($confirmActionLabel = $confirmActionLabel ?? 'Ya, simpan')

@if ($errors->any())
    <div class="rounded-xl border border-red-500/30 bg-red-950/30 px-4 py-3 text-sm text-red-200">
        {{ $errors->first() }}
    </div>
@endif

<div class="space-y-6">
    <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
        <div class="grid gap-5 lg:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-white">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $managedUser?->name) }}"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('name') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="Contoh: Admin Operasional" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-white">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $managedUser?->email) }}"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('email') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="admin@example.com" required>
                @error('email')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="mb-2 block text-sm font-medium text-white">Role</label>
                <select id="role" name="role"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('role') ? 'border-red-400/60' : 'border-white/8' }}"
                    required>
                    <option value="" disabled @selected(old('role', $managedUser?->role?->value) === null)>Pilih role</option>
                    @foreach ($availableRoles as $roleOption)
                        <option value="{{ $roleOption->value }}" @selected(old('role', $managedUser?->role?->value) === $roleOption->value)>
                            {{ $roleOption->label() }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-xl border border-white/8 bg-onyx p-4 text-sm text-smoke">
                <p class="font-medium text-white">{{ $managedUser ? 'Update akses user' : 'Atur akses user baru' }}</p>
                <p class="mt-2 leading-6">
                    {{ $managedUser ? 'Biarkan password kosong jika tidak ingin mengganti password user ini.' : 'Password minimal 8 karakter dan akan langsung di-hash oleh Laravel.' }}
                </p>
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-white">
                    {{ $managedUser ? 'Password Baru' : 'Password' }}
                </label>
                <input type="password" id="password" name="password"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('password') ? 'border-red-400/60' : 'border-white/8' }}"
                    placeholder="{{ $managedUser ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                    @required(!$managedUser)>
                @error('password')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-white">
                    Konfirmasi Password
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 border-white/8"
                    placeholder="Ulangi password" @required(!$managedUser)>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
    <a href="{{ $cancelUrl }}"
        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-white/5 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
        Batal
    </a>
    <button type="button"
        data-confirm-submit
        data-confirm-intent="save"
        data-confirm-title="{{ $confirmTitle }}"
        data-confirm-message="{{ $confirmMessage }}"
        data-confirm-action-label="{{ $confirmActionLabel }}"
        class="inline-flex items-center justify-center rounded-lg bg-white px-5 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200">
        {{ $submitLabel }}
    </button>
</div>
