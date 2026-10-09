# Feature Specification: Reviews Schema 2.0 Alignment

**Feature Branch**: `fix/to-216-reviews-schema-alignment` (based on `update-2.2`)

**Created**: 2026-10-09

**Status**: Draft

**Tracking**: [TO-216](https://linear.app/lightspeedwp/issue/TO-216/align-reviews-schema-with-approved-schema-20-mappings) · [lightspeedwp/to-reviews#220](https://github.com/lightspeedwp/to-reviews/issues/220)

**Approved mapping**: [Tour Operator – Structured Data › Reviews (audit)](https://docs.google.com/document/d/1UVfrdgR1o2A-5YuyMbY6Bxevy3EXYfOkj-p6dwXFxMA/edit?tab=t.l9k66a4nq8kl)

**Input**: "Align the Reviews extension schema output with the approved Tour Operator Schema 2.0 mappings and fix the current Review schema defects, so Review posts emit a valid `Review` node with no privacy leaks, invalid properties or duplicate-key overwrites."

## Context: Baseline on `update-2.2`

The earlier `feature/1143-schema-2.2-reviews-alignment` work has already been merged into `update-2.2`. The audit below compares each TO-216 task with the current baseline.

| TO-216 task | Baseline status |
| --- | --- |
| `@type` stays `Review` | ✅ Done |
| `@id` is unique (`#/schema/review/{post_id}`) | ✅ Done |
| Duplicate reviewer-name assignment removed | ✅ Done |
| `reviewer_name` → `author.name` | ✅ Done |
| Reviewer email not in public JSON-LD | ✅ Done. The email is used only inside a one-way keyed hash for `author.@id` (see FR-003) |
| `headline`, `datePublished`, `dateModified`, `commentCount`, `mainEntityOfPage`, `reviewBody` | ✅ Done |
| `reviewRating` with actual `ratingValue`, `bestRating` 5 and `worstRating` 1 | ✅ Done. Gap: a stored rating of `0` produces an invalid rating (see FR-005) |
| Tours and accommodation merged into one `itemReviewed` (`TouristTrip` / `LodgingBusiness`) | ✅ Done. Gap: no URL and no de-duplication (see FR-006, FR-007) |
| Date of visit as an ISO 8601 interval or a "Date of Visit" property | ✅ Done |
| Invalid `reviewSection` replaced with `about` | ⚠️ Partly done. `about` is a comma-joined text string, but schema.org expects `Thing` (see FR-009) |
| Destinations → `spatialCoverage` as `TouristDestination` | ✅ Done |
| `url` added | ✅ Done |
| `publisher` from the site organisation | ✅ Done |
| Tests / manual validation for one populated Review | ❌ Not done |

Other gaps found during the audit:

- **Inherited `offers` output**: The Review node still calls the shared core "offers" builder. When a Special is linked to a Review, that builder emits an Offer with a mis-cased `PriceSpecification` plain string, which is the defect TO-217 removes from Specials. `offers` is not part of the approved Review mapping.
- **Core version dependency**: The schema piece relies on shared helpers that ship only with Tour Operator core 2.2+. The loader checks only for the older base class, so running this extension with core 2.1 would cause a fatal error on Review pages.
- **Legacy base classes removed from core** *(added 2026-10-09)*: Core commit `f62d3b5dd` deletes the legacy schema base class and utilities that the Review piece extends. With that core build, Review pages currently emit **no Review node at all**, which was confirmed on the local site. The piece must stop depending on those classes (see FR-014 and [plan.md](./plan.md)).
- **Relationship storage** *(added 2026-10-09)*: Related tours, accommodation and destinations are stored as one serialised array per key. The legacy code read them as separate meta rows, so it was handed nested arrays rather than post IDs.

## User Scenarios & Testing *(mandatory)*

### User Story 1 – Search engines read a valid, privacy-safe Review (Priority: P1)

A guest submits a review of a safari, including their name, email, a 4-star rating and their travel dates. The operator publishes it, linked to a tour and a lodge. Search engines and AI assistants then find a single valid `Review` node that credits the guest by name, describes what was reviewed, and never exposes the guest's email address.

**Why this priority**: Exposing guest emails is a privacy breach, and an incorrect rating scale misrepresents customer sentiment. Both are critical defects named in the audit.

**Independent Test**: Load one fully populated Review on the QA site. Validate it with the Schema.org Validator and the Google Rich Results Test, then search the page source for the reviewer's email.

**Acceptance Scenarios**:

1. **Given** a Review with reviewer name, reviewer email, rating 4, content, visit dates, one linked tour, one linked accommodation and one linked destination, **When** the page is validated, **Then** exactly one `Review` node is reported with `@type` `Review`, a unique `@id`, `url`, `author` (Person with name), `headline`, `reviewBody`, `datePublished`, `dateModified`, `reviewRating` (ratingValue 4, bestRating 5, worstRating 1), `itemReviewed` with one `TouristTrip` and one `LodgingBusiness`, `spatialCoverage` with one `TouristDestination`, and `publisher`.
2. **Given** the same page, **When** the full HTML source is searched for the reviewer's email, in plain text or URL-encoded form, **Then** there are zero matches.
3. **Given** the same page, **When** the JSON-LD is inspected, **Then** no `reviewSection` property is present.

---

### User Story 2 – Reviewed products are identifiable and not duplicated (Priority: P2)

An AI trip planner aggregating sentiment needs to know exactly which tours and lodges a review is about, and to follow links to them.

**Why this priority**: `itemReviewed` entries that carry only a name are weak signals. Duplicates distort counts.

**Independent Test**: Link the same accommodation to a Review twice and add one tour. Validate the page and confirm `itemReviewed` has exactly two entries, each with a URL.

**Acceptance Scenarios**:

1. **Given** a Review linked to tour A once and accommodation B twice, **When** the page is validated, **Then** `itemReviewed` contains exactly two entries: A (`TouristTrip`) and B (`LodgingBusiness`).
2. **Given** a linked tour or accommodation, **When** its `itemReviewed` entry is inspected, **Then** it includes the product's public URL.
3. **Given** a linked product that is unpublished, trashed or deleted, **When** the page is validated, **Then** that product is not listed in `itemReviewed`.

---

### User Story 3 – Only approved, valid properties are emitted (Priority: P2)

A structured data reviewer checks that the Review node contains only properties from the approved mapping and that each property uses the type schema.org expects.

**Why this priority**: Properties outside the approved mapping, or with invalid values, create validator warnings and can carry defects in from other modules.

**Independent Test**: Link a Special and a category to a Review, validate the page, and compare its properties with the approved mapping.

**Acceptance Scenarios**:

1. **Given** a Review with one or more categories, **When** the page is validated, **Then** `about` contains one structured entry per category with no validator type warnings, and excludes the default "Uncategorised" category.
2. **Given** a Review linked to a Special, **When** the page is validated, **Then** the Review node contains no mis-cased `PriceSpecification` key, and handles `offers` as agreed in FR-010.

---

### User Story 4 – Sites never break because of Reviews schema (Priority: P2)

A site owner updates the Reviews extension before updating Tour Operator core. Their Review pages must keep rendering.

**Why this priority**: A front-end fatal error is far more damaging than missing structured data.

**Independent Test**: Activate the extension with Tour Operator core 2.1 and Yoast SEO, then load a Review page.

**Acceptance Scenarios**:

1. **Given** Tour Operator core older than 2.2, **When** a Review page loads, **Then** the page renders, no PHP fatal or warning is raised, and no Review node is emitted.

---

### User Story 5 – QA evidence exists for sign-off (Priority: P3)

A reviewer approving the PR needs repeatable evidence that the output matches the approved mapping.

**Why this priority**: The acceptance criteria require validation "against the agreed structured data QA approach".

**Independent Test**: Follow the documented QA steps and compare the captured JSON-LD with the expected output.

**Acceptance Scenarios**:

1. **Given** the documented QA procedure, **When** a reviewer follows it, **Then** they can reproduce a zero-error validator run for the reference Review.
2. **Given** the automated checks, **When** they run, **Then** at least one check fails if the reviewer email appears in output, `bestRating` stops being 5, `reviewSection` reappears, or tour/accommodation items overwrite each other.

### Edge Cases

- **No rating, or a stored rating of `0`**: omit `reviewRating` entirely. A rating of 0 on a 1–5 scale is invalid.
- **Rating outside 1–5** from imported data: omit `reviewRating` rather than emit an out-of-range value.
- **Reviewer name empty**: omit `author.name`. If no identifying author data remains, omit `author`. The email must still never be emitted.
- **Only one visit date**, or an invalid date: emit the "Date of Visit" property with the valid date only, or omit it. Never emit a malformed interval.
- **Visit end before start**, as on the local reference review: do not emit a reversed `temporalCoverage` interval. Fall back to the "Date of Visit" property with both dates as entered, and leave the correction to content QA.
- **No linked tours or accommodation**: omit `itemReviewed`. The Review stays valid schema.org even though it is not eligible for Google review rich results. Recorded as an assumption.
- **Exactly one reviewed item**: `itemReviewed` is a single object, not a one-item list.
- **Only the default category**: omit `about`.
- **Yoast SEO inactive, or no site organisation configured**: omit `publisher`. No errors.
- **Review in draft or preview**: no Review node is emitted for non-public posts.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: Each public Review page MUST emit exactly one node with `@type` `Review` and an `@id` unique to that Review.
- **FR-002**: The Review MUST include `url`, `headline`, `reviewBody` (filtered, markup-free), `datePublished`, `dateModified`, `commentCount` and `mainEntityOfPage`.
- **FR-003**: `author` MUST be a Person carrying the reviewer name. The reviewer email MUST NOT appear anywhere in the page output in plain text, encoded or reversible form. A one-way keyed hash used only to build a stable `author.@id` is acceptable.
- **FR-004**: `reviewRating` MUST be a Rating with `ratingValue` set to the stored rating, `bestRating` 5 and `worstRating` 1.
- **FR-005**: `reviewRating` MUST be omitted when the stored rating is empty, `0` or outside the 1–5 range.
- **FR-006**: Linked tours and accommodation MUST be merged into one `itemReviewed` value, typed `TouristTrip` and `LodgingBusiness` respectively. Neither list may overwrite the other, duplicate links MUST be removed, and only published products may be included.
- **FR-007**: Each `itemReviewed` entry MUST include the product's public URL, in addition to its name and type.
- **FR-008**: Date of visit MUST be emitted as an ISO 8601 `temporalCoverage` interval when both dates are valid and the end is not before the start. Otherwise it MUST be a "Date of Visit" `additionalProperty` with the valid dates as entered, or be omitted.
- **FR-009**: `reviewSection` MUST NOT be emitted. Categories MUST map to `about` as one structured `Thing` entry per category, excluding the default "Uncategorised" category.
- **FR-010**: The Review node MUST NOT emit `offers` from linked Specials. *(Default chosen: `offers` is not in the approved Review mapping and currently carries the TO-217 price-specification defect. Confirm in `/speckit-clarify`.)*
- **FR-011**: Linked destinations MUST map to `spatialCoverage` as `TouristDestination` entries.
- **FR-012**: `publisher` MUST reference the site organisation whenever one is configured.
- **FR-013**: The image MUST continue to be emitted through the shared schema image utility.
- **FR-014**: The Reviews schema piece MUST only register when the Tour Operator core schema helpers it depends on are available. Otherwise it MUST register nothing and raise no errors. It MUST NOT depend on the legacy core schema base classes, and it MUST emit the Review node both with and without Yoast SEO active.
- **FR-015**: The plugin MUST include at least one automated regression check covering FR-003, FR-004/FR-005, FR-006 and FR-009.
- **FR-016**: The PR MUST include documented manual QA evidence for one fully populated Review: validator results, captured JSON-LD and the email search result.
- **FR-017**: Aggregate rating output that core consumes for tours, accommodation and destinations MUST NOT change as a result of this work.

### Key Entities

- **Review (post)**: A guest testimonial. Attributes: reviewer name, reviewer email (private), rating 0–5, content, visit start/end dates, categories, tags. Relationships: linked tours, accommodation, destinations and specials.
- **Review (schema node)**: The structured-data representation of a Review post.
- **Reviewed item**: A tour (`TouristTrip`) or accommodation (`LodgingBusiness`) that the review is about, identified by name, type and URL.
- **Site organisation**: The Organization node supplied by Yoast SEO and referenced as `publisher`.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: The reference Review on the QA site returns **0 errors** in the Schema.org Validator and the Google Rich Results Test, with no warnings for any property this spec covers.
- **SC-002**: **0** occurrences of any reviewer email address in the rendered HTML of every Review page tested.
- **SC-003**: **100%** of the rows in the approved Reviews mapping are present and correctly typed in the captured JSON-LD for the reference Review.
- **SC-004**: A Review linked to N distinct published products lists exactly N `itemReviewed` entries, for every N from 0 to 10 tested.
- **SC-005**: **0** PHP fatal errors, warnings or notices on Review pages across core 2.1 and 2.2, with Yoast SEO active and inactive.
- **SC-006**: The automated regression check fails when any one of the FR-015 defects is reintroduced, and passes on the delivered code.

## Assumptions

- **Base branch**: Work branches from `update-2.2` and the PR targets `update-2.2`, as TO-216's delivery decision specifies. `update-2.2` is currently fully contained in `develop`.
- **Mapping authority**: The "Reviews (audit)" tab is the approved mapping. The older "Schema (old reference)" rows (`numAdults`, `numChildren`, date of visit as `datePublished`) are superseded and out of scope.
- **QA approach**: Schema.org Validator plus the Google Rich Results Test, run against a fully populated Review on `tour-operator.lightspeedwp.dev`.
- **Author `@id` hash**: The existing core helper builds `author.@id` from a site-salted one-way hash of name and email. This is treated as compliant with FR-003 because the email cannot be recovered from it.
- **Reviews with no linked product** remain valid schema.org output. Google rich-result eligibility for them is not a goal of this ticket.
- **Date-order errors** are content problems. The dates are reported as entered in the "Date of Visit" property rather than as a reversed interval.
- **Cross-node `@id` linking** to core `TouristTrip`/`LodgingBusiness` nodes needs core changes and is out of scope. URLs (FR-007) are the agreed linking mechanism.
- **Core defects noted, not fixed here**: Core's offered/reviewed item helper discards its own de-duplication result, and core's Special offer builder emits a mis-cased `PriceSpecification` string. FR-006 and FR-010 can be met within this extension. Matching core fixes should be raised separately against `tour-operator`.

### Out of Scope

- Tour, Accommodation and Destination schema in core, including the `AggregateRating` that core builds from reviews.
- Specials schema (TO-217) and Team schema (TO-171).
- Review submission forms, moderation and the storage of reviewer emails.
