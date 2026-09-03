{{--
    resources/views/daftar/sukses.blade.php

    Variabel dari controller:
    $pendaftaran->nomor_pendaftaran, ->nama_gelar, ->unit_kerja, ->jabatan,
    ->kegiatan->nama, ->kegiatan->tanggal

    Route unduhan: route('pendaftaran.unduh', ['pendaftaran' => $pendaftaran->id, 'jenis' => ...])

    PERUBAHAN: kartu "Unduh Dokumen" sengaja ditaruh PALING ATAS (above the fold),
    langsung setelah nomor pendaftaran, bukan di bawah ringkasan — supaya peserta
    tidak perlu scroll untuk sampai ke aksi terpenting halaman ini. Ditambah sticky
    bar di bawah layar khusus mobile sebagai jaring pengaman kedua.
--}}
@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@section('content')

    {{-- ============ HERO SUKSES + UNDUH DOKUMEN (above the fold) ============ --}}
    <section class="relative overflow-hidden bg-[radial-gradient(ellipse_90%_60%_at_50%_-10%,rgba(15,42,67,0.08),transparent)] pb-8 pt-10 sm:pb-10 sm:pt-12 lg:pb-14 lg:pt-16">
        <div class="pointer-events-none absolute -top-24 right-[-6%] h-80 w-80 rounded-full bg-gold/20 blur-[100px]"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-64 opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_0%,#000_20%,transparent_75%)]" style="background-image: radial-gradient(circle, #0F2A43 1.4px, transparent 1.4px); background-size: 24px 24px;"></div>

        <div class="relative mx-auto max-w-3xl px-5 text-center sm:px-6 lg:px-8">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border-2 border-dashed border-gold/50 bg-white shadow-sm sm:h-16 sm:w-16">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-success/10 sm:h-11 sm:w-11">
                    <svg class="h-5 w-5 text-success sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </span>
            </div>

            <p class="mt-3 text-xs font-semibold uppercase tracking-wider text-gold-600">Pendaftaran Terkirim</p>
            <h1 class="mt-2 font-display text-lg font-bold tracking-tight text-ink-900 sm:text-xl lg:text-2xl">
                Terima kasih, {{ explode(' ', $pendaftaran->nama_gelar)[0] }}. Data kamu sudah tercatat.
            </h1>

            <div class="mx-auto mt-4 inline-flex items-center gap-3 rounded-full border border-line bg-white px-4 py-2 shadow-sm">
                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-success"></span>
                <span class="font-mono text-xs font-semibold tracking-wide text-ink-900">{{ $pendaftaran->nomor_pendaftaran }}</span>
            </div>

            {{-- ===== UNDUH DOKUMEN — ditaruh langsung di sini, ini aksi utama halaman ===== --}}
            <div class="mt-7 rounded-2xl border-2 border-gold/30 bg-white p-4 text-left shadow-md shadow-ink-900/[0.05] sm:mt-8 sm:p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-gold-600">Jangan lewatkan</p>
                <h2 class="mt-1 font-display text-sm font-semibold text-ink-900 sm:text-base">
                    Unduh Surat Tugas dan SPPD kamu sekarang
                </h2>
                <p class="mt-1 text-xs text-ink/55">
                    Dua dokumen ini wajib dibawa saat kegiatan. Simpan atau cetak sebelum meninggalkan halaman ini.
                </p>

                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    @php
                        $dokumen = [
                            [
                                'jenis' => 'surat-tugas',
                                'label' => 'Surat Tugas',
                                'utama' => true,
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
                            ],
                            [
                                'jenis' => 'sppd',
                                'label' => 'SPPD',
                                'utama' => true,
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>',
                            ],
                            [
                                'jenis' => 'bukti',
                                'label' => 'Bukti Pendaftaran',
                                'utama' => false,
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1M6 3h9l4.5 4.5V19a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"/>',
                            ],
                        ];
                    @endphp
                    @foreach ($dokumen as $d)
                        <a
                            href="{{ route('pendaftaran.unduh', ['pendaftaran' => $pendaftaran->id, 'jenis' => $d['jenis']]) }}"
                            @class([
                                'group flex items-center justify-between gap-3 rounded-xl px-4 py-3.5 text-sm font-semibold transition',
                                'bg-ink-900 text-canvas shadow-sm hover:bg-ink-700' => $d['utama'],
                                'border border-line text-ink-900 hover:border-gold/40 hover:bg-gold/[0.04]' => !$d['utama'],
                            ])
                        >
                            <span class="flex items-center gap-2.5">
                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $d['icon'] !!}</svg>
                                {{ $d['label'] }}
                            </span>
                            <svg class="h-4 w-4 shrink-0 transition group-hover:translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l-6-6m6 6l6-6" /></svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- padding bawah ekstra di mobile supaya konten tidak ketutup sticky bar --}}
    <section class="bg-white pb-24 sm:pb-0">
        <div class="mx-auto max-w-3xl px-5 pb-12 sm:px-6 sm:pb-16 lg:px-8">

            {{-- ===== RINGKASAN PESERTA ===== --}}
            <div class="rounded-2xl border border-line p-5 sm:p-7">
                <h2 class="font-display text-sm font-semibold text-ink-900">Ringkasan pendaftaran</h2>
                <dl class="mt-4 grid gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Nama dan Gelar</dt>
                        <dd class="mt-1 text-sm font-medium text-ink-900">{{ $pendaftaran->nama_gelar }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Jabatan</dt>
                        <dd class="mt-1 text-sm font-medium text-ink-900">{{ $pendaftaran->jabatan }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Unit Kerja</dt>
                        <dd class="mt-1 text-sm font-medium uppercase text-ink-900">{{ $pendaftaran->unit_kerja }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Kegiatan</dt>
                        <dd class="mt-1 text-sm font-medium text-ink-900">{{ $pendaftaran->kegiatan->nama }}</dd>
                    </div>
                    <div>
                        <div>
    <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Jadwal</dt>
    <dd class="mt-1 font-mono text-sm text-ink-900">{{ $pendaftaran->kegiatan->teks_jadwal }}</dd>
</div>
                    </div>
                </dl>
            </div>

            {{-- ===== QR KEHADIRAN ===== --}}
            <div class="mt-5 rounded-2xl border border-line p-5 text-center sm:p-7">
                <p class="font-display text-sm font-semibold text-ink-900">QR Kehadiran</p>
                <p class="mt-1 text-xs text-ink/55">Tunjukkan ini ke panitia saat check-in di lokasi kegiatan</p>

                <div class="mx-auto mt-4 flex w-fit items-center justify-center rounded-xl border border-line bg-white p-3">
                    <div id="qr-peserta"></div>
                </div>

                <p class="mt-3 font-mono text-xs font-semibold text-ink-900">{{ $pendaftaran->nomor_pendaftaran }}</p>

                <button
                    type="button"
                    id="btn-simpan-qr"
                    class="mt-4 inline-flex items-center justify-center gap-2 rounded-full border border-line px-5 py-2.5 text-xs font-semibold text-ink-900 transition hover:border-ink-900/30"
                >
                    Simpan sebagai gambar
                </button>
            </div>

            {{-- ===== INFO STATUS BERKAS ===== --}}
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-gold/25 bg-gold/[0.06] p-4">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                <p class="text-xs leading-relaxed text-ink/70">
                    Kalau kamu sudah menutup halaman ini sebelum sempat unduh, dokumen (dan QR ini) tetap bisa diambil kapan saja lewat
                    <a href="/cek-status?nomor_pendaftaran={{ $pendaftaran->nomor_pendaftaran }}" class="font-semibold text-ink-900 underline decoration-gold/60 underline-offset-2">halaman Cek Status</a>
                    dengan nomor pendaftaran <span class="font-mono font-semibold text-ink-900">{{ $pendaftaran->nomor_pendaftaran }}</span>.
                </p>
            </div>

            {{-- ===== NAVIGASI LANJUTAN ===== --}}
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="/cek-status?nomor_pendaftaran={{ $pendaftaran->nomor_pendaftaran }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-full border border-line bg-white px-6 py-3.5 text-sm font-semibold text-ink-900 transition hover:border-ink-900/30">
                    Cek Status Berkas
                </a>
                <a href="/" class="inline-flex flex-1 items-center justify-center gap-2 rounded-full border border-line bg-white px-6 py-3.5 text-sm font-semibold text-ink-900 transition hover:border-ink-900/30">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>

    {{-- ============ STICKY BAR — jaring pengaman kedua, khusus mobile ============ --}}
    <div class="fixed inset-x-0 bottom-0 z-30 border-t border-line bg-white/95 px-5 py-3 shadow-[0_-4px_16px_rgba(15,42,67,0.08)] backdrop-blur sm:hidden">
        <div class="flex items-center gap-2">
            <a
                href="{{ route('pendaftaran.unduh', ['pendaftaran' => $pendaftaran->id, 'jenis' => 'surat-tugas']) }}"
                class="flex flex-1 items-center justify-center gap-1.5 rounded-full bg-ink-900 px-4 py-3 text-xs font-semibold text-canvas shadow-sm"
            >
                Surat Tugas
            </a>
            <a
                href="{{ route('pendaftaran.unduh', ['pendaftaran' => $pendaftaran->id, 'jenis' => 'sppd']) }}"
                class="flex flex-1 items-center justify-center gap-1.5 rounded-full bg-ink-900 px-4 py-3 text-xs font-semibold text-canvas shadow-sm"
            >
                SPPD
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        const qrContainer = document.getElementById('qr-peserta');
        new QRCode(qrContainer, {
            text: '{{ $pendaftaran->token_kehadiran }}',
            width: 160,
            height: 160,
            colorDark: '#0F2A43',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M,
        });

        document.getElementById('btn-simpan-qr')?.addEventListener('click', () => {
            // qrcodejs merender <img> atau <canvas> tergantung browser
            const canvas = qrContainer.querySelector('canvas');
            const img = qrContainer.querySelector('img');
            const dataUrl = canvas ? canvas.toDataURL('image/png') : img?.src;
            if (!dataUrl) return;

            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = 'qr-{{ $pendaftaran->nomor_pendaftaran }}.png';
            link.click();
        });
    </script>

@endsection