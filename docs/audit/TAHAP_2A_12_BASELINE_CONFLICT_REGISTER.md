# TAHAP 2A.12 — BASELINE CONFLICT REGISTER

**Scope:** Compare selected engineering baseline OD-01–OD-18 against 2A.8R1/2A.9 design contracts, 2A.11 status, source/migrations/configuration, and local evidence in 2A.6.  
**Status:** No formal school decision or implementation approval is implied.

## 1. Method and Overall Result

The review separated:

1. **Internal baseline contradiction:** selected OD choices contradict one another.
2. **Existing implementation gap:** current source/schema does not meet the selected design.
3. **Unresolved clarification/residual risk:** baseline contract or operational evidence needs more definition.

OD-04/OD-10 ownership and async lifecycle were checked against 2A.8R1 and 2A.10R1; OD-08/OD-14 ordering; OD-13/OD-17 atomicity and exactly-one initial batch; OD-11/login relationship; OD-12/OD-16 enforcement; OD-03 locale behavior. No contradictory pair among the selected baseline statements was confirmed. This is not a closure of existing OD, blocker, or historical gaps.

**Result:** `PASS WITH OPEN ITEMS`. There are implementation conflicts/gaps and unresolved technical questions listed below. All OD-01–OD-18 remain `PENDING`; BL-01–BL-10 and SRC-GAP-01/02 remain `OPEN`.

## 2. Current Implementation Gaps Against Baseline

| ID | Classification / severity | Evidence and conflict | Impact | Required resolution/evidence |
|---|---|---|---|---|
| GAP-01 | `BLOCKING TECHNICAL GAP` / High | Local ICU 71.1 `Collator('id-ID')` actual locale is `root` per [2A.6](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md); baseline requires pinned `id-ID` comparator and fail-closed behavior. Existing source search did not find a NIS comparator or golden fixtures. | Current local comparator evidence does not meet baseline; publication must not proceed by fallback. | Select/provision compatible target ICU/data/runtime, prove actual locale and parity, approve golden order, and test fail-closed behavior. |
| GAP-02 | `IMPLEMENTATION GAP` / High | [PendaftarController::generateNis](../../app/Http/Controllers/PPDB/PendaftarController.php) scans `siswa.nis` by prefix and increments a string-descending maximum. It hardcodes entry grade 1. Manual TU accepts NIS directly; importer reads NIS from workbook. No shared allocator/registry was found. | Multiple writer paths can bypass a single permanent allocator/cohort lock; PPDB path is not a deterministic alphabetical initial publication. | Centralize all issuance through one allocator after legacy reconciliation and valid-placement/cohort gates; keep manual/import workflows from bypassing it. |
| GAP-03 | `IMPLEMENTATION GAP` / High | PPDB confirmation locks only the individual `ppdb_pendaftar` row; no cohort/sequence lock, unique batch-per-cohort guard, NIS sequence registry, or immutable slot table was found. | Parallel confirmations may calculate the same next NIS. Unique NIS can reject one write, but recovery/retry/idempotency are undefined. Multiple candidates are not atomically published as one initial cohort batch. | Target-engine concurrency tests, cohort lock, permanent slot/registry uniqueness, logical-batch idempotency, and atomic initial publisher. |
| GAP-04 | `IMPLEMENTATION GAP` / High | `siswa.nis` unique index exists, but [SiswaController](../../app/Http/Controllers/Tu/SiswaController.php) exposes force-delete and [Siswa](../../app/Models/Siswa.php) uses soft deletes. No independent permanent registry was found. | Soft-deleted values remain blocked by the table unique constraint while the row exists; force-delete removes that protection and can allow historical value reuse. No evidence survives row deletion. | Keep issued values in durable registry/sequence slots not deleted with Siswa; prove restore/relink/force-delete constraints and direct SQL denial. |
| GAP-05 | `IMPLEMENTATION GAP` / High | Baseline OD-04 selects async transactional outbox. Existing `config/queue.php` has queue backend options and `after_commit=false`, plus a `jobs` table migration, but no provisioning outbox/job/worker/retry/idempotency implementation was found. | Queue configuration alone does not atomically persist provisioning intent with NIS publication; worker may not exist operationally. | Design/implement custom durable outbox in same publication transaction; worker after commit; prove retry lifecycle, idempotency, monitoring, and login gate. |
| GAP-06 | `IMPLEMENTATION GAP` / High | [LoginRequest](../../app/Http/Requests/Auth/LoginRequest.php) looks up Siswa via NIS and loads `Siswa.user`; [PPDB controller](../../app/Http/Controllers/PPDB/PendaftarController.php) writes `siswa.user_id` and `users.siswa_id`. Migration/schema evidence 2A.6 says both references are nullable/non-unique. | The baseline canonical relationship is not enforced one-to-one; async pending/failed lifecycle is not represented in login gate. | Reconcile every link before canonical transition, enforce one-to-one, update readers/writers, test deny cases before successful provisioning. |
| GAP-07 | `IMPLEMENTATION GAP` / High | 2A.6 local account grant includes global `*.*` privileges and `WITH GRANT OPTION`; no role separation evidence for target. | Does not satisfy OD-12 least privilege; a runtime account with broad rights can bypass ledger constraints and audit expectations. | Separate runtime/migration/DBA grants; verify effective target grants and denied direct mutations; document break-glass. |
| GAP-08 | `IMPLEMENTATION GAP` / Medium | [SiswaImport](../../app/Imports/SiswaImport.php) and [SiswaSheetImport](../../app/Imports/SiswaSheetImport.php) accept NIS from workbook and use `firstOrCreate` by that NIS. Active route calls `SiswaImport`; no PHP callsite for `SiswaSheetImport` was found. | Import is an alternate NIS source and can add placements without allocator/entry-evidence contract; direct NIS replacement of existing record is not what the `firstOrCreate` attributes do. | Route imports through shared identity/reconciliation APIs; validate all sheets/workbook writers and retain existing NIS; verify external integrations. |
| GAP-09 | `IMPLEMENTATION GAP` / Medium | `PendaftarController::konfirmasiDaftarUlang()` assigns NIS before a separate class placement workflow. `simpanPenempatanKelas()` restricts PPDB class to grade I; current generator hardcodes grade 1. | Current flow cannot establish valid placement before allocation and assumes grade-I entry for all PPDB, which is not sufficient for transfer-entry policy. | Approved entry evidence/grade resolver and placement eligibility must precede allocator; unknown/contradictory transfer evidence hardblocks. |
| GAP-10 | `IMPLEMENTATION GAP` / Medium | Existing importer/placement/rollover uses current level/cohort mapping for placements; no permanent `official_entry_at` or explicit validated year-code mapping/ordinal was found. | Current academic-year/year-class values cannot alone reproduce official entry chronology or deterministic supplemental order after movement. | Store validated immutable entry history, explicit year-code mapping, UTC timestamp semantics, stable ordinal and approved tie rules. |

## 3. Unresolved Clarification and Residual Risks

| ID | Type | Issue needing resolution | Why not settled by this audit |
|---|---|---|---|
| OPEN-01 | `NEEDS_CLARIFICATION` | OD-08 specifies `official_entry_at → permanent cohort ordinal → comparator → siswa.id`. Define whether ordinal is unique per cohort and in which exact tie domain the comparator is expected to apply. | The ordering tuple is preserved as selected; semantics are not changed without owner/architecture clarification. |
| OPEN-02 | `NEEDS_OWNER_IDENTIFICATION` | Formal policy owner, academic/administrative approver, exception approver, technical authority, evidence verifier, and decision recorder are not identified. | [2A.11 authority matrix](./TAHAP_2A_11_AUTHORITY_AND_APPROVER_MATRIX.md) explicitly leaves identity/mandate unresolved. |
| OPEN-03 | `HISTORICAL SOURCE GAP` | Original 2A.1 and 2A.2 not found in reviewed local corpus; historical decisions cannot be attributed to originals. | Per [2A.9](./TAHAP_2A_9_HISTORICAL_SOURCE_RECOVERY.md), SRC-GAP-01/02 remain open. |
| OPEN-04 | `TARGET ENVIRONMENT GAP` | Production database/runtime/worker topology, ICU data, privilege matrix, and operations/recovery are not evidenced. | 2A.6 is a local audit only; no environment or database query was performed in 2A.12. |
| OPEN-05 | `CAPACITY/RECOVERY GAP` | No representative publication benchmark, numeric batch cap, backup/restore drill, or uncertain-commit test. | Sparse local row counts do not establish or disprove school capacity. Numeric cap remains deferred until benchmark. |
| OPEN-06 | `POLICY GAP` | Evidence types and approval boundaries for transfers, corrections, exceptions, collision reconciliation, cancellation/reopen, and relink remain pending. | Engineering cannot infer school policy or approver authority. |

## 4. No-Conflict Checks

| Pair/contract | Result | Evidence/boundary |
|---|---|---|
| OD-04 async vs OD-10 no reservation | `CONSISTENT` | Async provisioning uses a transactional intent; no reservation is activated. Hybrid does not follow from asynchronous mode. |
| AC-ACC-02-B lifecycle | `CONSISTENT WITH OPEN ITEMS` | Preserve `pending`, `processing`, `succeeded`, `failed`, `retryable`, `failed_manual_review`; attempt result remains distinct. No unprovided manual-review transition invented. |
| OD-11 canonical link vs login | `DESIGN CONSISTENT; IMPLEMENTATION GAP` | Login lookup by NIS then Siswa→User is compatible with proposed canonical Siswa FK, but second FK, provisioning gate, and one-to-one enforcement remain. |
| OD-13 atomic visibility vs staged work | `CONSISTENT` | Staging/chunking is not publication. Final visible commit must be all-or-nothing; no equivalence claim made for non-atomic final commits. |
| OD-17 exactly-one initial batch | `CONSISTENT` | One initial batch per cohort and idempotent retry align with OD-13; no current guard has been verified. |
| OD-03 and OD-08/14 | `OPEN` | Comparator is selected but actual local locale fails; comparator/golden sequence and timestamp/ordinal tie behavior need evidence. |
| OD-16 and OD-12 | `CONSISTENT DESIGN; IMPLEMENTATION GAP` | Permanent registry and least privilege can enforce no reuse, but current Siswa row unique constraint plus force-delete cannot. |

No contradiction was removed or resolved by assumption. Any later owner choice that changes the selected baseline must be registered explicitly as a new decision/change and consistency-reviewed before implementation.

## 5. Status

- **BASELINE CONSISTENCY:** `PASS WITH OPEN ITEMS`
- **FORMAL BASELINE CONFLICTS:** `NONE CONFIRMED`
- **IMPLEMENTATION GAPS:** `OPEN — see GAP-01–GAP-10`
- **OD-01–OD-18:** `PENDING`
- **BL-01–BL-10:** `OPEN`
- **SRC-GAP-01/02:** `OPEN`
- **IMPLEMENTATION AUTHORIZATION:** `NOT AUTHORIZED`
