@extends('layouts.app')

@section('title', 'Cetak Sertifikat')

@section('content')

    <section class="relative overflow-hidden bg-[radial-gradient(ellipse_90%_60%_at_50%_-10%,rgba(15,42,67,0.07),transparent)] pb-8 pt-8 sm:pb-10 sm:pt-12 lg:pb-14 lg:pt-16">
        <div class="pointer-events-none absolute -top-24 right-[-6%] h-80 w-80 rounded-full bg-gold/20 blur-[100px]"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-64 opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_0%,#000_20%,transparent_75%)]" style="background-image: radial-gradient(circle, #0F2A43 1.4px, transparent 1.4px); background-size: 24px 24px;"></div>

        <div class="relative mx-auto max-w-3xl px-5 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-xs text-ink/50">
                <a href="/" class="hover:text-ink-900">Beranda</a>
                <span>/</span>
                <span class="font-medium text-ink-900">Cetak Sertifikat</span>
            </nav>
            <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl lg:text-4xl">Cetak Sertifikat</h1>
            <p class="mt-3 max-w-xl text-sm leading-relaxed text-ink/65 sm:text-base">
                Masukkan nomor pendaftaranmu. Sertifikat terbit otomatis setelah kegiatan selesai dan
                kehadiranmu tercatat lewat absensi QR.
            </p>

            <form action="/sertifikat" method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row">
                <input
                    type="text"
                    name="nomor_pendaftaran"
                    value="{{ request('nomor_pendaftaran') }}"
                    placeholder="Contoh: BT-2026-0142"
                    class="w-full rounded-full border border-line bg-white px-5 py-3.5 font-mono text-sm text-ink-900 outline-none transition placeholder:font-sans placeholder:text-ink/35 focus:border-ink-900 focus:ring-2 focus:ring-ink-900/10"
                >
                <button type="submit" class="w-full shrink-0 rounded-full bg-ink-900 px-7 py-3.5 text-sm font-semibold text-canvas shadow-md shadow-ink-900/20 transition hover:bg-ink-700 sm:w-auto">
                    Cari
                </button>
            </form>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-5 pb-16 sm:px-6 lg:px-8">

            @if ($dicari && $pendaftaran && $pendaftaran->status === 'sertifikat')
                {{-- ============ SERTIFIKAT SIAP ============ --}}
                <div class="rounded-2xl border-2 border-gold/40 bg-white p-5 shadow-md shadow-ink-900/[0.05] sm:p-7">
                    <div class="relative overflow-hidden rounded-xl border border-gold/30 bg-[radial-gradient(ellipse_80%_80%_at_50%_0%,rgba(201,154,61,0.08),transparent)] p-6 text-center sm:p-10">
                        <div class="pointer-events-none absolute inset-3 rounded-lg border border-dashed border-gold/40 sm:inset-4"></div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gold-600 sm:text-xs">Sertifikat Diberikan Kepada</p>
                        <p class="mt-3 font-display text-lg font-bold text-ink-900 sm:text-2xl">{{ $pendaftaran->nama_gelar }}</p>
                        <p class="mt-2 text-xs text-ink/55 sm:text-sm">atas partisipasinya dalam</p>
                        <p class="mt-1 text-sm font-semibold text-ink-900 sm:text-base">{{ $pendaftaran->kegiatan->nama }}</p>
                        {{-- [PERBAIKAN BUG] Menggunakan teks_jadwal --}}
                        <p class="mt-1 font-mono text-xs text-ink/50">{{ $pendaftaran->kegiatan->teks_jadwal }}</p>
                    </div>

                    <a
                        href="{{ route('pendaftaran.unduh', ['pendaftaran' => $pendaftaran->id, 'jenis' => 'sertifikat']) }}"
                        class="mt-5 flex w-full items-center justify-center gap-2 rounded-full bg-ink-900 px-6 py-3.5 text-sm font-semibold text-canvas shadow-md shadow-ink-900/20 transition hover:bg-ink-700"
                    >
                        Unduh Sertifikat (PDF)
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l-6-6m6 6l6-6" /></svg>
                    </a>
                </div>

            @elseif ($dicari && $pendaftaran)
                {{-- ============ KETEMU, TAPI BELUM SAATNYA ============ --}}
                @php
                    $progres = $pendaftaran->progresAbsensi();
                    $persen = $progres['wajib'] > 0 ? min(100, round(($progres['hadir'] / $progres['wajib']) * 100)) : 0;
                @endphp
                <div class="rounded-2xl border border-dashed border-line p-8 text-center sm:p-10">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gold/10">
                        <svg class="h-6 w-6 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg>
                    </div>
                    <p class="mt-4 font-display text-base font-semibold text-ink-900">Sertifikat Belum Terbit</p>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">
                        Halo {{ explode(' ', $pendaftaran->nama_gelar)[0] }}, sertifikat untuk 
                        <span class="font-medium text-ink-900">{{ $pendaftaran->kegiatan->nama }}</span> 
                        saat ini masih terkunci.
                    </p>

                    {{-- [TAMBAHAN UX] Menampilkan kotak progres kehadiran --}}
                    <div class="mx-auto mt-6 max-w-sm rounded-xl border border-line bg-canvas p-5 text-left">
                        <div class="mb-2 flex items-center justify-between text-xs">
                            <span class="font-medium text-ink/60">Status Kehadiran</span>
                            <span class="font-mono font-bold text-ink-900">{{ $progres['hadir'] }} / {{ $progres['wajib'] }} Hari</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-line/50">
                            <div class="h-full rounded-full bg-gold-500 transition-all duration-500" style="width: {{ $persen }}%;"></div>
                        </div>
                        <p class="mt-3 text-[11px] text-ink/50 text-center">
                            Pastikan kamu melakukan scan QR setiap harinya. Sertifikat otomatis terbuka setelah kehadiran mencapai 100%.
                        </p>
                    </div>

                    <a href="/cek-status?nomor_pendaftaran={{ $pendaftaran->nomor_pendaftaran }}" class="mt-6 inline-flex items-center justify-center gap-2 rounded-full border border-line bg-white px-6 py-3 text-sm font-semibold text-ink-900 transition hover:border-ink-900/30">
                        Cek Status Lengkap
                    </a>
                </div>

            @elseif ($dicari && !$pendaftaran)
                {{-- ============ TIDAK DITEMUKAN ============ --}}
                <div class="rounded-2xl border border-line p-8 text-center sm:p-10">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="mt-4 font-display text-base font-semibold text-ink-900">Nomor pendaftaran tidak ditemukan</p>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">Periksa kembali penulisan nomornya, contoh: <span class="font-mono">BT-2026-0142</span>.</p>
                </div>

            @else
                <div class="rounded-2xl border border-dashed border-line p-8 text-center sm:p-10">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-ink-900/[0.05]">
                        <svg class="h-6 w-6 text-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-ink/55">Masukkan nomor pendaftaranmu di atas untuk melihat sertifikatmu.</p>
                </div>
            @endif

        </div>
    </section>

@endsection