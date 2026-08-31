<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin — SIMBIMTEK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-ink-900 px-5 font-body antialiased">

    <div class="pointer-events-none fixed inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 22px 22px;"></div>
    <div class="pointer-events-none fixed left-1/2 top-0 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gold/30 blur-[100px]"></div>

    <div class="relative w-full max-w-sm">
        <div class="mb-8 flex flex-col items-center text-center">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gold font-display text-base font-bold text-ink-900">SB</span>
            <p class="mt-3 font-display text-lg font-bold text-canvas">SIMBIMTEK</p>
            <p class="text-xs text-canvas/50">Masuk ke Panel Admin</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white p-6 shadow-xl sm:p-7">
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="/admin/login" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="text-sm font-semibold text-ink-900">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@simbimtek.go.id" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                </div>
                <div>
                    <label for="password" class="text-sm font-semibold text-ink-900">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                </div>

                <label class="flex items-center gap-2.5">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-line text-ink-900 focus:ring-ink-900/20">
                    <span class="text-sm text-ink/65">Ingat saya</span>
                </label>

                <button type="submit" class="w-full rounded-full bg-ink-900 px-6 py-3.5 text-sm font-semibold text-canvas shadow-md shadow-ink-900/20 transition hover:bg-ink-700">
                    Masuk
                </button>
            </form>
        </div>

        <a href="/" class="mt-6 flex items-center justify-center gap-1.5 text-xs font-medium text-canvas/50 transition hover:text-canvas">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali ke beranda
        </a>
    </div>

</body>
</html>