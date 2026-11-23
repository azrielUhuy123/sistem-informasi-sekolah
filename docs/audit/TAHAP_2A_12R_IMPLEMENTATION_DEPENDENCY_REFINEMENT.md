# TAHAP 2A.12R — IMPLEMENTATION DEPENDENCY REFINEMENT

**Baseline induk:** [TAHAP 2A.12 Implementation Dependency Plan](./TAHAP_2A_12_IMPLEMENTATION_DEPENDENCY_PLAN.md)  
**Status:** Dependency refinement dokumenter; tidak mengotorisasi implementasi.

## 1. Tujuan dan Aturan Membaca Graph

Dokumen ini mengganti visualisasi graph workstream A–I di rencana 2A.12 agar dependency arah dan prerequisite eksplisit. Tujuan, risiko, acceptance criteria, dan scope workstream di 2A.12 tetap berlaku kecuali dipertegas di bawah.

- Panah `X → Y` berarti **X wajib selesai/terpenuhi sebelum Y dapat melanjutkan ke gate yang bergantung padanya**.
- `I` dimulai sejak awal dan berjalan cross-cutting. I bukan predecessor data/DB yang menciptakan cycle; deliverable authority/privilege/audit I adalah gate yang harus terpenuhi sebelum publisher atau exception operation diaktifkan.
- Graph adalah dependency plan, bukan urutan persetujuan owner dan bukan bukti pekerjaan telah selesai.
- Tidak ada readiness blocker yang ditutup oleh graph ini.

## 2. Dependency Graph Final

```text
Core sequencing:
A → B
B → D
D → C
C → E
A → F
E → F
B → G
C → G
D → G
E → G
F → G
A → H
B → H
C → H
F → H
I → H

Activation gates (not sequencing predecessors for planning work):
I --activation gate--> E (initial publication)
I --activation gate--> G (supplemental publication)
I --activation gate--> H (exception/correction/relink operations)

I starts at project outset and continues cross-cutting.
```

Equivalent dependency sets:

| Workstream | Required predecessor/deliverable |
|---|---|
| **A** | Start after target-engine direction, schema/data authorization, and backup/recovery prerequisites are established. I governance/privilege design starts in parallel; A must not activate runtime mutation/publication without applicable I gate. |
| **B** | `A + I` authorization/evidence gate. |
| **D** | `B + I` authorization gate; A database lock/constraint capabilities are available through B's required A predecessor. |
| **C** | `D` (and therefore `B`, `A`) plus approved comparator/year/timestamp contracts and I privilege enforcement gate. |
| **E** | `C` (and therefore `D`, `B`, `A`) plus I authority/authorization/privilege/audit activation gate. |
| **F** | `E + A`; also requires the OD-11 canonical relationship reconciliation and approval, target worker/runtime readiness, and OD-04/10/11/18 decisions. Reconciliation may be delivered in B before E; this does not add a reverse edge. |
| **G** | `B + C + D + E + F`; also requires OD-07/08/09/14/15 policies, comparator/timestamp validation, and I’s publication activation gate. |
| **H** | `A + B + C + F + I`; also requires applicable OD-05/11/12/16/18 approval, evidence, and recovery contract. |
| **I** | Starts at project outset; continues across A–H. Its readiness/authority/privilege deliverables gate publication and exception activation, not the planning or documentation of upstream work. |

I’s start is not an edge `E → I` or `H → I`; such an edge would incorrectly reverse the required activation gate and could introduce a cycle. I may define/prepare its control design while A–D are being developed, but the relevant control evidence must pass before activation.

## 3. Workstream Consistency and Preserved Prerequisites

### A — Database foundation

Retains target engine, schema-drift and legacy preflight, schema/data authorization, and backup/restore prerequisites. A is the base of the serial data path. Runtime grants must be separated; I defines the privilege boundary. Target-specific DDL remains deferred until engine/target is selected.

### B — Cohort, entry history, and legacy reconciliation

Retains A’s storage direction, authorized data access, evidence/approver roles, and OD-06/07/14/15/18 policy gates. Inventory must cover active, soft-deleted, historical backup, and available administrative sources. Historical collision/provenance uncertainty blocks the affected allocation. Any OD-11 canonical-link reconciliation needed by F must use its staged process and complete before F is activated.

### D — Placement and cohort locking

Retains A lock/constraint capability, B entry evidence, OD-07/09/14/15/17 decisions, and I authorization. D precedes C so allocation cannot bypass eligibility/cutoff and the cohort lock.

### C — Registry, permanent slots, and allocator

Retains target constraints/locking, B’s reconciled legacy slot and mapping, D placement/cohort lock, OD-03/08/14/15 contract, comparator/runtime evidence, and I privilege boundary. Under refined OD-08, supplemental sorting uses `official_entry_at → name comparator → siswa.id`; permanent cohort ordinal remains an immutable audit identity and is not a sorting key.

### E — Initial publication

Retains A/B/C/D, OD-03/09/13/14/15/17 decisions, I authorization, and target rollback/recovery evidence. OD-12 effective-grant verification and denied-mutation tests are explicit activation gates. NIS, registry/slots, cutoff, batch success, and account-provisioning intent must meet the OD-13 atomic visibility contract.

### F — Async account provisioning/outbox

Retains E’s outbox contract and A’s target queue/database capability, OD-04/10/11/18 confirmation, canonical relation reconciliation, and worker operational readiness. It may not allocate NIS or start before E’s publication contract exists; retries use the same intent key. Login remains denied until account and canonical link are valid.

### G — Supplemental issuance

Retains B/C/D/E/F, OD-07/08/09/14/15, comparator/runtime parity, and timestamp/cutoff gates. It cannot reopen or create a second initial batch. Already-issued NIS remains unchanged after later name changes.

### H — Correction, deletion, restore, and relink

Retains A/B/C/F/I, immutable registry enforcement, reconciliation/evidence, canonical account behavior, and OD-05/11/12/16/18 approval. Force-delete/restore/relink cannot remove or reuse an issued number/slot.

### I — Authorization, audit, and operational hardening

Starts immediately and remains cross-cutting. It retains formal authority identification, least-privilege separation, audit, operational monitoring/recovery and no self-approval. Effective grants and denied direct mutation evidence gate publisher activation. Break-glass use must be auditable. Its status cannot be marked complete from a proposed design alone.

## 4. Cycle and Direction Check

The directed workstream edges are:

```text
A → B
B → D
D → C
C → E
A → F
E → F
B → G
C → G
D → G
E → G
F → G
A → H
B → H
C → H
F → H
I → H
I --activation gate--> E
I --activation gate--> G
```

This graph has no reverse edge from downstream work to A–E, no self-edge, and no circular dependency, including the activation-gate edges. I starts independently; no edge returns from E/G/H to I. The required base chain is exactly `A → B → D → C → E`; all specified F/G/H dependencies are retained.

## 5. Activation Gates and Status

Publisher activation requires all applicable A–E outputs, OD-13 atomicity evidence, OD-17 exactly-one initial guard, and I’s verified target privilege/authorization/audit controls. Supplemental activation additionally requires G prerequisites. Exception/correction/relink activation requires H prerequisites and I’s separation-of-duties gate.

At this refinement:

- Workstreams A–I are plans, not reported complete.
- No effective target grants, denied-mutation tests, backup/restore drill, or application acceptance tests were run.
- Technical readiness remains `NOT READY`.
- Implementation remains `NOT AUTHORIZED`.
- School policy confirmation remains `PENDING`; OD-01–OD-18 remain `PENDING`; BL-01–BL-10 and SRC-GAP-01/02 remain `OPEN`.
