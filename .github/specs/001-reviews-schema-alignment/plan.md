# Implementation Plan: Reviews Schema 2.0 Alignment

**Branch**: `fix/to-216-reviews-schema-alignment` | **Date**: 2026-10-09 | **Spec**: [spec.md](./spec.md)

## Summary

Rebuild the `Review` schema piece as a self-contained class on the Tour Operator core 2.2 schema helpers, as was done for TO Team (TO-171). Then close the remaining gaps in the spec: invalid ratings, linked and de-duplicated `itemReviewed`, structured `about`, safe date-of-visit handling, no `offers`, and no reviewer email. Add PHPUnit regression tests and QA evidence.

## Technical Context

**Language/Version**: PHP 7.2+; tests run on PHP 8.x with PHPUnit 10.5 (already in `require-dev`)
**Primary Dependencies**: Tour Operator core 2.2 (`lsx\schema\Helpers`); Yoast SEO graph API (optional)
**Testing**: PHPUnit with WordPress function stubs and in-memory fixtures (same harness as TO Team); manual QA on the local Studio site
**Constraints**: The aggregate rating that core builds for tours, accommodation and destinations is untouched.

## Research and Key Decisions

### R1 – Legacy base removed (same as TO-171)

Core `f62d3b5dd` deletes `LSX_TO_Schema_Graph_Piece` and `lsx\legacy\Schema_Utils`. Confirmed on the local site: `/review/rachel-nigel/` emits **no Review node**.

**Decision**: `LSX_TO_Schema_Review` becomes a plain class with an optional Yoast context, `is_needed()` and `generate()`, depending only on `lsx\schema\Helpers`. It is registered only when that class exists, and prints standalone JSON-LD when Yoast is inactive.

### R2 – Relationship storage

On the reference review, `tour_to_review`, `accommodation_to_review`, `destination_to_review` and `special_to_review` each hold a single serialised array (`[["13"]]`). `Helpers::get_meta_array()` handles both this and multi-row storage, so all relationship reads use it.

### R3 – Author identity (FR-003)

`author` is `{ "@type": "Person", "@id": …, "name": reviewer_name }`. The `@id` keeps the legacy scheme, `{site_url}#/schema/person/{wp_hash( name . email )}`. `wp_hash()` is an HMAC keyed with the site's secret salts, so the email cannot be recovered, and the same reviewer keeps a stable ID across reviews. `site_url` comes from the Yoast context, falling back to `home_url( '/' )`. The email itself is never placed in the output.

### R4 – Field rules

| Property | Source | Rule |
| --- | --- | --- |
| `headline` | title | Plain text (texturised entities decoded) |
| `reviewBody` | content | `the_content` filtered, plain text |
| `datePublished` / `dateModified` | post dates (GMT) | W3C format via `mysql2date( DATE_W3C, … )` |
| `commentCount` | approved comments | Integer |
| `reviewRating` | `rating` | Only when the rating is an integer 1–5; `bestRating` 5, `worstRating` 1 |
| `itemReviewed` | `tour_to_review`, `accommodation_to_review` | `{ @type, name, url }` as `TouristTrip` / `LodgingBusiness`; published only; de-duplicated; a single object when there is one item |
| `temporalCoverage` | visit start/end | `YYYY-MM-DD/YYYY-MM-DD` only when both dates are valid and end ≥ start; otherwise a "Date of Visit" `additionalProperty` |
| `about` | `category` terms | `Thing` nodes; "Uncategorized" excluded; a single object when there is one |
| `keywords` | `post_tag` terms | Joined with `", "` |
| `spatialCoverage` | `destination_to_review` | `TouristDestination` `{ name, url }`; published only; de-duplicated |
| `publisher` | Yoast `site_represents_reference` | When present |
| `offers` | — | Not emitted (FR-010) |

The data passes through the `lsx_to_schema_review_data` filter.

### R5 – Tests

Reuse the TO Team harness (`phpunit.xml.dist` plus `tests/php/bootstrap.php`), extended with stubs for `mysql2date`, `get_comment_count`, `wp_hash` and `home_url`. Add a `composer test` script.

## Files Touched

```text
classes/class-to-review-schema.php        # rewritten piece
classes/class-to-reviews-frontend.php     # guard + standalone output
tests/php/bootstrap.php, tests/php/ReviewSchemaTest.php, phpunit.xml.dist
composer.json                             # test script
changelog.md
.github/specs/001-reviews-schema-alignment/quickstart.md
```
