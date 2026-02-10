<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>NIS</th>
            <th>Rombel</th>
            <th>Angkatan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($siswa as $row)
            <tr>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->nis }}</td>
                <td>
                    <span class="badge bg-primary">
                        {{ $row->kelas->nama_kelas }}
                    </span>
                </td>
                <td>
                    {{ $row->kelas->kelasGrup->angkatan->tahun ?? '-' }}
                </td>
                <td>
                    <span class="badge bg-success">Aktif</span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Tidak ada data
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
