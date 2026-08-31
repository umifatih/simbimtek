{{-- resources/views/admin/peserta/verifikasi.blade.php --}}
@extends('layouts.admin')

@section('title', 'Verifikasi Peserta')
@section('page-title', 'Verifikasi Peserta')

@section('content')

    <div class="flex items-center justify-between">
        <p class="text-sm text-ink/60">Periksa kebenaran data sebelum peserta ditandai terverifikasi.</p>
        <span class="rounded-full bg-gold/15 px-3 py-1 text-xs font-semibold text-gold-600">18 menunggu</span>
    </div>

    @php
        $antrean = [
            [
                'nama' => 'Siti Aminah, S.Pd.', 'nip' => '198501012010012001', 'unit' => 'SD NEGERI 1 MANDIRAJA KULON',
                'jabatan' => 'Bendahara BOSP', 'kegiatan' => 'Bimtek Pengelolaan Keuangan Desa',
                'kepsek' => 'Drs. Wahyu Hidayat, M.Pd.', 'daftar_pada' => '28 Agt 2026, 09.14',
            ],
            [
                'nama' => 'Ahmad Fauzi, S.Pd.I.', 'nip' => '199002152015031002', 'unit' => 'MI NEGERI 1 BANYUMAS',
                'jabatan' => 'Operator BOSP', 'kegiatan' => 'Bimtek Penyusunan Laporan Kinerja',
                'kepsek' => 'Hj. Siti Nurjanah, S.Pd.I., M.Pd.', 'daftar_pada' => '28 Agt 2026, 11.02',
            ],
        ];
    @endphp

    <div class="mt-5 space-y-4">
        @foreach ($antrean as $a)
            <div class="rounded-2xl border border-line bg-white p-5 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-display text-sm font-semibold text-ink-900">{{ $a['nama'] }}</p>
                        <p class="mt-0.5 text-xs text-ink/50">Daftar {{ $a['daftar_pada'] }}</p>
                    </div>
                    <span class="rounded-full bg-gold/15 px-3 py-1 text-xs font-semibold text-gold-600">Menunggu</span>
                </div>

                <dl class="mt-4 grid gap-x-6 gap-y-3 border-t border-line pt-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">NIP</dt>
                        <dd class="mt-0.5 font-mono text-ink-900">{{ $a['nip'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Jabatan</dt>
                        <dd class="mt-0.5 text-ink-900">{{ $a['jabatan'] }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Unit Kerja</dt>
                        <dd class="mt-0.5 uppercase text-ink-900">{{ $a['unit'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Kegiatan</dt>
                        <dd class="mt-0.5 text-ink-900">{{ $a['kegiatan'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Kepala Sekolah</dt>
                        <dd class="mt-0.5 text-ink-900">{{ $a['kepsek'] }}</dd>
                    </div>
                </dl>

                <div class="mt-5 flex flex-col gap-2.5 border-t border-line pt-4 sm:flex-row sm:justify-end">
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-full border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                        Tolak
                    </button>
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Setujui / Verifikasi
                    </button>
                </div>
            </div>
        @endforeach
    </div>

@endsection