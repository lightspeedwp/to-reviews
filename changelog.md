# Change log

## [2.2] - 2026-07-29

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
