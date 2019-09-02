<?php
/**
 * Tour Operator - Review Metabox config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$metabox = array(
	'title'  => esc_html__( 'Tour Operator Plugin', 'to-reviews' ),
	'pages'  => 'review',
	'fields' => array(),
);

$metabox['fields'][] = array(
	'id'   => 'featured',
	'name' => esc_html__( 'Featured', 'to-reviews' ),
	'type' => 'checkbox',
);

$metabox['fields'][] = array(
	'id'   => 'disable_single',
	'name' => esc_html__( 'Disable Single', 'to-reviews' ),
	'type' => 'checkbox',
);

if ( ! class_exists( 'LSX_Banners' ) ) {
	$metabox['fields'][] = array(
		'id'   => 'tagline',
		'name' => esc_html__( 'Tagline', 'to-reviews' ),
		'type' => 'text',
	);
}

$metabox['fields'][] = array(
	'id'	=> 'no_adults',
	'name'	=> esc_html__( 'No of Adults', 'to-reviews' ),
	'type'	=> 'text',
	'cols'	=> 6,
);

$metabox['fields'][] = array(
	'id'	=> 'no_children',
	'name'	=> esc_html__( 'No of Children', 'to-reviews' ),
	'type'	=> 'text',
	'cols'	=> 6,
);

$metabox['fields'][] = array(
	'id'	=> 'reviewer_name',
	'name'	=> esc_html__( 'Reviewer Name', 'to-reviews' ),
	'type'	=> 'text',
	'cols'	=> 6,
);

$metabox['fields'][] = array(
	'id'	=> 'reviewer_email',
	'name'	=> esc_html__( 'Reviewer Email', 'to-reviews' ),
	'type'	=> 'text',
	'cols'	=> 6,
);

$metabox['fields'][] = array(
	'id'		 => 'rating',
	'name'		 => esc_html__( 'Rating', 'to-reviews' ),
	'type'		 => 'radio',
	'options'	 => array( '0', '1', '2', '3', '4', '5' ),
	'allow_none' => true,
);
$metabox['fields'][] = array(
	'id'	=> 'date_of_visit_start',
	'name'	=> esc_html__( 'Date of visit', 'to-reviews' ),
	'type'	=> 'date',
	'cols'	=> 6,
);

$metabox['fields'][] = array(
	'id'	=> 'date_of_visit_end',
	'name'	=> '',
	'type'	=> 'date',
	'cols'	=> 6,
);

if ( class_exists( 'LSX_TO_Team' ) ) {
	$metabox['fields'][] = array(
		'id'         => 'team_to_review',
		'name'       => esc_html__( 'Reviewed By', 'to-reviews' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => 'team',
			'nopagin'        => true,
			'posts_per_page' => 1000,
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);
}

$metabox['fields'][] = array(
	'id'   => 'gallery_title',
	'name' => esc_html__( 'Gallery', 'to-reviews' ),
	'type' => 'title',
);

$metabox['fields'][] = array(
	'id'         => 'gallery',
	'name'       => esc_html__( 'Gallery', 'to-reviews' ),
	'type'       => 'image',
	'repeatable' => true,
	'show_size'  => false,
);

if ( class_exists( 'Envira_Gallery' ) ) {
	$metabox['fields'][] = array(
		'id'   => 'envira_title',
		'name' => esc_html__( 'Envira Gallery', 'to-reviews' ),
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'         => 'envira_gallery',
		'name'       => esc_html__( 'Envira Gallery', 'to-reviews' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => 'envira',
			'nopagin'        => true,
			'posts_per_page' => '-1',
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);

	if ( class_exists( 'Envira_Videos' ) ) {
		$metabox['fields'][] = array(
			'id'         => 'envira_video',
			'name'       => esc_html__( 'Envira Video Gallery', 'to-reviews' ),
			'type'       => 'post_select',
			'use_ajax'   => false,
			'allow_none' => true,
			'query'      => array(
				'post_type'      => 'envira',
				'nopagin'        => true,
				'posts_per_page' => '-1',
				'orderby'        => 'title',
				'order'          => 'ASC',
			),
		);
	}
}

$post_types = array(
	'post'          => esc_html__( 'Posts', 'to-reviews' ),
	'accommodation' => esc_html__( 'Accommodation', 'to-reviews' ),
	'destination'   => esc_html__( 'Destinations', 'to-reviews' ),
	'tour'          => esc_html__( 'Tours', 'to-reviews' ),
);

foreach ( $post_types as $slug => $label ) {
	$metabox['fields'][] = array(
		'id'   => $slug . '_title',
		'name' => $label,
		'type' => 'title',
	);

	$metabox['fields'][] = array(
		'id'         => $slug . '_to_review',
		'name'       => $label . esc_html__( ' related with this review', 'to-reviews' ),
		'type'       => 'post_select',
		'use_ajax'   => false,
		'repeatable' => true,
		'allow_none' => true,
		'query'      => array(
			'post_type'      => $slug,
			'nopagin'        => true,
			'posts_per_page' => '-1',
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
	);
}

$metabox['fields'] = apply_filters( 'lsx_to_review_custom_fields', $metabox['fields'] );

return $metabox;
