<?php
/**
 * Review Card Pattern
 *
 * @package to-reviews
 */

return array(
	'title'         => __( 'Review Card', 'to-reviews' ),
	'description'   => __( 'A card layout for displaying review testimonials in query loops with quotation mark and author information.', 'to-reviews' ),
	'categories'    => array( 'lsx-tour-operator' ),
	'keywords'      => array(
		__( 'review', 'to-reviews' ),
		__( 'testimonial', 'to-reviews' ),
		__( 'card', 'to-reviews' ),
		__( 'quote', 'to-reviews' ),
		__( 'quotation', 'to-reviews' ),
	),
	'postTypes'     => array( 'wp_template' ),
	'blockTypes'    => array( 'core/post-template' ),
	'templateTypes' => array( 'archive-review', 'single-review' ),
	'viewportWidth' => 400,
	'content'       => '<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Review Card', 'to-reviews' ) . '","categories":["lsx-tour-operator"],"patternName":"lsx-tour-operator/review-card"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20","right":"var:preset|spacing|20"}}},"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"},"ariaLabel":"' . esc_attr__( 'Review Card', 'to-reviews' ) . '"} -->
<div aria-label="' . esc_attr__( 'Review Card', 'to-reviews' ) . '" class="wp-block-group" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)"><!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Content', 'to-reviews' ) . '"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Rating', 'to-reviews' ) . '"},"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:lsx-tour-operator/review-rating /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Quote', 'to-reviews' ) . '"},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-excerpt {"textAlign":"center","showMoreOnNewLine":false,"excerptLength":40,"fontSize":"medium"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"' . esc_attr__( 'Author', 'to-reviews' ) . '"},"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical","justifyContent":"center","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:lsx-tour-operator/review-reviewer-name /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);
