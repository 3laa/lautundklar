<?php
	if ( ! defined( 'ABSPATH' ) ) {die;}
	
	include(plugin_dir_path(__FILE__) . 'EnqueueScripts.php');
	include(plugin_dir_path(__FILE__) . 'PostTypeWPGenerator.php');
	
	
	$enqueueScripts = new EnqueueScripts(999);
	$enqueueScripts->addStyles('wp', 'wp-luk-marquee', plugin_dir_url(__FILE__) . 'assets/css/wp-luk-marquee.css');
	$enqueueScripts->addScripts('wp', 'wp-luk-marquee', plugin_dir_url(__FILE__) . 'assets/js/wp-luk-marquee.js', array( 'jquery' ), '1.0.0',true);
	$enqueueScripts->addStyles('admin', 'admin-luk-marquee', plugin_dir_url(__FILE__) . '../admin/assets/css/admin-luk-marquee.css');
	$enqueueScripts->addScripts('admin', 'wp-luk-marquee', plugin_dir_url(__FILE__) . '../admin/assets/js/admin-luk-marquee.js', array( 'jquery' ), '1.0.0',true);
	$enqueueScripts->run();
	
	$lukMarqueeWP = new PostTypeWPGenerator('luk_marquee', 'luk_marquee');
	$lukMarqueeWP->run();