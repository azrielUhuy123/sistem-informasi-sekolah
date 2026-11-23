# SINERGI-MJ — Audit Tahap 2F: Workflow Dokumen Sekolah dan Surat

## 1. Metadata dan baseline

| Item | Hasil |
|---|---|
| Waktu audit | 9 Oktober 2026, 14:25:57 WIB (UTC+07:00) |
| Branch | `main` |
| Commit aktif | `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` — `SINERGI-MJ UPDATE` |
| Waktu commit | 9 Oktober 2026, 12:30:29 WIB |
| Status Git sebelum laporan | `M docs/audit/TAHAP_1_INVENTARIS.md`; untracked `TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md`, `TAHAP_2B_WORKFLOW_TU_AKADEMIK.md`, `TAHAP_2C_WORKFLOW_PENJADWALAN.md`, `TAHAP_2D_WORKFLOW_GURU_SISWA.md`, dan `TAHAP_2E_WORKFLOW_EKSTRAKURIKULER.md`. Tidak tampak file aplikasi yang berubah. |
| Pemeriksaan | Pembacaan statis route, controller, model, view, migration, konfigurasi filesystem, dan test yang relevan. |

Baseline dicatat dari repository saat audit dimulai, bukan diambil dari baseline laporan lama. Tidak ada file terdahulu yang diubah atau ditimpa. Sesudah laporan dibuat, status Git diperiksa kembali.

## 2. Referensi audit sebelumnya

Semua laporan berikut ditemukan dan dibaca:

- `docs/audit/TAHAP_1_INVENTARIS.md`
- `docs/audit/TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md`
- `docs/audit/TAHAP_2B_WORKFLOW_TU_AKADEMIK.md`
- `docs/audit/TAHAP_2C_WORKFLOW_PENJADWALAN.md`
- `docs/audit/TAHAP_2D_WORKFLOW_GURU_SISWA.md`
- `docs/audit/TAHAP_2E_WORKFLOW_EKSTRAKURIKULER.md`

Tahap 1 mencatat dokumen dan surat sebagai modul terpetakan sebagian, termasuk model `Dokumen`, `ArsipDokumen`, `PengajuanSurat`, `NomorSurat` dan migration terkait. Audit ini menelusuri workflow secara spesifik tanpa mengulang inventaris keseluruhan. Notifikasi dan Activity Log secara eksplisit berada di luar cakupan.

## 3. Inventaris halaman, route, controller, model, migration, dan storage

### Area aplikasi

| Area | Bukti kode |
|---|---|
| Dokumen TU | Route daftar dokumen sekolah/siswa, form create, store, edit/update, dan delete berada dalam group `auth`, `role:tu`, `tu.mode`. `routes/tu.php:37-45,243-268`. |
| Dokumen guru | Route GET `/guru/dokumen/surat`; group memakai `auth`, `role:guru`, `guru.mode`. `routes/guru.php:33-36,319-320`. |
| Dokumen Superadmin | Route GET `/superadmin/dokumen/surat`; group memakai `auth`, `role:superadmin`. `routes/superadmin.php:27-30,180-181`. |
| Dokumen siswa | Route GET `/siswa/download-dokumen`; group siswa memakai `auth`. Terdapat deklarasi ganda dengan URI dan nama route sama, satu closure dan satu controller. `routes/siswa.php:14-17,61-71`. |
| Pengajuan surat | Guru memakai resource routes; TU mempunyai route GET/POST/PUT/DELETE eksplisit; Superadmin mempunyai route daftar/detail/validasi/tolak. `routes/guru.php:322`; `routes/tu.php:298-321`; `routes/superadmin.php:183-200`. |
| Nomor surat | Guru, TU, siswa, dan Superadmin masing-masing memiliki route GET index dan POST generate dalam group aktor masing-masing. Route siswa mempunyai duplikasi GET index. `routes/guru.php:324-328`; `routes/tu.php:323-331`; `routes/siswa.php:73-88`; `routes/superadmin.php:203-215`. |
| Arsip dokumen terpisah | `ArsipDokumenController` di-import pada `routes/tu.php:19`, tetapi pencarian route seluruh file `routes/` tidak menemukan route yang menunjuk controller atau route bernama `tu.arsip-dokumen.*`. Controller merujuk view `tu.surat.index/create/edit`; view dengan nama tersebut tidak ditemukan di `resources/views/tu/surat/`. |

### Model dan migration

| Entitas | Bukti |
|---|---|
| Dokumen | Model `Dokumen` menggunakan tabel `dokumen` dan field fillable `nama_surat`, `kategori_surat`, `jenis_dokumen`, `file`. `app/Models/Dokumen.php:7-17`. Migration membentuk field yang sama. `database/migrations/2026_05_09_185542_create_dokumen_table.php:14-23`. |
| Pengajuan surat | Model `PengajuanSurat` menyimpan `user_id`, metadata/perihal/pengirim/tujuan, file, status, validator, waktu validasi; relasi `user` dan `validator`. `app/Models/PengajuanSurat.php:7-38`. Migration awal membentuk `pengajuan_surats`, status enum, dan foreign key user/validator; migration lanjutan menambah `validated_file`, `perihal`, `pengirim`, serta menghapus `file_final`. `database/migrations/2026_05_09_215854_create_pengajuan_surats_table.php:11-52`; `2026_05_09_231223_add_validated_file_to_pengajuan_surats_table.php:11-24`; `2026_05_10_145351_add_perihal_to_pengajuan_surats_table.php:11-21`; `2026_05_10_154947_add_pengirim_to_pengajuan_surats_table.php:14-29`; `2026_05_10_230504_remove_file_final_from_pengajuan_surats_table.php:14-25`. |
| Nomor surat | Model `NomorSurat` menyimpan `user_id`, `pengirim`, `nomor_surat`, `status` dan relasi user. `app/Models/NomorSurat.php:7-25`. Migration memberi foreign key ke `users`, unique pada `nomor_surat`, serta enum status `digunakan`; migration berikut menghapus `jenis_surat`. `database/migrations/2026_05_11_224413_create_nomor_surats_table.php:14-48`; `2026_05_11_225536_drop_jenis_surat_from_nomor_surats_table.php:11-20`. |
| Arsip dokumen | Migration awal membuat `arsip_dokumen` dengan `nama_file`, `file_path`, `kategori`; `ArsipDokumen` dan `ArsipDokumenController` memakai field tambahan seperti `nama_surat`, `nomor_surat`, `tanggal_dibuat`, `keterangan`. `database/migrations/2025_12_05_173923_init_all_tables.php:135-140`; `app/Models/ArsipDokumen.php:7-22`; `app/Http/Controllers/Tu/ArsipDokumenController.php:33-57,98-118`. |

### Lokasi file dan PDF

- Controller `DokumenController` dan kedua controller `PengajuanSuratController` memindahkan upload ke direktori di bawah `public/uploads/`; view membentuk tautan menggunakan `asset('uploads/...')`.
- `ArsipDokumenController` memakai disk Laravel `public` dengan path `arsip`; `config/filesystems.php:41` mendefinisikan disk public. Tidak ada konfigurasi environment yang dibuka.
- Input dokumen/surat yang tampak menerima berkas PDF, DOC, DOCX. Preview dokumen TU membuka file PDF melalui URL aset langsung. Tidak ditemukan pembuatan PDF atau template surat dinamis pada workflow dokumen/surat yang ditelusuri. Dependency DomPDF/FPDF/FPDI dideklarasikan di `composer.json:10,15-16`, sementara pencarian controller menemukan penggunaan PDF pada raport, absensi, dan jadwal final, bukan pada alur surat ini.

## 4. Tabel workflow

Status **lengkap** berarti bagian source yang dijelaskan dapat ditelusuri secara statis; bukan bukti alur berhasil dijalankan. **Parsial** berarti ada hubungan komponen, otorisasi, atau perilaku yang belum dapat dipastikan.

| ID | Aktor / UI dan tindakan | Endpoint dan middleware | Controller, validasi, penyimpanan, respons | Bukti dan status |
|---|---|---|---|---|
| DOK-01 | TU: melihat daftar dokumen sekolah atau siswa, mengunggah dokumen, memperbarui metadata/berkas, menghapus dokumen. Form sekolah/siswa mengirim kategori, nama dan jenis dokumen tersembunyi. | GET daftar sekolah/siswa, GET create, POST store, GET edit, PUT update, DELETE dokumen; group TU memakai `auth`, `role:tu`, `tu.mode`. | `DokumenController` memfilter daftar dengan `jenis_dokumen`; validasi create mewajibkan nama, kategori, file dengan `mimes:pdf,doc,docx`, lalu memindahkan file ke `public/uploads/dokumen` dan membuat record. Update memvalidasi metadata, tetapi tidak tampak validasi MIME/ukuran berkas pengganti; file lama dihapus sebelum file baru dipindahkan. Delete menghapus file fisik bila ditemukan lalu menghapus record. Redirect menuju daftar jenis dokumen. | **FAKTA KODE:** `routes/tu.php:243-268`; `resources/views/tu/surat/sekolah/create.blade.php:18-70`, `sekolah/index.blade.php:48,105-137`, `sekolah/edit.blade.php:17-82`; `resources/views/tu/surat/siswa/create.blade.php:18-69`, `siswa/index.blade.php:48,106-137`, `siswa/edit.blade.php:19-84`; `app/Http/Controllers/Tu/DokumenController.php:16-164`; model dan migration pada Bagian 3. **Status: lengkap untuk alur utama, statis.** |
| DOK-02 | Guru/Superadmin: memfilter kategori dan membaca/unduh dokumen sekolah. Siswa: membaca dan mengunduh dokumen jenis siswa. | GET daftar di route group per aktor: guru `auth/role:guru/guru.mode`, Superadmin `auth/role:superadmin`, siswa `auth`. Tidak ditemukan endpoint controller khusus download. | Controller guru dan Superadmin hanya memilih record dengan `jenis_dokumen=sekolah`, mengambil kategori, lalu render view. Controller siswa memilih dokumen `jenis_dokumen=siswa`. Tautan unduh/preview mengarah langsung ke `asset('uploads/dokumen/...')`, bukan melewati pemeriksaan record/pemilik dalam controller download. | **FAKTA KODE:** `routes/guru.php:33-36,319-320`; `routes/superadmin.php:27-30,180-181`; `routes/siswa.php:14-17,68-71`; `app/Http/Controllers/Guru/DokumenGuruController.php:11-39`; `app/Http/Controllers/SuperAdmin/DokumenSuperAdminController.php:11-39`; `app/Http/Controllers/Siswa/DownloadDokumenController.php:10-24`; views `guru/dokumen/index.blade.php:92-93`, `superadmin/dokumen/index.blade.php:175-176`, `siswa/download-dokumen/index.blade.php:90`. **Status: parsial.** Group middleware membatasi halaman daftar; akses aktual ke URL file publik bergantung konfigurasi web server/storage dan tidak diuji. |
| DOK-03 | TU: alur arsip terpisah untuk upload, metadata, preview PDF, download dan hapus berkas. | Route/controller aktif tidak ditemukan dalam file routes; `ArsipDokumenController` hanya di-import pada `routes/tu.php:19`. View yang dirujuk `tu.surat.index/create/edit` tidak ditemukan. View `tu/surat/sekolah/preview.blade.php:9` merujuk route `tu.arsip-dokumen.index`, yang juga tidak ditemukan. | Controller menyimpan ke disk `public`, menyediakan preview PDF, download melalui `Storage::disk('public')`, update metadata, dan menghapus file fisik. Validasi upload mensyaratkan PDF/DOC/DOCX dan maksimum `50000` KB. | **FAKTA KODE:** `app/Http/Controllers/Tu/ArsipDokumenController.php:16-135`; migration `2025_12_05_173923_init_all_tables.php:135-140`; `app/Models/ArsipDokumen.php:7-22`; view yang tersedia terinventarisasi lewat pencarian `resources/views/tu/surat/`. **Status: parsial/belum terhubung.** Tidak ada pemeriksaan route runtime. |
| SUR-01 | Guru: membuat, melihat, mengubah dan menghapus pengajuan surat. Form memuat kategori, nama, perihal, tujuan serta file draft; daftar menyediakan detail/edit/hapus. | Resource `guru.pengajuan-surat.*` di group `auth`, `role:guru`, `guru.mode`. | `Guru\PengajuanSuratController::store()` memvalidasi field wajib dan `mimes:pdf,doc,docx`, memindahkan file ke `public/uploads/pengajuan-surat`, menyimpan `user_id` dari sesi dan status `draft`. Index memfilter `user_id` pemilik. Detail/edit/update/destroy memakai `findOrFail($id)` tanpa scope user tampak di method; update me-reset status ke draft, membatalkan metadata validasi, dan bila ada file baru mengganti berkas. Hasil berupa redirect/flash. | **FAKTA KODE:** `routes/guru.php:33-36,322`; `resources/views/guru/pengajuan-surat/create.blade.php:25-103`, `index.blade.php:48,101-116`, `edit.blade.php:25-124`, `show.blade.php:38-40`; `app/Http/Controllers/Guru/PengajuanSuratController.php:17-233`; `app/Models/PengajuanSurat.php:7-38`. **Status: parsial.** Validasi mime/ukuran pengganti dan scope kepemilikan pada detail/edit/update/delete tidak tampak; tidak ada request yang dijalankan. |
| SUR-02 | TU: membuat/mengelola pengajuan surat. | GET/POST/PUT/DELETE `/tu/pengajuan-surat...` di group `auth`, `role:tu`, `tu.mode`. | `Tu\PengajuanSuratController` memvalidasi field dan MIME pada pembuatan, menyimpan pengaju (`Auth::id()`) serta role pengirim, file ke `public/uploads/pengajuan-surat`, status `draft`; update mengembalikan status ke draft serta mengosongkan metadata validasi. Daftar TU mengambil seluruh pengajuan; show/edit/update/destroy mencari berdasarkan ID. Hasil redirect/flash. | **FAKTA KODE:** `routes/tu.php:37-45,298-321`; `resources/views/tu/pengajuan-surat/create.blade.php:25-105`, `index.blade.php:48,101-116`, `edit.blade.php:25-126`; `app/Http/Controllers/Tu/PengajuanSuratController.php:18-236`. **Status: parsial.** Form edit menerima file baru, tetapi validasi di method update tidak memuat aturan file; owner scope tidak tampak pada CRUD TU. |
| SUR-03 | Superadmin: meninjau detail pengajuan, menolak atau memvalidasi. UI menampilkan tombol hanya ketika status `pending` atau `draft`; berkas dibuka/diunduh lewat URL aset. | GET daftar/detail; PATCH validasi/tolak; group `auth`, `role:superadmin`. | `ValidasiSuratController` mengambil seluruh record, lalu `validasi()` mengubah status menjadi `disetujui`, menyimpan `validated_by` dan `validated_at`; `tolak()` hanya mengubah status `ditolak`. Tidak tampak validasi request, pemeriksaan status sebelumnya, pengunggahan file final, atau catatan penolakan pada method tersebut. Respons redirect/flash. | **FAKTA KODE:** `routes/superadmin.php:27-30,183-200`; `resources/views/superadmin/pengajuan-surat/index.blade.php:154-183`, `show.blade.php:49-55,165-198`; `app/Http/Controllers/SuperAdmin/ValidasiSuratController.php:17-88`. **Status: parsial.** UI menyebut validasi/ditandatangani, namun penggantian file final tidak terlihat pada alur controller yang diperiksa. |
| SUR-04 | Guru, TU, Siswa, Superadmin: membuka halaman nomor surat, membuat nomor baru, melihat daftar milik sendiri. | GET index dan POST generate pada route group masing-masing; otorisasi mengikuti group role yang tercatat pada route. | Keempat controller menggunakan pola yang sama: membaca record terakhir secara global, mengekstrak urutan dari pola `421.1/{angka}/`, menambah angka, memformat tiga digit, bulan Romawi, tahun dan `SDNMkj`, lalu menyimpan dengan `user_id`, `pengirim`, `status=digunakan`. Index menyaring `user_id` saat ini. Tidak tampak input nomor manual, jenis surat, transaksi/lock pada pengambilan urutan, atau PDF yang terhubung ke nomor. | **FAKTA KODE:** form guru/TU/siswa/Superadmin masing-masing di `resources/views/{guru,tu,siswa,superadmin}/nomor-surat/index.blade.php` (form generate: `:41`, `:41`, `:65`, `:56-57`); routes di atas; controller guru `:16-108`, TU `:17-106`, siswa `:15-91`, Superadmin `:17-102`; `NomorSurat` migration unique `nomor_surat` pada `2026_05_11_224413_create_nomor_surats_table.php:23-45`. **Status: parsial.** Format dan persistensi tampak; keunikan saat permintaan bersamaan dan perilaku runtime belum diperiksa. |

## 5. Temuan teknis yang perlu diverifikasi

| ID | Kategori | Temuan dan bukti | Batas verifikasi |
|---|---|---|---|
| 2F-01 | **FAKTA KODE** | Migration `pengajuan_surats` menetapkan enum status `pending`, `disetujui`, `ditolak` (`2026_05_09_215854_create_pengajuan_surats_table.php:31-35`), sedangkan controller Guru dan TU menyimpan status `draft` (`Guru/PengajuanSuratController.php:76-86`; `Tu/PengajuanSuratController.php:74-88`). UI validasi menganggap `draft` sebagai status yang dapat divalidasi/ditolak (`superadmin/pengajuan-surat/show.blade.php:165-198`). | Tidak dapat memastikan migration yang diterapkan atau status schema aktual karena database tidak diakses. |
| 2F-02 | **FAKTA KODE** | Deklarasi POST `/siswa/nomor-surat` generate dan GET `/siswa/download-dokumen` masing-masing didahului deklarasi GET closure dengan URI/nama yang sama pada route siswa (`routes/siswa.php:61-88`). | Tidak menggunakan `route:list` atau request; resolusi Laravel tidak diuji. |
| 2F-03 | **FAKTA KODE** | Resource `superadmin.nomor-surat` didaftarkan selain route POST generate dan GET index; `NomorSuratController` yang diperiksa hanya mendefinisikan `index()` dan `generate()`. | Keberhasilan route CRUD bawaan tidak diuji. |
| 2F-04 | **FAKTA KODE / INFERENSI TEKNIS** | Migrasi awal `arsip_dokumen` membuat kolom `nama_file`, `file_path`, `kategori`, sedangkan model/controller memakai `nama_surat`, `nomor_surat`, `tanggal_dibuat`, `kategori`, `file_path`, `keterangan`; pencarian migration lain tidak menemukan penyesuaian untuk tabel tersebut. Controller juga merujuk view dan route yang tidak ditemukan. | Tidak menyimpulkan bentuk schema aktual atau efek runtime dari source migration saja. |
| 2F-05 | **FAKTA KODE** | Upload utama dokumen dan pengajuan surat memakai `mimes:pdf,doc,docx` tetapi tidak tampak aturan ukuran maksimum. Pada update berkas di dokumen dan pengajuan surat, aturan MIME/ukuran untuk file baru tidak terlihat dalam validasi method update. Sebaliknya, controller arsip memakai `max:50000`. | Pemeriksaan hanya mencakup controller yang disebut; tidak ada upload yang dilakukan. |
| 2F-06 | **FAKTA KODE / INFERENSI TEKNIS** | Tautan file dokumen/surat menggunakan URL `asset()` ke subdirektori `public/uploads`, sedangkan otorisasi yang tampak diterapkan pada halaman route. Tidak ada controller download yang memeriksa role/pemilik untuk tautan tersebut. | Kemungkinan URL dapat diakses langsung bergantung pada konfigurasi server/storage dan tidak diuji. |
| 2F-07 | **FAKTA KODE** | Daftar pengajuan surat guru dibatasi ke `user_id` sesi, tetapi method show/edit/update/destroy memakai `findOrFail($id)` tanpa filter `user_id` tampak; jalur ini berada dalam route group guru. | Dampak akses aktual tidak diuji dan tidak dinilai sebagai eksploitasi. |
| 2F-08 | **FAKTA KODE** | Migration menambah `validated_file`, tetapi pencarian referensi aplikasi tidak menemukan pemakaian field tersebut. Migration berikut menghapus `file_final`; method validasi tidak menyimpan file validasi/final. | Tidak dapat memastikan kebutuhan bisnis atau pemakaian eksternal field tersebut. |
| 2F-09 | **FAKTA KODE** | Empat controller per-role mengulang pembentukan nomor dengan query `latest()->first()` global, kemudian menyimpan nomor ber-constraint unique; tidak tampak transaksi/locking pada jalur generate. | Risiko tabrakan konkuren merupakan inferensi teknis; tidak diukur atau diuji. |
| 2F-10 | **BELUM DIKETAHUI** | Tidak diketahui apakah folder upload publik, public storage link, dokumen aktual, dan schema production sesuai dengan source repository; tidak diketahui pula apakah ada mekanisme server/deployment tambahan untuk mengamankan berkas. | Di luar pemeriksaan statis dan tanpa membuka data pribadi atau konfigurasi rahasia. |

## 6. Workflow parsial atau belum terpetakan

- **Terpetakan secara statis:** pengelolaan Dokumen sekolah/siswa oleh TU; halaman baca/unduh dokumen untuk guru, siswa, dan Superadmin; pengajuan surat Guru/TU; validasi/penolakan Superadmin; pembuatan dan daftar nomor surat per aktor.
- **Parsial:** akses file download karena tautan melewati aset publik tanpa endpoint berotorisasi; kepemilikan untuk operasi detail/edit/update/delete pengajuan guru; validasi dan batas ukuran untuk file pengganti; hasil status validasi terhadap berkas surat final.
- **Belum terhubung pada route yang ditemukan:** workflow `ArsipDokumenController` dan halaman arsip `tu.surat.*`; route bernama `tu.arsip-dokumen.index` yang direferensikan view preview.
- **Tidak ditemukan pada alur surat yang diperiksa:** pembuatan surat dari template berisi data sekolah/siswa, hasil cetak/PDF khusus surat, pencatatan nomor surat otomatis ke record `PengajuanSurat`, unggah berkas validasi/final, serta relasi pengajuan surat dengan entitas siswa/guru/pegawai selain pengaju `User`.
- **Test:** pencarian test tidak menemukan test khusus dokumen, arsip, pengajuan surat, atau nomor surat. Test tidak dijalankan; tidak ditemukannya test khusus tidak membuktikan fitur tidak berfungsi atau tidak diuji tidak langsung.

## 7. Perbedaan frontend dan backend yang terlihat

1. Upload form meminta/menampilkan PDF, DOC, DOCX; backend validasi create menyebut ekstensi tersebut, tetapi tidak menunjukkan batas ukuran. Validasi file baru tidak tampak pada method update.
2. Link unduhan/preview file dibuat langsung dengan `asset()`, bukan route/controller download.
3. UI Superadmin membuka opsi validasi/penolakan bagi status `draft` atau `pending`, sedangkan migration status hanya menyebut `pending`, `disetujui`, `ditolak`; controller pengaju menyimpan `draft`.
4. Controller validasi mengubah metadata status/validator/waktu, tetapi tidak menghasilkan atau menyimpan file bertanda tangan/final yang dapat ditautkan ke field `validated_file`.
5. Prefix TU/Siswa menampilkan arsip/dokumen, tetapi controller arsip terpisah tidak tersambung pada route yang ditemukan; alur TU aktif menggunakan `DokumenController`.
6. Model/migration ArsipDokumen tidak selaras pada nama field yang dipakai controller.
7. Route siswa mendeklarasikan URI dan nama GET dokumen serta nomor surat lebih dari sekali; Superadmin memakai resource nomor surat walaupun controller yang diperiksa hanya memiliki index dan generate.

## 8. Batas cakupan dan pemeriksaan lanjutan

- Audit bersifat statis. Tidak menjalankan request aplikasi, database/query, migration, seeder, test, build, atau operasi cache.
- Tidak membuka isi dokumen pribadi, file upload, `.env`, credential, atau data pengguna.
- Pemeriksaan file terbatas pada route/controller/model/view/migration yang ditemukan lewat pencarian area dokumen dan surat; deployment, web-server access rules, symlink storage aktual, dan berkas runtime tidak diperiksa.
- Test yang ditemukan pada pencarian terarah hanya menyebut dokumen PPDB pada test JavaScript; tidak ditemukan test khusus workflow surat/dokumen. Test tidak dijalankan.
- Verifikasi lanjutan yang masih diperlukan bila cakupan audit berikutnya disetujui: konfirmasi schema yang benar-benar diterapkan tanpa mengakses data produksi; telusuri asal route/view arsip alternatif; cocokkan status form/controller dengan evolusi migration; dan dokumentasikan jalur download/otorisasi dari konfigurasi yang memang disediakan. Tidak ada usulan implementasi atau perbaikan pada laporan ini.

Audit berhenti pada Tahap 2F. Notifikasi dan Activity Log tidak diperiksa.
