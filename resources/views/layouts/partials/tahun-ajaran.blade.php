@php
    $tahun = current_tahun();
@endphp

<div class="px-3 mb-3">

    <div class=" border rounded-3 p-2 d-flex flex-column align-items-center justify-content-center gap-1">
 
        <div class="small text-muted mb-1">
            Tahun Ajaran
        </div>

        <div class="fw-semibold">
            {{ $tahun?->tahun }}
        </div>

        <div class="small text-muted">
            Semester {{ $tahun?->semester }}
        </div>

    </div>
    
</div>
