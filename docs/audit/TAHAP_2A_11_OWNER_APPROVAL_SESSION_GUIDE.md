# TAHAP 2A.11 — OWNER APPROVAL SESSION GUIDE

**Tujuan:** Menyiapkan rapat untuk membahas dan mencatat keputusan kebijakan NIS OD-01–OD-18.  
**Status sebelum rapat:** seluruh OD `PENDING`; tidak ada keputusan atau approver yang terverifikasi.  
**Batas:** Persiapan rapat, bukan persetujuan, validasi teknis, atau otorisasi implementasi.

## 1. Tujuan Rapat

1. Menetapkan siapa yang memiliki kewenangan kebijakan dan siapa yang berhak memberi persetujuan formal.
2. Membahas alternatif setiap OD tanpa mengubah rekomendasi teknis menjadi keputusan.
3. Mencatat pilihan, alasan, syarat, bukti, approver, tanggal, dan status secara dapat diaudit.
4. Menandai pertanyaan yang belum dapat dijawab sebagai `NEEDS_OWNER_CLARIFICATION` dan konflik ketergantungan sebagai `DECISION_CONFLICT`.
5. Mengidentifikasi bukti teknis/data yang tetap diperlukan setelah keputusan kebijakan.

## 2. Batas Pembahasan

- **Termasuk:** definisi policy, scope, pilihan, alasan, authority, approval evidence, dan prasyarat bukti untuk OD-01–OD-18.
- **Tidak termasuk:** implementasi kode, migration, seeder, worker, outbox, reservation, account lifecycle, perubahan data, atau aktivasi generator.
- **Tidak dapat dianggap sebagai approval:** rekomendasi engineer, diskusi rapat tanpa approver berwenang, notulen tanpa keputusan eksplisit, atau status dokumen yang dibuat oleh auditor.
- Sumber asli 2A.1 dan 2A.2 belum ditemukan. Ringkasan pada tahap berikutnya tidak boleh dianggap sebagai naskah/approval historis.

## 3. Materi dan Persiapan Peserta

Gunakan [Owner Decision Worksheet](./TAHAP_2A_11_OWNER_DECISION_WORKSHEET.md) dan [Authority and Approver Matrix](./TAHAP_2A_11_AUTHORITY_AND_APPROVER_MATRIX.md) sebagai lembar kerja.

Sebelum rapat, pihak yang mengatur rapat perlu memperoleh—tanpa mengasumsikan jabatan:

- identitas dan dasar mandat policy owner;
- dasar kewenangan approver kebijakan akademik, administrasi siswa, username/login, exception, dan teknis;
- pihak yang berwenang memverifikasi bukti serta pihak yang mencatat keputusan;
- bukti pendukung per OD yang tersedia, dengan sumber/provenance;
- jawaban tertulis untuk item yang memerlukan klarifikasi.

Jika pemilik kewenangan tidak dapat diverifikasi, jangan meminta peserta memilih `APPROVED`; tandai `NEEDS_OWNER_IDENTIFICATION`.

## 4. Penjelasan Singkat Arsitektur NIS

Baseline dokumen yang tersedia menggambarkan NIS sebagai identitas permanen yang diterbitkan melalui publication batch dengan cohort/year-code dan entry grade, urutan initial yang terkendali, eligibility/placement gate, sequence/registry, serta transaction atomicity. Setelah diterbitkan, NIS/slot tidak diganti atau digunakan ulang. Account provisioning merupakan lifecycle terpisah yang dapat synchronous atau asynchronous. Hybrid hanya menyatakan username reservation sebagai dimensi terpisah; provisioning setelah reservation masih dipilih terpisah. Notification intent/delivery juga berbeda dari account-provisioning intent.

Ini adalah **baseline desain dokumen**, bukan klaim bahwa fitur sudah diimplementasikan, evidence production telah diperoleh, atau owner telah menyetujui kebijakan.

## 5. Status yang Dapat Dipakai

| Status | Penggunaan |
|---|---|
| `PENDING` | Belum ada keputusan eksplisit yang dapat diverifikasi. |
| `NEEDS_OWNER_CLARIFICATION` | Policy owner yang tepat belum diidentifikasi, scope/pertanyaan belum terjawab, atau informasi kebijakan belum cukup. |
| `APPROVED` | Hanya setelah pilihan eksplisit disetujui pihak berwenang dan bukti persetujuan dapat diverifikasi; isi kolom approver, evidence, dan tanggal wajib. |
| `REJECTED` | Pilihan tertentu secara eksplisit ditolak approver berwenang; catat pilihan dan alasan penolakan. |
| `DEFERRED` | Approver berwenang menunda keputusan dengan alasan dan kondisi/jadwal peninjauan; status ini tidak berarti approval. |

`APPROVED` tidak membuktikan technical validation lulus, blocker tertutup, historical recovery lengkap, atau implementation readiness.

## 6. Urutan Rapat Berdasarkan Dependency

Urutan berikut adalah urutan finalisasi kontrak, bukan larangan untuk membahas topik lain lebih awal.

### A. Identifikasi kewenangan terlebih dahulu

Bahas OD-18 dan authority matrix sebagai **penentuan siapa berwenang**, bukan langsung menyetujui seluruh role policy. Sampai mandat formal terverifikasi, persetujuan substantif belum dapat dicatat sebagai `APPROVED`.

### B. Akar kebijakan dan platform

1. OD-01 — database engine dan target scope.
2. OD-02 — runtime/deployment (Priority C, tetapi prerequisite OD-03).
3. OD-11 — canonical User–Siswa relationship.
4. OD-15 — year-code/cohort mapping.
5. OD-18 — role separation, setelah pihak berwenang teridentifikasi.

OD-11/15 dapat dibahas tanpa menunggu platform, tetapi implementasi tetap memerlukan technical evidence.

### C. Keputusan yang bergantung pada akar

1. OD-03 setelah OD-02: comparator, fixture, golden ordering, dan parity.
2. OD-12 setelah OD-01: database privileges.
3. OD-14 setelah OD-01: clock/precision dan cutoff; makna administratif owner dapat dibahas sebelum itu.
4. OD-13 setelah OD-01/12: atomicity dan capacity; numeric cap menunggu benchmark.
5. OD-10 setelah OD-11/18: username collision/reservation policy tunggal. Jika reservation diizinkan dan digunakan, technical dependency OD-01/12 aktif.

### D. Keputusan operasional turunan

- OD-04 setelah OD-10/11/18. Jika Hybrid memakai reservation yang diizinkan OD-10, OD-01/12 menjadi conditional technical prerequisites. Hybrid tidak memilih sync/async secara otomatis.
- OD-05 setelah OD-18.
- OD-06 setelah OD-12/15/18 dan legacy inventory evidence.
- OD-07 setelah OD-14/15/18; OD-09 setelah OD-07/15/18.
- OD-08 setelah OD-03/14/15.
- OD-16 setelah OD-05/11/12/18.
- OD-17 setelah OD-01/12/13/15.

Referensi edge lengkap dan kondisional: [Decision Dependency Matrix](./TAHAP_2A_10_DECISION_DEPENDENCY_MATRIX.md).

## 7. Daftar Prioritas

### Prioritas A — Fundamental Architecture

OD-01, OD-03, OD-11, OD-12, OD-13, OD-15, OD-17.

### Prioritas B — Operational Governance

OD-04, OD-05, OD-06, OD-07, OD-08, OD-09, OD-10, OD-14, OD-16, OD-18.

### Prioritas C — Runtime Validation

OD-02. Dapat dibahas lebih awal karena OD-03 bergantung padanya.

Prioritas bukan topological order. OD-18 diperlukan untuk mengidentifikasi authority sebelum approval kebijakan turunan.

## 8. Daftar Validasi Teknis yang Tetap Diperlukan

- OD-01/12/13/17: target DB constraints, lock/concurrency, rollback, restore, volume dan atomicity.
- OD-02/03/08: actual CLI/web/worker runtime, locale/ICU/comparator parity dan golden ordering.
- OD-04/10/11: relasi User–Siswa, username collisions, login deny/allow; worker retry hanya jika async dipilih.
- OD-06: complete authorized legacy inventory termasuk deleted records, provenance dan collision disposition.
- OD-07/09/14/15/17: evidence entry, placement, timestamp/cutoff, mapping, cohort completeness, concurrent publication.
- OD-16/18: FK/delete/restore/relink behavior, role allow/deny, self-approval dan audit.

Daftar ini adalah kebutuhan bukti dari dokumen 2A.10/2A.8R1; belum dijalankan pada rapat ini.

## 9. Pertanyaan Pembuka untuk Policy Owner

1. Siapa policy owner untuk NIS, dan dokumen mandat apa yang membuktikan kewenangannya?
2. Siapa approver formal untuk kebijakan akademik, administrasi siswa, username/login, exception, serta risiko/arsitektur teknis?
3. Apakah satu orang boleh meminta sekaligus menyetujui? Jika tidak, bagaimana delegasi saat approver tidak tersedia?
4. Siapa yang memverifikasi evidence dan siapa yang mencatat keputusan final?
5. Bagaimana keputusan disahkan: notulen, surat, sistem approval, atau bentuk lain yang dapat diverifikasi?
6. Untuk keputusan dengan dependency, bolehkah policy diputuskan lebih dulu tetapi status implementasi tetap menunggu technical evidence?
7. Bagaimana keputusan historis yang tidak dapat dilacak ke 2A.1/2A.2 akan disahkan ulang, bila memang diperlukan?

## 10. Penutupan Rapat

Untuk setiap OD, konfirmasi bahwa worksheet berisi pilihan eksplisit atau status non-approval, rationale, dependencies, open evidence, authority, approval proof, dan date. Tandai `DECISION_CONFLICT` jika keputusan yang dicatat melanggar prerequisite atau bertentangan dengan keputusan lain. Jangan menyelesaikan konflik dengan asumsi.

**Status awal sesi:** OD-01–OD-18 `PENDING`; BL-01–BL-10 `OPEN`; SRC-GAP-01/02 `OPEN`; NIS implementation readiness `NOT READY FOR IMPLEMENTATION`.
