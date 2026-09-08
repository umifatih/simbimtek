@extends('layouts.admin')

@section('title', 'Pengaturan Beranda')
@section('page-title', 'Pengaturan Beranda')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="rounded-2xl border border-line bg-white p-5 sm:p-8">
        <h2 class="font-display text-lg font-bold text-ink-900">Identitas & Konten Beranda</h2>
        <p class="mt-1 text-sm text-ink/60">Perubahan di sini akan langsung tampil di halaman beranda publik.</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg bg-success/10 px-4 py-3 text-sm text-success">{{ session('status') }}</div>
        @endif

        <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-ink-900">Logo</label>
                <div class="mt-2 flex items-center gap-4">
                    @if ($setting->logo_url)
                        <img src="{{ $setting->logo_url }}" alt="Logo saat ini" class="h-14 w-14 rounded-lg border border-line object-contain p-1.5">
                    @else
                        <span class="flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-line text-xs text-ink/40">
                            Tanpa logo
                        </span>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="block text-sm">
                </div>
                <p class="mt-1.5 text-xs text-ink/50">Format PNG/JPG/SVG/WEBP, maksimal 1MB. Biarkan kosong kalau tidak ingin mengganti.</p>
                @error('logo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-ink-900">Nama Aplikasi</label>
                <input type="text" name="nama_aplikasi" value="{{ old('nama_aplikasi', $setting->nama_aplikasi) }}"
                       class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                @error('nama_aplikasi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-ink-900">Tagline Hero</label>
                <input type="text" name="tagline" value="{{ old('tagline', $setting->tagline) }}"
                       class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                @error('tagline') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-ink-900">Deskripsi Hero</label>
                <textarea name="deskripsi" rows="3"
                          class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">{{ old('deskripsi', $setting->deskripsi) }}</textarea>
                @error('deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <hr class="border-line">

            <div>
                <label class="block text-sm font-semibold text-ink-900">Judul "Tentang Program"</label>
                <input type="text" name="tentang_program_judul" value="{{ old('tentang_program_judul', $setting->tentang_program_judul) }}"
                       class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                @error('tentang_program_judul') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-ink-900">Deskripsi "Tentang Program"</label>
                <textarea name="tentang_program_deskripsi" rows="3"
                          class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">{{ old('tentang_program_deskripsi', $setting->tentang_program_deskripsi) }}</textarea>
                @error('tentang_program_deskripsi') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <hr class="border-line">

            <div>
                <label class="block text-sm font-semibold text-ink-900">Judul "Kenapa SIMBIMTEK"</label>
                <input type="text" name="kenapa_simbimtek_judul" value="{{ old('kenapa_simbimtek_judul', $setting->kenapa_simbimtek_judul) }}"
                       class="mt-1.5 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                @error('kenapa_simbimtek_judul') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($setting->fitur as $i => $f)
                    <div class="rounded-xl border border-line p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink/45">Fitur {{ $i + 1 }}</p>
                        <input type="text" name="fitur[{{ $i }}][judul]" value="{{ old("fitur.$i.judul", $f['judul']) }}"
                               placeholder="Judul fitur"
                               class="mt-2 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">
                        <textarea name="fitur[{{ $i }}][desc]" rows="2" placeholder="Deskripsi fitur"
                                  class="mt-2 w-full rounded-lg border border-line px-3 py-2 text-sm focus:border-ink-900/30 focus:outline-none focus:ring-2 focus:ring-gold/30">{{ old("fitur.$i.desc", $f['desc']) }}</textarea>
                        @error("fitur.$i.judul") <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        @error("fitur.$i.desc") <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end border-t border-line pt-5">
                <button type="submit" class="rounded-full bg-ink-900 px-6 py-3 text-sm font-semibold text-canvas transition hover:bg-ink-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection