@extends('layouts.app')

@section('title', 'Ajukan Perubahan Data Pegawai')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h3 class="mb-1">Ajukan Perubahan Data Pegawai</h3>
        <p class="text-muted mb-0">
            Pilih perubahan yang diperlukan. Perubahan data resmi baru berlaku setelah diverifikasi Operator aktif.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.pegawai-changes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($retryItem)
            <input type="hidden" name="resubmits[{{ $retryItem->field }}]" value="{{ $retryItem->id }}">
        @endif

        @foreach ($fields as $field => $definition)
            @php
                $current = $employee->{$field};
                $current = $current instanceof \Carbon\CarbonInterface ? $current->format('Y-m-d') : $current;
                $default = $retryItem?->field === $field ? $retryItem->new_value : $current;
                $value = old("changes.$field", $default);
            @endphp
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-start">
                        <div class="col-lg-3">
                            <strong>{{ $definition['label'] }}</strong>
                            <div class="small text-muted">{{ $definition['category'] }}</div>
                            <div class="small mt-2">Data saat ini: {{ $current ?? '—' }}</div>
                            @if ($retryItem?->field === $field)
                                <span class="badge text-bg-danger mt-2">Perbaiki pengajuan ditolak</span>
                            @endif
                        </div>
                        <div class="col-lg-5">
                            <label for="change-{{ $field }}" class="form-label">Nilai yang diajukan</label>
                            @if ($definition['type'] === 'select')
                                <select id="change-{{ $field }}" name="changes[{{ $field }}]" class="form-select">
                                    <option value="">-- Tidak diubah --</option>
                                    @foreach ($definition['options'] as $optionValue => $optionLabel)
                                        @php($optionValue = is_int($optionValue) ? $optionLabel : $optionValue)
                                        <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>
                                            {{ $optionLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input id="change-{{ $field }}" name="changes[{{ $field }}]"
                                    type="{{ $definition['type'] === 'number' ? 'number' : $definition['type'] }}"
                                    class="form-control" value="{{ $value }}"
                                    @if ($definition['type'] === 'number') min="0" @endif
                                    @if (isset($definition['max'])) maxlength="{{ $definition['max'] }}" @endif>
                            @endif
                            @if ($definition['evidence'])
                                <label for="evidence-{{ $field }}" class="form-label mt-3">Dokumen bukti (PDF/JPG/PNG, maks. 5 MB)</label>
                                <input id="evidence-{{ $field }}" type="file" name="evidence[{{ $field }}]"
                                    class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-between">
            <a href="{{ route('guru.pegawai-changes.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
        </div>
    </form>
</div>
@endsection
