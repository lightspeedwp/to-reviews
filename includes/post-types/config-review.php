<?php
/**
 * Tour Operator - Review Post Type config
 *
 * @package   tour_operator
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

$post_type = array(
	'class'               => 'LSX_TO_Reviews',
	'menu_icon'           => 'dashicons-editor-ul',
	'labels'              => array(
		'name'               => esc_html__( 'Reviews', 'to-reviews' ),
		'singular_name'      => esc_html__( 'Review', 'to-reviews' ),
		'add_new'            => esc_html__( 'Add New', 'to-reviews' ),
		'add_new_item'       => esc_html__( 'Add New Review', 'to-reviews' ),
		'edit_item'          => esc_html__( 'Edit Review', 'to-reviews' ),
		'new_item'           => esc_html__( 'New Reviews', 'to-reviews' ),
		'all_items'          => esc_html__( 'Reviews', 'to-reviews' ),
		'view_item'          => esc_html__( 'View Review', 'to-reviews' ),
		'search_items'       => esc_html__( 'Search Reviews', 'to-reviews' ),
		'not_found'          => esc_html__( 'No reviews found', 'to-reviews' ),
		'not_found_in_trash' => esc_html__( 'No reviews found in Trash', 'to-reviews' ),
		'parent_item_colon'  => '',
		'menu_name'          => esc_html__( 'Activities', 'to-reviews' ),
	),
	'public'              => true,
	'publicly_queryable'  => true,
	'show_ui'             => true,
	'show_in_menu'        => 'tour-operator',
	'menu_position'       => 60,
	'query_var'           => true,
	'rewrite'             => array(
		'slug'       => 'review',
		'with_front' => false,
	),
	'exclude_from_search' => false,
	'capability_type'     => 'post',
	'has_archive'         => 'reviews',
	'hierarchical'        => false,
	'supports'            => array(
		'title',
		'slug',
		'editor',
		'thumbnail',
		'excerpt',
		'custom-fields',
	),
);

return $post_type;
