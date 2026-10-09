<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $subject }}</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f5f7fa;
    font-family: Arial, Helvetica, sans-serif;
">

    <div style="
        max-width: 600px;
        margin: 30px auto;
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    ">

        <div style="
            padding: 24px;
            background-color: #0d6efd;
            color: #ffffff;
            text-align: center;
        ">
            <h2 style="margin: 0;">
                SDN Mekar Jaya
            </h2>

            <p style="
                margin: 8px 0 0;
                font-size: 14px;
            ">
                Sistem Penerimaan Peserta Didik Baru
            </p>
        </div>


        <div style="padding: 30px;">

            <h3 style="
                margin-top: 0;
                color: #212529;
            ">
                {{ $subject }}
            </h3>

            <p>
                Yth. <strong>{{ $pendaftar->nama }}</strong>,
            </p>

            @php
                $statusLabel = match ($pendaftar->status) {
                    'verifikasi' => 'Sedang Diverifikasi',
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu Keputusan Admin',
                };
            @endphp

            <p>
                Berikut informasi terbaru mengenai pendaftaran PPDB Anda.
            </p>

            <div style="
                margin: 24px 0;
                padding: 20px;
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 6px;
            ">

                <p style="margin: 0 0 10px;">
                    <strong>Nomor Pendaftaran</strong>
                </p>

                <div style="
                    font-size: 22px;
                    font-weight: bold;
                    color: #0d6efd;
                ">
                    {{ $pendaftar->no_pendaftaran }}
                </div>

                <p style="margin: 18px 0 0;">
                    <strong>Status Pendaftaran</strong>
                </p>
                <div style="
                    margin-top: 6px;
                    font-size: 18px;
                    font-weight: bold;
                    color: #17416e;
                ">
                    {{ $statusLabel }}
                </div>

            </div>

            @if($pendaftar->status === 'pending')
                <p>
                    Pendaftaran Anda masih menunggu keputusan admin.
                </p>
            @elseif($pendaftar->status === 'verifikasi')
                <p>
                    Tindakan yang perlu dilakukan: tunggu hasil verifikasi dari petugas PPDB. Tidak ada tindakan tambahan saat ini.
                </p>
            @elseif($pendaftar->status === 'diterima')
                <p>
                    Tindakan yang perlu dilakukan: lakukan daftar ulang sesuai informasi dan jadwal yang ditetapkan sekolah.
                </p>
            @elseif($pendaftar->status === 'ditolak')
                <p>
                    Tindakan yang perlu dilakukan: hubungi pihak sekolah untuk mendapatkan informasi lebih lanjut mengenai pendaftaran Anda.
                </p>
            @endif

            @if(!empty($statusUrl))
                <div style="text-align: center; margin: 30px 0;">
                    <a
                        href="{{ $statusUrl }}"
                        style="
                            display: inline-block;
                            padding: 12px 24px;
                            background-color: #0d6efd;
                            color: #ffffff;
                            text-decoration: none;
                            border-radius: 6px;
                            font-weight: bold;
                        "
                    >
                        Lihat Status Pendaftaran
                    </a>
                </div>
            @endif

            <p>
                Pastikan alamat email yang Anda gunakan aktif dan dapat diakses untuk menerima pemberitahuan terkait pendaftaran.
            </p>


            <hr style="
                margin: 30px 0;
                border: 0;
                border-top: 1px solid #dee2e6;
            ">


            <p style="
                margin-bottom: 0;
                font-size: 14px;
                color: #6c757d;
            ">
                Email ini dikirim secara otomatis oleh sistem PPDB
                SDN Mekar Jaya. Mohon tidak membalas email ini.
            </p>

        </div>


        <div style="
            padding: 18px 30px;
            background-color: #f8f9fa;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        ">
            &copy; {{ date('Y') }} SDN Mekar Jaya
        </div>

    </div>

</body>
</html>