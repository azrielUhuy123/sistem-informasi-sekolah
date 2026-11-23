# SINERGI-MJ — Audit Tahap 3: Otorisasi Lintas Peran

## 1. Metadata dan baseline

| Metadata | Hasil |
|---|---|
| Waktu audit | 9 Oktober 2026, 14:38:37 WIB (UTC+07:00), mengikuti waktu sesi yang diberikan |
| Repository | `azrielUhuy123/sistem-informasi-sekolah` |
| Branch aktif | `main` |
| Commit aktif | `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` (`SINERGI-MJ UPDATE`), commit time 9 Oktober 2026, 12:30:29 WIB |
| Status Git sebelum audit | `M docs/audit/TAHAP_1_INVENTARIS.md`; untracked `docs/audit/TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md`, `TAHAP_2B_WORKFLOW_TU_AKADEMIK.md`, `TAHAP_2C_WORKFLOW_PENJADWALAN.md`, `TAHAP_2D_WORKFLOW_GURU_SISWA.md`, `TAHAP_2E_WORKFLOW_EKSTRAKURIKULER.md`, dan `TAHAP_2F_WORKFLOW_DOKUMEN_SURAT.md`. Tidak tampak file aplikasi berubah pada snapshot ini. |
| Status Git sesudah laporan | Diperiksa setelah laporan dibuat dan dicatat pada penutup. |

Baseline dicatat dengan `git status --short`, `git branch --show-current`, `git rev-parse HEAD`, dan `git log -1`. Perubahan dokumentasi yang sudah ada diperlakukan sebagai baseline lokal; tidak ada file lama yang ditimpa atau dibatalkan. Audit ini bersifat statis. Tidak ada file `.env`, credential, data siswa, database, atau berkas unggahan yang dibuka. Tidak ada request aplikasi, query database, migration, seeder, test, build, atau operasi cache yang dijalankan.

## 2. Referensi laporan sebelumnya

Ketujuh laporan yang diminta tersedia di lokasi berikut dan digunakan sebagai indeks serta konteks audit. Temuan di dalamnya diverifikasi ulang pada source yang saat ini ada; laporan terdahulu bukan pengganti bukti source:

- [Tahap 1 — Inventaris](./TAHAP_1_INVENTARIS.md)
- [Tahap 2 — Workflow Frontend–Backend](./TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md)
- [Tahap 2B — Workflow TU dan Akademik](./TAHAP_2B_WORKFLOW_TU_AKADEMIK.md)
- [Tahap 2C — Workflow Penjadwalan](./TAHAP_2C_WORKFLOW_PENJADWALAN.md)
- [Tahap 2D — Workflow Guru dan Siswa](./TAHAP_2D_WORKFLOW_GURU_SISWA.md)
- [Tahap 2E — Workflow Ekstrakurikuler](./TAHAP_2E_WORKFLOW_EKSTRAKURIKULER.md)
- [Tahap 2F — Workflow Dokumen dan Surat](./TAHAP_2F_WORKFLOW_DOKUMEN_SURAT.md)

## 3. Inventaris mekanisme autentikasi dan otorisasi

### 3.1 Identitas, role, dan autentikasi

| Mekanisme | Bukti source | Hasil pemetaan |
|---|---|---|
| Nilai role terpusat | `app/Enums/UserRole.php:5-11` | Konstanta bernilai `superadmin`, `admin`, `tu`, `guru`, dan `siswa` (`MURID` adalah nama konstanta untuk nilai `siswa`). Nilai `spmb` juga dipakai pada route, tetapi tidak ada pada enum ini. |
| Role pada akun | `app/Models/User.php:10-26,42-64` | Role dan status akun disimpan pada `User`; model menyediakan `hasRole`, `isRole`, dan pemetaan dashboard. Relasi `guru()` memakai `pegawai_id` sebagai foreign key pada `Guru.data_kepegawaian_id`; `pegawai()` dan `siswa()` menghubungkan identitas lain (`:68-102`). Konsistensi record aktual tidak diketahui. |
| Login NIP/NIS | `routes/auth.php:13-15`; `app/Http/Requests/Auth/LoginRequest.php:30-80,95-154` | POST login memakai `LoginRequest`; lookup akun yang terlihat dilakukan lewat NIP pada `DataKepegawaian` lalu NIS pada `Siswa`, diikuti pemeriksaan password/status dan `Auth::login`. Email tidak tampak sebagai credential lookup pada request ini. |
| Pemeriksaan approval/aktif saat login | `app/Http/Requests/Auth/LoginRequest.php:95-154`; `app/Http/Controllers/Auth/AuthenticatedSessionController.php:31-50` | Login menolak status yang belum disetujui/aktif; controller juga mengecek approval selain superadmin. Tidak ada status akun yang diuji melalui login pada audit ini. |
| Redirect sesudah login | `app/Http/Controllers/Auth/AuthenticatedSessionController.php:54-108` | Cabang eksplisit mengarahkan guru, TU, superadmin, dan siswa. Nilai role lainnya mencapai `default => abort(403)`. Login view/controller tidak diuji. |
| Middleware role | `bootstrap/app.php:13-20`; `app/Http/Middleware/RoleMiddleware.php:10-29` | Alias `role` digunakan di route group. Middleware membandingkan role dengan argumen, tetapi lebih dahulu meloloskan setiap user `superadmin` tanpa membandingkan role tujuan (`:18-21`). |
| Approval/aktif pada request | `app/Http/Middleware/EnsureUserApproved.php:10-37`; `routes/admin.php:8-9`; `routes/tu.php:38-45,90` | `approved` memeriksa approval dan status aktif, tetapi penerapannya tidak seragam: group admin menggunakannya; di route TU yang diperiksa ia hanya digunakan pada nested group kelas. Group route guru, siswa, dan superadmin tidak memakai middleware `approved`. Tidak ditemukan middleware global pemeriksa role/status pada konfigurasi alias `bootstrap/app.php`. |
| Mode Guru/TU | `app/Http/Middleware/GuruModeMiddleware.php:11-59`; `app/Http/Middleware/TuModeMiddleware.php:11-29` | Middleware menginisialisasi/mengubah session mode; keduanya tidak menolak request berdasarkan mode. `GuruModeMiddleware` mengembalikan mode ekskul ke akademik bagi guru yang tidak mempunyai penugasan tahun aktif bila relasi guru tersedia. |
| Policy dan Gate | `app/Policies/UserPolicy.php:7-26`; `app/Providers/AppServiceProvider.php:48-61` | `UserPolicy` membatasi admin dari target superadmin dan akun sendiri. Gate `manage-user` memiliki aturan berbeda: meloloskan superadmin, menolak target superadmin bagi aktor lain, lalu meloloskan semua aktor lainnya. Pencarian call-site tidak menemukan pemanggilan `authorize`, `Gate::authorize`, atau `manage-user` pada controller; tidak disimpulkan bahwa deklarasi ini melindungi endpoint. |
| Pembatasan target oleh admin | `app/Http/Middleware/ForbidSuperadminTarget.php:11-30`; `routes/admin.php:19-35`; `app/Http/Controllers/Admin/UserController.php:13-18,61-75,114-160` | Route edit/update/delete memakai middleware target; method controller juga menolak operasi tertentu terhadap superadmin dan akun sendiri. Route toggle tidak memakai middleware target, tetapi controller memeriksa target superadmin dan akun sendiri. |

### 3.2 Matriks role × modul × operasi

Matriks berikut adalah ringkasan route dan controller yang diperiksa, bukan definisi kebijakan sekolah atau jaminan cakupan seluruh endpoint. `Route` berarti grup route mengizinkan role tersebut; `Controller` berarti ada pembatasan data/operasi yang terlihat pada controller yang ditelusuri; `—` berarti tidak ditemukan pada route/controller yang diperiksa, bukan bukti ketiadaan global. Superadmin dapat melewati pemeriksaan role pada route yang menggunakan middleware `role` sesuai temuan AUTHZ-001.

| Modul / operasi utama | Admin | Superadmin | TU | Guru | Siswa | SPMB |
|---|---|---|---|---|---|---|
| Dashboard dan akun | Route admin memakai `auth`, `approved`, `role:admin`; CRUD akun punya pemeriksaan target tertentu. | Route superadmin memakai `auth`, `role:superadmin`; bypass role juga memberi lintasan ke grup role lain. | Route akun ada di grup TU; dapat membaca akun dan menjalankan reset password/toggle status berdasarkan ID. | Tidak ada operasi manajemen akun yang ditemukan dalam route guru. | Tidak ada operasi manajemen akun pada route siswa. | Dashboard route meminta `role:spmb`; role dan login terkait belum selaras pada source yang diperiksa. |
| Data sekolah dan akademik TU | Tidak ditemukan route CRUD TU dalam group admin. | Sebagian pengelolaan data/penetapan tersedia pada grup superadmin. | Route TU mencakup siswa, guru, rombel, tahun ajaran, mapel, dokumen, dan entitas lain; beberapa sub-area menambah `approved`/`tahun.aktif`. | Tidak ditemukan operasi master TU pada group guru. | Tidak ditemukan operasi master TU pada group siswa. | — |
| Jadwal akademik | — | Monitoring keputusan/publikasi dan jadwal final pada grup superadmin. | Baca jadwal final dan ekspor pada grup TU. | Pengajuan jadwal dan operasi guru pada grup `auth`, `role:guru`, `guru.mode`; resource pengajuan tertentu tidak memfilter index berdasarkan guru. | GET jadwal pada grup `auth`; controller memakai penempatan siswa/tahun pada alur yang ditinjau. | — |
| Absensi, nilai, rapor, perkembangan | — | Tidak ditemukan operasi pencatatan akademik dalam route superadmin yang diperiksa. | Tidak ditemukan operasi guru/siswa terkait pada route TU yang diperiksa. | Pencatatan absensi/nilai, perkembangan, detail siswa, dan rapor pada grup guru; pembatasan assignment berbeda antar-method. | GET nilai/absensi pada grup `auth`; query yang dibaca menyaring `siswa_id` dari relasi akun dan tahun berjalan. | — |
| E-learning | — | — | — | Resource dan endpoint komentar berada pada grup guru; batas controller e-learning berbeda menurut method. | GET materi/komentar berada pada grup `auth`; controller materi dan komentar yang dibaca membandingkan kelas/penempatan aktif. | — |
| Ekstrakurikuler | — | Penetapan pembina, monitoring dan publikasi pada grup superadmin. | Resource master kegiatan pada grup TU. | Anggota, presensi, jurnal, catatan pembinaan, serta jadwal pada grup guru; beberapa operasi belum men-scope ID target ke pembina. | GET kegiatan/detail/absensi pada grup `auth`; index menyaring keanggotaan aktif, sementara detail menerima route-bound ekskul tanpa pemeriksaan membership yang tampak. | — |
| Dokumen dan surat | — | Baca dokumen, validasi surat, nomor surat pada grup superadmin. | CRUD dokumen, surat, arsip/nomor surat dalam grup TU; operasi pengajuan memakai hak group TU. | Daftar dan CRUD surat guru berada pada grup guru; operasi berdasarkan ID belum tampak melakukan scope pemilik. | Daftar dokumen dan nomor surat berada pada grup `auth`; tautan berkas memakai URL aset. | — |
| PPDB/SPMB | Tidak ditemukan pada group admin. | — | Area PPDB berada pada route `auth`, `role:tu`; middleware `approved` tidak tampak di `routes/ppdb-admin.php`. Route publik pendaftaran/status tidak memerlukan login. | — | — | Route dashboard dilindungi `auth`, `role:spmb`; deklarasi nilai role/login tidak konsisten. |
| Notifikasi | Route inbox memakai `auth`; controller membatasi role yang didukung. | Sama. | Sama. | Sama. | Sama. | Tidak termasuk role yang diterima oleh controller notifikasi. Notifikasi realtime dan Activity Log tetap di luar cakupan audit. |
| Keuangan | Modul/route tidak ditelusuri tuntas pada tahap ini. | Modul/route tidak ditelusuri tuntas pada tahap ini. | Controller/route yang menghubungkan seluruh operasi keuangan belum diverifikasi ulang. | — | — | — |

## 4. Temuan otorisasi dan kepemilikan data

Setiap status di bawah adalah penilaian atas bukti source. Tidak ada permintaan akses yang dijalankan. Kategori requirement menyatakan apakah ada dasar requirement yang ditemukan dalam cakupan audit, bukan tingkat keparahan.

| ID | Modul / aktor / endpoint | Frontend dan perilaku backend | Bukti dan klasifikasi | Kategori requirement | Dampak potensial dan verifikasi | Status |
|---|---|---|---|---|---|---|
| AUTHZ-001 | Lintas modul; superadmin; semua route dengan `role:<role>` | UI tiap role menyediakan menu sesuai area, tetapi `RoleMiddleware` menerima seluruh request superadmin sebelum membandingkan role yang diminta. Route guru/TU/admin/siswa menggunakan middleware role di grupnya masing-masing. | **FAKTA KODE:** `app/Http/Middleware/RoleMiddleware.php:10-29`; `routes/admin.php:8-9`; `routes/guru.php:33-36`; `routes/tu.php:38-45`; `routes/superadmin.php:27-30`. | `DESIGN DECISION` | Superadmin secara teknis dapat mencapai endpoint yang dimaksudkan untuk role lain; otorisasi per-resource di controller masih dapat mempersempit sebagian operasi. Apakah akses lintas role ini sengaja menjadi hak superadmin perlu diputuskan dan dicocokkan dengan kontrol data pada setiap modul. | `PERLU VERIFIKASI` |
| AUTHZ-002 | Akun; petugas TU; `GET /tu/akun`, `GET /tu/akun/{user}`, `POST /tu/akun/{user}/reset-password`, `POST /tu/akun/{user}/toggle-status` | UI daftar akun menyediakan detail, reset password, dan toggle status per baris tanpa kondisi role target yang tampak. Index mengambil `User` tanpa filter role wajib. Controller menerima target route-bound, menyetel password dari tanggal lahir untuk role guru/TU/superadmin atau siswa, dan toggle `is_active`; tidak tampak larangan bagi target superadmin/admin atau pemeriksaan policy. | **FAKTA KODE:** `routes/tu.php:38-45,392-415`; `app/Http/Controllers/Tu/AkunController.php:12-35,37-98`; `resources/views/tu/akun/index.blade.php:136-163`. | `UNSUPPORTED` | Petugas TU dapat secara statis menjalankan operasi atas target lintas role yang diketahui ID-nya, termasuk reset password superadmin dan mengubah status aktifnya. Batas wewenang TU atas akun lain harus dikonfirmasi oleh Project Owner. Tidak dicoba terhadap akun/data aktual. | `TERKONFIRMASI SECARA STATIS` |
| AUTHZ-003 | Detail akademik siswa; guru; `GET guru.kelas-saya.show` | UI kelas memberi jalur menuju detail siswa. Method `show(Siswa $siswa)` membaca nilai, absensi, dan perkembangan siswa berdasarkan ID route serta tahun aktif; tidak tampak validasi bahwa siswa tersebut berada pada kelas wali atau kelas/mapel yang diampu guru pemanggil. | **FAKTA KODE:** `routes/guru.php:294-299`; `app/Http/Controllers/Guru/WaliKelasController.php:144-249`; konteks daftar assignment pada `:16-141`. | `UNSUPPORTED` | Potensi guru membaca ringkasan akademik siswa di luar assignment-nya melalui ID siswa lain. Pemeriksaan middleware lain dan aturan apakah seluruh guru boleh melihat detail tersebut perlu dikonfirmasi; tidak dilakukan request. | `TERKONFIRMASI SECARA STATIS` |
| AUTHZ-004 | Absensi; guru; `GET guru.absensi.edit`, `PUT guru.absensi.update` | Halaman menyediakan edit absensi. Method edit hanya me-render record hasil route model binding; update menulis status/keterangan pada record itu tanpa validasi input atau pemeriksaan bahwa jadwal/siswa/record berada dalam kewenangan guru saat ini. | **FAKTA KODE:** `routes/guru.php:223-231`; `app/Http/Controllers/Guru/AbsensiController.php:378-401`. Method `store` memiliki pembatasan jadwal guru pada `:138-195`, tetapi pemeriksaan tersebut tidak tampak dipakai oleh `edit/update`. | `UNSUPPORTED` | ID absensi yang diketahui berpotensi dipakai guru lain untuk melihat/mengubah record lintas assignment. Perlu validasi kebutuhan hak koreksi absensi dan verifikasi dinamis di lingkungan uji sebelum menyimpulkan dampak operasional. | `TERKONFIRMASI SECARA STATIS` |
| AUTHZ-005 | Penjadwalan akademik; guru; `GET guru.pengajuan-jadwal.index` | Halaman daftar menampilkan resource pengajuan. `index()` mengambil semua `PengajuanJadwal` terbaru, tidak menyaring `guru_id`; sedangkan `store()` menautkan guru saat ini. Jalur grid Jadwal Saya terpisah dan memakai query berscope guru. | **FAKTA KODE:** `routes/guru.php:193-196`; `app/Http/Controllers/Guru/PengajuanJadwalController.php:16-25,37-57`; pembanding `app/Http/Controllers/Guru/JadwalSayaController.php:319-345`. | `UNSUPPORTED` | Guru pada route ini berpotensi melihat pengajuan guru lain, termasuk metadata jadwal dan statusnya. Apakah daftar lintas guru memang diperlukan tidak didokumentasikan pada source yang diperiksa. | `TERKONFIRMASI SECARA STATIS` |
| AUTHZ-006 | Pengajuan surat; guru; operasi show/edit/update/destroy resource | UI daftar guru menautkan detail/edit/hapus hanya untuk baris pengajuan yang terdaftar bagi user tersebut. Index memang memfilter `user_id`, tetapi `show`, `edit`, `update`, dan `destroy` mencari record hanya dengan `PengajuanSurat::findOrFail($id)`. Update/delete juga mengubah atau menghapus berkas record target. | **FAKTA KODE:** `routes/guru.php:322`; `app/Http/Controllers/Guru/PengajuanSuratController.php:17-27,98-135,210-233`; `resources/views/guru/pengajuan-surat/index.blade.php:48,101-116`. | `UNSUPPORTED` | Mengetahui ID record berpotensi memungkinkan guru melihat, mengubah, atau menghapus pengajuan guru lain melalui endpoint resource. Batasan lain di luar method ini dan kebutuhan TU/Superadmin untuk berkas tidak diuji. | `TERKONFIRMASI SECARA STATIS` |
| AUTHZ-007 | Ekstrakurikuler; guru/pembina; `DELETE guru.ekskul.jurnal.destroy`, `POST guru.ekskul.catatan-pembinaan.store` | UI jurnal menyediakan aksi hapus record; controller `destroy` mengambil jurnal hanya dengan ID dan menghapusnya tanpa cek assignment pembina. Form catatan mengirim `ekskul_id`/`siswa_id`; controller memvalidasi keberadaan field lalu `updateOrCreate` berdasar ID tersebut tanpa tampak memeriksa relasi siswa–ekskul atau penugasan pembina. | **FAKTA KODE:** `routes/guru.php:154-164,334-343`; `app/Http/Controllers/Guru/Ekskul/JurnalEkskulController.php:91-100`; `app/Http/Controllers/Guru/Ekskul/CatatanPembinaanController.php:51-95`; `resources/views/guru/ekskul/catatan/index.blade.php:214-219`. | `UNSUPPORTED` | Guru berpotensi menghapus jurnal ekskul lain atau membuat/mengubah catatan untuk pasangan ekskul/siswa di luar penugasannya. Tabel anggota dan kebijakan pembina lintas tahun perlu dicocokkan oleh pemilik proses; tidak ada percobaan perubahan data. | `TERKONFIRMASI SECARA STATIS` |
| AUTHZ-008 | Ekstrakurikuler; siswa; `GET siswa.ekskul.show` dan `.absensi` | UI siswa hanya menautkan detail/absensi dari daftar ekskul milik sendiri yang berstatus aktif. Kedua method menerima ekskul melalui route binding dan tidak tampak memastikan membership siswa. Query absensi tetap membatasi `siswa_id` ke siswa yang login, tetapi detail menghitung jumlah anggota/pertemuan. | **FAKTA KODE:** `routes/siswa.php:46-59`; `app/Http/Controllers/Siswa/EkskulController.php:13-38,41-90,94-175`; `resources/views/siswa/ekskul/index.blade.php:104-111`. | `UNSUPPORTED` | ID kegiatan lain berpotensi membuka statistik agregat kegiatan; riwayat absensi yang ditampilkan tetap tersaring ke siswa saat ini. Apakah halaman detail kegiatan memang ditujukan untuk anggota aktif saja perlu dikonfirmasi. | `PERLU VERIFIKASI` |
| AUTHZ-009 | Dokumen dan surat; seluruh aktor dengan akses URL berkas | View dokumen/surat memakai tautan `asset()` ke `uploads/dokumen` atau `uploads/pengajuan-surat`; controller menyimpan berkas pada path di bawah `public/`. Akses halaman daftar dibatasi route role, tetapi URL berkas tidak melewati controller otorisasi yang terlihat. | **FAKTA KODE:** `app/Http/Controllers/Tu/DokumenController.php:74,113,152-162`; `app/Http/Controllers/Guru/PengajuanSuratController.php:67,192`; `resources/views/siswa/download-dokumen/index.blade.php:90`; `resources/views/guru/pengajuan-surat/show.blade.php:31-37`; `resources/views/superadmin/pengajuan-surat/show.blade.php:49-55`. **INFERENSI TEKNIS:** jika URL publik dapat dijangkau, pembatasan halaman tidak otomatis diterapkan pada berkas. | `UNSUPPORTED` | Berkas yang dimaksud privat mungkin dapat diakses langsung bila web server melayani path publik tanpa aturan tambahan. Konfigurasi deployment, aturan web server, dan storage aktual tidak diperiksa; karena itu paparan aktual belum dinyatakan terkonfirmasi. | `PERLU VERIFIKASI` |
| AUTHZ-010 | Semua akun; perubahan status approval/aktif dan sesi yang sudah login | Login memeriksa status akun, dan Superadmin dapat memblokir/membuka akun. Namun group TU (di luar nested kelas), guru, siswa, serta superadmin hanya menyebut `auth`/`role`/mode dan tidak selalu menjalankan `approved`; method block tidak tampak mencabut session aktif. | **FAKTA KODE:** `app/Http/Requests/Auth/LoginRequest.php:95-154`; `app/Http/Controllers/SuperAdmin/UserApprovalController.php:83-126`; `app/Http/Middleware/EnsureUserApproved.php:10-37`; `routes/guru.php:33-36`; `routes/siswa.php:16-18`; `routes/superadmin.php:27-30`; `routes/tu.php:38-45,90`. | `DESIGN DECISION` | Jika sesi yang sudah terautentikasi tetap dipertahankan sesudah akun diblokir, route tanpa `approved` berpotensi masih dapat dipanggil. Revalidasi status/session pada setiap request tidak diuji; perlu keputusan mengenai waktu efektif pemblokiran. | `PERLU VERIFIKASI` |
| AUTHZ-011 | Autentikasi dan role; admin/SPMB | Admin dapat dibuat lewat form/controller admin, tetapi credential login yang ditelusuri mencari NIP/NIS dan redirect login tidak mempunyai cabang admin. Route dashboard SPMB meminta `role:spmb`, sementara enum role tidak memuat nilai itu; terdapat kelas response lain dengan nilai `spmb`/`murid`, tetapi route login yang diperiksa memakai `AuthenticatedSessionController`. | **FAKTA KODE:** `app/Http/Controllers/Admin/UserController.php:82-106`; `app/Http/Requests/Auth/LoginRequest.php:43-80`; `app/Http/Controllers/Auth/AuthenticatedSessionController.php:54-108`; `routes/admin.php:8-14`; `routes/web.php:47-49`; `app/Enums/UserRole.php:5-11`; `app/Http/Responses/LoginResponse.php:16-31`; response Fortify yang di-bind berada pada `app/Actions/Fortify/LoginResponse.php:7-14`. | `CONFLICT` | Jalur akun admin/SPMB dan penerimaan role belum konsisten pada source; tidak dapat dipastikan akun mana yang dapat login tanpa memeriksa data/config runtime. Perlu konfirmasi apakah kedua jenis akun memang harus dapat masuk dan controller/response mana yang menjadi alur aktif. | `PERLU VERIFIKASI` |

## 5. Perbandingan frontend–backend

1. **Menu bukan kontrol endpoint.** Sidebar Guru/TU/Siswa memilih navigasi menurut role/mode, tetapi perlindungan backend berasal dari middleware route dan pemeriksaan controller. `guru.mode`/`tu.mode` mengelola session mode, bukan menolak seluruh endpoint mode lain.
2. **Tampilan daftar berscope tidak selalu menjamin detail/update berscope.** Index surat guru dan daftar jadwal grid menyaring data, sedangkan method detail/edit/update/delete pengajuan surat serta index resource jadwal memiliki query berbeda yang tidak tampak menerapkan scope pemilik. Lihat AUTHZ-005 dan AUTHZ-006.
3. **Aksi akun TU ditampilkan per baris tanpa pembatasan role target yang tampak.** Route tetap dibatasi role TU, tetapi controller menerima user target melalui route binding tanpa pemeriksaan target role untuk reset/toggle. Lihat AUTHZ-002.
4. **UI ekstrakurikuler membatasi entry point, tetapi ID route/request tetap perlu diperiksa server-side.** Link siswa berasal dari daftar anggota aktif; endpoint detail tidak memeriksa membership. Form catatan dikirim dari daftar anggota pembina, namun backend menggunakan ID request tanpa validasi relasi/assignment yang tampak. Lihat AUTHZ-007 dan AUTHZ-008.
5. **Pembatasan halaman tidak sama dengan pembatasan file.** Halaman dokumen/surat dibatasi route group, sedangkan link file mengarah langsung ke aset. Keberhasilan akses langsung bergantung pada web server dan deployment yang tidak diperiksa.
6. **E-learning memperlihatkan pola scope yang lebih eksplisit pada jalur siswa yang dibaca.** Materi dan komentar siswa membandingkan kelas dari penempatan aktif; balasan memeriksa kelas forum dan menyimpan `user_id` dari sesi (`app/Http/Controllers/Siswa/ElearningController.php:19-36,50-72,89-116`; `app/Http/Controllers/Elearning/ElearningKomentarController.php:14-39`; `ElearningKomentarReplyController.php:15-28`). Ini tidak membuktikan seluruh lifecycle lampiran/komentar sudah terotorisasi.
7. **Perbedaan method resource dan pemeriksaan controller.** Route resource guru membentuk endpoint standar, sementara operasi surat secara eksplisit menerima ID tanpa scope user. Daftar role pada UI tidak dijadikan bukti bahwa backend menerapkan aturan identik.

## 6. Temuan yang belum dapat dipastikan

- Apakah superadmin memang dimaksudkan melewati semua nilai `role` atau hanya memiliki route superadmin sendiri.
- Apakah TU boleh melihat/mengubah status akun dan mengatur ulang password akun superadmin/admin/pegawai/siswa.
- Apakah guru boleh melihat semua detail siswa, mengoreksi semua absensi, atau mengelola record kegiatan ekskul lintas penugasan.
- Apakah halaman detail ekskul siswa dimaksudkan terbuka untuk siswa yang bukan anggota.
- Apakah file pada direktori `public/uploads` dapat diambil langsung di konfigurasi deployment aktif atau dilindungi lapisan web server lain.
- Apakah akun dinonaktifkan harus kehilangan akses pada request berikutnya atau hanya pada login baru.
- Apakah role `admin` dan `spmb` memang aktif; login, status/relasi user, binding Fortify, dan route aktif tidak diuji secara runtime.
- Apakah assignment historis, tahun ajaran aktif, semester, penempatan siswa, dan data anggota sesuai dengan asumsi query source. Database tidak diakses.
- Apakah kebijakan tertentu didasarkan pada regulasi atau kebijakan sekolah. Tidak ada dokumen requirement/regulasi yang digunakan sebagai bukti audit ini.

## 7. Pertanyaan untuk validasi kebutuhan sekolah

Pertanyaan berikut memerlukan jawaban Project Owner/sekolah; laporan ini tidak menetapkan jawabannya:

1. Apakah superadmin harus bisa mengakses semua endpoint role lain, atau hanya endpoint yang secara eksplisit didelegasikan?
2. Apakah petugas TU berwenang mereset password maupun mengubah status akun seluruh role, khususnya superadmin dan admin?
3. Data siswa mana yang boleh dilihat guru bidang, wali kelas, dan guru tanpa assignment aktif? Apakah detail siswa harus dibatasi dengan aturan yang berbeda dari rekap kelas?
4. Guru mana yang boleh mengubah absensi setelah pencatatan, dan batas assignment/tanggal/status apa yang berlaku?
5. Apakah pengajuan jadwal guru harus hanya terlihat bagi pengaju, atau daftar lintas guru memang kebutuhan monitoring?
6. Siapa yang boleh melihat, memperbarui, atau menghapus pengajuan surat guru/TU, dan apakah berkasnya bersifat privat?
7. Apakah pembina ekskul hanya boleh menghapus jurnal/mengisi catatan untuk ekskul dan siswa yang ditugaskan pada tahun ajaran aktif?
8. Apakah siswa nonanggota boleh melihat jumlah anggota/jumlah pertemuan ekskul?
9. Seberapa cepat blokir akun harus menghentikan sesi yang sedang berjalan?
10. Apakah role `admin` dan `spmb` merupakan role aktif, dan identifier/credential/login redirect apa yang ditetapkan untuknya?

Semua requirement yang tidak disertai sumber dalam repositori diklasifikasikan `UNSUPPORTED`, `DESIGN DECISION`, atau `CONFLICT` sesuai tabel temuan; klasifikasi tersebut bukan klaim bahwa kebijakan sekolah telah ditetapkan.

## 8. Cakupan dan batas audit

### Telah diperiksa

- Baseline Git dan ketujuh laporan tahap sebelumnya.
- Role enum, model `User`, relasi pengguna ke Guru/Siswa/pegawai, request login, redirect, role/approval/mode middleware, Gate/Policy, bootstrap alias, dan route entry point.
- Deklarasi route group utama admin, TU, guru, siswa, superadmin, PPDB, auth, dan beberapa route publik; fokus controller pada autentikasi/akun, penjadwalan, data akademik guru/siswa, e-learning, ekskul, surat/dokumen, PPDB, serta notifikasi.
- Jalur kritis berbasis ID pada operasi show/edit/update/destroy, beberapa download/export/print yang terhubung dengan area di atas, dan bukti frontend terkait temuan yang dicatat.
- Nama test otorisasi/akun yang ditemukan, termasuk `tests/Feature/ElearningStudentAuthorizationTest.php` dan `tests/Feature/Tu/AkunPasswordResetTest.php`. Test tidak dijalankan.

### Belum diperiksa tuntas

- Audit baris demi baris seluruh controller dan endpoint pada seluruh 176 file PHP aplikasi; matriks hanya mencatat route dan kontrol yang ditelusuri.
- Seluruh CRUD/bulk/import/export/print untuk semua controller TU/Superadmin/PPDB serta seluruh operasi profil dan kepegawaian.
- Seluruh query kepemilikan pada setiap controller e-learning, pengumpulan lampiran, semua operasi komentar, route API, dan mekanisme integrasi.
- Setiap operasi kenaikan kelas, arsip dokumen legacy, seluruh modul keuangan, dan seluruh route yang tidak terhubung jelas dengan UI.
- Seluruh tes dan konfigurasi server, storage, web server, policy lingkungan deployment, atau mekanisme autentikasi di luar source yang dibaca.

Pernyataan “tidak ditemukan” dalam laporan ini dibatasi pada route/controller dan pencarian yang disebutkan; itu bukan bukti bahwa suatu fitur atau kontrol tidak ada di luar cakupan tersebut. Tidak ada kesimpulan kesesuaian terhadap regulasi atau kebijakan sekolah.

### Status akhir dan perubahan file

Pemeriksaan Git sesudah pembuatan laporan menunjukkan:

- Branch tetap `main`; commit tetap `b32f25fc6e28b8e0262fa745e6cf454ea28e6414`.
- Perubahan baseline tetap: `M docs/audit/TAHAP_1_INVENTARIS.md` dan laporan Tahap 2 sampai 2F tetap untracked.
- Laporan baru `docs/audit/TAHAP_3_AUDIT_OTORISASI_LINTAS_PERAN.md` tercatat sebagai satu file dokumentasi untracked tambahan.
- Tidak ada perubahan file aplikasi yang tampak akibat audit; tidak ada laporan terdahulu yang ditimpa.
- Tidak seluruh modul dan operasi aplikasi diperiksa baris demi baris; area yang belum selesai dirinci di atas.

Audit berhenti pada Tahap 3. Tidak ada perubahan kode, commit, atau implementasi perbaikan.
