# TAHAP 2A.8R — CHANGELOG

## 1. Deliverables

- [Architecture consistency refinement](./TAHAP_2A_8R_ARCHITECTURE_CONSISTENCY_REFINEMENT.md)
- [Traceability matrix](./TAHAP_2A_8R_TRACEABILITY_MATRIX.md)
- This changelog

## 2. Files Read

- `TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md`
- `TAHAP_2A_8_TRACEABILITY_MATRIX.md`
- `TAHAP_2A_8_CHANGELOG.md`
- `TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md`
- `TAHAP_2A_7R_CHANGELOG.md`
- `TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md`
- `TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md`
- Related available evidence/design documents from Tahap 2A.3, 2A.5, and 2A.6

Workspace search for the required historical documents did not find Tahap 2A.1 or Tahap 2A.2. They were not reconstructed or treated as reviewed.

## 3. Conceptual Changes

| Area | Prior condition | Refinement | Reason / impact |
|---|---|---|---|
| Provisioning options | Synchronous username/account write and post-commit outbox appeared together without consistent branch-specific results. | Opsi A, Opsi B, and Hybrid are explicit alternatives with separate boundaries and outcomes. | Removes ambiguous assumptions without choosing for owner. |
| Publication transaction | AC-PUB-03 included username/account rollback unconditionally. | Common rollback covers committed NIS state and transaction-coupled intent; account/User rollback is required only if Opsi A is chosen. | Avoids applying A semantics to B. |
| Username collision | AC-REG-03 implied no issuance for every collision. | A pre-commit collision may block; a B post-commit collision preserves NIS and enters manual review; Hybrid must prove claim integrity. | Correctly represents when a collision is discovered. |
| Outbox criteria | AC-ACC-01 could be read as universal. | AC-ACC-01 is scoped to Opsi B; AC-ACC-04 to Opsi A; AC-ACC-05–08 are proposed additions for Hybrid, replay, login gate, and manual resolution. | Makes test preconditions and expected results measurable. |
| Publication result | NIS, account, and email could be interpreted as one all-or-nothing state. | Three outcomes and two independent state machines are documented. | Email/account failure cannot falsely imply NIS rollback or success. |
| Failure/retry | Retry semantics did not cover duplicate event, uncertain commit, post-commit crash, or manual recovery as one matrix. | Added failure matrix and non-negotiable retry rules. | Prevents reissue, slot reuse, account duplication, and notification with uncommitted data. |
| Username reservation | Reservation could be mistaken for active account or assumed unique across tables. | Defined candidate/reservation/User separately and documented cross-writer uniqueness, expiry, fencing, and no-login requirements. | Hybrid remains unapproved and technically unproven. |
| Supplemental order | Recommendation could be recomputed at publication. | Reaffirmed permanent cohort ordinal assigned at official entry, not recomputed at publication. | Preserves deterministic historical ordering; OD-03/08/14 remain pending. |
| Traceability | Existing CON-02 and AC mapping did not reflect explicit branch behavior. | New traceability maps rules, OD, TE/TV, BL, original AC IDs and proposed additions to refinement sections. | Prior source documents remain unchanged; this refinement is the updated interpretation for the provisioning conflict. |

## 4. Contradictions Resolved at Documentation Level

1. Opsi A and B no longer share conflicting account rollback expectations.
2. AC-PUB-03 distinguishes common NIS transaction rollback from Opsi-A account rollback.
3. AC-REG-03 distinguishes pre-commit collision from post-commit collision.
4. AC-ACC-01–04 are scoped to the applicable branch; proposed Hybrid and recovery/login criteria are separately identified.
5. Publication, provisioning and notification have distinct outcomes and state.
6. Email failure is explicitly independent from NIS/account commit.
7. Retry, duplicate outbox processing, post-commit crash and uncertain commit never trigger NIS reallocation.
8. Reservation is not treated as account activation, and a separate table is not assumed to guarantee cross-table uniqueness.
9. Supplemental ordering retains the permanent ordinal recommendation.

These are specification clarifications only. They do not prove implementation behavior or close any blocker.

## 5. Unresolved Items

- Owner has not selected Opsi A, B, or Hybrid.
- OD-01 through OD-18 remain `PENDING`, particularly OD-04, OD-10, OD-11, and OD-18.
- Username equality/candidate generation, expiry/release, login eligibility, credential and notification policy remain undecided.
- Hybrid claim uniqueness across all existing/external account writers is unverified.
- Canonical User–Siswa relation and legacy account/username data have not been reconciled.
- Target DB/runtime, worker recovery, concurrency, notification deduplication, and login gate are not tested by this document.
- BL-01–BL-10 remain open.
- Tahap 2A.1 and Tahap 2A.2 remain unavailable; historical design consistency is incomplete.

## 6. Decisions Intentionally Not Made

- No provisioning option was selected.
- No username policy, collision disposition, claim TTL, role, manual-review authority, or account activation policy was approved.
- No canonical relationship, schema, outbox table, worker, database constraint, or login behavior was declared implemented.
- No acceptance criterion was marked approved or passed.
- No blocker was closed.
- No source report from Tahap 2A.1–2A.8 was overwritten.

## 7. Execution Boundary

Only the three Tahap 2A.8R documentation files listed above were created. No source code, database, data, migration, configuration, dependency, privilege, account, username, NIS, or workflow was changed. No query that modifies data, migration, seeder, or test was run.

**Verdict:** `REFINEMENT INCOMPLETE` because Tahap 2A.1 and 2A.2 are unavailable and the architecture/owner/evidence blockers remain open. This verdict does not authorize implementation.

## 8. Tahap 2A.8R1 Consistency Correction

The authoritative addendum [TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md](./TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md) and its [traceability matrix](./TAHAP_2A_8R1_TRACEABILITY_MATRIX.md) supersede conflicting 2A.8R wording on:

- AC-ACC-02 state coverage for synchronous A, asynchronous B, and Hybrid reservation.
- Hybrid's independent reservation and account-provisioning dimensions.
- Notification intent creation versus post-commit availability, delivery, acknowledgement, and retry.
- Branch-specific failure handling, expiry, retry, and idempotency.

No owner option was selected. OD-01–18 remain `PENDING`; all evidence statuses and blockers remain unchanged. The overall 2A.8R historical verdict remains `REFINEMENT INCOMPLETE`. This scoped correction is not implementation approval.
