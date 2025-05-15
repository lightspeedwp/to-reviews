<?php
/*
 * Plugin Name: Tour Operator Reviews
 * Plugin URI:  https://touroperator.solutions/plugins/reviews/
 * Description: The Tour Operator Reviews extension adds the “Reviews” post type, which you can assign to our Tour Operator core post types: Tours, accommodations and destinations.
 * Version:     2.0.2
 * Author:      LightSpeed
 * Author URI:  https://www.lightspeedwp.agency/
 * License:     GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: to-reviews
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'LSX_TO_REVIEWS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSX_TO_REVIEWS_CORE', __FILE__ );
define( 'LSX_TO_REVIEWS_URL', plugin_dir_url( __FILE__ ) );
define( 'LSX_TO_REVIEWS_VER', '2.0.2' );

/* ======================= Below is the Plugin Class init ========================= */

require_once LSX_TO_REVIEWS_PATH . '/classes/class-to-reviews.php';
