# TAHAP 2A.10 — OWNER DECISION PACKAGE

**Sistem:** SINERGI-MJ / NIS  
**Tujuan:** Paket bahan keputusan owner untuk OD-01–OD-18  
**Status seluruh OD:** `PENDING`  
**Status paket:** Draft untuk review; rekomendasi bukan keputusan, persetujuan, atau izin implementasi  
**Historical source recovery:** `INCOMPLETE` — SRC-GAP-01 dan SRC-GAP-02 tetap `OPEN`

## 1. Executive Summary

Dokumen ini menyajikan 18 decision brief agar pemilik kebijakan dapat memutuskan hal yang masih terbuka berdasarkan pilihan, konsekuensi, risiko, dependensi, dan bukti yang dibutuhkan. Keputusan tetap milik owner yang berwenang. Kolom keputusan, approver, bukti persetujuan, dan tanggal sengaja tidak diisi.

Arsitektur 2A.8R1 menjadi baseline paling baru yang tersedia untuk publication NIS, state account, notification, Hybrid reservation, dan acceptance criteria. Tahap 2A.9 menunjukkan sumber asli 2A.1/2A.2 belum ditemukan dalam ruang pencarian lokal; referensi tahap berikutnya hanya bukti sekunder. Karena itu, paket ini **tidak menggantikan sumber historis** dan tidak menyatakan semua keputusan historis telah dipulihkan.

Kesimpulan:

- Semua OD-01–OD-18 tetap `PENDING`.
- Opsi provisioning A/B dan Hybrid tidak dipilih. Hybrid reservation tetap terpisah dari pilihan provisioning synchronous/asynchronous.
- Rekomendasi teknis di bawah bersumber pada dokumen yang tersedia dan diberi label; provenance terhadap 2A.1/2A.2 tidak dapat diverifikasi.
- Kebutuhan validasi teknis tetap terpisah dari keputusan owner.
- BL-01–BL-10 dan SRC-GAP-01/02 tetap terbuka.
- Arsitektur NIS tetap `NOT READY FOR IMPLEMENTATION`.

## 2. Tujuan dan Batasan

Paket ini mengubah daftar OD menjadi pertanyaan keputusan eksplisit yang dapat ditinjau owner, bukan menjadi keputusan yang langsung diterapkan.

Tidak dilakukan:

- perubahan kebijakan NIS atas nama owner;
- perubahan status OD menjadi `APPROVED`;
- penutupan blocker tanpa bukti;
- implementasi kode, service, worker, outbox, reservation, migration, atau login gate;
- akses/perubahan database atau data;
- migration, seeder, atau test.

Seluruh bukti runtime/database yang disebut merupakan status yang tercatat pada audit terdahulu; tidak diverifikasi ulang untuk deployment dalam tahap ini.

## 3. Sumber dan Provenance

Sumber yang dibaca:

1. [Desain arsitektur NIS 2A.3](./TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md)
2. [Final Readiness Gate 2A.4](./TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md)
3. [Evidence Closure 2A.5](./TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md)
4. [Local Environment & Schema Verification 2A.6](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md)
5. [Owner Decision Resolution 2A.7](./TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md)
6. [Refinement 2A.7R](./TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md)
7. [Consolidation 2A.8](./TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md)
8. [Traceability 2A.8](./TAHAP_2A_8_TRACEABILITY_MATRIX.md)
9. [Refinement 2A.8R](./TAHAP_2A_8R_ARCHITECTURE_CONSISTENCY_REFINEMENT.md)
10. [Traceability 2A.8R](./TAHAP_2A_8R_TRACEABILITY_MATRIX.md)
11. [Correction 2A.8R1](./TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md)
12. [Traceability 2A.8R1](./TAHAP_2A_8R1_TRACEABILITY_MATRIX.md)
13. [Historical Source Recovery 2A.9](./TAHAP_2A_9_HISTORICAL_SOURCE_RECOVERY.md)
14. [Historical Decision Traceability 2A.9](./TAHAP_2A_9_HISTORICAL_DECISION_TRACEABILITY.md)

**Sumber historis yang hilang:** dokumen asli 2A.1 dan 2A.2 belum ditemukan. Klaim/rekomendasi yang hanya dapat ditelusuri ke 2A.3–2A.9 adalah provenance dokumen tersedia, bukan bukti bahwa ia pernah disetujui owner atau identik dengan keputusan historis.

**Catatan bukti lokal:** Tahap 2A.6 mencatat MySQL 8.0.30/InnoDB, PHP CLI 8.2.29, Laravel 12.40.2, ICU 71.1, dan Collator `id-ID` dengan actual locale `root` pada lingkungan lokal yang diperiksa. Ini bukan bukti konfigurasi production. Grant lokal juga tercatat terlalu luas; tidak ada perubahan grant dilakukan.

## 4. Ringkasan 18 Keputusan

| OD | Keputusan | Prioritas | Status | Rekomendasi teknis ringkas |
|---|---|---|---|---|
| OD-01 | Database engine dan baseline | A | PENDING | Tetapkan MySQL lokal sebagai dev/test saja bila sesuai; pilih target operational sebelum DDL. |
| OD-02 | Runtime dan deployment | C | PENDING | Tetapkan minimum/runtime matrix; buktikan PHP web handler dan CLI/worker parity. |
| OD-03 | Name comparator | A | PENDING | Fail closed hingga golden order owner disetujui dan parity terbukti. |
| OD-04 | Account lifecycle/provisioning | B | PENDING | Putuskan sync atau async; Hybrid reservation adalah dimensi terpisah. |
| OD-05 | Authority koreksi data | B | PENDING | Request, verify, separate approval, append-only; NIS/slot tetap immutable. |
| OD-06 | Legacy NIS collision/reservation | B | PENDING | Fail closed; reservation NIS hanya dengan evidence/approval. |
| OD-07 | Transfer entry evidence | B | PENDING | Tetapkan evidence dan approver; jangan menurunkan entry grade dari kelas aktif. |
| OD-08 | Supplemental ordering | B | PENDING | Putuskan tie-break timestamp-only vs permanent ordinal; keduanya belum disahkan. |
| OD-09 | Valid placement | B | PENDING | Definisikan satu placement sah dan named exceptions; hardblock konflik. |
| OD-10 | Username collision | B | PENDING | Tidak auto-merge/rename; rekonsiliasi/manual resolution berdasarkan evidence. |
| OD-11 | Canonical User–Siswa relation | A | PENDING | Tentukan satu source of truth setelah rekonsiliasi; kandidat bukan approval. |
| OD-12 | DB privileges/least privilege | A | PENDING | Pisahkan runtime, deployment/migration, dan DBA accounts. |
| OD-13 | Publication atomicity | A | PENDING | All-or-nothing; tentukan batas batch dari benchmark, bukan tebakan. |
| OD-14 | Timestamp authority/semantics | B | PENDING | Pisahkan `recorded_at`, `effective_at`, `official_entry_at`; tentukan clock/watermark. |
| OD-15 | Year-code mapping | A | PENDING | Mapping eksplisit dan validated; pertahankan contoh `2026/2027 → 2627`. |
| OD-16 | Delete/restore/relink | B | PENDING | Registry/slot permanen; restore/relink berbukti dan berotorisasi. |
| OD-17 | Satu initial batch per cohort | A | PENDING | Cohort lock, uniqueness, idempotent retry; tidak ada initial kedua. |
| OD-18 | Role separation/governance | B | PENDING | Pisahkan prepare/publish/reserve/correct/audit/exception approval; cegah self-approval. |

Prioritas mempertahankan kelompok yang diminta. OD-02 tetap Prioritas C (runtime validation), walau OD-03 bergantung pada runtime; owner dapat membahas runtime lebih awal, tetapi comparator tidak dapat difinalkan sebelum runtime diuji.

## 5. Decision Briefs

Setiap brief mencakup 20 unsur wajib. “Pihak berwenang” berarti fungsi owner yang harus ditetapkan oleh organisasi, bukan nama atau jabatan yang diasumsikan. Rekomendasi ditandai sebagai rekomendasi teknis, bukan keputusan owner.

### OD-01 — Database Engine dan Baseline

1. **ID/judul:** OD-01 — Database engine dan baseline.
2. **Status:** `PENDING`.
3. **Latar belakang:** Audit 2A.6 mengamati MySQL 8.0.30/InnoDB lokal. Deployment operational belum ditetapkan/dibuktikan pada baseline 2A.9.
4. **Masalah:** Migration, locking, upsert, FK, unique/check/trigger, dan transaction semantics berbeda antar-engine/versi.
5. **Pertanyaan owner:** Engine/versi apa untuk NIS dev/test dan target operasional? Apakah MySQL 8.0.30 hanya baseline lokal? Kapan target production akan dipilih dan berapa lama tiap versi didukung?
6. **Alternatif:** (A) MySQL 8.0.30 hanya baseline dev/test, target operational dipilih kemudian; (B) tetapkan MySQL versi target setelah Ops mengonfirmasi dukungan; (C) pilih engine lain dan siapkan parity environment; (D) tunda pilihan—DDL NIS tetap diblokir.
7. **Plus/minus:** A menghindari asumsi deployment namun menunda DDL target; B mengurangi variasi tetapi memerlukan bukti support; C dapat cocok dengan hosting namun perlu desain/tes ulang; D paling konservatif namun memblokir migration spesifik driver.
8. **Dampak arsitektur:** Menentukan tipe/constraint, locking, upsert, trigger, FK, rollback, migration sequence dan strategi concurrency.
9. **Dampak keamanan/integritas:** Engine menentukan enforcement uniqueness/immutability dan kemampuan least privilege; belum ada hasil target.
10. **Dampak operasional:** Ops perlu menyediakan versi terkelola, backup/restore, monitoring, patching dan akses deployment yang sesuai.
11. **Risiko penundaan:** DDL dan acceptance suite tidak dapat difinalkan; perubahan engine setelah coding meningkatkan rework dan risiko data.
12. **Rekomendasi teknis:** Nyatakan MySQL lokal hanya bukti dev/test; tetapkan target/version sebelum migration, lalu uji fitur pada target disposable.
13. **Alasan:** Konsisten dengan TE-01/TV-01 dan menghindari menjadikan koneksi lokal sebagai keputusan production.
14. **Dependensi:** Prasyarat bagi OD-12, OD-13, OD-14, OD-17; detail pada matriks dependency.
15. **Bukti/validasi:** Owner/Ops target statement; supported-version policy; schema feature matrix; lock/rollback/restore test pada engine/version target.
16. **Pihak berwenang:** Owner sistem bersama pihak Ops/DBA yang berwenang menetapkan platform; identitas belum diberikan.
17. **Ruang keputusan owner:** `Pilihan: ____` / `Ruang lingkup dev-test vs operational: ____` / `Syarat: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** Baseline 2A.7/2A.8 membatasi bukti MySQL ke lokal; 2A.9 tidak menemukan sejarah 2A.1/2A.2. Tidak ada persetujuan terverifikasi.

### OD-02 — Runtime dan Deployment Environment

1. **ID/judul:** OD-02 — Runtime PHP dan deployment.
2. **Status:** `PENDING`.
3. **Latar belakang:** Manifest mencatat PHP `^8.2`; audit lokal mencatat PHP CLI 8.2.29 dan Laravel 12.40.2. Handler Apache/PHP web yang benar-benar digunakan belum terverifikasi.
4. **Masalah:** CLI, web, queue worker, CI, OS, extension dan ICU dapat berbeda; comparator permanen tidak boleh bergantung pada runtime tak dikenal.
5. **Pertanyaan owner:** PHP minimum dan versi target apa? Laravel baseline apa? SAPI/web server mana? Extension/driver apa wajib? Apakah CLI/web/worker harus sama atau diuji sebagai matrix?
6. **Alternatif:** (A) Satu image/runtime yang dipin untuk web, CLI, worker, CI; (B) versi patch fleksibel dalam rentang PHP didukung dengan matrix parity; (C) runtime berbeda per komponen tetapi wajib lulus matrix; (D) tunda target—publikasi fail closed.
7. **Plus/minus:** A menekan drift namun membutuhkan konsistensi deployment; B fleksibel tetapi perubahan ICU/runtime memengaruhi sorting; C mendukung hosting heterogen namun menambah test/operasi; D aman sementara namun mencegah aktivasi.
8. **Dampak arsitektur:** Menentukan dependency, comparator, job, PDO driver, clock/timezone, dan cara menjalankan worker.
9. **Dampak keamanan/integritas:** Perbedaan extension/SAPI atau kode bisa menghasilkan login/sort/transaction behavior berbeda; pinning dan artifact parity mengurangi risiko.
10. **Dampak operasional:** Ops perlu manifest runtime, build/deploy process, patch policy, observability dan dukungan queue/web server.
11. **Risiko penundaan:** Tidak dapat membuktikan comparator, autentikasi, atau retry worker berperilaku konsisten.
12. **Rekomendasi teknis:** Tetapkan PHP minimum sesuai manifest; pilih matrix eksplisit dan catat PHP/Laravel/extensions/ICU/PDO per runtime. Jangan klaim Apache handler lokal sudah diketahui.
13. **Alasan:** Actual locale lokal `root` dan web handler belum terverifikasi; presence `intl` saja tidak membuktikan comparator.
14. **Dependensi:** OD-03 bergantung pada OD-02. Runtime dapat dibahas sendiri; sign-off comparator tidak.
15. **Bukti/validasi:** Web SAPI/php.ini aktual, PHP CLI/worker, extension intl/Normalizer/Collator/mbstring/PDO, ICU/actual locale, composer lock/artifact parity, runtime matrix CI.
16. **Pihak berwenang:** Owner menetapkan standar aplikasi; Ops/deployment owner mengesahkan runtime yang benar-benar disediakan.
17. **Ruang keputusan owner:** `PHP minimum: ____` / `Laravel baseline: ____` / `Web server/SAPI: ____` / `Extension/runtime policy: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** Bukti lokal 2A.6 tidak berlaku otomatis untuk deployment. OD-02 tetap prioritas C menurut paket; dapat dibahas lebih awal.

### OD-03 — Name Comparator dan Alphabetical Ordering

1. **ID/judul:** OD-03 — Comparator nama dan urutan alfabetis.
2. **Status:** `PENDING`.
3. **Latar belakang:** Urutan awal menjadi permanen. Collator `id-ID` lokal melaporkan locale aktual `root`; belum ada golden ordering owner-approved.
4. **Masalah:** Locale, ICU version, Unicode normalization, kapitalisasi, diakritik, spasi, punctuation, gelar/prefix dan nama identik dapat mengubah urutan.
5. **Pertanyaan owner:** Comparator apa yang digunakan? Bagaimana tiap karakteristik nama diperlakukan? Apa hasil urut yang disetujui? Tie-break apa berlaku bila nama setara?
6. **Alternatif:** (A) ICU Collator hanya bila actual locale/data terverifikasi dan versi dipin; (B) library collation yang dipaketkan/dikunci versinya setelah review lisensi/maintenance; (C) comparator eksplisit dengan aturan yang disetujui owner; (D) fail closed sampai bukti tersedia.
7. **Plus/minus:** A memanfaatkan ICU tapi lokal `root` bukan bukti locale Indonesia; B meningkatkan reproducibility namun menambah dependency/maintenance; C memberi kontrol eksplisit namun aturan dapat tidak sesuai ekspektasi jika tidak ditinjau; D mencegah urutan salah tetapi menunda initial publication.
8. **Dampak arsitektur:** Menentukan urutan awal per `(year_code, entry_grade)` serta tie-break nama pada supplemental; fingerprint comparator harus masuk snapshot.
9. **Dampak keamanan/integritas:** Urutan permanen tidak dapat diperbaiki setelah issue tanpa melanggar identitas/ledger.
10. **Dampak operasional:** Perubahan runtime/ICU/library perlu controlled upgrade dan kompatibilitas fixture.
11. **Risiko penundaan:** Publication initial tetap diblokir; memaksa sort tanpa approval menghasilkan urutan permanen yang disengketakan.
12. **Rekomendasi teknis:** Fail closed sampai owner mengesahkan golden fixture konkret dan hasil sama pada runtime target.
13. **Alasan:** Actual locale `root` menunjukkan request locale tidak cukup; comparator perlu dibuktikan end-to-end.
14. **Dependensi:** OD-02; OD-08 memakai comparator sebagai secondary key; bergantung juga pada validasi TE-06/07.
15. **Bukti/validasi:** Golden ordering fixture mencakup NFC/NFD, case, aksen, whitespace berulang, punctuation, gelar/prefix, nama identik, tie-break ID; parity CLI/web/worker/OS yang disetujui.
16. **Pihak berwenang:** Owner kebijakan menyetujui urutan expected; engineering/Ops membuktikan implementasi/runtime.
17. **Ruang keputusan owner:** `Comparator: ____` / `Expected fixture/order reference: ____` / `Tie-break: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** 2A.8R1 mempertahankan recommendation ordinal supplemental; keputusan comparator dan golden output tetap terbuka.

### OD-04 — Account Lifecycle dan Provisioning

1. **ID/judul:** OD-04 — Account lifecycle relatif terhadap penerbitan NIS.
2. **Status:** `PENDING`.
3. **Latar belakang:** Login legacy menggunakan NIS dan relasi `siswa.user_id`; NIS baru diterbitkan setelah entry/placement valid. Tahap 2A.8R1 memisahkan synchronous, asynchronous dan Hybrid reservation.
4. **Masalah:** Waktu User dibuat, login eligibility, username, transaction boundary, kegagalan provisioning, dan notifikasi harus konsisten tanpa mengganti NIS yang telah commit.
5. **Pertanyaan owner:** Kapan User dibuat/diaktifkan? Provisioning synchronous atau asynchronous? Jika Hybrid dipilih, apakah username reservation yang kebijakannya disetujui pada OD-10 digunakan? Apakah username harus sama dengan NIS? Apa akibat account failure setelah NIS commit? Kapan credential/notifikasi boleh dikirim?
6. **Alternatif:** (A) Synchronous: account/link yang diwajibkan dibuat dalam publication transaction; kegagalan DB me-rollback batch. (B) Asynchronous: NIS dan durable provisioning intent atomik; worker idempotent setelah commit. (C) Hybrid: gunakan username reservation hanya jika diizinkan dan sesuai kondisi/kebijakan yang diputuskan pada OD-10; provisioning setelah reservation dipilih terpisah sebagai synchronous atau asynchronous. (D) pre-created inactive User hanya jika identifier dan login policy disetujui.
7. **Plus/minus:** A menjaga satu commit tetapi menambah transaksi/risiko batch rollback; B memisahkan NIS dari ketersediaan account namun ada periode non-login dan kebutuhan worker; Hybrid dapat mengikuti kebijakan reservation OD-10 yang telah disetujui, tetapi kebutuhan teknisnya tetap harus dibuktikan; pre-created User dapat orphan/placeholder jika identifier tidak terikat aman.
8. **Dampak arsitektur:** Menentukan User write, outbox, login gate, event notification, retry, dan hubungan publication. Untuk Hybrid, OD-04 mengonsumsi kebijakan reservation OD-10; OD-04 tidak menetapkan ulang kondisi, ownership, approval, expiry, fencing, revalidation, atau collision policy reservation.
9. **Dampak keamanan/integritas:** Login harus ditolak sebelum account sukses dan canonical relation valid; no auto-link/merge/rename.
10. **Dampak operasional:** A dapat menahan seluruh batch karena satu akun; B membutuhkan queue/reconciler/manual review; Hybrid memerlukan operator claim expiry dan collision; TU/PPDB butuh status yang mudah dipahami.
11. **Risiko penundaan:** PPDB, login, reset password, credentials dan NIS publication tidak dapat diintegrasikan.
12. **Rekomendasi teknis:** Tidak memilih opsi owner. Pertahankan no-login gate. Jika B dipilih, publication transaction menyimpan durable provisioning intent bersama NIS; worker memproses intent yang sama. Lifecycle yang dinyatakan AC-ACC-02-B adalah `pending → processing → succeeded`, atau `processing → failed → retryable → processing`, dengan jalur `processing → failed_manual_review` untuk kegagalan yang memerlukan manusia. Catatan hasil setiap worker attempt tetap terpisah dari provisioning lifecycle; `failed` adalah lifecycle state yang menunggu klasifikasi, bukan state tambahan untuk setiap attempt. Kegagalan sementara berlanjut ke `retryable` dengan idempotency key intent yang sama; collision/identity ambiguity atau kegagalan yang memerlukan manusia tidak auto-retry/auto-repair dan tidak memanggil allocator. Apakah jalur manual-review harus melewati state `failed` sebelum `failed_manual_review` tidak ditentukan eksplisit oleh AC-ACC-02-B; tandai `NEEDS_CLARIFICATION` sebelum menetapkan transisi tersebut, tanpa menambah state. Jika A dipilih, operasi provisioning wajib berada dalam publication transaction; keberhasilan berarti transaksi provisioning berhasil commit, bukan status worker `succeeded`, dan A tidak mensyaratkan worker lifecycle. Hybrid tidak memilih ulang atau menduplikasi kebijakan reservation: reservation hanya dapat digunakan sesuai keputusan/ketentuan OD-10, dan tidak otomatis asynchronous; setelah reservation, provisioning dapat synchronous (AC-ACC-02-A/04) atau asynchronous (AC-ACC-02-B/01) sesuai pilihan owner.
13. **Alasan:** Mengikuti koreksi 2A.8R1; menghindari NIS rollback/reissue untuk kegagalan setelah commit.
14. **Dependensi:** Kebijakan account lifecycle secara unconditional bergantung pada OD-10 (sumber kebijakan username termasuk reservation), OD-11, dan OD-18. Jika Hybrid dipilih, OD-04 wajib mengadopsi kebijakan reservation OD-10 yang disetujui; OD-04 tidak membuat keputusan reservation kedua. Bila mekanisme reservation digunakan sesuai kebijakan OD-10, OD-01 (target database) dan OD-12 (database privilege) menjadi conditional technical dependencies untuk uniqueness, locking, dan privilege enforcement. Jika reservation tidak dipilih/digunakan, conditional dependency reservation pada OD-04 tidak aktif.
15. **Bukti/validasi:** Login deny/allow tests; canonical relation reconciliation; unique username collision; worker idempotency/recovery hanya jika provisioning asynchronous; transaction rollback untuk provisioning synchronous; jika reservation digunakan sesuai OD-10, uniqueness lintas seluruh account writers, target locking/race safety, expiry/fencing/revalidation, dan privilege enforcement; operational requirement for TU/PPDB.
16. **Pihak berwenang:** Owner kebijakan akun/login bersama owner proses sekolah; nama/jabatan dan delegasi manual review harus ditentukan.
17. **Ruang keputusan owner:** `Provisioning mode (synchronous/asynchronous): ____` / `Hybrid digunakan?: ____` / `Jika ya, referensi keputusan/kebijakan OD-10: ____` / `Login eligibility: ____` / `Notification/credential: ____`. Keputusan apakah reservation diperbolehkan dan syaratnya dicatat hanya pada OD-10.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** AC-ACC-01 khusus async, AC-ACC-04 khusus sync, Hybrid tidak otomatis async. OD-10 adalah satu-satunya sumber keputusan kebijakan reservation; OD-04 hanya memilih penerapannya sebagai bagian Hybrid. Rekomendasi outbox terdahulu bukan approval.

### OD-05 — Authority dan Workflow Koreksi Data

1. **ID/judul:** OD-05 — Otoritas dan alur koreksi pasca-publication.
2. **Status:** `PENDING`.
3. **Latar belakang:** NIS/slot yang sudah terbit immutable; kesalahan nama, entry facts, cohort atau link tidak boleh diperbaiki dengan reissue.
4. **Masalah:** Siapa dapat mengajukan, memverifikasi, menyetujui, serta mencatat koreksi belum ditetapkan.
5. **Pertanyaan owner:** Apakah pemohon boleh approve? Siapa yang memverifikasi bukti? Koreksi mana yang bisa dilakukan di aplikasi? Bagaimana kasus NIS salah ditangani tanpa reuse?
6. **Alternatif:** (A) Requester, verifier dan approver berbeda; (B) satu pejabat boleh meminta sekaligus menyetujui; (C) correction di luar aplikasi dengan audit record; (D) hanya koreksi metadata tertentu, identitas NIS tidak dapat diubah.
7. **Plus/minus:** A kuat separation tetapi lebih lambat; B mudah namun konflik kepentingan; C menjaga aplikasi namun perlu proses eksternal yang dapat diaudit; D sederhana namun perlu exception administratif untuk kasus tidak tercakup.
8. **Dampak arsitektur:** Append-only correction/event, approval states, registry link, blocking rules dan audit.
9. **Dampak keamanan/integritas:** Perubahan tanpa dual control dapat menautkan NIS ke orang salah; immutable NIS melindungi histori.
10. **Dampak operasional:** Perlu form/request, evidence reference, review SLA, dan jalur eskalasi.
11. **Risiko penundaan:** Data konflik tidak dapat diselesaikan, cohort lain dapat terus hardblock, dan perubahan manual sulit diaudit.
12. **Rekomendasi teknis:** Request → verification → separate approval; correction append-only; tidak mengubah NIS, slot, atau snapshot issue.
13. **Alasan:** Menyelaraskan auditability dengan immutability dan mengurangi self-approval.
14. **Dependensi:** OD-18 governance; terkait OD-06 reservation dan OD-16 delete/relink.
15. **Bukti/validasi:** Correction case fixtures, evidence types, authorization allow/deny, audit immutability, retry/idempotency.
16. **Pihak berwenang:** Owner menetapkan pejabat peminta/verifier/approver dan exception authority.
17. **Ruang keputusan owner:** `Workflow: ____` / `Self-approval: ____` / `Exception: ____` / `Pihak: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** 2A.7R graph menetapkan OD-18 sebagai dependency; historical provenance 2A.1/2A.2 tidak tersedia.

### OD-06 — Legacy NIS Collision dan Reservation

1. **ID/judul:** OD-06 — Collision NIS legacy dan reservasi slot.
2. **Status:** `PENDING`.
3. **Latar belakang:** Legacy NIS harus dipertahankan exact; snapshot lokal tidak representatif dan provenance year/grade/sequence belum lengkap.
4. **Masalah:** Collision exact antara legacy dan candidate sequence dapat menghalangi publication; auto-skip/reserve/remap dapat mengubah nomor permanen.
5. **Pertanyaan owner:** Apa bukti yang cukup untuk reservation? Siapa requester/approver? Apakah default selalu hardblock? Bagaimana exact legacy string dicatat?
6. **Alternatif:** (A) Hardblock sampai rekonsiliasi selesai; (B) reserve exact slot permanen dengan evidence, reason, approver dan event audit; (C) tidak menggunakan reservation, hanya reconciliation; (D) remap NIS lama—bertentangan dengan baseline dan tidak direkomendasikan.
7. **Plus/minus:** A melindungi identitas tetapi menunda issuance; B menyelesaikan kasus dengan kontrol tetapi mengonsumsi slot; C paling sederhana namun beberapa collision tidak selesai; D menghindari collision lokal tetapi mengubah identitas yang telah digunakan.
8. **Dampak arsitektur:** Registry, slot `reserved`, reconciliation event, unique tuple, allocator high-water dan workflow approval.
9. **Dampak keamanan/integritas:** Reservation tanpa bukti/role dapat memblokir slot atau memungkinkan reuse; audit harus immutable.
10. **Dampak operasional:** Membutuhkan inventory NIS aktif/deleted, bukti administratif dan reviewer yang ditunjuk.
11. **Risiko penundaan:** Cohort terdampak tidak dapat diterbitkan; auto-skip berisiko nomor dipakai ulang/terlewat tanpa otoritas.
12. **Rekomendasi teknis:** Fail closed; jangan auto-reserve, auto-skip, normalize, remap atau delete. Reservation hanya melalui kasus disetujui.
13. **Alasan:** Menjaga exact legacy string dan menghindari dugaan sejarah dari format/current grade.
14. **Dependensi:** OD-12 (ledger/privilege), OD-15 (year-code context), OD-18 (approver); inventory adalah bukti teknis tambahan.
15. **Bukti/validasi:** Authorized inventory termasuk soft-deleted; exact collision fixtures; reservation transaction/unique/locking and rollback tests.
16. **Pihak berwenang:** Owner menetapkan reviewer/approver legacy; DBA/engineering membuktikan ledger enforcement.
17. **Ruang keputusan owner:** `Default: ____` / `Reservation allowed: ____` / `Evidence: ____` / `Approver: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** Bedakan NIS slot reservation (OD-06) dari username reservation (OD-04/10); keduanya bukan hal yang sama.

### OD-07 — Transfer Entry Evidence

1. **ID/judul:** OD-07 — Bukti entry siswa pindahan/manual.
2. **Status:** `PENDING`.
3. **Latar belakang:** Year/grade NIS mencerminkan entry pertama yang disahkan di SDN Mekar Jaya, bukan kelas sekarang, sekolah asal, atau tanggal issue.
4. **Masalah:** Dokumen yang sah, verifier, approver dan fallback bila bukti hilang belum didefinisikan.
5. **Pertanyaan owner:** Dokumen mana diterima? Siapa verifikasi? Siapa mengesahkan year/entry_grade? Apa proses untuk arsip tidak lengkap? Bagaimana cutoff membedakan initial vs supplemental?
6. **Alternatif:** (A) Dokumen transfer/penerimaan resmi yang ditentukan owner; (B) arsip administrasi sekolah yang dapat diverifikasi; (C) manual exception berbukti dengan dual review; (D) hardblock jika tidak ada bukti memadai.
7. **Plus/minus:** A konsisten tetapi mungkin tidak tersedia pada kasus lama; B menjangkau arsip tapi mutunya beragam; C menangani pengecualian namun membutuhkan audit/separation; D fail-closed namun siswa tertahan.
8. **Dampak arsitektur:** Entry-history record, provenance pointer, approver, `official_entry_at`, cohort membership, cutoff dan placement.
9. **Dampak keamanan/integritas:** Entry grade keliru menghasilkan identitas/sequence keliru; jangan infer dari class/current data.
10. **Dampak operasional:** TU perlu checklist evidence, storage/reference, review role dan proses exception.
11. **Risiko penundaan:** Transfer tidak dapat dimasukkan dengan benar; dapat salah diperlakukan sebagai kelas 1 atau cohort lain.
12. **Rekomendasi teknis:** Owner menetapkan daftar dokumen/exception; tanpa bukti terverifikasi, hardblock. Tidak ada fallback otomatis.
13. **Alasan:** Konsisten dengan baseline first accepted entry dan fail-closed.
14. **Dependensi:** OD-14 timestamp/effective date, OD-15 mapping, OD-18 approver; OD-09 placement.
15. **Bukti/validasi:** SOP/dokumen yang diakui owner, anonymized fixtures pre/post initial, authority tests.
16. **Pihak berwenang:** Owner kebijakan penerimaan/administrasi menetapkan evidence dan approver; nama jabatan belum diketahui.
17. **Ruang keputusan owner:** `Evidence accepted: ____` / `Verifier: ____` / `Approver: ____` / `Exception policy: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** 2A.3, OD-07 dan 2A.8 mempertahankan prinsip entry-first-at-school; tidak ada bukti approval historis 2A.1/2A.2.

### OD-08 — Supplemental Issuance Ordering

1. **ID/judul:** OD-08 — Urutan supplemental.
2. **Status:** `PENDING`.
3. **Latar belakang:** Issued sequence tidak dapat diurut ulang; dua formulasi tercatat, satu tanpa ordinal dan satu dengan ordinal.
4. **Masalah:** Timestamp sama/precision rendah atau batas batch dapat membuat hasil berbeda bila urutan tidak lengkap.
5. **Pertanyaan owner:** Pilih `(official_entry_at, comparator name, siswa.id)` atau `(official_entry_at, cohort ordinal, comparator name, siswa.id)`? Apakah urutan dalam satu batch atau across all eligible events yang menentukan?
6. **Alternatif:** (A) timestamp → comparator → ID; (B) timestamp → permanent cohort ordinal → comparator → ID; (C) aturan deterministic lain yang disahkan owner; (D) hentikan supplemental sampai diputuskan.
7. **Plus/minus:** A lebih sederhana tetapi tie timestamp dapat bergantung nama/runtime; B memperjelas chronology dan replay namun memerlukan ordinal assign-under-lock; C fleksibel tetapi harus diuji; D menghindari keputusan arbitrer namun menunda penerbitan.
8. **Dampak arsitektur:** Entry-history sequence, comparator, preview snapshot, batch boundary dan allocator.
9. **Dampak keamanan/integritas:** Perbedaan hasil menghasilkan NIS permanen berbeda; ordinal tidak boleh dihitung ulang saat issue.
10. **Dampak operasional:** Pemilik harus memahami apakah tanggal resmi masuk atau nama sekunder menentukan urutan nomor.
11. **Risiko penundaan:** Supplemental tidak dapat diterbitkan; memilih asal-asalan membuat hasil tergantung jadwal/batch.
12. **Rekomendasi teknis:** Pertimbangkan B untuk tie determinism, tetapi jangan pilih tanpa owner approval; ordinal harus permanen pada saat official entry.
13. **Alasan:** 2A.8R1 menyatakan urutan recommended `official_entry_at → cohort_entry_ordinal → approved comparator → siswa.id`, tetapi statusnya tetap recommendation.
14. **Dependensi:** OD-03 comparator, OD-14 timestamp, OD-15 year-code.
15. **Bukti/validasi:** Golden ordering across equal timestamps, batch-window variations, retries and runtimes; immutable ordinal tests.
16. **Pihak berwenang:** Owner kebijakan penerbitan menentukan prioritas kronologis vs alfabetis; Engineering membuktikan determinism.
17. **Ruang keputusan owner:** `Order: ____` / `Ordinal policy: ____` / `Equal timestamp rule: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** Dua varian telah dicatat dalam 2A.7R/2A.8; 2A.8R1 mengukuhkan rekomendasi ordinal tanpa approval.

### OD-09 — Valid Placement Definition

1. **ID/judul:** OD-09 — Definisi placement valid.
2. **Status:** `PENDING`.
3. **Latar belakang:** Placement adalah prasyarat NIS; data lokal memiliki gap uniqueness placement dan sparse snapshot tidak membuktikan seluruh kondisi.
4. **Masalah:** Satu record placement belum cukup jika year, grade, status, kelas/rombel atau relasinya tidak valid.
5. **Pertanyaan owner:** Status/tanggal apa membuat placement valid? Haruskah tepat satu placement aktif per siswa/tahun? Apakah temporary placement dibolehkan? Siapa menyelesaikan konflik?
6. **Alternatif:** (A) Satu placement aktif dengan class/year/grade valid; (B) named temporary/exception state dengan approval; (C) kombinasi status formal dan tanggal efektif yang owner tetapkan; (D) hardblock jika tidak memenuhi satu definisi tunggal.
7. **Plus/minus:** A deterministik namun perlu definisi status; B mendukung transisi namun rawan bypass; C mencerminkan administrasi tetapi lebih kompleks; D mengurangi ambiguitas namun menunda siswa belum lengkap.
8. **Dampak arsitektur:** Eligibility resolver, unique constraint, snapshot revision, cohort lock dan mutation paths.
9. **Dampak keamanan/integritas:** Ganda/inactive/mismatched placement dapat mengeluarkan NIS untuk cohort/entry grade salah.
10. **Dampak operasional:** Placement anomalies harus terlihat di preview dan punya owner untuk remediasi.
11. **Risiko penundaan:** Tidak ada hardblock konsisten; publisher dapat issue pada kelas yang salah.
12. **Rekomendasi teknis:** Minimum satu placement sah pada tahun/grade yang relevan; hardblock missing, duplicate atau contradiction; exceptions named/authorized/audited.
13. **Alasan:** Mencegah silent inclusion/exclusion dan menutup gap validitas yang tidak dijaga record count.
14. **Dependensi:** OD-07 (entry evidence), OD-15 (year mapping), OD-18 (exception authority); teknis juga OD-01.
15. **Bukti/validasi:** Owner status/date rules; fixtures missing/multiple/inactive/year-grade mismatch; placement mutation lock/revision tests.
16. **Pihak berwenang:** Owner akademik/administrasi menetapkan aturan; peran formal belum disebutkan.
17. **Ruang keputusan owner:** `Valid status/date: ____` / `Multiplicity: ____` / `Exceptions: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** Baseline menyebut placement required; detail sah/exception tetap pending.

### OD-10 — Username Collision

1. **ID/judul:** OD-10 — Collision username.
2. **Status:** `PENDING`.
3. **Latar belakang:** Username unik pada schema lokal; legacy account dapat memakai candidate yang diperlukan siswa lain. NIS collision pada registry adalah isu terpisah.
4. **Masalah:** Auto-rename/merge/link dapat memberikan akses ke akun orang lain atau mengubah login yang sudah digunakan.
5. **Pertanyaan owner:** Apakah username harus sama dengan NIS? Apakah username reservation diperbolehkan; jika ya, dalam kondisi/alasan apa? Apa aturan uniqueness dan ownership? Siapa berwenang menyetujui reservation/collision? Bagaimana expiry, release/fencing, publication revalidation, dan collision ditangani? Apa bukti teknis wajib bila reservation dipilih?
6. **Alternatif:** (A) Reservation tidak diperbolehkan; collision di-hardblock dan direkonsiliasi manual. (B) Reservation diperbolehkan hanya pada kondisi/alasan yang ditetapkan owner, dengan ownership, uniqueness, expiry/release/fencing, revalidation, collision handling, dan otoritas approval yang eksplisit. (C) Kebijakan reservation ditunda; sampai diputuskan tidak ada reservation. (D) Untuk collision tanpa reservation, owner dapat memilih hardblock/manual reconciliation, username alternatif dengan format disetujui, atau controlled rename setelah bukti dan approval terpisah.
7. **Plus/minus:** A paling sederhana namun dapat menunda account; B dapat mengurangi race hanya jika claim otoritatif dan seluruh writers menghormatinya, tetapi membutuhkan kontrol dan bukti teknis lengkap; C menghindari asumsi namun menahan penggunaan reservation; D dapat menyelesaikan collision tanpa reservation tetapi perubahan username/akun berdampak pada login dan perlu persetujuan.
8. **Dampak arsitektur:** Kebijakan ini menjadi sumber tunggal untuk izin/kondisi reservation, uniqueness, ownership, approval, expiry/release/fencing, revalidation, dan collision. Bila reservation dipilih, berdampak pada database uniqueness, locking, privilege enforcement dan seluruh account writers; juga berinteraksi dengan OD-04 provisioning, login, reset password, dan notification.
9. **Dampak keamanan/integritas:** Salah merge/rename/link adalah risiko akses lintas identitas; tidak boleh terjadi otomatis.
10. **Dampak operasional:** Manual-review workload; komunikasi username dan proses pemulihan.
11. **Risiko penundaan:** Account provisioning dapat gagal; fallback otomatis dapat mengambil alih akun lama.
12. **Rekomendasi teknis:** Owner memutuskan apakah reservation diperbolehkan beserta kondisi/alasan, uniqueness/ownership, otoritas approval, expiry/release/fencing, revalidation, dan collision handling. Sampai ada keputusan, reservation tidak digunakan. Tidak ada auto-merge, auto-rename, atau auto-link. Bila reservation dipilih, bukti teknis wajib mencakup database uniqueness, locking/race safety, privilege enforcement, dan uniqueness lintas seluruh account writers.
13. **Alasan:** OD-10 harus menjadi satu-satunya owner decision atas kebijakan username reservation; pemisahan ini mencegah kebijakan ganda di OD-04 serta menjaga account ownership.
14. **Dependensi:** Kebijakan username collision/reservation secara unconditional bergantung pada OD-11 canonical relation dan OD-18 approval authority. OD-04 bergantung pada kebijakan OD-10 untuk account lifecycle dan mengadopsi kebijakan reservation hanya jika Hybrid memakainya. Jika reservation diperbolehkan dan dipilih/digunakan, OD-01 dan OD-12 menjadi conditional technical dependencies untuk target database uniqueness/locking dan privilege enforcement. Tanpa reservation, dependency tambahan tersebut tidak aktif.
15. **Bukti/validasi:** Complete username/account inventory, collation/unique behavior, candidate collision tests, login/authorization tests; jika reservation diperbolehkan dan dipilih, target DB uniqueness dan locking/race tests, privilege enforcement, ownership/expiry/fencing/revalidation tests, serta coverage seluruh account writers.
16. **Pihak berwenang:** Owner akun/login menetapkan kebijakan reservation, kondisi dan alasan; owner juga menetapkan role/authority approver. Tidak ada pihak atau kewenangan yang diasumsikan.
17. **Ruang keputusan owner:** `Reservation diperbolehkan?: ____` / `Kondisi/alasan: ____` / `Uniqueness dan ownership: ____` / `Expiry/release/fencing/revalidation/collision: ____` / `Otoritas approval: ____` / `Aturan collision tanpa reservation: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** 2A.8R1 menetapkan reservation sebagai dimensi terpisah dari provisioning. OD-10 adalah sumber tunggal keputusan kebijakan reservation; OD-04 tidak mengubah atau menduplikasi keputusan tersebut. Reservation tidak otomatis berarti asynchronous provisioning.

### OD-11 — Canonical User–Siswa Relationship

1. **ID/judul:** OD-11 — Relasi resmi User–Siswa.
2. **Status:** `PENDING`.
3. **Latar belakang:** Login path yang dicatat mencari Siswa lewat NIS lalu `siswa.user`; schema/model juga memiliki `users.siswa_id`. Data lengkap belum direkonsiliasi.
4. **Masalah:** Dua arah relasi dapat drift, one-to-one tidak terbukti, dan perubahan canonical relation memengaruhi login/provisioning/delete.
5. **Pertanyaan owner:** FK mana source of truth? Apakah link one-to-one? Bagaimana migrasi field lama, orphan dan duplicate? Bagaimana relink dengan evidence?
6. **Alternatif:** (A) `siswa.user_id` canonical; (B) `users.siswa_id` canonical; (C) dua FK dengan satu authority dan DB-enforced consistency; (D) transisi bertahap dengan compatibility field read-only lalu removal.
7. **Plus/minus:** A sejalan dengan login yang tercatat tetapi workflows lain perlu disesuaikan; B memerlukan auth changes; C mempertahankan dual data namun butuh sync enforcement; D mengurangi cutover tetapi menimbulkan risiko dual-write bila tidak one-source.
8. **Dampak arsitektur:** Auth lookup, FK, uniqueness, service ownership, account lifecycle, delete/restore/relink.
9. **Dampak keamanan/integritas:** Link yang salah dapat memberi akses account ke Siswa lain; no auto-link by name.
10. **Dampak operasional:** Perlu reconciliation list, conflict resolution dan staged cutover plan.
11. **Risiko penundaan:** Account creation/login/migration tidak dapat difinalkan; jangan backfill otomatis.
12. **Rekomendasi teknis:** `siswa.user_id` hanya kandidat berdasar jalur login; putuskan setelah inventory seluruh reader/writer dan data reconciliation. Terapkan one-to-one pada target bila sesuai.
13. **Alasan:** Sumber menyatakan jalur login saat ini demikian, tetapi itu bukan persetujuan canonical model.
14. **Dependensi:** Owner decision independen pada governance graph; implementasi dipengaruhi OD-04/10/12/16 dan technical reconciliation.
15. **Bukti/validasi:** Complete pairwise FK aggregates, orphan/duplicate/mismatch inventory, all callsites, auth allow/deny and target constraints tests.
16. **Pihak berwenang:** Owner sistem/identitas siswa; DBA/engineering membuktikan migrasi aman.
17. **Ruang keputusan owner:** `Canonical relation: ____` / `Transition: ____` / `Orphan/duplicate handling: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** `siswa.user_id` tidak dinaikkan dari kandidat menjadi keputusan; OD-11 tetap PENDING.

### OD-12 — Database Privileges dan Least Privilege

1. **ID/judul:** OD-12 — Pemisahan privilege database.
2. **Status:** `PENDING`.
3. **Latar belakang:** 2A.6 mencatat grant lokal global `*.*` termasuk DML/DDL/admin dan `GRANT OPTION`; bukan target privilege.
4. **Masalah:** Runtime dengan hak berlebih dapat melewati service/trigger dan merusak ledger; migration/DBA access perlu dibedakan.
5. **Pertanyaan owner:** Role apa untuk runtime, migration/deployment, DBA, backup/restore? Hak tabel/procedure apa yang diperlukan? Siapa mengaudit break-glass?
6. **Alternatif:** (A) Separate least-privilege app, migration, DBA accounts; (B) restricted app account ditambah DB constraints/triggers dan controlled break-glass; (C) satu shared privileged account—tidak direkomendasikan.
7. **Plus/minus:** A/C paling mudah diaudit bila benar diterapkan namun butuh secret/role management; B memberi defense-in-depth tetapi trigger support wajib diuji; C sederhana namun blast radius tinggi dan klaim immutability tidak kuat.
8. **Dampak arsitektur:** Hak SELECT/INSERT/UPDATE/DELETE/DDL/trigger, deployment pipeline, audit, backup/restore, publication service.
9. **Dampak keamanan/integritas:** Runtime DDL/admin/GRANT OPTION memungkinkan bypass; privilege minim mengurangi perubahan tidak sah.
10. **Dampak operasional:** Perlu credential separation, rotasi, deployment role elevation terbatas, DBA audit dan restore procedure.
11. **Risiko penundaan:** Tidak dapat mengklaim registry/slot immutable atau mengaktifkan writer.
12. **Rekomendasi teknis:** Pisahkan application runtime, migration/deployment, DBA; runtime hanya hak minimum; no global admin grant; gunakan constraints/triggers sesuai target dan documented break-glass.
13. **Alasan:** Sesuai OD-12/TE-12/TV-05 dan temuan grant lokal.
14. **Dependensi:** OD-01 menetapkan fitur engine; menjadi prasyarat OD-06/13/16/17 sesuai dependency graph.
15. **Bukti/validasi:** Approved role/grant matrix; effective grants; direct-SQL deny tests; trigger/FK `SET NULL`; backup/restore authorization test.
16. **Pihak berwenang:** Owner menyetujui risk/role policy; DBA/Ops merancang grants dan membuktikan effective privileges.
17. **Ruang keputusan owner:** `Runtime grants: ____` / `Deployment grants: ____` / `DBA/break-glass: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** Tidak ada grant diubah. Bukti lokal bukan konfigurasi deployment.

### OD-13 — Publication Atomicity

1. **ID/judul:** OD-13 — Atomicity dan ukuran publication batch.
2. **Status:** `PENDING`.
3. **Latar belakang:** Baseline mengharuskan all-or-nothing publication; tidak ada target-sized benchmark/restore drill.
4. **Masalah:** Publication menyentuh registry, sequence slots, Siswa NIS, batch/snapshot/cutoff dan dapat menyentuh account intent/reservation; transaction besar dapat timeout/deadlock.
5. **Pertanyaan owner:** Apakah publication selalu all-or-nothing? Kapan numeric cap ditetapkan? Apa dilakukan jika cohort melebihi cap? Apakah chunked staging dapat diterima hanya jika tidak terlihat partial?
6. **Alternatif:** (A) satu transaction per batch; (B) staged/chunked internal processing hanya jika staging belum dianggap NIS published, tidak ada partial publication yang terlihat pengguna, dan final visibility/commit memenuhi atomic publication contract; (C) partial publication; (D) cap numerik sekarang tanpa benchmark.
7. **Plus/minus:** A mudah dibuktikan tetapi mungkin menahan lock; B dapat mengurangi kerja internal dalam satu langkah namun menambah kompleksitas dan hanya setara dengan all-or-nothing bila atomic final visibility/commit benar-benar dapat dijamin serta dibuktikan; jika tidak, B bukan alternatif yang setara dan memerlukan desain/bukti tambahan; C bertentangan dengan baseline serta membuat partial issuance terlihat; D tidak berdasar evidence.
8. **Dampak arsitektur:** Locking, transaction length, attempt logging terpisah, outbox/reservation, rollback/retry, batch state.
9. **Dampak keamanan/integritas:** Partial issuance dapat menyulitkan audit dan menyebabkan slot/NIS inconsistent; failure logging tidak boleh memberi success-shaped state.
10. **Dampak operasional:** Timeout, operator recovery, monitoring, rollback/forward fix.
11. **Risiko penundaan:** Batas volume dan recovery tidak dapat difinalkan; tidak aman untuk batch production-size.
12. **Rekomendasi teknis:** Pertahankan all-or-nothing sebagai baseline. Staging/chunking tidak menerbitkan NIS dan tidak boleh membuat partial state terlihat; final visibility/commit harus memenuhi atomic publication contract. Jika satu atomic commit/final visibility tidak dapat dijamin, jangan menyebut pendekatan tersebut setara dengan all-or-nothing tanpa desain dan bukti tambahan. Benchmark sintetis diperlukan sebelum batas numerik ditetapkan.
13. **Alasan:** Lokal sparse bukan basis kapasitas; AC-PUB-03/04 dan TE-19/20 belum lulus. Memecah pekerjaan internal tidak mengubah kontrak bahwa NIS baru published setelah commit atomik dan tidak ada partial publication yang terlihat pengguna.
14. **Dependensi:** OD-01 dan OD-12; OD-17 bergantung pada contract/volume ini.
15. **Bukti/validasi:** Target-sized benchmark, lock duration, failure injection, rollback, uncertain commit, retry, deadlock/recovery, backup restore.
16. **Pihak berwenang:** Owner menyetujui atomicity/risk; Ops/DBA memberi target volume/SLO; engineering menguji.
17. **Ruang keputusan owner:** `Atomicity: ____` / `Cap (setelah benchmark): ____` / `At-cap behavior: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** Attempt/failure log dipisah sesuai desain 2A.3; tidak ada benchmark pada data lokal.

### OD-14 — Timestamp Authority dan Semantics

1. **ID/judul:** OD-14 — Otoritas waktu dan timestamp.
2. **Status:** `PENDING`.
3. **Latar belakang:** 2A.7R memisahkan `recorded_at`, `effective_at`, `official_entry_at`; official entry dibuat post-lock dalam transaction; cutoff/ordinal masih proposal.
4. **Masalah:** Timestamp administratif dan teknis tidak boleh tercampur; precision/equality/skew memengaruhi batas cohort dan urutan supplemental.
5. **Pertanyaan owner:** Clock authority apa? UTC/precision? Watermark atau timestamp cutoff? Apa aturan equality? Apakah effective date hanya administratif? Bagaimana correction mempertahankan original event?
6. **Alternatif:** (A) DB UTC clock dengan cohort ordinal/watermark; (B) timestamp-only cutoff dengan explicit `<`/`<=` equality; (C) trusted application clock dengan monitored drift; (D) hybrid clock only after target validation.
7. **Plus/minus:** A deterministic but needs DB/ordinal validation; B simpler but equal precision ambiguous; C easier app integration but clock skew risk; D provides fallback but coordination complexity.
8. **Dampak arsitektur:** Entry history, initial cutoff, supplemental order, snapshots, correction events, audit.
9. **Dampak keamanan/integritas:** Clock mutation/backdating must not reorder issued NIS; audit chronology and administrative date must remain distinct.
10. **Dampak operasional:** Display timezone, official timestamp source, reconciliation of clock drift and audit interpretation.
11. **Risiko penundaan:** Cohort boundary ambiguity and non-deterministic supplemental sequence.
12. **Rekomendasi teknis:** Store technical timestamps UTC; keep `effective_at` administrative; set immutable `official_entry_at` after lock in same approval transaction; use permanent ordinal/watermark only after owner/target validation.
13. **Alasan:** Separates administrative record from event order and supports tie determinism without rewriting history.
14. **Dependensi:** OD-01 for clock/precision engine behavior; OD-07/08 depend on timestamp semantics.
15. **Bukti/validasi:** DB clock precision, failover/skew, UTC storage, equality, ordinal monotonicity and cutoff concurrency tests.
16. **Pihak berwenang:** Owner menetapkan makna administratif/cutoff; Ops/DBA membuktikan clock; engineering menguji.
17. **Ruang keputusan owner:** `Clock authority: ____` / `Cutoff: ____` / `Equality: ____` / `effective_at policy: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** 2A.8R1 defines recommendation, not approval. Do not equate correction date with issue order.

### OD-15 — Year Code Mapping

1. **ID/judul:** OD-15 — Mapping year code.
2. **Status:** `PENDING`.
3. **Latar belakang:** Contoh baseline `2026/2027 → 2627`; general parser, semester mapping, ambiguous source records and century rollover remain unresolved.
4. **Masalah:** Wrong or guessed year code changes NIS identity and sequence partition.
5. **Pertanyaan owner:** Format label valid apa? Apakah semester records share one cohort? How handle multiple records/renames/rollover? Who validates mapping?
6. **Alternatif:** (A) Explicit approved mapping table per academic cohort; (B) validated parser with constrained format plus owner-approved rollover; (C) parser for suffix rule after exhaustive representative validation; (D) hardblock unknown/ambiguous labels.
7. **Plus/minus:** A auditable but needs maintained map; B balances scale/control but requires strict validation; C convenient but risks ambiguous/inconsistent labels; D prevents guessing but requires manual disposition.
8. **Dampak arsitektur:** Cohort key, unique year_code, sequence/entry history, registry and batch lock.
9. **Dampak keamanan/integritas:** Mapping errors split/merge cohorts or encode wrong year; issuance cannot be corrected by simply changing code.
10. **Dampak operasional:** School-year setup and label correction must follow controlled process.
11. **Risiko penundaan:** Cohort creation and publication blocked; auto parse may silently assign wrong identity.
12. **Rekomendasi teknis:** Explicit validated mapping; preserve `2026/2027 → 2627`; reject ambiguous labels and never infer from class/current year or issue date.
13. **Alasan:** Mapping umum tidak dibuktikan oleh one example; maintainable audit trail.
14. **Dependensi:** Independent owner policy; OD-06/07/08/09/17 depend on cohort mapping.
15. **Bukti/validasi:** Accepted label fixtures, semester/cohort pairing, duplicate mapping, invalid label and century rollover tests.
16. **Pihak berwenang:** Owner akademik menetapkan definisi tahun ajaran/cohort; Ops/data steward verifies source mappings.
17. **Ruang keputusan owner:** `Mapping rule: ____` / `Source label authority: ____` / `Rollover: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** 2A.3 §16.1 records open questions; 2A.9 marks historical provenance unverified.

### OD-16 — Delete, Restore, dan Relink Policy

1. **ID/judul:** OD-16 — Lifecycle Siswa dan relink account.
2. **Status:** `PENDING`.
3. **Latar belakang:** Soft delete, force delete, restore dan relink dapat memutus Siswa operasional dari registry/account; slot/NIS harus tidak dapat dipakai ulang.
4. **Masalah:** FK/cascade, operational link, tombstone, restore identity verification dan relink authority belum final.
5. **Pertanyaan owner:** Apa yang boleh dihapus? Apakah force delete dibolehkan? Bukti apa untuk restore/relink? Siapa approve? Bagaimana account lama dinonaktifkan?
6. **Alternatif:** (A) Soft-delete only, immutable registry/slot retained; (B) allow force-delete but permanent tombstone snapshots; (C) restore same verified Siswa/NIS; (D) recreate new operational record with audited evidence-based relink, never reuse slot.
7. **Plus/minus:** A simplest but retention/privacy constraints; B accommodates cleanup but demands non-cascading ledger; C preserves identity but requires robust matching evidence; D supports recreation but risks wrongful relink.
8. **Dampak arsitektur:** FK RESTRICT/SET NULL, registry snapshots, tombstone, account canonical relation, audit events.
9. **Dampak keamanan/integritas:** Cascade delete can erase identity evidence or transfer login to wrong student; no link by name alone.
10. **Dampak operasional:** Define support workflow, proof, reviewer, retention, privacy and break-glass.
11. **Risiko penundaan:** Delete/relink may erase ledger, orphan accounts or permit NIS reuse.
12. **Rekomendasi teknis:** Preserve registry/slot permanently; restore same identity only after verification; relink via authorized append-only event; never reuse NIS.
13. **Alasan:** Satisfies immutability while acknowledging operational Siswa/User records may change.
14. **Dependensi:** OD-05 correction authority, OD-11 canonical link, OD-12 DB protection, OD-18 governance.
15. **Bukti/validasi:** FK/trigger behavior, force-delete/restore/recreate tests, access controls, duplicate identity recovery fixtures.
16. **Pihak berwenang:** Owner menetapkan deletion/retention/relink policy; privacy/data governance and DBA roles must be identified.
17. **Ruang keputusan owner:** `Delete policy: ____` / `Restore evidence: ____` / `Relink approval: ____` / `Retention: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** Relasi canonical belum disetujui; no backfill/auto-link permitted.

### OD-17 — One Initial Batch per Cohort

1. **ID/judul:** OD-17 — Satu initial publication per cohort.
2. **Status:** `PENDING`.
3. **Latar belakang:** Initial batch dirancang mencakup seluruh siswa pre-cutoff lintas jalur/kelas; pre-cutoff omission hardblocks supplemental.
4. **Masalah:** Cohort definition, readiness/cutoff, duplicate publication, cancellation dan retry harus dapat dibuktikan secara concurrent.
5. **Pertanyaan owner:** Bagaimana cohort/initial defined? Kapan seluruh kandidat dianggap siap? Apakah satu attempt gagal bisa diulang? Bagaimana cancel? Apakah second initial pernah dibolehkan?
6. **Alternatif:** (A) One batch per cohort, lock + unique guard + same-batch retry; (B) retry same batch after preview revision, no second initial; (C) create a new batch for correction—berisiko melanggar exactly-one; (D) owner-defined explicit cancellation/reopen exception with immutable audit.
7. **Plus/minus:** A protects identity but readiness defects block everyone; B supports recovery without duplicate issuance; C operationally simple but violates baseline; D handles exceptional governance but needs strong authorization.
8. **Dampak arsitektur:** Cohort row/lock, unique initial guard, candidate snapshot, cutoff watermark, attempt lifecycle and idempotency.
9. **Dampak keamanan/integritas:** Concurrent publishers must not issue different initial sequence sets.
10. **Dampak operasional:** TU/PPDB must resolve placement/eligibility anomalies before approval; cancellation must not permit silent batch duplication.
11. **Risiko penundaan:** Duplicate batches, omitted students, or inconsistent cutoff.
12. **Rekomendasi teknis:** Exactly one initial batch; cohort lock plus target DB unique guard and idempotent retry; hardblock missing/invalid placement; failed batch retries same logical batch.
13. **Alasan:** Aligns AC-COH-02/PUB-02/04 and prevents sequence identity from depending on retries.
14. **Dependensi:** OD-01, OD-12, OD-13, OD-15; dependency rationale in matrix.
15. **Bukti/validasi:** Concurrent publisher tests (same/different keys), failed/cancel lifecycle, retry after uncertain commit, one-initial unique guard, eligibility completeness.
16. **Pihak berwenang:** Owner menetapkan definition/cutoff/cancel authority; Ops/DBA and engineering validate race-safety.
17. **Ruang keputusan owner:** `Cohort definition: ____` / `Readiness/cutoff: ____` / `Cancel/retry: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** 2A.7R dependency graph removes OD-13/OD-17 cycle; no initial second publication is approved.

### OD-18 — Role Separation dan Approval Governance

1. **ID/judul:** OD-18 — Roles, separation of duties and approval governance.
2. **Status:** `PENDING`.
3. **Latar belakang:** NIS-specific role map tidak tersedia; publish, reserve, correct and manual review require authority boundaries.
4. **Masalah:** Self-approval or one broad role can authorize irreversible identity changes; no named roles/personnel supplied.
5. **Pertanyaan owner:** Who may prepare, publish, reserve, correct, audit, approve exception? Which actions require dual control? Is requester allowed to approve own request?
6. **Alternatif:** (A) Separate duties for preparation, publication, reservation/correction approval and audit; (B) allow one role for routine actions but dual approval for irreversible exceptions; (C) one broad role—simple but highest risk.
7. **Plus/minus:** A strongest separation but operationally demanding; B balances workload and risk if thresholds are explicit; C simple but self-approval/conflict risk.
8. **Dampak arsitektur:** Authorization policy, actor snapshots, audit records, approval events, UI/service checks and DB privilege separation.
9. **Dampak keamanan/integritas:** Unauthorized publication/correction/reservation can create irreversible NIS, consume slots, or corrupt identity links.
10. **Dampak operasional:** Need role assignment, absence coverage, escalation and periodic access review.
11. **Risiko penundaan:** No valid approver for exception/correction; unsafe informal approvals.
12. **Rekomendasi teknis:** Define permissions per action; require distinct approver for high-impact exceptions; prevent self-approval; log immutable actor/reason/evidence.
13. **Alasan:** Supports governance for OD-04–07/09–10/16 and avoids relying on DB privileges as application roles.
14. **Dependensi:** Independent governance decision in 2A.7R graph; prerequisite for OD-04/05/06/07/09/10/16 approvals.
15. **Bukti/validasi:** Owner-approved role matrix, allow/deny tests, self-approval tests, audit append-only, staff lifecycle/access review procedure.
16. **Pihak berwenang:** System owner/governing authority must identify actual roles; no names or posts assumed here.
17. **Ruang keputusan owner:** `Role matrix: ____` / `Dual-control actions: ____` / `Self-approval rule: ____`.
18. **Bukti persetujuan:** `________________________________`.
19. **Tanggal keputusan:** `________________`.
20. **Catatan revisi:** OD-18 has no dependency of its own in 2A.7R; this avoids self/circular dependency, not an approval.

## 6. Petunjuk Pengambilan Keputusan

1. Beri keputusan hanya oleh pihak berwenang yang ditetapkan organisasi; catat ruang lingkup (dev/test vs operational), pilihan, syarat dan tanggal.
2. Lampirkan bukti persetujuan yang dapat ditelusuri (mis. notulen/approval record resmi), bukan rekomendasi teknis.
3. Jika belum cukup bukti, tandai `NEEDS_OWNER_CLARIFICATION` pada pilihan tersebut dan biarkan OD `PENDING`.
4. Keputusan prinsip dapat dibahas paralel, tetapi jangan mengesahkan dependent contract untuk implementasi sebelum dependency dan technical evidence terkait tersedia.
5. Jangan memperlakukan approval dokumen ini sebagai acceptance test pass, blocker closure, atau izin implementasi.

## 7. Verdict

- **OWNER DECISION PACKAGE:** `COMPLETE` — seluruh OD-01–OD-18 memiliki decision brief dengan 20 elemen wajib.
- **DECISION APPROVAL STATUS:** `ALL PENDING`.
- **HISTORICAL SOURCE RECOVERY:** `INCOMPLETE` — SRC-GAP-01/02 tetap `OPEN`.
- **NIS IMPLEMENTATION READINESS:** `NOT READY FOR IMPLEMENTATION`.

Paket ini hanya bahan pengambilan keputusan. Tidak memberi otorisasi coding, migration, data change, atau aktivasi publication.
