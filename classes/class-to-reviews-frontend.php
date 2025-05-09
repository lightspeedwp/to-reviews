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
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'lsx_to_custom_field_query', array( $this, 'rating' ), 5, 10 );
		add_filter( 'wpseo_schema_graph_pieces', array( $this, 'add_graph_pieces' ), 11, 2 );
	}

	/**
	 * Filter and make the star ratings
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
	 * Adds Schema pieces to our output.
	 *
	 * @param array                 $pieces  Graph pieces to output.
	 * @param \WPSEO_Schema_Context $context Object with context variables.
	 *
	 * @return array $pieces Graph pieces to output.
	 */
	public function add_graph_pieces( $pieces, $context ) {
		if ( class_exists( 'LSX_TO_Schema_Graph_Piece' ) ) {
			require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-review-schema.php';
			$pieces[] = new LSX_TO_Schema_Review( $context );
		}
		return $pieces;
	}
}
