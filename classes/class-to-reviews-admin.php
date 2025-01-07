<?php
/**
 * LSX_TO_Reviews_Admin
 *
 * @package   LSX_TO_Reviews_Admin
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Reviews_Admin
 * @author  LightSpeed
 */

class LSX_TO_Reviews_Admin extends LSX_TO_Reviews {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->set_vars();

		add_filter( 'lsx_get_metaboxes_configs', array( $this, 'meta_box_config' ), 10, 1 );

		add_filter( 'lsx_to_destination_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_tour_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_accommodation_custom_fields', array( $this, 'custom_fields' ) );

		add_filter( 'lsx_to_team_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_special_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_activity_custom_fields', array( $this, 'custom_fields' ) );
	}

	/**
	 * Register the activity post type config
	 *
	 * @param  $objects
	 * @return   array
	 */
	public function post_type_config( $objects ) {

		foreach ( $this->post_types as $key => $label ) {
			if ( file_exists( LSX_TO_REVIEWS_PATH . 'includes/post-types/config-' . $key . '.php' ) ) {
				$objects[ $key ] = include LSX_TO_REVIEWS_PATH . 'includes/post-types/config-' . $key . '.php';
			}
		}

		return 	$objects;
	}

	/**
	 * Register the activity metabox config
	 *
	 * @param  $meta_boxes
	 * @return   array
	 */
	public function meta_box_config( $meta_boxes ) {
		foreach ( $this->post_types as $key => $label ) {
			if ( file_exists( LSX_TO_REVIEWS_PATH . 'includes/metaboxes/config-' . $key . '.php' ) ) {
				$meta_boxes[ $key ] = include LSX_TO_REVIEWS_PATH . 'includes/metaboxes/config-' . $key . '.php';
			}
		}
		return 	$meta_boxes;
	}

	/**
	 * Adds in the fields to the Tour Operators Post Types.
	 */
	public function custom_fields( $fields ) {
		global $post, $typenow, $current_screen;

		$post_type = false;
		if ( $post && $post->post_type ) {
			$post_type = $post->post_type;
		} elseif ( $typenow ) {
			$post_type = $typenow;
		} elseif ( $current_screen && $current_screen->post_type ) {
			$post_type = $current_screen->post_type;
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = sanitize_key( $_REQUEST['post_type'] );
		} elseif ( isset( $_REQUEST['post'] ) ) {
			$post_type = get_post_type( sanitize_key( $_REQUEST['post'] ) );
		}
		if ( false !== $post_type ) {
			$fields[] = array(
				'id' => 'review_to_' . $post_type,
				'name' => 'Reviews related with this ' . $post_type,
				'type' => 'pw_multiselect',
				'use_ajax'   => false,
				'repeatable' => false,
				'allow_none' => true,
				'options'  => array(
					'post_type_args' => 'review',
				),
			);
		}
		return $fields;
	}
}
new LSX_TO_Reviews_Admin();
