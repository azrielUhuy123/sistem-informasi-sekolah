<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Perbaikan Berkas Pendaftaran PPDB</title>
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
                Perbaikan Berkas Pendaftaran PPDB
            </h3>

            <p>
                Yth. <strong>{{ $pendaftar->nama }}</strong>,
            </p>

            <p>
                Setelah pemeriksaan berkas pendaftaran, ditemukan bahwa
                beberapa dokumen perlu diperbaiki dan dikirim ulang.
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

            </div>

            <p style="font-weight: bold; color: #dc3545; margin-bottom: 8px;">
                Catatan Petugas:
            </p>
            <p style="margin-top: 0; color: #495057;">
                {{ $pendaftar->catatan_verifikasi }}
            </p>

            <p>
                Mohon perbaiki seluruh berkas sesuai dengan catatan di atas dan
                ajukan ulang melalui halaman perbaikan berkas.
            </p>

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
                        Perbaiki Berkas
                    </a>
                </div>
            @endif

            <p>
                Silakan kunjungi halaman di atas untuk memulai proses perbaikan
                berkas. Jika membutuhkan bantuan, hubungi tim PPDB SDN Mekar Jaya.
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