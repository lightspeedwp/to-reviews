# TO Reviews

The Tour Operator Reviews extension adds the `review` post type with full Gutenberg block support for displaying review data on your site.

## Requirements

- [Tour Operator Plugin](https://touroperator.solutions/) (parent plugin)
- WordPress 6.7+
- PHP 8.0+

## Installation

1. Ensure the Tour Operator Plugin is installed and activated.
2. Upload or install the `to-reviews` plugin.
3. Activate via **Plugins → Installed Plugins**.

## Post Type

**Slug:** `review`

Post meta fields:

| Field | Key | Type |
|---|---|---|
| Tagline | `tagline` | text |
| Rating | `rating` | text |
| Reviewer Name | `reviewer_name` | text |
| Date of Visit Start | `date_of_visit_start` | text |
| Date of Visit End | `date_of_visit_end` | text |
| No. Adults | `no_adults` | text |
| No. Children | `no_children` | text |

## Blocks

See [block-development.md](block-development.md) for a full block reference.

## Building

```bash
npm install
npm run build
```

## Support

[LightSpeed support form](https://lightspeedwp.agency/lsx/support/)
