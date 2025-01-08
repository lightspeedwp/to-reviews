<?php
/**
 * LSX_TO_Reviews
 *
 * @package   LSX_TO_Reviews
 * @author    LightSpeed
 * @license   GPL-3.0+
 * @link      
 * @copyright 2016 LightSpeedDevelopment
 */
if (!class_exists( 'LSX_TO_Reviews' ) ) {
	/**
	 * Main plugin class.
	 *
	 * @package LSX_TO_Reviews
	 * @author  LightSpeed
	 */
	class LSX_TO_Reviews {
		
		/**
		 * The plugins id
		 */
		public $plugin_slug = 'to-reviews';

		/**
		 * Holds the setup class
		 *
		 * @var object
		 */
		public $setup;

		/**
		 * Holds the admin class
		 *
		 * @var object
		 */
		public $admin;

		/**
		 * Holds the frontend class
		 *
		 * @var object
		 */
		public $frontend;

		/**
		 * Constructor
		 */
		public function __construct() {
			require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-reviews-setup.php';
			$this->setup = new LSX_TO_Reviews_Setup();

			require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-reviews-admin.php';
			$this->admin = new LSX_TO_Reviews_Admin();

			require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-reviews-frontend.php';
			$this->frontend = new LSX_TO_Reviews_Frontend();

			require_once LSX_TO_REVIEWS_PATH . '/includes/template-tags.php';

			// Make TO last plugin to load.
			add_action( 'activated_plugin', array( $this, 'activated_plugin' ) );

			// flush_rewrite_rules.
			register_activation_hook( LSX_TO_REVIEWS_CORE, array( $this, 'register_activation_hook' ) );
			add_action( 'admin_init', array( $this, 'register_activation_hook_check' ) );

			
		}

		/**
		 * Make TO last plugin to load.
		 */
		public function activated_plugin() {
			if ( $plugins = get_option( 'active_plugins' ) ) {
				$search = preg_grep( '/.*\/tour-operator\.php/', $plugins );
				$key = array_search( $search, $plugins );

				if ( is_array( $search ) && count( $search ) ) {
					foreach ( $search as $key => $path ) {
						array_splice( $plugins, $key, 1 );
						array_push( $plugins, $path );
						update_option( 'active_plugins', $plugins );
					}
				}
			}
		}

		/**
		 * On plugin activation
		 */
		public function register_activation_hook() {
			if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
				set_transient( '_tour_operators_reviews_flush_rewrite_rules', 1, 30 );
			}
		}

		/**
		 * On plugin activation (check)
		 */
		public function register_activation_hook_check() {
			if ( ! get_transient( '_tour_operators_reviews_flush_rewrite_rules' ) ) {
				return;
			}

			delete_transient( '_tour_operators_reviews_flush_rewrite_rules' );
			flush_rewrite_rules();
		}
	}
	new LSX_TO_Reviews();
}
