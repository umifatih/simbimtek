@extends('layouts.admin')

@section('title', 'Sertifikat')
@section('page-title', 'Sertifikat')

@section('content')
<div class="max-w-4xl">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-display text-lg font-semibold text-ink-900">Peserta Siap Cetak Sertifikat</h2>
            <p class="mt-1 text-sm text-ink/55">Daftar peserta yang absensinya sudah lengkap untuk kegiatan terpilih.</p>
        </div>

        <form method="GET" class="flex items-center gap-2">
            <select name="kegiatan_id" onchange="this.form.submit()" class="rounded-lg border border-line px-3 py-2 text-sm">
                @foreach ($kegiatanList as $k)
                    <option value="{{ $k->id }}" @selected($k->id == $kegiatanId)>{{ $k->nama }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-line">
        <table class="w-full text-left text-sm">
            <thead class="bg-ink-900/[0.03] text-xs uppercase tracking-wide text-ink/50">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">NIP</th>
                    <th class="px-4 py-3">Unit Kerja</th>
                    <th class="px-4 py-3">Absensi</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($pendaftaranList as $p)
                    @php $progres = $p->progresAbsensi(); @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium text-ink-900">{{ $p->nama_gelar }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-ink/60">{{ $p->nip }}</td>
                        <td class="px-4 py-3 uppercase text-ink/70">{{ $p->unit_kerja }}</td>
                        <td class="px-4 py-3 text-ink/70">{{ $progres['hadir'] }}/{{ $progres['wajib'] }} hari</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('pendaftaran.unduh', ['pendaftaran' => $p->id, 'jenis' => 'sertifikat']) }}"
                               class="inline-flex items-center gap-1.5 rounded-lg bg-ink-900 px-3 py-1.5 text-xs font-semibold text-canvas hover:bg-ink-700">
                                Unduh
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-ink/50">
                            Belum ada peserta dengan absensi lengkap untuk kegiatan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection