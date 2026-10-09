@extends('layouts.app')

@section('title', 'Data Pendaftar Diterima')

@section('content')

<div class="container-fluid">
{{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Daftar Pendaftar Diterima</h3>
                        <p class="mb-0 opacity-75">Kelola data pendaftar yang diterima.</p>
                    </div>
                    <div class="badge bg-white text-primary px-3 py-2 fs-6">
                        <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                    </div>
                </div>
            </div>
        </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- CARD --}}
    <x-table.card
        title="Daftar Pendaftar Diterima"
        subtitle="Seluruh pendaftar peserta didik baru yang diterima pada SDN Mekar Jaya."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $data->count() }} <span class="text-badge">Pendaftar</span>
            </span>
        </x-slot:badge>

        <x-slot:actions> 
            <div class="d-flex flex-column align-items-center gap-2">                       
                <input
                    type="text"
                    id="searchInput"
                    class="form-control akun-filter-search table-search"
                    placeholder="Cari Pendaftar..."
                >                             
                <div class="d-flex align-items-center gap-2">
                    <form method="GET" action="{{ route('tu.ppdb.diterima.index') }}" class="akun-filter-form">
                        <select name="tahun_ajaran_id" class="form-select akun-filter-status" aria-label="Tahun Ajaran">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach($tahunAjaran as $tahun)
                                <option value="{{ $tahun->id }}" @selected((string) $tahunAjaranId === (string) $tahun->id)>
                                    {{ $tahun->tahun }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </form>

                    @if($tahunAjaranId)
                        <a href="{{ route('tu.ppdb.diterima.export', ['tahun_ajaran_id' => $tahunAjaranId]) }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel me-1"></i>
                            Export Excel
                        </a>
                    @else                    
                        <button type="button" class="btn btn-success" disabled>
                            <i class="bi bi-file-earmark-excel me-1"></i>
                            Export Excel
                        </button>                                            
                    @endif
                </div>
            </div>  
        </x-slot:actions>
        

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="180">No Pendaftaran</th>
                        <th>Nama</th>
                        <th width="150">Jenis Kelamin</th>                        
                        <th style="min-width: 280px;">Email Pendaftar</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tablePPDB">
                    @foreach($data as $item)
                    <tr>
                        <td>
                            <span>
                                {{ $item->no_pendaftaran }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-person-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $item->nama }}
                                    </div>                                   
                                </div>
                            </div>
                            
                        </td>
                        
                        <td>
                            @if($item->jenis_kelamin == 'L')                    
                                <span class="badge bg-indigo rounded-pill px-2 py-1">
                                    <i class="bi bi-gender-male me-1"></i>
                                    Laki-Laki
                                </span>
                            @else
                                <span class="badge bg-rose rounded-pill px-2 py-1">
                                    <i class="bi bi-gender-female me-1"></i>
                                    Perempuan
                                </span>
                            @endif
                        </td>         
                        <td style="min-width: 280px;">
                            <div class="d-flex flex-column align-items-start gap-2">                                
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm resend-email-toggle"
                                        data-pendaftar-id="{{ $item->id }}">
                                    <i class="bi bi-envelope-arrow-up me-1"></i>
                                    Kirim Ulang Email
                                </button>
                                <form class="d-none resend-email-form w-100"
                                      data-url="{{ route('tu.ppdb.diterima.resend-email', $item) }}">
                                    @csrf
                                    <div class="d-flex flex-column flex-sm-row align-items-sm-start gap-2 w-100">
                                        <input type="email"
                                               name="email"
                                               class="form-control form-control-sm flex-grow-1"
                                               value="{{ $item->email }}"
                                               maxlength="255"
                                               required
                                               aria-label="Alamat email pendaftar">
                                        <button type="submit" class="btn btn-success btn-sm flex-shrink-0 resend-email-submit">
                                            Kirim
                                        </button>
                                    </div>
                                    <small class="resend-email-feedback d-none" role="status" aria-live="polite"></small>
                                </form>
                                <small class="resend-email-row-feedback d-none text-success" role="status" aria-live="polite">
                                    <span>Email berhasil dikirim</span>
                                    <span class="d-block">Kirim ulang tersedia dalam <span class="resend-email-countdown">30</span> detik</span>
                                </small>
                            </div>
                        </td>                      
                        <td>
                            <a href="{{ route('tu.ppdb.pendaftar.show', $item) }}"
                            class="btn btn-primary btn-sm rounded-pill px-3 py-0">
                                Detail
                                <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>   
            <div class="mt-4 d-flex justify-content-end">
                {{ $data->links() }} 
            </div>
        </div>
    </x-table.card>
</div>

{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tablePPDB tr'
            );

        rows.forEach(row => {

            const text =
                row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword)
                ? ''
                : 'none';

        });
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        document.querySelectorAll('.resend-email-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const row = toggle.closest('tr');
                const form = row?.querySelector('.resend-email-form');

                if (!form) return;

                toggle.classList.add('d-none');
                form.classList.remove('d-none');
                form.querySelector('input[name="email"]')?.focus();
            });
        });

        document.querySelectorAll('.resend-email-form').forEach(function (form) {
            form.addEventListener('submit', async function (event) {
                event.preventDefault();

                const row = form.closest('tr');
                const toggle = row?.querySelector('.resend-email-toggle');
                const input = form.querySelector('input[name="email"]');
                const submitButton = form.querySelector('.resend-email-submit');
                const feedback = form.querySelector('.resend-email-feedback');
                const rowFeedback = row?.querySelector('.resend-email-row-feedback');

                if (!input || !submitButton || !feedback) return;

                submitButton.disabled = true;
                feedback.classList.remove('d-none', 'text-success', 'text-danger');
                feedback.textContent = '';
                rowFeedback?.classList.add('d-none');

                try {
                    const response = await fetch(form.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ email: input.value }),
                    });
                    const data = await response.json();

                    if (response.ok && data.success) {
                        const emailDisplay = row?.querySelector('[data-email-display]');
                        if (emailDisplay) emailDisplay.textContent = input.value;
                        form.classList.add('d-none');

                        const countdown = rowFeedback?.querySelector('.resend-email-countdown');
                        if (toggle && rowFeedback && countdown) {
                            let secondsRemaining = 30;

                            toggle.classList.add('d-none');
                            rowFeedback.classList.remove('d-none');
                            countdown.textContent = secondsRemaining;

                            const cooldownTimer = window.setInterval(function () {
                                secondsRemaining -= 1;

                                if (secondsRemaining <= 0) {
                                    window.clearInterval(cooldownTimer);
                                    rowFeedback.classList.add('d-none');
                                    toggle.classList.remove('d-none');
                                    return;
                                }

                                countdown.textContent = secondsRemaining;
                            }, 1000);
                        }
                    } else {
                        feedback.classList.add('text-danger');
                        feedback.textContent = data.errors?.email?.[0]
                            || data.message
                            || 'Email gagal dikirim.';
                    }
                } catch (error) {
                    feedback.classList.add('text-danger');
                    feedback.textContent = 'Email gagal dikirim. Periksa koneksi lalu coba lagi.';
                } finally {
                    submitButton.disabled = false;
                }
            });
        });
    });
</script>
@endsection