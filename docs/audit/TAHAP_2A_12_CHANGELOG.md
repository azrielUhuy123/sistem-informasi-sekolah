# TAHAP 2A.12 — CHANGELOG

## 1. Dokumen Dibuat

1. [Engineering Baseline](./TAHAP_2A_12_ENGINEERING_BASELINE.md)
2. [Technical Readiness Matrix](./TAHAP_2A_12_TECHNICAL_READINESS_MATRIX.md)
3. [Implementation Dependency Plan](./TAHAP_2A_12_IMPLEMENTATION_DEPENDENCY_PLAN.md)
4. [Baseline Conflict Register](./TAHAP_2A_12_BASELINE_CONFLICT_REGISTER.md)
5. Dokumen changelog ini.

Tidak ada dokumen 2A.10/2A.11 lama yang diubah.

## 2. Dokumen yang Ditelaah

Dokumen sumber yang tersedia dan ditemukan di `docs/audit/`:

- `TAHAP_2A_10_OWNER_DECISION_PACKAGE.md`
- `TAHAP_2A_10_DECISION_DEPENDENCY_MATRIX.md`
- `TAHAP_2A_10_APPROVAL_REGISTER.md`
- `TAHAP_2A_10R_CHANGELOG.md`
- `TAHAP_2A_10R1_CHANGELOG.md`
- `TAHAP_2A_11_OWNER_APPROVAL_SESSION_GUIDE.md`
- `TAHAP_2A_11_OWNER_DECISION_WORKSHEET.md`
- `TAHAP_2A_11_AUTHORITY_AND_APPROVER_MATRIX.md`
- `TAHAP_2A_11_DECISION_CONSISTENCY_REVIEW.md`
- `TAHAP_2A_11_CHANGELOG.md`
- `TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md`
- `TAHAP_2A_8R1_TRACEABILITY_MATRIX.md`
- `TAHAP_2A_9_HISTORICAL_SOURCE_RECOVERY.md`
- `TAHAP_2A_9_HISTORICAL_DECISION_TRACEABILITY.md`
- `TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md`
- `TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md`
- `TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md`

Prompt Tahap 2A.12 dari attachment juga digunakan. Naskah asli 2A.1/2A.2 tidak ditemukan; tidak direkonstruksi. Temuan terkait tetap `SRC-GAP-01/02 OPEN`.

## 3. Ruang Audit Source yang Dibaca

Pembacaan read-only mencakup:

- PPDB penerbitan: `app/Http/Controllers/PPDB/PendaftarController.php`, `routes/ppdb-admin.php`.
- TU siswa dan deletion/import: `app/Http/Controllers/Tu/SiswaController.php`, `routes/tu.php`.
- Workbook: `app/Imports/SiswaImport.php`, `app/Imports/SiswaSheetImport.php`.
- Placement/naik kelas: `app/Http/Controllers/Tu/PenempatanSiswaController.php`.
- Identity/login: `app/Models/Siswa.php`, `app/Models/User.php`, `app/Http/Requests/Auth/LoginRequest.php`, `app/Console/Commands/GenerateUsername.php`.
- Queue/database configuration and selected migrations for Siswa, User, placement, jobs, username, and soft delete.
- Existing feature tests for NIS immutability and PPDB sequence/workflow; related annual placement tests.

Tidak semua jalur di luar repository, direct SQL, atau deployment runtime dapat dibuktikan dari source lokal.

## 4. Ringkasan Perubahan Dokumen

- Mencatat pilihan engineering design baseline OD-01–OD-18 secara eksplisit, terpisah dari policy approval dan technical validation.
- Mempertahankan AC-ACC-02-B dan pemisahan provisioning lifecycle dari attempt result; memilih async + transactional outbox dan no username reservation sebagai design baseline, bukan persetujuan sekolah.
- Menetapkan actual ICU locale `root` sebagai fail-closed readiness blocker terhadap baseline comparator `id-ID`.
- Menyusun 16 baris technical readiness berdasarkan evidence source/audit dan menandai gap target tanpa menjalankan validation.
- Menyiapkan workstreams A–I dengan tujuan, prasyarat, kemungkinan modul/file terdampak, perubahan data yang mungkin, risiko, acceptance criteria, dependensi, dan urutan.
- Membedakan konflik internal baseline dari implementation gaps dan unresolved/residual risks. Tidak ada formal internal baseline contradiction yang terkonfirmasi; GAP-01–GAP-10 dan OPEN-01–OPEN-06 dicatat eksplisit.

## 5. Status yang Tidak Berubah

- OD-01–OD-18: tetap `PENDING` dalam approval register.
- BL-01–BL-10: tetap `OPEN`.
- SRC-GAP-01/02: tetap `OPEN`.
- Owner authority: `NOT IDENTIFIED`.
- School policy confirmation: `PENDING`.
- Technical validation: `PENDING`.
- Implementation authorization: `NOT AUTHORIZED`.

Tidak ada persetujuan, blocker closure, historical recovery, benchmark, atau target-environment verification yang diklaim.

## 6. Batas Eksekusi

Tahap ini bersifat dokumentasi dan audit read-only. Tidak ada source code, migration, seeder, model, controller, service, job/worker, test, configuration, dependency, data, atau database yang diubah. Tidak dijalankan test suite, query database, migration, atau seeder. Dokumen yang dibuat hanya lima output Markdown Tahap 2A.12.

## 7. Final Verdict

1. **ENGINEERING BASELINE:** `COMPLETE`
2. **BASELINE CONSISTENCY:** `PASS WITH OPEN ITEMS`
3. **TECHNICAL READINESS:** `NOT READY`
4. **IMPLEMENTATION DEPENDENCY PLAN:** `COMPLETE`
5. **IMPLEMENTATION AUTHORIZATION:** `NOT AUTHORIZED`
6. **SCHOOL POLICY CONFIRMATION:** `PENDING`

Kelengkapan baseline dan work plan tidak sama dengan technical verification, policy approval, atau otorisasi implementasi.
