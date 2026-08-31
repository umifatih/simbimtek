{{-- resources/views/admin/cetak/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Cetak Dokumen')
@section('page-title', 'Cetak Dokumen')

@section('content')

    <p class="text-sm text-ink/60">Pilih kegiatan, lalu pilih jenis dokumen yang mau dicetak.</p>

    <div class="mt-5 max-w-sm">
        <label class="text-sm font-semibold text-ink-900">Kegiatan</label>
        <select id="pilih-kegiatan" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
            <option value="1">Bimtek Pengelolaan Keuangan Desa — 14–16 Sep 2026</option>
            <option value="2">Bimtek Digitalisasi Pelayanan Publik — 22–24 Sep 2026</option>
            <option value="3">Bimtek Penyusunan Laporan Kinerja — 2–3 Okt 2026</option>
        </select>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        @php
            $dokumen = [
                ['label' => 'Daftar Peserta', 'desc' => 'Rekap semua peserta terdaftar per kegiatan.', 'target' => '/admin/cetak/daftar-peserta', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>'],
                ['label' => 'Daftar Hadir', 'desc' => 'Lembar tanda tangan kehadiran hari-H.', 'target' => '/admin/cetak/daftar-hadir', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286z"/>'],
                ['label' => 'Daftar Penerimaan Konsumsi & ATK', 'desc' => 'Bukti tanda terima konsumsi dan ATK peserta.', 'target' => '/admin/cetak/konsumsi-atk', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>'],
            ];
        @endphp
        @foreach ($dokumen as $d)
            <a href="#" data-target="{{ $d['target'] }}" class="cetak-link group rounded-2xl border border-line bg-white p-5 transition hover:border-gold/40 hover:bg-gold/[0.03]">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-ink-900/[0.06] text-ink-900">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $d['icon'] !!}</svg>
                </div>
                <p class="mt-3 font-display text-sm font-semibold text-ink-900">{{ $d['label'] }}</p>
                <p class="mt-1.5 text-sm leading-relaxed text-ink/60">{{ $d['desc'] }}</p>
                <span class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-gold-600">
                    Buka & Cetak
                    <svg class="h-3.5 w-3.5 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </span>
            </a>
        @endforeach
    </div>

    <script>
        // ikutkan kegiatan yang dipilih sebagai query param saat pindah ke halaman cetak
        document.querySelectorAll('.cetak-link').forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const kegiatanId = document.getElementById('pilih-kegiatan').value;
                window.location.href = `${link.dataset.target}?kegiatan_id=${kegiatanId}`;
            });
        });
    </script>

@endsection