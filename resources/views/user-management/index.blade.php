@extends('layouts.app')

@section('title', 'User Management')

@section('content')
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
                        User Management
                    </span>
                    <div>
                        <h1 class="text-2xl font-semibold tracking-[-0.04em] text-white lg:text-3xl">
                            Pengelolaan User &
                            <span class="bg-gradient-to-r from-gold-soft to-champagne bg-clip-text text-transparent">Hak Akses</span>
                        </h1>
                        <p class="mt-2 max-w-xl text-sm leading-6 text-smoke">
                            Kelola daftar pengguna terdaftar di sistem, kelola peran (role), dan pantau hak akses akun.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 motion-safe:motion-preset-slide-left-sm motion-safe:motion-delay-[100ms]">
                    <a href="{{ route('user-management.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft hover:shadow-[0_6px_24px_rgba(199,161,90,0.4)]">
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        Tambah User
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             STATS CARDS
        ══════════════════════════════════════════════ --}}
        <div class="grid gap-4 sm:grid-cols-3 motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[80ms]">
            <div class="flex items-center gap-4 rounded-2xl border border-white/8 bg-white/3 p-5 transition-all hover:border-white/14 hover:bg-white/5">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-smoke">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">Total User</p>
                    <p class="mt-0.5 text-2xl font-semibold text-white">{{ $users->total() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-blue-500/20 bg-blue-500/5 p-5 transition-all hover:border-blue-500/30 hover:bg-blue-500/8">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-blue-500/25 bg-blue-500/12 text-blue-400">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-blue-400/80">Admin Operasional</p>
                    <p class="mt-0.5 text-2xl font-semibold text-white">{{ $adminCount }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-red-500/20 bg-red-500/5 p-5 transition-all hover:border-red-500/30 hover:bg-red-500/8">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-red-500/25 bg-red-500/12 text-red-400">
                    <i class="fa-solid fa-user-shield text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-red-400/80">Superadmin</p>
                    <p class="mt-0.5 text-2xl font-semibold text-white">{{ $superadminCount }}</p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             SEARCH & FILTER
        ══════════════════════════════════════════════ --}}
        <div class="rounded-2xl border border-white/8 bg-white/3 px-5 py-4 motion-safe:motion-preset-fade-lg motion-safe:motion-delay-[80ms]">
            <form action="{{ route('user-management.index') }}" method="GET"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">
                {{-- Search input with icon --}}
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center">
                        <i class="fa-solid fa-magnifying-glass text-xs text-smoke/60"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau email user..."
                        class="w-full rounded-xl border border-white/8 bg-onyx py-2.5 pl-9 pr-4 text-sm text-champagne placeholder:text-smoke/50 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors">
                </div>

                {{-- Role selector --}}
                <div class="w-full sm:w-48">
                    <select name="role"
                        class="w-full rounded-xl border border-white/8 bg-onyx py-2.5 px-3.5 text-sm text-champagne focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12 transition-colors">
                        <option value="">Semua role</option>
                        @foreach ($availableRoles as $roleOption)
                            <option value="{{ $roleOption->value }}" @selected($selectedRole === $roleOption)>
                                {{ $roleOption->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-gold/25 bg-gold/10 px-4 py-2.5 text-sm font-medium text-gold-soft transition-all duration-200 hover:border-gold/40 hover:bg-gold/18">
                        <i class="fa-solid fa-filter text-[10px]"></i>
                        Filter
                    </button>
                    <a href="{{ route('user-management.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-white/8 bg-transparent px-4 py-2.5 text-sm font-medium text-smoke transition-all duration-200 hover:border-white/15 hover:text-white">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════
             TABLE / EMPTY STATE
        ══════════════════════════════════════════════ --}}
        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/3 motion-safe:motion-preset-slide-up-sm motion-safe:motion-delay-[120ms]">
            @if ($users->isEmpty())
                {{-- Empty state --}}
                <div class="flex flex-col items-center px-6 py-20 text-center">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-3xl bg-gold/8 blur-xl"></div>
                        <div class="relative flex h-20 w-20 items-center justify-center rounded-3xl border border-gold/20 bg-gold/10 text-gold-soft">
                            <i class="fa-solid fa-users text-2xl"></i>
                        </div>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-white">Belum ada user</h3>
                    <p class="mt-2 max-w-sm text-sm leading-6 text-smoke">
                        @if (request('search') || request('role'))
                            Tidak ditemukan akun user yang sesuai kriteria filter.
                        @else
                            Mulai dengan membuat akun pengguna pertama di sistem.
                        @endif
                    </p>
                    @if (!request('search') && !request('role'))
                        <a href="{{ route('user-management.create') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition-all duration-200 hover:bg-gold-soft">
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            Tambah User
                        </a>
                    @endif
                </div>
            @else
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-white/6 bg-noir/50 text-left text-[10px] font-semibold uppercase tracking-[0.18em] text-smoke/70">
                                <th class="px-6 py-3.5">User</th>
                                <th class="px-4 py-3.5">Email</th>
                                <th class="px-4 py-3.5">Role</th>
                                <th class="hidden px-4 py-3.5 sm:table-cell">Bergabung</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach ($users as $managedUser)
                                <tr class="group align-top transition-colors duration-150 hover:bg-white/3">
                                    {{-- User info --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-gold/12 text-sm font-bold text-gold-soft">
                                                {{ mb_strtoupper(mb_substr($managedUser->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-white transition-colors group-hover:text-gold-soft">{{ $managedUser->name }}</p>
                                                <p class="mt-0.5 font-mono text-[10px] text-smoke/60">ID #{{ $managedUser->id }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Email --}}
                                    <td class="px-4 py-4">
                                        <span class="text-xs text-champagne/90">{{ $managedUser->email }}</span>
                                    </td>

                                    {{-- Role --}}
                                    <td class="px-4 py-4">
                                        @if ($managedUser->isSuperadmin())
                                            <span class="inline-flex items-center gap-1.5 rounded-md border border-red-500/25 bg-red-500/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-red-300">
                                                <i class="fa-solid fa-shield-halved text-[9px]"></i>
                                                {{ $managedUser->role->label() }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-md border border-blue-500/20 bg-blue-500/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.1em] text-blue-300">
                                                <i class="fa-solid fa-user text-[9px]"></i>
                                                {{ $managedUser->role->label() }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Joined --}}
                                    <td class="hidden px-4 py-4 sm:table-cell">
                                        <p class="text-xs font-medium text-champagne/80">{{ $managedUser->created_at?->format('d M Y') ?? '-' }}</p>
                                        <p class="mt-0.5 text-[10px] text-smoke/60">{{ $managedUser->created_at?->format('H:i') ?? '' }}</p>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-500/25 bg-emerald-500/10 px-2.5 py-1 text-[10px] font-medium text-emerald-300">
                                            <span class="h-1 w-1 rounded-full bg-emerald-400"></span>
                                            Aktif
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('user-management.edit', $managedUser) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/8 px-3 py-1.5 text-xs font-medium text-gold-soft transition-all duration-150 hover:border-gold/35 hover:bg-gold/15"
                                                title="Edit user">
                                                <i class="fa-solid fa-pen text-[10px]"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('user-management.destroy', $managedUser) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    data-confirm-submit
                                                    data-confirm-intent="delete"
                                                    data-confirm-title="Hapus user ini?"
                                                    data-confirm-message="User {{ $managedUser->name }} akan dihapus dari sistem. Tindakan ini tidak bisa dibatalkan."
                                                    data-confirm-action-label="Ya, hapus"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-400/25 bg-red-500/8 px-3 py-1.5 text-xs font-medium text-red-300/80 transition-all duration-150 hover:border-red-400/40 hover:bg-red-500/16 hover:text-red-200"
                                                    title="Hapus user">
                                                    <i class="fa-solid fa-trash text-[10px]"></i>
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

                {{-- Footer: count + pagination --}}
                <div class="flex flex-col items-start justify-between gap-3 border-t border-white/6 bg-noir/30 px-6 py-4 sm:flex-row sm:items-center">
                    <p class="text-xs text-smoke">
                        Menampilkan
                        <span class="font-medium text-champagne/80">{{ $users->firstItem() }}–{{ $users->lastItem() }}</span>
                        dari <span class="font-medium text-champagne/80">{{ $users->total() }}</span> akun user
                    </p>
                    <div class="text-sm">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>

    </section>
@endsection
