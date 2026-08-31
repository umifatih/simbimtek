<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cetak Dokumen') — SIMBIMTEK</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-sheet { box-shadow: none !important; margin: 0 !important; border: none !important; }
            @page { size: A4 landscape; margin: 12mm; }
        }
    </style>
</head>
<body class="bg-canvas font-body text-ink antialiased">

    {{-- toolbar, hilang saat print --}}
    <div class="no-print sticky top-0 z-10 flex items-center justify-between border-b border-line bg-white px-5 py-3 sm:px-8">
        <a href="/admin/cetak" class="flex items-center gap-1.5 text-sm font-medium text-ink/60 hover:text-ink-900">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Kembali
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-semibold text-canvas hover:bg-ink-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0M6.34 18l-.53 2.647a.75.75 0 00.75.853h10.88a.75.75 0 00.75-.853L17.66 18M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0c.646.049 1.288.11 1.927.185C20.482 7.379 21 8.264 21 9.334v6.416a2.25 2.25 0 01-2.25 2.25h-1.084M17.66 18l.53 2.647a.75.75 0 01-.75.853H6.56m11.1-3.5H6.34m11.42 0V9.75a2.25 2.25 0 00-2.25-2.25h-6.75A2.25 2.25 0 006.5 9.75v6.75"/></svg>
            Cetak
        </button>
    </div>

    <div class="mx-auto max-w-6xl px-5 py-8 sm:px-8">
        <div class="print-sheet rounded-2xl border border-line bg-white p-6 shadow-sm sm:p-10">
            @yield('content')
        </div>
    </div>

</body>
</html>