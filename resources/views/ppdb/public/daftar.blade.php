<form
    action="{{ route('ppdb.store') }}"
    method="POST"
    enctype="multipart/form-data"
>

@csrf

<div class="card">

<div class="card-body">

<h4>Data Pribadi</h4>

<input
    type="text"
    name="nama"
    class="form-control mb-3"
    placeholder="Nama Lengkap"
>

<input
    type="text"
    name="nik"
    class="form-control mb-3"
    placeholder="NIK"
>

<input
    type="text"
    name="nomor_kk"
    class="form-control mb-3"
    placeholder="Nomor KK"
>

<button
    class="btn btn-primary"
>

Daftar

</button>

</div>

</div>

</form>