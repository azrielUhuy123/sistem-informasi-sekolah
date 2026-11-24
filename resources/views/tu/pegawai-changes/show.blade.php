@extends('layouts.app')

@section('title', 'Periksa Pengajuan Perubahan Data')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h3 class="mb-1">Pengajuan #{{ $pegawaiChangeRequest->id }}</h3>
        <p class="text-muted mb-0">
            {{ $pegawaiChangeRequest->pegawai->nama }} · Diajukan {{ $pegawaiChangeRequest->created_at->format('d-m-Y H:i') }}
        </p>
    </div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach ($pegawaiChangeRequest->items as $item)
        @php($definition = config('employee_profile_changes.fields.'.$item->field, ['label' => $item->field]))
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h5>{{ $definition['label'] }}</h5>
                    <span class="badge text-bg-secondary">{{ ucfirst($item->status) }}</span>
                </div>
                <dl class="row">
                    <dt class="col-sm-3">Nilai sebelumnya</dt><dd class="col-sm-9">{{ $item->old_value ?? '—' }}</dd>
                    <dt class="col-sm-3">Nilai diajukan</dt><dd class="col-sm-9">{{ $item->new_value ?? '—' }}</dd>
                    @if ($item->evidence_path)
                        <dt class="col-sm-3">Bukti</dt>
                        <dd class="col-sm-9"><a href="{{ route('pegawai-changes.evidence', $item) }}">Unduh bukti privat</a></dd>
                    @endif
                </dl>
                @if ($item->status === 'pending')
                    <div class="d-flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('tu.pegawai-changes.approve', $item) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Setujui dan Terapkan</button>
                        </form>
                        <form method="POST" action="{{ route('tu.pegawai-changes.reject', $item) }}" class="d-flex flex-grow-1 gap-2">
                            @csrf
                            @method('PATCH')
                            <input name="reason" class="form-control" required maxlength="2000"
                                placeholder="Alasan penolakan">
                            <button type="submit" class="btn btn-outline-danger">Tolak</button>
                        </form>
                    </div>
                @elseif ($item->rejection_reason)
                    <div class="alert alert-warning mb-0">Alasan penolakan: {{ $item->rejection_reason }}</div>
                @endif
            </div>
        </div>
    @endforeach

    <a href="{{ route('tu.pegawai-changes.index') }}" class="btn btn-outline-secondary">Kembali ke Antrean</a>
</div>
@endsection
