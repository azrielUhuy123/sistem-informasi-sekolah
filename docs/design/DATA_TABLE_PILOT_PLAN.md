# SINERGI-MJ Data Table Pilot Plan

**Status:** Proposed implementation sequence; approval required before coding.  
**Source inspection:** Read-only. No application, route, controller, database, or workflow changes were made.

## 1. Decision summary

Run three isolated pilots after the owner approves the token direction:

1. **A — Master Data:** Superadmin Mata Pelajaran, a simple read-only list.
2. **B — Transaction/Workflow:** Superadmin Data Kepegawaian Approval, with status, DOM search, confirmation and PATCH actions.
3. **C — Academic/Specialized:** Siswa Jadwal Mingguan, a read-only timetable matrix with dynamic day columns.

These represent three different table families without using the pilot as a reason to add new filters, pagination, sorting, bulk actions, or workflow behavior. The full inventory is in [`DATA_TABLE_MIGRATION_INVENTORY.md`](./DATA_TABLE_MIGRATION_INVENTORY.md).

## 2. Design-token verification and comparison

### Actual source evidence

| Token area | Current implementation evidence | Comparison to `DATA_TABLE_DESIGN_SYSTEM.md` |
|---|---|---|
| Primary / interactive blue | No single project-level primary token was found. Bootstrap's default primary (`#0d6efd`) is used in shared helpers; `dashboard.css` and `pagination.css` use `#3f51f5`; form focus and shared blue utilities use `#2563eb`; page hero gradients use `#0f4c81` to `#2563eb`; Manajemen Penugasan also uses `#17416e`. | The spec's `#2563eb` focus color exists in source, but it is not the sole primary color. **Do not declare or replace the canonical brand primary during a table pilot.** |
| Background | White (`#fff`/`#ffffff`) is the table/form surface; `#f8fafc` is used for table headers, disabled controls and utility areas. Dashboard page background includes `#eef1f8`. | White and `#f8fafc` match the spec proposal. Dashboard background is a separate page-level treatment. |
| Border | Shared table row border `#eef2f7`; pagination and disabled controls `#e2e8f0`; form controls `#dbe4ee`; CRUD inline CSS additionally uses `#e0e4f4` and `#eef1f9`. | The spec proposal's `#e2e8f0` exists, but table rows do not use it consistently. Tokenization should preserve table-specific, subtle separators. |
| Text / muted | Shared table header and pagination use `#64748b`; pagination strong text and shared text utility use `#334155`; form control text uses `#1e293b`; dashboard has page-specific palette. | The spec's `#334155` and `#64748b` are already used, but not centrally tokenized. |
| Typography | Bootstrap is imported through `resources/css/app.css`; `dashboard.css` sets `'Segoe UI', sans-serif` in its scope. Table header in `table.css` is `.75rem` (12px), reduced to `.7rem` (11.2px) on mobile. Table cells inherit Bootstrap size at desktop and become `.8rem` (12.8px) on mobile. Status badges use `.75rem` (12px). | The spec's 14px body/header and 13px status text are larger than the current table/mobile values. Pilot should adopt the spec's readable baseline only inside the table scope; do not globally override Bootstrap typography. |
| Spacing | Shared table cells use 14px vertical and 16px horizontal padding; mobile uses 11px/12px. Common toolbar gaps are 8px and 12px. CRUD has separate 14px header padding and 12px/16px body padding. | Comfortable spec padding (12px/16px) is close to desktop implementation; current mobile density is smaller. |
| Radius | Pagination buttons: 8px desktop / 7px mobile. Form controls: 14px; CRUD responsive wrapper: 10px. Cards use Bootstrap `rounded-4` (24px). Status badges use `999px`. | The spec's 8px table radius aligns with pagination; do not force card radius into cell controls. |
| Buttons | Bootstrap supplies `.btn` variants; `x-button.add` composes `btn btn-primary rounded-3` on desktop and a mobile FAB. Approval uses standard success/danger buttons with page-local sizing. No single global table-action button token was found. | Keep current Bootstrap button identities and establish table control sizing in a scoped component. |
| Badges | `badge.css` defines status colors for active, draft, complete, inactive, pending and verification; status font is `.75rem`. `role-badge` uses a separate role palette. | Semantic status variants already exist; do not add new badge colors or use role badges as statuses. Increase text size only in a scoped, approved table treatment. |
| CSS variables | No table token set or single shared color-token map was found in the inspected table CSS. | The `--dt-*` names in the design spec are proposed, not implemented application tokens. |

### Token gate

Before phase 1, the owner should confirm whether the shared table layer should:

- retain Bootstrap `#0d6efd` as the primary control color;
- use the currently common utility/focus blue `#2563eb`; or
- defer to a broader visual identity decision.

Until confirmed, use existing neutral and semantic colors in the table scope; do not introduce a new primary, selected-row, error, or status color. The pilot may use existing `#f8fafc`, `#64748b`, `#334155`, `#eef2f7`, and `#f8fbff` where they already match the application.

## 3. Pilot candidates

### A. Master Data — Superadmin Mata Pelajaran

- **Source:** [`superadmin/mapel/index.blade.php`](../../resources/views/superadmin/mapel/index.blade.php), [`MataPelajaranController.php`](../../app/Http/Controllers/SuperAdmin/MataPelajaranController.php), `routes/superadmin.php`.
- **Route/method:** `GET /mapel`, named `superadmin.mapel.index`, handled by `MataPelajaranController@index`.
- **Columns:** No, Kode, Nama Mata Pelajaran (3).
- **Existing behavior:** `<x-table.card>`, Bootstrap table, horizontal overflow, `@forelse` empty branch; no search, filter, sort, pagination, bulk, export or table-specific JavaScript found.
- **Why selected:** Small, read-only master list; low interaction surface gives a safe place to validate scoped typography, row density, header, empty state and wrapper without touching CRUD workflows.
- **Complexity:** Low.
- **Backend dependency:** Controller-provided collection and row values; no query or mutation contract found in this view.
- **Regression risk:** Low. Main risk is accidental addition of controls that do not exist in the backend contract, or changing the displayed collection semantics.
- **Must preserve:** Existing route/access, column values and order, count badge, empty wording, and page navigation. The current empty row spans 4 columns for 3 headers; correct the visual colspan to match the table without changing empty-state meaning.
- **Pilot boundary:** Visual-only. Do not add create/edit/delete actions, search, pagination, sort, or bulk selection.

### B. Transaction/Workflow — Superadmin Data Kepegawaian Approval

- **Source:** [`approval.blade.php`](../../resources/views/superadmin/datakepegawaian/approval.blade.php), [`DataKepegawaianApprovalController.php`](../../app/Http/Controllers/SuperAdmin/DataKepegawaianApprovalController.php), `routes/superadmin.php`.
- **Routes/methods:** Approval list `GET superadmin.datakepegawaian.approval`; detail `GET superadmin.datakepegawaian.show`; approve `PATCH superadmin.datakepegawaian.approve`; reject `PATCH superadmin.datakepegawaian.reject`.
- **Columns:** No, Nama Pegawai & NIP, Status, Validasi, Aksi (5).
- **Existing behavior:** Controller loads `DataKepegawaian::all()`. Search is an inline DOM filter on `#tablePegawai`, not a query parameter or server filter. Approve/reject actions are status-dependent; approval and rejection use confirmation/modal UI, and rejection submits a reason. The table has `@forelse`, `.table-responsive`, no paginator/sort/bulk/export, and no loading state.
- **Why selected:** It represents a real workflow table with visible status, a bounded search interaction and consequential actions, so the shared layer can be validated against nontrivial use without changing its behavior.
- **Complexity:** Medium.
- **Backend dependency:** GET list/detail and PATCH approve/reject; the rejection payload includes `rejected_reason`. Controller retrieves all rows; no pagination contract is currently used.
- **Regression risk:** High relative to A/C because approval/rejection can affect account and employee state. Search DOM scope and status-based action visibility are easy to break during row rendering changes.
- **Must preserve:** Search remains client-side over the same rendered rows unless a separately approved backend change is made; status text; action visibility; route IDs; PATCH methods; CSRF; confirmation wording; rejection modal and reason field; empty-state row. The current empty row spans 4 columns for 5 headers; correct its colspan as a presentation fix.
- **Pilot boundary:** Do not change controller, endpoint, query behavior, business status, confirmation flow, or add pagination/bulk actions. Implement accessible visual controls and semantic table markup only.

### C. Academic/Specialized — Siswa Jadwal Mingguan

- **Source:** [`siswa/jadwal/index.blade.php`](../../resources/views/siswa/jadwal/index.blade.php), [`JadwalController.php`](../../app/Http/Controllers/Siswa/JadwalController.php), `routes/siswa.php`.
- **Route/method:** `GET /siswa/jadwal`, named `siswa.jadwal.index`, with `feature:siswa.academic`.
- **Columns:** Jam plus one dynamic column for every school day in `$hari`; each cell shows subject, teacher and time context, or “Tidak Ada”.
- **Existing behavior:** `<x-table.card>`, `.table-responsive`, bordered schedule matrix, inline `.jadwal-*` CSS, no search/filter/sort/pagination/bulk/export or table JavaScript.
- **Why selected:** It exercises the specialized-table rule: retain a timetable as a matrix rather than forcing it into a generic CRUD list.
- **Complexity:** Medium, due dynamic day headers, time rows, cell content and narrow-screen width.
- **Backend dependency:** Read-only controller-built `$hari`, `$jam`, `$grid`, and active placement/class context; no table mutation route.
- **Regression risk:** Low to medium if the work remains scoped. Risks include losing row/column context, altering cell min-width, changing “Tidak Ada” meaning, or breaking horizontal scroll.
- **Must preserve:** All dynamic day columns, time intervals, class identity, subject/teacher content, no-schedule state, existing route/middleware and current scroll behavior.
- **Pilot boundary:** No sorting/search/CRUD/pagination. Add caption and row/column header semantics only after accessibility review; keep the matrix visually distinct from list tables.

## 4. Dependency-aware migration sequence

The sequence below follows the requested 1–11 areas, but treats backend contract mapping and accessibility as gates that run before and during the related component work.

### Phase 0 — Baseline and contract gate

1. Freeze screenshots and record current role, exact route/method, query names, POST payload, paginator behavior, export route and action authorization for each pilot.
2. Confirm token choice with the owner. The current source has several competing blues; no new primary is selected here.
3. Record a table's feature scope. A pilot may not gain a capability simply because a reusable component supports it.

### Phase 1 — Shared design tokens

1. Introduce table-scoped CSS variables only after the token gate; source CSS must not receive global color/typography changes.
2. Map approved values to existing colors and dimensions first. Keep Bootstrap variables and other modules untouched.
3. Use a scoped table class; do not change selectors for every `.table` in the application.

### Phase 2 — Shared table wrapper

1. Retain `x-table.card` and add only nonbreaking slots/attributes if the pilot needs them.
2. Keep wrapper layout (title, count, toolbar, table and footer) separate from page route/query logic.
3. Pilot A first; verify the simple empty and populated states before moving to B/C.

### Phase 3 — Toolbar

1. Add toolbar structure only to views that already have controls or an approved feature requirement.
2. Keep pilot A without a toolbar control; B keeps its current search; C remains a matrix with no toolbar.
3. Do not assume a `name` attribute or route parameter exists just because a search box is displayed.

### Phase 4 — Search and filters

1. Preserve every current parameter name, value, and search scope.
2. Keep B's current DOM-only search behavior for a style-only pilot. Converting it to server search requires a separate backend approval.
3. Keep C's `hari` structure; no additional filter is introduced by this migration.

### Phase 5 — Header and sorting

1. Add semantic header scopes and accessible names to every pilot.
2. None of the three pilots needs sorting. Do not display sort indicators/buttons for them.
3. For later tables, implement sorting only when the controller has a confirmed, safe sort contract.

### Phase 6 — Cell styling

1. Add default, status, numeric and action cell styles under the scoped table class.
2. Preserve each pilot's column order and data formatting.
3. Keep the student schedule matrix's custom subject/teacher cells; do not put a card inside every ordinary list cell.

### Phase 7 — Row actions

1. Keep A read-only.
2. In B, preserve approve/reject/detail routes, methods, CSRF, confirmation and modal payload exactly.
3. C has no row action. Do not render a placeholder action column.
4. Retain `x-table.actions` for existing consumers; do not replace all action menus during pilot.

### Phase 8 — Pagination

1. Do not add pagination to any of the three pilots: their current controllers/views do not expose a paginator.
2. Validate `x-table.pagination` and Laravel `links()` later on a table that already paginates.
3. Avoid page-size, query-string or paginator changes as part of this phase.

### Phase 9 — Empty state

1. Standardize the empty presentation without changing the meaning of current empty branches.
2. A and B have record-list empty states; C represents unfilled timetable cells as “Tidak Ada”, which is not the same as an empty result set.
3. Verify `colspan` against the real column count; do not copy existing mismatches.

### Phase 10 — Responsive behavior

1. Keep horizontal scrolling for C's day-by-day matrix.
2. For A/B, verify search/actions and table widths at desktop and mobile; do not rely on hover for actions.
3. Remove fixed widths only when measured and tested; the current global 650px/600px minimum must not be changed globally in a pilot.

### Phase 11 — Accessibility and regression gate

Accessibility begins in the wrapper/header/control components, not after their implementation. Final verification must cover semantic table structure, captions, labels, keyboard focus, sort state if later applicable, status text, screen-reader row count, contrast, reduced motion and responsive zoom.

## 5. Pilot acceptance criteria

- No application route, controller, model, database, authorization, workflow, request parameter or payload changes.
- No added search, filter, sort, paginator, selection or export capability.
- The three tables retain all current columns, values, messages and action behavior.
- A and B empty branches span the correct visible column count.
- B's client-side search scope and PATCH confirmation actions remain unchanged.
- C's day columns, time rows, cell content, empty-cell text and horizontal scroll remain intact.
- Shared styles do not leak into unrelated `.table` elements, PDF output or CRUD pages.
- Keyboard, screen-reader and narrow viewport checks pass before declaring each pilot complete.

## 6. Unresolved risks / approval gates

1. The authoritative primary blue has not been selected; existing Bootstrap, dashboard, pagination, form and module colors differ.
2. Global `.table` rules and the inline CRUD table styles can mask pilot results unless the new selectors are scoped.
3. Some existing client-side search only sees rendered DOM rows; changing its scope would change behavior.
4. Some pages have no table pagination even when their controllers expose related parameters; do not infer missing UI features.
5. Known empty-state colspan mismatches include Superadmin Mata Pelajaran (4 vs 3 headers), Data Kepegawaian Approval (4 vs 5 headers), and Superadmin Siswa (9 vs 7 headers); other empty states need per-table verification.
6. Table-specific loading/error states, complete ARIA coverage, and sticky-header behavior are not consistently present.
7. `routes/siswa.php` declares duplicate GET routes for the nomor-surat list path/name; route ownership must be reviewed before migrating that page. The earlier audit also noted duplicate student document-download route declarations.
8. Numerous non-pilot list route/controller pairs remain `BVD` in the inventory and must be traced before each migration batch.
9. PDF and print tables are intentionally out of scope for the interactive pilot; print visual regression requires a separate test pass.

**Recommendation:** Approve the token direction and pilot scope first. Implement A as the first low-risk proof of scoped styles, then C as a distinct specialized-table check, and only then B after explicit workflow-owner approval. Do not start mass migration until all three pass their separate regression gates.
