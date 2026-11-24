@extends('layouts.app')

@section('title', 'Riwayat Perubahan Data Pegawai')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Perubahan Data Pegawai</h3>
            <p class="text-muted mb-0">Pengajuan hanya dapat dibuat untuk data kepegawaian akun Anda.</p>
        </div>
        <a href="{{ route('guru.pegawai-changes.create') }}" class="btn btn-primary">Ajukan Perubahan</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($requests as $changeRequest)
        <a class="card shadow-sm border-0 mb-3 text-decoration-none text-reset"
            href="{{ route('guru.pegawai-changes.show', $changeRequest) }}">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <strong>Pengajuan #{{ $changeRequest->id }}</strong>
                    <div class="small text-muted">{{ $changeRequest->created_at->format('d-m-Y H:i') }}</div>
                    <div class="small">{{ $changeRequest->items->count() }} item perubahan</div>
                </div>
                <span class="badge text-bg-secondary">{{ str_replace('_', ' ', ucfirst($changeRequest->status)) }}</span>
            </div>
        </a>
    @empty
        <div class="alert alert-info">Belum ada pengajuan perubahan data.</div>
    @endforelse

    {{ $requests->links() }}
</div>
@endsection
