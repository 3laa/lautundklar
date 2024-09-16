<?php
	if( ! defined( 'ABSPATH' ) )	{ die(); }

	/*
	* Please do not modify this file on your child theme to add scripts to Head section.
	* Refer to this post - https://kriesi.at/documentation/enfold/add-custom-js-or-php-script/#add-a-script-to-head-section and use functions.php file instead
	*/

	global $avia_config;

	$lightbox_option = avia_get_option( 'lightbox_active' );
	$avia_config['use_standard_lightbox'] = empty( $lightbox_option ) || ( 'lightbox_active' == $lightbox_option ) ? 'lightbox_active' : 'disabled';

	/**
	 * Allow to overwrite the option setting for using the standard lightbox
	 * Make sure to return 'disabled' to deactivate the standard lightbox - all checks are done against this string
	 *
	 * @added_by Günter
	 * @since 4.2.6
	 * @param string $use_standard_lightbox				'lightbox_active' | 'disabled'
	 * @return string									'lightbox_active' | 'disabled'
	 */
	$avia_config['use_standard_lightbox'] = apply_filters( 'avf_use_standard_lightbox', $avia_config['use_standard_lightbox'] );


	$style = $avia_config['box_class'];
	$responsive = avia_get_option( 'responsive_active' ) != 'disabled' ? 'responsive' : 'fixed_layout';
	$blank = isset( $avia_config['template'] ) ? $avia_config['template'] : '';
	$preloader = avia_get_option( 'preloader' ) == 'preloader' ? 'av-preloader-active av-preloader-enabled' : 'av-preloader-disabled';

	$html_classes = array();
	$body_classes = array();

	$html_classes[] = "html_{$style}";
	$html_classes[] = $responsive;
	$html_classes[] = $preloader;
	$html_classes[] = avia_header_class_filter( avia_header_class_string() );
	$html_classes[] = 'av-no-preview'; /*required for live previews*/

	if( $avia_config['use_standard_lightbox'] != 'disabled' )
	{
		$html_classes[] = 'av-default-lightbox';

		/**
		 * Hide scrollbars when lightbox is opened to avoid scroll of lightbox and body
		 * (might cause flicker when scrollbars are removed)
		 *
		 * @since 5.1
		 * @param boolean no_scroll
		 * @return false|mixed
		 */
		if( false !== apply_filters( 'avf_default_lightbox_no_scroll', false ) )
		{
			$html_classes[] = 'av-default-lightbox-no-scroll';
		}
	}
	else
	{
		$html_classes[] = 'av-custom-lightbox';
	}

	$body_classes[] = $style;
	$body_classes[] = $blank;
	$body_classes[] = avia_get_option( 'sidebar_styling' );

	if( 'disable_mobile_css_animations' == avia_get_option( 'disable_mobile_css_animations' ) )
	{
		$body_classes[] = 'avia-mobile-no-animations';
	}

	/**
	 * Get footer stylings and post overrides
	 */
	$the_id = avia_get_the_id(); //use avia get the id instead of default get id. prevents notice on 404 pages
	$body_layout = avia_get_option( 'color-body_style' );
	$footer_options = avia_get_option( 'display_widgets_socket', 'all' );
	$footer_behavior = avia_get_option( 'footer_behavior' );
	$footer_media = avia_get_option( 'curtains_media' );

	$footer_post = get_post_meta( $the_id, 'footer', true );
	$footer_behavior_post = get_post_meta( $the_id, 'footer_behavior', true );

	/**
	 * Reset individual page override to defaults if widget or page settings are different (user might have changed theme options)
	 * (if user wants a page as footer he must select this in main options - on individual page it's only possible to hide the page)
	 */
	if( false !== strpos( $footer_options, 'page' ) )
	{
		/**
		 * User selected a page as footer in main options
		 */
		if( ! in_array( $footer_post, array( 'page_in_footer_socket', 'page_in_footer', 'nofooterarea' ) ) )
		{
			$footer_post = '';
		}
	}
	else
	{
		/**
		 * User selected a widget based footer in main options
		 */
		if( in_array( $footer_post, array( 'page_in_footer_socket', 'page_in_footer' ) ) )
		{
			$footer_post = '';
		}
	}

	$footer_option = ! empty( $footer_post ) ? $footer_post : $footer_options;

	switch ( $footer_behavior_post )
	{
		case 'scroll':
			$footer_behavior = '';
			break;
		case 'curtain_footer':
			$footer_behavior = 'curtain_footer';
			break;
		default:
			break;
	}

	if( 'stretched' != $body_layout )
	{
		$footer_behavior = '';
		$footer_media = '';
	}
	else
	{
		if( empty( $footer_media ) )
		{
			$footer_media = '70';
		}
	}

	$avia_config['footer_option'] = $footer_option;
	$avia_config['footer_behavior'] = $footer_behavior;
	$avia_config['footer_media'] = $footer_media;

	/**
	 * If title attribute is missing for an image default lightbox displays the alt attribute
	 *
	 * @since 4.7.6.2
	 * @param bool
	 * @return false|mixed			anything except false will activate this feature
	 */
	$body_classes[] = false !== apply_filters( 'avf_lightbox_show_alt_text', false ) ? 'avia-mfp-show-alt-text' : '';

	/**
	 * Allows to alter default settings Enfold-> Main Menu -> General -> Menu Items for Desktop
	 * @since 4.4.2
	 */
	$is_burger_menu = apply_filters( 'avf_burger_menu_active', avia_is_burger_menu(), 'header' );
	$html_classes[] = $is_burger_menu ? 'html_burger_menu_active' : 'html_text_menu_active';

	if( ! $is_burger_menu )
	{
		$handling = avia_get_option( 'header_mobile_device_handling' );
		$html_classes[] = 'mobile_switch_portrait' != $handling ? 'av-mobile-menu-switch-default' : 'av-mobile-menu-switch-portrait';
	}

	/**
	 * Add additional custom body classes
	 * e.g. to disable default image hover effect add av-disable-avia-hover-effect
	 *
	 * @since 4.4.2
	 * @param string $custom_body_classes
	 * @return string
	 */
	$body_classes[] = apply_filters( 'avf_custom_body_classes', '' );

	/**
	 * @since 4.2.3 we support columns in rtl order (before they were ltr only). To be backward comp. with old sites use this filter.
	 */
	$body_classes[] = 'yes' == apply_filters( 'avf_rtl_column_support', 'yes' ) ? 'rtl_columns' : '';

	/**
	 * @since 4.8.6.3
	 */
	$body_classes[] = 'curtain_footer' == $avia_config['footer_behavior'] ? 'av-curtain-footer' : '';
	$body_classes[] = is_numeric( $footer_media ) || empty( $footer_media ) ? 'av-curtain-numeric' : "av-curtain-screen {$footer_media}";


	$html_classes = implode( ' ', array_unique( array_filter( $html_classes ) ) );



?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo $html_classes; ?>">
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php

/*
 * outputs a rel=follow or nofollow tag to circumvent google duplicate content for archives
 * located in framework/php/function-set-avia-frontend.php
 */
 if( function_exists( 'avia_set_follow' ) )
 {
	 echo avia_set_follow();
 }

?>


<!-- mobile setting -->
<?php

$meta_viewport = ( strpos( $responsive, 'responsive' ) !== false ) ?  '<meta name="viewport" content="width=device-width, initial-scale=1">' : '';

/**
 * @since 4.7.6.4
 * @param string
 * @return string
 */
echo apply_filters( 'avf_header_meta_viewport', $meta_viewport );

?>


<!-- Scripts/CSS and wp_head hook -->
<?php
/* Always have wp_head() just before the closing </head>
 * tag of your theme, or you will break many plugins, which
 * generally use this hook to add elements to <head> such
 * as styles, scripts, and meta tags.
 */

wp_head();

?>

</head>

<?php


$body_classes[] = $avia_config['font_stack'];
$body_classes = implode( ' ', array_unique( array_filter( $body_classes ) ) );

?>
<body id="top" <?php body_class( $body_classes ); avia_markup_helper( array( 'context' => 'body' ) ); ?>>
	<?php

	/**
	 * WP 5.2 add a new function - stay backwards compatible with older WP versions and support plugins that use this hook
	 * https://make.wordpress.org/themes/2019/03/29/addition-of-new-wp_body_open-hook/
	 *
	 * @since 4.5.6
	 */
	if( function_exists( 'wp_body_open' ) )
	{
		wp_body_open();
	}
	else
	{
		do_action( 'wp_body_open' );
	}

	do_action( 'ava_after_body_opening_tag' );

	if( 'av-preloader-active av-preloader-enabled' === $preloader )
	{
		echo avia_preload_screen();
	}

	?>

	<div id='wrap_all'>

	<?php
	if( ! $blank ) //blank templates dont display header nor footer
	{
		//fetch the template file that holds the main menu, located in includes/helper-menu-main.php
		get_template_part( 'includes/helper', 'main-menu' );
	}
	?>

	<div id='main' class='all_colors' data-scroll-offset='<?php echo avia_header_setting( 'header_scroll_offset' ); ?>'>

	<?php

		if( isset( $avia_config['temp_logo_container'] ) )
		{
			echo $avia_config['temp_logo_container'];
		}

		do_action( 'ava_after_main_container' );
