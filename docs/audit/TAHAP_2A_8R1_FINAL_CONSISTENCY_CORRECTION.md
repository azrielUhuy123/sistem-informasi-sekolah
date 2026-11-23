# TAHAP 2A.8R1 — FINAL CONSISTENCY CORRECTION

**Sistem:** SINERGI-MJ  
**Jenis:** Koreksi konsistensi terbatas atas Tahap 2A.8R  
**Status:** Dokumen koreksi arsitektur untuk review; bukan persetujuan owner atau otorisasi implementasi  
**Verdict koreksi:** `CORRECTION COMPLETE`  
**Status arsitektur keseluruhan:** Tetap belum siap implementasi; OD-01–OD-18 `PENDING`, blocker tetap terbuka, sumber historis 2A.1/2A.2 tidak tersedia.

## 1. Executive Summary

Koreksi ini menyelesaikan tiga ketidakkonsistenan dokumentasi:

1. AC-ACC-02 dan provisioning state machine kini membedakan Opsi A (synchronous), Opsi B (asynchronous), dan Hybrid. Opsi A tidak diwajibkan memakai lifecycle worker; Hybrid hanya mendefinisikan reservation dan tidak memilih mekanisme provisioning.
2. Notification intent creation, event availability, delivery, acknowledgement, dan retry/reconciliation memiliki batas yang jelas. Jika pesan membutuhkan fakta publication seperti NIS, rekomendasi teknis adalah mencatat intent secara atomik bersama publication, lalu mengirim setelah commit.
3. Hybrid dipisah menjadi dua dimensi: username reservation dan mekanisme account provisioning (synchronous atau asynchronous). Tidak ada kombinasi yang dipilih atau dinyatakan telah disetujui.

AC-ACC-01 tetap khusus Opsi B (transactional account-provisioning intent); AC-ACC-04 tetap khusus Opsi A (account DB write wajib dalam publication transaction). AC-ACC-02 kini memiliki assertion bersama dan cabang khusus tiap opsi. AC-ACC-03 menangani notification intent dan delivery terpisah.

`CORRECTION COMPLETE` berarti tiga temuan di dalam scope telah diselaraskan dalam dokumen Tahap 2A.8R1 dan errata otoritatif pada dokumen Tahap 2A.8R. Ini **bukan** historical review complete, owner approval, technical validation, blocker closure, atau `READY FOR IMPLEMENTATION`. OD-01–OD-18 tetap `PENDING`; BL-01–BL-10 dan source gaps tetap terbuka.

## 2. Scope, Sources, dan Batasan

Koreksi hanya meliputi:

- cakupan AC-ACC-02 dan state machine provisioning;
- transaction boundary notification intent dan delivery;
- pemisahan reservation Hybrid dari lifecycle account provisioning;
- failure/retry serta traceability yang langsung terkait dengan ketiga temuan.

Sumber yang dibaca:

- `TAHAP_2A_8R_ARCHITECTURE_CONSISTENCY_REFINEMENT.md`
- `TAHAP_2A_8R_TRACEABILITY_MATRIX.md`
- `TAHAP_2A_8R_CHANGELOG.md`
- `TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md`
- `TAHAP_2A_8_TRACEABILITY_MATRIX.md`

Tahap 2A.1 dan 2A.2 sebelumnya dinyatakan tidak ditemukan; isi historisnya tidak dibuat atau diasumsikan. Tahap ini tidak mengulang audit source/runtime/database dan tidak mengklaim bahwa mekanisme usulan sudah diimplementasikan.

**Batas eksekusi:** hanya dokumentasi yang dibuat atau diperbarui. Tidak ada source code, database/data, migration, konfigurasi, dependency, seeder, test, worker, outbox, reservation, login gate, NIS, atau akun yang diubah.

## 3. Tiga Temuan dan Resolusi

| ID | Temuan | Koreksi |
|---|---|---|
| R1 | AC-ACC-02 menyatakan state `pending/processing/...` berlaku untuk semua opsi, padahal A tidak memakai worker. | Kriteria bersama hanya memeriksa hasil account yang benar, rollback boundary, no partial commit, login gate, dan audit yang relevan. Lifecycle asynchronous wajib hanya untuk B atau Hybrid bila owner memilih asynchronous. |
| R2 | Notification intent kadang disebut berada dalam publication transaction, kadang disebut dibuat sebagai workflow setelah commit. | Memisahkan intent creation (dapat atomik bersama publication), event availability setelah commit, delivery, acknowledgement, dan retry/reconciliation. Notification intent bukan provisioning intent. |
| R3 | Hybrid seolah satu opsi provisioning asynchronous. | Hybrid hanya menyatakan adanya reservation/claim. Account provisioning setelah reservation masih keputusan terpisah: synchronous atau asynchronous. Matriks menampilkan keempat kombinasi konseptual tanpa menyatakan semuanya layak/approved. |

## 4. Keputusan Desain Tetap Terbuka

| Dimensi | Alternatif yang dipertahankan | Owner decision |
|---|---|---|
| Account provisioning | Synchronous dalam transaksi publication; asynchronous melalui intent/outbox setelah publication. | OD-04 tetap `PENDING`. |
| Username reservation | Tidak ada reservation; atau reservation/claim terpisah sebagai prasyarat publication. | OD-04/OD-10 tetap `PENDING`. |
| Hybrid + provisioning | Reservation dapat dipasangkan dengan synchronous atau asynchronous provisioning. | Belum dipilih; reservation dan lifecycle provisioning adalah keputusan berbeda. |
| Notification intent | Direkomendasikan dibuat dalam transaction publication jika pesan memerlukan NIS/result publication; alternatif tetap dapat dievaluasi. | Rekomendasi teknis, bukan persetujuan owner. |
| Canonical User–Siswa relation, login, username, role/review | Tidak ditetapkan pada koreksi ini. | OD-10/11/18 tetap `PENDING`. |

## 5. AC-ACC-02 dan Provisioning State Machine

Seluruh acceptance criteria pada bagian ini berstatus **`PROPOSED / PENDING OWNER APPROVAL / NOT RUN`**.

### 5.1 Kriteria bersama — seluruh kombinasi

**AC-ACC-02-C (proposed):** Dengan keadaan awal dan provisioning mechanism yang disetujui untuk opsi terpilih, uji bahwa:

- account/User/username/link yang diwajibkan mengikuti transaction boundary opsi tersebut;
- tidak ada account atau NIS parsial yang committed bertentangan dengan hasil publication;
- account tidak dapat login sebelum kondisi account sukses dan relasi canonical yang disetujui valid;
- collision/identity ambiguity tidak memicu auto-merge, auto-rename, atau auto-link;
- audit merekam outcome yang relevan tanpa credential/token; status hanya diwajibkan jika lifecycle yang dipilih memang menggunakannya;
- kegagalan notification tidak membatalkan publication/account;
- retry/reconciliation tidak mengalokasikan NIS baru atau menggunakan ulang slot.

**Status:** PROPOSED / PENDING OWNER APPROVAL / NOT RUN.  
**Lapisan/jenis uji:** service + database + auth; integration/feature, serta failure injection yang sesuai opsi.

### 5.2 Opsi A — synchronous provisioning

Opsi A tidak mensyaratkan status worker provisioning `pending`, `processing`, `retryable`, atau `failed_manual_review`.

```text
publication transaction:
  issuance + required account/user/username/canonical link
      ├── commit -> NIS issued + required account successful
      └── failure -> rollback all publication/account writes

notification delivery: separate lifecycle after publication commit
```

**AC-ACC-02-A (proposed):** Pada Opsi A, account/User/username/link yang dinyatakan wajib berhasil dalam publication transaction; injeksi kegagalan pada operasi account yang diwajibkan me-rollback transaction sehingga tidak ada NIS, registry, sequence slot, batch sukses, atau account parsial committed; setelah commit account berada pada kondisi sukses sesuai policy; tidak ada worker state asynchronous yang diwajibkan; notification failure diuji terpisah dan tidak membatalkan hasil committed.

Kriteria ini tidak mengharuskan status `pending/processing/retryable/failed_manual_review` untuk provisioning A. Publication retry mengikuti request/batch idempotency dan hanya dilakukan setelah hasil commit direkonsiliasi.

**AC-ACC-04 (tetap, khusus A):** Jika opsi A dipilih, kegagalan operasi DB account yang diwajibkan menyebabkan rollback penuh issuance/account; email atau delivery notification tetap di luar batas atomic DB dan tidak membatalkan NIS yang sudah commit.

### 5.3 Opsi B — asynchronous provisioning

```text
publication transaction:
  issuance + durable account-provisioning intent
      ├── rollback -> no NIS and no provisioning intent
      └── commit -> NIS issued + intent pending

worker: pending -> processing -> succeeded
                         ├── failed -> retryable -> processing
                         └── failed_manual_review
```

**AC-ACC-02-B (proposed):** Uji lifecycle lengkap `pending`, `processing`, `succeeded`, `failed`, `retryable`, `failed_manual_review`; intent durable commit atomik bersama NIS; worker memproses intent yang sama; retry menggunakan idempotency key yang sama; kegagalan atau collision pasca-commit tidak mengubah NIS/registry/slot/batch; tidak ada NIS issuance ulang; login ditolak sampai provisioning sukses dan canonical relation valid.

Definisi ringkas status:

- `pending`: intent telah commit dan belum diproses.
- `processing`: worker sedang mengerjakan intent dengan claim/lease yang dapat direkonsiliasi.
- `succeeded`: User/username/link yang diwajibkan telah commit dan tervalidasi.
- `failed`: attempt gagal tercatat dan menunggu klasifikasi.
- `retryable`: kegagalan sementara; boleh diproses ulang dengan key sama.
- `failed_manual_review`: identity/username conflict atau kegagalan yang memerlukan keputusan manusia; tidak auto-retry/auto-repair.

**AC-ACC-01 (tetap, khusus B):** Durable account-provisioning intent commit atomik bersama NIS; worker failure meninggalkan NIS issued dan account non-login; retry intent tidak menjalankan allocator.

### 5.4 Hybrid — reservation dan provisioning adalah dua dimensi

Reservation lifecycle tidak menentukan provisioning lifecycle:

```text
username candidate -> claim/reservation -> publication revalidation
                                   ├── released / expired (before publication)
                                   └── consumed/retained per approved policy

after reservation:
  account provisioning = synchronous OR asynchronous (owner choice)
```

**AC-ACC-02-H (proposed):** Uji bahwa reservation/claim memiliki uniqueness, ownership, expiry, release, fencing, publication revalidation dan race-safe behavior; reservation sendiri tidak membuat User aktif, canonical link, credential, atau login entitlement. Setelah reservation, uji account provisioning memakai AC-ACC-02-A jika owner memilih synchronous atau AC-ACC-02-B jika memilih asynchronous. Hybrid tidak otomatis memerlukan worker.

**AC-ACC-05 (tetap, khusus reservation):** Claim hanya satu pemilik lintas semua account writers; expiry/release/fencing mencegah stale claim; publication memverifikasi ulang claim di bawah transaction/locking policy; reservation tidak memberikan hak login. Kelayakan uniqueness lintas `users.username`/reservation/writers harus dibuktikan pada target, tidak diasumsikan dari unique index pada satu tabel.

## 6. Hybrid Two-Dimensional Architecture

### 6.1 Dimensi 1 — Username reservation

Pihak yang mengusulkan reservation wajib mendefinisikan dan menguji:

1. **Candidate:** sumber pembentukan username; candidate bukan username aktif atau claim unik.
2. **Claim/uniqueness:** atomic uniqueness terhadap username aktif, seluruh reservation aktif, dan semua writers.
3. **Ownership:** claim mengidentifikasi siswa/intent dan actor/system yang memegangnya; tidak dapat dipakai kandidat lain.
4. **Expiry:** waktu berlaku dan perilaku claim yang melewati batas, menggunakan waktu dan policy yang disetujui.
5. **Release:** actor/reason/event; release tidak boleh membebaskan claim yang sudah dikonsumsi atau terkait account aktif.
6. **Fencing:** token/generation mencegah worker/request lama memakai claim setelah expiry atau reassignment.
7. **Publication revalidation:** claim masih valid, owner cocok, dan tidak ada collision saat publication commit.
8. **Race:** dua kandidat/writer memperebutkan candidate bersamaan hanya boleh menghasilkan satu owner.
9. **No-login:** claim/reservation tidak membuat account aktif atau memberi autentikasi.

Syarat-syarat tersebut masih usulan acceptance; belum dibuktikan. Pemeriksaan `SELECT` sebelum `INSERT` bukan jaminan uniqueness yang race-safe.

### 6.2 Dimensi 2 — Account provisioning

Setelah reservation (jika dipilih), owner dapat memilih:

- **Synchronous:** buat account/link dalam publication transaction; gunakan AC-ACC-02-A/04.
- **Asynchronous:** simpan account-provisioning intent bersama publication, lalu worker; gunakan AC-ACC-02-B/01.

Reservation intent dan account-provisioning intent adalah entitas/peristiwa berbeda. Reservation tidak boleh disamakan dengan status provisioning, dan kedua jenis intent harus memiliki tujuan serta idempotency key yang tidak saling menggantikan.

### 6.3 Matriks kombinasi konseptual

Matriks ini adalah klasifikasi alternatif, bukan keputusan bahwa setiap kombinasi layak, disetujui, atau sudah terbukti aman.

| Reservation | Provisioning | Klasifikasi | Transaction boundary | Collision/failure handling | Login eligibility | Retry | Risiko utama |
|---|---|---|---|---|---|---|---|
| Tidak ada | Synchronous | Opsi A | Publication transaction mencakup NIS dan account/link wajib. | Collision/account DB failure sebelum commit menggagalkan seluruh transaction; tidak ada partial issuance. | Hanya setelah transaction commit dan policy/login relation valid. | Retry publication setelah rollback atau reconciliation uncertain commit; tidak membuat account/NIS ganda. | Transaction besar; satu account conflict dapat menggagalkan batch. |
| Tidak ada | Asynchronous | Opsi B | Publication mencakup NIS dan account-provisioning intent; worker terpisah. | Collision setelah commit menjadi provisioning failure/manual review; NIS tetap issued. | Deny hingga provisioning `succeeded` dan canonical relation valid. | Retry same intent/key; tidak memanggil allocator. | Jeda NIS issued tanpa account login; worker/recovery diperlukan. |
| Ada | Synchronous | Hybrid + synchronous | Reservation claim dibuat/dipegang terpisah; publication revalidates claim; required account write dan issuance commit sesuai boundary synchronous yang disetujui. Claim lifetime/consume harus diselaraskan dengan transaction. | Claim collision/expiry sebelum publication dapat hardblock. Account DB failure pada publication me-rollback issuance; reservation release/retention ditangani policy claim. | Reservation sendiri tidak memberi login; account hanya usable setelah transaction sukses dan policy valid. | Retry claim tidak mengubah NIS; retry publication setelah rollback mengikuti same request/idempotency dan fencing. | Reservation dan publication bersaing; klaim lintas writers dan atomic consume kompleks. |
| Ada | Asynchronous | Hybrid + asynchronous | Reservation claim terpisah; publication atomik menyimpan NIS + account-provisioning intent, dan notification intent jika dipilih; worker terpisah. | Expiry sebelum commit menghalangi publication bila claim prerequisite. Collision/account failure setelah commit tidak membatalkan NIS; manual review. | Reservation bukan login; deny hingga async provisioning sukses/canonical link valid. | Retry provisioning same key; reservation reconciliation terpisah; tidak ada allocator/reissue. | Dua lifecycle asynchronous/expiry berbeda; stale claim, split ownership, serta monitoring lebih kompleks. |

## 7. Notification Transaction Boundary

### 7.1 Lifecycle notification

Notification memiliki event, tujuan, idempotency key, state, dan attempt history tersendiri. Notification intent bukan account-provisioning intent.

1. **Intent creation:** jika pesan bergantung pada data publication, misalnya NIS, rekomendasi teknis adalah menulis notification intent secara atomik di transaction publication. Intent hanya menggambarkan hasil yang akan committed bila transaksi sukses.
2. **Event availability after commit:** intent tidak dapat diklaim/diproses oleh delivery worker sebelum transaction publication commit. Setelah commit, event menjadi available. Jika publication rollback, intent ikut rollback dan tidak ada event yang dapat dikirim.
3. **Delivery:** worker/provider mengirim di luar publication transaction.
4. **Acknowledgement:** hasil delivery dicatat terpisah sebagai acknowledgement pada batas provider yang dipilih. `succeeded` menyatakan acknowledgement yang dimaksud telah diterima, bukan bahwa penerima membaca pesan.
5. **Retry/reconciliation:** retry mengacu ke notification event/idempotency key yang sama. Reconcile status provider sebelum mengulang ketika acknowledgement tidak pasti; jangan memanggil NIS allocator.

### 7.2 Boundary pada seluruh opsi

Notification intent dapat disimpan dalam publication transaction untuk Opsi A, Opsi B, atau Hybrid jika owner menyetujui pola tersebut. Ini tidak memilih mekanisme account:

- A tetap melakukan account provisioning synchronous; notification delivery berjalan terpisah.
- B tetap memproses account-provisioning intent secara asynchronous; notification intent adalah event berbeda.
- Hybrid tetap memisahkan reservation dari provisioning; notification intent tidak membuat claim/account dan tidak mengubah lifecycle provisioning.

Jika notification intent tidak dimasukkan ke publication transaction, alternatifnya harus memastikan intent dibuat hanya sesudah outcome publication terbukti committed dan memakai idempotent event key. Ada failure window antara commit dan intent creation; tanpa durable reconciliation, notifikasi dapat hilang. Ini adalah alternatif teknis dengan risiko, bukan rekomendasi utama.

### 7.3 Notification state dan hasil

```text
intent: absent (transaction rollback) | committed/available (publication commit)
delivery: pending -> processing -> succeeded
                                  -> failed -> retryable -> processing
                                           -> failed_manual_review
```

Status delivery terpisah dari intent existence dan account provisioning:

- `pending`: committed event siap diambil.
- `processing`: delivery attempt aktif.
- `succeeded`: acknowledgement pada provider boundary tersimpan.
- `failed`: attempt gagal, belum diklasifikasikan.
- `retryable`: kegagalan sementara/acknowledgement belum pasti; reconcile lalu retry event yang sama.
- `failed_manual_review`: tujuan/collision/content/policy memerlukan keputusan manusia.

Notifikasi tidak boleh menyatakan account siap login sebelum provisioning sukses. Jika intent memerlukan status account, isi pesan dipilih berdasarkan fakta committed ketika event dibuat atau melalui event terpisah setelah provisioning sukses; jangan mengarang status account saat publication.

## 8. Failure Handling Matrix

| Kondisi | NIS / batch / registry-slot | Account — A synchronous | Account — B asynchronous | Account — Hybrid | Notification intent / delivery | Rollback dan tindak lanjut |
|---|---|---|---|---|---|---|
| Validasi gagal sebelum publication | Tidak issued; batch tidak published; tidak ada registry/slot baru. | Belum dibuat. | Tidak ada provisioning intent. | Claim prerequisite yang gagal/expired memblokir jika policy mensyaratkan; tidak ada issue. | Tidak ada publication-dependent intent. | Tidak ada publication commit; koreksi prasyarat/otorisasi. |
| Account DB gagal di publication transaction | Tidak issued; seluruh NIS state rollback. | Account/User/username/link rollback bersama publication; no partial state. | Bukan provisioning worker failure; publication boleh tetap sukses hanya jika B tidak mensyaratkan account write di transaction. | Bila provisioning sync dipilih, sama dengan A; claim lifecycle ditangani terpisah. | Transactional notification intent ikut rollback. | Catat attempt failure di luar transaction; retry setelah penyebab dan commit status direkonsiliasi. |
| Publication transaction gagal sebelum commit | Tidak issued; batch gagal/tidak published; registry/slot/high-water rollback. | Account rollback. | Provisioning intent rollback. | Reservation yang terpisah mengikuti expiry/release policy; tidak ada issuance. | Notification intent rollback jika berada di transaction. | Tidak ada worker/event yang boleh memproses data yang rollback. |
| Publication commit, worker belum mulai/crash — B atau Hybrid+async | Issued permanen; batch published; registry/slot permanen. | Tidak berlaku. | Intent `pending`; worker/reconciler memproses intent yang sama. | Reservation status ditangani terpisah; provisioning mengikuti B. | Intent tersedia setelah commit; delivery dapat pending. | Tidak ada rollback sesudah commit; retry/reconcile key yang sama. |
| Worker/account failure sementara — hanya async | NIS/batch/registry-slot tetap committed. | Tidak berlaku. | `failed` lalu `retryable`; account tidak login. | Jika provisioning async dipilih, sama dengan B; reservation tidak ikut lifecycle worker. | Notification tidak berubah kecuali delivery sendiri berjalan. | Retry intent yang sama; no allocator. |
| Username collision sebelum commit | Tidak issued jika opsi memutuskan collision sebagai hardblock. | Hardblock/rollback, tanpa auto-merge/rename/link. | Preflight dapat menemukan; bila hanya diketahui setelah commit gunakan baris post-commit. | Claim tidak unik/owned menyebabkan prerequisite gagal dan publication diblokir. | Transactional intent rollback/absent. | Manual resolution berwenang; retry idempotent. |
| Username collision setelah NIS commit — B atau Hybrid+async | NIS tetap issued; batch/registry/slot tidak berubah. | Hanya mungkin bila invariant A telah gagal; tidak membatalkan NIS dan perlu incident/manual resolution. | `failed_manual_review`; account tidak login; tidak auto-repair. | Same B behavior untuk account; reservation collision/expiry ditangani terpisah. | Jangan kirim credential/claim account siap. Notification NIS boleh hanya sesuai konten yang disetujui. | No NIS rollback/reissue; manual audit dan provisioning retry setelah resolution. |
| Reservation expiry sebelum publication — Hybrid | Publication diblokir jika claim merupakan prerequisite; belum ada NIS. | Jika provisioning sync dipilih, belum berjalan. | Jika async dipilih, intent belum dibuat. | Claim release/expiry/fencing dicatat; reacquire hanya dengan aturan dan ownership valid. | Tidak ada publication-dependent intent. | Jangan memakai stale claim; manual jika owner ambigu. |
| Reservation expiry setelah NIS commit — Hybrid | NIS tetap issued; batch/registry/slot immutable. | Account sukses jika sync transaction committed; expiry tidak membatalkan NIS/account. | Account lifecycle tetap status async; expiry tidak membatalkan NIS. | Reconcile reservation hanya; jangan menghapus/menonaktifkan account otomatis. | Intent/delivery tidak dibatalkan; konten tidak boleh menyiratkan reservation login. | Manual/release sesuai policy; no rollback/reissue. |
| Notification delivery/provider failure | NIS, batch, registry/slot tidak berubah. | Account tidak berubah. | Account state tidak berubah. | Reservation/provisioning tidak berubah. | Event gagal/retryable/manual-review; delivery status disimpan terpisah. | Retry event yang sama; no allocator, no account recreation. |
| Worker/provider duplicate event | NIS dan account tetap satu hasil. | Reconcile publication request/account state. | Dedup idempotency key; satu intent outcome. | Dedup claim dan provisioning sesuai mechanism yang dipilih. | Dedup notification event; provider acknowledgement direkonsiliasi. | Tidak ada efek ganda; no reissue/relink/send duplicate bila provider mendukung idempotency. |
| Commit outcome atau delivery acknowledgement uncertain | Cek attempt/batch/registry berdasarkan key; bila committed NIS tetap issued. | Cek transaction/account outcome sebelum retry. | Cek outbox state sebelum worker retry. | Cek claim generation dan provisioning state secara terpisah. | Cek event/provider acknowledgement sebelum mengirim ulang. | Reconcile dahulu; jangan blind retry, rollback, atau allocator. |
| Manual review / resolution | Issued NIS tidak berubah. | Perbaiki hanya sesuai kewenangan; tidak mengubah NIS. | Retry same intent setelah keputusan; no auto-merge/rename/link. | Reservation ownership dan account lifecycle ditinjau terpisah. | Konten/delivery dilanjutkan hanya bila committed facts dan policy valid. | Append-only audit; manual action authorized; no NIS reissue. |

### 8.1 Idempotency rules

1. Setiap publication, provisioning intent, reservation claim, dan notification event memiliki key/domain identitas tersendiri.
2. Retry account asynchronous hanya mengulang provisioning intent yang sama; retry synchronous hanya mengulang permintaan publication setelah rollback/uncertain commit direkonsiliasi.
3. Retry reservation hanya memakai claim ownership/fencing yang valid; tidak menciptakan hak login.
4. Notification retry hanya mengulang event yang sama dan mengecek acknowledgement bila hasil provider uncertain.
5. Tidak satu pun retry setelah NIS commit boleh memanggil allocator, mengubah registry/slot, atau menghasilkan NIS baru.
6. No auto-merge, auto-rename, auto-link untuk collision atau identity ambiguity.
7. Publication-dependent notification hanya available setelah commit; rolled-back intent tidak dikirim.

## 9. Acceptance Criteria Consolidation

Semua kriteria di bawah `PROPOSED / PENDING OWNER APPROVAL / NOT RUN`.

| ID | Acceptance condition | Applicability / test |
|---|---|---|
| AC-ACC-01 | Account-provisioning intent commit bersama issuance; worker failure tidak membatalkan NIS; same-key retry tidak mengalokasi NIS. | Opsi B saja; integration/recovery. |
| AC-ACC-02-C | Kriteria bersama §5.1: correct transaction boundary, no partial account/NIS, login gate, no auto-repair, safe audit, notification independence, no reissue. | Seluruh kombinasi; integration/feature/failure injection sesuai opsi. |
| AC-ACC-02-A | Required account/User/username/link sukses dalam publication transaction; injected required DB failure me-rollback semua; tidak ada partial NIS/account; post-commit account sukses; tidak ada worker lifecycle yang wajib. | Opsi A; integration/failure injection. |
| AC-ACC-02-B | Durable intent commit atomik dengan NIS; seluruh enam state lifecycle; worker, same-key retry, no reissue, login deny sampai sukses/canonical link valid. | Opsi B; integration/recovery/concurrency/auth. |
| AC-ACC-02-H | Reservation tests di AC-ACC-05; provisioning setelahnya diuji sebagai synchronous memakai AC-ACC-02-A atau asynchronous memakai AC-ACC-02-B, sesuai keputusan owner. | Hybrid; tidak mengasumsikan worker. |
| AC-ACC-03 | Publication-dependent notification intent atomik dengan publication bila pola ini disetujui; rollback ikut rollback; event available hanya sesudah commit; delivery/ack/retry status terpisah; retry no allocator; konten tidak menyatakan login-ready sebelum account sukses. | Semua opsi; integration/provider/recovery. |
| AC-ACC-04 | Required account DB failure me-rollback publication hanya jika Opsi A dipilih; email/notification delivery failure tidak membatalkan committed NIS/account. | Opsi A; integration/failure injection. |
| AC-ACC-05 | Reservation uniqueness/ownership/expiry/release/fencing/publication revalidation/race; reservation tidak memberi login; uniqueness lintas seluruh writers. | Hybrid reservation dimension; DB/service concurrency. |
| AC-ACC-06 | Crash/duplicate worker/uncertain publication response menggunakan key yang sama dan tidak menggandakan NIS/slot/account/notification. Worker assertion hanya untuk asynchronous provisioning; notification retry diuji terpisah. | B dan Hybrid+async; A menguji publication idempotency; notification berlaku semua opsi jika intent dipilih. |
| AC-ACC-07 | Login deny untuk NIS belum issued, account belum sukses, atau canonical relation invalid; reservation saja tidak cukup. | Semua opsi; auth feature/integration. |
| AC-ACC-08 | Manual resolution terotorisasi/audited; tidak auto-merge/rename/link; issued NIS tidak berubah; provisioning retry hanya jika mechanism async dipilih. | Collision/manual-review branches; authorization/integration. |
| AC-PPDB-01 | PPDB confirmation tidak menerbitkan NIS; precommit notification tidak membawa NIS uncommitted; postcommit notification hanya menyatakan account siap bila provisioning sukses. | Semua opsi; feature/integration. |
| AC-AUTH-01/02 | Canonical User–Siswa relation tetap owner-approved; login dan username collision sesuai lifecycle terpilih; reservation bukan account/link. | Semua opsi bercabang; auth/integration. |

AC-PUB-03 tetap mewajibkan publication data atomik. Account/User write rollback termasuk hanya jika sinkron dan diwajibkan oleh Opsi A. Account-provisioning intent rollback bersama NIS pada B; Hybrid mengikuti asynchronous atau synchronous provisioning yang kelak dipilih. Notification intent rollback bersama publication bila rekomendasi transactional intent dipilih.

## 10. Owner Decision Register

Semua keputusan tetap `PENDING`; koreksi ini tidak memilih alternatif.

| OD | Topik terkait | Status | Dampak yang perlu diputuskan |
|---|---|---|---|
| OD-01 | Target database/version | PENDING | Transaction, uniqueness, locking, claim/fencing dan outbox behavior. |
| OD-02 | Runtime | PENDING | Runtime worker/provider jika opsi asynchronous terpilih. |
| OD-03 | Comparator | PENDING | Tidak diubah. |
| OD-04 | Account lifecycle | PENDING | Synchronous vs asynchronous; Hybrid reservation terpisah dari lifecycle. |
| OD-05 | Post-publication correction | PENDING | Otoritas koreksi account/identity setelah NIS commit. |
| OD-06 | Legacy NIS collision | PENDING | Tetap terpisah dari username reservation. |
| OD-07 | Entry evidence | PENDING | Tidak diubah. |
| OD-08 | Supplemental ordering | PENDING | Permanent ordinal masih rekomendasi. |
| OD-09 | Placement | PENDING | Tidak diubah. |
| OD-10 | Username policy/collision | PENDING | Candidate, collision handling, reservation requirement. |
| OD-11 | Canonical User–Siswa relation | PENDING | Relasi login/provisioning yang berlaku. |
| OD-12 | DB privileges | PENDING | Enforcement boundary untuk claim, publication, worker. |
| OD-13 | Batch atomicity/volume | PENDING | Dampak synchronous account writes pada transaction batch. |
| OD-14 | Timestamp | PENDING | Tidak diubah. |
| OD-15 | Year-code | PENDING | Tidak diubah. |
| OD-16 | Delete/restore/relink | PENDING | Retention/release claim dan account link. |
| OD-17 | One initial batch | PENDING | Tidak diubah. |
| OD-18 | Roles/separation of duties | PENDING | Manual review/reservation/reconciliation authority. |

## 11. Blocker Register

Tidak ada blocker yang ditutup oleh dokumentasi ini.

| ID | Status | Kaitan koreksi | Bukti/keputusan yang tetap dibutuhkan |
|---|---|---|---|
| BL-01 | OPEN | Transaction boundary, outbox/claim uniqueness | OD-01; target transaction/constraint/lock tests. |
| BL-02 | OPEN | Worker runtime hanya jika asynchronous dipilih | OD-02; effective web/worker runtime evidence. |
| BL-03 | OPEN | Tidak terdampak langsung | Golden comparator evidence. |
| BL-04 | OPEN | Username reservation bukan legacy NIS inventory | Authorized legacy reconciliation. |
| BL-05 | OPEN | Canonical identity/account collision | OD-10/11; complete account/relation reconciliation. |
| BL-06 | OPEN | Claim/ledger privilege controls | OD-12; least privilege/direct SQL validation. |
| BL-07 | OPEN | Tidak terdampak langsung | Timestamp/year-code validation. |
| BL-08 | OPEN | Account option, reservation, login/notification policy | OD-04/10/11/18; selected-branch acceptance. |
| BL-09 | OPEN | Transaction size/recovery/notification retry | OD-13; target volume/failure/restore evidence. |
| BL-10 | OPEN | Writers, workers, notification providers, authorization | Complete writer and role map; target integration evidence. |
| SRC-GAP-01/02 | OPEN | Historical decisions unavailable | Locate and compare Tahap 2A.1/2A.2. |

## 12. Implementation Boundary

Koreksi ini tidak memberi izin implementasi. Jangan membuat outbox/worker/reservation/login gate, mengubah account/username, schema, publication, atau data berdasarkan dokumen ini.

Sebelum implementasi komponen terkait, owner harus memilih provisioning lifecycle dan reservation requirement; menyetujui username/login/canonical-link/role policies; dan bukti teknis target harus membuktikan uniqueness lintas writers, transaction rollback, expiry/fencing, notification commit/delivery/retry, dan recovery. Acceptance criteria cabang yang dipilih harus diuji sebelum aktivasi. Semua blocker dan source gap tetap terbuka.

## 13. Final Verdict

# `CORRECTION COMPLETE`

Ketiga temuan dalam scope telah diselaraskan:

- AC-ACC-02 memiliki assertion bersama serta cabang A/B/Hybrid yang tepat; A tidak diwajibkan worker state dan Hybrid tidak diasumsikan asynchronous.
- Notification intent creation, post-commit availability, delivery, acknowledgement, dan retry/reconciliation mempunyai batas yang eksplisit dan berbeda dari account-provisioning intent.
- Hybrid dipisahkan menjadi reservation dimension dan provisioning dimension, dengan matriks kombinasi serta failure handling yang sesuai.

Verdict ini hanya menilai koreksi konsistensi dokumentasi. **Historical review tetap incomplete** karena Tahap 2A.1/2A.2 tidak tersedia. Owner approval dan technical validation belum complete; blocker tidak ditutup; sistem **bukan READY FOR IMPLEMENTATION**.

## 14. Next Recommended Stage

1. Owner meninjau OD-04/10/11/18 dan memutuskan provisioning lifecycle, kebutuhan reservation, login/collision, serta notification policy.
2. Setelah target DB/runtime ditetapkan, kumpulkan bukti uniqueness/fencing, transaction/worker/provider recovery dan login enforcement.
3. Sediakan Tahap 2A.1/2A.2 untuk melengkapi historical review.
4. Terapkan hanya kriteria untuk kombinasi yang secara eksplisit dipilih; jangan menandai proposed criteria passed tanpa hasil uji.
5. Minta otorisasi implementasi terpisah.
