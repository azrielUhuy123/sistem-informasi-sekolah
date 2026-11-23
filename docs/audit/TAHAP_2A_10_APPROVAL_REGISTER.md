# TAHAP 2A.10 — APPROVAL REGISTER

**Status:** Semua keputusan `PENDING`. Ruang owner, approver, bukti, dan tanggal dibiarkan kosong.  
**Penting:** Rekomendasi di kolom terkait bukan keputusan owner.

| OD | Judul | Prioritas | Status | Rekomendasi teknis (bukan approval) | Keputusan Owner | Approver | Bukti Approval | Tanggal |
|---|---|---|---|---|---|---|---|---|
| OD-01 | Database engine dan baseline | A | PENDING | Local MySQL hanya baseline dev/test; target operational harus dinyatakan dan diuji. |  |  |  |  |
| OD-02 | Runtime dan deployment environment | C | PENDING | Tetapkan runtime matrix dan buktikan SAPI/extension/ICU parity. |  |  |  |  |
| OD-03 | Name comparator dan alphabetical ordering | A | PENDING | Fail closed sampai golden order disetujui dan parity terbukti. |  |  |  |  |
| OD-04 | Account lifecycle dan provisioning | B | PENDING | Pilih sync/async; Hybrid reservation terpisah dari provisioning lifecycle. |  |  |  |  |
| OD-05 | Authority dan workflow koreksi data | B | PENDING | Separate request/verification/approval; append-only dan NIS/slot immutable. |  |  |  |  |
| OD-06 | Legacy NIS collision dan reservation | B | PENDING | Fail closed; no auto-reserve/remap; reservation berbukti dan disetujui. |  |  |  |  |
| OD-07 | Transfer entry evidence | B | PENDING | Evidence/approver ditetapkan owner; jangan infer dari kelas saat ini. |  |  |  |  |
| OD-08 | Supplemental issuance ordering | B | PENDING | Bandingkan timestamp/name/ID dengan timestamp/permanent ordinal/name/ID; belum memilih. |  |  |  |  |
| OD-09 | Valid placement definition | B | PENDING | Definisikan placement tunggal valid; hardblock missing/duplicate/contradictory. |  |  |  |  |
| OD-10 | Username collision | B | PENDING | Manual evidence-based resolution; no auto-merge/rename/link. |  |  |  |  |
| OD-11 | Canonical User–Siswa relationship | A | PENDING | Rekonsiliasi sebelum memilih source of truth; `siswa.user_id` hanya kandidat. |  |  |  |  |
| OD-12 | Database privileges dan least privilege | A | PENDING | Pisahkan runtime, deployment/migration, DBA; validasi grants efektif. |  |  |  |  |
| OD-13 | Publication atomicity | A | PENDING | All-or-nothing; numeric limit setelah benchmark. |  |  |  |  |
| OD-14 | Timestamp authority dan semantics | B | PENDING | Bedakan timestamp teknis/administratif; clock/watermark diuji dan disetujui. |  |  |  |  |
| OD-15 | Year-code mapping | A | PENDING | Mapping eksplisit/validated; pertahankan `2026/2027 → 2627`. |  |  |  |  |
| OD-16 | Delete, restore, dan relink policy | B | PENDING | Registry/slot permanent; restore/relink berbukti dan berotorisasi. |  |  |  |  |
| OD-17 | One initial batch per cohort | A | PENDING | One initial, cohort lock + unique guard + idempotent retry. |  |  |  |  |
| OD-18 | Role separation dan approval governance | B | PENDING | Role per action, distinct approval untuk exception, self-approval controls. |  |  |  |  |

## Panduan Pengisian Approval

1. Hanya pihak yang secara formal berwenang dapat mengisi keputusan.
2. Catat pilihan yang benar-benar disetujui, batas scope dan syarat; jangan menyalin rekomendasi ke kolom keputusan tanpa persetujuan.
3. Isi nama/role approver sesuai kewenangan yang dapat diverifikasi; tidak ada approver yang diasumsikan di register ini.
4. Lampirkan referensi bukti persetujuan yang dapat ditelusuri, seperti notulen/approval record resmi. Jangan membuat bukti retrospektif.
5. Isi tanggal hanya setelah persetujuan terjadi dan sumber tanggalnya jelas.
6. Jika belum cukup informasi, biarkan status `PENDING` dan catat pertanyaan owner di dokumen review terpisah.
7. Approval tidak otomatis membuktikan technical evidence lulus atau blocker tertutup. Catat hasil uji/bukti secara terpisah.
8. Sampai sumber asli 2A.1/2A.2 ditemukan atau keputusan terkait disahkan ulang secara eksplisit, SRC-GAP-01/02 tetap `OPEN`.

## Status Terkait

- OD-01–OD-18: `PENDING`.
- BL-01–BL-10: `OPEN` sampai bukti penutupan tersedia.
- SRC-GAP-01/02: `OPEN`.
- Historical source recovery: `INCOMPLETE`.
- NIS implementation readiness: `NOT READY FOR IMPLEMENTATION`.
