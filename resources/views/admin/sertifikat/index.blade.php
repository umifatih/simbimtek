@extends('layouts.admin')

@section('title', 'Sertifikat')
@section('page-title', 'Sertifikat')

@section('content')
<div class="max-w-4xl" x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }} }">

    {{-- ===================== PANEL PENANDATANGAN SERTIFIKAT (READ-ONLY) ===================== --}}
    <div class="rounded-2xl border border-line bg-white p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="font-display text-base font-bold text-ink-900">Penandatangan Sertifikat</h2>
                <p class="mt-1 text-sm text-ink/55">Nama & NIP Ketua K3S yang tercetak otomatis di setiap sertifikat.</p>
            </div>

            <button type="button" @click="showModal = true"
                    class="shrink-0 rounded-full border border-line px-4 py-1.5 text-xs font-semibold text-ink-900 transition hover:bg-ink-900/5">
                Edit
            </button>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-ink/40">Nama Ketua K3S</p>
                <p class="mt-1 truncate text-sm font-semibold text-ink-900">
                    {{ $setting->nama_ketua_k3s ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-ink/40">NIP Ketua K3S</p>
                <p class="mt-1 truncate font-mono text-sm font-semibold text-ink-900">
                    {{ $setting->nip_ketua_k3s ?? '—' }}
                </p>
            </div>
        </div>

        @if (session('status_ketua'))
            <div class="mt-4 rounded-lg bg-success/10 px-4 py-2.5 text-sm text-success">
                {{ session('status_ketua') }}
            </div>
        @endif
    </div>
    {{-- ===================== /PANEL PENANDATANGAN SERTIFIKAT ===================== --}}

    {{-- ===================== MODAL EDIT ===================== --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">

        {{-- overlay --}}
        <div x-show="showModal" x-transition.opacity
             @click="showModal = false"
             class="absolute inset-0 bg-ink-900/40"></div>

        {{-- modal box --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.outside="showModal = false"
             class="relative w-full max-w-lg rounded-2xl border border-line bg-white p-5 shadow-lg sm:p-6">

            <div class="flex items-center justify-between">
                <h3 class="font-display text-base font-bold text-ink-900">Edit Penandatangan Sertifikat</h3>
                <button type="button" @click="showModal = false"
                        class="rounded-full p-1.5 text-ink/40 hover:bg-ink-900/5 hover:text-ink-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.sertifikat.ketua.update') }}" class="mt-5 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-semibold text-ink-900">Nama Ketua K3S</label>
                    <input type="text" name="nama_ketua_k3s"
                           value="{{ old('nama_ketua_k3s', $setting->nama_ketua_k3s) }}"
                           placeholder="mis. Drs. Ahmad Fauzi, M.Pd."
                           class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                    @error('nama_ketua_k3s') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-ink-900">NIP Ketua K3S</label>
                    <input type="text" name="nip_ketua_k3s" inputmode="numeric" maxlength="18"
                           value="{{ old('nip_ketua_k3s', $setting->nip_ketua_k3s) }}"
                           placeholder="mis. 198501012010011001"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 font-mono text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                    @error('nip_ketua_k3s') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-2 border-t border-line pt-4">
                    <button type="button" @click="showModal = false"
                            class="rounded-full px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink-900/5">
                        Batal
                    </button>
                    <button type="submit"
                            class="rounded-full bg-ink-900 px-5 py-2 text-sm font-semibold text-canvas transition hover:bg-ink-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== PANEL MATERI KEGIATAN ===================== --}}
<div class="mt-8 rounded-2xl border border-line bg-white p-5 sm:p-6"
     x-data="{ showModalMateri: {{ $errors->has('nama_materi') || $errors->has('jumlah_jp') ? 'true' : 'false' }} }">

    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h2 class="font-display text-base font-bold text-ink-900">Materi Kegiatan</h2>
            <p class="mt-1 text-sm text-ink/55">Daftar materi & jumlah JP yang tercetak pada sertifikat.</p>
        </div>

        <button type="button" @click="showModalMateri = true"
                class="shrink-0 rounded-full border border-line px-4 py-1.5 text-xs font-semibold text-ink-900 transition hover:bg-ink-900/5">
            + Tambah Materi
        </button>
    </div>

    <div class="mt-5 overflow-hidden rounded-xl border border-line">
        <table class="w-full text-left text-sm">
            <thead class="bg-ink-900/[0.03] text-xs uppercase tracking-wide text-ink/50">
                <tr>
                    <th class="px-4 py-2.5 w-16">Urutan</th>
                    <th class="px-4 py-2.5">Nama Materi</th>
                    <th class="px-4 py-2.5 w-24">JP</th>
                    <th class="px-4 py-2.5 w-20 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse ($materiList as $m)
                    <tr>
                        <td class="px-4 py-2.5 text-ink/60">{{ $m->urutan }}</td>
                        <td class="px-4 py-2.5 font-medium text-ink-900">{{ $m->nama_materi }}</td>
                        <td class="px-4 py-2.5 text-ink/70">{{ $m->jumlah_jp }} JP</td>
                        <td class="px-4 py-2.5 text-right">
                            <form method="POST"
                                  action="{{ route('admin.sertifikat.materi.destroy', $m->id) }}"
                                  onsubmit="return confirm('Hapus materi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-500 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-ink/50">
                            Belum ada materi untuk kegiatan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (session('status_materi'))
        <div class="mt-4 rounded-lg bg-success/10 px-4 py-2.5 text-sm text-success">
            {{ session('status_materi') }}
        </div>
    @endif

    {{-- ===== MODAL TAMBAH MATERI ===== --}}
    <div x-show="showModalMateri" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">

        <div x-show="showModalMateri" x-transition.opacity
             @click="showModalMateri = false"
             class="absolute inset-0 bg-ink-900/40"></div>

        <div x-show="showModalMateri"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.outside="showModalMateri = false"
             class="relative w-full max-w-lg rounded-2xl border border-line bg-white p-5 shadow-lg sm:p-6">

            <div class="flex items-center justify-between">
                <h3 class="font-display text-base font-bold text-ink-900">Tambah Materi Kegiatan</h3>
                <button type="button" @click="showModalMateri = false"
                        class="rounded-full p-1.5 text-ink/40 hover:bg-ink-900/5 hover:text-ink-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.sertifikat.materi.store') }}" class="mt-5 space-y-4">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $kegiatanId }}">

                <div>
                    <label class="block text-sm font-semibold text-ink-900">Nama Materi</label>
                    <input type="text" name="nama_materi"
                           value="{{ old('nama_materi') }}"
                           placeholder="mis. Strategi Pembelajaran Berdiferensiasi"
                           class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                    @error('nama_materi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-ink-900">Urutan</label>
                        <input type="number" name="urutan" min="1"
                               value="{{ old('urutan', $materiList->max('urutan') + 1) }}"
                               class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                        @error('urutan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-ink-900">Jumlah JP</label>
                        <input type="number" name="jumlah_jp" min="1" step="1"
                               value="{{ old('jumlah_jp') }}"
                               placeholder="mis. 4"
                               class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                        @error('jumlah_jp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-line pt-4">
                    <button type="button" @click="showModalMateri = false"
                            class="rounded-full px-4 py-2 text-sm font-medium text-ink/60 hover:bg-ink-900/5">
                        Batal
                    </button>
                    <button type="submit"
                            class="rounded-full bg-ink-900 px-5 py-2 text-sm font-semibold text-canvas transition hover:bg-ink-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- ===== /MODAL TAMBAH MATERI ===== --}}
</div>
{{-- ===================== /PANEL MATERI KEGIATAN ===================== --}}
    {{-- ===================== /MODAL EDIT ===================== --}}

    <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
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