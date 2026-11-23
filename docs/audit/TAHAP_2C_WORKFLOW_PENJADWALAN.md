# SINERGI-MJ — Audit Tahap 2C: Workflow Penjadwalan Akademik

## 1. Metadata dan baseline audit

| Item | Hasil |
|---|---|
| Waktu audit | 10 Oktober 2026, 13:21:31 WIB |
| Branch | `main` |
| Commit aktif | `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` — `SINERGI-MJ UPDATE` |
| Waktu commit terakhir | 9 Oktober 2026, 12:30:29 WIB |
| Status baseline sebelum laporan dibuat | `main...origin/main`; `M docs/audit/TAHAP_1_INVENTARIS.md`; `?? docs/audit/TAHAP_2B_WORKFLOW_TU_AKADEMIK.md`; `?? docs/audit/TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md` |
| Perubahan aplikasi pada baseline | Tidak ada perubahan file aplikasi yang tampak pada `git status` |
| Perubahan baru oleh audit ini | Hanya file laporan ini; status akhir diperiksa setelah pembuatan |

Status lokal di atas adalah baseline yang benar-benar terlihat saat audit ini dimulai; laporan sebelumnya tidak diasumsikan sebagai pengganti pemeriksaan Git. Dibanding snapshot status yang tersedia dari Tahap 2B, tiga path lokal tersebut tetap tercatat dan tidak tampak perubahan source aplikasi baru. Audit ini tidak membatalkan, menimpa, atau merapikan perubahan yang sudah ada.

## 2. Referensi audit sebelumnya

Laporan berikut dibaca sebagai peta awal dan tidak diubah:

- `docs/audit/TAHAP_1_INVENTARIS.md` — struktur sistem, komponen, dan inventaris awal.
- `docs/audit/TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md` — Tahap 2 sebelumnya secara eksplisit belum memetakan workflow penjadwalan (`:123`, `:127-130`).
- `docs/audit/TAHAP_2B_WORKFLOW_TU_AKADEMIK.md` — Tahap 2B mencatat bahwa penjadwalan final dan relasi mata pelajaran memerlukan audit tersendiri (`:110-112`).

Setiap referensi kode untuk laporan ini ditelusuri ulang terhadap repositori saat ini.

## 3. Inventaris halaman, route, controller, model, dan komponen terkait

| Komponen | Bukti kode | Catatan pemetaan |
|---|---|---|
| Halaman penyusunan jadwal guru | `resources/views/guru/jadwal-saya/index.blade.php:60-180,234-313` | Memilih kelas/mapel, menampilkan total JP/status slot, mengirim submit, dan menghapus slot. |
| Route jadwal guru utama | `routes/guru.php:33-36,173-196` | Group guru menggunakan `auth`, `role:guru`, dan `guru.mode`; route utama menyediakan GET index, POST pilih slot, DELETE hapus, POST submit, serta resource pengajuan terpisah. |
| Pengendali jadwal guru utama | `app/Http/Controllers/Guru/JadwalSayaController.php:22,140-220,222-301,311-402` | Memuat opsi, memeriksa assignment, membuat draft, menghapus draft/rejected, dan mengirim beberapa slot. |
| Resource pengajuan jadwal terpisah | `app/Http/Controllers/Guru/PengajuanJadwalController.php:16-57`; `resources/views/guru/pengajuan-jadwal/create.blade.php:16-105` | Jalur resource kedua membuat draft dengan validasi berbeda dari grid `Jadwal Saya`. |
| Monitoring dan keputusan | `routes/superadmin.php:27,68-90`; `resources/views/superadmin/monitoring-jadwal/index.blade.php:145`; `resources/views/superadmin/monitoring-jadwal/show.blade.php:51-68,383-466`; `app/Http/Controllers/SuperAdmin/MonitoringJadwalController.php:17-109,110-290` | Daftar/monitor kelas, approve batch submitted, reject per slot, serta publish. Middleware yang tertera adalah `role:superadmin`; bukti route tidak menyebut role “Kepala Sekolah”. |
| Jadwal final TU dan Superadmin | `routes/tu.php:182-188`; `routes/superadmin.php:152-158`; `app/Http/Controllers/Tu/JadwalFinalController.php:13-58`; `app/Http/Controllers/SuperAdmin/JadwalFinalController.php:13-58` | Kedua controller membaca pengajuan berstatus `published` untuk tahun yang dipilih oleh helper dan menyediakan endpoint export. |
| Jadwal siswa | `routes/siswa.php:16-29`; `app/Http/Controllers/Siswa/JadwalController.php:14-154`; `resources/views/siswa/jadwal/index.blade.php` | Menentukan penempatan siswa pada tahun yang diminta, lalu membentuk grid dari pengajuan yang sudah `published`. Route yang terlihat hanya memakai middleware `auth`. |
| Entitas pengajuan | `app/Models/PengajuanJadwal.php:8-61`; `database/migrations/2026_05_07_092325_create_pengajuan_jadwal_table.php:11-47`; `database/migrations/2026_05_14_161859_alter_status_pengajuan_jadwal_table.php:10-21` | Model dan tabel menyimpan guru, kelas, mapel, hari, jam pelajaran, tahun ajaran, status, dan catatan. Migrasi lanjutan menambahkan status `published`. Tidak terlihat kolom ruang atau semester pada schema pengajuan tersebut. |
| Tahun ajaran dan kuota JP | `app/Helpers/TahunHelper.php:6-32`; `database/seeders/KelasMasterSeeder.php:9-13` | Helper memilih `ta` dari request jika tersedia lalu fallback ke tahun aktif. Helper kuota kelas mencocokkan nilai tingkat numerik; seeder tingkat kelas mengisi angka Romawi. |
| Jejak jadwal legacy | `app/Actions/Jadwal/GetJPStatus.php:8-57`; `app/Http/Controllers/Tu/JadwalGrupController.php:14-18,89-101,148`; pencarian route pada `routes/*.php` | Masih ditemukan action/controller dan entitas `Jadwal`/`JadwalGrup` terpisah. Pencarian pada file route tidak menemukan route ke `JadwalGrupController` atau pemanggil action tersebut; hubungan operasionalnya belum dipastikan. |

## 4. Workflow yang berhasil dipetakan

Label **FAKTA KODE** berarti perilaku berikut ditemukan langsung di source. Label **INFERENSI TEKNIS** menunjukkan interpretasi statis yang belum dikonfirmasi dengan request atau database. **BELUM DIKETAHUI** berarti bukti repositori yang diperiksa tidak cukup.

| ID workflow | Aktor dan halaman | Tindakan pengguna | Route / middleware | Controller, validasi, persistensi, hasil | Bukti dan status pemetaan |
|---|---|---|---|---|---|
| 2C-WF-01 | Guru; `guru/jadwal-saya/index` | Memilih kelas, mapel, hari, dan jam untuk menambah satu slot draft. | `POST guru.jadwal-saya.pilih-slot`; group `auth`, `role:guru`, `guru.mode`. | `JadwalSayaController::pilihSlot` memvalidasi ID dan keberadaan kelas/mapel/hari/jam, mengecek akses guru ke kelas dan assignment mapel, mencegah kelas yang telah dipublish dan slot kelas-hari-jam yang sudah terisi, menghitung kuota mapel dan kelas, lalu membuat `PengajuanJadwal` berstatus `draft`. Respons berupa redirect dengan pesan. | **FAKTA KODE:** `routes/guru.php:33-36,179-181`; `JadwalSayaController.php:140-220`; view `guru/jadwal-saya/index.blade.php:60-159,291-313`; model/migrasi `PengajuanJadwal.php:8-21`, `2026_05_07_092325_create_pengajuan_jadwal_table.php:11-47`. **Pemetaan: lengkap secara statis.** Tidak diverifikasi melalui request. |
| 2C-WF-02 | Guru; grid Jadwal Saya | Mengirim semua slot draft/rejected milik guru untuk kelas yang dipilih. | `POST guru.jadwal-saya.submit`; middleware group guru yang sama. | `submitJadwal` memvalidasi kelas, mengecek akses guru dan status published, lalu dalam transaksi mengambil slot milik guru/kelas/tahun berstatus `draft` atau `rejected` dengan `lockForUpdate`, mengubahnya menjadi `submitted`, dan memanggil notifikasi bisnis. Jika tidak ada slot yang dapat dikirim, tidak ada perubahan; hasil berupa redirect/pesan. | **FAKTA KODE:** `routes/guru.php:189-191`; `JadwalSayaController.php:248-301`; view `guru/jadwal-saya/index.blade.php:161-180`; test yang menyebut batch submit `tests/Feature/BusinessNotificationTest.php:173-202` (tidak dijalankan). **Pemetaan: lengkap secara statis.** |
| 2C-WF-03 | Guru; grid Jadwal Saya | Menghapus satu slot yang masih draft atau ditolak. | `DELETE guru.jadwal-saya.hapus-slot`; middleware group guru yang sama. | `hapusSlot` membatasi pencarian ke guru dan tahun yang dipilih. Slot `published` atau status selain `draft`/`rejected` ditolak; selain itu record dihapus dan redirect menampilkan pesan. | **FAKTA KODE:** `routes/guru.php:184-186`; `JadwalSayaController.php:222-245`; view `guru/jadwal-saya/index.blade.php:268-289`. **Pemetaan: lengkap secara statis.** Tidak ditemukan route di jalur ini untuk mengedit slot `submitted`/`approved` atau membatalkan pengajuan setelah dikirim. |
| 2C-WF-04 | Guru; halaman resource `guru/pengajuan-jadwal` | Membuka daftar/tombol tambah dan membuat pengajuan jadwal lain. | `Route::resource('pengajuan-jadwal', ...)` di dalam group guru; resource menurunkan method HTTP standar Laravel. | `index()` mengambil `PengajuanJadwal::latest()->get()` tanpa filter guru yang tampak. `store()` hanya memvalidasi bahwa empat input wajib terisi, menggunakan `auth()->user()->guru_id`, menyimpan tahun aktif dan status `draft`, lalu redirect. Tidak tampak validasi `exists`, pemeriksaan assignment, konflik, atau kuota di method ini. | **FAKTA KODE:** `routes/guru.php:193-196`; `PengajuanJadwalController.php:16-57`; form `guru/pengajuan-jadwal/create.blade.php:16-105`; daftar `guru/pengajuan-jadwal/index.blade.php:11-56`. **Pemetaan: parsial.** Hubungan antara jalur resource ini dan alur grid utama belum dapat dipastikan. |
| 2C-WF-05 | Superadmin; monitoring jadwal dan detail kelas | Melihat daftar/status, menyetujui seluruh pengajuan submitted di kelas-tahun, atau menolak pengajuan submitted pada hari/jam tertentu dengan alasan. | GET index/show, POST approve/reject; route berada di `auth`, `role:superadmin`. | `approve()` memilih submitted untuk kelas dan tahun, mengecek `hasConflict`, lalu mengubah status menjadi `approved` dalam transaksi. `reject()` memvalidasi alasan, hari, jam, dan catatan opsional; memilih satu record submitted di slot tersebut, menulis `rejected` dan catatan, lalu memberi respons redirect. | **FAKTA KODE:** `routes/superadmin.php:27,68-85`; monitoring view `:51-52,383-466`; `MonitoringJadwalController.php:17-109,110-230`; migration status `2026_05_14_161859_alter_status_pengajuan_jadwal_table.php:10-21`. **Pemetaan: lengkap secara statis.** Aktor route terbukti `superadmin`; kesetaraan dengan jabatan Kepala Sekolah tidak dapat dipastikan dari alur ini. |
| 2C-WF-06 | Superadmin; monitoring detail kelas | Menerbitkan jadwal kelas yang telah disetujui. | `PATCH superadmin.monitoring-jadwal.publish`; middleware `auth`, `role:superadmin`. | `publish()` menghitung semua record kelas/tahun dan record approved; mensyaratkan jumlah approved sama dengan jumlah record, mengecek konflik per jadwal approved, kemudian mengubah status approved menjadi `published`. Hasil berupa redirect dan pesan. | **FAKTA KODE:** `routes/superadmin.php:88-90`; `monitoring-jadwal/show.blade.php:67-68`; `MonitoringJadwalController.php:232-290`. **Pemetaan: lengkap secara statis.** Persyaratan menghitung semua record berarti record draft/rejected yang masih ada ikut memengaruhi perbandingan jumlah (`:234-249`). |
| 2C-WF-07 | TU dan Superadmin; halaman jadwal final | Membaca jadwal kelas yang telah dipublish dan mengirim form export. | GET `tu.jadwal-final.index` / `superadmin.jadwal-final.index`; POST route export masing-masing di group role terkait. | Kedua controller memfilter relasi `pengajuanJadwal` dengan `tahun_ajaran_id` helper dan status `published`, lalu membentuk data per kelas/hari/jam. Ada form export yang terlihat pada halaman final. | **FAKTA KODE:** `routes/tu.php:182-188`; `routes/superadmin.php:152-158`; `Tu/JadwalFinalController.php:13-58`; `SuperAdmin/JadwalFinalController.php:13-58`; `tu/jadwal-final/index.blade.php:24-25`; `superadmin/jadwal-final/index.blade.php:24-25`. **Pemetaan: parsial.** Pada view TU, action export menunjuk `superadmin.jadwal-final.export` (`tu/jadwal-final/index.blade.php:24`), sedangkan route TU yang terdaftar bernama `tu.jadwal-final.export` (`routes/tu.php:186-188`). Tujuan ekspor tidak diuji runtime. |
| 2C-WF-08 | Siswa; halaman `siswa/jadwal/index` | Melihat jadwal kelas berdasarkan penempatan siswa pada tahun yang dipilih. | `GET siswa.jadwal.index`; route dalam group `auth`. | `JadwalController::index` mengambil siswa dari user, mencari penempatan siswa berdasarkan `current_tahun_id()`, menghentikan proses dengan 404 jika data/penempatan tidak ditemukan, lalu mengambil jadwal `published` untuk kelas dan tahun itu, menyusun grid, dan mengembalikan view. | **FAKTA KODE:** `routes/siswa.php:16-29`; `Siswa/JadwalController.php:14-154`; view `resources/views/siswa/jadwal/index.blade.php`. **Pemetaan: lengkap secara statis.** Pembatasan route yang tampak hanya `auth`; perilaku dengan akun tanpa relasi siswa tampak ditangani di controller (`:22-41`). |

## 5. Pemeriksaan aturan dan relasi tahun/semester

### Konflik dan batas kuota

- **FAKTA KODE:** Pemeriksaan slot pada `pilihSlot()` menolak kombinasi kelas, hari, jam, dan tahun ajaran yang sudah mempunyai record. Query itu tidak memfilter `status` (`app/Http/Controllers/Guru/JadwalSayaController.php:176-186`). Dengan demikian, record berstatus `rejected` yang masih ada juga termasuk dalam pencarian slot. Kuota mapel dan total JP kelas juga menghitung seluruh record tahun tersebut tanpa filter status (`:188-205`).
- **FAKTA KODE:** `hasConflict()` memeriksa guru yang sama, hari/jam yang sama, tahun ajaran yang sama, selain record itu sendiri (`app/Http/Controllers/SuperAdmin/MonitoringJadwalController.php:275-290`). Pemeriksaan ini digunakan pada approve dan publish (`:126-128,255-258`). Tidak ada pemeriksaan ruang pada jalur yang ditemukan.
- **FAKTA KODE:** Schema `pengajuan_jadwal` memiliki `jam_pelajaran_id`, tetapi tidak memiliki `ruangan_id` (`database/migrations/2026_05_07_092325_create_pengajuan_jadwal_table.php:11-47`; `app/Models/PengajuanJadwal.php:12-21`). Form resource dan grid guru juga tidak menampilkan pilihan ruang (`resources/views/guru/pengajuan-jadwal/create.blade.php:16-105`; `resources/views/guru/jadwal-saya/index.blade.php:60-159`).
- **INFERENSI TEKNIS:** Helper maksimum JP menggunakan angka tingkat `1` sampai `6`, dengan default `35` (`app/Helpers/TahunHelper.php:21-32`). Pemanggil memberi nilai `kelasMaster.tingkat` (`JadwalSayaController.php:197-205`), sedangkan `KelasMasterSeeder` mengisi tingkat `'I'` sampai `'VI'` (`database/seeders/KelasMasterSeeder.php:9-13`). Jika nilai yang digunakan memang berasal dari seeder tersebut, cast integer pada helper tidak akan cocok dengan cabang angka 1-6 dan nilai akan jatuh ke default 35. Efek runtime dan isi database tidak diverifikasi.

### Tahun ajaran dan semester

- **FAKTA KODE:** `current_tahun_id()` memilih nilai `request('ta')` bila tersedia, lalu fallback ke `TahunAjaran::aktif()?->id` (`app/Helpers/TahunHelper.php:6-9`). Jalur pilih slot, monitoring, jadwal final, dan siswa memakai helper tersebut; resource `PengajuanJadwalController::store()` langsung menggunakan `TahunAjaran::aktif()->id` (`app/Http/Controllers/Guru/PengajuanJadwalController.php:49-56`).
- **FAKTA KODE:** Record pengajuan mempunyai foreign key tahun ajaran, tetapi tidak mempunyai `semester_id` (`database/migrations/2026_05_07_092325_create_pengajuan_jadwal_table.php:35-47`). Semester aktif atau pembatasan workflow berdasarkan semester tidak ditemukan pada jalur penjadwalan yang diperiksa.
- **BELUM DIKETAHUI:** Validitas nilai `ta` dari request, kesesuaian parameter tersebut dengan tahun aktif pada setiap endpoint, dan konfigurasi tahun/semester pada database tidak dapat dipastikan tanpa menjalankan aplikasi atau memeriksa database.

## 6. Temuan teknis untuk diverifikasi (tanpa perbaikan)

| ID | Kategori | Bukti | Temuan statis | Status |
|---|---|---|---|---|
| 2C-001 | FAKTA KODE / INFERENSI TEKNIS | `TahunHelper.php:21-32`; `JadwalSayaController.php:197-205`; `KelasMasterSeeder.php:9-13` | Nilai tingkat yang disediakan seeder berupa angka Romawi, sedangkan helper maksimum JP mencocokkan angka 1–6 setelah cast integer. Secara statis, angka Romawi tidak memenuhi cabang tersebut dan akan memakai default `35`; pemakaian nilai lain di database belum diperiksa. | Perlu dikonfirmasi pada sumber data aktual; tidak diperbaiki. |
| 2C-002 | FAKTA KODE | `PengajuanJadwalController.php:16-18,40-56`; `User.php:14-17,86-99` | Resource pengajuan mengambil semua pengajuan untuk index tanpa filter guru dan menyimpan `auth()->user()->guru_id`. Sementara itu, model User juga mendefinisikan relasi guru melalui `pegawai_id`. Hubungan field `guru_id` pada user dengan relasi tersebut belum dapat dipastikan dari controller ini. | Perbedaan jalur dan sumber identitas dicatat; tidak disimpulkan dampak runtime. |
| 2C-003 | FAKTA KODE | `resources/views/guru/pengajuan-jadwal/index.blade.php:35-42`; `app/Models/PengajuanJadwal.php:49-61`; migration `2026_05_07_092325_create_pengajuan_jadwal_table.php:31-47` | View daftar resource membaca `$item->jam->nama`, sedangkan model mendefinisikan relasi `jamPelajaran()` dan schema menyimpan `jam_pelajaran_id`; accessor/relasi `jam` tidak ditemukan pada model yang diperiksa. | Ketidaksesuaian statis view-model; halaman tidak dijalankan. |
| 2C-004 | FAKTA KODE | `resources/views/tu/jadwal-final/index.blade.php:24-25`; `routes/tu.php:182-188`; `routes/superadmin.php:152-158` | Form export pada view TU mengarah ke nama route Superadmin, sementara route export TU juga terdaftar. | Perlu verifikasi perilaku/render route; tidak diperbaiki. |
| 2C-005 | FAKTA KODE | `PengajuanJadwalController.php:40-56` dibandingkan `JadwalSayaController.php:140-220` | Dua endpoint pembuatan draft memiliki kontrol yang berbeda: resource mengandalkan required saja, sedangkan grid utama melakukan validasi ID, otorisasi assignment, pemeriksaan slot, serta kuota. | Keduanya tercatat sebagai jalur berbeda; penggunaan aktual dan hubungan UI-nya belum diketahui. |
| 2C-006 | FAKTA KODE | `JadwalSayaController.php:176-205`; `MonitoringJadwalController.php:275-290`; migration `2026_05_07_092325_create_pengajuan_jadwal_table.php:11-47` | Pemeriksaan konflik yang tampak mencakup okupansi slot kelas dan benturan guru; tidak ditemukan pemeriksaan benturan ruang, sementara schema pengajuan tidak memiliki foreign key ruang. | Tidak ada kesimpulan kesesuaian kebutuhan; dicatat sebagai batas bukti implementasi yang diperiksa. |
| 2C-007 | FAKTA KODE / BELUM DIKETAHUI | `app/Actions/Jadwal/GetJPStatus.php:8-57`; `app/Http/Controllers/Tu/JadwalGrupController.php:14-18,89-101,148`; route yang diperiksa pada `routes/tu.php` dan `routes/*.php` | Terdapat kode jadwal lain berbasis model `Jadwal`/`JadwalGrup`. Pencarian route tidak menemukan penghubung ke `JadwalGrupController`; action status JP juga masih membaca model `Jadwal`. | Status penggunaan aktif, cakupan route lain, dan dampaknya belum dipastikan. |

## 7. Workflow parsial atau belum terpetakan

### Parsial

- Resource `guru.pengajuan-jadwal`: controller dan view tersedia, tetapi filter kepemilikan, validasi terhadap entitas/assignment, konflik, kuota, serta keterhubungan dengan jalur utama belum dapat dibuktikan konsisten.
- Export jadwal final TU: form dan controller/route terkait ada, namun nama route yang dipakai view berbeda dari route TU yang didaftarkan.
- Komponen `JadwalGrupController`/`Jadwal` legacy: kelas controller dan model dapat ditemukan, tetapi route operasionalnya tidak ditemukan dalam pencarian route yang dilakukan.
- Perubahan/pembatalan jadwal setelah submitted/approved/published: guru hanya memiliki jalur hapus untuk draft/rejected yang terlihat; alur ubah atau batalkan untuk status selanjutnya belum ditemukan.

### Belum ditemukan pada cakupan pencarian ini

- Pemilihan atau pemesanan ruang pada workflow pengajuan jadwal.
- Pemeriksaan konflik ruang.
- Kolom/relasi semester pada tabel `pengajuan_jadwal` dan filter semester aktif pada workflow penjadwalan yang ditelusuri.
- Route yang menghubungkan `JadwalGrupController` dan action `GetJPStatus` ke alur utama, setelah pencarian route/controller/view terkait.
- Workflow penjadwalan Kepala Sekolah yang memakai role selain `superadmin`; route monitoring yang ditemukan secara eksplisit dibatasi `role:superadmin`.

“Tidak ditemukan” di atas terbatas pada source dan pencarian yang disebutkan, bukan pernyataan tentang konfigurasi runtime atau integrasi eksternal.

## 8. Ringkasan perbedaan frontend dan backend yang terlihat

1. View resource daftar pengajuan membaca relasi `jam`, sedangkan model menyediakan `jamPelajaran`; hasil aktual halaman belum diverifikasi.
2. Form ekspor jadwal final TU merujuk route ekspor Superadmin, bukan nama route TU yang terdaftar.
3. Jalur form resource guru dan grid Jadwal Saya berbeda pada validasi, pemeriksaan akses, benturan, dan kuota.
4. Jalur utama menampilkan batas total JP; helper yang menentukan batas tersebut menggunakan angka tingkat, sementara seeder menyiapkan tingkat Romawi. Ini adalah inferensi atas data seeder, belum verifikasi terhadap data runtime.

## 9. Cakupan yang belum diperiksa dan keterbatasan

- Audit ini bersifat statis. Tidak ada request aplikasi, browser, query database, migration, seeder, test, build, atau operasi cache yang dijalankan.
- Tidak dapat dipastikan apakah status, nilai tingkat, tahun ajaran aktif, dan record penjadwalan aktual di database sesuai dengan default atau seeder dalam source.
- Penelusuran `JadwalGrupController`/model `Jadwal` hanya untuk memastikan apakah ada jalur alternatif terkait penjadwalan. Seluruh keterhubungan action/controller legacy dengan seluruh pemanggil dinamis belum diperiksa sampai tuntas.
- Test yang ditemukan terkait submit jadwal adalah bagian dari `tests/Feature/BusinessNotificationTest.php:173-202`; test tersebut tidak dijalankan. Tidak ada kesimpulan bahwa test tersebut mencakup keseluruhan workflow penjadwalan.
- Notifikasi hanya dicatat sejauh pemanggilan `BusinessNotificationService` dari controller; kanal, pengiriman aktual, dan penerimaan oleh pengguna tidak diverifikasi.
- Peran jabatan “Kepala Sekolah”, kebutuhan sekolah, dan kebenaran aturan kuota/konflik tidak dinilai sebagai kesesuaian kebutuhan bisnis.
- Modul lain di luar penjadwalan akademik, termasuk workflow kepegawaian/kelas umum, pembelajaran, absensi, e-learning, ekskul, dan transisi tahun ajaran secara menyeluruh, tidak diaudit ulang.

## 10. Cakupan lanjutan yang dapat dipertimbangkan

Jika Project Owner menyetujui tahap audit lanjutan, cakupan berikut dapat diperiksa terpisah:

- Menelusuri kode jadwal legacy dan seluruh pemanggilnya untuk memastikan apakah masih digunakan atau hanya tersisa sebagai jalur lama.
- Memeriksa alur perubahan dan pembatalan jadwal per status, serta hubungan data jadwal dengan semester/tahun ajaran.
- Memverifikasi konsistensi ID route/form dan relasi yang dirujuk view terhadap model, termasuk daftar pengajuan dan export TU.
- Jika audit dinamis kelak diizinkan, menetapkan lingkungan aman terlebih dahulu untuk memeriksa aturan konflik, kuota, serta respons UI tanpa menggunakan data produksi.

## 11. Batas cakupan audit Tahap 2C

Audit ini hanya memetakan workflow penjadwalan yang dapat ditelusuri di source code pada baseline di atas. Temuan adalah catatan bukti kode, inferensi teknis yang diberi label, atau hal yang belum diketahui. Tidak dilakukan perubahan kode atau evaluasi kesesuaian implementasi terhadap kebutuhan sekolah.
