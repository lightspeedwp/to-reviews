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

class LSX_TO_Reviews_Admin {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );

		add_filter( 'lsx_to_destination_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_tour_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_accommodation_custom_fields', array( $this, 'custom_fields' ) );

		add_filter( 'lsx_to_team_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_special_custom_fields', array( $this, 'custom_fields' ) );
		add_filter( 'lsx_to_activity_custom_fields', array( $this, 'custom_fields' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'to-reviews', FALSE, basename( LSX_TO_REVIEWS_PATH ) . '/languages');
	}

	/**
	 * Registers the custom post type for the content model.
	 *
	 * @return void
	 */
	public function register_post_type() {
		register_post_type(
			'review',
			require_once LSX_TO_REVIEWS_PATH . '/includes/post-types/config-review.php'
		);
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
