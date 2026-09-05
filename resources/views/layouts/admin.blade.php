<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SIMBIMTEK</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas text-ink font-body antialiased">

    <div class="flex min-h-screen">

        {{-- ============ SIDEBAR ============ --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-ink-900/40 lg:hidden"></div>

        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full bg-ink-900 transition-transform duration-200 lg:static lg:translate-x-0">
            <div class="flex h-16 items-center gap-2.5 border-b border-white/10 px-5">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gold font-display text-sm font-bold text-ink-900">SB</span>
                <div class="leading-tight">
                    <p class="font-display text-sm font-bold text-canvas">SIMBIMTEK</p>
                    <p class="text-[11px] text-canvas/45">Panel Admin</p>
                </div>
            </div>

            <nav class="flex flex-col gap-1 px-3 py-4">
                @php
                    $menu = [
                        ['href' => '/admin/dashboard', 'label' => 'Dashboard', 'match' => 'admin/dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6a2.25 2.25 0 012.25-2.25h12A2.25 2.25 0 0120.25 6v2.25a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25A2.25 2.25 0 0110.5 15.75V18A2.25 2.25 0 018.25 20.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>'],
                        ['href' => '/admin/kegiatan', 'label' => 'Data Kegiatan', 'match' => 'admin/kegiatan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>'],
                        ['href' => '/admin/peserta', 'label' => 'Data Peserta', 'match' => 'admin/peserta$|admin/peserta\?*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>'],
                        ['href' => '/admin/absensi', 'label' => 'Absensi QR Code', 'match' => 'admin/absensi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5A.75.75 0 014.5 3.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5zM3.75 15a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75V15zM14.25 4.5a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5z"/>'],
                        ['href' => '/admin/cetak', 'label' => 'Cetak Dokumen', 'match' => 'admin/cetak', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0M6.34 18l-.53 2.647a.75.75 0 00.75.853h10.88a.75.75 0 00.75-.853L17.66 18M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0c.646.049 1.288.11 1.927.185C20.482 7.379 21 8.264 21 9.334v6.416a2.25 2.25 0 01-2.25 2.25h-1.084M17.66 18l.53 2.647a.75.75 0 01-.75.853H6.56m11.1-3.5H6.34m11.42 0V9.75a2.25 2.25 0 00-2.25-2.25h-6.75A2.25 2.25 0 006.5 9.75v6.75"/>'],
                    ];
                @endphp
                @foreach ($menu as $m)
                    @php $aktif = preg_match('#^' . $m['match'] . '#', request()->path()); @endphp
                    <a
                        href="{{ $m['href'] }}"
                        @class([
                            'flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition',
                            'bg-white/10 text-canvas' => $aktif,
                            'text-canvas/55 hover:bg-white/5 hover:text-canvas' => !$aktif,
                        ])
                    >
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $m['icon'] !!}</svg>
                        {{ $m['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="absolute inset-x-0 bottom-0 border-t border-white/10 p-3">
                <form action="/admin/logout" method="POST">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium text-canvas/55 transition hover:bg-white/5 hover:text-canvas">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- ============ KONTEN ============ --}}
        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-line bg-white/90 px-5 backdrop-blur sm:px-6">
                <button id="sidebar-toggle" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-line lg:hidden" aria-label="Buka menu">
                    <svg class="h-5 w-5 text-ink-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
                </button>
                <h1 class="font-display text-sm font-semibold text-ink-900 sm:text-base">@yield('page-title', 'Admin')</h1>
                <div class="ml-auto flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-ink-900/[0.06] text-xs font-semibold text-ink-900">A</span>
                    <span class="hidden text-sm font-medium text-ink-900 sm:inline">Admin</span>
                </div>
            </header>

            <main class="p-5 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggle = document.getElementById('sidebar-toggle');

        function bukaSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
        function tutupSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        toggle?.addEventListener('click', bukaSidebar);
        overlay?.addEventListener('click', tutupSidebar);
    </script>

    @yield('scripts')
</body>
</html>