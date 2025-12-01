@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Preview Dokumen</h4>

        <a href="{{ route('tu.arsip-dokumen.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0" style="height: 85vh;">
            
            <iframe 
                src="{{ $fileUrl }}" 
                frameborder="0" 
                style="width: 100%; height: 100%;"
            ></iframe>

        </div>
    </div>

</div>
@endsection
