<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SIMBIMTEK</title>

    @if ($globalSetting->logo_url)
        <link rel="icon" type="image/png" href="{{ $globalSetting->logo_url }}">
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-canvas text-ink font-body antialiased">

    <div class="flex min-h-screen">

        {{-- ============ SIDEBAR ============ --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-ink-900/40 lg:hidden"></div>

        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full overflow-y-auto bg-ink-900 transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
            <div class="flex h-16 items-center gap-2.5 border-b border-white/10 px-5">
                @if ($globalSetting->logo_url)
                    <img src="{{ $globalSetting->logo_url }}" alt="{{ $globalSetting->nama_aplikasi }}" class="h-9 w-9 shrink-0 object-contain">
                @else
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gold font-display text-sm font-bold text-ink-900">
                        {{ strtoupper(substr($globalSetting->nama_aplikasi, 0, 2)) }}
                    </span>
                @endif
                <div class="leading-tight">
                    <p class="font-display text-sm font-bold text-canvas">{{ $globalSetting->nama_aplikasi }}</p>
                    <p class="text-[11px] text-canvas/45">Panel Admin</p>
                </div>
            </div>

            <nav class="flex flex-col gap-1 px-3 py-4">
                @php
                    $menu = [
                        ['href' => '/admin/dashboard', 'label' => 'Dashboard', 'match' => 'admin/dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6a2.25 2.25 0 012.25-2.25h12A2.25 2.25 0 0120.25 6v2.25a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25A2.25 2.25 0 0110.5 15.75V18A2.25 2.25 0 018.25 20.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>'],
                        // ===== BARU: Data Master (sumber data sekolah/kepala sekolah/NIP KS untuk autofill) =====
                        ['href' => '/admin/data-master', 'label' => 'Data Master', 'match' => 'admin/data-master', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/>'],
                        ['href' => '/admin/kegiatan', 'label' => 'Data Kegiatan', 'match' => 'admin/kegiatan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>'],
                        ['href' => '/admin/peserta', 'label' => 'Data Peserta', 'match' => 'admin/peserta$|admin/peserta\?*', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>'],
                        ['href' => '/admin/absensi', 'label' => 'Absensi QR Code', 'match' => 'admin/absensi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5A.75.75 0 014.5 3.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5zM3.75 15a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75V15zM14.25 4.5a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5z"/>'],
                        ['href' => '/admin/cetak', 'label' => 'Cetak Dokumen', 'match' => 'admin/cetak', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0M6.34 18l-.53 2.647a.75.75 0 00.75.853h10.88a.75.75 0 00.75-.853L17.66 18M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0c.646.049 1.288.11 1.927.185C20.482 7.379 21 8.264 21 9.334v6.416a2.25 2.25 0 01-2.25 2.25h-1.084M17.66 18l.53 2.647a.75.75 0 01-.75.853H6.56m11.1-3.5H6.34m11.42 0V9.75a2.25 2.25 0 00-2.25-2.25h-6.75A2.25 2.25 0 006.5 9.75v6.75"/>'],
                        ['href' => '/admin/sertifikat', 'label' => 'Sertifikat', 'match' => 'admin/sertifikat', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286z"/>'],
                        ['href' => '/admin/pengaturan', 'label' => 'Pengaturan', 'match' => 'admin/pengaturan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'],
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