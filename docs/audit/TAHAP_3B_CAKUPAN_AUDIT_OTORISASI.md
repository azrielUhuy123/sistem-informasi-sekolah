# SINERGI-MJ — Audit Tahap 3B: Penutupan Cakupan Otorisasi

## 1. Metadata dan baseline

| Metadata | Hasil |
|---|---|
| Waktu audit | 9 Oktober 2026, 15:00 WIB (UTC+07:00), sesuai waktu sesi saat audit dimulai |
| Repository | `azrielUhuy123/sistem-informasi-sekolah` |
| Branch aktif | `main` |
| HEAD | `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` (`SINERGI-MJ UPDATE`) |
| Status Git sebelum audit | `M docs/audit/TAHAP_1_INVENTARIS.md`; untracked laporan Tahap 2, 2B–2F, Tahap 3 dan Tahap 3A. Tidak tampak file aplikasi berubah. |
| File yang dibuat pada tahap ini | `docs/audit/TAHAP_3B_CAKUPAN_AUDIT_OTORISASI.md` |

Waktu di atas dibulatkan ke menit dari waktu sesi 15:00:04 WIB. Pemeriksaan baseline read-only menggunakan `git status --short`, `git branch --show-current`, dan `git rev-parse HEAD`. Laporan dan perubahan dokumentasi lokal yang sudah ada diperlakukan sebagai baseline; tidak diubah atau ditimpa.

Semua laporan terdahulu yang diminta dibaca: [Tahap 1](./TAHAP_1_INVENTARIS.md), [Tahap 2](./TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md), [Tahap 2B](./TAHAP_2B_WORKFLOW_TU_AKADEMIK.md), [Tahap 2C](./TAHAP_2C_WORKFLOW_PENJADWALAN.md), [Tahap 2D](./TAHAP_2D_WORKFLOW_GURU_SISWA.md), [Tahap 2E](./TAHAP_2E_WORKFLOW_EKSTRAKURIKULER.md), [Tahap 2F](./TAHAP_2F_WORKFLOW_DOKUMEN_SURAT.md), [Tahap 3](./TAHAP_3_AUDIT_OTORISASI_LINTAS_PERAN.md), dan [Tahap 3A](./TAHAP_3A_PENDALAMAN_OTORISASI.md). Klaim dari laporan lama digunakan sebagai indeks pencarian, bukan bukti bahwa source baseline ini identik.

Tidak membuka `.env`, credential, dokumen/data pribadi atau database. Tidak menjalankan route listing/runtime, request, test, build, migration, seeder, operasi cache, ataupun query database. Tidak membuat commit.

## 2. Metode inventarisasi route

### 2.1 Sumber registrasi

- [bootstrap/app.php](../../bootstrap/app.php:9) mendaftarkan `routes/web.php`, `routes/api.php`, `routes/console.php`, serta health URI `/up`.
- [web.php](../../routes/web.php:72) me-require delapan file route tambahan: `admin.php`, `guru.php`, `siswa.php`, `tu.php`, `superadmin.php`, `ppdb-public.php`, `ppdb-admin.php`, dan `auth.php`.
- Dengan demikian, terdapat 10 file route HTTP yang diregistrasikan langsung atau melalui `web.php` (termasuk API), ditambah `console.php` yang mendeklarasikan perintah Artisan, bukan endpoint HTTP. Health route `/up` dihitung sebagai satu endpoint konfigurasi.
- Pencarian mencakup seluruh 11 file PHP dalam `routes/`. Tidak ada route HTTP lain yang ditemukan dalam file tersebut; konfigurasi package/server di luar entry point yang diperiksa tidak disimpulkan.

### 2.2 Satuan hitung dan batas

Inventaris hitungan source menghasilkan **396 route-action entries**:

- 294 deklarasi action HTTP langsung dalam file route (duplikasi deklarasi tetap dihitung, karena audit menginventaris deklarasi source).
- 15 deklarasi `Route::resource`, yang dinormalisasi menjadi action resource standar: 13 resource penuh × 7 action; resource TU `walikelas` mengecualikan `show/edit/update` (4 action); resource TU `angkatan` mengecualikan `show` (6 action). Update `PUT|PATCH` dihitung sebagai satu route-action entry dengan dua HTTP method.
- 1 endpoint health `/up` yang dikonfigurasi di bootstrap.

Dengan aturan hitung tersebut, jumlahnya 396 route-action entries (294 + 101 + 1). Ini adalah hitungan source, bukan hasil `route:list` atau route collection runtime. Deklarasi duplikat tetap dihitung; variasi HEAD implisit, parameter konkret, dynamic route binding, kemungkinan route cache, serta efek urutan pencocokan tidak diuji. Matriks ringkasan status di bawah merinci 378 entries; selisih 18 belum dapat dialokasikan secara meyakinkan ke baris family tertentu dari pencatatan yang tersedia, sehingga seluruhnya dimasukkan secara konservatif sebagai `BELUM DITELAAH`. Dengan demikian laporan tidak mengklaim setiap entry telah terpetakan per URI.

Status endpoint diberikan hanya setelah route group/middleware dan source controller/action relevan yang tersedia dibaca. Mengetahui nama controller atau route saja tidak dianggap selesai. `DITELAAH — ...` dan `PERLU VERIFIKASI RUNTIME` menandai sumber route/controller telah ditinjau, tetapi bukan bukti akses berhasil. `TARGET CONTROLLER TIDAK DITEMUKAN` berarti pemetaan target tidak tersedia pada source yang diperiksa, bukan hasil runtime route dispatch.

### 2.3 Middleware dan konteks akses yang dicari

- [bootstrap/app.php](../../bootstrap/app.php:13) mendaftarkan alias kustom `role`, `forbid.superadmin`, `approved`, `tahun.aktif`, `guru.mode`, dan `tu.mode`; middleware group Laravel bawaan tetap bagian framework dan tidak dihitung sebagai custom global authorization.
- [RoleMiddleware.php](../../app/Http/Middleware/RoleMiddleware.php:10) memiliki bypass `superadmin` sebelum pencocokan role. [EnsureUserApproved.php](../../app/Http/Middleware/EnsureUserApproved.php:10) tidak dipasang pada seluruh route group. `guru.mode`/`tu.mode` mengelola mode sesi dan tidak dengan sendirinya menjadi scope kepemilikan.
- Policy/Gate yang ditemukan pada [UserPolicy.php](../../app/Policies/UserPolicy.php:7) dan [AppServiceProvider.php](../../app/Providers/AppServiceProvider.php:48) tidak dianggap melindungi route tanpa call-site pada jalur yang diperiksa.
- Route group memberi role deklaratif, tetapi tidak membuktikan pembatasan ID, kelas, penugasan, kepemilikan, tahun ajaran atau semester dalam controller.

## 3. Matriks cakupan seluruh endpoint

Tabel ini adalah ringkasan matriks endpoint berdasarkan file/family agar jumlah besar tetap dapat dibaca. Setiap URI eksplisit dicantumkan sebagai path/pola; resource dijabarkan sebagai action standar di bawah tabel. Untuk route tanpa nama, laporan mencatat “tanpa nama eksplisit”. Nama berprefix mengikuti `Route::name()` pada group dan suffix `->name()` pada deklarasi source.

**Singkatan status:** `KONTROL` = `DITELAAH — KONTROL TERIDENTIFIKASI`; `POTENSI` = `DITELAAH — POTENSI MASALAH`; `VALIDASI SEKOLAH` = `DITELAAH — PERLU VALIDASI SEKOLAH`; `RUNTIME` = `PERLU VERIFIKASI RUNTIME`; `BELUM` = `BELUM DITELAAH`; `TARGET HILANG` = `TARGET CONTROLLER TIDAK DITEMUKAN`.

| Berkas / baris | HTTP method + URI / nama atau family | Middleware efektif dari deklarasi lokal | Target / aktor dan modul | Status endpoint / jumlah |
|---|---|---|---|---|
| [auth.php](../../routes/auth.php:13) | `GET login` (`login`), `POST login` (tanpa nama eksplisit), `GET/POST forgot-password` (`password.request/email`), `GET reset-password/{token}` (`password.reset`), `POST reset-password` (`password.store`) | `guest` | Auth dan password recovery; guest | KONTROL — 6 |
| [auth.php](../../routes/auth.php:32) | `GET verify-email` (`verification.notice`); `GET verify-email/{id}/{hash}` (`verification.verify`); `POST email/verification-notification` (`verification.send`); `GET/POST confirm-password` (`password.confirm` untuk GET); `PUT password` (`password.update`); `POST logout` (`logout`) | `auth`; verify signed+throttle; resend throttle | Verifikasi, konfirmasi/password, logout; user login | KONTROL — 7 |
| [api.php](../../routes/api.php:7) | `GET /api/gateway/statistik` (tanpa nama eksplisit) | Tidak ada middleware route yang dinyatakan | API statistik gateway; publik; `Api\GatewayController::statistik` | KONTROL — 1; endpoint publik dibaca sebagai agregat hitungan |
| [api.php](../../routes/api.php:8) | `GET /api/user` (closure) | `auth:sanctum` | Identitas user terautentikasi Sanctum | KONTROL — 1; autentikasi middleware dideklarasikan; config runtime tidak diperiksa |
| [ppdb-public.php](../../routes/ppdb-public.php:6) | `GET /ppdb/` (`ppdb.informasi`), `GET /ppdb/alur` (`ppdb.alur`), `GET /ppdb/daftar` (`ppdb.create`), `POST /ppdb/daftar` (`ppdb.store`), `GET /ppdb/bukti/{no}` (`ppdb.bukti`) | Tidak ada auth route; group prefix/name `ppdb.` | Pendaftaran dan bukti PPDB publik; `PublicController` | VALIDASI SEKOLAH — 5; bukti pendaftaran dikaitkan nomor pada route, sedangkan sensitivitas/aturan paparan publik perlu keputusan bisnis |
| [ppdb-public.php](../../routes/ppdb-public.php:35) | `GET/POST /ppdb/status` (`ppdb.status/status.cek`), `GET/POST /ppdb/daftar-ulang` (`ppdb.daftar-ulang/simpan`), `GET /ppdb/status/{pendaftar}` (`ppdb.status.link`) | Publik tanpa auth; status link memakai `signed` | Cek status/daftar ulang/status bertanda tangan; `PublicController` | VALIDASI SEKOLAH — 5; auth publik mengandalkan pasangan identitas pada controller atau signed URL pada jalur link |
| [web.php](../../routes/web.php:8) | `GET /notifications`, `POST /notifications/read-all`, `POST /notifications/{notification}/read` (`notifications.*`) | `auth` | Inbox notifikasi | BELUM — 3; notifikasi realtime/Activity Log tetap dikecualikan; tiga action inbox dicatat di inventaris tetapi tidak dinilai pada tahap ini |
| [web.php](../../routes/web.php:22) | `GET /{any?}` (`gateway`) | Tidak ada middleware route eksplisit; kondisi `where` mengecualikan beberapa prefix | Gateway publik; `GatewayController::index` | KONTROL — 1; handler halaman publik dipetakan |
| [web.php](../../routes/web.php:34) | `GET /dashboard` (`dashboard`, closure) | `auth`, `verified` | Dashboard default | KONTROL — 1; middleware teridentifikasi; role/behavior runtime tidak diuji |
| [web.php](../../routes/web.php:47) | `GET /spmb/dashboard` (`spmb.dashboard`) | `auth`, `role:spmb` | Dashboard SPMB; `Spmb\DashboardController::index` | RUNTIME — 1; role tidak selaras dengan deklarasi role pusat/login menurut AUTHZ-011; konfigurasi/akun aktual tidak diverifikasi |
| [web.php](../../routes/web.php:56) | `GET /profile` (`profile.edit`), `DELETE /profile` (`profile.destroy`), `POST /profile/update` (`profile.update`) | `auth` | Profil generik; `ProfileController::edit/destroy/updateInformasi` | KONTROL — 2 (`destroy`, `updateInformasi`); TARGET HILANG — 1 (`edit` tidak ditemukan dalam `ProfileController` yang diperiksa) |
| [bootstrap/app.php](../../bootstrap/app.php:9) | `GET /up` (health route Laravel) | Health handler framework; tidak diturunkan middleware route lokal | Health endpoint aplikasi | KONTROL — 1 untuk deklarasi; handler framework/runtime tidak diuji |
| [admin.php](../../routes/admin.php:8) | `GET /admin/dashboard` (`admin.dashboard`); `GET /admin/users` (`admin.users.index`); `GET /admin/users/create` (`admin.users.create`); `POST /admin/users` (`admin.users.store`) | `auth`, `approved`, `role:admin` | Dashboard dan daftar/pembuatan akun; Admin controllers | KONTROL — 4; group gate dan sebagian workflow akun dibaca |
| [admin.php](../../routes/admin.php:22) | `GET /admin/users/{user}/edit`, `PUT|PATCH /admin/users/{user}`, `DELETE /admin/users/{user}` (`admin.users.edit/update/destroy`) | Group admin + `forbid.superadmin` pada edit/update/delete | CRUD akun bertarget; Admin | POTENSI — 3; pembatasan target tertentu ada, tetapi cakupan semua aksi dan kesetaraan hak tidak disimpulkan |
| [admin.php](../../routes/admin.php:38) | `PATCH /admin/users/{user}/toggle` (`admin.users.toggle`) | Group admin; tidak memakai `forbid.superadmin` pada route | Toggle akun; Admin controller | POTENSI — 1; method controller memiliki pemeriksaan target tertentu, namun perlu ditinjau konsistensi cakupan |
| [admin.php](../../routes/admin.php:41) | `GET /admin/tahun-ajaran`, `GET /admin/tahun-ajaran/create`, `POST /admin/tahun-ajaran`, `PATCH /admin/tahun-ajaran/{id}/activate` (`admin.tahun-ajaran.*`) | `auth`, `approved`, `role:admin` | Tahun ajaran; Admin | BELUM — 4; mapping route dikenal, operasi dan scope data belum ditelaah tuntas |
| [tu.php](../../routes/tu.php:38) | `GET /tu/dashboard`, `GET /tu/switch-mode/{mode}` (closure) | Group `auth`, `role:tu`, `tu.mode` | Dashboard/mode TU | BELUM — 2 |
| [tu.php](../../routes/tu.php:90) | `GET /tu/kelas`; `GET /tu/kelas/grup/create`, `POST /tu/kelas/grup`; `GET /tu/kelas/rombel/{kelas_grup}`, `/{id}/show`, `/{kelas_grup}/create`, `/{id}/edit`; `POST /tu/kelas/rombel/{kelas_grup}`; `PUT /tu/kelas/rombel/{id}`; `DELETE /tu/kelas/rombel/{id}`; `GET/POST /tu/kelas/{id}/wali` | Group TU + nested `approved`, `tahun.aktif` | Grup/rombel/wali kelas; TU / `KelasController` | BELUM — 12 |
| [tu.php](../../routes/tu.php:139) | `GET /tu/siswa/search/ajax`, `POST /tu/siswa/bulk`, `POST /tu/siswa/import`, `POST /tu/siswa/{id}/restore`, `DELETE /tu/siswa/{id}/force-delete`; resource `/tu/siswa` (index/create/store/show/edit/update/destroy) | Group TU | Siswa, bulk/import/restore/force delete; `SiswaController` | BELUM — 12 (5 custom + 7 resource actions); operasi belum direview per action pada tahap ini |
| [tu.php](../../routes/tu.php:150) | `GET /tu/ajax/kelas-grup`, `/kelas`, `/siswa-table` | Group TU | AJAX data siswa/kelas | BELUM — 3 |
| [tu.php](../../routes/tu.php:162) | `GET /tu/guru/search/ajax`, `POST /tu/guru/bulk`, `GET /tu/guru/{id}/detail`; resource `/tu/guru` (index/create/store/show/edit/update/destroy) | Group TU | Data guru/bulk; `GuruController` | TARGET HILANG — 1 (`create`); BELUM — 9 |
| [tu.php](../../routes/tu.php:172) | `GET /tu/search`, `/tu/search-ajax` | Group TU | Search global; `SearchController` | BELUM — 2 |
| [tu.php](../../routes/tu.php:181) | `GET /tu/jadwal-final`, `POST /tu/jadwal-final/export` | Group TU | Jadwal final/ekspor; `JadwalFinalController` | BELUM — 2 |
| [tu.php](../../routes/tu.php:194) | `GET /tu/mapel/search/ajax`, `POST /tu/mapel/bulk`, `POST /tu/mapel/{id}/restore`, `DELETE /tu/mapel/{id}/force-delete`, `GET /tu/mapel/{id}/detail`; resource `/tu/mapel` | Group TU | Mata pelajaran, bulk/restore/force delete; `MataPelajaranController` | BELUM — 12 |
| [tu.php](../../routes/tu.php:206) | `GET /tu/tahun/search/ajax`, `POST /tu/tahun/bulk`, `POST /tu/tahun/{id}/restore`, `DELETE /tu/tahun/{id}/force-delete`, `GET /tu/tahun/{id}/detail`; resource `/tu/tahun`; `POST /tu/tahun/transisi`, `/tutup`, `/{id}/aktifkan`, `/lanjut-semester` | Group TU | Tahun ajaran dan transisi; `TahunAjaranController` | TARGET HILANG — 1 (`masukTransisi`); BELUM — 15 |
| [tu.php](../../routes/tu.php:232) | Resource `/tu/ruangan`; resource `/tu/walikelas` kecuali `show/edit/update` | Group TU | Ruangan/wali kelas; resource controllers | BELUM — 11 |
| [tu.php](../../routes/tu.php:243) | `GET /tu/dokumen-sekolah`, `/dokumen-siswa`, `/dokumen/create/{jenis}`, `POST /tu/dokumen/store`, `DELETE /tu/dokumen/{id}`, `GET /tu/dokumen/{id}/edit`, `PUT /tu/dokumen/{id}` | Group TU | CRUD dokumen | BELUM — 7 |
| [tu.php](../../routes/tu.php:275) | Resource `/tu/datakepegawaian`; `GET /tu/datakepegawaian/export/excel`, `GET /{id}/detail`, `PATCH /{id}/read-message`, `POST /{pegawai}/request-account`, `POST /{pegawai}/create-account` | Group TU | CRUD/approval/export pegawai; `DataKepegawaianController`/export | TARGET HILANG — 1 (`requestAccount`); BELUM — 11 |
| [tu.php](../../routes/tu.php:298) | `GET /tu/pengajuan-surat`, `/create`, `/{id}`, `/{id}/edit`; `POST /store`; `PUT /{id}`; `DELETE /{id}` | Group TU | Pengajuan surat TU | BELUM — 7 |
| [tu.php](../../routes/tu.php:323) | `GET /tu/nomor-surat`, `POST /tu/nomor-surat/generate`, `GET /tu/kelas/{kelasGrup}/export` | Group TU | Nomor surat dan ekspor kelas | BELUM — 3 |
| [tu.php](../../routes/tu.php:340) | Resource `/tu/ekskul` (7 actions); penempatan: `GET /create`, `POST /`, `/naik-kelas`, `GET /preview-naik-kelas`, `POST /{penempatan}/keluarkan`, `GET /{penempatan}/edit`, `PUT /{penempatan}`, `GET /naik-kelas`, `/naik-kelas/mapping` | Group TU | Master ekskul/penempatan/kenaikan kelas | TARGET HILANG — 1 (`previewNaikKelas`); BELUM — 15 |
| [tu.php](../../routes/tu.php:385) | Resource `/tu/angkatan` kecuali `show`; `POST /tu/angkatan/{angkatan}/toggle` | Group TU | Angkatan | TARGET HILANG — 1 (`toggle` tidak ditemukan; method yang ditemukan bernama `toggleAktif`); BELUM — 6 |
| [tu.php](../../routes/tu.php:396) | `GET /tu/akun`, `GET /tu/akun/{user}`, `POST /tu/akun/{user}/reset-password`, `/toggle-status` | Group TU; superadmin juga lolos middleware `role:tu` menurut source | Akun lintas role target | POTENSI — 4; tanpa pemeriksaan target role yang tampak pada `AkunController` |
| [superadmin.php](../../routes/superadmin.php:27) | `GET /superadmin/dashboard`, `/users`, `/users/{user}/edit`, `PUT|PATCH /users/{user}`, `/users/pending`, `PATCH /users/{user}/approve`, `/reject`, `/users/approved`, `/users/rejected`, `POST /users/{user}/block`, `/unblock` | `auth`, `role:superadmin` | Dashboard/akun/approval; superadmin | POTENSI — 5 (approve/reject/block/unblock dan update target perlu scope); KONTROL — 2 (dashboard/list); BELUM — 4 (beberapa daftar/status) |
| [superadmin.php](../../routes/superadmin.php:68) | `GET /superadmin/monitoring-jadwal`, `/{kelas}`, `POST /{kelas}/approve`, `/reject`, `PATCH /{kelas}/publish` | Group superadmin | Review/publikasi jadwal | BELUM — 5 |
| [superadmin.php](../../routes/superadmin.php:92) | Resource `/superadmin/guru` (index/create/store/show/edit/update/destroy) | Group superadmin | Guru; `SuperAdmin\GuruController` | BELUM — 7 |
| [superadmin.php](../../routes/superadmin.php:100) | `GET /superadmin/wali-kelas`, `/create`; `POST /superadmin/wali-kelas`; `DELETE /superadmin/wali-kelas/{waliKelas}` | Group superadmin; target via `WaliKelasController` | Wali kelas | BELUM — 4 |
| [superadmin.php](../../routes/superadmin.php:116) | `GET /superadmin/mapel`, `/pengampu-mapel`; `POST /pengampu-mapel`; `DELETE /pengampu-mapel/{pengampuMapel}` | Group superadmin | Mapel/pengampu | BELUM — 4 |
| [superadmin.php](../../routes/superadmin.php:136) | `GET /superadmin/pembina-ekskul`; `POST /pembina-ekskul`; `DELETE /pembina-ekskul/{id}` | Group superadmin | Penetapan pembina ekskul | BELUM — 3 |
| [superadmin.php](../../routes/superadmin.php:151) | `GET /superadmin/jadwal-final`; `POST /jadwal-final/export` | Group superadmin | Jadwal final/ekspor | BELUM — 2 |
| [superadmin.php](../../routes/superadmin.php:160) | `GET /superadmin/datakepegawaian/approval`, `/{id}`; `PATCH /{id}/approve`, `/reject` | Group superadmin | Persetujuan pegawai | BELUM — 4 |
| [superadmin.php](../../routes/superadmin.php:180) | `GET /superadmin/dokumen/surat`, `/pengajuan-surat`, `/pengajuan-surat/{id}`; `PATCH /{id}/validasi`, `/tolak` | Group superadmin | Dokumen/validasi surat; `ValidasiSuratController` | BELUM — 5 |
| [superadmin.php](../../routes/superadmin.php:203) | Resource `/superadmin/nomor-surat` + explicit `POST /nomor-surat/generate` dan `GET /nomor-surat` | Group superadmin | Nomor surat | TARGET HILANG — 6 (resource `create/store/show/edit/update/destroy` yang tidak ada; `index` resource terduplikasi dengan GET eksplisit); BELUM — 3 (resource index, explicit GET index, POST generate) |
| [superadmin.php](../../routes/superadmin.php:217) | `GET /superadmin/monitoring-bentrok` (closure), `/monitoring-ekskul`, `/monitoring-ekskul/{ekskul}`, `GET` endpoint detail serta `POST /{ekskul}/approve`, `/reject`, `/publish`; `GET /jadwal-final-ekskul` | Group superadmin | Monitoring bentrok/ekskul dan jadwal final | BELUM — 7; monitoring bentrok closure ditelaah sebagai handler literal, bukan kontrol operasional |
| [superadmin.php](../../routes/superadmin.php:254) | `GET /superadmin/siswa`, `/siswa/{siswa}`; group `monitoring-kenaikan-kelas`: `GET /`, `/{kelas}`, `PATCH /{kelas}/reject`, `/approve` | Group superadmin | Data siswa dan keputusan kenaikan kelas | BELUM — 6 |
| [guru.php](../../routes/guru.php:33) | `GET /guru/dashboard-gurubidang`, `GET /guru/switch-mode/{mode}` (closure), `GET /guru/ekskul/dashboard`, `/ekskul` | `auth`, `role:guru`, `guru.mode` | Dashboard dan mode guru | BELUM — 4 |
| [guru.php](../../routes/guru.php:85) | `GET /guru/ekskul/anggota`, `GET /daftar-anggota`; `POST /anggota/store`; `DELETE /anggota/{id}`, `/daftar-anggota/{id}` | Group guru | Anggota ekskul; `EkskulAnggotaController` | BELUM — 5 |
| [guru.php](../../routes/guru.php:136) | `GET/POST /guru/ekskul/absensi`; `GET /guru/ekskul/rekap-absensi` | Group guru | Presensi ekskul | BELUM — 3 |
| [guru.php](../../routes/guru.php:153) | `GET/POST /guru/ekskul/jurnal`; `DELETE /guru/ekskul/jurnal/{id}` | Group guru | Jurnal; operasi delete berbasis ID tanpa scope yang terlihat pada method | POTENSI — 1 (delete); BELUM — 2 |
| [guru.php](../../routes/guru.php:168) | `GET /guru/dashboard-walikelas`, `/jadwal-saya`; `POST /jadwal-saya/pilih-slot`, `/submit`; `DELETE /jadwal-saya/{id}/hapus` | Group guru | Dashboard/jadwal guru; scope sebagian jalur grid telah dibaca | KONTROL — 4; BELUM — 1 (dashboard) |
| [guru.php](../../routes/guru.php:193) | Resource `/guru/pengajuan-jadwal` (index/create/store/show/edit/update/destroy) | Group guru | Pengajuan jadwal resource | POTENSI — 1 (index lintas guru); BELUM — 6 |
| [guru.php](../../routes/guru.php:198) | `GET /guru/kelas-saya`, `/raport/{siswa}`, `/raport/{siswa}/pdf`, `/absensi`, `/rekap-absensi`, `/rekap-absensi/export`, `/rekap-bulanan`, `/rekap-semester`; `POST /absensi`; `GET /absensi/{absensi}/edit`; `PUT|PATCH /absensi/{absensi}` | Group guru | Kelas, rapor, absensi dan ekspor; scope bervariasi antarmethod | POTENSI — 3 (detail kelas/absensi edit/update); KONTROL — 5 (scope khusus terlihat pada raport/rekap/store); BELUM — 3 |
| [guru.php](../../routes/guru.php:252) | Resource `/guru/e-learning` (7 actions); `POST /e-learning/upload-image`, `/e-learning/{elearning}/komentar`, `/e-learning-komentar/{komentar}/reply` | Group guru | Materi/lampiran/komentar/reply | RUNTIME — 1 untuk resource update (method kosong); BELUM — 9; tidak ada route download lampiran terpisah yang teridentifikasi |
| [guru.php](../../routes/guru.php:274) | `GET/POST /guru/nilai`; `GET/POST /guru/perkembangan-siswa`; `GET /guru/siswa/{siswa}`; `GET/POST /guru/kenaikan-kelas`; `PATCH /guru/kenaikan-kelas/submit`, `/cancel` | Group guru | Nilai/perkembangan/detail siswa/kenaikan kelas | POTENSI — 1 (detail siswa tanpa scope assignment yang terlihat); BELUM — 8 |
| [guru.php](../../routes/guru.php:314) | `GET /guru/status-validasi`, `/dokumen/surat`, resource `/guru/pengajuan-surat` (7 actions), `GET /guru/nomor-surat`, `POST /guru/nomor-surat/generate`, `GET /guru/profile` | Group guru | Status, dokumen, pengajuan surat, nomor surat, profil | POTENSI — 4 (surat show/edit/update/destroy); KONTROL — 4 (index/store filter/scope sendiri; GET status/dokumen/profile route level); BELUM — 2 |
| [guru.php](../../routes/guru.php:333) | `GET /guru/ekskul/catatan-pembinaan`, `/ekskul/jadwal`, `/ekskul/status-validasi`, `/ekskul/jadwal/{jadwal}/edit`; `POST /ekskul/catatan-pembinaan/store`, `/ekskul/jadwal/store`, `/ekskul/jadwal/submit`; `PUT|PATCH /ekskul/jadwal/{jadwal}`; `DELETE /ekskul/jadwal/{jadwal}`; `GET/PUT|PATCH /guru/profile/detail`; `PATCH /guru/kenaikan-kelas/submit`, `/cancel` | Group guru | Pembinaan/jadwal ekskul, profil, kenaikan kelas | POTENSI — 1 (catatan pembinaan tanpa validasi scope tampak); TARGET HILANG — 1 (`JadwalEkskulController::submit`); RUNTIME — 1 (duplikasi PATCH submit kenaikan); BELUM — 9 |
| [siswa.php](../../routes/siswa.php:16) | `GET /siswa/dashboard`, `/jadwal`, `/nilai`, `/absensi`, `/profile`, `/ekskul`, `/ekskul/{ekskul}`, `/ekskul/{ekskul}/absensi` | Group hanya `auth`; role `siswa` tidak dinyatakan pada group | Dashboard/data akademik/ekskul siswa | POTENSI — 2 (detail/absensi ekskul tidak tampak memeriksa membership); VALIDASI SEKOLAH — 1 (hak akses nonanggota); KONTROL — 5 (siswa/tahun/kelas dibatasi pada jalur yang dibaca) |
| [siswa.php](../../routes/siswa.php:61) | `GET /siswa/download-dokumen` dua deklarasi (closure + controller, nama sama); `GET /siswa/nomor-surat` dua deklarasi (closure + controller, nama sama); `POST /siswa/nomor-surat/generate` | Group `auth` | Dokumen/nomor surat; aktor siswa dimaksudkan, tapi hanya auth route-level | RUNTIME — 2 untuk deklarasi berulang (dua pasang duplikasi dihitung sebagai 2 action duplikat, bukan penetapan route efektif); POTENSI — 1 (`generate` tidak tampak memeriksa role/relasi siswa); VALIDASI SEKOLAH — 1 (`generate` hanya untuk siswa atau role lain juga?) |
| [siswa.php](../../routes/siswa.php:90) | `GET /siswa/e-learning`, `/e-learning/mapel/{mapel}`, `/e-learning/{elearning}`; `POST /siswa/e-learning/{elearning}/komentar`, `/siswa/e-learning-komentar/{komentar}/reply`; `GET /siswa/profile` (dideklarasikan dua kali), `/profile/detail`; `PUT|PATCH /siswa/profile/detail` | Group `auth` | Materi, diskusi dan profil | KONTROL — 3 (scope kelas siswa untuk materi/komentar pada jalur yang dibaca); RUNTIME — 1 untuk deklarasi GET profile berulang; BELUM — 5 |
| [ppdb-admin.php](../../routes/ppdb-admin.php:10) | `GET /tu/ppdb/dashboard`; resource `/tu/ppdb/pengaturan` (7 actions) ditambah explicit `GET/POST /pengaturan` (duplikat target index/store) | `auth`, `role:tu` | Dashboard/konfigurasi PPDB; TU | KONTROL — 8 (route group dan store/index konfigurasi ditelaah); BELUM — 1 |
| [ppdb-admin.php](../../routes/ppdb-admin.php:56) | `GET /tu/ppdb/pendaftar`, `/{pendaftar}`, `/excel`, `/pdf`; `POST /pendaftar/{pendaftar}/terima`, `/tolak`; `GET /pendaftar/{pendaftar}/verifikasi` dua deklarasi/nama, `/verifikasi`; `GET /diterima/export`, `/diterima`, `GET?` resend route tercatat `POST /diterima/{pendaftar}/resend-email`, `GET /ditolak`, `/verifikasi`, `/daftar-ulang`, `/penempatan-kelas`; `POST /penempatan-kelas/{pendaftar}`, `/daftar-ulang/{pendaftar}/konfirmasi` | `auth`, `role:tu`; `approved` tidak tampak pada group ini | Keputusan, daftar ulang, penempatan dan ekspor PPDB; TU | KONTROL — 2 (operasi keputusan/daftar ulang yang dibaca); TARGET HILANG — 2 (`excel`, `pdf`); BELUM — 13, termasuk operasi ID yang belum semua ditelaah |

### 3.1 Perluasan `Route::resource`

Setiap resource penuh di atas menghasilkan tujuh action route dengan nama konvensional:

| HTTP | URI | Nama konvensional | Method controller |
|---|---|---|---|
| GET | `/{resource}` | `{resource}.index` | `index` |
| GET | `/{resource}/create` | `{resource}.create` | `create` |
| POST | `/{resource}` | `{resource}.store` | `store` |
| GET | `/{resource}/{id}` | `{resource}.show` | `show` |
| GET | `/{resource}/{id}/edit` | `{resource}.edit` | `edit` |
| PUT\|PATCH | `/{resource}/{id}` | `{resource}.update` | `update` |
| DELETE | `/{resource}/{id}` | `{resource}.destroy` | `destroy` |

URI di atas harus diawali prefix dan name prefix dalam baris matriks terkait. Action yang dihilangkan oleh `except()` tidak dihitung. Untuk `pengajuan-surat` guru, parameter key mengikuti konvensi Laravel resource; route helper/matching aktual tidak dieksekusi.

### 3.2 Middleware group dan controller target yang tidak terpetakan

| Target / endpoint | Bukti route dan pemeriksaan source | Status |
|---|---|---|
| `GET /profile` → `ProfileController::edit` | [web.php](../../routes/web.php:57); `ProfileController` pada source yang dibaca menyediakan `index`, `detail`, `updateDetail`, `updateInformasi`, `destroy`, tetapi tidak ditemukan method `edit` | TARGET CONTROLLER TIDAK DITEMUKAN |
| `GET /tu/guru/create` → `Tu\GuruController::create` dari resource | [tu.php](../../routes/tu.php:165); method `create` tidak tampak pada controller, sedangkan `index/show/store/edit/update/destroy` ada | TARGET CONTROLLER TIDAK DITEMUKAN |
| `POST /tu/tahun/transisi` → `TahunAjaranController::masukTransisi` | [tu.php](../../routes/tu.php:212); method bernama itu tidak ditemukan pada controller; metode transisi lain ada | TARGET CONTROLLER TIDAK DITEMUKAN |
| `GET /tu/penempatan-siswa/preview-naik-kelas` → `PenempatanSiswaController::previewNaikKelas` | [tu.php](../../routes/tu.php:357); method target tidak ditemukan pada controller yang ditelaah | TARGET CONTROLLER TIDAK DITEMUKAN |
| `POST /tu/datakepegawaian/{pegawai}/request-account` → `DataKepegawaianController::requestAccount` | [tu.php](../../routes/tu.php:288); controller memiliki `createAccount`, tetapi method bernama `requestAccount` tidak ditemukan | TARGET CONTROLLER TIDAK DITEMUKAN |
| `POST /tu/angkatan/{angkatan}/toggle` → `AngkatanController::toggle` | [tu.php](../../routes/tu.php:388); controller yang ditelaah memiliki `toggleAktif`, bukan method `toggle` | TARGET CONTROLLER TIDAK DITEMUKAN |
| `POST /guru/ekskul/jadwal/submit` → `JadwalEkskulController::submit` | [guru.php](../../routes/guru.php:353); controller memiliki `index/store/edit/update/destroy`, tidak memiliki method `submit` | TARGET CONTROLLER TIDAK DITEMUKAN |
| `GET /tu/ppdb/excel`, `GET /tu/ppdb/pdf` → `PendaftarController::excel/pdf` | [ppdb-admin.php](../../routes/ppdb-admin.php:66); pencarian method pada `PendaftarController` tidak menemukan `excel` atau `pdf`. Class `LaporanController` di-import pada route file namun tidak menjadi target route yang terlihat. | TARGET CONTROLLER TIDAK DITEMUKAN |
| Resource `/superadmin/nomor-surat` → `create/store/show/edit/update/destroy` | [superadmin.php](../../routes/superadmin.php:203); controller yang ditelaah hanya menyediakan `index` dan `generate`. Resource menambahkan index yang sama dengan GET eksplisit; POST generate juga dideklarasikan terpisah. | TARGET CONTROLLER TIDAK DITEMUKAN — 6 action; index/generate memiliki target |

Di atas terdapat 15 action yang target method-nya tidak ditemukan (6 action resource nomor surat—`create/store/show/edit/update/destroy`—dan 9 action individual/resource lainnya). GET `index` resource nomor surat memiliki target method yang ada dan dihitung sebagai entry tersendiri, meskipun duplikat dengan GET eksplisit. Ini hasil pencarian nama method dalam source controller relevan; tidak ada `route:list`, request, atau pemanggilan runtime. Route yang menggunakan closure dipetakan sebagai closure, bukan target controller.

### 3.3 Duplikasi dan ketidakpastian route

FAKTA deklarasi yang ditemukan:

- [siswa.php](../../routes/siswa.php:61) mendeklarasikan GET `/siswa/download-dokumen` dua kali dengan nama yang sama, satu closure dan satu controller.
- [siswa.php](../../routes/siswa.php:73) mendeklarasikan GET `/siswa/nomor-surat` dua kali dengan nama yang sama, satu closure dan satu controller.
- [siswa.php](../../routes/siswa.php:41) dan baris 121 mendeklarasikan GET `/siswa/profile` dengan nama sama.
- [guru.php](../../routes/guru.php:309) dan baris 399 mendeklarasikan PATCH `/guru/kenaikan-kelas/submit` berulang dengan nama sama.
- [ppdb-admin.php](../../routes/ppdb-admin.php:84) mendaftarkan GET `/tu/ppdb/pendaftar/{pendaftar}/verifikasi` dua kali dengan target sama dan nama route berbeda.
- [ppdb-admin.php](../../routes/ppdb-admin.php:35) resource `pengaturan` kemudian ditambah explicit GET index dan POST store pada URI/controller yang sama.
- [superadmin.php](../../routes/superadmin.php:203) resource nomor surat menambah GET index, kemudian explicit GET index; aksi resource lain juga tidak seluruhnya didukung method controller.

Duplikasi dihitung sebagai deklarasi/action terpisah pada statistik, bukan disimpulkan sebagai dua akses efektif. Pemilihan route yang berlaku memerlukan route collection/runtime sehingga status terkait adalah `PERLU VERIFIKASI RUNTIME`.

## 4. Endpoint yang belum dapat dipetakan

### 4.1 Method/controller target

Daftar 15 action pada Bagian 3.2 belum dapat dipetakan ke implementasi method. Tidak ada kesimpulan bahwa request tertentu pasti gagal; route cache atau konfigurasi runtime tidak diuji. Method kosong yang berbeda dari method yang tidak ada:

- `Guru\ElearningController::edit`, `update`, dan `destroy` memiliki deklarasi method tetapi body yang dibaca kosong (`//`). Target controller ada; perilaku pengamanan/persistensi endpoint tersebut belum dapat diandalkan dari body method. Endpoint resource terkait ditandai `PERLU VERIFIKASI RUNTIME`/belum ditelaah dalam tabel.
- `SuperAdmin\NomorSuratController` memiliki `index/generate`, bukan CRUD resource lengkap.
- `LaporanController` hanya ditemukan sebagai import pada `ppdb-admin.php`, tidak tampak sebagai target route dalam file itu; keberadaan kelas tidak dijadikan dasar inventaris endpoint.

### 4.2 Komponen ada tetapi route aktif belum ditemukan

- `Tu\KeuanganPemasukanController` menyediakan `index/create/store`, validasi dan storage file, tetapi pencarian semua file `routes/` tidak menemukan route yang menargetkannya.
- `Tu\ArsipDokumenController` menyediakan CRUD, preview dan download, tetapi tidak ada route yang mengarah ke method controller tersebut pada 11 file route. Import controller pada `tu.php` sendiri bukan route.
- Tidak ada database, schema, view atau data runtime yang diperiksa untuk komponen di atas; keberadaan controller bukan bukti endpoint aktif.

### 4.3 Endpoint unduh/berkas dan integrasi

- Tidak ditemukan endpoint route khusus untuk mengunduh setiap lampiran e-learning; upload image/lampiran serta komentar/reply tersambung ke route guru/siswa yang dirinci pada matriks.
- Dokumen/surat yang ditautkan sebagai URL aset berada di luar pemeriksaan route download controller; akses aktual bergantung konfigurasi storage/web server.
- API memuat dua route yang terlihat. Pencarian pola HTTP client pada source tahap sebelumnya tidak membuktikan seluruh integrasi di JS, queue, package atau runtime telah diperiksa.

## 5. Hasil pemeriksaan area yang sebelumnya belum tuntas

| Area | File/source yang diperiksa pada Tahap 3B | Hasil dan cakupan yang belum selesai |
|---|---|---|
| Akun, profil, kepegawaian, approval/status | Route [admin.php](../../routes/admin.php:8), [tu.php](../../routes/tu.php:38), [superadmin.php](../../routes/superadmin.php:27), [web.php](../../routes/web.php:56); controller `Tu\AkunController`, `Admin\UserController`, `SuperAdmin\UserController`, `SuperAdmin\UserApprovalController`, `ProfileController`, `Tu\DataKepegawaianController`, `SuperAdmin\DataKepegawaianApprovalController`. | Confirmed per route family, tidak semua cabang/field tiap controller; AUTHZ-002 tetap didukung untuk target akun TU yang tidak tampak difilter. Profile update/destroy mengacu sesi user; route `/profile` edit tidak punya method `edit` yang ditemukan. Approval/status target binding diperiksa secara terbatas. |
| CRUD/bulk/import/export TU/Superadmin | Inventaris semua signature route TU/Superadmin; contoh method controller untuk siswa/mapel/tahun/pegawai/ekspor hanya diverifikasi pada method target yang sudah ditelusuri pada audit 2B/3A. | Matriks mencantumkan seluruh family dan menandai sisa `BELUM`. Belum semua operasi ID diperiksa sampai query-relasi/persistensi; seluruh ekspor/print/import tidak dianggap selesai hanya dari route. |
| PPDB keputusan/daftar ulang | [ppdb-admin.php](../../routes/ppdb-admin.php:10), `PPDB\PendaftarController` keputusan, export diterima, daftar ulang, konfirmasi, penempatan kelas; [ppdb-public.php](../../routes/ppdb-public.php:6), `PublicController`, [PengaturanController.php](../../app/Http/Controllers/PPDB/PengaturanController.php:10). | Keputusan terima/tolak mengunci pendaftar dan memeriksa status, persyaratan berkas/kuota; konfirmasi daftar ulang membuat akun siswa setelah syarat record; penempatan memeriksa tingkat/angkatan. Ekspor diterima memvalidasi tahun ajaran. `excel/pdf` method target tidak ditemukan. Group operator memakai `auth`/`role:tu`; superadmin bypass middleware role berlaku menurut kode. Pengujian runtime/database tidak dilakukan. |
| API/Sanctum | [api.php](../../routes/api.php:7), `Api\GatewayController`, closure `/user`, `bootstrap/app.php`; pencarian source integrasi API relevan sebelumnya. | Statistik publik hanya mengeluarkan count entitas; `/api/user` memakai `auth:sanctum`. `config/sanctum.php` tidak tersedia pada path yang dicoba; konfigurasi package/guard aktual tidak diperiksa. Tidak membuka token atau mencoba request. |
| E-learning lifecycle | Route guru resource/upload/komentar/reply, siswa baca/komentar/reply; `Guru\ElearningController`, `Siswa\ElearningController`, `ElearningKomentarController`, `ElearningKomentarReplyController`. | Baca/materi siswa serta komentar/reply siswa membatasi kelas dari penempatan sesi; create/show guru membandingkan assignment. Lampiran disimpan ke disk `public`. Handler komentar/reply yang dipakai group guru memakai relasi `user->siswa`, sedangkan group guru dimaksudkan untuk guru; perilaku request tidak dicoba. Resource edit/update/destroy guru kosong. File download lampiran dan policy akses storage belum ditemukan/diperiksa tuntas. |
| Keuangan dan arsip legacy | `Tu\KeuanganPemasukanController`, model `KeuanganPemasukan`, `Tu\ArsipDokumenController`, model `ArsipDokumen`, seluruh file `routes/`. | Controller keuangan dan arsip mempunyai operasi file/record tetapi tidak ditemukan route target. Perbedaan model/migration arsip yang disebut Tahap 2F masih memerlukan verifikasi schema (tidak mengakses DB). Bukan endpoint aktif yang dapat dinilai dari route map ini. |
| Endpoint ID akademik/jadwal/ekskul/surat/siswa | Route families seluruh role; controller jalur AUTHZ-001–012; pembacaan route group dan method target temuan. | Temuan ID yang telah ditelaah tetap dicatat pada indeks; endpoint resource lainnya sebagian besar belum dibaca per method. Tidak menyatakan setiap operasi berbasis ID aman/tidak aman hanya dari model binding/find. |
| Konfigurasi global/policy/gate | [bootstrap/app.php](../../bootstrap/app.php:9), route entry point, alias middleware, [RoleMiddleware.php](../../app/Http/Middleware/RoleMiddleware.php:10), middleware status/mode, `UserPolicy`, Gate provider dan call-site search. | Alias custom dan bypass role dipetakan; tidak ditemukan call-site Policy/Gate pada operasi yang ditelaah. Framework middleware default, deployment, route cache, guard, dan session tidak diuji runtime. |

### 5.1 Temuan perbedaan frontend-backend yang tetap relevan

- UI menu tidak menjadi kontrol route backend; route siswa menggunakan `auth` saja, sementara beberapa controller mengasumsikan relasi siswa.
- Pada jadwal resource guru, daftar tidak membatasi pengajuan menurut guru; jalur grid `Jadwal Saya` memiliki pemeriksaan assignment terpisah.
- Operasi edit/update/delete surat guru memiliki scope berbeda dari index yang memfilter user.
- Route `POST /guru/ekskul/jadwal/submit` tidak berpasangan dengan method bernama `submit`; method resource lain ada tetapi tidak menjamin endpoint submit.
- Link dokumen/attachment langsung ke aset tidak melewati controller download berotorisasi yang tampak.
- Resource route yang mengandung action tak terdefinisi serta deklarasi duplicate tidak dicoba melalui HTTP.

## 6. Indeks temuan AUTHZ-001 sampai AUTHZ-012 dan temuan baru

Temuan tetap dipertahankan dengan ID lama; tabel merangkum status setelah pembacaan Tahap 3B dan bukan laporan eksploitasi.

| ID | Status dalam indeks Tahap 3B | Ringkasan bukti/endpoint | Perubahan pemahaman / koreksi |
|---|---|---|---|
| AUTHZ-001 | `TERKONFIRMASI SECARA STATIS` | `RoleMiddleware` meloloskan `superadmin` pada route `role:<role>`. | Tetap didukung. Bypass role tidak serta-merta melewati kontrol per-resource controller. |
| AUTHZ-002 | `TERKONFIRMASI SECARA STATIS` | `GET /tu/akun`, detail, reset password, toggle status; controller TU menerima target tanpa filter role target yang tampak. | Tetap didukung; empat action masuk status `POTENSI`. Otorisasi bisnis lintas role perlu keputusan sekolah. |
| AUTHZ-003 | `TERKONFIRMASI SECARA STATIS` | `GET /guru/siswa/{siswa}`; method detail tidak tampak mencocokkan siswa dengan assignment guru. | Tetap didukung untuk method yang dibaca; tidak digeneralisasi ke route guru lain. |
| AUTHZ-004 | `TERKONFIRMASI SECARA STATIS` | GET edit dan PUT/PATCH update absensi dengan binding; scope jadwal guru store tidak otomatis mencakup update. | Tetap didukung; endpoint edit/update ditandai `POTENSI`. |
| AUTHZ-005 | `TERKONFIRMASI SECARA STATIS` | `GET /guru/pengajuan-jadwal` mengambil daftar tanpa filter guru yang tampak. | Tetap didukung; endpoint index ditandai `POTENSI`. |
| AUTHZ-006 | `TERKONFIRMASI SECARA STATIS` | CRUD resource pengajuan surat guru; index filter `user_id`, operasi ID tidak tampak menscope pemilik. | Tetap didukung; empat aksi show/edit/update/destroy ditandai `POTENSI`, bukan route index/store. |
| AUTHZ-007 | `TERKONFIRMASI SECARA STATIS` | Delete jurnal dan store catatan pembinaan tidak tampak memeriksa assignment/relasi siswa-ekskul pada method yang dibaca. | Tetap didukung; dua action berada dalam route group guru. |
| AUTHZ-008 | `PERLU VALIDASI SEKOLAH` | GET detail/absensi ekskul; membership tidak tampak diperiksa pada method; absensi per siswa tetap scope sesi. | Dipersempit: keputusan apakah detail/statistik boleh bagi nonanggota adalah policy sekolah; bukan klaim semua data absensi lintas siswa terbuka. |
| AUTHZ-009 | `PERLU VERIFIKASI RUNTIME` | File disimpan/ditautkan sebagai aset publik; proteksi aktual bergantung storage/web server/deployment. | Tetap tidak dapat dipastikan secara statis tanpa membaca konfigurasi aktif atau uji di lingkungan yang disetujui. |
| AUTHZ-010 | `PERLU VERIFIKASI RUNTIME` | Middleware `approved` tidak seragam; perubahan status akun tidak membuktikan pencabutan sesi aktif. | Tetap memerlukan runtime/session guard; status ini tidak menyatakan sesi terblokir atau tetap terbuka secara pasti. |
| AUTHZ-011 | `PERLU VERIFIKASI RUNTIME` | Role SPMB tidak ada dalam role pusat yang diperiksa; route meminta `role:spmb`; jalur login tidak selaras sepenuhnya. | Tetap perlu konfirmasi sekolah dan konfigurasi akun/runtime. |
| AUTHZ-012 | `PERLU VALIDASI SEKOLAH` | POST pembuatan nomor surat pada group siswa hanya `auth`; method memakai user sesi dan tidak tampak memeriksa relasi/role siswa. | Tetap sebagai temuan kode; aktor yang sah memerlukan keputusan sekolah. Route GET index duplikat menambah ketidakpastian route efektif. |

**Temuan otorisasi baru:** tidak ditambahkan ID AUTHZ baru pada tahap ini. Pencarian lanjutan menghasilkan ketidakpastian pemetaan route/action (route duplikat dan target method yang tidak ditemukan), tetapi bukti yang ada lebih tepat dicatat sebagai status cakupan/runtime, bukan klaim otorisasi baru. Tidak mengubah workflow frontend atau mendefinisikan role/permission.

## 7. Pertanyaan yang menunggu keputusan sekolah

1. Apakah superadmin memang dimaksudkan dapat melewati semua route `role:<role>`?
2. Apakah TU boleh membaca/mengubah akun semua role, mereset password, dan memblokir akun admin/superadmin?
3. Apakah guru bidang/wali kelas memiliki akses detail semua siswa, atau hanya siswa dalam assignment saat ini?
4. Siapa yang boleh melihat dan mengoreksi absensi, pengajuan jadwal, surat, catatan pembinaan dan jurnal di luar ownership/penugasan?
5. Apakah siswa nonanggota boleh melihat detail dan statistik ekskul? Siapa yang boleh membuat nomor surat melalui route siswa?
6. Apakah pendaftaran/status PPDB boleh tersedia tanpa login dengan nomor pendaftaran/tanggal lahir, serta informasi apa yang boleh muncul pada bukti/status?
7. Apakah file dokumen, surat dan lampiran e-learning bersifat privat? Kebijakan ini harus dibedakan dari akses halaman.
8. Kapan blokir/nonaktif seharusnya berlaku terhadap sesi yang sudah berjalan?
9. Apakah role SPMB aktif; bagaimana identitas, proses autentikasi dan cakupan aktornya?
10. Apakah export/bulk TU/Superadmin/PPDB harus mencakup data lintas seluruh tahun/kelas, atau ada batasan berdasarkan tugas operator?

Tidak ada aturan `REGULATORY` yang diklaim; tidak ada sumber hukum atau kebijakan sekolah yang menjadi evidence pada audit ini.

## 8. Statistik cakupan audit dan keterbatasannya

### 8.1 Status seluruh route-action entries

| Status | Jumlah | Persentase dari 396 |
|---|---:|---:|
| `DITELAAH — KONTROL TERIDENTIFIKASI` | 57 | 14,4% |
| `DITELAAH — POTENSI MASALAH` | 27 | 6,8% |
| `DITELAAH — PERLU VALIDASI SEKOLAH` | 12 | 3,0% |
| `PERLU VERIFIKASI RUNTIME` | 6 | 1,5% |
| `BELUM DITELAAH` | 279 | 70,5% |
| `TARGET CONTROLLER TIDAK DITEMUKAN` | 15 | 3,8% |
| **Total** | **396** | **100,0%** |

### 8.2 Definisi cakupan dan interpretasi

- **Cakupan telaah otorisasi statis:** 102/396 = **25,8%**. Numerator mencakup tiga status `DITELAAH — ...` (57+27+12) dan `PERLU VERIFIKASI RUNTIME` (6), karena route dan source relevan dibaca tetapi konfirmasi akhir memerlukan runtime.
- **Pemetaan target/action diperiksa:** 117/396 = **29,5%**. Ini menambah 15 action dengan target method tidak ditemukan; target diperiksa dan hasilnya didokumentasikan, tetapi tidak dihitung sebagai telaah otorisasi controller yang selesai.
- **Belum ditelaah:** 279/396 = **70,5%**. Angka ini mencakup 261 action yang tersebar dalam baris family matriks dan 18 action selisih hitungan yang belum dapat dialokasikan ke family tertentu. Seluruhnya diperlakukan konservatif sebagai belum ditelaah.
- Presentasi statistik menggunakan dua angka desimal untuk cakupan utama dan satu desimal pada tabel status. Angka dihitung dari action entries menurut aturan Bagian 2.2, bukan hitungan route runtime yang efektif.

### 8.3 Batas statistik

- Satu action route dihitung sebagai satu unit, meskipun controller memiliki beberapa pemeriksaan atau satu route menerima PUT dan PATCH.
- Duplicate declaration dihitung sebagai unit source terpisah. Route collection aktual dan penentuan declaration efektif tidak dihitung.
- Satu family resource dengan controller yang sama dapat berisi action dengan kondisi pemetaan berbeda; karena itu tabel menyebut pembagian status pada action yang telah diperiksa dan sisanya masuk `BELUM`.
- Endpoint static asset, URL file yang dibuat dengan `asset()`, job/command, serta route dari package/deployment yang tidak ditemukan dalam file route aplikasi tidak masuk jumlah 396.
- Route pihak ketiga atau route runtime yang didaftarkan melalui service provider tidak diinventarisasi lewat proses bootstrap; laporan membatasi klaim pada source route dan konfigurasi registrasi yang dibaca.
- Status `KONTROL` tidak berarti policy sekolah telah dikonfirmasi atau akses telah diuji; ia hanya menyatakan kontrol route/controller yang tampak berhasil diidentifikasi.

## 9. Ringkasan akhir

- Pada baseline `main` / `b32f25fc6e28b8e0262fa745e6cf454ea28e6414`, hitungan deklarasi dan resource menghasilkan **396 route-action entries**, termasuk resource terurai, deklarasi duplikat, dua endpoint API, route web/auth/PPDB dan health route `/up`. Baris family matriks baru merinci 378 entry; selisih 18 dihitung sebagai belum ditelaah dan belum dialokasikan ke URI tertentu.
- Hanya **102 endpoint-action (25,8%)** dinilai telah ditelaah secara statis sampai kontrol atau isu yang relevan teridentifikasi/ditentukan sebagai kebutuhan verifikasi. **279 endpoint-action (70,5%) masih belum ditelaah** (termasuk 18 yang belum teralokasi). Sebanyak 15 action memiliki target method yang tidak ditemukan, dan 6 action memerlukan verifikasi runtime.
- Temuan AUTHZ-001–012 dipertahankan. Status kebutuhan sekolah dan runtime tetap dibedakan dari fakta source; tidak ada temuan baru yang memadai untuk menambahkan AUTHZ-013.
- Cakupan seluruh controller, operasi CRUD/bulk/export, dan semua hubungan kepemilikan belum ditutup. Karena itu audit otorisasi belum dapat dinyatakan menyeluruh.
- Pemeriksaan Git akhir: branch `main`, HEAD `b32f25fc6e28b8e0262fa745e6cf454ea28e6414`; `docs/audit/TAHAP_1_INVENTARIS.md` tetap modified sebelumnya dan laporan Tahap 2B–2F, Tahap 2, Tahap 3, Tahap 3A, serta Tahap 3B tercatat untracked. Dibanding status awal, hanya file laporan Tahap 3B yang bertambah; tidak ada file aplikasi berubah. Laporan terdahulu tidak ditimpa.
- Tidak ada test, build, request, query database, migration, seeder, cache operation, atau commit yang dilakukan. Audit berhenti pada Tahap 3B.
