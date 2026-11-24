# Tahap 5E - Verifikasi Akhir dan Penutupan Temuan Keamanan

## Keputusan

**LULUS DENGAN CATATAN.** Temuan HIGH pada pemulihan status akun Superadmin telah ditutup dan terverifikasi. Full regression MySQL QA serta pengujian keamanan terarah lulus. Browser automation tersedia dan login/redirect Guru serta halaman alur data pegawai berhasil diuji, tetapi smoke browser lintas peran tidak dapat diselesaikan dalam satu sesi browser karena keterbatasan pengendalian cookies/session pada browser automation. Hak redirect Operator, Kepsek, Murid, dan pencabutan sesi diverifikasi oleh PHPUnit.

## Temuan awal dan penutupan

Temuan awal security review:

| Severity | Temuan awal | Keadaan akhir |
|---|---|---|
| HIGH | `SuperAdmin\UserApprovalController::unblock()` belum memeriksa status akun aktor pada sesi aktif. | Ditutup. Middleware `approved` kini berlaku untuk seluruh role termasuk Superadmin. Route Superadmin memakai middleware `approved`. Controller memuat ulang aktor dari database, menolak akun yang tidak aktif/belum disetujui/belum menyelesaikan password setup, dan menolak aksi terhadap akun sendiri. |

Audit source aktual juga menegaskan:

- Akun pegawai baru tidak memakai tanggal lahir/NIP sebagai password awal. Password acak disimpan sebagai hash dan hanya ada sementara sebagai nilai setup internal.
- Flag `must_change_password` ditolak pada login dan middleware, serta ditolak oleh workflow pegawai dan pemeriksaan Operator.
- Password broker Laravel menyimpan hash token dengan masa berlaku 60 menit, sekali pakai, dan token kadaluarsa atau terpakai ditolak.
- Pembuatan akun dan reset memeriksa akun aktor, approved, active, assignment active/published bertipe tepat, dan menu tepat.
- Pencabutan assignment, pencabutan approval, dan penonaktifan akun dimuat ulang pada request berikutnya.
- Bukti privat memakai authorization pemilik/Kepsek/Operator berwenang dan tidak menampilkan file tanpa bukti.
- Kepsek tidak memiliki endpoint approval tahap kedua pada monitoring workflow perubahan pegawai.
- Profil Guru mengarahkan perubahan data pribadi ke workflow pegawai; tidak ditemukan update field pegawai pribadi melalui endpoint profil lama.

## Perubahan implementasi

- `app/Http/Middleware/EnsureUserApproved.php`
  - Status approved, active, dan `must_change_password` kini berlaku untuk Superadmin.
- `routes/superadmin.php`
  - Grup route Superadmin memakai `approved` selain `auth` dan `role:superadmin`.
- `app/Http/Controllers/SuperAdmin/UserApprovalController.php`
  - Menambah pemeriksaan aktor aktif dan disetujui pada approve/reject/block/unblock.
  - Menolak pemrosesan akun sendiri.
- `tests/Feature/Tu/EmployeeAccountSecurityTest.php`
  - Menambah pengujian Superadmin aktif, diblokir, dan percobaan reaktivasi pada sesi aktif.
- `tests/Feature/PegawaiChangeWorkflowTest.php`
  - Menambah putus akses bukti/monitoring saat akun pemilik atau Kepsek dinonaktifkan.
- `routes/guru.php` dan `routes/web.php`
  - Route Guru utama dan unduhan bukti juga memakai pemeriksaan approved.
- `app/Services/PegawaiChangeWorkflow.php`
  - Bukti privat memuat ulang aktor dari database dan memeriksa status setup password.

Perubahan utama Tahap 5E sebelumnya tetap dipertahankan pada controller provisioning/reset, model User, migration aktivasi, service workflow, serta test terkait.

## Database dan migration

- Target: `sekolah_app_pegawai_qa`.
- MySQL: 8.0.30.
- Host/port: `127.0.0.1:3306`.
- Preflight `SELECT DATABASE()` menghasilkan `sekolah_app_pegawai_qa`.
- Tidak ada config cache.
- Migration `2026_11_24_004200_add_must_change_password_to_users` tercatat Ran dan kolom `users.must_change_password` ada.
- Tidak ada migration baru yang dijalankan pada proses verifikasi.
- Tidak ada database produksi, `.env`, `.env.testing`, migrate:fresh, reset, DROP, TRUNCATE, rollback, atau penghapusan massal.
- Test memakai `RefreshDatabaseState::$migrated = true` dan transaksi.

## Pengujian terarah

| Cakupan | Hasil |
|---|---|
| Akun, reset, workflow, profil, assignment, feature access, dan password | **48 test, 275 assertion lulus** |
| Akun provisioning Superadmin rekativasi sesi aktif | **1 test, 7 assertion lulus** |
| Full regression MySQL QA | **273 test, 1.597 assertion lulus, 0 failed, 0 errors, 0 skipped** |
| PHP lint file perubahan | Lulus |
| `git diff --check` | Lulus |

Hasil full regression awal sebelum penutupan tambahan tetap penuh setelah temuan difix, tidak ada kegagalan sisa. Suite terarah mencakup status Operator inactive/pending/revoke, assignment unpublished, menu salah, akun sendiri, bukti privat, Superadmin nonaktif, dan migrasi status sesi.

## Verifikasi redirect

Hasil otomatis dan source:

| Peran | Redirect/target | Bukti |
|---|---|---|
| Guru tanpa assignment akademik | Workflow perubahan data pegawai miliknya sendiri | `User::getDashboardUrl()` dan test feature |
| Guru dengan assignment akademik | Dashboard akademik yang memiliki menu; route menolak assignment palsu | `User::getDashboardUrl()` dan middleware menu |
| Guru dengan assignment Operator | Antrean atau dashboard Operator yang memiliki menu aktif/published | Test assignment dan redirect |
| Guru dengan beberapa assignment | Prioritas Wali, Operator, PPDB, Ekskul, Guru, atau menu pegawai sesuai konfigurasi aktual; semua route tetap melewati menu assignment | Test merge assignment dan middleware |
| Kepsek | `/superadmin/dashboard` | Login response dan route Superadmin |
| Murid | `/siswa/dashboard` | Login response dan route siswa |

Redirect Operator dan Kepsek tidak dipalsukan dengan assignment akademik. Browser Guru benar-benar login dan membuka `/guru/pegawai/perubahan`; halaman pembuatan pengajuan juga terbuka. Dashboard akademik Guru fixture menghasilkan 500 karena fixture browser tidak memiliki tahun ajaran aktif, bukan error authorization atau redirect. Tidak ada perubahan source untuk membuat tahun ajaran palsu.

## Browser smoke

Server QA dijalankan ulang pada `http://127.0.0.1:8015` dengan process-only environment QA. Login halaman merespons HTTP 200.

Hasil aktual:

- Login browser Guru fiktif berhasil.
- Redirect menuju `/guru/dashboard-gurubidang` terjadi sesuai assignment akademik.
- Halaman riwayat `/guru/pegawai/perubahan` terbuka dan menampilkan “Belum ada pengajuan”.
- Halaman `/guru/pegawai/perubahan/create` terbuka dengan allowlist field dan bukti.
- Akun fiktif Guru, Operator, multi-assignment, Wali, Kepsek, dan Murid dibuat pada database QA untuk smoke test.

Keterbatasan browser automation:

- Browser automation tersedia, tetapi tidak menyediakan pengendalian cookies/session yang dapat memisahkan login lintas peran secara aman pada sesi ini.
- Pencabutan assignment, penonaktifan sesi, pembuatan akun, reset browser, dan smoke lintas kepsek/murid tidak dapat dinyatakan berhasil melalui browser.
- Keempat skenario tersebut telah diuji melalui feature suite dan status middleware di MySQL QA.
- Tidak ada kredensial akun atau password ditulis ke laporan ini.

## Risiko residual

1. Provisioning siswa PPDB masih memakai password awal berbasis tanggal lahir dan mengirim kredensial melalui email. Ini tidak termasuk temuan HIGH/Medium Tahap 5E pegawai dan perlu keputusan terpisah.
2. Browser smoke lintas peran belum selesai sepenuhnya karena keterbatasan pengelolaan sesi browser.
3. Dashboard akademik Guru memerlukan fixture tahun ajaran aktif untuk browser; failure 500 pada smoke Guru adalah keterbatasan fixture, bukan kegagalan otorisasi.
4. MariaDB belum diuji; bukti aktual hanya MySQL 8.0.30.

## Status kerja

Tidak ada commit, push, reset, restore, clean, checkout, atau stash. Perubahan lokal yang sudah ada dipertahankan. Server QA dan MySQL tetap berjalan pada host lokal untuk pelaporan ini.
