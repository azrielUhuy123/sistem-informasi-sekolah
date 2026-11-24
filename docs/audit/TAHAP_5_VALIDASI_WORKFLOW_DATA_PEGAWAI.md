# Tahap 5 - Validasi Workflow Data Pegawai

## Ringkasan

Audit source dilakukan ulang pada branch `main`, HEAD `6349b56c75c9fff815b5cd3ef941ca1770f1d49c`. Tidak ada perubahan staged pada awal pemeriksaan; perubahan lokal yang sudah ada dipertahankan.

Audit menemukan satu bypass otorisasi dengan severity **HIGH**: akun Kepsek yang dinonaktifkan atau belum disetujui masih dapat mengakses monitoring perubahan pegawai dan bukti privat selama role-nya `superadmin`. Guard workflow telah diperketat dan regresi ditambahkan untuk seluruh endpoint monitoring dan endpoint bukti. Test workflow terarah lulus.

Full suite pada SQLite `:memory:` menghasilkan 254 test lulus dan 5 gagal. Kelima kegagalan terjadi di modul Ekskul dan Kenaikan Kelas, dan dapat direproduksi saat test terkait dijalankan sendiri. Kegagalan menunjukkan ketergantungan fixture/test pada perilaku MySQL, bukan regresi workflow Data Pegawai. Karena itu sistem **belum dinyatakan lulus penuh atau siap migrasi produksi**; verifikasi MySQL/MariaDB, migrasi terhadap QA disposable, dan browser smoke test belum dilakukan.

## Lingkungan dan keselamatan

- Workspace: `C:\laragon\www\sistem-informasi-sekolah`.
- Branch/HEAD: `main` / `6349b56c75c9fff815b5cd3ef941ca1770f1d49c`.
- Config cache Laravel tidak ada.
- Pengujian dijalankan dengan environment proses sementara `APP_ENV=testing`, `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`, dan `DB_FOREIGN_KEYS=true`. Tidak ada `.env`, `.env.testing`, `phpunit.xml`, atau konfigurasi aplikasi yang diubah.
- Koneksi yang dipakai oleh test adalah SQLite in-memory; test tidak diarahkan ke database sekolah ataupun database persisten.
- Test `RefreshDatabase` menjalankan schema hanya pada database in-memory yang dibuang bersama proses. Tidak ada migration terhadap database persisten, `migrate:fresh`, reset, atau penghapusan data.
- Tidak ada credential yang dibuka atau dicatat.
- Migration workflow belum diterapkan ke database persisten.

## Temuan keamanan dan perbaikan

### HIGH - akses Kepsek yang tidak aktif/tidak disetujui

Sebelum perbaikan, `PegawaiChangeWorkflow::assertKepsek()` hanya memeriksa `role === 'superadmin'`. Endpoint monitoring memakai middleware `auth` dan `role:superadmin`, sedangkan middleware role tidak memeriksa status akun. Akibatnya akun Kepsek yang diblokir tetapi masih memiliki sesi dapat melewati pemeriksaan akses workflow. Jalur unduh bukti di `authorizeEvidence()` juga memberikan akses hanya berdasarkan role.

| Severity | File | Lines | Vulnerability | Confidence |
|---|---|---:|---|---:|
| 🟠 HIGH | `app/Services/PegawaiChangeWorkflow.php` | 55-63, 299-325 | Sebelum perbaikan, Kepsek nonaktif/belum disetujui dapat mengakses monitoring dan bukti privat; sudah diperbaiki | 9/10 |

Perbaikan yang dilakukan:

- `assertKepsek()` (`app/Services/PegawaiChangeWorkflow.php:55-63`) sekarang mensyaratkan role `superadmin`, `approval_status === 'approved'`, dan `is_active`.
- `authorizeEvidence()` memanggil guard Kepsek yang sama sebelum mengizinkan bukti.
- `PegawaiChangeWorkflowTest` memverifikasi akun inactive dan pending ditolak pada seluruh empat endpoint monitoring dan endpoint bukti. Akun Kepsek aktif yang disetujui tetap dapat memonitor.

Temuan ini terverifikasi melalui test terarah setelah perbaikan. Tidak ada bypass otorisasi lain yang terkonfirmasi pada jalur yang ditinjau.

### Jalur legacy dan batas otorisasi

- `ProfileController::updateDetail()` tidak memperbarui field pribadi `DataKepegawaian` dari profil Guru; cabang Guru memperbarui email.
- `Tu\DataKepegawaianController::update()` menggunakan `validateAdministrativeData()` dengan allowlist administratif, sehingga Guru tidak memperoleh jalur edit pribadi melalui CRUD TU.
- `Tu\DataKepegawaianController::store()` masih dapat membuat record onboarding dengan field lebih luas, tetapi record baru menunggu approval; ini bukan jalur untuk mengedit pegawai yang sudah ada.
- `SuperAdmin\DataKepegawaianApprovalController::approve()` dan `reject()` mengelola status onboarding, bukan mengubah field profil pegawai.
- Identitas pemohon pengajuan diperoleh dari akun terautentikasi. Pengajuan Guru dibatasi ke record pegawainya sendiri.
- Verifikasi Operator memerlukan akun aktif/disetujui, assignment aktif bertipe `operator`, menu `operator.employees`, dan menolak verifikasi pengajuan pegawai milik sendiri.
- Monitoring Kepsek bersifat baca-saja; tidak ada approval kedua.

### Field, bukti, dan schema

`config/employee_profile_changes.php` membatasi field yang dapat diajukan: nama, NIP, NUPTK, tempat/tanggal lahir, agama, jenis kelamin, status kawin, ijazah terakhir, pengalaman, dan data sertifikasi. Controller membatasi key request, tipe/nilai, serta file PDF/JPG/JPEG/PNG maksimum 5 MB. Service memeriksa allowlist ulang dan mewajibkan bukti untuk NIP, NUPTK, tanggal lahir, status kawin, ijazah terakhir, serta data sertifikasi saat nilainya berubah.

Field status kawin (`K`/`TK`), pendidikan (`ijazah_terakhir`), identitas, pengalaman, dan sertifikasi memiliki kolom yang sesuai pada schema yang ditinjau. Tidak ditemukan kolom gelar akademik tersendiri; field yang ada hanya `ijazah_terakhir`. Pangkat/SK, jabatan, status kerja, gaji, masa kerja administratif, serta penugasan tidak termasuk allowlist Guru. Penugasan dikelola terpisah.

Bukti disimpan melalui disk privat dan diambil melalui endpoint terautentikasi, bukan URL `storage/public`. Pemeriksaan ini tidak membuktikan keaslian dokumen, hanya validasi format/ukuran/keberadaan. Jika transaksi database gagal setelah file tersimpan, file privat orphan dapat tertinggal; risiko ini tidak membuka file secara publik tetapi memerlukan strategi cleanup.

### Transaksi, snapshot, dan race condition

Service memproses submission dan keputusan di dalam transaksi, mengunci akun/pegawai/request/item terkait, mengecek status, serta membandingkan snapshot nilai lama sebelum mengubah data resmi. Persetujuan dilakukan per item; histori keputusan dipertahankan ketika item ditolak dan diajukan ulang. Test workflow mencakup penolakan snapshot basi dan keputusan parsial.

Tidak ditemukan race yang terbukti dapat melewati pemeriksaan tersebut. Namun perilaku lock belum diverifikasi pada MySQL/MariaDB. Pengecekan duplikasi NIP di migration sebelum pembuatan unique index dapat berlomba dengan write pada masa deployment; lakukan saat write dihentikan atau setelah prosedur locking deployment diverifikasi. Pencegahan pengajuan aktif ganda bergantung pada lock dan pengecekan aplikasi, bukan unique constraint lintas request di database.

## Pemeriksaan migration

1. `2026_11_23_215900_enforce_unique_employee_nip.php`
   - Menghentikan migration jika ditemukan NIP duplikat non-null; tidak menghapus atau mengubah record.
   - Menambahkan unique index dan rollback menghapus index tersebut.
   - Kolom NIP pada schema awal nullable; perilaku multiple `NULL` pada unique index harus tetap dipastikan pada engine dan versi target.
   - Tidak dijalankan terhadap database persisten. Data aktual duplikat NIP dan perilaku index MySQL/MariaDB belum diverifikasi.
2. `2026_11_23_220000_create_pegawai_change_workflow_tables.php`
   - Membuat tabel request/item/decision dengan foreign key, index, unique field per request, serta aturan cascade/restrict/null yang konsisten dengan histori.
   - Rollback menghapus tabel dalam urutan terbalik dependensi.
   - Pemeriksaan static PHP lulus; migrasi hanya dieksekusi melalui suite SQLite in-memory, bukan MySQL/MariaDB.

Tidak ditemukan operasi destruktif dalam migration yang ditinjau. Kompatibilitas MySQL/MariaDB dan kondisi data aktual belum dapat dinyatakan teruji.

## Pengujian dan hasil

### Test workflow terarah

Perintah:

```powershell
$env:APP_ENV='testing'; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; $env:DB_FOREIGN_KEYS='true'
php artisan test tests/Feature/PegawaiChangeWorkflowTest.php
```

Hasil akhir: **12 test passed, 69 assertions, 0 failed**.

Sebelum perubahan guard, test terarah terkait akses assignment, profil, jadwal, dan workflow juga menghasilkan 28 test / 150 assertion lulus. Hasil setelah perubahan guard yang menjadi validasi akhir adalah 12 / 69 di atas.

### Full suite

Perintah:

```powershell
$env:APP_ENV='testing'; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; $env:DB_FOREIGN_KEYS='true'
php artisan test
```

Hasil final: **259 test; 254 passed; 5 failed; 0 skipped; 1.462 assertions; 25,09 detik**. Tidak ada error terpisah yang dilaporkan selain lima test failed.

Kegagalan dan sebab teknis:

1. `EkskulAbsensiTest::attendance report uses its own menu permission`
2. `EkskulAbsensiTest::guru absensi page handles deleted member records`
3. `EkskulAbsensiTest::guru catatan pembinaan page handles deleted member records`
   - Ketiganya gagal saat fixture memasukkan `semester = 'ganjil'`; schema migration mendefinisikan enum `Ganjil`/`Genap`, sehingga check constraint SQLite menolak nilai lowercase.
4. `KeputusanKenaikanUniqueTest::database unique key rejects second class for same student period`
   - Test mengharapkan kode error MySQL `1062`, sedangkan SQLite mengembalikan kode `19`.
5. `KeputusanKenaikanUniqueTest::writer handles a concurrent duplicate insert without generic error`
   - Interceptor test mengharapkan SQL ber-quote backtick (`from \`keputusan_kenaikan\``); query SQLite memakai quote berbeda, sehingga competing insert tidak terinjeksi dan assertion fixture gagal.

Kedua class kegagalan dijalankan ulang secara individual dan menghasilkan kegagalan yang sama. Ini membuktikan isolasi terhadap full-suite order, tetapi tidak membuktikan hasil MySQL. Tidak ada failure pada test workflow Data Pegawai, profil, reset password, atau transisi semester pada full run ini.

### Perbandingan baseline

- Baseline historis yang tersedia: 247 test, 1.393 assertions, 242 passed, 5 failed (tiga Profile, reset-password link, dan transisi semester).
- Run saat ini menambahkan 12 test / 69 assertions; semua 12 test workflow baru lulus.
- Lima nama failure saat ini berbeda dari lima baseline historis. Kegagalan baseline tersebut tidak muncul di daftar failure run saat ini.
- Perbandingan tidak sepenuhnya apple-to-apple karena run audit ini sengaja memakai SQLite in-memory yang aman, sementara failure saat ini menunjukkan ketergantungan driver MySQL pada fixture/test. Tidak dapat disimpulkan bahwa lima kegagalan SQLite ini adalah regresi aplikasi atau bahwa kompatibilitas MySQL sudah lulus.

### Pemeriksaan teknis lainnya

- PHP lint lulus pada workflow service/controller, controller TU dan profil terkait, dua migration, dan test workflow.
- Route list mengonfirmasi empat route monitoring memakai `auth` dan `role:superadmin`; endpoint bukti memakai `auth` dan menjalankan pemeriksaan tambahan pada service.
- Test terarah merender halaman monitoring yang diuji; tidak menjalankan `view:cache` terpisah.
- `git diff --check` lulus. Git memberi peringatan normal konversi CRLF/LF pada file lokal; tidak ada whitespace error.
- Browser smoke test **tidak dilakukan**: tidak tersedia environment QA dengan database disposable dan akun/data fiktif yang disetujui dalam sesi ini. Tidak ada klaim browser test berhasil.

## File yang berubah dalam audit ini

Perubahan source yang dilakukan khusus dalam validasi ini:

- `app/Services/PegawaiChangeWorkflow.php` - memeriksa status aktif/disetujui Kepsek dan menggunakan guard yang sama untuk akses bukti.
- `tests/Feature/PegawaiChangeWorkflowTest.php` - menambahkan regresi untuk penolakan akun Kepsek inactive/pending pada seluruh endpoint monitoring dan endpoint bukti.
- `docs/audit/TAHAP_5_VALIDASI_WORKFLOW_DATA_PEGAWAI.md` - laporan ini.

## Perubahan lokal yang dipertahankan

Tidak ada perubahan awal yang dihapus, di-reset, di-stash, atau di-restore. Status awal menunjukkan tidak ada staged change; seluruh perubahan existing dipertahankan.

Tracked modified pada awal audit:

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
- `tests/Feature/TahunAjaranSemesterTransitionTest.php`

Untracked pada awal audit dan dipertahankan:

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
- `resources/views/guru/pegawai-changes/`
- `resources/views/superadmin/pegawai-changes/`
- `resources/views/tu/pegawai-changes/`
- `tests/Feature/PegawaiChangeWorkflowTest.php`

## Risiko yang belum diverifikasi dan rekomendasi

- Jalankan suite pada database MySQL/MariaDB disposable yang dipastikan bukan database sekolah/produksi; jangan gunakan database persisten sebelum ada backup, isolasi, dan persetujuan eksplisit.
- Validasi migration, unique index NIP, serta lock transaction pada versi MySQL/MariaDB target dan data QA representatif.
- Perbaiki atau tandai secara eksplisit asumsi engine-specific pada lima test Ekskul/Kenaikan Kelas; jangan melemahkan assertion bisnis untuk sekadar meluluskan test.
- Sediakan akun dan data fiktif di environment QA agar browser smoke test untuk Guru, Operator, Kepsek, bukti privat, partial approval, dan pengajuan ulang dapat dilakukan.
- Jika kebutuhan bisnis memang memerlukan gelar akademik terpisah dari ijazah terakhir, tentukan kolom/domain field secara eksplisit sebelum menambahkannya.
- Pertimbangkan cleanup terkontrol untuk file bukti privat orphan jika transaksi database gagal setelah penyimpanan file.

## Status kesiapan

Perbaikan otorisasi HIGH telah diterapkan dan regresi workflow terarah lulus. Tidak ditemukan bypass lain pada source path yang ditinjau. Namun full suite belum hijau pada SQLite, dan MySQL/MariaDB, migrasi persisten, data QA aktual, serta browser belum diverifikasi. Statusnya **tervalidasi sebagian; belum layak dinyatakan lulus penuh atau siap untuk penerapan migration produksi**.
