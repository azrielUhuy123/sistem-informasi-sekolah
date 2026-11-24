# Tahap 5E - Penutupan Temuan Keamanan Provisioning

## Keputusan

**LULUS DENGAN CATATAN.** Perbaikan provisioning akun pegawai, reset password, pemeriksaan status sesi, assignment Operator, dan redirect Guru/Operator telah lulus regression suite MySQL QA. Satu risiko terpisah pada provisioning akun siswa PPDB masih memakai tanggal lahir sebagai password awal dan belum diubah pada tahap ini agar tidak mengubah alur akses siswa tanpa keputusan produk. Uji browser terautentikasi juga tidak diulang karena kredensial QA tidak digunakan atau ditampilkan pada sesi ini.

## Baseline dan keselamatan

| Pemeriksaan | Hasil |
|---|---|
| Workspace | `C:\laragon\www\sistem-informasi-sekolah` |
| Branch / HEAD | `main` / `6349b56c75c9fff815b5cd3ef941ca1770f1d49c` |
| PHP | 8.2.29 |
| Laravel | 12.40.2 |
| Database | MySQL 8.0.30, `127.0.0.1:3306` |
| Schema QA | `sekolah_app_pegawai_qa` |
| Config cache | Tidak ada |
| Server QA | `http://127.0.0.1:8015` |

Database diverifikasi lewat `SELECT DATABASE()`, host, port, dan versi sebelum migration. Semua operasi database memakai process-only override ke schema QA. Tidak ada akses produksi, perubahan `.env`, reset, `migrate:fresh`, rollback, penghapusan data, commit, atau push. Test suite memakai `RefreshDatabaseState::$migrated = true` dan transaksi; test tidak menjalankan migration atau reset terhadap fixture QA yang telah ada. Seluruh perubahan lokal sebelum Tahap 5E tetap dipertahankan.

## Temuan dan keputusan teknis

| Temuan | Hasil |
|---|---|
| Password awal akun pegawai berasal dari tanggal lahir | **Ditutup.** `createAccount()` membuat credential acak yang hanya disimpan dalam bentuk hash. Akun baru tetap `pending`, nonaktif, dan memiliki `must_change_password=true`; NIP tetap menjadi identifier login. |
| Reset password TU mengatur password ke tanggal lahir dan dapat menargetkan akun Superadmin | **Ditutup.** Reset sekarang membuat token password-reset Laravel yang disimpan dalam bentuk hash, sekali pakai, dan mengikuti masa berlaku broker 60 menit. Tautan ditampilkan hanya pada respons POST Operator dengan header `no-store`; akun Superadmin/role lain ditolak. Siswa tetap tidak dapat meminta reset publik, namun dapat memakai tautan yang diterbitkan Operator. |
| Persetujuan akun baru langsung mengaktifkan akun sebelum password dibuat | **Ditutup.** Akun `must_change_password` tetap nonaktif saat disetujui. Login dan route workflow menolak akun yang belum menyelesaikan aktivasi. Setelah token valid digunakan, status aktivasi dibuka hanya bila akun telah disetujui. Endpoint TU, Admin, dan Superadmin untuk mengaktifkan akun juga menolak bypass sebelum setup password. |
| Status akun/assignment dapat berubah ketika Operator masih memiliki sesi aktif | **Ditutup untuk jalur sensitif.** Middleware `approved` memuat ulang status pengguna dari database pada setiap request. Service memeriksa ulang role dasar `guru`, status approved/active, flag setup password, relasi Guru, tipe assignment `operator`, status `active`, `published_at`, dan menu yang sesuai. Provisioning memerlukan `operator.employees`; manajemen akun memerlukan `operator.accounts`. Revoke assignment segera memutus akses. |
| Jabatan “Kepala Sekolah” dapat membuat akun `superadmin` melalui endpoint Operator | **Ditutup.** Endpoint Operator menolak provisioning pegawai dengan jabatan Kepala Sekolah; tidak lagi menentukan role `superadmin` dari teks jabatan. Akun tersebut harus disiapkan melalui proses Superadmin. |
| Login Guru/Operator diarahkan ke dashboard akademik yang tidak diizinkan | **Ditutup.** `getDashboardUrl()` memilih halaman berdasarkan menu assignment aktif yang dipublikasikan; Operator dengan menu `operator.employees` menuju antrean perubahan pegawai. Guru tanpa assignment akademik menuju workflow data pegawainya sendiri, bukan dashboard akademik yang menghasilkan 403. |
| Provisioning siswa PPDB masih menggunakan password tanggal lahir dan mengirimkannya melalui email | **Belum ditutup / di luar scope perubahan ini.** `PendaftarController` masih membentuk password awal dari tanggal lahir dan menyertakannya pada email kredensial siswa. Dibutuhkan keputusan terpisah mengenai perubahan onboarding siswa serta cara aktivasi yang dapat diandalkan. Risiko ini tidak boleh dianggap selesai oleh perbaikan akun pegawai. |

### Cakupan audit akses

- Kedua alias provisioning `tu.datakepegawaian.request-account` dan `tu.datakepegawaian.create-account` masuk ke method yang sama dan menjalankan pemeriksaan Operator di server.
- Role `tu` lama tidak dapat memenuhi pemeriksaan service akun pegawai; hak tidak diberikan melalui role fallback.
- Permission menu tetap digabungkan dari lima jenis assignment. Satu assignment yang dicabut tidak menghapus hak lain, tetapi assignment draft, unpublished, atau revoked tidak memberi akses.
- Endpoint reset/status akun dibatasi ke akun Guru/Siswa; Operator tidak dapat mereset atau mengaktifkan akun Superadmin.
- `UserApprovalController::approve`, `unblock`, `Admin\UserController::toggleStatus`, dan `Tu\AkunController::toggleStatus` telah ditinjau untuk mencegah aktivasi sebelum password setup.
- Route reset publik tetap menggunakan password broker. Siswa tetap tidak dapat meminta tautan reset publik dan harus dibantu Operator.

## Migration dan penerapan

Migration baru [`2026_11_24_004200_add_must_change_password_to_users.php`](../../database/migrations/2026_11_24_004200_add_must_change_password_to_users.php) menambahkan kolom boolean `users.must_change_password` dengan default `false`, sehingga akun lama tidak berubah statusnya. Migration dijalankan hanya pada `sekolah_app_pegawai_qa` menggunakan `artisan migrate --force`; status setelahnya `Ran`. Jumlah migration menjadi 114. Tidak ada perubahan data akun yang sudah ada, dan tidak ada migration lain yang pending. MariaDB maupun database produksi tidak diuji.

## Pengujian

| Pengujian | Hasil |
|---|---|
| `EmployeeAccountSecurityTest` | **10 test, 67 assertion lulus** |
| Regresi provisioning, reset, workflow pegawai, assignment, redirect, dan notifikasi approval | **84 test, 520 assertion lulus** |
| Regresi ekskul dan notifikasi dengan fixture QA persisten | **16 test, 139 assertion lulus** |
| Full regression MySQL QA | **272 test, 1.564 assertion lulus; 0 failed, 0 errors, 0 skipped; 3:21.234** |
| PHP lint untuk file PHP yang diubah | Lulus |
| `git diff --check` | Lulus |

Test mencakup password acak dan nonaktif sebelum aktivasi; approval tanpa auto-aktivasi; token reset valid, kadaluarsa, dan pemakaian ulang; penolakan target Superadmin; pencegahan aktivasi melalui toggle; penolakan assignment unpublished/non-Operator/role legacy; revoke assignment dan status akun saat sesi masih aktif; serta redirect login Guru tanpa assignment akademik dan Operator dengan menu pegawai.

Satu kegagalan pada run full suite awal adalah assertion redirect lama yang mengharapkan dashboard Guru, padahal user memiliki assignment ekstrakurikuler. Ekspektasi diselaraskan dengan halaman ekskul yang memang berhak diakses. Satu assertion lain menghitung seluruh baris tabel notifikasi dan tercampur fixture QA lama; assertion kini dibatasi pada notifikasi pemilik yang diuji. Setelah kedua penyesuaian tersebut, full suite lulus.

## Browser QA

Server QA lama tetap merespons `GET /login` dengan HTTP 200 dan judul `SINERGI-MJ QA - Login`. Pengujian browser terautentikasi Tahap 5E tidak dilakukan karena kredensial akun tidak digunakan/ditampilkan pada sesi ini. Redirect, status sesi, dan pembatasan assignment diperiksa melalui full feature suite pada MySQL. Skenario alur browser sebelumnya dari Tahap 5D tetap terdokumentasi di [`TAHAP_5D_KESIAPAN_PENGUJIAN_MANUAL.md`](./TAHAP_5D_KESIAPAN_PENGUJIAN_MANUAL.md); verifikasi UI terautentikasi pasca-perubahan ini masih diperlukan.

## File yang berubah pada Tahap 5E

**Implementasi dan konfigurasi route:**

- `app/Models/User.php`
- `app/Services/PegawaiChangeWorkflow.php`
- `app/Http/Controllers/Tu/DataKepegawaianController.php`
- `app/Http/Controllers/Tu/AkunController.php`
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Controllers/Auth/NewPasswordController.php`
- `app/Http/Controllers/SuperAdmin/UserApprovalController.php`
- `app/Http/Controllers/Admin/UserController.php`
- `app/Http/Middleware/EnsureUserApproved.php`
- `app/Http/Requests/Auth/LoginRequest.php`
- `routes/tu.php`
- `routes/guru.php`
- `resources/views/tu/akun/index.blade.php`
- `resources/views/tu/akun/password-reset-link.blade.php` (baru)
- `database/migrations/2026_11_24_004200_add_must_change_password_to_users.php` (baru)

**Test:**

- `tests/Feature/Tu/EmployeeAccountSecurityTest.php` (baru)
- `tests/Feature/Tu/AkunPasswordResetTest.php`
- `tests/Feature/Auth/PasswordResetTest.php`
- `tests/Feature/PegawaiChangeWorkflowTest.php`
- `tests/Feature/FeatureAccessTest.php`
- `tests/Feature/ManajemenPenugasanTest.php`
- `tests/Feature/EkskulJadwalWorkflowTest.php`
- `tests/Feature/SinergiNotificationTest.php`

File lain yang telah modified/untracked sebelum tahap ini tidak dihapus, dipulihkan, atau ditimpa. Tidak ada file `.env` atau credential yang dibuka atau dicantumkan.

## Risiko dan tindak lanjut

1. Tinjau terpisah password awal PPDB berbasis tanggal lahir dan kredensial yang dikirim melalui email. Perubahan sengaja tidak dicampurkan dengan provisioning akun pegawai.
2. Token reset pegawai menggunakan email internal yang tidak memiliki alamat kirim nyata; Operator yang berwenang harus menyerahkan tautan sekali pakai melalui kanal aman. Kanal distribusi tersebut belum diuji atau disahkan secara operasional.
3. Jalankan browser smoke test terautentikasi setelah tersedia prosedur penggunaan akun QA yang aman; jangan mencantumkan credential atau token pada laporan.
4. Kompatibilitas MariaDB belum diverifikasi; bukti database yang tersedia adalah MySQL 8.0.30.
