@extends('layouts.admin')

@section('title', 'Data Peserta')
@section('page-title', 'Data Peserta')

@section('content')

    <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-ink/35" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama, unit kerja, atau NIP…" class="w-full rounded-full border border-line bg-white py-2.5 pl-10 pr-4 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
        </div>
        <select name="kegiatan_id" onchange="this.form.submit()" class="shrink-0 rounded-full border border-line bg-white px-4 py-2.5 text-sm text-ink-900 outline-none focus:border-ink-900">
            <option value="">Semua Kegiatan</option>
            @foreach ($kegiatan as $k)
                <option value="{{ $k->id }}" @selected(request('kegiatan_id') == $k->id)>{{ $k->nama }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()" class="shrink-0 rounded-full border border-line bg-white px-4 py-2.5 text-sm text-ink-900 outline-none focus:border-ink-900">
            <option value="">Semua Status</option>
            <option value="daftar" @selected(request('status') === 'daftar')>Terdaftar</option>
            <option value="sppd" @selected(request('status') === 'sppd')>SPPD Terbit</option>
            <option value="sertifikat" @selected(request('status') === 'sertifikat')>Selesai</option>
        </select>
        <button type="submit" class="hidden">Cari</button>
    </form>

    @php
        $warnaStatus = [
            'daftar' => ['Terdaftar', 'bg-gold/15 text-gold-600'],
            'sppd' => ['SPPD Terbit', 'bg-[#3E6B8F]/10 text-[#3E6B8F]'],
            'sertifikat' => ['Selesai', 'bg-success/10 text-success'],
        ];
    @endphp

    {{-- Mobile: kartu --}}
    <div class="mt-5 space-y-3 sm:hidden">
        @forelse ($peserta as $p)
            <div onclick="showDetail({
                noDaftar: '{{ addslashes($p->nomor_pendaftaran ?? '#' . $p->id) }}',
                nama: '{{ addslashes($p->nama_gelar) }}',
                nip: '{{ addslashes($p->peserta->nip ?? '-') }}',
                unit: '{{ addslashes($p->unit_kerja) }}',
                jabatan: '{{ addslashes($p->jabatan) }}',
                kegiatan: '{{ addslashes($p->kegiatan->nama) }}',
                statusText: '{{ $warnaStatus[$p->status][0] }}',
                statusClass: '{{ $warnaStatus[$p->status][1] }}',
                hadir: '{{ $p->hadir_pada ? $p->hadir_pada->format('d M Y, H:i') : 'Belum hadir' }}'
            })" class="cursor-pointer rounded-2xl border border-line bg-white p-4 transition hover:border-ink-900/30">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-semibold text-ink-900">{{ $p->nama_gelar }}</p>
                    <span @class(['shrink-0 rounded-full px-2.5 py-0.5 text-[11px] font-semibold', $warnaStatus[$p->status][1]])>{{ $warnaStatus[$p->status][0] }}</span>
                </div>
                <p class="mt-1.5 text-xs uppercase text-ink/50">{{ $p->unit_kerja }}</p>
                <p class="mt-2 text-xs text-ink/60">{{ $p->jabatan }} · {{ $p->kegiatan->nama }}</p>
                @if ($p->hadir_pada)
                    <span class="mt-2 inline-flex items-center gap-1 text-[11px] font-medium text-success">✓ Hadir {{ $p->hadir_pada->format('d M, H:i') }}</span>
                @endif
            </div>
        @empty
            <p class="py-10 text-center text-sm text-ink/40">Belum ada peserta yang cocok dengan filter ini.</p>
        @endforelse
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
                    <th class="px-5 py-3">Kehadiran</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($peserta as $p)
                    <tr onclick="showDetail({
                        noDaftar: '{{ addslashes($p->nomor_pendaftaran ?? '#' . $p->id) }}',
                        nama: '{{ addslashes($p->nama_gelar) }}',
                        nip: '{{ addslashes($p->peserta->nip ?? '-') }}',
                        unit: '{{ addslashes($p->unit_kerja) }}',
                        jabatan: '{{ addslashes($p->jabatan) }}',
                        kegiatan: '{{ addslashes($p->kegiatan->nama) }}',
                        statusText: '{{ $warnaStatus[$p->status][0] }}',
                        statusClass: '{{ $warnaStatus[$p->status][1] }}',
                        hadir: '{{ $p->hadir_pada ? $p->hadir_pada->format('d M Y, H:i') : 'Belum hadir' }}'
                    })" class="cursor-pointer transition hover:bg-canvas/40">
                        <td class="px-5 py-3.5 font-medium text-ink-900">{{ $p->nama_gelar }}</td>
                        <td class="px-5 py-3.5 uppercase text-ink/60">{{ $p->unit_kerja }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $p->jabatan }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $p->kegiatan->nama }}</td>
                        <td class="px-5 py-3.5">
                            @if ($p->hadir_pada)
                                <span class="text-xs font-medium text-success">✓ {{ $p->hadir_pada->format('d M, H:i') }}</span>
                            @else
                                <span class="text-xs text-ink/35">Belum hadir</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', $warnaStatus[$p->status][1]])>{{ $warnaStatus[$p->status][0] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-ink/40">Belum ada peserta yang cocok dengan filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $peserta->links() }}</div>

    {{-- Modal Detail Peserta (Modern, Clean, Minimalist) --}}
    <div id="pesertaModal" class="fixed inset-0 z-50 flex items-center justify-center bg-ink-900/40 backdrop-blur-sm p-4 hidden transition-all duration-300">
        <div class="w-full max-w-md transform overflow-hidden rounded-3xl border border-line/80 bg-white p-6 sm:p-8 shadow-2xl shadow-ink/10 transition-all">
            
            {{-- Header Modal --}}
            <div class="flex items-center justify-between border-b border-line/60 pb-4">
                <div>
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-ink/40">Detail Peserta</span>
                    <h3 class="text-base font-bold text-ink-900 mt-0.5">Informasi Keikutsertaan</h3>
                </div>
                <button onclick="closeDetail()" class="flex h-8 w-8 items-center justify-center rounded-full bg-canvas text-ink/50 transition hover:bg-line/40 hover:text-ink-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Detail Content --}}
            <div class="mt-5 space-y-3.5 text-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 border-b border-line/40 pb-3">
                    <span class="text-xs font-medium text-ink/50">Nomor Pendaftaran</span>
                    <span class="font-semibold text-ink-900 font-mono text-xs sm:text-right" id="modal-no-daftar">-</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 border-b border-line/40 pb-3">
                    <span class="text-xs font-medium text-ink/50">Nama & Gelar</span>
                    <span class="font-semibold text-ink-900 text-right" id="modal-nama">-</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 border-b border-line/40 pb-3">
                    <span class="text-xs font-medium text-ink/50">NIP</span>
                    <span class="font-medium text-ink-900 font-mono text-xs sm:text-right" id="modal-nip">-</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 border-b border-line/40 pb-3">
                    <span class="text-xs font-medium text-ink/50">Unit Kerja</span>
                    <span class="font-medium uppercase text-ink-900 sm:text-right" id="modal-unit">-</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 border-b border-line/40 pb-3">
                    <span class="text-xs font-medium text-ink/50">Jabatan</span>
                    <span class="font-medium text-ink-900 sm:text-right" id="modal-jabatan">-</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 border-b border-line/40 pb-3">
                    <span class="text-xs font-medium text-ink/50">Kegiatan</span>
                    <span class="font-medium text-ink-900 sm:text-right" id="modal-kegiatan">-</span>
                </div>
                <div class="flex items-center justify-between border-b border-line/40 pb-3">
                    <span class="text-xs font-medium text-ink/50">Status Keikutsertaan</span>
                    <span id="modal-status" class="rounded-full px-3 py-1 text-xs font-semibold">-</span>
                </div>
                <div class="flex items-center justify-between pb-1">
                    <span class="text-xs font-medium text-ink/50">Waktu Kehadiran</span>
                    <span class="font-medium text-ink-900 text-xs" id="modal-hadir">-</span>
                </div>
            </div>

            {{-- Footer Button --}}
            <div class="mt-6">
                <button onclick="closeDetail()" class="w-full rounded-2xl bg-ink-900 py-3 text-xs font-semibold text-white shadow-md shadow-ink-900/10 transition hover:opacity-90 active:scale-[0.98]">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Script JavaScript Murni untuk Kontrol Modal --}}
    <script>
        function showDetail(data) {
            document.getElementById('modal-no-daftar').innerText = data.noDaftar;
            document.getElementById('modal-nama').innerText = data.nama;
            document.getElementById('modal-nip').innerText = data.nip;
            document.getElementById('modal-unit').innerText = data.unit;
            document.getElementById('modal-jabatan').innerText = data.jabatan;
            document.getElementById('modal-kegiatan').innerText = data.kegiatan;
            
            const statusEl = document.getElementById('modal-status');
            statusEl.innerText = data.statusText;
            statusEl.className = 'rounded-full px-3 py-1 text-xs font-semibold ' + data.statusClass;
            
            document.getElementById('modal-hadir').innerText = data.hadir;
            
            document.getElementById('pesertaModal').classList.remove('hidden');
        }

        function closeDetail() {
            document.getElementById('pesertaModal').classList.add('hidden');
        }

        // Tutup modal jika area luar popup diklik
        document.getElementById('pesertaModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetail();
            }
        });
    </script>

@endsection