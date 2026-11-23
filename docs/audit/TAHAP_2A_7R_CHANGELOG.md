# TAHAP 2A.7R — CHANGE LOG

**Dokumen refinement:** [TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md](./TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md)  
**Dokumen dasar:** [TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md](./TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md)  
**Jenis perubahan:** dokumentasi keputusan saja; tidak mengubah baseline atau menyetujui keputusan owner.

| Bagian | Kondisi sebelum revisi | Perubahan yang dilakukan | Alasan | Dampak terhadap keputusan lain |
|---|---|---|---|---|
| OD-04 — Account provisioning | Merekomendasikan akun dibuat/diaktifkan bersama atau sesudah publication, tanpa batas transaksi, failure semantics, retry idempotent dan status operasional yang cukup eksplisit. | Mengevaluasi provisioning dalam transaksi publication dibanding transactional outbox/job sesudah commit; membedakan kegagalan DB akun dan email; menambahkan pending/non-login gate, retry key, status, audit, serta manual-review untuk conflict. | NIS adalah identitas permanen dan tidak semestinya otomatis dibatalkan hanya karena provisioning/email gagal. Pemisahan hasil perlu eksplisit untuk menjaga atomic issuance sekaligus pemulihan akun. | Menambahkan dependensi OD-04 pada OD-10, OD-11 dan OD-18. Membuka alignment question terhadap usulan sync `users.username` dalam transaksi publication pada desain 2A.3. OD-04 tetap `PENDING`. |
| OD-14 — Timestamp | Timestamp teknis dan tanggal administratif disebut umum; fungsi `recorded_at`, `effective_at` dan `official_entry_at`, cutoff equality serta correction semantics belum dibedakan tuntas. | Mendefinisikan tiga timestamp; mempertahankan post-lock/transaksi/UTC untuk official entry; merekomendasikan supplemental ordering dari official timestamp + cohort ordinal; cutoff initial dari locked watermark; `effective_at` tidak mengubah ordering/NIS; koreksi append-only. | Menghindari timestamp administratif yang berubah memindahkan urutan permanen atau mengubah membership historis. | OD-08 bergantung pada OD-14; OD-07 memerlukan timestamp semantics; OD-14 bergantung pada OD-01 untuk clock/precision teknis. Semua tetap `PENDING`. |
| OD-18 — Self-dependency | Daftar dependency OD-18 memuat `OD-18` sendiri. | Menghapus self-edge; OD-18 tidak memiliki prerequisite dalam final graph dan menjadi prerequisite governance bagi OD-04/05/06/07/09/10/16. | Self-dependency tidak masuk akal dan dapat membuat proses keputusan tampak tak dapat dimulai. | Tidak mengubah kewenangan atau approval. OD-18 tetap `PENDING`. |
| Dependency OD-13/OD-17 | OD-13 mencantumkan OD-17; OD-17 mencantumkan OD-13, membentuk circular dependency. | Menetapkan arah satu sisi: OD-13 bergantung pada OD-01/OD-12; OD-17 bergantung pada OD-01/OD-12/OD-13/OD-15. | Atomicity/engine/privilege menjadi dasar untuk memilih guard publication; guard tidak menjadi prasyarat untuk mendefinisikan atomicity. | Graph final tidak memiliki cycle. OD-13/OD-17 tetap `PENDING` dan memerlukan validasi teknis. |
| Dependency OD lainnya | Dependency tersebar di detail keputusan dan register, beberapa merupakan relasi yang lebih tepat sebagai validasi teknis daripada prerequisite keputusan. | Menyusun matriks final OD-01–OD-18 dengan rationale dan status; memisahkan prasyarat finalisasi dari pekerjaan review paralel dan bukti teknis. | Memeriksa logika antar keputusan serta membuat urutan topologis yang dapat diaudit. | Tidak ada kebijakan baru; validasi teknis tetap dicatat di status/rationale dan tidak dianggap approval. |
| Updated register dan consistency review | Register sebelumnya tidak memasukkan failure semantics OD-04 atau cutoff/watermark OD-14; graph OD-18 tidak valid. | Memperbarui rekomendasi/status dependency dan memeriksa konsistensi terhadap atomic publication, initial cutoff, supplemental, immutability dan baseline 2A.1–2A.6. | Memastikan refinement terbatas tidak merusak invariant NIS maupun menimbulkan approval implisit. | Semua approval status OD-01–OD-18 tetap `PENDING`; owner decision fields tetap belum diisi. |

## Bagian yang sengaja tidak diubah

- Format NIS `TTTT.T.NNN`, pemetaan contoh `2026/2027 → 2627`, sequence per `(year_code, entry_grade)` lintas rombel, dan sequence minimum tiga digit tanpa batas maksimum 999.
- Invariant NIS immutable/tidak digunakan ulang, initial batch satu per cohort, placement prerequisite, transfer entry grade/year berdasarkan penerimaan resmi, dan supplemental tanpa perubahan NIS sebelumnya.
- Baseline initial alphabetical sort dan fail-closed sampai comparator disahkan; temuan actual locale `root` tidak diubah.
- Kebijakan legacy exact-string, collision fail-closed dan larangan menebak entry year/grade.
- Temuan privilege lokal, schema dan data agregat Tahap 2A.6; tidak dilakukan pemeriksaan database tambahan.
- Status persetujuan seluruh OD. Tidak ada status `APPROVED`, `REJECTED` atau `DEFERRED` yang ditambahkan.
- Source code, migration, konfigurasi, dependency, grant, data, NIS, username, dan workflow aplikasi.

**Validasi pelaksanaan:** hanya dokumen baru yang dibuat. Tidak ada test, migration, seeder, query/operasi database, atau perintah yang mengubah aplikasi dijalankan.
