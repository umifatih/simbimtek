@extends('layouts.app')

@section('title', 'Pendaftaran Online')

@section('content')

    {{-- ============ PAGE HEADER ============ --}}
    <section class="relative overflow-hidden bg-[radial-gradient(ellipse_90%_60%_at_50%_-10%,rgba(15,42,67,0.07),transparent)] pb-8 pt-8 sm:pb-10 sm:pt-12 lg:pb-14 lg:pt-16">
        <div class="pointer-events-none absolute -top-24 right-[-6%] h-80 w-80 rounded-full bg-gold/20 blur-[100px]"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-64 opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_0%,#000_20%,transparent_75%)]" style="background-image: radial-gradient(circle, #0F2A43 1.4px, transparent 1.4px); background-size: 24px 24px;"></div>

        <div class="relative mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-xs text-ink/50">
                <a href="/" class="hover:text-ink-900">Beranda</a>
                <span>/</span>
                <span class="font-medium text-ink-900">Pendaftaran Online</span>
            </nav>

            <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl lg:text-4xl">Formulir Pendaftaran Bimtek</h1>
            <p class="mt-3 max-w-xl text-sm leading-relaxed text-ink/65 sm:text-base">
                Lengkapi data di bawah ini sekali saja. Setelah dikirim, Surat Tugas dan SPPD langsung
                terbit otomatis dan siap diunduh, tanpa perlu unggah berkas apa pun.
            </p>
        </div>
    </section>

    {{-- ============ FORM ============ --}}
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-5 py-8 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
            <div class="grid gap-8 sm:gap-10 lg:grid-cols-12 lg:items-start">

                {{-- ===== FORM UTAMA ===== --}}
                <form action="/pendaftaran" method="POST" class="space-y-6 sm:space-y-8 lg:col-span-8">
                    @csrf

                    @if (session('status'))
                        <div class="rounded-xl border border-success/30 bg-success/10 px-5 py-4 text-sm font-medium text-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- --- Bagian 1: Pilih Kegiatan --- --}}
                    <div class="rounded-2xl border border-line p-5 sm:p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-ink-900 font-mono text-xs font-semibold text-canvas">1</span>
                            <h2 class="font-display text-base font-semibold text-ink-900">Pilih Kegiatan</h2>
                        </div>

                        <div class="mt-5">
                            <label for="kegiatan-select" class="text-sm font-semibold text-ink-900">Kegiatan Bimtek <span class="text-gold-600">*</span></label>
                            <select
                                id="kegiatan-select"
                                name="kegiatan_id"
                                required
                                class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10"
                            >
                                <option value="" data-tanggal="" data-lokasi="" data-kuota="">— Pilih salah satu kegiatan —</option>
                                @foreach ($kegiatanList as $k)
                                    <option
                                        value="{{ $k->id }}"
                                        data-tanggal="{{ $k->teks_jadwal }}"
                                        data-lokasi="{{ $k->lokasi }}"
                                        data-kuota="{{ $k->kuota_terisi }} dari {{ $k->kuota }} kuota terisi"
                                        {{ (string) old('kegiatan_id') === (string) $k->id ? 'selected' : '' }}
                                    >{{ $k->nama }}</option>
                                @endforeach
                            </select>
                            @error('kegiatan_id')
                                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- info kegiatan terpilih, tampil begitu dropdown diubah --}}
                            <div id="kegiatan-info" class="mt-4 hidden rounded-xl border border-gold/30 bg-gold/[0.06] p-4">
                                <div class="grid gap-2.5 text-sm text-ink/75 sm:grid-cols-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-12A.75.75 0 014.5 6z"/></svg>
                                        <span id="info-tanggal" class="font-mono text-xs"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                        <span id="info-lokasi"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                        <span id="info-kuota"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- --- Bagian 2: Data Diri --- --}}
                    <div class="rounded-2xl border border-line p-5 sm:p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-ink-900/30 bg-canvas font-mono text-xs font-semibold text-ink-900">2</span>
                            <h2 class="font-display text-base font-semibold text-ink-900">Data Diri Peserta</h2>
                        </div>
                        <p class="mt-2 text-xs text-ink/45">
                            Sudah pernah daftar sebelumnya? Ketik NIP atau nama kamu di kolom yang sesuai — data akan muncul untuk dipilih dan langsung terisi otomatis.
                        </p>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2 sm:gap-5">

                            {{-- ===== Unit Kerja: combobox pencarian dari Data Master ===== --}}
                            <div class="relative sm:col-span-2" data-combobox="unit_kerja">
                                <label for="unit_kerja" class="text-sm font-semibold text-ink-900">Unit Kerja <span class="text-gold-600">*</span></label>
                                <p class="mt-0.5 text-xs text-ink/45">Ketik nama sekolah, lalu pilih dari daftar master. Kepala Sekolah &amp; NIP KS akan terisi otomatis.</p>
                                <input type="text" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}" required autocomplete="off" placeholder="Contoh: SD NEGERI 1 MANDIRAJA KULON" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm uppercase text-ink-900 outline-none transition placeholder:text-ink/35 placeholder:normal-case focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                <div class="kotak-saran absolute z-20 mt-1 hidden w-full overflow-hidden rounded-xl border border-line bg-white shadow-lg"></div>
                                @error('unit_kerja') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="nama_gelar" class="text-sm font-semibold text-ink-900">Nama dan Gelar <span class="text-gold-600">*</span></label>
                                <div class="relative" data-combobox="peserta-nama">
                                    <input type="text" id="nama_gelar" name="nama_gelar" value="{{ old('nama_gelar') }}" required autocomplete="off" placeholder="Contoh: Siti Aminah, S.Pd." class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                    <div class="kotak-saran absolute z-20 mt-1 hidden w-full overflow-hidden rounded-xl border border-line bg-white shadow-lg"></div>
                                </div>
                                @error('nama_gelar') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="nip" class="text-sm font-semibold text-ink-900">NIP <span class="text-gold-600">*</span></label>
                                <div class="relative" data-combobox="peserta-nip">
                                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" required inputmode="numeric" autocomplete="off" placeholder="18 digit NIP" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 font-mono text-sm text-ink-900 outline-none transition placeholder:font-sans placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                    <div class="kotak-saran absolute z-20 mt-1 hidden w-full overflow-hidden rounded-xl border border-line bg-white shadow-lg"></div>
                                </div>
                                <p id="nip-status" class="mt-1.5 hidden text-xs font-medium"></p>
                                @error('nip') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="pangkat_golongan" class="text-sm font-semibold text-ink-900">Pangkat / Golongan <span class="text-gold-600">*</span></label>
                                <input type="text" id="pangkat_golongan" name="pangkat_golongan" value="{{ old('pangkat_golongan') }}" required placeholder="Contoh: Penata Muda, III/a" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                @error('pangkat_golongan') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tempat_lahir" class="text-sm font-semibold text-ink-900">Tempat Lahir <span class="text-gold-600">*</span></label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Contoh: Purbalingga" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                @error('tempat_lahir') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="text-sm font-semibold text-ink-900">Tanggal Lahir <span class="text-gold-600">*</span></label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                @error('tanggal_lahir') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="jabatan" class="text-sm font-semibold text-ink-900">Jabatan <span class="text-gold-600">*</span></label>
                                <select id="jabatan" name="jabatan" required class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                    <option value="" {{ old('jabatan') ? '' : 'selected' }}>— Pilih jabatan —</option>
                                    <option value="Bendahara BOSP" {{ old('jabatan') === 'Bendahara BOSP' ? 'selected' : '' }}>Bendahara BOSP</option>
                                    <option value="Operator BOSP" {{ old('jabatan') === 'Operator BOSP' ? 'selected' : '' }}>Operator BOSP</option>
                                </select>
                                @error('jabatan') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="text-sm font-semibold text-ink-900">Email <span class="text-gold-600">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                @error('email') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- --- Sub bagian: Data Kepala Sekolah --- --}}
                        <div class="mt-6 border-t border-line pt-6">
                            <p class="text-xs font-semibold uppercase tracking-wider text-ink/40">Data Kepala Sekolah</p>
                            <p class="mt-1 text-xs text-ink/45">Terisi otomatis begitu Unit Kerja dipilih dari daftar master. Boleh diedit manual bila perlu.</p>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2 sm:gap-5">
                                <div class="sm:col-span-2">
                                    <label for="nama_gelar_kepsek" class="text-sm font-semibold text-ink-900">Nama dan Gelar Kepala Sekolah <span class="text-gold-600">*</span></label>
                                    <input type="text" id="nama_gelar_kepsek" name="nama_gelar_kepsek" value="{{ old('nama_gelar_kepsek') }}" required placeholder="Contoh: Budi Santoso, S.Pd., M.Pd." class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 text-sm text-ink-900 outline-none transition placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                    @error('nama_gelar_kepsek') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="nip_kepsek" class="text-sm font-semibold text-ink-900">NIP Kepala Sekolah <span class="text-ink/35">(opsional)</span></label>
                                    <input type="text" id="nip_kepsek" name="nip_kepsek" value="{{ old('nip_kepsek') }}" inputmode="numeric" placeholder="18 digit NIP, kosongkan jika non-ASN" class="mt-2 w-full rounded-xl border border-line bg-white px-4 py-3 font-mono text-sm text-ink-900 outline-none transition placeholder:font-sans placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10">
                                    @error('nip_kepsek') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- --- Persetujuan & Submit --- --}}
                    <div class="rounded-2xl bg-canvas p-5 sm:p-7">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="setuju" required class="mt-0.5 h-4 w-4 shrink-0 rounded border-line text-ink-900 focus:ring-ink-900/20 cursor-pointer">
                            <span class="text-sm text-ink/70">
                                Saya menyatakan data yang saya isi sudah benar dan bersedia mengikuti seluruh rangkaian kegiatan sesuai jadwal.
                            </span>
                        </label>
                        @error('setuju') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                            <p class="text-center text-xs text-ink/45 sm:text-left">Bukti pendaftaran, Surat Tugas, dan SPPD otomatis siap diunduh setelah dikirim.</p>
                            <button type="submit" class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-full bg-ink-900 px-8 py-3.5 text-sm font-semibold text-canvas shadow-md shadow-ink-900/20 transition hover:bg-ink-700 hover:shadow-lg sm:w-auto">
                                Kirim Pendaftaran
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- ===== SIDEBAR ===== --}}
                <aside class="space-y-5 sm:space-y-6 lg:sticky lg:top-24 lg:col-span-4">
                    <div class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
                        <p class="font-display text-sm font-semibold text-ink-900">Alur berkas peserta</p>
                        <ol class="mt-5 space-y-0">
                            @php
                                $tahapan = [
                                    ['label' => 'Daftar Online', 'desc' => 'Sedang kamu isi sekarang'],
                                    ['label' => 'Terbit SPPD', 'desc' => 'Surat tugas siap dicetak'],
                                    ['label' => 'Sertifikat', 'desc' => 'Diunduh usai kegiatan'],
                                ];
                            @endphp
                            @foreach ($tahapan as $i => $t)
                                <li class="relative flex gap-4 pb-6 last:pb-0 sm:pb-7">
                                    @if (!$loop->last)
                                        <span class="absolute left-[15px] top-8 h-full w-px border-l-2 border-dashed border-line"></span>
                                    @endif
                                    <span @class([
                                        'relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-mono text-xs font-semibold',
                                        'bg-ink-900 text-canvas' => $loop->first,
                                        'border-2 border-ink-900/30 bg-canvas text-ink-900' => !$loop->first,
                                    ])>
                                        {{ $i + 1 }}
                                    </span>
                                    <div class="pt-0.5">
                                        <p class="text-sm font-semibold text-ink-900">{{ $t['label'] }}</p>
                                        <p class="text-xs text-ink/55">{{ $t['desc'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="rounded-2xl border border-gold/25 bg-gold/[0.05] p-5 sm:p-6">
                        <p class="font-display text-sm font-semibold text-ink-900">Butuh bantuan?</p>
                        <p class="mt-2 text-sm leading-relaxed text-ink/65">
                            Kalau ada kendala saat mengisi formulir, hubungi panitia bimtek pada jam kerja.
                        </p>
                        <p class="mt-3 font-mono text-sm font-semibold text-ink-900">0281-XXX-XXX</p>
                    </div>
                </aside>

            </div>
        </div>
    </section>

    <script>
        const kegiatanSelect = document.getElementById('kegiatan-select');
        const kegiatanInfo = document.getElementById('kegiatan-info');
        const infoTanggal = document.getElementById('info-tanggal');
        const infoLokasi = document.getElementById('info-lokasi');
        const infoKuota = document.getElementById('info-kuota');

        function syncKegiatanInfo() {
            const opt = kegiatanSelect.selectedOptions[0];
            if (!opt || !opt.value) {
                kegiatanInfo.classList.add('hidden');
                return;
            }
            infoTanggal.textContent = opt.dataset.tanggal;
            infoLokasi.textContent = opt.dataset.lokasi;
            infoKuota.textContent = opt.dataset.kuota;
            kegiatanInfo.classList.remove('hidden');
        }

        kegiatanSelect?.addEventListener('change', syncKegiatanInfo);
        if (kegiatanSelect?.value) syncKegiatanInfo();

        // Unit Kerja: paksa huruf kapital pada nilai yang benar-benar dikirim
        const unitKerjaInput = document.getElementById('unit_kerja');
        unitKerjaInput?.addEventListener('input', (e) => {
            const pos = e.target.selectionStart;
            e.target.value = e.target.value.toUpperCase();
            e.target.setSelectionRange(pos, pos);
        });

        // =====================================================================
        // KOMPONEN COMBOBOX PENCARIAN (dipakai untuk 3 kolom: unit_kerja, nama, nip)
        // =====================================================================
        const nipStatus = document.getElementById('nip-status');

        function tampilkanStatusNip(teks, warna) {
            nipStatus.textContent = teks;
            nipStatus.className = `mt-1.5 text-xs font-medium ${warna}`;
            nipStatus.classList.remove('hidden');
        }

        // Field yang ikut terisi begitu peserta lama ditemukan (lewat NIP maupun Nama)
        const fieldPeserta = {
            unit_kerja: document.getElementById('unit_kerja'),
            nama_gelar: document.getElementById('nama_gelar'),
            nip: document.getElementById('nip'),
            pangkat_golongan: document.getElementById('pangkat_golongan'),
            tempat_lahir: document.getElementById('tempat_lahir'),
            tanggal_lahir: document.getElementById('tanggal_lahir'),
            jabatan: document.getElementById('jabatan'),
            email: document.getElementById('email'),
            nama_gelar_kepsek: document.getElementById('nama_gelar_kepsek'),
            nip_kepsek: document.getElementById('nip_kepsek'),
        };

        async function isiDataPesertaDariNip(nip) {
            try {
                const res = await fetch(`/pendaftaran/cari-nip/${encodeURIComponent(nip)}`);
                const hasil = await res.json();

                if (!hasil.ditemukan) {
                    tampilkanStatusNip('NIP baru — silakan lengkapi data di bawah.', 'text-ink/45');
                    return;
                }

                Object.entries(fieldPeserta).forEach(([key, el]) => {
                    if (el && hasil.data[key] != null && hasil.data[key] !== '') {
                        el.value = hasil.data[key];
                    }
                });
                // pastikan Unit Kerja tetap huruf kapital sesuai aturan form
                if (fieldPeserta.unit_kerja) fieldPeserta.unit_kerja.value = fieldPeserta.unit_kerja.value.toUpperCase();

                tampilkanStatusNip('✓ Data ditemukan dari pendaftaran sebelumnya, otomatis diisi. Silakan periksa kembali.', 'text-success');
            } catch (e) {
                // gagal ambil data, biarkan peserta isi manual
            }
        }

        function isiDataSekolah(data) {
            const kepsekNama = document.getElementById('nama_gelar_kepsek');
            const kepsekNip = document.getElementById('nip_kepsek');
            if (kepsekNama) kepsekNama.value = data.nama_kepsek ?? '';
            if (kepsekNip) kepsekNip.value = data.nip_kepsek ?? '';
        }

        /**
         * Membuat combobox: mengetik di `input` -> panggil `urlPencarian?q=...` (debounce),
         * tampilkan daftar saran di `.kotak-saran`, klik saran -> jalankan `onPilih(item)`.
         * `renderBaris(item)` mengembalikan HTML satu baris saran.
         */
        function pasangCombobox({ input, kotakSaran, urlPencarian, minKarakter = 2, renderBaris, onPilih }) {
            if (!input || !kotakSaran) return;
            let timer = null;

            function sembunyikan() {
                kotakSaran.classList.add('hidden');
                kotakSaran.innerHTML = '';
            }

            input.addEventListener('input', () => {
                clearTimeout(timer);
                nipStatus?.classList.add('hidden');
                const q = input.value.trim();

                if (q.length < minKarakter) {
                    sembunyikan();
                    return;
                }

                timer = setTimeout(async () => {
                    try {
                        const res = await fetch(`${urlPencarian}?q=${encodeURIComponent(q)}`);
                        const hasil = await res.json();
                        const daftar = hasil.data || [];

                        if (daftar.length === 0) {
                            sembunyikan();
                            return;
                        }

                        kotakSaran.innerHTML = daftar.map((item, i) => `
                            <button type="button" data-idx="${i}" class="block w-full border-b border-line/60 px-4 py-2.5 text-left text-sm last:border-b-0 hover:bg-canvas">
                                ${renderBaris(item)}
                            </button>
                        `).join('');

                        kotakSaran.querySelectorAll('button[data-idx]').forEach(btn => {
                            btn.addEventListener('click', () => {
                                const item = daftar[Number(btn.dataset.idx)];
                                onPilih(item);
                                sembunyikan();
                            });
                        });

                        kotakSaran.classList.remove('hidden');
                    } catch (e) {
                        sembunyikan();
                    }
                }, 350);
            });

            document.addEventListener('click', (e) => {
                if (!input.parentElement.contains(e.target)) sembunyikan();
            });
        }

        // --- Combobox 1: Unit Kerja, dari Data Master sekolah ---
        pasangCombobox({
            input: document.getElementById('unit_kerja'),
            kotakSaran: document.querySelector('[data-combobox="unit_kerja"] .kotak-saran'),
            urlPencarian: '/pendaftaran/cari-sekolah',
            renderBaris: (item) => `
                <span class="font-medium text-ink-900">${item.unit_kerja}</span>
                <span class="block text-xs text-ink/45">Kepsek: ${item.nama_kepsek ?? '-'}</span>
            `,
            onPilih: (item) => {
                const el = document.getElementById('unit_kerja');
                el.value = item.unit_kerja.toUpperCase();
                isiDataSekolah(item);
            },
        });

        // --- Combobox 2: Nama peserta (cari peserta lama berdasarkan nama) ---
        pasangCombobox({
            input: document.getElementById('nama_gelar'),
            kotakSaran: document.querySelector('[data-combobox="peserta-nama"] .kotak-saran'),
            urlPencarian: '/pendaftaran/cari-peserta',
            minKarakter: 3,
            renderBaris: (item) => `
                <span class="font-medium text-ink-900">${item.nama_gelar}</span>
                <span class="block text-xs text-ink/45">${item.unit_kerja ?? '-'} · NIP ${item.nip}</span>
            `,
            onPilih: (item) => isiDataPesertaDariNip(item.nip),
        });

        // --- Combobox 3: NIP peserta (cari peserta lama berdasarkan NIP) ---
        pasangCombobox({
            input: document.getElementById('nip'),
            kotakSaran: document.querySelector('[data-combobox="peserta-nip"] .kotak-saran'),
            urlPencarian: '/pendaftaran/cari-peserta',
            minKarakter: 3,
            renderBaris: (item) => `
                <span class="font-mono font-medium text-ink-900">${item.nip}</span>
                <span class="block text-xs text-ink/45">${item.nama_gelar} · ${item.unit_kerja ?? '-'}</span>
            `,
            onPilih: (item) => {
                document.getElementById('nip').value = item.nip;
                isiDataPesertaDariNip(item.nip);
            },
        });

        // Ketik NIP lengkap (>=8 digit) tanpa memilih saran -> tetap coba autofill langsung
        const nipInput = document.getElementById('nip');
        let timerNipLangsung = null;
        nipInput?.addEventListener('input', () => {
            clearTimeout(timerNipLangsung);
            const nip = nipInput.value.trim();
            if (nip.length < 8) return;
            timerNipLangsung = setTimeout(() => isiDataPesertaDariNip(nip), 600);
        });
    </script>

@endsection