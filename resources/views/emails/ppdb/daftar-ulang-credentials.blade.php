<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ulang Berhasil - Informasi Akun Siswa</title>
</head>
<body style="margin:0;padding:24px;background:#f5f7fa;font-family:Arial,Helvetica,sans-serif;color:#212529">
    <main style="max-width:600px;margin:0 auto;padding:28px;background:#fff;border:1px solid #e5e7eb;border-radius:8px">
        <h1 style="font-size:22px;margin:0 0 20px">Daftar ulang berhasil</h1>
        <p>Yth. {{ $studentName }},</p>
        <p>Akun siswa Anda telah dibuat. Gunakan informasi berikut untuk login:</p>
        <p><strong>Username / NIS:</strong> {{ $nis }}</p>
        <p>Untuk mengaktifkan akun dan membuat password baru, gunakan tautan satu kali berikut:</p>
        <p style="text-align:center;margin:28px 0">
            <a href="{{ $resetUrl }}" style="display:inline-block;padding:12px 20px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:6px">
                Aktivasi Akun Siswa
            </a>
        </p>
        <p>Email ini hanya berisi informasi akun. Jika memerlukan bantuan, silakan hubungi TU/operator sekolah.</p>
    </main>
</body>
</html>