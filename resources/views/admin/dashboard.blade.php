{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3">
        @php
            $stat = [
                ['label' => 'Total Peserta', 'nilai' => $totalPeserta, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>', 'warna' => 'bg-ink-900/10 text-ink-900'],
                ['label' => 'Kegiatan Aktif', 'nilai' => $kegiatanAktif, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>', 'warna' => 'bg-[#3E6B8F]/10 text-[#3E6B8F]'],
                ['label' => 'Hadir Hari Ini', 'nilai' => $hadirHariIni, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5A.75.75 0 014.5 3.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5z"/>', 'warna' => 'bg-success/10 text-success'],
            ];
        @endphp
        @foreach ($stat as $s)
            <div class="rounded-2xl border border-line bg-white p-3.5 sm:p-5">
                <div class="flex items-center gap-2.5 sm:block">
                    <div @class(['flex h-9 w-9 shrink-0 items-center justify-center rounded-lg sm:h-10 sm:w-10 sm:rounded-xl', $s['warna']])>
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $s['icon'] !!}</svg>
                    </div>
                    <p class="font-display text-xl font-bold text-ink-900 sm:mt-3 sm:text-2xl">{{ $s['nilai'] }}</p>
                </div>
                <p class="mt-1.5 text-xs text-ink/55 sm:mt-0">{{ $s['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6">

        {{-- ===== KEGIATAN BERJALAN ===== --}}
        <div class="rounded-2xl border border-line bg-white p-5 sm:p-6">
            <div class="flex items-center justify-between">
                <p class="font-display text-sm font-semibold text-ink-900">Kegiatan Berjalan</p>
                <a href="/admin/kegiatan" class="text-xs font-semibold text-ink-900 underline underline-offset-2">Lihat semua</a>
            </div>
            <div class="mt-4 divide-y divide-line">
                @forelse ($kegiatanBerjalan as $k)
                    <div class="flex items-center justify-between gap-3 py-3.5">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-ink-900">{{ $k->nama }}</p>
                            <p class="font-mono text-xs text-ink/50">{{ $k->tanggal }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-canvas px-3 py-1 text-xs font-semibold text-ink-900">{{ $k->jumlah_peserta }} / {{ $k->kuota }}</span>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-ink/40">Belum ada kegiatan yang dibuka.</p>
                @endforelse
            </div>
        </div>

    </div>

@endsection