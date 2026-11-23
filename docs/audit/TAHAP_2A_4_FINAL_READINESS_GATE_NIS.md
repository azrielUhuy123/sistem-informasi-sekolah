# TAHAP 2A.4 — FINAL READINESS GATE SISTEM NIS SINERGI-MJ

**Status:** NOT READY FOR IMPLEMENTATION  
**Jenis pemeriksaan:** Read-only readiness review. Dokumen ini bukan persetujuan kebijakan, bukti migrasi, atau klaim implementasi.

## 1. Executive Summary

Refinement 2A.3 telah menutup rancangan konseptual untuk siklus FK, relasi User–Siswa, lock lintas cohort, dan perlindungan immutability. Namun, kelengkapan dokumen tidak membuktikan konfigurasi deployment, kondisi data aktual, izin DB, atau perilaku concurrency.

**Verdict: NOT READY FOR IMPLEMENTATION.** Coding database dan service NIS belum boleh dimulai karena masih ada keputusan owner yang memengaruhi identitas/urutan/akses, dan bukti teknis kritis belum diperoleh. Persiapan audit dan spesifikasi test dapat berlanjut, tetapi bukan implementasi atau migration.

Fakta yang tersedia:

- `composer.json` mensyaratkan PHP `^8.2` dan Laravel `^12.0`; ini batas manifest, bukan versi runtime.
- `config/database.php` memiliki default SQLite, tetapi juga konfigurasi MySQL/MariaDB/PostgreSQL/SQL Server melalui environment. Tidak ada dasar untuk menyimpulkan driver deployment dari default.
- Pada pemeriksaan shell tahap ini, `php` tidak ditemukan pada PATH. Oleh karena itu, versi CLI saat ini dan extension tidak dapat diperiksa dengan perintah tersebut. Tidak ada pemeriksaan PHP web runtime atau akses DB.
- Audit sebelumnya yang dirujuk desain mencatat PHP lokal 8.2.29, ICU 71.1, dan Collator Indonesia yang menghasilkan actual locale `root`; catatan itu bukan verifikasi runtime deployment saat ini.
- Model/migration menunjukkan dua arah relasi User–Siswa (`siswa.user_id`, `users.siswa_id`), sementara `LoginRequest` mencari siswa via NIS lalu `Siswa.user`; data aktual kedua FK belum direkonsiliasi.

Tidak ada test, migration, seeder, koneksi database, atau perintah yang mengubah data dijalankan untuk readiness gate ini.

## 2. Referensi dan Ruang Lingkup Audit

### Referensi desain/kode yang dibaca

- `docs/audit/TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md` (refinement 2A.3), termasuk open decisions dan §16.1–§16.14.
- `docs/audit/TAHAP_1_INVENTARIS.md` sebagai catatan audit repository sebelumnya; isinya bukan bukti konfigurasi/runtime deployment saat ini.
- `composer.json`, `config/database.php`.
- `database/migrations/2025_12_05_173923_init_all_tables.php`.
- `database/migrations/2026_05_25_195130_add_user_id_to_siswa_table.php`.
- `database/migrations/2026_06_25_204529_refactor_users_and_guru_relationships.php`.
- `database/migrations/2026_06_17_071339_add_username_to_users_table.php`.
- `app/Models/Siswa.php`, `app/Models/User.php`, `app/Http/Requests/Auth/LoginRequest.php`.
- Pencarian read-only writer NIS/account pada `app/`, `routes/`, dan `database/`.

### Batas audit

Tidak membuka `.env`/file kredensial, tidak menghubungi database, tidak menjalankan test/migration/seeder, dan tidak mengubah file selain membuat dokumen readiness yang diminta. Pencarian writer yang dilakukan menunjukkan beberapa file yang relevan, tetapi tidak diperlakukan sebagai bukti bahwa seluruh call path telah ditutup.

### Kebijakan final yang dipertahankan

NIS baru berformat `TTTT.T.NNN`; `2026/2027` menggunakan `year_code=2627`; sequence berbagi ruang nomor per `(year_code, entry_grade)` lintas rombel; NIS immutable dan tidak digunakan ulang; penerbitan setelah entry resmi dan placement sah; initial lintas jalur/tingkat; supplemental hanya setelah cutoff; kandidat pre-cutoff yang terlewat hardblock; legacy tidak ditebak/dinormalisasi; reservation memerlukan approval; force-delete tidak menghapus ledger; comparator fail closed sampai terbukti.

## 3. Owner Decisions Register

Rekomendasi adalah masukan teknis, bukan kebijakan final. Tahap yang terdampak membatasi kapan keputusan harus tersedia; sebagian keputusan tidak menghalangi kerja desain yang independen.

| ID | Topik dan pilihan | Rekomendasi teknis | Dampak bila belum diputuskan | Blocker dan tahap terdampak |
|---|---|---|---|---|
| OD-01 | Database/driver deployment: MySQL, MariaDB, PostgreSQL, atau engine lain yang resmi | Owner/ops menetapkan satu engine dan versi target per deployment; jangan menjadikan SQLite default sebagai bukti. | DDL, FK, unique, trigger, lock, upsert, isolation dan test strategy belum dapat dipilih. | **Ya:** migration, concurrency, publication. |
| OD-02 | Runtime PHP web dan ekstensi | Setujui runtime web yang didukung serta extension minimum; runtime CLI harus disejajarkan atau perbedaannya dibatasi. | Aplikasi mungkin berbeda dari runtime yang diuji; sorting dan PDO tidak pasti. | **Ya:** sorting/integrasi; deployment gate. |
| OD-03 | Sorting nama Indonesia: ICU locale terbukti, pinned library/data, atau comparator eksplisit yang disetujui | Pilih comparator lintas runtime yang golden-order-nya lulus; tidak ada fallback ke `root`/byte sort. | Initial NIS immutable dapat diberikan dengan urutan yang salah/berbeda. | **Ya:** initial publication; blocker aktif. |
| OD-04 | Siklus akun siswa: akun nonaktif sebelum publish, akun dibuat sesudah publish, atau identifier sementara | Utamakan opsi 1 atau 3 yang selaras dengan login berbasis NIS; identifier sementara hanya dengan security review. | Perubahan PPDB, login, reset, email, password awal dan orphan lifecycle tak dapat difinalkan. | **Ya:** account/PPDB integration; tidak harus menghalangi schema ledger independen. |
| OD-05 | Koreksi setelah initial publish: pejabat, bukti, status konflik, laporan/placement yang terdampak | Append-only correction/conflict; NIS/slot tak pernah diterbitkan ulang; unresolved case memblokir supplemental terkait. | Tidak ada kewenangan/prosedur penyelesaian fakta salah. | **Ya:** correction workflow dan cohort terkait; blocker release untuk koreksi. |
| OD-06 | Legacy exact-collision: stop-and-reconcile saja atau izin `exact_collision_block` yang disetujui | Default fail closed; reservation hanya setelah policy, approver, evidence dan audit disetujui. | Generator dapat berhenti; auto-skip tanpa izin melanggar kontrol sequence. | **Ya saat collision ditemukan:** reconciliation/affected publication. |
| OD-07 | Bukti administratif transfer/pindahan dan pejabat pengesah | Owner menetapkan dokumen/arsip yang dapat membuktikan tahun dan tingkat pertama masuk serta approver berwenang. | Tahun/grade entry tidak boleh ditebak dari placement aktif atau sekolah asal. | **Ya:** menerima siswa pindahan/manual; tidak harus menghalangi PPDB biasa. |
| OD-08 | Urutan supplemental: kronologis `(official_entry_at, nama, siswa.id)` atau sort nama per-batch | Rekomendasi kronologis, nama/ID sebagai tie-break; perlu persetujuan eksplisit karena nomor immutable. | Batch boundary dapat mengubah suffix yang diberikan. | **Ya:** supplemental publication. |
| OD-09 | Valid placement: definisi rombel, tahun, tingkat, status aktif, tanggal dan exception | Tetapkan resolver yang dapat diperiksa; tidak menerbitkan jika placement wajib tidak valid. | Hardblock tak dapat dioperasionalkan secara konsisten. | **Ya:** placement integration dan publication. |
| OD-10 | Benturan `users.username` yang sudah dipakai | Tidak auto-merge/rename; hardblock candidate dan rekonsiliasi akun secara manual. Owner menetapkan apakah batch all-or-nothing atau akun tertentu menunda batch (sesuai atomicity final). | Unique collision dapat menggagalkan penerbitan atau salah menghubungkan akun. | **Ya:** akun/candidate collision; kebijakan sebelum rollout. |
| OD-11 | Relasi kanonis User–Siswa: `siswa.user_id` atau alternatif; migrasi/deprecate `users.siswa_id` | Rekomendasi `siswa.user_id` karena LoginRequest memakainya; verifikasi data dan owner approval sebelum backfill/deprecate. | Login dapat memakai akun salah, link ganda, atau orphan. | **Ya:** account-link migration/integrasi; ledger NIS independen dapat dikerjakan setelah blocker lain. |
| OD-12 | Hak DB runtime dan direct SQL | Pisahkan runtime role dari migration/DBA role; runtime tidak punya UPDATE/DELETE ledger tanpa mekanisme audited yang disetujui. | Laravel lock/service dapat dilewati oleh raw SQL/akun berhak tinggi. | **Ya:** klaim immutability dan publisher activation. |
| OD-13 | Batas ukuran batch dan atomicity | Tetapkan volume maksimum berbasis load test target. Default satu publikasi all-or-nothing; tidak chunk tanpa desain atomisitas setara. | Transaction/lock terlalu lama atau resiko partial issuance. | **Ya:** sebelum production publication; tidak menghalangi unit/service design. |
| OD-14 | Otoritas timestamp, database clock, UTC dan precision | Gunakan DB time dengan UTC dan logical monotonic per cohort; setujui failover/precision behavior. | Initial/supplemental cutoff bisa nondeterministik. | **Ya:** mutation entry dan publication. |
| OD-15 | Pemetaan label `tahun_ajaran` menjadi year_code | Pertahankan keputusan `2026/2027 → 2627`; owner menyetujui aturan umum, semester, duplicate source, label correction dan century rollover. | Cohort dapat salah dipetakan atau digabung. | **Ya:** setiap source label ambiguous; fixed fixture tetap harus diuji. |
| OD-16 | Delete/restore/relink Siswa dan akun | Registry/history/slot permanen; FK operasional boleh `SET NULL`; relink hanya dengan bukti/approver dan audit event. Tentukan deaktivasi akun pada soft-delete dan restore. | Akses akun atau relasi historis dapat salah; force-delete dapat memutus link. | **Ya:** delete/account integration dan reconciliation. |
| OD-17 | Satu initial batch per cohort: generated guard, partial unique, atau setara | Pilih implementasi hanya setelah engine diketahui; unique constraint sebagai pertahanan kedua cohort lock. Failed retry pada batch sama; cancelled tidak membebaskan slot. | Dua row initial dapat terbentuk atau recovery mendorong batch kedua. | **Ya:** migration batch dan concurrent initial publish. |
| OD-18 | Pemisahan tugas, role publish/reservation/correction dan otoritas approval | Tetapkan role/permission dan apakah requester boleh menjadi approver; enforce separation-of-duty bila diwajibkan. | Aksi administratif tanpa otoritas/audit yang dapat dipertanggungjawabkan. | **Ya:** protected workflows sebelum enablement; schema dapat dirancang dengan actor snapshot. |

## 4. Technical Evidence Register

Status menggunakan **VERIFIED** hanya untuk fakta yang dapat dibuktikan dari materi yang dibaca saat ini; audit sebelumnya dicatat sebagai bukti historis, bukan bukti deployment.

| ID | Bukti yang harus didapat | Kondisi saat ini dan sumber | Cara verifikasi read-only yang diperlukan | Status | Dampak jika belum terbukti |
|---|---|---|---|---|---|
| TE-01 | Engine/versi DB deployment dan schema aktif | `config/database.php` mendukung beberapa driver dan default SQLite; tidak ada `.env` dibuka atau koneksi DB. | Ops menyatakan target; koneksi read-only ke environment non-production/deployment; query engine/version, schema catalog, isolation. | **UNVERIFIED** | Tidak dapat menyelesaikan migration/locking strategy. |
| TE-02 | PHP CLI yang tersedia pada sesi ini | Perintah `php -v` menghasilkan command-not-found. Manifest mensyaratkan `^8.2`. | Gunakan executable PHP eksplisit dari Laragon/CI, tanpa mengubah PATH/config; catat versi. | **PARTIALLY VERIFIED** | Tidak bisa memeriksa runtime CLI saat ini. |
| TE-03 | PHP web runtime (PHP-CGI/FPM/Apache/IIS) sama dengan runtime aplikasi | Tidak ada inspeksi web runtime; audit sebelumnya menyebut PHP lokal 8.2.29, belum membuktikan runtime web/deployment. | Endpoint diagnostik terbatas/non-public atau server inventory; bandingkan binary/build/SAPI/ini/extensions. Jangan expose phpinfo publik. | **UNVERIFIED** | Sorting, PDO, extension dan runtime parity belum diketahui. |
| TE-04 | Versi Composer dan resolved dependency runtime | `composer.json` menetapkan constraints PHP/Laravel; perintah Composer tidak digunakan sebagai hasil runtime dalam tahap ini. | `composer --version` dan `composer show --locked` pada environment build yang disetujui; tidak menjalankan install/update. | **PARTIALLY VERIFIED** | Versi package efektif/build reproducibility belum diketahui. |
| TE-05 | Extension intl, `Normalizer`, `Collator`, mbstring, PDO dan driver PDO | Audit terdahulu mencatat lokal `intl`, Normalizer, Collator, mbstring dan ICU 71.1; ini tidak membuktikan runtime yang berjalan sekarang. | Inventaris `php -m`, `extension_loaded`, `PDO::getAvailableDrivers()` pada CLI dan web SAPI target. | **PARTIALLY VERIFIED** | Dependency sorting/connection dapat hilang pada web runtime. |
| TE-06 | ICU data/version dan actual locale Indonesia | Catatan audit sebelumnya: ICU Collator requested Indonesia aktual `root`; Windows NLS bukan bukti parity. | Uji locale aliases, `getLocale(ULOC_ACTUAL_LOCALE)`, ICU/data version serta golden fixtures di runtime target. | **PARTIALLY VERIFIED** | **Blocker** sorting. |
| TE-07 | Hasil sorting nama Indonesia lintas runtime | Belum ada hasil comparator implementasi/golden run lintas PHP web/CLI dan production OS. | Fixture versi immutable yang mencakup case, accent, NFC, whitespace, punctuation, gelar, nama identik; bandingkan seluruh output dan fingerprint. | **UNVERIFIED** | Jangan publish initial batch. |
| TE-08 | Isolation, transaksi dan row-level lock | Rancangan mengusulkan `FOR UPDATE`; engine deployment tidak diketahui. Audit lokal lama mencatat MySQL 8.0.30/InnoDB/REPEATABLE-READ, bukan deployment. | Pada staging disposable dengan engine/version target, periksa isolation dan dua koneksi/proses untuk row lock/deadlock/retry. | **PARTIALLY VERIFIED** | Concurrency tidak terbukti. |
| TE-09 | Atomic upsert/bootstrap dan unique conflict semantics | Ada rancangan MySQL/PostgreSQL; tidak ada bukti implementasi/engine aktual. | Integration probes terisolasi yang menguji concurrent bootstrap cohort/sequence dan rollback; PostgreSQL harus rollback/savepoint setelah error. | **UNVERIFIED** | Cohort/sequence row dapat race. |
| TE-10 | Unique, CHECK, partial index/generated column dan FK support | Migration source lama menggunakan constraint Laravel, tetapi desain ledger belum diimplementasikan; driver tak diketahui. | DDL prototype di disposable DB target, inspect catalogs, test enforced check/unique partial/generated semantics dan NULL behavior. | **UNVERIFIED** | Initial uniqueness dan ledger invariants belum dapat dijamin. |
| TE-11 | Trigger vs `ON DELETE SET NULL` | Belum ada trigger NIS atau hasil uji FK action dengan immutable-column triggers. | Uji delete parent pada engine target; pastikan referential action berjalan, trigger tidak memblokir, kolom immutable tetap terlindungi. | **UNVERIFIED** | Force-delete dapat gagal atau membuka celah immutability. |
| TE-12 | Hak DB runtime dan direct SQL writers | Tidak ada bukti grant database, role, external ETL, atau trigger privileges. | Review grant read-only oleh DBA; inventaris job/import/admin writer; uji permission pada staging. | **UNVERIFIED** | Raw SQL dapat melewati Laravel service/lock. |
| TE-13 | Data aktual `siswa.user_id` vs `users.siswa_id` | Model/migration membuktikan dua FK nullable; tidak membuktikan data pasangan/unique aktual. | Query agregat read-only di lingkungan berizin: null mismatch, duplicate, orphan, role mismatch; tanpa menampilkan PII. | **UNVERIFIED** | Jangan migrasikan/deprecate relasi dan jangan auto-link. |
| TE-14 | NIS legacy, collision, duplicate, bentuk byte/collation dan soft-deleted inventory | Migration awal menetapkan NIS NOT NULL unique; tidak membuktikan schema/data aktif saat ini. | Agregat read-only pada `siswa` termasuk trashed dan registry pengguna; exact byte comparison; laporan terlindungi. | **UNVERIFIED** | Risiko migration tinggi; allocator tidak boleh aktif. |
| TE-15 | Username collision dan relasi account-Siswa aktual | `users.username` nullable unique pada migration source; `LoginRequest` memakai `Siswa.user`; belum ada inventaris data. | Agregat read-only collision terhadap calon NIS dan relasi silang; tidak expose username/PII pada laporan publik. | **UNVERIFIED** | Akun dapat salah terhubung atau batch gagal. |
| TE-16 | Schema aktual: nullable NIS, FK actions, index placement, deleted rows | Migration awal menyatakan `siswa.nis` NOT NULL unique; schema deployment tidak diinspeksi. | Read-only catalog inspection untuk kolom, unique/FK/index/check dan ON DELETE actions. | **PARTIALLY VERIFIED** | Migration plan tidak boleh dianggap siap. |
| TE-17 | Placement aktif/sah dan konsistensi PPDB↔Siswa | Dokumen audit sebelumnya merekam beberapa jalur PPDB/TU/import/placement; keadaan record dan skema aktual belum diverifikasi. | Query agregat read-only untuk status contradictory/orphan/multiple active placement; validasi resolver terhadap source. | **PARTIALLY VERIFIED** | Hardblock/membership dapat melewatkan atau salah memasukkan siswa. |
| TE-18 | Seluruh mutation path dan writer NIS/username | Pencarian read-only menemukan, antara lain, PPDB `PendaftarController`, TU `SiswaController`, `SiswaImport`, `SiswaSheetImport`, `GenerateUsername`; bukan bukti semua jalur telah ditutup. | Call-site audit menyeluruh routes/controllers/services/jobs/commands/import/export/model events/raw SQL; build writer map sebelum code. | **PARTIALLY VERIFIED** | Jalur alternatif dapat bypass cohort lock/issuer. |
| TE-19 | Runtime engine capabilities dan backup/restore | Konfigurasi berisi SQLite/MySQL/MariaDB/PostgreSQL/SQL Server; backup/restore drill deployment tidak diperiksa. | Ops mengidentifikasi engine; verifikasi katalog/feature dan latihan restore staging dari backup tervalidasi. | **UNVERIFIED** | Recovery/data-retention risk. |
| TE-20 | Batas volume batch dan durasi transaksi | Belum ada metrik jumlah candidate, lock duration, log/undo capacity atau load test target. | Benchmark dataset sintetis pada staging representatif, bukan data produksi tanpa izin; tetapkan threshold/alert. | **UNVERIFIED** | All-or-nothing publish mungkin tidak operasional. |

## 5. Implementation Acceptance Criteria

Semua test di bawah adalah kriteria masa depan; belum dijalankan. `DB` = database constraint/trigger; `SVC` = domain service; `CTRL/UI` = endpoint/presentation; `TST` = test. Prioritas **P0** memblokir rilis/publisher, **P1** wajib untuk integrasi terkait, **P2** operasional/perawatan.

| ID | Komponen | Kondisi awal dan tindakan yang diuji | Hasil terukur yang diharapkan | Lapisan | Jenis | Prioritas |
|---|---|---|---|---|---|---|
| AC-DB-01 | A. Schema | Jalankan migration kosong pada setiap engine/version resmi; inspect schema. | Kolom/FK/unique/CHECK/index sesuai desain; FK siklus terpasang bertahap; no orphan; migration kedua kali tidak merusak. | DB, TST | migration/integration | P0 |
| AC-DB-02 | A. Schema | Backfill fixtures issued, legacy verified, legacy unknown dan duplicate/collision. | Unknown tetap `needs_verification`, tuple/slot NULL; exact NIS tidak berubah; invalid state ditolak sebelum publisher aktif. | DB, SVC, TST | migration/integration | P0 |
| AC-DB-03 | A. Schema | Sebelum insert permanen, lakukan down pada schema kosong; setelah memasukkan ledger fixture, coba destructive rollback. | Empty-only rollback melepas FK dalam reverse order; setelah ada registry/slot/history/reservation, drop ditolak atau prosedur mengharuskan forward-fix. | DB, ops, TST | migration | P0 |
| AC-COH-01 | B. Membership/locking | Mutasi membership, entry, placement, nama calon dan publish serentak untuk satu cohort. | Semua mengambil cohort lock yang sama; revision bertambah tepat sekali per transaksi perubahan relevan; preview stale ditolak. | SVC, DB, TST | feature/concurrency | P0 |
| AC-COH-02 | B. Membership/locking | Dua publisher membuat initial batch cohort sama bersamaan. | Tepat satu initial batch row; satu publisher memperoleh hasil/idempotent conflict terdefinisi; tidak ada batch kedua meskipun failed/cancelled. | DB, SVC, TST | concurrency/integration | P0 |
| AC-COH-03 | B. Membership/locking | Initial cutoff committed; official entry baru dibuat sesudah cutoff. | Candidate hanya eligible supplemental; NIS lama tetap; entry tanpa official timestamp tidak eligible. | SVC, DB, TST | feature | P0 |
| AC-COH-04 | B. Membership/locking | Ditemukan kandidat official-entry sebelum cutoff yang tidak ada pada initial batch. | Supplemental hardblock; tidak diam-diam menerbitkan siswa tersebut dan tidak mengubah cutoff. | SVC, TST | feature | P0 |
| AC-EH-01 | C. Entry history | Catat entry PPDB/manual/transfer setelah cohort lock; pakai timestamp permohonan/daftar ulang yang berbeda. | `official_entry_at` ditentukan setelah lock pada transaksi approval/entry; waktu permohonan tidak dipakai; UTC/logical precision memenuhi kontrak. | SVC, DB, TST | unit/integration | P0 |
| AC-EH-02 | C. Correction | Koreksi belum approved lalu approval/rejection berulang dengan request key sama. | Proposal pending tidak mengubah effective entry; append-only event; retry tidak membuat event/correction ganda; rejected tidak menjadi effective. | DB, SVC, TST | feature/integration | P0 |
| AC-EH-03 | C. Correction | Koreksi memindahkan entry antara cohort 2627 dan 2728 saat kedua cohort dipakai. | Kedua lock diambil sorted; revisi tiap cohort yang benar berubah; jika salah satu initial published, correction conflict append-only dan seluruh issued values tetap identik. | SVC, DB, TST | concurrency | P0 |
| AC-LEG-01 | D. Legacy | NIS canonical legacy bertabrakan exact dengan candidate; reservation tidak disetujui. | Publish berhenti; tidak ada slot/sequence issuance; string tidak diparse sebagai sejarah; attempt conflict tercatat. | SVC, DB, TST | integration | P0 |
| AC-LEG-02 | D. Reservation | Reservation approved dengan bukti, lalu proses allocator. | Slot reserved dibuat sekali dan atomik dengan approval; tuple legacy tidak diklaim; high-water tidak turun; rejected request tidak membuat slot. | DB, SVC, TST | feature/integration | P0 |
| AC-SEQ-01 | E. Sequence | Dua proses mengalokasikan pada tuple `(year_code, entry_grade)` yang sama bersamaan. | Dua nomor berbeda dan berurutan; unique tuple mencegah duplikat; rollback mengembalikan high-water/slot issuances sesuai transaksi. | DB, SVC, TST | concurrency | P0 |
| AC-SEQ-02 | E. Sequence | Dua rombel pada tingkat masuk/tahun yang sama diterbitkan dalam satu/berbagai batch. | Kedua rombel berbagi sequence ruang `(year_code, entry_grade)`; grade berbeda memiliki sequence independen; contoh nomor melewati 999 menjadi 1000 tanpa truncation. | SVC, DB, TST | integration | P0 |
| AC-REG-01 | F. Registry | Coba update/delete registry NIS/tuple/history link dan slot via SQL runtime role. | DB menolak; service tidak menyediakan mutator reissue; history/slot permanen. DBA superuser limitation didokumentasikan. | DB, SVC, ops, TST | integration | P0 |
| AC-REG-02 | F. Registry | Siswa memiliki NIS NULL, lalu publisher mengeluarkan NIS; coba ubah/hapus via aplikasi dan raw SQL. | Hanya NULL→nilai yang cocok dengan registry `issued` diterima; update berikutnya/clear/duplicate ditolak di kedua jalur. | DB, SVC, CTRL, TST | feature/integration | P0 |
| AC-REG-03 | F. Username | Username target telah dipakai akun lain. | Tidak auto-merge/rename; collision tercatat dan sesuai kebijakan owner (default hardblock); NIS/sequence tidak partial commit. | DB, SVC, TST | feature/integration | P0 |
| AC-PUB-01 | G. Initial publication | Siswa PPDB dan transfer resmi telah masuk sebelum initial; beragam grade/rombel; semua placement sah. | Satu initial lintas jalur/tingkat; sort comparator disetujui per `(year_code,entry_grade)`; NIS format benar dan batch snapshot/cutoff consistent. | SVC, DB, TST | feature/integration | P0 |
| AC-PUB-02 | G. Initial publication | Dua user publish initial bersamaan dengan batch request key sama/berbeda. | Tidak lebih dari satu initial batch; key sama idempotent; key berbeda mendapat existing batch/conflict sesuai contract; no double issue. | DB, SVC, TST | concurrency | P0 |
| AC-PUB-03 | G. Failure/retry | Paksa error setelah beberapa candidate di tengah transaksi publikasi. | Rollback registry, slot issued, siswa.nis, username, high-water, cutoff dan batch published state; attempt kegagalan dicatat sesudah rollback dalam transaksi terpisah. | DB, SVC, TST | integration | P0 |
| AC-PUB-04 | G. Retry | Retry gagal memakai request key sama, key baru, lalu retry batch yang telah published. | Key sama mengembalikan result tanpa issue ulang; key baru hanya attempt baru pada batch yang sama; published batch mengembalikan hasil committed tanpa perubahan. | DB, SVC, TST | feature/integration | P0 |
| AC-SUP-01 | H. Supplemental | Siswa baru resmi masuk setelah initial cutoff dan belum ber-NIS. | Hanya kandidat pasca-cutoff memperoleh nomor berikutnya; cutoff awal tidak berubah; belum ada NIS ganda. | SVC, DB, TST | feature | P0 |
| AC-SUP-02 | H. Supplemental | Dua kandidat masuk di waktu berbeda; publikasikan supplemental dalam urutan batch berbeda. | Order mengikuti kebijakan owner yang disahkan (rekomendasi official timestamp lalu nama/ID); hasil deterministic pada seluruh runtime comparator. | SVC, TST | integration | P0 |
| AC-SUP-03 | H. Supplemental / transfer | Siswa pindahan masuk secara resmi setelah initial publication dengan tingkat entry yang didukung bukti. | Kandidat hanya diproses sebagai supplemental; entry grade berasal dari persetujuan/evidence, bukan default kelas I; placement sah dan cutoff memenuhi syarat; NIS existing lain tidak berubah. | SVC, DB, TST | feature/integration | P0 |
| AC-PLACE-01 | I. Placement/hardblock | Siswa wajib ditempatkan tetapi belum punya placement rombel sah. | Preview/publish menampilkan hardblock; tidak ada NIS/sequence/registry yang commit. | SVC, CTRL, TST | feature | P0 |
| AC-PLACE-02 | I. Placement/hardblock | Status PPDB diterima/daftar ulang berlawanan dengan relasi Siswa atau PPDB. | Kandidat terdeteksi contradictory; publish berhenti; data tidak auto-repair. | SVC, DB, TST | integration | P0 |
| AC-PPDB-01 | J. PPDB | Daftar ulang/konfirmasi membuat siswa dan akun sebelum publication; email lama mengharuskan NIS. | Workflow tidak menerbitkan NIS; email pre-publish tidak mengandung NIS/password yang belum tersedia; setelah publish notifikasi idempotent/outbox sesuai owner decision. | SVC, CTRL, TST | feature | P0 |
| AC-TU-01 | K. TU/import | Manual TU/import per sheet mencoba mengisi NIS atau membuat siswa/placement. | Writer tidak dapat menetapkan NIS; semua membership/placement masuk service dan cohort lock; import legacy dipisah dari issuance baru. | SVC, CTRL, DB, TST | integration | P0 |
| AC-AUTH-01 | L. Login/relasi | Link User–Siswa cocok, conflict, duplicate, orphan; siswa belum ber-NIS. | Hanya relasi canonical yang dipakai; maksimum satu akun↔satu siswa; no fallback temporary ID tanpa keputusan; belum ada NIS berarti login sesuai policy ditolak. | DB, SVC, CTRL, TST | feature/integration | P0 |
| AC-AUTH-02 | L. Login/username | Username NIS terbit kemudian ada percobaan rename atau collision. | Username hanya dapat disinkronkan satu kali sesuai registry; perubahan dan akun collision tidak membuat relink otomatis. | DB, SVC, TST | integration | P0 |
| AC-DEL-01 | M. Delete/restore | Force-delete siswa setelah issuance; restore sebelum force-delete; create/relink siswa baru dengan NIS lama. | Soft-delete/restore tidak menerbitkan ulang; force-delete hanya memutus FK operasional sesuai `SET NULL`; registry, history, slot, NIS dan high-water tetap; create ulang NIS lama ditolak. | DB, SVC, TST | integration | P0 |
| AC-CON-01 | N. Concurrency | Dua transaksi mengunci cohort 2627/2728 dalam urutan berbeda yang diminta caller. | Coordinator selalu mengunci sorted; tidak ada transaksi yang mengambil lock out-of-order; uji deadlock/failure menghasilkan rollback dan bounded retry tanpa duplikasi. | SVC, DB, TST | concurrency | P0 |
| AC-CON-02 | N. Recovery | Putus koneksi/exception sesudah attempt `started`, sebelum/ketika publication commit. | `started` direkonsiliasi; hasil commit diperiksa dengan idempotency key sebelum retry; tidak ada issuance duplikat atau batch palsu published. | DB, SVC, TST | integration/concurrency | P0 |
| AC-OBS-01 | O. Audit/observability | Publish sukses, hardblock, collision, correction, force-delete, retry, email failure. | Actor/request key, outcome, error code, revision/snapshot/runtime sort version dan audit event tersedia; log tidak mengandung kredensial/PII berlebih. | SVC, DB, TST | feature/integration | P1 |
| AC-OBS-02 | O. Comparator/runtime | Jalankan golden fixture pada web/CLI dan OS yang disetujui. | Actual locale bukan `root`; output full-order identik dan metadata algoritma/version tercatat; perbedaan runtime menggagalkan publish. | SVC, TST | integration | P0 |

## 6. Dependency dan Implementation Order

### 6.1 Wajib sebelum coding/migration

1. Tutup OD-01/02/03/07/09/11/12/14/15/17 dan keputusan lain yang dibutuhkan pada komponen yang akan disentuh; hasil disimpan sebagai keputusan owner, bukan asumsi engineer.
2. Kumpulkan TE-01 sampai TE-19 untuk deployment/staging yang dituju; tidak membuka atau menampilkan secret.
3. Audit call graph semua writer dan inventaris legacy secara read-only. Siapkan backup/restore plan dan pemilik data untuk reconciliation.
4. Finalisasi kontrak format/mapping, lock order, nullable/correction semantics, approval roles dan policy account sebelum mengubah schema.

### 6.2 Urutan komponen

| Tahap | Deliverable/pekerjaan | Dependency / exit criteria |
|---|---|---|
| I-0 | Baseline dan environment evidence | Engine/runtime/schema target diketahui; backup restore drill dan status worktree direkam. |
| I-1 | Legacy reconciliation plan dan owner-approved mapping | Inventaris NIS/username/placement/relasi; collision dan data conflict punya disposition; tidak ada tuple ditebak. |
| I-2 | Migration foundation additive: cohort, history/correction, sequence, batch, registry, attempts, reservation requests/events, slots | Gunakan sequence FK bertahap §16.11: parent tersedia → nullable reference → FK balik → CHECK/trigger final. Writer belum aktif. |
| I-3 | User–Siswa canonical relation | Rekonsiliasi data, unique/FK, keputusan deprecate `users.siswa_id`; hanya setelah OD-11 disetujui. |
| I-4 | Database protections | Unique/check/trigger, initial-batch guard, least-privilege runtime grants, SET NULL behavior diuji pada engine target; jalankan migration tests. |
| I-5 | Domain foundations | Cohort coordinator/atomic bootstrap, global lock order, revision, logical UTC clock, entry-history service dan hardblock resolver. Tidak ada publisher dulu. |
| I-6 | Legacy reconciliation/reservation | Import exact strings, status/evidence, owner-approved slot reservations; high-water reconstruction tanpa reuse. |
| I-7 | Comparator dan sequence/registry publisher | Comparator golden-order terverifikasi; allocator memakai cohort+sequence locks; registry/slot/siswa NIS atomic; audit. |
| I-8 | Workflow integration | PPDB lalu TU/manual/import, placement/rollover, delete/restore/force-delete, username/login/mail; semua jalur menuju service tunggal, tidak ada writer lama. |
| I-9 | Test/recovery gates | Unit, feature, schema/migration, driver-target concurrency, failure/retry, authorization, load threshold; criteria P0 semua hijau. |
| I-10 | Staged activation | Dry-run/preview, reconciliation report tanpa PII berlebih, sign-off owner; aktifkan terbatas, monitor; rollout per cohort sesuai persetujuan. |

### 6.3 Transaksi publish dan failure logging

Simpan draft batch commit terlebih dahulu; buat attempt `started` dan commit dengan FK ke batch; transaksi publication berikutnya mengunci cohort dan menulis seluruh issuance/slot/high-water/account/batch success atomik. Bila gagal, rollback penuh, baru update attempt/batch outcome dalam transaksi terpisah. PostgreSQL unique violation tidak boleh ditangkap untuk query lanjutan sebelum rollback/savepoint. Jika outcome commit tidak diketahui, cari result dengan idempotency key sebelum retry.

### 6.4 Aktivasi dan rollback

Aktivasi hanya setelah schema constraints, legacy reconciliation, DB grants, comparator, target driver concurrency, outbox/retry, monitoring dan owner sign-off lulus. Jangan aktifkan writer jika ada P0 gagal. Setelah ledger permanen berisi data, tidak ada rollback migration destruktif; nonaktifkan publisher bila diperlukan dan lakukan forward-fix atau rollback aplikasi yang tetap kompatibel.

## 7. Critical Risks

| ID | Risiko | Tingkat | Mitigasi/gate |
|---|---|---|---|
| CR-01 | Sorting actual locale `root` atau runtime berbeda memberi urutan nama yang tidak disepakati; NIS tidak dapat diurut ulang setelah terbit. | Critical | Comparator bukti actual locale dan golden-order lintas runtime sebelum initial publication. |
| CR-02 | Engine deployment tidak diketahui; unique/FK/trigger/isolation/upsert assumptions dapat salah. | Critical | TE-01/08/09/10/11 diverifikasi dan integration concurrency pada engine/version resmi. |
| CR-03 | Legacy NIS/sequence collision dan data aktual tidak diinventaris; migration dapat gagal atau reuse number. | Critical | Read-only inventory termasuk soft-deleted, exact bytes, registry/username collision; owner-approved disposition sebelum publisher. |
| CR-04 | Dua relasi User–Siswa dapat bertentangan; login berdasarkan FK yang berbeda dapat menunjuk akun salah. | High | Reconcile dan owner decision OD-11; no auto-merge; canonical FK/unique dibuktikan. |
| CR-05 | Direct SQL atau writer alternatif melewati Laravel lock/service/immutability. | Critical | Call graph complete, runtime least privilege, trigger/DB protection dan direct SQL tests. |
| CR-06 | Entry/placement/PPDB contradictory atau transfer grade ditebak, menyebabkan cohort salah. | High | Definisi valid placement/evidence authority, resolver hardblock, audit record real. |
| CR-07 | Batch terlalu besar atau recovery salah setelah commit uncertain mengakibatkan timeout/partial state/duplikasi. | High | Benchmark threshold, all-or-nothing transaction, attempt/idempotency/reconciler tests. |
| CR-08 | Siklus FK atau trigger yang menolak ON DELETE SET NULL menggagalkan force-delete atau menghilangkan audit link. | High | Migration order dan FK+trigger behavior test pada engine resmi; ledger tidak pernah cascade-delete. |
| CR-09 | Timestamp skew/precision/equality memindahkan siswa ke batch yang salah. | High | DB clock UTC/logical monotonic contract dan failover test. |

## 8. Remaining Blockers

| ID | Blocker | Status penutupan yang diwajibkan | Menghalangi |
|---|---|---|---|
| BL-01 | Driver/engine/version deployment belum diverifikasi | TE-01/19 complete, ada owner/ops confirmation dan catalog evidence | Migration, constraints, concurrency |
| BL-02 | PHP web runtime/extensions belum diverifikasi; CLI `php` tidak tersedia pada PATH sesi ini | TE-02–TE-05 complete untuk web runtime target dan build runtime | Runtime integration/sorting |
| BL-03 | Comparator Indonesia belum memenuhi actual locale dan parity | OD-03 disetujui; TE-06/07/AC-OBS-02 lulus | Initial publication |
| BL-04 | Data NIS legacy, collision dan placement aktual belum diinventaris | TE-14/17 selesai secara agregat dan disposition owner disetujui | Migration/backfill/allocator |
| BL-05 | Relasi `siswa.user_id`/`users.siswa_id` dan orphan belum direkonsiliasi | TE-13/15 + OD-11 complete | Account migration/login integration |
| BL-06 | Hak akses direct DB dan trigger behavior tidak diketahui | OD-12 + TE-11/12; AC-REG direct SQL tests lulus | Publisher/immutability claims |
| BL-07 | Official timestamp/deployment year-code mapping belum dikonfirmasi | OD-14/15 dan target tests disahkan | Entry creation/cutoff |
| BL-08 | Policy supplemental, transfer evidence, placement validity, collision reservation, post-publish correction, username conflict belum disetujui | OD-05–10 dan OD-18 diselesaikan sesuai tahap terkait | Correction/transfer/supplemental/account release |
| BL-09 | Batas transaksi/volume dan recovery belum diukur | TE-20 dan AC-PUB-03/04/AC-CON-02 lulus | Production-scale publication |
| BL-10 | Belum ada bukti semua writer telah dipetakan/dapat dialihkan | TE-18 complete; semua mutation path masuk service dan lock protocol | Integrasi dan activation |

## 9. Final Readiness Verdict

# NOT READY FOR IMPLEMENTATION

Alasan:

1. Tidak semua owner decision yang mengendalikan comparator, akun, relasi canonical, timestamp, placement, collision, runtime dan hak DB telah disetujui.
2. Engine/runtime deployment, schema dan data NIS aktual, extension web, grant DB, serta concurrency target belum diverifikasi.
3. Sorting Indonesia belum terbukti; catatan sebelumnya actual locale `root`.
4. Inventaris legacy, NIS collision, User–Siswa mismatch, PPDB/placement inconsistency dan seluruh writer belum lengkap untuk keputusan migration.
5. Tidak ada bukti test driver-target yang membuktikan atomic publication, rollback/retry, FK-trigger behavior, atau immutability direct SQL.

Dokumen desain 2A.3 cukup menjadi dasar untuk menutup readiness gates, bukan otorisasi mulai membuat database/service. Tahap berikutnya **belum boleh mulai mengimplementasikan schema atau service NIS** sampai blocker yang memengaruhi komponen tersebut ditutup dan owner memberi persetujuan eksplisit.

## 10. Rekomendasi Tahap Berikutnya

1. Minta owner menjawab OD-01–OD-18 dengan urutan berdasarkan komponen; keputusan comparator, database/runtime, year mapping, timestamp, placement, dan User–Siswa perlu didahulukan.
2. Minta Ops/DBA menyediakan bukti TE-01–TE-12 pada staging/deployment yang disetujui, tanpa membuka secret. Gunakan query/catalog read-only untuk data dan schema.
3. Selesaikan TE-13–TE-18 berupa agregat data terlindungi dan mutation-path inventory; tetapkan pemilik/approver rekonsiliasi.
4. Setelah blocker evidence tertutup, lakukan design sign-off final, lalu implementasi dapat dimulai dalam tahap terpisah dengan migration driver-specific dan test plan AC-DB/AC-CON.
5. Jangan menjalankan migration, mengubah data, mengaktifkan writer, atau menerbitkan NIS sebelum seluruh P0 terkait telah lulus dan project owner menyetujui activation gate.

### Batas pekerjaan dan Git

Dokumen yang dibuat: `docs/audit/TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md`. Tidak ada source, migration, konfigurasi, database, test, dependency, atau file audit sebelumnya yang diubah; tidak ada test/migration/seeder/database operation dijalankan.

Git diperiksa read-only sebelum dan sesudah. Branch `main`, commit `b32f25f`, tidak ada staged changes. Perubahan lokal tracked dan file untracked yang sudah ada dipertahankan; dokumen readiness ini adalah file baru yang diminta dan tidak di-stage/commit.
