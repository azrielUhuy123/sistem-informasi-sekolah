# TAHAP 2A.12 — ENGINEERING BASELINE

**Sistem:** SINERGI-MJ  
**Jenis:** Baseline desain dan audit teknis read-only  
**Status:** `DESIGN BASELINE SELECTED` / `TECHNICAL VALIDATION PENDING` / `SCHOOL POLICY CONFIRMATION PENDING`  
**Implementation authorization:** `NOT AUTHORIZED`

## 1. Tujuan dan Batas

Dokumen ini mencatat pilihan engineering baseline OD-01–OD-18 untuk menyiapkan desain, audit readiness, dan urutan kerja. Pilihan ini bukan persetujuan formal sekolah, bukti bahwa source code sudah memenuhi rancangan, ataupun izin implementasi.

Pekerjaan 2A.12 hanya membaca dokumen, source, migration, konfigurasi, dan test yang telah ada. Tidak ada source, konfigurasi, dependency, data, atau schema yang diubah; tidak ada query database, migration, atau test yang dijalankan. Rekomendasi penggunaan desain ini tetap tunduk pada konfirmasi policy owner dan validasi teknis yang terpisah.

### Status yang dipisahkan

| Status | Arti pada dokumen ini |
|---|---|
| `DESIGN BASELINE SELECTED` | Pilihan desain untuk menjadi arah kerja engineering; bukan approval sekolah. |
| `TECHNICAL VALIDATION PENDING` | Acceptance evidence, target environment, atau pengujian implementasi belum tersedia. |
| `SCHOOL POLICY CONFIRMATION PENDING` | Policy owner/approver formal belum teridentifikasi atau keputusan sekolah belum dibuktikan. |
| `TECHNICALLY VERIFIED` | Hanya dapat digunakan setelah bukti yang sesuai target tersedia. Belum diberikan oleh dokumen ini. |
| `IMPLEMENTATION READY` | Tidak diberikan; seluruh readiness gate belum terpenuhi. |

Semua OD-01–OD-18 tetap `PENDING` pada approval register. BL-01–BL-10 serta SRC-GAP-01/02 tetap `OPEN`.

## 2. Engineering Baseline OD-01–OD-18

| OD | Engineering design baseline terpilih | Rationale dan invariant | Batas konfirmasi/validasi |
|---|---|---|---|
| **OD-01 — Database engine** | MySQL 8.0.30/InnoDB adalah baseline development/testing lokal yang diketahui. Production target belum dipilih; DDL khusus engine ditunda sampai target tersedia. | Transaksi dan row locking dirancang terhadap engine yang ditetapkan, bukan diasumsikan lintas-engine. | Bukti 2A.6 hanya untuk koneksi lokal. Target production, versi, fitur constraint, isolation, recovery, dan parity masih harus dibuktikan. |
| **OD-02 — Runtime** | PHP 8.2+ dengan parity web, CLI, worker, dan CI yang dibuktikan. | Comparator, extension, database driver, transaksi, dan worker tidak boleh berubah diam-diam antar-SAPI. | Lokal: PHP CLI 8.2.29 dan `artisan serve` teramati dengan binary yang sama; handler Apache, runtime worker/CI, dan production belum diverifikasi. |
| **OD-03 — Name comparator** | ICU `Collator` locale `id-ID`; versi ICU dan collation data dipin; expected golden order ditentukan dan diuji. Actual locale harus diperiksa. Jika bukan locale yang disyaratkan, penerbitan fail-closed; tanpa fallback diam-diam. | Initial ordering harus repeatable lintas runtime dan menggunakan comparator yang disahkan, bukan urutan database collation atau urutan incidental. | Audit lokal mencatat ICU 71.1 tetapi actual locale `root`; ini belum memenuhi baseline dan memblokir publication sampai runtime/collation yang sesuai serta golden order tervalidasi. |
| **OD-04 — Account lifecycle** | Asynchronous provisioning dengan transactional outbox. Intent akun harus commit atomik bersama publication; worker berjalan setelah commit. NIS tetap permanen jika provisioning gagal. Login ditolak sebelum akun/relasi valid. Retry idempotent memakai intent key yang sama. Notification intent/delivery terpisah dan kegagalannya tidak membatalkan NIS. Tidak menggunakan username reservation. | Ini mempertahankan lifecycle async AC-ACC-02-B. Status worker attempt tidak menggantikan provisioning lifecycle; retry tidak pernah mengalokasikan NIS baru. | Baseline pilihan tidak mengubah OD-04 formal yang masih `PENDING`. State dan transition manual-review yang tidak ditentukan AC-ACC-02-B tetap `NEEDS_CLARIFICATION`; jangan menciptakan transisi baru. |
| **OD-05 — Correction** | Request → verification → separate approval. Koreksi append-only; NIS dan sequence slot immutable; tidak ada reissue/reuse. | Koreksi metadata tidak boleh menulis ulang identitas historis atau menghilangkan jejak perubahan. | Jenis bukti, otoritas/verifier/approver, SLA, dan audit acceptance masih menunggu kebijakan dan otorisasi formal. |
| **OD-06 — Legacy NIS** | Fail-closed. Tidak ada auto-remap, auto-normalization, auto-skip, atau auto-reservation. Collision memerlukan evidence dan manual reconciliation. Inventory mencakup aktif, soft-deleted, dan bukti historis yang tersedia. | Nilai legacy tidak boleh dianggap aman hanya dari format atau snapshot aktif; nomor yang sudah diterbitkan harus terlindungi. | 2A.6 hanya mengukur satu snapshot lokal yang sparse. Provenance, riwayat force-delete, completeness, dan disposition collision belum diverifikasi. |
| **OD-07 — Transfer evidence** | Entry year dan entry grade berdasarkan evidence masuk ke SDN Mekar Jaya; kelas saat ini atau tahun sekolah asal tidak menggantikan evidence. Evidence tidak cukup berarti hardblock. Transfer sebelum initial publication dapat memenuhi eligibility initial batch; transfer setelahnya supplemental. | Siswa pindahan tidak diasumsikan mulai kelas 1. | Jenis dokumen, verifier/approver, serta aturan waktu/pengecualian menunggu owner. |
| **OD-08 — Supplemental ordering** | Urutan baseline: `official_entry_at → permanent cohort ordinal → comparator → siswa.id`. Ordinal ditetapkan permanen, tidak dihitung ulang, dan assignment berada dalam cohort lock. Timestamp tie harus deterministic. | Timestamp administratif, sequence ordinal, comparator, dan final tie-break harus menghasilkan hasil yang stabil. | Klarifikasi teknis diperlukan tentang keunikan/domain ordinal dan apakah comparator memang dapat dicapai sebagai tie-break setelah ordinal. Ini tidak diubah dengan asumsi. |
| **OD-09 — Valid placement** | Tepat satu placement aktif yang valid; tahun ajaran, kelas, dan grade konsisten. Missing, duplicate, inactive, atau contradictory placement menjadi hardblock tanpa exception otomatis. | Eligibility harus dibuktikan sebelum alokasi dan publication. | Status/tanggal placement yang dianggap valid serta otoritas exception belum disetujui. Local snapshot kecil bukan bukti resolver lengkap. |
| **OD-10 — Username reservation** | Reservation tidak digunakan. Collision di-hardblock dan direkonsiliasi manual; tidak ada auto-merge, auto-rename, atau auto-link. OD-04 mengikuti keputusan ini. | Reservation policy dimiliki OD-10; tidak ada kebijakan reservation kedua yang dibuat melalui OD-04. | OD-10 tetap `PENDING`; collision/reconciliation policy dan approval authority tetap menunggu konfirmasi sekolah. |
| **OD-11 — Canonical User–Siswa** | `siswa.user_id` menjadi canonical relationship dan harus one-to-one. `users.siswa_id` diperlakukan sebagai legacy/redundant projection. Tidak ada auto-link/backfill sebelum reconciliation. | Login/provisioning harus menggunakan relasi canonical yang disetujui; seluruh reader/writer harus konsisten. | Skema lokal memiliki kedua FK nullable non-unique dan controller PPDB menulis dua arah. Data reconciliation dan constraint enforcement belum dilakukan. |
| **OD-12 — Database privileges** | Pisahkan runtime application account, migration/deployment account, dan DBA/break-glass account. Runtime least privilege. Evaluasi direct SQL, trigger, constraint, dan ledger immutability. Catat superuser sebagai residual risk. | Aplikasi runtime tidak boleh dapat mengubah/menghapus ledger permanen melalui privilege luas. | 2A.6 mencatat grant lokal global `*.*` dengan hak DML/DDL/admin dan `GRANT OPTION`; target-role matrix/deny tests belum ada. Tidak ada perubahan grant dilakukan. |
| **OD-13 — Atomic publication** | Initial publication all-or-nothing. NIS, registry, sequence slots, cohort cutoff, batch success, dan transactional intents terkait memenuhi satu atomic visibility contract. Staging/chunking bukan publication. Jika final atomic visibility tidak terbukti, pendekatan tersebut tidak setara. Numeric cap menunggu benchmark. | Tidak boleh ada partial publication yang terlihat pengguna. Publication dianggap sukses hanya pada final commit yang memenuhi invariant keseluruhan. | Implementasi, rollback/recovery/uncertain-commit, target volume dan benchmark belum dibuktikan. |
| **OD-14 — Timestamp** | Pisahkan `recorded_at` (teknis), `effective_at` (administratif), dan `official_entry_at` (kronologi resmi). Timestamp teknis UTC. `official_entry_at` ditetapkan sesudah cohort lock dan entry validation dalam transaksi. | Administrative effective date tidak menggantikan kronologi resmi; ordering tidak bersandar pada jam lokal yang tidak tervalidasi. | Precision, clock authority/skew, equality, cutoff, watermark, serta hubungan timestamp dengan ordinal masih perlu validasi teknis/kebijakan. |
| **OD-15 — Year-code mapping** | Mapping academic-year eksplisit dan tervalidasi; pertahankan `2026/2027 → 2627`. Jangan infer dari tanggal penerbitan/kelas saat ini. Mapping unknown/ambiguous fail-closed. | Satu cohort/year label harus menghasilkan year-code konsisten dan dapat diaudit. | Mapping umum dan aturan rollover harus memiliki fixtures dan persetujuan policy; kode saat ini menurunkan prefix dengan parsing string tahun. |
| **OD-16 — Delete, restore, relink** | Soft-delete default. Registry/sequence slot permanen. Force-delete tidak menghapus ledger. Restore mempertahankan identitas/NIS yang sama setelah verifikasi. Recreate/relink membutuhkan evidence dan approval; tidak ada slot reuse. | Siswa record yang dihapus tidak mengosongkan nomor identitas yang telah diterbitkan. | Controller saat ini menyediakan force-delete; tabel ledger belum ada. Retention, FK behavior, restoration dan relink tests belum diverifikasi terhadap baseline. |
| **OD-17 — Initial batch** | Satu initial batch per cohort, cohort lock dan unique guard. Retry menggunakan logical batch yang sama. Tidak membuat initial kedua otomatis. Semua siswa wajib memenuhi eligibility sebelum publication; missing placement/conflict hardblock. | Satu cohort tidak dapat secara bersamaan/berulang menghasilkan initial publication kedua atau batch parsial. | Tidak ada batch/guard/lock di source/schema yang diperiksa. Cutoff, cancellation/reopen, eligibility completeness, dan concurrency tetap menunggu owner/technical proof. |
| **OD-18 — Separation of duties** | Pisahkan preparation, verification, publication, correction approval, exception approval, dan audit; cegah self-approval untuk tindakan kritis. | Setiap tindakan berisiko memiliki pemohon, pemeriksa, dan approver yang dapat diaudit dan tidak diasumsikan sama. | Nama/jabatan/mandat formal tidak ditemukan di dokumen 2A.11; developer/operator tidak ditetapkan sebagai policy owner. |

## 3. Konsistensi Baseline terhadap Account Lifecycle

Pilihan OD-04 asynchronous transactional outbox dan OD-10 no reservation konsisten: tidak ada Hybrid reservation yang aktif. Baseline outbox tidak berarti reservation, dan tidak mengubah NIS jika worker gagal.

Lifecycle yang dipertahankan:

- Publication transaction mencatat NIS dan intent provisioning secara durable/atomik.
- Setelah commit, worker memproses intent yang sama secara idempotent.
- `pending`, `processing`, `succeeded`, `failed`, `retryable`, dan `failed_manual_review` tetap mengikuti AC-ACC-02-B; hasil satu worker attempt berbeda dari lifecycle provisioning.
- Belum ada transition manual-review tambahan yang ditetapkan.
- Notification delivery terpisah dari intent provisioning dan tidak membatalkan publication.

**Status:** konsistensi desain `PASS WITH OPEN ITEMS`; implementasi async/outbox/login gate belum diverifikasi.

## 4. Bukti dan Batas Audit

Bukti desain dan kebijakan terdahulu: [2A.10 Owner Decision Package](./TAHAP_2A_10_OWNER_DECISION_PACKAGE.md), [2A.10 Dependency Matrix](./TAHAP_2A_10_DECISION_DEPENDENCY_MATRIX.md), [2A.10 Approval Register](./TAHAP_2A_10_APPROVAL_REGISTER.md), [2A.8R1 correction](./TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md), [2A.8R1 traceability](./TAHAP_2A_8R1_TRACEABILITY_MATRIX.md), [2A.9 historical recovery](./TAHAP_2A_9_HISTORICAL_SOURCE_RECOVERY.md), [2A.9 decision traceability](./TAHAP_2A_9_HISTORICAL_DECISION_TRACEABILITY.md), [2A.11 consistency review](./TAHAP_2A_11_DECISION_CONSISTENCY_REVIEW.md), dan [2A.11 authority matrix](./TAHAP_2A_11_AUTHORITY_AND_APPROVER_MATRIX.md).

Source and local environment evidence is summarized in [2A.6 Local Environment & Schema Verification](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md). Local evidence is not target/production evidence. Original 2A.1 and 2A.2 sources remain unavailable per 2A.9; SRC-GAP-01/02 remain open.

## 5. Final Status

- **ENGINEERING BASELINE:** `COMPLETE`
- **BASELINE CONSISTENCY:** `PASS WITH OPEN ITEMS`
- **TECHNICAL READINESS:** `NOT READY`
- **SCHOOL POLICY CONFIRMATION:** `PENDING`
- **IMPLEMENTATION AUTHORIZATION:** `NOT AUTHORIZED`

The design baseline is recorded for planning only. No OD was changed to `APPROVED`; no blocker or historical gap was closed.
