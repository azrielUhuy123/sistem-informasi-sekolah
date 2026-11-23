# TAHAP 2A.8 — FINAL NIS ARCHITECTURE CONSOLIDATION

## 1. Document Control & Version

| Field | Value |
|---|---|
| Project / module | SINERGI-MJ / Nomor Induk Siswa (NIS) |
| Document | Final architecture consolidation, version 1.0 |
| Date | 2 November 2026 (Asia/Jakarta; date supplied in session context) |
| Document status | Consolidated working architecture for owner review; not approved for implementation |
| Owner decision status | OD-01–OD-18 all `PENDING` |
| Implementation status | Not implemented; no migration, schema change, or workflow activation is authorized by this document |
| Source limitation | Required Tahap 2A.1 and 2A.2 documents were not found in the repository; see §5 and §27 |

This is a read-only document consolidation. It does not claim that the proposed schema, services, constraints, comparator, or tests exist or have passed.

## 2. Executive Summary

The consolidated design uses one publication authority, an immutable identity registry, a permanent sequence-slot ledger, an audited entry history, a cohort lock, and atomic initial/supplemental publication. A new NIS is `TTTT.T.NNN`; sequence space is shared across rombels for `(year_code, entry_grade)`. Entry year and grade reflect the first accepted entry at SDN Mekar Jaya, not current class, school of origin, or issue date. Already-issued NIS values are never rewritten or reused.

The intended workflow is: establish evidenced entry history; validate PPDB/identity and placement; preview all eligible cohort candidates; hardblock on unresolved contradictions; obtain publication authorization; allocate under cohort and sequence locks; atomically commit registry, slot, Siswa NIS, sequence high-water, publication snapshot and batch state. A failed publication rolls back all issuance state; its failed attempt is recorded in a separate transaction. Retry is idempotent on the same batch.

Two refinements are integrated but remain recommendations:

- Account provisioning is recommended after NIS publication through a transactional outbox and idempotent worker. This conflicts with Tahap 2A.3's proposal to synchronize `users.username` in the issuance transaction. Owner must decide whether to create/reserve the username at publication or during provisioning. Email failure must not undo an issued NIS.
- Supplemental sorting is recommended by `official_entry_at`, cohort ordinal, approved name comparator, then numeric `siswa.id`. Tahap 2A.7R also described the less-specific timestamp/name/ID order. The ordinal is necessary to resolve equal timestamps deterministically, but OD-03/08/14 approval and technical validation remain outstanding.

The local evidence is not deployment evidence. The checked local environment was PHP 8.2.29, Laravel 12.40.2, MySQL 8.0.30/InnoDB and ICU 71.1. The requested `id-ID` Collator returned actual locale `root`; sorting is fail-closed. The local schema was sparse because the project is in development/testing. Local DB grants were broad (`*.*`, including DML/DDL/admin and `GRANT OPTION`), and the Apache PHP handler was not established.

**Consolidation verdict: `CONSOLIDATION INCOMPLETE`.** Available design/readiness/owner documents were consolidated, but mandatory Tahap 2A.1 and 2A.2 documents could not be located or inspected. Several owner decisions, technical evidence items and architecture alignments also remain open. This verdict is not authorization to implement or activate NIS publication.

## 3. Project Context

The supplied project context states that SINERGI-MJ is a school information system for SDN Mekar Jaya, currently in local development/testing, not yet in operational school use and without a production deployment. The local database `sekolah_app_testing` is sparse because it is a test/development database. Its record count is neither an anomaly nor a basis for estimating school population, capacity or batch size.

Tahap 2A.6 verified a local Laravel connection and local schema. Those facts are scoped to that snapshot only. There is no production deployment to compare under the supplied context; future target environment requirements still need to be established.

## 4. Scope & Non-Goals

### In scope

- Consolidate the NIS policy, proposed database model, publication workflow, concurrency, comparator, timestamps, account provisioning, legacy reconciliation, local evidence, owner decisions and implementation gates from available Tahap 2A documents.
- Preserve source IDs (`OD`, `TE`, `TV`, `BL`, `AC`) for traceability.
- Identify unavailable sources and conflicts rather than resolving them by assumption.

### Non-goals

- No application code, migration, schema, database data, privilege, configuration or dependency changes.
- No migration, seeder, test, database write, publication, account change or NIS change.
- No claim that proposed entities or controls have been implemented.
- No owner approval is inferred from a recommendation, prior design baseline, local result or lack of objection.
- No inference of entry year/grade from current placement, `angkatan_id`, NIS shape, school of origin or issue date.

## 5. Source Documents & Traceability

### 5.1 Availability

| Source ID | Expected source | Availability and use |
|---|---|---|
| SRC-21 | Tahap 2A.1 — initial inventory/analysis | Not found under `docs/audit/` or repository glob for `*2A*`; contents unavailable and not inferred. |
| SRC-22 | Tahap 2A.2 — workflow/design deepening | Not found under `docs/audit/` or repository glob for `*2A*`; contents unavailable and not inferred. |
| SRC-23 | [Tahap 2A.3 architecture](./TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md) | Available and reviewed. Contains baseline, proposed tables, locking, publication, integrations, tests and open decisions. |
| SRC-24 | [Tahap 2A.4 readiness gate](./TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md) | Available and reviewed. Contains OD-01–18, TE-01–20, BL-01–10, AC-* matrix and implementation sequence. |
| SRC-25 | [Tahap 2A.5 evidence closure](./TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md) | Available and reviewed. Contains runtime/comparator evidence, mutation paths, relationship findings, legacy query plan and owner register. |
| SRC-26 | [Tahap 2A.6 local verification](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md) | Available and reviewed. Contains local environment/schema, aggregate data, comparator, grant and TE/BL updates. |
| SRC-27 | [Tahap 2A.7 owner decision resolution](./TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md) | Available and reviewed. Contains OD rationale, options, technical recommendation, owner and technical registers. |
| SRC-28 | [Tahap 2A.7R refinement](./TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md) | Available and reviewed. Clarifies OD-04, OD-14, removes OD-18 self-dependency and fixes OD-13/17 cycle. |
| SRC-29 | [Tahap 2A.7R changelog](./TAHAP_2A_7R_CHANGELOG.md) | Available and reviewed. Records the prior refinement scope and what it intentionally left unchanged. |

### 5.2 Source limitations

Tahap 2A.1 and 2A.2 are mandatory per the consolidation brief but were unavailable. No claim is made that their findings are reproduced or that all historical rationale is covered. The consolidated architecture is therefore based on available Tahap 2A.3–2A.7R documents, not on all requested source material. Any later delivery of 2A.1/2A.2 must be reviewed for contradictions before this architecture is treated as complete.

### 5.3 Information classification

| Category | Meaning in this document |
|---|---|
| Established Design Baseline | Baseline repeated in available design documents and the task brief. It is not necessarily formal owner approval. |
| Recommended Technical Design | Proposed solution; requires owner decision and/or technical validation. |
| Pending Owner Decisions | OD-01–OD-18; all remain `PENDING`. |
| Technical Validation Required | Runtime, database, comparator, concurrency, recovery, privilege or data evidence not proven for a target. |
| Implementation Blockers | Conditions preventing the affected component from being implemented safely or publication from being activated. |

## 6. Terminology

| Term | Definition |
|---|---|
| NIS | Permanent school identity, distinct from NISN and database primary key. |
| `year_code` | Four-digit code for the academic year of first entry to SDN Mekar Jaya; example `2026/2027 → 2627`. |
| `entry_grade` | Grade at first officially accepted entry to SDN Mekar Jaya, not current grade. |
| Cohort | Publication grouping keyed by the approved academic-year mapping (`year_code`). |
| Initial publication | One initial batch for a cohort, containing all eligible pre-cutoff students across paths, grades and rombels. |
| Supplemental publication | Later issuance for a student officially entering after initial cutoff, without changing prior issuance. |
| Registry | Permanent record of every issued or inventoried legacy NIS; proposed, not currently present in local schema. |
| Slot | Permanent tuple `(year_code, entry_grade, sequence_number)` in `issued` or approved `reserved` state. |
| Cohort ordinal | Proposed monotonic entry-order tie-break assigned under cohort lock; requires owner/technical validation. |
| `recorded_at` | Technical time the system records an event. |
| `effective_at` | Administrative time/date a decision is declared to apply. |
| `official_entry_at` | UTC system time at which approved entry history is committed after cohort locking. |
| Outbox | Proposed durable intent committed with publication and processed asynchronously; not an existing table/service. |

## 7. Established Design Baseline

The following baseline is preserved from available Tahap 2A.3 and subsequent refinement; it is not an implicit sign-off:

1. New NIS format is `TTTT.T.NNN`, e.g. `2627.1.001`; sequence has at least three digits and expands beyond 999 without truncation.
2. `TTTT` identifies the year of first entry; `T` identifies entry grade; it is not current grade or issue year.
3. `2026/2027 → 2627` is a fixed example. General mapping, invalid labels and century rollover remain OD-15.
4. Sequence is shared across rombels for `(year_code, entry_grade)`.
5. NIS is distinct from NISN and database primary key.
6. Publish only after official entry history and a valid placement; PPDB re-registration does not itself issue NIS.
7. One initial publication per cohort; pre-cutoff eligible students cannot be omitted. Unresolved mandatory candidates or placement anomalies hardblock publication.
8. Initial alphabetical ordering uses the full name and an owner-approved, verified comparator. Current local Collator output is not adequate; fail closed.
9. Transfer students use the first year/grade accepted at SDN Mekar Jaya; their former school or current class is not a fallback. Pre-initial transfers enter initial; post-initial transfers are supplemental.
10. Issued NIS and consumed slots are immutable, permanent and never reused, including after soft delete, force delete, restore, correction or relink.
11. Legacy NIS are preserved exactly. No automatic parsing, normalization, remapping or reissue.
12. Publication is all-or-nothing. Failed attempt logging is outside the issuance transaction; retry is idempotent and uses the same batch.
13. Membership, entry, placement, and relevant pre-issuance sort-key changes use the same cohort-lock protocol as publication. Multi-cohort operations lock in deterministic key order.

## 8. NIS Business Rules

| Rule ID | Rule |
|---|---|
| NIS-R01 | New values follow `TTTT.T.NNN`; suffix padding minimum is 3 digits and no maximum of 999. |
| NIS-R02 | Year and grade encode first official entry to this school. Do not infer either from current grade, `angkatan_id`, school of origin or issue date. |
| NIS-R03 | Sequence key is `(year_code, entry_grade)` across all rombels. Different grades have separate sequence spaces. |
| NIS-R04 | No duplicate sequence slot or exact NIS; no re-numbering or reuse. |
| NIS-R05 | Name changes do not change a published NIS. Name may affect ordering only before initial issuance and according to approved comparator rules. |
| NIS-R06 | Preview consumes no slot. Publication failure leaves no partial issuance. |
| NIS-R07 | Entry and placement must be validated before issuance. PPDB state alone is insufficient. |
| NIS-R08 | A student with a pre-initial official entry omitted from initial publication blocks supplemental until reconciled. |
| NIS-R09 | A post-cutoff eligible student gets the next available slot within the proper year/grade tuple. |
| NIS-R10 | Legacy exact strings remain unchanged; unresolved collision blocks affected allocation unless an approved reservation procedure applies. |

## 9. Eligibility & Placement Rules

### 9.1 PPDB candidate conditions

The available design treats ordinary PPDB eligibility as all of:

- Final status accepted.
- Re-registration completed.
- Valid `siswa_id` and existing Siswa row.
- Official entry history recorded under the approved workflow.
- Valid placement for the relevant academic year and grade.

Rejected, undecided, or accepted-but-not-re-registered applicants are not automatically included. Contradictions among PPDB status, Siswa link, history and placement hardblock; do not auto-repair.

### 9.2 Placement gate

Owner must define exact valid-placement status/date/exception rules (OD-09). Recommended hardblocks include absent placement; more than one eligible open placement; invalid/deleted/inactive class hierarchy; year/grade mismatch; missing entry grade evidence; or placement mutation outside the cohort-lock protocol. “No anomaly in the sparse local snapshot” is not a placement guarantee.

### 9.3 Transfer

Transfer evidence must support first acceptance year and grade at SDN Mekar Jaya. The authorized documents and approving officer are OD-07. Entry grade must not default to grade 1. The cutoff—not the administrative route—determines initial versus supplemental.

## 10. Database Architecture

### 10.1 Existing versus proposed schema

**Existing tables observed in local schema:** `siswa`, `users`, `ppdb_pendaftar`, `penempatan_siswa`, `tahun_ajaran`, class/group/master tables, and other application tables. The local schema had 50 tables. PPDB is singular (`ppdb_pendaftar`). This is local schema evidence only.

**Relevant existing local columns/constraints:** `siswa.nis` was `NOT NULL` with unique index; `siswa.user_id` and `users.siswa_id` were nullable FKs without one-to-one unique enforcement; `users.username` had a unique index; placement lacked a unique constraint preventing multiple open placements per student/year; `siswa.angkatan_id` had no FK/index. Actual migration/schema details and caveats are in Tahap 2A.6.

**Proposed only:** NIS cohort/history/sequence/slot/batch/registry/reconciliation/attempt/reservation structures below. None was present in the inspected local schema. Physical types, collations, constraints, triggers and FK behavior depend on OD-01 and database validation.

### 10.2 Proposed entity catalog

All entities in this catalog are proposals, not active schema. “Immutable/append-only” describes the intended contract, which needs service, database privilege/constraints and direct-SQL validation.

| Proposed table | Purpose, important fields and relation | PK / FKs / uniqueness (proposal) | Mutability, retention and invariant |
|---|---|---|---|
| `nis_publication_cohorts` | Stable mutex/revision and publication cutoff for one year cohort; `tahun_ajaran_id`, `year_code`, source label, revision, logical clock, initial batch/published timestamp. | PK `id`; FK academic year RESTRICT; unique `year_code`; optional FK initial batch added in staged migration due cycle. | Persistent row; mutable revision/clock/state only by coordinator/publisher. No cascade to history, sequence, batch or registry. |
| `siswa_entry_history` | Permanent official first-entry fact: `siswa_id`/snapshot, cohort, year, entry grade, `official_entry_at`, source, PPDB reference, approver snapshot and evidence pointer. | PK `id`; FK cohort RESTRICT; Siswa nullable unique FK SET NULL; optional PPDB/actor FKs SET NULL; unique current `siswa_id` where supported. | Core facts immutable; Siswa deletion nulls operational FK, not history. At most one current history per Siswa. |
| `siswa_entry_history_corrections` | Approved effective corrections with from/to year/grade, reason/evidence, requester/approver and correction number. | PK `id`; history/registry FKs RESTRICT; unique `(entry_history_id, correction_no)`; actor FKs SET NULL with snapshots. | Append-only; corrections do not alter issued NIS/slot or original event. |
| `siswa_entry_history_correction_events` | Submitted/approved/rejected/conflict events, request key, case key, proposed values and actor evidence. | PK `id`; history/registry FKs RESTRICT; unique case/event and request key; actor SET NULL. | Append-only; effective value changes only with approved correction event and corresponding correction row in one transaction. |
| `nis_sequences` | High-water allocator per `(year_code, entry_grade)`. | PK `id` or composite; unique tuple; optional cohort FK/validation. | One-way mutable high-water; never decrease; at least highest committed issued/reserved slot. |
| `nis_sequence_slots` | Canonical permanent tuple for issued/reserved sequence; state, reservation evidence/approval. | PK `id`; unique `(year_code, entry_grade, sequence_number)`; reservation request FK RESTRICT and target-specific unique behavior. | Immutable after creation; never delete or transition reserved to issued. Consumes the slot permanently. |
| `nis_publication_batches` | Initial/supplemental batch, snapshot hash, revision, comparator metadata, count, cutoff/published time, request key and state. | PK `id`; cohort FK RESTRICT; unique `(cohort_id,batch_type,batch_no)` and unique initial guard specific to target DB; unique batch request key. | Mutable draft/preview; published snapshot immutable. Failed/cancelled batch does not authorize a second initial batch. |
| `nis_registry` | Authoritative exact-string record for every newly issued or inventoried legacy NIS; origin, tuple if evidenced, slot/history/batch refs, nullable operational Siswa/User links and snapshots. | PK `id`; byte-exact unique NIS; unique slot/history/user links where target semantics permit; FKs history/slot/batch RESTRICT; Siswa/User SET NULL. | Permanent, no delete; legacy tuple nullable until verified. Operational links can change only through audited relink. |
| `nis_legacy_reconciliations` | Append-only evidence decisions and status (`verified`, `needs_verification`, `data_conflict`) for registry entries. | PK `id`; registry FK RESTRICT; reviewer FK SET NULL with snapshot; indexes by registry/time. | Append-only; latest status may be cached, events retained. |
| `nis_publication_attempts` | Durable `started/published/blocked/failed/...` attempt log with idempotency key and error category. | PK `id`; batch FK RESTRICT; unique `(batch_id,attempt_no)` and `(batch_id,request_key)`; actor SET NULL + snapshot. | Started attempt committed before issuance; success in publication transaction; failure marked after rollback in separate transaction. |
| `nis_sequence_reservation_requests` | Requested exact slot reservation, type, collision source, evidence, requester and status. | PK `id`; unique case key; collision registry FK RESTRICT; request to slot relationship unique. | Status projection changes only with corresponding event; only approved request can create reserved slot. |
| `nis_sequence_reservation_events` | Append-only submitted/approved/rejected/cancelled events with reason, actor/role, evidence and UTC time. | PK `id`; request FK RESTRICT; unique `(request_id,event_no)`; actor SET NULL + snapshot. | Append-only; rejection creates no slot; approval/slot creation atomic under cohort and sequence locks. |
| Account provisioning outbox (name/schema TBD) | Proposed durable intent per issued Siswa/account task with idempotency key, state, attempt metadata and safe error code. | Not specified as final table. Unique intent key per registry/student/task; FK registry/Siswa RESTRICT or nullable operational ref with snapshots must be designed. | Proposed only; committed with publication; worker updates status; no credential/token in logs; no NIS allocation on retry. |

### 10.3 Proposed changes to existing tables

| Existing table | Proposed change (not implemented) | Required invariant |
|---|---|---|
| `siswa` | Permit `nis=NULL` before publication; preserve exact unique semantics; only transition NULL → issued registry value. Keep operational row distinct from registry authority. | Never change/clear issued NIS; only publication service can set first value. |
| `users` | Preserve unique `username`; canonical relation decision remains OD-11; account may remain absent/pending until provisioning. | No auto-merge/rename; username collision blocks or manual review. |
| `ppdb_pendaftar` | Keep as eligibility source; entry history may hold nullable FK SET NULL. | Contradictory status/link blocks; PPDB confirmation itself must not issue NIS. |
| `penempatan_siswa` | Use placement resolver; consider target-specific uniqueness protection for one valid open placement. | Placement changes affecting candidate require cohort lock and revision. |
| `tahun_ajaran`, class/group/master | Resolve year-code and placement/grade explicitly. | No inference from current class; mapping rejects ambiguous values. |

### 10.4 Migration sequencing and FK cycles

Proposed implementation must add parent entities before dependent FKs. The architecture contains cycles such as cohort ↔ initial batch and sequence slot ↔ registry/request relationships. Install in stages: create base tables with nullable references; add references/unique constraints after both sides exist; then enforce CHECK/trigger/NOT NULL conditions after data backfill and compatibility checks. Migration order must be target-engine tested; do not assume all engines support identical partial indexes, CHECK, generated columns, deferrable constraints or `ON DELETE SET NULL` with immutability triggers.

## 11. Registry & Immutability

The `nis_registry` is the permanent identity record; `nis_sequence_slots` protects consumed tuple space. Both survive Siswa/User deletion. Exact NIS uniqueness must be byte-exact or proven equivalent using target-driver collation; do not trim, case-fold or rewrite legacy strings.

- New issuance: registry origin `issued`, exact NIS, tuple and slot/history/batch link all commit atomically.
- Legacy: registry origin `legacy`, exact old string and reconciliation state; unknown year/grade/sequence remain NULL. No slot mapping without evidence.
- Soft delete: retain registry/slot/history; Siswa operational FK may remain or be nullable as designed.
- Force delete: never cascade-delete registry/slot/history; `siswa_id` operational link may become NULL while immutable ID snapshot remains.
- Restore: original Siswa retains its existing NIS; no new allocation.
- Recreate/relink: require evidence, canonical relationship, authorization and append-only event; never reclaim slot or silently attach by matching name.
- FK policy: registry-to-slot/history/batch and ledger-to-parent relations generally RESTRICT; operational Siswa/User and actor links may SET NULL with snapshots. Exact behavior must be tested with triggers.
- Direct SQL: runtime privileges must prevent unauthorized ledger update/delete. DBA/superuser powers cannot be claimed impossible; use separate roles, audited break-glass access, backups and recovery evidence.

Absolute immutability is not established by Laravel models/events alone and cannot be claimed while privileged database administrators can alter data directly.

## 12. Initial Publication Workflow

1. **Prepare cohort:** resolve official academic-year identity and `year_code`; reject ambiguous mapping. Create/reuse stable cohort row race-safely.
2. **Collect candidate universe:** include PPDB, transfer and manual candidates regardless of grade/rombel; do not filter away candidates because they lack placement.
3. **Check eligibility:** accepted + completed re-registration for ordinary PPDB, valid Siswa, official history; approved equivalent source for transfer/manual.
4. **Validate history:** year/entry grade and evidence; no derivation from active class or school of origin.
5. **Validate placement:** exact owner-approved definition; anomalies hardblock.
6. **Inventory collision:** check exact legacy NIS, sequence slot and username conflicts. Reservation requires owner-approved evidence/process.
7. **Lock cohort:** acquire cohort lock after race-safe bootstrap; all candidate/mutation writers follow same protocol.
8. **Recompute revision and candidate snapshot:** validate candidate set after locks; compare to preview revision/hash. Changed snapshot is stale and requires new preview.
9. **Preview:** group by `(year_code, entry_grade)`, sort full names with approved comparator; preview consumes no sequence/slot.
10. **Hardblock:** unresolved required candidate, status/link contradiction, missing or invalid placement, legacy conflict, username conflict under selected policy, unknown cohort/grade, comparator mismatch or stale revision stops publication.
11. **Authorize publication:** actor permissions and approval/separation rules per OD-18. Owner has not yet mapped school roles.
12. **Create attempt:** durable `started` attempt committed before issuance transaction; same request key returns the existing attempt.
13. **Allocate:** lock sequence rows in sorted tuple order; allocate monotonically above committed high-water and every issued/reserved slot. No truncation at 999.
14. **Write ledger/cache:** create issued slots, registry rows and `siswa.nis` NULL→value; update sequence high-water, snapshot/watermark, cohort and batch published state atomically.
15. **Commit:** either all issuance state commits, or none does. No success-shaped partial state.
16. **After commit:** record audit/observability and enqueue post-publication account provisioning/notification if owner approves outbox alternative. Email is not part of DB atomicity.
17. **Failure:** full rollback first; mark attempt outcome in separate transaction. If commit outcome is uncertain, query by idempotency key before retry.

## 13. Supplemental Publication Workflow

Supplemental requires an existing initial publication and only includes eligible, evidenced entries after the initial cutoff that do not already have an NIS. It receives the next sequence within its `(year_code, entry_grade)` space.

1. Lock cohort; validate initial batch is committed and watermark stable.
2. Resolve and lock all candidates, including anomalous candidates.
3. If any eligible pre-cutoff candidate was omitted from initial, hardblock supplemental and reconcile.
4. Exclude all already-issued students without altering their NIS.
5. Apply owner-approved deterministic order. Recommended key is:

   `(official_entry_at ASC, cohort_entry_ordinal ASC, approved_name_comparator ASC, siswa.id ASC)`

   `official_entry_at` gives official entry chronology; ordinal resolves equal timestamps/clock precision; approved name comparator provides predictable secondary order; numeric `siswa.id` is final stable tie-break. `effective_at` is not a key.
6. Capture supplemental snapshot/cutoff and issue atomically with next sequence slots.
7. Retry the same batch idempotently; no reissue or re-number.

Tahap 2A.7R also states `(official_entry_at, comparator name, siswa.id)`. This is a genuine specification variance: the refined recommendation adds a cohort ordinal before name. The consolidated recommendation uses the more deterministic key but does not silently treat it as approved. OD-03, OD-08 and OD-14 remain pending; comparator/runtime and ordinal implementation need validation.

## 14. Cohort Lock & Concurrency

### 14.1 Global lock protocol (proposed)

1. Validate request and determine all relevant `year_code`/cohort keys before business writes.
2. Race-safely bootstrap cohort rows using target-driver-specific upsert/unique behavior; locking a nonexistent row is not sufficient.
3. Lock all cohort rows in ascending `year_code`. Multi-cohort corrections/rollovers use the same order.
4. Lock domain rows in one global table rank and ascending primary key within each table. The precise rank for every source table must be made explicit in implementation design; no workflow may lock Siswa/PPDB first and then wait for cohort if that reverses the order.
5. Lock/bootstrap sequence rows in ascending `(year_code, entry_grade)` only when allocation/reservation is needed.
6. Re-read candidate/cohort keys and revalidate under lock. If the set changes, rollback and restart with the complete sorted set.
7. Recompute eligibility, revision, preview fingerprint and placement/conflict conditions before write.

### 14.2 Concurrency requirements

- Every membership, official-entry, placement and relevant pre-issuance name mutation participates in the same cohort lock/revision protocol as publication.
- Two initial publishers for the same cohort serialize; database uniqueness is defense-in-depth, not just service guard.
- Two allocators for the same tuple serialize on sequence row and are protected by unique slot tuple.
- Deadlock/serialization retry is bounded and occurs only after full rollback. Unique collision is classified, never blindly retried.
- The database engine/version is not determined by local evidence for any future deployment; lock/upsert/isolation behavior requires disposable-database concurrency tests.

## 15. Sequence Allocation

- Sequence identity is `(year_code, entry_grade, sequence_number)`.
- Rombels share the same sequence for a year and entry grade; distinct entry grades have independent sequences.
- `nis_sequences.last_allocated_sequence` is a one-way high-water at least as large as every committed issued/reserved slot; it never decreases.
- `nis_sequence_slots` has unique tuple across `issued` and `reserved`.
- A slot becomes `issued` in the same transaction as registry, Siswa NIS and publication batch.
- Reservation is not implicit auto-skip. A reserved slot requires an approved request, exact evidence, actor, reason and audit event, under the same cohort/sequence locks.
- Sequence formatting zero-pads to three characters minimum; `1000` remains `1000`.
- Rollback must not leave a partial issued slot/high-water advance. Approved permanent reservations remain consumed even if a later publication fails.

## 16. Comparator & Sorting

Initial order is full name alphabetically within each `(year_code, entry_grade)` group, not within a rombel. The exact comparator/expected order remains unapproved (OD-03).

Recommended comparator contract:

1. Preserve the stored name; sorting works on a transient key.
2. NFC normalization; deterministic Unicode case handling and owner-approved whitespace normalization.
3. Use a proven Indonesian locale/data or explicitly approved comparator; actual locale must be checked, not just requested locale.
4. Do not silently remove accents, punctuation, titles or prefixes.
5. Owner approves full golden order including case, composed/decomposed Unicode, accent, whitespace, punctuation, prefix/title and identical names.
6. Use deterministic final tie-break and record algorithm/data/runtime metadata in batch snapshot.
7. Missing/mismatched comparator or environment fingerprint blocks publication.

Local ICU 71.1 Collator requested as `id-ID` returned actual locale `root`; the tested order is not a golden Indonesian result. Windows NLS was not proven end-to-end across PHP/runtime environments. Sorting remains **FAIL-CLOSED**.

## 17. Timestamp Architecture

| Timestamp | Meaning | Proposed use |
|---|---|---|
| `recorded_at` | Technical time the system records an event or row. | UTC, append-only event time for corrections, attempts, approvals and audit. Does not determine administrative effective date. |
| `effective_at` | Administrative date/time declared applicable by an authorized decision. | Preserve source, evidence and correction history. Informational/administrative; not an issuance ordering key or historical cutoff fallback. |
| `official_entry_at` | System time when official entry history is approved and committed after cohort lock. | UTC, set in same transaction as approved history. Immutable original event; not application request, re-registration or import timestamp. |

Recommended source is a verified database-based UTC logical cohort clock, but source, precision, failover behavior and runtime timezone are not yet established. A monotonic cohort ordinal is recommended to resolve timestamp equality; it is a proposed value, not owner approval.

- Supplemental order: `official_entry_at`, then cohort ordinal, then approved comparator, then `siswa.id`.
- Initial boundary: while cohort lock is held, capture a committed entry watermark/ordinal and `initial_published_at` technical UTC timestamp in the publication transaction. Ordinals `<= watermark` are in initial only after owner approves that boundary; later ordinals are post-cutoff candidates. An entry cannot race across a locked boundary.
- If timestamp-only cutoff is used instead, owner must explicitly decide `<`/`<=` equality and target precision; unresolved equality hardblocks.
- `effective_at` correction does not change `official_entry_at`, ordinal, batch snapshot, cutoff or any issued NIS.
- Corrections append old/new values, evidence, reason, actors and new `recorded_at`; a correction to the official entry decision uses OD-05, never overwrites original event.
- Snapshot stores entry timestamp/ordinal, watermark, comparator fingerprint and candidate IDs needed to explain immutable issuance; minimize PII.

## 18. Account Provisioning & Username

### 18.1 Alternatives

| Option | Transaction boundary | Failure semantics |
|---|---|---|
| A. Account inside publication transaction | User creation/link/username and NIS issuance commit or roll back together when account DB state is mandatory. | DB account/username conflict can roll back all NIS issuance. Email cannot be atomically rolled back; mail failure after commit must not cancel NIS. |
| B. Transactional outbox + idempotent worker (Tahap 2A.7R recommendation) | Publication atomically commits NIS and durable account-provisioning intent. Worker later creates/links or activates account in a separate transaction. | NIS remains issued if worker fails; account is pending/non-login; retries use same intent and never allocate a new NIS. Email failure has independent retry/state. |

### 18.2 Recommended but unapproved behavior

Option B is recommended, not owner-approved. Proposed states include `pending`, `processing`, `succeeded`, `failed_retryable`, `failed_manual_review`; state/attempts should have idempotency key, safe error category, timestamps and audit actor/source. Do not log secrets or activation tokens.

Login must deny pending/failed/inactive/unapproved accounts at the backend, not merely hide a UI action. Provisioning links only through an approved canonical relation and issued identity, not matching names. Username conflict is manual review; no auto-merge/rename.

Email/notification is a separate side effect. A mail outage must not roll back the issued NIS or a successfully provisioned account. Retry notification independently.

### 18.3 Unresolved username alignment

Tahap 2A.3 publication design says synchronize `users.username` with NIS in the issuance transaction; Tahap 2A.7R recommends creating/linking the account after publication. Possible approaches requiring owner/technical decision:

1. Create a disabled User row during publication and atomically set unique username, then activate after provisioning checks.
2. Reserve the username in a dedicated unique namespace/outbox/registry record during publication; worker consumes the reservation to create account.
3. Create and set username only in the worker; publication commits NIS without a username reservation, so a later collision can require manual resolution while NIS remains issued.

No option is selected here. The exact treatment of username uniqueness, reservation, account activation and collision is an open OD-04/OD-10 decision. The publisher/provisioner cannot be finalized until this is resolved.

## 19. Legacy NIS Reconciliation

Each exact NIS gets one of:

- `verified`: evidence supports the current NIS and any mapped entry tuple.
- `needs_verification`: NIS exists, but provenance/tuple/link remains unproven.
- `data_conflict`: exact collision or contradictory NIS/student/account/evidence.

Rules:

1. Include active and soft-deleted records, historical evidence and username collisions in authorized inventory.
2. Preserve malformed-but-used legacy NIS exactly until an approved reconciliation outcome; never auto-format or reissue.
3. Do not infer year/grade from active class, `angkatan_id`, NIS shape, current alphabetical order, school of origin or NISN.
4. Exact byte/string collision with a new candidate hardblocks. Reservation only after policy approval, evidence, reason, distinct approver and append-only audit.
5. Backfill registry only after source dataset, mapping and disposition are approved; unknown tuples stay NULL and do not consume an invented slot.
6. Reconcile source counts, duplicates, soft-deleted rows, relations, placement and username collisions using privacy-limited aggregates; review PII only under authorized process.
7. Unresolved conflict blocks affected allocation and remains visible as a conflict; no silent skip or high-water adjustment.

The sparse local snapshot had one active Siswa and zero soft-deleted rows, with no measured structural NIS anomaly/duplicate. This does not establish historical completeness or legacy provenance.

## 20. Delete, Restore & Relink

| Operation | Required outcome |
|---|---|
| Soft delete issued Siswa | NIS, registry, slot and entry history remain permanently. |
| Force delete issued Siswa | Operational FK may be nulled; registry, snapshot, slot/high-water and audit remain; no slot release. |
| Restore same Siswa | Reuse its original NIS/registry link; no allocation or new batch. |
| Recreate or relink | Require evidence, canonical relationship, authorization and audited event; detect existing registry; no name-only auto-link. |
| Delete User | Must not silently transfer account or NIS identity; handle canonical relationship and login effects per OD-11/16. |
| Rollback after issuance | No destructive ledger downgrade; disable publisher/forward-fix or use backward-compatible app version. |

Retention, privacy and authorized relink/delete authority remain owner decisions. `SET NULL` versus trigger/immutability interaction requires target-engine test.

## 21. Local Environment & Technical Evidence

All evidence below is from the local development/testing environment checked in Tahap 2A.6, except repository/source facts explicitly identified. None is deployment evidence. No production deployment is described in the supplied project context.

| Evidence | Local result | Limit |
|---|---|---|
| Laravel / PHP / Composer | Laravel 12.40.2; PHP CLI and active `artisan serve` PHP 8.2.29; Composer 2.4.1 | Apache PHP handler/vhost unknown; no deployment. |
| Database | Effective local Laravel connection MySQL 8.0.30, schema `sekolah_app_testing`, InnoDB, `REPEATABLE-READ`, timezone SYSTEM | Snapshot sparse; no production/target parity. |
| Laragon / ICU | Laragon 6.0.0; ICU 71.1 | Local only. |
| Comparator | `Collator('id-ID')` actual locale `root`; synthetic fixture output observed | Not Indonesian golden order; fail closed. |
| Grants | Local connection account had global `*.*` DML/DDL/admin privileges and `GRANT OPTION` | Serious local least-privilege concern; no privilege change was made. Not evidence of future runtime grants. |
| Schema/migrations | 50 tables; 109 migration source files recorded locally; PPDB table is `ppdb_pendaftar`; selected actual indexes/FKs inspected | Migration record alignment does not exclude all schema drift; no NIS ledger exists in local schema. |
| Data snapshot | One active Siswa, zero soft-deleted; no measured structural NIS duplicate/format anomaly; relationship/PPDB/placement aggregates as in 2A.6 | Not representative, complete or evidence of provenance/capacity. |

### 21.1 Technical validation register

Statuses preserve prior TE/TV IDs. `PARTIALLY VERIFIED` is not target approval. No acceptance or concurrency test is claimed passed.

| ID | Description | Available evidence | Status | Missing evidence / validation | Dependent stage |
|---|---|---|---|---|---|
| TE-01 | DB engine/version/schema | Local effective MySQL 8.0.30/InnoDB/schema verified | VERIFIED — local only | Future target selection and schema/driver parity | Migration, constraints |
| TE-02 | PHP CLI | Local PHP 8.2.29 | VERIFIED — local only | Target/CI runtime agreement | Runtime gate |
| TE-03 | PHP web SAPI | `artisan serve` local PHP binary verified; Apache fcgid present | PARTIALLY VERIFIED | Apache handler/vhost and target web SAPI | Comparator/login integration |
| TE-04 | Effective dependencies | Local Composer 2.4.1, Laravel 12.40.2, lockfile | PARTIALLY VERIFIED | Deployed artifact/vendor parity; no deployment | Build/runtime |
| TE-05 | intl/Normalizer/Collator/mbstring/PDO | Available in local CLI; mysql/sqlite PDO drivers | PARTIALLY VERIFIED | Web SAPI/target extensions and drivers | Sorting/DB integration |
| TE-06 | ICU/actual locale | ICU 71.1; `id-ID` actual `root` | PARTIALLY VERIFIED — blocker | Approved locale/data version on target | Comparator |
| TE-07 | Golden sorting/parity | Synthetic SORT-01–12 probe exists | PARTIALLY VERIFIED | Owner expected order and exact parity across target runtimes | Initial publication |
| TE-08 | Isolation/row-level locks | Local InnoDB `REPEATABLE-READ` metadata | PARTIALLY VERIFIED | Contention/deadlock/retry on disposable target DB | Cohort/sequence service |
| TE-09 | Upsert/bootstrap/rollback semantics | Local engine/index metadata only | PARTIALLY VERIFIED | Race-safe upsert, rollback and failure integration test | Cohort/publication |
| TE-10 | Unique/CHECK/partial/generated/FK support | Selected local schema metadata | PARTIALLY VERIFIED | Target feature semantics incl NULL/unique/index | Migration |
| TE-11 | `SET NULL` with triggers | Local FK action inspected; no selected trigger found | PARTIALLY VERIFIED | Disposable target test with immutability trigger | Delete/registry |
| TE-12 | Grants/direct SQL | Local global grant observed | VERIFIED — local risk | Approved target role matrix and deny tests | Immutability/activation |
| TE-13 | User–Siswa data | Local aggregate: no measured mismatch/orphan/duplicate | VERIFIED — sparse local snapshot | Reconciliation on owner-approved complete data source | Canonical relation |
| TE-14 | Legacy NIS/collision/deleted | Local structural snapshot has no measured anomaly; one active row | PARTIALLY VERIFIED | Complete history/provenance, force-deleted records, candidate collision | Reconciliation |
| TE-15 | Username collision/account relation | Local linked-account aggregate only | PARTIALLY VERIFIED | Complete account/username inventory and candidate collisions | Provisioning |
| TE-16 | Schema vs migration source | Local selected schema and 109 migration records inspected | VERIFIED — local snapshot | Full drift check on designated source/target schema | Migration preflight |
| TE-17 | PPDB/placement consistency | Local aggregates had no measured conflict | VERIFIED — sparse local snapshot | Resolver definition and representative fixtures | Eligibility |
| TE-18 | Complete writer map | Principal source paths reviewed | PARTIALLY VERIFIED | External SQL/jobs/ETL, deployment scheduler, exhaustive call path | Integration/activation |
| TE-19 | Backup/restore | No restore drill | UNVERIFIED | Disposable target restore test and evidence | Migration/rollback |
| TE-20 | Volume/transaction benchmark | No load benchmark; sparse data not representative | UNVERIFIED | Synthetic target-sized benchmark | Batch limit/activation |
| TV-01 | Database feature support | Local MySQL known | PARTIALLY VERIFIED | Owner target and constraints/locking integration tests | Schema/service |
| TV-02 | PHP CLI/web runtime | CLI/`artisan serve` known locally | PARTIALLY VERIFIED | Actual Apache/target SAPI, ini/extensions | Runtime |
| TV-03 | Comparator/golden order | Actual locale root; no approved golden order | UNVERIFIED | Comparator choice, owner order, parity tests | Initial publication |
| TV-04 | Schema constraints/delete behavior | Local unique NIS; two account FKs non-unique; placement uniqueness gap | PARTIALLY VERIFIED | Target-specific schema/trigger/FK tests | Registry/placement |
| TV-05 | Least privilege/ledger direct SQL | Local grants too broad | UNVERIFIED — approved target design | Separate roles/effective grants and denial tests | Immutability |
| TV-06 | Legacy provenance/collision | Sparse local structural inventory only | PARTIALLY VERIFIED | Authorized complete legacy inventory/disposition | Backfill/allocator |
| TV-07 | User–Siswa/username | Sparse local aggregates; dual relationship exists | PARTIALLY VERIFIED | Complete reader/writer and row reconciliation | Account migration |
| TV-08 | Locking/recovery | No contention/recovery test | UNVERIFIED | Concurrent initial, allocator, deadlock/retry, uncertain commit | Publisher |
| TV-09 | Capacity/backup/restore | No benchmark/drill | UNVERIFIED | Synthetic capacity and restore evidence | Batch/activation |
| TV-10 | Writers/authorization | Main route/controller/import paths documented | PARTIALLY VERIFIED | External writer map and approved permissions | Workflow integration |
| TV-11 | Placement/PPDB eligibility | Sparse local snapshot consistent on measured aggregates | PARTIALLY VERIFIED | Policy resolver and contradiction/transfer fixtures | Publisher |
| TV-12 | Audit/activation plan | Conceptual design only | UNVERIFIED | Dry run, monitoring, rollback/forward-fix and privacy-safe logs | Activation |

## 22. Owner Decision Register OD-01–OD-18

All `Owner decision` values are intentionally blank/pending. Recommendations are not decisions. Dependencies follow Tahap 2A.7R; no self-edge or cycle.

| Decision ID | Name | Scope | Baseline | Recommendation | Alternatives | Dependency | Technical validation | Risk if undecided | Owner decision | Approval status |
|---|---|---|---|---|---|---|---|---|---|---|
| OD-01 | Database/version | Development and future operational target | Local MySQL 8.0.30 is evidence only | May adopt as dev/test baseline; target scope explicitly selected | Other engine/version; defer target | — | Engine features, schema, locking on selected target | DDL/transaction strategy uncertain | Pending owner | PENDING |
| OD-02 | PHP runtime/extensions | CLI, web, CI, target | Manifest PHP `^8.2`; local 8.2.29 | Pin supported runtime/extensions and comparator parity | One SAPI/build; tested runtime matrix | — | Web SAPI/ini/extensions/ICU | Comparator/runtime drift | Pending owner | PENDING |
| OD-03 | Name comparator | Initial sorting and supplemental secondary key | Full name alphabetical, no final comparator yet | Select only after golden-order and locale parity; fail closed meanwhile | ICU, packaged collation data, explicit rule set, defer publication | OD-02 | Actual locale, Unicode golden fixture across runtimes | Permanent incorrect ordering | Pending owner | PENDING |
| OD-04 | Account lifecycle/provisioning | PPDB, publication, login, email | NIS follows valid placement; account lifecycle unsettled | Outbox/idempotent worker after issuance; pending account cannot login | Account in publication transaction; pre-created disabled account | OD-10, OD-11, OD-18 | Login gate, idempotency, username collision, worker recovery | Issued NIS without usable account or incorrect link | Pending owner | PENDING |
| OD-05 | Post-publication correction authority | Identity/history correction | NIS/slot immutable | Append-only correction, evidence, separation requester/approver | Single approver; documented external exception | OD-18 | Correction/auth/audit tests | Unsafe correction or unresolved conflicts | Pending owner | PENDING |
| OD-06 | Legacy collision/reservation | Legacy migration/allocator | Exact legacy preserved; fail closed | No auto-skip; reservation only approved/evidenced | Reconcile only; controlled exact reservation | OD-12, OD-15, OD-18 | Complete inventory and reservation transaction | Reuse/collision or indefinitely blocked cohort | Pending owner | PENDING |
| OD-07 | Transfer evidence/entry grade | Transfer/manual admission | First accepted year/grade at this school | Owner-approved evidence + audited exception | Archive, manual verification, combination | OD-14, OD-15, OD-18 | Evidence workflow fixtures | Wrong cohort/grade | Pending owner | PENDING |
| OD-08 | Supplemental ordering | Supplemental immutable suffix order | Deterministic order required; unresolved variance | `official_entry_at`, ordinal, comparator, `siswa.id` | Timestamp/name/ID; sort within batch; owner-defined | OD-03, OD-14, OD-15 | Timestamp ties, batch boundaries, comparator parity | Permanent order depends on batch schedule | Pending owner | PENDING |
| OD-09 | Valid placement | Eligibility hardblock | Placement required before NIS | Define one valid placement and hardblock contradictions | Authorized named exceptions | OD-07, OD-15, OD-18 | Missing/multiple/inactive/mismatched placement tests | Publication for wrong/incomplete placement | Pending owner | PENDING |
| OD-10 | Username conflicts | NIS-to-account username | Unique username; no automatic takeover | No auto-merge/rename; collision manual review | Alternative username; controlled rename | OD-11, OD-18 | Inventory and unique collision tests | Wrong account access or batch failure | Pending owner | PENDING |
| OD-11 | Canonical User–Siswa relation | Login and account lifecycle | Login uses `siswa.user_id`; `users.siswa_id` also exists | `siswa.user_id` candidate only; reconcile before change | Reverse FK; consistency-enforced dual link; staged deprecation | — | Complete relation/data reconciliation; one-to-one and login tests | Orphan/mismatched login | Pending owner | PENDING |
| OD-12 | DB privileges/direct SQL | Runtime, migration, DBA and ledger | Local grant is global and broad | Separate least-privilege runtime/deployment/DBA roles | Shared privileged account (not recommended) | OD-01 | Effective grants, direct SQL and trigger tests | Ledger bypass and broad blast radius | Pending owner/DBA | PENDING |
| OD-13 | Batch size/atomicity | Publication transaction and operations | All-or-nothing publication | Preserve atomicity; numeric limit only after benchmark | Equivalent atomic staging; defer limit | OD-01, OD-12 | Target DB failure/volume/recovery benchmarks | Partial state/timeouts/unknown commit | Pending owner | PENDING |
| OD-14 | Timestamp/time authority | Entry, cutoff, order, audit | Post-lock official entry; UTC technical time | Separate recorded/effective/official timestamps; ordinal/watermark | Clock source/precision alternatives | OD-01 | Clock precision/skew/failover/equality tests | Batch boundary/order ambiguity | Pending owner | PENDING |
| OD-15 | Year-code mapping | Cohort identification | `2026/2027 → 2627` | Explicit mapping/validated parser; immutable after issuance | Two-year suffix parser; owner map | — | Invalid labels, duplicates, rollover tests | Wrong cohort/sequence | Pending owner | PENDING |
| OD-16 | Delete/restore/relink | Siswa, account, registry lifecycle | Registry/slot never reused/deleted | Permanent tombstone; restore same NIS; evidence-based relink | Soft-delete only; controlled force delete + tombstone | OD-05, OD-11, OD-12, OD-18 | FK/trigger/delete/recovery tests | Deleted identity reused or login link lost | Pending owner | PENDING |
| OD-17 | One initial batch/cohort | Batch guard and concurrent publishers | Exactly one initial publication | Cohort lock + DB uniqueness + idempotent retry | Engine-specific equivalent; service-only guard inadequate alone | OD-01, OD-12, OD-13, OD-15 | Two-publisher race, retry, rollback | Duplicate initial batch/issuance | Pending owner | PENDING |
| OD-18 | Role/separation of duties | Prepare/publish/reserve/correct/audit | No NIS-specific role approval exists | Separate permissions; distinct approver for exceptions | One role; split only sensitive actions | — | Owner role matrix and allow/deny tests | Unauthorized publication/correction | Pending owner | PENDING |

## 23. Technical Validation Register TE/TV

See §21.1 for the consolidated register. All TE-01–TE-20 and TV-01–TV-12 IDs are retained. The register describes evidence status, not acceptance completion. In particular, TE-01/TE-12/TE-13/TE-16/TE-17 are verified only for a local snapshot; no TE/TV entry is deployment verification.

## 24. Implementation Blockers BL

| ID | Blocker | Current status | Evidence required to close | Components blocked |
|---|---|---|---|---|
| BL-01 | Database engine/version/target feature support | Partially narrowed locally; target not established | OD-01, TE-01 and target DB feature/restore tests | Migration, constraints, concurrency |
| BL-02 | PHP web runtime/extensions | CLI and `artisan serve` local known; Apache handler unknown | OD-02, TE-02–05 for supported web/CI runtime | Comparator and runtime integration |
| BL-03 | Indonesian comparator/golden order | OPEN / FAIL-CLOSED; actual locale `root` locally | OD-03, TE-06/07, AC-OBS-02 exact parity | Initial publication; supplemental name tie-break |
| BL-04 | Legacy NIS/provenance/collision | Sparse local snapshot only | TE-14/17; authorized inventory and owner disposition | Backfill, reservations, allocator |
| BL-05 | User–Siswa and username reconciliation | Local aggregate only; canonical relation pending | OD-11, TE-13/15 and reader/writer reconciliation | Account migration/login/provisioning |
| BL-06 | Privilege/direct SQL/trigger behavior | Local global grant; target controls absent | OD-12, TE-11/12, AC-REG-01/02 | Immutability claim and activation |
| BL-07 | Timestamp authority and year-code | Policy/target semantics unresolved | OD-14/15, TE-08/09, AC-EH-01 and timestamp boundary tests | Entry history/cohort/cutoff |
| BL-08 | Transfer/correction/placement/supplemental/account policy | Several OD pending | OD-04–10/18 and relevant AC suites | Affected workflow integration |
| BL-09 | Volume, rollback, backup/recovery | Not benchmarked/restored | OD-13, TE-19/20, AC-PUB-03/04 and AC-CON-02 | Production-size publication |
| BL-10 | Complete mutation/writer map | Main repository paths known; external paths not excluded | TE-18/TV-10; all writers must use same service/lock | Integration and activation |

No blocker is declared closed merely because a requirement is documented.

## 25. Acceptance Criteria AC

The following original AC IDs come from Tahap 2A.4. New refinement criteria are `AC-ACC-*` and `AC-TIME-*`. Every criterion is **NOT RUN**; none is evidence of a passing implementation.

| AC ID | Acceptance condition (concise) | Gate / priority |
|---|---|---|
| AC-DB-01 | Fresh migration on every target engine yields expected schema/FKs/unique/CHECK/index; cyclic FK install staged; repeat migration safe. | Schema / P0 |
| AC-DB-02 | Backfill issued, verified legacy, unknown and collision fixtures; exact strings preserved; unknown tuple stays NULL/needs verification. | Schema / P0 |
| AC-DB-03 | Empty-schema rollback works; destructive rollback with ledger data is blocked or forward-fix only. | Schema / P0 |
| AC-COH-01 | Concurrent membership/entry/placement/name mutation and publish use same cohort lock; revision increments; stale preview denied. | Cohort / P0 |
| AC-COH-02 | Two concurrent initial requests yield one initial batch including failed/cancelled lifecycle. | Cohort / P0 |
| AC-COH-03 | Entry after committed initial cutoff becomes supplemental; no official timestamp is ineligible. | Cohort / P0 |
| AC-COH-04 | Pre-cutoff omitted candidate hardblocks supplemental; cutoff unchanged. | Cohort / P0 |
| AC-EH-01 | PPDB/manual/transfer entry is recorded post-lock in approval transaction; request/re-registration time is not used as official entry. | Entry history / P0 |
| AC-EH-02 | Pending/rejected corrections do not change effective entry; append-only and idempotent approval/retry. | Correction / P0 |
| AC-EH-03 | Cross-cohort correction locks sorted; published identity remains unchanged; conflict event appended. | Correction / P0 |
| AC-LEG-01 | Exact legacy collision without approval blocks with no issuance; no parsing as provenance. | Legacy / P0 |
| AC-LEG-02 | Approved reservation creates one permanent reserved slot atomically; rejected request creates none. | Reservation / P0 |
| AC-SEQ-01 | Two allocators concurrent on same tuple produce distinct monotonic slots; rollback consistent. | Sequence / P0 |
| AC-SEQ-02 | Rombels share sequence; grades separate; 999 advances to 1000 without truncation. | Sequence / P0 |
| AC-REG-01 | Runtime SQL cannot update/delete issued registry/slot/history link; DBA limitation documented. | Registry / P0 |
| AC-REG-02 | Only NULL→matching registry issued NIS is allowed; later update/clear/duplicate rejected via app and SQL. | Registry / P0 |
| AC-REG-03 | Username collision does not auto-merge/rename; failure produces no partial issuance. | Account / P0 |
| AC-PUB-01 | Initial includes eligible PPDB + transfer across grades/rombels, uses approved comparator and consistent snapshot. | Initial publication / P0 |
| AC-PUB-02 | Concurrent initial requests with same/different keys cannot double issue; idempotency/conflict contract holds. | Initial publication / P0 |
| AC-PUB-03 | Injected mid-transaction failure rolls back registry/slots/NIS/username/high-water/cutoff/batch; attempt failure logged separately. | Atomicity / P0 |
| AC-PUB-04 | Retry same/different key and already-published batch are idempotent; same batch only. | Recovery / P0 |
| AC-SUP-01 | Post-cutoff eligible student gets next sequence; cutoff unchanged; no duplicate NIS. | Supplemental / P0 |
| AC-SUP-02 | Ordering is owner-approved and deterministic for timestamp/batch-boundary variations on all runtimes. | Supplemental / P0 |
| AC-SUP-03 | Post-initial transfer uses evidenced entry grade and valid placement; existing NIS unchanged. | Supplemental / P0 |
| AC-PLACE-01 | Required candidate without valid placement hardblocks; no sequence/registry/NIS commit. | Placement / P0 |
| AC-PLACE-02 | PPDB/Siswa/link contradiction detected and blocks; no auto-repair. | Placement / P0 |
| AC-PPDB-01 | Re-registration/confirmation creates no NIS; pre-publish notification has no false NIS/credential; post-publish notification idempotent. | PPDB / P0 |
| AC-TU-01 | Manual/import/sheet path cannot set new NIS; all membership/placement mutates via service/lock; legacy import separate. | TU/import / P0 |
| AC-AUTH-01 | Only canonical relation; orphan/duplicate/conflict handled; pre-NIS login denied per approved policy. | Auth / P0 |
| AC-AUTH-02 | Username sync follows registry and approved policy; rename/collision does not auto-relink. | Auth / P0 |
| AC-DEL-01 | Soft delete/restore/force-delete/recreate retains registry/slot/high-water; old NIS cannot be reused. | Delete / P0 |
| AC-CON-01 | Opposite-order multi-cohort requests acquire sorted locks; bounded rollback/retry without duplicate. | Concurrency / P0 |
| AC-CON-02 | Crash/uncertain commit reconciles started attempt by idempotency key before retry. | Recovery / P0 |
| AC-OBS-01 | Outcomes, actor, key, revision, snapshot, comparator and safe error logged without excessive PII/secrets. | Audit / P1 |
| AC-OBS-02 | Golden fixture exact order and metadata match across approved web/CLI/OS; actual locale not root; otherwise publish denied. | Comparator / P0 |
| AC-ACC-01 | Outbox intent commits atomically with NIS; worker failure leaves NIS issued and account non-login; same key retry does not allocate NIS. | Provisioning / P0 |
| AC-ACC-02 | Pending/processing/succeeded/retryable/manual-review state transitions are audited, idempotent and secret-free. | Provisioning / P0 |
| AC-ACC-03 | Mail failure is retried independently and does not roll back NIS/account; duplicate notification is suppressed. | Notification / P1 |
| AC-ACC-04 | In-transaction account option, if selected, rolls back issuance on required DB account failure; email remains outside atomic DB boundary. | Provisioning / P0 |
| AC-TIME-01 | `recorded_at`, `effective_at`, `official_entry_at` have distinct semantics, UTC technical storage, immutable original audit event. | Timestamp / P0 |
| AC-TIME-02 | Official entry is set after cohort lock in the same committed transaction as approval/history. | Timestamp / P0 |
| AC-TIME-03 | Equal timestamps resolve by approved monotonic cohort ordinal; supplemental tuple ordering matches golden results. | Timestamp/order / P0 |
| AC-TIME-04 | Initial watermark boundary is atomic under cohort lock; `<= watermark` membership and later entry behavior are tested; effective date correction cannot alter issued NIS. | Cutoff / P0 |

## 26. Implementation Boundary

### 26.1 May enter implementation planning (not implementation authorization)

- Break proposed entities into additive migration design and dependency/FK installation plan.
- Map services and workflows to one publisher/cohort lock protocol.
- Draft synthetic acceptance fixtures, database feature matrix and comparator golden fixture proposal.
- Plan privacy-safe read-only legacy inventory, backup/restore evidence collection and least-privilege role proposal.
- Plan account outbox/state machine and timestamp model alternatives for owner decision.

These are planning artifacts only; approval, target selection and any separate implementation authorization remain necessary.

### 26.2 Must not be implemented until blockers close

- Final DB-specific migrations/constraints/triggers and account-link backfill.
- Comparator or permanent initial ordering without approved golden output/runtime evidence.
- Legacy registry/history backfill, reservation or year/grade mapping without authorized inventory/disposition.
- Publication allocator, timestamp cutoff/watermark or supplemental ordering before OD-03/08/14/15 and database/concurrency validation.
- Account provisioning/username synchronization before OD-04/10/11/18 and username alignment is resolved.
- Delete/relink enforcement until OD-05/11/12/16/18 and FK/trigger semantics are tested.

### 26.3 Must not be activated

Every NIS issuance path remains inactive until all relevant owner decisions are explicitly approved, P0 acceptance tests pass on the target engine/runtime, legacy disposition is approved, least privilege and recovery are proven, all writers are centralized, and owner signs off on staged activation. No existing workflow should be redirected or enabled by this consolidation.

### 26.4 Dependency-oriented future work

1. Source availability and owner baseline confirmation.
2. Target DB/runtime and role decision; evidence matrix.
3. Legacy inventory/reconciliation plan, account canonical relation and transfer/placement policy.
4. Schema foundation with carefully staged FKs and non-destructive rollback rules.
5. Lock coordinator, revision, timestamp/ordinal and eligibility/placement resolver.
6. Registry/slot/sequence and approved legacy reservation.
7. Comparator golden gate and publication service.
8. Account provisioning/username policy and all existing workflow integrations.
9. P0 migration, concurrency, recovery, direct-SQL, auth, volume and restore gates.
10. Dry run, owner sign-off, staged activation and monitoring.

## 27. Open Questions & Conflicts

1. Required Tahap 2A.1 and 2A.2 documents are absent from the workspace. Their contents may affect historical rationale; obtain them and compare before calling this consolidation complete.
2. Supplemental ordering differs between 2A.7R formulations: timestamp/name/ID versus timestamp/ordinal/name/ID. This document recommends the latter for equality determinism, still pending OD-03/08/14.
3. Tahap 2A.3 places `users.username` sync within publication transaction; 2A.7R recommends post-publication provisioning. Owner must decide whether username is created/reserved at issuance or worker time.
4. No owner decision is recorded for the transactional outbox, account login eligibility, temporary/prepublication account, username conflict, or email policy.
5. No owner-approved comparator/golden order; actual local locale remains `root`.
6. Source/precision/monotonicity of authoritative clock and exact initial boundary remain to be approved/tested. The watermark proposal is not implemented.
7. `siswa.user_id` is the login path and candidate canonical FK, not an approved canonical relationship; `users.siswa_id` also exists.
8. Existing schema local has no NIS registry or permanent slot ledger and has a placement uniqueness gap; target-engine DDL/constraint strategy is not validated.
9. Local DB grant is too broad for a least-privilege runtime model; no privilege change is authorized or performed.
10. Local data is sparse; no capacity, historic provenance or production-data claim is possible.
11. Mutation paths were mapped in available audits, but external ETL, operator SQL, deployment jobs or other out-of-repository writers are not excluded.

## 28. Architecture Dependency Graph

Edges mean “must be resolved before finalizing dependent decision,” not “must be discussed first.” Matches Tahap 2A.7R.

```text
OD-02 -> OD-03
OD-10, OD-11, OD-18 -> OD-04
OD-18 -> OD-05
OD-12, OD-15, OD-18 -> OD-06
OD-14, OD-15, OD-18 -> OD-07
OD-03, OD-14, OD-15 -> OD-08
OD-07, OD-15, OD-18 -> OD-09
OD-11, OD-18 -> OD-10
OD-01 -> OD-12
OD-01, OD-12 -> OD-13
OD-01 -> OD-14
OD-05, OD-11, OD-12, OD-18 -> OD-16
OD-01, OD-12, OD-13, OD-15 -> OD-17
```

Independent roots: OD-01, OD-02, OD-11, OD-15, OD-18. No self-edge or cycle is present. OD-18 is independent and is a governance prerequisite for decisions requiring approval/actors. OD-13 depends on OD-01/12; OD-17 depends on OD-01/12/13/15.

## 29. Final Consolidated Verdict

# CONSOLIDATION INCOMPLETE

### Successfully consolidated

- Available Tahap 2A.3–2A.7R baseline and proposed architecture.
- Initial versus supplemental workflows, all-or-nothing publication, cohort/sequence locking, registry/ledger and legacy policy.
- OD-04 account transaction alternatives and outbox recommendation, OD-14 timestamp semantics and the corrected dependency graph.
- Local evidence, legacy/account/placement limitations, 20 TE IDs, 12 TV IDs, BL-01–10 and all 35 original acceptance criteria plus 8 refinement criteria.
- All OD-01–18 remain `PENDING`.

### Still unresolved or unproven

- Mandatory Tahap 2A.1 and 2A.2 sources are unavailable, so the complete source-set acceptance criterion is not met.
- Owner decisions remain pending, especially database/runtime scope, comparator, account/username handling, timestamp/watermark, legacy reservation, canonical relationship, privileges, batch limits and roles.
- Technical tests for target database, direct SQL, locking, rollback/retry, backup/restore, capacity, comparator parity and full writer coverage have not passed or been run.

### What blocks planning, implementation and activation

- **Implementation planning:** broad sequencing can be planned, but source gaps and unresolved design alignments should be recorded as dependencies.
- **Implementation:** affected code/schema is blocked by relevant pending OD, target-engine/runtime evidence, legacy/account reconciliation and owner policy.
- **Activation:** all publication paths remain blocked until every relevant P0 acceptance criterion is implemented and passes on the approved target and owner explicitly authorizes activation.

This verdict assesses the consolidation deliverable only. It is not `READY FOR IMPLEMENTATION`, not schema approval, not proof legacy data is safe and not approval for publication.

## 30. Next Recommended Stage

1. Locate/provide Tahap 2A.1 and 2A.2; compare their decisions and evidence against §§7–28.
2. Owner resolves OD-04, OD-08, OD-10, OD-11, OD-14 and OD-18 interactions, particularly username reservation and timestamp/ordinal policy.
3. Owner/Ops establish target DB/runtime and least-privilege roles; technical tests use disposable database and synthetic data only.
4. Approve a comparator and golden fixture before any initial NIS ordering.
5. Complete authorized legacy inventory and relationship/placement disposition before backfill.
6. Only after explicit project-owner instruction may a separate implementation stage begin. Do not implement or activate based on this document alone.
