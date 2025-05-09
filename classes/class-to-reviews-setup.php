<?php
/**
 * LSX_TO_Reviews_Setup
 *
 * @package   LSX_TO_Reviews_Setup
 * @author    LightSpeed
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 LightSpeedDevelopment
 */

/**
 * Main plugin class.
 *
 * @package LSX_TO_Reviews_Setup
 * @author  LightSpeed
 */

class LSX_TO_Reviews_Setup {

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
		
		add_filter( 'lsx_to_post_types', array( $this, 'post_types_filter' ) );
		add_filter( 'lsx_to_post_types_singular', array( $this, 'post_types_singular_filter' ) );
		
		add_filter( 'lsx_to_framework_taxonomies', array( $this, 'taxonomies_filter' ) );
		add_filter( 'lsx_to_framework_taxonomies_plural', array( $this, 'taxonomies_plural_filter' ) );
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
}
