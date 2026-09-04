@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden bg-[radial-gradient(ellipse_90%_60%_at_50%_-10%,rgba(15,42,67,0.08),transparent)]">
        {{-- ornamen: cahaya lingkaran & pola titik, sengaja dibuat kelihatan --}}
        <div class="pointer-events-none absolute -top-32 right-[-6%] h-[34rem] w-[34rem] rounded-full bg-gold/30 blur-[100px]"></div>
        <div class="pointer-events-none absolute -bottom-40 left-[-10%] h-[28rem] w-[28rem] rounded-full bg-ink-700/20 blur-[90px]"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-[28rem] opacity-[0.5] [mask-image:radial-gradient(ellipse_65%_65%_at_50%_0%,#000_25%,transparent_75%)]" style="background-image: radial-gradient(circle, #0F2A43 1.4px, transparent 1.4px); background-size: 24px 24px;"></div>

        {{-- garis diagonal tipis ala kop surat resmi --}}
        <div class="pointer-events-none absolute left-0 top-0 h-full w-1.5 bg-gradient-to-b from-gold via-ink-900 to-gold/60"></div>

        <div class="relative mx-auto max-w-7xl px-5 pb-12 pt-10 sm:px-6 sm:pb-16 sm:pt-14 lg:px-8 lg:pt-20">
            <div class="grid grid-cols-1 items-center gap-10 sm:gap-12 lg:grid-cols-12 lg:gap-14">

                <div class="lg:col-span-7">
                    <span class="inline-flex items-center gap-2 rounded-full border border-gold/40 bg-white px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-ink-700 shadow-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-gold"></span>
                        Sistem Informasi Manajemen Bimtek
                    </span>

                    <h1 class="mt-5 font-display text-[2rem] font-bold leading-[1.15] tracking-tight text-ink-900 sm:mt-6 sm:text-4xl md:text-5xl lg:text-[3.4rem] lg:leading-[1.1]">
                        Dari daftar sampai
                        <span class="relative inline-block">
                            sertifikat,
                            <svg class="pointer-events-none absolute -bottom-1 left-0 w-full" height="10" viewBox="0 0 220 10" preserveAspectRatio="none"><path d="M2,7 C60,-1 160,-1 218,7" fill="none" stroke="#C99A3D" stroke-width="4" stroke-linecap="round"/></svg>
                        </span>
                        satu alur yang jelas.
                    </h1>

                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-ink/70 sm:mt-6 sm:text-base lg:text-lg">
                        SIMBIMTEK menyatukan pendaftaran, penerbitan SPPD, dan sertifikat
                        bimbingan teknis dalam satu tempat — peserta cukup daftar sekali dengan NIP,
                        dan data lamanya otomatis dipakai untuk kegiatan berikutnya.
                    </p>

                    <div class="mt-7 flex flex-col gap-3 sm:mt-9 sm:flex-row">
                        <a href="/pendaftaran" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-ink-900 px-7 py-3.5 text-sm font-semibold text-canvas shadow-md shadow-ink-900/20 transition hover:bg-ink-700 hover:shadow-lg sm:w-auto">
                            Daftar Sekarang
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </a>
                        <a href="/cek-status" class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-line bg-white px-7 py-3.5 text-sm font-semibold text-ink-900 transition hover:border-ink-900/30 sm:w-auto">
                            Cek Status Pendaftaran
                        </a>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-ink-900/10 pt-5 sm:mt-10 sm:gap-8 sm:pt-6">
                        <div>
                            <p class="font-display text-xl font-bold text-ink-900 sm:text-2xl">1.240<span class="text-gold">+</span></p>
                            <p class="text-xs text-ink/55">Peserta terlayani</p>
                        </div>
                        <div class="hidden h-8 w-px bg-line sm:block"></div>
                        <div>
                            <p class="font-display text-xl font-bold text-ink-900 sm:text-2xl">48</p>
                            <p class="text-xs text-ink/55">Kegiatan bimtek</p>
                        </div>
                        <div class="hidden h-8 w-px bg-line sm:block"></div>
                        <div>
                            <p class="font-display text-xl font-bold text-ink-900 sm:text-2xl">100%</p>
                            <p class="text-xs text-ink/55">Sertifikat digital</p>
                        </div>
                    </div>
                </div>

                {{-- Signature element: alur mini + stempel resmi — merefleksikan tahap berkas peserta yang sesungguhnya --}}
                <div class="relative mt-2 lg:col-span-5 lg:mt-0">
                    <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-gold/30 blur-2xl"></div>

                    {{-- stempel/seal berputar, memberi kesan dokumen resmi & bersertifikat — dikecilkan & digeser di mobile supaya tidak menumpuk konten --}}
                    <div class="pointer-events-none absolute -right-2 -top-4 z-20 h-14 w-14 -rotate-[8deg] sm:-right-4 sm:-top-7 sm:h-20 sm:w-20 lg:-right-5 lg:-top-9 lg:h-28 lg:w-28 lg:-rotate-[10deg]">
                        <svg viewBox="0 0 100 100" class="h-full w-full drop-shadow-sm">
                            <circle cx="50" cy="50" r="46" fill="#F5F7F6" stroke="#C99A3D" stroke-width="1.6" stroke-dasharray="2.5 3.5"/>
                            <circle cx="50" cy="50" r="37" fill="none" stroke="#0F2A43" stroke-width="1"/>
                            <path id="sealPath" d="M 50,50 m -30,0 a 30,30 0 1,1 60,0 a 30,30 0 1,1 -60,0" fill="none"/>
                            <text font-size="7.2" fill="#0F2A43" letter-spacing="2.2" font-family="'JetBrains Mono', monospace" font-weight="500">
                                <textPath href="#sealPath" startOffset="2%">SIMBIMTEK • SISTEM RESMI •</textPath>
                            </text>
                            <path d="M38,50 l8,8 l16,-18" fill="none" stroke="#C99A3D" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div class="relative rounded-2xl border border-line bg-white p-5 shadow-lg shadow-ink-900/[0.06] sm:p-6">
                        <p class="font-display text-sm font-semibold text-ink-900">Alur berkas peserta</p>
                        <ol class="mt-5 space-y-0">
                            @php
                                $tahapan = [
                                    ['label' => 'Daftar Online', 'desc' => 'Isi data diri atau otomatis terisi dari NIP'],
                                    ['label' => 'Terbit SPPD', 'desc' => 'Surat tugas siap dicetak langsung'],
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
                </div>

            </div>
        </div>
    </section>

    {{-- gelombang: transisi canvas -> putih --}}
    <div class="relative -mb-px h-8 w-full overflow-hidden sm:h-14" aria-hidden="true">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" class="absolute bottom-0 h-full w-full">
            <path d="M0,22 C240,50 480,2 720,20 C960,38 1200,54 1440,24 L1440,60 L0,60 Z" class="fill-white"></path>
        </svg>
    </div>

    {{-- ============ INFORMASI KEGIATAN ============ --}}
    <section id="informasi-kegiatan" class="relative scroll-mt-24 overflow-hidden bg-white py-12 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-12 lg:gap-12">

                <div class="lg:col-span-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gold-600">Tentang Program</p>
                    <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl">
                        Bimtek Bendahara &amp; Operator BOSP
                    </h2>
                    <p class="mt-4 text-sm leading-relaxed text-ink/65 sm:text-base">
                        Bimbingan teknis pengelolaan Bantuan Operasional Satuan Pendidikan (BOSP) bagi
                        Bendahara dan Operator sekolah, mulai dari perencanaan anggaran, pelaporan, sampai
                        pertanggungjawaban dana — sesuai ketentuan yang berlaku.
                    </p>

                    <div class="mt-6 rounded-2xl border border-gold/25 bg-gold/[0.05] p-5">
                        <p class="font-display text-sm font-semibold text-ink-900">Siapa yang wajib ikut?</p>
                        <ul class="mt-3 space-y-2 text-sm text-ink/70">
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Bendahara BOSP di setiap satuan pendidikan
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Operator BOSP di setiap satuan pendidikan
                            </li>
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Atas penugasan dan sepengetahuan Kepala Sekolah
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <p class="font-display text-sm font-semibold text-ink-900">Materi yang dibahas</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        @php
                            $materi = [
                                [
                                    'judul' => 'Perencanaan & Penganggaran',
                                    'desc' => 'Menyusun rencana kegiatan dan anggaran BOSP sesuai juknis terbaru.',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>',
                                ],
                                [
                                    'judul' => 'Pelaporan & ARKAS',
                                    'desc' => 'Praktik langsung input dan pelaporan dana lewat aplikasi ARKAS.',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/>',
                                ],
                                [
                                    'judul' => 'Pertanggungjawaban Dana',
                                    'desc' => 'Menyusun bukti dan laporan pertanggungjawaban yang sah dan tertib.',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286z"/>',
                                ],
                                [
                                    'judul' => 'Studi Kasus & Simulasi',
                                    'desc' => 'Latihan kasus lapangan yang sering ditemui saat pemeriksaan.',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>',
                                ],
                            ];
                        @endphp
                        @foreach ($materi as $m)
                            <div class="rounded-2xl border border-line p-5 transition hover:border-gold/40 hover:bg-gold/[0.03]">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-ink-900/[0.06] text-ink-900">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $m['icon'] !!}</svg>
                                </div>
                                <p class="mt-3 font-display text-sm font-semibold text-ink-900">{{ $m['judul'] }}</p>
                                <p class="mt-1.5 text-sm leading-relaxed text-ink/60">{{ $m['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex items-start gap-3 rounded-xl bg-canvas p-4">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-ink/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                        <p class="text-xs leading-relaxed text-ink/60">
                            Peserta cukup membawa Surat Tugas dan SPPD yang sudah diunduh otomatis setelah
                            mendaftar — lihat jadwal kegiatan yang sedang dibuka di bawah ini.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============ KEGIATAN TERDEKAT — gaya kartu jadwal/tiket ============ --}}
    <section id="kegiatan" class="relative scroll-mt-24 overflow-hidden bg-white py-2 sm:py-4">
        <div class="pointer-events-none absolute -left-16 top-1/2 h-72 w-72 -translate-y-1/2 rounded-full bg-ink-900/[0.05] blur-3xl"></div>
        <div class="pointer-events-none absolute -right-10 bottom-0 h-56 w-56 rounded-full bg-gold/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-5 py-10 sm:px-6 sm:py-12 lg:px-8 lg:py-16">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gold-600">Jadwal Terdekat</p>
                <h2 class="mt-2 font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl">Kegiatan yang sedang dibuka</h2>
            </div>
        </div>

        <div id="jadwal" class="mt-8 grid gap-5 sm:mt-10 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
            @forelse ($kegiatanList as $k)
                @php
                    $persenTerisi = $k->kuota > 0 ? $k->kuota_terisi / $k->kuota : 0;
                    $statusLabel = $persenTerisi >= 0.8 ? 'Segera Ditutup' : 'Kuota Tersedia';
                @endphp
                <div class="group relative overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-line transition hover:-translate-y-1 hover:shadow-xl hover:shadow-ink-900/[0.08]">
                    <div @class([
                        'h-1.5 w-full',
                        'bg-success' => $statusLabel === 'Kuota Tersedia',
                        'bg-gold' => $statusLabel === 'Segera Ditutup',
                    ])></div>

                    <div class="p-5 sm:p-6">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="font-display text-base font-semibold leading-snug text-ink-900 sm:text-lg">{{ $k->nama }}</h3>
                        </div>

                        <dl class="mt-4 space-y-2.5 text-sm text-ink/70 sm:mt-5">
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-ink-900/[0.06] text-ink-900">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-12A.75.75 0 014.5 6z"/></svg>
                                </span>
                                <span class="font-mono text-xs">{{ $k->teks_jadwal }}</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-ink-900/[0.06] text-ink-900">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                </span>
                                {{ $k->lokasi }}
                            </div>
                        </dl>
                    </div>

                    {{-- perforasi ala tiket kegiatan --}}
                    <div class="relative flex items-center px-5 sm:px-6">
                        <div class="h-3.5 w-3.5 -translate-x-1/2 rounded-full bg-canvas ring-1 ring-line"></div>
                        <div class="flex-1 border-t border-dashed border-line"></div>
                        <div class="h-3.5 w-3.5 translate-x-1/2 rounded-full bg-canvas ring-1 ring-line"></div>
                    </div>

                    <div class="flex items-center justify-between px-5 py-4 sm:px-6 sm:py-5">
                        <span class="text-xs font-medium text-ink/55">{{ $k->kuota_terisi }} dari {{ $k->kuota }} kuota</span>
                        <span @class([
                            'rounded-full px-3 py-1 text-xs font-semibold',
                            'bg-success/10 text-success' => $statusLabel === 'Kuota Tersedia',
                            'bg-gold/15 text-gold-600' => $statusLabel === 'Segera Ditutup',
                        ])>
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-line bg-white p-10 text-center">
                    <p class="text-sm text-ink/50">Belum ada kegiatan yang dibuka saat ini. Cek lagi lain waktu ya.</p>
                </div>
            @endforelse
        </div>
        </div>
    </section>

    {{-- gelombang: transisi putih -> canvas --}}
    <div class="relative -mb-px h-8 w-full overflow-hidden sm:h-14" aria-hidden="true">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" class="absolute bottom-0 h-full w-full">
            <path d="M0,24 C240,54 480,4 720,22 C960,40 1200,52 1440,20 L1440,60 L0,60 Z" class="fill-canvas"></path>
        </svg>
    </div>

    {{-- ============ KENAPA SIMBIMTEK ============ --}}
    <section class="relative overflow-hidden bg-canvas py-12 sm:py-16 lg:py-24">
        <div class="pointer-events-none absolute inset-0 opacity-[0.5] [mask-image:radial-gradient(ellipse_70%_70%_at_50%_30%,#000_15%,transparent_70%)]" style="background-image: radial-gradient(circle, #0F2A43 1.4px, transparent 1.4px); background-size: 22px 22px;"></div>
        <div class="pointer-events-none absolute right-[8%] bottom-0 h-64 w-64 rounded-full bg-gold/20 blur-3xl"></div>
        <div class="pointer-events-none absolute left-[5%] top-10 h-48 w-48 rounded-full bg-ink-700/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-wider text-gold-600">Kenapa SIMBIMTEK</p>
            <h2 class="mt-2 max-w-xl font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl">
                Semua yang dibutuhkan peserta, tanpa bolak-balik ke panitia
            </h2>

            <div class="mt-10 grid gap-5 sm:mt-12 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
                @php
                    $fitur = [
                        [
                            'judul' => 'Pendaftaran dengan NIP',
                            'desc' => 'Isi NIP saja — kalau sudah pernah daftar, data lama otomatis terisi.',
                            'warna' => 'bg-ink-900/10 text-ink-900 group-hover:bg-ink-900 group-hover:text-white',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1M6 3h9l4.5 4.5V19a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"/>',
                        ],
                        [
                            'judul' => 'Cetak Dokumen Otomatis',
                            'desc' => 'Bukti daftar, SPPD, dan sertifikat tersedia dalam format siap cetak.',
                            'warna' => 'bg-gold/15 text-gold-600 group-hover:bg-gold group-hover:text-white',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>',
                        ],
                        [
                            'judul' => 'Absensi QR Code',
                            'desc' => 'Kehadiran tercatat begitu QR dipindai di lokasi kegiatan.',
                            'warna' => 'bg-success/10 text-success group-hover:bg-success group-hover:text-white',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5A.75.75 0 014.5 3.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5zM3.75 15a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75V15zM14.25 4.5a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v4.5a.75.75 0 01-.75.75h-4.5a.75.75 0 01-.75-.75v-4.5zM14.25 15.75h2.25v2.25h-2.25v-2.25zM17.25 18.75h2.25V21h-2.25v-2.25zM14.25 18.75h.008v.008h-.008v-.008zM17.25 15.75h.008v.008h-.008v-.008zM19.5 15.75h.008v.008h-.008v-.008zM19.5 18.75h.008v.008h-.008v-.008z"/>',
                        ],
                        [
                            'judul' => 'Cek Status Real-time',
                            'desc' => 'Lihat status pendaftaran, SPPD, dan sertifikat kapan pun tanpa perlu bertanya.',
                            'warna' => 'bg-[#3E6B8F]/10 text-[#3E6B8F] group-hover:bg-[#3E6B8F] group-hover:text-white',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>',
                        ],
                    ];
                @endphp
                @foreach ($fitur as $f)
                    <div class="group rounded-2xl border border-line/70 bg-white/70 p-5 shadow-sm backdrop-blur-sm transition hover:shadow-md sm:p-6">
                        <div @class(['flex h-11 w-11 items-center justify-center rounded-xl transition', $f['warna']])>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">{!! $f['icon'] !!}</svg>
                        </div>
                        <h3 class="mt-4 font-display text-base font-semibold text-ink-900">{{ $f['judul'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-ink/60">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- gelombang: transisi canvas -> putih --}}
    <div class="relative -mb-px h-8 w-full overflow-hidden sm:h-14" aria-hidden="true">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" class="absolute bottom-0 h-full w-full">
            <path d="M0,20 C240,48 480,0 720,16 C960,32 1200,56 1440,26 L1440,60 L0,60 Z" class="fill-white"></path>
        </svg>
    </div>

    {{-- ============ ALUR PENDAFTARAN DETAIL ============ --}}
<section id="alur" class="relative scroll-mt-24 overflow-hidden bg-white py-12 sm:py-16 lg:py-24">
    <div class="pointer-events-none absolute -right-20 top-10 h-72 w-72 rounded-full bg-ink-700/[0.06] blur-3xl"></div>
    <div class="pointer-events-none absolute -left-20 bottom-0 h-64 w-64 rounded-full bg-gold/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gold-600">Cara Kerja</p>
            <h2 class="mt-2 max-w-xl font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl">Tiga langkah, dari daftar sampai sertifikat</h2>
        </div>
        <a href="{{ route('cara-kerja') }}" class="inline-flex shrink-0 items-center gap-1.5 text-sm font-semibold text-ink-900 hover:text-gold-600">
            Lihat detail lengkap &amp; FAQ
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
        </a>
    </div>

    <div class="relative mt-10 sm:mt-14">
        {{-- garis penghubung horizontal, tampak di layar besar --}}
        <div class="pointer-events-none absolute left-0 right-0 top-5 hidden border-t-2 border-dashed border-gold/40 lg:block"></div>

        <div class="grid gap-5 sm:grid-cols-3 sm:gap-6">
            @php
                $langkah = [
                    ['no' => '01', 'judul' => 'Daftar dengan NIP', 'desc' => 'Masukkan NIP — kalau sudah pernah terdaftar, data diri otomatis terisi.'],
                    ['no' => '02', 'judul' => 'SPPD langsung terbit', 'desc' => 'Surat Tugas dan SPPD siap diunduh dan dicetak begitu formulir dikirim.'],
                    ['no' => '03', 'judul' => 'Ikuti & unduh sertifikat', 'desc' => 'Hadir sesuai jadwal, absen lewat QR Code, sertifikat terbit usai kegiatan.'],
                ];
            @endphp
            @foreach ($langkah as $l)
                <div class="relative">
                    <span class="relative z-10 mb-4 flex h-10 w-10 items-center justify-center rounded-full border-2 border-gold bg-white font-mono text-xs font-bold text-ink-900">
                        {{ $l['no'] }}
                    </span>
                    <div class="rounded-2xl border border-line bg-canvas/60 p-5 transition hover:border-gold/40 hover:bg-canvas sm:p-6">
                        <h3 class="font-display text-base font-semibold text-ink-900">{{ $l['judul'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink/60">{{ $l['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center sm:hidden">
            <a href="{{ route('cara-kerja') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-ink-900">
                Lihat detail lengkap &amp; FAQ
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>
    </div>
    </div>
</section>

    {{-- gelombang: transisi putih -> navy --}}
    <div class="relative -mb-px h-8 w-full overflow-hidden sm:h-14" aria-hidden="true">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" class="absolute bottom-0 h-full w-full">
            <path d="M0,26 C240,56 480,6 720,24 C960,42 1200,50 1440,18 L1440,60 L0,60 Z" class="fill-ink-900"></path>
        </svg>
    </div>

    {{-- ============ CEK STATUS BAND ============ --}}
    <section id="cek-status" class="relative scroll-mt-24 overflow-hidden bg-ink-900 py-12 sm:py-16 lg:py-20">
        <div class="pointer-events-none absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="pointer-events-none absolute left-1/2 top-0 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gold/30 blur-[100px]"></div>
        <div class="pointer-events-none absolute -right-10 bottom-0 h-56 w-56 rounded-full bg-white/5 blur-3xl"></div>

        <div class="relative mx-auto max-w-3xl px-5 text-center sm:px-6 lg:px-8">
            <h2 class="font-display text-xl font-bold text-canvas sm:text-2xl lg:text-3xl">Sudah daftar? Cek status berkasmu</h2>
            <p class="mt-3 text-sm text-canvas/60 sm:text-base">Masukkan nomor pendaftaran untuk melihat status pendaftaran, SPPD, dan sertifikat.</p>

            <form action="/cek-status" method="GET" class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:justify-center">
                <input
                    type="text"
                    name="nomor_pendaftaran"
                    placeholder="Contoh nomor pendaftaran: BT-2026-0142"
                    class="w-full rounded-full border-0 bg-white/10 px-5 py-3.5 font-mono text-sm text-canvas placeholder:text-canvas/40 ring-1 ring-inset ring-white/15 focus:ring-2 focus:ring-gold sm:max-w-xs"
                >
                <button type="submit" class="w-full shrink-0 rounded-full bg-gold px-7 py-3.5 text-sm font-semibold text-ink-900 shadow-lg shadow-gold/20 transition hover:bg-gold-600 sm:w-auto">
                    Cek Status
                </button>
            </form>
        </div>
    </section>

@endsection