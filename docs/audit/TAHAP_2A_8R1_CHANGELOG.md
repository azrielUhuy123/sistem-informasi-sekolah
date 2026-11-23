# TAHAP 2A.8R1 — CHANGELOG

## 1. Deliverables

- [Final consistency correction](./TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md)
- [Traceability matrix](./TAHAP_2A_8R1_TRACEABILITY_MATRIX.md)
- This changelog

## 2. Files Read

- `TAHAP_2A_8R_ARCHITECTURE_CONSISTENCY_REFINEMENT.md`
- `TAHAP_2A_8R_TRACEABILITY_MATRIX.md`
- `TAHAP_2A_8R_CHANGELOG.md`
- `TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md`
- `TAHAP_2A_8_TRACEABILITY_MATRIX.md`

## 3. Files Created and Updated

### Created for Tahap 2A.8R1

- `TAHAP_2A_8R1_FINAL_CONSISTENCY_CORRECTION.md`
- `TAHAP_2A_8R1_TRACEABILITY_MATRIX.md`
- `TAHAP_2A_8R1_CHANGELOG.md`

### Updated for consistency

- `TAHAP_2A_8R_ARCHITECTURE_CONSISTENCY_REFINEMENT.md` — added an authoritative correction addendum that supersedes the earlier account-state, Hybrid, notification-boundary, failure-matrix, and acceptance wording.
- `TAHAP_2A_8R_TRACEABILITY_MATRIX.md` — added correction-specific traceability and revised affected mappings; source evidence and blocker statuses remain unchanged.
- `TAHAP_2A_8R_CHANGELOG.md` — records the 2A.8R1 corrections and retains 2A.8R history.

Tahap 2A.8 baseline architecture/traceability documents were read but not modified.

## 4. Conceptual Corrections

| Area | Prior inconsistency | Correction |
|---|---|---|
| AC-ACC-02 | One asynchronous state machine appeared to apply across all options. | Added common assertions and separate A synchronous, B asynchronous, and Hybrid reservation assertions. |
| Opsi A | Worker states could be read as required. | Account/User/username/link required by the option commit within publication transaction; required DB failure rolls back; no asynchronous account-worker lifecycle is required. |
| Opsi B | State lifecycle needed explicit transaction and login boundaries. | Durable account-provisioning intent commits with NIS; worker state applies; same-key retry never reallocates NIS; login denied until success and valid canonical relation. |
| Hybrid | Reservation was conflated with asynchronous provisioning. | Reservation and account provisioning are independent dimensions; provisioning may be synchronous or asynchronous, not selected. |
| Notification transaction boundary | Intent creation and delivery timing were described ambiguously. | Distinguished intent creation, commit-gated event availability, delivery, acknowledgement, and retry/reconciliation. Transactional intent remains a technical recommendation, not owner approval. |
| Failure matrix | Worker failure and reservation expiry were not consistently scoped. | Worker failures apply only when async provisioning is selected; reservation expiry has separate pre/post-commit semantics; notification failure never rolls back NIS/account. |
| Traceability | Affected AC/OD/BL mappings did not reflect the corrected dimensions. | Updated AUTH-R01/02/03, CON-02 and requested AC/OD/BL mappings without upgrading evidence or blocker status. |

## 5. Decisions and Statuses Intentionally Unchanged

- Opsi A, Opsi B, Hybrid, and all four reservation/provisioning combinations remain alternatives/classification only; none is approved or selected.
- OD-01–OD-18 remain `PENDING`.
- OD-04, OD-10, OD-11, and OD-18 remain owner decisions.
- Username policy, canonical User–Siswa relation, login policy, role/manual-review authority, notification policy, and Hybrid safety remain unresolved.
- All acceptance criteria remain `PROPOSED / PENDING OWNER APPROVAL / NOT RUN`.
- Technical evidence statuses are unchanged.
- BL-01–BL-10 and SRC-GAP-01/02 remain open.
- Historical review remains incomplete because Tahap 2A.1 and 2A.2 were unavailable.

## 6. Execution Boundary and Verdict

Only documentation was created or updated. No source code, database/data, migration, configuration, dependency, seeder, test, account, username, NIS, worker, outbox, reservation, or login gate was changed or executed.

**Correction verdict:** `CORRECTION COMPLETE` for the three scoped consistency findings. This is not owner approval, technical validation, historical completion, blocker closure, or `READY FOR IMPLEMENTATION`.
