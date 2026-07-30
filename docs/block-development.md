# Block Development — TO Reviews

All blocks use the `lsx-tour-operator/` namespace and are registered as **block variations** via `wp.blocks.registerBlockVariation()`.

## Block Reference

### Featured

| Block Name | Variation | Registration | Description |
|---|---|---|---|
| `lsx-tour-operator/featured-review` | `core/group` | Global | Query loop for featured reviews |

### Related — Cross Type

These blocks were migrated from the parent tour-operator plugin in v2.2.

| Block Name | Post Types | Templates | className |
|---|---|---|---|
| `lsx-tour-operator/review-related-destination` | `destination` | `destination`, `country`, `region` | `lsx-review-related-destination-query-wrapper` |
| `lsx-tour-operator/review-related-accommodation` | `accommodation` | `accommodation` | `lsx-review-related-accommodation-query-wrapper` |
| `lsx-tour-operator/review-related-tour` | `tour` | `tour` | `lsx-review-related-tour-query-wrapper` |

### Post Meta

All scoped to `review` post type and template.

| Block Name | Binding Key | Element |
|---|---|---|
| `lsx-tour-operator/review-tagline` | `tagline` | `core/paragraph` with `lsx/post-meta` |
| `lsx-tour-operator/review-rating` | `rating` | `core/group` with icon + `core/paragraph` |
| `lsx-tour-operator/review-reviewer-name` | `reviewer_name` | `core/group` with icon + `core/paragraph` |
| `lsx-tour-operator/review-date-of-visit` | `date_of_visit_start`, `date_of_visit_end` | `core/group` with two `core/paragraph` |
| `lsx-tour-operator/review-no-adults` | `no_adults` | `core/paragraph` with `lsx/post-meta` |
| `lsx-tour-operator/review-no-children` | `no_children` | `core/paragraph` with `lsx/post-meta` |

### Post Connection

All scoped to `review` post type and template.

| Block Name | Connection Key | Icon |
|---|---|---|
| `lsx-tour-operator/accommodation-to-review` | `accommodation_to_review` | `accommodationIcon` |
| `lsx-tour-operator/destination-to-review` | `destination_to_review` | `destinationIcon` |
| `lsx-tour-operator/tour-to-review` | `tour_to_review` | `tourIcon` |
| `lsx-tour-operator/team-to-review` | `team_to_review` | `teamIcon` |

### Gallery

| Block Name | Source | Registration |
|---|---|---|
| `lsx-tour-operator/review-gallery` | `lsx/gallery` | `review` post type only |

## Conditional Registration

Blocks use `registerForPostTypesAndTemplates(postTypes, templates, registerFn)` from `@utils/conditional-block-registration.js` to limit insertion to relevant post type edit screens. Featured blocks are registered globally.

## Binding Sources

| Source | Use |
|---|---|
| `lsx/post-meta` | Binds `core/paragraph` content to a CMB2 meta field via `args.key` |
| `lsx/post-connection` | Binds `core/paragraph` content to a connected post via `args.key` |
| `lsx/gallery` | Binds `core/gallery` images to the post gallery meta |

## Adding a New Block

1. Create `src/blocks/{block-name}/block.json` with `"editorScript": "file:index.js"` and `"textdomain": "to-reviews"`
2. Create `src/blocks/{block-name}/index.js` with the variation registration
3. Run `npm run build`
