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
	'type'		 => 'select',
	'options'	 => array( '0', '1', '2', '3', '4', '5' ),
	'allow_none' => true,
);
$metabox['fields'][] = array(
	'id'	=> 'date_of_visit_start',
	'name'	=> esc_html__( 'Start date of visit', 'to-reviews' ),
	'type'	=> 'text_date_timestamp',
	'cols'	=> 6,
);

$metabox['fields'][] = array(
	'id'	=> 'date_of_visit_end',
	'name'	=> esc_html__( 'End date of visit', 'to-reviews' ),
	'type'	=> 'text_date_timestamp',
	'cols'	=> 6,
);

if ( class_exists( 'LSX_TO_Team' ) ) {
	$metabox['fields'][] = array(
		'id'         => 'team_to_review',
		'name'       => esc_html__( 'Reviewed By', 'to-reviews' ),
		'type'       => 'pw_multiselect',
		'use_ajax'   => false,
		'allow_none' => true,
		'options'  => array(
			'post_type_args' => 'team',
		),
	);
}

$metabox['fields'][] = array(
	'id'   => 'gallery_title',
	'name' => esc_html__( 'Gallery', 'to-reviews' ),
	'type' => 'title',
);

$metabox['fields'][] = array(
    'name' => esc_html__( 'Gallery', 'tour-operator' ),
	'desc' => esc_html__( 'Add images related to the review to be displayed in the Reviews\'s gallery.', 'tour-operator' ),
    'id'   => 'gallery',
    'type' => 'file_list',
    'preview_size' => 'thumbnail', // Image size to use when previewing in the admin.
    'query_args' => array( 'type' => 'image' ), // Only images attachment
    'text' => array(
        'add_upload_files_text' => esc_html__( 'Add new image', 'tour-operator' ), // default: "Add or Upload Files"
    ),
);

$metabox['fields'][] = array(
	'id'   => 'related_title',
	'name' => esc_html__( 'Related', 'to-reviews' ),
	'type' => 'title',
);

$post_types = array(
	'post'          => esc_html__( 'Posts', 'to-reviews' ),
	'accommodation' => esc_html__( 'Accommodation', 'to-reviews' ),
	'destination'   => esc_html__( 'Destinations', 'to-reviews' ),
	'tour'          => esc_html__( 'Tours', 'to-reviews' ),
);

foreach ( $post_types as $slug => $label ) {
	$metabox['fields'][] = array(
		'id'         => $slug . '_to_review',
		'name'       => $label . esc_html__( ' related with this review', 'to-reviews' ),
		'type'       => 'pw_multiselect',
		'use_ajax'   => false,
		'repeatable' => true,
		'allow_none' => true,
		'options'  => array(
			'post_type_args' => $slug,
		),
	);
}

$metabox['fields'] = apply_filters( 'lsx_to_review_custom_fields', $metabox['fields'] );

return $metabox;
