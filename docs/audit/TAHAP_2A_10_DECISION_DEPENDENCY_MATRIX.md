# TAHAP 2A.10 — DECISION DEPENDENCY MATRIX

**Baseline:** Dependency matrix final Tahap 2A.7R §7, diteruskan pada 2A.8/2A.8R1.  
**Status:** Semua OD `PENDING`; edge adalah prasyarat untuk memfinalkan dependent decision, bukan urutan diskusi yang wajib.

## 1. Matrix OD-01–OD-18

`Depends on` berarti prerequisite untuk memfinalkan keputusan/kontrak dependent. **Unconditional policy dependency** berlaku untuk semua opsi pada keputusan terkait. **Conditional technical dependency** hanya aktif bila opsi yang disebut dipilih owner; edge kondisional tidak berlaku jika opsi itu tidak dipilih. Approval “independent” berarti prinsip owner dapat dibahas/disetujui tanpa menunggu OD lain; technical validation yang relevan tetap dapat menjadi syarat implementasi.

**Reservation decision ownership:** OD-10 adalah satu-satunya sumber keputusan kebijakan apakah username reservation diperbolehkan dan, jika ya, kondisi/alasan, uniqueness/ownership, authority, expiry/release/fencing, revalidation, dan collision handling. OD-04 menentukan account lifecycle dan provisioning mode; bila Hybrid dipilih, OD-04 hanya mengonsumsi reservation policy yang telah diputuskan pada OD-10, tanpa mendefinisikannya ulang.

| OD | Keputusan | Depends on | Enables / downstream | Dependensi teknis | Dependensi governance | Dapat disetujui independen? | Harus mendahului |
|---|---|---|---|---|---|---|---|
| OD-01 | Database/version | — | OD-12, OD-13, OD-14, OD-17 | Engine/version mendasari DDL, locking, clock precision, unique/trigger. | Owner/Ops target scope. | Ya, target direction dapat dipilih; operational approval perlu Ops evidence. | OD-12/13/14/17 sebelum implementation contracts. |
| OD-02 | PHP/runtime/deployment | — | OD-03 | SAPI/extensions/ICU behavior. | Owner menetapkan support matrix; Ops membuktikan deployment. | Ya. | OD-03 comparator sign-off. |
| OD-03 | Comparator/sort | OD-02 | OD-08; initial publication | Golden ordering dan parity pada selected runtime. | Owner menyetujui expected order. | Prinsip dapat direview; final comparator menunggu runtime + fixture. | OD-08 final comparator tie-break. |
| OD-04 | Account lifecycle/provisioning | **Unconditional policy:** OD-10 (reservation policy source), OD-11, OD-18. **Conditional technical:** OD-01, OD-12 only if Hybrid reservation is used under OD-10 policy. | Integrasi login/PPDB; OD-16 | Select synchronous/asynchronous provisioning; Hybrid is a separate dimension. If Hybrid reservation is used, adopt OD-10 policy; target DB uniqueness/locking and privilege enforcement apply. | Login/provisioning mode and canonical relation; reservation policy/approval is owned only by OD-10. | Tidak untuk final account contract; alternatif dapat dibahas paralel. | Final account contract after OD-10/11/18; if Hybrid uses reservation permitted by OD-10, its technical contract also waits on OD-01/12. |
| OD-05 | Correction authority | OD-18 | OD-16; correction workflow | Append-only correction and immutable registry. | Requester/verifier/approver separation. | Tidak final sebelum role authority ditentukan; prinsip correction dapat dibahas. | OD-16 final relink/delete correction workflow. |
| OD-06 | Legacy collision/reservation | OD-12, OD-15, OD-18 | Legacy reconciliation/allocator | Ledger controls, cohort mapping, slot uniqueness. | Reservation approver/evidence authority. | Tidak final sebelum authorization; inventory evidence remains separate prerequisite. | Legacy allocation/reservation handling. |
| OD-07 | Transfer evidence/entry grade | OD-14, OD-15, OD-18 | OD-09; entry history and eligibility | Timestamp and cohort mapping. | Evidence verifier/approver. | Prinsip evidence dapat dibahas; implementable policy menunggu dependencies. | OD-09 and entry-history acceptance. |
| OD-08 | Supplemental ordering | OD-03, OD-14, OD-15 | Supplemental publication | Comparator, official entry/ordinal, year code. | Owner chooses ordering priority. | Tidak final sebelum comparator/time/year mapping; choices dapat didiskusikan. | Supplemental sort contract. |
| OD-09 | Valid placement | OD-07, OD-15, OD-18 | Eligibility resolver/publication | Entry grade/year resolver, placement constraints. | Exception authority. | Tidak final sebelum evidence/cohort/authority. | Placement hardblock and publisher eligibility. |
| OD-10 | Username conflict and reservation policy | **Unconditional policy:** OD-11, OD-18. **Conditional technical:** OD-01, OD-12 only if reservation is permitted and selected for use. | OD-04 consumes this policy when Hybrid reservation is used. | Sole policy decision: whether reservation is allowed; its conditions/reasons, uniqueness/ownership, approval authority, expiry/release/fencing, revalidation, and collision handling. If selected, target DB uniqueness, locking/race safety, privilege enforcement, and coverage across all account writers must be proven. | Owner account/username policy and designated reservation/collision approver. | Tidak final sebelum identity/authority; reservation policy may be decided independently of technical implementation, but use remains blocked until required evidence. | Reservation policy must be decided here before OD-04 can select/use Hybrid reservation; reservation-specific technical contract waits on OD-01/12 only if permitted and selected. |
| OD-11 | Canonical User–Siswa relation | — | OD-04, OD-10, OD-16 | Complete data reconciliation, one-to-one enforcement, auth tests. | Owner selects canonical link; no auto-link. | Ya sebagai policy decision; backfill/enforcement tetap tunggu technical reconciliation. | OD-04/10/16 final integration. |
| OD-12 | DB privileges/direct SQL | OD-01 | OD-06, OD-13, OD-16, OD-17 | Engine-specific effective grants/triggers/constraints. | Role separation and break-glass authority. | Tidak final sebelum target engine; principles may be discussed. | Ledger, reservation, publication and delete enforcement. |
| OD-13 | Batch atomicity/volume | OD-01, OD-12 | OD-17 | Target transaction, benchmark and restore. Staging/chunking is not publication; final visibility/commit must satisfy atomic publication, otherwise additional design/evidence is required before claiming equivalence. | Owner accepts atomicity/operational envelope. | Prinsip all-or-nothing dapat dibahas; numeric cap menunggu benchmark. | OD-17 final batch guard. |
| OD-14 | Timestamp authority/semantics | OD-01 | OD-07, OD-08 | Engine clock precision, skew/failover and ordinal behavior. | Owner defines administrative/effective-time meaning. | Makna administratif dapat dibahas independen; final source/precision depends on OD-01. | OD-07/08 final timeline/order. |
| OD-15 | Year-code mapping | — | OD-06, OD-07, OD-08, OD-09, OD-17 | Parser/label/mapping tests. | Owner defines academic-year cohort. | Ya. | Cohort-dependent mapping rules. |
| OD-16 | Delete/restore/force-delete/relink | OD-05, OD-11, OD-12, OD-18 | Account and registry lifecycle | FK/trigger/restore/relink tests. | Correction/relink authority. | Tidak final sebelum prerequisites. | Delete/relink implementation. |
| OD-17 | One initial batch/cohort | OD-01, OD-12, OD-13, OD-15 | Initial publication | Unique guard, lock, transaction and cohort key. | Owner approves cutoff/readiness/exception. | Tidak final sebelum DB, atomicity and mapping. | Initial publication implementation. |
| OD-18 | Roles/separation of duties | — | OD-04, OD-05, OD-06, OD-07, OD-09, OD-10, OD-16 | Authorization policy implementation/test. | Owner must identify roles; none assumed. | Ya; independent governance decision. | Approval workflow contracts for dependent decisions. |

## 2. Dependency Graph

Edges point from prerequisite to dependent. `--[conditional: ...]-->` marks a technical edge active only if the exact condition is true. All other edges are unconditional policy dependencies:

```text
OD-02 -> OD-03
OD-10, OD-11, OD-18 -> OD-04
OD-01 --[conditional: OD-10 permits reservation AND OD-04 selects Hybrid reservation]--> OD-04
OD-12 --[conditional: OD-10 permits reservation AND OD-04 selects Hybrid reservation]--> OD-04
OD-18 -> OD-05
OD-12, OD-15, OD-18 -> OD-06
OD-14, OD-15, OD-18 -> OD-07
OD-03, OD-14, OD-15 -> OD-08
OD-07, OD-15, OD-18 -> OD-09
OD-11, OD-18 -> OD-10
OD-01 --[conditional: OD-10 reservation policy permits and reservation is selected for use]--> OD-10
OD-12 --[conditional: OD-10 reservation policy permits and reservation is selected for use]--> OD-10
OD-01 -> OD-12
OD-01, OD-12 -> OD-13
OD-01 -> OD-14
OD-05, OD-11, OD-12, OD-18 -> OD-16
OD-01, OD-12, OD-13, OD-15 -> OD-17
```

Independent roots: OD-01, OD-02, OD-11, OD-15, OD-18.

## 3. Edge Rationale and Validation

Unconditional edges retain the Tahap 2A.7R §7 dependency graph. Conditional technical edges refine OD-04/OD-10 only and do not replace or broaden their unconditional policy dependencies:

- OD-04 menunggu OD-10 (sumber kebijakan username/reservation), canonical identity (OD-11), dan authority (OD-18) sebagai unconditional policy dependencies.
- OD-10 memutuskan apakah username reservation diperbolehkan serta ketentuan dan otoritasnya. Jika reservation diizinkan dan dipilih untuk digunakan, OD-10 memerlukan OD-01 (target database) dan OD-12 (database privilege) untuk bukti uniqueness, locking/race safety, dan privilege enforcement.
- Jika OD-04 memilih Hybrid, ia wajib mengadopsi kebijakan reservation OD-10 yang sudah diputuskan; OD-04 tidak membuka keputusan reservation kedua. Hanya jika Hybrid benar-benar menggunakan reservation sesuai kebijakan OD-10, OD-01/OD-12 menjadi conditional technical dependencies OD-04. Jika tidak digunakan, conditional edges tersebut tidak aktif.
- OD-05/06/07/09/10/16 memerlukan governance approval OD-18 untuk pihak yang boleh memproses/menyetujui.
- OD-10 selalu bergantung pada OD-11/OD-18. Hanya jika kebijakan OD-10 mengizinkan dan reservation dipilih untuk digunakan, OD-01/OD-12 menjadi prerequisite teknis untuk target uniqueness, locking/race safety, privilege enforcement, dan cakupan seluruh account writers; tanpa penggunaan reservation tidak ada dependency tambahan itu.
- OD-06 memerlukan privilege/ledger (OD-12) dan cohort mapping (OD-15).
- OD-07/08 memerlukan timestamp semantics OD-14; OD-08 juga memerlukan comparator OD-03 dan year-code OD-15.
- OD-12 bergantung pada database target OD-01.
- OD-13 bergantung pada engine/privilege OD-01/12; OD-17 kemudian bergantung pada atomicity OD-13.
- OD-14 clock source/precision perlu disesuaikan dengan OD-01.
- OD-16 memerlukan correction authority, canonical relation, database protection, dan role governance.
- OD-17 menggabungkan engine, privilege, atomic publication, dan cohort-key rule.

2A.7R menyebut graph sebelumnya memiliki cycle OD-13 ↔ OD-17 dan self-dependency OD-18; final graph yang digunakan di sini mempertahankan OD-13 bergantung OD-01/12 saja, OD-17 bergantung OD-01/12/13/15, dan OD-18 tanpa dependency.

## 4. Cycle dan Self-Dependency Check

- **Self-dependency:** tidak ada.
- **Circular dependency:** tidak ada pada graph gabungan, yakni seluruh unconditional edge dan seluruh conditional edge dianggap aktif sekaligus sebagai pemeriksaan konservatif. Penambahan conditional edge OD-01/OD-12 → OD-04/OD-10 tidak membentuk cycle.
- **Conditional edge condition:** OD-01/OD-12 → OD-10 aktif hanya jika kebijakan reservation OD-10 mengizinkan dan reservation dipilih untuk digunakan. OD-01/OD-12 → OD-04 aktif hanya jika kebijakan OD-10 tersebut berlaku dan OD-04 memilih Hybrid reservation. Jika reservation tidak diizinkan atau tidak digunakan, conditional edges tidak aktif.
- **Tidak beralasan:** hanya empat conditional technical edges untuk OD-04/OD-10 ditambahkan; unconditional edges lain dipertahankan sesuai 2A.7R §7.
- **Kepemilikan keputusan berlapis:** OD-10 memiliki kebijakan reservation; OD-04 menentukan account lifecycle/provisioning mode dan, bila memilih Hybrid, mengonsumsi kebijakan OD-10. Hybrid reservation tetap terpisah dari sync/async provisioning. OD-14 membedakan owner policy time semantics dari technical clock validation.
- **Catatan:** dapat membahas atau menyetujui prinsip policy paralel bila governance mengizinkan; urutan graph adalah untuk memfinalkan contract implementasi, bukan melarang diskusi lebih awal.

## 5. Urutan Keputusan yang Direkomendasikan

Urutan topologis praktis, bukan jadwal wajib:

1. **Akar/fundamental:** OD-01, OD-02, OD-11, OD-15, OD-18.
2. **Runtime/engine-sensitive:** OD-03 setelah OD-02; OD-12 dan OD-14 setelah OD-01.
3. **Atomicity/authority:** OD-13 setelah OD-01/12; OD-05 setelah OD-18; OD-10 setelah OD-11/18.
4. **Operational policies:** Putuskan kebijakan reservation pada OD-10 setelah OD-11/18; OD-04 kemudian mengadopsi kebijakan OD-10 bersama OD-11/18 untuk final account contract. Jika OD-10 mengizinkan dan OD-04 memilih Hybrid reservation, technical contracts terkait juga menunggu OD-01/12. OD-06 setelah OD-12/15/18; OD-07 setelah OD-14/15/18; OD-08 setelah OD-03/14/15; OD-09 setelah OD-07/15/18.
5. **Dependent lifecycle gates:** OD-16 setelah OD-05/11/12/18; OD-17 setelah OD-01/12/13/15.

Kelompok prioritas owner yang diminta tetap:

- **Prioritas A — Fundamental Architecture:** OD-01, OD-03, OD-11, OD-12, OD-13, OD-15, OD-17.
- **Prioritas B — Operational Governance:** OD-04, OD-05, OD-06, OD-07, OD-08, OD-09, OD-10, OD-14, OD-16, OD-18.
- **Prioritas C — Runtime Validation:** OD-02.

Prioritas bukan topological order: OD-02 berprioritas C tetapi menjadi prerequisite OD-03; diskusi runtime dapat dimulai lebih awal. OD-01/12/13/15 secara topologis mendahului finalisasi OD-17. OD-01/12 juga mendahului hanya bagian teknis OD-10 jika reservation diizinkan dan digunakan, serta OD-04 jika Hybrid menggunakan kebijakan reservation OD-10; keduanya bukan prerequisite kebijakan unconditional.

## 6. Status dan Batas

Semua node tetap `PENDING`; dependency tidak mengubah approval status. Local technical evidence bukan approval target operational. SRC-GAP-01/02 tetap `OPEN`. BL-01–BL-10 tetap `OPEN`; implementation readiness `NOT READY FOR IMPLEMENTATION`.
