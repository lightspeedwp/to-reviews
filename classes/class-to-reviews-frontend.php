<?php
/**
 * LSX_TO_Reviews_Frontend
 *
 * @package   LSX_TO_Reviews_Frontend
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Reviews_Frontend
 * @author  LightSpeed
 */

class LSX_TO_Reviews_Frontend {

	/**
	 * Holds the $page_links array while its being built on the single review page.
	 *
	 * @var array
	 */
	public $page_links = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'lsx_to_custom_field_query', array( $this, 'rating' ), 5, 10 );
		add_filter( 'lsx_to_custom_field_query', array( $this, 'travel_dates' ), 5, 10 );
		add_filter( 'wpseo_schema_graph_pieces', array( $this, 'add_graph_pieces' ), 11, 2 );
		add_action( 'wp_head', array( $this, 'output_standalone_schema' ), 5 );
	}

	/**
	 * Filter and make the star ratings.
	 *
	 * @param string $html     The HTML to filter.
	 * @param string $meta_key The meta key.
	 * @param string $value    The meta value.
	 * @param string $before   HTML before the output.
	 * @param string $after    HTML after the output.
	 * @return string
	 */
	public function rating( $html = '', $meta_key = false, $value = false, $before = '', $after = '' ) {
		if ( get_post_type() === 'review' && 'rating' === $meta_key ) {
			$ratings_array = array();
			$counter       = 5;
			$html          = '';
			if ( 0 !== (int) $value ) {
				while ( $counter > 0 ) {
					$ratings_array[] = '<figure class="wp-block-image size-large is-resized">';
					// phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage
					$ratings_array[] = '<img src="';
					if ( (int) $value > 0 ) {
						$ratings_array[] = LSX_TO_URL . 'assets/img/rating-star-full.png';
					} else {
						$ratings_array[] = LSX_TO_URL . 'assets/img/rating-star-empty.png';
					}
					$ratings_array[] = '" alt="" style="width:20px;vertical-align:sub;">';
					$ratings_array[] = '</figure>';

					$counter --;
					$value --;
				}
				$html = $before . implode( '', $ratings_array ) . $after;
			}
		}
		return $html;
	}

	/**
	 * Filter the travel date start/end custom fields, formatting the stored
	 * timestamp using the date format configured in WordPress (Settings > General).
	 *
	 * @param string $html     The HTML to filter.
	 * @param string $meta_key The meta key.
	 * @param string $value    The meta value.
	 * @param string $before   HTML before the output.
	 * @param string $after    HTML after the output.
	 * @return string
	 */
	public function travel_dates( $html = '', $meta_key = false, $value = false, $before = '', $after = '' ) {
		if ( get_post_type() === 'review' && in_array( $meta_key, array( 'date_of_visit_start', 'date_of_visit_end' ), true ) ) {
			if ( '' !== $value && false !== $value ) {
				$formatted_date = date_i18n( get_option( 'date_format' ), (int) $value );
				$html           = $before . $formatted_date . $after;
			}
		}
		return $html;
	}

	/**
	 * Whether the Tour Operator core schema helpers (core 2.2+) are loaded.
	 *
	 * The Review schema piece depends on them, so it is only registered when
	 * they are available. Older core versions get no Review schema, rather
	 * than a fatal error.
	 *
	 * @return bool
	 */
	public function has_schema_support() {
		return class_exists( '\lsx\schema\Helpers' );
	}

	/**
	 * Adds Schema pieces to the Yoast SEO graph.
	 *
	 * @param array                                   $pieces  Graph pieces to output.
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context Object with context variables.
	 *
	 * @return array $pieces Graph pieces to output.
	 */
	public function add_graph_pieces( $pieces, $context ) {
		if ( $this->has_schema_support() ) {
			require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-review-schema.php';
			$pieces[] = new LSX_TO_Schema_Review( $context );
		}
		return $pieces;
	}

	/**
	 * Prints a standalone JSON-LD Review graph when Yoast SEO is not active.
	 *
	 * Mirrors the core Tour Operator standalone output, which only covers the
	 * core post types.
	 *
	 * @return void
	 */
	public function output_standalone_schema() {
		if ( defined( 'WPSEO_VERSION' ) || ! $this->has_schema_support() || ! is_singular( 'review' ) ) {
			return;
		}

		require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-review-schema.php';
		$piece = new LSX_TO_Schema_Review();
		$graph = array(
			'@context' => 'https://schema.org',
			'@graph'   => array( $piece->generate() ),
		);

		// JSON_HEX_TAG prevents </script> injection; JSON_HEX_AMP avoids HTML entity issues.
		$json = wp_json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP );
		if ( $json ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<script type="application/ld+json">' . "\n" . $json . "\n</script>\n";
		}
	}
}
