<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SG Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        referrerpolicy="no-referrer">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-obsidian text-champagne">
    <div class="min-h-screen bg-[linear-gradient(180deg,_#0b0d12_0%,_#10141b_100%)]">
        <div class="mx-auto grid min-h-screen max-w-6xl items-center gap-10 px-6 py-10 lg:grid-cols-[1fr_420px] lg:px-10">
            <section class="space-y-8">
                <div class="inline-flex items-center gap-3 rounded-lg border border-white/8 bg-white/4 px-4 py-2">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-white/6 text-sm font-semibold text-white">
                        SG
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">SG Admin</p>
                        <p class="text-xs text-smoke">Secure workspace</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h1 class="max-w-2xl text-5xl font-semibold tracking-[-0.04em] text-white sm:text-6xl">
                        Login ke workspace admin yang lebih rapi dan fokus.
                    </h1>
                    <p class="max-w-xl text-base leading-8 text-smoke sm:text-lg">
                        Dirancang untuk operasional harian: publikasi konten, manajemen pengguna, dan monitoring aktivitas
                        dalam satu panel yang tenang dan mudah dibaca.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-xl border border-white/8 bg-white/4 p-5">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Visibility</p>
                        <p class="mt-3 text-3xl font-semibold text-white">24/7</p>
                        <p class="mt-2 text-sm leading-6 text-smoke">Pantau status sistem setiap saat.</p>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-white/4 p-5">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Session</p>
                        <p class="mt-3 text-3xl font-semibold text-white">Safe</p>
                        <p class="mt-2 text-sm leading-6 text-smoke">Akses tetap terjaga dan terstruktur.</p>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-white/4 p-5">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-smoke">Flow</p>
                        <p class="mt-3 text-3xl font-semibold text-white">Fast</p>
                        <p class="mt-2 text-sm leading-6 text-smoke">Dari login ke dashboard tanpa friksi.</p>
                    </div>
                </div>
            </section>

            <section class="w-full">
                <div class="rounded-2xl border border-white/8 bg-noir p-8 shadow-2xl shadow-black/20 sm:p-10">
                    <div class="mb-8">
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
                            <a href="/" class="text-sm text-smoke transition-colors hover:text-white">Kembali</a>
                        </div>

                        <button type="submit"
                            class="w-full rounded-lg bg-white px-4 py-3 text-sm font-medium text-obsidian transition-colors hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-white/20">
                            Masuk ke Dashboard
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</body>

</html>
