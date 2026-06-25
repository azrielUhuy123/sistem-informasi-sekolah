@extends('layouts.app')

@section('title', 'Monitoring Ekskul')

@section('content')

<div class="container-fluid py-4">

    <h3 class="fw-bold mb-4">

        Monitoring Ekskul

    </h3>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Ekskul</th>

                        <th>Pembina</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($ekskul as $e)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                {{ $e->nama }}

                            </td>

                            <td>

                                {{ $e->pembina->guru->pegawai->nama ?? '-' }}

                            </td>

                            <td>

                                <a
                                    href="{{ route('superadmin.monitoring-ekskul.show',$e->id) }}"
                                    class="btn btn-dark btn-sm"
                                >

                                    Monitoring

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection