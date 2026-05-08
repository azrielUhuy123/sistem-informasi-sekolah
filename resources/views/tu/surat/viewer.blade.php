@extends('layouts.app')

@section('title', 'Preview Dokumen')

@section('content')

<h1 class="mb-4">Preview Dokumen</h1>

<div class="card">
    <div class="card-body">
        <iframe 
            src="{{ asset($pdfPath) }}" 
            style="width:100%; height:800px; border:none;"
        >
        </iframe>
    </div>
</div>

@endsection
