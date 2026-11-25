@extends('layouts.app')

@section('title', 'Riwayat Perubahan Pegawai')

@section('content')
<div class="container-fluid dt-siswa-page">
    <x-table.breadcrumb
        :items="[
            [
                'label' => 'Kepala Sekolah',                                
            ],
            [
                'label' => 'Data Kepegawaian',
                'url' => route('superadmin.pegawai-changes.employees'),
            ],
            [
                'label' => 'Monitoring Data Pegawai',
                'url' => route('superadmin.pegawai-changes.employees'),
            ],
            [
                'label' => 'Riwayat Perubahan Pegawai',                
            ],
        ]"
    />
    <div class="mb-4">
        <h3 class="mb-1">{{ $pegawai->nama }}</h3>
        <p class="text-muted mb-0">{{ $pegawai->nip ?? 'Tanpa NIP' }} · {{ $pegawai->jabatan ?? 'Jabatan tidak tercatat' }}</p>
    </div>

    @forelse ($pegawai->pegawaiChangeRequests as $changeRequest)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <strong>Pengajuan #{{ $changeRequest->id }}</strong>
                    <span class="badge text-bg-secondary">{{ str_replace('_', ' ', ucfirst($changeRequest->status)) }}</span>
                </div>
                <div class="small text-muted mb-3">
                    {{ $changeRequest->created_at->format('d-m-Y H:i') }}
                    · {{ $changeRequest->submitted_by_name }}
                </div>
                @foreach ($changeRequest->items as $item)
                    @php($definition = config('employee_profile_changes.fields.'.$item->field, ['label' => $item->field]))
                    <div class="border-top py-2">
                        <strong>{{ $definition['label'] }}</strong> · {{ ucfirst($item->status) }}
                        <div class="small">Nilai: {{ $item->old_value ?? '—' }} → {{ $item->new_value ?? '—' }}</div>
                        @if ($item->rejection_reason)
                            <div class="small text-danger">Alasan penolakan: {{ $item->rejection_reason }}</div>
                        @endif
                        @if ($item->evidence_path)
                            <a class="small" href="{{ route('pegawai-changes.evidence', $item) }}">Unduh bukti privat</a>
                        @endif
                    </div>
                @endforeach
                <a href="{{ route('superadmin.pegawai-changes.show', $changeRequest) }}" class="btn btn-sm btn-outline-primary mt-3">
                    Detail riwayat keputusan
                </a>
            </div>
        </div>
    @empty
        <div class="alert alert-info">Belum ada pengajuan perubahan untuk pegawai ini.</div>
    @endforelse

    <a href="{{ route('superadmin.pegawai-changes.employees') }}" class="btn btn-outline-secondary">Kembali ke Monitoring Pegawai</a>
</div>
@endsection
