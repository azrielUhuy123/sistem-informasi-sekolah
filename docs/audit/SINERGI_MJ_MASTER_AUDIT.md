# SINERGI-MJ — Master Audit

Dokumen konsolidasi lintas tahap untuk Project Owner dan Lead Architect. Dokumen ini merangkum bukti dan batas audit pada laporan tahap individual; bukan pengganti atau pembaruan atas laporan tersebut. Temuan adalah hasil audit statis, bukan bukti eksploitasi atau hasil eksekusi workflow.

## 1. Metadata proyek dan baseline

| Item | Hasil |
|---|---|
| Proyek | SINERGI-MJ — sistem informasi sekolah berbasis Laravel |
| Repository | `azrielUhuy123/sistem-informasi-sekolah` |
| Waktu konsolidasi | 9 Oktober 2026, 15:23 WIB (UTC+07:00) |
| Branch saat konsolidasi | `main` |
| HEAD saat konsolidasi | `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` (`SINERGI-MJ UPDATE`) |
| Baseline acuan Tahap 3B | `main` / `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` |
| Baseline sekarang dibanding Tahap 3B | Branch dan HEAD sama. Status awal memperlihatkan perubahan lokal yang dilaporkan berada pada dokumentasi audit; tidak terlihat perubahan file aplikasi. Source tidak diperiksa ulang menyeluruh untuk konsolidasi ini. |
| Pembaruan dokumentasi | 22 November 2026: pemeriksaan source terbatas pada relasi akun pegawai, penentuan role, penghapusan data kepegawaian, constraint terkait, dan FK `tahun_ajaran_id`. Database aktif tidak diperiksa. |
| Status Git sebelum konsolidasi | `M docs/audit/TAHAP_1_INVENTARIS.md`; laporan Tahap 2, 2B–2F, 3, 3A, dan 3B untracked. Tidak tampak file aplikasi berubah. |
| Output tahap ini | File baru `docs/audit/SINERGI_MJ_MASTER_AUDIT.md`; laporan terdahulu tidak ditimpa. |
| Status Git sesudah konsolidasi | File master tercatat untracked; `TAHAP_1_INVENTARIS.md` tetap modified dan laporan lokal sebelumnya tetap untracked. Tidak tampak file aplikasi berubah. |

### Perubahan baseline historis yang penting

- **Tahap 1** mencatat branch `main`, commit `e082d0e966fab00b5776ddbc657cce99c7596bf3` (27 Agustus 2026), dan 223 entri perubahan lokal. Laporan itu dibuat sebelum baseline commit yang digunakan oleh tahap-tahap berikutnya.
- **Tahap 2 dan tahap lanjutan** mencatat commit `b32f25fc6e28b8e0262fa745e6cf454ea28e6414`. Pada baseline tahap-tahap tersebut tidak tampak perubahan source aplikasi; perubahan lokal yang dilaporkan adalah dokumentasi.
- Karena itu, temuan lintas tahap tidak boleh dibaca seolah-olah seluruh laporan mengaudit baseline yang sama. Untuk konsolidasi ini, statistik route dan indeks otorisasi Tahap 3B merujuk `b32f25fc6e28b8e0262fa745e6cf454ea28e6414`. Temuan lain dipresentasikan sesuai laporan asal dan tidak diperbarui dengan pemeriksaan source menyeluruh pada waktu konsolidasi.
- `TAHAP_1_INVENTARIS.md` tercatat modified di worktree saat konsolidasi. Perubahan lokal itu tidak ditimpa atau dikembalikan.

## 2. Tujuan, ruang lingkup, metode, dan batasan

### Tujuan

Menyatukan peta workflow frontend–backend, inventaris modul/role, bukti teknis, pertanyaan kebijakan, serta bagian yang memerlukan verifikasi runtime. Status ringkasan mempertahankan perbedaan antara:

- **FAKTA KODE** — deklarasi atau perilaku yang terlihat langsung pada source yang dibaca dalam laporan terkait.
- **INFERENSI TEKNIS** — interpretasi hubungan antar-komponen yang belum dibuktikan melalui eksekusi.
- **PERLU VERIFIKASI RUNTIME** — membutuhkan konfirmasi route dispatch, konfigurasi deployment, session, storage, schema, atau hasil eksekusi.
- **PERLU VALIDASI SEKOLAH** — memerlukan keputusan kebutuhan/policy Project Owner atau sekolah.
- **BELUM DITELAAH / BELUM DIKETAHUI** — bukti belum cukup atau area belum diperiksa.

### Sumber wajib yang dikonsolidasikan

| Tahap | Dokumen sumber | Peran dalam master |
|---|---|---|
| 1 | [TAHAP_1_INVENTARIS.md](./TAHAP_1_INVENTARIS.md) | Struktur, teknologi, modul, role, tabel/model, test, dan keterbatasan inventaris. |
| 2 | [TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md](./TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md) | Auth/profil, gateway, PPDB/SPMB, dan temuan `WF-*`. |
| 2B | [TAHAP_2B_WORKFLOW_TU_AKADEMIK.md](./TAHAP_2B_WORKFLOW_TU_AKADEMIK.md) | Workflow TU/data akademik dan `TU-AK-*`, `TU-OBS-*`. |
| 2C | [TAHAP_2C_WORKFLOW_PENJADWALAN.md](./TAHAP_2C_WORKFLOW_PENJADWALAN.md) | Penjadwalan dan `2C-WF-*`, `2C-*`. |
| 2D | [TAHAP_2D_WORKFLOW_GURU_SISWA.md](./TAHAP_2D_WORKFLOW_GURU_SISWA.md) | Guru/siswa, pembelajaran, dan `2D-WF-*`, `2D-*`. |
| 2E | [TAHAP_2E_WORKFLOW_EKSTRAKURIKULER.md](./TAHAP_2E_WORKFLOW_EKSTRAKURIKULER.md) | Ekstrakurikuler dan `EXK-*`. |
| 2F | [TAHAP_2F_WORKFLOW_DOKUMEN_SURAT.md](./TAHAP_2F_WORKFLOW_DOKUMEN_SURAT.md) | Dokumen/surat dan `DOK-*`, `SUR-*`, `2F-*`. |
| 3 | [TAHAP_3_AUDIT_OTORISASI_LINTAS_PERAN.md](./TAHAP_3_AUDIT_OTORISASI_LINTAS_PERAN.md) | Mekanisme authz, temuan `AUTHZ-001`–`AUTHZ-011`, pertanyaan policy. |
| 3A | [TAHAP_3A_PENDALAMAN_OTORISASI.md](./TAHAP_3A_PENDALAMAN_OTORISASI.md) | Verifikasi ulang dan pemisahan status AUTHZ; menambahkan `AUTHZ-012`. |
| 3B | [TAHAP_3B_CAKUPAN_AUDIT_OTORISASI.md](./TAHAP_3B_CAKUPAN_AUDIT_OTORISASI.md) | Pemetaan route-action, indeks temuan terkini, statistik cakupan, dan celah route. |

Metode yang tercatat pada laporan sumber adalah pembacaan statis route, middleware, view/form, controller/request, model/relasi, migration, storage reference, dan test yang relevan. Tidak ada laporan tahap yang menjalankan test, build, request aplikasi, migration, seeder, operasi cache, atau query database. Pembaruan 22 November 2026 melakukan pembacaan statis terbatas atas relasi akun pegawai, role, penghapusan pegawai, dan constraint penugasan; tidak menjalankan aplikasi atau mengakses database.

### Batas audit

- Tidak ada source, route, workflow frontend, konfigurasi, schema, atau data yang diubah sebagai bagian dari audit.
- Tidak dibuka `.env`, credential/token, dokumen pribadi, atau dataset siswa.
- Tidak dilakukan uji eksploitasi. Temuan statis bukan klaim bahwa akses berhasil atau bug pasti terjadi pada runtime.
- Tidak ada sumber kebijakan sekolah atau regulasi yang dijadikan bukti. Tidak ada klaim kepatuhan regulasi.
- **Realtime Notification dan Activity Log dikecualikan** dari cakupan audit ini; penyebutan notifikasi bisnis pada beberapa workflow hanya konteks kode yang dilalui, bukan audit kanal/realtime.

## 3. Inventaris modul dan komponen utama

Status “teridentifikasi/terpetakan sebagian” berasal dari inventaris Tahap 1 dan tidak berarti fitur telah diuji. Referensi file sumber dan bukti rinci berada pada laporan tahap yang dicantumkan.

| Modul/komponen | Fungsi yang tampak dari source | Komponen bukti yang dicatat | Cakupan konsolidasi |
|---|---|---|---|
| Publik/gateway | Halaman publik dan statistik agregat. | `routes/web.php`, `routes/api.php`, `GatewayController`, Vue/Blade publik. | Terpetakan sebagian; interaksi seluruh halaman publik tidak diperiksa. |
| Autentikasi, profil, akun | Login NIP/NIS, verifikasi/reset/perubahan password, logout, pengelolaan akun dan profil. | `routes/auth.php`, `ProfileController`, `LoginRequest`, controller Auth, `User`, middleware/policy. | Workflow utama dipetakan statis; role admin/SPMB, beberapa route profil, sesi/status akun dan akses akun lintas role belum tuntas. |
| Admin, Superadmin, TU | Dashboard, akun, approval, kepegawaian dan data master/administrasi. | `routes/admin.php`, `superadmin.php`, `tu.php`; controller terkait dan view `resources/views/{admin,superadmin,tu}`. | Tahap 2B dan 3B memetakan sebagian subalur; tidak semua CRUD/bulk/import/export/print diperiksa. |
| PPDB/SPMB | Pendaftaran publik, cek status/daftar ulang, keputusan pendaftar, ekspor, dan penempatan kelas. | `routes/ppdb-public.php`, `ppdb-admin.php`, controller PPDB, view publik/admin, model `PpdbPendaftar`/`PpdbSetting`. | Jalur inti diperiksa lintas Tahap 2 dan 3B; tidak semua cabang dan route export/target diperiksa tuntas. |
| Data siswa, kelas, rombel, tahun ajaran, penempatan, mapel | Administrasi siswa/kelas dan periode akademik. | Controller TU, model `Siswa`, `Kelas`, `PenempatanSiswa`, `TahunAjaran`, `MataPelajaran`, migration terkait. | Subalur TU utama dipetakan; area kenaikan kelas, beberapa view/resource/method dan kondisi schema masih terbuka. |
| Penjadwalan akademik | Draft/pengajuan guru, review/publikasi, jadwal final dan tampilan siswa. | `JadwalSayaController`, `PengajuanJadwalController`, `MonitoringJadwalController`, controller jadwal final, `PengajuanJadwal`. | Workflow inti statis terpetakan, tetapi ada jalur resource paralel, isu route-view, aturan kuota/semester/ruang, dan kode legacy yang belum dipastikan. |
| Pembelajaran guru/siswa | Dashboard, absensi, nilai, raport, perkembangan dan detail siswa. | Controller guru/siswa, view akademik, `AbsensiSiswa`, `NilaiSiswa`, `PerkembanganSiswa`, relasi kelas/assignment. | Alur baca/tulis utama dipetakan sebagian; pembatasan ID, validasi nilai, semester dan cakupan assignment tidak konsisten/masih perlu diperiksa. |
| E-learning | Materi/forum, lampiran, komentar dan balasan. | `ElearningController`, controller siswa, komentar/reply, `Elearning` dan lampiran. | Baca siswa dan sebagian pembuatan guru ditelusuri; resource edit/update/delete guru kosong menurut pembacaan sumber, komentar guru memerlukan penjelasan tambahan, dan download lampiran belum terpetakan. |
| Ekstrakurikuler | Master kegiatan, pembina, anggota, presensi, jurnal, pembinaan, jadwal dan monitoring. | Controller TU/guru/siswa/Superadmin, model `Ekskul*`, `PengajuanJadwalEkskul`, view per role. | Workflow utama dicatat dengan ID `EXK-*`; pengajuan anggota siswa, konsistensi representasi anggota, scope penugasan dan status tertentu masih belum pasti. |
| Dokumen, arsip, surat, nomor surat | Upload/daftar dokumen, pengajuan dan validasi surat, nomor surat. | `Dokumen`, `PengajuanSurat`, `NomorSurat`, `ArsipDokumen`, migration dan controller/view per role. | DOK/SUR/2F dipetakan statis; penyajian file publik, status migration/controller, ownership dan arsip legacy memerlukan verifikasi. |
| Keuangan | Controller/model pemasukan ditemukan pada inventaris; route aktif tidak ditemukan pada pencarian yang dilaporkan. | `Tu\KeuanganPemasukanController`, model terkait. | Belum dapat dianggap workflow route aktif; route/schema/runtime belum dipastikan. |
| Test | Feature, unit dan JavaScript tests ditemukan pada tahap inventaris; test relevan disebutkan pada laporan spesifik. | `tests/Feature/`, `tests/Unit/`, `tests/JavaScript/`. | File test dibaca secara selektif; tidak ada test yang dijalankan dan keberadaan test bukan bukti workflow berfungsi. |

Tahap 1 mencatat manifest Laravel 12/PHP 8.2 serta Vue 3/Vite/Tailwind/Bootstrap dan dependency lain sebagai **deklarasi manifest**, bukan hasil verifikasi versi terpasang. Tahap 1 mencatat 109 migration, 46 model, 250 Blade view, dan test PHP/JavaScript pada inventarisnya. Seluruhnya bukan hasil introspeksi database/runtime.

## 4. Peta workflow frontend–backend berdasarkan modul

ID workflow dipertahankan persis sebagaimana pada laporan asal. “Lengkap statis” hanya menjelaskan rantai source yang ditelusuri, bukan bukti alur dieksekusi berhasil.

| Modul | Workflow/ID asal dan peta ringkas | Status dan bukti utama |
|---|---|---|
| Auth/profil | `AUTH-01`, `AUTH-02`, `AUTH-03`, `AUTH-04`, `AUTH-05`, `AUTH-06`, `AUTH-07`, `AUTH-08`, `AUTH-09`: login/logout, verifikasi/reset/perubahan password, profil umum/detail, pembuatan akun admin/pegawai dan approval. | Parsial hingga lengkap statis per subalur; redirect admin, method profil, role dan ketersediaan interface verifikasi belum dipastikan. Lihat Tahap 2 §3A dan `WF-001`–`WF-004`. Bukti inti: `routes/auth.php:13-52`, `routes/web.php:56-62`, `LoginRequest.php:30-154`, `AuthenticatedSessionController.php:17-108`. |
| Gateway/halaman publik | Workflow gateway dan statistik publik ditelusuri dalam Tahap 2; API statistik ditemukan publik dan `/api/user` memakai `auth:sanctum` pada Tahap 3B. | Terpetakan sebagian; respons/config Sanctum/integrasi runtime tidak diuji. Bukti: `routes/api.php:7-8`, `bootstrap/app.php:9`. |
| PPDB/SPMB | Workflow publik informasi, pendaftaran, status dan daftar ulang; workflow internal daftar/detail, keputusan, pengaturan, ekspor, konfirmasi dan penempatan kelas. | Inti statis dipetakan; form pendek vs wizard dan direct GET status dicatat sebagai `WF-005`/`WF-006`; verifikasi ganda `WF-007`; role SPMB belum selaras pada source yang dibaca (`AUTHZ-011`). Route `excel/pdf` target method tidak ditemukan pada mapping Tahap 3B. |
| TU/data akademik | `TU-AK-01`, `TU-AK-02`, `TU-AK-03`, `TU-AK-04`, `TU-AK-05`, `TU-AK-06`, `TU-AK-07`, `TU-AK-08`, `TU-AK-09`, `TU-AK-10`, `TU-AK-11`: dashboard, siswa, siswa baru, penempatan/mutasi, kenaikan kelas, grup/rombel, tahun ajaran, mapel, angkatan, kepegawaian/akun. | Sebagian subalur statis lengkap; keseluruhan parsial. Temuan `TU-OBS-01`–`TU-OBS-08` mencakup rujukan view yang tidak ditemukan, nama method route, import kelas, soft delete, transisi, hitungan dashboard, dan kenaikan kelas. Bukti route utama: `routes/tu.php:38-45,90-390`. |
| Penjadwalan akademik | `2C-WF-01`, `2C-WF-02`, `2C-WF-03`, `2C-WF-04`, `2C-WF-05`, `2C-WF-06`, `2C-WF-07`, `2C-WF-08`: guru menambah/mengirim/menghapus draft; Superadmin menyetujui/menolak/menerbitkan; TU/Superadmin melihat/ekspor; siswa membaca jadwal. | Jalur utama statis dipetakan, namun resource pengajuan terpisah memiliki cakupan kontrol berbeda dan ekspor TU merujuk nama route Superadmin. Konflik, semester/ruang, kuota dan legacy tercatat `2C-001`–`2C-007`. Bukti: `routes/guru.php:173-196`, `routes/superadmin.php:68-90`, `routes/tu.php:182-188`. |
| Guru/siswa akademik | `2D-WF-01`, `2D-WF-02`, `2D-WF-03`, `2D-WF-04`, `2D-WF-05`, `2D-WF-06`, `2D-WF-07`, `2D-WF-08`, `2D-WF-09`, `2D-WF-10`, `2D-WF-11`, `2D-WF-12`: dashboard, ringkasan siswa, nilai/absensi baca, pencatatan absensi/nilai/perkembangan, raport/detail siswa, e-learning guru/siswa, pencarian pengumpulan tugas. | Read flow siswa, sebagian input, dan raport ditelusuri; keseluruhan parsial. Pengumpulan tugas tidak ditemukan dalam cakupan pencarian, bukan klaim tidak ada di repository. Temuan `2D-001`–`2D-011` merinci scope dan validasi. |
| Ekstrakurikuler | `EXK-01`, `EXK-02`, `EXK-03`, `EXK-04`, `EXK-05`, `EXK-06`, `EXK-07`, `EXK-08`, `EXK-09`, `EXK-10`: CRUD kegiatan TU, pembina Superadmin, anggota, pendaftaran siswa, pengajuan/review jadwal, presensi, jurnal, pembinaan, detail/absensi siswa. | Pemetaan beragam dari lengkap statis hingga parsial/belum ditemukan. Detail temuan `EXK-T01`–`EXK-T09`; route submit jadwal menunjuk method yang tidak ditemukan dan keanggotaan memakai dua representasi status. |
| Dokumen/surat | `DOK-01`, `DOK-02`, `DOK-03`; `SUR-01`, `SUR-02`, `SUR-03`, `SUR-04`: CRUD dokumen TU, pembacaan/unduhan oleh role, arsip, pengajuan surat guru/TU, validasi Superadmin, nomor surat per role. | Jalur utama statis diketahui; file URL publik, ownership CRUD surat, status/migration, schema arsip, dan nomor surat konkuren masih perlu diverifikasi. `2F-01`–`2F-10` merinci. Bukti route: `routes/tu.php:243-331`, `routes/guru.php:319-328`, `routes/superadmin.php:180-215`. |

## 5. Matriks aktor, role, middleware, dan cakupan otorisasi

### Role dan middleware yang teridentifikasi

| Aktor/role | Deklarasi/route yang tercatat | Kontrol statis dan keterbatasan |
|---|---|---|
| `superadmin` | `app/Enums/UserRole.php:5-11`; group `routes/superadmin.php:27`. | `RoleMiddleware.php:10-29` meloloskan superadmin sebelum pencocokan role tujuan. Ini menunjukkan bypass role pada middleware tersebut, bukan bypass otomatis atas scope di controller. Maksud desain perlu diputuskan (`AUTHZ-001`). |
| `admin` | Enum yang sama; `routes/admin.php:8-9`. | Group memakai `auth`, `approved`, `role:admin`; operasi akun memiliki sebagian kontrol target dan middleware `forbid.superadmin`, tetapi login admin tidak memiliki cabang redirect yang ditemukan (`WF-003`). |
| `tu` | Enum; `routes/tu.php:38`; PPDB internal `routes/ppdb-admin.php:10`. | Group TU memakai `auth`, `role:tu`, `tu.mode`; sebagian route memakai `approved`/`tahun.aktif`. `AkunController` berbasis ID tidak tampak memfilter role target (`AUTHZ-002`). |
| `guru` | Enum; `routes/guru.php:33-36`. | Group memakai `auth`, `role:guru`, `guru.mode`; scope assignment bervariasi antar-method, khususnya detail siswa, absensi edit/update, pengajuan jadwal/surat dan operasi ekskul. |
| `siswa` | Nilai terpusat adalah `'siswa'` (konstanta bernama `MURID`); `routes/siswa.php:16`. | Route siswa yang ditelaah memakai `auth`, bukan `role:siswa`. Sejumlah controller melakukan scope lewat relasi siswa/penempatan, sementara lainnya bergantung pada relasi tersebut (`2D-001`, `AUTHZ-008`, `AUTHZ-012`). |
| `spmb` | Dipakai pada group dashboard SPMB `routes/web.php:47-48`; tidak ada pada daftar role pusat yang diperiksa Tahap 1/3. | Konflik deklarasi dan jalur login; keberadaan konfigurasi/akun runtime belum dipastikan (`AUTHZ-011`). |

Alias custom yang dicatat pada `bootstrap/app.php` mencakup `role`, `approved`, `forbid.superadmin`, `tahun.aktif`, `guru.mode`, dan `tu.mode`. Laporan Tahap 3 menyebut `approved` tidak terpasang secara seragam, sedangkan mode Guru/TU terutama mengelola session mode. Policy/Gate yang ditemukan tidak dianggap melindungi endpoint bila tidak ada call-site otorisasi pada jalur yang ditelaah. Pengaruh middleware framework, deployment, guard, route cache dan session belum diverifikasi runtime.

**Akun pegawai:** source memodelkan satu kolom `role` pada `User`; pembuatan akun TU menetapkan satu role dari teks jabatan. Admin dan SuperAdmin mengelola nilai role tunggal itu. `User` terhubung ke `DataKepegawaian` melalui `pegawai_id`, dan relasi ke `Guru` menggunakan pasangan `pegawai_id`–`data_kepegawaian_id`. Temuan, batas pembuktian constraint, dan risiko penghapusan dirinci pada §6.8. Bukti: `app/Models/User.php:14-22,86-100`; `app/Http/Controllers/Tu/DataKepegawaianController.php:251-313`; `app/Http/Controllers/Admin/UserController.php:82-96,115-136`; `app/Http/Controllers/SuperAdmin/UserController.php:15-17,31-43`.

### Ringkasan role × area

`Route` menunjukkan deklarasi group; bukan jaminan semua baris record dibatasi. `Controller` berarti terdapat pembatasan data pada sebagian method yang ditelusuri. `Belum` berarti tidak semua operasi telah diaudit.

| Area | Admin | Superadmin | TU | Guru | Siswa | SPMB |
|---|---|---|---|---|---|
| Dashboard/akun | Admin group; beberapa target dibatasi. | Group superadmin; bypass middleware role lintas group juga berlaku. | Akun lintas role dapat ditargetkan pada operasi TU yang diperiksa. | Tidak ada manajemen akun yang ditemukan pada route guru. | Tidak ada manajemen akun siswa pada group siswa yang diperiksa. | Route meminta `role:spmb`; definisi/akses belum dipastikan. |
| Data master/administrasi | Tidak ditemukan CRUD TU pada group admin yang ditelaah. | Sebagian pengelolaan dan penetapan. | Route TU mencakup siswa, guru, kelas, tahun, mapel, dokumen, kepegawaian dan area lain. | Tidak ditemukan operasi master TU pada group guru. | Baca data pribadi/akademik pada route siswa. | Belum dipetakan. |
| Jadwal akademik | Tidak ditemukan operasi utama. | Monitoring/keputusan/publikasi dan jadwal final. | Baca/ekspor jadwal final. | Draft dan pengajuan; beberapa endpoint perlu pembatasan scope. | GET jadwal dipautkan ke penempatan aktif di controller yang dibaca. | Belum dipetakan. |
| Absensi/nilai/rapor/perkembangan | Tidak ditemukan operasi yang ditelaah. | Tidak ditemukan pencatatan pada route yang dibaca. | Tidak ditelusuri menyeluruh. | CRUD/rekap sesuai role; pembatasan assignment tidak seragam pada method. | Read query yang ditelaah menyaring siswa sesi dan tahun. | Belum dipetakan. |
| E-learning/ekskul | Belum dipetakan. | Penetapan pembina/monitoring. | Master ekskul. | Materi, anggota, presensi, jurnal, pembinaan, jadwal; ada scope yang belum tampak pada operasi ID. | Materi/aktivitas pada group `auth`; sebagian query memakai kelas/anggota sesi. | Belum dipetakan. |
| Dokumen/surat | Belum ditelusuri tuntas. | Dokumen, validasi surat, nomor surat. | Dokumen, pengajuan, nomor surat dan area arsip. | Dokumen baca dan CRUD pengajuan surat. | Dokumen/nomor surat pada group `auth`. | Belum dipetakan. |
| PPDB/SPMB | Tidak ditemukan di group admin yang diperiksa. | Bypass `role` memungkinkan lintasan jika route memakai middleware tersebut. | Route internal PPDB memakai `auth`, `role:tu`; publik tanpa login. | — | — | Route role belum selaras. |

Matriks lengkap operasi tertentu terdapat pada [Tahap 3 §3.2](./TAHAP_3_AUDIT_OTORISASI_LINTAS_PERAN.md); Tahap 3B memiliki daftar family route yang lebih luas. Matriks ini bukan keputusan policy sekolah.

## 6. Temuan teknis terindeks

Ringkasan berikut mempertahankan ID asli. Path/baris merujuk bukti yang disebut pada laporan asal; status adalah status audit yang tercatat, bukan status perbaikan. Untuk detail endpoint, narasi bukti, dan batas pemeriksaan, lihat laporan tahap yang dirujuk.

### 6.1 Workflow/auth awal — `WF-*`

| ID | Ringkasan temuan dan bukti | Klasifikasi/status konsolidasi | Tindak lanjut |
|---|---|---|---|
| WF-001 | Route GET `/profile` menunjuk `ProfileController::edit` yang tidak ditemukan; form `profile.update` memakai PATCH sementara route didaftarkan POST. `routes/web.php:56-62`; `ProfileController.php:21-84,98-146,150-188`; `resources/views/profile/partials/update-profile-information-form.blade.php:16-20`. | Fakta source; dampak rendering/submission perlu runtime. | Verifikasi pemetaan view, method dan method HTTP pada environment uji. |
| WF-002 | Form admin menawarkan role `murid` dan meminta konfirmasi password, sementara store menerima `admin,tu,guru` dan tidak menetapkan rule `confirmed`. `resources/views/admin/users/create.blade.php:24-45`; `Admin/UserController.php:82-106`. | Fakta source; efek submit tidak diuji. | Validasi daftar role yang disetujui sekolah dan perilaku form/backend. |
| WF-003 | Login yang dibaca mencari NIP/NIS; redirect tidak memiliki cabang `admin`, walaupun route/dashboard admin ada. `login.blade.php:289-338`; `LoginRequest.php:43-154`; `AuthenticatedSessionController.php:25-108`; `routes/admin.php:8-14`. | Fakta source; alur login aktual belum diuji. | Konfirmasi mekanisme identitas dan login akun admin. |
| WF-004 | Controller memanggil API verifikasi email, tetapi deklarasi `User` yang dibaca tidak menyatakan interface/trait verifikasi yang diharapkan. `User.php:10-17`; controller Verify/Notification masing-masing `:15-25` dan `:14-23`. | Fakta source; ketersediaan method/runtime belum diketahui. | Periksa implementasi trait/parent runtime pada dependency dan uji aman. |
| WF-005 | View pendaftaran pendek mengirim nama/NIK/KK, sedangkan GET/store PPDB yang dipetakan menggunakan wizard dan validasi lebih luas. `ppdb/public/daftar.blade.php:1-35`; `PPDB/PublicController.php:28-47,51-160`. | Fakta perbedaan source; hubungan view ke entry point belum dipastikan. | Telusuri tautan/target view dan validasi flow aktif. |
| WF-006 | Form status memakai modal layout publik; GET langsung `/ppdb/status` merender view tanpa data yang sama. `routes/ppdb-public.php:35-43`; `PublicController.php:422-460`; `layouts/public.blade.php:626-661`; `status.blade.php:145-158`. | Fakta source; perilaku browser belum diuji. | Verifikasi alur modal dan direct GET pada test environment. |
| WF-007 | GET `/tu/ppdb/pendaftar/{pendaftar}/verifikasi` terdaftar dua kali ke controller yang sama dengan nama berbeda. `routes/ppdb-admin.php:84-92`. | Fakta deklarasi; route efektif perlu runtime. | Konfirmasi route collection pada environment uji yang disetujui. |

### 6.2 TU dan administrasi akademik — `TU-OBS-*`

| ID | Ringkasan temuan dan bukti | Klasifikasi/status konsolidasi | Tindak lanjut |
|---|---|---|---|
| TU-OBS-01 | Sejumlah controller merujuk view yang tidak ditemukan pada path yang diperiksa (guru edit, grup-create/wali, mapel detail, tahun edit/detail, angkatan CRUD, kenaikan kelas). Contoh `GuruController.php:81`, `KelasController.php:209,333`, `MataPelajaranController.php:87`, `TahunAjaranController.php:155,259`, `AngkatanController.php:15,20,43`, `PenempatanSiswaController.php:247,300`. | Fakta referensi/path; efek runtime belum diuji. | Konfirmasi view alternatif/dinamis dan render pada environment uji. |
| TU-OBS-02 | Route toggle memanggil `AngkatanController::toggle`, sedangkan method yang ditemukan `toggleAktif`; controller menggunakan kolom `aktif`, migration menghapusnya. `routes/tu.php:388-390`; controller `:70`; migration `2026_05_15_135051_drop_aktif_from_angkatan_table.php`. | Fakta source; schema aktif dan efek runtime belum diketahui. | Cocokkan migration/schema pada lingkungan non-produksi dan route mapping. |
| TU-OBS-03 | Route menunjuk `masukTransisi`, `previewNaikKelas`, `requestAccount` yang tidak ditemukan; resource guru juga meminta `create` yang tidak ditemukan. Sebagian method alternatif ada. | Fakta source; target route/runtime belum diuji. Bukti route/controller: `routes/tu.php:165,212,288,357`; `routes/tu.php:388-390`; daftar lengkap Tahap 3B §3.2. | Inventarisasi setiap target/action hilang pada 3B; cek route dispatch tanpa production request. |
| TU-OBS-04 | Form berjudul import kelas mengarah ke `tu.siswa.import`; modal menerima CSV, controller import menyebut XLS/XLSX. `resources/views/tu/kelas/index.blade.php:121-125`; `routes/tu.php:139-144`; `SiswaController.php:338-354`. | Fakta markup/route; maksud fungsional inferensial. | Validasi kontrak format dan tujuan dengan pemilik proses. |
| TU-OBS-05 | Controller memakai `withTrashed`/restore, tetapi model yang dibaca tidak menunjukkan `SoftDeletes`. `MataPelajaranController.php:17-23,135-151`; `MataPelajaran.php:7-38`; `TahunAjaranController.php:152-158,196-212,256-260`; `TahunAjaran.php:7-58`. | Fakta source; method/schema runtime belum dibuktikan. | Periksa model/schema/runtime terisolasi. |
| TU-OBS-06 | Teks konfirmasi transisi semester menyebut pembatalan bila ada data tidak valid, sedangkan cabang controller melewati data invalid lalu tetap memperbarui status tahun. `tu/tahun/index.blade.php:80-100`; `TahunAjaranController.php:650-724`. | Fakta source; dampak terhadap data aktual tidak diuji. | Validasi aturan transisi dengan sekolah dan periksa alur secara terisolasi. |
| TU-OBS-07 | Kartu dashboard melabeli akun aktif, tetapi `$akun` berasal dari `User::count()` tanpa filter status aktif. `DashboardController.php:20-24`; `tu/dashboard.blade.php:65-77`. | Fakta source; makna label merupakan keputusan produk. | Konfirmasi definisi hitungan yang dikehendaki. |
| TU-OBS-08 | Kenaikan kelas memerlukan array siswa; view mapping/index yang dirujuk tidak ditemukan, dan preview target juga tidak ditemukan. `PenempatanSiswaController.php:247,300`; route `routes/tu.php:357`. | Fakta source/pencarian; alur alternatif belum diketahui. | Telusuri seluruh entry point dan minta konfirmasi proses sekolah. |

### 6.3 Penjadwalan — `2C-*`

| ID | Ringkasan temuan dan bukti | Klasifikasi/status konsolidasi | Tindak lanjut |
|---|---|---|---|
| 2C-001 | Seeder memberi tingkat kelas Romawi, helper kuota mencocokkan integer 1–6 lalu menggunakan default 35. `TahunHelper.php:21-32`; `JadwalSayaController.php:197-205`; `KelasMasterSeeder.php:9-13`. | Fakta implementasi + inferensi pada data seeder; nilai runtime belum diperiksa. | Validasi sumber nilai tingkat dan aturan kuota. |
| 2C-002 | Resource pengajuan mengambil semua record dan menggunakan `auth()->user()->guru_id`; model `User` juga memetakan guru via `pegawai_id`. `PengajuanJadwalController.php:16-18,40-56`; `User.php:14-17,86-99`. | Fakta source; hubungan field/identitas runtime belum diketahui. Terkait `AUTHZ-005`, tanpa menghapus ID terpisah. | Konfirmasi identitas guru dan perilaku daftar resource. |
| 2C-003 | View membaca `$item->jam->nama`, sedangkan model menyediakan `jamPelajaran()` dan schema `jam_pelajaran_id`. `guru/pengajuan-jadwal/index.blade.php:35-42`; model `PengajuanJadwal.php:49-61`; migration `2026_05_07_092325_create_pengajuan_jadwal_table.php:31-47`. | Fakta source; render belum diuji. | Verifikasi relasi/view pada environment uji. |
| 2C-004 | Form ekspor final TU memanggil nama route Superadmin meski route TU terdaftar. `tu/jadwal-final/index.blade.php:24-25`; `routes/tu.php:182-188`; `routes/superadmin.php:152-158`. | Fakta source; hasil route generation belum diuji. | Validasi link ekspor pada environment uji. |
| 2C-005 | Dua alur pembuatan draft berbeda: resource hanya required, grid memvalidasi ID, assignment, konflik dan kuota. `PengajuanJadwalController.php:40-56`; `JadwalSayaController.php:140-220`. | Fakta source; jalur UI aktif/tujuan bisnis belum diketahui. | Konfirmasi apakah resource masih merupakan entry point yang dimaksud. |
| 2C-006 | Konflik yang tampak memeriksa slot kelas dan benturan guru; tidak ditemukan pemeriksaan ruang dan schema pengajuan tidak memiliki foreign key ruang. `JadwalSayaController.php:176-205`; `MonitoringJadwalController.php:275-290`; migration pengajuan `:11-47`. | Fakta source; tidak menyimpulkan kebutuhan ruang. | Tanyakan kebutuhan sekolah tentang ruang dan konflik. |
| 2C-007 | Kode legacy `Jadwal`/`JadwalGrup` dan `GetJPStatus` ditemukan, tetapi route operasional/pemanggil aktif belum ditemukan. `GetJPStatus.php:8-57`; `Tu/JadwalGrupController.php:14-18,89-101,148`; pencarian route. | Fakta hasil pencarian terbatas; status aktif belum diketahui. | Telusuri pemanggil dinamis pada tahap terpisah. |

### 6.4 Guru/siswa — `2D-*`

| ID | Ringkasan temuan dan bukti | Klasifikasi/status konsolidasi | Tindak lanjut |
|---|---|---|---|
| 2D-001 | Route siswa memakai `auth`, bukan `role:siswa`; beberapa controller mengakses `$user->siswa` tanpa null guard, sementara dashboard/e-learning berbeda. `routes/siswa.php:16-18`; `Siswa/NilaiController.php:14-18`; `Siswa/AbsensiController.php:15-19`; `Siswa/ElearningController.php:12-15`. | Fakta source; perilaku akun non-siswa belum diuji. | Validasi actor dan relasi yang diwajibkan per route. |
| 2D-002 | Jadwal absensi yang dipilih dari request diambil dengan `find($jadwalId)`; jalur simpan memeriksa ulang guru. `guru/absensi/index.blade.php:77-96,165-180`; `Guru/AbsensiController.php:71-80,138-193`. | Fakta source; akses baca/tulis berbeda pada alur yang ditelaah. | Tinjau boundary baca dan jalankan hanya pada environment uji berizin. |
| 2D-003 | Edit/update absensi berbasis binding; update mengubah status/keterangan tanpa validasi atau scope guru yang terlihat. `routes/guru.php:223-231`; `Guru/AbsensiController.php:378-401`. Terkait `AUTHZ-004`. | Fakta source; bukan bukti operasi berhasil lintas pemilik. | Validasi policy koreksi absensi dan periksa jalur lengkap. |
| 2D-004 | Input nilai membatasi 0–100 di HTML; server tidak tampak memvalidasi rentang/struktur nilai; assignment mapel/kelas diperiksa, keanggotaan setiap ID siswa tidak tampak diperiksa di loop. `guru/nilai/index.blade.php:365-396`; `Guru/NilaiController.php:356-443`. | Fakta source/inferensi risiko; payload/database tidak diuji. | Tinjau kontrak validasi dan hubungan siswa-kelas sebelum keputusan perubahan. |
| 2D-005 | Guru bidang bisa memilih kelas, tetapi penyimpanan perkembangan mensyaratkan wali kelas; field semester diwajibkan migration tetapi tidak tampak dikirim oleh controller. `PerkembanganSiswaController.php:35-55,120-153`; view `guru/perkembangan/index.blade.php:63-95,262-357`; migration perkembangan `:27-51`. | Fakta source; hasil persistensi belum diuji. | Konfirmasi actor, semester, dan perilaku schema pada environment uji. |
| 2D-006 | View komentar/reply guru mengarah ke handler bersama yang mengambil `$user->siswa` dan mengecek kelas siswa aktif. `routes/guru.php:262-271`; `guru/elearning/show.blade.php:167-179,292-314`; `ElearningKomentarController.php:18-30`; `ElearningKomentarReplyController.php:18-25`. | Fakta source + mismatch teknis potensial; request tidak dicoba. | Pastikan konteks actor yang dimaksud; uji aman setelah persetujuan. |
| 2D-007 | Resource e-learning memiliki edit/update/destroy, sementara method tersebut memiliki body kosong. `Guru/ElearningController.php:377-412`; `routes/guru.php:252-255`. | Fakta source; hasil response/runtime tidak diuji. | Cocokkan route/UI yang benar-benar dipakai dan perilaku method. |
| 2D-008 | Raport memberi label tahun/semester; nilai/absensi tidak memiliki kolom semester pada schema yang dibaca, perkembangan memiliki semester; query raport difilter siswa/tahun. `RaportController.php:47-145,160-186`; model nilai/absensi/perkembangan; `raport/show.blade.php:70-72`. | Fakta source; makna data aktif belum diketahui. | Konfirmasi semantik tahun/semester dan data yang diharapkan. |
| 2D-009 | Detail siswa guru mengambil data akademik berbasis ID siswa/tahun tanpa pemeriksaan assignment yang tampak. `Guru/WaliKelasController.php:144-251`; `routes/guru.php:294-299`; link view `guru/kelas-saya/index.blade.php:213`. Terkait `AUTHZ-003`. | Fakta source; dampak tidak diuji. | Validasi batas akses guru bidang/wali kelas dan scope target. |
| 2D-010 | E-learning siswa difilter kelas aktif, tetapi `Elearning` yang dibaca tidak memiliki `tahun_ajaran_id`; forum guru discope assignment tahun berjalan. `Siswa/ElearningController.php:20-117`; `Elearning.php:7-58`; migration `2026_06_11_223438_create_elearnings_table.php:18-38`. | Fakta source; perilaku record lama belum diketahui. | Konfirmasi lifetime forum/assignment dan keterkaitan tahun ajaran. |
| 2D-011 | Rapor menerima assignment wali/pengampu terkait penempatan aktif, lalu mengambil seluruh nilai/absensi/perkembangan siswa tahun itu; filter mapel pengampu tidak tampak diterapkan ke seluruh raport. `RaportController.php:47-145,160-186`. | Fakta source; kesesuaian policy belum diketahui. | Validasi batas raport untuk guru bidang dan wali kelas. |

### 6.5 Ekstrakurikuler — `EXK-T*`

| ID | Ringkasan temuan dan bukti | Klasifikasi/status konsolidasi | Tindak lanjut |
|---|---|---|---|
| EXK-T01 | Route jadwal ekskul `submit` menunjuk method yang tidak ditemukan; alur test memakai PUT `update`. `routes/guru.php:354-356`; `JadwalEkskulController.php:17,57,101,132,203`; test `EkskulJadwalWorkflowTest.php:20-69`. | Fakta source; route dispatch belum diuji. | Validasi target route dan lifecycle yang dikehendaki. |
| EXK-T02 | Migration/model keanggotaan `anggota_ekskuls` memakai status `pending/diterima/ditolak`; jalur aktif yang ditemukan memakai `ekskul_siswa` dengan status lain, pembina membuat `aktif`. Migration `2026_05_29_001222...`, `2026_05_29_003638...`; `EkskulAnggotaController.php:179`; `EkskulSiswa.php:7-25`. | Fakta source; schema aktif tidak diakses. | Tentukan relasi/model yang canonical melalui verifikasi schema dan pemilik proses. |
| EXK-T03 | Sejumlah query pembina/reKap memakai guru_id tanpa filter tahun ajaran, sementara alur presensi tertentu memakai tahun aktif. Controller guru ekskul: `EkskulController.php:32-39`, `EkskulAnggotaController.php:29-39`, `JurnalEkskulController.php:20-25,59-63`, `CatatanPembinaanController.php:17-25`, `AbsensiEkskulController.php:171-196`. | Fakta source; dampak historis belum diketahui. | Validasi kebutuhan isolasi tahun/assignment. |
| EXK-T04 | UI mengondisikan kontrol approve/tolak/publish dengan `$bisaApprove`; backend memiliki operasi terpisah. View monitoring `:70-110,337-398`; route `routes/superadmin.php:234-247`; controller `MonitoringEkskulController.php:57-68,111-136`. | Fakta source; respons UI pada konflik belum diuji. | Uji tiap status/aksi pada environment uji setelah disetujui. |
| EXK-T05 | Delete jurnal memakai ID; store catatan memakai ID ekskul/siswa tanpa scope penugasan yang tampak. `JurnalEkskulController.php:91-100`; `CatatanPembinaanController.php:51-95`; view catatan `:214-219`. Terkait `AUTHZ-007`. | Fakta source; bukan bukti write lintas ekskul terjadi. | Validasi relasi guru–ekskul–anggota dan tahun aktif. |
| EXK-T06 | Index siswa menampilkan anggota aktif; show/absensi menerima ekskul route-bound tanpa pemeriksaan membership eksplisit yang ditemukan. `Siswa/EkskulController.php:13-38,41-90,94-175`; `routes/siswa.php:46-59`. Terkait `AUTHZ-008`. | Fakta source; akses statistik dan policy anggota belum diputuskan. | Validasi policy sekolah, lalu cek query secara lengkap. |
| EXK-T07 | Hitungan memakai label `alpa`, migration memakai `alpha`. `Siswa/EkskulController.php:151-160`; view absensi `:11-14`; migration `2026_05_29_060705_create_absensi_ekskul_table.php:28-32`. | Fakta perbedaan source; isi data tidak diperiksa. | Cocokkan enum source dan nilai data pada environment yang disetujui. |
| EXK-T08 | `AnggotaEkskul` mungkin merupakan jalur lama/alternatif karena tidak ditemukan pemakai pada pencarian, sedangkan jalur aktif memakai `EkskulSiswa`. `app/Models/AnggotaEkskul.php:7-37`; route/controller anggota. | Inferensi teknis. | Telusuri semua pemakai termasuk pemanggil di luar pencarian awal. |
| EXK-T09 | Kesamaan schema aktif dengan migration dan penggunaan endpoint yang targetnya tidak tersedia tidak dapat dipastikan. | Belum diketahui; tidak ada akses DB/request. | Verifikasi pada lingkungan non-produksi yang diizinkan. |

### 6.6 Dokumen/surat — `2F-*` (workflow `DOK-*`, `SUR-*`)

| ID | Ringkasan temuan dan bukti | Klasifikasi/status konsolidasi | Tindak lanjut |
|---|---|---|---|
| 2F-01 | Migration status surat menyebut `pending/disetujui/ditolak`, sedangkan controller Guru/TU menyimpan `draft` dan UI validasi mengenali `draft`. Migration `2026_05_09_215854...:31-35`; controller Guru `:76-86`, TU `:74-88`; view Superadmin `:165-198`. | Fakta source; status DB aktif belum diketahui. | Cocokkan history migration dengan schema dan alur status yang disetujui. |
| 2F-02 | Route siswa mendeklarasikan GET download dokumen dan nomor surat duplikat (closure dan controller). `routes/siswa.php:61-88`. | Fakta deklarasi; route efektif perlu runtime. | Periksa route collection tanpa menyentuh data pengguna. |
| 2F-03 | Resource nomor surat Superadmin menambah action CRUD, sedangkan controller yang dibaca hanya `index/generate`. `routes/superadmin.php:203-215`; `NomorSuratController`. | Fakta source; dispatch tidak diuji. | Cocokkan seluruh resource action dengan method/controller. |
| 2F-04 | Migration arsip membuat `nama_file/file_path/kategori`; model/controller memakai field tambahan; rujukan view/route tidak ditemukan. `2025_12_05_173923_init_all_tables.php:135-140`; `ArsipDokumen.php:7-22`; `ArsipDokumenController.php:33-57,98-118`. | Fakta source; schema runtime tidak diketahui. | Telusuri migration lain dan route/view alternatif pada baseline yang disetujui. |
| 2F-05 | Upload create memakai MIME PDF/DOC/DOCX tanpa batas ukuran yang tampak; update file pengganti tidak menunjukkan rule file; arsip memakai `max:50000`. `DokumenController.php:16-164`; controller pengajuan Guru/TU; `ArsipDokumenController.php`. | Fakta source pada controller yang dibaca; tidak ada upload diuji. | Validasi persyaratan file dengan pemilik proses; runtime tidak diuji. |
| 2F-06 | Dokumen/surat memakai URL `asset()` di `public/uploads`; tidak ada endpoint download berotorisasi yang ditemukan untuk tautan itu. | Fakta source; akses nyata bergantung server/storage (`AUTHZ-009`). | Periksa konfigurasi deploy dan perilaku pada lingkungan uji yang disetujui, tanpa membuka dokumen pribadi. |
| 2F-07 | Index pengajuan guru memfilter `user_id`, sedangkan show/edit/update/destroy memakai ID tanpa scope pemilik yang terlihat. `Guru/PengajuanSuratController.php:17-27,98-135,210-233`. | Fakta source; terkait `AUTHZ-006`; dampak akses belum diuji. | Validasi policy akses lintas pemilik. |
| 2F-08 | Migration menambah `validated_file`, tetapi penggunaan aplikasi tidak ditemukan; migration berikut menghapus `file_final`; method validasi tidak menyimpan file final. Migration `2026_05_09_231223...:11-24`, `2026_05_10_230504...:14-25`; controller validasi. | Fakta source/pencarian terbatas; kebutuhan business belum diketahui. | Konfirmasi apakah hasil surat final diperlukan dan status/field canonical. |
| 2F-09 | Empat controller nomor surat mengambil nomor terakhir global, menambah urutan, lalu menyimpan tanpa transaksi/locking yang tampak; migration memberi unique nomor. Controller Guru/TU/Siswa/Superadmin; migration `2026_05_11_224413...:23-45`. | Fakta source; tabrakan konkurensi adalah inferensi, tidak diuji. | Validasi format/uniqueness dan skenario concurrent pada lingkungan non-produksi. |
| 2F-10 | Keamanan URL publik, schema produksi, dan konfigurasi deployment/storage tidak diketahui. | Belum diketahui. | Minta konfigurasi non-rahasia dan verifikasi deployment yang disetujui. |

Workflow `DOK-01`–`DOK-03` dan `SUR-01`–`SUR-04` diringkas pada Bagian 4. Tidak ditemukan alur template/PDF surat dinamis, nomor otomatis yang ditautkan ke pengajuan surat, atau route arsip aktif dalam pencarian Tahap 2F; ini dibatasi sebagai hasil pencarian pada cakupan yang dilaporkan.

### 6.7 Otorisasi lintas peran — `AUTHZ-001`–`AUTHZ-012`

Status terkini mengikuti indeks Tahap 3B setelah pendalaman Tahap 3A. `TERKONFIRMASI SECARA STATIS` mengonfirmasi observasi source yang disebut, bukan keberhasilan eksploitasi. Kategori requirement di bawah dipertahankan dari laporan otorisasi.

| ID | Modul/aktor/endpoint dan observasi source | Bukti utama | Requirement/status | Dampak potensial dan tindak lanjut |
|---|---|---|---|---|
| AUTHZ-001 | Lintas modul; superadmin; middleware `role:<role>` meloloskan superadmin sebelum membandingkan role. | `RoleMiddleware.php:10-29`; route admin/guru/TU/Superadmin: `routes/admin.php:8`, `guru.php:33`, `tu.php:38`, `superadmin.php:27`. | `DESIGN DECISION`; terkonfirmasi statis untuk perilaku middleware. | Tetapkan apakah bypass lintas role disengaja; evaluasi scope controller terpisah. |
| AUTHZ-002 | TU/superadmin; daftar/detail/reset/toggle akun TU memakai target ID tanpa filter role target yang tampak. | `routes/tu.php:38-45,392-415`; `Tu/AkunController.php:12-98`; view akun `:136-163`. | `UNSUPPORTED`; terkonfirmasi statis. | Dapat memengaruhi akun lintas role menurut source; sekolah perlu menetapkan kewenangan TU. |
| AUTHZ-003 | Guru; detail siswa `GET guru.kelas-saya.show` menerima ID siswa dan method tidak tampak mengikat siswa ke assignment guru. | `routes/guru.php:294-299`; `Guru/WaliKelasController.php:144-249`. | `UNSUPPORTED`; terkonfirmasi statis atas method yang dibaca. | Potensi data akademik di luar assignment; validasi aturan guru dan scope alternatif/runtime. |
| AUTHZ-004 | Guru; edit/update absensi memakai binding; pemeriksaan assignment store tidak tampak mencakup edit/update. | `routes/guru.php:223-231`; `Guru/AbsensiController.php:378-401`; store `:138-195`. | `UNSUPPORTED`; terkonfirmasi statis. | Potensi akses/ubah ID record di luar assignment; validasi hak koreksi dan verifikasi aman. |
| AUTHZ-005 | Guru; index pengajuan jadwal mengambil daftar tanpa filter `guru_id`, sementara grid Jadwal Saya berbeda dan discope. | `routes/guru.php:193-196`; `Guru/PengajuanJadwalController.php:16-25,37-57`; pembanding `JadwalSayaController.php:319-345`. | `UNSUPPORTED`; terkonfirmasi statis. | Potensi melihat pengajuan lintas guru; konfirmasi apakah konsol bersama memang dimaksudkan. |
| AUTHZ-006 | Guru; daftar surat memfilter `user_id`, operasi show/edit/update/destroy berbasis ID tanpa scope pemilik yang terlihat. | `routes/guru.php:322`; `Guru/PengajuanSuratController.php:17-27,98-135,210-233`; view index `:48,101-116`. | `UNSUPPORTED`; terkonfirmasi statis. | Potensi operasi atas pengajuan lain; validasi policy dan file terkait. |
| AUTHZ-007 | Guru/pembina; delete jurnal dan store catatan pembinaan tidak tampak mencocokkan target ke assignment/relasi pembina. | `routes/guru.php:154-164,334-343`; `JurnalEkskulController.php:91-100`; `CatatanPembinaanController.php:51-95`. | `UNSUPPORTED`; terkonfirmasi statis. | Potensi perubahan lintas ekskul/peserta; konfirmasi relasi/tahun aktif. |
| AUTHZ-008 | Siswa; detail/absensi ekskul tidak tampak mengecek membership kegiatan; query absensi tetap mengikat siswa sesi. | `routes/siswa.php:16,46-59`; `Siswa/EkskulController.php:41-90,94-175`. | `SCHOOL POLICY`; perlu validasi sekolah. | Apakah statistik detail dapat dilihat nonanggota harus diputuskan; bukan klaim seluruh absensi terbuka. |
| AUTHZ-009 | Dokumen/surat; berkas disimpan di area public dan tautan menggunakan URL aset; perlindungan aktual bergantung konfigurasi server/storage. | `Tu/DokumenController.php:74,113,152-162`; `Guru/PengajuanSuratController.php:67,192`; beberapa view unduhan. | `UNSUPPORTED`; perlu verifikasi runtime. | Akses halaman dan file bisa berbeda; cek konfigurasi/test hanya di lingkungan yang disetujui. |
| AUTHZ-010 | Status approval/aktif tidak diterapkan seragam; perubahan status tidak membuktikan sesi lama tercabut. | `EnsureUserApproved.php:10-37`; `routes/admin.php:8-9`, `routes/tu.php:38-45`; `SuperAdmin/UserApprovalController.php:107`. | `DESIGN DECISION`; perlu verifikasi runtime. | Tetapkan kapan blokir berlaku; uji guard/session pada environment uji. |
| AUTHZ-011 | Role `spmb` diminta route tetapi tidak ada pada enum yang diperiksa; login/redirect tidak membuktikan akses SPMB. | `UserRole.php:5-11`; `routes/web.php:47-48`; `AuthenticatedSessionController.php:54-108`; `LoginRequest.php:30-154`. | `CONFLICT`; perlu verifikasi runtime dan validasi sekolah. | Pastikan role, identifier, metode login, dan akun SPMB yang sah. |
| AUTHZ-012 | POST pembuatan nomor surat pada group siswa memakai `auth`; method menggunakan user sesi tanpa pemeriksaan role/relasi siswa yang tampak. | `routes/siswa.php:73-88`; controller nomor surat siswa `:15-91` (lihat Tahap 3A §4); GET terkait tercatat duplikat. | `UNSUPPORTED` untuk klaim kontrol; perlu validasi sekolah untuk aktor sah. | Tetapkan aktor yang berhak membuat nomor; cek route efektif dan relasi user hanya setelah keputusan. |

Tidak ada `AUTHZ-013` pada konsolidasi ini. Tahap 3B menyatakan tidak menambahkan ID baru karena beberapa observasi tambahan lebih tepat diletakkan sebagai status cakupan/route dispatch. Temuan yang beririsan—misalnya `2D-003`/`AUTHZ-004`, `2D-009`/`AUTHZ-003`, `2C-002`/`AUTHZ-005`, `EXK-T05`/`AUTHZ-007`, `2F-07`/`AUTHZ-006`, `2F-06`/`AUTHZ-009`—tetap memiliki ID masing-masing dan tidak digabungkan.

### 6.8 Akun pegawai, constraint, dan retensi penugasan

| ID | Ringkasan temuan dan bukti | Klasifikasi/status konsolidasi | Tindak lanjut |
|---|---|---|---|
| AKUN-001 | `User` memiliki satu atribut `role`; pembuatan akun TU menurunkan satu role dari teks `jabatan`. `User` menghubungkan `pegawai_id` ke `DataKepegawaian` serta `pegawai_id` ke `Guru.data_kepegawaian_id`; `DataKepegawaian` mendeklarasikan `hasOne` ke User dan Guru. Admin dan SuperAdmin memvalidasi/memperbarui satu nilai role. Bukti: `app/Models/User.php:14-22,86-100`; `app/Models/DataKepegawaian.php:79-92`; `app/Models/Guru.php:18-34`; `app/Http/Controllers/Tu/DataKepegawaianController.php:251-313`; `app/Http/Controllers/Admin/UserController.php:82-96,115-136`; `app/Http/Controllers/SuperAdmin/UserController.php:15-17,31-43`. | Fakta source. | Konfirmasi apakah role tunggal memang model akses yang dikehendaki; bukan kesimpulan kebutuhan multi-role. |
| AKUN-002 | Relasi ORM `hasOne` tidak menetapkan unique constraint. Migration relasi pegawai menetapkan FK, tetapi migration yang diperiksa tidak mendeklarasikan unique untuk `users.pegawai_id` atau `guru.data_kepegawaian_id`. Bukti: `database/migrations/2026_06_19_002923_add_data_kepegawaian_id_to_guru_table.php:13-17`; `database/migrations/2026_06_25_204529_refactor_users_and_guru_relationships.php:14-30`; `database/migrations/2026_08_21_190145_fix_foreign_keys_data_kepegawaian.php:14-33`. | Fakta source; constraint schema aktif belum diverifikasi karena database tidak diakses. | Cocokkan migration efektif dan constraint aktual pada database non-produksi sebelum menetapkan kardinalitas sebagai invariant. |
| AKUN-003 | `DataKepegawaianController::destroy()` menghapus penugasan wali kelas, pengampu mapel, relasi guru-mapel, pembina ekskul, akun, dan data pegawai dalam transaksi. Migration juga mendeklarasikan cascade pegawai → `users` dan `guru`. Bukti: `app/Http/Controllers/Tu/DataKepegawaianController.php:112-157`; `database/migrations/2026_08_21_190145_fix_foreign_keys_data_kepegawaian.php:14-33`; `app/Models/Guru.php:7-8`. | Fakta source / risiko kehilangan akun dan penugasan; efek database aktif belum diuji. | Tetapkan kebutuhan retensi/nonaktif vs hapus dan verifikasi perilaku pada schema uji. |
| AKAD-001 | Sejumlah tabel penugasan menyimpan `tahun_ajaran_id` dan memakai FK `cascadeOnDelete`, termasuk `pengajuan_jadwal`, `pengampu_mapel`, dan `pembina_ekskul`. Jika parent tahun ajaran dihapus secara fisik, deklarasi cascade dapat menghapus data terkait, sehingga tidak menjadi perlindungan riwayat. Bukti: `database/migrations/2026_05_07_092325_create_pengajuan_jadwal_table.php:35-37`; `database/migrations/2026_05_14_210710_create_pengampu_mapel_table.php:30-32`; `database/migrations/2026_05_29_015016_create_pembina_ekskul_table.php:23-25`. | Fakta migration / risiko retensi; hanya tabel yang disebut diperiksa. | Inventarisasi kebijakan penghapusan dan arsip untuk semua tabel historis sebelum tindakan operasional. |

## 7. Pertanyaan kebijakan sekolah yang belum diputuskan

Pertanyaan berikut bukan requirement yang telah disetujui. Tidak ada dasar yang cukup dalam laporan untuk menyebut kebijakan/regulasi tertentu sebagai fakta.

1. Apakah superadmin memang boleh melewati seluruh middleware `role`, atau hanya mendapat akses lintas area tertentu?
2. Apakah TU berwenang melihat, reset password, dan mengubah status seluruh role, termasuk admin/superadmin?
3. Apakah guru bidang/wali kelas boleh melihat detail akademik semua siswa, atau hanya siswa pada assignment aktif? Bagaimana hak koreksi absensi dan raport berbeda?
4. Apakah daftar pengajuan jadwal guru merupakan konsol lintas guru atau hanya daftar pengajuan sendiri?
5. Siapa yang dapat melihat, mengedit, dan menghapus pengajuan surat; apakah file draft/final privat; perlu/tidaknya file validasi/tanda tangan?
6. Apakah pembina hanya boleh menghapus jurnal/mengisi pembinaan untuk ekskul, anggota, dan tahun penugasannya?
7. Apakah siswa nonanggota boleh melihat detail/statistik ekskul? Apakah siswa dapat mendaftar, keluar, atau menunggu persetujuan?
8. Siapa yang boleh membuat nomor surat melalui endpoint pada group siswa?
9. Apakah berkas dokumen, surat, dan lampiran e-learning harus dapat diakses hanya setelah otorisasi halaman?
10. Kapan perubahan status nonaktif/blokir harus memutus sesi yang sedang berjalan?
11. Apakah role SPMB aktif; identifier login, alur autentikasi, dan redirect apa yang dimaksud?
12. Apakah ekspor/bulk/import operator harus mencakup seluruh tahun/kelas atau dibatasi assignment/tanggung jawab?
13. Aturan sekolah apa yang menentukan konflik ruang/jadwal, kuota JP, semester, kenaikan kelas, format nomor surat, dan siklus approval?
14. Apakah angka dashboard TU bermakna total akun atau akun aktif?
15. Apakah pengumpulan tugas siswa dan penilaian submission memang bagian dari kebutuhan e-learning saat ini?

Pertanyaan berlabel `REGULATORY` tidak ditetapkan dalam audit karena tidak ada sumber peraturan yang dikaji. Kategori seperti `UNSUPPORTED`, `SCHOOL POLICY`, `DESIGN DECISION`, dan `CONFLICT` menggambarkan status dasar requirement pada laporan, bukan keputusan final.

## 8. Hal yang memerlukan verifikasi runtime

Verifikasi runtime belum dilakukan. Sebelum memulai, Project Owner perlu menyetujui lingkungan non-produksi, data uji non-pribadi, langkah aman, dan cakupan observasi. Urutan area:

1. **Route dispatch dan target method:** method target tidak ditemukan, method kosong, resource yang tidak didukung controller, route ganda, variasi PUT/PATCH, serta link/view yang menunjuk nama route berbeda. Tahap 3B menginventaris 15 target action yang tidak ditemukan dan enam action perlu verifikasi runtime dalam matriks status.
2. **Auth dan session:** login admin/SPMB, ketersediaan API verifikasi email, role bypass, penerapan approval/status pada request, dan efek blokir terhadap sesi aktif.
3. **Scope ID dan assignment:** detail siswa, absensi edit/update, jadwal, surat, jurnal/pembinaan ekskul, membership siswa, dan relasi kelas/tahun/semester. Uji hanya dengan akun serta record fiktif yang disetujui.
4. **Storage/berkas:** akses URL `public/uploads`, public disk, symlink/web-server rule, file pengganti, validasi mime/ukuran, dan lampiran e-learning. Jangan menggunakan dokumen pribadi atau production.
5. **Status dan schema:** status `draft` terhadap enum migration, field `validated_file`, arsip dokumen, model soft-delete, status anggota, keunikan `pegawai_id`/`data_kepegawaian_id`, aksi FK pegawai, serta cascade `tahun_ajaran_id` dan perlindungan riwayat. Periksa schema hanya pada database uji; database aktif tidak dibaca.
6. **Workflow/response UI:** render view yang dirujuk, form method/route binding, form export TU, import kelas, PPDB direct GET/status, serta UI monitoring ekskul.
7. **Kondisi persaingan yang relevan:** uniqueness nomor surat dan urutan serial. Tidak ada percobaan concurrent atau operasi tulis dalam audit ini.

Jangan memakai `route:list`, request, test, build, atau query database produksi sebagai pengganti persetujuan dan rancangan verifikasi; semua itu di luar tindakan audit yang telah dilakukan.

## 9. Statistik cakupan dan bagian yang belum ditelaah

Angka berikut mempertahankan statistik terbaru dari Tahap 3B. Unit hitungnya adalah **route-action source entry**, bukan jumlah URL efektif yang diuji runtime.

| Status Tahap 3B | Jumlah | Persentase |
|---|---:|---:|
| Ditelaah — kontrol teridentifikasi | 57 | 14,4% |
| Ditelaah — potensi masalah | 27 | 6,8% |
| Ditelaah — perlu validasi sekolah | 12 | 3,0% |
| Perlu verifikasi runtime | 6 | 1,5% |
| Belum ditelaah | 279 | 70,5% |
| Target controller tidak ditemukan | 15 | 3,8% |
| **Total route-action entries** | **396** | **100,0%** |

- Cakupan telaah otorisasi statis menurut definisi Tahap 3B: **102/396 = 25,8%** (tiga kategori `DITELAAH` dan enam entry `PERLU VERIFIKASI RUNTIME`).
- Entry belum ditelaah: **279/396 = 70,5%**. Jumlah itu mencakup 261 entry yang dijumlahkan pada family matrix dan **18 entry yang belum berhasil dialokasikan ke kelompok URI tertentu**; 18 dicatat konservatif sebagai belum ditelaah.
- **15** action route menunjuk target method yang tidak ditemukan pada source controller relevan yang diperiksa; ini bukan hasil request dan bukan kesimpulan bahwa request pasti gagal.
- Tahap 3B mencatat total source dari `294` action HTTP langsung + `101` hasil normalisasi resource + `1` health endpoint = **396**. Resource update `PUT|PATCH` dihitung satu action. Route duplikat dihitung sebagai deklarasi terpisah; route collection aktif, package/runtime routes, dan variasi implicit tidak diuji.
- **Konflik/ketidakpastian statistik:** versi sebelum koreksi Tahap 3B menyebut `381` dan pasangan method `394`; teks yang tersimpan pada Tahap 3B kini menyatakan hitungan formula `396`, tetapi rincian family matrix hanya merinci 378 dan menyisakan 18 tanpa URI. Master mengikuti angka status total 396 yang disajikan pada tabel statistik Tahap 3B dan mengungkap selisih 18 secara eksplisit; tidak mengarang alokasi endpoint.
- Cakupan 3B tidak berarti seluruh 396 endpoint dipetakan per URI secara detail, dan persentase bukan hasil uji akses. Banyak controller/action masih belum ditelaah. Seluruh test, schema runtime, konfigurasi deployment, file private/public dan alur eksternal juga belum diverifikasi.

## 10. Riwayat perubahan baseline dan keputusan audit

| Tahap | Baseline/keputusan yang dicatat |
|---|---|
| Tahap 1 | Baseline historis `e082d0e...`; 223 entri lokal. Inventaris statis dan pembuatan laporan saja. Tidak menilai kebutuhan sekolah atau runtime. |
| Tahap 2 | Baseline laporan berpindah ke `b32f25f...`; memetakan auth/profil/gateway/PPDB dan mempertahankan temuan `WF-001`–`WF-007`. |
| Tahap 2B | Memperdalam TU/data akademik; mempertahankan `TU-AK-*` dan `TU-OBS-01`–`TU-OBS-08`. |
| Tahap 2C | Memetakan jadwal akademik; `2C-WF-01`–`08`; mencatat `2C-001`–`2C-007`. |
| Tahap 2D | Memetakan guru/siswa dan e-learning; `2D-WF-01`–`12`; mencatat `2D-001`–`2D-011`. |
| Tahap 2E | Memetakan ekstrakurikuler; `EXK-01`–`10` serta `EXK-T01`–`T09`. |
| Tahap 2F | Memetakan dokumen/surat; `DOK-01`–`03`, `SUR-01`–`04`, `2F-01`–`10`. Realtime notification dan Activity Log dikecualikan. |
| Tahap 3 | Memetakan authz lintas role dan `AUTHZ-001`–`011`; temuan tidak dinyatakan sebagai eksploitasi. |
| Tahap 3A | Verifikasi statis ulang `AUTHZ-001`–`011`, mempersempit `AUTHZ-008`, memisahkan runtime dan policy, menambahkan `AUTHZ-012`. |
| Tahap 3B | Memperluas peta route, mengindeks ulang `AUTHZ-001`–`012`; mencatat target hilang, route ganda, dan angka cakupan dengan 18 entry tidak teralokasi. Tidak mengklaim audit menyeluruh. |
| Master | Baseline aktual tetap `main`/`b32f25fc...`; konsolidasi laporan saja. Tidak mengubah klasifikasi, memutuskan policy sekolah, atau menyatakan temuan selesai. |
| Pembaruan 22 November 2026 | Pemeriksaan source terbatas atas akun pegawai, role tunggal, FK/unique relasi, penghapusan pegawai, dan beberapa FK penugasan bertahun ajaran; memperbarui §5, §6.8, dan §8. Tidak mengubah source/migration/database/konfigurasi serta tidak memeriksa database aktif. |
| Revisi backend SINERGI-MJ V1 | Menghubungkan penugasan aktif ke izin menu individual, membatasi Guru/TU/SPMB legacy melalui middleware route, memetakan akun TU lama menjadi Guru dengan snapshot rollback, dan menyinkronkan tugas operasional hanya dari data tahun ajaran aktif. Role `admin` dipertahankan sebagai role teknis karena masih dipakai route/policy; role SPMB tidak lagi menjadi jalur otorisasi. Validasi memakai database `sekolah_app_testing`; tidak menjalankan migrasi terhadap database produksi. |

Keputusan konsolidasi:

- ID temuan lintas laporan yang tampak berkaitan tetap terpisah; cross-reference dipakai untuk membantu penelusuran.
- Perilaku source dipisahkan dari akses efektif/runtime dan aturan sekolah.
- Klaim yang didukung hanya oleh pencarian terbatas ditulis sebagai “tidak ditemukan pada cakupan pencarian”, bukan “tidak ada”.
- Pada saat master audit ini disusun, tidak ada temuan yang ditandai selesai atau diperbaiki; perubahan V1 dicatat terpisah pada riwayat di atas.

## 11. Rekomendasi urutan validasi berikutnya

Rekomendasi ini adalah urutan **validasi audit dan keputusan**, bukan persetujuan implementasi:

1. **Konfirmasi policy/aktor terlebih dahulu:** role SPMB, bypass superadmin, kewenangan akun TU, batas akses guru dan pembina, membership ekskul, privasi file, hak nomor surat, serta ruang lingkup ekspor. Catat pemilik keputusan dan jawaban sekolah.
2. **Triage target route yang tidak ditemukan dan method kosong:** cocokkan deklarasi resource/custom route dengan controller dan kebutuhan UI. Prioritaskan route auth/profil, tahun ajaran/kenaikan kelas, data kepegawaian, ekskul, PPDB, nomor surat, dan e-learning. Jangan menyimpulkan kegagalan runtime sebelum lingkungan uji.
3. **Tinjau akses ID berpotensi berdampak pada data pribadi/akademik:** detail siswa, absensi, pengajuan surat, akun lintas role, catatan/jurnal ekskul, serta endpoint siswa. Telusuri semua lapisan kontrol dan relasi sebelum menerima/menolak temuan.
4. **Tetapkan aturan data akademik:** assignment wali/pengampu, tahun ajaran, semester, status jadwal, penempatan, konflik ruang, kuota, kenaikan kelas dan import. Cocokkan aturan sekolah dengan apa yang terimplementasi sebelum desain perubahan.
5. **Tinjau dokumen dan storage:** putuskan klasifikasi akses file; verifikasi konfigurasi storage/web server pada deployment non-produksi dan jalur upload/penggantian/download tanpa dokumen pribadi.
6. **Rekonsiliasi migration/source dengan schema uji:** status pengajuan surat, field arsip, soft delete, keanggotaan ekskul, kolom perkembangan semester, dan serial nomor surat.
7. **Susun rencana runtime terisolasi:** setelah persetujuan tertulis, siapkan akun/record sintetis dan skenario read-only terlebih dahulu; operasi perubahan hanya bila diperlukan, dapat dipulihkan, serta disetujui. Catat hasil aktual terpisah dari temuan statis.
8. **Tutup inventaris route secara kuantitatif:** alokasikan 18 entry yang belum teralokasi, cocokkan duplicate/resource normalization, dan perbarui statistik hanya dengan bukti yang dapat direproduksi. Jangan menyebut cakupan otorisasi menyeluruh sebelum semua endpoint relevan ditelaah.

---

Master audit ini merangkum laporan yang tersedia pada baseline yang disebutkan. Referensi ke source code dan nomor baris adalah bukti yang dicatat laporan tahap terkait; perubahan source di luar baseline atau perilaku runtime memerlukan audit/verifikasi baru. Realtime Notification dan Activity Log tetap di luar cakupan.

## 12. Validasi akhir Manajemen Penugasan SINERGI-MJ

Validasi ini membaca source/worktree aktif dan memakai database `sekolah_app_testing`. Semua test dijalankan dengan bootstrap sementara yang menandai skema test sudah termigrasi agar `RefreshDatabase` tidak menjalankan reset atau migration. Tidak ada migration, `migrate:fresh`, atau perubahan data produksi yang dijalankan. Migration penugasan sudah tercatat pada database testing; perubahan file migration saat ini tidak mengubah database yang telah menjalankan versi sebelumnya.

### Otorisasi dan cakupan route

- Skenario izin gabungan, pencabutan parsial, izin ganda, draft, akses URL langsung, dan isolasi antarguru lulus pada `ManajemenPenugasanTest` dan `FeatureAccessTest`.
- Dari 48 menu terdefinisi, 48 memiliki rujukan route. Seluruh 243 route bernama `guru.*`, `tu.*`, atau `spmb.*` memiliki middleware `menu.route`; 240 memiliki pemetaan menu dan 3 adalah pengecualian profil mandiri: `guru.profile`, `guru.profile.detail`, dan `guru.profile.detail.update`.
- Tidak ditemukan route namespace tersebut yang tidak terpetakan, menu key yang tidak dikenal, atau menu yang tidak dipakai. Sepuluh route `ppdb.*` pada `routes/ppdb-public.php` tidak memakai otorisasi penugasan karena merupakan informasi/pendaftaran/status publik; tautan status individual tetap memakai middleware `signed`. Route staf PPDB berada di grup `tu.ppdb.*` dan diperiksa bersama route TU.
- Pemeriksaan target controller menemukan 26 route yang menunjuk method yang tidak ada: TU (`tu.siswa.search`, `tu.guru.search`, `tu.guru.bulk`, `tu.guru.detail`, `tu.guru.create`, `tu.tahun.show`, `tu.tahun.transisi`, `tu.ruangan.show`, `tu.datakepegawaian.detail`, `tu.datakepegawaian.request-account`, `tu.ekskul.show`, `tu.penempatan-siswa.preview-naik-kelas`, `tu.angkatan.toggle`); Guru (`guru.ekskul.index`, `guru.pengajuan-jadwal.show`, `guru.pengajuan-jadwal.edit`, `guru.pengajuan-jadwal.update`, `guru.pengajuan-jadwal.destroy`, `guru.ekskul.jadwal.submit`); PPDB (`tu.ppdb.pengaturan.create`, `tu.ppdb.pengaturan.show`, `tu.ppdb.pengaturan.edit`, `tu.ppdb.pengaturan.update`, `tu.ppdb.pengaturan.destroy`, `tu.ppdb.excel`, `tu.ppdb.pdf`). Middleware menolak pengguna tanpa izin sebelum aksi; pengguna yang memang diberi izin tetap dapat menemui HTTP 500 pada target yang hilang. Ini adalah masalah fungsional legacy yang belum diperbaiki dalam validasi ini.

`User::hasFeature()` untuk Guru hanya memeriksa menu yang dikenal pada penugasan aktif dan relasi Guru yang valid; draft, revoked, override fitur lama, dan role TU tidak memberi akses Guru. Kepsek teknis `superadmin` tetap bypass penuh, sementara Murid memakai daftar fitur role yang terpisah. Role `admin` masih aktif pada route `/admin`, `UserPolicy`, middleware, notifikasi, dan controller; karena itu penghapusan aman belum dapat dilakukan tanpa pemetaan kebijakan serta akun. Kondisi ini masih menyimpang dari target tepat tiga role utama. Alias role `tu` masih tersisa sementara untuk kompatibilitas.

### Rekonsiliasi data legacy dan kesiapan migration

Sinkronisasi migration memakai data tahun ajaran aktif hanya jika dapat menentukan tepat satu tahun aktif. Hasilnya berupa draft tanpa menu; tidak ada Operator/Admin PPDB yang dibuat hanya dari role TU. Sinkronisasi berulang menghindari duplikasi exact item/detail dan mempertahankan pasangan mapel-kelas. Wali kelas yang memiliki lebih dari satu kelas aktif dilewati, dan sumber operasional tidak dihapus. Publikasi penugasan operasional kini juga mensyaratkan tepat satu tahun aktif dan mengirimkan ID tersebut ke sinkronisasi modul lama; parameter request `ta` tidak dapat mengganti tahun sumber. FK penugasan pegawai memakai `restrictOnDelete`; detail master memakai `nullOnDelete`; item/menu memakai cascade. `down()` menolak rollback ketika masih ada riwayat penugasan dan mengembalikan role TU dari snapshot hanya pada rollback yang aman.

Pada MySQL, pembuatan tabel tidak dapat dianggap atomik bersama transaksi sinkronisasi data. Jika sinkronisasi gagal setelah tabel dibuat, migrasi dapat belum tercatat sementara tabel sudah ada; preflight berikutnya akan menolak skema yang telah terbentuk dan memerlukan pemeriksaan/pemulihan manual. Uji terhadap skema dan data representatif harus dilakukan sebelum database utama.

Daftar rekonsiliasi sebelum migration pada database utama:

1. Akun role TU tanpa `pegawai_id`, tanpa data pegawai, atau tanpa relasi Guru valid: role dapat berubah menjadi Guru tetapi tidak otomatis memperoleh akses; identitas/relasi perlu diverifikasi manual.
2. Tidak ada atau lebih dari satu tahun ajaran aktif: seluruh sinkronisasi legacy dilewati; tentukan tahun sumber secara manual.
3. Guru dengan beberapa kelas wali aktif: baris dilewati dan perlu penetapan kelas yang benar.
4. Relasi wali kelas, pengampu mapel, atau pembina ekskul dengan Guru/master yang hilang atau tidak konsisten: tidak boleh ditebak; cocokkan setiap pasangan dan tahun ajaran dengan sumber operasional.
5. Duplikasi exact pada pengampu/pembina: sinkronisasi menghindari duplikasi penugasan draft, tetapi tetap perlu memastikan duplikasi sumber memang duplikasi administratif, bukan penugasan terpisah.
6. Database yang sudah menjalankan versi migration sebelumnya: bandingkan skema dan isi penugasan aktual. Karena migration tercatat, perbaikan source tidak akan dijalankan ulang; tentukan migration korektif forward-only setelah rekonsiliasi.

Database produksi tidak diperiksa, sehingga daftar di atas adalah kriteria rekonsiliasi, bukan inventaris akun/baris produksi. Kesiapan migrasi utama belum disetujui.

## 13. Perbaikan target route legacy

Bagian ini memperbarui temuan historis pada §12 setelah menelusuri deklarasi route, controller, view, dan pemanggil yang tersedia di worktree. Route yang masih didukung tidak dihapus untuk menghindari error 500; endpoint tanpa workflow/view/pemakai dan tanpa implementasi bisnis yang valid dikeluarkan secara eksplisit, bukan diganti dengan respons dummy.

| Route | Controller/method lama | Penyebab | Perbaikan | Status |
|---|---|---|---|---|
| `tu.siswa.search` | `Tu\SiswaController@ajaxSearch` | Method target belum tersedia; UI membutuhkan pencarian AJAX. | Method `ajaxSearch` ditambahkan dengan validasi dan respons JSON. | Diperbaiki |
| `tu.guru.search` | `Tu\GuruController@ajaxSearch` | Method target belum tersedia; UI membutuhkan pencarian AJAX. | Method `ajaxSearch` ditambahkan memakai data relasi Guru yang tersedia. | Diperbaiki |
| `tu.guru.bulk` | `Tu\GuruController@bulkAction` | Tidak ada method maupun pemanggil/workflow bulk Guru yang aktif. | Route dihentikan; tidak ada operasi sukses semu. | Dihentikan |
| `tu.guru.detail` | `Tu\GuruController@detail` | Alias detail tidak memiliki method. | Alias dipertahankan dan didelegasikan ke `show`. | Diperbaiki |
| `tu.guru.create` | `Tu\GuruController@create` | Tidak ada halaman/form pembuatan Guru pada workflow yang ditemukan. | Dikeluarkan dari resource; endpoint lain tetap tersedia. | Dihentikan |
| `tu.tahun.show` | `Tu\TahunAjaranController@show` | Tidak ada method/detail view yang mendukung resource `show`. | Dikeluarkan dari resource. | Dihentikan |
| `tu.tahun.transisi` | `Tu\TahunAjaranController@masukTransisi` | Workflow lama mengubah status ke `transisi`, yang tidak dipakai alur tahun ajaran aktif. | Route legacy dihapus; alur `tutup` dan `lanjutSemester` yang tersedia tetap dipertahankan. | Dihentikan |
| `tu.ruangan.show` | `Tu\RuanganController@show` | Tidak ada method atau halaman detail ruangan yang digunakan. | `show` dikecualikan dari resource. | Dihentikan |
| `tu.datakepegawaian.detail` | `Tu\DataKepegawaianController@detail` | Method alias detail tidak ada, tetapi halaman detail pegawai tersedia. | Diarahkan ke method `show`. | Diperbaiki |
| `tu.datakepegawaian.request-account` | `Tu\DataKepegawaianController@requestAccount` | Method lama tidak ada; pembuatan akun tersedia melalui `createAccount`. | Alias lama dipertahankan dan diarahkan ke `createAccount`. | Diperbaiki |
| `tu.ekskul.show` | `Tu\EkskulController@show` | Tidak ada method/detail view yang digunakan. | `show` dikecualikan dari resource. | Dihentikan |
| `tu.penempatan-siswa.preview-naik-kelas` | `Tu\PenempatanSiswaController@previewNaikKelas` | Preview lama bergantung pada skema/status penempatan yang sudah usang; tidak ditemukan pemakai. | Route legacy dihapus; alur naik kelas/mapping yang tersedia tetap dipertahankan. | Dihentikan |
| `tu.angkatan.toggle` | `Tu\AngkatanController@toggle` | Method aktif bernama `toggleAktif`. | Diperbaiki agar memanggil `toggleAktif`. | Diperbaiki |
| `guru.ekskul.index` | `Guru\EkskulController@index` | Dashboard ekskul ada, tetapi method `index` tidak ada. | Alias diarahkan ke method `dashboard` yang digunakan route dashboard. | Diperbaiki |
| `guru.pengajuan-jadwal.show` | `Guru\PengajuanJadwalController@show` | Workflow resource hanya menyediakan daftar, form buat, dan simpan. | Resource dibatasi ke `index`, `create`, dan `store`. | Dihentikan |
| `guru.pengajuan-jadwal.edit` | `Guru\PengajuanJadwalController@edit` | Tidak ada workflow edit atau view edit yang aktif. | Resource dibatasi ke aksi yang benar-benar tersedia. | Dihentikan |
| `guru.pengajuan-jadwal.update` | `Guru\PengajuanJadwalController@update` | Tidak ada workflow update yang aktif. | Resource dibatasi ke aksi yang benar-benar tersedia. | Dihentikan |
| `guru.pengajuan-jadwal.destroy` | `Guru\PengajuanJadwalController@destroy` | Tidak ada workflow hapus yang aktif. | Resource dibatasi ke aksi yang benar-benar tersedia. | Dihentikan |
| `guru.ekskul.jadwal.submit` | `Guru\Ekskul\JadwalEkskulController@submit` | Tidak ada method/pemanggil; pengajuan memakai workflow `store` dan perubahan memakai `update`. | Route submit legacy dihapus. | Dihentikan |
| `tu.ppdb.pengaturan.create` | `PPDB\PengaturanController@create` | Form pengaturan memakai `index` dan `store`, tanpa aksi resource `create`. | Resource tambahan dihapus; route `index`/`store` tetap eksplisit. | Dihentikan |
| `tu.ppdb.pengaturan.show` | `PPDB\PengaturanController@show` | Tidak ada method/detail view pengaturan. | Resource tambahan dihapus; route `index`/`store` tetap eksplisit. | Dihentikan |
| `tu.ppdb.pengaturan.edit` | `PPDB\PengaturanController@edit` | Tidak ada workflow edit pengaturan. | Resource tambahan dihapus; route `index`/`store` tetap eksplisit. | Dihentikan |
| `tu.ppdb.pengaturan.update` | `PPDB\PengaturanController@update` | Tidak ada workflow update pengaturan. | Resource tambahan dihapus; route `index`/`store` tetap eksplisit. | Dihentikan |
| `tu.ppdb.pengaturan.destroy` | `PPDB\PengaturanController@destroy` | Tidak ada workflow hapus pengaturan. | Resource tambahan dihapus; route `index`/`store` tetap eksplisit. | Dihentikan |
| `tu.ppdb.excel` | `PPDB\PendaftarController@excel` | Endpoint ekspor lama tidak memiliki method. | Ekspor Excel diimplementasikan memakai service laporan, exporter, dan filter tervalidasi. | Diperbaiki |
| `tu.ppdb.pdf` | `PPDB\PendaftarController@pdf` | Endpoint ekspor lama tidak memiliki method. | Ekspor PDF diimplementasikan memakai service laporan, template, dan filter tervalidasi. | Diperbaiki |

Sebanyak **9 route diperbaiki** dan **17 route dari temuan 26 dihentikan** setelah tidak ditemukan workflow yang didukung. Satu route tambahan, `tu.tahun.detail`, juga dihentikan karena view tujuan tidak tersedia dan tidak ditemukan pemanggil. Tidak ada dari route tersebut yang dikecualikan dari pemeriksaan otorisasi dengan cara menghapus middleware. Sesudah perubahan, audit runtime menemukan 225 route bernama `guru.*`, `tu.*`, atau `spmb.*`; seluruh 225 memiliki `menu.route`, dan tidak ada target controller/method yang hilang. Pemeriksaan target tidak membuktikan setiap alur bisnis dapat berhasil untuk seluruh kondisi data.

### Hasil pengujian setelah perbaikan route

- Test terarah Manajemen Penugasan dan ekspor PPDB: 34 test, 159 assertion, lulus.
- Feature Access: 6 test, 13 assertion, lulus.
- PPDB: 47 test, 269 assertion, lulus.
- Ekstrakurikuler: 14 test, 129 assertion, lulus.
- Kelas/Wali Kelas: 20 test, 111 assertion, lulus.
- Notifikasi: 15 test, 164 assertion, lulus.
- Akun/autentikasi: 24 test, 84 assertion, 1 gagal pada ekspektasi tautan reset password.
- Full suite: 239 test, 1.336 assertion, 234 lulus dan 5 gagal. Lima kegagalan adalah tiga `ProfileTest` (target method/HTTP method profil), ekspektasi tautan `/forgot-password`, dan `TahunAjaranSemesterTransitionTest`. Ini sama dengan lima isu yang telah tercatat sebelum perbaikan route; tidak ada controller/fitur di luar cakupan yang diubah untuk menutupi kegagalan tersebut.

Hasil full suite di atas berasal dari database testing `sekolah_app_testing` dengan bootstrap sementara untuk menghindari reset/migrasi. Tidak ada migration atau reset database yang dijalankan. Bootstrap sementara dihapus setelah pengujian.

## 14. Hasil pengujian baseline sebelum perbaikan route

Semua hasil di bawah memakai database test yang sudah tersedia tanpa menjalankan migration:

| Kelompok | Hasil |
|---|---:|
| Manajemen Penugasan | 23 test, 120 assertion lulus |
| Feature Access | 6 test, 13 assertion lulus |
| PPDB | 45 test, 256 assertion lulus |
| Ekstrakurikuler | 14 test, 129 assertion lulus |
| Kelas/Wali Kelas terkait | 20 test, 111 assertion lulus |
| Notifikasi | 15 test, 164 assertion lulus |
| Akun dan autentikasi | 24 test, 84 assertion, 1 gagal |
| Seluruh suite setelah perbaikan tahun ajaran | 233 test, 1.312 assertion, 228 lulus, 5 gagal, 0 error |

Lima kegagalan full suite: `PasswordResetTest` mengharapkan tautan `/forgot-password` yang tidak terdapat pada halaman login saat ini; tiga `ProfileTest` karena method `ProfileController::edit()` tidak ada dan dua request menerima 405; serta `TahunAjaranSemesterTransitionTest::test_cohort_mismatch_is_skipped_and_reported` yang gagal pada assertion. Kegagalan ini tidak diubah karena tidak terbukti disebabkan oleh Manajemen Penugasan. Tidak ditemukan suite mandiri Pengampu Mapel; pasangan legacy diuji melalui test sinkronisasi pada `ManajemenPenugasanTest`.

Kesimpulan pada baseline tersebut: **belum lulus untuk kesiapan produksi**. Temuan target route yang hilang diperbarui pada §13; role `admin` yang masih fungsional, test regresi yang gagal, serta rekonsiliasi data produksi dan status versi migration tetap membutuhkan tindakan lanjutan.

## 15. Audit dampak penghentian route

Audit ulang dilakukan pada source aktif, deklarasi route sebelum perubahan, seluruh Blade/JavaScript/controller/test/dokumentasi repository yang tersedia, serta route matcher Laravel yang aktif. Kolom status di bawah menilai dampak yang dapat dibuktikan dari repository. Integrasi eksternal tidak dapat diverifikasi dari source lokal; oleh karena itu kesimpulan keseluruhan tetap **PERLU PERHATIAN**, meskipun beberapa route aman terhadap pemakai internal yang ditemukan.

| Route | HTTP dan URI sebelumnya | Controller/method sebelumnya | Pemakai, penyebab, dan pengganti yang ditemukan | Ketergantungan / status |
|---|---|---|---|---|
| `tu.guru.bulk` | `POST /tu/guru/bulk` | `Tu\GuruController@bulkAction` | Route custom tidak memiliki method. Tidak ditemukan pemakai Blade/JS/controller/test; daftar Guru hanya menautkan detail (`resources/views/tu/guru/index.blade.php`). | Tidak ditemukan pemakai internal; operasi bulk tidak pernah tersedia. **AMAN (internal)** |
| `tu.guru.create` | `GET /tu/guru/create` | Resource `Tu\GuruController@create` | Method dan form create tidak ada; daftar Guru menautkan `tu.guru.show`, bukan create (`resources/views/tu/guru/index.blade.php`). URI ini sekarang cocok dengan wildcard `tu.guru.show`, bukan action create; bukan endpoint create yang berfungsi. | Tidak ditemukan pemakai internal; URL dapat jatuh ke show dan gagal lookup. **PERLU PERHATIAN** |
| `tu.tahun.show` | `GET /tu/tahun/{tahun}` | Resource `Tu\TahunAjaranController@show` | Method show dan halaman show tidak ada. Daftar tahun menampilkan data dan aksi tahun aktif, tanpa tautan detail (`resources/views/tu/tahun/index.blade.php`). | Tidak ditemukan pemakai internal; GET pada URI resource yang tersisa tidak menjalankan show. **AMAN (internal)** |
| `tu.tahun.transisi` | `POST /tu/tahun/transisi` | `Tu\TahunAjaranController@masukTransisi` | Method tidak ada dan form tidak mengirim ke route ini. Halaman tahun menggunakan `tu.tahun.lanjutSemester` dan `tu.tahun.tutup`; keduanya dicakup test transisi/rollover. | Workflow semester/tahun aktif dialihkan dan teruji; tidak ada pemanggil `transisi`. **AMAN (internal)** |
| `tu.ruangan.show` | `GET /tu/ruangan/{ruangan}` | Resource `Tu\RuanganController@show` | Method show tidak ada dan tidak ditemukan tautan detail. Selain itu, view `tu.ruangan.index/create/edit` yang dirujuk method controller yang masih ada juga tidak ditemukan. | Route show tidak memiliki pemakai internal; workflow Ruangan yang lebih luas sudah tidak dapat dibuktikan berfungsi sebelum penghentian. **PERLU PERHATIAN** |
| `tu.ekskul.show` | `GET /tu/ekskul/{ekskul}` | Resource `Tu\EkskulController@show` | Method show tidak ada. UI master ekskul memakai index/create/edit/update/destroy (`resources/views/tu/ekskul/index.blade.php`, `create.blade.php`, `edit.blade.php`) dan tidak menautkan detail. | Tidak ditemukan pemakai detail internal; CRUD yang digunakan UI tetap tersedia. **AMAN (internal)** |
| `tu.penempatan-siswa.preview-naik-kelas` | `GET /tu/penempatan-siswa/preview-naik-kelas` | `Tu\PenempatanSiswaController@previewNaikKelas` | Method dan view preview tidak ada; tidak ditemukan pemanggil. Route `naik-kelas` dan `naik-kelas.mapping` masih ada, tetapi view yang dirujuk controller untuk alur mapping juga tidak ditemukan. Penutupan/rollover tahun menggunakan workflow lain yang diuji. | Tidak terbukti ada pemakai preview; workflow mapping mandiri masih belum terpetakan ujung-ke-ujung. **PERLU PERHATIAN** |
| `guru.pengajuan-jadwal.show` | `GET /guru/pengajuan-jadwal/{pengajuan_jadwal}` | Resource `Guru\PengajuanJadwalController@show` | Controller hanya menyediakan index/create/store; view resource hanya memakai index/create/store (`resources/views/guru/pengajuan-jadwal/index.blade.php`, `create.blade.php`). Tidak ditemukan tautan show. | Tidak ditemukan pemakai internal; method show memang tidak tersedia. **AMAN (internal)** |
| `guru.pengajuan-jadwal.edit` | `GET /guru/pengajuan-jadwal/{pengajuan_jadwal}/edit` | Resource `Guru\PengajuanJadwalController@edit` | Tidak ada method/view edit atau tautan edit pada daftar pengajuan. | Tidak ditemukan pemakai internal; method edit tidak tersedia. **AMAN (internal)** |
| `guru.pengajuan-jadwal.update` | `PUT/PATCH /guru/pengajuan-jadwal/{pengajuan_jadwal}` | Resource `Guru\PengajuanJadwalController@update` | Tidak ada method update atau form update untuk resource tersebut. Form aktif menggunakan store; alur grid `guru.jadwal-saya.*` memiliki mekanisme tersendiri. | Tidak ditemukan pemakai internal; method update tidak tersedia. **AMAN (internal)** |
| `guru.pengajuan-jadwal.destroy` | `DELETE /guru/pengajuan-jadwal/{pengajuan_jadwal}` | Resource `Guru\PengajuanJadwalController@destroy` | Tidak ada method destroy atau tombol hapus pada view daftar pengajuan. | Tidak ditemukan pemakai internal; method destroy tidak tersedia. **AMAN (internal)** |
| `guru.ekskul.jadwal.submit` | `POST /guru/ekskul/jadwal/submit` | `Guru\Ekskul\JadwalEkskulController@submit` | Method submit tidak ada dan form tidak memakai route ini. Form membuat draft melalui `guru.ekskul.jadwal.store`; pengajuan/resubmit memakai `update` dari halaman edit. `EkskulJadwalWorkflowTest` memverifikasi alur store/update. | Workflow yang tampak memakai route aktif lain dan teruji; tidak ditemukan pemakai route submit. **AMAN (internal)** |
| `tu.ppdb.pengaturan.create` | `GET /tu/ppdb/pengaturan/create` | Resource `PPDB\PengaturanController@create` | Controller tidak memiliki create; halaman pengaturan memakai satu form index yang mengirim store (`resources/views/ppdb/pengaturan/index.blade.php`). | Tidak ditemukan pemakai internal; endpoint create tidak diimplementasikan. **AMAN (internal)** |
| `tu.ppdb.pengaturan.show` | `GET /tu/ppdb/pengaturan/{pengaturan}` | Resource `PPDB\PengaturanController@show` | Controller dan view tidak memiliki halaman detail pengaturan; navigasi hanya ke index. | Tidak ditemukan pemakai internal; endpoint show tidak diimplementasikan. **AMAN (internal)** |
| `tu.ppdb.pengaturan.edit` | `GET /tu/ppdb/pengaturan/{pengaturan}/edit` | Resource `PPDB\PengaturanController@edit` | Tidak ada method/view edit; nilai pengaturan dimuat kembali pada index berdasarkan `tahun_ajaran_id`. | Tidak ditemukan pemakai internal; pengaturan diubah melalui form index/store. **AMAN (internal)** |
| `tu.ppdb.pengaturan.update` | `PUT/PATCH /tu/ppdb/pengaturan/{pengaturan}` | Resource `PPDB\PengaturanController@update` | Tidak ada method/form update. `store()` memakai `updateOrCreate`, dan `PpdbQuotaEnforcementTest` menguji simpan/perbarui berdasarkan tahun ajaran. | Workflow simpan/perbarui dialihkan ke store dan teruji; tidak ditemukan pemakai update. **AMAN (internal)** |
| `tu.ppdb.pengaturan.destroy` | `DELETE /tu/ppdb/pengaturan/{pengaturan}` | Resource `PPDB\PengaturanController@destroy` | Tidak ada method atau tombol hapus pengaturan pada view. | Tidak ditemukan pemakai internal; tidak ada workflow hapus yang diimplementasikan. **AMAN (internal)** |
| `tu.tahun.detail` | `GET /tu/tahun/{id}/detail` | `Tu\TahunAjaranController@detail` | Method detail masih ada, tetapi mengembalikan view `tu.tahun.detail` yang tidak ditemukan. Tidak ditemukan route helper, hardcoded link, JS, test, atau tautan daftar tahun yang menuju URL ini. Dokumentasi workflow sebelumnya juga mencatat view detail tidak ada. | Tidak ada pemakai internal yang ditemukan; kemungkinan URL lama/bookmark atau caller di luar repository tidak dapat dipastikan. **PERLU PERHATIAN** |

### Cakupan dependency dan matcher

- Pencarian route-name mencakup `app`, `resources`, `tests`, `docs`, `config`, dan `routes`. Tidak ditemukan pemanggil route yang dihentikan pada Blade, JavaScript, controller, atau test. Rujukan yang cocok ditemukan pada laporan audit historis dan catatan workflow saja. Tidak ditemukan Manual Book terpisah pada repository.
- Tidak ada integrasi eksternal khusus yang mencantumkan route-route ini pada konfigurasi/dokumentasi. Ini tidak membuktikan tidak adanya pemanggil eksternal, bookmark, atau reverse proxy; hal tersebut memerlukan konfirmasi operator/deployment.
- Pemeriksaan route matcher menemukan seluruh **18 nama route sudah tidak terdaftar**. Beberapa URI statis ditangkap oleh wildcard resource untuk method lain: misalnya `GET /tu/guru/create` cocok ke `tu.guru.show`, sementara request ke URI update resource dengan verb GET menghasilkan method-not-allowed. Pemeriksaan ini memverifikasi target matcher, bukan request terautentikasi atau hasil database; tidak ditemukan dispatch ke action bisnis lama.
- Dari 225 route aktif bernama `guru.*`, `tu.*`, atau `spmb.*`, seluruh 225 memiliki `auth`, role middleware, dan `menu.route`; 222 dipetakan ke menu dan 3 adalah route profil yang dikecualikan. Tidak ada target controller/method yang hilang.
- `User::hasFeature()` untuk Guru hanya memutuskan izin dari menu penugasan aktif; role TU legacy selalu ditolak pada pemeriksaan feature. `RoleMiddleware` memang menerima nilai TU pada grup role Guru untuk kompatibilitas, tetapi `TuModeMiddleware` dan `menu.route` tetap menolak karena TU tidak memperoleh feature dari penugasan. `superadmin` adalah identitas teknis Kepsek dan bypass yang disengaja. Tidak ditemukan akses tambahan Guru melalui route pengganti.
- `UserRole::ADMIN` dan route `/admin` masih ada di luar workflow yang dihentikan. Route bernama `spmb.dashboard` saat ini memakai `auth`, `approved`, `role:guru`, dan `menu.route` (`routes/web.php`); catatan lama tentang `role:spmb` pada laporan otorisasi sebelumnya tidak sesuai dengan deklarasi source aktif. Model tiga jabatan belum konsisten secara global karena role admin masih ada.

### Dampak workflow bisnis

| Workflow | Dampak penghentian | Status |
|---|---|---|
| Pengelolaan siswa dan penempatan | Route resource siswa dan operasi penempatan yang dipakai tetap ada; preview legacy tidak memiliki view/pemakai. Rollover dan penempatan diuji melalui test siswa, rombel, dan tahun ajaran. | Tidak ditemukan dampak pemutusan pada alur yang diuji. |
| Pengelolaan Guru/kepegawaian | Daftar/detail Guru serta resource kepegawaian tetap ada. Route Guru create/bulk yang dihentikan tidak memiliki method/form aktif. Belum ada test khusus untuk alur create Guru. | Perlu konfirmasi proses pembuatan Guru yang dimaksud; tidak ada bukti route create menjadi workflow aktif. |
| Tahun ajaran/semester dan kenaikan kelas | Form index menggunakan `create`, `aktifkan`, `lanjutSemester`, dan `tutup`; route `transisi` tidak digunakan. `AnnualRolloverTest` lulus; satu test `TahunAjaranSemesterTransitionTest` gagal pada assertion cohort mismatch yang telah dikenal. | Workflow utama tersedia; detail dan mapping naik kelas mandiri belum terpetakan. |
| Angkatan | Route toggle tidak termasuk route yang dihentikan dan masih mengarah ke `toggleAktif`; sebelumnya diuji pada Manajemen Penugasan. | Tidak terdampak penghentian 18 route. |
| Ruangan | `tu.ruangan.show` yang dihapus tidak memiliki method/view/pemakai. Namun route CRUD ruangan lain masih merujuk view `tu.ruangan.*` yang tidak ada, dan tidak ada test ruangan khusus. | **BERMASALAH (pre-existing):** fungsi manajemen Ruangan tidak terbukti dapat digunakan. Ini bukan regresi yang disebabkan penghentian `show`; perlu pekerjaan terpisah. |
| Ekstrakurikuler | Master TU tetap memiliki index/create/edit/store/update/destroy. Jadwal Guru tetap memakai store/edit/update/destroy; test jadwal dan absensi dijalankan. | Tidak ditemukan pemutusan workflow oleh route show/submit yang dihentikan. |
| Pengajuan jadwal Guru | Resource legacy tetap memiliki index/create/store; operasi show/edit/update/delete tidak diimplementasikan dan tidak digunakan oleh view. Alur `Jadwal Saya` adalah workflow terpisah. Namun `PengajuanJadwalController::index()` mengambil semua record tanpa scope guru, sedangkan `store()` membaca `auth()->user()->guru_id`, yang tidak didefinisikan pada model `User` yang diperiksa. Dokumentasi workflow sebelumnya juga mencatat relasi view/model `jam` yang belum cocok. | Penghentian empat aksi tidak memutus pemakai yang ditemukan, tetapi endpoint index/create/store tetap parsial dan cakupan data/policy belum dipastikan. **PERLU PERHATIAN**; tidak diubah tanpa keputusan apakah daftar memang dimaksudkan bersama. |
| Pengaturan dan proses PPDB/SPMB | Form pengaturan memakai index/store; proses pendaftar tetap aktif. Export Excel/PDF legacy bukan route yang dihentikan dan mempunyai test download/filter. Smoke test browser menunjukkan halaman publik `/ppdb` merespons 200 dan menyatakan PPDB ditutup. | Tidak ditemukan dampak penghentian; halaman staf autentik tidak diuji di browser karena tidak ada akun uji browser yang diberikan. |

### Pengujian dan kesimpulan audit

Test terarah dijalankan dengan database `sekolah_app_testing` dan bootstrap sementara yang mencegah `RefreshDatabase` menjalankan migration/reset. Hasil: **147 test, 831 assertion, 146 lulus, 1 gagal**. Kegagalan adalah `TahunAjaranSemesterTransitionTest::test_cohort_mismatch_is_skipped_and_reported`, sama dengan kegagalan yang telah diketahui; suite yang mencakup siswa, rombel, annual rollover, ekskul, PPDB, export, akses penugasan, dan feature access lainnya lulus. Tidak ada migration atau operasi reset yang dijalankan; bootstrap sementara dihapus setelah selesai.

**Kesimpulan: PERLU PERHATIAN.** Tidak ditemukan workflow internal yang bergantung pada action-action yang dihentikan dan masih aktif tanpa pengganti. Namun ketergantungan eksternal tidak bisa dibuktikan dari repository, workflow Ruangan memang bermasalah secara terpisah, mapping kenaikan kelas mandiri belum terpetakan, dan test semester mempunyai satu kegagalan lama. Penghentian route dapat diterima untuk pemakai internal yang teridentifikasi setelah pemilik sistem mengonfirmasi tidak ada integrasi/bookmark eksternal dan menyetujui tindak lanjut Ruangan serta test semester.

## 16. Pengajuan Jadwal Guru — pembatasan kepemilikan dan validasi alur

Bagian ini memperbarui temuan Pengajuan Jadwal Guru pada §15 berdasarkan implementasi dan test fitur terbaru.

### Identitas dan perubahan

- Relasi terverifikasi adalah `User.pegawai_id` → `DataKepegawaian.id` → `Guru.data_kepegawaian_id` → `PengajuanJadwal.guru_id`. Tidak ada kolom `users.guru_id` pada skema `sekolah_app_testing`; pemeriksaan skema dilakukan baca-saja.
- Daftar resource kini dibatasi pada `Auth::user()->guru`; opsi kelas/mapel hanya berasal dari assignment aktif pada tahun yang dipilih. `guru_id` dari request diabaikan.
- `PengajuanJadwal` resource dan grid `Jadwal Saya` memakai `JadwalDraftService` yang sama untuk assignment, slot terisi, konflik jadwal Guru lintas kelas, kuota JP mapel, batas JP kelas, dan status jadwal terpublikasi. Penyimpanan draft mengunci Guru dan kelas dalam transaksi; submission memvalidasi ulang seluruh draft/rejected sebelum mengubah status.
- Grid `Jadwal Saya` menolak kelas/mapel di luar assignment Guru. Slot milik Guru lain hanya ditampilkan sebagai “Slot terisi”; mapel/status dan kontrol hapusnya tidak ditampilkan. Penghapusan tetap mencari record berdasarkan `guru_id` login dan hanya mengizinkan status draft/rejected.
- Resource tetap hanya mendaftarkan index/create/store; route show/edit/update/destroy tidak dihidupkan kembali. View daftar memakai relasi `jamPelajaran` dan kolom `jam_ke`, `mulai`, serta `selesai`.
- Monitoring dan approval/publish tidak diubah. Route Kepsek tetap berada pada grup `auth` dan `role:superadmin`, sesuai identifier teknis sistem.

### Verifikasi

- Route Jadwal Guru tetap memiliki `auth`, `role:guru`, `guru.mode`, `menu.route`, dan `feature:guru.academic`. Test menolak Guru tanpa menu penugasan individual.
- `PengajuanJadwalWorkflowTest`: **8 test, 57 assertion lulus**. Cakupan termasuk Guru A/B, manipulasi `guru_id`, pembatasan kelas/mapel, validasi input, kepemilikan hapus, conflict/kuota mapel dan kelas, draft legacy yang tidak valid, serta monitoring–approval–publication Kepsek.
- Regresi notifikasi jadwal pada `BusinessNotificationTest`: **4 test, 40 assertion lulus**.
- PHP lint, kompilasi Blade in-memory untuk tiga view terkait, audit route/middleware, dan `git diff --check` lulus. Pengujian memakai skema `sekolah_app_testing` yang sudah ada melalui bootstrap sementara; tidak ada migration atau reset database.
- Smoke test browser terautentikasi tidak dijalankan karena tidak tersedia akun uji Guru/Kepsek pada sesi ini. Full suite tidak dijalankan pada tahap ini.

**Status: LULUS DENGAN CATATAN.** Kepemilikan data, validasi entry point, proteksi terhadap draft lama yang invalid, serta monitoring dan publication Kepsek terbukti melalui test fitur. Verifikasi browser dan full suite masih diperlukan sebelum menyatakan kesiapan rilis keseluruhan.

## 17. Full Regression Audit

### Metode dan hasil full suite

- Pemeriksaan dilakukan pada **2026-11-23 18:47 +07:00**. `.env.testing` menunjuk ke MySQL database `sekolah_app_testing`. Sebelum tes, port TCP 3306 tidak memiliki listener.
- Suite dijalankan dengan `C:\laragon\bin\php\php-8.2.29-Win32-vs16-x64\php.exe vendor\bin\phpunit --bootstrap tests\no-refresh-bootstrap.php`. Bootstrap sementara hanya menandai skema sebagai tersedia agar `RefreshDatabase` tidak menjalankan migration/reset; test tetap memerlukan koneksi database untuk membuka transaksi.
- Hasil: **247 test, 2 assertion, 2 lulus, 0 gagal assertion, 245 error, 0 skipped** (38:38.499). Seluruh 245 error melaporkan `SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it`, pada pembukaan koneksi/transaksi `RefreshDatabase`, sebelum isi test dijalankan. Karena itu, angka ini bukan hasil verifikasi regresi fungsional.
- Baseline terdokumentasi di §14 adalah **239 test, 1.336 assertion, 234 lulus, 5 gagal**. Suite kini bertambah 8 test; hasil saat ini tidak dapat dibandingkan untuk menyimpulkan apakah lima kegagalan baseline berubah karena semua test yang membutuhkan DB berhenti pada kegagalan koneksi.
- Tidak ada migration atau reset database yang dijalankan. Bootstrap sementara yang dibuat untuk run ini telah dihapus setelah pengujian.

## 18. Penutupan Temuan Keamanan Provisioning Akun Pegawai

Bagian ini mencatat implementasi dan validasi Tahap 5E. Laporan rinci: [`TAHAP_5E_PENUTUPAN_TEMUAN_KEAMANAN.md`](./TAHAP_5E_PENUTUPAN_TEMUAN_KEAMANAN.md).

### Perbaikan

- Password awal akun pegawai tidak lagi berasal dari tanggal lahir. Akun dibuat pending/nonaktif dan wajib menyelesaikan setup password melalui token reset satu kali sebelum dapat aktif.
- Reset password TU tidak lagi mengganti password menjadi tanggal lahir. Tautan berbasis token Laravel diterbitkan hanya pada respons Operator berwenang, bersifat `no-store`, dan target Superadmin ditolak.
- Operator pada setiap request sensitif harus memiliki role dasar Guru, status approved/active, serta assignment `operator` aktif dan published dengan menu yang sesuai. Status akun dimuat ulang dari database untuk memutus sesi yang statusnya telah dicabut.
- Approval dan seluruh endpoint toggle status tidak dapat mengaktifkan akun sebelum setup password. Endpoint Operator tidak dapat membuat akun Superadmin dari jabatan Kepala Sekolah.
- Redirect login memilih halaman yang sesuai dengan menu assignment; Guru tanpa assignment akademik menuju workflow data pegawainya sendiri.

### Validasi

- Migration aditif `2026_11_24_004200_add_must_change_password_to_users` diterapkan hanya ke MySQL QA `sekolah_app_pegawai_qa` (MySQL 8.0.30); default `false` mempertahankan status akun lama.
- Test terarah provisioning/reset/workflow/assignment: **84 test, 520 assertion lulus**; test redirect dan notifikasi tambahan: **16 test, 139 assertion lulus**.
- Full regression suite MySQL QA: **272 test, 1.564 assertion lulus, 0 failed/error/skipped**. PHPUnit menjalankan test dalam transaksi tanpa migration/reset.
- Browser QA merespons HTTP 200 pada halaman login lokal. Alur terautentikasi belum diulang pada Tahap 5E karena credential QA tidak digunakan/ditampilkan.
- Tidak ada perubahan ke `.env`, produksi, atau database selain penambahan kolom QA; tidak ada reset, rollback, penghapusan data, commit, atau push.

**Status: LULUS DENGAN CATATAN.** Provisioning akun pegawai dan kontrol sesi/assignment lulus test. Risiko yang belum diselesaikan: provisioning siswa PPDB masih memakai tanggal lahir sebagai password awal dan mengirimkannya melalui email; perlu keputusan terpisah agar perubahan kebijakan onboarding siswa tidak dilakukan sepihak. Penyerahan tautan aktivasi pegawai melalui kanal aman dan browser smoke test terautentikasi juga belum diverifikasi.

## 19. Verifikasi Akhir Tahap 5E

Bagian ini menutup temuan keamanan Tahap 5E dan mencatat verifikasi aktual.

### Temuan dan perbaikan

Security review menemukan satu temuan HIGH: sesi Superadmin yang diblokir masih dapat mencapai `unblock()` dan mengaktifkan akun. Perbaikan dilakukan pada middleware, route, dan controller:

- Middleware `EnsureUserApproved` memuat ulang akun dari database dan kini memeriksa approval, aktivasi, serta password setup untuk Superadmin.
- Grup route `superadmin` memakai middleware `approved`.
- `UserApprovalController::approve/reject/block/unblock` memuat ulang aktor, menolak aktor yang tidak aktif/belum disetujui/belum menyelesaikan setup, dan menolak pemrosesan akun sendiri.
- Route Guru utama dan unduhan bukti memakai pemeriksaan status approved pada sesi aktif.
- Test menambahkan pencabutan akses untuk bukti privat dan monitoring Kepsek.

### Verifikasi database dan test

- Target MySQL QA terverifikasi `sekolah_app_pegawai_qa`, MySQL 8.0.30, `127.0.0.1:3306`.
- Migration aktivasi `must_change_password` tercatat Ran pada QA.
- Pengujian terarah: **48 test, 275 assertion lulus**.
- Full regression: **273 test, 1.597 assertion lulus, 0 failed, 0 errors, 0 skipped**.
- PHP lint dan `git diff --check` lulus.
- Tidak ada migration/reset/destructive cleanup, `.env`/`.env.testing` tidak diubah.

### Browser dan risiko residual

- Server QA lokal `http://127.0.0.1:8015` kembali hidup.
- Browser berhasil login sebagai Guru fiktif dan membuka riwayat serta form perubahan data pegawai.
- Smoke browser lintas peran tidak dapat diselesaikan sepenuhnya karena keterbatasan pengendalian cookies/session browser automation.
- Redirect peran dan pencabutan sesi diverifikasi melalui PHPUnit, bukan browser lintas peran.
- Risiko residual: password awal siswa PPDB berbasis tanggal lahir, keterbatasan browser smoke, dan MariaDB belum diuji.

**Status: LULUS DENGAN CATATAN.** Temuan HIGH Tahap 5E tertutup dan terverifikasi; browser lintas peran serta onboarding siswa tetap memiliki catatan yang tidak disembunyikan.

## 20. Audit dan Penutupan Risiko PPDB/SPMB

Tahap 6 memeriksa dan memperbaiki jalur PPDB/SPMB yang tersisa pada [`TAHAP_6_AUDIT_KEAMANAN_PPDB.md`](./TAHAP_6_AUDIT_KEAMANAN_PPDB.md).

### Temuan dan perbaikan

- Dokumen identitas PPDB baru disimpan pada disk private `local`. Download dokumen wajib melalui route PPDB terotorisasi; view verifikasi tidak lagi memakai URL public storage.
- Bukti pendaftaran menggunakan temporary signed route dan tidak dapat dibuka melalui nomor pendaftaran unsigned.
- Lookup status publik ditambah throttle dan respons generik.
- Daftar ulang publik tidak lagi mengubah status dari nomor+DOB. Sistem menerbitkan token random SHA-256 30 menit yang dikirim melalui email dan hanya dapat dipakai sekali.
- Konfirmasi siswa tidak lagi membuat password dari tanggal lahir. Akun dibuat inactive dan wajib reset password melalui token.
- Grup route PPDB admin ditambah middleware `approved`, sehingga status akun aktif/disetujui diperiksa pada sesi.

### Validasi

- Migration aditif token daftar ulang diterapkan hanya pada QA `sekolah_app_pegawai_qa`.
- Pengujian PPDB terarah: **43 test, 274 assertion lulus**.
- Full regression: **276 test, 1.636 assertion lulus, 0 failed, 0 errors, 0 skipped**.
- Test membuktikan private download, unsigned-bukti rejection, status throttle, token expiry/reuse, otorisasi role, dan alur normal PPDB.

**Status: LULUS DENGAN CATATAN.** File lama yang sudah tersimpan pada public disk belum dipindahkan atau dihapus secara destruktif. Inventarisasi dan migrasi kompatibilitas file lama memerlukan persetujuan operasional; penggunaan/unggahan baru sudah private.

### Lima kegagalan baseline

Kelima test diperiksa secara terpisah, tetapi tidak dapat mencapai assertion karena koneksi MySQL ditolak:

| Area | Run terarah | Hasil run saat ini | Temuan source yang dapat diperiksa | Kesimpulan |
|---|---|---|---|---|
| `ProfileTest` (kelas berisi 5 test; baseline mencatat 3 kegagalan) | `--filter ProfileTest` | 5 error koneksi, 0 assertion | Route `GET /profile` menunjuk `ProfileController@edit`, sementara controller tidak menyediakan `edit`. Dua test update memakai `PATCH /profile`, sedangkan route aplikasi adalah `POST /profile/update`, sesuai mismatch method 405 yang tercatat pada baseline. | Mismatch route/test terindikasi dari source; run kali ini tidak dapat mengonfirmasi status runtime. Tidak diubah karena tidak berkaitan dengan jadwal. |
| Reset password link | `--filter test_reset_password_link_screen_can_be_rendered` | 1 error koneksi, 0 assertion | Baseline menyebut test mengharapkan tautan `/forgot-password`; halaman login memakai pemicu modal bantuan, bukan tautan tersebut. | Kontrak test dan UI berbeda; penyebab perilaku yang dikehendaki belum diverifikasi melalui runtime. Tidak diubah. |
| Transisi Tahun Ajaran/Semester | `--filter test_cohort_mismatch_is_skipped_and_reported` | 1 error koneksi, 0 assertion | Test mencari teks `"mismatch Angkatan: 1"` sedangkan controller membentuk laporan dengan istilah `"ketidaksesuaian Angkatan"`. Logika sumber melewati placement yang angkatannya tidak cocok. | Potensi mismatch assertion/teks; hasil transisi dan pesan aktual belum dapat diverifikasi. Tidak diubah. |

Run individual di atas mengalami exception database yang sama dengan full suite, sehingga tidak dapat digunakan untuk menyatakan lima kegagalan baseline telah terkonfirmasi atau terselesaikan.

### Dampak lintas modul dan pemeriksaan teknis

- Perubahan workflow jadwal yang tercatat di §16 tetap terbatas pada identitas/ownership Guru, validasi draft bersama, serta relasi jadwal; jalur monitoring dan approval Kepsek tidak diubah. Test terarah sebelumnya untuk `PengajuanJadwalWorkflowTest` (8 test/57 assertion) dan regresi notifikasi jadwal (4 test/40 assertion) tercatat lulus pada §16, tetapi tidak menggantikan full regression.
- Pemeriksaan source/teknis yang telah dilakukan sebelum run ini: PHP lint file terkait lulus; kompilasi Blade in-memory untuk view jadwal dan login lulus; route list/middleware menunjukkan proteksi Guru dan Kepsek sesuai implementasi; `git diff --check` lulus dengan peringatan konversi CRLF.
- Tidak ada smoke test browser dalam audit ini. Tidak dilakukan perubahan kode aplikasi untuk mengatasi kegagalan koneksi atau kegagalan baseline.
- Worktree sudah memiliki banyak perubahan staged, unstaged, dan untracked sebelum audit regresi. Untuk tahap ini hanya dokumen audit yang diperbarui; perubahan lainnya dipertahankan.

**Risiko tersisa:** kesiapan regresi belum dapat dinilai karena MySQL testing tidak berjalan pada host saat pemeriksaan. Status lima kegagalan historis juga belum dapat dibandingkan secara runtime. Temuan mismatch profil, tautan bantuan/reset, dan teks transisi perlu divalidasi terhadap kontrak produk setelah database testing tersedia.

**Status: BELUM DAPAT DINYATAKAN LULUS.** Rekomendasi berikutnya adalah menyalakan/menyediakan service MySQL untuk database testing yang telah disetujui (tanpa migration/reset), lalu mengulang full suite dan kelima test terarah; setelah itu tinjau hanya mismatch yang terbukti melalui assertion/runtime dan ulangi pemeriksaan regresi.

### Pemulihan database testing — 2026-11-23

- Laragon MySQL 8.0.30 aktif dari direktori instalasi Laragon. Tidak ditemukan service Windows MySQL/MariaDB terdaftar; proses `mysqld` berjalan dan mendengarkan pada port 3306 (port tambahan 33060 juga aktif). TCP `127.0.0.1:3306` berhasil; tidak ditemukan konflik listener pada port database yang dikonfigurasi.
- `.env.testing` dan konfigurasi runtime Laravel sama-sama memilih koneksi `mysql`, host `127.0.0.1`, port `3306`, dan database `sekolah_app_testing`. `phpunit.xml` menetapkan `APP_ENV=testing` tanpa override DB; config cache tidak ada. Validasi PDO hanya-baca berhasil dan `SELECT DATABASE()` mengembalikan database yang disetujui. Tidak ada kredensial yang dicatat.
- Penyebab penolakan koneksi sebelumnya adalah tidak adanya listener MySQL pada port 3306 saat audit pukul 18:47. Saat follow-up ini, proses dan koneksi telah tersedia.
- Sebelum test, perilaku `RefreshDatabase` diverifikasi: tanpa perlindungan, test pertama dapat memanggil `migrate:fresh`. Test terarah memakai bootstrap sementara yang menandai skema sudah dimuat, sehingga test tetap memakai transaksi rollback dan tidak menjalankan migrasi/reset. Tidak ditemukan operasi truncate/drop pada rangkaian test terarah.
- Hasil terarah dengan koneksi tersedia:

| Test | Hasil |
|---|---:|
| `ProfileTest` | 5 test, 15 assertion; 2 lulus, 3 gagal (route `edit` tidak tersedia dan dua request `PATCH` mendapat 405) |
| Test reset password link | 1 test, 5 assertion; gagal karena halaman login tidak menyertakan URL `/forgot-password` yang diharapkan |
| Test cohort mismatch | 1 test, 3 assertion; gagal pada teks pesan `"mismatch Angkatan: 1"` |
| `PengajuanJadwalWorkflowTest` | 8 test, 57 assertion; lulus |
| Regresi notifikasi jadwal (`BusinessNotificationTest`) | 4 test, 40 assertion; lulus |

  Total run terarah: **19 test, 120 assertion; 14 lulus dan 5 gagal**. Tiga kegagalan baseline kini terkonfirmasi sebagai ketidaksesuaian route/ekspektasi test yang telah dijelaskan di atas; tidak ada source aplikasi atau test yang diubah. Tidak dilakukan browser smoke test.
- Full suite belum diulang pada tahap pemulihan ini. **Lingkungan database siap untuk Full Regression Audit** dengan pemeriksaan target database dan bootstrap aman yang sama; jangan gunakan runner yang dapat menjalankan `migrate:fresh` pada skema/data yang tersedia.
- Helper bootstrap sementara telah dihapus setelah test. Perubahan tahap ini hanya memperbarui catatan audit; tidak ada migration, reset, penghapusan data, atau perubahan kredensial.

### Full suite final — 2026-11-23

- Sebelum run, koneksi diuji lagi melalui bootstrap Laravel dan `SELECT DATABASE()`; konfigurasi runtime adalah `mysql@127.0.0.1:3306/sekolah_app_testing`. Port TCP aktif dan config cache tidak ada.
- Command: `C:\laragon\bin\php\php-8.2.29-Win32-vs16-x64\php.exe vendor\bin\phpunit --bootstrap tests\no-refresh-bootstrap.php --log-junit <file-temporer>`.
- Bootstrap menetapkan `RefreshDatabaseState::$migrated = true`, sehingga `RefreshDatabase` tidak memanggil `migrate:fresh`; setiap test database tetap membuka transaksi dan me-rollback saat teardown. Pencarian suite tidak menemukan pemanggilan migrasi/reset/truncate eksplisit; seeder yang melakukan `truncate` tidak dipanggil. Tidak ada migration, reset, atau penghapusan data.
- Hasil PHPUnit: **247 test, 1.393 assertion, 242 passed, 5 failed, 0 errors, 0 skipped**, durasi **45,735 detik**. Semua 247 test dieksekusi. PHPUnit keluar dengan status 1 karena lima assertion failure.

#### Perbandingan dan konsistensi

| Pembanding | Test | Assertion | Passed | Failed | Errors / skipped |
|---|---:|---:|---:|---:|---:|
| Baseline sebelum perubahan terkait | 239 | 1.336 | 234 | 5 | 0 / 0 |
| Run terarah pada pemulihan database | 19 | 120 | 14 | 5 | 0 / 0 |
| Full suite final | 247 | 1.393 | 242 | 5 | 0 / 0 |

Kelima failure full suite sama dengan failure baseline dan run terarah; tidak ada failure baru atau test yang tidak tereksekusi. Jumlah 8 test dan 57 assertion tambahan lulus. Tidak ditemukan perbedaan antara hasil isolated dan full suite untuk lima failure tersebut.

#### Rincian seluruh failure

| Test | Penyebab teknis dan bukti source | Klasifikasi |
|---|---|---|
| `PasswordResetTest::test_reset_password_link_screen_can_be_rendered` — [test](../../tests/Feature/Auth/PasswordResetTest.php), [login view](../../resources/views/auth/login.blade.php) | Assertion test meminta URL `route('password.request')` pada halaman login. View hanya membuka modal bantuan melalui `javascript:void(0)` dan tidak merender URL reset; assertion pada test gagal. | Failure baseline; ketidaksesuaian kontrak test dan UI, bukan error koneksi. |
| `ProfileTest::test_profile_page_is_displayed` — [test](../../tests/Feature/ProfileTest.php), [routes](../../routes/web.php), [controller](../../app/Http/Controllers/ProfileController.php) | `GET /profile` diarahkan ke `ProfileController@edit`, sedangkan controller tidak memiliki method `edit` (method yang tersedia antara lain `index` dan `detail`); request berakhir HTTP 500. | Failure baseline; target route tidak cocok dengan controller. |
| `ProfileTest::test_profile_information_can_be_updated` — [test](../../tests/Feature/ProfileTest.php), [routes](../../routes/web.php) | Test mengirim `PATCH /profile`, tetapi route update yang tersedia adalah `POST /profile/update`; respons aktual HTTP 405. | Failure baseline; method/URI test berbeda dari route aktif. |
| `ProfileTest::test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged` — [test](../../tests/Feature/ProfileTest.php), [routes](../../routes/web.php) | Memakai `PATCH /profile` yang sama, sehingga mendapat HTTP 405 sebelum assertion email terverifikasi tercapai. | Failure baseline; mismatch yang sama dengan test update profil. |
| `TahunAjaranSemesterTransitionTest::test_cohort_mismatch_is_skipped_and_reported` — [test](../../tests/Feature/TahunAjaranSemesterTransitionTest.php), [controller](../../app/Http/Controllers/Tu/TahunAjaranController.php) | Test mengharapkan substring `mismatch Angkatan: 1`; `ringkasanTransisi()` menghasilkan frasa `dilewati karena ketidaksesuaian Angkatan: 1`. Assertion pesan gagal. | Failure baseline; mismatch teks assertion. Tidak ada bukti kegagalan koneksi atau error runtime lain. |

Tidak ada implementasi yang diubah pada tahap ini. Bukti menunjukkan kegagalan tetap pada test/route/UI yang sama seperti baseline; audit ini tidak mengubah atau melemahkan test untuk menutupi hasil.

**Status kesiapan:** full suite kini dapat dijalankan pada database testing dengan mekanisme aman, tetapi **sistem belum dapat dinyatakan lulus seluruh test** selama lima failure baseline tersebut masih ada. Penanganan berikutnya perlu menetapkan kontrak yang benar untuk profil, tautan reset di login, dan redaksi pesan transisi, lalu memperbarui source atau ekspektasi test pada tahap terpisah sesuai keputusan produk. Bootstrap dan file laporan JUnit temporer telah dibersihkan; tidak ada perubahan aplikasi/test, commit, atau push.

## 18. Penyelesaian lima baseline failure

### Analisis dan keputusan

1. **GET profil:** `/profile` adalah halaman profil akun umum yang dipakai oleh dropdown admin/superadmin, navigasi, dan tautan notifikasi. View `profile.edit` dan partial Breeze tersedia, sedangkan profil informasi Guru/Murid tetap memakai endpoint terpisah `/guru/profile` dan `/siswa/profile`. Route umum kini memanggil `ProfileController::edit`, yang merender view tersebut untuk akun login; tidak mengalihkan halaman profil Guru.
2. **Update profil:** form aktual memakai `route('profile.update')` dengan method override `PATCH`, mengirim `name` dan `email`; `ProfileUpdateRequest` memang memvalidasi dua field itu. Kontrak diselaraskan menjadi `PATCH /profile` → `ProfileController::update`. Handler mengisi hanya data tervalidasi, mengosongkan `email_verified_at` hanya bila email berubah, lalu kembali ke `profile.edit` dengan status yang dipakai view. Route name `profile.update` dipertahankan. Route `POST /profile/update` dan `updateInformasi` lama tidak memiliki pemanggil lain di repository; audit `app`, `resources/views`, `routes`, dan `tests` menemukan hanya form Breeze tersebut, yang melakukan PATCH dan tidak mengirim `telepon`/`alamat`. Endpoint lama diganti, tidak dipertahankan sebagai endpoint duplikat. Integrasi eksternal di luar repository tidak dapat dipastikan.
3. **Reset password:** `routes/auth.php`, `PasswordResetLinkController`, view permintaan reset, dan test membuktikan alur self-service aktif untuk akun non-Siswa. Siswa tetap diarahkan menghubungi TU/operator di controller permintaan maupun penyimpanan password. Link “Lupa kata sandi?” ditambahkan pada login; modal “Butuh Bantuan?” tetap tersedia. Kebijakan autentikasi dan pengecualian Siswa tidak diubah.
4. **Transisi semester:** implementasi membandingkan `siswa.angkatan_id` dengan cohort kelas, menghitung placement mismatch dan melewatinya, tanpa membuat placement Genap untuk mismatch. Ringkasan UI menggunakan istilah Indonesia `ketidaksesuaian Angkatan`; hanya ekspektasi teks test yang diselaraskan. Pemeriksaan cohort, status semester, dan data placement tidak diubah.

### File yang diubah

- [routes/web.php](../../routes/web.php)
- [ProfileController.php](../../app/Http/Controllers/ProfileController.php)
- [login.blade.php](../../resources/views/auth/login.blade.php)
- [TahunAjaranSemesterTransitionTest.php](../../tests/Feature/TahunAjaranSemesterTransitionTest.php)
- Dokumen audit ini.

### Hasil pengujian

Database dikonfirmasi kembali sebagai `sekolah_app_testing` di `127.0.0.1:3306`. Semua test dijalankan menggunakan bootstrap aman yang menandai skema sudah tersedia dan membuat transaksi rollback; tidak ada migration, `migrate:fresh`, reset, atau penghapusan data.

| Kelompok | Hasil |
|---|---:|
| Lima test yang sebelumnya gagal, dijalankan satu per satu | 5 test, 23 assertion; lulus |
| `ProfileTest` | 5 test, 21 assertion; lulus |
| Seluruh test `tests/Feature/Auth` | 23 test, 78 assertion; lulus |
| `TahunAjaranSemesterTransitionTest` | 6 test, 31 assertion; lulus |
| Full suite | **247 test, 1.403 assertion; 247 lulus, 0 gagal, 0 error, 0 skipped** (37,096 detik) |

#### Perbandingan dengan hasil sebelumnya

| Hasil | Test | Assertion | Passed | Failed |
|---|---:|---:|---:|---:|
| Baseline historis | 239 | 1.336 | 234 | 5 |
| Sebelum penyelesaian ini | 247 | 1.393 | 242 | 5 |
| Sesudah penyelesaian ini | 247 | 1.403 | 247 | 0 |

Kelima failure baseline terselesaikan; tidak ada failure baru. Assertion bertambah 10 dibanding full suite sebelumnya karena test yang dahulu berhenti pada kegagalan kini melanjutkan pemeriksaan. Delapan test tambahan yang sudah ada tetap lulus.

**Status:** seluruh suite lulus. Risiko yang tersisa adalah integrasi eksternal tak terdokumentasi yang mungkin masih mengirim request ke `POST /profile/update`; tidak ditemukan pemanggil internal, dan route name `profile.update` tetap kompatibel untuk frontend yang memakai nama route. Tidak dilakukan browser smoke test manual; render halaman profil dan login tercakup oleh Feature Test.

## 19. Workflow perubahan Data Pegawai — implementasi (23 November 2026)

### Relasi dan keputusan field

- Identitas akun mengikuti relasi aktual `users.pegawai_id → data_kepegawaian.id`; relasi Guru adalah `guru.data_kepegawaian_id → data_kepegawaian.id`. Pemilik pengajuan selalu diambil dari akun login, bukan dari parameter request.
- Field yang boleh diajukan Guru didefinisikan eksplisit di `config/employee_profile_changes.php`: identitas (nama, NIP, NUPTK), data pribadi/keluarga (tempat/tanggal lahir, agama, jenis kelamin, status kawin), pendidikan/pengalaman (ijazah terakhir, pengalaman mengajar), dan sertifikasi. NIP/NUPTK, tanggal lahir, status kawin, ijazah, dan sertifikasi mensyaratkan dokumen bukti.
- Karena login pegawai mencari akun melalui NIP, perubahan nama juga menyinkronkan `users.name`. Keunikan NIP divalidasi saat pengajuan dan persetujuan serta diperkuat unique index; migration memeriksa duplikasi lama dan berhenti dengan pesan eksplisit tanpa menghapus/mengubah data jika ditemukan.
- Alamat dan kontak tidak dimasukkan karena kolom tersebut tidak tersedia pada schema `data_kepegawaian` yang diperiksa. Jabatan, status kerja, SK/pangkat, masa kerja, gaji, tugas administratif, dan penugasan tetap dikelola melalui jalur Operator.

### Alur yang diterapkan

1. Guru yang akunnya aktif/disetujui dan mempunyai relasi Guru dapat mengajukan hanya perubahan untuk pegawainya sendiri. Snapshot nilai lama disimpan saat submit; perubahan identitas dari request body diabaikan.
2. Setiap field menjadi item terpisah dengan status, bukti privat, alasan, pemeriksa, dan keputusan berwaktu. Perubahan data resmi berjalan dalam transaksi setelah snapshot dinyatakan masih cocok. Snapshot basi ditolak dan dicatat agar Guru dapat mengajukan nilai terbaru tanpa menimpa data baru.
3. Verifikasi dibatasi pada akun Guru aktif dengan assignment aktif bertipe `operator` dan menu `operator.employees`; Operator tidak dapat memverifikasi pengajuannya sendiri. Field yang ditolak dapat diajukan ulang sebagai item baru dan tertaut ke item asal.
4. Status request dihitung dari status item, termasuk `partially_processed` bila hasilnya campuran. Notifikasi dikirim kepada Operator terkait dan Guru pemohon. Nama pemohon disimpan sebagai snapshot agar riwayat tetap terbaca bila akun dihapus; FK akun menjadi null dan FK pegawai mencegah penghapusan data yang masih memiliki riwayat. Kepsek menggunakan role teknis `superadmin` untuk monitoring lintas Guru yang bersifat read-only; tidak ada approval tahap kedua pada workflow ini.
5. Bukti disimpan pada disk privat `local`, tidak diletakkan pada disk publik, dan hanya diunduh melalui endpoint terautentikasi dengan pemeriksaan pemilik, Operator aktif, atau Kepsek.
6. Form profil Guru tidak lagi mengubah kolom resmi Data Kepegawaian secara langsung; email akun tetap dapat diperbarui terpisah. Form dan endpoint edit TU untuk pegawai yang sudah ada hanya menerima field administratif. Pembuatan data onboarding dan approval onboarding legacy tidak dihapus.

### File yang berubah untuk workflow

- Model, migration, konfigurasi, dan service: `DataKepegawaian.php`, `PegawaiChangeRequest.php`, `PegawaiChangeItem.php`, `PegawaiChangeDecision.php`, `2026_11_23_215900_enforce_unique_employee_nip.php`, `2026_11_23_220000_create_pegawai_change_workflow_tables.php`, `employee_profile_changes.php`, `PegawaiChangeWorkflow.php`.
- Endpoint dan routing: `ProfileController.php`, `DataKepegawaianController.php`, `PegawaiChangeController.php`, `PegawaiChangeVerificationController.php`, `PegawaiChangeMonitoringController.php`, `BusinessNotificationService.php`, `assignment_menus.php`, `guru.php`, `tu.php`, `superadmin.php`, `web.php`.
- UI: form profil, form edit administrasi TU, sidebar Guru/Operator/Kepsek, dan view daftar/buat/detail/antrean/monitoring pada `resources/views/{guru,tu,superadmin}/pegawai-changes`.
- Test: `tests/Feature/PegawaiChangeWorkflowTest.php`.

### Validasi dan batas

| Pemeriksaan | Hasil |
|---|---|
| Workflow perubahan Data Pegawai | **12 test, 59 assertion; lulus** |
| Feature access, manajemen penugasan, dan profil | **38 test, 165 assertion; lulus** |
| Gabungan test terarah | **50 test, 224 assertion; lulus** |
| PHP lint | Seluruh file PHP yang berubah lulus |
| Route list | Endpoint Guru, Operator, Kepsek, serta bukti privat terdaftar; role/assignment diperiksa di middleware/controller/service |
| Blade | `view:cache` berhasil; hasil cache dibersihkan |
| `git diff --check` | Lulus; Git hanya memberi peringatan normal konversi CRLF pada sebagian file |
| Database | Pengujian dijalankan pada SQLite `:memory:` terisolasi. `RefreshDatabase` hanya memigrasikan database sementara tersebut; database `sekolah_app_testing`/produksi tidak dikoneksikan atau diubah. Migration baru belum diterapkan ke database persisten. |
| Browser/manual smoke test | Tidak dilakukan |
| Full test suite | Tidak dijalankan pada tahap implementasi ini |

**Risiko tersisa:** migration harus diterapkan melalui prosedur deployment/QA yang disetujui sebelum workflow digunakan pada database persisten. Migration unique NIP akan menolak penerapan bila ada nilai NIP duplikat; lakukan rekonsiliasi resmi sebelum deployment, tanpa penghapusan otomatis. Perilaku khusus MySQL/MariaDB belum diverifikasi pada tahap ini. Full suite aplikasi dan browser smoke test masih perlu dijalankan pada lingkungan QA yang aman. Daftar allowlist field mengikuti kolom aktif yang ditemukan; penambahan field alamat/kontak memerlukan schema dan keputusan terpisah.

**Status workflow:** **LULUS DENGAN CATATAN** untuk pengujian terarah dan isolasi pemilik pada SQLite memori. Belum dinyatakan siap produksi sebelum migration, full regression suite, dan smoke test pada QA terkontrol tervalidasi.
