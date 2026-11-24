# Tahap 5B - Penutupan Validasi Workflow Data Pegawai

## Status akhir

**LULUS DENGAN CATATAN**

Full suite terisolasi sekarang lulus: **259 passed, 1 skipped, 1.472 assertions, 28,09 detik**. Skip tunggal adalah test race-recovery yang khusus memverifikasi MySQL/MariaDB dan belum dapat dijalankan karena tidak ada database disposable yang dikonfirmasi. Tidak ada migration terhadap database persisten, akses database produksi, browser smoke test, commit, atau push.

## Lingkungan dan keselamatan

- Workspace: `C:\laragon\www\sistem-informasi-sekolah`.
- Branch/HEAD: `main` / `6349b56c75c9fff815b5cd3ef941ca1770f1d49c`.
- Semua test berjalan dengan environment proses `APP_ENV=testing`, `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`, `DB_FOREIGN_KEYS=true`.
- Tidak ada file konfigurasi database yang diubah. Tidak ada credential yang dibuka atau dicatat.
- `RefreshDatabase` hanya membuat schema pada SQLite in-memory. Tidak ada koneksi ke database produksi atau database persisten, migration persisten, `migrate:fresh`, reset, maupun penghapusan data.
- Tidak ada database QA disposable MySQL/MariaDB yang dapat diverifikasi aman untuk migration dan test concurrency pada tahap ini. Karena itu validasi engine MySQL/MariaDB hanya bersifat static review.

## Verifikasi lima kegagalan regression suite

Semua lima kasus sebelumnya dijalankan ulang secara individual. Empat kini lulus setelah perbaikan test fixture/assertion yang mempertahankan kontrak bisnis; satu di-skip secara eksplisit pada SQLite karena memverifikasi perilaku khusus MySQL/MariaDB.

| Test | Kegagalan semula dan bukti teknis | Analisis | Hasil akhir |
|---|---|---|---|
| `EkskulAbsensiTest::test_attendance_report_uses_its_own_menu_permission` di [`EkskulAbsensiTest.php`](../../tests/Feature/EkskulAbsensiTest.php) | `QueryException`: `SQLSTATE[23000]`, SQLite code `19`, `CHECK constraint failed: semester`. SQL gagal: `insert into "tahun_ajaran" ... ("semester") values (..., ganjil, ...)`. Stack teratas: `vendor/laravel/framework/src/Illuminate/Database/Connection.php:824`, lalu `Connection.php:570`. | Fixture memakai `ganjil`, sedangkan schema enum dan validasi aplikasi menetapkan `Ganjil`/`Genap`. Ini fixture yang tidak sama dengan nilai domain/schema, bukan regresi aplikasi. | Fixture diselaraskan ke `Ganjil`; individual rerun **1 passed, 2 assertions**. |
| `EkskulAbsensiTest::test_guru_absensi_page_handles_deleted_member_records` di [`EkskulAbsensiTest.php`](../../tests/Feature/EkskulAbsensiTest.php) | Awalnya error enum yang sama di atas. Setelah nilai semester diperbaiki, insert siswa berikutnya menghasilkan `SQLSTATE[23000]`, SQLite code `19`, `NOT NULL constraint failed: siswa.jenis_kelamin`; SQL insert hanya mengirim `nama` dan `nis`. Stack teratas `Connection.php:824` dan `Connection.php:570`. | Selain nilai semester, fixture tidak menyertakan kolom enum wajib `siswa.jenis_kelamin`, yang ditambahkan sebagai non-null tanpa default. Test belum mencapai kode halaman yang dimaksud. Ini fixture tidak lengkap. | Fixture diberi nilai valid `jenis_kelamin = L`; individual rerun **1 passed, 2 assertions**. |
| `EkskulAbsensiTest::test_guru_catatan_pembinaan_page_handles_deleted_member_records` di [`EkskulAbsensiTest.php`](../../tests/Feature/EkskulAbsensiTest.php) | Error awal enum semester sama. Setelah koreksi semester, error `NOT NULL constraint failed: siswa.jenis_kelamin` yang sama muncul saat insert siswa. Stack teratas `Connection.php:824` dan `Connection.php:570`. | Fixture tidak memenuhi schema siswa yang berlaku, bukan regresi halaman catatan. | Fixture diberi nilai valid `jenis_kelamin = L`; individual rerun **1 passed, 2 assertions**. |
| `KeputusanKenaikanUniqueTest::test_database_unique_key_rejects_second_class_for_same_student_period` di [`KeputusanKenaikanUniqueTest.php`](../../tests/Feature/KeputusanKenaikanUniqueTest.php) | Duplikat ditolak oleh SQLite, tetapi test gagal pada `tests/Feature/KeputusanKenaikanUniqueTest.php:399`: mengharapkan MySQL code `1062`, menerima SQLite code `19`. Exception melaporkan unique constraint atas `keputusan_kenaikan.siswa_id` dan `tahun_ajaran_id`. | Unique key bekerja; assertion mengunci kode dan nama index MySQL sehingga tidak portable. Penolakan duplikat adalah perilaku yang sama pada kedua engine. | Assertion kini tetap mengecek SQLSTATE `23000`, kode serta pesan constraint spesifik engine, dan jumlah record tetap satu. MySQL assertion atas code `1062` dan nama index dipertahankan. Individual rerun **1 passed, 4 assertions**. |
| `KeputusanKenaikanUniqueTest::test_writer_handles_a_concurrent_duplicate_insert_without_generic_error` di [`KeputusanKenaikanUniqueTest.php`](../../tests/Feature/KeputusanKenaikanUniqueTest.php) | `raceInserted` tetap `false`, gagal pada `...UniqueTest.php:457`. Query listener hanya mencocokkan `from \`keputusan_kenaikan\``; SQLite mengutip nama tabel dengan tanda petik ganda, sehingga competing insert tidak pernah diinjeksi. | Test belum mencapai jalur race atau penanganan exception aplikasi pada run SQLite. Controller memang mengenali duplicate MySQL berdasarkan code `1062` dan nama index. Mengubah perilaku bisnis hanya untuk meniru MySQL di SQLite tidak dibenarkan. | Test sekarang di-skip khusus saat PDO driver bukan `mysql`; test tetap aktif untuk MySQL/MariaDB. Individual rerun: **1 skipped**, dengan alasan dicatat. Jalur race MySQL/MariaDB belum diverifikasi pada mesin ini. |

Perbaikan enum dan jenis kelamin pada fixture menggunakan nilai yang diizinkan schema; tidak ada assertion perilaku aplikasi yang dilemahkan. Assertion unique key tetap memverifikasi larangan duplikat dan hasil database. Test race tidak dianggap lulus—ia tidak dieksekusi pada SQLite.

## Hasil test

### Test yang sebelumnya gagal

- Tiga kasus `EkskulAbsensiTest`: **3 passed, 6 assertions** ketika file dijalankan bersama; juga masing-masing lulus saat dijalankan individual.
- `database_unique_key_rejects_second_class_for_same_student_period`: **1 passed, 4 assertions** individual.
- `writer_handles_a_concurrent_duplicate_insert_without_generic_error`: **1 skipped, 0 assertions** individual pada SQLite.

### Workflow Data Pegawai

Perintah `php artisan test tests/Feature/PegawaiChangeWorkflowTest.php` pada SQLite in-memory:

- **13 passed, 72 assertions**.
- Termasuk coverage baru: Operator inactive dan Operator approval status pending ditolak; item tetap pending.
- Test file juga memverifikasi ownership Guru, manipulasi ID permintaan dan bukti, self-verification Operator, tipe assignment, bukti privat, jalur edit profil/TU, partial decision, snapshot basi, dan monitoring Kepsek aktif/inaktif.

### Full regression

Perintah:

```powershell
$env:APP_ENV='testing'; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; $env:DB_FOREIGN_KEYS='true'
php artisan test --no-ansi
```

Hasil final: **259 passed, 1 skipped, 0 failed, 1.472 assertions, 28,09 detik**. Total 260 test. Skip tunggal adalah skenario race MySQL/MariaDB yang dijelaskan di atas.

## Audit migration MySQL/MariaDB

### `2026_11_23_215900_enforce_unique_employee_nip.php`

- Query memeriksa duplikasi NIP non-null menggunakan `GROUP BY` dan `COUNT(*)`; jika ada duplikat, migration melempar `RuntimeException` dengan instruksi rekonsiliasi. Tidak ada penghapusan atau normalisasi data lama.
- Unique index hanya ditambahkan setelah pemeriksaan tersebut. Rollback menghapus index yang ditambahkan, bukan tabel/kolom lama.
- Kolom NIP pada schema pegawai nullable; MySQL dan MariaDB mengizinkan beberapa `NULL` pada unique index. Perlakuan duplikat nilai non-null tetap mengikuti collation database.
- Kondisi data aktual dan eksekusi penambahan index pada versi MySQL/MariaDB target belum diverifikasi.

### `2026_11_23_220000_create_pegawai_change_workflow_tables.php`

- Tabel request mereferensikan `data_kepegawaian` dan `users`; item mereferensikan request dan parent resubmission; decision mereferensikan item dan actor. Tipe `foreignId` sesuai dengan `$table->id()` pada tabel induk.
- `restrictOnDelete`, `nullOnDelete`, dan `cascadeOnDelete` sesuai kebutuhan relasi: histori request tidak menghapus data pegawai, akun pemohon/verifikator dapat dihapus tanpa menghilangkan snapshot/keputusan, dan item/decision mengikuti request/item induknya.
- Unique constraint `(pegawai_change_request_id, field)` mencegah field ganda dalam satu request; index status, field, dan resubmission menunjang query workflow.
- Nama index/constraint berada di bawah batas panjang identifier MySQL 64 karakter. Migration menggunakan tipe kolom dan foreign key umum Laravel yang ditargetkan pada MySQL/MariaDB modern.
- Rollback menghapus hanya tiga tabel milik workflow, urut dari tabel dependen ke induk. Ini tidak menghapus tabel lama, namun tentu menghapus data workflow yang dibuat oleh fitur; rollback pada lingkungan berisi histori harus melalui persetujuan dan rencana retensi.

Kedua file lulus PHP syntax lint dan dieksekusi oleh migrasi test pada SQLite in-memory. **Migration belum dijalankan atau divalidasi terhadap MySQL/MariaDB.** Static review bukan bukti kompatibilitas runtime. Jalankan pada database disposable terpisah setelah nama/isolasinya dikonfirmasi; jangan gunakan database `sekolah_app_testing` yang sekadar dikenal sebagai database test tanpa memastikan sifat disposable-nya.

## Verifikasi keamanan workflow

- **Kepemilikan Guru:** `employeeForTeacher()` mengambil pegawai dari akun login, dan mensyaratkan role Guru, akun aktif/disetujui, serta relasi Guru. `store()` tidak memakai `data_kepegawaian_id` dari request untuk menetapkan pemilik. Test mencoba mengirim ID pegawai lain; request tetap terikat ke pemohon. Halaman detail request milik Guru lain menghasilkan 404.
- **Operator:** `assertActiveOperator()` mensyaratkan akun Guru aktif/disetujui, relasi pegawai/Guru, assignment aktif bertipe `operator`, dan menu `operator.employees`. Verifikasi menolak record pegawai Operator sendiri. Test tambahan menunjukkan status inactive maupun pending menghasilkan 403 tanpa mengubah status item.
- **Kepsek:** `assertKepsek()` mensyaratkan role `superadmin`, `approval_status=approved`, dan `is_active`. Keempat endpoint monitoring memanggil guard ini; monitoring tidak menampilkan aksi approval. Test mengecek inactive/pending Kepsek ditolak pada semua endpoint monitoring.
- **Bukti privat dan manipulasi ID:** route unduh bukti memakai `auth`; service mengizinkan pemilik yang valid, Operator aktif yang bukan pemilik, atau Kepsek aktif/disetujui. ID bukti Guru lain menghasilkan 403; ID request Guru lain tidak dapat dibuka; Kepsek inactive/pending ditolak. File berada pada disk privat dan dikirim melalui controller setelah authorization, bukan URL publik.
- **Jalur legacy:** test profil Guru membuktikan field resmi tidak tersimpan langsung; test edit TU membuktikan field pribadi seperti nama/NIP tidak berubah lewat edit lama sementara field administratif tetap dapat diperbarui. `Tu\DataKepegawaianController::update()` menggunakan allowlist administratif. Jalur onboarding `store()` membuat record baru pending approval; approval lama mengubah status onboarding, bukan mengedit perubahan pribadi yang sudah berjalan.
- **Hasil:** tidak ada bypass workflow tambahan yang terkonfirmasi pada jalur yang ditinjau. Test tidak menggantikan pemeriksaan runtime MySQL atau smoke test browser.

## Browser smoke test

**Tidak dilakukan.** Tidak tersedia akun fiktif yang disetujui atau environment QA disposable yang dapat dipastikan aman untuk workflow end-to-end; karena itu server tidak dijalankan untuk browser testing. Tidak ada data pegawai nyata digunakan dan tidak ada klaim browser test berhasil.

## File yang berubah pada Tahap 5B

- [`tests/Feature/EkskulAbsensiTest.php`](../../tests/Feature/EkskulAbsensiTest.php) - enum semester fixture dan kolom wajib jenis kelamin.
- [`tests/Feature/KeputusanKenaikanUniqueTest.php`](../../tests/Feature/KeputusanKenaikanUniqueTest.php) - assertion unik berdasarkan driver; concurrency scenario ditandai MySQL/MariaDB-only.
- [`tests/Feature/PegawaiChangeWorkflowTest.php`](../../tests/Feature/PegawaiChangeWorkflowTest.php) - regresi status akun Operator inactive/pending.
- [`docs/audit/TAHAP_5B_PENUTUPAN_VALIDASI_WORKFLOW_DATA_PEGAWAI.md`](./TAHAP_5B_PENUTUPAN_VALIDASI_WORKFLOW_DATA_PEGAWAI.md) - laporan ini.

Tidak ada implementasi aplikasi, tampilan, atau migration yang diubah pada Tahap 5B. Seluruh perubahan lokal yang telah ada dipertahankan; tidak ada commit atau push.

## Risiko tersisa dan tindak lanjut

1. Verifikasi dua migration dan full suite pada MySQL/MariaDB disposable yang namanya dan isolasinya telah diperiksa.
2. Jalankan test concurrency `writer_handles_a_concurrent_duplicate_insert_without_generic_error` pada MySQL/MariaDB; hasilnya belum diketahui.
3. Lakukan browser smoke test memakai akun serta data fiktif di QA yang disetujui.
4. Perlakukan rollback migration workflow sebagai penghapusan histori workflow; jangan melakukan rollback pada lingkungan berisi data tanpa persetujuan eksplisit dan rencana retensi.

Dengan empat kasus portable diperbaiki dan seluruh test lainnya lulus, workflow dinyatakan **LULUS DENGAN CATATAN**, bukan LULUS penuh: kompatibilitas migration dan penanganan concurrency MySQL/MariaDB serta browser belum diverifikasi.
