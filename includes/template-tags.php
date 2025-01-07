<?php
/**
 * Template Tags
 *
 * @package   LSX_TO_Reviews
 * @license   GPL-2.0+
 */

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
