@extends('layouts.app')

@section('title', 'Notifikasi Push')

@section('content')
    @use('App\Enums\PushNotificationStatus')

    <section class="space-y-6">
        <div class="rounded-[28px] border border-black/8 bg-black/3 px-7 py-6 lg:px-9 lg:py-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.24em] text-gold-soft">Aplikasi Mini Micro</span>
                    <h1 class="mt-2 text-2xl font-semibold tracking-[-0.04em] text-ivory lg:text-3xl">Notifikasi Push</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-smoke">
                        Broadcast pengumuman dan promosi ke semua pengguna aplikasi, lalu pantau riwayat pengirimannya.
                    </p>
                </div>
                <a href="{{ route('push-notifications.create') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-gold px-5 py-2.5 text-sm font-semibold text-obsidian shadow-[0_4px_18px_rgba(199,161,90,0.28)] transition hover:bg-gold-soft">
                    <i class="fa-solid fa-plus text-xs"></i> Buat Notifikasi
                </a>
            </div>
        </div>

        @include('push-notifications.partials.mode-banner')

        <div class="grid gap-4 sm:grid-cols-4">
            <a href="{{ route('push-notifications.index') }}"
                class="rounded-2xl border p-5 transition hover:bg-black/5 {{ $activeStatus === null ? 'border-gold/35 bg-gold/8' : 'border-black/8 bg-black/3' }}">
                <p class="text-xs text-smoke">Total</p>
                <p class="mt-2 text-2xl font-semibold text-ivory">{{ $counts->sum() }}</p>
            </a>
            @foreach ([PushNotificationStatus::Sent, PushNotificationStatus::DryRun, PushNotificationStatus::Failed] as $statusCase)
                <a href="{{ route('push-notifications.index', ['status' => $statusCase->value]) }}"
                    class="rounded-2xl border p-5 transition hover:bg-black/5 {{ $activeStatus === $statusCase ? 'border-gold/35 bg-gold/8' : 'border-black/8 bg-black/3' }}">
                    <p class="text-xs text-smoke">{{ $statusCase->label() }}</p>
                    <p class="mt-2 text-2xl font-semibold text-ivory">{{ $counts[$statusCase->value] ?? 0 }}</p>
                </a>
            @endforeach
        </div>

        <form action="{{ route('push-notifications.index') }}" method="GET" class="flex gap-3 rounded-2xl border border-black/8 bg-black/3 p-4">
            @if ($activeStatus)
                <input type="hidden" name="status" value="{{ $activeStatus->value }}">
            @endif
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-xs text-smoke/60"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari judul atau isi notifikasi..."
                    class="w-full rounded-xl border border-black/8 bg-onyx py-2.5 pl-10 pr-4 text-sm text-champagne placeholder:text-smoke/40 focus:border-gold/35 focus:outline-none focus:ring-2 focus:ring-gold/12">
            </div>
            <button type="submit" class="rounded-xl border border-black/10 bg-black/5 px-5 py-2.5 text-sm font-medium text-ivory hover:bg-black/8">Cari</button>
            @if (request()->filled('search') || $activeStatus)
                <a href="{{ route('push-notifications.index') }}" class="rounded-xl px-4 py-2.5 text-sm text-smoke hover:text-ivory">Reset</a>
            @endif
        </form>

        <div class="overflow-hidden rounded-2xl border border-black/8 bg-black/3">
            @if ($notifications->isEmpty())
                <div class="flex min-h-72 flex-col items-center justify-center px-6 text-center">
                    <i class="fa-solid fa-bell text-4xl text-smoke/40"></i>
                    <h2 class="mt-5 text-lg font-semibold text-ivory">Belum ada riwayat notifikasi</h2>
                    <p class="mt-2 text-sm text-smoke">Buat notifikasi manual, atau kirim dari halaman Berita dan Banner.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[960px] text-left">
                        <thead class="border-b border-black/8 bg-black/4 text-[10px] uppercase tracking-[0.16em] text-smoke/70">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Waktu</th>
                                <th class="px-6 py-4 font-semibold">Notifikasi</th>
                                <th class="px-6 py-4 font-semibold">Topic &amp; Sumber</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Pengirim</th>
                                <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/6">
                            @foreach ($notifications as $notification)
                                <tr class="align-top transition hover:bg-black/3">
                                    <td class="whitespace-nowrap px-6 py-4 text-xs text-smoke">
                                        {{ $notification->created_at?->format('d M Y') }}
                                        <span class="block font-mono text-champagne/80">{{ $notification->created_at?->format('H:i:s') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-ivory">{{ $notification->title }}</p>
                                        <p class="mt-1 max-w-md text-xs leading-5 text-smoke">{{ $notification->description }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <p class="font-medium text-ivory">{{ $notification->topic->value }}</p>
                                        <p class="mt-1 text-smoke">{{ $notification->sourceLabel() }}</p>
                                        <p class="mt-1 text-smoke/70">Target: semua pengguna</p>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 font-medium {{ $notification->status->badgeClasses() }}">
                                            {{ $notification->status->label() }}
                                        </span>
                                        @if ($notification->fcm_topic)
                                            <p class="mt-2 font-mono text-champagne/80">{{ $notification->fcm_topic }}</p>
                                        @endif
                                        @if ($notification->error_message)
                                            <p class="mt-2 max-w-xs leading-5 text-red-700">{{ $notification->error_message }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-smoke">{{ $notification->user?->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end">
                                            @if ($notification->status === PushNotificationStatus::Failed)
                                                <form action="{{ route('push-notifications.retry', $notification) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" data-confirm-submit data-confirm-intent="save"
                                                        data-confirm-title="Kirim ulang notifikasi ini?"
                                                        data-confirm-message="{{ $isDryRun ? 'Mode uji aktif: notifikasi tidak akan sampai ke pengguna.' : 'Notifikasi akan di-broadcast ke SEMUA pengguna aplikasi dan tidak bisa dibatalkan.' }}"
                                                        data-confirm-action-label="Ya, kirim ulang"
                                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gold/20 bg-gold/8 px-3.5 py-1.5 text-xs font-medium text-gold-soft hover:bg-gold/15">
                                                        <i class="fa-solid fa-rotate-right text-[10px]"></i> Kirim Ulang
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-smoke/50">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($notifications->hasPages())
                    <div class="border-t border-black/8 px-6 py-4">{{ $notifications->links() }}</div>
                @endif
            @endif
        </div>
    </section>
@endsection
