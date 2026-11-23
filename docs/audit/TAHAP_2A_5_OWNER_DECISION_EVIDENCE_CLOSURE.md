# TAHAP 2A.5 — OWNER DECISION & EVIDENCE CLOSURE

**Sistem:** SINERGI-MJ  
**Status:** READ-ONLY; belum ada keputusan owner yang dianggap disetujui.  
**Kesimpulan:** **NOT READY FOR IMPLEMENTATION**. Bukti lokal mempersempit beberapa ketidakpastian, tetapi tidak membuktikan konfigurasi deployment atau kondisi data aktual.

## 1. Executive Summary

Pemeriksaan ini melanjutkan desain yang telah direfine pada Tahap 2A.1–2A.3 dan readiness gate Tahap 2A.4; arsitektur tidak dirancang ulang.

Temuan penutupan:

- **Diperoleh bukti lokal CLI:** PHP 8.2.29 CLI, Laravel 12.40.2, Composer 2.4.1, ICU 71.1, `Normalizer`, `Collator`, `mbstring`, PDO, serta driver PDO `mysql` dan `sqlite`.
- **Belum terbukti:** PHP SAPI yang benar-benar melayani web, driver/database deployment, konfigurasi schema aktif, data NIS legacy, grant DB, dan perilaku concurrency deployment.
- **Sorting tetap fail closed:** `Collator` yang diminta dengan locale `id-ID` menghasilkan actual locale `root` pada PHP CLI lokal. Probe perilaku Unicode bukan comparator aplikasi yang disahkan dan bukan bukti konsistensi lintas runtime.
- **Jalur NIS aktual:** PPDB mengeluarkan NIS satu per satu saat konfirmasi daftar ulang, bukan melalui batch alfabetis. Ada jalur TU manual dan import yang juga menerima NIS. Penempatan PPDB terjadi melalui endpoint terpisah.
- **Data dan relasi User–Siswa belum direkonsiliasi:** login mencari Siswa berdasarkan NIS lalu mengikuti `siswa.user_id`; model/migration juga memiliki `users.siswa_id`.
- **Test yang ada** memeriksa sebagian alur PPDB, sequence serial, relasi akun, serta larangan mengubah NIS melalui form TU. Tidak ditemukan test sorting, concurrency allocator, ledger immutability, direct SQL, deletion/reuse, transfer grade, atau reconciliation.

Tidak ada `.env`/secret yang dibuka, koneksi database yang dilakukan, test yang dijalankan, ataupun migration/seeder/data operation. Satu-satunya file yang dibuat pada tahap ini adalah laporan ini.

## 2. Ruang Lingkup dan Batas Pemeriksaan

### Referensi

- `docs/audit/TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md` — OD-01–OD-18, TE-01–TE-20, BL-01–BL-10.
- `docs/audit/TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md` — desain/refinement 2A.3.
- Source yang ditelusuri pada `app/`, `routes/`, `database/migrations/`, `config/`, `composer.json`, `composer.lock`, dan `tests/`.
- Inventaris dan uji runtime yang tersedia di mesin lokal saat audit.

### Batas bukti

- **Repository** membuktikan konfigurasi dan perilaku kode yang dibaca; bukan konfigurasi deployment.
- **Lingkungan lokal** membuktikan keberadaan executable/proses lokal yang teramati; bukan database atau web runtime yang melayani deployment.
- **Database/deployment** tidak dihubungi. Tidak ada data record yang dibaca.
- Pencarian repository memperluas dan menutup sejumlah call path, tetapi tidak membuktikan ketiadaan external SQL, operator, ETL, konfigurasi deployment, atau pemanggil di luar repository.
- Semua status **APPROVED** dilarang pada register owner sampai ada persetujuan eksplisit pemilik sistem. Tidak ada keputusan yang diberi status tersebut.

### Pemisahan kategori laporan

- **FAKTA KODE / REPOSITORY:** langsung terlihat pada source, routes, migration, test, manifest, atau lockfile.
- **FAKTA LINGKUNGAN LOKAL:** hasil pemeriksaan mesin lokal, tidak dapat digeneralisasi ke deployment.
- **INFERENSI TEKNIS:** risiko atau konsekuensi yang mengikuti dari fakta kode, belum diuji sebagai kejadian aktual.
- **BELUM TERVERIFIKASI:** membutuhkan bukti Ops/DBA/owner, runtime web/deployment, atau data aktual.

## 3. Database dan Runtime Evidence

### 3.1 Hasil yang dapat dibuktikan

| ID | Lingkup | Fakta / hasil | Status dan batas |
|---|---|---|---|
| RT-01 | Repository | `composer.json` menetapkan PHP `^8.2` dan Laravel `^12.0`; `composer.lock` menyematkan `laravel/framework v12.40.2`. | VERIFIED untuk manifest/lockfile saja. |
| RT-02 | Lokal — PHP CLI | Executable Laragon menghasilkan PHP `8.2.29`, SAPI `cli`, NTS x64. `php artisan --version` menghasilkan Laravel `12.40.2`. | VERIFIED hanya untuk CLI lokal. |
| RT-03 | Lokal — ekstensi | `intl`/ICU `71.1`, `Normalizer`, `Collator`, `mbstring 8.2.29`, PDO tersedia. PDO drivers yang terdaftar: `mysql`, `sqlite`. | VERIFIED hanya untuk CLI lokal; driver tidak membuktikan koneksi/schema. |
| RT-04 | Lokal — Composer | Composer `2.4.1`. | VERIFIED hanya untuk executable lokal; bukan build/deployment. |
| RT-05 | Lokal — database executable/proses | Binary `mysqld.exe` pada Laragon melaporkan MySQL `8.0.30`; proses `mysqld` teramati. Tidak ada koneksi yang dibuat. | VERIFIED sebagai executable/proses lokal saja; bukan database aplikasi atau deployment. |
| RT-06 | Repository — driver konfigurasi | `config/database.php` menyediakan SQLite, MySQL, MariaDB, PostgreSQL, dan SQL Server; default kode adalah SQLite. | VERIFIED untuk konfigurasi repository; pilihan environment deployment UNVERIFIED. |
| RT-07 | Lokal — web server | Proses Apache lokal teramati. Inventaris modul yang tersedia sebelumnya menunjukkan `fcgid`, bukan `php_module`; executable `php-cgi.exe` ada. Tidak dibuktikan bahwa vhost aplikasi memakai executable tersebut. | PARTIALLY VERIFIED; PHP web SAPI, versi, `php.ini`, ekstensi dan handler aplikasi UNVERIFIED. |
| RT-08 | Deployment | Database engine/version, schema aktif, PHP web runtime, ICU, PDO, grants, isolation, dan server OS deployment. | UNVERIFIED. Tidak ada akses/bukti deployment yang diberikan. |
| RT-09 | Composer/vendor deployment | Constraint, lockfile dan versi Laravel lokal terbaca; Composer/vendor deployment tidak diperiksa. | PARTIALLY VERIFIED. |

**Makna:** SQLite sebagai default config tidak membuktikan SQLite dipakai oleh aplikasi. Demikian pula executable/proses MySQL dan Apache Laragon tidak membuktikan aplikasi web lokal maupun deployment menggunakannya.

### 3.2 Bukti khusus yang harus diminta dari Ops/DBA

1. DB engine, versi patch, storage engine, charset/collation, schema aktif, migration level, isolation default, timezone, dan endpoint/environment yang diuji.
2. PHP web SAPI/build/versi/arsitektur, executable, `php.ini` efektif, versi ICU/data ICU, ekstensi/driver yang aktif, dan konfigurasi FCGI/FPM/IIS yang benar-benar melayani aplikasi. Jangan mengirim `phpinfo()` publik atau secret.
3. Versi Composer dan lockfile/artifact build yang benar-benar dideploy; verifikasi dependency efektif tanpa menjalankan update/install.
4. Inventaris role/grant runtime, migration, publisher, DBA, dan jalur ETL/raw SQL; bukti apakah runtime dapat menulis/menghapus ledger.
5. Bukti staging disposable untuk isolation, row locking, conflict/upsert, rollback, unique/check/FK/trigger, `ON DELETE SET NULL`, serta restore dari backup.
6. Konfirmasi eksplisit bahwa environment dan akses yang ditawarkan berwenang untuk pemeriksaan read-only. Jangan menganggap DB lokal sebagai deployment.

## 4. Comparator dan Sorting Evidence

### 4.1 Fakta dan hasil probe

- **FAKTA KODE:** `PendaftarController::generateNis()` mengambil satu NIS terakhir berdasarkan prefix NIS (`orderByDesc('nis')`) lalu menaikkan suffix. Fungsi ini tidak mengurutkan nama siswa.
- **FAKTA KODE:** pemanggilnya `konfirmasiDaftarUlang()` untuk satu pendaftar per request. Tidak ditemukan initial-batch comparator atau comparator alfabetis pada jalur penerbitan ini.
- **FAKTA LOKAL:** `Collator::create('id-ID')` dengan Normalization ON dan Tertiary strength memberi `Locale::ACTUAL_LOCALE = root`. Pemeriksaan sebelumnya terhadap alias `id_ID` dan `id` juga mencatat actual locale `root`.
- **FAKTA LOKAL:** probe terpisah menggunakan Normalizer NFC, Unicode case-fold, whitespace collapse, dan Collator `root` menunjukkan: pasangan beda kapital menjadi sama setelah case-fold; bentuk aksen composed/decomposed menjadi sama setelah NFC; aksen, tanda baca, dan awalan gelar tetap menghasilkan key berbeda pada probe tersebut.
- **BATAS:** probe tersebut hanya eksperimen CLI lokal, bukan implementasi aplikasi, bukan golden order Indonesia yang disetujui, dan tidak menguji parity antar-OS/ICU/runtime.
- **BELUM TERVERIFIKASI:** aturan owner untuk kapitalisasi, aksen/diakritik, spasi Unicode, tanda baca, gelar/awalan, nama identik, sumber data nama, serta comparator dan versi data yang harus dipin.

### 4.2 Golden fixture yang diusulkan

Fixture sintetis; nama bukan data siswa. ID dipakai sebagai tie-break numerik hanya setelah aturan tersebut disetujui. Tetapkan expected order dan snapshot/hash setelah OD-03 disetujui dan hasil target-runtime lulus; **jangan mengisi urutan berdasarkan Collator `root`**.

| Fixture ID | ID sintetis | Nama input | Kasus yang harus dibuktikan |
|---|---:|---|---|
| SORT-01 | 101 | `Ayu Putri` | Bentuk dasar. |
| SORT-02 | 102 | `ayu putri` | Kapitalisasi dan case-fold. |
| SORT-03 | 103 | `Ayu  Putri` | Spasi berulang. |
| SORT-04 | 104 | `Ayu Putri` | NBSP/whitespace Unicode dibanding spasi biasa. |
| SORT-05 | 105 | `Ayu-Putri` | Tanda baca dipertahankan atau dinormalisasi sesuai keputusan owner. |
| SORT-06 | 201 | `Dewi` | Bentuk tanpa diakritik. |
| SORT-07 | 202 | `Dewí` | Aksen composed; bandingkan dengan SORT-08. |
| SORT-08 | 203 | `Dewí` | Aksen decomposed; harus konsisten dengan SORT-07 jika NFC dipilih. |
| SORT-09 | 301 | `Budi` | Nama tanpa gelar/awalan. |
| SORT-10 | 302 | `Dr. Budi` | Gelar/awalan tidak boleh dibuang tanpa keputusan. |
| SORT-11 | 401 | `Siti Aminah` | Nama identik, record pertama. |
| SORT-12 | 402 | `Siti Aminah` | Nama identik; urutan harus deterministik dengan tie-break yang disetujui. |

**Assertions wajib:** hasil comparator tidak bergantung locale OS default; input/expected order diberi version/hash; NFC-equivalence, case, aksen, whitespace, tanda baca dan prefix masing-masing memiliki assertion eksplisit; nama identik memiliki tie-break stabil; output CLI dan web SAPI pada build target identik byte-per-byte. Unicode fixture yang ditulis dalam file test harus disimpan UTF-8 dan memuat code point yang ditetapkan, bukan bergantung pada encoding terminal.

**Kesimpulan sorting:** fail closed untuk initial publication. Dukungan ekstensi saja tidak cukup; actual locale `root` bukan bukti comparator Indonesia yang benar.

## 5. Legacy NIS Inventory

### 5.1 Hasil saat ini

Database tidak dihubungi dan data siswa tidak dibaca.

| Kategori | Status saat ini | Makna |
|---|---|---|
| VERIFIED | Tidak ada record yang dapat diklasifikasikan. | Bukan klaim bahwa record anomali/duplikat berjumlah nol. |
| NEEDS VERIFICATION | Semua agregat NIS aktif, soft-deleted, kosong, format, username dan placement. | Query harus dijalankan DBA dengan akses read-only pada DB yang diidentifikasi dan diotorisasi. |
| DATA CONFLICT | Belum dapat ditentukan. | Tidak boleh membuat reservation, mapping year/grade, deduplikasi, atau alokasi baru dari asumsi. |

**FAKTA REPOSITORY:** migration awal mendefinisikan `siswa.nis` sebagai `string()->unique()`; `Siswa` memakai `SoftDeletes`; TU menyediakan `forceDelete()`. Tidak ditemukan NIS registry/ledger permanen dalam source yang diperiksa.

**INFERENSI TEKNIS:** unique constraint pada row `siswa` tidak menyimpan tombstone NIS sesudah force delete. Jika record benar-benar dihapus permanen dan tidak ada ledger/arsip lain, uniqueness pada row aktif tidak dapat mencegah pemakaian NIS yang sama oleh writer lain. Apakah kondisi itu sudah terjadi di data tidak diverifikasi.

### 5.2 Query pack DBA — MySQL 8, SELECT-only

Query berikut referensi untuk DBA setelah engine/schema aktual dikonfirmasi. Adaptasi sintaks hanya oleh DBA terhadap engine target. Semua query menghasilkan agregat, bukan nama/NIS/username. Jangan jalankan pada database yang belum diidentifikasi dan diotorisasi. Tidak ada query di bawah yang dijalankan dalam audit ini.

```sql
-- LGC-Q1: jumlah row aktif/trashed, NIS kosong, dan bentuk struktural non-canonical.
SELECT
    COUNT(*) AS rows_total,
    SUM(deleted_at IS NULL) AS active_rows,
    SUM(deleted_at IS NOT NULL) AS soft_deleted_rows,
    SUM(nis IS NULL OR TRIM(nis) = '') AS empty_nis_rows,
    SUM(
        nis IS NOT NULL AND TRIM(nis) <> ''
        AND CAST(nis AS CHAR) NOT REGEXP '^[0-9]{4}[.][0-9][.][0-9]{3}$'
    ) AS structural_format_anomalies
FROM siswa;

-- LGC-Q2: duplikat byte-exact, termasuk row soft-deleted.
SELECT
    COUNT(*) AS duplicate_value_groups,
    COALESCE(SUM(extra_rows), 0) AS duplicate_extra_rows
FROM (
    SELECT COUNT(*) - 1 AS extra_rows
    FROM siswa
    WHERE nis IS NOT NULL
    GROUP BY CAST(nis AS BINARY)
    HAVING COUNT(*) > 1
) AS duplicate_groups;

-- LGC-Q3: arah FK User-Siswa yang orphan atau tidak saling menunjuk.
SELECT
    (SELECT COUNT(*)
     FROM siswa AS s
     LEFT JOIN users AS u ON u.id = s.user_id
     WHERE s.user_id IS NOT NULL AND u.id IS NULL) AS siswa_user_orphans,
    (SELECT COUNT(*)
     FROM siswa AS s
     JOIN users AS u ON u.id = s.user_id
     WHERE NOT (u.siswa_id <=> s.id)) AS siswa_to_user_mismatches,
    (SELECT COUNT(*)
     FROM users AS u
     LEFT JOIN siswa AS s ON s.id = u.siswa_id
     WHERE u.siswa_id IS NOT NULL AND s.id IS NULL) AS user_siswa_orphans,
    (SELECT COUNT(*)
     FROM users AS u
     JOIN siswa AS s ON s.id = u.siswa_id
     WHERE NOT (s.user_id <=> u.id)) AS user_to_siswa_mismatches,
    (SELECT COUNT(*)
     FROM (
         SELECT siswa_id
         FROM users
         WHERE siswa_id IS NOT NULL
         GROUP BY siswa_id
         HAVING COUNT(*) > 1
     ) AS duplicate_user_links) AS siswa_with_multiple_users,
    (SELECT COUNT(*)
     FROM (
         SELECT user_id
         FROM siswa
         WHERE user_id IS NOT NULL
         GROUP BY user_id
         HAVING COUNT(*) > 1
     ) AS duplicate_siswa_links) AS users_with_multiple_siswa;

-- LGC-Q4: relasi PPDB yang perlu ditinjau; tidak mengeluarkan identitas.
SELECT
    SUM(
        p.status = 'diterima'
        AND p.status_daftar_ulang = 'sudah'
        AND p.siswa_id IS NULL
    ) AS completed_reregistration_without_siswa,
    SUM(p.siswa_id IS NOT NULL AND s.id IS NULL) AS ppdb_siswa_orphans,
    SUM(p.siswa_id IS NOT NULL AND s.deleted_at IS NOT NULL) AS ppdb_links_to_trashed_siswa
FROM ppdb_pendaftars AS p
LEFT JOIN siswa AS s ON s.id = p.siswa_id;

-- LGC-Q5: siswa dengan lebih dari satu placement aktif pada tahun aktif.
SELECT COUNT(*) AS siswa_with_multiple_active_placements
FROM (
    SELECT ps.siswa_id
    FROM penempatan_siswa AS ps
    JOIN tahun_ajaran AS ta ON ta.id = ps.tahun_ajaran_id
    WHERE ta.status = 'aktif' AND ps.tanggal_keluar IS NULL
    GROUP BY ps.siswa_id
    HAVING COUNT(*) > 1
) AS conflicts;
```

`LGC-Q1` hanya memeriksa bentuk struktural, bukan mengesahkan year-code, tingkat masuk, atau kebijakan format. Untuk benturan dengan calon sequence/username, DBA hanya boleh menjalankan agregat terhadap daftar kandidat yang telah disetujui owner; jangan menyimpulkan entry year/grade dari kelas aktif. Berkas hasil harus membatasi akses dan menghindari export PII.

```sql
-- LGC-Q6: ketidakcocokan username terhadap NIS pada relasi operasional.
SELECT
    SUM(u.username IS NULL OR TRIM(u.username) = '') AS linked_student_accounts_without_username,
    SUM(u.username IS NOT NULL AND CAST(u.username AS BINARY) <> CAST(s.nis AS BINARY))
        AS linked_student_accounts_username_differs_from_nis
FROM siswa AS s
JOIN users AS u ON u.id = s.user_id
WHERE u.role = 'siswa';

-- LGC-Q7: benturan terhadap daftar kandidat yang sudah disetujui.
-- Ganti placeholder dengan parameter terikat; query hanya mengembalikan jumlah.
WITH candidate_nis (nis) AS (
    SELECT CAST(? AS CHAR)
    UNION ALL SELECT CAST(? AS CHAR)
)
SELECT
    COUNT(DISTINCT CAST(c.nis AS BINARY)) AS candidate_count,
    COUNT(DISTINCT s.id) AS matching_siswa_rows,
    COUNT(DISTINCT u.id) AS matching_usernames
FROM candidate_nis AS c
LEFT JOIN siswa AS s ON CAST(s.nis AS BINARY) = CAST(c.nis AS BINARY)
LEFT JOIN users AS u ON CAST(u.username AS BINARY) = CAST(c.nis AS BINARY);
```

## 6. User–Siswa Reconciliation

### Fakta kode

- `Siswa::user()` adalah `belongsTo(User::class, 'user_id')`; `User::siswa()` adalah `belongsTo(Siswa::class, 'siswa_id')`.
- Migration menambah kedua FK nullable dengan `ON DELETE SET NULL`; migration source yang dibaca tidak menunjukkan unique constraint pada kedua kolom relasi tersebut.
- `LoginRequest` mencari `Siswa` berdasarkan `nis`, lalu memakai `$siswa->user`; jalur login siswa dengan demikian mengikuti `siswa.user_id`.
- Konfirmasi daftar ulang PPDB membuat User dan Siswa dalam transaksi, kemudian menulis `siswa.user_id` dan `users.siswa_id`.
- `GenerateUsername` mencari siswa melalui `siswa.user_id` dan menulis `users.username = siswa.nis`.
- `Admin\UserController::destroy()` menghapus User; FK `siswa.user_id` dapat di-null-kan oleh referential action. Dampak data aktual tidak diperiksa.

### Status reconciliation

| ID | Yang harus direkonsiliasi | Status | Dampak |
|---|---|---|---|
| REL-01 | Null, mismatch silang, orphan, duplicate link, role siswa/non-siswa, soft-deleted link | UNVERIFIED | Jangan auto-link, backfill, merge, atau deprecate field. |
| REL-02 | Relasi operasional login dan hubungan yang dipakai workflow | PARTIALLY VERIFIED pada source; aktual belum | Login bergantung `siswa.user_id`; relasi kanonis tetap membutuhkan OD-11. |
| REL-03 | Penghapusan User/Siswa, restore, force delete, dan relink | PARTIALLY VERIFIED pada source; aktual belum | Dapat memutus FK operasional; tidak diketahui apakah ada histori eksternal/backup. |

**Rekomendasi teknis:** pertahankan `siswa.user_id` sebagai kandidat relasi operasional karena dipakai LoginRequest, tetapi jangan mengubah relasi atau melakukan backfill sebelum inventory agregat, keputusan eksplisit owner (OD-11), dan prosedur konflik/relink disetujui.

## 7. Mutation Path Inventory

### 7.1 Jalur yang ditelusuri ke operasi data

| ID | Entry point / aktor | Sumber / method | Writer aktual dan data yang berubah | Risiko bypass / implikasi |
|---|---|---|---|---|
| MUT-01 | Route publik PPDB → `PublicController::store()` | `app/Http/Controllers/PPDB/PublicController.php::store`; route `routes/ppdb-public.php` | Membuat pendaftar dan data orang tua; tidak membuat row Siswa/NIS. | Data identitas pendaftar bukan bukti entry history atau cohort Siswa. |
| MUT-02 | Route publik PPDB → `PublicController::simpanDaftarUlang()` | `app/Http/Controllers/PPDB/PublicController.php::simpanDaftarUlang`; route `routes/ppdb-public.php` | Mengubah status/tanggal daftar ulang pada pendaftar; tidak menerbitkan NIS. | Hanya status prasyarat; tidak sama dengan penerbitan. |
| MUT-03 | `tu/ppdb/pendaftar/{id}/terima` → `PendaftarController::terima()` | `app/Http/Controllers/PPDB/PendaftarController.php::terima`; `routes/ppdb-admin.php` | Mengubah status PPDB menjadi diterima; tidak membuat NIS. | Status PPDB saja tidak membuktikan placement/entry grade. |
| MUT-04 | `tu/ppdb/daftar-ulang/{id}/konfirmasi` → `konfirmasiDaftarUlang()` → `generateNis()` | `app/Http/Controllers/PPDB/PendaftarController.php::konfirmasiDaftarUlang/generateNis`; `routes/ppdb-admin.php` | Dalam transaksi per pendaftar membuat User, Siswa, username=NIS, `angkatan_id`, kedua FK User–Siswa, dan link PPDB. Prefix berasal dari tahun pendaftar; tingkat masuk di-hardcode 1; sequence diambil dari NIS terakhir. | Bukan alfabetis/batch; lock hanya pada row pendaftar, bukan cohort/sequence. Dua konfirmasi berbeda dapat membaca suffix sama; unique NIS dapat menolak salah satu transaksi. Tidak ada retry allocator teramati. |
| MUT-05 | `tu/ppdb/penempatan-kelas/{id}` → `simpanPenempatanKelas()` | `app/Http/Controllers/PPDB/PendaftarController.php::simpanPenempatanKelas`; `routes/ppdb-admin.php` | Membuat/memperbarui placement ke tingkat I dan angkatan Siswa. Berjalan terpisah dari konfirmasi yang menerbitkan NIS. | Saat penerbitan, siswa dapat belum ditempatkan; placement tidak menjadi bagian transaksi penerbitan. |
| MUT-06 | TU `POST /siswa` → `SiswaController::store()` | `app/Http/Controllers/Tu/SiswaController.php::store`; resource route `routes/tu.php` | Menerima NIS dari request; membuat Siswa dan placement dalam transaksi. | Jalur penerbitan/manual NIS alternatif; tidak memakai generator/cohort lock; NIS dapat ditetapkan di luar ledger. |
| MUT-07 | TU `POST /siswa/import` → `importExcel()` → `SiswaImport::collection()` | `app/Http/Controllers/Tu/SiswaController.php::importExcel`; `app/Imports/SiswaImport.php::collection`; `routes/tu.php` | Memakai NIS dari spreadsheet sebagai key `firstOrCreate`; membuat Siswa jika tidak ditemukan dan membuat/mengganti placement. | Bypass issuer; import dapat memperkenalkan NIS yang tidak dihasilkan service. `firstOrCreate` tidak memperbarui NIS row yang sudah ditemukan. |
| MUT-08 | `SiswaSheetImport::collection()` | `app/Imports/SiswaSheetImport.php::collection` | Implementasi sheet serupa: input NIS dipakai pada `firstOrCreate`, placement dibuat/diperbarui. | Pencarian seluruh PHP repository menemukan deklarasi class tetapi tidak menemukan call site; jalur aktifnya belum terbukti. |
| MUT-09 | TU update/delete/restore/force-delete/bulk → `SiswaController` | `app/Http/Controllers/Tu/SiswaController.php::update/destroy/restore/forceDelete/bulkAction`; `app/Models/Siswa.php` | Update mengizinkan perubahan nama tetapi rule `nis: missing`; delete/restore memakai SoftDeletes; force-delete menghapus row; bulk delete/restore berlaku pada banyak row. Migration source juga menyatakan `penempatan_siswa.siswa_id` cascade on delete dan `users.siswa_id` set null. | Nama tidak menulis ulang NIS pada jalur ini. Force-delete dapat menghilangkan satu-satunya row yang mengikat nilai unik, menghapus placement terkait, dan memutus link reverse akun; tidak terlihat ledger permanen pada source. |
| MUT-10 | `php artisan generate:username` → `GenerateUsername::handle()` | `app/Console/Commands/GenerateUsername.php::handle` | Mengubah username User menjadi NIP/NIS dari relasi pegawai/Siswa. | Bukan penerbit NIS, tetapi username collision dapat mengganggu hubungan login; unique runtime migration ada di source. |
| MUT-11 | TU placement/naik kelas → `PenempatanSiswaController`; SuperAdmin approve → `KenaikanKelasController::approve()` | `app/Http/Controllers/Tu/PenempatanSiswaController.php`; `app/Http/Controllers/SuperAdmin/KenaikanKelasController.php::approve`; routes `tu.php` dan `superadmin.php` | Membuat/menutup placement; kenaikan kelas tidak menulis NIS pada kode yang diperiksa. | Mengubah placement aktif yang bisa keliru dipakai untuk menebak entry grade jika desain tidak memakai history resmi. |
| MUT-12 | Transisi tahun ajaran/semester → `TahunAjaranController` | `app/Http/Controllers/Tu/TahunAjaranController.php` (`transisikanKeGenap`, rollover); `routes/tu.php` | Menutup dan membuat placement untuk tahun/semester tujuan; tidak mengubah NIS pada operasi yang diperiksa. | Placement rollover bukan perubahan identitas atau tahun pertama masuk. |
| MUT-13 | Admin menghapus User → `Admin\UserController::destroy()` | `app/Http/Controllers/Admin/UserController.php::destroy`; `database/migrations/2026_05_25_195130_add_user_id_to_siswa_table.php` | Menghapus akun; FK `siswa.user_id` memiliki `nullOnDelete()` pada migration. | Dapat menghilangkan link yang dipakai login meski row Siswa/NIS bertahan. |

### 7.2 Cakupan dan hal yang belum tertutup

- Pencarian read-only pada source mencakup routes, controllers, models, imports, commands, migrations, dan tests. Tidak ditemukan `DB::table('siswa')`/raw `UPDATE siswa` yang menjadi writer tambahan pada pencarian tersebut.
- `SiswaSheetImport` tidak mempunyai call site PHP yang ditemukan, tetapi keberadaan integrasi eksternal tidak dapat dikesampingkan.
- Source yang diperiksa tidak menunjukkan model event/observer NIS yang mengalihkan semua writer ke satu issuer. Pencarian repository tidak dapat membuktikan tidak adanya external SQL, job pada deployment terpisah, operator, atau data pipeline.
- Sebelum implementasi, pemetaan call path harus dipastikan lagi terhadap semua route group/middleware, scheduler/queue deployment, command yang benar-benar dijadwalkan, deployment scripts, serta grant/database integrations. Status TE-18 tetap PARTIALLY VERIFIED.

### 7.3 Test yang ditemukan dan cakupan yang belum ada

| Test source | Cakupan yang terlihat | Batas |
|---|---|---|
| `tests/Feature/SiswaNisImmutabilityTest.php` | NIS manual diterima dan duplicate ditolak; update nama mempertahankan NIS; request update dengan NIS ditolak; form tidak menampilkan field edit NIS. | Tidak menguji permanent ledger, import, force-delete/reuse, pindahan, concurrency atau SQL langsung. |
| `tests/Feature/PpdbDaftarUlangWorkflowTest.php` | Konfirmasi PPDB membuat akun/Siswa dan kedua link; NIS memakai tahun pendaftar, sequence bertambah pada konfirmasi berurutan; konfirmasi ulang idempotent; rollback pada kegagalan; Siswa awal belum ditempatkan. | Sequence serial bukan bukti concurrent allocation; tidak menguji alfabetis, batch cohort, supplemental, transfer atau comparator. |
| `tests/Feature/Auth/AuthenticationTest.php` | Menggunakan NIS Siswa untuk username login. | Bukan uji konsistensi dua FK atau relink/deletion. |
| `tests/Feature/AnnualRolloverTest.php`, `tests/Feature/TahunAjaranSemesterTransitionTest.php` | Memiliki fixture NIS dan menguji operasi tahun/semester/placement. | Tidak ditemukan assertion bahwa rollover/naik kelas mempertahankan NIS immutable. |
| Test lain di `tests/Feature/` | Beberapa membuat NIS sebagai fixture pada modul lain. | Kehadiran fixture bukan coverage kebijakan NIS. |

Tidak ada test NIS Unit yang ditemukan. **Tidak ada test yang dijalankan** pada tahap audit ini.

## 8. Owner Decision Register

**Status yang digunakan:** `OPEN` = belum siap diputuskan/masih menunggu bukti atau belum diajukan sebagai paket lengkap; `READY FOR OWNER REVIEW` = pilihan, dampak, dan rekomendasi telah disiapkan untuk dibahas; `APPROVED` tidak digunakan.

| ID | Prioritas / keputusan yang diperlukan | Pilihan dan dampak | Rekomendasi teknis | Komponen terdampak | Batas waktu keputusan | Status |
|---|---|---|---|---|---|---|
| OD-01 | Tetapkan DB engine dan versi resmi deployment. | MySQL/MariaDB/PostgreSQL/SQL Server/engine lain yang disetujui; menentukan DDL, locking, constraints, upsert dan test. | Owner/Ops menetapkan engine/version secara eksplisit; jangan infer dari default SQLite. | Migration, registry, sequence, concurrency. | Sebelum memilih/membuat migration. | OPEN |
| OD-02 | Tetapkan PHP web runtime dan minimum ekstensi. | Runtime web disamakan dengan CLI/build atau perbedaan dibuat eksplisit; memengaruhi comparator, PDO dan perilaku aplikasi. | Kunci versi SAPI/build/ICU/extensions pada deployment dan CI. | Sorting, login, PDO, production gate. | Sebelum comparator/integrasi ditandatangani. | OPEN |
| OD-03 | Setujui comparator nama Indonesia, normalisasi, versi data, tie-break. | ICU locale yang tervalidasi, dependency/data yang dipin, atau comparator eksplisit dengan golden order owner; fallback `root`/byte sort tidak menjamin urutan Indonesia. | Pilih satu hasil golden yang identik pada runtime target; fail closed bila actual locale/parity gagal. | Initial publication permanen. | Sebelum implementasi comparator dan sebelum initial publication. | OPEN |
| OD-04 | Tentukan kapan akun siswa dibuat relatif terhadap penerbitan NIS. | Akun nonaktif sebelum publish, akun setelah publish, atau identifier sementara; pilihan mengubah PPDB/login/reset/password/email. | Hindari identifier sementara kecuali security review; pilih satu lifecycle konsisten dengan login NIS. | PPDB, account service, login. | Sebelum integrasi akun/PPDB. | READY FOR OWNER REVIEW |
| OD-05 | Tetapkan otoritas/prosedur koreksi setelah initial publish. | Pejabat, bukti, status konflik, laporan dan placement terdampak; tanpa aturan correction unresolved case dapat memblokir cohort. | Append-only correction/conflict; slot/NIS terbit tidak pernah dialihkan atau dipakai ulang. | Correction, supplemental, audit. | Sebelum implementasi correction; sebelum menyelesaikan kasus pertama. | READY FOR OWNER REVIEW |
| OD-06 | Setujui kebijakan konflik NIS legacy dan reservation. | Stop-and-reconcile atau exact-collision reservation dengan approval; auto-skip mengubah urutan/sequence. | Default fail closed; reservation hanya dengan bukti, alasan, approver, dan audit. | Reconciliation, allocator, publikasi cohort konflik. | Setelah inventory, sebelum menangani collision/allocator. | OPEN |
| OD-07 | Tetapkan bukti administratif transfer dan pejabat pengesah. | Dokumen/arsip berbeda atau tidak diterima; menentukan apakah tahun/tingkat pertama masuk dapat diverifikasi. | Wajib ada bukti entry year/grade; jangan menurunkan entry grade dari kelas aktif/sekolah asal. | Siswa pindahan, entry history. | Sebelum menerima/import siswa pindahan ke sistem baru. | READY FOR OWNER REVIEW |
| OD-08 | Tetapkan urutan supplemental publication. | Kronologis `(official_entry_at, nama, siswa.id)` atau sort nama per-batch; mengubah nomor permanen batch selanjutnya. | Kronologis dengan tie-break deterministik sebagai usulan, bukan keputusan. | Supplemental allocator/publisher. | Sebelum supplemental service diimplementasikan. | READY FOR OWNER REVIEW |
| OD-09 | Definisikan placement yang sah untuk membership/publikasi. | Definisi rombel, tahun, tingkat, status, tanggal dan exception; tanpa ini hardblock tidak deterministik. | Tetapkan resolver tervalidasi dan block jika relasi/status tidak lengkap atau bertentangan. | Cohort membership, placement, publisher. | Sebelum service cohort dan integrasi PPDB/TU. | READY FOR OWNER REVIEW |
| OD-10 | Tetapkan tindakan saat username bentrok. | Hardblock candidate dan rekonsiliasi manual vs pilihan batch atomicity/penundaan; rename/merge otomatis berisiko salah akun. | Tidak auto-merge/rename; rekonsiliasi terotorisasi, tidak membocorkan akun lain. | Account linking, publication. | Sebelum integrasi akun/publisher. | READY FOR OWNER REVIEW |
| OD-11 | Setujui relasi kanonis User–Siswa dan nasib FK legacy. | `siswa.user_id` atau alternatif; backfill/deprecate `users.siswa_id` tanpa inventory dapat salah menghubungkan akun. | `siswa.user_id` sebagai kandidat karena dipakai LoginRequest; owner menyetujui hanya setelah agregat mismatch/orphan tersedia. | Auth, migration, account lifecycle. | Sebelum account migration/backfill. | OPEN |
| OD-12 | Setujui least-privilege dan perlindungan direct SQL ledger. | Pemisahan runtime/migration/DBA role, trigger/privilege atau mekanisme lain; tanpa kontrol DB service dapat dilewati. | Runtime tidak memiliki UPDATE/DELETE pada ledger/slot yang sudah diterbitkan; DBA access diaudit. | Schema, registry immutability, activation. | Sebelum klaim immutability atau mengaktifkan writer. | OPEN |
| OD-13 | Tetapkan batas ukuran batch dan atomicity. | Batas volume berbasis benchmark; all-or-nothing atau strategi chunk setara atomisitas. | All-or-nothing sampai load test membuktikan threshold dan recovery; chunk hanya dengan kontrak setara. | Transaction, lock, publisher, recovery. | Sebelum production publication. | OPEN |
| OD-14 | Tetapkan otoritas timestamp, UTC dan precision. | DB clock/UTC, presisi, failover/equality behavior; menentukan cutoff dan urutan. | DB time UTC dengan logical monotonic sequence per cohort seperti desain usulan. | Entry history, cutoff, publication. | Sebelum entry/cutoff implementation. | READY FOR OWNER REVIEW |
| OD-15 | Setujui aturan year-code dari label tahun ajaran. | Aturan label/semester/duplikasi/koreksi/century rollover; contoh desain `2026/2027 → 2627` harus tetap menjadi fixture. | Aturan parser eksplisit dan menolak label ambiguous; persetujuan generalisasi oleh owner. | Cohort key, allocator, legacy reconciliation. | Sebelum menetapkan cohort/migrasi entry history. | READY FOR OWNER REVIEW |
| OD-16 | Tetapkan delete, restore, force-delete dan relink Siswa/akun. | Retensi/deaktivasi akun, restore dengan NIS lama, relink dengan bukti/approver; tanpa aturan force-delete dapat memutus identity. | Registry tetap menyimpan record immutable; relink ter-audit, tidak membebaskan sequence. | Delete lifecycle, Auth, registry. | Sebelum delete/account integration dan activation. | READY FOR OWNER REVIEW |
| OD-17 | Pilih penegakan satu initial batch per cohort. | Guard/generated uniqueness/partial unique atau equivalent bergantung engine; tanpa itu dua initial batch mungkin bersaing. | Cohort lock ditambah unique constraint sebagai pertahanan kedua, ditentukan setelah OD-01. | Batch schema, publisher, retry. | Sebelum migration batch dan concurrent test. | READY FOR OWNER REVIEW |
| OD-18 | Tetapkan role publisher/reservation/correction dan separation of duties. | Role/permission, approver, requester boleh/tidak menjadi approver; menentukan otorisasi tindakan sensitif. | Actor snapshot, audit event dan pemisahan tugas untuk reservation/correction sesuai kontrol owner. | Authorization, publication, audit. | Sebelum endpoint terlindungi diaktifkan. | READY FOR OWNER REVIEW |

Tidak ada baris berstatus APPROVED. Keputusan dapat diselesaikan bertahap jika komponennya independen, tetapi keputusan blocker harus disetujui sebelum komponen yang bergantung padanya diimplementasikan/diaktifkan.

## 9. Remaining Technical Evidence

Status di bawah memperbarui TE register Tahap 2A.4. “Partially verified” tidak berarti deployment telah terverifikasi.

| ID | Kondisi sekarang / sumber | Cara penutupan | Status |
|---|---|---|---|
| TE-01 | Driver didukung source; MySQL binary/proses lokal ada; tidak ada inspeksi koneksi/schema deployment. | Ops mengonfirmasi engine/version; DBA memberi katalog/schema pada environment resmi. | PARTIALLY VERIFIED |
| TE-02 | PHP CLI lokal 8.2.29 teruji; manifest mensyaratkan `^8.2`. | Catat CLI/build yang dipakai CI/deployment dan bedakan dari web SAPI. | VERIFIED untuk lokal; deployment UNVERIFIED |
| TE-03 | Apache dan `php-cgi.exe` tersedia; fcgid terlihat, tetapi SAPI aplikasi tidak dibuktikan. | Bukti konfigurasi handler/vhost serta probe terkontrol SAPI web non-public. | PARTIALLY VERIFIED |
| TE-04 | `composer.lock` Laravel 12.40.2; Composer lokal 2.4.1; deployment artifact tidak diperiksa. | Cocokkan lock hash dan `vendor` deployment dengan build yang disahkan. | PARTIALLY VERIFIED |
| TE-05 | Intl/Normalizer/Collator/mbstring/PDO dan PDO mysql/sqlite ada pada CLI lokal. | Ulangi inventaris pada web SAPI dan runtime deployment/build target. | PARTIALLY VERIFIED |
| TE-06 | ICU lokal 71.1; Collator `id-ID` actual locale `root`. | Tetapkan comparator/locale pada target dan bukti actual locale serta versi data. | PARTIALLY VERIFIED; blocker sorting |
| TE-07 | Probe Unicode lokal tersedia; belum ada comparator aplikasi/golden order lintas runtime. | Jalankan fixture SORT-01–12 pada seluruh target runtime dan cocokkan signature. | UNVERIFIED |
| TE-08 | Tidak ada DB connection/lock probe. | Dua koneksi pada disposable staging dengan engine/version target; catat isolation/lock/deadlock/retry. | UNVERIFIED |
| TE-09 | Tidak ada bootstrap/upsert/rollback test pada DB target. | Probe race cohort/sequence dan rollback pada staging. | UNVERIFIED |
| TE-10 | Migration baru NIS belum ada; engine deployment tidak diketahui. | Uji unique/check/partial/generated/FK di katalog dan pada target DB. | UNVERIFIED |
| TE-11 | Source FK memakai `nullOnDelete()`; tidak ada trigger immutability aktif yang diuji. | Uji referential action dengan trigger pada DB target. | UNVERIFIED |
| TE-12 | Grant/role/direct SQL/external ETL tidak diperiksa. | DBA audit grant dan uji izin runtime terpisah pada staging. | UNVERIFIED |
| TE-13 | Dua relasi nullable terlihat di model/migration; row aktual tidak diperiksa. | Jalankan agregat LGC-Q3 dan analisis conflict terlindungi. | UNVERIFIED |
| TE-14 | NIS unique pada migration source; data termasuk soft-delete tidak diinventaris. | Jalankan LGC-Q1/Q2, cek byte/collation dan disposition per konflik secara berizin. | UNVERIFIED |
| TE-15 | Login/username code diketahui; benturan username aktual tidak diperiksa. | Agregat relasi/username dan kandidat NIS dengan query terlindungi. | UNVERIFIED |
| TE-16 | Migration source mendefinisikan unique NIS dan FK; schema aktif tidak diperiksa. | Katalog DB deployment: kolom/index/FK/action/unique/soft-delete. | PARTIALLY VERIFIED |
| TE-17 | Call paths PPDB/TU/import/placement ditemukan; kondisi placement/PPDB aktual tidak dibaca. | Query agregat relasi status, orphan dan multiple active placement; verifikasi resolver. | PARTIALLY VERIFIED |
| TE-18 | Writer source utama dipetakan pada §7; external calls/privilege belum ditutup. | Final call graph termasuk routes, scheduler/jobs, deployment commands, ETL/raw SQL/grants. | PARTIALLY VERIFIED |
| TE-19 | Tidak ada backup/restore test atau bukti target feature matrix. | Ops/DBA identifikasi target dan lakukan restore drill staging. | UNVERIFIED |
| TE-20 | Volume/transaction/lock-duration tidak diukur. | Benchmark sintetis di staging representatif dan tetapkan batas/alert. | UNVERIFIED |

## 10. Updated Blocker Register

| ID | Perubahan bukti dibanding 2A.4 | Status sekarang | Syarat penutupan |
|---|---|---|---|
| BL-01 | Database config dan executable/proses MySQL lokal diketahui; deployment masih tidak diketahui. | OPEN — sedikit dipersempit, tidak tertutup. | TE-01/19 dan konfirmasi engine/version deployment. |
| BL-02 | PHP CLI 8.2.29, Laravel 12.40.2, serta ekstensi lokal terverifikasi; web/deployment runtime belum. | OPEN — CLI portion closed only. | TE-02–05 untuk CI/build dan web runtime target. |
| BL-03 | Actual locale `root` terkonfirmasi pada CLI lokal; comparator aplikasi/golden/parity belum ada. | OPEN — fail closed. | OD-03 dan TE-06/07 lulus di target. |
| BL-04 | Format/soft-delete schema source dan query pack dipersiapkan; data legacy tetap tidak diperiksa. | OPEN. | TE-14/17; disposition owner untuk data conflict sebelum allocator. |
| BL-05 | Login path dan kedua relasi model/migration telah dipastikan; actual rows belum. | OPEN. | TE-13/15 dan OD-11; tanpa auto-link. |
| BL-06 | `nullOnDelete()` FK source ditemukan; grant/trigger/direct SQL belum diuji. | OPEN. | OD-12, TE-11/12, dan direct SQL acceptance test. |
| BL-07 | Generator PPDB memakai tahun pendaftar dan entry grade hardcoded 1; timestamp/year mapping umum belum disetujui. | OPEN. | OD-14/15 dan test target; kebijakan transfer tetap memerlukan OD-07. |
| BL-08 | Jalur PPDB/TU/import dan placement dipetakan; aturan transfer, correction, supplemental, placement, reservation, username belum disetujui. | OPEN. | OD-05–10 dan OD-18 untuk tahap terkait. |
| BL-09 | Belum ada volume/lock benchmark. | OPEN. | OD-13, TE-20, recovery/retry acceptance tests. |
| BL-10 | Source writer utama telah ditemukan, termasuk jalur manual/import/command; external integrations belum ditutup. | OPEN — peta lebih lengkap, belum exhaustif. | TE-18; semua writer diarahkan ke protocol/service sebelum aktivasi. |

### Risiko kritis tersisa

1. **Penerbitan tak alfabetis dan tak atomic lintas cohort:** current PPDB sequence bergerak mengikuti urutan konfirmasi admin; concurrent prefix collision mungkin menghasilkan duplicate attempt yang ditolak unique constraint, bukan sequence allocator yang aman.
2. **Legacy dan permanence:** tidak ada bukti inventory atau ledger aktif; force-delete dapat menghilangkan row yang menjadi satu-satunya constraint NIS.
3. **Akun:** relasi silang tidak dibuktikan konsisten; User delete dapat null-kan FK yang dibutuhkan login; username disalin dari NIS melalui lebih dari satu writer.
4. **Jalur bypass:** manual TU, import, dan PPDB mengatur NIS/username di luar satu layanan penerbitan terpusat.
5. **Kelas bukan entry history:** promosi/rollover mengubah placement; tidak boleh dipakai untuk menyimpulkan entry grade atau cohort awal.
6. **Deployment controls:** comparator locale, engine semantics, grant, trigger/FK action, batch volume dan recovery belum dibuktikan pada target.

## 11. Final Readiness Verdict dan Rekomendasi Tahap Berikutnya

# NOT READY FOR IMPLEMENTATION

Tahap berikutnya **belum boleh mulai mengimplementasikan database dan service NIS**. Pemeriksaan lokal tidak menggantikan persetujuan database/runtime deployment, golden comparator yang lulus pada target, inventory legacy dan User–Siswa, keputusan owner, serta bukti least privilege/concurrency/recovery. Kesimpulan ini konsisten dengan Tahap 2A.4.

### Jawaban final

| Pertanyaan | Jawaban |
|---|---|
| Hambatan apa yang berhasil ditutup? | Bukti lokal CLI, Composer/Laravel, ICU/ext, executable/proses MySQL lokal, jalur source utama, relasi login, serta cakupan test yang relevan kini teridentifikasi. Tidak ada blocker deployment/data yang dinyatakan tertutup. |
| Hambatan apa yang masih terbuka? | BL-01 sampai BL-10 tetap terbuka; beberapa hanya dipersempit. Sorting tetap fail closed, legacy/relasi aktual tidak diketahui, dan writer eksternal belum ditutup. |
| Bukti apa yang belum tersedia? | Engine/schema/grants deployment, PHP web SAPI, isolation/locking/constraint/trigger behavior, data agregat NIS dan relasi, backup/restore, external writer map, serta benchmark/recovery target. |
| Keputusan apa yang membutuhkan owner? | OD-01–OD-18; tidak ada keputusan dianggap approved. OD-03, OD-06, OD-11, OD-12, dan OD-13 khususnya membutuhkan bukti teknis sebelum keputusan final yang aman. |
| Apakah ada perubahan temuan dibanding 2A.4? | Ya, bukti lokal CLI kini lebih spesifik; actual locale `root` kembali dikonfirmasi; jalur mutasi dan test NIS diperinci. Tidak ada perubahan verdict atau bukti deployment/data. |
| Apakah implementasi sebagian boleh dimulai? | **Belum boleh memulai implementasi database/service NIS atau mengaktifkan writer.** Persiapan dokumen, fixture, query DBA read-only dan test specification boleh berlanjut; coding schema/service menunggu blocker komponen terkait ditutup dan persetujuan owner. |

### Rekomendasi Tahap Berikutnya

1. Minta Ops/DBA mengidentifikasi secara tertulis environment yang berwenang untuk bukti database dan web runtime; selesaikan TE-01–05, TE-08–12, TE-19 tanpa membagikan secret.
2. Minta owner meninjau OD-01–OD-18 dengan prioritas: database/runtime; comparator dan year/timestamp; relasi akun dan legacy; transfer/placement/correction; kemudian supplemental/operasional/authorization.
3. Jalankan query inventory read-only hanya DBA pada target yang disetujui. Sajikan agregat dan jalur aman untuk menginvestigasi konflik individual tanpa memasukkan PII ke laporan.
4. Sahkan aturan comparator terlebih dahulu, kemudian jalankan golden fixture pada web SAPI dan build target. Actual locale `root` saat ini wajib tetap fail closed.
5. Lengkapi writer/call graph termasuk scheduled command, queue, external SQL dan grants; tentukan transisi setiap writer menuju service/lock protocol desain.
6. Setelah blocker untuk komponen tertentu ditutup, lakukan design sign-off per komponen. Persiapan fixture/test spesifikasi dapat berjalan, tetapi tidak termasuk perubahan schema/data atau aktivasi penerbitan.

Tidak ada test, migration, seeder, koneksi database, perubahan source, konfigurasi, dependency, atau data yang dijalankan/diubah dalam Tahap 2A.5. Worktree sebelumnya dipertahankan; laporan ini adalah satu-satunya file baru tahap ini.
