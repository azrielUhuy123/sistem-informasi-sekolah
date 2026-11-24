@extends('layouts.app')

@section('title', 'Detail Pengajuan Perubahan Data')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Pengajuan #{{ $pegawaiChangeRequest->id }}</h3>
            <p class="text-muted mb-0">Diajukan {{ $pegawaiChangeRequest->created_at->format('d-m-Y H:i') }}</p>
        </div>
        <span class="badge text-bg-secondary">{{ str_replace('_', ' ', ucfirst($pegawaiChangeRequest->status)) }}</span>
    </div>

    @foreach ($pegawaiChangeRequest->items as $item)
        @php($definition = config('employee_profile_changes.fields.'.$item->field, ['label' => $item->field]))
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h5>{{ $definition['label'] }}</h5>
                    <span class="badge text-bg-secondary">{{ ucfirst($item->status) }}</span>
                </div>
                <dl class="row mb-0">
                    <dt class="col-sm-3">Nilai sebelumnya</dt><dd class="col-sm-9">{{ $item->old_value ?? '—' }}</dd>
                    <dt class="col-sm-3">Nilai diajukan</dt><dd class="col-sm-9">{{ $item->new_value ?? '—' }}</dd>
                    @if ($item->evidence_path)
                        <dt class="col-sm-3">Bukti</dt>
                        <dd class="col-sm-9">
                            <a href="{{ route('pegawai-changes.evidence', $item) }}">Unduh bukti privat</a>
                        </dd>
                    @endif
                    @if ($item->rejection_reason)
                        <dt class="col-sm-3">Alasan penolakan</dt><dd class="col-sm-9">{{ $item->rejection_reason }}</dd>
                    @endif
                    @if ($item->verifier)
                        <dt class="col-sm-3">Diperiksa oleh</dt><dd class="col-sm-9">{{ $item->verifier->name }}</dd>
                    @endif
                </dl>
                @if ($item->status === 'rejected' && ! $item->resubmissions()->exists())
                    <a class="btn btn-sm btn-outline-primary mt-3"
                        href="{{ route('guru.pegawai-changes.create', ['retry' => $item->id]) }}">
                        Ajukan Ulang Item
                    </a>
                @endif
            </div>
        </div>
    @endforeach

    <a href="{{ route('guru.pegawai-changes.index') }}" class="btn btn-outline-secondary">Kembali ke Riwayat</a>
</div>
@endsection
