<?php
/**
 * Template Tags
 *
 * @package   LSX_TO_Reviews
 * @license   GPL-2.0+
 */

/**
 * Outputs the posts attached review
 *
 * @package 	to-reviews
 * @subpackage	template-tags
 * @category 	review
 */
if ( ! function_exists( 'lsx_to_review_posts' ) ) {
	function lsx_to_review_posts() {
		global $lsx_to_archive;

		$args = array(
			'from'		=> 'post',
			'to'		=> 'review',
			'column'	=> '3',
			'before'	=> '<section id="posts" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-posts">' . esc_html__( 'Featured Posts', 'to-reviews' ) . '</h2><div id="collapse-posts" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Find the content part in the plugin
 *
 * @package 	to-reviews
 * @subpackage	template-tag
 * @category 	content
 */
function lsx_to_review_content( $slug, $name = null ) {
	do_action( 'lsx_to_review_content', $slug, $name );
}

/* ================  REVIEWS =========================== */
/**
 * Gets the current reviews rating
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	review
 */
function lsx_to_review_rating( $before = '', $after = '', $echo = true ) {
	lsx_to_custom_field_query( 'rating', $before, $after, $echo );
}

/**
 * Outputs the reviews dates
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	review
 */
function lsx_to_review_dates( $before = '', $after = '', $echo = true ) {
	$valid_from = get_post_meta( get_the_ID(), 'date_of_visit_start', true );
	$valid_to = get_post_meta( get_the_ID(), 'date_of_visit_end', true );

	if ( false !== $valid_from && '' !== $valid_from ) {
		$valid_from = date( 'd M Y', strtotime( $valid_from ) );
	}

	if ( false !== $valid_to && '' !== $valid_to ) {
		$valid_from .= ' - ' . date( 'd M Y', strtotime( $valid_to ) );
	}

	if ( false !== $valid_from && '' !== $valid_from ) {
		$return = $before . $valid_from . $after;

		if ( $echo ) {
			echo $return;
		} else {
			return $return;
		}
	}
}
/**
 * Outputs the connected accommodation for a review
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	review
 */
function lsx_to_review_accommodation() {
	global $lsx_archive;

	if ( post_type_exists( 'accommodation' ) && is_singular( 'review' ) ) {
		$args = array(
			'from'		=> 'accommodation',
			'to'		=> 'review',
			'column'	=> '3',
			'before'	=> '<section id="accommodation" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-accommodation">' . __( lsx_to_get_post_type_section_title( 'accommodation', '', 'Featured Accommodations' ), 'to-reviews' ) . '</h2><div id="collapse-accommodation" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected tour only a review
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	review
 */
function lsx_to_review_tour() {
	global $lsx_archive;

	if ( post_type_exists( 'tour' ) && is_singular( 'review' ) ) {
		$args = array(
			'from'		=> 'tour',
			'to'		=> 'review',
			'column'	=> '3',
			'before'	=> '<section id="tours" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-tours">' . __( lsx_to_get_post_type_section_title( 'tour', '', 'Featured Tours' ), 'to-reviews' ) . '</h2><div id="collapse-tours" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected destination only a review
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	review
 */
function lsx_to_review_destination() {
	global $lsx_archive;

	if ( post_type_exists( 'destination' ) && is_singular( 'review' ) ) {
		$args = array(
			'from'		=> 'destination',
			'to'		=> 'review',
			'column'	=> '3',
			'before'	=> '<section id="destinations" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-destinations">' . __( lsx_to_get_post_type_section_title( 'destination', '', 'Featured Destinations' ), 'to-reviews' ) . '</h2><div id="collapse-destinations" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected reviews for an accommodation
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	review
 */
function lsx_to_accommodation_reviews() {
	global $lsx_archive;

	if ( post_type_exists( 'review' ) && is_singular( 'accommodation' ) ) {
		$args = array(
			'from'		=> 'review',
			'to'		=> 'accommodation',
			'column'	=> '2',
			'before'	=> '<section id="review" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-review">' . __( lsx_to_get_post_type_section_title( 'review', '', 'Reviews' ), 'to-reviews' ) . '</h2><div id="collapse-review" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected reviews for a tour
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	review
 */
function lsx_to_tour_reviews() {
	global $lsx_archive;

	if ( post_type_exists( 'review' ) && is_singular( 'tour' ) ) {
		$args = array(
			'from'		=> 'review',
			'to'		=> 'tour',
			'column'	=> '2',
			'before'	=> '<section id="review" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-review">' . __( lsx_to_get_post_type_section_title( 'review', '', 'Reviews' ), 'to-reviews' ) . '</h2><div id="collapse-review" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Outputs the connected reviews for a destination
 *
 * @package 	lsx-tour-operators
 * @subpackage	template-tags
 * @category 	review
 */
function lsx_to_destination_reviews() {
	global $lsx_archive;

	if ( post_type_exists( 'review' ) && is_singular( 'destination' ) ) {
		$args = array(
			'from'		=> 'review',
			'to'		=> 'destination',
			'column'	=> '2',
			'before'	=> '<section id="review" class="lsx-to-section lsx-to-collapse-section"><h2 class="lsx-to-section-title lsx-to-collapse-title lsx-title" data-toggle="collapse" data-target="#collapse-review">' . __( lsx_to_get_post_type_section_title( 'review', '', 'Reviews' ), 'to-reviews' ) . '</h2><div id="collapse-review" class="collapse in"><div class="collapse-inner">',
			'after'		=> '</div></div></section>',
		);

		lsx_to_connected_panel_query( $args );
	}
}

/**
 * Gets the current specials connected reviews
 *
 * @param		$before	| string
 * @param		$after	| string
 * @param		$echo	| boolean
 * @return		string
 *
 * @package 	tour-operator
 * @subpackage	template-tags
 * @category 	connections
 */
function lsx_to_connected_reviews( $before = '', $after = '', $echo = true ) {
	lsx_to_connected_items_query( 'review', get_post_type(), $before, $after, $echo );
}
