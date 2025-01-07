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

class LSX_TO_Reviews_Frontend extends LSX_TO_Reviews {

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
		$this->set_vars();
		add_filter( 'lsx_to_custom_field_query', array( $this, 'rating' ), 5, 10 );
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
					if ( $value > 0 ) {
						$ratings_array[] = '<i class="fa fa-star"></i>';
					} else {
						$ratings_array[] = '<i class="fa fa-star-o"></i>';
					}

					$counter--;
					$value--;
				}

				$html = $before . implode( '', $ratings_array ) . $after;
			}
		}
		return $html;
	}
}

new LSX_TO_Reviews_Frontend();
