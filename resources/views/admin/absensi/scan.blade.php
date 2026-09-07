@extends('layouts.admin')

@section('title', 'Absensi Peserta Harian')
@section('page-title', 'Absensi QR Code Per Hari')

@section('content')
    <div class="grid items-start gap-6 lg:grid-cols-3">
        
        {{-- ============ BAGIAN KIRI: TABEL RIWAYAT ============ --}}
        <div class="flex flex-col gap-4 lg:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gold-600">Kegiatan Aktif</span>
                    <h2 class="font-display text-lg font-semibold text-ink-900">{{ $kegiatan->nama ?? 'Nama Kegiatan' }}</h2>
                </div>
                <span id="jumlah-hadir" class="rounded-full bg-ink-900/[0.06] px-3 py-1 text-xs font-semibold text-ink-900">0 peserta</span>
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
                                <td colspan="3" class="px-5 py-8 text-center text-ink/40">Belum ada data absensi untuk hari ini.</td>
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
                <select id="pilih-tanggal" class="w-full rounded-xl border border-line bg-canvas px-4 py-2.5 text-sm font-semibold text-ink-900 outline-none focus:border-ink-900 focus:ring-1 focus:ring-ink-900">
                    @if(isset($jadwal_harian) && count($jadwal_harian) > 0)
                        @foreach ($jadwal_harian as $tanggal => $label)
                            <option value="{{ $tanggal }}">{{ $label }}</option>
                        @endforeach
                    @else
                        <option value="{{ date('Y-m-d') }}">Hari Ini ({{ date('d M Y') }})</option>
                    @endif
                </select>
            </div>

            {{-- Kamera Scanner --}}
            <div class="rounded-2xl border border-line bg-white p-4 shadow-sm">
                <div class="overflow-hidden rounded-xl border border-line bg-black">
                    <div id="qr-reader" class="mx-auto w-full"></div>
                </div>
                <p id="camera-hint" class="mt-3 text-center text-xs text-ink/50">Arahkan kamera ke QR peserta</p>
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
        const hasilBanner  = document.getElementById('hasil-banner');
        const hasilJudul   = document.getElementById('hasil-judul');
        const hasilDetail  = document.getElementById('hasil-detail');
        const tabelRiwayat = document.getElementById('tabel-riwayat');
        const riwayatKosong= document.getElementById('riwayat-kosong');
        const jumlahHadir  = document.getElementById('jumlah-hadir');
        const tanggalInput = document.getElementById('pilih-tanggal');

        let jumlah = 0;
        let sedangProses = false;

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

        function tambahRiwayat(nama, unit_kerja) {
            if (riwayatKosong) riwayatKosong.style.display = 'none';
            
            const tr = document.createElement('tr');
            const jam = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            tr.innerHTML = `
                <td class="px-5 py-3.5 font-medium text-ink-900">${nama}</td>
                <td class="px-5 py-3.5 text-ink/70">${unit_kerja}</td>
                <td class="px-5 py-3.5">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-canvas px-2 py-1 font-mono text-xs font-semibold text-ink-900 border border-line">
                        ${jam}
                    </span>
                </td>
            `;
            
            tabelRiwayat.prepend(tr);
            jumlah += 1;
            jumlahHadir.textContent = `${jumlah} peserta`;
        }

        async function onScanSuccess(kode) {
            if (sedangProses) return;
            sedangProses = true;

            const tanggalTerpilih = tanggalInput.value;

            try {
                const res = await fetch('{{ route('absensi.scan') }}', {
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
                    tambahRiwayat(data.nama, data.unit_kerja);
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

        const qr = new Html5Qrcode('qr-reader');
        qr.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 220, height: 220 } },
            (decodedText) => onScanSuccess(decodedText),
            () => {}
        );

        tanggalInput.addEventListener('change', () => {
            tabelRiwayat.innerHTML = `
                <tr id="riwayat-kosong">
                    <td colspan="3" class="px-5 py-8 text-center text-ink/40">Ganti hari. Belum ada data absensi yang discan.</td>
                </tr>
            `;
            jumlah = 0;
            jumlahHadir.textContent = '0 peserta';
            hasilBanner.classList.add('hidden');
        });
    </script>
@endsection