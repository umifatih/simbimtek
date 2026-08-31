
@extends('layouts.admin')

@section('title', 'Absensi Peserta')
@section('page-title', 'Absensi QR Code')

@section('content')

    <div class="mx-auto max-w-md">

            <p class="text-sm text-ink/60">Arahkan kamera ke QR kehadiran peserta untuk mencatat absensi.</p>


            {{-- ===== KAMERA ===== --}}
            <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-black shadow-sm">
                <div id="qr-reader" class="mx-auto w-full max-w-md"></div>
            </div>

            <p id="camera-hint" class="mt-3 text-center text-xs text-ink/45">Meminta izin kamera…</p>

            {{-- ===== HASIL SCAN TERAKHIR (banner besar, muncul sesaat) ===== --}}
            <div id="hasil-banner" class="mt-5 hidden rounded-2xl border-2 p-5 text-center transition">
                <p id="hasil-judul" class="font-display text-base font-semibold"></p>
                <p id="hasil-detail" class="mt-1 text-sm"></p>
            </div>

            {{-- ===== RIWAYAT SCAN HARI INI ===== --}}
            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <p class="font-display text-sm font-semibold text-ink-900">Baru saja discan</p>
                    <span id="jumlah-hadir" class="rounded-full bg-ink-900/[0.06] px-3 py-1 text-xs font-semibold text-ink-900">0 peserta</span>
                </div>
                <div id="riwayat-list" class="mt-3 divide-y divide-line rounded-2xl border border-line bg-white">
                    <p id="riwayat-kosong" class="px-5 py-6 text-center text-sm text-ink/40">Belum ada peserta yang discan.</p>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const hasilBanner  = document.getElementById('hasil-banner');
        const hasilJudul   = document.getElementById('hasil-judul');
        const hasilDetail  = document.getElementById('hasil-detail');
        const riwayatList  = document.getElementById('riwayat-list');
        const riwayatKosong = document.getElementById('riwayat-kosong');
        const jumlahHadir  = document.getElementById('jumlah-hadir');
        const cameraHint   = document.getElementById('camera-hint');

        let jumlah = 0;
        let sedangProses = false; // cegah scan ganda beruntun untuk kode yang sama

        function tampilkanHasil(status, judul, detail) {
            const warna = {
                berhasil: ['border-success/40', 'bg-success/5', 'text-success'],
                duplikat: ['border-gold/40', 'bg-gold/5', 'text-gold-600'],
                gagal:    ['border-red-300', 'bg-red-50', 'text-red-600'],
            }[status];

            hasilBanner.className = `mt-5 rounded-2xl border-2 p-5 text-center transition ${warna[0]} ${warna[1]}`;
            hasilJudul.className = `font-display text-base font-semibold ${warna[2]}`;
            hasilJudul.textContent = judul;
            hasilDetail.textContent = detail;
            hasilBanner.classList.remove('hidden');
        }

        function tambahRiwayat(nama, keterangan) {
            riwayatKosong.classList.add('hidden');
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between gap-3 px-5 py-3.5';
            item.innerHTML = `
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-ink-900">${nama}</p>
                    <p class="text-xs text-ink/50">${keterangan}</p>
                </div>
                <span class="shrink-0 text-xs text-ink/40">${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
            `;
            riwayatList.prepend(item);
            jumlah += 1;
            jumlahHadir.textContent = `${jumlah} peserta`;
        }

        async function onScanSuccess(kode) {
            if (sedangProses) return;
            sedangProses = true;

            try {
                const res = await fetch('{{ route('absensi.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ kode }),
                });
                const data = await res.json();

                if (data.status === 'berhasil') {
                    tampilkanHasil('berhasil', `✓ ${data.nama}`, `${data.unit_kerja} — ${data.kegiatan}`);
                    tambahRiwayat(data.nama, data.unit_kerja);
                } else if (data.status === 'duplikat') {
                    tampilkanHasil('duplikat', `${data.nama}`, data.pesan ?? 'Sudah absen sebelumnya');
                } else {
                    tampilkanHasil('gagal', 'Tidak dikenali', data.pesan ?? 'Nomor pendaftaran tidak ditemukan');
                }
            } catch (e) {
                tampilkanHasil('gagal', 'Gagal memproses', 'Periksa koneksi internet, lalu coba scan ulang.');
            }

            // beri jeda sebelum bisa scan kode berikutnya
            setTimeout(() => { sedangProses = false; }, 2000);
        }

        const qr = new Html5Qrcode('qr-reader');
        qr.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 240, height: 240 } },
            (decodedText) => onScanSuccess(decodedText),
            () => {} // error per-frame diabaikan, normal terjadi terus saat kamera cari QR
        ).then(() => {
            cameraHint.textContent = 'Arahkan kamera ke QR peserta.';
        }).catch(() => {
            cameraHint.textContent = 'Kamera tidak bisa diakses. Pastikan izin kamera sudah diberikan.';
        });
    </script>

@endsection