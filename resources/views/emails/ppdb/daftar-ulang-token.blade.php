<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tautan Daftar Ulang PPDB</title>
</head>
<body style="margin:0;padding:24px;background:#f5f7fa;font-family:Arial,Helvetica,sans-serif;color:#212529">
    <main style="max-width:600px;margin:24px auto;padding:28px;background:#fff;border:1px solid #e5e7eb;border-radius:8px">
        <h1 style="font-size:22px;margin:0 0 20px">Konfirmasi Daftar Ulang</h1>
        <p>Yth. {{ $pendaftar->nama }},</p>
        <p>Gunakan tautan berikut untuk menyelesaikan daftar ulang. Tautan hanya berlaku 30 menit dan hanya dapat digunakan sekali.</p>
        <p style="text-align:center;margin:28px 0">
            <a href="{{ $actionUrl }}" style="display:inline-block;padding:12px 20px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:6px">
                Konfirmasi Daftar Ulang
            </a>
        </p>
        <p>Jika Anda tidak meminta daftar ulang, abaikan email ini.</p>
    </main>
</body>
</html>
