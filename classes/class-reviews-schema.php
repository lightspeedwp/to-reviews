<?php
/**
 * LSX_TO_Reviews_Schema
 *
 * @package   LSX_TO_Reviews_Schema
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2018 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_Specials_Schema
 * @author  LightSpeed
 */

class LSX_TO_Reviews_Schema extends LSX_TO_Reviews {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();
		add_action( 'wp_head', array( $this, 'reviews_single_schema' ), 1499 );
	}

	/**
	 * Creates the schema for the reviews post type
	 *
	 * @since 1.0.0
	 * @return    object    A single instance of this class.
	 */
	public function reviews_single_schema() {
		if ( is_singular( 'review' ) ) {

			$rating_value = get_post_meta( get_the_ID(), 'rating', true );
			$review_author = get_post_meta( get_the_ID(), 'reviewer_name', false );
			$title_accommodation = get_the_title();
			$url_accommodation = get_the_permalink();
			$review_description = wp_strip_all_tags( get_the_content() );
			$review_thumb = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			$review_email = get_post_meta( get_the_ID(), 'reviewer_email', false );
			$tour_review = get_post_meta( get_the_ID(), 'tour_to_review', false );
			$tour_list = array();
			
			foreach ( $tour_review as $tour_id ) {
			$title_tour = get_the_title($tour_id);
			$tour_arrays =  array(
				"@type" => "trip",
				"name" => "$title_tour",
			);
			$tour_list[] = $tour_arrays;
			}
			$meta = array(
			"@context" => "https://schema.org/",
			"@type" => "Review",
			"reviewRating" => array(
			"@type" => "Rating",
			"ratingValue" => $rating_value,
			"bestRating"  => $rating_value,
			),
			"author" => array(
				"@type" => "Person",
				"name" => $review_author,
				"email" => $review_email
			),
				"reviewBody" => $review_description,
				"itemReviewed" => $tour_list,
			); 
			
			$output = wp_json_encode( $meta, JSON_UNESCAPED_SLASHES );
			?>
			<script type="application/ld+json">
				<?php echo wp_kses_post( $output ); ?>
			</script>
			<?php
		}
	}
}

new LSX_TO_Reviews_Schema();