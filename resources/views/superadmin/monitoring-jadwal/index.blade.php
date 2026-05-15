@extends('layouts.app')

@section('title', 'Jadwal Kelas')

@section('content')
@php
    use App\Models\PengajuanJadwal;

    $tahunId = current_tahun_id();

    $submittedCount = PengajuanJadwal::where('tahun_ajaran_id', $tahunId)
        ->where('status', 'submitted')
        ->count();

    $approvedCount = PengajuanJadwal::where('tahun_ajaran_id', $tahunId)
        ->where('status', 'approved')
        ->count();

    $rejectedCount = PengajuanJadwal::where('tahun_ajaran_id', $tahunId)
        ->where('status', 'rejected')
        ->count();

    $publishedCount = PengajuanJadwal::where('tahun_ajaran_id', $tahunId)
        ->where('status', 'published')
        ->count();
@endphp

<div class="container-fluid">

    <h2 class="mb-4">
        Monitoring Jadwal
    </h2>

    <div class="row mb-4 g-3">

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Submitted</h6>
                    <h3 class="text-warning">
                        {{ $submittedCount }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Approved</h6>
                    <h3 class="text-success">
                        {{ $approvedCount }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Rejected</h6>
                    <h3 class="text-danger">
                        {{ $rejectedCount }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6>Published</h6>
                    <h3 class="text-primary">
                        {{ $publishedCount }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    @php
        use Illuminate\Support\Str;
    @endphp

    <div class="accordion" id="accordionTingkat">

    @foreach($kelasPerTingkat as $tingkat => $kelasList)

        @php
            $slug = Str::slug((string) $tingkat);

            $collapseId = "tingkat-$slug";
            $headingId = "heading-$slug";
        @endphp

        <div class="accordion-item mb-3 border-0 shadow-sm">

            <h2 class="accordion-header" id="{{ $headingId }}">

                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="false"
                    aria-controls="{{ $collapseId }}"
                >
                    Kelas Tingkat {{ $tingkat }}
                </button>

            </h2>

            <div
                id="{{ $collapseId }}"
                class="accordion-collapse collapse"
                aria-labelledby="{{ $headingId }}"
                data-bs-parent="#accordionTingkat"
            >

                <div class="accordion-body">

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Total Jadwal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                            @forelse($kelasList as $itemKelas)

                                @php
                                    $submitted = $itemKelas->pengajuanJadwal
                                        ->where('status', 'submitted')
                                        ->count();

                                    $approved = $itemKelas->pengajuanJadwal
                                        ->where('status', 'approved')
                                        ->count();

                                    $rejected = $itemKelas->pengajuanJadwal
                                        ->where('status', 'rejected')
                                        ->count();

                                    $published = $itemKelas->pengajuanJadwal
                                        ->where('status', 'published')
                                        ->count();

                                    $total = $itemKelas->pengajuanJadwal->count();

                                    if ($published > 0) {
                                        $kelasStatus = 'published';
                                    } elseif ($submitted > 0) {
                                        $kelasStatus = 'submitted';
                                    } elseif ($rejected > 0) {
                                        $kelasStatus = 'rejected';
                                    } elseif ($approved > 0) {
                                        $kelasStatus = 'approved';
                                    } else {
                                        $kelasStatus = 'draft';
                                    }
                                @endphp

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $itemKelas->nama_kelas }}
                                        </strong>
                                    </td>

                                    <td>

                                        <span class="
                                            badge

                                            @if($kelasStatus == 'published')
                                                bg-primary
                                            @elseif($kelasStatus == 'submitted')
                                                bg-warning
                                            @elseif($kelasStatus == 'approved')
                                                bg-success
                                            @elseif($kelasStatus == 'rejected')
                                                bg-danger
                                            @else
                                                bg-secondary
                                            @endif
                                        ">
                                            {{ ucfirst($kelasStatus) }}
                                        </span>

                                    </td>

                                    <td>
                                        {{ $total }}
                                    </td>

                                    <td>

                                        <a
                                            href="{{ route('superadmin.monitoring-jadwal.show', $itemKelas->id) }}"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Lihat
                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Tidak ada data kelas
                                    </td>
                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

    </div>

</div>
@endsection