@extends('layouts.app')

@section('title', 'Verifikasi Perubahan Data Pegawai')

@section('content')
<div class="container-fluid py-4">
    <h3 class="mb-1">Antrean Verifikasi Perubahan Data</h3>
    <p class="text-muted mb-4">Hanya pengajuan Guru lain yang dapat diverifikasi.</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($requests as $changeRequest)
        <a class="card shadow-sm border-0 mb-3 text-decoration-none text-reset"
            href="{{ route('tu.pegawai-changes.show', $changeRequest) }}">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $changeRequest->pegawai->nama }}</strong>
                    <div class="small text-muted">Pengajuan #{{ $changeRequest->id }} · {{ $changeRequest->created_at->format('d-m-Y H:i') }}</div>
                    <div class="small">{{ $changeRequest->items->where('status', 'pending')->count() }} item menunggu</div>
                </div>
                <span class="badge text-bg-warning">Menunggu verifikasi</span>
            </div>
        </a>
    @empty
        <div class="alert alert-info">Tidak ada pengajuan yang menunggu verifikasi.</div>
    @endforelse

    {{ $requests->links() }}
</div>
@endsection
