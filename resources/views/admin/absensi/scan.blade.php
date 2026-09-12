@extends('layouts.admin')

@section('title', 'Absensi Peserta Harian')
@section('page-title', 'Absensi QR Code Per Hari')

@section('content')
    <div class="grid items-start gap-6 lg:grid-cols-3">

        {{-- ============ BAGIAN KIRI: TABEL RIWAYAT ============ --}}
        <div class="flex flex-col gap-4 lg:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gold-600">Kegiatan</span>

                    @if ($daftarKegiatan->count() > 1)
                        <select
                            id="pilih-kegiatan"
                            onchange="pilihKegiatan(this.value)"
                            class="mt-1 block w-full rounded-xl border border-line bg-white px-3 py-2 font-display text-base font-semibold text-ink-900 outline-none focus:border-ink-900 focus:ring-1 focus:ring-ink-900"
                        >
                            @foreach ($daftarKegiatan as $k)
                                <option value="{{ $k->id }}" {{ $kegiatan && $kegiatan->id === $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }}{{ $k->tanggal_selesai->lt(now()->startOfDay()) ? ' (Selesai)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <h2 class="font-display text-lg font-semibold text-ink-900">{{ $kegiatan->nama ?? 'Belum ada kegiatan' }}</h2>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    @if ($kegiatan)
                        <a
                            href="{{ route('admin.absensi.export', $kegiatan) }}"
                            class="inline-flex items-center gap-1.5 rounded-full border border-line bg-white px-3.5 py-2 text-xs font-semibold text-ink-900 transition hover:border-ink-900/30"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l-6-6m6 6l6-6" /></svg>
                            Unduh Excel
                        </a>
                    @endif
                    <span id="jumlah-hadir" class="rounded-full bg-ink-900/[0.06] px-3 py-1 text-xs font-semibold text-ink-900">0 peserta</span>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-line bg-ink-900/[0.02] text-xs uppercase tracking-wide text-ink/50">
                            <tr>
                                <th class="px-5 py-3.5 font-medium">Nama Peserta</th>
                                <th class="px-5 py-3.5 font-medium">Unit Kerja</th>
                                <th class="px-5 py-3.5 font-medium">Jam Absen</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-riwayat" class="divide-y divide-line">
                            <tr id="riwayat-kosong">
                                <td colspan="3" class="px-5 py-8 text-center text-ink/40">
                                    {{ $kegiatan ? 'Memuat data absensi...' : 'Belum ada peserta' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============ BAGIAN KANAN: KONTROL & SCANNER ============ --}}
        <div class="flex flex-col gap-5 lg:col-span-1">

            {{-- Pilihan Hari/Tanggal Dinamis --}}
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <label for="pilih-tanggal" class="mb-2 block text-xs font-medium uppercase tracking-wide text-ink/50">Tanggal Kegiatan</label>
                <select id="pilih-tanggal" class="w-full rounded-xl border border-line bg-canvas px-4 py-2.5 text-sm font-semibold text-ink-900 outline-none focus:border-ink-900 focus:ring-1 focus:ring-ink-900" {{ $kegiatan ? '' : 'disabled' }}>
                    @if(isset($jadwal_harian) && count($jadwal_harian) > 0)
                        @foreach ($jadwal_harian as $tanggal => $label)
                            <option value="{{ $tanggal }}" {{ $tanggal === ($hariIni ?? date('Y-m-d')) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    @else
                        <option value="{{ date('Y-m-d') }}">Hari Ini ({{ date('d M Y') }})</option>
                    @endif
                </select>
            </div>

            {{-- Kamera Scanner --}}
            <div class="rounded-2xl border border-line bg-white p-4 shadow-sm">
                @if (isset($statusAbsen) && $statusAbsen === 'buka')
                    <div class="overflow-hidden rounded-xl border border-line bg-black">
                        <div id="qr-reader" class="mx-auto w-full"></div>
                    </div>
                    <p id="camera-hint" class="mt-3 text-center text-xs text-ink/50">
                        Arahkan kamera ke QR peserta
                    </p>
                @elseif (isset($statusAbsen) && $statusAbsen === 'belum_mulai')
                    <div class="flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-line bg-canvas py-10 text-center">
                        <svg class="h-8 w-8 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-semibold text-ink-900">Kegiatan Belum Dimulai</p>
                        <p class="max-w-[220px] text-xs text-ink/50">
                            Absensi akan otomatis terbuka saat tanggal kegiatan sudah masuk (sesuai jam WIB).
                        </p>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-line bg-canvas py-10 text-center">
                        <svg class="h-8 w-8 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <p class="text-sm font-semibold text-ink-900">Absensi Tidak Tersedia</p>
                        <p class="max-w-[230px] text-xs text-ink/50">
                            Kegiatan ini sudah lewat atau belum terlaksana. Data kehadiran yang tercatat tetap bisa dilihat dan diunduh.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Banner Hasil --}}
            <div id="hasil-banner" class="hidden rounded-xl border-2 p-4 text-center transition">
                <p id="hasil-judul" class="font-display text-sm font-semibold"></p>
                <p id="hasil-detail" class="mt-1 text-xs"></p>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const hasilBanner   = document.getElementById('hasil-banner');
        const hasilJudul    = document.getElementById('hasil-judul');
        const hasilDetail   = document.getElementById('hasil-detail');
        const tabelRiwayat  = document.getElementById('tabel-riwayat');
        const jumlahHadir   = document.getElementById('jumlah-hadir');
        const tanggalInput  = document.getElementById('pilih-tanggal');

        let jumlah = 0;
        let sedangProses = false;

        const urlRiwayat = @json($kegiatan ? route('admin.absensi.riwayat', $kegiatan) : null);

        function pilihKegiatan(id) {
            window.location.href = '{{ route('admin.absensi.index') }}?kegiatan_id=' + id;
        }

        function tampilkanHasil(status, judul, detail) {
            const warna = {
                berhasil: ['border-success/40', 'bg-success/5', 'text-success'],
                duplikat: ['border-gold/40', 'bg-gold/5', 'text-gold-600'],
                gagal:    ['border-red-300', 'bg-red-50', 'text-red-600'],
            }[status];

            hasilBanner.className = `mt-2 rounded-xl border-2 p-4 text-center transition ${warna[0]} ${warna[1]}`;
            hasilJudul.className = `font-display text-sm font-semibold ${warna[2]}`;
            hasilJudul.textContent = judul;
            hasilDetail.textContent = detail;
            hasilBanner.classList.remove('hidden');
        }

        function baruRiwayatKosong(teks) {
            tabelRiwayat.innerHTML = `
                <tr id="riwayat-kosong">
                    <td colspan="3" class="px-5 py-8 text-center text-ink/40">${teks}</td>
                </tr>`;
            jumlah = 0;
            jumlahHadir.textContent = '0 peserta';
        }

        function tambahBarisRiwayat(nama, unit_kerja, jam, keAwal = false) {
            const kosong = document.getElementById('riwayat-kosong');
            if (kosong) kosong.remove();

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-5 py-3.5 font-medium text-ink-900">${nama}</td>
                <td class="px-5 py-3.5 text-ink/70">${unit_kerja}</td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-canvas px-2 py-1 font-mono text-xs font-semibold text-ink-900 border border-line">
                        ${jam}
                    </span>
                </td>
            `;

            if (keAwal) {
                tabelRiwayat.prepend(tr);
            } else {
                tabelRiwayat.appendChild(tr);
            }

            jumlah += 1;
            jumlahHadir.textContent = `${jumlah} peserta`;
        }

        async function muatRiwayat(tanggal) {
            if (!urlRiwayat) {
                baruRiwayatKosong('Belum ada peserta');
                return;
            }

            baruRiwayatKosong('Memuat data absensi...');

            try {
                const res = await fetch(`${urlRiwayat}?tanggal=${tanggal}`);
                const data = await res.json();

                if (!data.length) {
                    baruRiwayatKosong('Belum ada peserta');
                    return;
                }

                baruRiwayatKosong('');
                document.getElementById('riwayat-kosong')?.remove();
                data.forEach(d => tambahBarisRiwayat(d.nama, d.unit_kerja, d.jam));
            } catch (e) {
                baruRiwayatKosong('Gagal memuat data. Periksa koneksi jaringan.');
            }
        }

        async function onScanSuccess(kode) {
            if (sedangProses || !urlRiwayat) return;
            sedangProses = true;

            const tanggalTerpilih = tanggalInput.value;

            try {
                const res = await fetch('{{ route('admin.absensi.process') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        kode: kode,
                        tanggal: tanggalTerpilih
                    }),
                });

                const data = await res.json();

                if (data.status === 'berhasil') {
                    tampilkanHasil('berhasil', `✓ ${data.nama}`, `${data.unit_kerja}`);
                    tambahBarisRiwayat(data.nama, data.unit_kerja, data.jam, true);
                } else if (data.status === 'duplikat') {
                    tampilkanHasil('duplikat', `${data.nama}`, data.pesan ?? 'Sudah absen hari ini');
                } else {
                    tampilkanHasil('gagal', 'Tidak dikenali', data.pesan ?? 'Data tidak ditemukan');
                }
            } catch (e) {
                tampilkanHasil('gagal', 'Gagal memproses', 'Periksa koneksi jaringan.');
            }

            setTimeout(() => { sedangProses = false; }, 2000);
        }

        @if(isset($statusAbsen) && $statusAbsen === 'buka')
        const qr = new Html5Qrcode('qr-reader');
        qr.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 220, height: 220 } },
            (decodedText) => onScanSuccess(decodedText),
            () => {}
        );
        @endif

        tanggalInput.addEventListener('change', () => {
            hasilBanner.classList.add('hidden');
            muatRiwayat(tanggalInput.value);
        });

        muatRiwayat(tanggalInput.value);
    </script>
@endsection