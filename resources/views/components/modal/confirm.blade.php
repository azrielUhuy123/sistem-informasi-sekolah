<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-body text-center p-5">

                <div class="mb-4">
                    <div id="confirmIcon"
                        class="bg-warning border bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-1"></i>
                    </div>
                </div>

                <h4 id="confirmTitle" class="fw-bold mb-2">
                    Konfirmasi
                </h4>

                <p id="confirmMessage" class="text-muted mb-4">
                    Apakah Anda yakin?
                </p>

                <div class="d-flex justify-content-center gap-2">

                    <button
                        class="btn btn-light px-4"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
                        id="confirmSubmit"
                        class="btn btn-danger px-4">
                        Ya, Lanjutkan
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>