# Tahap 6 - Audit Keamanan PPDB dan Penutupan Risiko

## Keputusan

**LULUS DENGAN CATATAN.** Seluruh temuan HIGH dalam cakupan baru PPDB/SPMB ditutup pada jalur unggah, bukti publik, daftar ulang, akun siswa, dan otorisasi internal. Full regression MySQL QA lulus. Risiko residual utama adalah dokumen lama yang mungkin sudah tersimpan pada public disk sebelum perubahan ini; file lama tidak dipindahkan atau dihapus secara destruktif tanpa inventarisasi data relasi dan persetujuan operasi.

## Ringkasan temuan awal

| Severity | Temuan sumber aktual | Exploitasi/impact | Penutupan |
|---|---|---|---|
| HIGH | Dokumen KK, KTP orang tua, ijazah, akta, KIP, dan foto disimpan pada disk `public` | Siapa pun yang mengetahui atau menebak path dapat mengunduh identitas pribadi; URL lama dapat tetap terpajang oleh browser atau mesin pencari. | Unggahan baru disimpan pada disk `local`; download hanya melalui route PPDB internal dengan assignment PPDB aktif dan menu route. View verifikasi memakai route terotorisasi. |
| HIGH | `/ppdb/bukti/{no}` dapat diselidiki dengan nomor pendaftaran berurutan | Enumerasi nomor dan status pendaftaran dapat memetakan calon siswa; tampilan membatasi nama/NIK tetapi tetap mengungkap nomor dan status. | Route bukti kini memerlukan URL bertanda tangan Laravel. Store PPDB mengarahkan ke link temporary signed 7 hari. |
| HIGH | Daftar ulang mengubah status dengan nomor pendaftaran dan tanggal lahir | Orang luar dapat mencantumkan kombinasi yang diketahui/ditebak dan mengubah status daftar ulang. | Tidak ada status daftar ulang yang berubah dari POST yang hanya memuat nomor/DOB/email. POST mengirim tautan random satu kali melalui email; POST token wajib dan memvalidasi hash, kedaluwarsa, status diterima, serta penggunaan ulang. |
| MEDIUM | Status publik menerima nomor+tanggal lahir lalu menampilkan data pendaftar | Keterbukaan data dan enumerasi dibatasi lemah oleh respons dan tidak ada throttle. | Respons kegagalan dibuat generik dan route status diberi throttle 5 permintaan/menit. Signed status link tetap menjadi jalur status yang diarahkan lewat email. |
| MEDIUM | Konfirmasi daftar ulang membuat akun siswa dengan password tanggal lahir | Password awal mudah diprediksi dan dikirim sebagai kredensial plaintext. | Akun siswa dibuat tidak aktif dan `must_change_password=true`, dengan password hash acak. Email berisi tautan password-reset Laravel satu kali, bukan password DOB. |
| MEDIUM | Route PPDB admin hanya memakai auth/role/menu | Status akun approved/active dan must-change belum selalu diperiksa pada sesi aktif. | Grup route PPDB admin memakai middleware `approved`, selain `auth`, `role:guru`, dan `menu.route`. |

## File diperiksa

- `routes/ppdb-public.php`
- `routes/ppdb-admin.php`
- `app/Http/Controllers/PPDB/PublicController.php`
- `app/Http/Controllers/PPDB/PendaftarController.php`
- `app/Models/PpdbPendaftar.php`
- `config/filesystems.php`
- `resources/views/ppdb/public/daftar-ulang.blade.php`
- `resources/views/ppdb/public/bukti.blade.php`
- `resources/views/ppdb/pendaftar/verifikasi-form.blade.php`
- `app/Mail/PpdbPendaftaranMail.php`
- `app/Mail/PpdbDaftarUlangCredentialsMail.php`

## Perubahan diterapkan

### Dokumen privat

Unggahan baru pada `PublicController::store()` memakai `Storage::disk('local')` untuk:

- Kartu keluarga
- KTP
- Ijazah
- Akta lahir
- Kartu KIP
- Foto

`PendaftarController::document()` membatasi field yang diizinkan, memastikan file ada pada disk local, dan mengirim download `no-store/private`. Route download berada pada grup PPDB admin yang memeriksa approved, role, dan menu assignment.

Dokumen lama pada public disk tidak dipindahkan atau dihapus. Path public lama tetap tidak digunakan oleh view verifikasi baru, sehingga file baru tidak terekspos. Migrasi file lama memerlukan inventarisasi dan persetujuan operasional.

### Bukti dan status publik

`ppdb/bukti/{no}` sekarang membutuhkan signed middleware.

- Store PPDB mengirim redirect ke temporary signed URL bukti 7 hari.
- Signed status link tetap tersedia selama 7 hari.
- Status lookup `POST /ppdb/status` diberi throttle dan respons kegagalan generik.

### Daftar ulang satu kali

Migration aditif menambah:

- `daftar_ulang_token_hash`
- `daftar_ulang_token_expires_at`
- `daftar_ulang_token_used_at`

Alur:

1. User mengirim nomor pendaftaran, tanggal lahir, dan email terdaftar.
2. Aplikasi memberikan respons generik dan hanya mengirim token bila semua kecocokan berlaku serta status diterima.
3. Token acak 48 karakter disimpan sebagai SHA-256, tidak disimpan plaintext.
4. Token berlaku 30 menit.
5. GET token hanya menampilkan form konfirmasi bila hash/expiry/status/used valid.
6. POST token mengunci pendaftar dalam transaksi, memvalidasi lagi hash/status/expiry/used, mengubah status daftar ulang, dan menandai token sebagai terpakai.
7. Penggunaan ulang atau kedaluwarsa ditolak.

Nomor pendaftaran dan DOB saja tidak lagi dapat mengubah status.

### Akun siswa

Konfirmasi daftar ulang oleh TU tidak lagi membuat password siswa dari tanggal lahir.

- Password dibuat acak dan hanya disimpan sebagai hash.
- Akun berstatus approved tetapi `is_active=false`.
- `must_change_password=true`.
- Email berisi NIS serta password-reset link Laravel satu kali.
- Token reset mengikuti broker `password_reset_tokens` dengan masa berlaku dan penggunaan sekali.

## Pengujian database

Preflight sebelum migration dan test:

- `APP_ENV=testing`
- `DB_CONNECTION=mysql`
- `DB_DATABASE=sekolah_app_pegawai_qa`
- `SELECT DATABASE()` menghasilkan `sekolah_app_pegawai_qa`
- MySQL 8.0.30 pada `127.0.0.1:3306`

Migration baru:

- `2026_11_24_042500_harden_ppdb_public_security`
- Status: `Ran`
- Tiga kolom token terverifikasi pada tabel `ppdb_pendaftar`
- Tidak ada migration reset/dfresh/reset/rollback

## Hasil pengujian

| Cakupan | Hasil |
|---|---|
| PPDB daftar ulang, private document, status, notification, resend, quota, cohort | **43 test, 274 assertion lulus** |
| Full regression MySQL QA | **276 test, 1.636 assertion lulus, 0 failed, 0 errors, 0 skipped** |
| PHP lint perubahan PPDB | Lulus |
| `git diff --check` | Lulus |

Pengujian membuktikan:

- Document endpoint public tanpa autentikasi ditolak; Guru tanpa PPDB ditolak; staf PPDB terotorisasi dapat mengunduh file local.
- Bukti unsigned ditolak; bukti signed dapat dibuka dan tetap masking identitas sensitif.
- Nomor+DOB/unknown email tidak mengubah status dan tidak membocorkan keberadaan data.
- Token daftar ulang kedaluwarsa dan token terpakai ditolak.
- Ulang konfirmasi tetap idempotent.
- Akun siswa tidak memakai password DOB, ditandai inactive dan must-change sampai reset.
- Status Pendaftar accepted/pending/verifikasi/ditolak tetap diverifikasi.
- Alur normal penerimaan, daftar ulang, konfirmasi, quota, resend email, cohort, dan NIS tetap lulus.

## Temuan residual

1. **MEDIUM/HIGH histori:** file PPDB lama yang sudah berada pada public disk belum dipindahkan atau dihapus. Tidak ada operasi destruktif yang dijalankan. Hapus atau pindahkan hanya setelah inventarisasi relasi dan backup persetujuan operasional.
2. Status lookup publik lama masih menerima email? Implementasi final tetap menerima nomor+DOB+email untuk menerbitkan token tetapi tidak mengembalikan status. Kelengkapan ini tetap sensitif karena kandidat harus memakai data pribadi untuk permintaan; tidak ada enumerasi karena respons generik dan throttle.
3. Distribusi token mengandalkan pengiriman email. Jika email tidak dapat dikirim, status tidak berubah dan tidak ada bukti status yang boleh digunakan untuk daftar ulang; ini harus dipantau oleh operasi.
4. MariaDB belum diuji. Bukti aktual terbatas pada MySQL 8.0.30.

## Status akhir

**LULUS DENGAN CATATAN**. Semua jalur HIGH/medium baru ditutup dan dibuktikan melalui test terarah serta full suite. Catatan residual hanya menargetkan kompatibilitas file lama dan proses migrasi data, bukan celah pada unggahan baru atau mutasi daftar ulang.
