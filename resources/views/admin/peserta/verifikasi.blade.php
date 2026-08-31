{{-- resources/views/admin/peserta/verifikasi.blade.php --}}
@extends('layouts.admin')

@section('title', 'Verifikasi Peserta')
@section('page-title', 'Verifikasi Peserta')

@section('content')

    @if (session('status'))
        <div class="mb-5 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-sm font-medium text-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <p class="text-sm text-ink/60">Periksa kebenaran data sebelum peserta ditandai terverifikasi.</p>
        <span class="rounded-full bg-gold/15 px-3 py-1 text-xs font-semibold text-gold-600">{{ $antrean->count() }} menunggu</span>
    </div>

    <div class="mt-5 space-y-4">
        @forelse ($antrean as $a)
            <div class="rounded-2xl border border-line bg-white p-5 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="font-display text-sm font-semibold text-ink-900">{{ $a->nama_gelar }}</p>
                        <p class="mt-0.5 text-xs text-ink/50">Daftar {{ $a->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    <span class="rounded-full bg-gold/15 px-3 py-1 text-xs font-semibold text-gold-600">Menunggu</span>
                </div>

                <dl class="mt-4 grid gap-x-6 gap-y-3 border-t border-line pt-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">NIP</dt>
                        <dd class="mt-0.5 font-mono text-ink-900">{{ $a->peserta->nip }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Jabatan</dt>
                        <dd class="mt-0.5 text-ink-900">{{ $a->jabatan }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Unit Kerja</dt>
                        <dd class="mt-0.5 uppercase text-ink-900">{{ $a->unit_kerja }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Kegiatan</dt>
                        <dd class="mt-0.5 text-ink-900">{{ $a->kegiatan->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Kepala Sekolah</dt>
                        <dd class="mt-0.5 text-ink-900">{{ $a->peserta->nama_gelar_kepsek }}</dd>
                    </div>
                </dl>

                <div class="mt-5 flex flex-col gap-2.5 border-t border-line pt-4 sm:flex-row sm:justify-end">
                    <form action="{{ route('admin.peserta.tolak', $a) }}" method="POST" onsubmit="return confirm('Tolak dan hapus pendaftaran {{ $a->nama_gelar }}?');">
                        @csrf
                        <button type="submit" class="w-full rounded-full border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50 sm:w-auto">
                            Tolak
                        </button>
                    </form>
                    <form action="{{ route('admin.peserta.setujui', $a) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700 sm:w-auto">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            Setujui / Verifikasi
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-line p-10 text-center">
                <p class="text-sm text-ink/50">Tidak ada peserta yang menunggu verifikasi saat ini.</p>
            </div>
        @endforelse
    </div>

@endsection