<?php
/**
 * Review Schema Graph Piece
 *
 * Outputs schema.org Review markup for the `review` post type. When Yoast SEO
 * is active the class is registered as a graph piece via
 * LSX_TO_Reviews_Frontend::add_graph_pieces(). When Yoast is inactive
 * LSX_TO_Reviews_Frontend::output_standalone_schema() calls generate()
 * directly.
 *
 * The piece is self-contained and depends only on the Tour Operator core
 * schema helpers (lsx\schema\Helpers, core 2.2+), following the same pattern
 * as the core Trip, Accommodation and Destination pieces.
 *
 * @package to-reviews
 */

use lsx\schema\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates schema.org Review data for a single review.
 */
class LSX_TO_Schema_Review {

	/**
	 * Yoast schema context (null when Yoast is not active).
	 *
	 * @var \Yoast\WP\SEO\Context\Meta_Tags_Context|\WPSEO_Schema_Context|null
	 */
	protected $context;

	/**
	 * Post ID being processed.
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * Post object being processed.
	 *
	 * @var \WP_Post|null
	 */
	protected $post;

	/**
	 * Canonical URL for the current post.
	 *
	 * @var string
	 */
	protected $canonical;

	/**
	 * Constructor.
	 *
	 * @param \Yoast\WP\SEO\Context\Meta_Tags_Context|\WPSEO_Schema_Context|null $context Yoast context, or null when Yoast is inactive.
	 */
	public function __construct( $context = null ) {
		$this->context   = $context;
		$this->post_id   = ( null !== $context ) ? (int) $context->id : (int) get_the_ID();
		$this->post      = get_post( $this->post_id );
		$this->canonical = (string) get_permalink( $this->post_id );
	}

	/**
	 * Determines whether this piece should be added to the graph.
	 *
	 * @return bool
	 */
	public function is_needed() {
		return is_singular( 'review' );
	}

	/**
	 * Generates and returns the Review schema data array.
	 *
	 * @return array Schema.org Review data.
	 */
	public function generate() {
		if ( ! is_object( $this->post ) ) {
			return array();
		}

		$comment_count = get_comment_count( $this->post_id );

		$data = array(
			'@type'            => 'Review',
			'@id'              => $this->canonical . '#/schema/review/' . $this->post_id,
			'url'              => $this->canonical,
			'headline'         => get_the_title( $this->post_id ),
			'datePublished'    => mysql2date( DATE_W3C, $this->post->post_date_gmt, false ),
			'dateModified'     => mysql2date( DATE_W3C, $this->post->post_modified_gmt, false ),
			'commentCount'     => (int) $comment_count['approved'],
			'mainEntityOfPage' => array( '@id' => $this->canonical ),
			'reviewBody'       => Helpers::strip_to_text( apply_filters( 'the_content', $this->post->post_content ) ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		);

		$data = $this->add_author( $data );
		$data = $this->add_rating( $data );
		$data = $this->add_items_reviewed( $data );

		if ( null !== $this->context && ! empty( $this->context->site_represents_reference ) ) {
			$data['publisher'] = $this->context->site_represents_reference;
		}

		$data = $this->add_date_of_visit( $data );
		$data = $this->add_image( $data );
		$data = $this->add_terms( $data, 'keywords', 'post_tag' );
		$data = $this->add_terms( $data, 'about', 'category' );
		$data = $this->add_destinations( $data );

		/**
		 * Filter the complete Review schema data array.
		 *
		 * @param array $data    Review schema data.
		 * @param int   $post_id Current review post ID.
		 */
		return (array) apply_filters( 'lsx_to_schema_review_data', $data, $this->post_id );
	}

	/**
	 * Adds the review author. The reviewer email only feeds a one-way keyed
	 * hash for a stable author @id; it is never output.
	 *
	 * @param array $data Review data.
	 * @return array
	 */
	protected function add_author( array $data ) {
		$name  = Helpers::get_meta( $this->post_id, 'reviewer_name' );
		$email = Helpers::get_meta( $this->post_id, 'reviewer_email' );

		$site_url = ( null !== $this->context && ! empty( $this->context->site_url ) ) ? $this->context->site_url : home_url( '/' );

		$data['author'] = array(
			'@type' => 'Person',
			'@id'   => $site_url . '#/schema/person/' . wp_hash( $name . $email ),
			'name'  => $name,
		);

		return $data;
	}

	/**
	 * Adds the review rating on a 1–5 scale.
	 *
	 * @param array $data Review data.
	 * @return array
	 */
	protected function add_rating( array $data ) {
		$rating = Helpers::get_meta( $this->post_id, 'rating' );
		if ( '' !== $rating ) {
			$data['reviewRating'] = array(
				'@type'       => 'Rating',
				'ratingValue' => (int) $rating,
				'bestRating'  => 5,
				'worstRating' => 1,
			);
		}
		return $data;
	}

	/**
	 * Merges the related tours and accommodation into a single itemReviewed
	 * value, typed appropriately, without overwriting each other.
	 *
	 * @param array $data Review data.
	 * @return array
	 */
	protected function add_items_reviewed( array $data ) {
		$items = array_merge(
			$this->get_related_items( 'tour_to_review', 'TouristTrip' ),
			$this->get_related_items( 'accommodation_to_review', 'LodgingBusiness' )
		);

		if ( ! empty( $items ) ) {
			$data['itemReviewed'] = 1 === count( $items ) ? $items[0] : $items;
		}

		return $data;
	}

	/**
	 * Builds typed nodes for the posts linked through a relationship field.
	 *
	 * @param string $meta_key Relationship meta key.
	 * @param string $type     Schema.org type for the nodes.
	 * @return array[]
	 */
	protected function get_related_items( $meta_key, $type ) {
		$items = array();
		foreach ( Helpers::get_meta_array( $this->post_id, $meta_key ) as $related_id ) {
			$title = get_the_title( (int) $related_id );
			if ( '' !== $title ) {
				$items[] = array(
					'@type' => $type,
					'name'  => $title,
				);
			}
		}
		return $items;
	}

	/**
	 * Adds the date of visit as an ISO 8601 temporalCoverage interval when both
	 * dates are available, falling back to an additionalProperty otherwise.
	 *
	 * @param array $data Review data.
	 * @return array
	 */
	protected function add_date_of_visit( array $data ) {
		$start = Helpers::format_iso_date( Helpers::get_meta( $this->post_id, 'date_of_visit_start' ) );
		$end   = Helpers::format_iso_date( Helpers::get_meta( $this->post_id, 'date_of_visit_end' ) );

		if ( '' !== $start && '' !== $end ) {
			$data['temporalCoverage'] = $start . '/' . $end;
		} elseif ( '' !== $start || '' !== $end ) {
			$data['additionalProperty'][] = Helpers::make_property_value( __( 'Date of Visit', 'to-reviews' ), trim( $start . ' ' . $end ) );
		}

		return $data;
	}

	/**
	 * Adds taxonomy term names as a comma-separated value.
	 *
	 * @param array  $data     Review data.
	 * @param string $data_key Schema property.
	 * @param string $taxonomy Taxonomy name.
	 * @return array
	 */
	protected function add_terms( array $data, $data_key, $taxonomy ) {
		$terms = get_the_terms( $this->post_id, $taxonomy );
		if ( ! is_array( $terms ) ) {
			return $data;
		}

		$names = array();
		foreach ( $terms as $term ) {
			// phpcs:ignore WordPress.WP.I18n.MissingArgDomainDefault
			if ( __( 'Uncategorized' ) !== $term->name ) {
				$names[] = $term->name;
			}
		}

		$data[ $data_key ] = implode( ',', $names );
		return $data;
	}

	/**
	 * Adds the destinations attached to the review as spatialCoverage.
	 *
	 * @param array $data Review data.
	 * @return array
	 */
	protected function add_destinations( array $data ) {
		$places = $this->get_related_items( 'destination_to_review', 'TouristDestination' );
		if ( ! empty( $places ) ) {
			$data['spatialCoverage'] = $places;
		}
		return $data;
	}

	/**
	 * Adds the image from the Yoast context, falling back to the featured image.
	 *
	 * @param array $data Review data.
	 * @return array
	 */
	protected function add_image( array $data ) {
		if ( null !== $this->context && ! empty( $this->context->has_image ) ) {
			$data['image'] = array( '@id' => $this->canonical . Helpers::primary_image_hash() );
		} else {
			$thumbnail_url = get_the_post_thumbnail_url( $this->post_id, 'large' );
			if ( $thumbnail_url ) {
				$data['image'] = esc_url( $thumbnail_url );
			}
		}
		return $data;
	}
}
