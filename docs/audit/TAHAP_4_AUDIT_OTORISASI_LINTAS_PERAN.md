# SINERGI-MJ - Audit Tahap 4: Otorisasi Lintas Peran

## 1. Ringkasan dan batas pemeriksaan

| Metadata | Hasil |
|---|---|
| Waktu pemeriksaan | 23 November 2026, 21:07 WIB |
| Workspace | `C:\laragon\www\sistem-informasi-sekolah` |
| Branch / HEAD | `main` / `6349b56c75c9fff815b5cd3ef941ca1770f1d49c` |
| Jenis pemeriksaan | Static review; tidak melakukan eksploitasi atau perubahan data |
| File yang dibuat | `docs/audit/TAHAP_4_AUDIT_OTORISASI_LINTAS_PERAN.md` |

Audit meneruskan [Tahap 3](./TAHAP_3_AUDIT_OTORISASI_LINTAS_PERAN.md), [Tahap 3A](./TAHAP_3A_PENDALAMAN_OTORISASI.md), dan [Tahap 3B](./TAHAP_3B_CAKUPAN_AUDIT_OTORISASI.md), tanpa menimpa atau mengubah laporan tersebut. Status Git sebelum audit menunjukkan lima perubahan lokal yang telah ada: `ProfileController.php`, `SINERGI_MJ_MASTER_AUDIT.md`, `login.blade.php`, `routes/web.php`, dan `TahunAjaranSemesterTransitionTest.php`. Semuanya dipertahankan.

Tidak membuka `.env`, kredensial, data pribadi, atau berkas unggahan. Tidak menjalankan test, query database, migration, request aplikasi, atau mutasi. `route:list --json` hanya dipakai untuk membaca registrasi route. Tidak dilakukan browser testing. Hasil dan confidence di bawah adalah penilaian source statis; tidak ada temuan yang diklaim telah dibuktikan melalui request eksploit.

## 2. Inventaris route dan model akses

### 2.1 Registrasi runtime

`php artisan route:list --json` menghasilkan 365 route-action runtime, 360 bernama. Hitungan yang tercatat:

| Prefix | Route |
|---|---:|
| `tu/` | 149 |
| `superadmin/` | 75 |
| `guru/` | 75 |
| `siswa/` | 18 |
| `admin/` | 12 |
| `ppdb/` | 9, ditambah route root `ppdb` |

Middleware pada route runtime (jumlah saling tumpang tindih): `auth` 345, `role:*` 312, `menu.route` 225, `feature:*` 178, dan `approved` 25. Ada 20 route-action yang tidak menyatakan middleware autentikasi: halaman login/pemulihan password, pendaftaran/status PPDB publik, API statistik agregat, file serving lokal, health check, dan gateway publik. Endpoint `storage.local` yang diperiksa berasal dari disk lokal privat dan controller framework mensyaratkan relative signature; ini tidak dihitung sebagai temuan.

Semua 225 route yang memakai `menu.route` memiliki pemetaan menu atau tercantum sebagai unrestricted: 222 dipetakan dan 3 adalah route profil Guru. Pemeriksaan template sidebar menunjukkan link menu Guru, TU/PPDB, dan Siswa memakai `User::hasFeature`/`hasAnyAssignmentMenu` yang sama. Menu/UI bukan pengganti otorisasi server; kontrol final tetap bergantung pada middleware dan scope controller.

Hitungan runtime ini tidak sebanding langsung dengan 396 deklarasi source pada Tahap 3B, yang menghitung deklarasi resource/duplikasi dengan metode berbeda. Beberapa deklarasi siswa berulang untuk URI/nama yang sama; route-list runtime menunjukkan handler akhir yang terdaftar, tetapi deklarasi duplikat masih menjadi risiko pemeliharaan.

### 2.2 Identitas dan kebijakan role

- `User::guru()` memakai `users.pegawai_id` menuju `Guru.data_kepegawaian_id`; `User::pegawai()` dan `User::siswa()` memakai foreign key terpisah. Controller pengajuan jadwal saat ini menggunakan `$user->guru`, bukan atribut langsung `guru_id`.
- Untuk Guru, `User::hasFeature` mensyaratkan relasi Guru/pegawai dan menu pada penugasan aktif milik pegawai tersebut. TU selalu ditolak pada cabang `hasFeature`; akibatnya fallback `role:tu` ke route `role:guru` tidak dengan sendirinya memberi akses pada route yang juga memakai `menu.route`.
- `RoleMiddleware` meloloskan `superadmin` sebelum membandingkan role target. Ini konsisten dengan Kepsek yang memakai identifier teknis `superadmin` dan tidak dilaporkan sebagai temuan tersendiri. Controller/resource tetap perlu menjaga scope jika kebijakan mengharuskannya.
- `UserRole` memuat `admin`, tetapi login memiliki cabang default 403 untuk role selain Guru/TU/superadmin/Siswa. Route `spmb.dashboard` meminta `role:spmb`, sedangkan `spmb` tidak tampak dalam enum dan redirect login yang diperiksa. Dampak pada akun nyata tidak diverifikasi.
- `Gate manage-user` mengizinkan aktor non-superadmin atas target non-superadmin, tetapi pencarian seluruh workspace tidak menemukan call-site aplikasi. Ini merupakan deklarasi otorisasi berbahaya bila dipakai, bukan jalur eksploit aktif yang terbukti.

## 3. Temuan

Severity dan confidence menilai bukti source, bukan hasil uji akses. `HIGH` berarti terdapat potensi pembacaan/perubahan data penting lintas lingkup; exploitability runtime belum diuji.

| # | Severity | File | Lines | Vulnerability | Confidence |
|---|----------|------|-------|---------------|------------|
| 1 | 🟠 HIGH | [PublicController.php](../../app/Http/Controllers/PPDB/PublicController.php), [filesystems.php](../../config/filesystems.php), [verifikasi-form.blade.php](../../resources/views/ppdb/pendaftar/verifikasi-form.blade.php) | 179-187; 43-45, 77; 80-220 | Berkas KK, KTP orang tua, ijazah, akta, KIP, dan foto PPDB ditulis ke disk `public`; halaman verifikasi membentuk URL `/storage/...` langsung. Bila symlink standar terpasang, akses berkas tidak melewati pemeriksaan role/controller dan pemegang URL dapat membacanya. Keberadaan symlink deployment tidak diuji. | 9/10 |
| 2 | 🟠 HIGH | [AkunController.php](../../app/Http/Controllers/Tu/AkunController.php), [tu.php](../../routes/tu.php), [assignment_menus.php](../../config/assignment_menus.php) | 12-98; 396-420; 107 | Pengguna yang mendapat menu operator akun dapat menargetkan User lintas role. `resetPassword` mengatur password ke tanggal lahir target dan `toggleStatus` mengubah status aktif tanpa penolakan target superadmin/admin atau pemeriksaan kepemilikan. Menu assignment membatasi aktor, tetapi bukan target. | 9/10 |
| 3 | 🟠 HIGH | [WaliKelasController.php](../../app/Http/Controllers/Guru/WaliKelasController.php), [guru.php](../../routes/guru.php) | 144-214; 294-299 | `GET guru.kelas-saya.show` menerima ID Siswa dan membaca nilai, absensi, serta perkembangan tanpa memeriksa bahwa Siswa berada pada kelas wali/pengampu Guru tersebut. Manipulasi ID berpotensi membaca ringkasan akademik siswa lain. | 10/10 |
| 4 | 🟠 HIGH | [AbsensiController.php](../../app/Http/Controllers/Guru/AbsensiController.php), [guru.php](../../routes/guru.php) | 138-195, 378-401; 200-215 | `edit/update` mengakses record absensi berdasarkan route ID tanpa memeriksa guru, jadwal, kelas, atau siswa pemilik. Jalur `store` memeriksa jadwal milik Guru tetapi tidak memeriksa bahwa setiap `siswaId` pada payload merupakan anggota aktif kelas jadwal tersebut. | 10/10 |
| 5 | 🟠 HIGH | [NilaiController.php](../../app/Http/Controllers/Guru/NilaiController.php), [guru.php](../../routes/guru.php) | 356-430; 260-265 | `store` memastikan ada jadwal valid milik Guru untuk kelas/mapel, tetapi menerima key `siswaId` dari request tanpa memastikan siswa ditempatkan pada kelas itu. Guru dapat mengirim nilai untuk ID siswa di luar daftar kelas yang sah. | 9/10 |
| 6 | 🟠 HIGH | [PerkembanganSiswaController.php](../../app/Http/Controllers/Guru/PerkembanganSiswaController.php), [guru.php](../../routes/guru.php) | 120-153; 270-275 | `store` memastikan aktor adalah wali kelas, tetapi tidak membatasi `siswaId` pada payload ke siswa kelas wali. `updateOrCreate` dapat membuat atau menimpa catatan perkembangan siswa lain. | 9/10 |
| 7 | 🟠 HIGH | [AbsensiEkskulController.php](../../app/Http/Controllers/Guru/Ekskul/AbsensiEkskulController.php), [CatatanPembinaanController.php](../../app/Http/Controllers/Guru/Ekskul/CatatanPembinaanController.php), [JurnalEkskulController.php](../../app/Http/Controllers/Guru/Ekskul/JurnalEkskulController.php), [guru.php](../../routes/guru.php) | 87-162; 51-95; 91-99; 103-130, 136-148, 321-328 | Absensi ekskul memakai ekskul pembina yang login tetapi tidak memeriksa `siswaId` sebagai anggota aktif ekskul. Catatan pembinaan menerima `ekskul_id` dan `siswa_id` request tanpa mencocokkan pembina/keanggotaan. Hapus jurnal mencari ID global tanpa scope pembina. Berpotensi mengubah atau menghapus data lintas anggota/ekskul. | 9/10 |
| 8 | 🟡 MEDIUM | [ppdb-public.php](../../routes/ppdb-public.php), [PublicController.php](../../app/Http/Controllers/PPDB/PublicController.php), [bukti.blade.php](../../resources/views/ppdb/public/bukti.blade.php) | 31-33; 409-419, 462-477; 64-115 | Bukti pendaftaran publik dicari hanya dengan nomor pendaftaran berurutan; halaman mengungkap nomor, nama tersamarkan, tanggal daftar, dan status. Enumerasi nomor berpotensi membocorkan status pendaftaran. Dampak terbatas pada field yang ditampilkan, tetapi kerahasiaan status belum terlindungi. | 9/10 |
| 9 | 🟡 MEDIUM | [ppdb-public.php](../../routes/ppdb-public.php), [PublicController.php](../../app/Http/Controllers/PPDB/PublicController.php) | 46-53; 501-577 | Endpoint publik daftar ulang mengubah `status_daftar_ulang` menjadi `sudah` menggunakan nomor pendaftaran dan tanggal lahir. Ini adalah autentikasi berbasis dua data pemohon yang dapat diketahui/diterka, tanpa login atau token sekali pakai. Dampaknya adalah pemalsuan konfirmasi; tidak ada CSRF bypass yang diuji atau diklaim. | 8/10 |
| 10 | 🟡 MEDIUM | [siswa.php](../../routes/siswa.php), [guru.php](../../routes/guru.php), [UserApprovalController.php](../../app/Http/Controllers/SuperAdmin/UserApprovalController.php), [EnsureUserApproved.php](../../app/Http/Middleware/EnsureUserApproved.php) | 16-18; 33-36; 107-124; 10-37 | Login memeriksa approval/aktif, tetapi group Guru dan Siswa memakai `auth` tanpa `approved`; middleware status tidak diterapkan seragam pada route TU. Pemblokiran hanya mengubah status User dan tidak tampak mencabut sesi. Sesi yang masih aktif berpotensi tetap mengakses route yang tidak memeriksa status. Efek request berikutnya belum diuji runtime. | 8/10 |
| 11 | ⚪ LOW | [AppServiceProvider.php](../../app/Providers/AppServiceProvider.php), [UserPolicy.php](../../app/Policies/UserPolicy.php) | 48-61; 7-26 | Gate `manage-user` meloloskan setiap aktor selain superadmin untuk target selain superadmin; aturan ini lebih luas daripada `UserPolicy`. Tidak ditemukan pemanggil Gate/Policy tersebut pada route/controller/view yang ditelusuri, jadi belum ada endpoint aktif yang terbukti memakainya. | 9/10 |

Temuan 1 memperluas risiko file publik yang telah dicatat pada AUTHZ-009 Tahap 3. Berkas PPDB yang dimaksud mencakup identitas resmi dan dokumen keluarga. File surat/dokumen lain yang disimpan di area publik juga perlu ditinjau berdasarkan klasifikasi datanya; audit ini tidak mengunduh atau membuka berkas.

## 4. Kontrol yang bekerja / temuan lama yang berubah

- `PengajuanJadwalController@index` pada HEAD ini menyaring `guru_id` dengan relasi Guru user login; controller resource hanya mendaftarkan `index/create/store`. `store` menggunakan `JadwalDraftService` untuk membuat draft. Karena itu temuan lama AUTHZ-005 tentang index lintas Guru tidak terlihat lagi pada source saat ini. Tidak ada test atau request baru yang dijalankan untuk memverifikasinya secara dinamis.
- `JadwalSayaController` membatasi kelas/mapel pada opsi penugasan Guru dan memeriksa pemilik pada slot sebelum perubahan; ini tidak digeneralisasi ke semua alur jadwal.
- `RaportController` memeriksa penempatan siswa aktif dan relasi Wali Kelas/Pengampu sebelum menampilkan rapor. Ini berbeda dengan `WaliKelasController@show`, yang tidak memakai pemeriksaan serupa.
- Controller siswa yang ditinjau umumnya mengambil Siswa dari relasi user, membatasi nilai/absensi dengan `siswa_id`, dan membatasi e-learning dengan kelas aktif. Namun `siswa.ekskul.show` tidak memeriksa membership terhadap ekskul target; laporan sebelumnya AUTHZ-008 tetap perlu keputusan apakah detail hanya bagi anggota.
- Inbox notifikasi membatasi query pada notifikasi user login. API statistik publik hanya mengembalikan jumlah Siswa/Guru/Kelas, bukan data identitas.
- Route publik status PPDB melalui URL bertanda tangan memakai middleware `signed` dan URL dikirim dengan masa berlaku tujuh hari. Ini jalur yang lebih kuat dibandingkan `/ppdb/bukti/{no}`.

## 5. Cakupan audit dan yang belum diverifikasi

| Area | Status |
|---|---|
| Registrasi route runtime dan statistik middleware | Selesai untuk inventaris; tidak membuktikan setiap controller aman |
| Pemetaan menu route dan sidebar Guru/TU/Siswa | Diperiksa; 222 route terpetakan, 3 route profil Guru unrestricted |
| Role, assignment menu, Gate/Policy inti | Diperiksa secara statis |
| Controller ownership/IDOR | Parsial; endpoint pada temuan dan kontrol pembanding ditinjau |
| Public/API/PPDB | Route publik diinventaris; controller status, daftar ulang, bukti dan upload diperiksa |
| TU dan Superadmin | Pembatasan role, akun TU, penugasan dan beberapa controller terkait diperiksa; semua 224 action tidak ditelaah per-method |
| Tes/browser/runtime | Tidak dijalankan sesuai batas audit read-only |
| Database/deployment/web server | Tidak diperiksa; tidak ada query database atau pemeriksaan symlink deployment |

Belum seluruh 365 route-action dan 97 controller ditelaah method-by-method. Area yang masih memerlukan review khusus: operasi bulk/import/restore/force-delete TU, seluruh controller Superadmin dan export, seluruh endpoint surat/dokumen serta file download, endpoint SPMB/role legacy, dan semua pasangan ID yang belum tercakup dalam temuan. Temuan statis tidak membuktikan keberadaan akun/assignment tertentu pada database aktif atau akses aktual pada konfigurasi server.

## 6. Rekomendasi prioritas

1. Pindahkan upload PPDB dan dokumen privat dari disk publik; sajikan melalui endpoint berotorisasi dengan validasi pemilik/role. Periksa file yang telah terlanjur tersimpan secara aman sebelum perubahan operasional.
2. Tambahkan pemeriksaan record-level pada endpoint Guru: siswa harus berada di kelas/penugasan aktif; absensi dan jurnal harus berada pada jadwal/ekskul milik Guru; validasi juga status serta rentang nilai.
3. Batasi reset/toggle akun TU berdasarkan role target dan kebijakan akun istimewa. Hindari password default yang dapat ditebak dari tanggal lahir.
4. Ganti pemeriksaan publik daftar ulang dengan bukti kepemilikan yang disetujui (mis. token terbatas masa berlaku), dan kurangi enumerasi status melalui nomor berurutan.
5. Tetapkan apakah blokir akun harus langsung menghentikan sesi; terapkan pemeriksaan status secara konsisten atau cabut sesi/token saat akun dinonaktifkan.
6. Ganti atau hapus Gate `manage-user` yang terlalu permisif sebelum dipakai; selaraskan `admin`, `spmb`, dan role `tu` dengan jalur login serta menu.
7. Setelah persetujuan pemilik kebijakan dan lingkungan uji tersedia, tambahkan Feature Test IDOR untuk temuan 2-10. Jangan menguji perubahan terhadap akun atau data produksi.

## 7. Kesimpulan

**Status keamanan otorisasi: BELUM LULUS.** Terdapat temuan source-level berprioritas tinggi pada dokumen publik, manajemen akun TU, serta scope baca/tulis data akademik Guru dan ekskul. Cakupan route telah diinventaris tetapi belum semua action/controller diaudit satu per satu; status ini bukan sertifikasi keamanan keseluruhan aplikasi.
