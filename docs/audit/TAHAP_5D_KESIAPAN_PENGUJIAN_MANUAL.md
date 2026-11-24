# Tahap 5D - Kesiapan Pengujian Manual Workflow Data Pegawai

## Keputusan

**SIAP DENGAN CATATAN**

QA menggunakan MySQL 8.0.30 pada schema terpisah `sekolah_app_pegawai_qa`. Seluruh 113 migration tercatat `Ran`, test suite MySQL lulus, akun fiktif tersedia, dan server lokal berjalan. Guru, Operator, dan Kepsek dapat membuka alur workflow melalui URL langsung.

Catatan penentu:

1. Login Guru dan Operator berhasil, tetapi redirect awal ke `/guru/dashboard-gurubidang` menghasilkan HTTP 403 karena assignment akademik QA tidak tersedia. Halaman workflow tetap dapat dibuka melalui URL langsung yang dicantumkan di bawah.
2. Audit menemukan risiko keamanan pada provisioning akun pegawai legacy yang belum diperbaiki. Tidak ada perubahan kebijakan atau source code pada Tahap 5D.
3. Default `.env` dan `.env.testing` tetap menunjuk `sekolah_app_testing`. Server QA yang sedang berjalan menggunakan override environment proses ke schema QA; jangan jalankan ulang server atau test MySQL tanpa override yang sama.

## Baseline dan keselamatan

| Pemeriksaan | Hasil |
|---|---|
| Workspace | `C:\laragon\www\sistem-informasi-sekolah` |
| Branch / HEAD | `main` / `6349b56c75c9fff815b5cd3ef941ca1770f1d49c` |
| PHP CLI | 8.2.29 |
| Laravel | 12.40.2 |
| Database server | MySQL Community 8.0.30, `127.0.0.1:3306` |
| Database QA | `sekolah_app_pegawai_qa`, charset/collation `utf8mb4` / `utf8mb4_unicode_ci` |
| Config cache | Tidak ada |
| Config database default | `.env` dan `.env.testing` masih menunjuk `sekolah_app_testing`; tidak diubah |
| Perubahan lokal awal | Dipertahankan seluruhnya; tidak ada commit atau push |

Schema QA dibuat terpisah setelah diverifikasi tidak sama dengan `sekolah_app` maupun `sekolah_app_testing`. Migration dijalankan hanya dengan override environment proses `APP_ENV=testing`, `DB_CONNECTION=mysql`, `DB_HOST=127.0.0.1`, `DB_PORT=3306`, dan `DB_DATABASE=sekolah_app_pegawai_qa`. Tidak ada password atau credential yang dicatat dalam laporan.

Tidak ada akses ke data operasional/produksi, `migrate:fresh`, reset, rollback, penghapusan data, atau perubahan `.env`. Sebelum fixture QA dibuat, tabel data aplikasi yang digunakan test dipastikan kosong. PHPUnit dijalankan melalui runner in-process yang menandai `RefreshDatabaseState::$migrated = true`; setiap test memakai transaksi, sehingga test tidak memanggil jalur `migrate:fresh`. Setelah suite, hanya fixture fiktif yang dibuat untuk smoke test tersisa.

## Validasi migration MySQL

Kedua migration berjalan pada MySQL 8.0.30 dan tercatat pada tabel `migrations`:

- [`2026_11_23_215900_enforce_unique_employee_nip.php`](../../database/migrations/2026_11_23_215900_enforce_unique_employee_nip.php)
- [`2026_11_23_220000_create_pegawai_change_workflow_tables.php`](../../database/migrations/2026_11_23_220000_create_pegawai_change_workflow_tables.php)

Hasil inspeksi metadata MySQL:

- Total migration tercatat: **113**; seluruhnya berstatus `Ran`.
- Tiga tabel workflow terbentuk.
- Tujuh foreign key workflow terverifikasi terhadap tabel/kolom induk.
- Sebelas index workflow terverifikasi, termasuk unique index `(pegawai_change_request_id, field)`.
- Unique index NIP `data_kepegawaian_nip_unique` terbentuk sebagai unique index pada `data_kepegawaian.nip`.
- Uji duplikasi NIP dalam transaksi ditolak oleh MySQL dengan error **1062**. Transaksi di-rollback dan jumlah baris probe setelah rollback adalah **0**.

Migration tidak di-rollback; rollback workflow akan menghapus histori yang berada pada tabel workflow. MariaDB belum diuji. Ketahanan migration terhadap kegagalan parsial dan eksekusi rollback tidak diklaim terverifikasi.

## Hasil test MySQL dan concurrency

| Test | Hasil |
|---|---|
| `tests/Feature/PegawaiChangeWorkflowTest.php` | **13 passed, 72 assertions** |
| `KeputusanKenaikanUniqueTest::writer_handles_a_concurrent_duplicate_insert_without_generic_error` | **1 passed, 4 assertions** pada MySQL; sebelumnya di-skip pada SQLite |
| Full regression suite | **260 passed, 1.476 assertions, 0 failed, 0 errors, 0 skipped, 35,04 detik** |
| Dua proses submit field yang sama | Satu proses berhasil submit; proses bersamaan kedua ditolak karena field masih menunggu verifikasi |
| Replay keputusan yang sudah diproses | HTTP **409**, jumlah histori keputusan tidak berubah |
| Operator memverifikasi request miliknya sendiri | HTTP **403**, item tetap pending |

Race dua proses memakai dua koneksi/proses PHP independen pada MySQL dan row lock pada akun Guru QA. Request yang diterima disimpan sebagai fixture QA fiktif. Hasil ini memverifikasi serialisasi submit untuk satu field; bukan stress test beban produksi. Perlindungan item yang sudah diproses juga diperiksa melalui service pada MySQL. SQLite tidak digunakan untuk klaim concurrency MySQL.

## Audit keamanan workflow

Pemeriksaan source dan test pada [`PegawaiChangeWorkflow.php`](../../app/Services/PegawaiChangeWorkflow.php), controller terkait, routes, dan [`PegawaiChangeWorkflowTest.php`](../../tests/Feature/PegawaiChangeWorkflowTest.php) menunjukkan:

- Identitas pegawai untuk Guru ditentukan dari akun autentikasi dan relasi `Guru`, bukan dari `data_kepegawaian_id` kiriman pengguna.
- Submit mengunci akun dan pegawai, mengambil snapshot nilai resmi, menolak field yang masih pending, dan hanya menerima field allowlist dari `config/employee_profile_changes.php`.
- Operator verifikasi mensyaratkan akun Guru aktif/disetujui serta assignment aktif bertipe `operator` dengan menu `operator.employees`. Verifikasi akun sendiri ditolak.
- Keputusan mengunci request, item, aktor dan pegawai; snapshot stale ditolak tanpa menimpa nilai resmi. Keputusan item kedua kali ditolak dengan 409.
- Kepsek monitoring mensyaratkan role `superadmin`, aktif dan disetujui. Controller monitoring hanya membaca request/riwayat dan tidak menyediakan aksi approval tahap kedua.
- File bukti disajikan melalui route authenticated dan authorization service. Upload QA tersimpan di disk `local` di bawah `storage/app/private`, bukan di public root.
- Jalur profil/TU untuk pengubahan data pribadi tetap harus melalui workflow; test profil dan legacy edit memeriksa bahwa field pribadi tidak berubah melalui jalur edit lama.

### Temuan keamanan tersisa

| Severity | Lokasi | Temuan dan status |
|---|---|---|
| HIGH | [`DataKepegawaianController.php`](../../app/Http/Controllers/Tu/DataKepegawaianController.php:335) | `createAccount()` membuat password awal dari tanggal lahir (`Ymd`) lalu menyimpan hash-nya. NIP dipakai sebagai username login, sehingga password awal dapat diprediksi. Tidak diperbaiki pada tahap validasi ini. |
| MEDIUM | [`DataKepegawaianController.php`](../../app/Http/Controllers/Tu/DataKepegawaianController.php:309), [`tu.php`](../../routes/tu.php:39) | Endpoint provisioning akun berada di route group auth/role/mode/menu, tetapi controller tidak memeriksa ulang status akun Operator aktif/disetujui. Menu assignment tidak membuktikan status akun saat ini; sesi yang masih berlaku setelah pencabutan status perlu ditangani. |

`DataKepegawaianApprovalController` dan route approval lama mengubah `DataKepegawaian.approval_status`, yaitu approval onboarding pegawai. Pembacaan source tidak menemukan route tersebut yang memproses `PegawaiChangeRequest` atau `PegawaiChangeItem`; karena itu tidak diklasifikasikan sebagai bypass langsung workflow perubahan pribadi. Perbedaan kebijakan antara approval onboarding dan monitoring Kepsek tetap harus dipastikan oleh pemilik produk. Tidak ada route/kebijakan lama yang dihapus atau diubah.

## Browser smoke test

Server berjalan pada `http://127.0.0.1:8015` dengan PID PHP 63860, process-only database override ke `sekolah_app_pegawai_qa`, `APP_DEBUG=false`, mailer array, dan session file. Halaman login merespons HTTP 200. Asset build tersedia.

### Hasil alur browser

1. Login Guru fiktif; halaman riwayat dan form pengajuan dapat dibuka.
2. Guru mengajukan tiga item sekaligus, termasuk item NUPTK dengan bukti PDF fiktif.
3. Operator membuka antrean dan menyetujui item Agama serta NUPTK.
4. Operator menolak Tempat lahir dengan alasan; tanpa alasan endpoint juga mewajibkan validasi server (`reason` required).
5. Data resmi hanya berubah pada item yang disetujui. Tempat lahir yang ditolak tidak berubah.
6. Guru melihat status per item dan mengajukan ulang item Tempat lahir. Item lama berstatus rejected dan relasi `resubmitted_from_id` tetap menunjuk item tersebut.
7. Guru pemilik dan Kepsek dapat mengunduh bukti privat (HTTP 200, `application/pdf`); Guru lain ditolak (HTTP 403).
8. Manipulasi ID ke request Guru lain menghasilkan HTTP 404. Request Operator sendiri tidak dapat dibuka dari antrean verifikasi; test/service guard menolak aksi verifikasi mandiri dengan HTTP 403.
9. Kepsek dapat membuka monitoring dan melihat status/riwayat. Halaman tidak menampilkan tombol approval.
10. Uji browser meninggalkan data fiktif untuk manual testing; tidak ada data pegawai nyata digunakan.

### URL lokal

- Login: `http://127.0.0.1:8015/login`
- Guru - riwayat: `http://127.0.0.1:8015/guru/pegawai/perubahan`
- Guru - ajukan/perbaiki: `http://127.0.0.1:8015/guru/pegawai/perubahan/create`
- Operator - antrean: `http://127.0.0.1:8015/tu/pegawai/perubahan`
- Kepsek - monitoring: `http://127.0.0.1:8015/superadmin/perubahan-data-kepegawaian`

Setelah login, Guru dan Operator saat ini diarahkan ke `/guru/dashboard-gurubidang`, yang membalas 403 karena assignment akademik tidak disiapkan/tersedia pada fixture QA. Endpoint workflow di atas tetap dapat dibuka langsung. Server ini hanya untuk QA lokal dan menggunakan override proses; bila server dihentikan, jalankan kembali dengan override QA yang sama dan verifikasi `SELECT DATABASE()` sebelum menguji.

### Akun fiktif yang tersedia

Tiga akun telah dibuat pada QA: Guru, Guru dengan assignment Operator aktif (`operator.employees`), dan Kepsek aktif/disetujui. Masing-masing memiliki data pegawai fiktif, bukan identitas orang nyata. Credential tidak dicantumkan dalam dokumen ini; disampaikan hanya pada ringkasan akhir sesi Copilot. Pengajuan fiktif saat ini mencakup hasil partial decision, resubmission, bukti privat, request Operator sendiri, serta fixture race field yang sama.

## Skenario uji manual

1. Login Guru melalui URL login dan buka halaman riwayat perubahan.
2. Ajukan beberapa perubahan sekaligus; sertakan bukti untuk field yang mewajibkan bukti.
3. Pastikan data resmi belum berubah sebelum keputusan Operator.
4. Logout, login Operator, lalu buka antrean verifikasi.
5. Setujui satu item; verifikasi hanya field tersebut berubah pada data resmi.
6. Tolak item lain dengan alasan; pastikan validasi menolak alasan kosong.
7. Login Guru; pastikan hasil keputusan tampil per item.
8. Ajukan ulang item yang ditolak dan pastikan histori penolakan tetap ada.
9. Coba Operator memverifikasi request miliknya sendiri.
10. Login Kepsek; tinjau status dan riwayat tanpa approval kedua.
11. Uji download bukti oleh pemilik/Kepsek dan penolakan untuk Guru lain.
12. Ubah ID request/item di URL untuk memastikan akses ke data pegawai lain ditolak.

Gunakan hanya akun dan data QA pada schema yang disebutkan. Jangan menghubungkan server ini ke database lain.

## Perubahan lokal yang dipertahankan

Tahap 5D tidak mengubah source code, test, konfigurasi, UI, atau migration di repository. Satu-satunya file yang dibuat pada tahap ini adalah laporan ini. Seluruh perubahan yang sudah ada saat pemeriksaan baseline dipertahankan:

**Tracked modified:**

- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/Tu/DataKepegawaianController.php`
- `app/Models/DataKepegawaian.php`
- `app/Services/BusinessNotificationService.php`
- `config/assignment_menus.php`
- `docs/audit/SINERGI_MJ_MASTER_AUDIT.md`
- `resources/views/auth/login.blade.php`
- `resources/views/layouts/sidebar/guru-akademik.blade.php`
- `resources/views/layouts/sidebar/guru-ekskul.blade.php`
- `resources/views/layouts/sidebar/superadmin.blade.php`
- `resources/views/layouts/sidebar/tu-operasional.blade.php`
- `resources/views/profile/detail.blade.php`
- `resources/views/tu/datakepegawaian/edit.blade.php`
- `routes/guru.php`
- `routes/superadmin.php`
- `routes/tu.php`
- `routes/web.php`
- `tests/Feature/EkskulAbsensiTest.php`
- `tests/Feature/KeputusanKenaikanUniqueTest.php`
- `tests/Feature/TahunAjaranSemesterTransitionTest.php`

**Untracked existing before this report:**

- `app/Http/Controllers/Guru/PegawaiChangeController.php`
- `app/Http/Controllers/SuperAdmin/PegawaiChangeMonitoringController.php`
- `app/Http/Controllers/Tu/PegawaiChangeVerificationController.php`
- `app/Models/PegawaiChangeDecision.php`
- `app/Models/PegawaiChangeItem.php`
- `app/Models/PegawaiChangeRequest.php`
- `app/Services/PegawaiChangeWorkflow.php`
- `config/employee_profile_changes.php`
- `database/migrations/2026_11_23_215900_enforce_unique_employee_nip.php`
- `database/migrations/2026_11_23_220000_create_pegawai_change_workflow_tables.php`
- `docs/audit/TAHAP_4_AUDIT_OTORISASI_LINTAS_PERAN.md`
- `docs/audit/TAHAP_5B_PENUTUPAN_VALIDASI_WORKFLOW_DATA_PEGAWAI.md`
- `docs/audit/TAHAP_5C_VALIDASI_DATABASE_DAN_KESIAPAN_BROWSER.md`
- `docs/audit/TAHAP_5_VALIDASI_WORKFLOW_DATA_PEGAWAI.md`
- `resources/views/guru/pegawai-changes/create.blade.php`
- `resources/views/guru/pegawai-changes/index.blade.php`
- `resources/views/guru/pegawai-changes/show.blade.php`
- `resources/views/superadmin/pegawai-changes/employee.blade.php`
- `resources/views/superadmin/pegawai-changes/employees.blade.php`
- `resources/views/superadmin/pegawai-changes/index.blade.php`
- `resources/views/superadmin/pegawai-changes/show.blade.php`
- `resources/views/tu/pegawai-changes/index.blade.php`
- `resources/views/tu/pegawai-changes/show.blade.php`
- `tests/Feature/PegawaiChangeWorkflowTest.php`

Tidak ada perubahan staged yang tampak pada pemeriksaan baseline. Tidak ada git reset, restore, clean, checkout, stash, commit, atau push.
