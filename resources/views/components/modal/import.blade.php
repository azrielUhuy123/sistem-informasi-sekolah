<div
    class="modal fade"
    id="{{ $id }}"
    tabindex="-1"
    aria-labelledby="{{ $id }}Label"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4">

                <div>
                    <h5
                        class="modal-title fw-bold"
                        id="{{ $id }}Label"
                    >
                        {{ $title }}
                    </h5>

                    <small class="text-muted">
                        {{ $subtitle ?? 'Import data secara massal menggunakan file Excel atau CSV.' }}
                    </small>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>

            </div>


            {{-- FORM --}}
            <form
                action="{{ $action }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="modal-body px-4">

                    {{-- UPLOAD AREA --}}
                    <div class="import-dropzone">

                        <input
                            type="file"
                            id="{{ $id }}File"
                            name="{{ $name ?? 'file' }}"
                            accept="{{ $accept ?? '.xlsx,.xls,.csv' }}"
                            hidden
                            required
                        >

                        <label
                            for="{{ $id }}File"
                            class="import-dropzone-label"
                        >

                            <div class="import-icon">
                                <i class="bi bi-file-earmark-spreadsheet"></i>
                            </div>

                            <div class="fw-semibold">
                                Pilih file untuk diimport
                            </div>

                            <small class="text-muted">
                                Klik untuk memilih file
                            </small>

                            <div class="mt-2">

                                <span class="badge bg-light text-secondary">
                                    XLSX
                                </span>

                                <span class="badge bg-light text-secondary">
                                    XLS
                                </span>

                                <span class="badge bg-light text-secondary">
                                    CSV
                                </span>

                            </div>

                        </label>

                    </div>


                    {{-- FILE NAME --}}
                    <div
                        id="{{ $id }}FileName"
                        class="import-file-name small text-muted mt-3"
                    >
                        <i class="bi bi-info-circle me-1"></i>
                        Belum ada file yang dipilih
                    </div>


                    {{-- EXTRA CONTENT --}}
                    @isset($extra)
                        <div class="mt-3">
                            {{ $extra }}
                        </div>
                    @endisset

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-upload me-1"></i>
                        Import Data
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>


<script>
document.getElementById('{{ $id }}File')?.addEventListener('change', function () {

    const output = document.getElementById('{{ $id }}FileName');

    if (this.files.length) {

        output.innerHTML = `
            <i class="bi bi-file-earmark-check text-success me-1"></i>
            ${this.files[0].name}
        `;

    } else {

        output.innerHTML = `
            <i class="bi bi-info-circle me-1"></i>
            Belum ada file yang dipilih
        `;

    }

});
</script>