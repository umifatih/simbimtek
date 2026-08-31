{{-- resources/views/admin/peserta/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Data Peserta')
@section('page-title', 'Data Peserta')

@section('content')

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink/35" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" placeholder="Cari nama, unit kerja, atau NIP…" class="w-full rounded-full border border-line bg-white py-2.5 pl-10 pr-4 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
        </div>
        <select class="shrink-0 rounded-full border border-line bg-white px-4 py-2.5 text-sm text-ink-900 outline-none focus:border-ink-900">
            <option>Semua Kegiatan</option>
            <option>Bimtek Pengelolaan Keuangan Desa</option>
            <option>Bimtek Digitalisasi Pelayanan Publik</option>
        </select>
        <select class="shrink-0 rounded-full border border-line bg-white px-4 py-2.5 text-sm text-ink-900 outline-none focus:border-ink-900">
            <option>Semua Status</option>
            <option>Menunggu</option>
            <option>Terverifikasi</option>
            <option>Hadir</option>
        </select>
    </div>

    @php
        $peserta = [
            ['nama' => 'Siti Aminah, S.Pd.', 'unit' => 'SD NEGERI 1 MANDIRAJA KULON', 'jabatan' => 'Bendahara BOSP', 'kegiatan' => 'Keuangan Desa', 'status' => 'Menunggu'],
            ['nama' => 'Budi Santoso, S.Kom.', 'unit' => 'SMP NEGERI 2 PURBALINGGA', 'jabatan' => 'Operator BOSP', 'kegiatan' => 'Digitalisasi Pelayanan', 'status' => 'Terverifikasi'],
            ['nama' => 'Rina Wulandari, S.Pd.', 'unit' => 'SD NEGERI 3 KALIMANAH', 'jabatan' => 'Bendahara BOSP', 'kegiatan' => 'Keuangan Desa', 'status' => 'Hadir'],
            ['nama' => 'Ahmad Fauzi, S.Pd.I.', 'unit' => 'MI NEGERI 1 BANYUMAS', 'jabatan' => 'Operator BOSP', 'kegiatan' => 'Laporan Kinerja', 'status' => 'Menunggu'],
        ];
        $warnaStatus = ['Menunggu' => 'bg-gold/15 text-gold-600', 'Terverifikasi' => 'bg-[#3E6B8F]/10 text-[#3E6B8F]', 'Hadir' => 'bg-success/10 text-success'];
    @endphp

    {{-- Mobile: kartu --}}
    <div class="mt-5 space-y-3 sm:hidden">
        @foreach ($peserta as $p)
            <a href="#" class="block rounded-2xl border border-line bg-white p-4">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-semibold text-ink-900">{{ $p['nama'] }}</p>
                    <span @class(['shrink-0 rounded-full px-2.5 py-0.5 text-[11px] font-semibold', $warnaStatus[$p['status']]])>{{ $p['status'] }}</span>
                </div>
                <p class="mt-1.5 text-xs uppercase text-ink/50">{{ $p['unit'] }}</p>
                <p class="mt-2 text-xs text-ink/60">{{ $p['jabatan'] }} · {{ $p['kegiatan'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Desktop: tabel --}}
    <div class="mt-5 hidden overflow-hidden rounded-2xl border border-line bg-white sm:block">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-line bg-canvas/60 text-xs font-semibold uppercase tracking-wide text-ink/45">
                <tr>
                    <th class="px-5 py-3">Nama & Gelar</th>
                    <th class="px-5 py-3">Unit Kerja</th>
                    <th class="px-5 py-3">Jabatan</th>
                    <th class="px-5 py-3">Kegiatan</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach ($peserta as $p)
                    <tr class="transition hover:bg-canvas/40">
                        <td class="px-5 py-3.5 font-medium text-ink-900">{{ $p['nama'] }}</td>
                        <td class="px-5 py-3.5 uppercase text-ink/60">{{ $p['unit'] }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $p['jabatan'] }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $p['kegiatan'] }}</td>
                        <td class="px-5 py-3.5">
                            <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', $warnaStatus[$p['status']]])>{{ $p['status'] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <a href="#" class="text-xs font-semibold text-ink-900 hover:underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection