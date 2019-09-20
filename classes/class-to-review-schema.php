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
		$description         = wp_strip_all_tags( get_the_content() );
		$tour_list           = get_post_meta( get_the_ID(), 'tour_to_review', false );
		$accom_list          = get_post_meta( get_the_ID(), 'accommodation_to_review', false );
		$comment_count       = get_comment_count( $this->context->id );
		$review_author       = get_post_meta( $post->ID, 'reviewer_name', true );
		$date_of_visit_start = get_post_meta( $post->ID, 'date_of_visit_start', true );
		$date_of_visit_end   = get_post_meta( $post->ID, 'date_of_visit_end', true );

		$data          = array(
			'@type'            => 'Review',
			'@id'              => $this->context->canonical . '#review',
			'author'           => array(
				'@type' => 'Person',
				'@id'   => \lsx\legacy\Schema_Utils::get_author_schema_id( $review_author, $review_email, $this->context ),
				'name'  => $review_author,
				'email' => $review_email,
			),
			'headline'         => get_the_title(),
			'datePublished'    => mysql2date( DATE_W3C, $post->post_date_gmt, false ),
			'dateModified'     => mysql2date( DATE_W3C, $post->post_modified_gmt, false ),
			'commentCount'     => $comment_count['approved'],
			'mainEntityOfPage' => array(
				'@id' => $this->context->canonical . WPSEO_Schema_IDs::WEBPAGE_HASH,
			),
			'reviewRating' => array(
				'@type'       => 'Rating',
				'ratingValue' => $rating_value,
				'bestRating'  => $rating_value,
			),
			'reviewBody'       => $description,
			'itemReviewed'     => \lsx\legacy\Schema_Utils::get_item_reviewed( $tour_list, 'Product' ),
			'itemReviewed'     => \lsx\legacy\Schema_Utils::get_item_reviewed( $accom_list, 'Product' ),
		);

		if ( $this->context->site_represents_reference ) {
			$data['publisher'] = $this->context->site_represents_reference;
		}

		if ( '' !== $date_of_visit_start && '' !== $date_of_visit_end ) {
			$data['temporalCoverage'] = $date_of_visit_start . ' - ' . $date_of_visit_end;
		}

		$data = \lsx\legacy\Schema_Utils::add_image( $data, $this->context );
		$data = $this->add_taxonomy_terms( $data, 'keywords', 'post_tag' );
		$data = $this->add_taxonomy_terms( $data, 'reviewSection', 'category' );
		$data = $this->add_offers( $data );
		$data = $this->add_destinations( $data );
		return $data;
	}

	/**
	 * Adds the Destinations attached to the review
	 *
	 * @param array $data Country / State data.
	 *
	 * @return array $data Country / State data.
	 */
	public function add_destinations( $data, $data_key = '' ) {
		$places_array = array();
		$destinations = get_post_meta( $this->context->id, 'destination_to_' . $this->post_type, false );
		if ( ! empty( $destinations ) ) {
			foreach ( $destinations as $destination_id ) {
				if ( '' !== $destination_id ) {
					if ( 0 === wp_get_post_parent_id( $destination_id ) || false === wp_get_post_parent_id( $destination_id ) ) {
						$places_array = \lsx\legacy\Schema_Utils::add_place( $places_array, 'Country', $destination_id, $this->context );
					} else {
						$places_array = \lsx\legacy\Schema_Utils::add_place( $places_array, 'State', $destination_id, $this->context );
					}
				}
			}
		}
		if ( ! empty( $places_array ) ) {
			$data['spatialCoverage'] = $places_array;
		}
		return $data;
	}
}
