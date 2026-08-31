{{-- resources/views/admin/kegiatan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Data Kegiatan')
@section('page-title', 'Data Kegiatan')

@section('content')

    @if (session('status'))
        <div class="mb-5 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-sm font-medium text-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-ink/60">Kelola daftar kegiatan bimtek yang dibuka untuk pendaftaran.</p>
        <button type="button" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Kegiatan
        </button>
    </div>

    @php
        $warnaStatus = ['dibuka' => 'bg-success/10 text-success', 'ditutup' => 'bg-red-50 text-red-600', 'selesai' => 'bg-ink-900/10 text-ink/50'];
        $labelStatus = ['dibuka' => 'Dibuka', 'ditutup' => 'Ditutup', 'selesai' => 'Selesai'];
    @endphp

    {{-- Mobile: kartu. Desktop: tabel. --}}
    <div class="mt-5 space-y-3 sm:hidden">
        @forelse ($kegiatan as $k)
            <div class="rounded-2xl border border-line bg-white p-4">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-semibold text-ink-900">{{ $k->nama }}</p>
                    <span @class(['shrink-0 rounded-full px-2.5 py-0.5 text-[11px] font-semibold', $warnaStatus[$k->status]])>{{ $labelStatus[$k->status] }}</span>
                </div>
                <p class="mt-1.5 font-mono text-xs text-ink/50">{{ $k->tanggal }}</p>
                <p class="mt-0.5 text-xs text-ink/50">{{ $k->lokasi }}</p>
                <div class="mt-3 flex items-center justify-between border-t border-line pt-3">
                    <span class="text-xs font-medium text-ink/60">Kuota {{ $k->pendaftaran_count }}/{{ $k->kuota }}</span>
                    <div class="flex gap-3 text-xs font-semibold">
                        <button type="button" class="text-ink-900">Edit</button>
                        <form action="{{ route('admin.kegiatan.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus kegiatan {{ $k->nama }}?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="py-10 text-center text-sm text-ink/40">Belum ada kegiatan. Tambahkan yang pertama.</p>
        @endforelse
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
                @forelse ($kegiatan as $k)
                    <tr class="transition hover:bg-canvas/40">
                        <td class="px-5 py-3.5 font-medium text-ink-900">{{ $k->nama }}</td>
                        <td class="px-5 py-3.5 font-mono text-xs text-ink/60">{{ $k->tanggal }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $k->lokasi }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $k->pendaftaran_count }}/{{ $k->kuota }}</td>
                        <td class="px-5 py-3.5">
                            <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', $warnaStatus[$k->status]])>{{ $labelStatus[$k->status] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <button type="button" class="text-xs font-semibold text-ink-900 hover:underline">Edit</button>
                            <span class="mx-1.5 text-line">|</span>
                            <form action="{{ route('admin.kegiatan.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kegiatan {{ $k->nama }}?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-ink/40">Belum ada kegiatan. Tambahkan yang pertama.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection