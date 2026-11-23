# TAHAP 2A.12 — IMPLEMENTATION DEPENDENCY PLAN

**Jenis:** Rencana berurutan; tidak ada implementasi yang dijalankan  
**Status:** `DESIGN BASELINE SELECTED` / `IMPLEMENTATION AUTHORIZATION: NOT AUTHORIZED`

## 1. Sequencing Principles

1. Tutup governance/owner authority and select target environment before irreversible schema or publication activation.
2. Verify legacy data and entry evidence before allocating any value; avoid auto-repair.
3. Enforce database invariants before exposing writers.
4. Establish placement/cohort eligibility, permanent allocator, and exactly-one batch guard before the initial publisher.
5. Commit batch, NIS registry/slots, cutoff, and account-provisioning intent under one atomic visibility contract.
6. Release worker only after commit; retries consume the same intent and never allocate another NIS.
7. Enable supplemental, correction, and deletion/relink paths only after their shared identity and authorization controls exist.
8. No path is authorized to publish until implementation-specific acceptance criteria and applicable school policy approvals are evidenced.

The graph below is an engineering sequence, not a policy approval order. Workstream I governance/security starts in parallel and is a gate before privileged publication or exception operations.

```text
I (governance, audit, privilege design) ──┐
A (DB foundation) ── B (history/reconcile) ── D (placement/cohort locks) ─┐
           └────────────── C (registry/allocator) ─────────────────────────┤
                                      └──────────────────────────────────── E (initial publication)
I ────────────────────────────────────────────────────────────────────────┘
E + A ── F (async account/outbox)
E + B + C + D + F ── G (supplemental)
B + C + F + I ── H (correction/delete/restore/relink)
```

## 2. Workstreams

### A — Database foundation and integrity constraints

- **Tujuan:** Confirm target engine/version and install additive schema foundations for NIS cohort, registry/sequence slots, batches, entry history, outbox, audit and required constraints.
- **Prasyarat:** OD-01/12 target/privilege direction; schema-drift and legacy preflight; approval for schema/data handling; target backup/restore procedure.
- **Modul/file yang mungkin terdampak berdasarkan audit:** `database/migrations/*`; model `Siswa`, `User`, `PenempatanSiswa`, `PpdbPendaftar`; `config/database.php`; possibly database grants/deployment scripts outside the repository. This is a forecast, not an instruction to edit.
- **Perubahan data/migration mungkin:** additive cohort/entry-history/batch/registry/sequence-slot/outbox/audit structures; NIS/canonical-link indexes/FKs/constraints; legacy-safe references. Exact DDL depends on selected engine. No DDL prepared here.
- **Risiko:** Engine-specific semantics, existing duplicates, FK delete actions, lock order/deadlocks, backfill, rollback/recovery, runtime privilege bypass.
- **Acceptance criteria:** target migration applies/rolls back or forward-fix policy is documented; schema invariants and indexes are proved on target; valid/invalid/null/collision cases behave as required; direct unauthorized mutations denied; restore drill passes.
- **Ketergantungan:** Foundation for B, C, D, E, F, G, H. I must define privilege boundary before activation.
- **Urutan:** First schema implementation after target selection and migration preflight; foundational gate.

### B — Cohort, entry history, and legacy reconciliation

- **Tujuan:** Determine official cohort, entry year/grade, provenance, legacy issued values and conflicts without inferring from current class/name.
- **Prasyarat:** A's approved storage direction; authorized data access and evidence/approver roles; OD-06/07/14/15/18 policy confirmation and source recovery handling.
- **Modul/file yang mungkin terdampak:** `app/Models/Siswa.php`; `app/Models/Angkatan.php`; `app/Models/PpdbPendaftar.php`; `app/Imports/SiswaImport.php`; `app/Imports/SiswaSheetImport.php`; PPDB enrollment/transfer workflows; new reconciliation tooling/storage to be designed.
- **Perubahan data/migration mungkin:** append-only entry evidence/history and per-record reconciliation disposition, references to source evidence and authorized legacy NIS slots. No auto-normalization/remap.
- **Risiko:** Incomplete historical snapshot, soft/force-deleted data, duplicate identities, mistaken transfer entry grade, privacy/exposure of student data.
- **Acceptance criteria:** authorized complete inventory with active/deleted/history scope; each candidate has provenance/disposition; every collision blocks allocation pending explicit reconciliation; transfer entry-year/grade evidence is reviewable; no NIS/slot is silently changed or reused.
- **Ketergantungan:** Depends on A and I; must precede C and D eligibility finalization.
- **Urutan:** Second, after schema/authority/data access are ready; finish before opening allocator.

### C — NIS registry, sequence slots, and allocator

- **Tujuan:** Provide the only authorized allocation service with permanent monotonic slots and uniqueness scoped to the approved year-code/entry-grade cohort.
- **Prasyarat:** A target constraints/locking; B reconciled legacy slots and mapping; approved OD-03/08/14/15; ICU/runtime exact comparator; I privilege boundary.
- **Modul/file yang mungkin terdampak:** `app/Http/Controllers/PPDB/PendaftarController.php::generateNis()`; manual creation in `app/Http/Controllers/Tu/SiswaController.php`; both importers; new allocator/service/model and migrations; `GenerateUsername` only where canonical identity is affected.
- **Perubahan data/migration mungkin:** permanent registry/slot, high-water mark or equivalent, exact cohort key, unique guards and append-only issue records. No schema selected beyond baseline invariants.
- **Risiko:** Race conditions, lexical max parsing, overflow/truncation, legacy collision, bypass from manual/import writers, nondeterministic comparator, accidental reallocation on retry.
- **Acceptance criteria:** one allocation authority; sequence uniqueness under concurrent writers; monotonicity/no truncation; every legacy issued value reserved; retries are idempotent; no update/delete/reuse; unsupported locale and ambiguous mapping fail closed.
- **Ketergantungan:** A+B+D prerequisites; E and G consume it. Do not expose until all writers are routed through shared controls.
- **Urutan:** After reconciliation and placement contract are fixed, before initial publication.

### D — Placement and cohort locking

- **Tujuan:** Make candidate eligibility deterministic and serialize operations per cohort before any number assignment.
- **Prasyarat:** A DB lock/unique capabilities, B entry evidence, OD-07/09/14/15/17 decisions, I authorization.
- **Modul/file yang mungkin terdampak:** `app/Http/Controllers/Tu/PenempatanSiswaController.php`; PPDB `PendaftarController::penempatanKelas/simpanPenempatanKelas`; `SiswaImport` and `SiswaSheetImport`; `PenempatanSiswa` model; placement/academic-year migrations and rollover paths.
- **Perubahan data/migration mungkin:** validated placement state/constraints, cohort lock key, eligibility/cutoff snapshot and/or immutable entry history.
- **Risiko:** Existing duplicate or contradictory placement; grade/year mismatch; current class incorrectly treated as entry grade; row lock on wrong object; race with class assignment/rollover/import.
- **Acceptance criteria:** one approved placement resolver; missing/duplicate/inactive/contradictory placement hardblocks before allocation; cohort lock serializes eligibility and assignment; year/grade/cutoff persisted; tests cover transfers, current placement mismatch and concurrency.
- **Ketergantungan:** A+B+I; must precede C and E.
- **Urutan:** Before allocator activation, in parallel with reconciliation design after A.

### E — Initial publication transaction

- **Tujuan:** Publish one complete initial batch per cohort atomically with NIS values, registry/slots, cutoff, batch success, and account-provisioning intent.
- **Prasyarat:** A/B/C/D complete; OD-03/09/13/14/15/17 approved; I roles/authorization and target rollback/recovery evidence.
- **Modul/file yang mungkin terdampak:** PPDB enrollment and confirmation in `app/Http/Controllers/PPDB/PendaftarController.php`; `routes/ppdb-admin.php`; NIS/PPDB models; new publication service/batch records; mail/notification boundary.
- **Perubahan data/migration mungkin:** one initial batch/cohort unique guard, transaction-visible registry/slots/cutoff/outbox intent, batch outcome/audit record.
- **Risiko:** Partial visibility; two concurrent initial publications; timeout with uncertain commit; external email inside transaction; invalid/late candidate; duplicate retry allocates a new sequence.
- **Acceptance criteria:** exactly-one initial batch; all-or-nothing final visibility; staging hidden; durable intent committed with publication; duplicate submission/retry returns the same result; injected errors leave no partial publication; only committed batch is externally visible.
- **Ketergantungan:** A+B+C+D+I. F runs only after publication commit.
- **Urutan:** First issuer; no supplemental or production activation until passed.

### F — Account provisioning and outbox worker

- **Tujuan:** Provision accounts asynchronously from transactional intent, preserving canonical identity and gating login until verified.
- **Prasyarat:** E outbox contract; OD-04/10/11/18 confirmation; A target queue/db roles; canonical relationship reconciliation; worker runtime/operations plan.
- **Modul/file yang mungkin terdampak:** `app/Http/Requests/Auth/LoginRequest.php`; `app/Models/User.php`; `app/Models/Siswa.php`; `PendaftarController`; `app/Console/Commands/GenerateUsername.php`; `config/queue.php`; `app/Mail/*`; new outbox model/job/worker; related migrations and route/auth tests.
- **Perubahan data/migration mungkin:** transactional outbox, provisioning lifecycle/status, unique idempotency key, attempt/audit records, canonical one-to-one constraints and safe index migration.
- **Risiko:** Job runs before commit, duplicate account, username collision, one-sided relationship, active login before relationship valid, credentials exposure, worker retry alters identity, production worker absent.
- **Acceptance criteria:** intent and publication atomic; worker observes only committed intent; state transitions match AC-ACC-02-B; same intent key on retry; no new NIS allocation; login denied until canonical link/account success; notification is separate; credentials/tokens excluded from logs.
- **Ketergantungan:** A+E and identity reconciliation from B; I privilege/authorization.
- **Urutan:** After E contract exists; worker must remain disabled until acceptance tests and operational monitoring pass.

### G — Supplemental issuance

- **Tujuan:** Issue subsequent entries in the already-published cohort according to permanent official timestamp/ordinal ordering without reopening initial publication.
- **Prasyarat:** B/C/D/E and F contract; OD-07/08/09/14/15; comparator/runtime parity and explicit cutoff.
- **Modul/file yang mungkin terdampak:** Current PPDB supplemental/transfer workflow (not identified as a dedicated NIS issuer in source); `PendaftarController`, `PenempatanSiswaController`, `SiswaImport`, `SiswaSheetImport`, new supplemental publication service.
- **Perubahan data/migration mungkin:** permanent cohort ordinal, `official_entry_at`, supplemental batch/ordering metadata, lock/idempotency and audit records if not included in A.
- **Risiko:** Reordering prior NIS, timestamp ties, using current class for entry grade, accidentally opening a second initial batch, order changes with locale or runtime.
- **Acceptance criteria:** initial values unchanged; every later eligible student receives next permanent slot; ordering follows approved tuple with deterministic ties; retries cannot allocate another number; transfer evidence hardblocks when incomplete.
- **Ketergantungan:** B+C+D+E+F+I.
- **Urutan:** After successful initial publication design; before any supplemental activation.

### H — Correction, delete, restore, and relink

- **Tujuan:** Safely change non-identity data or lifecycle links while preserving permanent NIS/registry slots and requiring evidence/approval for identity relink.
- **Prasyarat:** A immutable registry enforcement; B reconciliation/evidence; C stable identity; F canonical account behavior; OD-05/11/12/16/18; I audit and separation of duties.
- **Modul/file yang mungkin terdampak:** `app/Http/Controllers/Tu/SiswaController.php` update/delete/restore/force-delete/bulk actions; `app/Models/Siswa.php`; `User`, PPDB and placement relationships; migrations/FKs; account login/logout/relink paths.
- **Perubahan data/migration mungkin:** append-only correction/relink events, durable registry independent of Siswa row, retention/anonymization rules and FK behavior.
- **Risiko:** Force-delete frees a unique NIS; cascade removes history; restore creates identity collision; name-only relink merges students; deleting user breaks login but should not erase NIS.
- **Acceptance criteria:** deletion cannot remove or free issued NIS/slot; restore retains same identity after verification; correction cannot change NIS; relink requires evidence/approval and audited change; no auto-merge/reuse; tests cover all lifecycle paths.
- **Ketergantungan:** A+B+C+F+I.
- **Urutan:** After registry and canonical account foundations; no force-delete or relink exception before acceptance.

### I — Authorization, audit, and operational hardening

- **Tujuan:** Define least-privilege operations, independent preparation/verification/publication/approval, auditability, operational monitoring and recovery.
- **Prasyarat:** Formal authority identification and OD-12/18 policy; target environment and operational owner identified.
- **Modul/file yang mungkin terdampak:** `routes/tu.php`, `routes/ppdb-admin.php`; `app/Policies/*`; `app/Http/Middleware/*`; auth/login; database grants/deployment and queue operations; audit/monitoring services.
- **Perubahan data/migration mungkin:** append-only audit events, actor/approval evidence references, role/privilege mappings, operational outbox/queue visibility.
- **Risiko:** Developer/operator self-approval, bypass via alternate writer/direct SQL, broad privilege blast radius, unaudited exceptions, missing worker/recovery responsibilities.
- **Acceptance criteria:** approved identity/role/authority matrix; allow/deny coverage per sensitive action; separation and no self-approval for critical action; least-privilege runtime verified with direct mutation denial; break-glass logged; monitoring, retry, restore and rollback/forward-fix runbooks proven.
- **Ketergantungan:** Cross-cutting A–H. Authority/privilege definition starts first; operational acceptance blocks publisher and exception path.
- **Urutan:** Begin before A's target DDL; gate before E/G/H activation, and complete before production.

## 3. Required Gates Before Any Implementation Authorization

1. Identify formal policy owner/approvers and obtain applicable OD approvals; preserve distinction between policy approval and technical sign-off.
2. Recover or formally disposition SRC-GAP-01/02; keep open until evidence-backed.
3. Select target DB/runtime and prove parity; resolve ICU actual-locale fail-closed blocker and approve golden ordering.
4. Approve entry-evidence, placement, cohort, cutoff, correction, delete/relink, and separation-of-duties rules.
5. Reconcile complete legacy/account/placement data without auto-remap or auto-link.
6. Approve schema, privilege, backup/restore, benchmark and recovery design.
7. Implement and pass targeted migration, concurrency, failure-injection, auth, importer, lifecycle, and recovery acceptance tests.
8. Authorize activation separately; this plan grants no implementation or publication authority.

## 4. Plan Verdict

- **IMPLEMENTATION DEPENDENCY PLAN:** `COMPLETE`
- **IMPLEMENTATION AUTHORIZATION:** `NOT AUTHORIZED`
- **TECHNICAL READINESS:** `NOT READY`

This is a sequence and acceptance contract only; no source files or schemas are changed.
