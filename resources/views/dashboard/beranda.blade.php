@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Overview hari ini</p>
                        <h2 class="mt-3 text-4xl font-semibold tracking-[-0.04em] text-white">
                            Ringkasan utama workspace admin.
                        </h2>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-smoke">
                            Sinyal operasional utama diringkas di satu tempat: pertumbuhan pengguna, performa publikasi,
                            antrian review, dan aktivitas terbaru yang perlu perhatian.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:w-[18rem] lg:grid-cols-1">
                        <div class="rounded-xl border border-white/8 bg-onyx p-4">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">System state</p>
                            <p class="mt-2 text-2xl font-semibold text-white">Stable</p>
                            <p class="mt-2 text-sm text-smoke">Seluruh indikator inti berada dalam kondisi normal.</p>
                        </div>
                        <div class="rounded-xl border border-white/8 bg-onyx p-4">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Focus today</p>
                            <p class="mt-2 text-2xl font-semibold text-white">18 Review</p>
                            <p class="mt-2 text-sm text-smoke">Prioritaskan moderasi dan validasi publikasi.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <p class="text-sm text-smoke">Total Pengguna</p>
                        <p class="mt-2 text-3xl font-semibold text-white">1,234</p>
                        <div class="mt-4 flex items-center justify-between text-sm">
                            <span class="text-gold-soft">+12%</span>
                            <span class="text-smoke">bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <p class="text-sm text-smoke">Total Posts</p>
                        <p class="mt-2 text-3xl font-semibold text-white">567</p>
                        <div class="mt-4 flex items-center justify-between text-sm">
                            <span class="text-gold-soft">+8%</span>
                            <span class="text-smoke">bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <p class="text-sm text-smoke">Total Views</p>
                        <p class="mt-2 text-3xl font-semibold text-white">45.2K</p>
                        <div class="mt-4 flex items-center justify-between text-sm">
                            <span class="text-gold-soft">+24%</span>
                            <span class="text-smoke">bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <p class="text-sm text-smoke">Moderation Queue</p>
                        <p class="mt-2 text-3xl font-semibold text-white">18</p>
                        <div class="mt-4 flex items-center justify-between text-sm">
                            <span class="text-smoke">perlu tindak lanjut</span>
                            <span class="text-gold-soft">hari ini</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6">
                <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Quick actions</p>
                            <h3 class="mt-2 text-2xl font-semibold text-white">Aksi cepat</h3>
                        </div>
                        <span class="rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                            Workflow
                        </span>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <button class="rounded-xl border border-white/8 bg-onyx px-4 py-4 text-left transition-colors hover:bg-white/6">
                            <p class="font-medium text-white">Tambah Post</p>
                            <p class="mt-1 text-sm text-smoke">Mulai draft baru untuk publikasi.</p>
                        </button>
                        <button class="rounded-xl border border-white/8 bg-onyx px-4 py-4 text-left transition-colors hover:bg-white/6">
                            <p class="font-medium text-white">Kelola Pengguna</p>
                            <p class="mt-1 text-sm text-smoke">Cek akun baru dan role akses.</p>
                        </button>
                        <button class="rounded-xl border border-white/8 bg-onyx px-4 py-4 text-left transition-colors hover:bg-white/6">
                            <p class="font-medium text-white">Review Komentar</p>
                            <p class="mt-1 text-sm text-smoke">Moderasi komentar yang masuk.</p>
                        </button>
                        <button class="rounded-xl border border-white/8 bg-onyx px-4 py-4 text-left transition-colors hover:bg-white/6">
                            <p class="font-medium text-white">Lihat Laporan</p>
                            <p class="mt-1 text-sm text-smoke">Evaluasi performa konten hari ini.</p>
                        </button>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Performance pulse</p>
                    <h3 class="mt-2 text-2xl font-semibold text-white">Performa mingguan</h3>

                    <div class="mt-6 space-y-4">
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="text-smoke">Engagement</span>
                                <span class="text-white">78%</span>
                            </div>
                            <div class="h-2 rounded-full bg-white/8">
                                <div class="h-2 w-[78%] rounded-full bg-gold"></div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="text-smoke">Publishing flow</span>
                                <span class="text-white">64%</span>
                            </div>
                            <div class="h-2 rounded-full bg-white/8">
                                <div class="h-2 w-[64%] rounded-full bg-gold"></div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="text-smoke">Moderation velocity</span>
                                <span class="text-white">82%</span>
                            </div>
                            <div class="h-2 rounded-full bg-white/8">
                                <div class="h-2 w-[82%] rounded-full bg-gold"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 2xl:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">System watch</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">Kesehatan workspace</h3>
                    </div>
                    <span class="rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold-soft">
                        Healthy
                    </span>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <p class="text-sm text-smoke">Session guard</p>
                        <p class="mt-2 text-xl font-semibold text-white">Authenticated</p>
                        <p class="mt-2 text-sm text-smoke">Akses admin aktif dan tervalidasi.</p>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <p class="text-sm text-smoke">Queue status</p>
                        <p class="mt-2 text-xl font-semibold text-white">Normal load</p>
                        <p class="mt-2 text-sm text-smoke">Tidak ada backlog kritis saat ini.</p>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <p class="text-sm text-smoke">Content review</p>
                        <p class="mt-2 text-xl font-semibold text-white">Need focus</p>
                        <p class="mt-2 text-sm text-smoke">18 item menunggu verifikasi manual.</p>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-onyx p-5">
                        <p class="text-sm text-smoke">User activity</p>
                        <p class="mt-2 text-xl font-semibold text-white">Growing</p>
                        <p class="mt-2 text-sm text-smoke">Pertumbuhan akun baru terlihat stabil.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-white/8 bg-white/4 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Recent activity</p>
                        <h3 class="mt-2 text-2xl font-semibold text-white">Timeline aktivitas</h3>
                    </div>
                    <span class="text-sm text-smoke">3 update terbaru</span>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="flex gap-4 rounded-xl border border-white/8 bg-onyx p-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white/8 text-white">
                            <i class="fa-solid fa-user-plus text-base"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-medium text-white">Pengguna baru John Doe bergabung</p>
                                <span class="text-xs text-smoke">2 menit lalu</span>
                            </div>
                            <p class="mt-2 text-sm text-smoke">Akun baru berhasil dibuat dan siap diberi role akses.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 rounded-xl border border-white/8 bg-onyx p-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white/8 text-white">
                            <i class="fa-solid fa-pen-to-square text-base"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-medium text-white">Post Tutorial Laravel diperbarui</p>
                                <span class="text-xs text-smoke">15 menit lalu</span>
                            </div>
                            <p class="mt-2 text-sm text-smoke">Perubahan konten tersimpan dan menunggu review akhir.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 rounded-xl border border-white/8 bg-onyx p-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white/8 text-white">
                            <i class="fa-solid fa-comments text-base"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="font-medium text-white">Komentar baru pada Artikel Terbaru</p>
                                <span class="text-xs text-smoke">1 jam lalu</span>
                            </div>
                            <p class="mt-2 text-sm text-smoke">Komentar memerlukan moderasi sebelum tampil publik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
