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
                    'link'  => route('admin.absensi.scan'),
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

        {{-- ===== STATUS PENDAFTARAN ===== --}}
        <div class="rounded-2xl border border-line bg-white p-5">
            <p class="font-display text-sm font-semibold text-ink-900">Status Pendaftaran</p>
            <div class="mt-4 space-y-3">
                @php
                    $totalStatus = max($menungguVerifikasi + $diterima + $ditolak, 1);
                    $statusList = [
                        ['label' => 'Menunggu', 'nilai' => $menungguVerifikasi, 'warna' => 'bg-amber-500'],
                        ['label' => 'Diterima', 'nilai' => $diterima, 'warna' => 'bg-success'],
                        ['label' => 'Ditolak', 'nilai' => $ditolak, 'warna' => 'bg-red-500'],
                    ];
                @endphp
                @foreach ($statusList as $st)
                    <div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-ink/60">{{ $st['label'] }}</span>
                            <span class="font-semibold text-ink-900">{{ $st['nilai'] }}</span>
                        </div>
                        <div class="mt-1 h-1.5 w-full overflow-hidden rounded-full bg-canvas">
                            <div class="h-full rounded-full {{ $st['warna'] }}" style="width: {{ round($st['nilai'] / $totalStatus * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.peserta.index') }}" class="mt-4 block text-center text-xs font-medium text-ink-900 underline underline-offset-2">Lihat detail</a>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- ===== ANTREAN VERIFIKASI ===== --}}
        <div class="rounded-2xl border border-line bg-white p-5 lg:col-span-2">
            <div class="flex items-center justify-between">
                <p class="font-display text-sm font-semibold text-ink-900">Antrean Verifikasi</p>
                @if($menungguVerifikasi > 0)
                    <span class="rounded-full bg-amber-500/10 px-2 py-0.5 text-xs font-semibold text-amber-600">{{ $menungguVerifikasi }} menunggu</span>
                @endif
            </div>
            <div class="mt-3 divide-y divide-line">
                @forelse ($antreanVerifikasi as $p)
                    <a href="{{ route('admin.peserta.index') }}" class="flex items-center justify-between gap-3 py-3 -mx-1 px-1 rounded-lg hover:bg-canvas">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-ink-900">{{ $p->peserta->nama_gelar ?? '—' }}</p>
                            <p class="mt-0.5 text-xs text-ink/45">{{ $p->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-amber-500/10 px-2.5 py-1 text-xs font-semibold text-amber-600">Baru</span>
                    </a>
                @empty
                    <p class="py-8 text-center text-sm text-ink/40">Tidak ada antrean verifikasi.</p>
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