# SINERGI-MJ Data Table Design System

**Status:** Technical and visual specification; shared foundation implementation reference  
**Scope:** Blade and Vue data tables, table-related styles, interaction patterns, and their existing backend contracts  
**Implementation status:** Shared components provide a compatible foundation; page-level table migrations remain separate and require their own validation.

## 1. Design Principles

This specification uses the [Setproduct Data Table UI Design Reference Guide for 2026](https://www.setproduct.com/blog/data-table-ui-design) as its primary design reference while retaining SINERGI-MJ's Bootstrap-based visual identity.

1. Use a table when people need to compare records across consistent attributes. Keep schedule matrices, printable reports, dashboards, and other specialized layouts specialized.
2. Give every table the same visual foundation, not the same feature set. Search, filters, sorting, selection, pagination, and row actions are enabled only when the screen's task and backend support them.
3. Keep the table anatomy predictable: toolbar, semantic header, body, and footer. Sticky elements are optional and must solve a real orientation problem.
4. Prefer comfortable readability over maximum row count. Density changes spacing, not the minimum readable type size.
5. Use whitespace and subtle row separators rather than a full grid of borders or decorative cards inside cells.
6. Make every interactive state visible to pointer, touch, keyboard, and assistive-technology users.
7. Keep server-backed behavior server-backed. Client-side sorting or filtering must not imply it covers records that were never loaded.
8. Treat current routes, query parameters, payloads, pagination, exports, and authorization as contracts. A visual redesign does not change them.

## 2. Design Tokens

These are proposed defaults for the shared table layer. A module may override a token only for a documented functional reason, such as a dense schedule matrix.

```css
:root {
    --dt-font-family: inherit;
    --dt-font-size: 0.875rem;
    --dt-font-size-header: 0.875rem;
    --dt-font-size-meta: 0.8125rem;

    --dt-color-text: #334155;
    --dt-color-muted: #64748b;
    --dt-color-header-bg: #f8fafc;
    --dt-color-row-bg: #ffffff;
    --dt-color-row-hover: #f8fbff;
    --dt-color-row-selected: #eef4ff;
    --dt-color-border: #e2e8f0;
    --dt-color-border-subtle: #edf1f5;
    --dt-color-focus: #2563eb;

    --dt-radius: 0.5rem;
    --dt-control-height: 2.5rem;
    --dt-cell-padding-x: 1rem;
    --dt-cell-padding-y: 0.75rem;
    --dt-header-height: 3rem;
    --dt-row-min-height: 3.25rem;
}
```

### Token rules

- Use the existing SINERGI-MJ blue as the primary interactive color; reserve green, amber, red, and neutral status colors for their semantic meanings.
- Prefer one subtle horizontal separator per row. Do not add vertical cell borders by default.
- Do not use shadows on every row or cell. A card-level shadow is sufficient where the surrounding page already uses one.
- Avoid global `min-width` rules on every `.table`. Width behavior belongs to a table variant or its wrapper.
- Keep numeric values right-aligned when magnitude comparison matters. Names and descriptive text are left-aligned; status and compact indicators may be centered.

## 3. Typography Specification

| Element | Font size | Weight | Rules |
|---|---:|---:|---|
| Cell body | `0.875rem` / 14px | 400 | Default readable text; do not reduce to fit more columns. |
| Column header | `0.875rem` / 14px | 600 | Sentence case preferred; clear contrast from body. |
| Primary identifier | `0.875rem` / 14px | 600 | May use a second muted line for an identifier such as NIP or NIS. |
| Supporting cell text | `0.8125rem` / 13px | 400 | Secondary information only; never the sole representation of a critical value. |
| Status badge | At least `0.8125rem` / 13px | 600 | Keep visible text; do not communicate status by color alone. |
| Toolbar labels and controls | `0.875rem` / 14px | 400–500 | Search and filters need accessible names, not placeholder-only labels. |
| Pagination | `0.8125rem` / 13px minimum | 500–600 | Show result range and total; maintain a clear current-page state. |

Use the application font stack (`inherit`) unless a separately approved application-wide typography change is made. Mobile layouts must not shrink table body text below the cell-body size simply to avoid horizontal scrolling.

## 4. Density Specification

Density is a table-level presentation option, not a row-by-row style. Default to **Comfortable**. Density may be persisted only if a later implementation has an approved preference mechanism.

| Density | Header min-height | Row min-height | Vertical cell padding | Horizontal cell padding | Intended use |
|---|---:|---:|---:|---:|---|
| Compact | 44px | 44px | 8px | 12px | Repeated operational scanning; controls still meet touch/keyboard needs. |
| Comfortable | 48px | 52px | 12px | 16px | Default for mixed-role school workflows. |
| Spacious | 52px | 64px | 16px | 16px | Small sets or touch-first review. |

Interactive targets should be at least 44px high where practical. The Setproduct reference notes the WCAG 2.2 24-by-24 CSS-pixel minimum criterion and the larger Material Design touch target recommendation; this system uses 44px as its preferred control target to make actions easier to hit.

Illustrative density selector:

```html
<div class="table-responsive" data-density="comfortable">
    <!-- semantic table -->
</div>
```

## 5. Component Specification

### Table wrapper

- Owns the visual boundary, title, optional description, optional count badge, toolbar slot, table body, and footer slot.
- Uses the existing `x-table.card` as the base; it must not decide business behavior or infer routes.
- Table itself remains a semantic `<table>`.
- Wrapper handles horizontal overflow only when the specific table requires it.
- Current API preserves `title`, optional `subtitle`, `badge` and `actions` slots, and the default slot. Optional `density` accepts `comfortable` (default), `compact`, or `spacious`; the wrapper exposes its heading through `aria-labelledby`.
- New `.dt-table` cell styling is opt-in. Existing `.table` markup is not automatically redesigned by the shared foundation.

### Toolbar

- Aligns search, relevant filters, primary action, and contextual bulk actions above the table.
- Responsive order: title/count, search and filters, then actions; controls wrap without overlap.
- Active filters remain visible via selected control state and, when there are multiple filters, removable filter chips or an equivalent summary.
- Do not show disabled controls that the current table cannot use.

### Search

- Use a labeled search input with an accessible name and a clear affordance.
- Use server search for server-paginated data. Debounce only asynchronous requests; ordinary GET filters may submit on Enter or an explicit submit button.
- State clearly whether search covers all records or only a loaded client-side set. The standard for server tables is all records matching backend query rules.
- Clearing search preserves other active filters.

### Filters

- Render only filters relevant to that table: role/status, year, class, activity, and similar bounded fields.
- Preserve existing parameter names and current values.
- Provide a visible reset/clear action when filters are active.
- Do not make a client-side filter appear to filter records beyond the loaded page.

### Column header and sorting

- Use `<th scope="col">` for column headers.
- Sortable headers use a native `<button>` inside the header, with a visible ascending/descending indicator and `aria-sort` on the active `<th>`.
- Only expose sorting for columns supported by the current controller/query. For server pagination, sorting must apply to the full result set, not just the current page.
- Unsortable headers remain visually quiet and are not given pointer cursors.

### Table body and cell types

- A row represents one record and cells keep a stable meaning across rows.
- Use the cell patterns in Section 6; avoid placing a card in every cell.
- Use a visible selected-row background when row selection exists.
- Do not hide essential row actions until hover.

### Row actions

- Retain `x-table.actions` for grouped secondary actions; primary actions may remain direct links where they are common and safe.
- Keep action ordering stable and destructive actions visually separated.
- Use a button with an accessible name and expanded state for dropdowns; dropdown items must work by keyboard and touch.
- Use the existing dropdown-to-body positioning behavior only where needed to prevent responsive-container clipping.
- Current `x-table.actions` keeps its default dropdown behavior and slot; optional `label` and `menuId` props provide an accessible trigger name and stable caller-supplied menu relationship.

### Bulk selection

- Use a leading checkbox column only when a real bulk operation exists.
- Label the header checkbox with its selection scope, e.g. “Pilih semua pada halaman ini”.
- Show a contextual toolbar with selected count and supported actions; state whether selection persists across pages.
- “Select all” means current page unless the UI explicitly offers and explains selecting all matching records.
- Keep the existing POST method, CSRF token, payload shape, and backend validation.

### Pagination

- Retain `x-table.pagination` as the shared Blade pagination presentation, extending it only as needed for accessibility and consistency.
- Show “Menampilkan X–Y dari Z data”, current page, total pages, previous/next controls, and a visible current-page state.
- Preserve query strings and existing paginator behavior.
- Do not display pagination for a fixed, deliberately small matrix where it adds no value.
- Current API remains `paginator`; the component keeps its Laravel paginator URLs and exposes additional page/control labels. Its `.dt-pagination` styling is opt-in to the Blade component and does not change the separate Manajemen Penugasan renderer.

### Empty, loading, and error states

- Implement `x-table.empty` as the shared empty-state presentation. Distinguish:
  - no records exist yet;
  - filters/search returned no results.
- Loading state uses skeleton rows matching the target density when a known table layout is awaiting asynchronous results. Use a spinner only for short/unpredictable loads.
- Error state explains that data failed to load and offers Retry only if retrying is supported. Never show an empty state for a failed request.
- Server-rendered tables that arrive with the page do not need a fabricated loading state.
- Current `x-table.empty` renders a `<tr>` and accepts `colspan`, `state="empty|no-results"`, optional `title`/`description`, and an optional action slot. It belongs inside a table `<tbody>`.

## 6. Cell Specification

| Cell type | Alignment and presentation | Accessibility / behavior |
|---|---|---|
| Text / name | Left; permit wrapping only in designated descriptive columns. | Long values should have a detail view or explicit disclosure, not silent truncation. |
| Identifier | Left; optionally stack secondary identifier beneath the main name. | Keep identifier text available to assistive technology. |
| Number / score / currency | Right when comparing magnitudes; use consistent decimal/number format. | Do not communicate meaning by color alone. |
| Date / time | Choose one alignment per table and keep it consistent. | Format is clear and unambiguous in Indonesian locale. |
| Status | Semantic text badge using existing status palette. | Include status text; icon/color are supplementary. |
| Link | Use a recognizable link treatment for navigation to details. | Link text describes the destination or record. |
| Row action | Compact, rightmost cell; direct primary action and/or action menu. | Keyboard-operable; never hover-only. |
| Selection | Leading checkbox cell. | Label each row checkbox with its record; announce selected count. |
| Schedule / matrix value | Centered where the grid represents a timetable or attendance matrix. | Preserve row/column headers and provide horizontal-scroll context. |

Badges are for statuses or a small number of semantic categories. Do not badge ordinary descriptive values solely for decoration.

## 7. Interaction States

| State | Visual specification | Behavior |
|---|---|---|
| Default | White body, subtle bottom separators, calm header background. | No state is conveyed by color alone. |
| Hover | Light row tint (`--dt-color-row-hover`). | Does not reveal the only way to access actions. |
| Keyboard focus | Visible 2px primary outline with offset where possible. | All actions and controls remain reachable in logical tab order. |
| Selected | Persistent pale blue row tint plus checked checkbox. | Selected count and scope are visible; selection persistence is explicit. |
| Sorted | Directional icon and `aria-sort` on active header. | Result order matches the backend's full filtered dataset. |
| Filtered | Selected filter values and active-filter indicator/chip. | Reset removes filters without changing route/workflow. |
| Loading | Density-matched skeleton rows or contextual spinner. | Announce loading where asynchronous changes update the table. |
| Empty | Icon/heading/help text appropriate to first-run or no-results state. | Offer a relevant create/reset action only when supported. |
| Error | Clear error message and Retry only if supported. | Do not present stale data as newly loaded success. |
| Disabled | Muted control plus explanatory text where the reason is not obvious. | Disabled controls cannot be mistaken for active actions. |

## 8. Responsive Specification

1. Keep the table semantic and horizontally scrollable when preserving column comparison is essential, especially schedule and attendance matrices.
2. For ordinary record lists, prioritize identifier/name, status, and primary action. Move secondary fields to detail views or a deliberate “more” disclosure rather than shrinking text.
3. Use a card transformation only for small sets where users inspect one record at a time; it is not the default for large admin lists.
4. A frozen identifier column is optional for wide matrices. Test stacking order and alignment before enabling it.
5. Sticky headers are optional. Use only for tall tables where headers otherwise scroll away; test inside nested scrolling containers to prevent overlap and clipping.
6. On mobile, toolbar controls wrap to full width, row actions remain reachable, and no control relies on hover.
7. Do not apply a single fixed `min-width` to all tables. Set widths and overflow strategy per table family.

## 9. Accessibility Specification

- Use native `<table>`, `<caption>`, `<thead>`, `<tbody>`, `<th scope="col">`, and `<th scope="row">` where appropriate. Do not add `role="grid"` to a normal HTML table.
- Use visually hidden captions when a visible title already exists.
- Every search, filter, checkbox, and action has an accessible name. Placeholder text alone is not a label.
- Sort controls are native buttons. The active header carries `aria-sort="ascending"` or `"descending"`; the icon is decorative.
- Pagination uses a labeled `<nav>`, marks the current page with `aria-current="page"`, and exposes disabled state correctly.
- Selection changes, filter results, sort results, and row-count changes are announced through a polite live region when they update without navigation.
- Maintain visible focus, sufficient contrast, and a non-color cue for state.
- Respect reduced motion; avoid relying on animation to communicate table state.

## 10. Component Architecture

### Reuse and evolution of current components

| Existing file/component | Decision | Future responsibility |
|---|---|---|
| `resources/views/components/table/card.blade.php` | **Keep and extend** | Table section wrapper and named slots; no route or query logic. |
| `resources/views/components/table/actions.blade.php` | **Keep and refine** | Accessible row-action dropdown; preserve its clipping workaround where needed. |
| `resources/views/components/table/pagination.blade.php` | **Keep and refine** | Shared Laravel paginator presentation with total, current page, and accessible navigation. |
| `resources/views/components/table/empty.blade.php` | **Keep and extend** | Implemented first-run and no-results row states; loading/error states remain page-specific until a shared contract is defined. |
| `resources/views/components/table/heading.blade.php` | **Additive helper** | Renders a semantic `<th>` with `scope="col"` by default; supports `scope="row"` for row headers. |
| `resources/views/components/crud/` | **Retain as a compatibility adapter; do not make universal** | Support existing Admin CRUD screens while each page migrates to supported, explicit capabilities. |
| `resources/css/components/table.css` | **Keep and incrementally extend** | Scoped `.dt-*` tokens and opt-in table styling; legacy global selectors remain unchanged. |
| `resources/css/components/pagination.css` | **Keep and align to tokens** | Shared pagination spacing, focus, active and disabled states. |
| `resources/css/components/badge.css` | **Keep and align status variants** | Shared semantic status colors and text treatment. |

### Proposed responsibility tree

```text
x-table.card
├── title / subtitle / badge / actions slots
├── x-table.toolbar (optional)
│   ├── x-table.search (optional)
│   ├── page-specific GET filters
│   ├── primary action
│   └── contextual bulk actions (only when supported)
├── native table (page supplies semantic headers and cell slots)
│   ├── x-table.heading (semantic header cell)
│   ├── optional sort button (only with backend support)
│   ├── typed cells
│   ├── optional selection column
│   └── x-table.actions (optional)
└── footer
    ├── x-table.empty / loading / error (when applicable)
    └── x-table.pagination (when paginator is present)
```

Keep module-specific filters and cell content in the page view; the shared components should not infer field names, routes, user roles, or business actions. A Vue table component is not proposed unless a real Vue-owned data table is introduced.

## 11. Backend Compatibility Contract

The table layer is presentation-only. It must not change controller logic, routes, validation, authorization, middleware, models, or persistence.

| Contract | Compatibility rule |
|---|---|
| Route and HTTP method | Keep existing named route, method, and route parameters for list, detail, action, and export. |
| Query parameters | Preserve each controller's existing names and values. Do not rename or silently drop them. |
| Pagination | Continue using the paginator supplied by the controller. Preserve page size and query string only where already supported or separately approved. |
| Sorting | Render only when the controller implements a safe, supported sort contract. Keep existing `sort`/`order` names and server whitelist. Never sort only the visible page while implying global sorting. |
| Filtering/search | Submit existing parameters and values. A client-side filter must be clearly limited to the data loaded into the browser. |
| Bulk action | Preserve POST method, CSRF, `ids[]`/existing payload, confirmation, and server-side authorization/validation. Define selection scope explicitly. |
| Export | Preserve export route, selected IDs, filters, output format, and server preconditions. Export behavior is not replaced by browser-side generation. |
| Authorization | Hiding a button is not authorization. Existing route middleware and controller checks remain authoritative. |

### Existing contracts observed in the audit

- Admin user list: `sort`, `order`, fixed page size 10, and controller whitelist for `name`, `email`, `role`.
- Admin year list: `per_page` is accepted by the controller.
- SuperAdmin student list: `search`, `status`; server pagination of 20 with query string.
- TU student list: `angkatan`, `kelas_grup`, `kelas`, `status`, `search`; bulk payload `ids[]`; pagination of 20.
- TU account list: `role`, `status`; pagination of 20. The current text search is client-side.
- TU year list: `q`, pagination of 5.
- Feature Access list: pagination of 15 with query string.
- PPDB accepted list/export: `tahun_ajaran_id`; the accepted export requires it.
- Schedule export: selected `kelas_ids`.
- Manajemen Penugasan: its current list state is a frontend simulation; do not treat it as persisted server data.

These are preservation notes, not permission to change the backend. In particular, query retention and incomplete CRUD wiring identified by the audit need separate approval before backend or workflow changes.

## 12. Table Type Specifications

| Type | Required | Optional when supported | Usually not relevant |
|---|---|---|---|
| **A. Master Data** | Semantic columns, readable identifiers, status where applicable, detail/action path, empty state. | Search, bounded filters, server sorting, pagination, bulk action for a proven workflow. | Loading skeleton for ordinary server-rendered pages; export if no existing export use case. |
| **B. Transaction and Workflow** | Status text, date/identifier, clear next action, confirmation for consequential actions, visible filter state. | Search, status/date filters, pagination, bulk action only when the business workflow already supports it. | Arbitrary client sorting, generic row actions unrelated to the workflow. |
| **C. Academic Data** | Correct student/class/subject context, consistent number/date alignment, understandable period labels. | Search/pagination for large rosters, selection for existing attendance/grade actions, download/export where already supported. | Generic CRUD toolbar on a fixed grade or timetable matrix. |
| **D. Monitoring and Reporting** | Summary labels, units/counts, status/decision, understandable time period. | Server filters, pagination, export where the backend provides it. | Bulk mutation, generic edit/delete actions, sorting on fixed report layouts. |
| **E. Administrative** | User/document identifiers, role/category and status, authorized action presentation. | Search, role/status filters, pagination, bulk operation only if its endpoint exists. | Client-only mutation or hiding authorization rules in frontend logic. |
| **F. Specialized** | Native table semantics where data is tabular; explicit context and responsive treatment. | Sticky/frozen column, custom matrix headers, print styling, selected-row export where relevant. | Forcing dashboards, PDF printouts, timetables, or simulated state into the generic CRUD component. |

## 13. Migration Strategy

1. **Freeze the baseline:** keep a per-table inventory of role, route, query names, payload, paginator, exports, and supported actions.
2. **Prepare the shared layer:** add tokens and scoped table styles; make the empty component reusable; align pagination and status badges. Do not alter controllers.
3. **Repair compatibility adapters:** explicitly declare CRUD capabilities and preserve current routes/parameters; avoid rendering unsupported sorting, bulk, search, or actions.
4. **Pilot by table type:** choose one master list, one workflow list, and one academic/report table. Compare screenshots and verify backend contracts before wider migration.
5. **Migrate in small batches:** retain module-specific markup and actions; replace only shared presentation and interactions that have equivalent backend support.
6. **Validate each batch:** run the regression checklist below, then proceed to the next role/module. Keep PDF layouts and specialized matrices separate.
7. **Measure before optimizing:** prefer existing server pagination for large datasets. Consider virtualization only after measured row counts and a demonstrated continuous-scroll use case.

## 14. Regression Testing Checklist

### Visual and responsive

- [ ] Comfortable density is the default; compact and spacious remain readable.
- [ ] No table body/header is smaller than the approved typography tokens.
- [ ] Header, row padding, border, hover, selected, status, and action patterns are consistent.
- [ ] Mobile toolbar wraps without overlap; essential data and row actions remain reachable.
- [ ] Horizontal scroll, sticky headers, and frozen columns are tested in the actual containing layout.
- [ ] PDF/print, timetable, attendance matrix, and dashboard tables retain their specialized layout.

### Interaction and backend compatibility

- [ ] Search covers the documented scope and preserves all other active filters.
- [ ] Sorting changes the full server-backed result set and displays direction; unsupported columns have no sort control.
- [ ] Pagination displays current/total pages and total records and retains approved query parameters.
- [ ] Bulk selection scope, count, cross-page persistence, payload, CSRF, and confirmation match the endpoint contract.
- [ ] Row actions preserve route, method, identifiers, and confirmation behavior.
- [ ] Export preserves selected IDs, query filters, output type, and server preconditions.
- [ ] Authorization remains enforced by the existing backend regardless of UI visibility.
- [ ] No backend action, controller method, route, or workflow is removed by a visual migration.

### States and accessibility

- [ ] First-run empty and no-results empty are distinct.
- [ ] Loading and error states appear only for supported asynchronous behavior; failed loads are never presented as empty success.
- [ ] Keyboard users can reach search, filters, sorting, selection, pagination, and actions with visible focus.
- [ ] Table headers use scope; sort state, current page, controls, and dynamic result changes are announced appropriately.
- [ ] Status, selection, error, and sort state are not communicated by color alone.
- [ ] Touch targets and color contrast are checked at representative mobile and desktop sizes.

---

This specification is the design reference for future implementation. It intentionally defines shared presentation and contracts without making any application-code or business-logic changes.
