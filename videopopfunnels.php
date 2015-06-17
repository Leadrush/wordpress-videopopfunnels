<?php
/**
 * @package   VideoPopFunnels
 * @author    ReallySuccessful Ltd. <support@reallysuccessful.com>
 * @license   GPL-2.0+
 * @link      http://reallysuccessfulsupport.com/
 * @copyright 2015 ReallySuccessful Ltd.
 *
 * @wordpress-plugin
 * Plugin Name:			VideoPopFunnels
 * Plugin URI:        	http://videopopfunnels.com
 * Description:       	Load your embed code easily in Wordpress
 * Version:           	1.0.0
 * Author:       		ReallySuccessful Ltd.
 * Author URI:       	http://reallysuccessfulsupport.com/
 * Text Domain:       	videopopfunnels
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: 	https://github.com/leadrush/wordpress-videopopfunnels
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/


require_once( plugin_dir_path( __FILE__ ) . 'public/class-videopopfunnels.php' );

register_activation_hook( __FILE__, array( 'VideoPopFunnels', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'VideoPopFunnels', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'VideoPopFunnels', 'get_instance' ) );


if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-videopopfunnels-admin.php' );
	add_action( 'plugins_loaded', array( 'VideoPopFunnels_Admin', 'get_instance' ) );

}
