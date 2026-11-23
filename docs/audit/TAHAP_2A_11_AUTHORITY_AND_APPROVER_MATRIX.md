# TAHAP 2A.11 — AUTHORITY AND APPROVER MATRIX

## 1. Tujuan dan Status

Matriks ini menunjukkan kewenangan yang perlu ditetapkan untuk mengambil/menyetujui OD-01–OD-18. **Tidak ada nama, jabatan, mandat, atau approver aktual yang dapat diverifikasi dari dokumen sumber yang ditelaah.** Fungsi pada kolom “pihak yang perlu diidentifikasi” adalah kategori kebutuhan untuk klarifikasi, bukan penunjukan pemegang kewenangan.

**Status authority aktual:** `NOT IDENTIFIED`  
**Flag seluruh assignment role:** `NEEDS_OWNER_IDENTIFICATION`

Developer, auditor, operator teknis, atau DBA tidak dianggap policy owner hanya karena menulis rekomendasi atau mengelola aplikasi. Pihak teknis dapat memberikan evidence/technical sign-off, tetapi itu tidak menggantikan persetujuan kebijakan sekolah.

## 2. Matriks OD dan Kewenangan

| OD | Kebijakan/approval yang diperlukan | Fungsi yang perlu diidentifikasi (belum ditunjuk) | Kontribusi teknis/administratif (bukan policy approval otomatis) | Bukti mandat/approval yang harus diminta | Status |
|---|---|---|---|---|---|
| OD-01 | Scope dan engine/version dev-test serta operational | Policy owner sistem dan authority platform/database | Ops/DBA menjelaskan target/support, membuktikan fitur dan recovery | Mandat policy owner; platform decision/approval record; dukungan target | NEEDS_OWNER_IDENTIFICATION |
| OD-02 | Runtime minimum, SAPI, extension dan parity | Owner standar aplikasi dan authority deployment/runtime | Ops membuktikan web/CLI/worker/CI matrix | Standar runtime dan pengesahan environment aktual | NEEDS_OWNER_IDENTIFICATION |
| OD-03 | Comparator, aturan nama dan expected golden order | Policy owner penerbitan/urutan alfabetis | Engineering/Ops membuktikan locale/runtime/parity | Keputusan expected order/fixture, nama/jabatan approver, tanggal | NEEDS_OWNER_IDENTIFICATION |
| OD-04 | Sync/async account provisioning, Hybrid dipakai atau tidak, login/account lifecycle | Owner akun/login dan owner proses penerimaan/sekolah | Engineering membuktikan transaction/worker/auth branch; jika Hybrid, hanya mengonsumsi reservation policy OD-10 | Keputusan provisioning mode dan account/login policy; bukti approver formal | NEEDS_OWNER_IDENTIFICATION |
| OD-05 | Request, verification, correction, approval dan exception authority | Owner koreksi data/identitas serta exception approver | TU/data steward mempersiapkan evidence; engineering menguji audit/auth | SOP correction, separation/self-approval rule, delegation | NEEDS_OWNER_IDENTIFICATION |
| OD-06 | Legacy collision disposition/reservation slot NIS | Owner legacy NIS dan authority reservation/exception | Data steward menyiapkan authorized inventory; DBA/engineering membuktikan ledger enforcement | Persetujuan disposition per collision, evidence provenance, approver | NEEDS_OWNER_IDENTIFICATION |
| OD-07 | Bukti siswa transfer, entry year/grade dan exception | Owner penerimaan/administrasi siswa | TU/arsip memverifikasi dokumen; engineering menguji resolver/authorization | Daftar bukti yang diterima, SOP, approver/verifier delegation | NEEDS_OWNER_IDENTIFICATION |
| OD-08 | Urutan supplemental dan tie-break | Owner kebijakan penerbitan NIS | Engineering membuktikan determinism, timestamp/comparator parity | Keputusan urutan dan fixture yang disahkan | NEEDS_OWNER_IDENTIFICATION |
| OD-09 | Valid placement, multiplicity, exception | Owner akademik/administrasi siswa dan exception authority | TU memvalidasi record; engineering membuktikan constraint/resolver | Definisi status/tanggal valid dan approval exception | NEEDS_OWNER_IDENTIFICATION |
| OD-10 | Kebijakan tunggal: reservation boleh/tidak, kondisi/alasan, ownership/uniqueness, approval, expiry/release/fencing/revalidation/collision | Owner username/account policy dan approver reservation/collision | DBA/engineering membuktikan uniqueness, locking, privilege dan semua writers jika reservation dipakai | Policy decision eksplisit, batas/conditions, mandat approver, technical proof terpisah | NEEDS_OWNER_IDENTIFICATION |
| OD-11 | Canonical User–Siswa relation dan identity reconciliation | Owner identitas/account siswa | Data steward/engineering menyediakan reconciliation; DBA membuktikan one-to-one | Policy decision, approved handling orphan/duplicate/mismatch | NEEDS_OWNER_IDENTIFICATION |
| OD-12 | DB runtime/deployment/DBA privileges dan break-glass | Owner risk/security policy dan authority database | DBA/Ops merancang grants dan membuktikan effective privileges | Role/grant approval, break-glass delegation, audit process | NEEDS_OWNER_IDENTIFICATION |
| OD-13 | Atomicity/at-cap publication policy | Owner integritas/publication dan authority menerima operational envelope | Ops/DBA target SLO/volume; engineering benchmark dan recovery | Keputusan atomicity, risk acceptance serta bukti benchmark untuk cap | NEEDS_OWNER_IDENTIFICATION |
| OD-14 | Otoritas waktu administratif, cutoff, equality/watermark | Owner policy akademik/penerimaan | DBA/Ops clock precision/skew; engineering timestamp tests | Cutoff policy, time authority, equality/exception approval | NEEDS_OWNER_IDENTIFICATION |
| OD-15 | Definisi tahun ajaran/cohort dan mapping year_code | Owner academic-year/cohort policy | Data steward/Ops memvalidasi labels/parser mapping | Mapping/label policy yang disahkan dan source record provenance | NEEDS_OWNER_IDENTIFICATION |
| OD-16 | Delete/force-delete, retention, restore, relink | Owner data/privacy/identity dan relink/exception authority | DBA/engineering membuktikan FK/ledger behavior; data steward verifikasi identity | Retention/delete SOP dan relink approval policy | NEEDS_OWNER_IDENTIFICATION |
| OD-17 | Exactly-one initial batch, readiness/cutoff, cancellation/reopen | Owner publication/cohort policy dan cancellation authority | DBA/engineering membuktikan unique guard/lock/idempotency | Cohort/cutoff/cancel rule serta named approval authority | NEEDS_OWNER_IDENTIFICATION |
| OD-18 | Pemilik kebijakan NIS; separation of duties; role/delegation matrix | Governing authority yang dapat menunjuk policy owner dan actual approvers | Engineering mengimplementasikan setelah disahkan; auditor/recorder hanya mencatat | Mandat/appointment tertulis untuk owner, approver, verifier, recorder dan delegation | NEEDS_OWNER_IDENTIFICATION |

## 3. Pertanyaan Klarifikasi Kewenangan

Semua pertanyaan berikut harus dijawab oleh pihak yang dapat menunjukkan mandat:

1. Siapa pemilik kebijakan NIS secara formal? Apa bukti penunjukannya dan batas kewenangannya?
2. Siapa yang berwenang menyetujui kebijakan akademik/cohort/year-code/entry grade?
3. Siapa yang berwenang menyetujui administrasi siswa, placement, transfer evidence, dan correction?
4. Siapa yang berwenang menyetujui username/login/account lifecycle serta username reservation OD-10?
5. Siapa yang berwenang menyetujui exception, collision disposition, cancellation/reopen dan relink?
6. Siapa technical authority untuk database, runtime, security/privilege dan acceptance evidence? Apakah peran ini hanya memberi technical sign-off atau juga memerlukan risk acceptance owner?
7. Siapa verifier evidence dan siapa recorder keputusan? Bagaimana approval record disimpan dan diverifikasi?
8. Apakah requester dapat menjadi approver? Apa aturan delegasi/absence dan dual control?
9. Apa dokumen mandat, resolusi, SOP, notulen atau approval workflow yang diterima sebagai bukti formal?

## 4. Aturan Penggunaan Matriks

- Jangan mengisi nama/jabatan dengan asumsi. Isi setelah mandate/authority evidence diperiksa.
- Nama orang tanpa dasar kewenangan tidak cukup untuk mengubah status OD.
- Technical validation dan policy approval dicatat terpisah.
- `APPROVED` hanya setelah keputusan eksplisit dari approver berwenang dan evidence/date dapat diverifikasi.
- Jika authority belum ditemukan, gunakan `NEEDS_OWNER_IDENTIFICATION`; OD tetap `PENDING`.
- OD-10 owns username reservation policy; OD-04 cannot independently approve a second reservation policy.

## 5. Verdict Kewenangan

Dokumen tersedia menyebut kategori fungsi (misalnya owner, Ops/DBA, engineering, TU/data steward) sebagai peran yang perlu dilibatkan, tetapi tidak mengidentifikasi orang/jabatan formal atau bukti mandat. Karena itu:

- **OWNER AUTHORITY IDENTIFICATION:** `NOT IDENTIFIED`
- **NEEDS_OWNER_IDENTIFICATION:** berlaku pada seluruh OD hingga appointment/delegation evidence tersedia.
- Tidak ada approver aktual atau persetujuan yang disimpulkan.
