{{--
    resources/views/peserta/status.blade.php
--}}
@extends('layouts.app')

@section('title', session('success') ? 'Pendaftaran Berhasil' : 'Cek Status Pendaftaran')

@section('content')

    {{-- ============ PAGE HEADER ============ --}}
    <section class="relative overflow-hidden bg-[radial-gradient(ellipse_90%_60%_at_50%_-10%,rgba(15,42,67,0.07),transparent)] pb-8 pt-8 sm:pb-10 sm:pt-12 lg:pb-14 lg:pt-16">
        <div class="pointer-events-none absolute -top-24 right-[-6%] h-80 w-80 rounded-full bg-gold/20 blur-[100px]"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-64 opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_0%,#000_20%,transparent_75%)]" style="background-image: radial-gradient(circle, #0F2A43 1.4px, transparent 1.4px); background-size: 24px 24px;"></div>

        <div class="relative mx-auto max-w-3xl px-5 text-center sm:px-6 lg:px-8">
            
            @if (session('success') && $pendaftaran)
                {{-- TAMPILAN HERO KETIKA BARU SAJA SUKSES MENDAFTAR --}}
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
                <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-ink/65">
                    Bukti pendaftaran kamu akan terunduh otomatis. Simpan nomor pendaftaran di bawah ini untuk mengecek status SPPD dan Sertifikat nanti.
                </p>
            @else
                {{-- TAMPILAN PENCARIAN DEFAULT --}}
                <nav class="flex items-center justify-center gap-2 text-xs text-ink/50 sm:justify-start">
                    <a href="/" class="hover:text-ink-900">Beranda</a>
                    <span>/</span>
                    <span class="font-medium text-ink-900">Cek Status</span>
                </nav>

                <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-left sm:text-3xl lg:text-4xl">Cek Status Pendaftaran</h1>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-ink/65 sm:text-left sm:text-base">
                    Masukkan nomor pendaftaran yang kamu dapat setelah mendaftar dan mengunduh dokumen.
                </p>

                <form action="/cek-status" method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <input
                        type="text"
                        name="nomor_pendaftaran"
                        value="{{ request('nomor_pendaftaran') }}"
                        placeholder="Contoh: BT-2026-0142"
                        class="w-full rounded-full border border-line bg-white px-5 py-3.5 font-mono text-sm text-ink-900 outline-none transition placeholder:font-sans placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10"
                    >
                    <button type="submit" class="w-full shrink-0 rounded-full bg-ink-900 px-7 py-3.5 text-sm font-semibold text-canvas shadow-md shadow-ink-900/20 transition hover:bg-ink-700 sm:w-auto">
                        Cek Status
                    </button>
                </form>
            @endif

        </div>
    </section>

    {{-- ============ MAIN CONTENT ============ --}}
    <section class="bg-white pb-24 sm:pb-0">
        <div class="mx-auto max-w-3xl px-5 pb-16 sm:px-6 lg:px-8">

            @if ($pendaftaran)
                {{-- ============ DATA DITEMUKAN / BERHASIL DAFTAR ============ --}}
                @php
                    $tahapan = [
                        ['key' => 'daftar', 'label' => 'Daftar Online', 'desc' => 'Data pendaftaran diterima'],
                        ['key' => 'sppd', 'label' => 'Surat Tugas & SPPD', 'desc' => 'Sudah bisa diunduh'],
                        ['key' => 'sertifikat', 'label' => 'Sertifikat', 'desc' => 'Terbit usai kegiatan selesai'],
                    ];
                    $urutanStatus = array_column($tahapan, 'key');
                    $stepAktif = array_search($pendaftaran->status, $urutanStatus);
                    $stepAktif = $stepAktif === false ? 0 : $stepAktif;
                    $sertifikatSiap = $pendaftaran->status === 'sertifikat';
                    $progres = $pendaftaran->progresAbsensi();
                @endphp

                <div class="mt-6 rounded-2xl border border-line p-5 sm:mt-0 sm:p-7">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-ink/40">Nomor Pendaftaran</p>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-success"></span>
                                <p class="font-mono text-sm font-semibold text-ink-900">{{ $pendaftaran->nomor_pendaftaran }}</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-success/10 px-3 py-1 text-xs font-semibold text-success">
                            {{ $tahapan[$stepAktif]['label'] }}
                        </span>
                    </div>

                    <dl class="mt-5 grid gap-x-6 gap-y-4 border-t border-line pt-5 sm:grid-cols-2">
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
                            <dt class="text-xs font-medium uppercase tracking-wide text-ink/40">Jadwal</dt>
                            <dd class="mt-1 font-mono text-sm text-ink-900">{{ $pendaftaran->kegiatan->teks_jadwal }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- ===== QR KEHADIRAN ===== --}}
                <div class="mt-5 rounded-2xl border border-line p-5 text-center sm:p-7">
                    <p class="font-display text-sm font-semibold text-ink-900">QR Kehadiran</p>
                    <p class="mt-1 text-xs text-ink/55">Tunjukkan ini ke panitia untuk check-in dan absensi setiap hari kegiatan</p>

                    <div class="mx-auto mt-4 flex w-fit items-center justify-center rounded-xl border border-line bg-white p-3">
                        <div id="qr-peserta"></div>
                    </div>

                    <button
                        type="button"
                        id="btn-simpan-qr"
                        class="mt-4 inline-flex items-center justify-center gap-2 rounded-full border border-line px-5 py-2.5 text-xs font-semibold text-ink-900 transition hover:border-ink-900/30"
                    >
                        Simpan sebagai gambar
                    </button>
                </div>

                {{-- ===== STEPPER ALUR BERKAS ===== --}}
                <div class="mt-5 rounded-2xl border border-line p-5 sm:p-7">
                    <p class="font-display text-sm font-semibold text-ink-900">Progres berkas</p>
                    <ol class="mt-5 space-y-0">
                        @foreach ($tahapan as $i => $t)
                            <li class="relative flex gap-4 pb-6 last:pb-0 sm:pb-7">
                                @if (!$loop->last)
                                    <span @class([
                                        'absolute left-[15px] top-8 h-full w-px border-l-2',
                                        'border-success/50' => $i < $stepAktif,
                                        'border-dashed border-line' => $i >= $stepAktif,
                                    ])></span>
                                @endif
                                <span @class([
                                    'relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-mono text-xs font-semibold',
                                    'bg-success text-white' => $i < $stepAktif,
                                    'bg-ink-900 text-canvas' => $i === $stepAktif,
                                    'border-2 border-ink-900/20 bg-canvas text-ink/40' => $i > $stepAktif,
                                ])>
                                    @if ($i < $stepAktif)
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </span>
                                <div class="pt-0.5 w-full">
                                    <p @class([
                                        'text-sm font-semibold',
                                        'text-ink-900' => $i <= $stepAktif,
                                        'text-ink/40' => $i > $stepAktif,
                                    ])>{{ $t['label'] }}</p>
                                    <p class="text-xs text-ink/55">{{ $t['desc'] }}</p>

                                    @if ($t['key'] === 'sertifikat' && $i <= $stepAktif + 1 && $progres['wajib'] > 0)
                                        <div class="mt-3 max-w-xs">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="font-medium text-ink/60">Absensi kehadiran</span>
                                                <span class="font-mono font-semibold text-ink-900">{{ $progres['hadir'] }} / {{ $progres['wajib'] }} hari</span>
                                            </div>
                                            <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-canvas">
                                                <div class="h-full rounded-full transition-all" style="width: {{ min(100, round(($progres['hadir'] / $progres['wajib']) * 100)) }}%; background-color: {{ $sertifikatSiap ? '#16a34a' : '#C99A3D' }};"></div>
                                            </div>
                                            @unless ($sertifikatSiap)
                                                <p class="mt-1.5 text-[11px] text-ink/45">
                                                    Absen lewat QR Kehadiran di setiap hari kegiatan sampai lengkap, sertifikat baru bisa diunduh.
                                                </p>
                                            @endunless
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                {{-- ===== UNDUH DOKUMEN ===== --}}
                <div class="mt-7 rounded-2xl border-2 border-gold/30 bg-white p-5 shadow-md shadow-ink-900/[0.05] sm:mt-8">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gold-600">Pusat Unduhan</p>
                    <h2 class="mt-1 font-display text-sm font-semibold text-ink-900">Dokumen Pendaftaran & Kegiatan</h2>
                    
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @php
                            $dokumen = [
                                ['jenis' => 'bukti', 'label' => 'Bukti Pendaftaran', 'siap' => true],
                                ['jenis' => 'surat-tugas', 'label' => 'Surat Tugas', 'siap' => true],
                                ['jenis' => 'sppd', 'label' => 'SPPD', 'siap' => true],
                                ['jenis' => 'sertifikat', 'label' => 'Sertifikat', 'siap' => $sertifikatSiap],
                            ];
                        @endphp
                        @foreach ($dokumen as $d)
                            @if ($d['siap'])
                                <a href="{{ route('pendaftaran.unduh', ['pendaftaran' => $pendaftaran->id, 'jenis' => $d['jenis']]) }}"
                                   class="group flex items-center justify-between gap-3 rounded-xl border border-line bg-white px-4 py-3.5 text-sm font-semibold text-ink-900 transition hover:border-gold/40 hover:bg-gold/[0.04] focus:ring-2 focus:ring-gold"
                                >
                                    {{ $d['label'] }}
                                    <svg class="h-4 w-4 shrink-0 text-ink/40 transition group-hover:translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l-6-6m6 6l6-6" /></svg>
                                </a>
                            @else
                                <div class="flex items-center justify-between gap-3 rounded-xl border border-dashed border-line bg-canvas px-4 py-3.5 text-sm font-medium text-ink/40">
                                    {{ $d['label'] }}
                                    <span class="text-xs">Belum tersedia</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                @if (session('success'))
                    <div class="mt-6 text-center">
                        <a href="/" class="inline-flex items-center justify-center gap-2 rounded-full border border-line bg-white px-6 py-3.5 text-sm font-semibold text-ink-900 transition hover:border-ink-900/30">
                            Kembali ke Beranda
                        </a>
                    </div>
                @endif

            @elseif (request('nomor_pendaftaran'))
                {{-- ============ TIDAK DITEMUKAN ============ --}}
                <div class="mt-6 rounded-2xl border border-line p-8 text-center sm:p-10">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="mt-4 font-display text-base font-semibold text-ink-900">Nomor pendaftaran tidak ditemukan</p>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">
                        Periksa kembali penulisan nomornya (contoh: <span class="font-mono">BT-2026-0142</span>),
                        atau kemungkinan kamu belum pernah mendaftar.
                    </p>
                    <a href="/pendaftaran" class="mt-5 inline-flex items-center justify-center gap-2 rounded-full bg-ink-900 px-6 py-3 text-sm font-semibold text-canvas transition hover:bg-ink-700">
                        Daftar Sekarang
                    </a>
                </div>

            @else
                {{-- ============ BELUM ADA PENCARIAN ============ --}}
                <div class="mt-6 rounded-2xl border border-dashed border-line p-8 text-center sm:p-10">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-ink-900/[0.05]">
                        <svg class="h-6 w-6 text-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-ink/55">
                        Masukkan nomor pendaftaranmu di kolom pencarian di atas untuk melihat status berkas.
                    </p>
                </div>
            @endif

        </div>
    </section>

    @if ($pendaftaran)
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
                const canvas = qrContainer.querySelector('canvas');
                const img = qrContainer.querySelector('img');
                const dataUrl = canvas ? canvas.toDataURL('image/png') : img?.src;
                if (!dataUrl) return;

                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = 'qr-{{ $pendaftaran->nomor_pendaftaran }}.png';
                link.click();
            });

            // Otomatis unduh bukti hanya jika baru saja daftar (session success)
            @if (session('success'))
                window.addEventListener('DOMContentLoaded', () => {
                    const linkBukti = document.createElement('a');
                    linkBukti.href = "{{ route('pendaftaran.unduh', ['pendaftaran' => $pendaftaran->id, 'jenis' => 'bukti']) }}";
                    document.body.appendChild(linkBukti);
                    linkBukti.click();
                    linkBukti.remove();
                });
            @endif
        </script>
    @endif

@endsection