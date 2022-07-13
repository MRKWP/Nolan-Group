<?php
/**
 * Plugin Name:     Nolan Group
 * Plugin URI:      https://www.nolan-group.com
 * Description:     Nolan Group Core Functions
 * Author:          MRK WP
 * Author URI:      https://www.mrkwp.com
 * Text Domain:     mrkwp-website
 * Domain Path:     /languages
 * Version:         1.0.8
 *
 * @package nolan-group
 */

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'No Access!' );

// Require once the Composer Autoload.
if ( file_exists( dirname( __FILE__ ) . '/lib/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/lib/autoload.php';
}

/**
 * The code that runs during plugin activation.
 *
 * @return void
 */
function activate_nolan_group_plugin() {
	Nolan_Group\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_nolan_group_plugin' );

/**
 * The code that runs during plugin deactivation.
 *
 * @return void
 */
function deactivate_nolan_group_plugin() {
	Nolan_Group\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_nolan_group_plugin' );

/**
 * Initialize all the core classes of the plugin.
 */
if ( class_exists( 'Nolan_Group\\Init' ) ) {
	Nolan_Group\Init::register_services();
}