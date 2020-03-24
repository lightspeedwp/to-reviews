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

		add_action( 'wp_head', array( $this, 'change_single_review_layout' ), 20, 1 );

		add_filter( 'lsx_to_entry_class', array( $this, 'entry_class' ) );
		add_action( 'init', array( $this, 'init' ) );

		if ( ! class_exists( 'LSX_TO_Template_Redirects' ) ) {
			require_once( LSX_TO_REVIEWS_PATH . 'classes/class-template-redirects.php' );
		}

		$this->redirects = new LSX_TO_Template_Redirects( LSX_TO_REVIEWS_PATH, array_keys( $this->post_types ) );

		add_action( 'lsx_to_review_content', array( $this->redirects, 'content_part' ), 10 , 2 );

		add_filter( 'lsx_to_page_navigation', array( $this, 'page_links' ) );

		add_action( 'lsx_entry_top',      array( $this, 'archive_entry_top' ), 15 );
		add_action( 'lsx_entry_bottom',   array( $this, 'archive_entry_bottom' ) );
		add_action( 'lsx_content_bottom', array( $this, 'single_content_bottom' ) );
		add_action( 'lsx_to_fast_facts', array( $this, 'single_fast_facts' ) );
	}

	/**
	 * Change single review layout.
	 */
	public function change_single_review_layout() {
		global $lsx_to_archive;

		if ( is_singular( 'review' ) && 1 !== $lsx_to_archive ) {
			remove_action( 'lsx_entry_bottom', 'lsx_to_single_entry_bottom' );
			add_action( 'lsx_entry_top', array( $this, 'lsx_to_single_entry_bottom' ) );
		}
	}

	/**
	 * Change single review layout.
	 */
	public function lsx_to_single_entry_bottom() {
		if ( is_singular( 'review' ) ) { ?>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<figure class="lsx-to-review-thumb">
					<?php lsx_thumbnail( 'lsx-thumbnail-square' ); ?>
				</figure>

				<?php $reviewer_name = get_post_meta( get_the_ID(), 'reviewer_name', true ); ?>
				<h3 class="lsx-to-summary-title text-center"><?php echo esc_html( $reviewer_name ); ?></h3>
			</div>
		<?php }
	}

	/**
	 * Runs on init after all files have been parsed.
	 */
	public function init() {
		add_filter( 'lsx_to_custom_field_query',array( $this, 'rating' ),5,10 );
	}

	/**
	 * A filter to set the content area to a small column on single
	 */
	public function entry_class( $classes ) {
		global $lsx_to_archive;

		if ( 1 !== $lsx_to_archive ) {
			$lsx_to_archive = false;
		}

		if ( is_main_query() && is_singular( 'review' ) && false === $lsx_to_archive ) {
			//if ( lsx_to_has_enquiry_contact() ) {
				$classes[] = 'col-xs-12 col-sm-8 col-md-9';
			//} else {
				//$classes[] = 'col-sm-12';
			//}
		}

		return $classes;
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

	/**
	 * Adds our navigation links to the review single post
	 *
	 * @param $page_links array
	 * @return $page_links array
	 */
	public function page_links( $page_links ) {
		if ( is_singular( 'review' ) ) {
			$this->page_links = $page_links;

			$this->get_related_accommodation_link();
			$this->get_related_tours_link();
			$this->get_related_destinations_link();
			$this->get_gallery_link();
			$this->get_videos_link();
			$this->get_related_posts_link();

			$page_links = $this->page_links;
		}

		return $page_links;
	}

	/**
	 * Tests for the Related Accommodation and returns a link for the section
	 */
	public function get_related_accommodation_link() {
		$connected_accommodation = get_post_meta( get_the_ID(), 'accommodation_to_review', false );

		if ( post_type_exists( 'accommodation' ) && is_array( $connected_accommodation ) && ! empty( $connected_accommodation ) ) {
			$connected_accommodation = new \WP_Query( array(
				'post_type' => 'accommodation',
				'post__in' => $connected_accommodation,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_accommodation = $connected_accommodation->posts;

			if ( is_array( $connected_accommodation ) && ! empty( $connected_accommodation ) ) {
				$this->page_links['accommodation'] = esc_html__( 'Accommodation', 'to-reviews' );
			}
		}
	}

	/**
	 * Tests for the Related Tours and returns a link for the section
	 */
	public function get_related_tours_link() {
		$connected_tours = get_post_meta( get_the_ID(), 'tour_to_review', false );

		if ( post_type_exists( 'tour' ) && is_array( $connected_tours ) && ! empty( $connected_tours ) ) {
			$connected_tours = new \WP_Query( array(
				'post_type' => 'tour',
				'post__in' => $connected_tours,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_tours = $connected_tours->posts;

			if ( is_array( $connected_tours ) && ! empty( $connected_tours ) ) {
				$this->page_links['tours'] = esc_html__( 'Tours', 'to-reviews' );
			}
		}
	}

	/**
	 * Tests for the Related Destinations and returns a link for the section
	 */
	public function get_related_destinations_link() {
		$connected_destination  = '';
		$connected_destinations = get_post_meta( get_the_ID(), 'destination_to_review', false );

		if ( post_type_exists( 'destination' ) && is_array( $connected_destinations ) && ! empty( $connected_destinations ) ) {
			$connected_destination = new \WP_Query( array(
				'post_type' => 'destination',
				'post__in' => $connected_destination,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_destination = $connected_destination->posts;

			if ( is_array( $connected_destination ) && ! empty( $connected_destination ) ) {
				$this->page_links['destinations'] = esc_html__( 'Destinations', 'to-reviews' );
			}
		}
	}

	/**
	 * Tests for the Gallery and returns a link for the section
	 */
	public function get_gallery_link() {
		$gallery_ids = get_post_meta( get_the_ID(), 'gallery', false );
		$envira_gallery = get_post_meta( get_the_ID(), 'envira_gallery', true );

		if ( ( ! empty( $gallery_ids ) && is_array( $gallery_ids ) ) || ( function_exists( 'envira_gallery' ) && ! empty( $envira_gallery ) && false === lsx_to_enable_envira_banner() ) ) {
			if ( function_exists( 'envira_gallery' ) && ! empty( $envira_gallery ) && false === lsx_to_enable_envira_banner() ) {
				// Envira Gallery
				$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-reviews' );
				return;
			} else {
				if ( function_exists( 'envira_dynamic' ) ) {
					// Envira Gallery - Dynamic
					$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-reviews' );
					return;
				} else {
					// WordPress Gallery
					$this->page_links['gallery'] = esc_html__( 'Gallery', 'to-reviews' );
					return;
				}
			}
		}
	}

	/**
	 * Tests for the Videos and returns a link for the section
	 */
	public function get_videos_link() {
		$videos_id = false;

		if ( class_exists( 'Envira_Videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'envira_video', true );
		}

		if ( empty( $videos_id ) && function_exists( 'lsx_to_videos' ) ) {
			$videos_id = get_post_meta( get_the_ID(), 'videos', true );
		}

		if ( ! empty( $videos_id ) ) {
			$this->page_links['videos'] = esc_html__( 'Videos', 'to-reviews' );
		}
	}

	/**
	 * Tests for the Related Posts and returns a link for the section
	 */
	public function get_related_posts_link() {
		$connected_posts = get_post_meta( get_the_ID(), 'post_to_review', false );

		if ( is_array( $connected_posts ) && ! empty( $connected_posts ) ) {
			$connected_posts = new \WP_Query( array(
				'post_type' => 'post',
				'post__in' => $connected_posts,
				'post_status' => 'publish',
				'nopagin' => true,
				'posts_per_page' => '-1',
				'fields' => 'ids',
			) );

			$connected_posts = $connected_posts->posts;

			if ( is_array( $connected_posts ) && ! empty( $connected_posts ) ) {
				$this->page_links['posts'] = esc_html__( 'Posts', 'to-reviews' );
			}
		}
	}

	/**
	 * Adds the template tags to the top of the archive review
	 */
	public function archive_entry_top() {
		global $lsx_to_archive;

		if ( 'review' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) {
			if ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_metadata'] ) ) { ?>
				<div class="lsx-to-archive-meta-data lsx-to-archive-meta-data-grid-mode">
					<?php
						$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

						lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'to-reviews' ) . ':</span> ', '</span>' );
						lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'to-reviews' ) . ':</span> ', '</span>' );
						lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Destinations', 'to-reviews' ) . ':</span> ', '</span>' );
					?>
				</div>
			<?php } ?>
		<?php }
	}

	/**
	 * Adds the template tags to the bottom of the archive review
	 */
	public function archive_entry_bottom() {
		global $lsx_to_archive;

		if ( 'review' === get_post_type() && ( is_archive() || $lsx_to_archive ) ) { ?>
				</div>

				<?php if ( is_search() || empty( tour_operator()->options[ get_post_type() ]['disable_entry_metadata'] ) ) { ?>
					<div class="lsx-to-archive-meta-data lsx-to-archive-meta-data-list-mode">
						<?php
							$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

							lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . __( 'Accommodation', 'to-reviews' ) . ':</span> ', '</span>' );
							lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . __( 'Tours', 'to-reviews' ) . ':</span> ', '</span>' );
							lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . __( 'Destinations', 'to-reviews' ) . ':</span> ', '</span>' );
						?>
					</div>
				<?php } ?>
			</div>

			<?php $has_single = ! lsx_to_is_single_disabled(); ?>

			<?php if ( $has_single && 'grid' === tour_operator()->archive_layout ) : ?>
				<a href="<?php the_permalink(); ?>" class="moretag"><?php esc_html_e( 'View more', 'to-reviews' ); ?></a>
			<?php endif; ?>
		<?php }
	}

	/**
	 * Adds the template tags fast facts
	 */
	public function single_fast_facts() {
		if ( is_singular( 'review' ) ) { ?>
			<section id="fast-facts">
				<div class="lsx-to-single-meta-data">
					<?php
						$meta_class = 'lsx-to-meta-data lsx-to-meta-data-';

						lsx_to_review_rating( '<span class="' . $meta_class . 'rating"><span class="lsx-to-meta-data-key">' . esc_html__( 'Rating', 'to-reviews' ) . ':</span> ', '</span>' );
						lsx_to_review_dates( '<span class="' . $meta_class . 'travel-dates"><span class="lsx-to-meta-data-key">' . esc_html__( 'Date Travelled', 'to-reviews' ) . ':</span> ', '</span>' );
						lsx_to_connected_accommodation( '<span class="' . $meta_class . 'accommodations"><span class="lsx-to-meta-data-key">' . esc_html__( 'Accommodation', 'to-reviews' ) . ':</span> ', '</span>' );
						lsx_to_connected_tours( '<span class="' . $meta_class . 'tours"><span class="lsx-to-meta-data-key">' . esc_html__( 'Tours', 'to-reviews' ) . ':</span> ', '</span>' );
						lsx_to_connected_destinations( '<span class="' . $meta_class . 'destinations"><span class="lsx-to-meta-data-key">' . esc_html__( 'Destinations', 'to-reviews' ) . ':</span> ', '</span>' );
					?>
				</div>
			</section>
		<?php }
	}

	/**
	 * Adds the template tags to the bottom of the single review
	 */
	public function single_content_bottom() {
		if ( is_singular( 'review' ) ) {
			lsx_to_review_accommodation();

			lsx_to_review_tour();

			lsx_to_review_destination();

			lsx_to_gallery( '<section id="gallery" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-gallery">' . esc_html__( 'Gallery', 'to-reviews' ) . '</h2><div id="collapse-gallery" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );

			if ( function_exists( 'lsx_to_videos' ) ) {
				lsx_to_videos( '<section id="videos" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-videos">' . esc_html__( 'Videos', 'to-reviews' ) . '</h2><div id="collapse-videos" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );
			} elseif ( class_exists( 'Envira_Videos' ) ) {
				lsx_to_envira_videos( '<section id="videos" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-videos">' . esc_html__( 'Videos', 'to-reviews' ) . '</h2><div id="collapse-videos" class="collapse in"><div class="collapse-inner">', '</div></div></section>' );
			}

			lsx_to_review_posts();
		}
	}

}

new LSX_TO_Reviews_Frontend();
