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

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-ink/60">Kelola daftar kegiatan bimtek yang dibuka untuk pendaftaran.</p>
        <button type="button" onclick="bukaModalTambah()" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Kegiatan
        </button>
    </div>

    @php
        $warnaStatus = ['dibuka' => 'bg-success/10 text-success', 'ditutup' => 'bg-red-50 text-red-600', 'selesai' => 'bg-ink-900/10 text-ink/50'];
        $labelStatus = ['dibuka' => 'Dibuka', 'ditutup' => 'Ditutup', 'selesai' => 'Selesai'];
    @endphp

    <div class="mt-5 space-y-3 sm:hidden">
        @forelse ($kegiatan as $k)
            @php
                $dataEdit = [
                    'id' => $k->id,
                    'nama' => $k->nama,
                    'tanggal' => $k->tanggal,
                    'tanggal_mulai' => optional($k->tanggal_mulai)->format('Y-m-d'),
                    'tanggal_selesai' => optional($k->tanggal_selesai)->format('Y-m-d'),
                    'lokasi' => $k->lokasi,
                    'kuota' => $k->kuota,
                    'status' => $k->status,
                ];
            @endphp
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
                        <button type="button" onclick='bukaModalEdit(@json($dataEdit))' class="text-ink-900">Edit</button>
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
                    @php
                        $dataEdit = [
                            'id' => $k->id,
                            'nama' => $k->nama,
                            'tanggal' => $k->tanggal,
                            'tanggal_mulai' => optional($k->tanggal_mulai)->format('Y-m-d'),
                            'tanggal_selesai' => optional($k->tanggal_selesai)->format('Y-m-d'),
                            'lokasi' => $k->lokasi,
                            'kuota' => $k->kuota,
                            'status' => $k->status,
                        ];
                    @endphp
                    <tr class="transition hover:bg-canvas/40">
                        <td class="px-5 py-3.5 font-medium text-ink-900">{{ $k->nama }}</td>
                        <td class="px-5 py-3.5 font-mono text-xs text-ink/60">{{ $k->tanggal }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $k->lokasi }}</td>
                        <td class="px-5 py-3.5 text-ink/60">{{ $k->pendaftaran_count }}/{{ $k->kuota }}</td>
                        <td class="px-5 py-3.5">
                            <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', $warnaStatus[$k->status]])>{{ $labelStatus[$k->status] }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <button type="button" onclick='bukaModalEdit(@json($dataEdit))' class="text-xs font-semibold text-ink-900 hover:underline">Edit</button>
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

    {{-- ============ MODAL TAMBAH / EDIT KEGIATAN ============ --}}
    <div id="modal-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-ink-900/40 p-4" onclick="if (event.target === this) tutupModal()">
        <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-2xl bg-white p-5 shadow-xl sm:p-6">
            <div class="flex items-center justify-between">
                <h3 id="modal-judul" class="font-display text-base font-semibold text-ink-900">Tambah Kegiatan</h3>
                <button type="button" onclick="tutupModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-ink/40 hover:bg-canvas hover:text-ink-900">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="form-kegiatan" method="POST" class="mt-5 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="">

                <div>
                    <label class="text-sm font-semibold text-ink-900">Nama Kegiatan</label>
                    <input type="text" name="nama" id="input-nama" required placeholder="Contoh: Bimtek Pengelolaan Keuangan Desa" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                </div>

                <div>
                    <label class="text-sm font-semibold text-ink-900">Jadwal (teks tampilan)</label>
                    <input type="text" name="tanggal" id="input-tanggal" required placeholder="Contoh: 14–16 Sep 2026" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-semibold text-ink-900">Tgl Mulai</label>
                        <input type="date" name="tanggal_mulai" id="input-tanggal_mulai" class="mt-2 w-full rounded-xl border border-line bg-white px-3 py-3 text-sm text-ink-900 outline-none focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-900">Tgl Selesai</label>
                        <input type="date" name="tanggal_selesai" id="input-tanggal_selesai" class="mt-2 w-full rounded-xl border border-line bg-white px-3 py-3 text-sm text-ink-900 outline-none focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-ink-900">Lokasi</label>
                    <input type="text" name="lokasi" id="input-lokasi" required placeholder="Contoh: Aula Diklat, Purbalingga" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-semibold text-ink-900">Kuota</label>
                        <input type="number" name="kuota" id="input-kuota" required min="1" value="40" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-900">Status</label>
                        <select name="status" id="input-status" required class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                            <option value="dibuka">Dibuka</option>
                            <option value="ditutup">Ditutup</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-full bg-ink-900 px-6 py-3.5 text-sm font-semibold text-canvas transition hover:bg-ink-700">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    <script>
        const modalOverlay = document.getElementById('modal-overlay');
        const modalJudul = document.getElementById('modal-judul');
        const formKegiatan = document.getElementById('form-kegiatan');
        const formMethod = document.getElementById('form-method');

        function bukaModal() {
            modalOverlay.classList.remove('hidden');
            modalOverlay.classList.add('flex');
        }

        function tutupModal() {
            modalOverlay.classList.add('hidden');
            modalOverlay.classList.remove('flex');
        }

        function bukaModalTambah() {
            modalJudul.textContent = 'Tambah Kegiatan';
            formKegiatan.reset();
            formKegiatan.action = '{{ route('admin.kegiatan.store') }}';
            formMethod.value = '';
            document.getElementById('input-kuota').value = 40;
            bukaModal();
        }

        function bukaModalEdit(data) {
            modalJudul.textContent = 'Edit Kegiatan';
            document.getElementById('input-nama').value = data.nama ?? '';
            document.getElementById('input-tanggal').value = data.tanggal ?? '';
            document.getElementById('input-tanggal_mulai').value = data.tanggal_mulai ?? '';
            document.getElementById('input-tanggal_selesai').value = data.tanggal_selesai ?? '';
            document.getElementById('input-lokasi').value = data.lokasi ?? '';
            document.getElementById('input-kuota').value = data.kuota ?? 40;
            document.getElementById('input-status').value = data.status ?? 'dibuka';

            formKegiatan.action = `/admin/kegiatan/${data.id}`;
            formMethod.value = 'PUT';
            bukaModal();
        }
    </script>

@endsection