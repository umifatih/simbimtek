{{-- resources/views/cara-kerja.blade.php --}}
@extends('layouts.app')

@section('title', 'Cara Kerja')

@section('content')

    {{-- ============ HEADER ============ --}}
<section class="relative overflow-hidden bg-[radial-gradient(ellipse_90%_60%_at_50%_-10%,rgba(15,42,67,0.07),transparent)] pb-10 pt-8 sm:pb-14 sm:pt-12 lg:pb-16 lg:pt-16">
    <div class="pointer-events-none absolute -top-24 right-[-6%] h-80 w-80 rounded-full bg-gold/20 blur-[100px]"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-64 opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_0%,#000_20%,transparent_75%)]" style="background-image: radial-gradient(circle, #0F2A43 1.4px, transparent 1.4px); background-size: 24px 24px;"></div>
    <div class="pointer-events-none absolute left-0 top-0 h-full w-1.5 bg-gradient-to-b from-gold via-ink-900 to-gold/60"></div>

    <div class="relative mx-auto max-w-3xl px-5 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-1.5 text-xs text-ink/45">
            <a href="/" class="font-medium hover:text-ink-900">Beranda</a>
            <svg class="h-3 w-3 text-ink/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            <span class="font-semibold text-ink-900">Cara Kerja</span>
        </nav>

        <span class="mt-6 inline-flex items-center gap-2 rounded-full border border-gold/40 bg-white px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-ink-700 shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-gold"></span>
            Panduan Peserta
        </span>

        <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl lg:text-4xl">Dari daftar sampai sertifikat</h1>
        <p class="mt-3 max-w-xl text-sm leading-relaxed text-ink/65 sm:text-base">
            Begini alur lengkapnya, tahap demi tahap — biar kamu tahu persis apa yang perlu dilakukan
            dan kapan dokumenmu siap.
        </p>
    </div>
</section>

    <section class="bg-white">
        <div class="mx-auto max-w-3xl px-5 py-10 sm:px-6 sm:py-14 lg:px-8">

            {{-- ============ 4 FASE ============ --}}
            <div class="space-y-5">
                @php
                    $fase = [
                        [
                            'no' => '01',
                            'judul' => 'Daftar dengan NIP',
                            'durasi' => '± 3 menit',
                            'desc' => 'Masukkan NIP dulu — kalau kamu pernah daftar kegiatan sebelumnya, data diri (Unit Kerja, Nama & Gelar, Jabatan, data Kepala Sekolah) otomatis terisi. Kalau NIP baru, isi sekali saja. Tidak perlu unggah berkas apa pun.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1M6 3h9l4.5 4.5V19a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"/>',
                        ],
                        [
                            'no' => '02',
                            'judul' => 'Surat Tugas & SPPD langsung terbit',
                            'durasi' => 'Instan',
                            'desc' => 'Begitu formulir dikirim, Surat Tugas dan SPPD otomatis dibuat dari data yang kamu isi dan langsung bisa diunduh — tanpa menunggu proses apa pun.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
                        ],
                        [
                            'no' => '03',
                            'judul' => 'Hadir & absen lewat QR Code',
                            'durasi' => 'Hari-H kegiatan',
                            'desc' => 'Tunjukkan QR kehadiranmu (ada di halaman Cek Status) ke panitia saat check-in di lokasi. Kehadiran tercatat otomatis begitu dipindai.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5A.75.75 0 014.5 3.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5zM3.75 15a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75V15zM14.25 4.5a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5zM14.25 15.75h2.25v2.25h-2.25v-2.25zM17.25 18.75h2.25V21h-2.25v-2.25zM14.25 18.75h.008v.008h-.008v-.008zM17.25 15.75h.008v.008h-.008v-.008zM19.5 15.75h.008v.008h-.008v-.008zM19.5 18.75h.008v.008h-.008v-.008z"/>',
                        ],
                        [
                            'no' => '04',
                            'judul' => 'Unduh sertifikat',
                            'durasi' => 'Usai kegiatan selesai',
                            'desc' => 'Sertifikat digital terbit otomatis setelah rangkaian kegiatan selesai, dan bisa diunduh dari halaman Cek Status atau Cetak Sertifikat.',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        ],
                    ];
                @endphp
                @foreach ($fase as $f)
                    <div class="flex gap-4 rounded-2xl border border-line p-5 sm:gap-5 sm:p-6">
                        <div class="flex flex-col items-center">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-ink-900/[0.06] text-ink-900">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $f['icon'] !!}</svg>
                            </span>
                            @if (!$loop->last)
                                <span class="mt-2 hidden w-px flex-1 border-l-2 border-dashed border-line sm:block"></span>
                            @endif
                        </div>
                        <div class="min-w-0 pb-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-xs font-bold text-gold-600">{{ $f['no'] }}</span>
                                <h2 class="font-display text-base font-semibold text-ink-900">{{ $f['judul'] }}</h2>
                            </div>
                            <span class="mt-1 inline-block rounded-full bg-canvas px-2.5 py-0.5 text-[11px] font-medium text-ink/50">{{ $f['durasi'] }}</span>
                            <p class="mt-2 text-sm leading-relaxed text-ink/65">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ============ FAQ (native <details>, tanpa JS) ============ --}}
            <div class="mt-12">
                <p class="text-xs font-semibold uppercase tracking-wider text-gold-600">Pertanyaan Umum</p>
                <h2 class="mt-2 font-display text-xl font-bold tracking-tight text-ink-900 sm:text-2xl">Yang sering ditanyakan</h2>

                <div class="mt-6 divide-y divide-line rounded-2xl border border-line">
                    @php
                        $faq = [
                            [
                                'q' => 'Apakah saya perlu unggah KTP atau pas foto saat daftar?',
                                'a' => 'Tidak. Formulir pendaftaran cukup diisi datanya saja — Surat Tugas dan SPPD dibuat otomatis dari data tersebut.',
                            ],
                            [
                                'q' => 'Saya sudah pernah daftar kegiatan lain, apa perlu isi ulang semua data?',
                                'a' => 'Tidak perlu. Masukkan NIP yang sama, dan sistem otomatis mengisi data dirimu dari pendaftaran sebelumnya. Kamu tinggal pilih kegiatan yang baru.',
                            ],
                            [
                                'q' => 'Berapa lama Surat Tugas dan SPPD siap diunduh?',
                                'a' => 'Langsung, begitu formulir pendaftaran dikirim. Tidak ada proses menunggu di tengah.',
                            ],
                            [
                                'q' => 'Bagaimana kalau saya lupa unduh dokumen setelah daftar?',
                                'a' => 'Buka halaman Cek Status, masukkan nomor pendaftaranmu, dokumen bisa diunduh ulang kapan saja.',
                            ],
                            [
                                'q' => 'QR Code untuk absensi didapat dari mana?',
                                'a' => 'Muncul otomatis di halaman Cek Status begitu nomor pendaftaranmu ditemukan. Tinggal tunjukkan ke panitia saat check-in.',
                            ],
                            [
                                'q' => 'Kapan sertifikat bisa diunduh?',
                                'a' => 'Setelah seluruh rangkaian kegiatan selesai dan kehadiranmu tercatat lewat absensi QR di hari-H.',
                            ],
                        ];
                    @endphp
                    @foreach ($faq as $item)
                        <details class="group p-5 sm:p-6">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-semibold text-ink-900 marker:content-none">
                                {{ $item['q'] }}
                                <svg class="h-4 w-4 shrink-0 text-ink/40 transition group-open:rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </summary>
                            <p class="mt-3 text-sm leading-relaxed text-ink/60">{{ $item['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>

            <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                <a href="/pendaftaran" class="inline-flex flex-1 items-center justify-center gap-2 rounded-full bg-ink-900 px-6 py-3.5 text-sm font-semibold text-canvas shadow-md shadow-ink-900/20 transition hover:bg-ink-700">
                    Daftar Sekarang
                </a>
                <a href="/cek-status" class="inline-flex flex-1 items-center justify-center gap-2 rounded-full border border-line bg-white px-6 py-3.5 text-sm font-semibold text-ink-900 transition hover:border-ink-900/30">
                    Cek Status Pendaftaran
                </a>
            </div>
        </div>
    </section>

@endsection