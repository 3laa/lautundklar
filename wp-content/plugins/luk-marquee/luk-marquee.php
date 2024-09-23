<?php
	/**
	 * Pure Css Marquee
	 *
	 * @package   Luk_Marquee
	 * @author    Alaa Ouda
	 * @license   GPL-2.0+
	 * @copyright 2022 Alaa Ouda
	 *
	 * @wordpress-plugin
	 * Plugin Name:       LUK Marquee
	 * Plugin URI:        https://lautundklar.de/luk-marquee
	 * Description:       Pure Css Marquee.
	 * Version:           1.0.0
	 * Author:            Alaa Ouda
	 * Author URI:        https://lautundklar.de/kontakt/
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       luk-marquee
	 * Domain Path:       /languages/
	 * GitHub Plugin URI: https://github.com/LautundKlar/luk-marquee
	 * GitHub Branch:     master
	 */
	
	// If this file is called directly, abort.
	if ( ! defined( 'ABSPATH' ) ) {die;}
	
	include(plugin_dir_path(__FILE__) . 'admin/luk-marquee-admin.php');
	include(plugin_dir_path(__FILE__) . 'public/luk-marquee-public.php');
	
	
	
	function luk_marquee_load_textdomain() {
		load_plugin_textdomain( 'luk-marquee', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	add_action( 'init', 'luk_marquee_load_textdomain' );
	function plugin_activate()
	{
		//luk_marquee_register_post_type();
		flush_rewrite_rules();
	}
	register_activation_hook(__FILE__, 'plugin_activate');
	function plugin_deactivate()
	{
		flush_rewrite_rules();
	}
	register_deactivation_hook(__FILE__, 'plugin_deactivate');