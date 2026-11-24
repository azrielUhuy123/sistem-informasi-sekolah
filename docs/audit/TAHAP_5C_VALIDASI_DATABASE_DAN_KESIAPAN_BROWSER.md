# Tahap 5C - Validasi Database dan Kesiapan Browser

## Keputusan akhir

**BELUM SIAP**

Alasan penentu: `.env.testing` dan `.env` sama-sama menunjuk `mysql` di `127.0.0.1:3306` dengan database `sekolah_app_testing`. Berdasarkan bukti yang tersedia, database tersebut tidak dapat dibedakan dari target koneksi aplikasi non-testing dan tidak dapat dinyatakan disposable. Demi keamanan, tidak ada koneksi database, inventaris isi database, migration, test MySQL, pembuatan akun/data, atau server QA yang dilakukan pada tahap ini.

## Lingkungan yang diperiksa

Pemeriksaan dilakukan pada 23 November 2026 pukul 23:18 WIB.

| Komponen | Hasil |
|---|---|
| Workspace | `C:\laragon\www\sistem-informasi-sekolah` |
| Branch / HEAD | `main` / `6349b56c75c9fff815b5cd3ef941ca1770f1d49c` |
| PHP CLI | PHP 8.2.29 |
| PDO drivers | `mysql`, `sqlite` |
| Laravel framework | `v12.40.2` dari `composer.lock` |
| MySQL server executable | MySQL Community Server 8.0.30; proses Laragon `mysqld` berjalan dan mendengarkan pada port 3306 |
| Config cache Laravel | Tidak ada |
| `phpunit.xml` | Mengatur `APP_ENV=testing`; tidak mengoverride koneksi/database |
| Endpoint pada `.env.testing` | `mysql`, `127.0.0.1:3306`, `sekolah_app_testing` |
| Endpoint pada `.env` | Sama persis: `mysql`, `127.0.0.1:3306`, `sekolah_app_testing` |
| Inventaris database server | Tidak dijalankan: koneksi yang dikonfigurasi tidak terbukti terisolasi, dan tidak ada CLI MySQL yang tersedia untuk inventaris aman tanpa memakai kredensial aplikasi |

Nilai yang dilaporkan hanya nama koneksi, host, port, dan nama database; tidak ada password atau credential yang dibaca atau ditampilkan. Nama `sekolah_app_testing` saja tidak membuktikan bahwa database tersebut aman. Karena target `.env.testing` identik dengan `.env`, target itu **tidak dianggap sebagai QA disposable**. Tidak ada SQL yang dikirim ke MySQL.

### Keamanan pengujian

- Semua test yang dijalankan pada tahap ini secara eksplisit menggunakan environment proses `APP_ENV=testing`, `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`, dan `DB_FOREIGN_KEYS=true`.
- Test workflow memakai `RefreshDatabase` hanya pada SQLite in-memory. Tidak ada `.env`, `.env.testing`, `phpunit.xml`, atau konfigurasi produksi yang diubah.
- Jangan jalankan `php artisan test` dengan konfigurasi default sebelum target `.env.testing` diperbaiki dan diverifikasi; konfigurasi saat ini berpotensi mengarahkan pengujian ke target yang sama dengan `.env`.
- Tidak ada migration terhadap MySQL, migration rollback, `migrate:fresh`, reset, penghapusan data, atau akses ke database produksi.

## Hasil validasi migration MySQL/MariaDB

**Tidak dieksekusi.** Prasyarat database disposable yang aman tidak terpenuhi, sehingga migration harus menunggu sampai lingkungan terpisah disediakan dan targetnya diverifikasi.

Static review terhadap source migration dan model workflow:

1. `2026_11_23_215900_enforce_unique_employee_nip.php`
   - Memeriksa nilai NIP non-null duplikat sebelum menambah unique index.
   - Jika duplikat ditemukan, migration melempar error yang meminta rekonsiliasi; tidak menghapus atau mengubah data.
   - Rollback hanya menghapus index yang ditambahkan migration.
   - Jumlah duplikat aktual, unique index pada engine target, dan dampak collation belum diuji pada MySQL/MariaDB.
2. `2026_11_23_220000_create_pegawai_change_workflow_tables.php`
   - Membuat request, item, dan decision dengan foreign key ke `data_kepegawaian`, `users`, request/item terkait.
   - Foreign key menggunakan tipe `foreignId` yang selaras secara tipe dengan primary key model induk; migration memasang unique constraint per field dalam request serta index status/query.
   - `down()` menghapus hanya tiga tabel workflow baru, dalam urutan terbalik dependensi. Itu tidak menyentuh tabel lama; rollback akan menghapus histori yang disimpan di tabel workflow.
   - PHP lint dan penerapan schema pada SQLite in-memory pernah lulus. Hal itu bukan bukti migration berjalan pada MySQL/MariaDB.

Tidak ada bukti runtime bahwa kedua migration berhasil pada MySQL/MariaDB, foreign key dan index benar-benar terbentuk pada engine tersebut, penolakan NIP duplikat bekerja pada database aktual, atau kegagalan migration bersifat atomic pada engine target. Tidak ada rollback yang dijalankan. Versi server yang terpasang teridentifikasi sebagai MySQL 8.0.30 dari executable, tetapi server tidak dihubungi.

## Concurrency dan keamanan workflow

### Concurrency

- Test MySQL/MariaDB `writer_handles_a_concurrent_duplicate_insert_without_generic_error` **tidak dijalankan** karena test database aman tidak tersedia. Pada Tahap 5B test ini di-skip saat driver bukan MySQL agar assertion MySQL tidak disamarkan sebagai hasil lulus SQLite.
- Full suite SQLite pada Tahap 5B menunjukkan 259 passed dan satu test tersebut skipped. Itu bukan verifikasi MySQL locking/race.
- Test workflow SQLite in-memory dijalankan ulang pada tahap ini: **13 passed, 72 assertions**. Cakupannya termasuk snapshot basi, keputusan item, ownership, penolakan self-verification, serta status akun Operator/Kepsek.
- Isolasi dua transaksi bersamaan, pemulihan setelah duplicate insert MySQL, dan perilaku lock `FOR UPDATE` pada MySQL/MariaDB tetap belum diverifikasi.

### Pemeriksaan otorisasi

Test workflow terarah dan route list menghasilkan bukti berikut:

- Guru hanya mengajukan untuk pegawai dari akun terautentikasi; pengiriman `data_kepegawaian_id` lain tidak mengganti pemilik. Guru lain tidak dapat membuka detail request atau bukti melalui ID langsung.
- Verifikasi Operator memeriksa role/relasi, akun aktif dan disetujui, serta assignment aktif bertipe `operator` dengan menu `operator.employees`; verifikasi pengajuan pegawai sendiri ditolak.
- Operator inactive dan Operator approval status pending menerima 403 dan tidak mengubah item pending.
- Kepsek hanya membaca monitoring; guard mengharuskan `superadmin`, aktif, dan disetujui. Test meliputi empat endpoint monitoring dan bukti privat untuk akun inactive/pending.
- Bukti diambil melalui endpoint dengan middleware `auth` dan pemeriksaan otorisasi pemilik/Operator/Kepsek, bukan URL storage publik.
- Test profil dan TU membuktikan jalur edit lama tidak langsung mengubah data pribadi workflow; field administratif tetap dapat diubah lewat jalur administratif.
- Route list menunjukkan route Guru memakai `auth`/`role:guru`, route Operator berada di group `auth`/`role:guru`/`tu.mode`/`menu.route` dengan guard assignment di controller/service, route monitoring memakai `auth`/`role:superadmin`, dan route bukti memakai `auth` serta guard service.

Hasil ini mendukung validasi source/test pada SQLite; bukan browser test atau validasi concurrency MySQL.

## Kesiapan browser QA

| Prasyarat | Status |
|---|---|
| Database QA terpisah dan terbukti disposable | **Tidak tersedia / belum terverifikasi** |
| Migration berhasil pada database QA MySQL/MariaDB | **Belum dijalankan** |
| Akun fiktif Guru | **Tidak dibuat** |
| Akun fiktif Guru dengan assignment Operator aktif | **Tidak dibuat** |
| Akun fiktif Kepsek | **Tidak dibuat** |
| Data pegawai fiktif | **Tidak dibuat** |
| Upload/download bukti di server QA | **Belum diuji** |
| Laravel QA server | **Tidak dijalankan** |
| URL lokal aplikasi QA yang aman | **Belum tersedia** |
| Browser workflow | **Tidak dilakukan** |

Apache Laragon ditemukan mendengarkan pada port 80/443 di semua interface lokal; prosesnya bukan server QA yang dibuat atau diverifikasi untuk workspace ini. Port tersebut tidak dikunjungi. Tidak terdeteksi listener Laravel QA pada port kandidat 8000/8001/8080/8081. Karena konfigurasi aplikasi saat ini menunjuk database yang tidak terbukti terisolasi, server aplikasi tidak dijalankan.

## Skenario uji manual yang disiapkan

Skenario berikut belum dijalankan. Gunakan hanya setelah database disposable, migration, server, akun, dan data fiktif diverifikasi. Jangan gunakan pegawai/data sekolah nyata.

1. Login sebagai Guru fiktif dan buka halaman perubahan data pegawai.
2. Kirim satu pengajuan dengan beberapa field dan bukti sesuai field yang diwajibkan.
3. Pastikan Guru melihat status request dan item tanpa melihat request Guru lain.
4. Login sebagai Guru fiktif dengan assignment Operator aktif; buka daftar antrean.
5. Setujui satu item dan pastikan hanya field item itu berubah pada data resmi.
6. Tolak item lain tanpa alasan terlebih dahulu dan pastikan validasi menolak; kemudian isi alasan dan tolak.
7. Pastikan nilai field ditolak tidak berubah pada data resmi, dan riwayat/keputusan menyimpan alasan.
8. Login kembali sebagai Guru pemohon; periksa status keputusan per item.
9. Ajukan ulang item yang ditolak; pastikan histori penolakan tetap tersedia.
10. Login sebagai Kepsek fiktif; tinjau daftar monitoring, detail pegawai, dan riwayat tanpa aksi approval.
11. Coba URL bukti dengan akun Guru lain, Operator yang tidak berwenang, serta Kepsek inactive/pending; semua harus ditolak.
12. Ubah ID request/item pada URL untuk mencoba membuka record Guru lain; akses harus ditolak tanpa membocorkan isi.

### Cara memberikan akses pengujian setelah prasyarat dipenuhi

Jangan simpan password di repository atau laporan audit. Buat akun hanya pada database disposable yang terverifikasi, berikan credential secara langsung melalui sesi kerja lokal yang aman, dan hapus/rotasi credential sesuai kebijakan setelah sesi pengujian. Pada tahap ini tidak ada credential yang dibuat atau dibagikan.

## Perubahan dan risiko tersisa

Tidak ada source, test, konfigurasi database, atau UI yang diubah pada Tahap 5C. File baru tahap ini:

- `docs/audit/TAHAP_5C_VALIDASI_DATABASE_DAN_KESIAPAN_BROWSER.md`

Seluruh perubahan lokal sebelumnya dipertahankan. Tidak ada commit atau push.

Pekerjaan yang menghalangi status SIAP:

1. Sediakan atau provision database QA disposable pada instance terpisah dari target `.env`; pastikan nama dan isolasinya sebelum migrasi.
2. Konfigurasi `.env.testing` secara khusus agar tidak menunjuk database yang sama dengan `.env`; jangan mengubah `.env` produksi.
3. Jalankan kedua migration, verifikasi schema/FK/index/unique NIP, dan uji rollback hanya terhadap QA disposable yang disetujui.
4. Jalankan regression concurrency MySQL/MariaDB dan verifikasi transaksi/lock dengan dua koneksi.
5. Buat data serta tiga akun fiktif dengan assignment dan status yang benar.
6. Jalankan aplikasi QA pada port lokal yang tidak bentrok setelah target DB aman, lalu uji login, workflow, dan bukti privat di browser.

**Keputusan: BELUM SIAP.** Belum ada URL browser QA yang dapat diberikan dengan aman, dan belum ada akun pengujian. Menggunakan konfigurasi MySQL saat ini atau menganggap `sekolah_app_testing` aman hanya berdasarkan namanya tidak dapat diterima.
