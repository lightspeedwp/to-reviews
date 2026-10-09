<?php
/**
 * Regression tests for the Review schema piece.
 *
 * @package to-reviews
 */

use PHPUnit\Framework\TestCase;

require_once dirname( __DIR__, 2 ) . '/classes/class-to-review-schema.php';

/**
 * Tests for LSX_TO_Schema_Review.
 */
class ReviewSchemaTest extends TestCase {

	const REVIEW_ID = 10;

	const EMAIL = 'guest.private@example.test';

	/**
	 * Sets up a published review with a named reviewer and email.
	 */
	protected function setUp(): void {
		lsx_to_test_reset();
		lsx_to_test_add_post(
			self::REVIEW_ID,
			array(
				'post_title'   => 'Rachel &amp; Nigel',
				'post_type'    => 'review',
				'post_content' => '<p>A <em>wonderful</em> trip.</p>',
			)
		);
		lsx_to_test_add_meta(
			self::REVIEW_ID,
			array(
				'reviewer_name'  => 'Rachel &amp; Nigel',
				'reviewer_email' => self::EMAIL,
				'rating'         => '4',
			)
		);
	}

	/**
	 * Builds the piece with a Yoast-like context.
	 *
	 * @param array $overrides Context properties to override.
	 * @return array Generated schema.
	 */
	private function generate( array $overrides = array() ) {
		$context = (object) array_merge(
			array(
				'id'                        => self::REVIEW_ID,
				'has_image'                 => false,
				'site_url'                  => 'https://example.test/',
				'site_represents_reference' => array( '@id' => 'https://example.test/#organization' ),
			),
			$overrides
		);
		return ( new LSX_TO_Schema_Review( $context ) )->generate();
	}

	public function test_review_core_properties() {
		$data = $this->generate();

		$this->assertSame( 'Review', $data['@type'] );
		$this->assertSame( 'https://example.test/review/10/#/schema/review/10', $data['@id'] );
		$this->assertSame( 'https://example.test/review/10/', $data['url'] );
		$this->assertSame( 'Rachel & Nigel', $data['headline'] );
		$this->assertSame( 'A wonderful trip.', $data['reviewBody'] );
		$this->assertSame( '2026-01-02T03:04:05+00:00', $data['datePublished'] );
		$this->assertSame( '2026-02-03T04:05:06+00:00', $data['dateModified'] );
		$this->assertSame( array( '@id' => 'https://example.test/#organization' ), $data['publisher'] );
	}

	public function test_reviewer_email_is_never_output() {
		$data = $this->generate();
		$json = (string) json_encode( $data );

		$this->assertSame( 'Rachel & Nigel', $data['author']['name'] );
		$this->assertArrayNotHasKey( 'email', $data['author'] );
		$this->assertStringNotContainsString( self::EMAIL, $json );
		$this->assertStringNotContainsString( rawurlencode( self::EMAIL ), $json );
		$this->assertStringNotContainsString( 'guest.private', $json );
	}

	public function test_author_is_omitted_without_a_reviewer_name() {
		lsx_to_test_add_meta( self::REVIEW_ID, array( 'reviewer_name' => '' ) );

		$this->assertArrayNotHasKey( 'author', $this->generate() );
	}

	public function test_rating_uses_a_one_to_five_scale() {
		$this->assertSame(
			array(
				'@type'       => 'Rating',
				'ratingValue' => 4,
				'bestRating'  => 5,
				'worstRating' => 1,
			),
			$this->generate()['reviewRating']
		);
	}

	/**
	 * Ratings that cannot be expressed on the 1–5 scale.
	 *
	 * @return array
	 */
	public static function invalid_ratings() {
		return array(
			'empty'        => array( '' ),
			'zero'         => array( '0' ),
			'above scale'  => array( '6' ),
			'not a number' => array( 'great' ),
			'decimal'      => array( '4.5' ),
		);
	}

	/**
	 * @dataProvider invalid_ratings
	 *
	 * @param string $rating Stored rating.
	 */
	public function test_invalid_ratings_are_omitted( $rating ) {
		lsx_to_test_add_meta( self::REVIEW_ID, array( 'rating' => $rating ) );

		$this->assertArrayNotHasKey( 'reviewRating', $this->generate() );
	}

	public function test_tours_and_accommodation_are_merged_without_overwriting() {
		lsx_to_test_add_post( 20, array( 'post_title' => 'Cape &#8211; Safari', 'post_type' => 'tour' ) );
		lsx_to_test_add_post( 21, array( 'post_title' => 'Draft Tour', 'post_type' => 'tour', 'post_status' => 'draft' ) );
		lsx_to_test_add_post( 30, array( 'post_title' => 'River Lodge', 'post_type' => 'accommodation' ) );
		lsx_to_test_add_meta(
			self::REVIEW_ID,
			array(
				// Stored as one serialised array, as CMB2 relationship fields do.
				'tour_to_review'          => array( array( '20', '21', '20' ) ),
				'accommodation_to_review' => array( array( '30', '30' ) ),
			)
		);

		$this->assertSame(
			array(
				array(
					'@type' => 'TouristTrip',
					'name'  => "Cape \u{2013} Safari",
					'url'   => 'https://example.test/tour/20/',
				),
				array(
					'@type' => 'LodgingBusiness',
					'name'  => 'River Lodge',
					'url'   => 'https://example.test/accommodation/30/',
				),
			),
			$this->generate()['itemReviewed']
		);
	}

	public function test_single_reviewed_item_is_an_object() {
		lsx_to_test_add_post( 30, array( 'post_title' => 'River Lodge', 'post_type' => 'accommodation' ) );
		lsx_to_test_add_meta( self::REVIEW_ID, array( 'accommodation_to_review' => array( 30 ) ) );

		$this->assertSame( 'LodgingBusiness', $this->generate()['itemReviewed']['@type'] );
	}

	public function test_ordered_visit_dates_form_an_iso_interval() {
		lsx_to_test_add_meta(
			self::REVIEW_ID,
			array(
				'date_of_visit_start' => (string) gmmktime( 0, 0, 0, 6, 1, 2025 ),
				'date_of_visit_end'   => (string) gmmktime( 0, 0, 0, 6, 14, 2025 ),
			)
		);

		$data = $this->generate();

		$this->assertSame( '2025-06-01/2025-06-14', $data['temporalCoverage'] );
		$this->assertArrayNotHasKey( 'additionalProperty', $data );
	}

	public function test_reversed_visit_dates_fall_back_to_a_property() {
		lsx_to_test_add_meta(
			self::REVIEW_ID,
			array(
				'date_of_visit_start' => (string) gmmktime( 0, 0, 0, 9, 5, 2025 ),
				'date_of_visit_end'   => (string) gmmktime( 0, 0, 0, 6, 19, 2025 ),
			)
		);

		$data = $this->generate();

		$this->assertArrayNotHasKey( 'temporalCoverage', $data );
		$this->assertSame(
			array(
				array(
					'@type' => 'PropertyValue',
					'name'  => 'Date of Visit',
					'value' => "2025-09-05 \u{2013} 2025-06-19",
				),
			),
			$data['additionalProperty']
		);
	}

	public function test_categories_map_to_about_things_and_review_section_is_gone() {
		lsx_to_test_add_terms( self::REVIEW_ID, 'category', array( 'Safari', 'Uncategorized', 'Family' ) );
		lsx_to_test_add_terms( self::REVIEW_ID, 'post_tag', array( 'Namibia', 'Etosha' ) );

		$data = $this->generate();

		$this->assertArrayNotHasKey( 'reviewSection', $data );
		$this->assertSame(
			array(
				array(
					'@type' => 'Thing',
					'name'  => 'Safari',
				),
				array(
					'@type' => 'Thing',
					'name'  => 'Family',
				),
			),
			$data['about']
		);
		$this->assertSame( 'Namibia, Etosha', $data['keywords'] );
	}

	public function test_about_and_keywords_are_omitted_when_empty() {
		lsx_to_test_add_terms( self::REVIEW_ID, 'category', array( 'Uncategorized' ) );

		$data = $this->generate();

		$this->assertArrayNotHasKey( 'about', $data );
		$this->assertArrayNotHasKey( 'keywords', $data );
	}

	public function test_destinations_map_to_tourist_destination_spatial_coverage() {
		lsx_to_test_add_post( 40, array( 'post_title' => 'Namibia', 'post_type' => 'destination' ) );
		lsx_to_test_add_post( 41, array( 'post_title' => 'Old', 'post_type' => 'destination', 'post_status' => 'trash' ) );
		lsx_to_test_add_meta( self::REVIEW_ID, array( 'destination_to_review' => array( array( '40', '41' ) ) ) );

		$this->assertSame(
			array(
				array(
					'@type' => 'TouristDestination',
					'name'  => 'Namibia',
					'url'   => 'https://example.test/destination/40/',
				),
			),
			$this->generate()['spatialCoverage']
		);
	}

	public function test_no_offers_are_emitted_for_linked_specials() {
		lsx_to_test_add_post( 50, array( 'post_type' => 'special' ) );
		lsx_to_test_add_meta( 50, array( 'price' => '1000' ) );
		lsx_to_test_add_meta( self::REVIEW_ID, array( 'special_to_review' => array( 50 ) ) );

		$data = $this->generate();

		$this->assertArrayNotHasKey( 'offers', $data );
		$this->assertStringNotContainsString( 'PriceSpecification', (string) json_encode( $data ) );
	}
}
