@extends('layouts.app')

@section('title', 'Nomor Surat')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-envelope"
        eyebrow="Administrasi / Nomor Surat"
        title="Data Nomor Surat"
        description="Kelola data nomor surat sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- CARD --}}
    {{-- ================= TABLE ================= --}}
    <x-table.card
        title="Riwayat Nomor Surat"
        subtitle="Seluruh nomor surat yang pernah dibuat."
        :count="$nomorSurat->total()"
        count-label="data"
    >

        <x-slot:pageActions>
           <form action="{{ route('tu.nomor-surat.generate') }}" method="POST">
                @csrf
                <button class="btn btn-primary rounded-3">
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Generate Nomor
                </button>
            </form>
        </x-slot:pageActions>

        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="70px" class="text-center">No</x-table.heading>
                        <x-table.heading>Nomor Surat</x-table.heading>
                        <x-table.heading width="220px">Tanggal Dibuat</x-table.heading>
                        <x-table.heading width="170px">Status</x-table.heading>
                    </tr>
                </thead>

                <tbody>
                    @forelse($nomorSurat as $item)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark rounded-circle">{{ $nomorSurat->firstItem() + $loop->index }}</span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">                                
                                <div>
                                    <div class="fw-semibold">{{ $item->nomor_surat }}</div>
                                    <small class="text-muted">Nomor Surat Resmi</small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $item->created_at->translatedFormat('d F Y') }}</div>
                            <small class="text-muted">{{ $item->created_at->format('H:i') }} WIB</small>
                        </td>

                        <td>
                            <span class="badge-status aktif">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Digunakan
                            </span>
                        </td>
                    </tr>
 
                    @empty
                        <x-table.empty
                            :colspan="4"
                            title="Belum ada data nomor surat"
                            description="Nomor surat yang pernah dibuat akan ditampilkan di sini."
                        />
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-table.pagination :paginator="$nomorSurat" />
    </x-table.card>
</div>

@endsection