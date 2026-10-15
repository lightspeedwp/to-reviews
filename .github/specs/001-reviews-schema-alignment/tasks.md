# Tasks: Reviews Schema 2.0 Alignment

**Input**: [spec.md](./spec.md), [plan.md](./plan.md)

## Phase 1 – Setup

- [x] T001 Add `phpunit.xml.dist`, `tests/php/bootstrap.php` and a `composer test` script

## Phase 2 – Foundational

- [x] T002 Rewrite `classes/class-to-review-schema.php` as a self-contained piece with baseline-equivalent output (FR-001, FR-002, FR-011–FR-013)
- [x] T003 Register only when `lsx\schema\Helpers` exists, and print standalone JSON-LD when Yoast is inactive (FR-014)

## Phase 3 – User Story 1: valid, privacy-safe Review (P1)

- [x] T004 [US1] `author` without email exposure (FR-003)
- [x] T005 [US1] `reviewRating` only for ratings 1–5 (FR-004, FR-005)
- [x] T006 [US1] Date of visit: interval only when ordered, otherwise a "Date of Visit" property (FR-008)

## Phase 4 – User Story 2: identifiable reviewed items (P2)

- [x] T007 [US2] `itemReviewed` with URL, published only, de-duplicated (FR-006, FR-007); `spatialCoverage` on the same rules (FR-011)

## Phase 5 – User Story 3: approved properties only (P2)

- [x] T008 [US3] `about` as `Thing` nodes, with no `reviewSection` (FR-009)
- [x] T009 [US3] No `offers` (FR-010)

## Phase 6 – User Story 5: tests and QA (P3)

- [x] T010 [US5] `tests/php/ReviewSchemaTest.php` (FR-015)
- [x] T011 [US5] Before/after capture and QA steps in `quickstart.md`, including the email search (FR-016) — local capture done; external validator, no-Yoast and core 2.1 runs pending (see quickstart.md)

## Phase 7 – Polish

- [x] T012 PHPCS on changed files — no violations on changed lines (note: the repo phpcs.xml.dist excludes `wp-content/*`, so run it outside a WordPress install or it scans 0 files)
- [x] T013 `changelog.md` Unreleased entry
