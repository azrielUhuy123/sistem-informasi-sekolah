@extends('layouts.public')

@section('content')

<div class="container py-5">

    <div class="card">

        <div class="card-body">

            <h3>Daftar Ulang</h3>

            <form
                method="POST"
                action="{{ route('ppdb.daftar-ulang.simpan') }}"
            >
                @csrf

                <input
                    type="text"
                    name="no_pendaftaran"
                    class="form-control mb-3"
                    placeholder="Nomor Pendaftaran"
                >

                <button
                    class="btn btn-primary"
                >
                    Daftar Ulang
                </button>

            </form>

        </div>

    </div>

</div>

@endsection