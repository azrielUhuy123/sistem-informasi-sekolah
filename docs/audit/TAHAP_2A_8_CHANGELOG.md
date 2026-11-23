# TAHAP 2A.8 — CHANGELOG

**Deliverable set**

- [Final NIS Architecture Consolidation](./TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md)
- [Traceability Matrix](./TAHAP_2A_8_TRACEABILITY_MATRIX.md)

## 1. Changes Made

| Area | Prior state | Consolidation/change | Reason | Impact |
|---|---|---|---|---|
| Document structure | Architecture, readiness, owner decision and local evidence were separate documents. | Created one 30-section working architecture with a companion source/rule/OD/TE/TV/BL/AC traceability matrix. | Provide a single reference while preserving source IDs and status boundaries. | Existing stage reports remain unchanged; consolidation is not implementation approval. |
| Source coverage | Brief required 2A.1 and 2A.2, alongside later reports. | Searched repository paths/patterns; 2A.1 and 2A.2 were not found. Marked them unavailable and the final verdict incomplete. | Avoid claiming unseen content was reviewed or inventing historical evidence. | Historical traceability remains incomplete until those documents are supplied. |
| Baseline and business rules | Baseline repeated across 2A.3–2A.7R. | Consolidated format, year/grade semantics, sequence, initial/supplemental, transfer, placement, legacy and immutability rules into invariant IDs. | Make rules testable and cross-referenceable. | No baseline was changed; formal approval remains distinct. |
| Database model | Proposed table details distributed across design report. | Cataloged current local tables/constraints separately from proposed NIS entities and proposed existing-table changes. Added responsibility, key/FK/unique, mutability, retention and invariants. | Prevent proposed schema from being mistaken for active schema. | No schema or migration was created. Outbox is clearly marked proposed and not fully modeled. |
| Publication and locks | Workflow and global locking protocol distributed across sections. | Combined initial and supplemental steps, all-or-nothing semantics, failure logging, sorted cohort locks, sequence locks and retry. | Show an end-to-end issuance path and lock boundaries. | Existing writers are not changed; activation remains blocked. |
| Supplemental ordering | 2A.7R includes timestamp/name/ID and timestamp/ordinal/name/ID formulations. | Recorded this as a conflict; recommended `official_entry_at`, cohort ordinal, approved comparator, then `siswa.id`. | Ordinal resolves equal timestamps deterministically; do not silently select a prior variant. | OD-03/08/14 stay PENDING; owner approval and validation required. |
| Timestamp architecture | `recorded_at`, `effective_at`, `official_entry_at`, cutoff and watermark were refined in 2A.7R. | Consolidated meanings, recommended UTC/source validation, monotonic cohort ordinal, watermark, correction events and immutable issuance snapshot. | Avoid administrative date corrections changing historical issuance ordering. | Clock/precision/watermark not approved or validated for a target. |
| Account provisioning and username | 2A.3 proposed username sync inside publication; 2A.7R recommends outbox worker after publication. | Preserved both alternatives; recommended outbox without deciding whether username is reserved at issue or created by worker. Added pending/login gate, retry states and mail failure semantics. | Preserve atomic NIS identity while allowing independent account/email recovery. | Material OD-04/10 conflict remains open; no username or account was touched. |
| Technical evidence | TE-01–20 and TV-01–12 appear in separate reports and have scope-specific statuses. | Combined all IDs in one register and retained local-only/partial/unverified status qualifiers. | Maintain traceability without generalizing local evidence to deployment. | No TE/TV item is claimed fully validated for deployment. |
| Blockers | BL-01–10 were updated across 2A.4–2A.6. | Consolidated closure evidence and affected components for all ten blockers. | Make implementation boundary explicit. | No blocker closed solely by documentation. |
| Acceptance criteria | 35 AC IDs in 2A.4 were future requirements; 2A.7R added account/timestamp semantics without test IDs. | Retained all original IDs and summarized expected observable outcomes; added AC-ACC-01–04 and AC-TIME-01–04 as proposed criteria. | Make OD-04 and OD-14 refinements testable without replacing original IDs. | All criteria remain NOT RUN; new criteria are not previously approved acceptance tests. |
| Owner decisions | OD-01–18 were PENDING in 2A.7R. | Created one integrated owner register with baseline, recommendation, alternatives, dependency, validation, risk and blank/pending owner decision. | Distinguish recommendation from approval. | All OD remain PENDING. |
| Dependency graph | 2A.7R removed OD-18 self-edge and OD-13/17 circular dependency. | Copied final graph and documented no self-edge/cycle; OD-18 independent and governance prerequisite. | Keep dependency contract consistent with latest refinement. | No dependency changed beyond 2A.7R. |
| Final verdict | Prior reports said NOT READY FOR IMPLEMENTATION or PARTIALLY READY FOR OWNER APPROVAL. | Selected `CONSOLIDATION INCOMPLETE` because required 2A.1/2A.2 sources are unavailable and conflicts remain. | Meet the source-inspection criterion honestly. | This is not an implementation readiness verdict or authorization. |

## 2. Conflicts Identified and Preserved

1. **Supplemental order:** `(official_entry_at, name comparator, siswa.id)` versus `(official_entry_at, cohort ordinal, name comparator, siswa.id)`. The latter is recommended for deterministic ties; owner approval remains pending.
2. **Username timing:** issuance transaction synchronization in Tahap 2A.3 versus post-issuance outbox provisioning in Tahap 2A.7R. The point at which `users.username` is reserved/set is not selected.
3. **Initial cutoff:** timestamp comparison in earlier workflow text versus locked cohort ordinal watermark in 2A.7R. The watermark is a recommendation; equality and persistence require owner decision and target-engine validation.
4. **Missing sources:** Tahap 2A.1 and 2A.2 were specified but unavailable in the workspace; their content cannot be checked for additional conflicts.

## 3. Decisions Intentionally Not Made

- No OD-01–OD-18 is `APPROVED`; all remain `PENDING`.
- No database engine/runtime is declared an operational target.
- No comparator, expected name order, timestamp clock/precision or cutoff equality rule is owner-approved.
- No canonical User–Siswa relationship is selected; `siswa.user_id` remains only a candidate.
- No account transaction/outbox, username reservation, account activation or email policy is selected.
- No legacy mapping/reservation, entry evidence policy, placement exception, role assignment or batch size is approved.
- No technical validation, acceptance criterion or blocker is declared passed/closed.

## 4. Information Intentionally Preserved

- NIS format and baseline year-code example `2026/2027 → 2627`.
- Sequence space per `(year_code, entry_grade)` across rombels and no reuse/renumber.
- Initial batch, placement prerequisite, transfer rules, supplemental distinction and immutable NIS.
- Legacy exact-string preservation and fail-closed collision handling.
- Local facts from Tahap 2A.6, including sparse testing data, MySQL/PHP/ICU versions, actual locale `root`, broad local grant and Apache handler uncertainty.
- TE-01–20, TV-01–12, BL-01–10 and all original AC identifiers.
- Prior reports and worktree contents; no source report was overwritten.

## 5. Execution Boundary

Only the three documentation deliverables for Tahap 2A.8 were created. No source code, migration, database, data, configuration, dependency, grant, username, account or workflow was changed. No migration, seeder, test suite or database operation was run.
