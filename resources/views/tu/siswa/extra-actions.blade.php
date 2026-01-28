<div class="d-flex gap-2 flex-wrap">

    {{-- =====================================================
         IMPORT EXCEL (WAJIB ANGKATAN & KELAS)
    ====================================================== --}}
    <form
        action="{{ route('tu.siswa.import') }}"
        method="POST"
        enctype="multipart/form-data"
        class="d-inline"
    >
        @csrf

        {{-- konteks wajib --}}
        <input type="hidden" name="angkatan" value="{{ $angkatan }}">
        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

        @php
            $importDisabled = empty($angkatan) || empty($kelasId);
        @endphp

        <label
            class="btn btn-outline-success btn-sm mb-0 {{ $importDisabled ? 'disabled' : '' }}"
            title="{{ $importDisabled ? 'Pilih angkatan dan kelas terlebih dahulu' : '' }}"
        >
            <i class="bi bi-file-earmark-excel"></i> Import Excel
            <input
                type="file"
                name="file"
                accept=".xlsx,.xls,.csv"
                hidden
                {{ $importDisabled ? 'disabled' : '' }}
                onchange="this.form.submit()"
            >
        </label>
    </form>

    {{-- =====================================================
         PREVIEW NAIK KELAS
    ====================================================== --}}
    <a
        href="{{ $angkatan
            ? route('tu.penempatan-siswa.preview-naik-kelas', ['angkatan' => $angkatan])
            : '#'
        }}"
        class="btn btn-outline-secondary btn-sm {{ !$angkatan ? 'disabled' : '' }}"
        title="{{ !$angkatan ? 'Pilih angkatan terlebih dahulu' : '' }}"
    >
        <i class="bi bi-eye"></i> Preview Naik Kelas
    </a>

    {{-- =====================================================
         NAIK KELAS OTOMATIS
    ====================================================== --}}
    <form
        action="{{ route('tu.penempatan-siswa.naik-kelas') }}"
        method="POST"
        class="d-inline"
        onsubmit="
            return {{ $angkatan
                ? 'confirm(\'Yakin naikkan kelas semua siswa angkatan ' . $angkatan . '?\')'
                : 'false'
            }};
        "
    >
        @csrf

        <input type="hidden" name="angkatan" value="{{ $angkatan }}">

        <button
            type="submit"
            class="btn btn-warning btn-sm"
            {{ !$angkatan ? 'disabled' : '' }}
            title="{{ !$angkatan ? 'Pilih angkatan terlebih dahulu' : '' }}"
        >
            <i class="bi bi-arrow-up-circle"></i> Naik Kelas Otomatis
        </button>
    </form>

</div>
