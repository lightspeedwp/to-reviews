<?php
/**
 * The Review Schema
 *
 * @package to-reviews
 */

/**
 * Returns schema Review data.
 *
 * @since 10.2
 */
class LSX_TO_Schema_Review extends LSX_TO_Schema_Graph_Piece {

	/**
	 * Constructor.
	 *
	 * @param \WPSEO_Schema_Context $context A value object with context variables.
	 */
	public function __construct( WPSEO_Schema_Context $context ) {
		$this->post_type = 'review';
		parent::__construct( $context );
	}

	/**
	 * Returns Review data.
	 *
	 * @return array $data Review data.
	 */
	public function generate() {
		$post                = get_post( $this->context->id );
		$review_author       = get_post_meta( $post->ID, 'reviewer_name', true );
		$review_email        = get_post_meta( $post->ID, 'reviewer_email', true );
		$rating_value        = get_post_meta( $post->ID, 'rating', true );
		$description         = \lsx\schema\Helpers::strip_to_text( apply_filters( 'the_content', $post->post_content ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$tour_list           = get_post_meta( $post->ID, 'tour_to_review', false );
		$accom_list          = get_post_meta( $post->ID, 'accommodation_to_review', false );
		$comment_count       = get_comment_count( $this->context->id );
		$date_of_visit_start = get_post_meta( $post->ID, 'date_of_visit_start', true );
		$date_of_visit_end   = get_post_meta( $post->ID, 'date_of_visit_end', true );

		$data = array(
			'@type'            => 'Review',
			'@id'              => $this->context->canonical . '#/schema/review/' . $post->ID,
			'url'              => $this->post_url,
			'author'           => array(
				'@type' => 'Person',
				'@id'   => \lsx\legacy\Schema_Utils::get_author_schema_id( $review_author, $review_email, $this->context ),
				'name'  => $review_author,
			),
			'headline'         => get_the_title( $post->ID ),
			'datePublished'    => mysql2date( DATE_W3C, $post->post_date_gmt, false ),
			'dateModified'     => mysql2date( DATE_W3C, $post->post_modified_gmt, false ),
			'commentCount'     => $comment_count['approved'],
			'mainEntityOfPage' => array(
				'@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH,
			),
			'reviewBody'       => $description,
		);

		if ( false !== $rating_value && '' !== $rating_value ) {
			$data['reviewRating'] = array(
				'@type'       => 'Rating',
				'ratingValue' => (int) $rating_value,
				'bestRating'  => 5,
				'worstRating' => 1,
			);
		}

		$data = $this->add_items_reviewed( $data, $tour_list, $accom_list );

		if ( $this->context->site_represents_reference ) {
			$data['publisher'] = $this->context->site_represents_reference;
		}

		$data = $this->add_date_of_visit( $data, $date_of_visit_start, $date_of_visit_end );

		$data = \lsx\legacy\Schema_Utils::add_image( $data, $this->context );
		$data = $this->add_taxonomy_terms( $data, 'keywords', 'post_tag' );
		$data = $this->add_taxonomy_terms( $data, 'about', 'category' );
		$data = $this->add_offers( $data );
		$data = $this->add_destinations( $data );
		return $data;
	}

	/**
	 * Merges the related tour and accommodation posts into a single
	 * itemReviewed value, typed appropriately, without overwriting each other.
	 *
	 * @param array $data       Review data.
	 * @param array $tour_list  Related tour post IDs.
	 * @param array $accom_list Related accommodation post IDs.
	 * @return array $data Review data.
	 */
	public function add_items_reviewed( $data, $tour_list, $accom_list ) {
		$items_reviewed = array();
		$items_reviewed = array_merge( $items_reviewed, \lsx\legacy\Schema_Utils::get_item_reviewed( $tour_list, 'TouristTrip' ) );
		$items_reviewed = array_merge( $items_reviewed, \lsx\legacy\Schema_Utils::get_item_reviewed( $accom_list, 'LodgingBusiness' ) );

		if ( ! empty( $items_reviewed ) ) {
			$data['itemReviewed'] = 1 === count( $items_reviewed ) ? $items_reviewed[0] : $items_reviewed;
		}

		return $data;
	}

	/**
	 * Adds the date of visit as an ISO 8601 temporalCoverage interval when both
	 * dates are available, falling back to an additionalProperty otherwise.
	 *
	 * @param array  $data                Review data.
	 * @param string $date_of_visit_start Start of visit timestamp.
	 * @param string $date_of_visit_end   End of visit timestamp.
	 * @return array $data Review data.
	 */
	public function add_date_of_visit( $data, $date_of_visit_start, $date_of_visit_end ) {
		if ( '' === $date_of_visit_start && '' === $date_of_visit_end ) {
			return $data;
		}

		$start = \lsx\schema\Helpers::format_iso_date( $date_of_visit_start );
		$end   = \lsx\schema\Helpers::format_iso_date( $date_of_visit_end );

		if ( '' !== $start && '' !== $end ) {
			$data['temporalCoverage'] = $start . '/' . $end;
		} else {
			$data['additionalProperty'][] = array(
				'@type' => 'PropertyValue',
				'name'  => __( 'Date of Visit', 'to-reviews' ),
				'value' => trim( $start . ' ' . $end ),
			);
		}

		return $data;
	}

	/**
	 * Adds the Destinations attached to the review as spatialCoverage,
	 * typed as TouristDestination.
	 *
	 * @param array $data Review data.
	 *
	 * @return array $data Review data.
	 */
	public function add_destinations( $data, $data_key = '' ) {
		$places_array = array();
		$destinations = get_post_meta( $this->context->id, 'destination_to_' . $this->post_type, false );
		if ( ! empty( $destinations ) ) {
			foreach ( $destinations as $destination_id ) {
				if ( '' !== $destination_id ) {
					$places_array = \lsx\legacy\Schema_Utils::add_place( $places_array, 'TouristDestination', $destination_id, $this->context );
				}
			}
		}
		if ( ! empty( $places_array ) ) {
			$data['spatialCoverage'] = $places_array;
		}
		return $data;
	}
}
