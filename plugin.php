<?php
/**
 * Plugin Name: All In One Video Player
 * Plugin URI:  https://wpmilitary.com/all-in-one-video-player
 * Description: All In One Video Player.
 * Version:     1.0.0
 * Author:      WP Military
 * Author URI:  https://wpmilitary.com/
 * Text Domain: aiovp
 * Domain Path: /languages/
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( __( 'You can\'t access this page', 'radio-player' ) );
}

/** define constants */
define( 'AIOVP_VERSION', '1.0.0' );
define( 'AIOVP_FILE', __FILE__ );
define( 'AIOVP_PATH', dirname( AIOVP_FILE ) );
define( 'AIOVP_INCLUDES', AIOVP_PATH . '/includes' );
define( 'AIOVP_URL', plugins_url( '', AIOVP_FILE ) );
define( 'AIOVP_ASSETS', AIOVP_URL . '/assets' );
define( 'AIOVP_TEMPLATES', AIOVP_PATH . '/templates' );


//Include the base plugin file.
include_once AIOVP_INCLUDES . '/base.php';