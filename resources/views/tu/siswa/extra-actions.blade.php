
<form action="{{ route('tu.siswa.import') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    <label class="btn btn-outline-success btn-sm mb-0">
        <i class="bi bi-file-earmark-excel"></i> Import Excel
        <input type="file"
               name="file"
               accept=".xlsx,.xls,.csv"
               hidden
               onchange="this.form.submit()">
    </label>
</form>

<a href="{{ route('tu.penempatan-siswa.preview-naik-kelas') }}"
   class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-eye"></i> Preview Naik Kelas
</a>

<form action="{{ route('tu.penempatan-siswa.naik-kelas') }}"
      method="POST"
      onsubmit="return confirm('Yakin naikkan kelas semua siswa?')">
    @csrf
    <button class="btn btn-warning btn-sm">
        <i class="bi bi-arrow-up-circle"></i> Naik Kelas Otomatis
    </button>
</form>
