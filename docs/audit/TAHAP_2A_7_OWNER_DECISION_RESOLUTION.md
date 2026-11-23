# TAHAP 2A.7 — OWNER DECISION RESOLUTION

**Sistem:** SINERGI-MJ  
**Jenis:** Penyelesaian keputusan arsitektur NIS  
**Status:** Dokumen untuk review owner; bukan persetujuan, implementasi, atau otorisasi perubahan database.  
**Verdict:** **PARTIALLY READY FOR OWNER APPROVAL**

## 1. Executive Summary

Tahap ini mengubah daftar OD-01–OD-18 menjadi paket keputusan yang dapat ditinjau: setiap keputusan memuat pilihan, dampak, rekomendasi, dependensi, bukti tersisa, dan status persetujuan. Tidak ada keputusan owner yang dianggap atau dicatat sebagai disetujui.

Konteks proyek pada brief menyatakan aplikasi masih dalam pengembangan/testing, belum digunakan operasional sekolah, dan belum memiliki deployment produksi. Database lokal `sekolah_app_testing` memiliki sedikit data karena belum menjadi database operasional; jumlah tersebut bukan anomali. Fakta tersebut membatasi kesimpulan Tahap 2A.6 pada lingkungan lokal dan tidak memberi dasar untuk menetapkan kapasitas produksi.

Hal yang tetap menjadi perhatian teknis:

- MySQL 8.0.30, PHP 8.2.29, Laravel 12.40.2, ICU 71.1 dan data schema telah diverifikasi **pada lingkungan lokal**. Tidak ada deployment produksi untuk dibandingkan menurut konteks proyek.
- Collator lokal yang diminta dengan locale `id-ID` melaporkan actual locale `root`; output fixture bukan golden order Indonesia yang disetujui. Sorting initial tetap **fail closed**.
- Grant akun koneksi lokal mencakup hak global `*.*`, termasuk DML, DDL, administratif dan `GRANT OPTION`. Ini perlu ditangani sebelum klaim immutability atau aktivasi writer.
- Snapshot lokal berisi sedikit data dan tidak membuktikan provenance NIS, riwayat entry, atau bebas konflik pada data yang lebih lengkap.
- Dua FK User–Siswa, keberadaan writer legacy, serta unique placement yang belum memadai masih harus diperlakukan dalam desain integrasi dan validasi.

**Keputusan yang dapat dibawa ke owner sekarang:** pilihan operasional dengan konsekuensi yang telah dijelaskan, terutama OD-04, OD-05, OD-07–OD-10, OD-14–OD-16 dan OD-18. Keputusan yang bergantung pada validasi mesin/schema perlu tetap berstatus `TECHNICAL VALIDATION REQUIRED` atau disetujui hanya sebagai keputusan bersyarat. Rekomendasi tidak sama dengan approval.

Tidak ada source code, migration, konfigurasi, database, privilege, NIS, username, atau workflow yang diubah. Tidak ada test atau operasi database yang dijalankan pada tahap ini.

## 2. Project and Audit Context

### 2.1 Konteks

Stack lokal yang dinyatakan dalam brief: Laravel 12.40.2, PHP 8.2.29, MySQL 8.0.30, Laragon 6.0.0, Vue.js dan Vite. Tahap 2A.6 membuktikan sebagian besar versi dan schema lokal melalui pemeriksaan read-only. Konteks “belum ada deployment produksi” berasal dari brief owner; laporan ini tidak mengklaim melakukan verifikasi deployment.

### 2.2 Sumber audit yang digunakan

- [Desain arsitektur NIS Tahap 2A.3](./TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md) — invariant, service boundary, publication, comparator, ledger, locking dan urutan implementasi konseptual.
- [Final Readiness Gate Tahap 2A.4](./TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md) — OD-01–OD-18, evidence register, blocker dan verdict terdahulu.
- [Owner Decision & Evidence Closure Tahap 2A.5](./TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md) — status keputusan sebelumnya, data yang belum terverifikasi, jalur mutasi dan query inventory read-only.
- [Local Environment & Schema Verification Tahap 2A.6](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md) — bukti lokal, schema aktual, agregat snapshot, comparator, privilege, mutation path dan pembaruan blocker.
- [Audit otorisasi lintas peran Tahap 3](./TAHAP_3_AUDIT_OTORISASI_LINTAS_PERAN.md) — konteks role/otorisasi; bukan persetujuan role NIS.

Laporan sebelumnya dipakai bersama bukti source yang tercatat di dalamnya; khususnya jalur login memakai `siswa.user_id`, sedangkan model/schema juga memiliki `users.siswa_id`. Tahap ini tidak mengulang seluruh audit repository dan tidak menafsirkan pencarian source sebagai bukti tidak adanya external SQL atau proses di luar repository.

### 2.3 Kategori bukti

- **Established design baseline:** rancangan yang harus dipertahankan sebagai baseline sampai owner secara eksplisit mengubahnya; bukan bukti approval formal.
- **Fakta lokal:** keadaan yang diamati pada mesin/schema lokal. Tidak otomatis berlaku pada CI, Apache handler yang belum diketahui, atau deployment lain.
- **Rekomendasi teknis:** pilihan yang disarankan, bukan keputusan owner.
- **Technical validation required:** pilihan atau sign-off belum aman difinalkan tanpa bukti/eksperimen yang disebutkan.
- **Future operational policy:** aturan yang harus ditetapkan sebelum operasi sekolah; belum perlu diberlakukan pada database testing sekarang.

## 3. Established Design Baseline

Baseline berikut dipertahankan dari desain Tahap 2A.3 dan brief Tahap 2A.7. Baseline ini bukan approval implisit; owner dapat mengubahnya secara eksplisit dengan dampak yang terdokumentasi.

1. NIS baru berformat `TTTT.T.NNN`, minimal tiga digit sequence dan sequence dapat melebihi 999.
2. Contoh mapping `2026/2027 → 2627`; NIS mencerminkan tahun dan tingkat pertama masuk SDN Mekar Jaya, bukan kelas sekarang atau tahun penerbitan.
3. Sequence dibagi berdasarkan `(year_code, entry_grade)` dan tidak dipisahkan per rombel.
4. NIS diterbitkan setelah penempatan rombel valid, bukan pada konfirmasi daftar ulang PPDB.
5. Initial publication adalah satu batch per cohort `year_code`; siswa wajib sebelum cutoff tidak boleh tertinggal. Placement atau data eligibility yang tidak valid memblokir publication.
6. Initial order adalah nama lengkap alfabetis setelah comparator dan golden order disetujui.
7. Pindahan sebelum initial publication masuk batch awal; pindahan sesudahnya diproses supplemental. Entry year/grade tidak diturunkan dari sekolah sebelumnya, kelas aktif atau angkatan.
8. NIS terbit immutable dan tidak digunakan kembali setelah soft delete, force delete, restore, koreksi, relinking, atau kegagalan workflow. Batch gagal tidak boleh meninggalkan issuance parsial.
9. Supplemental tidak mengubah NIS sebelumnya. Entry pra-initial yang tertinggal harus direkonsiliasi, bukan diam-diam dimasukkan sebagai supplemental.
10. Legacy dipertahankan exact-string; tidak dinormalisasi atau ditebak. Konflik fail closed sampai ada bukti dan keputusan sah.
11. Semua mutasi yang memengaruhi cohort/eligibility/placement memakai lock protocol bersama publisher; mutasi lintas cohort menggunakan urutan lock deterministik.
12. Attempt gagal dicatat di luar transaksi publication. Retry mengacu pada batch yang sama dan harus idempotent.

## 4. Decision Governance Principles

- Hanya owner dapat mengisi keputusan owner. Seluruh kolom `Owner decision` pada register masih menunggu jawaban.
- `Approval status` semua OD adalah `PENDING`; tidak ada `APPROVED`, `REJECTED`, atau `DEFERRED` yang direkayasa.
- Approval dapat bersyarat dan bertahap, tetapi harus menyatakan cakupan, syarat penutupan, serta komponen yang boleh berjalan.
- Keputusan teknis tidak boleh menghapus kebutuhan bukti. Contoh: memilih MySQL tidak membuktikan locking, trigger, atau privilege yang aman.
- Database lokal bukan database operasional, dan jumlah data lokal tidak digunakan untuk menetapkan kapasitas batch.
- Jika comparator, legacy conflict, entry grade, placement, atau authorization belum dapat dipastikan, sistem harus gagal tertutup.
- Tidak ada kebijakan administratif sekolah yang ditambahkan sebagai fakta. Formulir, pejabat pengesah, cutoff dan pembagian role harus dikonfirmasi owner.

## 5. Decision Priority and Dependencies

| Prioritas | Keputusan | Dependensi utama | Kapan harus ditutup |
|---|---|---|---|
| A — Fundamental architecture | OD-01 database; OD-03 comparator; OD-11 relasi canonical; OD-12 privilege; OD-13 atomicity; OD-15 year-code; OD-17 one initial batch | Engine memengaruhi constraints/lock; comparator memengaruhi urutan immutable; relasi/privilege memengaruhi auth dan ledger; year-code memengaruhi cohort; batch guard tergantung engine. | Sebelum schema final dan service publisher terkait. |
| B — Operational governance | OD-04 account lifecycle; OD-05 correction; OD-06 legacy collision; OD-07 evidence pindahan; OD-08 supplemental order; OD-09 valid placement; OD-10 username collision; OD-14 timestamp; OD-16 deletion/relink; OD-18 roles | Sebagian saling terkait dengan OD-03, OD-11, OD-13 dan OD-15; keputusan admin dapat disiapkan paralel. | Sebelum workflow yang bersangkutan dibangun/diaktifkan. |
| C — Runtime validation | OD-02 PHP runtime/extensions | Bergantung pada target runtime CI/web yang akan digunakan; terkait langsung dengan OD-03. | Sebelum comparator/integrasi target disahkan. |

### Dependensi penting

1. OD-01 → OD-12, OD-13, OD-17 dan uji constraint/locking/trigger.
2. OD-02 → OD-03; hasil `Collator` hanya bermakna pada runtime dan data locale yang ditetapkan.
3. OD-03 → initial publication dan tie-break supplemental OD-08.
4. OD-11 → login, user creation OD-04, username OD-10 dan lifecycle OD-16.
5. OD-14 → cutoff initial/supplemental, urutan kronologis OD-08 dan recovery.
6. OD-15 → cohort, entry history dan sequence allocator.
7. OD-06 → sequence/high-water/registry; reservation tidak boleh diasumsikan otomatis.
8. OD-09 → cohort eligibility dan publication hardblock.
9. OD-12/OD-17 → klaim immutability dan pencegahan publication ganda.
10. OD-13 → bentuk transaksi publication dan batas volume; angka batas tidak boleh dipilih sebelum benchmark.

## 6. OD-01 sampai OD-18 Resolution

Status sebelumnya di bawah mengikuti register Tahap 2A.5/2A.6. Status saat ini menyatakan kesiapan pembahasan atau kebutuhan validasi, bukan persetujuan.

### OD-01 — Database dan versi resmi

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `OPEN` |
| Masalah/konteks | MySQL 8.0.30/InnoDB dan schema `sekolah_app_testing` terbukti pada koneksi lokal. Brief menyatakan belum ada deployment produksi. Engine lokal tidak otomatis menjadi target operasional resmi. |
| Pilihan | (A) Tetapkan MySQL 8.0.30 sebagai baseline development/testing; (B) gunakan engine lain sebagai target resmi dan siapkan parity environment; (C) biarkan engine belum dipilih, sehingga schema NIS ditahan. |
| Kelebihan/risiko | A selaras dengan bukti lokal dan mengurangi variasi awal, tetapi belum membuktikan kompatibilitas semua constraint/trigger. B dapat cocok dengan arah hosting, tetapi memerlukan environment dan uji ulang. C menghindari asumsi, tetapi memblokir migration spesifik engine. |
| Rekomendasi | Owner dapat menetapkan MySQL 8.0.30 **sebagai baseline dev/test saja**. Sebelum deployment operasional, tetapkan engine/version target dan jalankan compatibility suite pada versi tersebut. |
| Konsekuensi bila ditunda | DDL, strategi concurrency, unique/trigger dan migration test tidak dapat difinalkan. |
| Dependensi | OD-12, OD-13, OD-17; TE-01, TE-04, TE-08–TE-11. |
| Bukti diperlukan | Persetujuan tertulis scope engine (development vs operational); manifest driver, schema dan migration target; compatibility tests di database disposable. |
| Status saat ini | `READY FOR OWNER REVIEW` untuk baseline development; target operasional adalah future operational policy. |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-02 — PHP runtime dan ekstensi

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `OPEN` |
| Masalah/konteks | PHP CLI dan proses `artisan serve` lokal menggunakan PHP 8.2.29. Apache `fcgid` tersedia tetapi handler/vhost aplikasinya belum diverifikasi. Manifest mensyaratkan PHP `^8.2`; local CLI memiliki `intl`, ICU 71.1, Normalizer, Collator, mbstring dan PDO. |
| Pilihan | (A) PHP 8.2+ dengan daftar extension wajib dan versi runtime/ICU dipin untuk setiap environment; (B) satu SAPI/build yang sama untuk CLI dan web; (C) menerima perbedaan runtime dengan matrix test eksplisit. |
| Kelebihan/risiko | A memberi fleksibilitas patch, tetapi comparator dapat berubah lintas ICU. B mengurangi drift, tetapi konfigurasi hosting harus mendukung. C memungkinkan hosting beragam, namun menambah beban compatibility test. |
| Rekomendasi | Tetapkan minimum PHP sesuai manifest (`>=8.2` pada rentang yang didukung), ekstensi yang dibutuhkan, dan kesetaraan perilaku CLI/web untuk comparator. Pin versi patch/ICU pada test dan release environment; jangan menyatakan Apache sudah memakai binary lokal. |
| Konsekuensi bila ditunda | Eksekusi CLI, web dan comparator dapat berbeda; hasil sort permanen belum aman. |
| Dependensi | OD-03; TE-02–TE-06. |
| Bukti diperlukan | Konfirmasi SAPI/`php.ini` web yang benar-benar dipakai; daftar extension/PDO drivers; runtime matrix CI dan probe actual locale. |
| Status saat ini | `TECHNICAL VALIDATION REQUIRED` untuk menandatangani runtime lintas SAPI. |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-03 — Comparator dan sorting nama

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `OPEN` |
| Masalah/konteks | Actual locale lokal `id-ID` adalah `root`. Tidak ada golden order resmi maupun comparator aplikasi yang teruji lintas runtime. Sorting menentukan NIS immutable. |
| Pilihan | (A) ICU/Collator dengan actual Indonesian locale dan ICU/data version yang dipin; (B) library collation mandiri dengan data dipaketkan; (C) aturan comparator eksplisit yang disetujui owner dan diuji dengan fixture; (D) tetap menahan initial publication. |
| Kelebihan/risiko | A memanfaatkan ICU, tetapi locale/data target belum terbukti. B dapat meningkatkan parity, namun dependency/lisensi/maintenance perlu dinilai. C mengontrol edge case, tetapi aturan aksen/tanda baca/gelar tidak boleh dikarang. D paling aman selama bukti belum cukup, tetapi menahan penerbitan. |
| Rekomendasi | Jangan menyetujui implementasi comparator final dari probe `root`. Pilih A/B/C melalui eksperimen terisolasi, susun golden fixture dan expected order yang disahkan owner. Pertahankan NFC, case behavior, whitespace, aksen, tanda baca, gelar dan tie-break sebagai keputusan eksplisit; jangan menghapus aksen atau tanda baca tanpa persetujuan. |
| Konsekuensi bila ditunda | Initial publication harus fail closed; kalau dipaksakan sekarang, NIS dapat terbit dengan urutan yang tidak disetujui dan tidak dapat diurut ulang. |
| Dependensi | OD-02; OD-08; TE-06/07. |
| Bukti diperlukan | Actual locale/data; golden order untuk kapitalisasi, composed/decomposed Unicode, whitespace, aksen, punctuation, prefix/gelar, nama identik; hasil identik pada runtime target. |
| Status saat ini | `TECHNICAL VALIDATION REQUIRED` |
| Owner decision / alasan | Menunggu owner; pilihan final tidak dapat ditetapkan hanya dari keberadaan ekstensi `intl`. |
| Approval status | `PENDING` |

### OD-04 — Siklus akun siswa relatif terhadap NIS

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | Login siswa mencari NIS dan mengikuti `siswa.user_id`; PPDB legacy membuat akun/username bersama dengan NIS. Baseline baru menerbitkan NIS sesudah placement valid, bukan saat daftar ulang. |
| Pilihan | (A) Buat akun nonaktif sebelum publication dengan identifier internal; (B) buat/link akun setelah NIS diterbitkan; (C) buat draft person/Siswa tanpa User lalu buat akun saat publication. |
| Kelebihan/risiko | A mendukung persiapan, tetapi rawan username placeholder/orphan. B selaras dengan login berbasis NIS, tetapi akun baru tersedia belakangan. C memisahkan admission dari credential, namun membutuhkan alur provisioning dan notifikasi. |
| Rekomendasi | C sebagai pola domain; akun login dibuat/diaktifkan dalam langkah publication setelah NIS tersedia, atau provisioning sesudahnya dengan status pending yang terkontrol. Jangan jadikan NIS belum terbit sebagai username dan jangan membuat identifier sementara yang dapat dipakai login. |
| Konsekuensi bila ditunda | Integrasi PPDB, login, reset password, aktivasi akun dan rollback tidak dapat ditetapkan konsisten. |
| Dependensi | OD-10, OD-11, OD-16, OD-18. |
| Bukti diperlukan | Konfirmasi workflow akun yang dibutuhkan sekolah; tes login/provisioning dan konflik username pada staging disposable. |
| Status saat ini | `READY FOR OWNER REVIEW` |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-05 — Otoritas koreksi setelah publikasi

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | NIS dan sequence yang sudah terbit immutable. Kesalahan nama, cohort, entry grade atau identity setelah publikasi tidak boleh diperbaiki dengan mengganti/menggunakan ulang NIS. |
| Pilihan | (A) Perubahan hanya melalui permintaan + verifikasi + persetujuan berbeda; (B) satu pejabat dapat meminta dan menyetujui; (C) kasus tidak dapat dikoreksi melalui aplikasi dan diproses sebagai exception administratif terdokumentasi. |
| Kelebihan/risiko | A memiliki separation of duties namun lebih lambat. B sederhana tetapi konflik kepentingan lebih tinggi. C menjaga batas aplikasi, tetapi membutuhkan proses eksternal yang dapat diaudit. |
| Rekomendasi | A dengan correction/conflict record append-only. Koreksi fakta dapat memperbarui data yang sah, tetapi tidak mengubah NIS lama, slot terbit, atau urutan ledger; jika NIS benar-benar salah, owner menetapkan exception terpisah tanpa reuse. |
| Konsekuensi bila ditunda | Tidak ada pemilik proses untuk data salah; supplemental terkait dapat terus terblokir. |
| Dependensi | OD-06, OD-16, OD-18; registry/ledger design. |
| Bukti diperlukan | Daftar role/pejabat, bukti pendukung per jenis koreksi, audit trail dan uji akses. |
| Status saat ini | `READY FOR OWNER REVIEW` |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-06 — Legacy collision dan reservation

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `OPEN` |
| Masalah/konteks | Snapshot lokal hanya berisi sedikit Siswa dan tidak membuktikan provenance atau kelengkapan data historis. Tidak ada dasar menyimpulkan bahwa collision yang tidak teramati pasti tidak ada. Reservation dapat memengaruhi sequence permanen. |
| Pilihan | (A) Stop publication dan rekonsiliasi collision tanpa auto-skip; (B) reserve exact slot setelah bukti, alasan, approver, dan audit; (C) remap NIS legacy (bertentangan dengan baseline dan tidak direkomendasikan). |
| Kelebihan/risiko | A paling konservatif namun dapat menunda cohort. B menyelesaikan kasus secara terkendali tetapi memerlukan owner authority dan ledger slot. C dapat menghindari collision lokal, tetapi mengubah identitas yang telah digunakan dan tidak sesuai baseline. |
| Rekomendasi | Fail closed sebagai default. Jangan auto-reserve atau mengubah legacy. Reservation hanya melalui persetujuan eksplisit, bukti yang memadai, exact string, alasan, approver terpisah dan audit append-only. |
| Konsekuensi bila ditunda | Allocator tidak dapat dipakai pada cohort yang collision-nya belum disposition; risiko sequence reuse/skip tanpa kewenangan tetap terbuka. |
| Dependensi | OD-01, OD-12, OD-15, OD-17; TE-14 dan inventory legacy berwenang. |
| Bukti diperlukan | Inventory aktif/soft-deleted/ledger/username; provenance entry year/grade; collision disposition owner. |
| Status saat ini | `TECHNICAL VALIDATION REQUIRED` |
| Owner decision / alasan | Menunggu bukti inventory sebelum menyetujui aturan kasus collision; default fail closed bukan approval reservation. |
| Approval status | `PENDING` |

### OD-07 — Bukti mutasi dan tingkat pertama masuk

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | Siswa pindahan memakai tahun dan grade pertama diterima di SDN Mekar Jaya. Kelas aktif, sekolah asal, angkatan, atau tanggal penerbitan bukan bukti yang cukup. |
| Pilihan | (A) Dokumen penerimaan/transfer resmi yang ditentukan owner; (B) arsip administrasi sekolah yang dapat diverifikasi; (C) verifikasi manual oleh pejabat berwenang dengan alasan dan bukti tercatat; dapat pula kombinasi berdasarkan kasus. |
| Kelebihan/risiko | A konsisten tetapi mungkin tidak tersedia untuk kasus lama. B dapat menjangkau data historis namun mutu arsip bervariasi. C menangani pengecualian, tetapi memerlukan separation of duties dan audit. |
| Rekomendasi | Owner menentukan daftar bukti yang dapat diterima dan pejabat pengesah. Dukung jalur exception manual dengan alasan, evidence reference dan approver; jangan mengisi entry year/grade dari kelas aktif sebagai fallback. |
| Konsekuensi bila ditunda | Transfer tidak dapat dimasukkan dengan aman ke entry history/cohort. |
| Dependensi | OD-09, OD-14, OD-15, OD-18. |
| Bukti diperlukan | SOP/dokumen yang diakui sekolah dan role pengesah; contoh uji tanpa menyebarkan PII. |
| Status saat ini | `READY FOR OWNER REVIEW` |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-08 — Urutan supplemental batch

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | Batch supplemental memperoleh nomor permanen; batch boundary dapat mengubah urutan jika urutan hanya berdasarkan nama per-batch. |
| Pilihan | (A) `(official_entry_at, comparator nama, siswa.id)`; (B) sort nama dalam setiap supplemental batch lalu `siswa.id`; (C) aturan lain yang owner definisikan. |
| Kelebihan/risiko | A mempertahankan prioritas entry resmi dan memberi tie-break deterministik, tetapi bergantung timestamp yang andal. B mudah dipahami per batch, tetapi entry lebih awal dapat memperoleh suffix lebih besar bila diproses di batch belakangan. |
| Rekomendasi | A, dengan timestamp resmi OD-14, comparator yang lulus OD-03 dan `siswa.id` sebagai tie-break stabil. |
| Konsekuensi bila ditunda | Supplemental publication fail closed; urutan permanen tidak boleh dipilih diam-diam oleh implementasi. |
| Dependensi | OD-03, OD-14, OD-15. |
| Bukti diperlukan | Golden tests perbedaan timestamp/tie; validasi clock/precision dan idempotensi batch. |
| Status saat ini | `READY FOR OWNER REVIEW` untuk prinsip urutan; eksekusi menunggu validasi OD-03/OD-14. |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-09 — Definisi placement valid

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | Penerbitan mensyaratkan placement valid. Schema lokal tidak mencegah semua multiple-open-placement; tidak adanya anomali pada snapshot kecil bukan jaminan integritas. |
| Pilihan | (A) Placement aktif tunggal pada tahun ajaran/cohort yang tepat, dengan kelas/rombel dan tingkat valid; (B) izinkan status placement sementara/pengecualian; (C) definisi lain oleh owner. |
| Kelebihan/risiko | A deterministik dan mudah diuji, tetapi aturan status/tanggal perlu disepakati. B melayani workflow transisi namun rawan menjadi bypass jika exception luas. |
| Rekomendasi | Tetapkan kondisi minimum eksplisit: siswa dan referensi tahun/rombel/tingkat ada dan aktif; placement berada pada tahun yang disetujui; satu placement yang memenuhi syarat; tidak ada konflik atau hubungan yang bertentangan. Pengecualian harus named, berotorisasi, tercatat, dan tidak mengabaikan konflik. |
| Konsekuensi bila ditunda | Resolver eligibility tidak dapat menghasilkan hardblock yang konsisten; publisher tidak boleh berjalan. |
| Dependensi | OD-07, OD-15, OD-18; schema/constraint OD-01. |
| Bukti diperlukan | Persetujuan owner atas status/tanggal/exception; fixture untuk placement hilang, ganda, inactive dan mismatch; test concurrency placement. |
| Status saat ini | `READY FOR OWNER REVIEW` |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-10 — Konflik username

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | Schema username unik; akun legacy dapat sudah memakai nilai yang akan ditetapkan sebagai username=NIS. Tidak boleh salah merge atau mengambil alih akun. |
| Pilihan | (A) Hardblock akun yang konflik dan rekonsiliasi manual; (B) username alternatif dengan format yang disetujui; (C) mengganti username lama setelah verifikasi dan approval. |
| Kelebihan/risiko | A menghindari perubahan akun lain namun dapat menunda candidate/batch. B menghindari collision tetapi mengubah konvensi login. C mempertahankan NIS sebagai username, tetapi berisiko mengganggu pemilik akun lama. |
| Rekomendasi | Jangan auto-merge atau auto-rename. Rekonsiliasi akun berdasarkan relasi canonical dan bukti identitas; jika alternatif username diperlukan, owner menyetujui aturan dan informasikan dampak login. |
| Konsekuensi bila ditunda | Provisioning akun yang konflik gagal; pilihan fallback otomatis dapat salah memberikan akses. |
| Dependensi | OD-04, OD-11, OD-16, OD-18. |
| Bukti diperlukan | Agregat username/NIS dan relasi pada dataset yang disetujui; tes unique collision/authorization. |
| Status saat ini | `READY FOR OWNER REVIEW` |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-11 — Canonical User–Siswa relationship

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `OPEN` |
| Masalah/konteks | `LoginRequest` mencari siswa via NIS dan relasi `siswa.user_id`; model/migration juga menyediakan `users.siswa_id`. Kedua FK nullable dan tidak menjamin konsistensi silang atau one-to-one pada schema lokal. Snapshot lokal kecil tidak menggantikan persetujuan relasi canonical. |
| Pilihan | (A) `siswa.user_id` sebagai satu-satunya canonical FK; (B) `users.siswa_id`; (C) mempertahankan dua arah dengan mekanisme consistency enforcement; (D) migration bertahap dengan read-only compatibility field selama transisi. |
| Kelebihan/risiko | A selaras dengan login yang ada, tetapi memerlukan rekonsiliasi dan penyesuaian workflow yang membaca arah lain. B memerlukan perubahan auth yang lebih luas. C tetap punya risiko drift jika kedua nilai ditulis terpisah. D mengurangi cutover besar tetapi rentan dual-write jika tidak ada satu source of truth. |
| Rekomendasi | Pilih `siswa.user_id` sebagai canonical **kandidat** karena jalur login memakainya; jangan backfill/auto-link sebelum inventory dan owner approval. Jika `users.siswa_id` dipertahankan sementara, jadikan compatibility/read-only dan rencanakan removal setelah seluruh reader/writer dipetakan. Enforce one-to-one sesuai target engine. |
| Konsekuensi bila ditunda | Auth, account lifecycle, delete/relink dan migration tidak dapat difinalkan; dual-link tidak boleh diperlakukan sebagai canonical. |
| Dependensi | OD-01, OD-04, OD-10, OD-12, OD-16; TE-13/15. |
| Bukti diperlukan | Reconciliation agregat pada snapshot yang disetujui; call-site reader/writer; aturan orphan/mismatch/relink; uji constraints dan login. |
| Status saat ini | `TECHNICAL VALIDATION REQUIRED` |
| Owner decision / alasan | Menunggu owner setelah bukti hubungan dan impact reader tersedia. |
| Approval status | `PENDING` |

### OD-12 — Database privilege dan direct SQL

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `OPEN` |
| Masalah/konteks | Akun koneksi aplikasi lokal memiliki grant global `*.*` termasuk DML, DDL, hak administratif dan `GRANT OPTION`. Service Laravel saja tidak dapat menjamin ledger immutable bila runtime atau external writer dapat mengubah ledger langsung. |
| Pilihan | (A) Pisahkan application, migration/deployment, dan DBA accounts; (B) satu akun shared dengan privileges luas; (C) runtime restricted plus DB constraints/trigger, akses break-glass DBA terkontrol. |
| Kelebihan/risiko | A/C menerapkan least privilege dan separation, tetapi memerlukan deployment/secret-role management; B sederhana tetapi risiko blast radius sangat tinggi dan tidak dapat mendukung klaim immutability. |
| Rekomendasi | Tolak B untuk environment yang mengaktifkan NIS. Pisahkan: application account hanya hak yang diperlukan; migration/deployment account memperoleh DDL hanya saat deployment; DBA account terpisah dan diaudit. Lindungi slot/registry lewat privilege serta constraint/trigger yang didukung engine. |
| Konsekuensi bila ditunda | Direct SQL dapat melewati service/lock dan mengubah/menghapus ledger; tidak boleh mengklaim immutability atau mengaktifkan publisher. |
| Dependensi | OD-01, OD-05, OD-06, OD-13, OD-17; TE-11/12. |
| Bukti diperlukan | Role/grant matrix yang disetujui; audit hak effective; direct SQL update/delete denial tests; trigger/FK `ON DELETE SET NULL` tests di database disposable. |
| Status saat ini | `TECHNICAL VALIDATION REQUIRED` |
| Owner decision / alasan | Menunggu owner/DBA approval; tidak ada perubahan grant dilakukan. |
| Approval status | `PENDING` |

### OD-13 — Batch volume dan atomicity

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `OPEN` |
| Masalah/konteks | Tidak ada benchmark volume/lock-duration, backup/restore drill, atau bukti recovery. Jumlah record lokal sangat kecil dan bukan dasar batas produksi. |
| Pilihan | (A) All-or-nothing transaction per publication; (B) chunked publication dengan staging dan protocol yang menjamin tak ada issuance parsial terlihat; (C) tetapkan ukuran numerik sekarang tanpa benchmark. |
| Kelebihan/risiko | A sederhana dan sesuai atomicity baseline, tetapi transaksi besar dapat menahan lock/timeout. B dapat meningkatkan skala, tetapi jauh lebih kompleks dan harus tetap memenuhi atomic visibility/idempotency. C cepat tetapi tidak berbasis data. |
| Rekomendasi | A untuk initial publication. Jangan tetapkan batas numerik sebelum synthetic benchmark. Jika batas tercapai, jangan mengubah ke chunking tanpa desain/approval baru yang mempertahankan atomicity dan recovery. Attempt logging tetap terpisah dari transaksi publish. |
| Konsekuensi bila ditunda | Batch service, timeout/retry dan ukuran transaksi belum dapat ditetapkan; production-scale publish dilarang. |
| Dependensi | OD-01, OD-12, OD-17; TE-08/09/19/20. |
| Bukti diperlukan | Benchmark sintetis di database disposable dengan ukuran representatif yang owner/ops sediakan; rollback, uncertain commit, retry, restore dan lock contention tests. |
| Status saat ini | `TECHNICAL VALIDATION REQUIRED` |
| Owner decision / alasan | Menunggu benchmark untuk angka limit; prinsip all-or-nothing dapat dibahas sekarang. |
| Approval status | `PENDING` |

### OD-14 — Timestamp dan otoritas waktu

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | `official_entry_at`, initial cutoff dan supplemental order harus stabil terhadap timezone, precision, skew serta retry. Lingkungan lokal MySQL menunjukkan timezone `SYSTEM`; belum ada operational authority yang disahkan. |
| Pilihan | (A) DB UTC clock sebagai sumber waktu; (B) trusted application clock dengan sinkronisasi; (C) waktu administratif yang disahkan dan dicatat, berbeda dari waktu insert teknis. |
| Kelebihan/risiko | A menyederhanakan konsistensi satu DB, tetapi perlu failover/clock validation. B dapat mendukung service terdistribusi, namun perlu drift controls. C merepresentasikan keputusan administratif, tetapi wajib memisahkan tanggal efektif dan waktu pencatatan audit. |
| Rekomendasi | Simpan timestamp teknis UTC dengan precision yang disepakati dan terpisah dari local display (WIB). Jika entry efektif ditetapkan petugas, simpan tanggal/waktu efektif beserta actor/evidence; jangan menimpa timestamp audit. Gunakan sequence/revision deterministik untuk tie dan bukan mengandalkan timestamp saja. |
| Konsekuensi bila ditunda | Cutoff batch, eligibility sebelum/sesudah initial dan urutan supplemental dapat berbeda antar proses. |
| Dependensi | OD-07, OD-08, OD-13, OD-15. |
| Bukti diperlukan | Owner menetapkan sumber waktu/precision; uji timezone, clock skew, equality boundary, retries dan failover pada target. |
| Status saat ini | `READY FOR OWNER REVIEW` untuk prinsip pemisahan waktu; detail runtime perlu validasi. |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-15 — Year-code mapping

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | Baseline menetapkan `2026/2027 → 2627`, tetapi belum menyelesaikan aturan parser untuk label invalid/ambiguous, tahun non-konsekutif, century rollover atau perubahan tahun ajaran. |
| Pilihan | (A) Dua digit akhir dari tahun awal + tahun akhir untuk label konsekutif; (B) mapping table owner-maintained dari academic-year ID ke year_code; (C) aturan lain yang ditetapkan owner. |
| Kelebihan/risiko | A transparan dan cocok dengan contoh, tetapi collision/century rollover perlu ditangani. B menghindari parsing ambigu tetapi menambah registry konfigurasi dan lifecycle. |
| Rekomendasi | Gunakan mapping explicit dari identitas tahun ajaran ke year_code dan validasi format/consecutive-year; fixture wajib mencakup `2026/2027 → 2627`, label salah, duplikasi dan batas century. Jangan mengubah year_code yang sudah terkait dengan issuance. |
| Konsekuensi bila ditunda | Kunci cohort dan tuple allocator belum dapat dibentuk secara deterministik; legacy mapping tidak boleh ditebak. |
| Dependensi | OD-01, OD-06, OD-07, OD-14, OD-17. |
| Bukti diperlukan | Owner menyetujui grammar, aturan rollover/duplicate/correction; test pada label tahun yang sah dan tidak sah. |
| Status saat ini | `READY FOR OWNER REVIEW` untuk contoh dan prinsip no-rewrite; aturan lengkap perlu disahkan. |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-16 — Delete, restore, force-delete dan relink

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | `siswa.nis` unique row bukan tombstone permanen; force delete dapat menghapus row yang mempertahankan nilai itu. FK User–Siswa dapat `SET NULL`; placement dapat cascade. |
| Pilihan | (A) Soft delete saja; (B) izinkan force delete tetapi simpan registry/ledger tombstone permanen; (C) restore row yang sama; (D) relink identitas/akun dengan proses verifikasi. |
| Kelebihan/risiko | A sederhana tetapi retention/privasi perlu dipertimbangkan. B menjaga non-reuse, tetapi butuh registry independen dari Siswa. C mempertahankan NIS lama tetapi memerlukan konflik check. D menangani koreksi akun tetapi berisiko account takeover jika tanpa bukti. |
| Rekomendasi | Registry/slot terbit tidak pernah ikut cascade/hard delete. Restore Siswa yang sama mempertahankan NIS lama; recreate/relink harus mencari record registry dan melalui otorisasi/bukti. Jangan membebaskan sequence. Owner menentukan retention dan pejabat relink. |
| Konsekuensi bila ditunda | Delete/restore dapat menghilangkan satu-satunya unique owner NIS atau merusak login; jalur tersebut harus diblokir/ditahan sebelum aktivasi. |
| Dependensi | OD-05, OD-11, OD-12, OD-18. |
| Bukti diperlukan | Aturan retention/restore/relink; tests FK/trigger, force-delete tombstone, restore, duplicate recreation dan direct SQL. |
| Status saat ini | `READY FOR OWNER REVIEW` |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

### OD-17 — Satu initial batch per cohort

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | Baseline mensyaratkan satu initial publication per cohort `year_code`. Dua publisher bersamaan tidak boleh menghasilkan batch awal ganda. Bentuk constraint bergantung engine. |
| Pilihan | (A) Unique state/initial batch per cohort + cohort row lock; (B) service-only guard; (C) engine-specific constraint selain lock sebagai pertahanan berlapis. |
| Kelebihan/risiko | A/C memberi defense-in-depth, tetapi perlu bukti engine mendukung desainnya. B lebih sederhana tetapi dapat dilewati oleh writer lain dan race condition. |
| Rekomendasi | Lock cohort dengan urutan global deterministik ditambah database uniqueness/constraint yang tersedia di target. Retry setelah batch sukses harus mengembalikan batch yang sama, bukan membuat initial kedua. |
| Konsekuensi bila ditunda | Publication ganda dan alokasi ulang mungkin terjadi; schema dan concurrency gate belum dapat diselesaikan. |
| Dependensi | OD-01, OD-12, OD-13, OD-15. |
| Bukti diperlukan | Pilihan constraint per engine; test dua publisher serentak, retry, rollback, idempotency dan competing locks pada database disposable. |
| Status saat ini | `TECHNICAL VALIDATION REQUIRED` |
| Owner decision / alasan | Menunggu bukti target-engine; prinsip satu initial batch dapat dikonfirmasi sekarang, mekanisme final belum. |
| Approval status | `PENDING` |

### OD-18 — Role dan separation of duties

| Elemen | Resolusi |
|---|---|
| Status sebelumnya | `READY FOR OWNER REVIEW` |
| Masalah/konteks | Audit otorisasi menemukan role dan middleware lintas modul yang perlu ditinjau; belum ada role NIS spesifik yang disahkan. Publish, reservation, correction dan audit memiliki risiko berbeda. |
| Pilihan | (A) Satu role dapat prepare/publish; (B) prepare dan approve/publish dipisah; (C) approval terpisah hanya untuk reservation/correction, publication berdasarkan permission; (D) role lain yang ditentukan owner. |
| Kelebihan/risiko | A sederhana tetapi separation lemah. B memberi kontrol terbaik namun membutuhkan lebih banyak aktor. C berimbang tetapi perlu batas tindakan yang jelas. |
| Rekomendasi | Role-based permission terpisah untuk prepare/preview, publish, reservation, correction dan read-only audit. Reservation/correction memerlukan approver berbeda dari requester. Jangan mengasumsikan nama role/aktor; owner menetapkan mapping ke peran sekolah dan semua action dicatat. |
| Konsekuensi bila ditunda | Endpoint sensitif tidak dapat diberi authorization contract yang dapat diuji; publication tidak boleh diaktifkan. |
| Dependensi | OD-05, OD-06, OD-12, OD-18; audit otorisasi yang lebih luas tidak otomatis memberi kewenangan NIS. |
| Bukti diperlukan | Matriks role × action disahkan; tests izin allow/deny, separation, actor snapshot dan audit. |
| Status saat ini | `READY FOR OWNER REVIEW` |
| Owner decision / alasan | Menunggu owner. |
| Approval status | `PENDING` |

## 7. Database Privilege Recommendation

Temuan grant global Tahap 2A.6 berlaku untuk akun koneksi **lokal** yang diperiksa, bukan klaim tentang account produksi. Jangan menjalankan perubahan grant dalam tahap keputusan ini.

| Jenis akun | Tanggung jawab yang disarankan | Pembatasan |
|---|---|---|
| Application runtime | Operasi runtime pada schema yang diperlukan | Tidak memiliki global `*.*`, `GRANT OPTION`, hak administratif, atau DDL umum. Registry/slot immutable tidak boleh dapat diubah bebas oleh jalur runtime yang bukan issuer. |
| Migration/deployment | Perubahan schema saat release yang diotorisasi | Credential terpisah; akses tidak digunakan oleh request web/queue runtime; dibatasi pada database dan window deployment. |
| DBA/break-glass | Administrasi, recovery dan operasi langsung yang disetujui | Akses terkontrol, audit, approval dan pencatatan alasan; bukan credential aplikasi. |

**Pertahanan immutability yang disarankan:** unique key untuk NIS exact, registry/slot permanen yang tidak bergantung pada row `siswa`, FK yang tidak cascade-delete ledger, database privileges/trigger sesuai dukungan engine, service authorization, audit append-only dan acceptance tests yang mencoba jalur aplikasi serta direct SQL. Model event atau controller validation tidak cukup sebagai satu-satunya kontrol.

Uji target wajib membuktikan interaksi trigger dengan `ON DELETE SET NULL`, serta memastikan DML yang diizinkan runtime tidak dapat mengganti/menghapus issuance. DBA tetap dapat memiliki kuasa administratif; risiko itu dimitigasi melalui separation, audit dan recovery drill, bukan diklaim mustahil.

## 8. Comparator Decision Analysis

### Pilihan dan gate

1. **ICU Collator / locale Indonesia:** hanya layak jika target PHP runtime memiliki actual locale Indonesia yang diharapkan, ICU data/version tercatat, dan golden fixture lulus.
2. **Library collation dengan data dipaketkan:** dapat mengurangi ketergantungan OS, tetapi dependency, data version, lisensi dan pembaruan harus direview.
3. **Comparator aturan eksplisit:** dapat portabel bila aturan dan Unicode data dipin; owner harus menentukan urutan karakter/aksen/tanda baca, bukan mengandalkan intuisi.
4. **Fail closed:** pilihan wajib selama comparator belum diverifikasi.

### Fixture minimum untuk sign-off

Golden fixture harus berisi pasangan/kelompok yang mencakup:

- kapitalisasi;
- bentuk Unicode composed/decomposed dan NFC;
- aksen serta variasi penulisan;
- whitespace awal/akhir, spasi berulang, tab/non-breaking space sesuai aturan;
- punctuation;
- gelar/prefix tanpa menghapusnya secara diam-diam;
- nama identik dengan tie-break `siswa.id`;
- comparator antisymmetry/transitivity dan keluaran urutan lengkap.

Owner mengesahkan **expected order**. Test harus dijalankan pada setiap runtime yang didukung (CLI dan web SAPI) dan mengassert exact order serta comparator metadata/fingerprint. Actual `root` di lokal bukan bukti comparator Indonesia yang aman; jangan menerbitkan NIS sebelum seluruh gate lulus.

## 9. Immutability and Ledger Governance

Ledger/registry adalah kebutuhan arsitektural untuk mempertahankan non-reuse sesudah force delete; schema lokal saat ini belum memiliki NIS registry NIS permanen menurut Tahap 2A.6. Usulan desain sebelumnya memisahkan sequence, slot, registry, batch dan attempt.

Kontrol minimum yang perlu diterima sebelum implementasi:

- slot sequence memiliki state yang eksplisit (misalnya reserved/issued) dan transisi diaudit;
- reservation tidak mengubah slot tanpa approval/evidence;
- exact NIS legacy tetap disimpan tanpa normalization atau tebakan;
- unique registry menjaga NIS tidak dapat terbit dua kali; sequence slot tidak dihapus atau dialihkan setelah issue;
- registry dan slot tidak terhapus ketika Siswa/User/placement dihapus;
- correction bersifat append-only; koreksi fakta tidak mengganti nomor terbit;
- publication transaction menulis issuance atomik; failed attempt berada pada transaksi terpisah;
- user runtime tidak memiliki hak unrestricted untuk memodifikasi ledger;
- recovery/backup restore diuji pada database disposable sebelum aktivasi.

Ini adalah kriteria rancangan, bukan pernyataan bahwa ledger atau proteksi tersebut sudah dibuat.

## 10. User–Siswa Relationship Decision

Fakta source yang relevan dari audit terdahulu: login menggunakan `siswa.user_id`; model dan schema juga mempunyai `users.siswa_id`; kedua FK nullable dan tidak menjamin konsistensi silang. Snapshot lokal Tahap 2A.6 tidak menunjukkan mismatch yang terukur, tetapi jumlah data yang sedikit tidak cukup untuk mengesahkan relasi.

**Rekomendasi kandidat:** jadikan `siswa.user_id` sebagai canonical karena itulah jalur login yang ada. Persetujuan owner dan reconciliation harus mendahului backfill/deprecation. Selama masa transisi:

1. tentukan satu source of truth;
2. jangan dual-write tanpa mekanisme consistency enforcement;
3. inventaris seluruh reader/writer, termasuk importer dan delete/relink;
4. selesaikan orphan/mismatch secara manual berdasarkan bukti, bukan auto-link;
5. setelah konsisten, enforce one-to-one pada database target bila memungkinkan;
6. deprecate/remove arah legacy dalam migration terpisah dan tes login/recovery.

Tidak ada relasi yang diubah atau akun yang ditautkan pada tahap ini.

## 11. Placement Integrity Decision

Audit lokal mencatat tidak ada anomali placement terukur pada snapshot, tetapi schema aktual tidak menjamin secara unik bahwa satu siswa hanya memiliki satu placement terbuka per tahun. Karena NIS baru diterbitkan setelah placement valid, publisher tetap perlu memvalidasi snapshot pada transaksi dan semua placement writer perlu mengikuti lock protocol.

Sebelum publication, hardblock setidaknya jika:

- siswa tidak mempunyai placement yang sesuai dengan tahun akademik/cohort;
- placement menunjuk kelas/rombel/tingkat yang hilang, inactive, soft-deleted atau tidak konsisten;
- lebih dari satu placement memenuhi syarat untuk siswa/tahun;
- status PPDB, relasi Siswa atau entry history bertentangan;
- placement masuk grade tidak dapat dikaitkan dengan entry grade yang disahkan;
- perubahan placement terjadi tanpa cohort lock/revision yang disepakati.

Daftar tersebut adalah rekomendasi acceptance behavior; owner harus menetapkan detail status/tanggal dan pengecualian OD-09. Snapshot saat ini tidak menggantikan hardblock atau concurrency tests.

## 12. Testing vs Operational Environment Boundary

- **Development/testing:** bukti MySQL 8.0.30 dan PHP 8.2.29 adalah fakta lokal. Database lokal yang berisi sedikit data digunakan hanya untuk pemeriksaan read-only, bukan benchmark atau simulasi produksi.
- **Disposable test database:** gunakan untuk migration, FK/trigger, unique, lock contention, rollback, retry, race, direct SQL denial dan restore drill. Gunakan data sintetis; jangan memodifikasi snapshot audit.
- **CI:** pin versi database/PHP/ICU yang disetujui; catat artifact/lockfile dan jalankan golden sorting serta concurrency suite.
- **Operasional masa depan:** sebelum mulai digunakan, owner/Ops menetapkan environment target, least privilege, backup/restore, observability, capacity dan prosedur activation/rollback. Tidak ada deployment produksi saat ini menurut brief, jadi tidak ada bukti compatibility produksi.
- **Kapasitas:** jangan mengambil angka batch dari satu record atau database testing kecil. Ukuran batch hanya setelah benchmark data sintetis pada resource/engine target.

## 13. Owner Decision Register

Register ini adalah ringkasan operasional dari §6. Status bukan approval. Kolom `Owner decision` sengaja menunggu jawaban owner.

| Decision ID | Current status | Recommended option | Alternative options | Owner decision | Decision rationale / dependencies | Evidence required | Approval status |
|---|---|---|---|---|---|---|---|
| OD-01 | READY FOR OWNER REVIEW | MySQL 8.0.30 sebagai baseline dev/test saja | Engine lain untuk target; menunda pemilihan target operasional | Menunggu owner | Lokal terverifikasi, tidak mewakili deployment; terkait OD-12/13/17 | Scope engine tertulis; schema/constraint/lock compatibility suite | PENDING |
| OD-02 | TECHNICAL VALIDATION REQUIRED | PHP 8.2+ sesuai manifest, extension wajib, runtime CLI/web parity | Single SAPI/build; atau matrix multi-runtime | Menunggu owner | Comparator sensitif pada runtime; terkait OD-03 | Web SAPI, `php.ini`, extension, ICU/actual locale, CI matrix | PENDING |
| OD-03 | TECHNICAL VALIDATION REQUIRED | Comparator terpilih setelah golden order disetujui; fail closed sebelum itu | ICU, library dengan data pinned, aturan eksplisit, tahan publication | Menunggu owner | Urutan NIS immutable; terkait OD-02/08 | Golden fixture dan exact order parity lintas runtime | PENDING |
| OD-04 | READY FOR OWNER REVIEW | Draft Siswa terpisah dari akun; provisioning setelah NIS tersedia | Akun nonaktif sebelum publish; identifier sementara (tidak direkomendasikan) | Menunggu owner | Selaras dengan login berbasis NIS; terkait OD-10/11/16 | Workflow provisioning/login dan account conflict tests | PENDING |
| OD-05 | READY FOR OWNER REVIEW | Correction append-only, requester/approver terpisah | Satu pejabat; exception administratif luar aplikasi | Menunggu owner | NIS/slot issue tidak pernah ditulis ulang; terkait OD-06/16/18 | Role/evidence/correction audit tests | PENDING |
| OD-06 | TECHNICAL VALIDATION REQUIRED | Fail-closed; reservation hanya kasus disetujui | Reconcile only; reservation berotorisasi; remap legacy (ditolak baseline) | Menunggu owner | Data provenance belum terbukti; terkait OD-01/12/15/17 | Inventory legacy dan disposition collision | PENDING |
| OD-07 | READY FOR OWNER REVIEW | Bukti resmi + verification/exception ter-audit | Arsip sekolah; verifikasi manual dengan approver; kombinasi | Menunggu owner | Mencegah entry year/grade ditebak; terkait OD-09/14/15/18 | Daftar bukti dan pejabat pengesah | PENDING |
| OD-08 | READY FOR OWNER REVIEW | Kronologis entry time, lalu comparator nama dan ID | Sort nama setiap batch; aturan owner lain | Menunggu owner | Nomor batch supplemental permanen; terkait OD-03/14 | Timestamp tie/boundary dan golden order tests | PENDING |
| OD-09 | READY FOR OWNER REVIEW | Definisi placement minimum eksplisit; hardblock konflik | Exception terbatas dan terotorisasi; aturan alternatif | Menunggu owner | Eligibility harus deterministik; terkait OD-07/15/18 | Placement fixtures, resolver rules, concurrency tests | PENDING |
| OD-10 | READY FOR OWNER REVIEW | Tidak auto-merge/rename; hardblock dan rekonsiliasi | Username alternatif; perubahan username existing dengan approval | Menunggu owner | Mencegah akses akun yang salah; terkait OD-04/11/16/18 | Username collision inventory dan authorization tests | PENDING |
| OD-11 | TECHNICAL VALIDATION REQUIRED | `siswa.user_id` kandidat canonical; no backfill sebelum reconciliation | `users.siswa_id`; consistency-enforced dual direction; staged deprecation | Menunggu owner | Login menggunakan `siswa.user_id`; schema tidak enforce one-to-one; terkait OD-01/04/12/16 | Reader/writer map, aggregate reconciliation, FK/unique/login tests | PENDING |
| OD-12 | TECHNICAL VALIDATION REQUIRED | Pisahkan runtime, migration/deployment, DBA; least privilege | Shared broad account (tidak direkomendasikan) | Menunggu owner/DBA | Grant lokal global; critical untuk ledger; terkait OD-01/13/17 | Approved grant matrix, effective grants, direct SQL/trigger tests | PENDING |
| OD-13 | TECHNICAL VALIDATION REQUIRED | All-or-nothing initial transaction; tetapkan limit setelah benchmark | Atomic staging/chunking dengan proof setara; angka tanpa benchmark (ditolak) | Menunggu owner | Tidak ada capacity evidence; terkait OD-01/12/17 | Synthetic capacity, rollback/retry/restore/lock tests | PENDING |
| OD-14 | READY FOR OWNER REVIEW | UTC technical timestamps + separate administrative effective time | Trusted application clock; database clock; explicit hybrid | Menunggu owner | Cutoff dan chronological ordering; terkait OD-07/08/13/15 | Precision/timezone/skew/equality/failover tests | PENDING |
| OD-15 | READY FOR OWNER REVIEW | Explicit mapping table/validated parser; preserve `2026/2027 → 2627` | Two-year suffix parser; alternative owner mapping | Menunggu owner | Cohort key tidak boleh ambiguous; terkait OD-01/06/14/17 | Grammar, rollover, duplicates, correction fixtures | PENDING |
| OD-16 | READY FOR OWNER REVIEW | Permanent ledger; restore same NIS; relink only with evidence/approval | Soft-delete only; controlled force-delete + tombstone | Menunggu owner | Unique siswa row alone cannot prevent reuse; terkait OD-05/11/12/18 | Retention/relink policy, FK/trigger/delete/restore tests | PENDING |
| OD-17 | TECHNICAL VALIDATION REQUIRED | Cohort lock + DB uniqueness + idempotent batch retry | Service-only guard (insufficient alone); engine-specific equivalent | Menunggu owner | Constraint depends on target engine; terkait OD-01/12/13/15 | Schema test; two-publisher race, retry and deadlock tests | PENDING |
| OD-18 | READY FOR OWNER REVIEW | Separate prepare/publish/reserve/correct/audit permissions; distinct approver for exception | One role; split only sensitive actions; owner role matrix | Menunggu owner | Tidak ada NIS-specific role approval; terkait OD-05/06/12/16 | Signed role/action matrix and allow/deny tests | PENDING |

**Status prior:** OD-01/02/03/06/11/12/13 tercatat `OPEN`; OD lainnya `READY FOR OWNER REVIEW` pada register 2A.5. Tahap 2A.6 memperbarui bukti lokal tetapi tidak mengubah keputusan apa pun menjadi `APPROVED`.

## 14. Technical Validation Register

| ID | Validasi wajib | Bukti saat ini | Cara penutupan | Status |
|---|---|---|---|---|
| TV-01 | Baseline database dan feature support | MySQL 8.0.30/InnoDB lokal terbukti; tidak ada target produksi menurut brief | Owner setujui engine/version per scope; migration, FK, unique, trigger, CHECK, isolation dan upsert diuji pada disposable database | PARTIALLY VERIFIED |
| TV-02 | PHP/CLI/web runtime | CLI dan `artisan serve` PHP 8.2.29; Apache handler belum terbukti | Periksa SAPI/ini/extensions/PDO pada target web; samakan atau test matrix | PARTIALLY VERIFIED |
| TV-03 | Comparator Indonesian dan golden order | ICU 71.1 lokal; requested `id-ID` actual `root`; fixture sintetis bukan golden | Pilih strategi, owner sahkan expected order, lintas runtime exact-order test | UNVERIFIED |
| TV-04 | Schema constraints & delete behavior | `siswa.nis` unique; kedua FK User–Siswa tidak unique; placement open tidak diberi unique guard yang memadai; trigger terpilih tidak ditemukan | Uji schema NIS future dan interaksi FK/trigger/SET NULL pada engine target | PARTIALLY VERIFIED |
| TV-05 | Least privilege dan ledger direct SQL | Grant lokal global dengan DML/DDL/admin/GRANT OPTION | Role matrix, effective grants, deny update/delete issued slot tests; tidak ada perubahan grant pada tahap ini | UNVERIFIED FOR APPROVED DESIGN |
| TV-06 | Legacy NIS/provenance/collision | Snapshot kecil tanpa anomali struktural terukur; bukan inventaris historis lengkap | Inventory read-only pada data yang owner/DBA nyatakan sebagai dataset reconciliation; review conflict terotorisasi tanpa PII berlebih | PARTIALLY VERIFIED |
| TV-07 | User–Siswa & username reconciliation | Snapshot lokal tidak menunjukkan mismatch/duplikat; relasi code memiliki dua FK | Reader/writer map lengkap, query agregat pada dataset resmi, manual disposition sebelum backfill | PARTIALLY VERIFIED |
| TV-08 | Cohort/sequence locking & recovery | Isolation lokal `REPEATABLE-READ`; belum ada contention test | Disposable MySQL: concurrent initial, same sequence, lock order, deadlock/retry, rollback dan uncertain commit | UNVERIFIED |
| TV-09 | Capacity, backup dan restore | Tidak ada benchmark/restore drill; data lokal bukan representatif | Synthetic volume benchmark dan restore drill pada resource/engine target | UNVERIFIED |
| TV-10 | Full writer/authorization coverage | Jalur utama PPDB, TU, import, placement, delete diketahui; external writer belum tertutup | Telusuri route/controller/service/jobs/commands/raw SQL dan role matrix sebelum activation; semua writer menuju protocol tunggal | PARTIALLY VERIFIED |
| TV-11 | Placement/PPDB eligibility consistency | Snapshot lokal kecil tidak memperlihatkan konflik tertentu | Uji resolver untuk accepted/rereg, contradictory links, missing/multiple placement, transfer, pre/post cutoff | PARTIALLY VERIFIED |
| TV-12 | Audit/operational activation plan | Desain mencakup batch/attempt/recovery; belum ada service atau activation | Dry-run, privacy-safe observability, retry idempotency, backup/restore and forward-fix plan | UNVERIFIED |

## 15. Unresolved Risks

| ID | Risiko | Dampak | Gate |
|---|---|---|---|
| UR-01 | Actual locale `root` atau runtime drift menghasilkan order yang tidak disetujui | NIS initial permanen dapat diberikan dengan urutan salah | OD-02/03 dan TV-02/03 lulus sebelum publication |
| UR-02 | Local grant terlalu luas; jalur SQL dapat bypass immutability/service | Ledger bisa diubah oleh runtime account atau proses yang salah | OD-12, grant separation dan direct SQL tests |
| UR-03 | NIS legacy berprovenance unknown atau soft-deleted tidak terinventaris lengkap | Collision, incorrect high-water atau reuse | OD-06 dan TV-06 |
| UR-04 | Dua relasi User–Siswa tidak canonical/one-to-one | Login salah, orphan, relink berbahaya | OD-11 dan TV-07 |
| UR-05 | Placement tidak punya unique guard dan dapat berubah di luar lock | Issuance untuk kelas/cohort yang salah atau duplicate placement | OD-09, TV-04/08/11 |
| UR-06 | Force-delete menghilangkan NIS row sebagai satu-satunya uniqueness owner | NIS dapat digunakan kembali jika tidak ada ledger | OD-12/16 dan registry/direct SQL tests |
| UR-07 | Tidak ada capacity/recovery benchmark | Timeout, lock lama, hasil commit ambigu atau retry ganda | OD-13, TV-08/09 |
| UR-08 | Role publish/reserve/correct belum disepakati | Unauthorized issuance atau correction | OD-18 dan TV-10 |
| UR-09 | Status PPDB, student link, entry history dan placement dapat bertentangan | Kandidat tertinggal/masuk cohort salah | OD-07/09/15 dan resolver integration tests |
| UR-10 | Local testing data kecil dianggap mewakili sistem operasional | Batas batch atau readiness diklaim tanpa dasar | Gunakan dataset sintetis dan environment disposable; no production extrapolation |

## 16. Recommended Approval Sequence

Tahapan berikut adalah urutan tata kelola, bukan otorisasi coding atau perubahan database.

1. **Owner mengonfirmasi baseline:** format, sequence lintas rombel, immutable/no reuse, placement prerequisite, satu initial batch dan fail-closed. Jika baseline diubah, catat perubahan serta efek ke desain.
2. **Bahas paralel policy yang dapat direview:** OD-04/05/07–10/14–16/18. Isi keputusan owner secara eksplisit dan tetap pending hingga disetujui.
3. **Tentukan scope environment:** OD-01 (dev/test vs future operational) dan OD-02; kumpulkan validasi runtime/engine yang sesuai scope.
4. **Tutup blocker permanen:** OD-03 dengan golden comparator; OD-06 dengan legacy inventory; OD-11 dengan reconciliation; OD-12 dengan least privilege; OD-13 dengan benchmark; OD-17 dengan engine-specific enforcement.
5. **Konfirmasi dependency antar policy:** OD-08 bergantung comparator dan waktu; OD-09 bergantung evidence/placement; OD-10 bergantung canonical account relation; OD-16 bergantung registry dan relink authority.
6. **Hanya setelah keputusan dan bukti selesai**, owner dapat memulai tahap implementasi yang cakupannya disetujui. Setiap migration/service tetap memerlukan review dan test terpisah; laporan ini tidak memberi izin menjalankannya.

## 17. Readiness Verdict

# PARTIALLY READY FOR OWNER APPROVAL

**Alasan:** semua OD-01–OD-18 telah disajikan dengan masalah, pilihan, pro/risiko, rekomendasi, konsekuensi, dependency dan bukti yang belum ada. Dengan demikian, paket layak dibaca dan dibahas owner. Namun, beberapa keputusan fundamental belum aman untuk disahkan sebagai kontrak implementasi tanpa validasi teknis; khususnya comparator (OD-03), runtime (OD-02), legacy collision (OD-06), relasi canonical (OD-11), privilege/immutability (OD-12), ukuran/atomicity (OD-13), serta one-initial guard (OD-17).

Tidak ada `Owner decision` yang telah diisi dan seluruh approval status tetap `PENDING`. Verdict ini hanya menyatakan sebagian paket siap untuk owner review; **bukan READY FOR IMPLEMENTATION**, bukan bukti migration siap, bukan bukti data legacy aman, dan bukan hasil concurrency test.

## 18. Recommended Next Stage

1. Owner mengisi/menyetujui atau menolak OD secara eksplisit; keputusan yang ditunda diberi status `DEFERRED` beserta syarat dan tanggal peninjauan, bukan diasumsikan.
2. Tutup validasi teknis di TV-01–TV-12 memakai runtime/engine yang ditetapkan dan database disposable dengan data sintetis.
3. Sahkan golden comparator sebelum ada initial publication; bila tidak ada comparator yang lulus, terus fail closed.
4. Jalankan legacy inventory read-only hanya pada dataset yang pemilik data/DBA sahkan sebagai sumber rekonsiliasi; jangan ubah/backfill selama audit.
5. Jangan memulai migration, service, workflow integration, grant update, atau activation sampai blocker komponen bersangkutan ditutup dan tahap implementasi diminta/diotorisasi secara terpisah.

**Batas akhir tahap:** laporan ini menyelesaikan persiapan keputusan. Berhenti di sini; tidak melanjutkan implementasi maupun Tahap 2A.8.
