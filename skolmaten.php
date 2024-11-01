<?php
/*
* Plugin Name: Skolmaten
* Plugin URI: http://www.snillrik.se/skolmaten/
* Description: Skolmaten hämtar en meny ifrån skolmaten.se
* Version: 1.7.0
* Author: Mattias Kallio
* Author URI: http://www.snillrik.se
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
* Requires at least: 4.5
* Tested up to: 6.7
*/

define("SNILLRIK_SKOLMATEN_PLUGIN_PATH",plugin_dir_path( __FILE__ ));
define("SNILLRIK_SKOLMATEN_PLUGIN_URL",plugin_dir_url( __FILE__ ));

require_once( SNILLRIK_SKOLMATEN_PLUGIN_PATH.'settings.php' );
require_once( SNILLRIK_SKOLMATEN_PLUGIN_PATH.'classes/shortcodes.php' );
require_once( SNILLRIK_SKOLMATEN_PLUGIN_PATH.'classes/widgets.php' );
require_once( SNILLRIK_SKOLMATEN_PLUGIN_PATH.'classes/skolmaten_api.php' );

function skolmaten_styles(){
	wp_register_style( 'skolmaten', SNILLRIK_SKOLMATEN_PLUGIN_URL.'css/skolmaten.css' );
}
add_action('wp_enqueue_scripts', 'skolmaten_styles');

function skolmaten_styles_admin() {
	wp_enqueue_style( 'skolmaten-admin', SNILLRIK_SKOLMATEN_PLUGIN_URL.'css/admin.css' );
	wp_enqueue_style('snillrik-admin-settings', SNILLRIK_SKOLMATEN_PLUGIN_URL . 'css/settings-page.css');
}
add_action( 'admin_enqueue_scripts', 'skolmaten_styles_admin' );

//For previous versions, using transients instead now.
function skolmaten_uninstall () {
	global $wpdb;
	$table_name = $wpdb->prefix . "snillrik_skolmaten";

	//Delete any options thats stored also?
	delete_option('skolmaten_adresses');
	delete_option('skolmaten_texten');

	$wpdb->query("DROP TABLE IF EXISTS $table_name");
}

register_deactivation_hook(__FILE__, 'skolmaten_uninstall');

?>
