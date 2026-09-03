{{-- resources/views/jadwal/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Jadwal Kegiatan')

@section('content')

    {{-- ============ HEADER ============ --}}
    <section class="relative overflow-hidden bg-[radial-gradient(ellipse_90%_60%_at_50%_-10%,rgba(15,42,67,0.07),transparent)] pb-8 pt-8 sm:pb-10 sm:pt-12 lg:pb-14 lg:pt-16">
        <div class="pointer-events-none absolute -top-24 right-[-6%] h-80 w-80 rounded-full bg-gold/20 blur-[100px]"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-64 opacity-[0.4] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_0%,#000_20%,transparent_75%)]" style="background-image: radial-gradient(circle, #0F2A43 1.4px, transparent 1.4px); background-size: 24px 24px;"></div>

        <div class="relative mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-xs text-ink/50">
                <a href="/" class="hover:text-ink-900">Beranda</a>
                <span>/</span>
                <span class="font-medium text-ink-900">Jadwal</span>
            </nav>
            <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink-900 sm:text-3xl lg:text-4xl">Jadwal Kegiatan</h1>
            <p class="mt-3 max-w-xl text-sm leading-relaxed text-ink/65 sm:text-base">
                Semua kegiatan bimtek — yang sedang dibuka pendaftarannya maupun yang sudah selesai.
            </p>

            {{-- ===== FILTER STATUS ===== --}}
            <div class="mt-6 flex flex-wrap gap-2">
                @php
                    $filter = [
                        ['key' => null, 'label' => 'Semua'],
                        ['key' => 'dibuka', 'label' => 'Dibuka'],
                        ['key' => 'ditutup', 'label' => 'Ditutup'],
                        ['key' => 'selesai', 'label' => 'Selesai'],
                    ];
                @endphp
                @foreach ($filter as $f)
                    <a
                        href="{{ $f['key'] ? '/jadwal?status=' . $f['key'] : '/jadwal' }}"
                        @class([
                            'rounded-full px-4 py-2 text-xs font-semibold transition',
                            'bg-ink-900 text-canvas' => $status === $f['key'],
                            'border border-line bg-white text-ink-900 hover:border-ink-900/30' => $status !== $f['key'],
                        ])
                    >
                        {{ $f['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-5 py-10 sm:px-6 sm:py-12 lg:px-8 lg:py-16">

            <div class="grid gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                @forelse ($kegiatanList as $k)
                    @php
                        $persenTerisi = $k->kuota > 0 ? $k->kuota_terisi / $k->kuota : 0;
                        $badge = match (true) {
                            $k->status === 'selesai' => ['Selesai', 'bg-ink-900/10 text-ink/50'],
                            $k->status === 'ditutup' => ['Ditutup', 'bg-red-50 text-red-600'],
                            $persenTerisi >= 0.8 => ['Segera Ditutup', 'bg-gold/15 text-gold-600'],
                            default => ['Kuota Tersedia', 'bg-success/10 text-success'],
                        };
                    @endphp
                    <div class="group relative overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-line transition hover:-translate-y-1 hover:shadow-xl hover:shadow-ink-900/[0.08]">
                        <div @class([
                            'h-1.5 w-full',
                            'bg-success' => $badge[0] === 'Kuota Tersedia',
                            'bg-gold' => $badge[0] === 'Segera Ditutup',
                            'bg-ink-900/20' => $badge[0] === 'Selesai',
                            'bg-red-400' => $badge[0] === 'Ditutup',
                        ])></div>

                        <div class="p-5 sm:p-6">
                            <h3 class="font-display text-base font-semibold leading-snug text-ink-900 sm:text-lg">{{ $k->nama }}</h3>

                            <dl class="mt-4 space-y-2.5 text-sm text-ink/70 sm:mt-5">
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-ink-900/[0.06] text-ink-900">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-12A.75.75 0 014.5 6z"/></svg>
                                    </span>
                                    <span class="font-mono text-xs">{{ $k->tanggal }}</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-ink-900/[0.06] text-ink-900">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                    </span>
                                    {{ $k->lokasi }}
                                </div>
                            </dl>
                        </div>

                        <div class="relative flex items-center px-5 sm:px-6">
                            <div class="h-3.5 w-3.5 -translate-x-1/2 rounded-full bg-canvas ring-1 ring-line"></div>
                            <div class="flex-1 border-t border-dashed border-line"></div>
                            <div class="h-3.5 w-3.5 translate-x-1/2 rounded-full bg-canvas ring-1 ring-line"></div>
                        </div>

                        <div class="flex items-center justify-between px-5 py-4 sm:px-6 sm:py-5">
                            <span class="text-xs font-medium text-ink/55">{{ $k->kuota_terisi }} dari {{ $k->kuota }} kuota</span>
                            <span @class(['rounded-full px-3 py-1 text-xs font-semibold', $badge[1]])>{{ $badge[0] }}</span>
                        </div>

                        @if ($k->status === 'dibuka')
                            <div class="px-5 pb-5 sm:px-6 sm:pb-6">
                                <a href="/pendaftaran" class="flex w-full items-center justify-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-semibold text-canvas transition hover:bg-ink-700">
                                    Daftar Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-line bg-white p-10 text-center">
                        <p class="text-sm text-ink/50">
                            @if ($status)
                                Tidak ada kegiatan dengan status ini.
                            @else
                                Belum ada kegiatan yang tercatat.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

@endsection