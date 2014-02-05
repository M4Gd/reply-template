<?php
/**
 * A WordPress plugin that helps bbpress moderators to use reply templates while posting reply in bbpress forum.
 *
 * @package   axiom
 * @author    averta
 * @license   GPL-2.0+
 * @copyright 2014 copyright averta
 *
 * @wordpress-plugin
 * Plugin Name:       Reply Templates
 * Plugin URI:        https://github.com/M4Gd/reply-template
 * Description:       A WordPress plugin that helps bbpress moderators to use reply templates while posting reply in bbpress forum.
 * Version:           1.0.0
 * Author:            averta
 * Author URI:        http://averta.net
 * Text Domain:       reply-template
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/M4Gd/reply-template
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-reply-template.php' );

register_activation_hook  ( __FILE__, array( 'ReplyTemplate', 'activate'   ) );
register_deactivation_hook( __FILE__, array( 'ReplyTemplate', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'ReplyTemplate', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-reply-template-admin.php' );
	add_action( 'plugins_loaded', array( 'ReplyTemplateAdmin', 'get_instance' ) );

}
