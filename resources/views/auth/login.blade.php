@extends('layouts.guest')

@section('title', 'Login - SG Admin')

@section('content')
    <div class="mx-auto grid min-h-screen max-w-lg items-center gap-10 px-6 py-10">
        <section class="w-full">
            <div class="rounded-2xl border border-white/8 bg-noir p-8 shadow-2xl shadow-black/20 sm:p-10">
                <div class="mb-8 text-center">
                    <h2 class="text-3xl font-semibold tracking-[-0.04em] text-white">Masuk</h2>
                    <p class="mt-2 text-sm text-smoke">Gunakan akun admin untuk melanjutkan.</p>
                </div>

                <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-500/30 bg-red-950/30 px-4 py-3 text-sm text-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-white">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('email') ? 'border-red-400/60' : 'border-white/8' }}"
                            placeholder="name@example.com" autocomplete="email" required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-white">Password</label>
                        <input type="password" id="password" name="password"
                            class="w-full rounded-lg border bg-onyx px-4 py-3 text-champagne placeholder:text-smoke focus:border-gold/30 focus:outline-none focus:ring-2 focus:ring-gold/15 {{ $errors->has('password') ? 'border-red-400/60' : 'border-white/8' }}"
                            placeholder="********" autocomplete="current-password" required>
                        @error('password')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                                class="rounded border-white/10 bg-onyx text-gold focus:ring-gold/20">
                            <span class="ml-2 text-sm text-smoke">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-white px-4 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-white/20">
                        Masuk ke Dashboard
                    </button>
                </form>
            </div>
        </section>
    </div>
@endsection
