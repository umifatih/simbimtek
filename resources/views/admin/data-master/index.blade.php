@extends('layouts.admin')

@section('title', 'Data Master')
@section('page-title', 'Data Master Sekolah')

@section('content')

    @if (session('success'))
        <div class="mb-5 rounded-xl border border-success/30 bg-success/10 px-5 py-4 text-sm font-medium text-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ===== Kartu Import ===== --}}
    <div class="relative rounded-2xl border border-line bg-white p-5 sm:p-7" id="kartu-import">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-display text-base font-semibold text-ink-900">Import Data Master</h2>
                <p class="mt-1 text-sm text-ink/60">
                    Unggah file .xlsx / .xls / .csv berisi Data Sekolah, Unit Kerja, Kepala Sekolah, NIP KS, dan Desa.
                    Data dengan Unit Kerja yang sama akan diperbarui otomatis (tidak dobel).
                </p>
            </div>
            <a href="{{ route('admin.data-master.template') }}" class="mt-3 inline-flex shrink-0 items-center gap-2 rounded-full border border-line px-4 py-2 text-xs font-semibold text-ink-900 hover:bg-canvas sm:mt-0">
                Unduh Contoh Template
            </a>
        </div>

        <div class="mt-4 flex items-start gap-2.5 rounded-xl border border-gold/25 bg-gold/[0.05] p-4">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
            <div class="flex-1 text-xs leading-relaxed text-ink/70">
                Nama Kepala Sekolah &amp; NIP KS sekarang otomatis dirapikan setiap kali import / tambah / edit
                (huruf kapital cuma di depan, NIP tanpa spasi). Untuk data yang sudah kadung masuk sebelumnya,
                klik tombol ini sekali untuk merapikan semuanya.
            </div>
            <form id="form-rapikan" action="{{ route('admin.data-master.rapikan') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" id="btn-rapikan" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-full border border-gold/40 bg-white px-4 py-2 text-xs font-semibold text-ink-900 transition hover:bg-gold/10 disabled:cursor-not-allowed disabled:opacity-70">
                    <span class="teks">Rapikan Data Lama</span>
                </button>
            </form>
        </div>

        <form id="form-import" action="{{ route('admin.data-master.import') }}" method="POST" enctype="multipart/form-data" class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            @csrf
            <input
                type="file"
                name="file_master"
                accept=".xlsx,.xls,.csv"
                required
                class="w-full rounded-xl border border-line bg-white px-4 py-2.5 text-sm text-ink-900 file:mr-3 file:rounded-full file:border-0 file:bg-ink-900 file:px-4 file:py-1.5 file:text-xs file:font-semibold file:text-canvas"
            >
            <button type="submit" id="btn-import" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-ink-900 px-6 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700 disabled:cursor-not-allowed disabled:opacity-70">
                <span class="teks">Import</span>
            </button>
        </form>
        @error('file_master')
            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
        @enderror

        {{-- overlay loading saat import berjalan (file besar bisa makan beberapa detik) --}}
        <div id="overlay-import" class="pointer-events-none absolute inset-0 hidden items-center justify-center rounded-2xl bg-white/70 backdrop-blur-[1px]">
            <div class="flex items-center gap-2.5 rounded-full border border-line bg-white px-5 py-2.5 text-sm font-semibold text-ink-900 shadow-md">
                <svg class="h-4 w-4 animate-spin text-ink-900" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                Mengimpor data, mohon tunggu...
            </div>
        </div>
    </div>

    {{-- ===== Kartu Tambah Manual ===== --}}
    <div class="mt-6 rounded-2xl border border-line bg-white p-5 sm:p-7">
        <button type="button" id="btn-toggle-tambah" class="flex w-full items-center justify-between text-left">
            <h2 class="font-display text-base font-semibold text-ink-900">+ Tambah Sekolah Manual</h2>
            <svg id="ikon-toggle-tambah" class="h-4 w-4 shrink-0 text-ink/50 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
        </button>

        <form id="form-tambah" class="mt-5 hidden">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
                <div class="sm:col-span-2">
                    <label class="text-sm font-semibold text-ink-900">Unit Kerja <span class="text-gold-600">*</span></label>
                    <input type="text" name="unit_kerja" required placeholder="Contoh: SD Negeri 1 Contoh" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-2.5 text-sm text-ink-900 outline-none focus:border-ink-900">
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-semibold text-ink-900">Data Sekolah <span class="text-ink/35">(opsional, huruf kapital untuk kop surat)</span></label>
                    <input type="text" name="data_sekolah" placeholder="Contoh: SD NEGERI 1 CONTOH" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-2.5 text-sm text-ink-900 outline-none focus:border-ink-900">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-900">Kepala Sekolah</label>
                    <input type="text" name="nama_kepsek" placeholder="Nama & gelar kepala sekolah" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-2.5 text-sm text-ink-900 outline-none focus:border-ink-900">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-900">NIP KS</label>
                    <input type="text" name="nip_kepsek" placeholder="18 digit NIP" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-2.5 font-mono text-sm text-ink-900 outline-none focus:border-ink-900">
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-semibold text-ink-900">Desa</label>
                    <input type="text" name="desa" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-2.5 text-sm text-ink-900 outline-none focus:border-ink-900">
                </div>
            </div>

            <p id="error-tambah" class="mt-3 hidden text-xs font-medium text-red-600"></p>

            <div class="mt-5 flex items-center gap-3">
                <button type="submit" id="btn-simpan-tambah" class="inline-flex items-center justify-center gap-2 rounded-full bg-ink-900 px-6 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700 disabled:cursor-not-allowed disabled:opacity-70">
                    <span class="teks">Simpan Sekolah</span>
                </button>
                <button type="button" id="btn-batal-tambah" class="rounded-full border border-line px-6 py-2.5 text-sm font-semibold text-ink-900 hover:bg-canvas">Batal</button>
            </div>
        </form>
    </div>

    {{-- ===== Kartu Tabel ===== --}}
    <div class="mt-6 rounded-2xl border border-line bg-white p-5 sm:p-7">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-display text-base font-semibold text-ink-900">
                Daftar Sekolah <span id="jumlah-sekolah" class="font-normal text-ink/45">({{ $dataMasterList->total() }} sekolah)</span>
            </h2>
            <form method="GET" class="flex items-center gap-2">
                <input
                    type="text" name="cari" value="{{ $cari }}"
                    placeholder="Cari unit kerja / kepala sekolah / desa..."
                    class="w-64 rounded-xl border border-line bg-white px-4 py-2 text-sm text-ink-900 outline-none focus:border-ink-900"
                >
                <button class="rounded-xl border border-line px-3 py-2 text-sm text-ink-900 hover:bg-canvas">Cari</button>
            </form>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="w-full text-left text-sm" id="tabel-sekolah">
                <thead>
                    <tr class="border-b border-line text-xs font-semibold uppercase tracking-wide text-ink/45">
                        <th class="py-2 pr-3">Unit Kerja</th>
                        <th class="py-2 pr-3">Kepala Sekolah</th>
                        <th class="py-2 pr-3">NIP KS</th>
                        <th class="py-2 pr-3">Desa</th>
                        <th class="py-2 pr-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-sekolah">
                    @forelse ($dataMasterList as $s)
                        <tr class="border-b border-line/60 transition-opacity" data-id="{{ $s->id }}">
                            <td class="py-2.5 pr-3 font-medium text-ink-900 kolom-unit_kerja">{{ $s->unit_kerja }}</td>
                            <td class="py-2.5 pr-3 kolom-nama_kepsek">{{ $s->nama_kepsek ?: '-' }}</td>
                            <td class="py-2.5 pr-3 font-mono text-xs kolom-nip_kepsek">{{ $s->nip_kepsek ?: '-' }}</td>
                            <td class="py-2.5 pr-3 kolom-desa">{{ $s->desa ?: '-' }}</td>
                            <td class="py-2.5 pr-3 text-right">
                                <button type="button" class="btn-edit text-xs font-semibold text-ink-900 hover:underline">Edit</button>
                                <button type="button" class="btn-hapus ml-3 text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr id="baris-kosong">
                            <td colspan="5" class="py-8 text-center text-sm text-ink/45">
                                Belum ada data. Silakan import file Excel/CSV atau tambah manual di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $dataMasterList->links() }}</div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            ?? '{{ csrf_token() }}';

        // ===== Helper animasi loading di tombol =====
        const svgSpinner = '<svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';

        function mulaiLoadingTombol(btn, teksLoading) {
            btn.disabled = true;
            btn.dataset.teksAsli = btn.innerHTML;
            btn.innerHTML = `${svgSpinner}<span>${teksLoading}</span>`;
        }

        function selesaiLoadingTombol(btn) {
            btn.disabled = false;
            if (btn.dataset.teksAsli) btn.innerHTML = btn.dataset.teksAsli;
        }

        // ===== 1. IMPORT: tampilkan overlay + tombol loading begitu form dikirim =====
        const formImport = document.getElementById('form-import');
        const btnImport = document.getElementById('btn-import');
        const overlayImport = document.getElementById('overlay-import');

        formImport?.addEventListener('submit', () => {
            mulaiLoadingTombol(btnImport, 'Mengimpor...');
            overlayImport.classList.remove('hidden');
            overlayImport.classList.add('flex');
            // form tetap submit normal (reload halaman), tidak perlu preventDefault
        });

        // ===== 1b. RAPIKAN DATA LAMA =====
        const formRapikan = document.getElementById('form-rapikan');
        const btnRapikan = document.getElementById('btn-rapikan');

        formRapikan?.addEventListener('submit', (e) => {
            if (!confirm('Rapikan semua nama Kepala Sekolah & NIP KS yang sudah ada di tabel sekarang?')) {
                e.preventDefault();
                return;
            }
            mulaiLoadingTombol(btnRapikan, 'Merapikan...');
            // form tetap submit normal (reload halaman)
        });

        // ===== 2. TAMBAH MANUAL =====
        const btnToggleTambah = document.getElementById('btn-toggle-tambah');
        const ikonToggleTambah = document.getElementById('ikon-toggle-tambah');
        const formTambah = document.getElementById('form-tambah');
        const btnBatalTambah = document.getElementById('btn-batal-tambah');
        const btnSimpanTambah = document.getElementById('btn-simpan-tambah');
        const errorTambah = document.getElementById('error-tambah');

        function bukaFormTambah() {
            formTambah.classList.remove('hidden');
            ikonToggleTambah.classList.add('rotate-180');
        }
        function tutupFormTambah() {
            formTambah.classList.add('hidden');
            ikonToggleTambah.classList.remove('rotate-180');
            errorTambah.classList.add('hidden');
        }

        btnToggleTambah?.addEventListener('click', () => {
            formTambah.classList.contains('hidden') ? bukaFormTambah() : tutupFormTambah();
        });
        btnBatalTambah?.addEventListener('click', () => {
            formTambah.reset();
            tutupFormTambah();
        });

        formTambah?.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorTambah.classList.add('hidden');
            mulaiLoadingTombol(btnSimpanTambah, 'Menyimpan...');

            const data = Object.fromEntries(new FormData(formTambah).entries());

            try {
                const res = await fetch('{{ route('admin.data-master.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                if (!res.ok) {
                    const gagal = await res.json().catch(() => null);
                    const pesan = gagal?.errors?.unit_kerja?.[0] ?? 'Gagal menyimpan. Pastikan Unit Kerja tidak kosong / belum dipakai.';
                    errorTambah.textContent = pesan;
                    errorTambah.classList.remove('hidden');
                    return;
                }

                location.reload();
            } catch (e) {
                errorTambah.textContent = 'Gagal menghubungi server. Coba lagi.';
                errorTambah.classList.remove('hidden');
            } finally {
                selesaiLoadingTombol(btnSimpanTambah);
            }
        });

        // ===== 3 & 4. EDIT & HAPUS baris tabel =====
        document.querySelectorAll('#tabel-sekolah .btn-edit').forEach(btn => {
            btn.addEventListener('click', () => mulaiEdit(btn.closest('tr')));
        });

        document.querySelectorAll('#tabel-sekolah .btn-hapus').forEach(btn => {
            btn.addEventListener('click', () => hapusBaris(btn.closest('tr'), btn));
        });

        function mulaiEdit(tr) {
            const id = tr.dataset.id;
            const kolom = ['unit_kerja', 'nama_kepsek', 'nip_kepsek', 'desa'];

            kolom.forEach(k => {
                const td = tr.querySelector(`.kolom-${k}`);
                const nilai = td.textContent.trim() === '-' ? '' : td.textContent.trim();
                td.innerHTML = `<input type="text" value="${nilai.replace(/"/g, '&quot;')}" class="w-full rounded-lg border border-line px-2 py-1 text-sm" data-field="${k}">`;
            });

            const tdAksi = tr.querySelector('td:last-child');
            tdAksi.innerHTML = `
                <button type="button" class="btn-simpan inline-flex items-center gap-1.5 text-xs font-semibold text-success hover:underline"><span class="teks">Simpan</span></button>
                <button type="button" class="btn-batal ml-3 text-xs font-semibold text-ink/50 hover:underline">Batal</button>
            `;

            tdAksi.querySelector('.btn-simpan').addEventListener('click', (e) => simpanEdit(tr, id, e.currentTarget));
            tdAksi.querySelector('.btn-batal').addEventListener('click', () => location.reload());
        }

        async function simpanEdit(tr, id, btnSimpan) {
            const data = {};
            tr.querySelectorAll('input[data-field]').forEach(inp => {
                data[inp.dataset.field] = inp.value.trim();
                inp.disabled = true; // kunci input selama proses simpan
            });

            mulaiLoadingTombol(btnSimpan, 'Menyimpan...');

            try {
                const res = await fetch(`/admin/data-master/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });
                if (!res.ok) throw new Error('gagal simpan');
                location.reload();
            } catch (e) {
                alert('Gagal menyimpan perubahan. Pastikan Unit Kerja tidak kosong / tidak duplikat.');
                selesaiLoadingTombol(btnSimpan);
                tr.querySelectorAll('input[data-field]').forEach(inp => inp.disabled = false);
            }
        }

        async function hapusBaris(tr, btnHapus) {
            if (!confirm('Hapus data sekolah ini dari master?')) return;
            const id = tr.dataset.id;

            tr.querySelectorAll('button').forEach(b => b.disabled = true);
            mulaiLoadingTombol(btnHapus, 'Menghapus...');
            tr.classList.add('opacity-50');

            try {
                const res = await fetch(`/admin/data-master/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                });
                if (!res.ok) throw new Error('gagal hapus');

                tr.style.transition = 'opacity .2s ease';
                tr.style.opacity = '0';
                setTimeout(() => {
                    tr.remove();
                    const jumlahEl = document.getElementById('jumlah-sekolah');
                    const angka = parseInt(jumlahEl.textContent.replace(/\D/g, ''), 10) || 0;
                    jumlahEl.textContent = `(${Math.max(angka - 1, 0)} sekolah)`;
                    if (!document.querySelector('#tbody-sekolah tr[data-id]')) {
                        document.getElementById('tbody-sekolah').innerHTML =
                            '<tr><td colspan="5" class="py-8 text-center text-sm text-ink/45">Belum ada data. Silakan import file Excel/CSV atau tambah manual di atas.</td></tr>';
                    }
                }, 200);
            } catch (e) {
                alert('Gagal menghapus data.');
                selesaiLoadingTombol(btnHapus);
                tr.classList.remove('opacity-50');
                tr.querySelectorAll('button').forEach(b => b.disabled = false);
            }
        }
    </script>

@endsection