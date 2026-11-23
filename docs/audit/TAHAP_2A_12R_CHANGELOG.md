# TAHAP 2A.12R — CHANGELOG

## 1. Dokumen Dibuat

1. [Engineering Baseline Refinement](./TAHAP_2A_12R_ENGINEERING_BASELINE_REFINEMENT.md)
2. [Implementation Dependency Refinement](./TAHAP_2A_12R_IMPLEMENTATION_DEPENDENCY_REFINEMENT.md)
3. Changelog ini.

Dokumen 2A.12 tidak ditimpa atau diedit. Dokumen 2A.12 tetap menjadi baseline induk; dokumen 2A.12R ini adalah refinement terbatas untuk topik yang disebutkan.

## 2. Baseline dan Sumber yang Ditelaah

- [TAHAP_2A_12_ENGINEERING_BASELINE.md](./TAHAP_2A_12_ENGINEERING_BASELINE.md)
- [TAHAP_2A_12_TECHNICAL_READINESS_MATRIX.md](./TAHAP_2A_12_TECHNICAL_READINESS_MATRIX.md)
- [TAHAP_2A_12_IMPLEMENTATION_DEPENDENCY_PLAN.md](./TAHAP_2A_12_IMPLEMENTATION_DEPENDENCY_PLAN.md)
- [TAHAP_2A_12_BASELINE_CONFLICT_REGISTER.md](./TAHAP_2A_12_BASELINE_CONFLICT_REGISTER.md)
- [TAHAP_2A_12_CHANGELOG.md](./TAHAP_2A_12_CHANGELOG.md)

Fakta lingkungan/data lokal yang dirujuk tetap dibatasi oleh audit 2A.6 sebagaimana tercatat di baseline/matrix; tidak ada query atau pemeriksaan database baru.

## 3. Bagian yang Diperjelas dan Alasannya

| Area | Sebelum refinement (2A.12) | Refinement 2A.12R | Alasan |
|---|---|---|---|
| OD-08 supplemental ordering | `official_entry_at → permanent cohort ordinal → comparator → siswa.id`; comparator dapat tidak pernah menjadi tie-break jika ordinal unik. | `official_entry_at → name comparator → siswa.id`. Permanent ordinal dipertahankan immutable sebagai administrative/audit identity, bukan sorting key. Initial alphabetical contract tetap terpisah. | Menetapkan deterministic sort yang menggunakan comparator saat timestamp sama dan final unique tie-break. Mempertahankan immutability NIS dan ordinal. |
| Workstream graph | Diagram lama ambigu pada hubungan C, D, E, F dan tidak menampilkan seluruh prerequisite secara konsisten. | Menetapkan `A → B → D → C → E`; F memerlukan E+A; G memerlukan B/C/D/E/F; H memerlukan A/B/C/F/I; I dimulai awal dan menjadi activation gate. | Menghilangkan arah ambigu/circular tanpa membuang prerequisite yang sudah tercatat. |
| OD-11 canonical relation | Canonical `siswa.user_id` dipilih, tetapi tidak ada phased migration sequence yang cukup eksplisit. | Menetapkan inventory → reconciliation → anomaly/ownership validation → migrate readers → stop `users.siswa_id` writers → unique/FK → separate drop migration setelah semua dependency terbukti selesai, dengan rollback/forward-fix per tahap. | Mencegah penghapusan kolom legacy sebelum data/callsite/deployment siap. |
| Legacy NIS | Snapshot lokal dan scope inventory disebut terbatas. | Menegaskan bahwa sparse snapshot bukan bukti kelengkapan; inventaris mencakup sumber aktif, soft-deleted, historical backup, dan bukti administratif tersedia. Missing historical issued values tetap collision risk; unresolved affected allocation fail-closed. | Mencegah false assumption slot bebas hanya karena tidak ditemukan pada tabel saat ini. |
| OD-12 privilege gate | Grant lokal luas telah dicatat sebagai risiko; target privilege gate belum dinyatakan sebagai seluruh prerequisite publisher secara operasional. | Menegaskan grant lokal `*.*`/`WITH GRANT OPTION` bukan konfigurasi final; runtime, migration/deployment, DBA/break-glass dipisah; direct SQL mutation dibatasi; effective grants, denied mutation tests, dan auditable break-glass wajib sebelum publisher activation. | Menjadikan least privilege sebagai activation gate yang dapat diverifikasi. |

## 4. Dependency yang Berubah atau Dipertahankan

- Refined OD-08 mengubah hanya supplemental sorting key; dependency kebijakan/teknis OD-03/07/09/14/15 dan atomicity OD-13 tetap berlaku.
- Baseline dependency dasar diklarifikasi menjadi `A → B → D → C → E`.
- F, G, dan H menerima dependency sets eksplisit seperti di dokumen dependency refinement. Dependency I adalah cross-cutting activation gate, bukan reverse edge.
- Canonical relation transition menambahkan urutan deployment/data gates, bukan menghapus prerequisites OD-11 atau menyatakan data telah direkonsiliasi.
- Privilege proof dan denied-mutation tests menjadi syarat sebelum publisher diaktifkan; tidak ada grant yang diubah atau test yang dijalankan.

## 5. Status Keputusan, Blocker, dan Batas

- OD-01–OD-18 tetap `PENDING`; refinement engineering tidak sama dengan school policy approval.
- BL-01–BL-10 tetap `OPEN`.
- SRC-GAP-01/02 tetap `OPEN`.
- Comparator/ICU, target runtime/DB, legacy inventory, migration effects, effective grants, denied-mutation tests, rollback/forward-fix, concurrency, and publication acceptance tetap memerlukan evidence.
- Technical readiness tetap `NOT READY`; implementation authorization tetap `NOT AUTHORIZED`.
- Hanya dokumen Markdown 2A.12R yang dibuat. Tidak ada source, migration, database/data, configuration, dependency, grant, test, seeder, atau generator yang diubah/dijalankan.

## 6. Quality Gate dan Verdict

- **OD-08 ORDERING:** `PASS` — tuple deterministic; ordinal bukan sorting key; NIS immutable.
- **WORKSTREAM DEPENDENCY:** `PASS` — required edges konsisten, arah eksplisit, tanpa cycle/self-dependency.
- **CANONICAL RELATION MIGRATION PLAN:** `COMPLETE` — staged path dan rollback/forward-fix gate terdokumentasi; eksekusi/validasi belum dilakukan.
- **LEGACY COLLISION SAFETY:** `PASS` — missing historical value bukan slot bebas; unresolved affected allocations fail-closed.
- **PRIVILEGE GATE:** `PASS` — effective-grant verification, denied mutation tests, separated accounts, and auditable break-glass are required before activation; evidence not yet obtained.
- **BASELINE REFINEMENT:** `COMPLETE` untuk scope dokumentasi.
- **IMPLEMENTATION READINESS:** `NOT READY`.

Kelengkapan refinement tidak menyatakan blocker teknis tertutup atau mengizinkan implementasi.
