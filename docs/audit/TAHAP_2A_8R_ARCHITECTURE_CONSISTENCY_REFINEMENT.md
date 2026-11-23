# TAHAP 2A.8R — ARCHITECTURE CONSISTENCY REFINEMENT

**Sistem:** SINERGI-MJ  
**Cakupan:** Konsistensi publication NIS, username, account provisioning, outbox, notification, failure handling, acceptance criteria dan traceability  
**Status:** Refinement dokumentasi untuk review; bukan persetujuan owner atau otorisasi implementasi  
**Verdict:** `REFINEMENT INCOMPLETE`  
**Approval status:** OD-01 sampai OD-18 tetap `PENDING`

> **Authoritative correction:** Apply [TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md](./TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md) for AC-ACC-02, Hybrid provisioning dimensions, notification transaction boundaries, and related failure/traceability wording. That correction supersedes conflicting statements in this baseline. Its `CORRECTION COMPLETE` verdict does not replace this document's historical `REFINEMENT INCOMPLETE` verdict or authorize implementation.

## 1. Executive Summary

Tahap 2A.8 memuat dua kontrak provisioning yang menghasilkan konsekuensi berbeda: sinkronisasi username/account dalam transaksi publication dan provisioning sesudah publication melalui outbox. Acceptance criteria AC-PUB-03, AC-REG-03, dan AC-ACC-01–04 belum secara konsisten membatasi hasil yang diuji pada opsi yang mendasarinya.

Refinement ini menyelesaikan kontradiksi dokumentasi dengan mempertahankan **Opsi A (synchronous), Opsi B (outbox asynchronous), dan Opsi Hybrid (username reservation)** sebagai alternatif. Tidak ada opsi yang dipilih. Kontrak NIS bersama tetap: publication atomik; NIS yang telah commit permanen; slot tidak digunakan ulang; retry provisioning atau notification tidak memanggil allocator. Perbedaan hasil akun setelah commit dinyatakan terpisah per opsi.

Kontrak yang diselaraskan:

- **Opsi A:** kegagalan operasi database account/username yang diwajibkan di dalam transaksi menggagalkan seluruh publication. Email tetap di luar transaksi dan kegagalannya tidak membatalkan NIS.
- **Opsi B:** publication menyimpan NIS dan provisioning intent secara atomik. Kegagalan worker atau collision yang baru diketahui setelah commit tidak membatalkan NIS; akun tetap tidak dapat login dan kasus yang tidak dapat diulang otomatis masuk manual review.
- **Hybrid:** reservation adalah claim username, bukan akun aktif. Mekanisme uniqueness lintas seluruh jalur pembuatan akun, masa berlaku, pelepasan, dan koordinasi dengan publication masih harus dirancang serta disetujui. Reservation yang tidak valid tidak boleh menghasilkan login atau mengubah NIS.
- Provisioning dan notification memiliki state serta idempotency terpisah. Email tidak menjadi bukti keberhasilan account provisioning.
- Kriteria acceptance yang terdampak kini menyebutkan cabang opsi secara eksplisit; seluruh kriteria tetap `PROPOSED / PENDING OWNER APPROVAL / NOT RUN`.

**Verdict tetap `REFINEMENT INCOMPLETE`** karena sumber wajib Tahap 2A.1 dan 2A.2 tidak ditemukan di workspace. Isi historisnya tidak direka. Kontradiksi provisioning yang berada dalam scope dokumen ini telah dipisahkan menjadi kontrak bersyarat, tetapi OD terkait, bukti teknis, dan blocker belum ditutup. Dokumen ini bukan `READY FOR IMPLEMENTATION`.

## 2. Scope dan Sumber yang Ditelaah

Refinement terbatas pada:

1. Memisahkan hasil NIS publication, account provisioning, dan notification.
2. Menilai tiga alternatif lifecycle username/account tanpa memilih untuk owner.
3. Menyamakan transaction boundary, kegagalan, status, retry dan idempotency.
4. Merevisi interpretasi acceptance criteria terkait dan traceability.
5. Memastikan supplemental ordering dan ordinal tidak berubah arti.

Dokumen yang ditelaah:

- `TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md`
- `TAHAP_2A_8_TRACEABILITY_MATRIX.md`
- `TAHAP_2A_8_CHANGELOG.md`
- `TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md`
- `TAHAP_2A_7R_CHANGELOG.md`
- `TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md`
- `TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md`
- Referensi teknis terkait yang tersedia: desain Tahap 2A.3, evidence closure Tahap 2A.5, dan verifikasi lokal Tahap 2A.6.

### 2.1 Batas sumber

Pemeriksaan workspace untuk dokumen `*2A*` menemukan dokumen Tahap 2A.3–2A.8 yang tercantum di atas, tetapi tidak menemukan Tahap 2A.1 atau Tahap 2A.2. Keduanya tetap `UNAVAILABLE`; isi dan keputusan historisnya tidak diasumsikan. Karena brief mewajibkan pemeriksaan seluruh rangkaian historis, kelengkapan historis belum dapat dinyatakan.

Analisis ini adalah rekonsiliasi dokumen, bukan verifikasi ulang runtime, database, source code, data siswa, worker, atau jalur login. Tidak ada klaim bahwa state/status, outbox, uniqueness claim, ataupun login gate sudah tersedia di aplikasi.

## 3. Temuan Kontradiksi

| ID | Dokumen/area | Kontradiksi atau ambiguitas | Resolusi dalam refinement |
|---|---|---|---|
| CR-01 | Tahap 2A.3 vs 2A.7R/2A.8 | `users.username` disebut dapat disinkronkan di transaksi publication; alternatif outbox menunda pembuatan akun. | Ketiganya dinyatakan sebagai alternatif bersyarat. Kriteria dan failure matrix memisahkan hasil A, B, dan Hybrid; OD-04 tetap pending. |
| CR-02 | AC-PUB-03 | Kriteria sebelumnya memasukkan username ke rollback publication tanpa membedakan opsi. Ini bertentangan dengan Opsi B, di mana worker account berada setelah commit. | Kontrak bersama rollback mencakup seluruh data NIS, batch, dan outbox intent. Account/username ikut rollback hanya pada Opsi A jika diwajibkan dalam transaksi. |
| CR-03 | AC-REG-03 | “Username collision ... failure produces no partial issuance” tidak konsisten dengan collision yang baru ditemukan worker setelah Opsi B commit. | Collision sebelum commit pada A dapat memblokir/rollback. Collision setelah commit pada B mempertahankan NIS dan mengubah provisioning ke manual review. Hybrid mengikuti hasil claim yang telah disetujui dan diverifikasi. |
| CR-04 | AC-ACC-01–04 | Kriteria outbox dan transaksi sinkron mencampur asumsi hasil yang berbeda. | Kriteria dipisah menjadi kriteria bersama, khusus A, khusus B, dan tambahan Hybrid. |
| CR-05 | Publication vs provisioning/email | “Setelah publication” dapat ditafsirkan sebagai satu hasil sukses tunggal, padahal NIS, akun, dan email dapat berakhir berbeda. | Workflow, state machine, dan matriks kegagalan memisahkan tiga lifecycle dan status. |
| CR-06 | Username uniqueness | Reservation table terpisah tidak otomatis menjamin username unik terhadap `users.username` atau writer lain. | Hybrid hanya aman bila ada satu mekanisme claim/uniqueness yang dipatuhi semua writer dan diverifikasi terhadap collation target. Ini belum terbukti. |
| CR-07 | Supplemental order | Rumusan Tahap 2A.7R berbeda terkait tie-break ordinal. | Tetap menggunakan rekomendasi `official_entry_at → cohort_entry_ordinal → approved comparator → siswa.id`; ordinal bersifat permanen, dialokasikan saat entry resmi di bawah lock, bukan dihitung ulang saat publication. OD tetap pending. |

Resolusi di atas menutup kontradiksi pada **spesifikasi bersyarat** saja. Ini tidak memilih kebijakan, tidak membuktikan implementasi, dan tidak menutup blocker.

## 4. Alternatif Account dan Username

Semua opsi di bawah adalah alternatif untuk owner review. Tidak ada opsi yang disetujui.

| Aspek | Opsi A — Synchronous provisioning | Opsi B — Asynchronous outbox | Opsi Hybrid — Username reservation |
|---|---|---|---|
| Batas transaksi | Satu transaksi DB publication mencakup registry/slot/NIS/batch/high-water serta User, username, dan link canonical yang diwajibkan oleh opsi. | Transaksi publication mencakup issuance dan durable provisioning intent. Worker membuat User/username/link dalam transaksi terpisah setelah commit. | Claim/reservation username dibuat sebelum publication dalam transaksi terpisah; publication wajib memverifikasi claim masih sah dan terikat pada kandidat yang tepat. User aktif tetap dibuat pada tahap yang dipilih owner. |
| Username collision sebelum publication commit | Collision yang terdeteksi adalah hardblock; tidak ada partial issuance. | Preflight dapat menemukan collision yang sudah ada, tetapi tidak meniadakan race atau collision yang baru diketahui worker. | Claim yang tidak dapat diperoleh memblokir langkah reservation; publication tidak boleh mengabaikan claim yang hilang/kedaluwarsa bila policy mensyaratkannya. |
| Username collision setelah publication commit | Tidak berlaku jika semua operasi account/username diwajibkan dan sukses dalam transaksi. Email collision/failure bukan kegagalan DB account. | NIS tetap issued; provisioning gagal dan masuk manual review. | Bergantung pada integritas claim dan universal enforcement. Jika collision ditemukan, NIS tetap issued; reservation bukan alasan untuk mengubah NIS atau auto-rename. |
| Kegagalan account DB | Rollback seluruh publication jika account creation/link diwajibkan atomik. | NIS tetap issued; intent menjadi `retryable` atau `failed_manual_review`. | Reservation dapat tetap ada sementara publication/provisioning gagal; expiry/release wajib ditentukan. |
| Email/notifikasi gagal | Tidak membatalkan NIS atau account; retry notification terpisah. | Tidak membatalkan NIS atau account; retry notification terpisah. | Tidak membatalkan NIS atau account; retry notification terpisah. |
| Login sebelum account valid | Tidak login sebelum transaction sukses dan kontrol login mengizinkan. | Deny sampai provisioning sukses dan link/status canonical valid. | Reservation saja tidak membuat User aktif dan tidak memberi hak login. |
| Retry | Retry publication hanya jika transaksi belum commit/hasil commit telah dipastikan; account yang commit tidak dibuat ulang. | Retry intent yang sama; tidak menjalankan publication/allocator. | Retry claim/provisioning dengan claim key yang sama; expiry/reacquire harus mencegah dua pemilik dan tidak mengulang issuance. |
| Kelebihan | Satu commit DB menghasilkan NIS dan account konsisten. | Memisahkan identitas permanen dari ketersediaan account, serta memungkinkan retry terisolasi. | Dapat mendeteksi/menahan username sebelum account aktif dan mengurangi collision pasca-publication bila claim benar-benar otoritatif. |
| Risiko/kebutuhan | Transaksi dan lock lebih besar; kegagalan satu account dapat menggagalkan seluruh batch; email tetap tidak atomik. | Ada periode NIS issued tanpa account login; memerlukan outbox, worker, monitoring, recovery, dan manual review. | Reservation dapat tertinggal, kedaluwarsa saat proses berlangsung, atau menghalangi pemilik sah; uniqueness lintas tabel/writer sulit dibuktikan. |
| Status | Alternatif; OD-04/10/11/18 `PENDING`. | Alternatif; rekomendasi teknis sebelumnya tidak mengubah status owner. | Alternatif yang harus dinilai; tidak tercatat sebagai keputusan sebelumnya yang disetujui. |

### 4.1 Rekomendasi teknis, bukan keputusan owner

Opsi B memisahkan dengan jelas commit identitas permanen dari ketersediaan login dan notification. Namun, rekomendasi tersebut **bukan** pemilihan owner. Jika Opsi B dipilih, kontrak wajib mencakup durable outbox intent di publication transaction, non-login gate, idempotency key permanen, retry tanpa allocator, dan manual review untuk collision yang tidak dapat diselesaikan otomatis.

Opsi A tetap dapat menjadi desain valid bila owner menyatakan account merupakan bagian wajib dari satu commit publication dan menerima dampak batch-wide rollback. Opsi Hybrid hanya layak setelah jaminan uniqueness, TTL/fencing, pemulihan, serta kerja sama seluruh account writer dibuktikan pada target.

## 5. Workflow yang Diselaraskan

### 5.1 Initial publication — tahap bersama

1. Validasi cohort, eligibility, entry history, placement, comparator, legacy conflict, permission, dan snapshot sesuai baseline yang masih pending approval.
2. Ambil cohort lock serta sequence lock dalam urutan global; revalidasi snapshot dan cutoff/watermark.
3. Buat atau ambil publication attempt berdasarkan idempotency key. Preview tidak mengonsumsi slot.
4. Jalankan preflight username sesuai opsi terpilih. Preflight bukan pengganti unique constraint atau claim yang otoritatif dan tidak boleh dipakai untuk mengklaim race telah hilang.
5. Alokasikan slot dan, dalam **satu transaksi publication**, tulis registry, sequence slot, `siswa.nis` NULL→value, high-water, publication batch/snapshot/cutoff, serta status batch `published`.
6. Pada Opsi B, simpan satu provisioning intent unik per tugas account yang diperlukan dalam transaksi yang sama. Pada Hybrid, validasi reservation/claim dan ikat ke identitas issued; reservation tidak menjadi account/login.
7. Commit menghasilkan outcome NIS yang definitif: sukses atau tidak sukses. Jika transaction gagal, tidak ada issuance parsial. Attempt failure dicatat terpisah setelah rollback.
8. Setelah commit, provisioning dan notification berjalan sebagai workflow terpisah jika opsi dan policy owner menghendakinya.
9. Jika commit outcome tidak diketahui akibat crash/koneksi, baca attempt/batch/registry dengan idempotency key sebelum memutuskan retry; jangan mengasumsikan transaksi gagal.

### 5.2 Tiga hasil independen

| Hasil | Sukses | Gagal |
|---|---|---|
| **NIS issuance** | NIS, slot, registry, batch dan high-water commit atomik; NIS menjadi permanen. | Transaction rollback; tidak ada NIS/slot/registry terbit dari percobaan itu. Failure attempt ditulis di luar transaksi. |
| **Account provisioning** | User, username dan relasi canonical yang disetujui commit; hanya setelah itu provisioning menjadi `succeeded`. | Opsi A: bila DB account diwajibkan dalam transaksi, publication rollback. Opsi B/Hybrid: NIS tetap issued; intent menjadi `retryable` atau `failed_manual_review`; account tidak dapat login. |
| **Notification delivery** | Notifikasi terkirim dan delivery tercatat terpisah. | NIS dan account tidak dibatalkan; delivery menjadi `retryable` atau `failed_manual_review` sesuai error/policy. Retry hanya notifikasi yang sama. |

Notification tidak boleh menyatakan account siap/login sebelum provisioning sukses. Notifikasi yang membawa NIS hanya dibuat/dikirim setelah NIS commit. Tidak ada email eksternal di dalam jaminan atomik transaksi database.

### 5.3 Account provisioning state machine

Status durable yang disarankan:

```text
pending -> processing -> succeeded
                     -> retryable -> processing
                     -> failed_manual_review
                     -> failed -> retryable | failed_manual_review
```

- `pending`: intent telah commit, belum diambil worker.
- `processing`: worker memegang lease/claim untuk intent.
- `succeeded`: User/username/link canonical yang disetujui commit dan diverifikasi.
- `failed`: hasil attempt gagal tercatat sebelum klasifikasi final; bukan bukti bahwa NIS gagal.
- `retryable`: error sementara atau lease kedaluwarsa; dapat diulang dengan idempotency key yang sama.
- `failed_manual_review`: collision/identity ambiguity atau kondisi permanen yang butuh keputusan manusia; tidak auto-retry/auto-repair.
- Retry dari `retryable` kembali ke `processing`. Pengulangan dengan idempotency key sama harus mengembalikan atau melanjutkan hasil yang sama, bukan membuat User/link kedua.
- Pada Opsi A, state account tidak boleh berpisah dari transaksi untuk bagian account yang diwajibkan atomik. `succeeded` tercapai hanya jika transaction publication/account commit sukses. Status mail tetap terpisah.

State transition, expiry/lease dan retry bounds adalah usulan desain, belum diuji atau disetujui.

### 5.4 Notification state machine

```text
pending -> processing -> succeeded
                     -> failed -> retryable | failed_manual_review
                     -> retryable -> processing
```

- Notification memiliki event/idempotency key dan attempt history sendiri.
- `pending` hanya dibuat dari fakta committed yang dibutuhkan oleh pesan tersebut.
- `processing` adalah satu upaya delivery yang dapat dipulihkan setelah lease/timeout.
- `failed` adalah hasil attempt; error kemudian diklasifikasi sebagai `retryable` atau `failed_manual_review`.
- `succeeded` berarti provider/delivery boundary yang dipilih berhasil; bukan jaminan penerima membaca pesan.
- Mail outage tidak mengubah NIS, registry, batch, username atau state account.
- Duplicate worker/event tidak mengirim pesan duplikat bila idempotency provider tersedia; jika tidak tersedia, duplicate-risk dan reconciliation harus didokumentasikan sebelum aktivasi.

## 6. Failure Handling dan Idempotency Matrix

Istilah: `tidak terbit` berarti tidak ada slot/registry/NIS baru committed dari percobaan; `issued` berarti identitas permanen telah commit. `batch: belum published` mencakup attempt gagal yang dicatat terpisah. Provisioning/notification memakai state machine §5.

| Kondisi | Status NIS | Publication batch / attempt | Registry / slot | Account provisioning | Notification | Rollback | Retry / manual |
|---|---|---|---|---|---|---|---|
| Validasi gagal sebelum publication transaction | Tidak terbit | Batch belum published; attempt `blocked` bila pencatatan tersedia | Tidak ada slot/registry baru | Tidak dibuat; intent tidak dibuat | Tidak dibuat | Tidak ada commit publication untuk rollback | Perbaiki prasyarat lalu request baru/same key sesuai attempt contract; manual bila policy/identity unresolved |
| Kegagalan di tengah transaksi publication | Tidak terbit | Tidak ada batch published; failure attempt dicatat terpisah | Registry/slot/high-water/NIS rollback seluruhnya | A: User/link/username yang satu transaksi rollback. B: outbox rollback bersama issuance. | Outbox notification dalam transaction juga rollback | Wajib rollback transaction penuh | Retry hanya setelah rollback dan penyebab diketahui; same batch/idempotency semantics |
| Username collision diketahui sebelum commit — A | Tidak terbit | Tidak published; attempt blocked/failed | Tidak ada issuance commit | Tidak dibuat/di-link; tidak rename/merge | Tidak dibuat | Rollback seluruh transaksi publication/account | Retry setelah collision diselesaikan oleh pihak berwenang |
| Publication commit, worker belum mulai / crash sebelum memproses event — B | Issued permanen | Published | Issued dan slot tetap | `pending` | `pending` bila intent notification ikut commit | Tidak ada rollback sesudah commit | Worker/reconciler memproses intent sama; tidak memanggil publisher |
| Kegagalan worker sementara — B/Hybrid | Issued permanen | Published | Issued dan slot tetap | `failed` attempt lalu `retryable` | Tetap terpisah; biasanya `pending` sampai account usable | Tidak ada rollback publication | Retry bounded dengan key yang sama; manual jika batas retry terlampaui sesuai policy |
| Username collision baru ditemukan setelah commit — B | Issued permanen | Published | Issued dan slot tetap | `failed_manual_review`; User tidak dibuat atau tetap inactive/unlinked | Jangan kirim credential/aktivasi; pesan netral hanya jika diizinkan | Tidak ada rollback; jangan ubah NIS | Intervensi manual; no auto-merge, auto-rename, auto-link |
| Email gagal setelah NIS/account commit | Issued permanen | Published | Issued dan slot tetap | Tetap `succeeded` jika provisioning memang sukses | `failed` attempt lalu `retryable` atau `failed_manual_review` | Tidak ada rollback | Retry notification key saja; tidak ada issuance/account ulang |
| Retry provisioning setelah error/transient crash | Issued permanen | Published | Sama persis; tidak ada slot baru | `retryable -> processing -> succeeded` atau manual review | Tidak mengirim ulang kecuali state/notification key memerlukannya | Tidak ada rollback publication | Idempotent pada intent yang sama; unique User/link/username menolak duplikasi |
| Duplicate outbox delivery / worker dipanggil dua kali | Issued permanen | Published | Sama persis | Satu hasil account maksimum; worker kedua membaca outcome/lease/idempotency | Satu delivery sesuai notification key/provider semantics | Tidak ada rollback | Deduplicate; jangan memulai publisher atau allocator |
| Publication sukses tetapi respons/API hilang / commit outcome uncertain | Belum diketahui sampai direkonsiliasi; jika commit, issued | Cek batch/attempt dengan request key | Cek registry/slot | Jika B, cek outbox state; jangan buat ulang sebelum cek | Cek notification event | Jangan blind rollback/republication | Query/reconcile idempotency key; setelah terbukti belum commit baru boleh retry contract |
| Manual resolution atas provisioning failure — B/Hybrid | Issued permanen | Published | Issued dan tetap | Setelah evidence/approval/link conflict diselesaikan: retry intent yang sama; kemudian `succeeded` | Kirim hanya event yang valid dan belum sukses | Tidak ada rollback publication | Manual decision terotorisasi; audit append-only; dilarang auto-merge/rename/link |
| Reservation tidak diperoleh/kedaluwarsa — Hybrid | Belum terbit sebelum publication; jika sudah commit, NIS tidak berubah | Sebelum publication: belum published. Setelah commit: published | Sebelum commit: tidak ada issuance; sesudah commit: tetap issued | Claim tidak valid: pending/manual review; no login | Tahan pesan credential/aktivasi | Sebelum commit rollback issuance; sesudah commit tidak rollback | Reacquire hanya dengan claim protocol dan authorization; expiry/release harus diaudit; manual jika ownership ambigu |

### 6.1 Invarian retry

Setiap retry wajib memenuhi semua kondisi berikut:

1. Tidak menghasilkan NIS baru atau memanggil generator/allocator setelah publication sudah commit.
2. Tidak mengubah NIS, registry, issued slot, sequence high-water, cohort watermark, atau publication snapshot yang sudah commit.
3. Tidak mengubah `issued` slot menjadi available dan tidak menggunakan ulang slot.
4. Menggunakan provisioning intent / notification event yang sama dan mencegah User/link ganda.
5. Tidak mengirim notification yang mengklaim hasil yang belum committed.
6. Pada uncertain commit, melakukan reconciliation berdasarkan idempotency key sebelum retry.
7. Error identity/username yang butuh keputusan masuk manual review; bukan retry buta atau auto-repair.

## 7. Username Candidate, Reservation, Account, dan Relasi

### 7.1 Istilah dan perilaku

| Konsep | Definisi | Batas |
|---|---|---|
| Username candidate | Nilai calon identifier login yang dihitung/diusulkan sesuai policy owner. | Bukan claim unik, bukan account, dan bukan bukti bahwa username tersedia. Apakah candidate sama dengan NIS masih OD-04/10. |
| Username reservation | Claim sementara/tercatat atas candidate oleh workflow tertentu sebelum User aktif. | Bukan User, credential, atau login authorization. Perlu owner-approved expiry, release, ownership, audit, dan penanganan publication failure. |
| Username aktif pada `users` | Username yang telah disimpan untuk account; tetap tunduk unique semantics tabel dan login policy. | Tidak dapat disamakan dengan status provisioning success jika link/status account belum valid. |
| Username digunakan akun lain | Candidate bertabrakan dengan account aktif, soft-deleted, reserved, atau identitas lain menurut aturan yang disetujui. | Jangan merge, rename, atau relink otomatis; klasifikasikan dan review. |
| Username gagal diprovisioning | Candidate belum menghasilkan account/link yang sah atau collision belum diselesaikan. | Pada Opsi B/Hybrid NIS dapat tetap issued; account tidak boleh login. |

### 7.2 Syarat teknis Hybrid

- Unique constraint pada tabel reservation saja **tidak** membuktikan keunikan terhadap `users.username` atau tabel/account writer lain. Satu mekanisme claim otoritatif harus digunakan semua jalur pembuatan akun, atau constraint/database design lain yang membuktikan atomic uniqueness lintas jalur.
- Normalisasi/case sensitivity/collation untuk username harus sama dengan aturan uniqueness target. Pemeriksaan `SELECT` sebelum `INSERT` saja tidak cukup untuk mengatasi race.
- Reservation perlu identitas owner, candidate, siswa/entry intent, created/expiry time UTC, state, idempotency key, actor, dan append-only event; jangan menyimpan credential/token.
- Expired reservation tidak boleh otomatis menghapus account yang telah terbuat atau membebaskan nama tanpa fencing/cek ownership. Expiry/release bersamaan dengan publication memerlukan revalidasi/lock atau token yang mencegah claim lama dipakai setelah diberikan ke pihak lain.
- Jika candidate diturunkan dari NIS/sequence yang belum diterbitkan, koordinasi dengan allocator dapat menciptakan slot hold atau risiko candidate berubah. Jangan mengonsumsi sequence diam-diam untuk reservation. Alternatif username yang tidak bergantung NIS juga memerlukan keputusan owner.
- Semua writer, termasuk jalur TU/import/PPDB dan proses eksternal yang belum terverifikasi, harus menghormati claim. Jika itu tidak dapat dibuktikan, Hybrid fail closed.

### 7.3 Relasi User–Siswa

Dokumen sebelumnya menyebut `siswa.user_id` sebagai jalur yang dipakai login serta `users.siswa_id` sebagai relasi tambahan; `siswa.user_id` adalah kandidat relasi canonical, bukan keputusan. Refinement ini tidak mengubah OD-11.

- Provisioning hanya boleh menulis relasi yang disetujui owner dan tervalidasi terhadap aturan one-to-one.
- Collision/duplicate/orphan atau kedua FK yang saling bertentangan tidak diselesaikan dengan auto-link atau auto-merge.
- Login harus memvalidasi NIS telah issued, account provisioning telah sukses, account aktif sesuai policy, dan relasi canonical cocok.
- Reservation tidak menciptakan relasi atau hak login.
- Pemulihan manual harus append-only/audited dan menggunakan bukti/aktor yang disetujui.

## 8. Acceptance Criteria yang Diselaraskan

Kriteria berikut adalah interpretasi refinement terhadap ID yang sudah ada dan kriteria tambahan yang diusulkan. Semua tetap `PROPOSED / PENDING OWNER APPROVAL / NOT RUN`. Tidak ada yang dinyatakan lulus.

| ID | Kondisi dan hasil yang harus diuji | Cabang | Lapisan / jenis uji | Status |
|---|---|---|---|---|
| AC-PUB-03 (diselaraskan) | Inject failure sebelum commit; registry, slots, `siswa.nis`, high-water, cutoff, publication batch dan transaction-coupled intent tidak meninggalkan partial commit; failure attempt dicatat terpisah. Account/User/username ikut rollback **hanya bila Opsi A dipilih dan diwajibkan atomik**. | Bersama; assertion account bercabang A/B | DB + service; integration/failure injection | Proposed; P0; pending; not run |
| AC-REG-03 (diselaraskan) | Collision tidak auto-merge/rename/relink. Collision pre-commit pada A menghalangi publication dan tidak ada partial issuance. Collision post-commit pada B mempertahankan NIS/registry/slot dan menempatkan provisioning ke manual review. Hybrid membuktikan claim atomic/valid sebelum publication dan menangani claim loss tanpa perubahan NIS sesudah commit. | A, B, Hybrid | DB + service + auth; integration/concurrency | Proposed; P0; pending; not run |
| AC-ACC-01 (dibatasi) | Untuk B, satu durable provisioning intent per tugas commit atomik bersama NIS; worker failure/collision tidak mengubah NIS dan account tetap non-login; same-key retry tidak mengalokasi NIS. Opsi A tidak menguji outbox sebagai syarat issuance; Hybrid menguji reservation terpisah melalui AC-ACC-05. | B; bagian A/Hybrid tidak diasumsikan | DB + service; integration/recovery | Proposed; P0; pending; not run |
| AC-ACC-02 (dipertahankan) | Status `pending/processing/succeeded/failed/retryable/failed_manual_review`, transitions, attempt audit, idempotency dan secret-free logging sesuai state machine; illegal transition ditolak. | Semua opsi, dengan lifecycle A yang terikat transaksi | Service + DB; unit/integration | Proposed; P0; pending; not run |
| AC-ACC-03 (dipertahankan) | Mail failure/retry/duplicate delivery diuji terpisah; NIS/account tidak rollback; notifikasi hanya memakai data yang committed. | Semua opsi | Notification service; feature/integration | Proposed; P1; pending; not run |
| AC-ACC-04 (dibatasi) | Jika A dipilih: DB failure pada account/user/username/link yang owner nyatakan wajib menyebabkan rollback penuh issuance; mail failure setelah commit tidak membatalkan NIS/account. | A saja | DB + service; integration/failure injection | Proposed; P0; pending; not run |
| AC-ACC-05 (baru) | Hybrid: claim unik terhadap seluruh account/reservation writers; race paralel hanya memberi satu pemilik; expiry/release/fencing dan publication revalidation mencegah claim ganda/stale; reservation tidak memberi login. | Hybrid | DB + service; concurrency/integration | Proposed; P0; pending; not run |
| AC-ACC-06 (baru) | Crash sesudah publication commit, sebelum worker, duplicate outbox, uncertain HTTP response dan retry memakai intent/request key yang sama; tidak ada NIS/slot/User/link/email duplikat. | B/Hybrid; A menguji retry request publication | Service + DB + worker; integration/recovery/concurrency | Proposed; P0; pending; not run |
| AC-ACC-07 (baru) | Login ditolak untuk provisioning pending/processing/failed/retryable/manual-review, missing/contradictory canonical link, atau NIS belum issued; login diizinkan hanya untuk keadaan yang disetujui policy. | Semua opsi | Auth/service; feature/integration | Proposed; P0; pending; not run |
| AC-ACC-08 (baru) | Collision/manual review tidak auto-merge, auto-rename, auto-link atau mengubah NIS; resolution berwenang diaudit, lalu retry intent yang sama dapat menyelesaikan provisioning tanpa issuance baru. | B/Hybrid dan kasus exception A | Service + authorization; feature/integration | Proposed; P0; pending; not run |
| AC-PPDB-01 (penyelarasan) | Konfirmasi/daftar ulang tidak menerbitkan NIS; pre-commit notification tidak menyertakan NIS yang belum committed/credential yang belum valid; post-commit notification idempotent dan mengikuti state account yang benar. | Semua opsi; provisioning mengikuti keputusan OD-04 | Service/controller/notification; feature | Proposed; P0; pending; not run |
| AC-AUTH-01/02 (penyelarasan) | Login/username memakai relasi canonical yang disetujui; collision/duplicate tidak auto-repair; username sync mengikuti cabang A/B/Hybrid yang dipilih; perubahan nama tidak mengubah NIS. | Semua opsi bercabang | DB + service + auth; feature/integration | Proposed; P0; pending; not run |

Kriteria AC-TIME-03 sebelumnya tetap mensyaratkan `cohort_entry_ordinal` sebagai tie-break permanen. Nilainya dialokasikan ketika entry resmi dicatat di bawah cohort lock; tidak dihitung ulang ketika initial/supplemental publication, nama berubah, atau batch dipublikasikan ulang. Urutan rekomendasi supplemental tetap:

`official_entry_at → cohort_entry_ordinal → approved comparator → siswa.id`

Kriteria, comparator, ordinal, dan cutoff tetap pending owner approval serta technical validation.

## 9. Owner Decision Register

Status berikut bukan perubahan dari Tahap 2A.8/2A.7R. **Semua OD-01–OD-18 tetap `PENDING`.**

| ID | Topik terdampak | Status | Dampak refinement |
|---|---|---|---|
| OD-01 | Database/version | PENDING | Target DB menentukan transactional, unique, lock, outbox dan claim behavior. |
| OD-02 | PHP runtime/extensions | PENDING | Tidak diubah. |
| OD-03 | Name comparator | PENDING | Tidak diubah; relevan ke order awal/supplemental. |
| OD-04 | Account lifecycle/provisioning | PENDING | Owner harus memilih/menetapkan opsi dan login eligibility/failure contract. |
| OD-05 | Post-publication correction | PENDING | Manual identity/account resolution tetap perlu otoritas correction. |
| OD-06 | Legacy collision/reservation | PENDING | Tidak mengubah kebijakan reservation legacy NIS; berbeda dari username claim. |
| OD-07 | Transfer evidence | PENDING | Tidak diubah. |
| OD-08 | Supplemental order | PENDING | Ordinal permanen tetap rekomendasi, bukan persetujuan. |
| OD-09 | Valid placement | PENDING | Tidak diubah. |
| OD-10 | Username conflict | PENDING | Tentukan collision policy, candidate, manual resolution, dan apakah username harus sesuai NIS. |
| OD-11 | Canonical User–Siswa relation | PENDING | Tidak ada relasi canonical yang dipilih atau di-backfill. |
| OD-12 | DB privilege/direct SQL | PENDING | Diperlukan untuk trust boundary outbox/claim/registry. |
| OD-13 | Batch atomicity/volume | PENDING | Opsi A dapat menambah account write ke batch transaction; volume belum dibuktikan. |
| OD-14 | Timestamp/time authority | PENDING | Semantik/ordinal tetap pending; tidak berubah. |
| OD-15 | Year-code mapping | PENDING | Tidak diubah. |
| OD-16 | Delete/restore/relink | PENDING | Retention account/claim/link saat delete memerlukan policy yang disetujui. |
| OD-17 | One initial batch | PENDING | Tidak diubah. |
| OD-18 | Roles/separation of duties | PENDING | Menetapkan siapa yang dapat menyelesaikan manual review/reservation; belum disetujui. |

## 10. Blocker Register

Tidak ada blocker yang dinyatakan closed karena perbaikan dokumen.

| ID | Status | Dampak provisioning | Bukti/keputusan untuk penutupan |
|---|---|---|---|
| BL-01 | OPEN | Engine/transaction/constraint semantics untuk publication, outbox, uniqueness claim belum ditetapkan pada target. | OD-01 dan target feature/locking/rollback tests. |
| BL-02 | OPEN | Runtime web/worker parity belum dibuktikan. | OD-02, runtime/SAPI/extensions evidence. |
| BL-03 | OPEN | Comparator remains fail-closed; ordering immutable. | Golden order yang disetujui dan parity tests. |
| BL-04 | OPEN | Legacy NIS inventory/provenance belum lengkap. | Authorized inventory/disposition; username claim tidak menggantikannya. |
| BL-05 | OPEN | User–Siswa and account/username data belum direkonsiliasi menyeluruh. | OD-11 approval, OD-10 policy, complete authorized reconciliation. |
| BL-06 | OPEN | Direct SQL/privilege and ledger protection not proven. | OD-12, least-privilege target grants, deny/restore tests. |
| BL-07 | OPEN | Timestamp/year-code boundary remains unresolved. | OD-14/15 and target tests. |
| BL-08 | OPEN | Account lifecycle/username conflict, placement, correction and related policies remain pending. | OD-04/07/09/10/11/18 plus criteria for selected branch. |
| BL-09 | OPEN | Batch volume, transaction duration, recovery/restore not tested. | OD-13, target-sized load and recovery evidence. |
| BL-10 | OPEN | Full writers, role enforcement and activation/observability not proven. | Complete writer inventory, OD-18, worker/auth/claim enforcement tests. |
| SRC-GAP-01/02 | OPEN | Tahap 2A.1/2A.2 historical decisions cannot be checked for hidden contradiction. | Provide/locate source documents and complete comparison. |

## 11. Implementation Boundary

Dokumen ini tidak mengotorisasi implementasi. Jangan mulai mengubah publication, account, username, login, outbox, schema, atau worker berdasarkan rekomendasi pada laporan ini.

Sebelum implementasi account/provisioning, minimum yang harus ditutup:

1. Owner menyetujui opsi lifecycle dan acceptance branch (OD-04), username collision/candidate policy (OD-10), canonical relation (OD-11), serta kewenangan manual review (OD-18).
2. Target DB/runtime dan keunikan username lintas seluruh writer dibuktikan; Hybrid memerlukan claim mechanism dan expiry/recovery test.
3. Login deny/allow gate serta credential/activation policy diverifikasi pada server-side path.
4. Failure injection, uncertain commit, duplicate worker, retry, manual resolution, notification failure dan no-reissue tests lulus pada target yang disetujui.
5. Existing PPDB/TU/import/login/delete workflows serta worker/external writers dipetakan dan disatukan dengan kontrak yang dipilih.
6. P0 acceptance criteria yang relevan lulus dan owner mengotorisasi aktivasi secara eksplisit.

**Tidak boleh diaktifkan:** setiap jalur issuance atau provisioning sampai keputusan dan bukti yang relevan ditutup, seluruh P0 lolos, dan aktivasi disetujui owner.

## 12. Final Verdict

# `REFINEMENT INCOMPLETE`

Alasan:

- Kontradiksi in-scope antara publication, username, account provisioning, outbox, notification, failure semantics dan acceptance criteria sudah dipilah menjadi kontrak bersyarat yang tidak saling bertentangan.
- Tidak ada opsi yang dipilih atas nama owner; OD-01–OD-18 tetap `PENDING`.
- Tahap 2A.1 dan 2A.2, sumber wajib, masih tidak tersedia. Rangkaian desain historis belum sepenuhnya tervalidasi.
- Uniqueness claim Hybrid, target DB/runtime, canonical relation, login enforcement, complete writer map dan recovery behavior belum dibuktikan.
- Blocker BL-01–BL-10 serta SRC-GAP-01/02 tetap terbuka; kriteria masih proposed dan not run.

Verdict ini bukan izin implementasi, migration, perubahan data, atau aktivasi publication. Refinement ini hanya memperjelas spesifikasi yang harus diputuskan dan diuji.

## 13. Next Recommended Stage

1. Sediakan dokumen Tahap 2A.1 dan 2A.2 untuk melengkapi historical consistency review.
2. Minta owner memutuskan OD-04/10/11/18 dan memilih kontrak account/username; pertahankan opsi lain sebagai rejected/deferred hanya setelah keputusan eksplisit terdokumentasi.
3. Setelah target DB/runtime ditetapkan, uji transaction, outbox/claim uniqueness, crash/retry, login gate, authorization, serta restore pada lingkungan disposable.
4. Perbarui architecture dan traceability utama dengan hasil owner/evidence yang benar-benar disahkan; jangan mengubah status kriteria dari proposed/not-run sebelum ada hasil uji.
5. Minta instruksi eksplisit terpisah sebelum memulai tahap implementasi.

## 14. Tahap 2A.8R1 Authoritative Consistency Correction

This section incorporates the limited correction in [TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md](./TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md) as the current interpretation of this document.

1. **AC-ACC-02 is option-specific.** Its shared criteria apply to every selected architecture. Synchronous provisioning (Opsi A) is verified as part of the publication transaction and does not require worker states. Asynchronous provisioning (Opsi B) uses the six-state worker lifecycle. Hybrid tests username reservation independently; after reservation, account provisioning follows either synchronous or asynchronous criteria as a separate owner decision.
2. **Hybrid is two-dimensional.** Reservation/claim and account provisioning are distinct mechanisms. Reservation requires uniqueness across all account writers, ownership, expiry/release, fencing, race safety, publication revalidation, and no login entitlement. It does not imply a worker.
3. **Notification is not account provisioning.** A publication-dependent notification intent is recommended to be recorded atomically with publication, if owner-approved. It becomes available only after commit; delivery, acknowledgement, retry, and reconciliation occur separately. A rollback removes a transactional intent. Delivery failure never rolls back NIS/account and retry never invokes the allocator.
4. **Failure handling is branch-specific.** Worker failure applies only when asynchronous account provisioning is selected. Reservation expiry before publication can block the prerequisite; after NIS commit it cannot revoke the NIS. Manual review never auto-merges, auto-renames, or auto-links.
5. **Status boundary is preserved.** OD-01–18 stay `PENDING`; all AC remain proposed/not run; evidence statuses and BL-01–10 remain unchanged. `CORRECTION COMPLETE` refers only to these documentation inconsistencies, not historical review or implementation readiness.
