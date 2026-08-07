@extends('layouts.app')

@section('title', 'Penempatan Kelas')

@section('content')

<div class="container-fluid">
{{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Kelola Penempatan Kelas Siswa baru</h3>
                        <p class="mb-0 opacity-75">Kelola data penempatan kelas siswa baru</p>
                    </div>
                    <div class="badge bg-white text-primary px-3 py-2 fs-6">
                        <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                    </div>
                </div>
            </div>
        </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- CARD --}}
    <x-table.card
        title="Penempatan Kelas Siswa Baru"
        subtitle="Kelola data penempatan kelas siswa baru pada SDN Mekar Jaya."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $data->total() }} Siswa belum ditempatkan
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <div class="d-flex flex-column align-items-end gap-2">
                <input
                    type="text"
                    id="searchInput"
                    class="form-control rounded-3"
                    placeholder="Cari Siswa..."
                    style="width: 320px;"
                >
            </div>

        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="180">No Pendaftaran</th>
                        <th>Nama</th>
                        <th width="500">Penempatan</th>                        
                    </tr>
                </thead>
                <tbody id="tablePenempatanKelas">
                    @forelse ($data as $item)
                    <tr>
                        <td>
                            <span>
                                {{ $item->no_pendaftaran }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-person-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-lowercase">
                                        {{ $item->nama }}
                                    </div>
                                    <small class="text-muted">
                                       NIS : {{ $item->siswa->nis }}
                                    </small>
                                </div>
                            </div>                            
                        </td>                        
                        <td>
                            <form method="POST" action="{{ route('tu.ppdb.penempatan-kelas.simpan', $item) }}">
                                @csrf
                                <div class="d-flex gap-2">
                                    <select name="kelas_id" class="form-select rounded-3" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->nama_kelas_label }} ({{ $k->jumlah_siswa }} Siswa)
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary rounded-3 btn-sm">Tempatkan</button>
                                </div>
                            </form>
                        </td>                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <i class="bi bi-check-circle display-6 text-success"></i>
                            <p class="mt-3 mb-0">Semua siswa sudah mendapatkan kelas.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>   
            <div class="mt-4 d-flex justify-content-end">
                {{ $data->links() }} 
            </div>
        </div>
    </x-table.card>
</div>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tablePenempatanKelas tr'
            );

        rows.forEach(row => {

            const text =
                row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword)
                ? ''
                : 'none';

        });
    });

</script>
@endsection