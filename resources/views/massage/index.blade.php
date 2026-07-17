@extends('layouts.app')

@section('title', 'Massages')

@section('content')
    <section class="space-y-6">
        @include('components.molecules.page-header', [
            'eyebrow' => 'Contact Tracking',
            'title' => 'Daftar Kontak',
            'description' => 'Pantau siapa saja yang menghubungi dan periksa isi pesan mereka.',
        ])

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Total Kontak</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $massages->total() }}</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Ditampilkan</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $massages->count() }}</p>
            </div>
            <div class="rounded-2xl border border-white/8 bg-white/4 p-5">
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Halaman</p>
                <p class="mt-3 text-3xl font-semibold text-white">{{ $massages->currentPage() }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-white/8 bg-white/4">
            @if ($massages->isEmpty())
                <div class="px-6 py-16 text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-white/8 bg-white/5 text-gold-soft">
                        <i class="fa-solid fa-envelope-open-text text-xl"></i>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold text-white">Belum ada kontak masuk</h3>
                    <p class="mt-2 text-sm text-smoke">Pesan dari formulir kontak akan muncul sebagai card di halaman ini.</p>
                </div>
            @else
                <div class="grid gap-4 p-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($massages as $massage)
                        @include('components.molecules.massage-card', ['massage' => $massage])
                    @endforeach
                </div>

                <div class="border-t border-white/8 px-6 py-4">
                    {{ $massages->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
