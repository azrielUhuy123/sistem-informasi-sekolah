# TAHAP 2A.11 — OWNER DECISION WORKSHEET

**Status seluruh OD sebelum sesi:** `PENDING`.  
**Aturan:** Alternatif tidak sama dengan rekomendasi. Jangan tandai `APPROVED` tanpa pilihan eksplisit, approver berwenang, bukti persetujuan yang dapat diverifikasi, dan tanggal. Jika authority belum diketahui, tandai `NEEDS_OWNER_IDENTIFICATION` pada kolom authority; status keputusan tetap `PENDING` kecuali rapat berwenang memutuskan status lain.

Gunakan status: `PENDING` / `NEEDS_OWNER_CLARIFICATION` / `APPROVED` / `REJECTED` / `DEFERRED`. Status `DECISION_CONFLICT` dicatat sebagai flag konflik, bukan approval.

## OD-01 — Database Engine dan Baseline

1. **ID/nama:** OD-01 — Database engine dan baseline.
2. **Masalah:** Migration, constraints, locking, upsert dan transaction semantics bergantung pada engine/version.
3. **Kondisi saat ini:** MySQL 8.0.30/InnoDB diamati lokal; target operasional belum dibuktikan. Bukti lokal bukan persetujuan target.
4. **Pilihan:** (A) MySQL 8.0.30 hanya dev/test, target operational kemudian; (B) tetapkan MySQL target setelah Ops mengonfirmasi dukungan; (C) pilih engine lain dan siapkan parity environment; (D) tunda pilihan dan blok DDL spesifik engine.
5. **Rekomendasi teknis:** Nyatakan baseline lokal hanya dev/test; tentukan target/version sebelum DDL dan validasi fitur di target disposable.
6. **Konsekuensi:** A menunda DDL target; B mengurangi variasi tetapi perlu bukti support; C perlu desain dan tes ulang; D mencegah asumsi tetapi menahan migration.
7. **Dependensi:** Tidak ada; menjadi prerequisite OD-12/13/14/17.
8. **Risiko bila belum diputuskan:** DDL/transaction/concurrency tidak dapat difinalkan dan perubahan engine kemudian meningkatkan rework.
9. **Pertanyaan policy owner:** Apa engine/version untuk dev/test dan operational? Siapa menyatakan target dan kebijakan support/upgrade?
10. **Persetujuan yang perlu diidentifikasi:** Policy owner sistem dan authority platform/Ops/DBA yang sah. `NEEDS_OWNER_IDENTIFICATION` sampai mandat diketahui.
11. **Bukti wajib:** Keputusan scope tertulis; supported-version policy; target DB evidence/feature matrix; lock, rollback, restore tests sebelum implementasi.
12. **Pencatatan sesi:** Keputusan: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-02 — Runtime dan Deployment Environment

1. **ID/nama:** OD-02 — Runtime PHP dan deployment.
2. **Masalah:** CLI, web, worker, CI dan ICU dapat berbeda; comparator/login/transaction behavior harus dapat direproduksi.
3. **Kondisi saat ini:** Manifest PHP `^8.2`; lokal CLI 8.2.29; web SAPI/handler belum terverifikasi.
4. **Pilihan:** (A) satu runtime/image terpin untuk web/CLI/worker/CI; (B) patch fleksibel dalam rentang didukung dengan parity matrix; (C) runtime berbeda per komponen tetapi wajib lulus matrix; (D) tunda target dan fail closed.
5. **Rekomendasi teknis:** Tetapkan minimum dan matrix PHP/Laravel/extensions/PDO/ICU; buktikan web, CLI, worker dan CI sesuai kebijakan.
6. **Konsekuensi:** A mengurangi drift namun menuntut deployment seragam; B fleksibel tetapi upgrade memerlukan parity; C menerima heterogenitas dengan biaya tes; D mencegah klaim palsu tetapi menahan aktivasi.
7. **Dependensi:** Tidak ada; OD-03 bergantung pada runtime policy/evidence OD-02.
8. **Risiko bila belum diputuskan:** Kesetaraan comparator/auth/worker belum dapat dibuktikan.
9. **Pertanyaan policy owner:** Minimum PHP/Laravel? SAPI/web server? Extension wajib? CLI/web/worker harus sama atau matrix?
10. **Persetujuan yang perlu diidentifikasi:** Owner standar aplikasi; deployment/Ops authority untuk runtime aktual. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Web SAPI/php.ini, CLI/worker versions, extension/PDO/ICU, artifact parity dan runtime matrix.
12. **Pencatatan sesi:** Keputusan: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-03 — Comparator dan Golden Ordering

1. **ID/nama:** OD-03 — Name comparator dan urutan alfabetis.
2. **Masalah:** Sorting penerbitan awal menjadi permanen; locale, Unicode, aksen, punctuation, nama identik dan tie-break bisa mengubah urutan.
3. **Kondisi saat ini:** Collator `id-ID` lokal menghasilkan actual locale `root`; belum ada golden order owner-approved.
4. **Pilihan:** (A) ICU Collator dengan actual locale/data terverifikasi dan versi dipin; (B) library collation terpaket setelah review lisensi/maintenance; (C) aturan comparator eksplisit yang disetujui owner; (D) fail closed sampai bukti tersedia.
5. **Rekomendasi teknis:** Jangan publish sebelum owner menyetujui golden fixtures/order dan parity runtime dibuktikan.
6. **Konsekuensi:** A memanfaatkan ICU tetapi locale lokal belum memadai; B reproducible namun menambah dependency; C eksplisit tetapi seluruh aturan harus diputuskan; D aman tetapi menunda initial publication.
7. **Dependensi:** OD-02; OD-08 menggunakan comparator sebagai komponen urutan.
8. **Risiko bila belum diputuskan:** NIS dapat diterbitkan menurut urutan yang diperselisihkan dan tidak bisa disortir ulang.
9. **Pertanyaan policy owner:** Bagaimana NFC/NFD, case, aksen, whitespace, tanda baca, gelar/prefix, nama sama, dan tie-break? Siapa menyetujui expected order?
10. **Persetujuan yang perlu diidentifikasi:** Policy owner menyetujui urutan expected; authority teknis/Ops memvalidasi runtime. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Golden fixtures/order yang disahkan; parity CLI/web/worker/OS; comparator fingerprint/version.
12. **Pencatatan sesi:** Keputusan: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti/order fixture: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-04 — Account Lifecycle dan Provisioning

1. **ID/nama:** OD-04 — Account lifecycle relatif terhadap penerbitan NIS.
2. **Masalah:** Waktu User dibuat/diaktifkan, login eligibility, transaction boundary dan kegagalan harus tidak mengubah NIS yang sudah committed.
3. **Kondisi saat ini:** Login legacy menggunakan NIS dan `siswa.user_id`; pilihan lifecycle belum disetujui. OD-10 adalah pemilik tunggal kebijakan reservation.
4. **Pilihan:** (A) Synchronous: account/link wajib dibuat dalam publication transaction; DB failure rollback seluruh publication/account. (B) Asynchronous: NIS dan durable provisioning intent commit atomik; worker setelah commit. (C) Hybrid: username reservation hanya jika diizinkan/diatur OD-10; provisioning setelahnya dipilih terpisah sync atau async. (D) pre-created inactive User hanya jika identifier dan login policy disetujui.
5. **Rekomendasi teknis:** Tidak memilih mode owner. A sukses melalui commit transaksi, tanpa worker lifecycle; B mengikuti AC-ACC-02-B; Hybrid mengadopsi OD-10 dan tidak otomatis async.
6. **Konsekuensi:** A satu boundary konsisten tetapi satu account failure dapat rollback batch; B NIS tetap issued selama account pending/non-login dan perlu worker; C menambah claim lifecycle/technical proof namun bisa dipasangkan dengan A atau B; D dapat orphan/placeholder.
7. **Dependensi:** Unconditional OD-10/11/18. Conditional OD-01/12 jika Hybrid benar-benar menggunakan reservation sesuai kebijakan OD-10.
8. **Risiko bila belum diputuskan:** PPDB/login/provisioning/credential tidak dapat diintegrasikan secara aman.
9. **Pertanyaan policy owner:** Sync atau async? Kapan login diizinkan? Hybrid digunakan? Jika ya, kebijakan OD-10 yang mana? Username harus sama dengan NIS? Bagaimana notifikasi/credential?
10. **Persetujuan yang perlu diidentifikasi:** Owner account/login dan proses sekolah; authority manual-review. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Login deny/allow; canonical relation reconciliation; rollback test untuk sync; worker same-key retry/recovery untuk async; bila Hybrid reservation dipakai, uniqueness lintas writers, lock, expiry/fencing/revalidation dan privilege proof. Async lifecycle states sesuai AC-ACC-02-B; hasil attempt terpisah. Jalur manual-review yang tidak eksplisit tetap `NEEDS_CLARIFICATION`.
12. **Pencatatan sesi:** Provisioning mode: `____` | Hybrid: `____` | Referensi OD-10 bila dipakai: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-05 — Authority dan Workflow Koreksi Data

1. **ID/nama:** OD-05 — Koreksi pasca-publication.
2. **Masalah:** Proses pengajuan/verifikasi/approval koreksi identity, cohort, entry facts dan link belum ditetapkan.
3. **Kondisi saat ini:** NIS dan slot terbit immutable; tidak boleh reissue/reuse.
4. **Pilihan:** (A) requester, verifier, approver berbeda; (B) satu pejabat boleh meminta sekaligus menyetujui; (C) koreksi di luar aplikasi dengan audit record; (D) hanya metadata tertentu yang dapat dikoreksi, NIS tidak berubah.
5. **Rekomendasi teknis:** Request → verification → separate approval; koreksi append-only tanpa mengganti NIS/slot/snapshot.
6. **Konsekuensi:** A kuat separation tetapi lebih lambat; B mudah tetapi conflict risk; C memerlukan audit eksternal; D sederhana tetapi butuh exception untuk kasus lain.
7. **Dependensi:** OD-18; terkait OD-06 dan OD-16.
8. **Risiko bila belum diputuskan:** Konflik data tertahan atau dikoreksi manual tanpa jejak yang dapat diaudit.
9. **Pertanyaan policy owner:** Siapa meminta/verifikasi/menyetujui? Self-approval boleh? Jenis evidence/exception apa? Bagaimana kesalahan NIS tanpa reuse?
10. **Persetujuan yang perlu diidentifikasi:** Owner correction policy dan exception authority. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Workflow/role matrix, correction fixtures, auth tests dan append-only audit evidence.
12. **Pencatatan sesi:** Keputusan: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-06 — Legacy NIS Collision dan Reservation

1. **ID/nama:** OD-06 — Legacy NIS collision/reserved slot.
2. **Masalah:** Exact legacy collision dapat memblokir allocator; auto-skip/remap dapat mengubah identitas permanen.
3. **Kondisi saat ini:** Legacy harus dipertahankan exact; inventory lokal sparse dan bukan basis keputusan.
4. **Pilihan:** (A) hardblock hingga reconciliation; (B) reserve exact slot permanen dengan evidence/reason/approver/audit; (C) no reservation, reconciliation only; (D) remap legacy NIS—bertentangan baseline dan tidak direkomendasikan.
5. **Rekomendasi teknis:** Fail closed; tidak auto-reserve/skip/normalize/remap/delete.
6. **Konsekuensi:** A melindungi identitas namun menunda issuance; B memakai slot dan memerlukan kontrol; C tidak menyelesaikan semua collision; D mengubah identitas yang telah digunakan.
7. **Dependensi:** OD-12, OD-15, OD-18; complete legacy inventory adalah bukti terpisah.
8. **Risiko bila belum diputuskan:** NIS reuse/collision atau cohort tertahan; tidak aman menebak provenance.
9. **Pertanyaan policy owner:** Bukti reservation apa sah? Siapa requester/approver? Default hardblock? Bagaimana exact legacy string dicatat?
10. **Persetujuan yang perlu diidentifikasi:** Legacy policy owner dan reservation approver; DBA/engineering hanya memvalidasi enforcement. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Authorized inventory termasuk deleted records, provenance, collision fixtures, reservation/unique/locking/rollback tests.
12. **Pencatatan sesi:** Keputusan: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti/inventory reference: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-07 — Transfer Entry Evidence

1. **ID/nama:** OD-07 — Bukti entry siswa pindahan/manual.
2. **Masalah:** Entry year/grade tidak boleh disimpulkan dari kelas saat ini atau sekolah asal; evidence dan authority belum ditentukan.
3. **Kondisi saat ini:** Baseline menyebut first accepted entry at this school; dokumen yang diterima/exception belum disahkan.
4. **Pilihan:** (A) dokumen transfer/penerimaan resmi yang ditentukan owner; (B) arsip administrasi yang dapat diverifikasi; (C) manual exception berbukti dengan dual review; (D) hardblock jika bukti tidak memadai.
5. **Rekomendasi teknis:** Tanpa evidence terverifikasi, hardblock; tidak ada fallback otomatis.
6. **Konsekuensi:** A konsisten tetapi mungkin tidak tersedia; B bervariasi mutunya; C menangani pengecualian tetapi perlu separation/audit; D fail-closed namun siswa tertahan.
7. **Dependensi:** OD-14, OD-15, OD-18; terkait OD-09.
8. **Risiko bila belum diputuskan:** Siswa pindahan salah masuk cohort/entry grade.
9. **Pertanyaan policy owner:** Bukti apa diterima? Siapa verifier/approver? Fallback arsip hilang? Cutoff initial/supplemental?
10. **Persetujuan yang perlu diidentifikasi:** Admission/administrative policy owner dan evidence approver; jabatan belum diketahui. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** SOP/dokumen yang diakui owner, anonymized fixtures pre/post initial, authority tests.
12. **Pencatatan sesi:** Keputusan: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Evidence policy: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-08 — Supplemental Issuance Ordering

1. **ID/nama:** OD-08 — Urutan supplemental.
2. **Masalah:** Timestamp tie/precision dan batas batch dapat mengubah sequence permanen.
3. **Kondisi saat ini:** Dua urutan tercatat: timestamp/name/ID dan timestamp/permanent ordinal/name/ID; belum disahkan.
4. **Pilihan:** (A) `official_entry_at → comparator → siswa.id`; (B) `official_entry_at → permanent cohort ordinal → comparator → siswa.id`; (C) deterministic rule lain yang disetujui; (D) tahan supplemental.
5. **Rekomendasi teknis:** B dipertimbangkan untuk tie determinism; bukan pilihan final. Ordinal tidak dihitung ulang.
6. **Konsekuensi:** A sederhana tetapi tie dapat bergantung comparator; B butuh ordinal assign-under-lock; C perlu fixture; D mencegah arbitrer tetapi menunda issue.
7. **Dependensi:** OD-03, OD-14, OD-15.
8. **Risiko bila belum diputuskan:** Hasil bergantung batch schedule atau tidak reproducible.
9. **Pertanyaan policy owner:** Chronology atau alfabetis sebagai prioritas? Urutan lintas batch atau dalam batch? Aturan timestamp sama?
10. **Persetujuan yang perlu diidentifikasi:** Owner policy penerbitan; engineering hanya membuktikan determinism. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Equal timestamp, batch-boundary, retry/runtime golden order dan immutable ordinal tests.
12. **Pencatatan sesi:** Urutan: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Fixture: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-09 — Valid Placement

1. **ID/nama:** OD-09 — Definisi placement valid.
2. **Masalah:** Missing, duplicate, inactive atau mismatched year/grade/rombel placement dapat menghasilkan eligibility yang salah.
3. **Kondisi saat ini:** Placement adalah prerequisite; valid state/multiplicity/exception belum diputuskan.
4. **Pilihan:** (A) satu placement aktif dengan class/year/grade valid; (B) named temporary/exception state dengan approval; (C) kombinasi status formal dan tanggal efektif yang ditentukan owner; (D) hardblock jika definisi tunggal tidak terpenuhi.
5. **Rekomendasi teknis:** Minimum satu placement sah; hardblock missing/duplicate/contradictory; exception bernama, authorized, audited.
6. **Konsekuensi:** A deterministik namun butuh status rules; B mendukung transisi tapi berisiko bypass; C mencerminkan administrasi namun kompleks; D menghindari ambiguitas namun menunda kasus tidak lengkap.
7. **Dependensi:** OD-07, OD-15, OD-18; target constraint juga OD-01.
8. **Risiko bila belum diputuskan:** Publisher tidak dapat hardblock secara konsisten; salah kelas/cohort.
9. **Pertanyaan policy owner:** Status/date apa valid? Multiplicity? Temporary placement? Siapa menyelesaikan konflik?
10. **Persetujuan yang perlu diidentifikasi:** Academic/administrative policy owner dan exception authority. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Approved status/date rules; missing/multiple/inactive/mismatch fixtures; placement mutation/concurrency tests.
12. **Pencatatan sesi:** Definisi: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-10 — Username Collision dan Reservation Policy

1. **ID/nama:** OD-10 — Satu-satunya sumber keputusan kebijakan username reservation/collision.
2. **Masalah:** Candidate username dapat bertabrakan dengan akun lama; auto-merge/rename/link berisiko akses lintas identitas.
3. **Kondisi saat ini:** Username tercatat unik pada schema lokal; complete account inventory/approval authority belum ada. Reservation bukan keputusan OD-04.
4. **Pilihan:** (A) reservation tidak diperbolehkan; hardblock/manual reconciliation. (B) reservation hanya pada kondisi/alasan owner-approved dengan ownership, uniqueness, expiry/release/fencing, revalidation, collision handling, approver eksplisit. (C) defer policy; reservation tidak digunakan sampai diputuskan. Untuk non-reservation collision, pilihan terdahulu tetap: hardblock/manual; username alternatif dengan format disetujui; controlled rename setelah bukti dan approval terpisah.
5. **Rekomendasi teknis:** Jangan auto-merge/rename/link. Sampai policy disetujui, jangan gunakan reservation. Jika dipilih, buktikan target DB uniqueness/locking/privilege dan coverage semua writers.
6. **Konsekuensi:** A menunda account namun sederhana; B dapat mengurangi race tetapi membutuhkan enforcement lintas writers; C menghindari asumsi namun menahan claim; fallback non-reservation dapat mengubah konvensi/login dan harus diputuskan.
7. **Dependensi:** Unconditional OD-11/18. Conditional OD-01/12 hanya jika policy mengizinkan dan reservation dipilih untuk digunakan.
8. **Risiko bila belum diputuskan:** Tidak aman menyelesaikan collision atau memberikan account; claim reservation tidak dapat dianggap sah.
9. **Pertanyaan policy owner:** Reservation allowed? Kondisi/alasan? Username/NIS rule? Ownership/uniqueness? Approver? Expiry/release/fencing/revalidation? Collision path? Alternative username/rename?
10. **Persetujuan yang perlu diidentifikasi:** Owner username/account policy dan authority yang menyetujui reservation/collision. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Account/username inventory, collation/unique behavior; bila reservation digunakan, DB uniqueness, locking/race, privilege enforcement, ownership/expiry/fencing/revalidation dan semua account writers.
12. **Pencatatan sesi:** Reservation allowed?: `____` | Kondisi: `____` | Collision policy: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-11 — Canonical User–Siswa Relationship

1. **ID/nama:** OD-11 — Relasi resmi User–Siswa.
2. **Masalah:** Dual nullable relation dapat drift; canonical source, one-to-one dan orphan handling belum disetujui.
3. **Kondisi saat ini:** Login path tercatat memakai `siswa.user`; `users.siswa_id` juga ada; complete reconciliation belum tersedia.
4. **Pilihan:** (A) `siswa.user_id` canonical; (B) `users.siswa_id`; (C) dua FK dengan satu authority dan DB-enforced consistency; (D) staged transition dengan compatibility field read-only lalu removal.
5. **Rekomendasi teknis:** `siswa.user_id` kandidat saja; pilih setelah seluruh readers/writers dan data direkonsiliasi.
6. **Konsekuensi:** A selaras dengan login tetapi reader lain perlu perubahan; B perlu auth changes; C dual data perlu enforcement; D mengurangi cutover tetapi dual-write risk.
7. **Dependensi:** Independen sebagai keputusan kebijakan; digunakan oleh OD-04/10/16.
8. **Risiko bila belum diputuskan:** Orphan/mismatched login, account provisioning dan relink tidak dapat difinalkan.
9. **Pertanyaan policy owner:** FK mana canonical? One-to-one? Bagaimana duplicate/orphan/mismatch dan transitional reads?
10. **Persetujuan yang perlu diidentifikasi:** Owner identitas/account siswa; DBA/engineering memvalidasi migration. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Pairwise FK aggregates, orphan/duplicate/mismatch inventory, all callsites, auth and target constraint tests.
12. **Pencatatan sesi:** Relation: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Reconciliation evidence: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-12 — Database Privileges dan Least Privilege

1. **ID/nama:** OD-12 — Database privilege/direct SQL.
2. **Masalah:** Runtime privileges berlebih dapat melewati service dan merusak ledger; deployment/DBA perlu dipisah.
3. **Kondisi saat ini:** Grant lokal tercatat global/broad; bukan konfigurasi target.
4. **Pilihan:** (A) akun app, migration/deployment, DBA terpisah dengan least privilege; (B) app restricted plus constraints/triggers dan controlled break-glass; (C) shared privileged account, tercatat tidak direkomendasikan.
5. **Rekomendasi teknis:** Pisahkan roles; runtime minimum; deployment DDL terbatas; DBA/break-glass diaudit.
6. **Konsekuensi:** A kuat pemisahan tetapi butuh role/secret management; B defense-in-depth tetapi target trigger wajib diuji; C sederhana namun blast radius tinggi dan immutability lemah.
7. **Dependensi:** OD-01; prerequisite teknis OD-06/13/16/17 dan conditional reservation OD-10/04.
8. **Risiko bila belum diputuskan:** Ledger/slot immutability tidak bisa diklaim; direct SQL dapat bypass.
9. **Pertanyaan policy owner:** Runtime grants apa? Siapa deployment/DBA? Bagaimana break-glass, rotation, audit?
10. **Persetujuan yang perlu diidentifikasi:** Owner risk/role policy dan DBA/Ops technical authority. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Role/grant matrix, effective grants, direct SQL deny tests, target trigger/FK behavior, backup/restore authorization.
12. **Pencatatan sesi:** Model privilege: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Grant evidence: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-13 — Publication Atomicity

1. **ID/nama:** OD-13 — Atomicity dan volume publication.
2. **Masalah:** Registry, slots, Siswa NIS, batch/snapshot/cutoff dan related intents harus committed sesuai one publication outcome; transaction volume belum dibenchmark.
3. **Kondisi saat ini:** All-or-nothing baseline; target-sized benchmark/restore drill belum ada.
4. **Pilihan:** (A) satu transaction per batch; (B) staged/chunked internal work hanya jika staging bukan publication, tidak ada partial visibility, dan final commit/visibility memenuhi atomic contract; (C) partial publication; (D) numeric cap tanpa benchmark.
5. **Rekomendasi teknis:** Pertahankan all-or-nothing; staging bukan issued NIS; jangan sebut setara jika atomic final visibility tidak terbukti; numeric cap hanya setelah benchmark.
6. **Konsekuensi:** A sederhana tetapi dapat menahan locks; B kompleks dan perlu bukti atomic visibility; C bertentangan dengan baseline; D tidak berdasar evidence.
7. **Dependensi:** OD-01/12; prerequisite OD-17.
8. **Risiko bila belum diputuskan:** Partial issue/unknown commit, timeout/recovery risk; tidak ada volume safe bound.
9. **Pertanyaan policy owner:** All-or-nothing wajib? Jika melebihi capacity, apakah ditunda atau design baru? Kriteria bukti untuk staged processing?
10. **Persetujuan yang perlu diidentifikasi:** Owner publication/integrity policy; Ops/DBA memberi SLO/volume, bukan mengganti policy owner. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Target benchmark, lock duration, failure injection, rollback, uncertain commit/retry, deadlock/recovery, restore.
12. **Pencatatan sesi:** Atomicity policy: `____` | At-cap action (setelah benchmark): `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-14 — Timestamp Authority dan Cutoff

1. **ID/nama:** OD-14 — Timestamp/time authority.
2. **Masalah:** Technical record time, administrative effective date dan official entry event tidak boleh dicampur; precision/equality memengaruhi cutoff/order.
3. **Kondisi saat ini:** `recorded_at`, `effective_at`, `official_entry_at` dibedakan pada baseline; clock/cutoff/watermark masih pending.
4. **Pilihan:** (A) DB UTC clock + cohort ordinal/watermark; (B) timestamp-only cutoff dengan aturan `<`/`<=`; (C) trusted app clock dengan drift monitoring; (D) hybrid clock setelah validasi target.
5. **Rekomendasi teknis:** Technical timestamps UTC; `effective_at` administratif; immutable `official_entry_at` setelah lock; ordinal/watermark hanya setelah validasi.
6. **Konsekuensi:** A deterministik tetapi perlu DB/ordinal validation; B sederhana namun equality bisa ambigu; C berisiko clock skew; D lebih kompleks.
7. **Dependensi:** OD-01; OD-07/08 bergantung pada semantik waktu.
8. **Risiko bila belum diputuskan:** Cohort boundary dan supplemental order ambigu/non-deterministic.
9. **Pertanyaan policy owner:** Clock authority? Precision/timezone? Cutoff/watermark? Equality? Koreksi event mempertahankan histori bagaimana?
10. **Persetujuan yang perlu diidentifikasi:** Owner definisi waktu administratif/cutoff; Ops/DBA teknis clock authority. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Precision, skew/failover, UTC, equality, ordinal monotonicity dan concurrency tests.
12. **Pencatatan sesi:** Clock/cutoff rule: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-15 — Year-Code Mapping

1. **ID/nama:** OD-15 — Mapping academic year ke year_code.
2. **Masalah:** Mapping salah dapat memecah/menggabungkan cohort dan sequence partition.
3. **Kondisi saat ini:** Contoh `2026/2027 → 2627`; parser/semester/ambiguity/century rollover belum ditetapkan.
4. **Pilihan:** (A) explicit approved mapping table; (B) constrained validated parser plus owner-approved rollover; (C) suffix parser setelah validasi representatif; (D) hardblock label unknown/ambiguous.
5. **Rekomendasi teknis:** Explicit validated mapping; pertahankan contoh; reject ambiguity, jangan infer dari current class/issue date.
6. **Konsekuensi:** A auditable tetapi perlu maintenance; B scalable dengan strict validation; C mudah namun berisiko ambiguity; D aman tetapi perlu manual disposition.
7. **Dependensi:** Tidak ada; menjadi prerequisite OD-06/07/08/09/17.
8. **Risiko bila belum diputuskan:** Cohort/sequence salah atau publication tertahan.
9. **Pertanyaan policy owner:** Label tahun valid? Semester records satu cohort? Rollover/rename/duplicate ditangani bagaimana?
10. **Persetujuan yang perlu diidentifikasi:** Owner academic-year/cohort policy; data steward/Ops memvalidasi mapping sumber. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Accepted label fixtures, semester pairing, duplicate/invalid/rollover mapping tests.
12. **Pencatatan sesi:** Mapping: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Evidence mapping: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-16 — Delete, Restore, dan Relink

1. **ID/nama:** OD-16 — Siswa lifecycle dan relink account.
2. **Masalah:** Delete/restore/relink dapat menghapus ledger, orphan account atau salah menautkan identity; slot tidak boleh digunakan ulang.
3. **Kondisi saat ini:** Soft/force-delete, tombstone, restore evidence, FK behavior dan authority belum final.
4. **Pilihan:** (A) soft-delete only, registry/slot retained; (B) force-delete dengan permanent tombstone snapshots; (C) restore same verified Siswa/NIS; (D) recreate record dengan audited evidence-based relink, no slot reuse.
5. **Rekomendasi teknis:** Registry/slot permanen; restore identity setelah verifikasi; relink append-only dan authorized; no reuse.
6. **Konsekuensi:** A memerlukan retention policy; B perlu non-cascading ledger; C butuh robust identity evidence; D mendukung recreate tetapi rawan salah relink.
7. **Dependensi:** OD-05/11/12/18.
8. **Risiko bila belum diputuskan:** Delete dapat menghapus evidence atau membolehkan reuse; login dapat pindah ke identitas salah.
9. **Pertanyaan policy owner:** Force delete boleh? Retention? Restore evidence? Relink approver? Akun lama dinonaktifkan bagaimana?
10. **Persetujuan yang perlu diidentifikasi:** Owner data retention/privacy dan identity/relink authority. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** FK/trigger behavior, force-delete/restore/recreate cases, authorization, duplicate identity recovery.
12. **Pencatatan sesi:** Delete/restore/relink rule: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-17 — Satu Initial Batch per Cohort

1. **ID/nama:** OD-17 — Exactly one initial publication.
2. **Masalah:** Definisi cohort/readiness/cutoff, duplicate publication, cancellation dan retry harus race-safe.
3. **Kondisi saat ini:** Baseline: satu initial batch per cohort; eligible pre-cutoff omission memblokir supplemental.
4. **Pilihan:** (A) one batch/cohort dengan lock + unique guard + same-batch retry; (B) retry same batch after preview revision, no second initial; (C) new batch for correction (risiko exactly-one); (D) explicit owner cancellation/reopen exception dengan immutable audit.
5. **Rekomendasi teknis:** Exactly one initial, lock/unique guard dan idempotent retry; retry tidak membuat logical batch baru.
6. **Konsekuensi:** A menjaga uniqueness namun readiness issue memblokir cohort; B recovery tanpa duplicate; C berisiko melanggar baseline; D menangani exception tetapi memerlukan authority/audit kuat.
7. **Dependensi:** OD-01/12/13/15.
8. **Risiko bila belum diputuskan:** Dua initial batch, omission atau cutoff berubah antar retry.
9. **Pertanyaan policy owner:** Cohort/readiness/cutoff? Retry/cancel? Boleh reopen? Siapa menyetujui exception?
10. **Persetujuan yang perlu diidentifikasi:** Owner policy publication/cohort dan cancellation authority; DBA/engineering menguji races. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Concurrent same/different keys, uncertain commit/retry, unique initial guard, failure/cancel, eligibility completeness.
12. **Pencatatan sesi:** Cohort/cutoff rule: `____` | Cancel/reopen: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Bukti: `____` | Tanggal: `____` | Status: `PENDING`.

## OD-18 — Separation of Duties dan Approval Governance

1. **ID/nama:** OD-18 — Role separation dan approval governance.
2. **Masalah:** Role NIS-specific belum dipetakan; self-approval/broad role berisiko pada tindakan irreversible.
3. **Kondisi saat ini:** Belum ada nama/jabatan/mandat formal terverifikasi; OD-18 merupakan root dependency dalam graph.
4. **Pilihan:** (A) pisahkan preparation, publication, reservation/correction approval dan audit; (B) satu role untuk routine, dual approval untuk exceptions irreversible; (C) satu broad role dengan risiko tertinggi.
5. **Rekomendasi teknis:** Permission per action; distinct approver untuk exception berdampak tinggi; cegah self-approval; audit actor/reason/evidence.
6. **Konsekuensi:** A strongest separation tetapi lebih operasional; B balance dengan threshold eksplisit; C sederhana namun conflict/blast radius tinggi.
7. **Dependensi:** Tidak ada; prerequisite governance OD-04/05/06/07/09/10/16.
8. **Risiko bila belum diputuskan:** Tidak ada approver valid untuk exceptions; persetujuan informal tidak dapat diaudit.
9. **Pertanyaan policy owner:** Siapa prepare/publish/reserve/correct/audit/approve? Dual-control actions? Self-approval? Delegasi/absence?
10. **Persetujuan yang perlu diidentifikasi:** Governing authority/system owner harus menunjuk role aktual; jangan mengasumsikan developer/operator. `NEEDS_OWNER_IDENTIFICATION`.
11. **Bukti wajib:** Mandate/role matrix, approval delegation, authorization allow/deny and self-approval tests, audit/access-review process.
12. **Pencatatan sesi:** Role matrix: `____` | Alasan: `____` | Approver nama/jabatan: `____` | Mandate evidence: `____` | Tanggal: `____` | Status: `PENDING`.

## Register Status dan Keputusan Sesi

| OD | Status sebelum sesi | Status sesudah sesi | Pilihan final/rejected/deferred | Alasan | Approver nama dan jabatan | Bukti persetujuan | Tanggal | Flag |
|---|---|---|---|---|---|---|---|---|
| OD-01 | PENDING |  |  |  |  |  |  |  |
| OD-02 | PENDING |  |  |  |  |  |  |  |
| OD-03 | PENDING |  |  |  |  |  |  |  |
| OD-04 | PENDING |  |  |  |  |  |  |  |
| OD-05 | PENDING |  |  |  |  |  |  |  |
| OD-06 | PENDING |  |  |  |  |  |  |  |
| OD-07 | PENDING |  |  |  |  |  |  |  |
| OD-08 | PENDING |  |  |  |  |  |  |  |
| OD-09 | PENDING |  |  |  |  |  |  |  |
| OD-10 | PENDING |  |  |  |  |  |  |  |
| OD-11 | PENDING |  |  |  |  |  |  |  |
| OD-12 | PENDING |  |  |  |  |  |  |  |
| OD-13 | PENDING |  |  |  |  |  |  |  |
| OD-14 | PENDING |  |  |  |  |  |  |  |
| OD-15 | PENDING |  |  |  |  |  |  |  |
| OD-16 | PENDING |  |  |  |  |  |  |  |
| OD-17 | PENDING |  |  |  |  |  |  |  |
| OD-18 | PENDING |  |  |  |  |  |  |  |

Approval hanya dapat dicatat sesudah sesi berdasarkan bukti eksplisit. Baris kosong tidak menyatakan persetujuan.
