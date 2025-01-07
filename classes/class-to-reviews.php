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
		 * The post types the plugin registers
		 */
		public $post_types = [];	

		/**
		 * The singular post types the plugin registers
		 */
		public $post_types_singular = '';	

		/**
		 * An array of the post types slugs plugin registers
		 */
		public $post_type_slugs = [];			

		/**
		 * The taxonomies the plugin registers
		 */
		public $taxonomies = [];			

		/**
		 * The taxonomies the plugin registers (plural)
		 */
		public $taxonomies_plural = [];			

		/**
		 * Constructor
		 */
		public function __construct() {
			//Set the variables
			$this->set_vars();

			// Make TO last plugin to load.
			add_action( 'activated_plugin', array( $this, 'activated_plugin' ) );

			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'init', array( $this, 'register_post_type' ) );

			if ( false !== $this->post_types ) {
				add_filter( 'lsx_to_framework_post_types', array( $this, 'post_types_filter' ) );
				add_filter( 'lsx_to_post_types', array( $this, 'post_types_filter' ) );
				add_filter( 'lsx_to_post_types_singular', array( $this, 'post_types_singular_filter' ) );
				add_filter( 'lsx_to_settings_path', array( $this, 'plugin_path' ), 10, 2 );
			}
			if ( false !== $this->taxonomies ) {
				add_filter( 'lsx_to_framework_taxonomies', array( $this, 'taxonomies_filter' ) );
				add_filter( 'lsx_to_framework_taxonomies_plural', array( $this, 'taxonomies_plural_filter' ) );
			}

			require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-reviews-admin.php';
			require_once LSX_TO_REVIEWS_PATH . '/includes/template-tags.php';

			// flush_rewrite_rules.
			register_activation_hook( LSX_TO_REVIEWS_CORE, array( $this, 'register_activation_hook' ) );
			add_action( 'admin_init', array( $this, 'register_activation_hook_check' ) );

			add_filter( 'wpseo_schema_graph_pieces', array( $this, 'add_graph_pieces' ), 11, 2 );
		}
	
		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'to-reviews', FALSE, basename( LSX_TO_REVIEWS_PATH ) . '/languages');
		}

		/**
		 * Sets the plugins variables
		 */
		public function set_vars() {
			$this->post_types = array(
				'review'	=>	__('Reviews','to-reviews')
			);
			$this->post_types_singular = array(
				'review'	=>	__('Review','to-reviews')
			);
			$this->post_type_slugs = array_keys( $this->post_types );			
		}

		/**
		 * Adds our post types to an array via a filter
		 */
		public function plugin_path($path,$post_type){
			if(false !== $this->post_types && array_key_exists($post_type,$this->post_types)){
				$path = LSX_TO_REVIEWS_PATH;
			}
			return $path;
		}	

		/**
		 * Adds our post types to an array via a filter
		 */
		public function post_types_slugs_filter($post_types){
			if(is_array($post_types)){
				$post_types = array_merge($post_types,$this->post_type_slugs);
			}else{
				$post_types = $this->post_type_slugs;
			}
			return $post_types;
		}

		/**
		 * Adds our post types to an array via a filter
		 */
		public function post_types_filter( $post_types ) {
			if ( is_array( $post_types ) && is_array( $this->post_types ) ) {
				$post_types = array_merge( $post_types, $this->post_types );
			} elseif ( is_array( $this->post_types ) ) {
				$post_types = $this->post_types;
			}
			return $post_types;
		}	

		/**
		 * Adds our post types to an array via a filter
		 */
		public function post_types_singular_filter( $post_types_singular ) {
			if ( is_array( $post_types_singular ) && is_array( $this->post_types_singular ) ) {
				$post_types_singular = array_merge( $post_types_singular, $this->post_types_singular );
			} elseif ( is_array( $this->post_types_singular ) ) {
				$post_types_singular = $this->post_types_singular;
			}
			return $post_types_singular;
		}	

		/**
		 * Adds our taxonomies to an array via a filter
		 */
		public function taxonomies_filter( $taxonomies ) {
			if ( is_array( $taxonomies ) && is_array( $this->taxonomies ) ) {
				$taxonomies = array_merge( $taxonomies, $this->taxonomies );
			} elseif ( is_array( $this->taxonomies ) ) {
				$taxonomies = $this->taxonomies;
			}
			return $taxonomies;
		}

		/**
		 * Adds our taxonomies_plural to an array via a filter
		 */
		public function taxonomies_plural_filter( $taxonomies_plural ) {
			if ( is_array( $taxonomies_plural ) && is_array( $this->taxonomies_plural ) ) {
				$taxonomies_plural = array_merge( $taxonomies_plural, $this->taxonomies_plural );
			} elseif ( is_array( $this->taxonomies_plural ) ) {
				$taxonomies_plural = $this->taxonomies_plural;
			}
			return $taxonomies_plural;
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
		/**
		 * Adds Schema pieces to our output.
		 *
		 * @param array                 $pieces  Graph pieces to output.
		 * @param \WPSEO_Schema_Context $context Object with context variables.
		 *
		 * @return array $pieces Graph pieces to output.
		 */
		public function add_graph_pieces( $pieces, $context ) {
			if ( class_exists( 'LSX_TO_Schema_Graph_Piece' ) ) {
				require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-review-schema.php';
				$pieces[] = new LSX_TO_Schema_Review( $context );
			}
			return $pieces;
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
	}
	new LSX_TO_Reviews();
}
