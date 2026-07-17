@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'User management',
            'title' => 'User Management',
            'description' => 'Kelola daftar user yang sudah terdaftar di sistem dan pantau informasi akun dasarnya.',
            'action' => [
                'href' => route('user-management.create'),
                'label' => 'Tambah User',
                'icon' => 'fa-solid fa-user-plus text-xs',
                'class' => 'inline-flex items-center justify-center gap-2 rounded-lg border border-white/8 bg-white/5 px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10',
            ],
        ])

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Total User</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $users->total() }}</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Admin</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $adminCount }}</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Superadmin</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $superadminCount }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
            <form action="{{ route('user-management.index') }}" method="GET"
                class="grid gap-4 lg:grid-cols-[1fr_220px_180px]">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-white">Cari user</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email"
                        class="w-full rounded-lg border border-white/8 bg-onyx px-4 py-3 text-sm text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15">
                </label>

                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-white">Role</span>
                    <select name="role"
                        class="w-full rounded-lg border border-white/8 bg-onyx px-4 py-3 text-sm text-champagne focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15">
                        <option value="">Semua role</option>
                        @foreach ($availableRoles as $roleOption)
                            <option value="{{ $roleOption->value }}" @selected($selectedRole === $roleOption)>
                                {{ $roleOption->label() }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <div class="flex items-end gap-3">
                    <button type="submit"
                        class="inline-flex flex-1 items-center justify-center rounded-lg border border-white/8 bg-white/6 px-4 py-3 text-sm font-medium text-white transition-colors hover:bg-white/10">
                        Filter
                    </button>
                    <a href="{{ route('user-management.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-white/8 bg-transparent px-4 py-3 text-sm font-medium text-smoke transition-colors hover:border-white/12 hover:text-white">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/4">
            @if ($users->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/8 bg-white/5 text-gold-soft">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold text-white">Belum ada user</h3>
                    <p class="mt-2 text-sm text-smoke">Data user akan muncul di sini setelah akun dibuat.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/8">
                        <thead class="bg-noir/70">
                            <tr class="text-left text-xs uppercase tracking-[0.18em] text-smoke">
                                <th class="px-6 py-4 font-medium">User</th>
                                <th class="px-6 py-4 font-medium">Email</th>
                                <th class="px-6 py-4 font-medium">Role</th>
                                <th class="px-6 py-4 font-medium">Joined</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 text-right font-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/6">
                            @foreach ($users as $managedUser)
                                <tr class="align-top transition-colors hover:bg-white/4">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-sm font-semibold text-obsidian">
                                                {{ mb_strtoupper(mb_substr($managedUser->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-white">{{ $managedUser->name }}</p>
                                                <p class="mt-1 text-sm text-smoke">ID #{{ $managedUser->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-smoke">
                                        {{ $managedUser->email }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $managedUser->isSuperadmin() ? 'border border-red-500/25 bg-red-500/10 text-red-200' : 'border border-blue-500/20 bg-blue-500/10 text-blue-100' }}">
                                            {{ $managedUser->role->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-smoke">
                                        {{ $managedUser->created_at?->format('d M Y, H:i') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('user-management.edit', $managedUser) }}"
                                                class="inline-flex items-center rounded-lg border border-white/8 bg-white/5 px-3 py-2 text-sm font-medium text-white transition-colors hover:bg-white/10">
                                                Edit
                                            </a>
                                            <form action="{{ route('user-management.destroy', $managedUser) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    data-confirm-submit
                                                    data-confirm-intent="delete"
                                                    data-confirm-title="Hapus user ini?"
                                                    data-confirm-message="User {{ $managedUser->name }} akan dihapus dari sistem. Tindakan ini tidak bisa dibatalkan."
                                                    data-confirm-action-label="Ya, hapus"
                                                    class="inline-flex items-center rounded-lg border border-red-500/25 bg-red-500/10 px-3 py-2 text-sm font-medium text-red-100 transition-colors hover:bg-red-500/20">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-white/8 px-6 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        @include('user-management.partials.confirm-modal')
    </section>
@endsection
