<section>

    <div class="d-flex align-items-center mb-4">

        <div class="section-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <div class="ms-3">

            <h5 class="fw-bold mb-1">
                Ubah Password
            </h5>

            <small class="text-muted">
                Gunakan password yang kuat agar akun tetap aman.
            </small>

        </div>

    </div>

    <form method="POST"
          action="{{ route('password.update') }}">

        @csrf
        @method('PUT')

        <div class="row g-4">

            <div class="col-md-12">

                <label class="form-label fw-semibold">
                    Password Saat Ini
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>

                    <input
                        type="password"
                        name="current_password"
                        id="update_password_current_password"
                        class="form-control"
                        autocomplete="current-password">

                </div>

                @error('current_password','updatePassword')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Password Baru
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-key-fill"></i>
                    </span>

                    <input
                        type="password"
                        name="password"
                        id="update_password_password"
                        class="form-control"
                        autocomplete="new-password">

                </div>

                @error('password','updatePassword')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Konfirmasi Password Baru
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>

                    <input
                        type="password"
                        name="password_confirmation"
                        id="update_password_password_confirmation"
                        class="form-control"
                        autocomplete="new-password">

                </div>

            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                @if(session('status') === 'password-updated')

                    <span class="badge bg-success rounded-pill px-3 py-2">

                        <i class="bi bi-check-circle-fill me-1"></i>

                        Password berhasil diperbarui

                    </span>

                @endif

            </div>

            <button class="btn btn-primary rounded-pill px-4">

                <i class="bi bi-shield-lock-fill me-2"></i>

                Simpan Password

            </button>

        </div>

    </form>

</section>

<style>
.section-icon{
    width:52px;
    height:52px;
    border-radius:14px;
    background:#eff6ff;
    color:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
}

.input-group-text{
    background:#f8fafc;
    border-color:#dee2e6;
    color:#2563eb;
}

.form-control{
    border-color:#dee2e6;
    padding:.7rem .9rem;
}

.form-control:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 .15rem rgba(37,99,235,.15);
}

.btn-primary{
    border-radius:999px;
    font-weight:600;
    padding:.65rem 1.5rem;
}

.badge{
    font-size:.85rem;
}
</style>