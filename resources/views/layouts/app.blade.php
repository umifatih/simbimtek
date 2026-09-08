<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMBIMTEK') — Sistem Informasi Manajemen Bimtek</title>
    <meta name="description" content="Ikuti bimbingan teknis dari pendaftaran sampai sertifikat, dalam satu sistem.">

    @if ($globalSetting->logo_url)
        <link rel="icon" type="image/png" href="{{ $globalSetting->logo_url }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas text-ink font-body antialiased">

    {{-- ============ NAVBAR ============ --}}
    <header class="sticky top-0 z-50 border-b border-line/70 bg-canvas/90 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <a href="{{ route('beranda') ?? '/' }}" class="flex items-center gap-2.5">
                @if ($globalSetting->logo_url)
                    <img src="{{ $globalSetting->logo_url }}" alt="{{ $globalSetting->nama_aplikasi }}" class="h-9 w-9 shrink-0 object-contain">
                @else
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gold font-display text-sm font-bold text-ink-900">
                        {{ strtoupper(substr($globalSetting->nama_aplikasi, 0, 2)) }}
                    </span>
                @endif
                <span class="font-display text-lg font-bold tracking-tight text-ink-900">{{ $globalSetting->nama_aplikasi }}</span>
            </a>

            {{-- Link ini mengarah ke section di beranda (bukan halaman terpisah), makanya pakai anchor "/#..." --}}
            <div class="hidden items-center gap-8 md:flex">
                <a href="/#informasi-kegiatan" class="text-sm font-medium text-ink/70 transition hover:text-ink-900">Kegiatan</a>
                <a href="/#kegiatan" class="text-sm font-medium text-ink/70 transition hover:text-ink-900">Jadwal</a>
                <a href="/#alur" class="text-sm font-medium text-ink/70 transition hover:text-ink-900">Cara Kerja</a>
                <a href="/#cek-status" class="text-sm font-medium text-ink/70 transition hover:text-ink-900">Cek Status</a>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <a href="/pendaftaran" class="rounded-full bg-ink-900 px-5 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700">
                    Daftar Sekarang
                </a>
            </div>

            <button id="menu-toggle" type="button" class="flex h-10 w-10 items-center justify-center rounded-lg border border-line md:hidden" aria-label="Buka menu" aria-expanded="false">
                <svg id="icon-open" class="h-5 w-5 text-ink-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
                <svg id="icon-close" class="hidden h-5 w-5 text-ink-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </nav>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden border-t border-line bg-canvas px-6 py-4 md:hidden">
            <div class="flex flex-col gap-4">
                <a href="/#informasi-kegiatan" class="nav-mobile-link text-sm font-medium text-ink/80">Kegiatan</a>
                <a href="/#kegiatan" class="nav-mobile-link text-sm font-medium text-ink/80">Jadwal</a>
                <a href="/#alur" class="nav-mobile-link text-sm font-medium text-ink/80">Cara Kerja</a>
                <a href="/#cek-status" class="nav-mobile-link text-sm font-medium text-ink/80">Cek Status</a>
                <hr class="border-line">
                <a href="/pendaftaran" class="rounded-full bg-ink-900 px-5 py-2.5 text-center text-sm font-semibold text-canvas">Daftar Sekarang</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="border-t border-line bg-ink-900 text-canvas/80">
        <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
            <div class="grid gap-10 md:grid-cols-4">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gold font-display text-sm font-bold text-ink-900">SB</span>
                        <span class="font-display text-lg font-bold text-canvas">SIMBIMTEK</span>
                    </div>
                    <p class="mt-4 max-w-sm text-sm leading-relaxed text-canvas/60">
                        Sistem Informasi Manajemen Bimbingan Teknis — mengelola pendaftaran, verifikasi,
                        SPPD, dan sertifikat peserta dalam satu alur yang jelas.
                    </p>
                </div>
                <div>
                    <p class="font-display text-sm font-semibold text-canvas">Untuk Peserta</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-canvas/60">
                        <li><a href="/#informasi-kegiatan" class="transition hover:text-canvas">Informasi Kegiatan</a></li>
                        <li><a href="/#kegiatan" class="transition hover:text-canvas">Jadwal Bimtek</a></li>
                        <li><a href="/pendaftaran" class="transition hover:text-canvas">Pendaftaran Online</a></li>
                        <li><a href="/cek-status" class="transition hover:text-canvas">Cek Status</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-display text-sm font-semibold text-canvas">Bantuan</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-canvas/60">
                        <li><a href="/cara-kerja" class="transition hover:text-canvas">Alur Pendaftaran</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 flex flex-col gap-2 border-t border-canvas/10 pt-6 text-xs text-canvas/40 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} SIMBIMTEK. Seluruh hak cipta dilindungi.</p>
                <p>Dibangun dengan Laravel &amp; Tailwind CSS.</p>
            </div>
        </div>
    </footer>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');

        function closeMobileMenu() {
            mobileMenu.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
            menuToggle.setAttribute('aria-expanded', 'false');
        }

        menuToggle?.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
            menuToggle.setAttribute('aria-expanded', String(isHidden));
        });

        // Tutup mobile menu otomatis begitu salah satu link section diklik,
        // supaya menu tidak nutupin hasil scroll-nya.
        document.querySelectorAll('.nav-mobile-link').forEach((link) => {
            link.addEventListener('click', closeMobileMenu);
        });
    </script>
</body>
</html>