# TAHAP 2A.6 — LOCAL ENVIRONMENT & SCHEMA VERIFICATION

**Sistem:** SINERGI-MJ  
**Jenis:** Audit lokal read-only  
**Verdict:** **NOT READY FOR IMPLEMENTATION**  
**Cakupan data:** koneksi efektif aplikasi lokal pada saat pemeriksaan; tidak diasumsikan lengkap/representatif bagi data historis atau environment lain.

## 1. Executive Summary

Koneksi efektif Laravel lokal berhasil dibuktikan tanpa menampilkan credential. Project memakai MySQL 8.0.30 pada schema `sekolah_app_testing`, InnoDB, isolation `REPEATABLE-READ`. Laravel config cache tidak aktif; 109 migration source tercatat diterapkan di tabel `migrations`, tanpa source migration yang belum tercatat.

Hasil utama:

- CLI memakai PHP 8.2.29; dependency Laravel efektif 12.40.2; Composer 2.4.1; Node.js 22.19.0 dan npm 10.9.3. Laragon teridentifikasi sebagai 6.0.0.
- Proses web Laravel yang berjalan adalah `php artisan serve`, diluncurkan dengan executable PHP 8.2.29 yang sama dan bind `127.0.0.1:8000`. Apache 2.4.54 juga berjalan pada 80/443; modul `fcgid` tersedia, tetapi handler PHP untuk vhost Apache tidak dibuktikan.
- Skema aktif memuat `ppdb_pendaftar` (singular), bukan `ppdb_pendaftars`. Model dan migration juga menyebut nama singular.
- Snapshot database memiliki 1 row Siswa aktif dan 0 soft-deleted; tidak ada NIS kosong, format menyimpang dari pola struktural `NNNN.N.NNN`, exact duplicate, atau collision setelah lower/trim/whitespace-collapse. Angka ini hanya menggambarkan schema lokal saat query.
- Relasi saat ini: 1 Siswa dengan `user_id`, 1 User dengan `siswa_id`, 1 User tanpa Siswa; tidak teramati orphan, mismatch silang, atau relasi ganda. Tidak ada persetujuan owner atas relasi canonical.
- PPDB: 3 pendaftar; satu diterima/daftar ulang selesai dan terhubung ke Siswa; dua ditolak tanpa link Siswa. Tidak teramati orphan PPDB link atau link ke soft-deleted Siswa.
- Placement: satu tahun ajaran berstatus aktif dan satu placement terbuka; tidak teramati duplicate placement, mismatch angkatan/rombel, atau Siswa aktif tanpa placement. Kolom legacy `siswa.kelas_id` kosong untuk placement yang ada.
- `utf8mb4_unicode_ci` pada database membandingkan `A` dengan `a` sama, `e` dengan `é` berbeda, dan `x` dengan `x ` sama. Ini perilaku collation, bukan comparator alfabetis yang disahkan.
- `Collator('id-ID')` lokal menghasilkan actual locale `root`; fixture SORT-01–12 dicoba dan menghasilkan urutan teramati, tetapi comparator tetap **FAIL-CLOSED**.
- Akun koneksi aplikasi mempunyai grant global pada `*.*`, termasuk DML/DDL/administratif dan `WITH GRANT OPTION`. Pemeriksaan sendiri tetap hanya menggunakan SELECT/metadata/SHOW; grant yang luas adalah risiko konfigurasi, bukan izin untuk melakukan perubahan.

Kesimpulan: bukti lokal menutup beberapa ketidakpastian lingkungan, skema, dan agregat snapshot. Keputusan owner, comparator, concurrency/recovery, pembatasan grant, serta asal/entry history data belum selesai. Tidak ada test, migration, seeder, HTTP request, atau operasi tulis dijalankan.

## 2. Scope dan Read-only Guarantee

### Pemeriksaan

- Membaca manifest/lockfile, source model, migration, konfigurasi, report Tahap 2A.5, dan state proses lokal.
- Bootstrap konfigurasi Laravel efektif tanpa mencetak credential.
- Koneksi menggunakan driver yang terpilih aplikasi; hanya `SELECT`, metadata `information_schema`, dan `SHOW GRANTS`.
- Inventaris record hanya agregat; tidak membaca atau mencetak nama siswa, NIS, NISN, username, email, password, token, maupun data keluarga.
- Probe sorting memakai fixture sintetis.

### Tidak dilakukan

Tidak ada INSERT, UPDATE, DELETE, REPLACE, ALTER, DROP, TRUNCATE, migration, seeder, test suite, instalasi/update dependency, perubahan `.env`, konfigurasi Laragon/MySQL, atau HTTP request. Tidak ada perubahan pada data atau source aplikasi. Credential dari `.env` tidak ditampilkan.

**Catatan akun:** koneksi berhasil dengan akun yang mempunyai hak tulis yang luas. Query audit tidak menggunakan hak tersebut untuk mengubah data.

## 3. Local Environment Profile

| Bukti | Hasil lokal | Batas |
|---|---|---|
| Root project | `C:\laragon\www\sistem-informasi-sekolah` | Verified dari workspace. |
| Laragon | Product version 6.0.0 | Versi executable lokal; tidak membuktikan versi komponen lain di deployment. |
| PHP | 8.2.29 NTS x64 | PHP CLI; lihat §4 untuk proses web. |
| Laravel | 12.40.2 (`artisan --version`) | Versi vendor lokal. |
| Composer | 2.4.1 | Executable lokal. |
| Node.js / npm | 22.19.0 / 10.9.3 | Executable lokal; tidak dibutuhkan untuk audit database. |
| MySQL | 8.0.30 Community Server | Terhubung melalui konfigurasi aplikasi lokal; bukan server deployment. |
| Laravel default driver | `mysql` | Dibaca dari konfigurasi efektif setelah bootstrap. |
| Schema aktif | `sekolah_app_testing` | Nilai `DATABASE()` dari koneksi; nama tidak dipakai untuk menyimpulkan isi/tujuan beyond label. |
| Config cache | Tidak aktif (`bootstrap/cache/config.php` tidak ada; `configurationIsCached() = false`) | Konfigurasi efektif dimuat dari konfigurasi runtime/`.env` tanpa menampilkan secret. |
| SQLite file default | `database/database.sqlite` tidak ada | Tidak mengubah fakta driver efektif adalah MySQL. |
| Server/timezone/isolasi | MySQL 8.0.30; InnoDB; `REPEATABLE-READ`; session/global time zone `SYSTEM` | Hasil query koneksi lokal saat audit; belum menguji clock drift/failover. |

## 4. PHP CLI dan Web Runtime

### CLI

PHP CLI eksplisit `C:\laragon\bin\php\php-8.2.29-Win32-vs16-x64\php.exe` melaporkan:

- PHP 8.2.29, SAPI `cli`.
- `intl` tersedia; ICU 71.1.
- `Normalizer`, `Collator`, `mbstring`, dan PDO tersedia.
- PDO drivers: `mysql`, `sqlite`.

### Web

- Proses yang berjalan menunjukkan `php artisan serve` dan child `php.exe -S 127.0.0.1:8000 ... Illuminate\Foundation\Console/.../server.php`.
- Executable path keduanya adalah PHP 8.2.29 Laragon yang sama dengan CLI. Dengan demikian runtime server Laravel lokal yang berjalan memakai binary tersebut dan built-in PHP server (`cli-server`).
- Apache 2.4.54 berjalan pada port 80/443. `httpd -M` menunjukkan `fcgid_module`; tidak ada `php_module`. Tidak ada `php-cgi.exe` worker yang teramati.
- Tidak dilakukan HTTP request untuk menghindari side effect session/log atau route aplikasi.

**Status:** runtime `artisan serve` terbukti dari proses aktif; PHP-FCGI/vhost Apache yang melayani aplikasi tidak terbukti. Jika pengguna aplikasi mengakses Apache, bukti konfigurasi handler/vhost masih diperlukan.

## 5. Database Connectivity

Koneksi Laravel berhasil. Query baca menghasilkan:

- `DATABASE()`: `sekolah_app_testing`.
- `VERSION()`: `8.0.30`, MySQL Community Server.
- Tabel relevan yang diperiksa menggunakan InnoDB.
- `@@transaction_isolation`: `REPEATABLE-READ`.
- `@@session.time_zone` dan `@@global.time_zone`: `SYSTEM`.
- Koneksi menggunakan `mysql`; config cache tidak aktif.

`SHOW GRANTS` dibaca dan account identity disembunyikan. Grant global pada `*.*` mencakup SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, ALTER, berbagai hak administratif termasuk CREATE USER/FILE/SHUTDOWN, dan `WITH GRANT OPTION`.

**Dampak:** ini bukan least-privilege application account. Pembatasan privilege memerlukan keputusan/aksi administrator terpisah; tidak ada perubahan grant dilakukan. Keberhasilan koneksi tidak berarti account tersebut aman untuk publisher/ledger.

## 6. Actual Database Schema

Database lokal memuat 50 tabel; seluruh 12 tabel akademik/PPDB yang diperiksa adalah base table InnoDB dengan collation tabel `utf8mb4_unicode_ci`.

| Tabel | Kolom/kendala aktual yang relevan |
|---|---|
| `siswa` | `id`, nullable `user_id`, nullable `angkatan_id`, `nama`, `nis` NOT NULL `varchar(255)`, nullable `nisn`, nullable `kelas_id`, `deleted_at`; unique index `siswa_nis_unique`, `siswa_nisn_unique`; FK `user_id → users.id ON DELETE SET NULL`, `kelas_id → kelas.id ON DELETE SET NULL`. `angkatan_id` tidak memiliki FK/index pada schema yang terbaca. |
| `users` | Nullable `siswa_id`, nullable `username`; `username` unique; `siswa_id` hanya FK `ON DELETE SET NULL`, bukan unique. |
| `ppdb_pendaftar` | `siswa_id` nullable FK `ON DELETE SET NULL`, bukan unique; `status`, `status_daftar_ulang`, `tahun_ajaran_id`, `nama`, dan kolom daftar ulang/PPDB lainnya tersedia; unique `nik` dan `no_pendaftaran`. |
| `ppdb_orangtua` | FK `ppdb_pendaftar_id ON DELETE CASCADE`; jenis enum ayah/ibu. |
| `penempatan_siswa` | FK Siswa/Kelas/Tahun Ajaran dengan `ON DELETE CASCADE`; `tanggal_masuk`, nullable `tanggal_keluar`; tidak ada unique index pada `(siswa_id,tahun_ajaran_id,tanggal_keluar)`. Ada index `siswa_id`. |
| `kelas` | Nullable `kelas_grup_id`, `nama_kelas`, `deleted_at`; unique `(kelas_grup_id,nama_kelas)`. |
| `kelas_grup` | Nullable `angkatan_id` dengan FK `SET NULL`; `kelas_master_id` FK; unique `(angkatan_id,kelas_master_id)`. |
| `kelas_master` | Kolom `tingkat`. |
| `angkatan` | Kolom `tahun` unique. |
| `tahun_ajaran` | `tahun`, semester enum, legacy boolean `aktif`, nullable status enum draft/aktif/selesai. |
| `ppdb_settings` | FK tahun ajaran dan unique `tahun_ajaran_id`. |
| `keputusan_kenaikan` | Unique `(siswa_id,tahun_ajaran_id)` dan FK ke Siswa/Kelas/Guru/Tahun Ajaran. |

Trigger pada `siswa`, `users`, `ppdb_pendaftar`, `penempatan_siswa`: **0**. CHECK constraints pada tabel terpilih: **0**. Ini adalah inspeksi schema lokal, bukan bukti database lain.

## 7. Migration vs Actual Schema Comparison

| Topik | Migration/source | Schema aktif | Kesimpulan |
|---|---|---|---|
| History migration | 109 file `database/migrations/*.php`; database `migrations` berisi 109 rows. | Tidak ada file source yang belum tercatat. Migrasi terbaru tercatat `2026_10_03_000000_create_notifications_table`, batch 2. | Repository dan migration repository cocok pada daftar nama; ini tidak membuktikan semua data/DDL bebas drift. |
| NIS | `2025_12_05_173923_init_all_tables.php` membuat `siswa.nis` unique; column nullable tidak dideklarasikan. | `siswa.nis` NOT NULL `varchar(255)`, unique index ada. | Cocok untuk constraint dasar NIS. |
| NISN | Migration menambahkan NISN; source mendefinisikan unique. | Nullable `nisn` memiliki unique index. | Cocok pada schema yang diperiksa. |
| `siswa.kelas_id` | Base migration membuat FK nullable; `2026_02_27_123758_drop_kelas_id_from_siswa.php` `up()`/`down()` kosong. | Kolom dan FK `SET NULL` tetap ada. | Cocok dengan implementasi migration yang sebenarnya; nama file “drop” tidak mencerminkan operasi. |
| `siswa.angkatan_id` | `2026_01_27_080319_add_angkatan_to_siswa_table.php` hanya menambah unsigned big integer nullable, tanpa `constrained()`. | Kolom ada tanpa FK/index. | Cocok; referential integrity untuk nilai ini tidak ditegakkan DB. |
| Penempatan aktif | Initial migration membuat unique composite; `2026_03_02_142005_drop_duplicate_unique_penempatan_index.php` menambahkan index siswa dan menjatuhkan unique; `2026_03_09_024539_drop_unique_penempatan_per_tahun.php` kosong. | Tidak ada unique constraint yang mencegah lebih dari satu placement aktif. | Cocok dengan migration chain; duplicate/multiple active placement tetap mungkin dan harus dicegah/diuji di service/DB lain. |
| PPDB table | `PpdbPendaftar::$table` dan migrations menggunakan `ppdb_pendaftar`. | Tabel aktual `ppdb_pendaftar`; tidak ada `ppdb_pendaftars` di daftar tabel. | Nama singular terverifikasi untuk source dan DB lokal. |
| User–Siswa | Dua migration membuat FK nullable `nullOnDelete()`. | Kedua FK ada, nullable, non-unique; tidak ada trigger. | Cocok untuk FK; schema tidak memaksa pasangan silang konsisten atau satu-ke-satu. |
| PPDB–Siswa | Migration FK `siswa_id` nullable `nullOnDelete()`. | FK ada dan non-unique. | Model `PpdbPendaftar::siswa()` menyatakan `belongsTo`, sedangkan `Siswa::ppdb()` `hasOne`; database tidak mencegah beberapa pendaftar menunjuk satu Siswa. Snapshot kini tidak menunjukkan duplikasi. |

## 8. PPDB Table Naming Verification

Nama tabel aktual yang diverifikasi adalah **`ppdb_pendaftar`**:

- Model: `app/Models/PpdbPendaftar.php`, `protected $table = 'ppdb_pendaftar'`.
- Create migration: `database/migrations/2026_06_06_212249_create_ppdb_pendaftars_table.php` membuat tabel singular `ppdb_pendaftar`.
- Migrations alter PPDB juga memanggil `Schema::table('ppdb_pendaftar', ...)`.
- `information_schema.TABLES` lokal menunjukkan `ppdb_pendaftar`; daftar semua tabel tidak menunjukkan `ppdb_pendaftars`.

Maka query inventory menggunakan **`ppdb_pendaftar`**, bukan nama file migration atau bentuk plural pada nama file.

## 9. Legacy NIS Inventory

Query agregat SELECT mencakup row aktif dan soft-deleted pada tabel `siswa`. Tidak ada nilai NIS atau identitas individual yang dicetak.

| Kategori | Hasil pada snapshot lokal | Penilaian |
|---|---:|---|
| Total Siswa | 1 | Agregat lokal pada waktu pemeriksaan. |
| Aktif / soft-deleted | 1 / 0 | Tidak ada row soft-deleted pada snapshot. |
| NIS NULL/kosong | 0 | Kolom juga NOT NULL pada schema. |
| Tidak cocok pola struktural `NNNN.N.NNN` | 0 | Pemeriksaan format semata; bukan verifikasi sejarah/entry grade. |
| Duplikat byte-exact | 0 group / 0 extra row | Mencakup row aktif dan soft-deleted. |
| Collision setelah `LOWER`, trim, whitespace collapse | 0 group / 0 extra row | Heuristik normalisasi sederhana; bukan kebijakan final comparator. |

**Klasifikasi:**

- **VERIFIED:** agregat di atas benar untuk snapshot `sekolah_app_testing` yang terhubung.
- **NEEDS VERIFICATION:** asal/provenance NIS, tahun dan tingkat masuk, bukti siswa pindahan, apakah snapshot berisi seluruh data sekolah yang perlu direkonsiliasi, serta potensi benturan dengan sequence baru. Tidak ada entry history ledger pada schema saat ini.
- **DATA CONFLICT:** tidak ditemukan konflik struktural yang terukur pada agregat yang dijalankan. Ini tidak membuktikan tidak ada konflik identitas/data di luar snapshot atau pada bukti administrasi.

Tidak dilakukan inferensi tahun masuk dari kelas/angkatan/NIS, reservation, normalisasi data, atau alokasi baru.

## 10. User–Siswa Relationship Audit

| Metrik | Hasil lokal |
|---|---:|
| Siswa dengan / tanpa `user_id` | 1 / 0 |
| User dengan / tanpa `siswa_id` | 1 / 1 |
| Orphan dari `siswa.user_id` / `users.siswa_id` | 0 / 0 |
| Cross-link mismatch (kedua arah) | 0 / 0 |
| User terhubung ke >1 Siswa / Siswa terhubung ke >1 User | 0 / 0 |
| Akun role `siswa` terhubung; username kosong / tidak sama byte-exact dengan NIS | 1; 0 / 0 |

Login tetap terlihat memakai `Siswa::with('user')` setelah mencari berdasarkan NIS pada `app/Http/Requests/Auth/LoginRequest.php`. Hasil snapshot hanya menyatakan pasangan yang ada konsisten pada saat pemeriksaan; tidak menetapkan `siswa.user_id` sebagai relasi canonical. Keputusan OD-11 dan aturan deletion/relink tetap menunggu owner.

## 11. PPDB and Placement Consistency

### Agregat status PPDB

| Status PPDB | Daftar ulang | Jumlah | Dengan `siswa_id` |
|---|---|---:|---:|
| diterima | sudah | 1 | 1 |
| ditolak | belum | 2 | 0 |

Di seluruh 3 pendaftar: 1 dengan link Siswa, 2 tanpa link; orphan link 0; link ke Siswa soft-deleted 0; diterima/daftar-ulang-sudah tanpa link Siswa 0. Tidak dikeluarkan detail pendaftar.

### Placement

- Tahun ajaran dengan `status='aktif'`: 1.
- Placement terbuka pada tahun aktif: 1 untuk 1 Siswa.
- Siswa dengan lebih dari satu placement terbuka pada tahun aktif: 0.
- Siswa aktif tanpa placement pada tahun aktif: 0.
- Placement dengan hierarchy kelas tidak lengkap, class hilang/soft-deleted, atau mismatch `siswa.angkatan_id` vs `kelas_grup.angkatan_id`: 0 pada agregat.
- Satu placement terbuka tidak mempunyai nilai legacy `siswa.kelas_id`; tidak ada ketidakcocokan dua nilai kelas yang terisi. Ini membuktikan kolom legacy tidak dipakai sebagai proksi universal placement, bukan bukti kesalahan penempatan.

Keadaan saat ini tidak memutuskan kelayakan penerbitan NIS, transfer, cohort, atau definisi placement sah. Tidak ada perubahan PPDB/placement dilakukan.

## 12. Sorting and Comparator Verification

Probe dilakukan pada PHP CLI lokal, ICU 71.1, requested locale `id-ID`, Collator strength Tertiary, Normalization ON. Actual locale tetap **`root`**. Fixture menggunakan string sintetis SORT-01–SORT-12 dari Tahap 2A.5.

### Hasil normalisasi yang diamati

- Case-fold membuat SORT-01 `Ayu Putri`, SORT-02 `ayu putri`, SORT-03 `Ayu  Putri`, dan SORT-04 `Ayu Putri` menghasilkan key yang sama setelah whitespace collapse.
- SORT-07 composed `Dewí` dan SORT-08 decomposed `Dewí` menjadi sama setelah NFC; bentuk input SORT-08 berubah oleh NFC.
- Aksen pada `Dewí` tidak dihapus; tanda baca `Ayu-Putri` dan prefix `Dr. Budi` tetap ada pada key.
- Nama identik SORT-11/12 tetap identik; urutan tie bukan deterministik tanpa tie-break ID eksplisit.

### Hasil urutan Collator aktual

Urutan hasil satu run `usort()` pada fixture mentah, memakai Collator `root` di atas (bukan comparator aplikasi):  
`SORT-03, SORT-02, SORT-01, SORT-04, SORT-05, SORT-09, SORT-06, SORT-07, SORT-08, SORT-10, SORT-11, SORT-12`.

Hasil ini bukan golden order Indonesia dan bukan validasi kebijakan kapitalisasi/aksen/punctuation. Database collation probe terpisah untuk `utf8mb4_unicode_ci` memberi: case equality `A=a` **true**, accent equality `e=é` **false**, trailing-space equality `x=x ` **true**.

**Verdict comparator:** **FAIL-CLOSED**. Owner belum menyetujui expected order; runtime parity, comparator final dan tie-break belum dibuktikan. Tidak ada NIS diterbitkan/diubah.

## 13. Mutation Paths Revalidation

Daftar Tahap 2A.5 masih sesuai dengan source yang dibaca; tidak ada jalur penerbitan baru yang ditemukan dalam revalidasi ini.

| Jalur | Bukti source | Dampak relevan |
|---|---|---|
| PPDB konfirmasi daftar ulang | `PendaftarController::konfirmasiDaftarUlang()` memanggil `generateNis()` per pendaftar; `generateNis()` mencari prefix NIS terakhir dan hardcode tingkat 1. | Penerbitan serial bukan batch alfabetis; tidak ada cohort/sequence lock pada jalur yang dibaca. |
| PPDB penempatan kelas | `PendaftarController::simpanPenempatanKelas()` menulis placement terpisah. | NIS dibuat sebelum placement pada jalur konfirmasi. |
| Manual TU | `SiswaController::store()` menerima `nis` dari request dan membuat Siswa+placement. | Writer NIS terpisah dari generator. |
| Import TU | `SiswaController::importExcel()` menjalankan `SiswaImport`; spreadsheet NIS menjadi key `firstOrCreate`. | Bypass generator/registry. |
| Sheet import | `SiswaSheetImport` menulis dengan pola serupa; tidak ditemukan pemanggil PHP repository. | Aktivasi/runtime eksternal tetap belum terbukti. |
| Update/delete/restore | `SiswaController::update()` melarang field NIS; destroy/restore/forceDelete/bulk operation tersedia. | Force delete menghapus row siswa; FK placement cascade, reverse `users.siswa_id` set null; NIS tombstone tidak ada dalam schema lama. |
| Akun/username | `GenerateUsername` menyalin NIS ke username melalui `siswa.user_id`; PPDB membuat username=NIS. | Writer username lebih dari satu; schema unique tidak menjamin canonical link. |
| Placement/rollover/kenaikan | TU placement, `TahunAjaranController`, dan `SuperAdmin\KenaikanKelasController` membuat/menutup placement. | Tidak menulis NIS pada jalur yang diperiksa; placement aktif bukan entry history. |
| Direct SQL/deployment jobs | Grant DB lokal memberi potensi DML/DDL global; job eksternal tidak dapat diinventaris dari source. | Belum ada bukti semua jalur diarahkan ke satu service. |

Audit mutation-path tetap PARTIALLY VERIFIED. Ketiadaan pemanggil di repository tidak membuktikan tidak ada operator, external ETL, atau SQL di luar repository.

## 14. TE-01 sampai TE-20 Evidence Matrix

Status sebelumnya merujuk Tahap 2A.5. Status terbaru dibatasi pada lingkungan lokal yang diperiksa; tidak menyiratkan deployment.

| ID | Deskripsi | Sebelumnya | Terbaru | Bukti aktual / lokasi | Cara verifikasi | Batas dan tindak lanjut |
|---|---|---|---|---|---|---|
| TE-01 | DB engine/version/schema | PARTIALLY VERIFIED | VERIFIED (local) | Laravel effective driver MySQL; schema `sekolah_app_testing`; MySQL 8.0.30/InnoDB. | Laravel bootstrap + SELECT metadata. | Tidak ada deployment terpisah menurut pemilik; revisi status jika target lain muncul. |
| TE-02 | PHP CLI | VERIFIED (local) | VERIFIED | PHP 8.2.29, SAPI cli. | Explicit Laragon PHP executable. | Berlaku untuk binary lokal. |
| TE-03 | PHP web SAPI | PARTIALLY VERIFIED | PARTIALLY VERIFIED | `php artisan serve` aktif dengan binary 8.2.29, bind 127.0.0.1:8000; Apache fcgid aktif, PHP handler Apache tidak diketahui. | Inspeksi proses/listener dan `httpd -M`; tanpa HTTP request. | Jika Apache dipakai untuk akses aplikasi, verifikasi handler/vhost secara read-only. |
| TE-04 | Dependency efektif | PARTIALLY VERIFIED | PARTIALLY VERIFIED | `composer.lock`, Composer 2.4.1; Laravel runtime 12.40.2. | Lockfile, Composer version, `artisan --version`. | Paket penuh/runtime dependency parity belum diekspor. |
| TE-05 | intl/Normalizer/Collator/mbstring/PDO | PARTIALLY VERIFIED | PARTIALLY VERIFIED | PHP CLI memiliki seluruhnya; PDO mysql/sqlite. | PHP extension/class/driver probe. | Web Apache handler tidak terverifikasi. |
| TE-06 | ICU dan actual locale | PARTIALLY VERIFIED | PARTIALLY VERIFIED | ICU 71.1; `id-ID` actual locale `root`. | Collator actual locale probe. | Tidak ada comparator Indonesia yang disahkan. |
| TE-07 | Golden sorting/parity | UNVERIFIED | PARTIALLY VERIFIED | Fixture SORT-01–12 dicoba; order aktual `root` dicatat di §12. | Probe Unicode dan Collator CLI. | Belum ada expected order owner/comparator aplikasi/parity; fail closed. |
| TE-08 | Isolation/row-level locks | UNVERIFIED | PARTIALLY VERIFIED | MySQL InnoDB, `REPEATABLE-READ`; tidak ada contention test. | Query `@@transaction_isolation`, metadata engine. | Row locks/deadlock/retry harus diuji pada staging disposable sebelum publisher. |
| TE-09 | Upsert/bootstrap/rollback semantics | UNVERIFIED | PARTIALLY VERIFIED | Engine lokal dan indeks teridentifikasi; tidak ada race/upsert/rollback test. | Inspeksi schema saja. | Perlu integration/concurrency test, tidak dilakukan read-only. |
| TE-10 | Unique/check/partial/generated/FK | UNVERIFIED | PARTIALLY VERIFIED | Unique index aktual tercatat; CHECK terpilih 0; tidak ada bukti partial/generated support usage. | `information_schema.STATISTICS`, `TABLE_CONSTRAINTS`. | Uji semantics target diperlukan; partial index tidak diasumsikan tersedia. |
| TE-11 | `ON DELETE SET NULL` vs trigger | UNVERIFIED | PARTIALLY VERIFIED | FK metadata `SET NULL`; trigger pada tabel terpilih 0. | `REFERENTIAL_CONSTRAINTS`, `TRIGGERS`. | Tidak menjalankan delete/trigger test; behavior tetap butuh test di DB disposable. |
| TE-12 | Grant/runtime direct SQL | UNVERIFIED | VERIFIED (local; risiko) | `SHOW GRANTS`: global `*.*` DML/DDL/administratif dengan `GRANT OPTION`. | SHOW GRANTS; identitas account disembunyikan. | Akses terlalu luas; owner/DBA harus menentukan least-privilege. Tidak ada perubahan privilege. |
| TE-13 | Data User–Siswa | UNVERIFIED | VERIFIED (local snapshot) | Agregat: 1/0 siswa dengan/tanpa link; mismatch/orphan/duplikasi 0. | SELECT aggregate kedua FK. | Hanya row lokal saat ini; OD-11 tetap owner decision. |
| TE-14 | Legacy NIS/collision/soft-delete | UNVERIFIED | PARTIALLY VERIFIED | 1 aktif, 0 trashed, null/kosong 0, pola menyimpang 0, duplicate/collision agregat 0. | SELECT aggregate termasuk `deleted_at`. | Provenance, entry grade/year, kelengkapan snapshot dan sequence collision belum dibuktikan. |
| TE-15 | Username collision/account relation | UNVERIFIED | PARTIALLY VERIFIED | 1 akun Siswa linked; tidak kosong/tidak cocok byte-exact 0. | Aggregate join Siswa/User. | Kandidat sequence masa depan belum ada; data luar snapshot tidak diuji. |
| TE-16 | Schema aktual/schema source | PARTIALLY VERIFIED | VERIFIED (local) | 12 tabel target, columns/index/FK/actions, 50 tabel total; 109/109 migration tercatat. | `information_schema` + daftar migration. | Tidak membuktikan data bebas drift non-schema atau schema database lain. |
| TE-17 | PPDB/placement consistency | PARTIALLY VERIFIED | VERIFIED (local snapshot) | 3 pendaftar; 1 link Siswa; 1 placement aktif; tidak ada orphan/duplikasi/mismatch terukur. | Aggregate SELECT terhadap tabel singular aktual. | Data sedikit; kriteria kelayakan/placement policy tetap belum disetujui. |
| TE-18 | Mutation path/writers | PARTIALLY VERIFIED | PARTIALLY VERIFIED | Jalur PPDB, TU, import, placement, delete dan command dikonfirmasi kembali. | Source/routes/migrations review. | External writer/scheduler/grants and call path deployment belum lengkap. |
| TE-19 | Backup/restore | UNVERIFIED | NOT VERIFIED | Tidak ada backup/restore drill. | Tidak dilakukan karena bisa memerlukan operasi tulis. | Ops/DBA perlu bukti restore disposable. |
| TE-20 | Volume/transaction benchmark | UNVERIFIED | NOT VERIFIED | Tidak ada benchmark atau load test. | Tidak dilakukan karena bukan read-only. | Ukur pada staging sintetis sebelum batas batch/production publication. |

## 15. BL-01 sampai BL-10 Blocker Matrix

| ID | Status Tahap 2A.5 | Pembaruan 2A.6 | Status blocker | Penutupan yang masih dibutuhkan |
|---|---|---|---|---|
| BL-01 | DB target belum diketahui | Engine/schema lokal aktif telah terverifikasi: MySQL 8.0.30. | PARTIALLY CLOSED (local evidence) | OD-01 tetap owner decision; target lain jika kelak dideploy harus diverifikasi. |
| BL-02 | CLI terbukti; web/deployment belum | `artisan serve` memakai binary 8.2.29; Apache handler belum diketahui. | PARTIALLY CLOSED | Buktikan handler Apache bila menjadi entry web; parity web perlu final probe aman. |
| BL-03 | Comparator actual locale root | Fixture lokal mengonfirmasi order tidak disahkan dan `root`. | OPEN / FAIL-CLOSED | OD-03, expected order dan target parity. |
| BL-04 | Data legacy belum diinventaris | Snapshot 1 Siswa, tidak ada anomali struktural terukur. | PARTIALLY CLOSED | Provenance/history, kelengkapan data dan entry-year/grade/collision masih terbuka. |
| BL-05 | Relasi aktual belum direkonsiliasi | Pasangan lokal konsisten, tanpa orphan/mismatch/duplikasi. | PARTIALLY CLOSED | OD-11 belum disetujui; aturan relink/delete dan dataset lengkap belum terbukti. |
| BL-06 | Privilege/trigger belum diketahui | FK Set Null dan 0 trigger; akun koneksi punya grant global DML/DDL dengan grant option. | OPEN — risiko meningkat | Least-privilege approval, revoke/role design di tahap authorized terpisah, plus tests trigger/direct SQL. |
| BL-07 | Timestamp/year-code belum disetujui | Tidak ada perubahan keputusan; status/tahun aktif tidak menutup policy. | OPEN | OD-14/15 dan target acceptance tests. |
| BL-08 | Policy placement/transfer/supplemental/correction belum disetujui | Snapshot saat ini tidak menunjukkan kontradiksi terukur, tetapi keputusan policy tetap terbuka. | OPEN | OD-05–10 dan OD-18 sesuai workflow. |
| BL-09 | Recovery/volume belum diukur | Tidak ada benchmark atau recovery test. | OPEN | OD-13, TE-19/20 dan retry/rollback tests. |
| BL-10 | Writer map belum exhaustif | Jalur source utama dikonfirmasi; external writer/grants belum tertutup. | OPEN | TE-18; satu protocol issuer dan semua jalur harus diarahkan sebelum activation. |

## 16. Remaining Owner Decisions

Tidak ada keputusan yang berubah menjadi APPROVED. Status berikut mengikuti Tahap 2A.5.

| ID | Status sebelumnya | Topik yang masih menunggu persetujuan |
|---|---|---|
| OD-01 | OPEN | Penetapan DB/version resmi; MySQL 8.0.30 terbukti sebagai koneksi lokal, belum dipilih sebagai kebijakan resmi. |
| OD-02 | OPEN | Runtime/ext minimum; CLI dan `artisan serve` binary diketahui, Apache FCGI belum. |
| OD-03 | OPEN | Comparator, normalisasi, tie-break dan golden order; actual locale `root`. |
| OD-04 | READY FOR OWNER REVIEW | Waktu pembuatan akun terhadap penerbitan NIS. |
| OD-05 | READY FOR OWNER REVIEW | Otoritas dan bukti koreksi setelah publikasi. |
| OD-06 | OPEN | Collision legacy dan reservation. |
| OD-07 | READY FOR OWNER REVIEW | Bukti administratif dan pejabat untuk transfer/tingkat masuk. |
| OD-08 | READY FOR OWNER REVIEW | Urutan batch supplemental. |
| OD-09 | READY FOR OWNER REVIEW | Definisi placement valid dan hardblock. |
| OD-10 | READY FOR OWNER REVIEW | Konflik username. |
| OD-11 | OPEN | Relasi canonical User–Siswa dan nasib FK legacy. |
| OD-12 | OPEN | Hak database, perlindungan direct SQL dan pembagian role; grant lokal terlalu luas. |
| OD-13 | OPEN | Ukuran batch dan atomicity. |
| OD-14 | READY FOR OWNER REVIEW | Otoritas timestamp/timezone/precision. |
| OD-15 | READY FOR OWNER REVIEW | Pemetaan label tahun ajaran menjadi year-code. |
| OD-16 | READY FOR OWNER REVIEW | Delete/restore/force-delete/relink. |
| OD-17 | READY FOR OWNER REVIEW | Penegakan satu initial batch per cohort. |
| OD-18 | READY FOR OWNER REVIEW | Role publish/reservation/correction dan separation of duties. |

## 17. Technical Findings

1. **High — database app account memiliki hak global.** `SHOW GRANTS` menunjukkan grant pada `*.*` termasuk DML, DDL, administratif, dan `GRANT OPTION`. Dampak: kesalahan aplikasi atau credential compromise dapat memengaruhi seluruh MySQL instance, bukan hanya schema ini. Perlu least-privilege review; tidak ada grant yang diubah.
2. **High — placement aktif tidak dilindungi unique constraint.** Migration chain memang menjatuhkan `unique_penempatan_aktif`; schema aktual tidak memiliki unique key yang mencegah dua placement terbuka untuk siswa/tahun yang sama. Snapshot tidak menunjukkan duplikat, tetapi service-level concurrency belum terbukti.
3. **Medium — relasi User–Siswa tidak one-to-one di database.** Kedua FK memiliki referential integrity nullable tetapi tidak unique; mismatch lintas arah tetap dapat dibuat. Snapshot saat ini konsisten.
4. **Medium — `siswa.angkatan_id` tanpa FK.** Kesesuaian cohort tidak dijamin schema. Satu placement aktif yang diperiksa tidak menunjukkan mismatch, tetapi validasi service tetap dibutuhkan.
5. **Medium — PPDB `siswa_id` tidak unique.** Schema mengizinkan beberapa pendaftar menunjuk Siswa yang sama walaupun relasi model tampak one-to-one. Tidak teramati duplikat lokal.
6. **Medium — NIS hanya dipertahankan oleh unique row `siswa`.** Tidak ada NIS registry/tombstone permanen dalam schema yang dibaca; force-delete dapat membebaskan nilai unik. Snapshot tidak punya soft-deleted row dan tidak membuktikan histori force-delete.
7. **Comparator fail-closed.** `root` locale dan urutan fixture tidak membuktikan sorting alfabetis Indonesia.
8. **Jumlah record kecil.** Snapshot agregat berisi 1 Siswa; temuan nol konflik tidak boleh ditafsirkan sebagai jaminan kelengkapan atau kebersihan dataset sekolah.

## 18. Limitations and Unverified Areas

- Snapshot lokal memakai schema bernama `sekolah_app_testing`; tidak diasumsikan mewakili semua data historis ataupun target di masa depan.
- Tidak ada source data administratif yang membuktikan entry year/grade satu-satunya Siswa.
- Tidak ada sequence registry NIS, publication batch, entry history, ledger immutability, atau audit attempt table pada schema saat ini.
- Tidak dilakukan transaction race, lock wait/deadlock, retry, rollback, direct SQL update/delete, FK delete, atau trigger behavior test.
- Tidak dilakukan restore drill, load benchmark, atau query ke external integration.
- Apache berjalan tetapi PHP handler/vhost yang melayani aplikasi tidak terverifikasi; hanya Laravel built-in server process yang teridentifikasi.
- Output sorting adalah satu run lokal atas synthetic fixture, bukan expected output disetujui owner.
- `SHOW GRANTS` mengungkap hak account, bukan credential/account identity; nama user tidak dicetak.
- Tidak ada test/migration yang dijalankan. Audit tidak menetapkan kebijakan atau melakukan perbaikan data.

## 19. Recommended Next Stage

1. Minta owner meninjau OD-01–OD-18; secara khusus OD-12 untuk privilege global dan OD-03 untuk comparator fail-closed.
2. Minta DBA menetapkan application role least-privilege. Perubahan grants harus menjadi tugas tersendiri yang diotorisasi; jangan dilakukan sebagai bagian audit read-only ini.
3. Konfirmasi dengan pemilik data apakah schema lokal `sekolah_app_testing` adalah snapshot data yang perlu direkonsiliasi atau hanya fixture; kumpulkan bukti administrasi untuk entry year/grade secara terpisah dan terlindungi.
4. Siapkan golden fixture expected order hanya setelah keputusan comparator; jalankan pada runtime `artisan serve` dan Apache handler yang ditetapkan, tanpa menerbitkan NIS.
5. Setelah persetujuan dan perubahan terpisah yang berizin, lakukan integration/concurrency/recovery tests pada database disposable; jangan menguji mutasi terhadap snapshot ini.
6. Pertahankan jalur penerbitan fail-closed sampai seluruh writer alternatif dapat diarahkan ke service/lock protocol dan acceptance gates lulus.

## 20. Final Readiness Verdict

# NOT READY FOR IMPLEMENTATION

Database lokal dan sebagian besar schema/data aggregate kini terverifikasi. Namun, fakta tersebut tidak menutup keputusan owner, sorting, concurrency, immutability, privilege, recovery, dan konsistensi writer. Global grant dengan hak administratif adalah risiko tambahan yang harus ditangani melalui proses berotorisasi.

Implementasi database/service NIS dan aktivasi penerbitan **belum boleh dimulai** berdasarkan gate yang ada. Tahap 2A.6 hanya menghasilkan bukti lokal read-only; tidak ada perubahan source, konfigurasi, data, grant, atau dependency. Perubahan worktree yang telah ada sebelum tahap ini dipertahankan; file ini adalah satu-satunya deliverable baru tahap ini.
