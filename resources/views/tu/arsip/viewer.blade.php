@extends('layouts.tu')

@section('title', 'Preview Dokumen')

@section('content')

<div style="height: 90vh;">
    <iframe 
        src="/pdfjs/web/viewer.html?file={{ $pdfPath }}"
        style="width: 100%; height: 100%; border:none;">
    </iframe>
</div>

@endsection
