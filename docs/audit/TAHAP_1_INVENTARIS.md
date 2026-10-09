# SINERGI-MJ — Audit Tahap 1: Inventaris Struktur dan Modul

## 1. Metadata audit dan baseline repositori

| Metadata | Hasil |
|---|---|
| Waktu audit | 9 Oktober 2026, 11:53 WIB (UTC+07:00) |
| Repository | `azrielUhuy123/sistem-informasi-sekolah` |
| Branch aktif | `main`, tracking `origin/main` |
| Commit terakhir | `e082d0e966fab00b5776ddbc657cce99c7596bf3` (`Mobile Responsive 1`) |
| Waktu commit terakhir | 27 Agustus 2026, 23:05:51 WIB |
| Baseline kerja | Worktree sudah berubah sebelum audit; 223 entri status Git: 158 modified, 8 deleted, 57 untracked |
| Sebaran entri status | `app` 42, `bootstrap` 1, `config` 2, `database` 29, `public` 9, `resources` 105, `routes` 6, `tests` 24, root/lainnya 5 |
| Laporan lama | `docs/audit/` dan `TAHAP_1_INVENTARIS.md` tidak ada sebelum pembuatan laporan ini |

Pemeriksaan baseline memakai `git status --short --branch`, `git branch --show-current`, dan `git log -1`; semuanya read-only. Perubahan lokal dipertahankan apa adanya. Angka perubahan di atas adalah snapshot sebelum audit, bukan penilaian atas perubahan tersebut. Daftar lengkap 223 path tidak disalin ke laporan agar perubahan lokal yang tidak relevan atau nama berkas unggahan tidak tersebar lebih lanjut.

Tanggal/waktu audit berasal dari waktu sesi audit. Isi `.env`, `.env.testing`, salinan `.env`, file database, kredensial, dan berkas upload tidak dibuka.

## 2. Ringkasan kondisi repositori

- **FAKTA KODE:** Manifest mendeklarasikan Laravel `^12.0` dan PHP `^8.2`; frontend mendeklarasikan Vue 3, Vue Router, Vite, Tailwind CSS, Bootstrap, dan Alpine.js. Versi yang benar-benar terpasang/berjalan tidak diuji.
- **FAKTA KODE:** Repositori memiliki area backend Laravel, Blade, source JavaScript/Vue, 10 file route, 109 file migration, 46 file model, 250 file Blade, dan test PHP serta JavaScript.
- **FAKTA KODE:** Nama route, controller, view, dan model mengindikasikan modul publik/gateway, autentikasi, administrasi sekolah, data akademik, guru, siswa, PPDB, e-learning, ekstrakurikuler, surat/dokumen, notifikasi, dan SPMB.
- **INFERENSI TEKNIS:** Beberapa modul dapat dipetakan lintas route-controller-model-view. Pemetaan berikut bukan verifikasi bahwa semua alur berhasil dijalankan.
- **BELUM DIKETAHUI:** Status schema aktif, data, konfigurasi deployment, layanan eksternal, dan perilaku runtime tidak diverifikasi.

## 3. Struktur direktori dan teknologi yang teridentifikasi

| Area | Bukti / isi yang teridentifikasi |
|---|---|
| `app/` | 176 file PHP: controllers, middleware, requests/responses, models, enums, policies, providers, services, actions, helpers, imports, exports, mail, notifications, dan console commands. |
| `bootstrap/` | `bootstrap/app.php` mendaftarkan route entry point, middleware aliases, serta health route `/up`. |
| `config/` | Konfigurasi Laravel termasuk `auth.php`, `database.php`, `mail.php`, `filesystems.php`, `queue.php`, `cache.php`, `services.php`, dan `menu.php`. Nilai runtime dari environment tidak diperiksa. |
| `database/` | 109 migration files, 7 seeder files, factories, serta direktori/file terkait testing. Database tidak dibuka dan migration/seeder tidak dijalankan. |
| `routes/` | 10 file PHP: `web.php`, `api.php`, `auth.php`, `admin.php`, `guru.php`, `siswa.php`, `tu.php`, `superadmin.php`, `ppdb-public.php`, dan `ppdb-admin.php`; juga ada `console.php`. |
| `resources/views/` | 250 Blade files, antara lain area `admin`, `auth`, `gateway`, `guru`, `ppdb`, `siswa`, `superadmin`, `tu`, `notifications`, `profile`, `raport`, `layouts`, dan `components`. |
| `resources/js/` dan `resources/css/` | Source Vue untuk halaman publik (home, informasi, kontak, PPDB, prestasi) dan komponen/layout; juga JavaScript dashboard serta stylesheet aplikasi. |
| `tests/` | 28 file PHP (Feature, Unit, dan `TestCase`) serta 6 file JavaScript. Test tidak dijalankan. |
| `public/` | Entry/static assets, `build/manifest.json`, PDF.js distribution, aset gambar/video, data wilayah, dan direktori upload. Isi file upload tidak diperiksa. |
| `storage/`, `stubs/` | Direktori ada; isi dan runtime artifacts tidak diaudit secara rinci. |
| `vendor/`, `node_modules/` | Direktori dependency ada di workspace. Versi runtime/dependency tidak dievaluasi dengan menjalankan aplikasi atau build. |

**Teknologi berdasarkan manifest:**

- `composer.json`: Laravel Framework `^12.0`, PHP `^8.2`; dependency yang dideklarasikan antara lain Laravel Socialite, Laravel Tinker, DomPDF, Laravel Excel, FPDF, dan FPDI. `composer.lock` tersedia.
- `package.json`: Vue `^3.5.35`, Vue Router `^5.1.0`, Vite `^7.0.7`, Tailwind CSS `^3.1.0`, Bootstrap `^5.3.8`, Alpine.js, Axios, dan tool terkait. `package-lock.json` tersedia. Script npm yang dideklarasikan adalah `build` dan `dev`; tidak ada script `test` di manifest yang diperiksa.
- `phpunit.xml` tersedia; Composer menyediakan script `test` yang memanggil Artisan. Script tersebut **tidak** dijalankan dalam audit ini.
- `config/auth.php` menetapkan guard `web` berbasis session serta provider Eloquent untuk model `App\Models\User` (dengan nama guard/model dapat dioverride melalui environment). Ini adalah konfigurasi kode, bukan bukti konfigurasi deployment aktif.

## 4. Daftar modul dan fitur

Status pemetaan mengukur keterhubungan bukti source yang ditemukan, bukan kualitas atau kelayakan fitur.

| Modul | Fungsi yang tampak dari implementasi | Route dan controller terkait | View / model terkait | Test yang ditemukan | Status pemetaan |
|---|---|---|---|---|---|
| Gateway / halaman publik | Menyediakan halaman publik dan endpoint statistik gateway. | `routes/web.php` → `GatewayController`; `routes/api.php` → `Api\GatewayController`. | `resources/views/gateway/`; Vue `resources/js/pages/` dan `resources/js/layouts/PublicLayout.vue`. | Tidak ada file test bernama gateway pada inventaris test. | Terpetakan sebagian |
| Autentikasi, profil, dan akun | Login, verifikasi email, reset/perubahan password, logout, dan profil. | `routes/auth.php`; `routes/web.php` → controller Auth dan `ProfileController`. | `resources/views/auth/`, `profile/`; model `User`. | Feature Auth (`AuthenticationTest`, `PasswordResetTest`, dll.) dan `ProfileTest`. | Teridentifikasi |
| Dashboard / administrasi admin | Dashboard admin, pengelolaan akun, dan tahun ajaran. | `routes/admin.php` → `Admin\DashboardController`, `Admin\UserController`, `Admin\TahunAjaranController`. | `resources/views/admin/`; `User`, `TahunAjaran`. | Tidak ditemukan test bernama khusus `Admin\User` pada daftar test. | Terpetakan sebagian |
| Administrasi TU dan data master | Pengelolaan siswa/guru, kelas/rombel, angkatan, mapel, tahun ajaran, dokumen, akun, penempatan siswa, dan beberapa operasi administratif. | `routes/tu.php` → controller dalam `App\Http\Controllers\Tu\...`; route menerapkan middleware TU dan beberapa sub-area memakai `approved`/`tahun.aktif`. | `resources/views/tu/`; model antara lain `Siswa`, `Guru`, `Kelas`, `KelasGrup`, `KelasMaster`, `TahunAjaran`, `Angkatan`, `MataPelajaran`, `PenempatanSiswa`, `Dokumen`. | `KelasRombelCrudTest`, `SiswaNisImmutabilityTest`, `AnnualRolloverTest`, `TahunAjaranSemesterTransitionTest`, `MasterAkademikAwalSeederTest`. | Terpetakan sebagian |
| Penjadwalan akademik | Route guru/TU/superadmin memuat pengajuan jadwal, jadwal, jadwal final, monitoring, hari dan jam belajar. | `routes/guru.php`, `routes/tu.php`, `routes/superadmin.php` → controller Jadwal/PengajuanJadwal/MonitoringJadwal/JadwalFinal. | `resources/views/guru/`, `tu/`, `superadmin/`; model `Jadwal`, `JadwalGrup`, `JadwalRombel`, `HariSekolah`, `JamPelajaran`, `PengajuanJadwal`, `PengampuMapel`. | Tidak ditemukan test bernama khusus untuk alur jadwal akademik pada daftar test. | Terpetakan sebagian |
| Guru dan proses pembelajaran | Dashboard guru, absensi, nilai, raport, perkembangan siswa, kelas/wali, profile, serta validasi. | `routes/guru.php` → controller dalam `App\Http\Controllers\Guru\...`. | `resources/views/guru/`, `raport/`; model termasuk `Guru`, `WaliKelas`, `AbsensiSiswa`, `NilaiSiswa`, `PerkembanganSiswa`, `KeputusanKenaikan`. | Tidak ditemukan test bernama khusus absensi/nilai/raport guru pada daftar test. | Terpetakan sebagian |
| Siswa | Dashboard, jadwal, nilai, absensi, profile, ekstrakurikuler, e-learning, unduh dokumen, dan nomor surat. | `routes/siswa.php` → controller `App\Http\Controllers\Siswa\...`; route group memakai `auth`. | `resources/views/siswa/`; model `Siswa` dan model akademik/e-learning/ekskul yang berkaitan. | `ElearningStudentAuthorizationTest`; `SiswaNisImmutabilityTest` terkait data siswa. | Terpetakan sebagian |
| PPDB / SPMB | Pendaftaran publik, bukti/status, daftar ulang; dashboard/pengaturan/verifikasi/keputusan/ekspor dan penempatan di area operator. | `routes/ppdb-public.php` → `PPDB\PublicController`; `routes/ppdb-admin.php` → controller PPDB; `routes/web.php` juga memiliki route dashboard `spmb`. | `resources/views/ppdb/`; model `PpdbSetting`, `PpdbPendaftar`, `PpdbOrangtua`; mail/export/report services tersedia. | Feature PPDB cohort/workflow/decision/export/resend/quota dan 6 test JavaScript PPDB. | Teridentifikasi |
| E-learning | Konten e-learning, lampiran, komentar, balasan, dan interaksi siswa. | Route di `routes/guru.php` dan `routes/siswa.php`; controller `Guru\ElearningController`, `Siswa\ElearningController`, serta controller komentar/reply. | `resources/views/guru/elearning/`, `resources/views/siswa/elearning/`; model `Elearning`, `ElearningLampiran`, `ElearningKomentar`, `ElearningKomentarReply`, `ElearningKomentarLampiran`. | `ElearningStudentAuthorizationTest` serta test UI PPDB yang tidak terkait. | Terpetakan sebagian |
| Ekstrakurikuler | Data kegiatan, anggota, jadwal, absensi, jurnal, pembinaan, dan monitoring/validasi. | Route guru/siswa/TU/superadmin; controller `Guru\Ekskul\...`, `Siswa\EkskulController`, `Tu\EkskulController`, `SuperAdmin\MonitoringEkskulController`. | View di `guru/ekskul/`, `siswa/ekskul/`, `tu/ekskul/`, `superadmin/monitoring-ekskul/`; model ekskul, anggota, absensi, jurnal, pembina, catatan, dan pengajuan jadwal. | `EkskulAbsensiTest`, `EkskulJadwalWorkflowTest`. | Terpetakan sebagian |
| Surat, dokumen, dan arsip | Pengajuan/validasi surat, nomor surat, berkas guru/siswa/sekolah, arsip, dan ekspor dokumen. | Route di `routes/guru.php`, `routes/siswa.php`, `routes/tu.php`, `routes/superadmin.php`; controller surat/dokumen per role. | View `guru/pengajuan-surat/`, `tu/pengajuan-surat/`, `superadmin/pengajuan-surat/`, `superadmin/dokumen/`; model `Dokumen`, `ArsipDokumen`, `PengajuanSurat`, `NomorSurat`. | Tidak ditemukan test bernama khusus surat/dokumen pada daftar test. | Terpetakan sebagian |
| Notifikasi | Inbox notifikasi dan aksi menandai dibaca; service bisnis juga membentuk notifikasi. | `routes/web.php` → `NotificationController`; `BusinessNotificationService` dan `SinergiNotification`. | `resources/views/notifications/`; tabel Laravel `notifications`. | `NotificationInboxTest`, `BusinessNotificationTest`, `SinergiNotificationTest`. | Teridentifikasi |
| Pemasukan keuangan | Controller dan model menyediakan query/pencatatan pemasukan. Route/view yang menghubungkannya belum teridentifikasi dari pencarian terarah. | `Tu\KeuanganPemasukanController` dan model tersedia; tidak ada rujukan `KeuanganPemasukan` yang ditemukan pada file route yang diperiksa. | Model menetapkan tabel `keuangan_pemasukan`; view/route belum dapat dipetakan. Migration pembuat tabel tidak ditemukan dalam 109 file migration yang diperiksa. | Tidak ditemukan test bernama khusus pemasukan keuangan. | Belum dapat dipastikan |

Daftar di atas sengaja mengelompokkan banyak route dan sub-alur. Tidak semua method route, validasi, query, atau view diperiksa satu per satu; tabel model/database dan area test di bawah melengkapi peta tingkat inventaris.

## 5. Daftar komponen teknis utama

| Komponen | Lokasi / bukti | Catatan |
|---|---|---|
| Route entry point dan middleware aliases | `bootstrap/app.php`; `routes/web.php`, `routes/api.php` | Alias kode mencakup `role`, `approved`, `forbid.superadmin`, `tahun.aktif`, `guru.mode`, dan `tu.mode`. |
| Autentikasi | `config/auth.php`, `routes/auth.php`, `App\Models\User`, controller Auth | Guard `web` memakai session, user provider Eloquent; konfigurasi aktif lingkungan tidak diperiksa. |
| Otorisasi berbasis role | `app/Enums/UserRole.php`, `app/Http/Middleware/RoleMiddleware.php`, `app/Policies/UserPolicy.php`, route middleware | Middleware role dan role checks tersebar di route, model, middleware, controller, provider, policy, serta service. |
| Data/persistensi | `app/Models/`, `database/migrations/`, `database/factories/`, `database/seeders/` | Migrations dan relasi model dipakai sebagai bukti struktur; tidak ada inspeksi database runtime. |
| Impor/ekspor | `app/Imports/`, `app/Exports/`; dependency Laravel Excel | Source import/export ditemukan; keberhasilan operasional tidak diuji. |
| Dokumen/PDF | controller, view PDF, package DomPDF/FPDF/FPDI | Kehadiran package dan source terkait tidak membuktikan hasil PDF pada runtime. |
| Notifikasi | `app/Notifications/SinergiNotification.php`, `app/Services/BusinessNotificationService.php`, `app/Http/Controllers/NotificationController.php` | Terhubung dengan tabel notifikasi Laravel dan beberapa route inbox. |
| Mail | `app/Mail/` dan view email PPDB | Integrasi provider mail aktif tidak diketahui; nilai environment tidak diperiksa. |
| Build frontend | `vite.config.js`, `resources/js/`, `resources/css/`, `package.json` | Tidak menjalankan build atau dev server. |

## 6. Inventaris role yang ditemukan

### Role bernilai eksplisit

| Nama/kode role | Bukti deklarasi/penggunaan | Area yang eksplisit memeriksa |
|---|---|---|
| `superadmin` | `UserRole::SUPERADMIN`; ditemukan juga pada seeder, middleware, policy, controller, dan query service. | Group route `routes/superadmin.php`; middleware `RoleMiddleware` memiliki bypass khusus superadmin; beberapa aturan akun/approval/notifikasi menguji role ini. |
| `admin` | `UserRole::ADMIN`; ditemukan pada seeder, validasi user, policy, dan pemeriksaan middleware. | `routes/admin.php`; pengelolaan akun oleh Admin controller/policy. |
| `tu` | `UserRole::TU`; ditemukan pada seeder, controller, service, dan pemeriksaan route. | `routes/tu.php` dan `routes/ppdb-admin.php`. |
| `guru` | `UserRole::GURU`; ditemukan pada seeder, controller, dan pemeriksaan role. | `routes/guru.php` dengan middleware `role:guru` serta `guru.mode`. |
| `siswa` | Nilai `UserRole::MURID` adalah string `'siswa'`; digunakan oleh model/dashboard dan pemeriksaan akun siswa. | `routes/siswa.php` memakai `auth` pada route group; pada route file tersebut tidak terlihat `role:siswa`. |

### Catatan role / potensi ketidakkonsistenan

- **FAKTA KODE:** `UserRole` adalah class berisi konstanta (bukan PHP backed enum); konstanta `MURID` bernilai `'siswa'`. Daftar role yang dipakai di `AppServiceProvider` berisi lima konstanta di atas.
- **FAKTA KODE:** `routes/web.php` memiliki group `role:spmb`, tetapi `'spmb'` tidak ada di `UserRole` dan tidak terlihat di daftar role yang dipakai `AppServiceProvider` maupun seeders yang dicari.
- **INFERENSI TEKNIS:** `spmb` tampak sebagai role route yang belum tercakup pada deklarasi role terpusat yang diperiksa. Apakah ada penciptaan akun/role SPMB melalui mekanisme lain atau efek runtime tertentu belum diverifikasi.
- **FAKTA KODE:** `RoleMiddleware` mengizinkan role `superadmin` melewati pengecekan roles sebelum mencocokkan role yang diminta.
- **BELUM DIKETAHUI:** Tidak ada pemeriksaan terhadap seluruh kemungkinan data role di database aktif. Tidak ada role baru yang diusulkan dalam audit ini.

## 7. Inventaris tabel, model, dan relasi database

109 file migration diperiksa secara statis; daftar di bawah adalah tabel yang ditemukan pada pernyataan pembentukan schema migration. Daftar tersebut **bukan** hasil introspeksi schema database saat ini. Sebagian perubahan tabel terjadi lewat `Schema::table`, rename, atau migration lanjutan sehingga bentuk akhir tidak disimpulkan hanya dari nama migration.

### Tabel yang tampak dari migration

| Kelompok | Tabel (model terkait bila ditemukan) | Migration pembentuk / bukti |
|---|---|---|
| Dasar akun dan Laravel | `users` (`User`), `password_reset_tokens`, `sessions`, `jobs`, `cache` | `2025_12_05_173923_init_all_tables.php`; `2025_12_05_175410_create_jobs_table.php`; `2025_12_07_212442_create_cache_table.php`. |
| Personel dan siswa | `siswa` (`Siswa`), `guru` (`Guru`), `data_kepegawaian` (`DataKepegawaian`) | `2025_12_05_173923_init_all_tables.php`; tabel tersebut memiliki migration lanjutan untuk kolom dan relasi. |
| Kelas / tahun / penempatan | `kelas` (`Kelas`), `kelas_grup` (`KelasGrup`), `kelas_master` (`KelasMaster`), `angkatan` (`Angkatan`), `tahun_ajaran` (`TahunAjaran`), `penempatan_siswa` (`PenempatanSiswa`) | `init_all_tables`; `2026_01_21_141538_create_kelas_grup_table.php`; `2026_01_27_092926_create_angkatan_table.php`; `2026_02_04_131257_create_kelas_master_table.php`; `2026_02_27_122721_create_penempatan_siswa_table.php`; serta migration lanjutan. |
| Mapel dan jadwal | `mata_pelajaran` (`MataPelajaran`), `guru_mapel` (pivot yang digunakan relasi model), `pengampu_mapel` (`PengampuMapel`), `wali_kelas` (`WaliKelas`), `jadwal` (`Jadwal`), `hari_sekolah` (`HariSekolah`), `jam_pelajaran` (`JamPelajaran`), `ruangan` (`Ruangan`), `pengajuan_jadwal` (`PengajuanJadwal`) | `init_all_tables`; `2026_04_07_223818_create_hari_sekolah_table.php`; `2026_04_07_223904_create_jam_pelajaran_table.php`; `2026_04_07_223942_create_jadwal_table.php`; `2026_04_08_134550_create_guru_mapel_table.php`; `2026_05_07_092325_create_pengajuan_jadwal_table.php`; `2026_05_14_210710_create_pengampu_mapel_table.php`. |
| Catatan akademik | `absensi_siswa` (`AbsensiSiswa`), `nilai_siswa` (`NilaiSiswa`), `perkembangan_siswa` (`PerkembanganSiswa`), `keputusan_kenaikan` (`KeputusanKenaikan`) | `2026_05_15_150345_create_absensi_siswa_table.php`; `2026_05_15_162308_create_nilai_siswa_table.php`; `2026_05_19_094137_create_perkembangan_siswa_table.php`; `2026_05_19_111949_create_keputusan_kenaikan_table.php`; beberapa memiliki migration lanjutan. |
| Ekstrakurikuler | `ekskuls` (`Ekskul`), `anggota_ekskuls` (`AnggotaEkskul`), `ekskul_siswa` (`EkskulSiswa`), `pembina_ekskul` (`PembinaEkskul`), `absensi_ekskul` (`AbsensiEkskul`), `jurnal_ekskul` (`JurnalEkskul`), `catatan_pembinaan_ekskul` (`CatatanPembinaanEkskul`), `pengajuan_jadwal_ekskul` (`PengajuanJadwalEkskul`) | Migration berawalan `2026_05_29_` untuk tabel kegiatan/anggota/absensi/jurnal/pembina/catatan; `2026_06_01_113833_create_pengajuan_jadwal_ekskul_table.php`. |
| PPDB | `ppdb_settings` (`PpdbSetting`), `ppdb_pendaftar` (`PpdbPendaftar`), `ppdb_orangtua` (`PpdbOrangtua`) | `2026_06_06_212245_create_ppdb_settings_table.php`, `2026_06_06_212249_create_ppdb_pendaftars_table.php`, `2026_06_06_212255_create_ppdb_orangtuas_table.php`; migration lanjutan menyelaraskan kolom. |
| E-learning | `elearnings` (`Elearning`), `elearning_lampiran` (`ElearningLampiran`), `elearning_komentar` (`ElearningKomentar`), `elearning_komentar_replies` (`ElearningKomentarReply`), `elearning_komentar_lampiran` (`ElearningKomentarLampiran`) | Migration `2026_06_11_223438`, `2026_06_11_230809`, `2026_06_12_002227`, `2026_06_17_143318`, dan `2026_06_17_151944` dengan nama create table masing-masing. |
| Dokumen/surat | `arsip_dokumen` (`ArsipDokumen`), `dokumen` (`Dokumen`), `pengajuan_surats` (`PengajuanSurat`), `nomor_surats` (`NomorSurat`) | `init_all_tables`; `2026_05_09_185542_create_dokumen_table.php`; `2026_05_09_215854_create_pengajuan_surats_table.php`; `2026_05_11_224413_create_nomor_surats_table.php`. |
| Notifikasi | `notifications` (notifikasi Laravel; tidak ada model aplikasi bernama `Notification` pada daftar model) | `2026_10_03_000000_create_notifications_table.php`. |

**Model aplikasi (46 file) yang ditemukan**, dikelompokkan menurut nama/konteks: `User`; `DataKepegawaian`, `Guru`, `Siswa`; `Angkatan`, `Kelas`, `KelasGrup`, `KelasMaster`, `TahunAjaran`, `PenempatanSiswa`, `WaliKelas`; `MataPelajaran`, `PengampuMapel`, `Jadwal`, `JadwalGrup`, `JadwalRombel`, `PengajuanJadwal`, `HariSekolah`, `JamPelajaran`, `Ruangan`; `AbsensiSiswa`, `Nilai`, `NilaiSiswa`, `PerkembanganSiswa`, `KeputusanKenaikan`; `Ekskul`, `AnggotaEkskul`, `EkskulSiswa`, `PembinaEkskul`, `AbsensiEkskul`, `JurnalEkskul`, `CatatanPembinaanEkskul`, `PengajuanJadwalEkskul`; `Elearning`, `ElearningLampiran`, `ElearningKomentar`, `ElearningKomentarReply`, `ElearningKomentarLampiran`; `PpdbSetting`, `PpdbPendaftar`, `PpdbOrangtua`; `Dokumen`, `ArsipDokumen`, `PengajuanSurat`, `NomorSurat`, dan `KeuanganPemasukan`.

### Relasi model dan constraint yang dapat dibuktikan

- `User` mendefinisikan relasi ke approver, guru, pegawai, dan siswa. `Guru` menghubungkan pegawai, mapel, wali kelas, pengampu mapel, dan pembina ekskul. `Siswa` menghubungkan penempatan, user, ekskul, dan PPDB. `PenempatanSiswa` menghubungkan siswa, kelas, serta tahun ajaran. `Elearning` menghubungkan guru/kelas/mapel, lampiran, komentar, pengampu, dan wali kelas. Bukti ada pada method relasi di `app/Models/`.
- Migration awal menetapkan unique pada `users.email` dan `siswa.nis`; foreign key siswa → kelas bersifat nullable dengan `nullOnDelete`; foreign key jadwal dan wali kelas menghubungkan kelas/guru/mapel sesuai deklarasi migration.
- Migration `penempatan_siswa` menghubungkan siswa, kelas, tahun ajaran dan menyatakan unique gabungan; migration keputusan kenaikan merujuk siswa/kelas/guru/tahun ajaran/user.
- Migration PPDB memberi unique pada `no_pendaftaran` dan `nik` serta relasi ke tahun ajaran/siswa. Migration lain menambahkan unique constraint pada beberapa tabel grup kelas, keputusan kenaikan, ekstrakurikuler, dan konfigurasi PPDB.
- **Catatan evolusi schema:** migration awal membuat `jadwal`, migration `2026_04_07_215052_rename_jadwal_table.php` mengganti nama `jadwal` menjadi `jadwal_legacy`, dan migration `2026_04_07_223942_create_jadwal_table.php` kemudian membuat `jadwal`. Hasil schema aktif tidak diverifikasi.
- **Belum ditemukan:** pencarian pada seluruh 109 migration tidak menemukan `Schema::create('keuangan_pemasukan')` maupun referensi string tabel tersebut, sementara `KeuanganPemasukan` model menetapkan nama tabel itu. Ini hanya temuan dari source migration yang diperiksa; kemungkinan schema di luar cakupan tidak ditentukan.

## 8. Inventaris test suite

| Lokasi | Jenis / isi |
|---|---|
| `tests/Feature/` | 26 file feature test, termasuk Authentication, email/password, profile, notifikasi, PPDB, ekstrakurikuler, e-learning authorization, rombel, kenaikan kelas, dan transisi tahun ajaran. |
| `tests/Unit/` | 1 file unit test (`ExampleTest.php`). |
| `tests/JavaScript/` | 6 file test UI/status PPDB. |
| `tests/TestCase.php` | Base test PHP. |
| Konfigurasi runner | `phpunit.xml`; manifest `package.json` tidak mendeklarasikan script `test`. |

Inventaris nama feature test antara lain `AnnualRolloverTest`, `AuthenticationTest`, `BusinessNotificationTest`, `EkskulAbsensiTest`, `EkskulJadwalWorkflowTest`, `ElearningStudentAuthorizationTest`, `KelasRombelCrudTest`, `KeputusanKenaikanUniqueTest`, `MasterAkademikAwalSeederTest`, `NotificationInboxTest`, beberapa `Ppdb...Test`, `ProfileTest`, `SinergiNotificationTest`, `SiswaNisImmutabilityTest`, dan `TahunAjaranSemesterTransitionTest`.

Tidak ditemukannya test dengan nama suatu modul **tidak** membuktikan fitur tidak berfungsi atau tidak tercakup oleh test lain. Daftar file test diperiksa; test suite tidak dijalankan sesuai batas audit.

## 9. Temuan awal dan bukti pendukung

| ID | Kategori | Bukti (path dan baris) | Ringkasan bukti | Status verifikasi / batasan |
|---|---|---|---|---|
| INV-001 | FAKTA KODE / baseline | Output read-only `git status --short --branch`, `git branch --show-current`, `git log -1` | Branch `main`; commit `e082d0e`; 223 entri lokal sebelum audit. | Terverifikasi saat audit; perubahan tidak disentuh. |
| INV-002 | FAKTA KODE | `composer.json:9-14`; `package.json:13-26`; `bootstrap/app.php:9-22` | Manifest framework/runtime dan dependency frontend/backend; route entry point serta alias middleware didaftarkan. | Terverifikasi dari manifest/source; runtime tidak dijalankan. |
| INV-003 | FAKTA KODE | `app/Enums/UserRole.php:5-11`; `routes/web.php:47`; `app/Providers/AppServiceProvider.php:23-29` | Role terpusat berisi `superadmin`, `admin`, `tu`, `guru`, `siswa`; route juga meminta `spmb`, yang tidak ada pada deklarasi role terpusat yang diperiksa. | Terverifikasi pada file yang disebut; makna/penyediaan role SPMB di luar cakupan belum diketahui. |
| INV-004 | FAKTA KODE | `routes/siswa.php:16-17`, `routes/siswa.php:62-83` | Group siswa menggunakan middleware `auth`; terdapat dua deklarasi GET path/name untuk `download-dokumen` dan dua deklarasi GET path/name untuk `nomor-surat`, masing-masing satu closure dan satu controller. | Deklarasi terverifikasi; resolusi/efek runtime tidak diuji dan tidak dinilai di sini. |
| INV-005 | FAKTA KODE / INFERENSI TEKNIS | `app/Models/KeuanganPemasukan.php:7-10`; `app/Http/Controllers/Tu/KeuanganPemasukanController.php:10-38`; pencarian 109 migration | Model/controller merujuk `keuangan_pemasukan`, tetapi route, view, dan migration pembuat tabel tidak ditemukan dalam cakupan pencarian. | Source model/controller terverifikasi; kelengkapan implementasi/runtime belum dapat dipastikan. |
| INV-006 | FAKTA KODE | `database/migrations/2025_12_05_173923_init_all_tables.php:14-17,59-64,91-95`; `database/migrations/2026_02_27_122721_create_penempatan_siswa_table.php:14-35`; `app/Models/PenempatanSiswa.php:21-33` | Migration mendeklarasikan entitas, FK/unique yang disebut pada bagian 7; model mengekspos relasi siswa/kelas/tahun ajaran. | Terverifikasi pada source; database aktif tidak diakses. |
| INV-007 | FAKTA KODE | `tests/Feature/`, `tests/Unit/`, `tests/JavaScript/`, `phpunit.xml` | Test PHP dan JavaScript ditemukan pada lokasi yang diinventarisasi. | File terinventarisasi; seluruh test tidak dijalankan. |
| INV-008 | FAKTA KODE / BELUM DIKETAHUI | `database/migrations/2026_04_07_215052_rename_jadwal_table.php:14`; `database/migrations/2026_04_07_223942_create_jadwal_table.php:10` | Riwayat source memindahkan tabel lama ke `jadwal_legacy`, lalu mendeklarasikan tabel `jadwal` baru. | Pernyataan migration terverifikasi; keadaan migrasi database aktif tidak diketahui. |

## 10. Area yang belum diperiksa atau belum dapat dipastikan

### Belum diperiksa secara rinci

- Seluruh isi 176 file PHP aplikasi, seluruh 250 Blade view, dan seluruh 109 migration tidak ditelaah baris demi baris; pemeriksaan mendalam diarahkan ke entry point, role, route, relasi, test, serta bukti tabel yang relevan dengan inventaris.
- Implementasi setiap method controller, validasi lengkap, query, kebijakan akses, dan hubungan setiap route ke seluruh komponen belum dipetakan sampai tuntas.
- Isi `vendor/`, `node_modules/`, PDF.js vendored distribution, `storage/`, serta berkas dalam `public/uploads/` tidak diperiksa.
- Semua kemungkinan layanan eksternal dan konfigurasi operasional/deployment tidak ditelusuri.

### Sudah diperiksa tetapi bukti tidak cukup

- Hubungan penuh setiap model dengan tabel fisik dan semua constraint tidak dapat dipastikan hanya dari nama konvensi atau subset relasi yang dilihat.
- Apakah controller/model keuangan digunakan melalui mekanisme route non-standar atau schema eksternal belum diketahui.
- Apakah role `spmb` dibuat/diizinkan melalui konfigurasi atau data di luar file yang ditelusuri belum diketahui.
- Efek route duplikat dan kelengkapan masing-masing alur belum diuji.

### Tidak diperiksa / tidak diketahui

- Isi dan validitas `.env`, `.env.testing`, serta database lokal/produksi; koneksi database dan schema yang aktif.
- Data pengguna, data sekolah, credentials, token, unggahan, dan log aplikasi.
- Hasil menjalankan aplikasi, test, build, atau integrasi layanan.
- Kesesuaian modul dengan kebutuhan sekolah; hal tersebut bukan ruang lingkup tahap ini.

## 11. Daftar pemeriksaan yang telah dilakukan

- [x] Mencatat branch aktif, commit terakhir, dan status perubahan lokal secara read-only sebelum pemeriksaan source.
- [x] Memastikan path laporan dan direktori `docs/audit/` belum tersedia sebelum membuatnya.
- [x] Menginventaris direktori utama, file manifest, lockfile, route, model, controller, view, migration, seeder, dan test.
- [x] Membaca deklarasi role, middleware role, model `User`, konfigurasi auth, bootstrap middleware, route entry point, dan migration schema awal.
- [x] Mencari penggunaan role pada aplikasi/route/seeder.
- [x] Mencari deklarasi tabel, foreign key/unique, dan relasi Eloquent pada cakupan yang disebutkan.
- [x] Mengelompokkan test berdasarkan lokasi dan nama file; tidak menjalankan test.
- [x] Tidak membuka file `.env`, credential, database, atau isi upload.
- [x] Tidak menjalankan migration, seeder, test, build, atau perintah yang mengubah database/cache/dependency.
- [x] Tidak mengubah file aplikasi; hanya membuat laporan audit baru.

## 12. Batas cakupan audit tahap pertama

Audit ini adalah inventaris statis repositori pada baseline lokal tanggal 9 Oktober 2026. Hasilnya mendokumentasikan bukti yang terlihat pada source dan perubahan lokal yang sudah ada saat audit dimulai. Audit ini bukan audit keamanan, review kebutuhan sekolah, verifikasi runtime, verifikasi data/schema aktif, pemeriksaan menyeluruh seluruh file, maupun Audit Tahap 2.

Perubahan lokal yang tercatat sebelum audit tidak dibatalkan atau ditimpa. Tidak ada test atau migration yang dijalankan. Tidak ada perbaikan, refactor, perubahan workflow, maupun perubahan file aplikasi yang dilakukan dalam tahap ini.
