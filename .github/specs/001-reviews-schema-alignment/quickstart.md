# QA: Reviews Schema 2.0 Alignment

How to verify TO-216 and the evidence captured during implementation.

## Automated checks

```bash
composer install
composer test
# or, with a tour-operator checkout elsewhere:
TO_CORE_PATH=/path/to/tour-operator vendor/bin/phpunit -c phpunit.xml.dist
```

Expected result: `OK (17 tests)`. Twelve of the tests fail against the pre-fix piece (`a241e2d`).

## Manual QA

1. Use a site with Tour Operator core 2.2+, Yoast SEO and this branch of TO Reviews active.
2. Populate a Review with: reviewer name, reviewer email, a rating of 1–5, content, visit start and end dates, two categories, tags, one linked tour, one linked accommodation, a linked destination and a linked Special.
3. View the page source and copy the `yoast-schema-graph` JSON-LD.
4. Run the page URL, or the copied JSON-LD for a local site, through the [Schema.org Validator](https://validator.schema.org/) and the [Google Rich Results Test](https://search.google.com/test/rich-results).
5. Check that:
   - Exactly one `Review` node is present, with `@id` ending `#/schema/review/{ID}`.
   - `reviewRating` is `{ ratingValue: N, bestRating: 5, worstRating: 1 }`.
   - `itemReviewed` has one `TouristTrip` and one `LodgingBusiness`, each with a `url`.
   - `about` lists the categories as `Thing` nodes. There is no `reviewSection` and no `offers`.
   - `spatialCoverage` lists the destinations as `TouristDestination`.
   - The validators report 0 errors.
6. **Privacy**: search the full page source (Ctrl/Cmd+F) for the reviewer email and its local part. Expect **0 matches**.
7. **Edge cases**:
   - Set the rating to `0`: `reviewRating` disappears.
   - Set the visit end before the start: no `temporalCoverage`; a "Date of Visit" property appears instead.
8. **Without Yoast**: deactivate Yoast SEO and confirm a standalone `<script type="application/ld+json">` Review graph is printed in `<head>`.
9. **Core 2.1**: activate with Tour Operator core 2.1. The page renders with no PHP errors and no Review node.

## Evidence (local Studio site, 2026-10-09)

Environment: `https://beta.local`, Tour Operator 2.2.0 (`fix/itinerary-schema-fix-1143`, which has the legacy schema classes removed), Yoast SEO 27.6, PHP 8.4. Reference review: `/review/rachel-nigel/` (ID 305).

**Before** (`update-2.2` baseline): **no Review node** in the Yoast graph.

**After** (this branch), with `reviewBody` omitted for length:

```json
{
    "@type": "Review",
    "@id": "https://beta.local/review/rachel-nigel/#/schema/review/305",
    "url": "https://beta.local/review/rachel-nigel/",
    "headline": "Rachel & Nigel",
    "datePublished": "2026-06-15T14:29:52+00:00",
    "dateModified": "2026-07-30T08:48:17+00:00",
    "commentCount": 0,
    "mainEntityOfPage": { "@id": "https://beta.local/review/rachel-nigel/" },
    "author": { "@type": "Person", "@id": "https://beta.local/#/schema/person/7a1f2bb8c6eabb1c9663961c486d3284", "name": "Rachel & Nigel" },
    "reviewRating": { "@type": "Rating", "ratingValue": 3, "bestRating": 5, "worstRating": 1 },
    "itemReviewed": [
        { "@type": "TouristTrip", "name": "Phuket – Krabi – Thailand – 14 Nights", "url": "https://beta.local/tour/phuket-krabi-thailand-14-nights/" },
        { "@type": "LodgingBusiness", "name": "Koh Yao Yai Village", "url": "https://beta.local/accommodation/koh-yao-yai-village/" }
    ],
    "publisher": { "@id": "https://beta.local/#organization" },
    "additionalProperty": [ { "@type": "PropertyValue", "name": "Date of Visit", "value": "2025-09-05 – 2025-06-19" } ],
    "image": { "@id": "https://beta.local/review/rachel-nigel/#primaryimage" },
    "spatialCoverage": [
        { "@type": "TouristDestination", "name": "Ao Nang", "url": "https://beta.local/destination/thailand/ao-nang/" },
        { "@type": "TouristDestination", "name": "Thailand", "url": "https://beta.local/destination/thailand/" }
    ]
}
```

- The reference review has its visit end (2025-06-19) **before** its start (2025-09-05). The schema correctly falls back to the "Date of Visit" property, and the content should be corrected.
- The review is linked to a Special (ID 210). No `offers` is emitted.
- Email search: 0 occurrences of the reviewer email in the page HTML. No PHP errors from TO Reviews.

**Still to do before sign-off**: steps 4 (external validators), 8 (no Yoast) and 9 (core 2.1) on `tour-operator.lightspeedwp.dev`.
