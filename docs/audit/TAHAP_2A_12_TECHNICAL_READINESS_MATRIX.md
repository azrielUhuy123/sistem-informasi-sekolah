# TAHAP 2A.12 — TECHNICAL READINESS MATRIX

**Jenis:** Audit teknis read-only  
**Status keseluruhan:** `TECHNICAL VALIDATION PENDING` / `NOT READY`  
**Execution:** Source, migration, konfigurasi, dokumen, dan test dibaca; tidak ada database query, test, migration, atau perubahan aplikasi yang dilakukan.

## 1. Arti Status

- `VERIFIED`: hanya aspek spesifik yang didukung bukti langsung; tidak otomatis berarti target/production siap.
- `PARTIALLY VERIFIED`: ada bukti terbatas/lokal atau sebagian jalur, tetapi acceptance contract belum lengkap.
- `NOT VERIFIED`: belum ada bukti yang cukup atas kemampuan atau perilaku yang diminta.
- `BLOCKED`: terdapat bukti langsung bahwa syarat baseline saat ini gagal atau fail-closed gate harus menghentikan publication.
- `NOT APPLICABLE`: kebutuhan tidak berlaku pada opsi desain yang dipilih. Tidak digunakan untuk mengecilkan gap.

`Evidence` memisahkan fakta source/audit dari baseline pilihan. Snapshot DB lokal yang sparse tidak digunakan untuk klaim kapasitas.

## 2. Readiness Matrix

| # | Kebutuhan | Status | Bukti yang tersedia | Gap / implikasi terhadap baseline |
|---:|---|---|---|---|
| 1 | Database engine dan transaction capabilities | `PARTIALLY VERIFIED` | [2A.6](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md) membuktikan koneksi lokal MySQL 8.0.30/InnoDB, `REPEATABLE-READ`, dan metadata schema. `DB::transaction()` dipakai oleh PPDB, TU, dan importer. | Production engine belum ditentukan. Tidak ada uji target untuk isolation, row-lock contention, deadlock/retry, uncertain commit, rollback/recovery, atau constraint semantics. Engine-specific DDL ditunda. |
| 2 | PHP, SAPI, ICU, dan extension parity | `PARTIALLY VERIFIED` | 2A.6: CLI PHP 8.2.29, `intl`/`Collator` dan ICU 71.1 tersedia; local `artisan serve` memakai binary sama. | Apache PHP handler/vhost, worker runtime, CI, production, ini/extensions dan artifact parity belum diverifikasi. |
| 3 | Comparator, locale actual, dan golden ordering | `BLOCKED` | 2A.6 mencatat `Collator('id-ID')` menghasilkan actual locale `root`; synthetic sort observations bukan approved golden order. Pencarian source tidak menemukan comparator NIS/golden test. | Baseline mensyaratkan actual locale sesuai dan fail-closed. Saat ini locale evidence tidak memenuhi syarat; jangan publish sampai locale/data runtime yang benar, expected fixtures, pinning, dan lintas-runtime parity tervalidasi. |
| 4 | Transactional outbox feasibility | `NOT VERIFIED` | [queue config](../../config/queue.php) memiliki pilihan database queue dan migration `jobs`; config menyatakan `after_commit` false. Tidak ditemukan outbox table/model/job ataupun callsite worker dalam source. | Queue backend bukan transactional outbox. Perlu intent durable custom, unique/idempotency key, commit bersama publication, dan dispatch/worker sesudah commit. Target queue deployment belum dibuktikan. |
| 5 | Worker retry dan idempotency | `NOT VERIFIED` | Tidak ditemukan job worker provisioning, `ShouldQueue`, retry policy, `retryUntil`, maupun intent key yang terkait penerbitan NIS. AC-ACC-02-B tersedia sebagai rancangan dokumen. | Implementasi harus menjaga state AC-ACC-02-B, memisahkan attempt result dari lifecycle, mempertahankan intent key yang sama, dan tidak pernah memanggil allocator pada retry. State transisi manual-review yang belum ditetapkan tetap perlu klarifikasi. |
| 6 | Login gate dan account lifecycle | `PARTIALLY VERIFIED` | [LoginRequest](../../app/Http/Requests/Auth/LoginRequest.php) menemukan siswa via NIS → `Siswa::with('user')`, kemudian mengecek approval status dan `is_active`. PPDB saat ini membuat user approved/active secara sinkron. | Tidak ada gate terhadap lifecycle provisioning async atau validasi satu relasi canonical; pending provisioning semantics tidak tersedia. Perlu memastikan login ditolak sampai akun dan relasi valid, termasuk collision/orphan/mismatch, tanpa mengubah NIS publication outcome. |
| 7 | Canonical User–Siswa migration impact | `PARTIALLY VERIFIED` | [Siswa model](../../app/Models/Siswa.php) memiliki `user_id`; [User model](../../app/Models/User.php) dan migration memiliki `users.siswa_id`. PPDB menulis kedua arah; login membaca `Siswa.user`; `GenerateUsername` mencari siswa via `user_id`. 2A.6 mencatat kedua FK nullable dan non-unique pada snapshot lokal. | Baseline memilih `siswa.user_id` canonical, tetapi complete data reconciliation, all-reader/writer map, one-to-one constraint, safe transition, dan auth tests belum selesai. Tidak boleh auto-link/backfill sebelum reconciliation. |
| 8 | Legacy NIS inventory dan reconciliation | `PARTIALLY VERIFIED` | 2A.6 melakukan inventory agregat lokal, termasuk siswa aktif dan soft-deleted; snapshot memiliki satu Siswa aktif dan tidak ada anomali struktural terukur. | Provenance, cohort/entry-grade evidence, historical/force-deleted values, completeness sumber data sekolah dan collision terhadap calon sequence belum terverifikasi. Tidak ada auto-remap/normalization/reservation. |
| 9 | Placement validation dan cohort locking | `PARTIALLY VERIFIED` | [PenempatanSiswaController](../../app/Http/Controllers/Tu/PenempatanSiswaController.php) mengecek kesesuaian angkatan pada assign/naik kelas; PPDB menempatkan ke kelas tingkat I; importers resolve angkatan dari tingkat. | Syarat placement untuk transfer/initial eligibility berbeda dari current controller checks. Tidak ada cohort lock; schema lokal tidak memiliki unique aktif placement constraint. Perlu definisi valid placement, complete data preflight, concurrent locking, dan hardblock tests. |
| 10 | Sequence registry dan permanent slot | `NOT VERIFIED` | `siswa.nis` memiliki unique index pada initial migration dan schema lokal 2A.6. `generateNis()` pada [PendaftarController](../../app/Http/Controllers/PPDB/PendaftarController.php) membaca nilai terakhir berdasarkan string prefix dan menambah angka. | Tidak ditemukan NIS registry, permanent sequence slot, issued-value ledger, cohort lock, overflow handling, atau immutability enforcement di database. Unique Siswa saja tidak mencegah reuse setelah force delete. |
| 11 | Timestamp authority dan ordering | `NOT VERIFIED` | Source menggunakan `now()` untuk `tanggal_masuk`, `tanggal_keluar`, dan timestamp model; [generateNis](../../app/Http/Controllers/PPDB/PendaftarController.php) memetakan tahun dari record tahun ajaran pendaftar. | Tidak ada `official_entry_at`, explicit `recorded_at/effective_at` contract, UTC proof, clock authority/skew/precision, deterministic tie rules, cutoff atau watermark validation. |
| 12 | Atomic publication dan benchmark | `PARTIALLY VERIFIED` | Konfirmasi daftar ulang membungkus pembuatan angkatan, user, Siswa, relasi, dan link PPDB dalam transaksi DB; test source memiliki rollback-on-Siswa-create-failure coverage. | Ini transaksi satu pendaftar, bukan all-or-nothing cohort publication. Tidak ada registry/slots/batch/outbox dalam transaksi, no partial-visibility proof, uncertain-commit/recovery drill, volume benchmark atau approved numeric cap. |
| 13 | Exactly-one initial batch per cohort | `NOT VERIFIED` | Existing confirmation idempotency hanya menolak pendaftar yang telah memiliki `siswa_id`; tidak ada initial-batch entity/unique cohort guard yang ditemukan. | Perlu unique batch-per-cohort, cohort lock, eligibility/cutoff snapshot, retry terhadap logical batch yang sama, duplicate/concurrency tests, dan kebijakan cancellation/reopen. |
| 14 | Database privilege separation | `PARTIALLY VERIFIED` | 2A.6 memeriksa grants lokal dan menemukan hak global `*.*` termasuk DML/DDL/admin dan `WITH GRANT OPTION`. | Fakta ini menegaskan risiko lokal, bukan target privilege matrix. Runtime/migration/DBA roles, least-privilege denial, direct SQL/trigger behavior, dan break-glass audit belum diimplementasikan atau diuji. |
| 15 | Soft delete, force delete, restore, relink | `PARTIALLY VERIFIED` | [Siswa](../../app/Models/Siswa.php) memakai `SoftDeletes`; [SiswaController](../../app/Http/Controllers/Tu/SiswaController.php) menyediakan delete, restore, dan force-delete. `siswa.nis` unique hanya pada tabel Siswa. | Force-delete menghapus row pemegang NIS; tidak ada ledger yang mempertahankan nilai/slot. Restore/relink identity verification/approval tidak ditegakkan oleh writer yang diperiksa. Baseline permanent slot belum dipenuhi. |
| 16 | Authorization dan separation of duties | `PARTIALLY VERIFIED` | TU/PPDB routes memiliki middleware; approval middleware dan `UserPolicy` tersedia. | Tidak ditemukan decision-specific separation untuk preparation, verification, publication, correction/exception approval dan audit; tidak ada bukti formal owner/approver. Authorization allow/deny, self-approval, audit-event dan authority matrix acceptance belum diuji. |

## 3. Kode NIS yang Teramati dan Dampaknya

### 3.1 Penerbitan saat daftar ulang PPDB

- `PendaftarController::generateNis()` membentuk year prefix dari `PpdbPendaftar.tahun_ajaran_id`, menetapkan `$tingkat = 1`, mencari NIS terbesar dengan prefix serupa, lalu menambah tiga-digit suffix.
- `konfirmasiDaftarUlang()` mengunci row pendaftar saja, lalu memanggil generator dan membuat akun/Siswa dalam satu transaksi. Row lock tersebut bukan cohort/sequence lock.
- Pendaftar yang tidak sama dapat berjalan bersamaan; current implementation does not serialize allocation by cohort/prefix. Unique indexes may reject a duplicate, but code contains no allocator retry/reconciliation contract.
- NIS dibuat sebelum placement; placement PPDB dilakukan pada workflow/controller terpisah dan menerima kelas tingkat `I`.
- Sequence continuation/format tests are sequential, not a concurrency proof or alphabetic-order proof.

### 3.2 Pembuatan TU, import, perubahan identitas

- `Tu\SiswaController::store()` menerima NIS dari request secara manual; tidak menggunakan generator.
- `SiswaImport` dan `SiswaSheetImport` mengambil NIS dari file dan menggunakan `firstOrCreate(['nis' => ...])`. Pada existing NIS, attributes bukan key tidak dipakai untuk menimpa record Siswa; import tetap dapat menambahkan/mengubah placement.
- Pencarian source menemukan route pemanggil `SiswaImport`; tidak menemukan callsite untuk `SiswaSheetImport` pada PHP repository. Integrasi eksternal tidak dapat dikesampingkan.
- `SiswaController::update()` menyatakan NIS `missing`, sedangkan `SiswaNisImmutabilityTest` menguji update nama/field lain dan penolakan NIS yang dikirim.
- Kenaikan kelas mengubah placement, bukan NIS, pada jalur yang diperiksa. Tidak ada bukti NIS registry untuk melindungi histori di luar row Siswa.

### 3.3 Test yang ditemukan (tidak dijalankan)

| Test file | Coverage source yang terlihat | Gap terhadap baseline |
|---|---|---|
| [SiswaNisImmutabilityTest](../../tests/Feature/SiswaNisImmutabilityTest.php) | TU dapat membuat NIS manual/menolak duplicate; update field lain mempertahankan NIS; request update NIS ditolak; form tidak menyediakan field edit NIS. | Tidak mencakup rename endpoint/import changes, soft/force delete/reuse, restore/relink, legacy ledger, transfer, cohort sorting, publication transaction. |
| [PpdbDaftarUlangWorkflowTest](../../tests/Feature/PpdbDaftarUlangWorkflowTest.php) | Workflow accepted/re-enrollment, year-based format, sequential same-year continuation, row rollback saat Siswa create gagal, serta mail failure behavior. | Tidak membuktikan alphabetic initial ordering, placement-before-issue, cohort/sequence concurrency, collision retry, immutable NIS, transactional outbox/worker, initial batch exactly-once. |
| [PpdbDiterimaExportTest](../../tests/Feature/PpdbDiterimaExportTest.php) | Export heading checks include NISN exclusion. | Bukan generator/identity integrity coverage. |
| [AnnualRolloverTest](../../tests/Feature/AnnualRolloverTest.php), [TahunAjaranSemesterTransitionTest](../../tests/Feature/TahunAjaranSemesterTransitionTest.php), [KeputusanKenaikanUniqueTest](../../tests/Feature/KeputusanKenaikanUniqueTest.php) | Coverage berorientasi placement, rollover, dan uniqueness keputusan akademik. | Tidak memverifikasi NIS berubah/tidak berubah, ledger/slot permanence, atau batch publication. |

Pencarian tests juga tidak menemukan golden comparator fixtures, outbox/provisioning worker tests, NIS force-delete/reuse tests, allocator concurrency/deadlock/retry tests, registry immutability tests, or initial-batch uniqueness tests. Tidak ada tests yang dijalankan dalam tahap ini.

## 4. Readiness Verdict

- **TECHNICAL READINESS:** `NOT READY`
- **IMPLEMENTATION AUTHORIZATION:** `NOT AUTHORIZED`
- **SCHOOL POLICY CONFIRMATION:** `PENDING`
- **Sumber historis 2A.1/2A.2:** tidak ditemukan menurut [2A.9](./TAHAP_2A_9_HISTORICAL_SOURCE_RECOVERY.md); SRC-GAP-01/02 tetap `OPEN`.

Prioritas blocker teknis: comparator locale fail-closed; target runtime/DB belum ditetapkan; tidak ada registry/sequence ledger, cohort lock, batch guard, outbox worker, complete legacy reconciliation, dan target privilege/recovery evidence.
