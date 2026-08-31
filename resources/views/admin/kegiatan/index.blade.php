{{-- resources/views/admin/kegiatan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Data Kegiatan')
@section('page-title', 'Data Kegiatan')

@section('content')

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-ink/60">Kelola daftar kegiatan bimtek yang dibuka untuk pendaftaran.</p>
        <button type="button" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Kegiatan
        </button>
    </div>

    @php
        $kegiatan = [
            ['nama' => 'Bimtek Pengelolaan Keuangan Desa', 'tanggal' => '14–16 Sep 2026', 'lokasi' => 'Aula Diklat, Purbalingga', 'kuota' => '18/40', 'status' => 'Dibuka'],
            ['nama' => 'Bimtek Digitalisasi Pelayanan Publik', 'tanggal' => '22–24 Sep 2026', 'lokasi' => 'Gedung Serbaguna, Purwokerto', 'kuota' => '35/40', 'status' => 'Dibuka'],
            ['nama' => 'Bimtek Penyusunan Laporan Kinerja', 'tanggal' => '2–3 Okt 2026', 'lokasi' => 'Aula Diklat, Purbalingga', 'kuota' => '6/40', 'status' => 'Dibuka'],
            ['nama' => 'Bimtek Pengadaan Barang & Jasa', 'tanggal' => '10–11 Agt 2026', 'lokasi' => 'Aula Diklat, Purbalingga', 'kuota' => '40/40', 'status' => 'Selesai'],
        ];
    @endphp

    {{-- Mobile: kartu. Desktop: tabel. --}}
    <div class="mt-5 space-y-3 sm:hidden">
        @foreach ($kegiatan as $k)
            <div class="rounded-2xl border border-line bg-white p-4">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-semibold text-ink-900">{{ $k['nama'] }}</p>
                    <span @class(['shrink-0 rounded-full px-2.5 py-0.5 text-[11px] font-semibold', 'bg-success/10 text-success' => $k['status'] === 'Dibuka', 'bg-ink-900/10 text-ink/50' => $k['status'] === 'Selesai'])>{{ $k['status'] }}</span>
                </div>
                <p class="mt-1.5 font-mono text-xs text-ink/50">{{ $k['tanggal'] }}</p>
                <p class="mt-0.5 text-xs text-ink/50">{{ $k['lokasi'] }}</p>
                <div class="mt-3 flex items-center justify-between border-t border-line pt-3">
                    <span class="text-xs font-medium text-ink/60">Kuota {{ $k['kuota'] }}</span>
                    <div class="flex gap-3 text-xs font-semibold">
                        <button type="button" class="text-ink-900">Edit</button>
                        <button type="button" class="text-red-600">Hapus</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-5 hidden overflow-hidden rounded-2xl border border-line bg-white sm:block">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-line bg-canvas/60 text-xs font-semibold uppercase tracking-wide text-ink/45">
                <tr>
                    <th class="px-5 py-3">Nama Kegiatan</th>
                    <th class="px-5 py-3">Jadwal</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3">Kuota</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach ($kegiatan as $k)
                    <tr class="transition hover:bg-canvas/40">
                        <td class="px-5 py-3.5 font-medium text-ink-900">{{ $k['nama'] }}</td>
                        <td class="px-5 py-3.5 font-mono text-xs text-ink/60">{{ $k['tanggal'] }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $k['lokasi'] }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $k['kuota'] }}</td>
                        <td class="px-5 py-3.5">
                            <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', 'bg-success/10 text-success' => $k['status'] === 'Dibuka', 'bg-ink-900/10 text-ink/50' => $k['status'] === 'Selesai'])>{{ $k['status'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <button type="button" class="text-xs font-semibold text-ink-900 hover:underline">Edit</button>
                            <span class="mx-1.5 text-line">|</span>
                            <button type="button" class="text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection