# TAHAP 2A.7R — OWNER DECISION RESOLUTION REFINEMENT

**Sistem:** SINERGI-MJ  
**Jenis:** Penyempurnaan terbatas dokumen keputusan NIS  
**Cakupan revisi:** OD-04, OD-14, dependency OD-01–OD-18  
**Verdict:** **REFINEMENT COMPLETE WITH OPEN QUESTIONS**  
**Approval status:** seluruh OD tetap `PENDING`.

## 1. Executive Summary

Refinement ini memperjelas batas transaksi penerbitan NIS dan provisioning akun, membedakan semantik tiga timestamp, dan memperbaiki dependency graph OD-01–OD-18. Tidak ada keputusan owner yang dibuat atau dianggap disetujui.

Temuan penting:

- Account provisioning di dalam transaksi publication berarti kegagalannya dapat menggagalkan penerbitan NIS jika kedua operasi diwajibkan atomik. Alternatif outbox/job memisahkan hasil: NIS tetap terbit setelah transaksi publication commit, sedangkan akun tetap pending/tidak dapat login sampai provisioning berhasil. Email merupakan proses terpisah dan kegagalannya tidak dengan sendirinya membatalkan NIS.
- `recorded_at` adalah waktu pencatatan teknis; `effective_at` adalah tanggal administratif yang dinyatakan berlaku; `official_entry_at` adalah waktu resmi ketika entry history disahkan setelah cohort lock dan dicatat dalam transaksi. Rekomendasi menggunakan `official_entry_at` dan urutan cohort deterministik untuk supplemental; cutoff initial memakai watermark cohort yang ditangkap pada publication. `effective_at` tidak mengubah urutan atau NIS yang telah diterbitkan.
- Dependency lama mengandung siklus OD-13 ↔ OD-17, selain self-dependency OD-18. Graph final menghapus kedua masalah tersebut. OD-18 berdiri sendiri; keputusan otorisasi OD-18 menjadi prasyarat bagi OD yang membutuhkan approver/aktor.

Rekomendasi di bawah adalah bahan owner review, bukan keputusan final. Tidak ada migration, test, seeder, konfigurasi, database, hak akses, source code, atau dependency yang diubah. Dokumen Tahap 2A.7 tetap dipertahankan; dokumen ini adalah refinement terpisah.

## 2. Scope dan Batasan

Pekerjaan ini terbatas pada:

1. Memperluas OD-04 dengan alternatif transaksi account provisioning, failure semantics, akses akun, retry, dan status/audit.
2. Memperjelas OD-14 tentang `recorded_at`, `effective_at`, `official_entry_at`, cutoff, supplemental ordering dan koreksi.
3. Memeriksa dan mengganti dependency OD-01–OD-18 dengan graph tanpa self-dependency maupun circular dependency.
4. Memperbarui ringkasan status keputusan tanpa mengubah approval status.

Tidak dilakukan pemeriksaan database baru, perubahan source, konfigurasi, privilege, migration, test, seeder, maupun operasi tulis. Fakta runtime/schema yang disebutkan berasal dari laporan sebelumnya dan hanya berlaku pada lingkungan lokal yang telah diperiksa.

## 3. Referensi Dokumen

- [Tahap 2A.7 — Owner Decision Resolution](./TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md) — keputusan, rekomendasi dan status sebelum refinement.
- [Desain arsitektur NIS Tahap 2A.3](./TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md) — baseline cohort lock, entry history, transaction, initial/supplemental publication dan logical UTC clock.
- [Tahap 2A.4 — Final Readiness Gate](./TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md) — definisi OD-01–OD-18 dan acceptance criteria.
- [Tahap 2A.5 — Owner Decision & Evidence Closure](./TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md) — owner status dan mutation path.
- [Tahap 2A.6 — Local Environment & Schema Verification](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md) — bukti lokal MySQL/PHP/ICU/schema, relasi, grant dan batas snapshot.
- [Tahap 2A.7R Change Log](./TAHAP_2A_7R_CHANGELOG.md) — rincian perubahan dokumen ini.

## 4. Revisi OD-04 — Account Provisioning dan Failure Semantics

### 4.1 Masalah dan baseline

Baseline publication sebelumnya menjadikan issuance atomik: slot/registry, `siswa.nis`, sequence/high-water dan status batch commit bersama. Desain Tahap 2A.3 juga mengusulkan sinkronisasi `users.username` dalam transaksi issuance. Namun, pembuatan akun login dan pengiriman email tidak harus identik dengan penerbitan identitas NIS. Kegagalan provisioning/email perlu memiliki hasil dan retry yang jelas tanpa mengubah NIS.

Login siswa yang ada memakai NIS untuk menemukan Siswa lalu relasi `siswa.user_id`; desain akun harus mempertahankan satu relasi canonical yang akhirnya disetujui melalui OD-11. Akun belum valid tidak boleh mendapatkan sesi login hanya karena sebuah row akun sudah dibuat.

### 4.2 Alternatif yang dievaluasi

| Alternatif | Batas transaksi | Kegagalan provisioning | Kelebihan | Risiko/konsekuensi |
|---|---|---|---|---|
| A. Provision akun di dalam transaksi publication | Pembuatan User, link canonical, username NIS dan issuance berada dalam satu transaksi DB. | Jika insert/link/username gagal dan dianggap bagian wajib, seluruh transaksi rollback: NIS, slot, sequence dan batch tidak terbit. Email eksternal tetap tidak dapat dibuat atomik dengan DB; email gagal setelah commit tidak membatalkan NIS. | Keadaan akun dan NIS konsisten pada commit; tidak ada keadaan NIS issued tetapi row User belum dibuat. | Memperbesar transaksi dan lock; konflik akun yang tidak berkaitan dapat menggagalkan seluruh batch; efek samping email tidak dapat ikut rollback. Semantics tidak aman jika kegagalan mail diperlakukan seperti kegagalan DB. |
| B. Provision setelah publication melalui outbox/job idempotent | Transaksi publication menyimpan issuance dan durable provisioning intent/outbox bersama-sama; worker membuat/mengaktifkan akun dalam transaksi terpisah. | NIS tetap terbit jika publication commit. Akun berstatus pending/absent dan tidak dapat login sampai provisioning selesai; kegagalan dicatat dan dapat dicoba ulang dengan idempotency key yang sama. | Memisahkan identitas permanen dari ketersediaan akun/email; retry tidak menerbitkan NIS baru; kegagalan email tidak membatalkan NIS. | Membutuhkan state machine, worker/outbox monitoring dan penanganan permanen untuk username/relasi conflict. Ada periode siswa belum dapat login. |

### 4.3 Rekomendasi teknis — bukan keputusan owner

Rekomendasi adalah Alternatif B, dengan kontrak berikut:

1. **Transaksi publication** tetap atomik untuk NIS: issuance registry/slot, `siswa.nis`, sequence/high-water, cohort/batch state, dan satu provisioning intent/outbox untuk setiap akun yang memang harus dibuat. Intent dibuat dalam transaksi yang sama agar tidak ada NIS commit tanpa jejak provisioning yang durable.
2. **Pembuatan User/link dan aktivasi akun** dijalankan setelah commit oleh worker idempotent. Jika job gagal, NIS/registry/sequence tetap issued; worker mencatat kegagalan dan mencoba ulang pada intent yang sama. Publisher tidak dipanggil ulang untuk memperbaiki akun.
3. **Email/notifikasi** merupakan efek samping terpisah dan tidak membatalkan NIS atau akun yang sudah berhasil dibuat. Email retry menggunakan notification/outbox key sendiri. Kegagalan mail tidak boleh menghapus User atau mengubah NIS.
4. Jika owner memilih Alternatif A, hanya kegagalan operasi DB akun yang secara eksplisit menjadi bagian transaksi dapat membatalkan issuance. Kegagalan pengiriman email tetap bukan kegagalan transaksi DB dan tidak membatalkan issuance.
5. Username collision, canonical relationship mismatch, atau identitas akun ambigu adalah kegagalan permanen/manual-review, bukan retry buta, rename otomatis, atau auto-merge. NIS yang sudah diterbitkan tetap sama.

### 4.4 Pencegahan login sebelum akun valid

- User yang belum selesai provisioning harus tidak aktif dan/atau belum disetujui sesuai kontrol login yang benar-benar ditegakkan; tidak diberikan password/credential yang dapat digunakan.
- Provisioning tidak boleh menautkan akun berdasarkan kesamaan nama saja. Gunakan identifier Siswa/NIS yang sudah issued, canonical relation yang disetujui (OD-11), serta collision check.
- Jalur login harus secara eksplisit menolak status provisioning pending/failed, akun inactive/unapproved, atau link yang tidak canonical. Status account tidak boleh hanya mengandalkan UI.
- Jangan mengirim credential sementara sebelum identity link selesai. Alur set-password/aktivasi harus menggunakan mekanisme aman yang disetujui owner.
- Semua syarat di atas membutuhkan feature tests untuk login deny/allow; laporan ini tidak mengklaim bahwa gate provisioning tersebut sudah tersedia atau diimplementasikan.

### 4.5 Retry dan pencatatan status

Usulan status provisioning per siswa/batch: `pending`, `processing`, `failed_retryable`, `failed_manual_review`, `succeeded`. Simpan minimal `attempt_count`, `last_attempt_at`, `next_attempt_at` bila ada, `completed_at`, kategori/kode error yang aman, correlation/idempotency key, serta actor/system source. Jangan mencatat password, token aktivasi, atau data sensitif dalam log.

- Constraint unik pada provisioning intent (misalnya per issued registry/student + jenis provisioning) mencegah intent ganda.
- Worker menggunakan idempotency key tetap; retry mencari atau melanjutkan intent yang sama dan memastikan User/link/username tidak terduplikasi.
- `succeeded` dicatat hanya sesudah User, canonical link dan status yang diperlukan berhasil commit.
- `failed_retryable` dapat dicoba kembali dengan backoff terbatas; `failed_manual_review` menunggu rekonsiliasi/approval. Semua attempt memiliki timestamp teknis UTC dan hasil.
- Retry tidak menjalankan allocator, tidak menaikkan sequence, tidak mengubah batch publication dan tidak memanggil generator NIS.
- Email/notifikasi memiliki status/attempt tersendiri agar outage SMTP/provider tidak mengaburkan status akun maupun NIS.

### 4.6 Keputusan yang tetap menunggu owner

Owner perlu memilih apakah akun dibuat dalam transaksi publication atau melalui outbox/job; kapan akun menjadi login-eligible; apakah username wajib sama dengan NIS; role yang menangani manual review; serta kebijakan credential/aktivasi dan notifikasi. Rekomendasi Alternatif B tidak mengubah `Owner decision`; OD-04 tetap `PENDING`.

**Revisi OD-04 untuk register:** `Recommended option` = provisioning sesudah publication melalui transactional outbox dan idempotent worker, dengan akun pending/non-login sampai valid. `Alternative options` = provisioning DB dalam transaksi publication; akun dibuat sebelum publication dalam keadaan nonaktif. Dependensi final: OD-10, OD-11, OD-18.

## 5. Revisi OD-14 — Timestamp Semantics

### 5.1 Definisi terpisah

| Timestamp | Definisi dan tujuan | Mutabilitas yang disarankan |
|---|---|---|
| `recorded_at` | Waktu teknis ketika sistem mencatat row/event atau perubahan, termasuk attempt, approval record dan correction event. Menjawab “kapan sistem merekamnya”, bukan kapan kondisi berlaku secara administratif. | Append-only untuk event; UTC. Koreksi membuat event baru, bukan menulis ulang event lama. |
| `effective_at` | Tanggal/waktu administratif yang dinyatakan oleh pejabat sebagai saat suatu keputusan/kondisi berlaku. Contoh: tanggal efektif penerimaan yang dibuktikan. Menjawab “berlaku sejak kapan menurut administrasi”. | Dapat dikoreksi melalui event baru yang berotorisasi; nilai lama dan alasan tetap tersimpan. Tidak menjadi urutan NIS. |
| `official_entry_at` | Waktu resmi ketika entry history siswa disahkan dan pencatatannya berhasil sebagai fakta sistem. Ditetapkan setelah cohort lock, saat approval dan insert/update history berhasil dalam transaksi yang sama. Bukan tanggal permohonan, daftar ulang, import, atau `effective_at`. | Immutable sebagai event asli. Jika keputusan entry perlu dikoreksi, buat correction/superseding event dengan actor, alasan dan waktu pencatatan baru; jangan mengedit timestamp event lama. |

### 5.2 Penetapan dan determinisme

Baseline dipertahankan: `official_entry_at` dibuat setelah lock cohort dan dicatat di transaksi yang sama dengan persetujuan serta pencatatan entry history. Timestamp teknis disimpan UTC. Rollback transaksi tidak boleh meninggalkan entry resmi yang tampak berhasil. Sumber clock (rekomendasi: logical UTC cohort clock berbasis sumber waktu database) dan precision masih harus diverifikasi pada engine/runtime target.

Timestamp saja tidak cukup untuk urutan deterministik jika beberapa entry memperoleh nilai yang sama atau precision clock terbatas. Rekomendasi adalah mempertahankan `official_entry_at` bersama ordinal/sequence entry yang monotonic dan unik per cohort, dialokasikan di bawah cohort lock. Tie-break terakhir tetap key stabil yang disetujui (misalnya `siswa.id`) bila kebutuhan proses mengizinkan. Owner harus menyetujui kebijakan; ordinal/tie-break bukan approval otomatis.

### 5.3 Timestamp yang dipakai untuk issuance

**Supplemental ordering:** rekomendasi memakai `official_entry_at ASC`, lalu comparator nama yang disetujui, lalu tie-break stabil. Untuk kesamaan timestamp, gunakan cohort entry ordinal; `effective_at` tidak menjadi sorting key. Nama comparator dan urutan supplemental tetap terkait OD-03/OD-08.

**Initial cutoff:** baseline memakai fakta entry resmi terhadap saat initial publication. Rekomendasi adalah saat publisher memegang cohort lock, ia menangkap publication cutoff/watermark dalam transaksi issuance, misalnya `initial_entry_watermark` sebagai ordinal entry tertinggi yang tercatat sebelum publication. Entry dengan ordinal sampai watermark menjadi anggota initial; entry berikutnya menjadi pasca-cutoff dan kandidat supplemental. Simpan juga `initial_published_at` UTC sebagai waktu publication teknis/audit. Penggunaan watermark menghilangkan ambiguitas jika timestamp entry dan publication memiliki nilai sama.

Jika skema hanya memakai timestamp cutoff, owner harus menentukan perlakuan nilai yang sama persis (`<` atau `<=`) dan precision. Sampai aturan/watermark diterapkan dan diuji, boundary ambiguity harus hardblock; jangan menggunakan `effective_at` sebagai fallback.

### 5.4 Peran `effective_at` dan koreksi administratif

- `effective_at` adalah informasi administratif dan dapat mendukung laporan/eligibility review, tetapi tidak mengubah urutan NIS supplemental maupun cutoff initial.
- Backdating atau mengoreksi `effective_at` setelah publication tidak mengubah `official_entry_at`, cohort watermark, slot, sequence, NIS atau snapshot publication yang sudah commit.
- Koreksi disimpan append-only: nilai sebelumnya, nilai pengganti, alasan, referensi bukti, actor/approver, `recorded_at`, serta dampak eligibility.
- Jika terdapat bukti bahwa **peristiwa resmi entry** sendiri salah dicatat, itu bukan koreksi `effective_at` biasa. Gunakan correction workflow OD-05; jangan menulis ulang `official_entry_at` asli. Untuk siswa yang telah memiliki NIS, koreksi tidak mengubah urutan historis atau NIS. Konflik entry yang berdampak pada cohort masa depan ditinjau dan dapat memblokir publication terkait.
- Snapshot/fingerprint issuance harus menyimpan ordinal dan timestamp entry yang dipakai, comparator version bila ada, serta cutoff/watermark, agar perubahan data administratif kemudian tidak menghitung ulang hasil historis.

### 5.5 Keputusan yang tetap menunggu owner

Owner tetap perlu menetapkan authority clock, timezone display, precision, urutan equality, sumber tanggal administratif yang dapat diterima, dan apakah rekomendasi ordinal/watermark digunakan. Detail driver/clock/recovery memerlukan validasi teknis. OD-14 tetap `PENDING`.

**Revisi OD-14 untuk register:** `Recommended option` = `recorded_at` UTC append-only; `effective_at` administratif dengan correction events; `official_entry_at` UTC ditetapkan post-lock dalam transaksi; supplemental diurutkan dengan official entry + ordinal, bukan effective date; cutoff initial menggunakan locked cohort watermark dan timestamp publication untuk audit. Dependensi final: OD-01.

## 6. Revisi OD-18 — Dependency Graph Correction

OD-18 sebelumnya mencantumkan `OD-18` sebagai dependency dirinya sendiri. Self-dependency dihapus. OD-18 tidak bergantung pada OD-05, OD-06 atau OD-12; matriks final menempatkan OD-18 sebagai keputusan independen yang menjadi prasyarat governance bagi keputusan yang memerlukan approver/aktor. Ini menjaga pemisahan antara kebijakan kewenangan, rancangan hak DB, dan proses koreksi/reservation.

Tidak ada keputusan role yang disahkan oleh revisi ini. Owner tetap menentukan role sekolah dan separation of duties. OD-18 memiliki approval `PENDING`.

## 7. Final Dependency Matrix

**Arti `Depends On`:** keputusan yang perlu diselesaikan lebih dahulu sebelum keputusan dependent dapat difinalkan untuk kontrak implementasi. Diskusi/owner review dapat berjalan paralel. Kebutuhan validasi teknis yang belum cukup untuk menyatakan hubungan owner-policy sebagai prerequisite dicantumkan di rationale/status, bukan diasumsikan sebagai approval.

| Decision ID | Decision Name | Depends On | Dependency Rationale | Status |
|---|---|---|---|---|
| OD-01 | Database dan versi resmi | — | Pilihan engine/version menjadi dasar dukungan DDL dan perilaku DB untuk keputusan berikut, tetapi tidak memerlukan OD lain. | PENDING — owner scope; technical validation lokal tersedia saja |
| OD-02 | PHP runtime dan ekstensi | — | Penetapan runtime dapat dibahas tanpa keputusan database; implementasi comparator menunggu runtime ini. | PENDING — web SAPI belum terverifikasi |
| OD-03 | Comparator dan sorting | OD-02 | Comparator harus dievaluasi pada runtime/extension/data yang disetujui. | PENDING — TECHNICAL VALIDATION REQUIRED |
| OD-04 | Siklus akun/provisioning | OD-10, OD-11, OD-18 | Provisioning memerlukan aturan username, relasi canonical dan aktor/permission untuk review kegagalan. | PENDING |
| OD-05 | Otoritas koreksi setelah publikasi | OD-18 | Request, verification dan approval correction bergantung pada pembagian kewenangan. Registry/immutability teknis tetap validasi terpisah. | PENDING |
| OD-06 | Legacy collision dan reservation | OD-12, OD-15, OD-18 | Reservation perlu kontrol akses/ledger, year-code context, serta approver. Data inventory tetap menjadi bukti teknis/prasyarat kasus, bukan keputusan lain. | PENDING — TECHNICAL VALIDATION REQUIRED |
| OD-07 | Bukti mutasi dan tingkat pertama masuk | OD-14, OD-15, OD-18 | Bukti harus mengikat tanggal/entry context yang jelas dan diverifikasi oleh role yang ditetapkan. | PENDING |
| OD-08 | Urutan supplemental batch | OD-03, OD-14, OD-15 | Tie-break nama perlu comparator; urutan kronologis perlu timestamp semantics; sequence berada pada year-code cohort. | PENDING |
| OD-09 | Definisi placement valid | OD-07, OD-15, OD-18 | Valid entry-grade/evidence, cohort mapping dan authority exception menentukan eligibility placement. | PENDING |
| OD-10 | Konflik username | OD-11, OD-18 | Penanganan collision memerlukan canonical identity link dan pihak yang boleh menyelesaikan/menyetujui perubahan. | PENDING |
| OD-11 | Canonical User–Siswa relationship | — | Keputusan relasi dapat direview independen; actual backfill/enforcement memerlukan reconciliation dan database validation yang terpisah. | PENDING — TECHNICAL VALIDATION REQUIRED |
| OD-12 | Database privilege dan direct SQL | OD-01 | Hak, trigger dan defense-in-depth harus disesuaikan dengan engine/version target. | PENDING — TECHNICAL VALIDATION REQUIRED |
| OD-13 | Batch volume dan atomicity | OD-01, OD-12 | Transaction/locking semantics bergantung pada engine; privilege boundaries menentukan aktor writer. Benchmark kemudian mengisi batas volume. | PENDING — TECHNICAL VALIDATION REQUIRED |
| OD-14 | Timestamp dan otoritas waktu | OD-01 | Pilihan sumber DB clock/precision teknis perlu disesuaikan dengan engine. Owner dapat membahas makna administratif secara paralel. | PENDING — TECHNICAL VALIDATION REQUIRED |
| OD-15 | Year-code mapping | — | Aturan mapping adalah kebijakan/domain key; dapat ditentukan independen, kemudian diuji pada skema target. | PENDING |
| OD-16 | Delete, restore, force-delete dan relink | OD-05, OD-11, OD-12, OD-18 | Lifecycle correction, canonical link, perlindungan ledger dan authority harus selaras sebelum aturan final. | PENDING |
| OD-17 | Satu initial batch per cohort | OD-01, OD-12, OD-13, OD-15 | Constraint bergantung engine/privilege; atomic publication contract dan definisi cohort key menjadi prasyarat mekanisme guard. | PENDING — TECHNICAL VALIDATION REQUIRED |
| OD-18 | Role dan separation of duties | — | Matriks kewenangan NIS adalah keputusan governance independen; menjadi prasyarat bagi OD yang menetapkan approval workflow. | PENDING |

### 7.1 Circular dependency check

Graph final tidak memiliki self-dependency dan tidak memiliki circular dependency. Dependency lama OD-13 → OD-17 dan OD-17 → OD-13 membentuk siklus; final graph mempertahankan arah tunggal OD-13 → OD-17, sementara OD-13 hanya bergantung pada OD-01 dan OD-12. OD-18 kini tidak memiliki dependency.

Urutan topologis yang mungkin (bukan urutan wajib owner discussion):

1. Kelompok independen: OD-01, OD-02, OD-11, OD-15, OD-18.
2. Setelah prasyarat terkait: OD-03, OD-12, OD-14.
3. Berikutnya: OD-13, OD-05, OD-07, OD-10.
4. Berikutnya: OD-04, OD-06, OD-08, OD-09.
5. Akhir: OD-16 dan OD-17 (OD-17 menunggu OD-13).

**Catatan validasi:** ketergantungan teknis lintas engine/runtime bukan berarti owner tidak dapat memutuskan prinsip policy lebih awal; status `PENDING` dan kebutuhan evidence tetap berlaku.

## 8. Cross-Decision Consistency Review

| Pemeriksaan | Hasil |
|---|---|
| OD-04 vs atomic publication | Konsisten jika NIS/registry/sequence/batch tetap satu transaksi atomik. Rekomendasi outbox hanya memisahkan pembuatan User/aktivasi/email setelah publication commit; provisioning failure tidak mengubah issuance dan tidak boleh memanggil allocator ulang. Ini menyimpang dari usulan sinkronisasi `users.username` dalam transaksi Tahap 2A.3 bila username dibuat bersama User; pilihan tersebut secara eksplisit dikembalikan kepada owner. |
| OD-04 failure semantics | Kegagalan DB akun di opsi in-transaction menyebabkan rollback issuance jika akun wajib atomik. Dalam outbox, NIS tetap issued; akun pending/non-login; retry idempotent terhadap NIS yang sama. Kegagalan email tidak membatalkan NIS pada kedua opsi. |
| OD-14 vs initial batch | Konsisten dengan initial batch setelah lock. Publication menangkap cutoff/watermark saat cohort terkunci; candidate pre-cutoff masuk initial. Timestamp sama perlu ordinal/watermark atau aturan equality yang disahkan dan diuji. |
| OD-14 vs supplemental | `official_entry_at` adalah waktu resmi entry; ordinal cohort memecahkan equality. Supplemental tidak memakai `effective_at` untuk mengubah ordering dan tidak menulis ulang issuance yang telah terjadi. |
| OD-14 correction vs immutability | Koreksi administratif bersifat append-only. Timestamp/order snapshot yang sudah menentukan NIS tidak berubah; NIS yang telah terbit tetap. |
| OD-18 vs OD-05/06/12 | Konsisten: OD-18 independen; OD-05 dan OD-06 bergantung padanya untuk approver; OD-12 bergantung pada OD-01 untuk engine, bukan OD-18. Pemisahan tugas dapat disepakati tanpa menyamakan dengan DB grants. |
| Baseline 2A.1–2A.6 | Format, entry-grade/year semantics, cross-rombel sequence, immutable/no reuse, placement prerequisite, one initial batch, fail-closed comparator, lock protocol dan snapshot lokal sebagai non-production evidence tetap dipertahankan. |
| Keputusan baru dianggap approved? | Tidak. Rekomendasi transaksi outbox, ordinal/watermark, fail-closed boundary dan graph hanya usulan teknis. Semua approval tetap `PENDING`. |

### Isu alignment yang dibuka, bukan perubahan baseline

Tahap 2A.3 memasukkan sinkronisasi `users.username` bersama NIS pada publication transaction. Alternatif outbox OD-04 memisahkan pembuatan akun setelah publication. Owner perlu memutuskan apakah `username=NIS` direservasi/ditulis pada transaksi publication (yang memerlukan akun row atau registry username) atau dibuat saat provisioning worker. Tidak ada perubahan pada username atau kontrak NIS; sampai diputuskan, publisher/provisioner belum boleh diimplementasikan.

## 9. Updated Owner Decision Register

Seluruh approval status wajib `PENDING`; kolom keputusan owner tidak diisi dengan rekomendasi. `Current status` menunjukkan kesiapan diskusi/validasi, bukan persetujuan.

| Decision ID | Current status | Recommended option | Alternative options | Owner decision | Depends On | Approval status |
|---|---|---|---|---|---|---|
| OD-01 | READY FOR OWNER REVIEW | MySQL 8.0.30 sebagai baseline dev/test saja; target lain dipilih terpisah | Engine lain; tunda target operasional | Menunggu owner | — | PENDING |
| OD-02 | TECHNICAL VALIDATION REQUIRED | PHP minimum sesuai manifest, extensions eksplisit, CLI/web parity | Single SAPI/build; atau runtime matrix | Menunggu owner | — | PENDING |
| OD-03 | TECHNICAL VALIDATION REQUIRED | Comparator setelah golden order disetujui; fail closed sebelum bukti lulus | ICU; library data-pinned; aturan eksplisit; tunda publication | Menunggu owner | OD-02 | PENDING |
| OD-04 | READY FOR OWNER REVIEW | Outbox/job idempotent setelah publication; akun pending/tidak login sampai valid | Akun DB dibuat di transaksi issuance; akun nonaktif sebelum publication | Menunggu owner; pilihan final transaksi dan aktivasi akun belum ditetapkan | OD-10, OD-11, OD-18 | PENDING |
| OD-05 | READY FOR OWNER REVIEW | Correction append-only, requester/approver terpisah | Single approver; exception terkontrol di luar aplikasi | Menunggu owner | OD-18 | PENDING |
| OD-06 | TECHNICAL VALIDATION REQUIRED | Fail closed; reservation hanya sesudah evidence dan approval | Reconcile only; reservation berotorisasi | Menunggu owner | OD-12, OD-15, OD-18 | PENDING |
| OD-07 | READY FOR OWNER REVIEW | Bukti resmi plus exception ter-audit | Arsip; verifikasi manual; kombinasi | Menunggu owner | OD-14, OD-15, OD-18 | PENDING |
| OD-08 | READY FOR OWNER REVIEW | `official_entry_at`, cohort ordinal, comparator nama, stable tie-break | Sort nama per batch; aturan owner lain | Menunggu owner | OD-03, OD-14, OD-15 | PENDING |
| OD-09 | READY FOR OWNER REVIEW | Placement minimum eksplisit dan hardblock konflik | Exception terbatas/berotorisasi | Menunggu owner | OD-07, OD-15, OD-18 | PENDING |
| OD-10 | READY FOR OWNER REVIEW | No auto-merge/rename; hardblock dan rekonsiliasi | Username alternatif; rename existing dengan approval | Menunggu owner | OD-11, OD-18 | PENDING |
| OD-11 | TECHNICAL VALIDATION REQUIRED | `siswa.user_id` kandidat canonical; no backfill sebelum reconciliation | `users.siswa_id`; consistency-enforced dual direction; staged deprecation | Menunggu owner | — | PENDING |
| OD-12 | TECHNICAL VALIDATION REQUIRED | Pisahkan runtime, migration/deployment, DBA; least privilege | Shared broad account (tidak direkomendasikan) | Menunggu owner/DBA | OD-01 | PENDING |
| OD-13 | TECHNICAL VALIDATION REQUIRED | All-or-nothing publication transaction; limit setelah benchmark | Atomic staging/chunking dengan bukti setara | Menunggu owner | OD-01, OD-12 | PENDING |
| OD-14 | TECHNICAL VALIDATION REQUIRED | Distinct UTC `recorded_at`/`official_entry_at`, administrative `effective_at`, cohort watermark | Clock source/precision alternatif yang disetujui | Menunggu owner; rekomendasi belum disahkan | OD-01 | PENDING |
| OD-15 | READY FOR OWNER REVIEW | Explicit mapping/parser, preserve `2026/2027 → 2627` | Two-year suffix parser atau mapping owner lain | Menunggu owner | — | PENDING |
| OD-16 | READY FOR OWNER REVIEW | Permanent ledger; restore same NIS; relink terotorisasi | Soft delete only; force delete dengan tombstone | Menunggu owner | OD-05, OD-11, OD-12, OD-18 | PENDING |
| OD-17 | TECHNICAL VALIDATION REQUIRED | Cohort lock + DB uniqueness + idempotent retry | Engine-specific equivalent; service-only guard tidak cukup sendiri | Menunggu owner | OD-01, OD-12, OD-13, OD-15 | PENDING |
| OD-18 | READY FOR OWNER REVIEW | Permission terpisah prepare/publish/reserve/correct/audit | Satu role; separation terbatas pada aksi sensitif | Menunggu owner | — | PENDING |

## 10. Remaining Open Questions

1. **OD-04:** Apakah account User dibuat dalam transaksi publication atau oleh worker setelahnya? Apakah username NIS ditulis/reserved saat publication atau saat provisioning? Kapan account dianggap login-eligible? Apa prosedur untuk `failed_manual_review`?
2. Apakah transactional outbox menjadi bagian wajib dari kontrak publication, dan bagaimana idempotency key serta retensi attempt ditentukan?
3. **OD-14:** Siapa sumber clock teknis resmi; precision dan timezone storage/display apa yang digunakan? Apakah owner menyetujui cohort ordinal/watermark sebagai boundary tie-break?
4. Apa kebijakan tepat untuk entry dengan timestamp yang sama dengan cutoff jika ordinal/watermark tidak tersedia?
5. Bagaimana correction atas tanggal efektif dibedakan dari koreksi keputusan entry resmi, dan role mana yang berwenang?
6. **OD-18:** Siapa requester, verifier, approver dan auditor untuk publication/reservation/correction di organisasi sekolah?
7. Bukti teknis apa yang digunakan untuk membuktikan actual runtime, database clock/locking dan login gates pada environment target?
8. Adakah keputusan/approval eksplisit di luar dokumen yang menjadi sumber? Tidak ditemukan approval seperti itu dalam dokumen yang dirujuk; status tetap pending sampai owner mencatatnya.

## 11. Final Verdict

# REFINEMENT COMPLETE WITH OPEN QUESTIONS

Kriteria refinement terpenuhi: OD-04 menjelaskan dua pola transaksi, kegagalan, pencegahan login prematur, retry idempotent dan status/audit; OD-14 membedakan tiga timestamp dan peran cutoff/supplemental/koreksi; OD-18 tidak lagi self-dependent; graph final telah diperiksa tanpa circular dependency; seluruh OD-01–OD-18 tetap `PENDING`.

Open questions tetap material bagi implementasi: pilihan owner atas account transaction/outbox dan timing aktivasi, perlakuan username, timestamp authority/precision/watermark, serta role operasional. Tidak ada keputusan owner yang dibuat. Tidak ada perubahan aplikasi/database/configuration/dependency dan tidak ada migration, seeder atau test yang dijalankan.

## 12. Next Recommended Stage

1. Minta owner menjawab OD-04, OD-14 dan OD-18 serta open questions §10; catat pilihan dan alasan secara eksplisit tanpa mengganti rekomendasi sebagai keputusan.
2. Sinkronkan keputusan outbox/username dengan kontrak publication OD-03 desain 2A.3 sebelum menyusun implementasi.
3. Validasi timestamp precision, cohort ordinal/watermark, transaction/locking, retry dan login denial pada database disposable/runtime target setelah pemilik mengotorisasi tahap teknis.
4. Pertahankan comparator, publication dan provisioning fail closed hingga keputusan dan acceptance evidence terkait lengkap.
5. Jangan melanjutkan implementasi atau tahap berikutnya tanpa instruksi eksplisit.
