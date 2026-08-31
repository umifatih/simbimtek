{{-- resources/views/admin/cetak/konsumsi-atk.blade.php --}}
@extends('layouts.print')

@section('title', 'Daftar Penerimaan Konsumsi & ATK')

@section('content')

    @php
        $kegiatan = ['nama' => 'Bimtek Pengelolaan Keuangan Desa', 'tanggal' => '14–16 Sep 2026', 'lokasi' => 'Aula Diklat, Purbalingga'];
        $peserta = [
            ['no' => 1, 'nama' => 'Siti Aminah, S.Pd.', 'unit' => 'SD NEGERI 1 MANDIRAJA KULON'],
            ['no' => 2, 'nama' => 'Budi Santoso, S.Kom.', 'unit' => 'SMP NEGERI 2 PURBALINGGA'],
            ['no' => 3, 'nama' => 'Rina Wulandari, S.Pd.', 'unit' => 'SD NEGERI 3 KALIMANAH'],
        ];
    @endphp

    <div class="flex items-center gap-4 border-b-2 border-ink-900 pb-4">
        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-ink-900 font-display text-lg font-bold text-canvas">SB</span>
        <div>
            <p class="font-display text-lg font-bold text-ink-900">SIMBIMTEK</p>
            <p class="text-xs text-ink/55">Sistem Informasi Manajemen Bimbingan Teknis</p>
        </div>
    </div>

    <div class="mt-6 text-center">
        <p class="font-display text-lg font-bold uppercase tracking-wide text-ink-900">Daftar Penerimaan Konsumsi dan ATK</p>
        <p class="mt-1 text-sm text-ink/70">{{ $kegiatan['nama'] }}</p>
        <p class="font-mono text-xs text-ink/50">{{ $kegiatan['tanggal'] }} — {{ $kegiatan['lokasi'] }}</p>
    </div>

    <table class="mt-6 w-full border-collapse text-sm">
        <thead>
            <tr class="border-b-2 border-ink-900 text-left text-xs font-semibold uppercase tracking-wide text-ink-900">
                <th class="w-10 py-2.5">No</th>
                <th class="py-2.5">Nama dan Gelar</th>
                <th class="py-2.5">Unit Kerja</th>
                <th class="w-24 py-2.5 text-center">ATK</th>
                <th class="w-24 py-2.5 text-center">Konsumsi H1</th>
                <th class="w-24 py-2.5 text-center">Konsumsi H2</th>
                <th class="w-24 py-2.5 text-center">Konsumsi H3</th>
                <th class="w-32 py-2.5">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peserta as $p)
                <tr class="border-b border-line">
                    <td class="py-4 align-top text-ink/70">{{ $p['no'] }}</td>
                    <td class="py-4 align-top font-medium text-ink-900">{{ $p['nama'] }}</td>
                    <td class="py-4 align-top uppercase text-ink/70">{{ $p['unit'] }}</td>
                    <td class="py-4 text-center align-top text-ink/30">☐</td>
                    <td class="py-4 text-center align-top text-ink/30">☐</td>
                    <td class="py-4 text-center align-top text-ink/30">☐</td>
                    <td class="py-4 text-center align-top text-ink/30">☐</td>
                    <td class="py-4"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="mt-4 text-xs text-ink/45">Centang (☑) kolom yang sesuai saat item diterima peserta; kolom Tanda Tangan diisi manual.</p>

    <div class="mt-8 flex justify-end">
        <div class="text-center text-sm">
            <p class="text-ink/60">Purbalingga, {{ now()->translatedFormat('d F Y') }}</p>
            <p class="mt-0.5 text-ink/60">Panitia Penyelenggara</p>
            <div class="mt-16 border-b border-ink/40"></div>
            <p class="mt-1 font-medium text-ink-900">(_________________________)</p>
        </div>
    </div>

@endsection