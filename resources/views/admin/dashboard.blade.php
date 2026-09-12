{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- ===== STAT CARDS (sekaligus pintasan) ===== --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        @php
            $stat = [
                [
                    'label' => 'Total Peserta',
                    'nilai' => $totalPeserta,
                    'link'  => route('admin.peserta.index'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>',
                    'warna' => 'bg-ink-900/10 text-ink-900',
                ],
                [
                    'label' => 'Kegiatan Aktif',
                    'nilai' => $kegiatanAktif,
                    'link'  => route('admin.kegiatan.index'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>',
                    'warna' => 'bg-[#3E6B8F]/10 text-[#3E6B8F]',
                ],
                [
                    'label' => 'Hadir Hari Ini',
                    'nilai' => $hadirHariIni,
                    'link'  => route('admin.absensi.index'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5A.75.75 0 014.5 3.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5z"/>',
                    'warna' => 'bg-success/10 text-success',
                ],
                [
                    'label' => 'Data Master',
                    'nilai' => $totalDataMaster,
                    'link'  => route('admin.data-master.index'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/>',
                    'warna' => 'bg-amber-500/10 text-amber-600',
                ],
            ];
        @endphp
        @foreach ($stat as $s)
            <a href="{{ $s['link'] }}" class="group active:scale-[0.98] rounded-2xl border border-line bg-white p-4 transition hover:border-ink-900/20 hover:shadow-sm">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $s['warna'] }}">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $s['icon'] !!}</svg>
                </div>
                <p class="mt-3 font-display text-2xl font-bold leading-none text-ink-900">{{ $s['nilai'] }}</p>
                <p class="mt-1 text-xs text-ink/55">{{ $s['label'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- ===== KEGIATAN BERJALAN ===== --}}
        <div class="rounded-2xl border border-line bg-white p-5 lg:col-span-2">
            <div class="flex items-center justify-between">
                <p class="font-display text-sm font-semibold text-ink-900">Kegiatan Berjalan</p>
                <a href="{{ route('admin.kegiatan.index') }}" class="text-xs font-medium text-ink/50 hover:text-ink-900">Semua →</a>
            </div>
            <div class="mt-3 divide-y divide-line">
                @forelse ($kegiatanBerjalan as $k)
                    <div class="flex items-center justify-between gap-3 py-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-ink-900">{{ $k->nama }}</p>
                            <p class="mt-0.5 text-xs text-ink/45">{{ \Illuminate\Support\Carbon::parse($k->tanggal_mulai)->translatedFormat('d M Y') }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-canvas px-2.5 py-1 text-xs font-semibold text-ink-900">{{ $k->jumlah_peserta }}/{{ $k->kuota }}</span>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-ink/40">Belum ada kegiatan yang dibuka.</p>
                @endforelse
            </div>
        </div>

        {{-- ===== AKSES CEPAT ===== --}}
        <div class="rounded-2xl border border-line bg-white p-5">
            <p class="font-display text-sm font-semibold text-ink-900">Akses Cepat</p>
            <div class="mt-3 space-y-1">
                <a href="{{ route('admin.absensi.index') }}" class="flex items-center justify-between rounded-lg px-2 py-2.5 -mx-2 hover:bg-canvas">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-success/10 text-success">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h4.5v4.5h-4.5v-4.5zM15.75 4.5h4.5v4.5h-4.5v-4.5zM3.75 15h4.5v4.5h-4.5v-4.5zM15 15h1.5v1.5H15V15zM18 15h1.5v1.5H18V15zM15 18h1.5v1.5H15V18zM18 18h1.5v1.5H18V18z"/></svg>
                        </div>
                        <p class="text-sm text-ink-900">Absensi QR Code</p>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('admin.sertifikat.index') }}" class="flex items-center justify-between rounded-lg px-2 py-2.5 -mx-2 hover:bg-canvas">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#3E6B8F]/10 text-[#3E6B8F]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286z"/></svg>
                        </div>
                        <p class="text-sm text-ink-900">Sertifikat</p>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('admin.cetak.index') }}" class="flex items-center justify-between rounded-lg px-2 py-2.5 -mx-2 hover:bg-canvas">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                        </div>
                        <p class="text-sm text-ink-900">Cetak Dokumen</p>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- ===== ABSENSI TERBARU ===== --}}
        <div class="rounded-2xl border border-line bg-white p-5 lg:col-span-2">
            <div class="flex items-center justify-between">
                <p class="font-display text-sm font-semibold text-ink-900">Absensi Terbaru</p>
                <a href="{{ route('admin.absensi.index') }}" class="text-xs font-medium text-ink/50 hover:text-ink-900">Semua →</a>
            </div>
            <div class="mt-3 divide-y divide-line">
                @forelse ($absensiTerbaru as $a)
                    <div class="flex items-center justify-between gap-3 py-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-ink-900">{{ $a->pendaftaran->peserta->nama_gelar ?? '—' }}</p>
                            <p class="mt-0.5 truncate text-xs text-ink/45">{{ $a->pendaftaran->kegiatan->nama ?? '—' }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-success/10 px-2.5 py-1 text-xs font-semibold text-success">
                            {{ $a->waktu_scan?->translatedFormat('d M · H:i') ?? '-' }}
                        </span>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-ink/40">Belum ada absensi.</p>
                @endforelse
            </div>
        </div>

        {{-- ===== SISTEM: DATA MASTER + PENGATURAN ===== --}}
        <div class="rounded-2xl border border-line bg-white p-5">
            <p class="font-display text-sm font-semibold text-ink-900">Sistem</p>
            <div class="mt-3 space-y-1">
                <a href="{{ route('admin.data-master.index') }}" class="flex items-center justify-between rounded-lg px-2 py-2.5 -mx-2 hover:bg-canvas">
                    <div>
                        <p class="text-sm text-ink-900">Data Master</p>
                        <p class="text-xs text-ink/45">{{ $totalDataMaster }} baris · {{ $dataMasterUpdatedAt ?? 'belum ada' }}</p>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('admin.pengaturan.edit') }}" class="flex items-center justify-between rounded-lg px-2 py-2.5 -mx-2 hover:bg-canvas">
                    <div>
                        <p class="text-sm text-ink-900">Pengaturan Situs</p>
                        <p class="mt-0.5 flex items-center gap-2.5 text-xs text-ink/45">
                            <span class="flex items-center gap-1">
                                <span class="h-1.5 w-1.5 rounded-full {{ $pengaturanLogoAda ? 'bg-success' : 'bg-red-500' }}"></span> Logo
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="h-1.5 w-1.5 rounded-full {{ $pengaturanNamaAda ? 'bg-success' : 'bg-red-500' }}"></span> Nama
                            </span>
                        </p>
                    </div>
                    <svg class="h-4 w-4 shrink-0 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

@endsection