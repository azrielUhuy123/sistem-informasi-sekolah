# TAHAP 2A.9 — HISTORICAL DECISION TRACEABILITY

**Baseline:** Tahap 2A.8R1  
**Tujuan:** Memetakan apa yang dapat diketahui dari sumber sekunder dan apa yang tetap tidak dapat diverifikasi karena 2A.1/2A.2 asli tidak ditemukan.  
**Batas:** ID `HSR-*` di bawah adalah ID temuan audit baru, bukan ID keputusan historis dan bukan OD. Tidak ada OD baru yang dibuat.

## 1. Klasifikasi

| Klasifikasi | Makna dalam matriks ini |
|---|---|
| `PRESERVED` | Sumber asli ditemukan dan isi terbukti dipertahankan oleh baseline. Tidak dapat digunakan untuk 2A.1/2A.2 pada audit ini. |
| `REFINED` | Sumber asli ditemukan dan perubahan tahap berikutnya dapat dibandingkan. Tidak dapat digunakan untuk 2A.1/2A.2 pada audit ini. |
| `SUPERSEDED` | Sumber asli dan bukti penggantian eksplisit tersedia. Tidak dapat digunakan untuk 2A.1/2A.2 pada audit ini. |
| `CONFLICT` | Isi sumber asli berkonflik dengan baseline dan bukti konflik tersedia. Tidak dapat ditentukan tanpa dokumen asli. |
| `OMITTED` | Persyaratan dalam sumber asli terbukti hilang dari baseline. Tidak dapat ditentukan tanpa dokumen asli. |
| `SECONDARY_EVIDENCE_ONLY` | Sumber tahap berikutnya menyebut tahap lama, namun naskah/provenance asli tidak tersedia. |
| `UNVERIFIABLE` | Konten/keputusan tahap historis tidak dapat dibandingkan atau diatribusikan dengan bukti yang ada. |

## 2. Source Search Findings

| ID Temuan | Sumber historis | Keputusan/konteks yang diketahui | Referensi bagian | Status sumber | Status di 2A.8R1 | Klasifikasi | Tindakan |
|---|---|---|---|---|---|---|---|
| HSR-01 | Tahap 2A.1, file asli tidak ditemukan | Tidak ada isi atau keputusan yang dapat diverifikasi. | Workspace/Git search; 2A.8 §5.1; 2A.8R1 §2 | `NOT_FOUND_AFTER_SEARCH` pada corpus lokal yang diperiksa | SRC-GAP-01 tetap terbuka; OD tetap pending | `UNVERIFIABLE` | Minta naskah asli/export/arsip dan provenance. |
| HSR-02 | Tahap 2A.2, file asli tidak ditemukan | Tidak ada isi atau keputusan yang dapat diverifikasi. | Workspace/Git search; 2A.8 §5.1; 2A.8R1 §2 | `NOT_FOUND_AFTER_SEARCH` pada corpus lokal yang diperiksa | SRC-GAP-02 tetap terbuka; OD tetap pending | `UNVERIFIABLE` | Minta naskah asli/export/arsip dan provenance. |
| HSR-03 | Riwayat Git sebelum shallow boundary | Mungkin berisi artefak/riwayat yang tidak ada pada clone saat ini; target path 2A.1/2A.2 tidak teridentifikasi di objek lokal. | `git rev-parse --is-shallow-repository` → `true`; no fetch performed | `INACCESSIBLE` untuk ancestor yang tidak dimiliki clone | Tidak dapat disimpulkan dari 2A.8R1 | `UNVERIFIABLE` | Jika owner menghendaki pemeriksaan, sediakan clone/arsip lengkap melalui prosedur berwenang; jangan fetch pada audit ini. |
| HSR-04 | Tahap 2A.5 §1 (rujukan sekunder) | Menyebut desain direfine pada 2A.1–2A.3; tidak menguraikan keputusan per 2A.1/2A.2. | `TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md` §1 | `FOUND` sebagai sumber sekunder | 2A.8R1 hanya mencatat gap; tidak mengatribusikan keputusan | `SECONDARY_EVIDENCE_ONLY` | Jangan gunakan sebagai bukti isi/approval; gunakan hanya sebagai petunjuk pencarian. |
| HSR-05 | Tahap 2A.3 §16 | Heading menyatakan “Refinement normatif 2A.1–2A.3”; isi berupa baseline/refinement komposit tanpa provenance tiap aturan ke tahap awal. | `TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md` §16 dan §16.1 dst. | `FOUND` sebagai sumber sekunder | Banyak aturan terintegrasi di 2A.8R1, tetapi asal historis tidak diketahui | `SECONDARY_EVIDENCE_ONLY` | Jangan menyimpulkan bahwa setiap isi §16 merupakan salinan 2A.1 atau 2A.2; cocokkan setelah sumber asli didapat. |
| HSR-06 | Tahap 2A.7R §8 | Ringkasan “Baseline 2A.1–2A.6” menyebut kelompok aturan desain, bukan bukti dokumen atau approval awal. | `TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md` §8 | `FOUND` sebagai sumber sekunder | Status owner tetap `PENDING` pada baseline terbaru | `SECONDARY_EVIDENCE_ONLY` | Pertahankan sebagai ringkasan sekunder saja. |
| HSR-07 | Git history lokal | Tidak ditemukan path asli 2A.1/2A.2, maupun record delete/rename atas nama tersebut pada riwayat lokal yang dapat dijangkau. | `git log --all --full-history --name-status`; delete/rename search | `NOT_FOUND_AFTER_SEARCH` dalam refs/objek lokal | SRC-GAP-01/02 tidak ditutup | `UNVERIFIABLE` | Catat keterbatasan shallow clone; jangan menyimpulkan tidak pernah ada. |

## 3. Historical Decision/Context Matrix

Baris berikut menggunakan ID temuan audit, bukan ID keputusan asli. Setiap baris menyatakan apakah materi historis tertentu dapat ditelusuri. **Tidak ada klaim bahwa setiap area ini pasti dibahas dalam 2A.1 atau 2A.2.**

| ID Temuan | Sumber Historis | Keputusan/Konteks | Referensi Bagian | Status di 2A.8R1 | Klasifikasi | Tindakan |
|---|---|---|---|---|---|---|
| HSR-08 | 2A.1/2A.2 tidak ditemukan; 2A.3 onward tersedia | Format NIS `TTTT.T.NNN` dan aturan padding/overflow | 2A.3 §2; 2A.4 §2; 2A.8 §7–8; 2A.8R1 §2 (scope references) | Baseline masa kini terdefinisi; asal keputusan historis tidak diketahui | `UNVERIFIABLE` | Verifikasi terhadap 2A.1/2A.2 atau sahkan ulang eksplisit jika sumber tak tersedia. |
| HSR-09 | Sama | Mapping academic year ke `year_code` dan contoh `2026/2027 → 2627` | 2A.3 §§2/16.1; 2A.8 §§7/17; OD-15 | Aturan contoh ada; kebijakan umum/approval tetap terbuka pada OD-15 | `UNVERIFIABLE` | Jangan atribusikan asalnya; minta bukti asal dan keputusan owner. |
| HSR-10 | Sama | Sequence key `(year_code, entry_grade)` lintas rombel | 2A.3 §2; 2A.8 §§2/7/15; 2A.8R1 inherited baseline | Didefinisikan dalam desain tersedia; asal historis tidak diketahui | `UNVERIFIABLE` | Cocokkan terhadap sumber asli dan sign-off. |
| HSR-11 | Sama | Placement sebagai prasyarat publication | 2A.3 §§2/eligibility; 2A.4 kebijakan; 2A.8 §9; OD-09 | Baseline placement gate ada; definisi valid dan authority masih pending | `UNVERIFIABLE` | Minta sumber asli dan konfirmasi owner atas definisi final. |
| HSR-12 | Sama | Definisi cohort dan satu initial batch | 2A.3 §§2/6/16; 2A.4 AC-COH/AC-PUB; 2A.8 §§7/12/14 | Baseline one-initial-batch dicatat; OD-17 dan validasi tetap pending | `UNVERIFIABLE` | Verifikasi sumber/approval; jangan tutup OD-17 dari dokumen sekunder. |
| HSR-13 | Sama | Kelayakan siswa PPDB dan kontradiksi status/relasi | 2A.3 eligibility; 2A.4 AC-PLACE/PPDB; 2A.8 §9; 2A.8R1 §§8–9 | Hardblock tertulis pada baseline; belum merupakan bukti implementasi atau historical source | `UNVERIFIABLE` | Minta sumber asli; status acceptance tetap proposed/not run. |
| HSR-14 | Sama | Siswa pindahan dan entry-grade evidence | 2A.3 §§2/eligibility; OD-07; 2A.8 §9 | Prinsip tidak default ke kelas 1 ada; bukti administrasi/approval masih pending | `UNVERIFIABLE` | Owner perlu mengonfirmasi ulang jika sumber historis tidak pulih. |
| HSR-15 | Sama | Sorting alfabetis dan comparator | 2A.3 §§2/9/16; OD-03; 2A.8 §16 | Comparator fail-closed; golden order/runtime belum disetujui | `UNVERIFIABLE` | Cari keputusan historis; owner tetap harus menyetujui comparator. |
| HSR-16 | Sama | Supplemental issuance dan urutan batch | 2A.3 §§2/7; OD-08/14; 2A.8 §13; 2A.8R1 §§3/9 | Cutoff/sort recommendation ada; timestamp/ordinal order tetap pending | `UNVERIFIABLE` | Cocokkan historical cutoff/order dan pertahankan owner decision. |
| HSR-17 | Sama | Immutability NIS dan larangan reuse | 2A.3 §§2/registry; 2A.4 AC-REG/DEL; 2A.8 §11 | Invariant tersedia sebagai baseline; enforcement belum terbukti | `UNVERIFIABLE` | Jangan klaim sejarah atau implementasi tanpa bukti. |
| HSR-18 | Sama | Legacy NIS, collision, reservation | 2A.3 §§2/10/15; OD-06; 2A.8 §11; 2A.8R1 §6 | Exact preservation/fail-closed tertulis; inventory dan approval tetap belum ada | `UNVERIFIABLE` | Temukan sumber dan data inventory berwenang; historical source recovery tidak menggantikan inventory. |
| HSR-19 | Sama | Cohort lock dan concurrency | 2A.3 §§3/6; 2A.4 AC-CON; 2A.8 §14 | Lock contract masih rancangan; target behavior belum diuji | `UNVERIFIABLE` | Minta konteks historis; technical evidence tetap wajib. |
| HSR-20 | Sama | Registry, sequence slots, ledger, NIS reservation | 2A.3 §§3–5; 2A.8 §§10–11/15 | Proposed architecture; belum schema/implementasi | `UNVERIFIABLE` | Jangan merekonstruksi keputusan lama dari desain kini. |
| HSR-21 | Sama | User–Siswa relationship | 2A.3 §§4.9/5; OD-11; 2A.8 §§10/27; 2A.8R1 §10 | `siswa.user_id` hanya kandidat; canonical relation masih PENDING | `UNVERIFIABLE` | Peroleh sumber asli dan rekonsiliasi data; no auto-link/backfill. |
| HSR-22 | Sama | Account provisioning dan username collision | 2A.3 §§4.9/10; OD-04/10; 2A.7R §4; 2A.8R1 §§4–9 | Opsi A/B/Hybrid dipisahkan; belum dipilih owner | `UNVERIFIABLE` | Jangan menganggap opsi terbaru sebagai keputusan historis atau persetujuan. |
| HSR-23 | Sama | Notification lifecycle | 2A.7R §4; 2A.8R §5.4; 2A.8R1 §7 | Intent/delivery terpisah sebagai koreksi terkini; asal kebijakan historis tidak diketahui | `UNVERIFIABLE` | Periksa sumber awal saat ditemukan; owner policy tetap diperlukan. |
| HSR-24 | Sama | Timestamps dan `official_entry_at` | 2A.3 §§4.1/4.2/6/7; 2A.7R §5; 2A.8 §17; 2A.8R1 inherited | Semantik refined; clock/watermark masih pending/unverified | `UNVERIFIABLE` | Cocokkan sumber dan keputusan waktu; tidak ada approval otomatis. |
| HSR-25 | Sama | Transaction atomicity | 2A.3 §§6/16.2; 2A.4 AC-PUB/CON; 2A.8 §12; 2A.8R1 §§5/8 | NIS publication all-or-nothing sebagai baseline; target tests belum dilakukan | `UNVERIFIABLE` | Historical provenance tidak menggantikan target transaction tests. |
| HSR-26 | Sama | Delete, restore, force delete, relink | 2A.3 §§4.6/5/10; OD-16; 2A.8 §11 | Ledger retention/relink policy proposed; owner/driver evidence pending | `UNVERIFIABLE` | Minta konfirmasi historical intent; tetap perlu approval/technical proof. |
| HSR-27 | Sama | DB access dan separation of duties | 2A.3 §§3/5/6.6; OD-12/18; 2A.8 §§11/22/24 | Grant/role target belum divalidasi; OD-12/18 pending | `UNVERIFIABLE` | Sumber awal bukan bukti grant deployment; minta owner/Ops evidence. |
| HSR-28 | Sama | Acceptance criteria dan test coverage | 2A.4 §5; 2A.8 §25; 2A.8R §8; 2A.8R1 §9 | Kriteria tersedia di tahapan kemudian; semua criteria tetap proposed/not run untuk koreksi terkait | `UNVERIFIABLE` | Setelah sumber asli pulih, periksa missing/changed requirements dan tambahkan traceability tanpa memalsukan status test. |

## 4. Persetujuan Owner dan Rekomendasi

Dokumen sekunder 2A.3 menyebut contoh year-code sebagai “telah disepakati”, tetapi materi yang ditemukan tidak mencakup approval record yang dapat mengidentifikasi pihak, waktu, cakupan, atau status keputusan tersebut. Tahap 2A.4, 2A.7, 2A.7R, 2A.8, dan 2A.8R1 secara eksplisit mempertahankan OD-01–OD-18 `PENDING`. Maka:

- Tidak ada keputusan 2A.1/2A.2 yang dapat dinyatakan memiliki bukti persetujuan owner berdasarkan audit ini.
- Tidak ada rekomendasi teknis yang dinaikkan menjadi persetujuan.
- Tidak ada temuan yang diklasifikasikan `PRESERVED`, `REFINED`, `SUPERSEDED`, `CONFLICT`, atau `OMITTED` terhadap dokumen asli, karena isi dokumen asli tidak tersedia untuk perbandingan.

## 5. Batas dan Tindakan Lanjutan

1. Status `NOT_FOUND_AFTER_SEARCH` berlaku untuk pencarian lokal yang didokumentasikan, bukan klaim non-eksistensi global.
2. Status `INACCESSIBLE` hanya digunakan untuk riwayat Git sebelum shallow boundary/remote yang belum tersedia, bukan disamakan dengan source yang teridentifikasi.
3. Bila sumber asli diterima, buat revisi traceability baru berbasis isi lengkap, heading/halaman, provenance, dan approval record; jangan mengedit status secara retroaktif tanpa bukti.
4. Sampai itu terjadi, SRC-GAP-01/02 tetap open dan readiness implementation tidak berubah.

