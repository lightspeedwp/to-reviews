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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 *
 * @package LSX_TO_Reviews_Templates
 * @author  LightSpeed
 */
class LSX_TO_Reviews_Templates {

	/**
	 * Holds array of out templates to be registered.
	 *
	 * @var array
	 */
	public $templates = [];

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type_templates' ] );
	}

	/**
	 * Registers our plugins templates.
	 *
	 * @return void
	 */
	public function register_post_type_templates() {

		/**
		 * The slugs of the built in post types we are using.
		 */
		$post_types = [
			'single-review'  => [
				'title'       => __( 'Single Review', 'to-reviews' ),
				'description' => __( 'Displays a single review', 'to-reviews' ),
				'post_types'  => ['review'],
			],
			'archive-review' => [
				'title'       => __( 'Reviews Archive', 'to-reviews' ),
				'description' => __( 'Displays all the reviews.', 'to-reviews' ),
				'post_types'  => ['review'],
			],
		];

		foreach ( $post_types as $key => $labels ) {
			$args = [
				'title'       => $labels['title'],
				'description' => $labels['description'],
				'content'     => $this->get_template_content( $key . '.html' ),
			];
			if ( isset( $labels['post_types'] ) ) {
				$args['post_types'] = $labels['post_types'];
			}

			if ( function_exists( 'register_block_template' ) ) {
				register_block_template( 'lsx-tour-operator//' . $key, $args );
			}
		}
	}

	/**
	 * Gets the PHP template file and returns the content.
	 *
	 * @param [type] $template
	 * @return void
	 */
	protected function get_template_content( $template ) {
		ob_start();
		include LSX_TO_REVIEWS_PATH . "/templates/{$template}";
		return ob_get_clean();
	}
}

new LSX_TO_Reviews_Templates();
