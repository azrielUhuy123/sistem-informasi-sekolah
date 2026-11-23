# TAHAP 2A.10R1 — CHANGELOG

## 1. Tujuan

Koreksi konsistensi terbatas atas kepemilikan keputusan username reservation antara OD-04 dan OD-10. Tidak dilakukan audit ulang arsitektur NIS atau perubahan keputusan owner.

## 2. Dokumen Diperbarui

- [Owner Decision Package 2A.10](./TAHAP_2A_10_OWNER_DECISION_PACKAGE.md)
- [Decision Dependency Matrix 2A.10](./TAHAP_2A_10_DECISION_DEPENDENCY_MATRIX.md)

## 3. Dokumen Dibuat

- Dokumen changelog ini.

## 4. Bagian dan Alasan Perubahan

- **OD-10 — Username Collision:** Diperjelas sebagai satu-satunya sumber keputusan kebijakan apakah username reservation diperbolehkan, kondisi/alasan, uniqueness/ownership, otoritas persetujuan, expiry/release/fencing, publication revalidation, collision handling, dan bukti teknis bila dipilih. Reservation tetap pending dan tidak digunakan tanpa keputusan.
- **OD-04 — Account Lifecycle:** Diperjelas bahwa OD-04 memutuskan provisioning synchronous/asynchronous serta apakah Hybrid dipakai sebagai dimensi reservation terpisah. Jika Hybrid menggunakan reservation, OD-04 wajib mengadopsi kebijakan OD-10 yang disetujui dan tidak menetapkan kebijakan kedua. Hybrid tidak menentukan provisioning mode.
- **Dependency Matrix:** Unconditional edges OD-11/OD-18 → OD-10 dan OD-10/OD-11/OD-18 → OD-04 ditegaskan. Conditional OD-01/OD-12 → OD-10 berlaku hanya jika reservation diizinkan dan digunakan; conditional OD-01/OD-12 → OD-04 berlaku hanya jika OD-10 mengizinkan dan OD-04 memilih Hybrid reservation. Edge arah tetap prerequisite → dependent.
- **Account lifecycle:** Lifecycle async tetap mengikuti AC-ACC-02-B pada 2A.8R1; worker attempt result terpisah. Sync tidak memerlukan worker lifecycle. Jalur yang belum ditetapkan tetap `NEEDS_CLARIFICATION`; tidak ada state/transisi baru.

## 5. Konsistensi Sebelum dan Sesudah

- **Sebelum:** OD-04 dan OD-10 sama-sama menyebut reservation sebagai alternatif, sementara arah ketergantungan belum cukup menegaskan siapa yang memegang keputusan policy dan kapan OD-04 mengonsumsinya.
- **Sesudah:** OD-10 menjadi policy owner tunggal. OD-04 tetap memiliki keputusan account lifecycle dan provisioning mode; Hybrid hanya menggunakan reservation sesuai kebijakan OD-10. Tidak terdapat dua kebijakan reservation yang berdiri sendiri.

## 6. Dependency yang Berubah atau Tidak Berubah

- Unconditional dependencies OD-10: OD-11 dan OD-18.
- Unconditional dependencies OD-04: OD-10, OD-11, dan OD-18.
- Conditional technical dependencies OD-10: OD-01 dan OD-12 hanya bila reservation diizinkan dan dipilih untuk digunakan.
- Conditional technical dependencies OD-04: OD-01 dan OD-12 hanya bila OD-10 mengizinkan dan OD-04 memilih Hybrid reservation.
- Dependency lain tidak diubah. Graph direction dipertahankan dan cycle/self-dependency check mencakup semua conditional edges seolah aktif sekaligus; hasil acyclic.

## 7. Approval Register dan Status

Approval register tidak diperbarui karena tidak ada keputusan atau status approval berubah. OD-01–OD-18 tetap `PENDING`; BL-01–BL-10 dan SRC-GAP-01/02 tetap `OPEN`. Tidak ada blocker ditutup. Implementation readiness tetap `NOT READY FOR IMPLEMENTATION`.

## 8. Batas Pekerjaan

Hanya dokumen audit 2A.10 yang diperbarui dan changelog 2A.10R1 dibuat. Tidak ada source code aplikasi, database/data, migration, seeder, test, konfigurasi, dependency/package, reservation, worker, outbox, atau account lifecycle yang diubah/diimplementasikan. Tidak ada query database dilakukan.

## 9. Verdict

- **RESERVATION DECISION OWNERSHIP:** `PASS`
- **OD-04/OD-10 CONSISTENCY:** `PASS`
- **CONDITIONAL DEPENDENCY CONSISTENCY:** `PASS`
- **ACCOUNT LIFECYCLE CONSISTENCY:** `PASS`
- **DOCUMENT TRACEABILITY:** `PASS`
- **OWNER APPROVAL:** `ALL PENDING`
- **IMPLEMENTATION READINESS:** `NOT READY FOR IMPLEMENTATION`
