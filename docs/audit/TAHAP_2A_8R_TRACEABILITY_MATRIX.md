# TAHAP 2A.8R — TRACEABILITY MATRIX

**Dokumen refinement:** [TAHAP_2A_8R_ARCHITECTURE_CONSISTENCY_REFINEMENT.md](./TAHAP_2A_8R_ARCHITECTURE_CONSISTENCY_REFINEMENT.md)  
**Status:** Pemetaan perubahan dokumentasi; bukan bukti implementasi atau persetujuan  
**Source gap:** Tahap 2A.1 dan 2A.2 belum ditemukan.

> **Correction precedence:** The option-specific AC-ACC-02, Hybrid two-dimensional model, notification boundary, and corresponding failure/traceability mappings in [TAHAP_2A_8R1_TRACEABILITY_MATRIX.md](./TAHAP_2A_8R1_TRACEABILITY_MATRIX.md) supersede earlier conflicting mappings below. No evidence or blocker status is upgraded.

## 1. Source Key

| ID | Sumber | Penggunaan dalam refinement |
|---|---|---|
| SRC-23 | Tahap 2A.3 — `TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md` | Baseline atomic publication, username sync proposal, ledger, locking, workflow. |
| SRC-24 | Tahap 2A.4 — `TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md` | OD-01–18, TE/AC/BL IDs dan acceptance baseline. |
| SRC-25 | Tahap 2A.5 — `TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md` | Account/User–Siswa and writer/evidence findings. |
| SRC-26 | Tahap 2A.6 — `TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md` | Local-only schema/runtime/account evidence; not deployment proof. |
| SRC-27 | Tahap 2A.7 — `TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md` | Owner option/rationale and pending approvals. |
| SRC-28 | Tahap 2A.7R — `TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md` | OD-04 transaction/outbox alternatives, login gate, retry/status, OD-14 ordinal semantics. |
| SRC-29 | Tahap 2A.8 — `TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md` | Consolidated baseline, workflows, AC-ACC and traceability conflict CON-02. |
| SRC-30 | Tahap 2A.8 traceability — `TAHAP_2A_8_TRACEABILITY_MATRIX.md` | Existing NIS rules, OD/evidence/blocker/AC relations and CON-01–03. |
| SRC-31 | Tahap 2A.8 changelog — `TAHAP_2A_8_CHANGELOG.md` | Records prior username timing conflict and intentionally pending decisions. |
| SRC-32 | Tahap 2A.8R attachment/user brief | Scope and acceptance for this refinement. |
| SRC-GAP-01 | Tahap 2A.1 | Not found in workspace; cannot map or infer. |
| SRC-GAP-02 | Tahap 2A.2 | Not found in workspace; cannot map or infer. |

## 2. Invariant and Conflict Traceability

| ID | Invariant / conflict | Decision | Evidence | Blocker | Acceptance criteria | Refinement section |
|---|---|---|---|---|---|---|
| NIS-R04/R06 | Issuance is atomic; issued NIS/slot is permanent; retries do not reuse or reissue. | OD-01/12/13/17 | TE-08–12, TE-18–20; TV-01/04/05/08/09 | BL-01/06/09/10 | AC-PUB-03/04; AC-SEQ-01/02; AC-REG-01/02; AC-CON-01/02; AC-ACC-06 | §§3, 5, 6, 8 |
| NIS-R07 | Entry/placement/PPDB candidate must be valid; confirmation itself does not issue NIS. | OD-04/07/09/11/18 | TE-13/15/17/18; TV-07/10/11/12 | BL-05/08/10 | AC-PPDB-01; AC-PLACE-01/02; AC-TU-01 | §§5, 8, 10 |
| NIS-R05 | Name changes do not change issued NIS; approved comparator governs original ordering. | OD-03/05 | TE-06/07; TV-03 | BL-03 | AC-PUB-01; AC-SUP-02; AC-OBS-02 | §§3, 8 |
| NIS-R09 | Supplemental order recommendation includes permanent ordinal tie-break; effective date does not reorder issuance. | OD-03/08/14/15 | TE-06–09/14/17; TV-03/08/11 | BL-03/07/08 | AC-SUP-02; AC-TIME-03/04 | §§3, 8 |
| AUTH-R01 | Account provisioning success is distinct from NIS issuance success. | OD-04/10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-ACC-01/02/04/06/07/08 | §§4–8 |
| AUTH-R02 | Notification delivery is distinct from account provisioning and publication. | OD-04/10/18 | TE-15/18; TV-10/12 | BL-08/10 | AC-ACC-03; AC-PPDB-01 | §§5, 6, 8 |
| AUTH-R03 | Username candidate/reservation is not an active account or login entitlement. | OD-04/10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-REG-03; AC-ACC-05/07/08; AC-AUTH-01/02 | §§4, 7, 8 |
| CON-02 | Publication-time username sync vs post-commit outbox had contradictory/unconditional outcomes. | OD-04; related OD-10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-PUB-03; AC-REG-03; AC-ACC-01–08 | §§3–8 |
| SRC-GAP-01/02 | Historical source coverage incomplete. | All OD remain PENDING until applicable review | Source documents unavailable | Historical traceability blocker | No acceptance claim closes this gap | §§2, 10, 12 |

## 3. Alternative-to-Outcome Mapping

| Outcome | Opsi A | Opsi B | Hybrid | Primary criteria |
|---|---|---|---|---|
| Username collision pre-commit | Hardblock/rollback if account/username is required inside transaction. | May be detected by preflight, but must also support a post-commit collision. | Failed claim prevents publication only if owner chooses claim as prerequisite and claim is valid. | AC-REG-03, AC-ACC-05 |
| Account DB failure | Roll back entire required publication transaction. | NIS remains issued; intent retryable/manual review. | Reservation may remain pending/expire; post-commit NIS stays issued. | AC-PUB-03, AC-ACC-01/04/05 |
| Worker failure after commit | Not applicable to synchronous required account write; email remains independent. | NIS remains issued; retry same intent. | NIS remains issued; reservation/account recovery only. | AC-ACC-01/02/06 |
| Username collision after commit | Not applicable if atomically guaranteed, otherwise design has failed its stated premise. | NIS remains issued; manual review, no auto-repair. | NIS remains issued; manual review if claim integrity cannot be established. | AC-REG-03, AC-ACC-05/08 |
| Email failure | No rollback of NIS/account. | No rollback of NIS/account. | No rollback of NIS/account. | AC-ACC-03 |
| Login eligibility | Deny until account/link transaction and approved policy are satisfied. | Deny until provisioning succeeds and canonical link is valid. | Reservation never grants login; require successful provisioning. | AC-ACC-07 |

## 4. Owner Decision Traceability

All approvals remain `PENDING`; the table identifies impact, not a decision.

| OD | Refinement impact | Refined section | Status |
|---|---|---|---|
| OD-01 | Target engine determines transaction/claim semantics. | §§4, 11 | PENDING |
| OD-02 | Worker/web runtime parity. | §§2, 11 | PENDING |
| OD-03 | Name comparator; no ordering policy selected. | §§3, 8 | PENDING |
| OD-04 | Select/define account lifecycle and publication boundary. | §§3–9 | PENDING |
| OD-05 | Authority for post-publication account/identity correction. | §§7, 9 | PENDING |
| OD-06 | Legacy NIS reservation remains distinct from username reservation. | §§7, 10 | PENDING |
| OD-07 | Entry evidence unchanged. | §§3, 8 | PENDING |
| OD-08 | Supplemental ordering; ordinal remains recommendation. | §§3, 8 | PENDING |
| OD-09 | Placement validity unchanged. | §§5, 8 | PENDING |
| OD-10 | Username collision/candidate policy. | §§4, 7, 9 | PENDING |
| OD-11 | Canonical User–Siswa relationship; no relink/backfill. | §§7, 9 | PENDING |
| OD-12 | Privileges and direct SQL trust boundary. | §§4, 10, 11 | PENDING |
| OD-13 | Batch transaction size and account write effect. | §§4, 8, 10 | PENDING |
| OD-14 | Timestamp/ordinal/cutoff unchanged. | §§3, 8 | PENDING |
| OD-15 | Year-code mapping unchanged. | §§3, 9 | PENDING |
| OD-16 | Delete/restore/relink and reservation/account retention. | §§7, 9 | PENDING |
| OD-17 | One initial batch remains unchanged. | §§5, 6 | PENDING |
| OD-18 | Manual review roles and separation of duties. | §§7, 9, 11 | PENDING |

## 5. Technical Evidence Traceability

| Evidence ID | Prior status (as recorded in Tahap 2A.8) | Refinement relevance | Required next proof |
|---|---|---|---|
| TE-01/08/09/10 | Local/partial; target behavior not established | Atomic publication, unique claim, transaction rollback | Target engine and disposable transaction/concurrency tests |
| TE-13/15 | Local/sparse or partial | User–Siswa, usernames and collision inventory | Authorized complete reconciliation and collision fixtures |
| TE-18 | Partial writer map | Every account/user creation path must honor claim and login gate | Complete application/external writer inventory |
| TE-19/20 | Unverified | Recovery and batch transaction capacity | Restore drill and target-sized load/failure test |
| TV-01/04 | Partial | Unique constraints, cross-table claims and FK behavior | Target-specific schema/constraint test |
| TV-05 | Unverified | Least privilege and direct-SQL boundaries | Role matrix and deny tests |
| TV-07 | Partial | Canonical relationship and username identity | Owner approval plus authorized data reconciliation |
| TV-08/09 | Unverified | Duplicate worker, uncertain commit, retry/recovery | Concurrency/crash/retry/restore tests |
| TV-10/12 | Partial/unverified | Authorization, notification and activation | Full writer/role map and staged activation evidence |

Prior statuses are not upgraded by this documentation refinement.

## 6. Blocker Traceability

| Blocker | Scope relation | Closure condition | Refinement criteria |
|---|---|---|---|
| BL-01 | DB transaction/outbox/claim semantics | OD-01 + target transaction, uniqueness and lock evidence | AC-PUB-03, AC-ACC-05/06 |
| BL-02 | Web/worker runtime | OD-02 + effective target SAPI/extensions parity | AC-ACC-06/07 |
| BL-03 | Comparator | Owner golden order and parity; unchanged by this stage | AC-SUP-02, AC-TIME-03 |
| BL-04 | Legacy inventory | Authorized complete NIS reconciliation; username claim is not substitute | AC-DB-02, AC-LEG-01/02 |
| BL-05 | User–Siswa/account identity | OD-11, complete reconciliation and no unresolved identity conflicts | AC-AUTH-01/02, AC-ACC-07/08 |
| BL-06 | Ledger protection | OD-12 and least-privilege/direct-SQL proof | AC-REG-01/02 |
| BL-07 | Timestamp/year-code | OD-14/15 and target boundary tests | AC-TIME-01–04 |
| BL-08 | Account/username policy | OD-04/10/11/18 and selected-branch acceptance | AC-REG-03, AC-ACC-01–08 |
| BL-09 | Batch volume/recovery | OD-13 and benchmark/rollback/restore evidence | AC-PUB-03/04, AC-ACC-06 |
| BL-10 | Writers/authorization | Complete writer map, OD-18, centralized worker/login controls | AC-TU-01, AC-AUTH-01/02, AC-ACC-05–08 |
| SRC-GAP-01/02 | Historical review | Locate/read Tahap 2A.1 and 2A.2 and compare | No criteria substitute for missing source |

**No blocker is closed by this matrix.**

## 7. Acceptance Criteria Traceability

All criteria are proposed/pending/not run. Existing criteria retain their IDs; the refinement document defines the branch-specific interpretation.

| AC ID | Source/constraint | Refined outcome | Section |
|---|---|---|---|
| AC-PUB-03 | SRC-24 §5; SRC-29 §25 | Common publication rollback; account rollback only under A; B outbox intent rolls back with failed publication. | §8 |
| AC-REG-03 | SRC-24 §5; SRC-29 §25 | A pre-commit collision blocks; B post-commit collision keeps NIS and manual-reviews account; Hybrid proves claim. | §8 |
| AC-ACC-01 | SRC-28 §4; SRC-29 §25 | Outbox atomicity and post-commit failure semantics apply to B; not a universal synchronous requirement. | §8 |
| AC-ACC-02 | SRC-28 §4; SRC-29 §25 | Explicit provisioning status/transition/idempotency contract. | §5, §8 |
| AC-ACC-03 | SRC-28 §4; SRC-29 §25 | Notification failure/retry independent of NIS/account. | §§5–6, §8 |
| AC-ACC-04 | SRC-28 §4; SRC-29 §25 | Synchronous account DB failure rolls back only if A is selected; email remains external. | §8 |
| AC-ACC-05–08 | SRC-32; proposed additions | Hybrid claim uniqueness; replay/retry; login gate; manual resolution without auto-repair. | §§7–8 |
| AC-PPDB-01 | SRC-24 §5; SRC-25 §7 | No NIS on re-registration; notifications use only committed/valid account state. | §§5, 8 |
| AC-AUTH-01/02 | SRC-24 §5; SRC-28 §4 | Canonical relationship and account policy remain owner-selected; collision never auto-repaired. | §§7–8 |
| AC-SUP-02 / AC-TIME-03 | SRC-24 §5; SRC-28 §5 | Ordinal is permanent, not recomputed at publication; comparator still pending. | §§3, 8 |

## 8. Consistency and Verification Statement

- Opsi A/B/Hybrid remain alternatives; no owner decision is inferred.
- NIS issuance outcome, provisioning outcome and notification outcome are independent after publication commit where applicable.
- A committed NIS is never rolled back, reissued, renamed or used to allocate a replacement due to provisioning/notification failure.
- AC-PUB-03 and AC-REG-03 no longer imply one result for mutually exclusive options.
- Retry and duplicate processing do not allocate NIS or consume/release an issued slot.
- Username reservation is not treated as an account, canonical relationship, or login permission.
- Supplemental ordering continues to recommend `official_entry_at → permanent cohort_entry_ordinal → approved comparator → siswa.id`; ordinal is not recomputed at publication.
- OD-01–18 remain `PENDING`; criteria and blockers are not passed/closed.
- Missing Tahap 2A.1/2A.2 prevents complete historical traceability.

This matrix maps documents only. It is not evidence that code, database constraints, login controls, workers, tests, or deployment behavior exist or pass.

## 9. Tahap 2A.8R1 Correction Addendum

The following mappings are authoritative for the three scoped corrections. Existing TE/TV statuses and blocker statuses remain unchanged.

| Mapping | Corrected interpretation | OD | Evidence IDs | Blockers | Acceptance criteria |
|---|---|---|---|---|---|
| AUTH-R01 | Account provisioning is distinct from NIS publication. A is synchronous; B is asynchronous; Hybrid reservation does not choose provisioning lifecycle. | OD-04/10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-ACC-01/02/04/05/06/07/08 |
| AUTH-R02 | Notification intent, commit-gated event availability, delivery, acknowledgement and retry are distinct; notification intent is not account-provisioning intent. | OD-04/10/18 | TE-01/08/09/18; TV-10/12 | BL-01/08/09/10 | AC-ACC-03; AC-PPDB-01; AC-PUB-03 |
| AUTH-R03 | Username reservation is not User, canonical link, credential or login entitlement; Hybrid includes separate provisioning choice. | OD-04/10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-ACC-02-C/H, AC-ACC-05/07/08, AC-AUTH-01/02 |
| CON-02 | Synchronous, asynchronous and reservation alternatives remain unselected; Opsi A/B/Hybrid outcomes are branch-specific. | OD-04/10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-PUB-03, AC-REG-03, AC-ACC-01–08 |
| Notification boundary | Transactional intent is a technical recommendation for publication-dependent notices; event is available only after commit; delivery/retry is separate. | OD-04/10/18 | TE-01/08/09/18; TV-10/12 | BL-01/08/09/10 | AC-ACC-03; AC-PPDB-01 |

| AC | Corrected applicability |
|---|---|
| AC-ACC-01 | Opsi B only; Hybrid only if asynchronous provisioning is selected. |
| AC-ACC-02 | Shared assertions plus A synchronous, B asynchronous, and Hybrid reservation assertions; A does not require worker states. |
| AC-ACC-03 | Notification intent/delivery is separate; applies to any option if owner selects the pattern. |
| AC-ACC-04 | Opsi A only; Hybrid only if synchronous provisioning is selected. |
| AC-ACC-05 | Reservation dimension of Hybrid; does not determine provisioning lifecycle. |
| AC-ACC-06 | Worker-specific assertions only for asynchronous provisioning; synchronous publication retry and notification retry are separately tested. |
| AC-ACC-07/08 | Login gate/manual-resolution behavior follows the chosen lifecycle; no reservation login or automatic identity repair. |

See the Tahap 2A.8R1 matrix for the full OD, TE/TV, BL and AC mappings.
