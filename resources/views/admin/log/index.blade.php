@extends('layouts.admin')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

@section('content')
<div class="rounded-2xl border border-line bg-white">
    <div class="divide-y divide-line">
        @forelse ($logs as $log)
            <div class="flex items-start gap-3 px-5 py-4">
                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-ink-900/[0.06] text-xs font-semibold text-ink-900">
                    {{ strtoupper(substr($log->admin->name ?? '?', 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="text-sm text-ink-900">
                        <span class="font-medium">{{ $log->admin->name ?? 'Admin (terhapus)' }}</span>
                        {{ $log->description }}
                    </p>
                    <p class="mt-0.5 text-xs text-ink-900/45">{{ $log->created_at->diffForHumans() }} · {{ $log->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        @empty
            <p class="px-5 py-8 text-center text-sm text-ink-900/50">Belum ada aktivitas tercatat.</p>
        @endforelse
    </div>
</div>

<div class="mt-5">
    {{ $logs->links() }}
</div>
@endsection