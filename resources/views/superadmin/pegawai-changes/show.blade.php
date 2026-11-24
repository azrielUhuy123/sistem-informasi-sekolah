@extends('layouts.app')

@section('title', 'Detail Monitoring Pengajuan')

@section('content')
<div class="container-fluid py-4">
    <nav aria-label="Breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Kepala Sekolah</li>
            <li class="breadcrumb-item">Manajemen Kepegawaian</li>
            <li class="breadcrumb-item">Perubahan Data</li>
            <li class="breadcrumb-item active" aria-current="page">Detail Pengajuan</li>
        </ol>
    </nav>
    <div class="mb-4">
        <h3 class="mb-1">Pengajuan #{{ $pegawaiChangeRequest->id }}</h3>
        <p class="text-muted mb-0">
            {{ $pegawaiChangeRequest->pegawai->nama }} · {{ str_replace('_', ' ', ucfirst($pegawaiChangeRequest->status)) }}
        </p>
    </div>

    @foreach ($pegawaiChangeRequest->items as $item)
        @php($definition = config('employee_profile_changes.fields.'.$item->field, ['label' => $item->field]))
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h5>{{ $definition['label'] }} <small class="text-muted">({{ ucfirst($item->status) }})</small></h5>
                <dl class="row mb-0">
                    <dt class="col-sm-3">Nilai sebelumnya</dt><dd class="col-sm-9">{{ $item->old_value ?? '—' }}</dd>
                    <dt class="col-sm-3">Nilai diajukan</dt><dd class="col-sm-9">{{ $item->new_value ?? '—' }}</dd>
                    @if ($item->verifier)
                        <dt class="col-sm-3">Operator</dt><dd class="col-sm-9">{{ $item->verifier->name }}</dd>
                    @endif
                    @if ($item->verified_at)
                        <dt class="col-sm-3">Waktu keputusan</dt><dd class="col-sm-9">{{ $item->verified_at->format('d-m-Y H:i') }}</dd>
                    @endif
                    @if ($item->rejection_reason)
                        <dt class="col-sm-3">Alasan penolakan</dt><dd class="col-sm-9">{{ $item->rejection_reason }}</dd>
                    @endif
                    @if ($item->evidence_path)
                        <dt class="col-sm-3">Dokumen bukti</dt>
                        <dd class="col-sm-9"><a href="{{ route('pegawai-changes.evidence', $item) }}">Unduh bukti privat</a></dd>
                    @endif
                </dl>
                @if ($item->decisions->isNotEmpty())
                    <h6 class="mt-3">Riwayat keputusan</h6>
                    <ul class="mb-0">
                        @foreach ($item->decisions as $decision)
                            <li>
                                {{ ucfirst($decision->action) }} oleh {{ $decision->actor?->name ?? 'akun yang sudah tidak tersedia' }}
                                pada {{ $decision->acted_at->format('d-m-Y H:i') }}
                                @if ($decision->reason) — {{ $decision->reason }} @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endforeach

    <a href="{{ route('superadmin.pegawai-changes.index') }}" class="btn btn-outline-secondary">Kembali ke Monitoring</a>
</div>
@endsection
