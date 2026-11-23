# TAHAP 2A.8 — TRACEABILITY MATRIX

**Related architecture:** [TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md](./TAHAP_2A_8_FINAL_NIS_ARCHITECTURE_CONSOLIDATION.md)  
**Status:** Traceability of available documents only. Tahap 2A.1 and 2A.2 were not found and cannot be mapped.

## 1. Source Key

| Source ID | Document | Main referenced sections |
|---|---|---|
| SRC-21 | Tahap 2A.1 — not found | Unavailable. |
| SRC-22 | Tahap 2A.2 — not found | Unavailable. |
| SRC-23 | [TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md](./TAHAP_2A_DESAIN_ARSITEKTUR_DATABASE_NIS.md) | §§2–12, 14–16: baseline, entity catalog, constraints, locking, publication, workflow integrations, tests, open decisions. |
| SRC-24 | [TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md](./TAHAP_2A_4_FINAL_READINESS_GATE_NIS.md) | §§3–5: OD, TE, AC; §6 dependency/order; §8 blockers; §9 verdict. |
| SRC-25 | [TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md](./TAHAP_2A_5_OWNER_DECISION_EVIDENCE_CLOSURE.md) | §§3–10: runtime/sorting, legacy query plan, relationships, mutations, owner/evidence/blockers. |
| SRC-26 | [TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md](./TAHAP_2A_6_LOCAL_ENVIRONMENT_SCHEMA_VERIFICATION.md) | §§3–18: local runtime/DB/schema/data/sorting/writers/TE/BL/risks. |
| SRC-27 | [TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md](./TAHAP_2A_7_OWNER_DECISION_RESOLUTION.md) | §§3–18: baseline, OD analyses, privilege, comparator, ledger, relationship, placement and registers. |
| SRC-28 | [TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md](./TAHAP_2A_7R_OWNER_DECISION_RESOLUTION_REFINEMENT.md) | §§4–10: OD-04, OD-14, OD-18, corrected dependencies, consistency, updated register and verdict. |
| SRC-29 | [TAHAP_2A_7R_CHANGELOG.md](./TAHAP_2A_7R_CHANGELOG.md) | Change log and intentionally unchanged baseline. |

## 2. Rule / Invariant Traceability

| Rule ID | Rule / invariant | Decision OD | Evidence TE/TV | Blocker BL | Acceptance criteria AC | Source trace |
|---|---|---|---|---|---|---|
| NIS-R01 | New format `TTTT.T.NNN`; minimum width 3, no max 999; example 2026/2027→2627 | OD-15 | TE-14, TV-06 | BL-04, BL-07 | AC-SEQ-02, AC-DB-02 | SRC-23 §2/§15; SRC-24 §§2/5; SRC-27 §3; SRC-28 §7 |
| NIS-R02 | Year/grade mean first accepted entry at SDN Mekar Jaya; do not infer from current class, origin or issue date | OD-07, OD-15 | TE-14, TE-17, TV-06, TV-11 | BL-04, BL-07, BL-08 | AC-EH-01, AC-SUP-03, AC-DB-02 | SRC-23 §§2/7/10; SRC-24 §§3/5; SRC-25 §§4/7; SRC-27 §§3/6; SRC-28 §§5/7 |
| NIS-R03 | Sequence key `(year_code, entry_grade)` shared across rombels | OD-15 | TE-01, TV-01 | BL-01, BL-07 | AC-SEQ-01, AC-SEQ-02 | SRC-23 §§2/4.4/6; SRC-24 §§2/5; SRC-27 §3 |
| NIS-R04 | No duplicate slot/NIS, reuse or renumbering | OD-05, OD-06, OD-12, OD-16 | TE-10–12, TE-14, TV-04–06 | BL-04, BL-06 | AC-LEG-01/02, AC-REG-01/02, AC-DEL-01 | SRC-23 §§4–6/9–10; SRC-24 §§3/5/8; SRC-26 §§6/15/17; SRC-27 §§6/7/9; SRC-28 §8 |
| NIS-R05 | Name change never changes issued NIS; initial sort uses approved full-name comparator | OD-03, OD-05 | TE-06/07, TV-03 | BL-03 | AC-PUB-01, AC-REG-02, AC-OBS-02 | SRC-23 §§2/9/11/12; SRC-24 §§3/5; SRC-25 §§4/12; SRC-26 §§12/13; SRC-27 §8 |
| NIS-R06 | Preview consumes no sequence; publication all-or-nothing; retry same batch | OD-13, OD-17 | TE-08/09/20, TV-08/09 | BL-01, BL-09 | AC-PUB-03/04, AC-CON-02 | SRC-23 §§6/7; SRC-24 §§5/6; SRC-27 §§3/16; SRC-28 §§4/7 |
| NIS-R07 | Entry and valid placement required; PPDB status/link contradictions hardblock | OD-04, OD-07, OD-09, OD-11 | TE-13/15/17, TV-07/10/11 | BL-05, BL-08, BL-10 | AC-PLACE-01/02, AC-PPDB-01, AC-AUTH-01 | SRC-23 §§2/7/11; SRC-24 §§3/5; SRC-25 §§6/7; SRC-26 §§11/13; SRC-27 §§6/10/11 |
| NIS-R08 | Pre-cutoff eligible candidate omitted from initial blocks supplemental | OD-08, OD-14, OD-17 | TE-08/09, TV-08/11 | BL-07/08/09 | AC-COH-04, AC-SUP-01/02, AC-TIME-04 | SRC-23 §§6/7; SRC-24 §5; SRC-28 §§5/8 |
| NIS-R09 | Post-cutoff candidates receive next tuple sequence; prior NIS unchanged | OD-08, OD-14, OD-15 | TE-08/09, TV-08/11 | BL-07/08/09 | AC-COH-03, AC-SUP-01/02/03 | SRC-23 §7; SRC-24 §5; SRC-28 §5 |
| NIS-R10 | Legacy exact strings preserved; unresolved exact collision fails closed; reservation requires approval | OD-05, OD-06, OD-12, OD-18 | TE-12/14, TV-05/06 | BL-04/06 | AC-DB-02, AC-LEG-01/02, AC-REG-01 | SRC-23 §§4.5a/b/4.6/4.7/10; SRC-24 §§3/5/8; SRC-25 §§5/8; SRC-26 §§8/14/15; SRC-28 §19 |
| NIS-R11 | NIS identity separate from NISN and DB primary key | OD-15 | TE-14, TV-06 | BL-04/07 | AC-DB-02, AC-REG-02 | SRC-23 §2; SRC-24 §2; SRC-27 §3 |
| NIS-R12 | Transfer before cutoff initial; after cutoff supplemental, based on entry evidence | OD-07, OD-08, OD-09, OD-14/15 | TE-14/17, TV-06/11 | BL-04/07/08 | AC-PUB-01, AC-SUP-03, AC-EH-01 | SRC-23 §§2/7; SRC-24 §§3/5; SRC-25 §§4/7; SRC-27 §§3/6; SRC-28 §§5/7 |

## 3. Decision Traceability

| Decision ID | Subject | Evidence IDs | Blockers | Criteria | Source trace |
|---|---|---|---|---|---|
| OD-01 | Database/version | TE-01/08/09/10/11/16; TV-01/04/08 | BL-01/06 | AC-DB-01/03, AC-CON-01/02 | SRC-24 §3/8; SRC-26 §§3–6/14–18; SRC-27 §§6/14; SRC-28 §7 |
| OD-02 | PHP runtime/extensions | TE-02–06; TV-02/03 | BL-02/03 | AC-OBS-02 | SRC-25 §§3/4/9; SRC-26 §§3/4/12/14; SRC-27 §§6/8/14 |
| OD-03 | Comparator | TE-06/07; TV-03 | BL-03 | AC-PUB-01, AC-SUP-02, AC-OBS-02 | SRC-23 §9; SRC-24 §§3/5/8; SRC-25 §4; SRC-26 §12; SRC-27 §8; SRC-28 §§4/7 |
| OD-04 | Account lifecycle/provisioning | TE-13/15/18; TV-07/10/12 | BL-05/08/10 | AC-PPDB-01, AC-AUTH-01/02, AC-ACC-01–04 | SRC-23 §§4.9/10/11; SRC-24 §3/5; SRC-25 §§6/7; SRC-27 §6; SRC-28 §4 |
| OD-05 | Post-publication correction | TE-14/18; TV-06/10 | BL-04/08 | AC-EH-02/03, AC-OBS-01 | SRC-23 §§4.3/4.3a/6/10; SRC-24 §3/5; SRC-27 §6; SRC-28 §§5/7 |
| OD-06 | Legacy collision/reservation | TE-14/15; TV-05/06 | BL-04/06 | AC-LEG-01/02 | SRC-23 §§4.5a/b/4.6/4.7/10; SRC-24 §3/5/8; SRC-25 §5; SRC-27 §6 |
| OD-07 | Transfer evidence | TE-14/17; TV-06/11 | BL-04/08 | AC-EH-01, AC-SUP-03 | SRC-23 §§2/7/10; SRC-24 §3/5; SRC-25 §§5/7; SRC-27 §6; SRC-28 §§5/7 |
| OD-08 | Supplemental order | TE-06/07/08; TV-03/08/11 | BL-03/07/08 | AC-SUP-02, AC-TIME-03/04 | SRC-23 §7; SRC-24 §3/5; SRC-27 §6; SRC-28 §§5/7/8 |
| OD-09 | Valid placement | TE-17/18; TV-10/11 | BL-08/10 | AC-PLACE-01/02, AC-PUB-01 | SRC-23 §§2/7/11; SRC-24 §3/5; SRC-25 §7; SRC-26 §§11/13; SRC-27 §11 |
| OD-10 | Username conflict | TE-15; TV-07/10 | BL-05/08 | AC-REG-03, AC-AUTH-02, AC-ACC-01 | SRC-23 §§4.6/4.9/10; SRC-24 §3/5; SRC-25 §§6/7; SRC-27 §6; SRC-28 §4 |
| OD-11 | Canonical User–Siswa | TE-13/15; TV-07 | BL-05 | AC-AUTH-01/02 | SRC-23 §§4.9/5/11; SRC-24 §3/8; SRC-25 §6; SRC-26 §§6/11; SRC-27 §10; SRC-28 §4 |
| OD-12 | DB privilege/direct SQL | TE-11/12; TV-04/05 | BL-06 | AC-REG-01/02, AC-DEL-01 | SRC-23 §§3/5/6.6; SRC-24 §3/5/8; SRC-26 §§5/15/17; SRC-27 §7 |
| OD-13 | Batch atomicity/volume | TE-08/09/19/20; TV-08/09 | BL-09 | AC-PUB-03/04, AC-CON-02 | SRC-23 §§6/6.4–6.5; SRC-24 §§5/6; SRC-27 §§6/14; SRC-28 §§4/7 |
| OD-14 | Timestamp/time authority | TE-08/09; TV-08 | BL-07 | AC-EH-01, AC-COH-03/04, AC-TIME-01–04 | SRC-23 §§4.1/4.2/6.3/6.4/7; SRC-24 §5; SRC-27 §6; SRC-28 §5 |
| OD-15 | Year-code mapping | TE-01/14/16; TV-01/06 | BL-01/04/07 | AC-DB-02, AC-SEQ-02, AC-PUB-01 | SRC-23 §§2/4.1/7; SRC-24 §3/5; SRC-27 §6; SRC-28 §7 |
| OD-16 | Delete/restore/relink | TE-11/13/14/15; TV-04/07 | BL-04/05/06 | AC-DEL-01, AC-REG-01/02 | SRC-23 §§4.2/4.6/5/10/11; SRC-24 §3/5; SRC-25 §7; SRC-26 §§6/17/18; SRC-27 §6 |
| OD-17 | One initial batch | TE-08/09/10; TV-01/08 | BL-01/09 | AC-COH-02, AC-PUB-02, AC-PUB-04 | SRC-23 §§4.5/6/7; SRC-24 §§3/5/6; SRC-27 §6; SRC-28 §7 |
| OD-18 | Roles/separation | TE-12/18; TV-05/10/12 | BL-06/08/10 | AC-LEG-02, AC-OBS-01, authorization portions of AC-PUB/ACC | SRC-24 §3; SRC-25 §§8/10; SRC-26 §§5/15; SRC-27 §§6/7/13; SRC-28 §§6/7 |

All OD approvals remain `PENDING`; traceability does not imply approval.

## 4. Technical Evidence Traceability

| Evidence range | Facts / question tracked | Main related rules and decisions | Blocker / criteria | Source |
|---|---|---|---|---|
| TE-01–05 | Local DB, PHP CLI/web, dependencies and extensions | OD-01/02; NIS-R rules are target-dependent | BL-01/02; AC-DB-01, AC-OBS-02 | SRC-25 §3; SRC-26 §§3–5/14 |
| TE-06–07 | ICU locale, golden order and parity | OD-03/08; NIS-R05 | BL-03; AC-OBS-02, AC-SUP-02 | SRC-25 §4; SRC-26 §12; SRC-23 §9 |
| TE-08–11 | Isolation, locking, upsert, constraints, trigger/FK behavior | OD-01/12/13/17; NIS-R04/06 | BL-01/06/09; AC-COH/SEQ/REG/CON suites | SRC-24 §§4–6; SRC-26 §§6/14; SRC-23 §§5–6 |
| TE-12 | Runtime grants/direct SQL | OD-12; NIS-R04/10 | BL-06; AC-REG-01/02 | SRC-26 §§5/15/17 |
| TE-13–15 | User–Siswa, NIS legacy and username aggregates | OD-06/10/11/16 | BL-04/05; AC-DB-02, AC-LEG, AC-AUTH, AC-DEL | SRC-25 §§5/6; SRC-26 §§8–10/14 |
| TE-16–17 | Local schema/migration and PPDB/placement snapshot | OD-01/09/15 | BL-01/08; AC-DB-01, AC-PLACE, AC-PUB | SRC-26 §§6–11/14 |
| TE-18 | Mutation path/writer coverage | OD-04/09/12/18 | BL-10; AC-TU-01, AC-COH-01, AC-OBS-01 | SRC-25 §7; SRC-26 §13; SRC-23 §11 |
| TE-19–20 | Backup/restore and batch volume | OD-13 | BL-09; AC-DB-03, AC-PUB-03/04, AC-CON-02 | SRC-24 §§5/6; SRC-26 §§14/18 |
| TV-01–04 | Target database feature/schema behavior | OD-01/12/17 | BL-01/06; AC-DB and AC-REG | SRC-27 §14; SRC-28 §7 |
| TV-05–07 | Privilege, legacy, identity relation | OD-06/11/12/16 | BL-04/05/06; AC-LEG/REG/AUTH/DEL | SRC-27 §§7/10; SRC-28 §7 |
| TV-08–09 | Concurrency, recovery, capacity/restore | OD-13/17 | BL-09; AC-CON/PUB | SRC-27 §14; SRC-28 §7 |
| TV-10–12 | Writer/authorization, placement, activation observability | OD-04/09/18 | BL-08/10; AC-PPDB/TU/PLACE/OBS/ACC | SRC-27 §§11/14; SRC-28 §§4/7 |

## 5. Blocker Traceability

| Blocker | Main dependency | Closure evidence | Acceptance criteria | Source |
|---|---|---|---|---|
| BL-01 | OD-01 | Selected engine/version, schema feature matrix, target lock/restore test | AC-DB-01/03, AC-CON-01/02 | SRC-24 §8; SRC-26 §15 |
| BL-02 | OD-02 | Web SAPI/extensions and CI/runtime matrix | AC-OBS-02 | SRC-24 §8; SRC-26 §§4/15 |
| BL-03 | OD-03 | Owner golden order, locale and exact runtime parity | AC-PUB-01, AC-SUP-02, AC-OBS-02 | SRC-23 §9; SRC-26 §§12/15 |
| BL-04 | OD-06/07/15 | Authorized legacy inventory, evidence and conflict disposition | AC-DB-02, AC-LEG-01/02 | SRC-24 §8; SRC-25 §5; SRC-26 §15 |
| BL-05 | OD-11 | Complete link/username reconciliation and canonical approval | AC-AUTH-01/02, AC-ACC-01 | SRC-24 §8; SRC-26 §15 |
| BL-06 | OD-12 | Least privilege, trigger/SET NULL and direct-SQL proof | AC-REG-01/02, AC-DEL-01 | SRC-24 §8; SRC-26 §15 |
| BL-07 | OD-14/15 | Clock/year-code decision, equality/watermark test | AC-EH-01, AC-COH-03/04, AC-TIME | SRC-24 §8; SRC-28 §5 |
| BL-08 | OD-04–10/18 | Owner policies for transfer, correction, placement, supplemental, account | AC-PLACE, AC-PPDB, AC-SUP, AC-ACC | SRC-24 §8; SRC-26 §15 |
| BL-09 | OD-13 | Synthetic load, rollback/retry and restore evidence | AC-PUB-03/04, AC-CON-02, AC-DB-03 | SRC-24 §8; SRC-26 §15 |
| BL-10 | OD-18/architecture integration | Full routes/jobs/import/raw SQL/external writer inventory and centralization proof | AC-COH-01, AC-TU-01, AC-OBS-01 | SRC-25 §7; SRC-26 §13/15 |

## 6. Acceptance Criteria Traceability

All AC below are not run. Original criteria are from SRC-24 §5; refinement criteria are proposed from SRC-28 §§4–5.

| AC IDs | Rules / OD | Evidence / blocker | Source |
|---|---|---|---|
| AC-DB-01–03 | NIS-R04/10; OD-01/06/12 | TE-01/10/11/14/16; TV-01/04/06; BL-01/04/06 | SRC-24 §5; SRC-23 §§4–5/10 |
| AC-COH-01–04 | NIS-R06/08/09; OD-08/13/14/17 | TE-08/09/18; TV-08/10/11; BL-07/09/10 | SRC-24 §5; SRC-23 §§6–7 |
| AC-EH-01–03 | NIS-R02/12; OD-05/07/14/15/18 | TE-14/17; TV-06/08/11; BL-04/07/08 | SRC-24 §5; SRC-23 §§4.2–4.3a/6.3 |
| AC-LEG-01–02 | NIS-R04/10; OD-06/12/18 | TE-12/14; TV-05/06; BL-04/06 | SRC-24 §5; SRC-23 §§4.5a/b/10 |
| AC-SEQ-01–02 | NIS-R01/03/04; OD-01/13/15/17 | TE-08/10; TV-01/08; BL-01/09 | SRC-24 §5; SRC-23 §§4.4/4.5a/6 |
| AC-REG-01–03 | NIS-R04/05/10; OD-10/12/16 | TE-11/12/15; TV-04/05/07; BL-05/06 | SRC-24 §5; SRC-23 §§4.6/5/10 |
| AC-PUB-01–04 | NIS-R05/06/07; OD-03/09/13/17 | TE-06–10/17; TV-01/03/08/11; BL-01/03/08/09 | SRC-24 §5; SRC-23 §§6.4/7 |
| AC-SUP-01–03 | NIS-R02/08/09/12; OD-03/07/08/14/15 | TE-06–09/14/17; TV-03/06/08/11; BL-03/04/07/08 | SRC-24 §5; SRC-23 §7; SRC-28 §5 |
| AC-PLACE-01–02 | NIS-R07; OD-09/11/15 | TE-13/17/18; TV-07/10/11; BL-05/08/10 | SRC-24 §5; SRC-23 §§2/7/11 |
| AC-PPDB-01 | NIS-R07; OD-04/10/11/18 | TE-13/15/18; TV-07/10/12; BL-05/08/10 | SRC-24 §5; SRC-25 §7; SRC-23 §11 |
| AC-TU-01 | NIS-R07; OD-09/12/18 | TE-18; TV-10; BL-10 | SRC-24 §5; SRC-25 §7; SRC-23 §11 |
| AC-AUTH-01–02 | NIS-R04/07; OD-04/10/11/16/18 | TE-13/15; TV-07/10; BL-05/08 | SRC-24 §5; SRC-25 §6; SRC-28 §4 |
| AC-DEL-01 | NIS-R04/10; OD-05/11/12/16 | TE-11/13/14; TV-04/05/07; BL-04/05/06 | SRC-24 §5; SRC-23 §§4.6/5/10 |
| AC-CON-01–02 | NIS-R06; OD-01/12/13/17 | TE-08/09/19/20; TV-01/08/09; BL-01/09 | SRC-24 §5; SRC-23 §§6.1–6.5 |
| AC-OBS-01–02 | NIS-R04/05/10; OD-03/05/12/18 | TE-06/07/12/18; TV-03/05/10/12; BL-03/06/10 | SRC-24 §5; SRC-23 §§6/9 |
| AC-ACC-01–04 | NIS-R06/07; OD-04/10/11/18 | TE-13/15/18; TV-07/10/12; BL-05/08/10 | SRC-28 §4; added in consolidated architecture, not in prior AC register |
| AC-TIME-01–04 | NIS-R08/09; OD-07/08/14/15/17 | TE-08/09/17; TV-08/11; BL-07/09 | SRC-23 §§4.1/6.3/6.4/7; SRC-28 §5; added in consolidated architecture |

## 7. Source Gaps and Conflicts

| Gap/conflict ID | Description | Affected traceability |
|---|---|---|
| SRC-GAP-01 | Tahap 2A.1 not available in workspace. | Historical initial facts/decisions cannot be traced; all rows that might depend on it are incomplete at source level. |
| SRC-GAP-02 | Tahap 2A.2 not available in workspace. | Workflow refinement history cannot be independently checked. |
| CON-01 | Supplemental sort is stated as timestamp/name/ID in one place and timestamp/ordinal/name/ID in refined timestamp analysis. | NIS-R09, OD-08, TE-07/08, BL-03/07, AC-SUP-02 and AC-TIME-03. Recommendation is ordinal before name; owner approval pending. |
| CON-02 | 2A.3 proposes username synchronization in publication transaction; 2A.7R recommends outbox provisioning after publication. | OD-04/10/11; AC-REG-03, AC-PPDB-01, AC-AUTH-02, AC-ACC-01/04. Username reservation point remains owner decision. |
| CON-03 | Timestamp-only initial cutoff and ordinal watermark both appear in prior materials. | OD-14/17, AC-COH-03/04, AC-TIME-04. Watermark recommendation is not implemented or approved; exact boundary requires test. |

## 8. Verification Statement

This matrix maps available written sources and does not prove that the linked tests ran, that blockers are closed, or that architecture is implemented. No owner decision is represented as approved. Missing Tahap 2A.1/2.2 materials prevent complete historical traceability.
