@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="mx-auto max-w-xl">

    @if (session('status'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-line bg-white p-6">
        <h2 class="font-display text-sm font-semibold text-ink-900">Informasi Akun</h2>
        <p class="mt-1 text-xs text-ink-900/50">Nama dan email yang digunakan untuk login.</p>

        <form action="{{ route('admin.profil.update') }}" method="POST" class="mt-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-xs font-medium text-ink-900/70">Nama</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                    class="w-full rounded-xl border border-line px-3.5 py-2.5 text-sm focus:border-ink-900 focus:outline-none">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-medium text-ink-900/70">Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                    class="w-full rounded-xl border border-line px-3.5 py-2.5 text-sm focus:border-ink-900 focus:outline-none">
            </div>

            <div class="border-t border-line pt-4">
                <p class="mb-3 text-xs font-medium text-ink-900/70">Ganti Password <span class="text-ink-900/40">(kosongkan kalau tidak ingin ganti)</span></p>

                <div class="space-y-3">
                    <input type="password" name="password_lama" placeholder="Password lama"
                        class="w-full rounded-xl border border-line px-3.5 py-2.5 text-sm focus:border-ink-900 focus:outline-none">
                    <input type="password" name="password_baru" placeholder="Password baru"
                        class="w-full rounded-xl border border-line px-3.5 py-2.5 text-sm focus:border-ink-900 focus:outline-none">
                    <input type="password" name="password_baru_confirmation" placeholder="Ulangi password baru"
                        class="w-full rounded-xl border border-line px-3.5 py-2.5 text-sm focus:border-ink-900 focus:outline-none">
                </div>
            </div>

            <button type="submit"
                class="mt-2 rounded-xl bg-ink-900 px-5 py-2.5 text-sm font-medium text-canvas transition hover:opacity-90">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection