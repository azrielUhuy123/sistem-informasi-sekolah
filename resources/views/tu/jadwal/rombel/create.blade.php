@extends('layouts.tu')

@section('title', 'Tambah Jadwal Rombel')

@section('content')

<div class="container-fluid">

    <form action="{{ route('tu.jadwal.simpan.semua', $grup->id) }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-body">

                <h5 class="fw-bold">Tambah Jadwal</h5>
                <small class="text-muted">
                    Kelas {{ $grup->kelasMaster->tingkat }} -
                    {{ $grup->tahunAjaran->tahun }} ({{ $grup->tahunAjaran->semester }})
                </small>

                {{-- PILIH ROMBEL --}}
                <div class="mt-3 d-flex gap-2">
                    <select id="rombelSelect" class="form-control">
                        <option value="">-- Pilih Rombel --</option>
                        @foreach($rombelOptions as $r)
                            <option value="{{ $r->id }}">
                                {{ $r->nama_kelas }}
                            </option>
                        @endforeach
                    </select>

                    <button type="button" class="btn btn-primary" onclick="tambahCard()">
                        PILIH
                    </button>
                </div>

            </div>
        </div>

        {{-- CONTAINER CARD --}}
        <div id="jadwalContainer" class="mt-3"></div>

        {{-- BUTTON SIMPAN --}}
        <div class="mt-3">
            <button type="submit" class="btn btn-success">
                Simpan Semua Jadwal
            </button>
        </div>

    </form>

</div>

@endsection

<script>
let mapelData = @json($mapel);
let guruData = @json($guru);

function tambahCard() {
    let select = document.getElementById('rombelSelect');
    let rombelId = select.value;
    let rombelText = select.options[select.selectedIndex].text;

    if (!rombelId) {
        alert('Pilih rombel dulu!');
        return;
    }

    let container = document.getElementById('jadwalContainer');

    let mapelOptions = '<option value="">-- Pilih Mapel --</option>';
    mapelData.forEach(m => {
        mapelOptions += `<option value="${m.id}">${m.nama_mapel}</option>`;
    });

    let guruOptions = '<option value="">-- Pilih Guru --</option>';
    guruData.forEach(g => {
        guruOptions += `<option value="${g.id}">${g.nama}</option>`;
    });

    let html = `
        <div class="card mb-3">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <h6 class="fw-bold">Rombel ${rombelText}</h6>
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.card').remove()">
                        Hapus
                    </button>
                </div>

                <input type="hidden" name="rombel[]" value="${rombelId}">

                <div class="row mt-2">

                    <div class="col-md-3">
                        <label>Jam Mulai</label>
                        <input type="time" class="form-control mb-2" name="jam_mulai[]" required>

                        <label>Jam Selesai</label>
                        <input type="time" class="form-control" name="jam_selesai[]" required>
                    </div>

                    <div class="col-md-4">
                        <label>Mapel</label>
                        <select class="form-control" name="mapel_id[]" required>
                            ${mapelOptions}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Guru</label>
                        <select class="form-control" name="guru_id[]" required>
                            ${guruOptions}
                        </select>
                    </div>

                </div>

            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}
</script>
