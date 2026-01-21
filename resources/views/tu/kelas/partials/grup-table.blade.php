<div class="card shadow-sm">
    <div class="card-header fw-bold">
        Daftar Grup Kelas
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tingkat</th>
                    <th>Tahun Ajaran</th>
                    <th>Jumlah Rombel</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($grupKelas as $grup)
                    <tr>
                        <td>
                            <span class="badge bg-primary">
                                Kelas {{ $grup->tingkat }}
                            </span>
                        </td>

                        <td>{{ $grup->tahun_ajaran }}</td>

                        <td>
                            {{ $grup->total_rombel }} Rombel
                        </td>

                        <td>
                            <a href="{{ route('tu.kelas.rombel.index', [
                                'kelasMasterId' => $grup->kelas_master_id,
                                'tahunAjaranId' => $grup->tahun_ajaran_id
                            ]) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Masuk
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Belum ada data kelas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
