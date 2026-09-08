# Change log

## [Unreleased]

### Security
- Cleared all 13 Dependabot alerts. Every one was a transitive build-toolchain package reached through `@wordpress/scripts`, `stylelint` or `cssnano`, with no direct dependency to bump, so they are pinned with `overrides` in `package.json` and the constraints survive lockfile regeneration: `fast-uri ^3.1.6` (4 high, SSRF and host confusion), `serialize-javascript ^7.0.5` (high RCE, moderate CPU-exhaustion DoS), `browserslist ^4.28.7` (high), `@humanfs/node ^0.16.8` (moderate), and `sockjs > uuid ^11.1.1`. `postcss-selector-parser` (2 low) is pinned `^6.1.3` at the root for the cssnano v6 plugin set, with nested `^7.1.3` overrides for stylelint and the postcss-modules packages, so neither major line is forced across a boundary. `markdownlint-cli ^0.49.1` clears the `markdown-it` smartquotes ReDoS and the `minimatch` backtracking alerts at source rather than force-patching leaves into a 2022-era parent.

### Changed
- All version sources reconciled on `2.2.0`. The plugin header and the readme `Stable tag` read `2.2`, `LSX_TO_REVIEWS_VER` read `2.2.0`, and `package.json` was further behind still at `2.0.0`. Only `2.2.0` has ever existed as a git tag, and `10up/action-wordpress-plugin-deploy` derives the WordPress.org SVN tag from the git tag, so `Stable tag: 2.2` pointed at a tag that was never created. All four sources now read `2.2.0`, and `npm run lint:version` (`scripts/check-version-sync.mjs`) fails CI if they drift apart again.
- Raised the Node support contract to `>=24.0.0` with `npm >=11.0.0`, pinned in a new `.nvmrc` on 24.20.0, the current LTS line. The previous `>=18.0.0` was unsatisfiable, since the lockfile resolves packages requiring Node 20 and 22.
- `.coderabbit.yaml` now parses. It had a duplicated `reviews.path_filters` mapping key, which is a hard YAML error, so CodeRabbit had been silently falling back to default settings instead of this repository's own review instructions. Also removed the top-level `commands` and `linked_issues` keys, which are not in the v2 schema.

### Added
- CI workflow that builds the block assets and lints styles. The only checks were PHP syntax and CodeQL, so a broken webpack config or stylesheet could merge unnoticed. It uses GitHub's native `concurrency` with `cancel-in-progress`, keyed on `github.ref` so that fork pull requests sharing a branch name cannot cancel each other's checks.

### Removed
- The `dependencies` block. `lodash`, `minimatch` and `randomatic` were never imported by any source file; they were leftover `npm audit fix` pins, and declaring them as runtime dependencies is why Dependabot reported them under runtime scope for a plugin that ships PHP and built assets only.
- `.github/workflows/check-php-syntax-errors.yml`. It pinned `overtrue/phplint@9.8`, whose published action image is missing `symfony/stopwatch` and dies before linting anything. The new CI workflow lints with plain `php -l` across 8.2, 8.3 and 8.4 instead.
- `.github/workflows/cancel.yml`. GitHub cancels superseded runs natively, and the workflow queried the API for every workflow id on each run.
- `.stylelintrc.json`. It extended `@humanmade/stylelint-config`, which is not installed, so `npm run lint:css` aborted with a `ConfigurationError` before linting anything, and it shadowed the `stylelint.config.js` that extends `@wordpress/stylelint-config`.

## [[2.2]](https://github.com/lightspeedwp/to-reviews/releases/tag/2.2) - 2026-07-29

### Description
This release introduces comprehensive block support for the Reviews post type, including new meta blocks, block variations for connecting reviews to core Tour Operator post types, a review card pattern, and updated block-based templates for archive and single review pages.

### Added
- New `LSX_TO_Reviews_Blocks` class for centralised block and pattern registration (`classes/class-to-reviews-blocks.php`)
- Meta blocks for review fields: `date-of-visit`, `rating`, `reviewer-name`, `tagline`, `gallery`, `no-adults`, `no-children`
- Connection blocks for linking reviews to other post types: `accommodation-to-review`, `destination-to-review`, `team-to-review`, `tour-to-review`
- Related review query blocks for core post types: `accommodation-related-review`, `destination-related-review`, `tour-related-review`
- Featured review block (`featured-review`) for highlighting a single review
- Block variations for reviews registered on accommodation, destination, team, and tour post types
- Review card block pattern (`patterns/review-card.php`) for displaying testimonials in query loops
- Conditional block registration utility (`src/utils/conditional-block-registration.js`)
- Post type JSON definition (`post-types/review.json`) with full field schema
- Breadcrumbs (Yoast SEO breadcrumbs block) and a hero/cover section with post title and tagline to `templates/single-review.html`

### Updated
- `templates/single-review.html` — rebuilt with new meta blocks and connection blocks
- `templates/single-review.html` — sticky menu now supports customizable active/hover background and text colors and font size; padding adjusted across review sections and the sticky menu; breadcrumbs section styling refined for layout and readability
- `templates/single-review.html` — removed the standalone "Reviewer:" label/value block (reviewer name is still shown via post meta elsewhere in the template)
- `templates/archive-review.html` — updated to use review card pattern in query loop
- `patterns/review-card.php` — post title in the review card is now a link (`isLink: true`)
- `classes/class-to-reviews-frontend.php` — added `travel_dates()` filter to format stored date-of-visit timestamps using the site's configured date format
- `README.txt` — "Tested up to" bumped to WordPress 7.0
- Plugin version bumped to `2.2.0`

### Fixed
- Schema: replaced duplicate `itemReviewed` keys (tour + accommodation were silently overwriting each other) with a new `add_items_reviewed()` method that merges both into a single array.
- Schema: `ratingValue` is now cast to `(int)` to match Schema.org convention; `bestRating` is fixed at `5` and `worstRating` at `1`; the `reviewRating` block is now conditional on a non-empty rating value.
- Schema: review `@id` updated from `#review` to `#/schema/review/{id}` to avoid collisions on pages with multiple reviews.
- Schema: `email` removed from the nested `author` `Person` object to prevent exposure of reviewer contact data.
- Schema: `taxonomy` field mapped from `reviewSection` → `about` for `category` terms.
- Schema: description now uses `\lsx\schema\Helpers::strip_to_text()` on filtered content instead of a bare `wp_strip_all_tags()` call.
- Schema: date-of-visit range now formatted as an ISO 8601 interval (`start/end`) using `\lsx\schema\Helpers::format_iso_date()`; falls back to an `additionalProperty` `PropertyValue` when only one date is present.
- Schema: destinations typed as `TouristDestination` (was Country/State based on post parent check); tours typed as `TouristTrip`; accommodation typed as `LodgingBusiness`.
- Schema: replaced `get_the_ID()` calls with explicit `$post->ID` for consistency in meta queries.
- Schema: added `url` property to the root Review node.

### Security
- Added `ABSPATH` checks to prevent direct file access in `classes/class-to-reviews.php`, `classes/class-to-reviews-templates.php`, `includes/post-types/config-review.php`, `includes/template-tags.php`, and `patterns/review-card.php`
- Removed an unnecessary `load_plugin_textdomain` action in `classes/class-to-reviews-admin.php`
- Renamed metabox variable for consistency and clarity in `includes/metaboxes/config-review.php`
- Addressed Plugin Check and WPCS issues flagged for the plugin (LS-1952)
- Tested with WordPress 7.0
- Tested with PHP 8.0+

## [[2.1]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/2.1) - 2025-12-20

### Description
This release introduces comprehensive block editor template support, code quality improvements, and enhanced custom field configurations for better Tour Operator 2.0 compatibility.

### Added
- Block editor templates for review archive and single pages (`templates/archive-review.html` and `templates/single-review.html`)
- New `LSX_TO_Reviews_Templates` class for proper template registration and fallback handling
- Comprehensive README.md with development guidelines and setup instructions
- Enhanced documentation in gulpfile.js for development workflows

### Updated
- Post field support with improved CMB2 configurations
- Array formatting consistency across all metabox configurations
- Field titles and descriptions for better user guidance
- Plugin version to 2.1 across all files

### Fixed
- All PHP coding standards violations (PHPCS compliance)
- JavaScript coding standards violations
- Template loading logic for better theme compatibility
- Code readability and inline documentation

### Changed
- Improved class structure and organization
- Enhanced template handling with proper fallback system
- Better integration with WordPress block editor

### Security
- Tested with WordPress 6.9
- Tested with PHP 8.0+
- Code quality improvements for better security

## [[2.0.1]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/2.0.1) - 2025-05-15

### Updated
- Plugin Assets and Documentation Links.

### Removed
- Unused Template Tags calling deprecated Tour Operator Functions.

## [[2.0.0]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/2.0.0) - 2025-05-09

### Description
The following PR contains the code for the block updates and the removal of the legacy code.

### Added
- WordPress block editor support
- Tour Operator 2.0 Support.

### Updated
- Custom fields to CMB2 and its add-ons.
- WPCS warnings notices fixed.

### Removed
- Old PHP Templates, function and legacy template code.

### Security
- Tested with WordPress 6.8.1

## [[1.2.7]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.2.7) - 2023-08-09

### Security
- General testing to ensure compatibility with latest WordPress version (6.3).

## [[1.2.6]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.2.6) - 2023-04-20

### Security
- General testing to ensure compatibility with latest WordPress version (6.2).

## [[1.2.5]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.2.5) - 2022-12-23

### Security
- General testing to ensure compatibility with latest WordPress version (6.1.1).

## [[1.2.4]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.2.4) - 2022-09-12

### Security
- General testing to ensure compatibility with latest WordPress version (6.0).

## [[1.2.3]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.2.3) - 2021-01-15
### Added
- Allowing the block editor for the single reviews description area.

### Fixed
- Fixed all undefined notices to adhere to coding standards.

### Security
- General testing to ensure compatibility with latest WordPress version (5.6).

## [[1.2.2]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.2.2) - 2020-03-30

### Fixed
- Fixed PHP error `Undefined variable: connected_destination`.

### Security
- General testing to ensure compatibility with latest WordPress version (5.4).
- General testing to ensure compatibility with latest LSX Theme version (2.7).


## [[1.2.1]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.2.1) - 2019-12-19

### Added
- Enabled the sorting of the gallery field.

### Security
- Checking compatibility with LSX 2.6 release.
- General testing to ensure compatibility with latest WordPress version (5.3).

### Fixed
- If the review does not have an excerpt it will show trimmed content on the Reviews widget.


## [[1.2.0]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.2.0) - 2019-09-27

### Added
- Added in the Review Schema integrated with Yoast WordPress SEO.
- Adding the .gitattributes file to remove unnecessary files from the WordPress version.
- Enabled the sorting of the gallery field.

### Fixed
- Updating files and fixing php warning notices.


## [[1.1.1]](https://github.com/lightspeeddevelopment/to-reviews/releases/tag/1.1.1) - 2019-06-21

### Added
- No sidebars on single or archive.

### Deprecated
- API Class Removal.

### Fixed
- Travis and dependency fixes.


## [[1.1.0]]()

### Added
- Support LSX Theme 2.0 new designs.
- Added compatibility with LSX 2.0.
- Added compatibility with Tour Operator 1.1.
- New project structure.
- Updated the the way the post type registers to match the refactored TO plugin.
- Updated the registering of the metaboxes.

### Fixed
- Fixed scripts/styles loading order.
- Fixed small issues.


## [[1.0.4]]()

### Added
- Standardized the Gallery and Video fields.


## [[1.0.3]]()

### Added
- Fixed menu navigation improved.

### Fixed
- Make the addon compatible with the latest version from TO Search addon.
- API key and email grabbed from the correct settings tab.
- Added TO Search as subtab on LSX TO settings page.
- Code refactored to follow the latest Tour Operator plugin workflow.
- Small fixes on front-end fields.
- Fixed content_part filter for plugin and add-ons.


## [[1.0.2]]()

### Fixed
- Fixed all prefixes replaces (to_ > lsx_to_, TO_ > LSX_TO_).


## [[1.0.1]]()

### Fixed
- Reduced the access to server (check API key status) using transients.
- Made the API URLs dev/live dynamic using a prefix "dev-" in the API KEY.


## [[1.0.0]]()

### Fixed
- First Version
