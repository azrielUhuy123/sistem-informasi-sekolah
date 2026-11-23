# SINERGI-MJ — Audit Tahap 2E: Workflow Ekstrakurikuler

## 1. Metadata dan baseline

| Item | Hasil |
|---|---|
| Waktu audit | 9 Oktober 2026, 14:06:42 WIB |
| Branch | `main` |
| Commit aktif | `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` — `SINERGI-MJ UPDATE` |
| Status Git sebelum laporan | `M docs/audit/TAHAP_1_INVENTARIS.md`; laporan Tahap 2, 2B, 2C, dan 2D berstatus untracked. Tidak tampak perubahan file aplikasi pada status tersebut. |
| Perubahan Tahap 2E sebelum laporan | Tidak ada perubahan file yang dibuat. |
| Status Git sesudah laporan | Perubahan lokal terdahulu tetap ada dan laporan Tahap 2E tercatat sebagai file untracked baru; tidak tampak perubahan file aplikasi. Branch dan commit tetap sama. |
| Pemeriksaan | Pembacaan source, route, view, migration, model, dan test secara statis. Tidak ada aplikasi atau database yang dijalankan. |

Perubahan dokumentasi yang telah ada diperlakukan sebagai bagian baseline lokal dan tidak dibatalkan atau ditimpa. Status sesudah laporan dicatat pada penyelesaian audit.

## 2. Referensi audit terdahulu

Kelima dokumen yang diminta ditemukan dan dibaca:

- `docs/audit/TAHAP_1_INVENTARIS.md`
- `docs/audit/TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md`
- `docs/audit/TAHAP_2B_WORKFLOW_TU_AKADEMIK.md`
- `docs/audit/TAHAP_2C_WORKFLOW_PENJADWALAN.md`
- `docs/audit/TAHAP_2D_WORKFLOW_GURU_SISWA.md`

Laporan ini mempersempit pemeriksaan ke ekstrakurikuler dan tidak mengulang inventaris umum atau pemetaan akademik pada laporan-laporan tersebut.

## 3. Inventaris komponen yang ditemukan

### Route dan aktor

| Aktor | Area route dan pembatasan yang tampak | Bukti |
|---|---|---|
| TU | Group TU memakai `auth`, `role:tu`, dan `tu.mode`. Resource `ekskul` disediakan pada group ini. | `routes/tu.php:38-42,340-343` |
| Guru/pembina | Group guru memakai `auth`, `role:guru`, `guru.mode`; route dashboard, anggota, jurnal, catatan, jadwal, dan validasi ekskul berada di dalamnya. | `routes/guru.php:35-36,86-112,154-164,334-384` |
| Siswa | Route daftar, detail, dan absensi ekskul berupa GET; tidak tampak route pendaftaran/permohonan anggota. | `routes/siswa.php:46-59` |
| Superadmin | Group memakai `auth`, `role:superadmin`; memuat penetapan pembina, monitoring/persetujuan jadwal, dan jadwal final. | `routes/superadmin.php:27-28,137-149,225-252` |

### Model, entitas, dan view

| Komponen | Bukti relasi / pemakaian |
|---|---|
| `Ekskul` | Model memiliki relasi guru, anggota, dan pembina. `app/Models/Ekskul.php:7-8,28-52` |
| `EkskulSiswa` | Model memakai tabel `ekskul_siswa` dan memiliki relasi siswa serta ekskul. `app/Models/EkskulSiswa.php:7-25` |
| `AnggotaEkskul` | Model terpisah dengan relasi siswa dan ekskul; pencarian penggunaan class hanya menemukan deklarasi model, tidak menemukan pemakaian class di controller/route yang diperiksa. `app/Models/AnggotaEkskul.php:7-37` |
| `PembinaEkskul` | Model memakai `pembina_ekskul` serta memiliki relasi guru dan ekskul. `app/Models/PembinaEkskul.php:7-32` |
| Presensi, jurnal, pembinaan | Model `AbsensiEkskul`, `JurnalEkskul`, dan `CatatanPembinaanEkskul` menunjuk tabel masing-masing serta relasi siswa/ekskul yang tersedia pada model. `app/Models/AbsensiEkskul.php:7-27`; `app/Models/JurnalEkskul.php:7-22`; `app/Models/CatatanPembinaanEkskul.php:9-34` |
| Pengajuan jadwal | `PengajuanJadwalEkskul` memakai tabel `pengajuan_jadwal_ekskul`, dengan relasi ekskul, guru, hari, dan tahun ajaran. `app/Models/PengajuanJadwalEkskul.php:7-41` |
| Halaman | View TU `resources/views/tu/ekskul/`; guru `resources/views/guru/ekskul/`; siswa `resources/views/siswa/ekskul/`; monitoring Superadmin `resources/views/superadmin/monitoring-ekskul/`. View pembina mencakup dashboard, jadwal, anggota, presensi, jurnal, catatan pembinaan, dan validasi. |

Migration yang ditemukan untuk entitas tersebut:

- `database/migrations/2026_05_29_001215_create_ekskuls_table.php`
- `database/migrations/2026_05_29_015016_create_pembina_ekskul_table.php`
- `database/migrations/2026_05_29_003638_create_ekskul_siswa_table.php`
- `database/migrations/2026_05_29_001222_create_anggota_ekskuls_table.php`
- `database/migrations/2026_05_29_060705_create_absensi_ekskul_table.php`
- `database/migrations/2026_05_29_064826_create_jurnal_ekskul_table.php`
- `database/migrations/2026_05_30_140219_create_catatan_pembinaan_ekskul_table.php`
- `database/migrations/2026_06_01_113833_create_pengajuan_jadwal_ekskul_table.php`
- `database/migrations/2026_08_29_000001_add_unique_constraint_to_ekskul_siswa_table.php`

Nama file migration diinventarisasi; migration tidak dijalankan. Constraint unique pada relasi siswa-ekskul-tahun tercatat di migration terakhir; struktur database aktual yang berjalan tidak diverifikasi.

## 4. Tabel workflow

Status **lengkap** berarti rantai implementasi statis yang tersedia dapat ditelusuri pada komponen yang diperiksa; tidak menyatakan alur berhasil dieksekusi. **Parsial** berarti ada bagian rantai atau batasannya yang tidak ditemukan.

| ID | Modul / aktor | UI dan tindakan | Route / middleware | Controller, validasi, persistensi, respons | Status dan batasan |
|---|---|---|---|---|---|
| EXK-01 | Master kegiatan — TU | Daftar, buat, edit, hapus. Form create/edit mengirim nama, guru, hari, waktu, kuota, status, dan deskripsi. `resources/views/tu/ekskul/index.blade.php:48,138-144`; `create.blade.php:17-75`; `edit.blade.php:18-77` | Resource `ekskul` dalam group TU `auth`, `role:tu`, `tu.mode`; `routes/tu.php:38-42,340-343` | `Tu\EkskulController` menyediakan `index/create/store/edit/update/destroy`; `store()` dan `update()` memanggil `Ekskul::create($request->all())` / `$ekskul->update($request->all())`, kemudian redirect. `app/Http/Controllers/Tu/EkskulController.php:12-82` | **Lengkap** untuk CRUD yang tampak. Validasi eksplisit tidak tampak pada `store/update`; tidak diuji saat audit. |
| EXK-02 | Penetapan pembina — Superadmin | Halaman penetapan pembina untuk ekskul; simpan/hapus penugasan. | Route index/store/destroy di group Superadmin; `routes/superadmin.php:27-28,137-149` | `PembinaEkskulController` memvalidasi dan menolak duplikasi untuk tahun aktif, kemudian membuat relasi `guru_id`, `ekskul_id`, `tahun_ajaran_id`; penghapusan mencari record berdasarkan ID. `app/Http/Controllers/SuperAdmin/PembinaEkskulController.php:13-29,52-108,124-127` | **Lengkap** untuk rute dan persistensi yang ditemukan. Jangkauan validasi/hak penghapusan tidak diperiksa di luar method tersebut. |
| EXK-03 | Dashboard dan daftar anggota — guru/pembina | Dashboard menautkan anggota, jurnal, presensi, dan pembinaan. Guru memilih siswa pada halaman anggota lalu submit pilihan massal; daftar anggota mempunyai aksi hapus. `resources/views/guru/ekskul/dashboard.blade.php:43,53,290,326,362,398`; `anggota/index.blade.php:74,146`; `anggota/daftar.blade.php:117` | Route anggota/index/store/daftar/destroy di group guru. `routes/guru.php:102-128` | Controller route aktif `EkskulAnggotaController` memeriksa penugasan pembina dan kuota, lalu `EkskulSiswa::firstOrCreate`; anggota yang dibuat berstatus aktif. Aksi hapus mengecek akses pembina/keanggotaan. `app/Http/Controllers/Guru/EkskulAnggotaController.php:16-39,95-179,197-238,287-307` | **Lengkap** untuk penambahan langsung oleh pembina dan penghapusan anggota. Tidak ditemukan persetujuan mandiri siswa pada route siswa. |
| EXK-04 | Pendaftaran/approval anggota — siswa/pengelola | View siswa menampilkan daftar “Ekskul Saya” dan link detail/presensi, bukan form pendaftaran. `resources/views/siswa/ekskul/index.blade.php:104-111` | Hanya route GET index/show/absensi yang ditemukan pada group siswa. `routes/siswa.php:46-59` | Route aktif memakai `EkskulSiswa`; model `AnggotaEkskul` dan migration `anggota_ekskuls` terpisah. Penggunaan class `AnggotaEkskul` tidak ditemukan pada controller/route dalam cakupan pencarian. Migration `anggota_ekskuls` mendefinisikan status `pending/diterima/ditolak`; alur utama `ekskul_siswa` menggunakan status `pending/aktif/keluar`, dan controller pembina membuat anggota berstatus `aktif`. | **Belum ditemukan** untuk pendaftaran oleh siswa dan approval anggota pada jalur route/view yang diperiksa. Perbedaan model/tabel/status perlu ditelusuri; penggunaan tabel lama di luar cakupan ini belum dapat dipastikan. |
| EXK-05 | Pengajuan jadwal — guru/pembina | Form jadwal membuat draft; halaman status validasi dan edit menawarkan perubahan/hapus jadwal. `resources/views/guru/ekskul/jadwal/index.blade.php:45,157-165`; `jadwal/edit.blade.php:44` | Route index, store, submit, status-validasi, edit, update, destroy. `routes/guru.php:344-384` | `JadwalEkskulController::store()` memvalidasi field dasar dan membuat `draft`; `update()` mengunci record, memeriksa kepemilikan guru/status, lalu mengubah menjadi `submitted` dan menghapus catatan; `destroy()` punya pemeriksaan akses. `app/Http/Controllers/Guru/Ekskul/JadwalEkskulController.php:57-91,101-120,132-212` | **Parsial**: route `POST /ekskul/jadwal/submit` menunjuk method `submit`, tetapi tidak ada method `submit()` dalam controller tersebut; method yang terlihat mengirim pengajuan adalah `update()`. Test jadwal juga mengirim melalui update, bukan endpoint submit. `routes/guru.php:354-356`; `tests/Feature/EkskulJadwalWorkflowTest.php:20-69` |
| EXK-06 | Review dan publikasi jadwal — Superadmin | Monitoring ekskul, melihat status/bentrok, mengisi alasan penolakan; aksi setujui/tolak/publish. `resources/views/superadmin/monitoring-ekskul/show.blade.php:70-110,337-398` (kontrol aksi dan modal reject) | Route show/approve/reject/publish serta jadwal final. `routes/superadmin.php:225-252` | `MonitoringEkskulController` mengevaluasi bentrok, menjalankan approve/reject/publish dalam transaksi dan mengubah status pengajuan; approve menetapkan `approved`, reject menetapkan `rejected` dan catatan, publish menetapkan `published`. Pemeriksaan bentrok membandingkan jadwal ekskul dengan jadwal akademik/ekskul. `app/Http/Controllers/SuperAdmin/MonitoringEkskulController.php:30-71,73-173,175-286` | **Parsial**: alur persistensi/status dapat ditelusuri. View membatasi tombol berdasarkan `$bisaApprove` yang terkait hasil bentrok; implikasi terhadap tombol tolak dan status tiap pengajuan belum diverifikasi dengan menjalankan aplikasi. |
| EXK-07 | Presensi kegiatan — guru/pembina | Memilih tindakan massal status kehadiran pada peserta dan menyimpan presensi untuk tanggal terpilih. `resources/views/guru/ekskul/absensi/index.blade.php:132-160,258-338` | Route index/store dan rekap dalam group guru. `routes/guru.php:132-151` | Controller memvalidasi tanggal dan struktur absensi, mencari ekskul yang dibina pada tahun aktif, lalu `updateOrCreate` per anggota/tanggal. `app/Http/Controllers/Guru/Ekskul/AbsensiEkskulController.php:18-76,87-160`; rekap `:171-404`. | **Parsial**: alur simpan terpetakan. Validitas ID/status setiap elemen absensi dan kesesuaian query rekap dengan tahun ajaran belum terpastikan dari cakupan validasi. View mengisi status hadir sebagai default bagi data tanpa record—ini perilaku tampilan, bukan bukti presensi pernah dicatat. |
| EXK-08 | Jurnal kegiatan — guru/pembina | Form tambah jurnal dan tombol hapus record. `resources/views/guru/ekskul/jurnal/index.blade.php:56,74,125,211` | Route GET/POST/DELETE di group guru. `routes/guru.php:154-164` | `JurnalEkskulController` memvalidasi data simpan, mencari penugasan pembina, membuat jurnal; `destroy()` mencari jurnal berdasarkan ID sebelum menghapus. `app/Http/Controllers/Guru/Ekskul/JurnalEkskulController.php:16-41,45-88,91-100` | **Parsial**: simpan dan hapus terpetakan. Pemeriksaan kepemilikan pada method hapus tidak tampak pada method yang diperiksa; dampak akses tidak diuji. |
| EXK-09 | Catatan pembinaan — guru/pembina | Form menyimpan kehadiran, partisipasi, perkembangan, dan catatan dengan `ekskul_id` serta `siswa_id` tersembunyi. `resources/views/guru/ekskul/catatan/index.blade.php:206-219,246-288` | Route index/store di group guru. `routes/guru.php:334-343` | Index menyajikan anggota ekskul binaan; `store()` memvalidasi field dan memakai `updateOrCreate`. `app/Http/Controllers/Guru/Ekskul/CatatanPembinaanController.php:13-49,51-95` | **Parsial**: form dan persistensi terpetakan. Pemeriksaan hubungan kepemilikan guru dengan pasangan ekskul/siswa pada method `store()` tidak tampak; belum dieksekusi. |
| EXK-10 | Informasi ekskul dan absensi — siswa | Daftar kegiatan yang diikuti, lalu buka detail atau riwayat absensi. `resources/views/siswa/ekskul/index.blade.php:104-111`; `detail.blade.php:162,214`; `absensi.blade.php:31,77,263` | Route GET index/show/absensi dalam route siswa. `routes/siswa.php:46-59` | Controller index membaca keanggotaan aktif siswa login; `show()` dan `absensi()` mencari data berdasarkan ekskul dan siswa login serta menghitung statistik. `app/Http/Controllers/Siswa/EkskulController.php:13-38,41-90,94-175` | **Parsial**: baca data terpetakan. Pada method show/absensi yang diperiksa tidak tampak pemeriksaan eksplisit bahwa siswa login adalah anggota ekskul yang diminta; akses nyata dan dampak belum diuji. |

## 5. Temuan teknis yang perlu diverifikasi

| ID | Kategori | Temuan berbasis source | Bukti / batasan |
|---|---|---|---|
| EXK-T01 | FAKTA KODE | Route submit jadwal guru menunjuk `JadwalEkskulController::submit`, sedangkan class yang diperiksa menyediakan `index/store/edit/update/destroy` dan tidak menyediakan method `submit`. Jalur pengajuan yang dirujuk test adalah `PUT update`. | `routes/guru.php:354-356`; `app/Http/Controllers/Guru/Ekskul/JadwalEkskulController.php:17,57,101,132,203`; `tests/Feature/EkskulJadwalWorkflowTest.php:20-69`. Route tidak dipanggil saat audit. |
| EXK-T02 | FAKTA KODE | Terdapat dua representasi keanggotaan: `anggota_ekskuls` dengan status `pending/diterima/ditolak` dan `ekskul_siswa` dengan status `pending/aktif/keluar`. Controller route aktif yang ditemukan menggunakan `EkskulSiswa`, dan penambahan oleh pembina membuat status `aktif`. | `database/migrations/2026_05_29_001222_create_anggota_ekskuls_table.php`; `database/migrations/2026_05_29_003638_create_ekskul_siswa_table.php`; `app/Http/Controllers/Guru/EkskulAnggotaController.php:179`; `app/Models/EkskulSiswa.php:7-25`. Migration tidak dijalankan. |
| EXK-T03 | FAKTA KODE | Sebagian alur dashboard dan presensi memakai tahun aktif, tetapi beberapa pemilihan pembina mengambil record berdasarkan `guru_id` saja tanpa filter `tahun_ajaran_id`; rekap presensi juga memilih penugasan dengan `guru_id` saja. | `app/Http/Controllers/Guru/EkskulController.php:32-39`; `app/Http/Controllers/Guru/EkskulAnggotaController.php:29-39`; `app/Http/Controllers/Guru/Ekskul/JurnalEkskulController.php:20-25,59-63`; `app/Http/Controllers/Guru/Ekskul/CatatanPembinaanController.php:17-25`; `app/Http/Controllers/Guru/Ekskul/AbsensiEkskulController.php:171-196`; filter tahun aktif pada alur presensi index/store `:52-54,121-123`. Batas dampak data historis belum diketahui. |
| EXK-T04 | FAKTA KODE | Pada view monitoring, kontrol approve/tolak/publish ditempatkan pada kondisi `$bisaApprove`; penolakan di backend merupakan route/method terpisah yang mengubah pengajuan `submitted` menjadi `rejected`. | `resources/views/superadmin/monitoring-ekskul/show.blade.php:70-110,337-398`; `routes/superadmin.php:234-247`; `app/Http/Controllers/SuperAdmin/MonitoringEkskulController.php:57-68,111-136`. Perilaku UI saat konflik belum diuji. |
| EXK-T05 | FAKTA KODE | Method hapus jurnal mengambil record menurut ID; pemeriksaan penugasan/pemilik tidak tampak di method `destroy()` yang diperiksa. Method simpan catatan pembinaan menerima ID ekskul/siswa dari form dan melakukan validasi field, namun hubungan kepemilikan tidak tampak diperiksa di method simpan tersebut. | `app/Http/Controllers/Guru/Ekskul/JurnalEkskulController.php:91-100`; `app/Http/Controllers/Guru/Ekskul/CatatanPembinaanController.php:51-95`; form `resources/views/guru/ekskul/catatan/index.blade.php:214-219`. Pemeriksaan middleware, helper, dan hasil request tidak diuji. |
| EXK-T06 | FAKTA KODE | Siswa melihat hanya keanggotaan berstatus aktif pada halaman index; show/absensi menerima route-bound ekskul dan query statistik per siswa, namun pemeriksaan keanggotaan eksplisit pada method tersebut tidak ditemukan. | `app/Http/Controllers/Siswa/EkskulController.php:13-38,41-90,94-175`; `routes/siswa.php:46-59`. Hanya analisis statis, bukan pengujian akses. |
| EXK-T07 | FAKTA KODE | View siswa/controller menggunakan label hitung `alpa`, sementara skema presensi yang diinventarisasi menggunakan nilai `alpha`; kesesuaian nilai yang benar-benar tersimpan dengan query statistik perlu dicocokkan. | `app/Http/Controllers/Siswa/EkskulController.php:151-160`; `resources/views/siswa/ekskul/absensi.blade.php:11-14`; migration `database/migrations/2026_05_29_060705_create_absensi_ekskul_table.php:28-32`. Tidak ada query database yang dijalankan. |
| EXK-T08 | INFERENSI TEKNIS | Tabel/model anggota lama mungkin merupakan sisa jalur sebelumnya atau jalur lain yang belum ditemukan, sebab class `AnggotaEkskul` tidak muncul pada controller/route dalam cakupan pencarian, sementara workflow aktif menggunakan `EkskulSiswa`. | `app/Models/AnggotaEkskul.php:7-37`; route/controller anggota aktif pada `routes/guru.php:102-128` dan `app/Http/Controllers/Guru/EkskulAnggotaController.php`. Kepastian maksud desain memerlukan pemeriksaan lanjutan. |
| EXK-T09 | BELUM DIKETAHUI | Tidak dapat dipastikan apakah tabel/skema yang telah dimigrasikan di lingkungan mana pun sama dengan migration repository, atau apakah endpoint yang berpotensi merujuk method tidak tersedia pernah digunakan. | Tidak ada akses/pemeriksaan database atau request aplikasi dalam audit ini. |

## 6. Workflow parsial dan belum terpetakan

### Terpetakan

- CRUD master kegiatan oleh TU.
- Penetapan pembina oleh Superadmin.
- Penambahan massal/penghapusan anggota oleh guru pembina.
- Draft, edit, pengajuan, review, penolakan, persetujuan, dan publikasi jadwal.
- Tampilan presensi, penyimpanan dan rekap oleh pembina.
- Simpan/hapus jurnal; simpan catatan pembinaan.
- Tampilan detail/riwayat absensi ekstrakurikuler bagi siswa.

### Parsial atau tidak ditemukan pada cakupan route/view yang diperiksa

- Pendaftaran mandiri siswa, approval/penolakan keanggotaan siswa, atau mekanisme peserta mengajukan keluar: **belum ditemukan** pada route siswa dan view siswa yang ditelusuri.
- Pembatasan pendaftaran siswa berdasarkan kuota atau tahun ajaran: kuota tampak digunakan pada penambahan anggota oleh guru; alur aplikasi mandiri siswa tidak ditemukan.
- Pengelolaan/pengubahan master pembina langsung melalui kegiatan TU versus relasi penugasan terpisah: kedua representasi tampak, aturan sinkronisasi tidak diketahui.
- Jadwal kegiatan terjadwal rutin, perubahan/pembatalan jadwal final oleh guru, dan peringatan perubahan kepada siswa: belum ditemukan bukti lengkap. Route edit guru menolak status approved/published dalam test yang tersedia; test tidak dijalankan.
- Persetujuan anggota kegiatan, daftar tunggu, dan batas maksimum peserta sebagai aturan lintas semua jalur: belum dapat dipastikan.
- Hubungan jurnal/pembinaan dengan semester/tahun ajaran: tidak tampak lengkap di semua alur yang diperiksa.
- Daftar test fitur ekskul lengkap selain dua file feature yang ditemukan belum dipastikan melalui penghitungan seluruh test suite.

## 7. Perbedaan frontend dan backend yang terlihat

1. **Route submit jadwal vs method controller**: nama endpoint submit memetakan method yang tidak ditemukan, sementara form/test menggunakan update untuk mengubah draft menjadi submitted.
2. **UI keanggotaan vs endpoint siswa**: view siswa hanya menyediakan akses detail/absensi kegiatan yang sudah diikuti; route siswa yang ditemukan hanya GET. Label “pendaftaran anggota” muncul di halaman pembina, tempat guru memilih peserta.
3. **Representasi status anggota**: satu migration mendefinisikan `pending/diterima/ditolak`, sedangkan controller aktif menambahkan `EkskulSiswa` sebagai `aktif`; penggunaan model lama tidak terhubung pada jalur yang ditemukan.
4. **Status statistik absensi**: ejaan `alpa` pada hitungan aplikasi perlu dibandingkan dengan nilai `alpha` pada skema.
5. **Tindakan monitoring**: UI menggunakan hasil deteksi bentrok untuk menentukan tersedianya kontrol tindakan; backend memiliki endpoint aksi tersendiri. Hubungan kondisi UI dengan masing-masing status belum dapat dipastikan tanpa eksekusi.

## 8. Batas cakupan dan audit lanjutan

- Semua kesimpulan bersumber dari pembacaan statis repo pada commit dan status lokal yang dicatat di atas.
- Tidak dilakukan request aplikasi, eksekusi test, build, migration, seeder, query database, atau pemeriksaan data pengguna.
- Dua test terkait yang ditemukan: `tests/Feature/EkskulAbsensiTest.php` dan `tests/Feature/EkskulJadwalWorkflowTest.php`. Test jadwal mencakup draft-submit lewat edit, validasi, resubmit, kepemilikan jadwal, approval/rejection/publish, dan konflik; test presensi/catatan mencakup halaman ketika anggota terhapus. **Daftar test ini hanya dibaca; tidak dijalankan.**
- Pemeriksaan berikutnya yang dapat memperjelas temuan statis: telusuri seluruh rujukan route/view bagi `AnggotaEkskul`, verifikasi rute submit terhadap implementasi controller yang benar-benar dimaksud, periksa hubungan tahun ajaran pada semua query anggota/presensi/jurnal/catatan, dan tinjau batas otorisasi jalur hapus/simpan dengan konteks middleware lengkap. Ini adalah cakupan verifikasi, bukan rekomendasi perubahan workflow.
- Modul TU, penjadwalan, guru, siswa, dan e-learning di luar ekstrakurikuler tetap mengikuti cakupan laporan terdahulu; audit ini tidak memulai Tahap 2F.
