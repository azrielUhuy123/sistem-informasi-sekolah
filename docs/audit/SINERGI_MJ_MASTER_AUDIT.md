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
|---|---|---|---|---|---|---|
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

Keputusan konsolidasi:

- ID temuan lintas laporan yang tampak berkaitan tetap terpisah; cross-reference dipakai untuk membantu penelusuran.
- Perilaku source dipisahkan dari akses efektif/runtime dan aturan sekolah.
- Klaim yang didukung hanya oleh pencarian terbatas ditulis sebagai “tidak ditemukan pada cakupan pencarian”, bukan “tidak ada”.
- Tidak ada temuan yang ditandai selesai atau diperbaiki.

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
