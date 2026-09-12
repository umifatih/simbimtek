@extends('layouts.print')

@section('title', 'Daftar Penerimaan Konsumsi & ATK')

@section('content')

    @foreach ($jadwalHarian as $hari)
        <div @if (!$loop->last) style="page-break-after: always;" @endif>

            <div class="flex items-center gap-4 border-b-2 border-ink-900 pb-4">
                @if ($setting->logo_url)
                    <img src="{{ $setting->logo_url }}" alt="Logo" class="h-14 w-14 shrink-0 rounded-lg object-contain">
                @else
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-ink-900 font-display text-lg font-bold text-canvas">
                        {{ strtoupper(substr($setting->nama_aplikasi, 0, 2)) }}
                    </span>
                @endif
                <div>
                    <p class="font-display text-lg font-bold text-ink-900">{{ $setting->nama_aplikasi }}</p>
                    <p class="text-xs text-ink/55">Sistem Informasi Manajemen Bimbingan Teknis</p>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="font-display text-lg font-bold uppercase tracking-wide text-ink-900">Daftar Penerimaan Konsumsi dan ATK</p>
                <p class="mt-1 text-sm text-ink/70">{{ $kegiatan->nama }}</p>
                <p class="font-mono text-xs text-ink/50">{{ $hari['label'] }} — {{ $hari['tanggal_format'] }} — {{ $kegiatan->lokasi }}</p>
            </div>

            <table class="mt-6 w-full border-collapse text-sm">
                <thead>
                    <tr class="border-b-2 border-ink-900 text-left text-xs font-semibold uppercase tracking-wide text-ink-900">
                        <th class="w-10 py-2.5">No</th>
                        <th class="py-2.5">Nama dan Gelar</th>
                        <th class="py-2.5">Unit Kerja</th>
                        <th class="w-24 py-2.5 text-center">ATK</th>
                        <th class="w-24 py-2.5 text-center">Konsumsi</th>
                        <th class="w-32 py-2.5">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peserta as $i => $p)
                        <tr class="border-b border-line">
                            <td class="py-4 align-top text-ink/70">{{ $i + 1 }}</td>
                            <td class="py-4 align-top font-medium text-ink-900">{{ $p->nama_gelar }}</td>
                            <td class="py-4 align-top uppercase text-ink/70">{{ $p->unit_kerja }}</td>
                            <td class="py-4 text-center align-top text-ink/30">☐</td>
                            <td class="py-4 text-center align-top text-ink/30">☐</td>
                            <td class="py-4"></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-ink/50">Belum ada peserta terdaftar untuk kegiatan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p class="mt-4 text-xs text-ink/45">Lembar ini khusus penerimaan {{ $hari['label'] }} ({{ $hari['tanggal_format'] }}); centang (☑) kolom yang sesuai saat item diterima, kolom Tanda Tangan diisi manual.</p>

            <div class="mt-8 flex justify-end">
                <div class="text-center text-sm">
                    <p class="text-ink/60">Purbalingga, {{ $hari['tanggal_format'] }}</p>
                    <p class="mt-0.5 text-ink/60">Panitia Penyelenggara</p>
                    <div class="mt-16 border-b border-ink/40"></div>
                    <p class="mt-1 font-medium text-ink-900">(_________________________)</p>
                </div>
            </div>

        </div>
    @endforeach

@endsection