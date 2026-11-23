# TAHAP 2B.0R — PREFLIGHT BLOCKER RESOLUTION

**Sistem:** SINERGI-MJ  
**Mode:** Read-only inspection  
**Waktu pemeriksaan:** 3 November 2026  
**Final verdict:** **NOT READY**

## 1. Executive Summary

Tahap ini menginventarisasi kondisi Git, konfigurasi lokal tanpa membaca credential, bukti backup yang terjangkau, dan runtime ICU CLI. Tidak ada source, migration, database, konfigurasi, environment, atau state Git yang diubah. Tidak ada database query, migration, seeder, atau test yang dijalankan.

Temuan utama:

- Git berada di branch `main`, commit `b32f25fc6e28b8e0262fa745e6cf454ea28e6414`; pada saat inventory terdapat **2 tracked files berubah dan 49 untracked files**. Satu tracked change adalah perubahan UI PPDB yang berpotensi memengaruhi aplikasi. Semua perubahan harus dipertahankan.
- 49 file untracked yang ada saat inventory merupakan dokumen audit Markdown. Dokumen laporan ini dibuat setelah inventory tersebut atas permintaan Tahap 2B.0R, sehingga menambah satu file audit untracked.
- File `.env` dan `.env copy` ada di root proyek. Nilainya tidak dibaca atau ditampilkan. `.env` diabaikan oleh `.gitignore`; status tracking `.env copy` tidak ditetapkan pada pemeriksaan ini. Tidak ada perubahan Git pada file konfigurasi/environment.
- Tidak ditemukan artefak backup pada root proyek dan lokasi backup Laragon/proyek yang disebutkan di §4. Backup eksternal dan restore drill **NOT VERIFIED**.
- Dengan executable PHP 8.2.29 dan ICU 71.1 yang sama, probe menghasilkan **ACTUAL locale `id_ID`** dan **VALID locale `root`**. Ini cocok dengan nilai `root` yang dicatat audit 2A.6 bila nilai VALID yang terambil/dilaporkan sebagai ACTUAL; metodologi historis tersebut tidak dapat dibuktikan ulang.
- Konfigurasi `.env` lokal menyebut MySQL dan schema `sekolah_app_testing`; itu bukan verifikasi target database efektif. Tidak ada koneksi/query database dilakukan.

**Kesimpulan:** ketidakpastian perbedaan locale dapat dijelaskan secara teknis dengan perbedaan ACTUAL vs VALID locale; tetapi target DB, backup/restore, dan perlindungan perubahan existing belum memenuhi entry conditions Workstream A.

## 2. Git Change Inventory

### 2.1 Kondisi Git

| Pemeriksaan | Hasil |
|---|---|
| Root repository | `C:\laragon\www\sistem-informasi-sekolah` |
| Branch | `main`, tracking `origin/main` |
| Commit HEAD | `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` (`SINERGI-MJ UPDATE`, 2026-10-09) |
| Repository shallow | `true` |
| Tracked files changed | 2 |
| Untracked files pada saat inventory | 49 |
| Git mutation | Tidak dilakukan |

### 2.2 Tracked files berubah dan diff

1. `docs/audit/TAHAP_1_INVENTARIS.md`
   - Diff: hanya whitespace/spasi tambahan di akhir baris tabel “Branch aktif”.
   - Kategori: **Audit documentation**.
   - Dampak aplikasi: tidak ada dampak runtime yang teridentifikasi.

2. `resources/views/ppdb/public/informasi.blade.php`
   - Diff: tautan “Mulai Mendaftar” diganti dropdown berisi “Peserta Didik Baru” menuju route alur PPDB dan “Peserta Didik Pindahan” dengan `href="#"`.
   - Kategori: **Existing application changes**.
   - Dampak: berpotensi mengubah navigasi publik PPDB; tautan peserta didik pindahan saat ini placeholder. Ini perubahan pengguna yang harus dipertahankan, bukan dibuang atau ditimpa.

Git menampilkan peringatan normalisasi CRLF pada kedua file saat membaca diff. Tidak ada file konfigurasi/environment, generated/build artifact, atau migration yang tercatat berubah.

### 2.3 Seluruh untracked files pada saat inventory

Semua file berikut berada di `docs/audit/` dan berekstensi Markdown; kategori: **Audit documentation**.

1. `SINERGI_MJ_MASTER_AUDIT.md`
2. `TAHAP_2_WORKFLOW_FRONTEND_BACKEND.md`
3. `TAHAP_2A_10_APPROVAL_REGISTER.md`
4. `TAHAP_2A_10_CHANGELOG.md`
5. `TAHAP_2A_10_DECISION_DEPENDENCY_MATRIX.md`
6. `TAHAP_2A_10_OWNER_DECISION_PACKAGE.md`
7. `TAHAP_2A_10R_CHANGELOG.md`
8. `TAHAP_2A_10R1_CHANGELOG.md`
9. `TAHAP_2A_11_AUTHORITY_AND_APPROVER_MATRIX.md`
10. `TAHAP_2A_11_CHANGELOG.md`
11. `TAHAP_2A_11_DECISION_CONSISTENCY_REVIEW.md`
12. `TAHAP_2A_11_OWNER_APPROVAL_SESSION_GUIDE.md`
13. `TAHAP_2A_11_OWNER_DECISION_WORKSHEET.md`
14. `TAHAP_2A_12_BASELINE_CONFLICT_REGISTER.md`
15. `TAHAP_2A_12_CHANGELOG.md`
16. `TAHAP_2A_12_ENGINEERING_BASELINE.md`
17. `TAHAP_2A_12_IMPLEMENTATION_DEPENDENCY_PLAN.md`
18. `TAHAP_2A_12_TECHNICAL_READINESS_MATRIX.md`
19. `TAHAP_2A_12R_CHANGELOG.md`
20. `TAHAP_2A_12R_ENGINEERING_BASELINE_REFINEMENT.md`
21. `TAHAP_2A_12R_IMPLEMENTATION_DEPENDENCY_REFINEMENT.md`
22. `TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md`
23. `TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md`
24. `TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md`
25. `TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md`
26. `TAHAP_2A_7R_CHANGELOG.md`
27. `TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md`
28. `TAHAP_2A_8_CHANGELOG.md`
29. `TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md`
30. `TAHAP_2A_8_TRACEABILITY_MATRIX.md`
31. `TAHAP_2A_8R_ARCHITECTURE_CONSISTENCY_REFINEMENT.md`
32. `TAHAP_2A_8R_CHANGELOG.md`
33. `TAHAP_2A_8R_TRACEABILITY_MATRIX.md`
34. `TAHAP_2A_8R1_CHANGELOG.md`
35. `TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md`
36. `TAHAP_2A_8R1_TRACEABILITY_MATRIX.md`
37. `TAHAP_2A_9_CHANGELOG.md`
38. `TAHAP_2A_9_HISTORICAL_DECISION_TRACEABILITY.md`
39. `TAHAP_2A_9_HISTORICAL_SOURCE_RECOVERY.md`
40. `TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md`
41. `TAHAP_2B_WORKFLOW_TU_AKADEMIK.md`
42. `TAHAP_2C_WORKFLOW_PENJADWALAN.md`
43. `TAHAP_2D_WORKFLOW_GURU_SISWA.md`
44. `TAHAP_2E_WORKFLOW_EKSTRAKURIKULER.md`
45. `TAHAP_2F_WORKFLOW_DOKUMEN_SURAT.md`
46. `TAHAP_3_AUDIT_OTORISASI_LINTAS_PERAN.md`
47. `TAHAP_3A_PENDALAMAN_OTORISASI.md`
48. `TAHAP_3B_CAKUPAN_AUDIT_OTORISASI.md`
49. `VALIDASI_MASTER_AUDIT.md`

### 2.4 Klasifikasi dan sensitivitas

| Kategori | Hasil |
|---|---|
| Existing application changes | 1 file Blade tracked berubah |
| Audit documentation | 1 file tracked berubah dan 49 file untracked pada saat inventory |
| Configuration/environment changes | Tidak ada yang tercatat berubah |
| Generated/build artifacts | Tidak ada yang tercatat |
| Unknown/unclassified | Tidak ada pada daftar Git di atas |
| Sensitive local files | `.env` dan `.env copy` terlihat di root. Isi tidak dibaca, ditampilkan, atau dibandingkan. `.env` di-ignore Git. |

Pencarian nama file tracked menemukan `.env.example` dan `.env.testing`, serta file kode/template email PPDB yang namanya mengandung “credentials”. Tidak ada credential value yang diperiksa atau ditampilkan; nama kelas/template tersebut bukan bukti adanya secret. Tidak ada secret yang teridentifikasi pada diff tracked yang diperiksa.

## 3. Preservation and Isolation Recommendation

**Rekomendasi: dedicated clone pada commit yang diketahui, dengan working copy sekarang dibiarkan utuh.**

Alasan:

- Perubahan tracked aplikasi yang belum dikomit dan 49 dokumen audit untracked tetap berada di working copy saat ini.
- Clone terpisah memberi batas isolasi lebih kuat daripada membuat branch pada working copy yang sedang dirty.
- Repository shallow, sehingga ketersediaan objek commit dan baseline remote harus diverifikasi sebelum clone terpisah dipakai. Tidak ada clone, fetch, branch, atau worktree dibuat pada tahap ini.

Langkah yang direkomendasikan setelah mendapat otorisasi:

1. Pertahankan direktori saat ini apa adanya sebagai sumber perubahan existing; jangan membersihkan atau mengganti file.
2. Buat clone terpisah dari commit `b32f25fc6e28b8e0262fa745e6cf454ea28e6414` setelah memastikan commit tersebut tersedia dan sumber remote benar.
3. Validasi hash baseline pada clone baru. Jangan menyalin seluruh dirty working tree secara otomatis.
4. Jika dokumen audit untracked diperlukan sebagai input, pilih dan salin hanya file yang disetujui; verifikasi isi/hash terhadap working copy. Perlakukan perubahan tampilan PPDB sebagai pekerjaan terpisah, bukan bagian baseline NIS tanpa keputusan eksplisit.
5. Mulai Workstream A hanya pada clone terisolasi, dengan izin database dan backup/recovery yang terpisah.

Alternatif worktree terpisah lebih baik daripada branch pada working tree dirty, tetapi berbagi metadata Git/object store dengan repository saat ini. Branch pada working tree yang ada bukan rekomendasi pertama karena perubahan saat ini bercampur dan belum diamankan/diidentifikasi pemiliknya.

## 4. Backup/Recovery Evidence

| Kategori bukti | Status | Hasil |
|---|---|---|
| Backup database ditemukan dan terverifikasi | **NOT VERIFIED** | Tidak ada backup yang ditemukan pada lokasi dalam cakupan; tidak ada integritas/kelengkapan atau restore drill yang dibuktikan. |
| Backup ditemukan tetapi belum diverifikasi | **NOT VERIFIED** | Tidak ada artefak backup teridentifikasi pada lokasi yang diperiksa. |
| Backup tidak ditemukan | **PARTIAL** | Tidak ditemukan pada root proyek, `backup/`, `backups/`, `storage/app/backups/`, `C:\laragon\backup`, dan `C:\laragon\backups`. |
| Backup di luar cakupan | **NOT VERIFIED** | NAS, cloud, removable media, lokasi admin/DBA, host lain, serta kebijakan backup eksternal tidak diperiksa dan tidak dapat disimpulkan. |
| Backup source code terverifikasi | **NOT VERIFIED** | Remote Git dan commit HEAD bukan bukti backup source lengkap; shallow clone dan perubahan lokal membuatnya bukan pengganti backup/recovery teruji. |
| Restore-ready | **BLOCKED** | Tidak ada bukti restore drill pada target terisolasi. |

### Lokasi database lokal dan identitas konfigurasi

- `.env` lokal menyatakan `DB_CONNECTION=mysql`, host `127.0.0.1`, port `3306`, dan database `sekolah_app_testing`.
- `config/database.php` membaca pengaturan melalui environment Laravel.
- Tidak ada query ke MySQL; karena itu engine/version/schema efektif dan apakah aplikasi saat ini benar-benar terhubung ke database tersebut **NOT VERIFIED** pada tahap ini.
- Nama database lokal bukan identifikasi target deployment/production.

### Prasyarat prosedur backup dan restore

Sebelum migration yang berisiko:

1. Owner/operator yang berwenang menetapkan target database dan jendela perubahan.
2. DBA membuat backup konsisten yang mencakup database dan komponen recovery terkait, mencatat lokasi, waktu, ukuran, target, metode, dan hasil integritas tanpa menyimpan credential dalam laporan.
3. Backup disimpan di lokasi terpisah/terkendali dan dapat diakses pihak recovery berwenang.
4. Lakukan restore drill ke instance terisolasi/non-production; cocokkan schema/data scope dan rekam hasil serta durasi recovery.
5. Tetapkan rollback/forward-fix, cutover, dan verifikasi pasca-restore sebelum perubahan.

Tidak satu pun langkah tersebut dilakukan pada tahap ini.

## 5. ICU Runtime Reconciliation

### Probe CLI yang berhasil

Executable eksplisit:

`C:\laragon\bin\php\php-8.2.29-Win32-vs16-x64\php.exe`

| Properti | Hasil |
|---|---|
| `PHP_BINARY` | Executable eksplisit di atas |
| PHP SAPI | `cli` |
| PHP version | `8.2.29` |
| Loaded `php.ini` | `C:\laragon\bin\php\php-8.2.29-Win32-vs16-x64\php.ini` |
| `intl` | Tersedia |
| ICU | `71.1` |
| Requested locale | `id-ID` |
| `Collator::getLocale(Locale::ACTUAL_LOCALE)` | `id_ID` |
| `Collator::getLocale(Locale::VALID_LOCALE)` | `root` |

### Rekonsiliasi terhadap audit 2A.6

[Audit 2A.6](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md) menyebut PHP 8.2.29/ICU 71.1 dan mencatat `root` sebagai actual locale. Probe Tahap 2B.0R dengan binary yang ditentukan menghasilkan actual `id_ID`, valid `root`.

**Kesimpulan:** nilai `root` dapat dijelaskan oleh perbedaan pemilihan konstanta locale: pada runtime yang diuji, `root` adalah **VALID**, bukan **ACTUAL**, untuk requested locale `id-ID`. Ini merupakan penjelasan teknis yang konsisten dengan kedua nilai, bukan bukti pasti tentang bagaimana probe historis 2A.6 dijalankan. Karena versi/executable yang sama kini memberikan hasil ACTUAL yang berbeda dari catatan lama, metodologi dan output historis tetap perlu diklarifikasi/dicatat sebagai koreksi evidence.

- CLI saat ini: **PASS** untuk mengukur ACTUAL dan VALID locale pada executable tersebut.
- Penyebab historis yang tepat: **PARTIAL / NOT VERIFIED**.
- Web SAPI: **NOT VERIFIED**. Tidak ada HTTP request atau pembacaan endpoint runtime; proses web/handler saat pemeriksaan ini tidak diverifikasi.
- Comparator/golden order: **NOT VERIFIED** oleh probe ini; hasil locale bukan bukti comparator aplikasi, golden ordering, atau CLI/web/worker parity.
- Baseline comparator: tidak diubah; tidak ada fallback yang ditambahkan.

## 6. Database Preflight Plan

Tidak ada query database pada Tahap 2B.0R. Sebelum Workstream A, tetapkan target yang benar dan gunakan kredensial read-only khusus; jangan gunakan `.env` lokal yang dapat berisi privilege lebih luas sebagai otorisasi pemeriksaan.

| Pemeriksaan yang diperlukan | Cara/bukti yang direncanakan | Akses |
|---|---|---|
| Engine dan versi efektif, host/schema koneksi | Konfirmasi target dari owner/operator; pada koneksi target baca `SELECT VERSION()`, `@@version_comment`, `DATABASE()` dan identitas host yang disetujui. Hindari menampilkan credential. | Read-only connection; konfirmasi target memerlukan otorisasi lingkungan. |
| Migration status | Bandingkan migration source/release dengan isi tabel `migrations` melalui pembacaan saja; catat pending/applied tanpa mengeksekusi migration. | SELECT read-only dan akses source release; jangan jalankan `migrate`. |
| Schema drift dan struktur tabel relevan | Baca `information_schema.TABLES`, `COLUMNS`, `STATISTICS`, `KEY_COLUMN_USAGE`, dan constraint untuk users, siswa, PPDB, placement, tahun ajaran, serta tabel terkait Workstream A. Bandingkan dengan migration source. | Read-only metadata; pastikan batas schema/tenant benar. |
| Foreign key dan unique constraints | Rekam definisi aktual dari `information_schema`/`SHOW CREATE TABLE` untuk tabel yang disetujui. Bedakan source migration dari schema efektif. | Read-only metadata; hak akses metadata perlu diuji/diotorisasi. |
| Index dan storage engine | Baca index aktual dan `TABLES.ENGINE`; periksa InnoDB untuk objek yang memerlukan transaksi. | Read-only metadata. |
| Transaction isolation | Baca session/global isolation variable tanpa memulai transaksi uji atau mengubah session. | Read-only `SELECT`; akses terhadap global variable dapat bergantung privilege/versi. |
| Effective grants | `SHOW GRANTS FOR CURRENT_USER()` pada koneksi runtime read-only; DBA terotorisasi menilai akun lain/role inheritance dan grants efektif. Jangan mencetak credential. | Current user mungkin dapat memeriksa grants-nya sendiri; pemeriksaan akun lain memerlukan DBA/privilege tambahan. |
| Backup/recovery gate | Verifikasi artefak backup, integrity evidence, lokasi terpisah, dan restore drill terisolasi sebelum DDL. | Persetujuan owner/operator/DBA; bukan sekadar read-only query database. |
| Locking/concurrency capability | Mulai dari inspeksi engine/isolation/index. Uji `SELECT ... FOR UPDATE`, deadlock/retry, dan rollback hanya pada staging/disposable yang disetujui; jangan menguji lock di production. | Memerlukan otorisasi tambahan dan lingkungan uji terisolasi; bukan pemeriksaan metadata read-only murni. |

Hasil lokal dalam [audit 2A.6](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md) adalah evidence pada waktu audit itu, bukan kondisi target/current. Tidak ada hasilnya yang dianggap sebagai verifikasi Workstream A saat ini.

## 7. Remaining Blockers

| Blocker | Status | Yang diperlukan |
|---|---|---|
| Perubahan existing belum diamankan/diisolasi | **BLOCKED** | Pertahankan working copy; implementasi di clone terpisah yang baseline-nya diverifikasi. |
| Backup database/source dan restore drill | **BLOCKED** | Bukti backup terkendali dan restore drill berhasil sebelum migration berisiko. |
| Target database belum dikonfirmasi | **BLOCKED** | Otorisasi target/schema dan inventory read-only pada koneksi yang benar. |
| Schema/migration drift dan effective grants current | **NOT VERIFIED** | Hasil metadata target dan grant review oleh pihak berwenang. |
| Runtime web/worker parity | **NOT VERIFIED** | Bukti binary/SAPI/intl/ICU locale aktual per runtime tanpa mengubah konfigurasi. |
| Locale evidence historis | **PARTIAL** | Koreksi metode 2A.6: catat ACTUAL dan VALID secara eksplisit; jangan ubah comparator hanya berdasarkan ini. |
| Legacy NIS inventory/privilege gate | **BLOCKED** | Bukti inventory historis lengkap dan least-privilege gate tetap menjadi prerequisite sesuai baseline 2A.12R. |

## 8. Workstream A Entry Conditions

Workstream A hanya dapat dimulai setelah seluruh kondisi berikut dipenuhi:

1. **Preservation PASS:** perubahan tracked/untracked saat ini tetap utuh; baseline clone/branch kerja terisolasi teridentifikasi tanpa menghilangkan dokumen audit atau perubahan UI.
2. **Target authorization PASS:** owner/operator mengonfirmasi database/environment yang akan diperiksa dan memberi izin eksplisit untuk inspeksi.
3. **Read-only evidence PASS:** engine/version, schema, migration status, constraints, indexes, engines, isolation, dan grants aktual dikumpulkan dari target yang benar.
4. **Recovery PASS:** backup terverifikasi tersedia di lokasi terkendali dan restore drill non-production berhasil.
5. **Privilege plan PASS:** akun runtime, migration/deployment, serta DBA/break-glass dibedakan; effective grants dan batas mutation disetujui.
6. **Evidence handling PASS:** credential/secret tidak disalin ke laporan; bukti dibatasi ke fakta yang diperlukan dan diberi tanggal/sumber.
7. **Scope gate PASS:** Workstream A tetap pada database foundation dan tidak mengaktifkan allocator/publisher atau menganggap policy owner telah menyetujui kebijakan yang masih pending.

## 9. Final Verdict

| Kriteria | Status |
|---|---|
| Git change inventory | **PASS** |
| Preservation/isolation | **BLOCKED** |
| Backup dan recovery evidence | **BLOCKED** |
| ICU CLI reconciliation | **PASS** untuk nilai runtime saat ini; historical probe **PARTIAL** |
| Web/worker locale parity | **NOT VERIFIED** |
| Target database identity | **NOT VERIFIED** |
| Database schema/grants saat ini | **NOT VERIFIED** |
| Workstream A entry conditions | **BLOCKED** |

**FINAL VERDICT: `NOT READY`**

Perbedaan locale dapat dijelaskan sebagai ACTUAL `id_ID` versus VALID `root` pada probe CLI saat ini; ini tidak menutup kebutuhan golden comparator atau runtime parity. Working tree tidak disentuh selain penambahan laporan ini. Seluruh perubahan existing tetap harus dipertahankan.
