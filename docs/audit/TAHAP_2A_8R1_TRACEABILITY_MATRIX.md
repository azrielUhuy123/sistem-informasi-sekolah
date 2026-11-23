# TAHAP 2A.8R1 — TRACEABILITY MATRIX

**Correction document:** [TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md](./TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md)  
**Status:** Documentation traceability only; evidence and blocker statuses are not upgraded.  
**Correction verdict:** `CORRECTION COMPLETE`; this does not mean implementation readiness.

## 1. Source Key

| ID | Source | Use |
|---|---|---|
| SRC-23 | Tahap 2A.3 NIS architecture | Atomic NIS publication, account/username proposal, outbox concepts. |
| SRC-24 | Tahap 2A.4 readiness gate | Original OD, TE/TV, BL and AC definitions. |
| SRC-25 | Tahap 2A.5 evidence closure | User–Siswa and mutation/writer evidence. |
| SRC-26 | Tahap 2A.6 local verification | Local-only schema/runtime/account evidence; no deployment implication. |
| SRC-28 | Tahap 2A.7R decision refinement | OD-04 alternatives, timestamp/ordinal baseline, pending decisions. |
| SRC-29 | Tahap 2A.8 consolidation | Consolidated architecture and original AC-ACC/AC-TIME proposals. |
| SRC-30 | Tahap 2A.8 traceability | Existing rule/OD/TE/TV/BL/AC mappings and CON-02. |
| SRC-32 | Tahap 2A.8R refinement | Baseline for this correction; synchronous, asynchronous and reservation alternatives. |
| SRC-33 | Tahap 2A.8R1 user brief | Three required consistency corrections and output criteria. |
| SRC-GAP-01 | Tahap 2A.1 | Not found; historical contents unavailable. |
| SRC-GAP-02 | Tahap 2A.2 | Not found; historical contents unavailable. |

## 2. Invariant and Finding Traceability

| ID | Invariant / finding | OD | Technical evidence (status unchanged) | Blocker | Acceptance criteria | Corrected sections |
|---|---|---|---|---|---|---|
| NIS-R04/R06 | Publication is atomic; issued NIS/slot is permanent; retries never reissue. | OD-01/12/13/17 | TE-01/08/09/10, TE-18–20; TV-01/04/05/08/09 | BL-01/06/09/10 | AC-PUB-03/04; AC-REG-01/02; AC-ACC-01/02/04/06 | Correction §§5–8 |
| AUTH-R01 | Account provisioning outcome is distinct from NIS publication. Sync/async depends on owner selection; reservation does not choose lifecycle. | OD-04/10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-ACC-01/02/04/05/06/07/08; AC-AUTH-01/02 | §§4–6, 8–11 |
| AUTH-R02 | Notification intent, availability, delivery, acknowledgement, and retry are distinct; delivery does not roll back NIS/account. | OD-04/10/18 | TE-15/18; TV-10/12 | BL-01/08/09/10 | AC-ACC-03; AC-PPDB-01; AC-PUB-03 | §§7–9 |
| AUTH-R03 | Username candidate/reservation is not active account, canonical link, credential, or login entitlement. | OD-04/10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-ACC-02-C/H, AC-ACC-05/07/08, AC-AUTH-01/02 | §§5–6, 8 |
| COR-01 | AC-ACC-02 asynchronous states must not apply to synchronous A or to Hybrid absent owner selection. | OD-04/10/11 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-ACC-02-C/A/B/H | §5 |
| COR-02 | Notification intent creation is not notification delivery; transactional intent is recommended when content depends on committed publication data. | OD-04/10/18 | TE-01/08/09/18; TV-01/08/10/12 | BL-01/08/09/10 | AC-ACC-03; AC-PPDB-01 | §§7–8 |
| COR-03 | Hybrid is reservation dimension plus separately chosen provisioning dimension. | OD-04/10/11/18 | TE-01/08/09/15/18; TV-01/04/07/08/10 | BL-01/05/08/10 | AC-ACC-02-H/05/06/07/08 | §§6, 8 |
| CON-02 | Username timing/provisioning alternative is not owner-resolved. | OD-04; related OD-10/11/18 | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-PUB-03, AC-REG-03, AC-ACC-01–08 | §§3–6, 9–11 |
| SRC-GAP-01/02 | Historical source coverage is incomplete. | OD-01–18 remain pending as recorded | Source unavailable | Historical traceability gap remains open | No AC closes this gap | §§2, 13 |

## 3. Option and Combination Traceability

| Combination | Provisioning contract | Reservation contract | Key criteria | Corrected section |
|---|---|---|---|---|
| No reservation + synchronous | Required account writes in publication transaction; failure rolls back issuance. | None. | AC-ACC-02-A/04 | §6.3 |
| No reservation + asynchronous | NIS + account-provisioning intent commit together; worker processes same key. | None. | AC-ACC-01/02-B/06/07 | §6.3 |
| Reservation + synchronous | Reservation lifecycle is separate; account provisioning remains synchronous. | Uniqueness, ownership, expiry/release, fencing, revalidation, race safety; no login. | AC-ACC-02-H/05 plus AC-ACC-02-A/04 | §6 |
| Reservation + asynchronous | Reservation lifecycle is separate from asynchronous account worker lifecycle. | Same reservation tests; async worker assertions only if selected. | AC-ACC-01/02-H/05/06/07 | §6 |

The matrix is a classification of alternatives, not approval of any combination.

## 4. Owner Decision Traceability

| OD | Related correction | Status |
|---|---|---|
| OD-04 | Synchronous/asynchronous provisioning; reservation separated from account lifecycle; notification policy remains open. | PENDING |
| OD-10 | Username candidate/collision/reservation rules remain open. | PENDING |
| OD-11 | Canonical User–Siswa relation is not selected. | PENDING |
| OD-18 | Reservation and manual-review authorities are not selected. | PENDING |
| OD-01/02/05/06/07/08/09/12/13/14/15/16/17 | No decision changed; technical prerequisites remain as in 2A.8R. | PENDING |

No owner approval is inferred.

## 5. Technical Evidence Traceability

| Evidence ID | Prior status in 2A.8/2A.8R | Relevance to this correction | Required proof |
|---|---|---|---|
| TE-01/08/09/10 | Local/partial; target behavior not established | Atomic publication and transactional intent/claim semantics. | Approved target DB, transaction, unique and concurrency tests. |
| TE-13/15 | Local/sparse or partial | Account identity, username conflict and canonical links. | Authorized complete reconciliation and collision fixtures. |
| TE-18 | Partial | Writers must honor reservation and login/account lifecycle. | Complete internal/external writer inventory. |
| TE-19/20 | Unverified | Recovery and transaction/provider failure behavior. | Target restore and load/failure evidence. |
| TV-01/04 | Partial | Unique constraints and transaction semantics. | Target-specific constraint and rollback tests. |
| TV-05 | Unverified | Direct SQL/privilege boundaries. | Approved role matrix and deny tests. |
| TV-07 | Partial | Canonical link and username identity. | Owner decision and complete data reconciliation. |
| TV-08/09 | Unverified | Async processing, duplicate handling, recovery. | Concurrency/crash/retry/restore tests if async selected. |
| TV-10/12 | Partial/unverified | Writers, authorization, notification/activation. | Full role/writer map and end-to-end target evidence. |

This correction does not change any evidence status.

## 6. Blocker Traceability

| Blocker | Related correction | Closure requirement | Criteria |
|---|---|---|---|
| BL-01 | Publication transaction, account intent, notification intent, reservation uniqueness. | OD-01 + target transaction/constraint/lock validation. | AC-PUB-03; AC-ACC-01/02/03/05/06 |
| BL-05 | Canonical relation and username/account reconciliation. | OD-10/11 + authorized complete identity reconciliation. | AC-AUTH-01/02; AC-ACC-02-C/07/08 |
| BL-08 | Account lifecycle, reservation, login and notification policy. | OD-04/10/11/18 + branch selection and applicable tests. | AC-REG-03; AC-ACC-01–08; AC-PPDB-01 |
| BL-10 | Writers, worker/provider, role enforcement. | Complete writer and role inventory plus selected-path enforcement proof. | AC-TU-01; AC-AUTH-01/02; AC-ACC-05–08 |
| BL-02/03/04/06/07/09 | No closure from this correction; previous requirements remain. | Existing 2A.8R closure evidence. | Existing linked criteria as recorded in 2A.8R. |
| SRC-GAP-01/02 | Historical review | Locate/read 2A.1/2A.2 and compare. | No criterion substitutes for missing source. |

**All listed blockers remain OPEN.**

## 7. Acceptance Criteria Traceability

Every criterion is `PROPOSED / PENDING OWNER APPROVAL / NOT RUN`.

| AC | Corrected interpretation | Applicable options | Evidence/blocker | Correction section |
|---|---|---|---|---|
| AC-ACC-01 | Durable account-provisioning intent committed with NIS; worker failure does not alter NIS; no allocator on retry. | B; Hybrid only if async provisioning selected. | TE-01/08/09/18; BL-01/08/10 | §§5.3, 6, 9 |
| AC-ACC-02-C | Shared correctness without imposing async states: proper boundary, no partial commits, valid login gate, safe audit, no auto-repair/no reissue. | All combinations | TE-13/15/18; TV-07/10; BL-05/08/10 | §5.1 |
| AC-ACC-02-A | Required account writes succeed in publication transaction; failure rolls all back; no worker-state requirement. | A; Hybrid+synchronous | TE-01/08/09; BL-01/08/09 | §5.2 |
| AC-ACC-02-B | Full six-state worker lifecycle, committed durable intent, same-key retry, no reissue and login deny until successful canonical link. | B; Hybrid+asynchronous | TE-01/08/09/18; TV-08/10; BL-01/08/10 | §5.3 |
| AC-ACC-02-H | Test reservation separately; then apply A or B assertions only for the provisioning mode owner selects. | Hybrid | TE-01/08/09/15/18; BL-01/05/08/10 | §5.4 |
| AC-ACC-03 | Transactional notification intent recommendation, commit-gated availability, separate delivery/ack/retry; no rollback or allocator retry. | A/B/Hybrid when owner selects this pattern | TE-01/08/09/18; TV-10/12; BL-01/08/09/10 | §§7–9 |
| AC-ACC-04 | Required account DB failure rolls back publication only for A/synchronous required account writes; email remains external. | A; Hybrid+synchronous | TE-01/08/09; BL-01/08/09 | §§5.2, 9 |
| AC-ACC-05 | Reservation uniqueness/ownership/expiry/release/fencing/revalidation/race; no login; all writers covered. | Hybrid reservation dimension | TE-01/08/09/15/18; TV-01/04/07/08/10; BL-01/05/08/10 | §§6, 9 |
| AC-ACC-06 | Same-key idempotency and no duplicate NIS/account/events; worker assertions only when async. | B/Hybrid+async; A publication retry; notification separately | TE-08/09/18/19/20; TV-08/09; BL-01/09/10 | §§8–9 |
| AC-ACC-07 | Deny login until issued NIS, successful applicable provisioning and valid canonical link; reservation alone never permits login. | All combinations | TE-13/15/18; TV-07/10; BL-05/08/10 | §§5–6, 9 |
| AC-ACC-08 | Authorized/audited manual resolution; no auto-merge/rename/link or NIS change; retry only under selected async mode. | Collision/manual-review branches | TE-15/18; TV-07/10/12; BL-05/08/10 | §§8–9 |
| AC-PPDB-01 | Confirmation does not issue NIS; notification only describes committed facts and valid account state. | All options | TE-13/15/18; TV-07/10/12; BL-05/08/10 | §§7–9 |
| AC-AUTH-01/02 | Canonical relation remains owner-selected; reservation is not User/link; collision is not auto-repaired. | All options with branch assertions | TE-13/15/18; TV-07/10; BL-05/08/10 | §§5–6, 9 |

## 8. Consistency Statement

- AC-ACC-02 does not impose worker lifecycle on Opsi A.
- Hybrid does not imply asynchronous provisioning; reservation is an independent dimension.
- AC-ACC-01 stays specific to asynchronous provisioning; AC-ACC-04 stays specific to synchronous required account writes.
- Notification intent creation is separated from post-commit event availability and delivery.
- Notification intent and account-provisioning intent use separate purpose, state, and idempotency.
- Reservation expiry before publication can fail a prerequisite; expiry after NIS commit cannot cancel issued identity.
- Notification failure cannot alter NIS or account.
- OD statuses, technical evidence statuses, and blocker statuses remain unchanged.

