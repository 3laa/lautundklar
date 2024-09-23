<?php

/*
Plugin Name: LUK Elements
Plugin URI:
Description: An extension for Visual Composer that extends the builder with custom elements
Author: lautundklar (Christoph Kern)
Version: 1.0.0
Author URI: https://lautundklar.de/
*/


// If this file is called directly, abort

if ( ! defined( 'ABSPATH' ) ) {
    die ('Silly human what are you doing here');
}


// Before VC Init

add_action( 'vc_before_init', 'luk_vc_before_init_actions' );

function luk_vc_before_init_actions() {

// Require new custom testimonial Element

    include( plugin_dir_path( __FILE__ ) . 'includes/vc-testimonial-element.php');
    wp_register_script('luk-testimonials-carousel',  '/wp-content/plugins/luk-elements/js/luk-testimonials-carousel.js', array('jquery', 'jquery-carouFredSel'), false, true);
}


// Link directory stylesheet

function luk_community_directory_scripts() {
    wp_enqueue_style( 'wpc_community_directory_stylesheet',  plugin_dir_url( __FILE__ ) . 'css/testimonial.css' );
}
add_action( 'wp_enqueue_scripts', 'luk_community_directory_scripts' );