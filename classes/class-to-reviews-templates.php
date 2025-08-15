<?php
/**
 * LSX_TO_Reviews_Templates
 *
 * @package   LSX_TO_Reviews_Templates
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Reviews_Templates
 * @author  LightSpeed
 */
class LSX_TO_Reviews_Templates {

	/**
	 * The slug for this plugin
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'to-reviews';

	/**
	 * The active template path
	 *
	 * @var      string
	 */
	protected $path = false;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->path = LSX_TO_REVIEWS_PATH;
		
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'template_include', array( $this, 'single_template_include' ), 20, 1 );
		add_filter( 'template_include', array( $this, 'archive_template_include' ), 20, 1 );
	}

	/**
	 * Initializes the variables we need.
	 */
	public function init() {
		$this->path = LSX_TO_REVIEWS_PATH;
	}

	/**
	 * Load the single template from the plugin if its not found in the theme
	 *
	 * @param string $template The template file.
	 * @return string
	 */
	public function single_template_include( $template ) {
		if ( is_main_query() && is_singular( 'review' ) ) {
			if ( empty( locate_template( array( 'single-review.php' ) ) ) ) {
				$template = $this->path . 'templates/single-review.php';
			}
		}
		return $template;
	}

	/**
	 * Load the archive template from the plugin if its not found in the theme
	 *
	 * @param string $template The template file.
	 * @return string
	 */
	public function archive_template_include( $template ) {
		$post_type = 'review';
		if ( is_main_query() && is_post_type_archive( $post_type ) ) {
			if ( empty( locate_template( array( 'archive-' . $post_type . '.php' ) ) ) ) {
				$template = $this->path . 'templates/archive-' . $post_type . '.php';
			}
		}
		return $template;
	}
}

new LSX_TO_Reviews_Templates();
