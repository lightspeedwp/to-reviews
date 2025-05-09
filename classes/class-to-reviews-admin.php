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
	 * The post type slug
	 *
	 * @var string
	 */
	public $post_type = 'review';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'cmb2_admin_init', array( $this, 'register_cmb2_fields' ) );

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
		load_plugin_textdomain( 'to-reviews', false, basename( LSX_TO_REVIEWS_PATH ) . '/languages' );
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
		// @phpcs:disable WordPress.Security.NonceVerification.Recommended
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
		// @phpcs:enable WordPress.Security.NonceVerification.Recommended
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

	/**
	 * Registers the CMB2 custom fields
	 *
	 * @return void
	 */
	public function register_cmb2_fields() {
		/**
		 * Initiate the metabox
		 */
		$cmb = [];
		$fields = include( LSX_TO_REVIEWS_PATH . 'includes/metaboxes/config-review.php' );

		$metabox_counter = 1;
		$cmb[ $metabox_counter ] = new_cmb2_box( array(
			'id'            => 'lsx_to_metabox_reviews_' . $metabox_counter,
			'title'         => $fields['title'],
			'object_types'  => array( $this->post_type ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true,
		) );

		foreach ( $fields['fields'] as $field ) {

			if ( 'title' === $field['type'] ) {
				$metabox_counter++;
				$cmb[ $metabox_counter ] = new_cmb2_box( array(
					'id'            => 'lsx_to_metabox_' . $this->post_type . '_' . $metabox_counter,
					'title'         => $field['name'],
					'object_types'  => array( $this->post_type ), // Post type
					'context'       => 'normal',
					'priority'      => 'high',
					'show_names'    => true,
				) );
				continue;
			}

			/**
			 * Fixes for the extensions
			 */
			if ( 'post_select' === $field['type'] || 'post_ajax_search' === $field['type'] ) {
				$field['type'] = 'pw_multiselect';
			}

			$cmb[ $metabox_counter ]->add_field( $field );
		}
	}
}
