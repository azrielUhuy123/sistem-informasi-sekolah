# TAHAP 2A.12R — ENGINEERING BASELINE REFINEMENT

**Sistem:** SINERGI-MJ  
**Baseline induk:** [TAHAP 2A.12 Engineering Baseline](./TAHAP_2A_12_ENGINEERING_BASELINE.md)  
**Status:** Refinement desain dokumenter; technical validation masih `PENDING`; school policy confirmation masih `PENDING`; implementation authorization `NOT AUTHORIZED`.

## 1. Tujuan dan Batas

Refinement ini memperjelas OD-08 supplemental ordering, risiko inventaris NIS historis, tahapan migrasi canonical User–Siswa OD-11, serta privilege gate OD-12. Baseline 2A.12 tetap menjadi dokumen induk dan tidak ditimpa. Ketentuan yang diperjelas di sini menjadi errata/refinement untuk topik terkait; bagian lain pada baseline 2A.12 tidak berubah.

Pekerjaan ini hanya dokumentasi dan audit read-only. Tidak ada source code, migration, konfigurasi, dependency, grant, database, atau data yang diubah. Tidak ada query database, test, migration, seeder, atau aktivasi generator yang dilakukan. Pilihan engineering bukan persetujuan formal sekolah atau bukti technical validation.

OD-01–OD-18 tetap `PENDING`; BL-01–BL-10 dan SRC-GAP-01/02 tetap `OPEN`. Historical values yang tidak tersedia tidak direkonstruksi atau diasumsikan.

## 2. OD-08 — Supplemental Ordering

### 2.1 Sorting contract

Tuple supplemental yang dipilih:

```text
official_entry_at → name comparator → siswa.id
```

Urutan penerapannya:

1. `official_entry_at` menentukan urutan kronologis utama.
2. Jika timestamp sama, gunakan name comparator sesuai OD-03.
3. Jika nama dianggap sama oleh comparator, gunakan `siswa.id` sebagai final deterministic tie-break.

`permanent cohort ordinal` tetap disimpan sebagai identitas urutan administratif/audit yang immutable. Ordinal tidak menjadi sorting key, tidak dipakai untuk mengurutkan kandidat supplemental, tidak dihitung ulang, dan nilainya tidak berubah. Tidak ada implikasi bahwa perubahan ordinal boleh mengubah NIS.

Comparator tetap fail-closed bila actual locale atau runtime tidak memenuhi OD-03. Tidak boleh ada fallback diam-diam ke database collation atau pembanding lain. Hasil comparator harus konsisten pada runtime yang disetujui.

### 2.2 Initial ordering dan immutability

- Initial batch tetap menggunakan alphabetical ordering sesuai kontrak comparator OD-03 dan golden ordering yang harus ditetapkan/divalidasi.
- Refinement supplemental tidak mengubah kontrak initial batch, cohort eligibility, publication atomicity, ataupun guard satu initial batch per cohort.
- Nama yang berubah setelah penerbitan tidak mengubah NIS yang telah diterbitkan dan tidak memicu alokasi ulang.
- NIS dan slot terbit tetap immutable serta tidak pernah digunakan ulang.
- Ordinal administratif tersimpan untuk audit; perubahan nama atau urutan supplemental tidak menghitung ulang ordinal.

### 2.3 Acceptance criteria OD-08

OD-08 dapat dinyatakan memenuhi desain hanya jika:

- sorting key benar-benar `official_entry_at`, comparator nama, lalu `siswa.id`, dalam urutan tersebut;
- `siswa.id` membuat hasil deterministic ketika comparator menganggap nama sama;
- comparator actual locale/runtime tervalidasi dan fail-closed saat tidak memenuhi baseline;
- ordinal permanen tersimpan untuk audit, tidak diubah, dan tidak ikut sorting;
- perubahan nama setelah NIS terbit tidak mengubah NIS atau slot tersebut;
- initial batch tetap memakai alphabetical contract tersendiri, dengan expected golden ordering;
- test menguji timestamp berbeda/sama, nama comparator-equal, deterministic ID tie-break, actual-locale mismatch, perubahan nama, dan pemisahan initial vs supplemental ordering.

Tidak ada acceptance test dijalankan pada tahap ini.

### 2.4 Traceability dan batas semantik

Refinement ini menggantikan hanya tuple OD-08 pada baseline 2A.12. Ketentuan terkait OD-03 comparator, OD-14 timestamp semantics, OD-15 cohort/year mapping, OD-07 entry evidence, OD-09 placement eligibility, dan OD-13 atomic visibility tetap berlaku.

Pertanyaan yang belum ditetapkan oleh tuple: kapan tepatnya `official_entry_at` menjadi immutable, bagaimana equality/precision/UTC diputuskan, dan pada titik mana nama untuk supplemental candidate dibaca/disnapshot. Jangan menyimpulkan bahwa rename sebelum publication memiliki atau tidak memiliki urutan historis tertentu tanpa keputusan/kontrak lebih lanjut. Rename setelah NIS terbit tetap tidak mengubah identitas/NIS yang sudah diterbitkan.

**Status OD-08:** `DESIGN BASELINE REFINED`; `TECHNICAL VALIDATION PENDING`; formal school policy confirmation tetap `PENDING`.

## 3. OD-11 — Canonical User–Siswa Migration Path

`siswa.user_id` tetap canonical. `users.siswa_id` adalah kolom legacy/redundant dan tidak boleh langsung dihapus sebelum semua dependency serta data selesai direkonsiliasi.

| Tahap | Tindakan wajib | Gate sebelum lanjut | Rollback / forward-fix requirement |
|---:|---|---|---|
| 1 | Inventaris semua reader dan writer untuk kedua FK, termasuk login, model relations, controllers, commands, imports, jobs/worker, reports, exports, direct SQL, scheduler, dan integrasi luar repository yang diketahui. Catat pemilik dan callsite. | Writer/reader map ditinjau; jalur eksternal yang tidak diketahui dicatat sebagai risiko, bukan diasumsikan tidak ada. | Batalkan perubahan tahap ini bila inventaris belum lengkap; perbaiki map sebelum schema/data transition. |
| 2 | Rekonsiliasi setiap pasangan User–Siswa pada sumber data yang disetujui. Tentukan kandidat pasangan dari bukti identitas/ownership; jangan auto-link atau backfill berdasarkan nama saja. | Data source, cakupan, provenance, authority, dan aturan disposition disetujui. | Simpan snapshot/hasil rekonsiliasi yang dapat dipulihkan; konflik tetap unresolved dan tidak dimutasi. |
| 3 | Validasi orphan, duplicate, mismatch silang, relasi satu-ke-banyak, akun tanpa pasangan, dan account ownership sebelum perubahan. | Setiap record memiliki disposition; tidak ada konflik yang tertutup dengan auto-merge/auto-link. | Hentikan cutover bila ada unresolved record yang termasuk scope; koreksi hanya melalui approved reconciliation dan audit. |
| 4 | Migrasikan seluruh application readers ke `siswa.user_id` sebagai satu-satunya sumber canonical. Verifikasi setiap callsite dan deploy/read compatibility sebelum menghentikan legacy writer. | Read-path parity dan login/authorization regression evidence tersedia; tidak ada reader aktif yang bergantung pada `users.siswa_id`. | Pertahankan `users.siswa_id` selama transisi. Bila reader baru gagal, rollback aplikasi ke versi sebelumnya hanya sebelum data cutover yang tidak dapat dipulihkan; selain itu gunakan forward-fix dengan kolom legacy tetap tersedia. |
| 5 | Hentikan seluruh writer yang mengubah `users.siswa_id`. Jangan mengandalkan dual-write tanpa batas. Bila masa transisi memerlukan writer compatibility, tetapkan fase dan batas pemutusnya sebelum cutover. | Writer inventory menunjukkan tak ada writer aktif; telemetry/audit atau deployment evidence mendukung penghentian. | Pertahankan kolom legacy tetapi read-only. Jika dependency writer ditemukan, forward-fix dengan menangani writer tersebut; jangan mengaktifkan kembali dual ownership secara diam-diam. |
| 6 | Tegakkan one-to-one pada canonical `siswa.user_id` melalui unique constraint dan referential integrity/FK setelah data bersih. | Preflight duplicate/orphan bersih pada target; migration/rollback behavior dan DB engine semantics tervalidasi. | Migration plan harus menyediakan rollback yang aman sebelum constraint menjadi authoritative; jika data atau external writes membuat rollback tidak aman, gunakan forward-fix dan jangan drop legacy column. |
| 7 | Hapus `users.siswa_id` hanya melalui migration terpisah setelah seluruh reader/writer, data reconciliation, deployment versions, jobs, reports, and integrations terbukti tidak lagi memerlukannya. | Tidak ada legacy dependency; backup/restore, schema deployment ordering, dan fallback/forward-fix disetujui. | Drop-column rollback hanya aman bila data lama masih tersedia dalam backup/recoverable snapshot dan writes tidak divergen. Jika syarat itu tidak terpenuhi, gunakan forward-fix; jangan menjanjikan rollback semu. |

Setiap tahap harus memiliki migration/deployment sequencing, preflight, observability, recovery owner, dan keputusan rollback vs forward-fix sebelum pelaksanaan. Penghapusan kolom bukan bagian dari tahap reader migration dan tidak boleh dilakukan dalam migration yang sama dengan cutover awal.

**Status OD-11:** canonical choice tetap `siswa.user_id`; tahapan desain `COMPLETE`; data reconciliation, callsite proof, schema changes, dan acceptance validation belum dilakukan. `TECHNICAL VALIDATION PENDING`.

## 4. Legacy NIS Risk dan Collision Gate

Snapshot lokal yang sparse bukan bukti bahwa historical NIS inventory lengkap. Tidak ditemukannya nilai dalam tabel `siswa` saat ini bukan bukti bahwa slot bebas.

Inventory yang diperlukan sebelum allocation mencakup, sejauh tersedia dan dapat diakses secara sah:

- record Siswa aktif;
- soft-deleted records;
- historical backups/snapshots yang relevan;
- bukti administrasi penerbitan NIS dan arsip sekolah;
- sumber lain yang telah diidentifikasi sebagai bukti NIS terbit.

Nilai yang pernah diterbitkan tetapi tidak lagi tercatat pada tabel aktif/deleted harus diperlakukan sebagai potensi collision. Jangan mengarang nilai atau provenance yang tidak tersedia, dan jangan menandai slot bebas hanya karena pencarian snapshot saat ini tidak menemukannya.

Jika collision, kelengkapan inventory, atau historical provenance untuk allocation terdampak belum terselesaikan, allocation cohort/slot yang terdampak fail-closed sampai disposition berbasis evidence disetujui dan dicatat. Tidak ada auto-remap, auto-normalization, auto-skip, auto-reservation, atau reuse. NIS/slot yang telah diterbitkan tidak berubah.

**Status:** safety rule ditegaskan; inventory completeness dan collision disposition tetap belum diverifikasi. BL yang terkait tetap `OPEN`.

## 5. OD-12 — Database Privilege Gate Sebelum Publisher

2A.6 mencatat local database account dengan global privileges pada `*.*`, termasuk DML/DDL/admin dan `WITH GRANT OPTION`. Konfigurasi lokal tersebut tidak dapat menjadi konfigurasi akhir publisher dan tidak membuktikan target grants.

Publisher tidak boleh diaktifkan sebelum seluruh gate berikut dipenuhi:

1. Runtime application account dipisahkan dan hanya memiliki privilege yang diperlukan untuk operasi runtime yang disetujui.
2. Migration/deployment account terpisah dari runtime account dan digunakan hanya untuk deployment/migration sesuai kontrol yang disetujui.
3. DBA/break-glass account terpisah, penggunaannya dibatasi, dan tidak digunakan oleh aplikasi runtime.
4. Direct SQL mutation terhadap NIS registry dan permanent sequence slots dibatasi; enforcement boundary dan authorized mutation path dibuktikan.
5. Effective grants pada target diperiksa dari database, bukan hanya konfigurasi source.
6. Denied mutation tests membuktikan runtime credential tidak dapat melakukan unauthorized update/delete/reuse atas NIS registry atau permanent slot.
7. Break-glass action dapat diaudit dengan actor, waktu, alasan, scope, dan outcome sesuai kebijakan; credential/secret tidak dicatat.
8. Grant design, deployment ordering, recovery, dan privilege ownership ditinjau sebelum publisher/exception activation.

Jika satu gate belum memiliki evidence, publisher dan exception operation tetap nonaktif. Tidak ada grant yang diperiksa/diubah dalam refinement ini dan tidak ada denied-mutation test yang dijalankan.

**Status:** `PREREQUISITE / NOT VERIFIED`; local broad grant adalah evidence risiko, bukan blocker yang ditutup.

## 6. Status dan Batas

- **BASELINE REFINEMENT:** `COMPLETE` untuk ruang lingkup dokumentasi ini.
- **OD-08:** sorting contract diperjelas; technical acceptance `PENDING`.
- **OD-11:** phased migration path ditetapkan; implementasi/data reconciliation `PENDING`.
- **Legacy inventory:** completeness tidak terbukti; collision gate fail-closed.
- **OD-12:** privilege gate menjadi prerequisite publisher; target grants tidak diverifikasi.
- **OD-01–OD-18:** tetap `PENDING`.
- **BL-01–BL-10:** tetap `OPEN`.
- **SRC-GAP-01/02:** tetap `OPEN`.
- **IMPLEMENTATION READINESS:** `NOT READY`.
- **IMPLEMENTATION AUTHORIZATION:** `NOT AUTHORIZED`.
