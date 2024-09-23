<?php
	if ( ! defined( 'ABSPATH' ) ) {die;}
	
	include(plugin_dir_path(__FILE__) . 'PostTypeAdminGenerator.php');
	include(plugin_dir_path(__FILE__) . 'MetaBoxGenerator.php');
	
	
	// Generate Post Type
	$lukMarqueePostType = new PostTypeAdminGenerator('luk_marquee', 'LUK Marquee', 'Marquee', 'Marquees', 'luk-marquee', 'luk_marquee');
	//$lukMarqueePostType->args['labels']['menu_name'] = 'New Name';
	//$lukMarqueePostType->args['menu_icon'] = 'dashicons-welcome-write-blog';
	$columns = array(
		'title' => __('Title', 'luk-marquee'),
		'shortcode' => __('Shortcode', 'luk-marquee'),
		'date' => __('Date', 'luk-marquee'),
	);
	$lukMarqueePostType->setCustomColumns($columns);
	$lukMarqueePostType->run(); //add_action('init', [ $lukMarqueePostType, 'registerPostType' ]);
	// Generate Post Type
	
	
	// Add Side Meta Box
	$sideMetaBox = new MetaBoxGenerator('luk_marquee_', 'luk_marquee_meta_box_side', 'Options', 'luk_marquee', 'luk-marquee');
	$sideMetaBox->setMetaBoxContext('side');
	
	$sideMetaBox->fields['shortcode'] = ['id' => 'shortcode', 'label' => __('Shortcode', 'luk-marquee'), 'type'=>'shortcode', 'shortcode-tag'=>'luk_marquee'];
	$sideMetaBox->fields['hover_pause'] = ['id' => 'hover_pause', 'label' => __('Pause on Hover', 'luk-marquee'), 'type'=>'checkbox'];
	$sideMetaBox->fields['speed'] = ['id' => 'speed', 'label' => __('Speed', 'luk-marquee')];
	$sideMetaBox->fields['fontsize'] = ['id' => 'fontsize', 'label' => __('Font Size', 'luk-marquee')];
	$sideMetaBox->fields['fontfamily'] = ['id' => 'fontfamily', 'label' => __('Font Family', 'luk-marquee'), 'class' => 'custom-class'];
	$sideMetaBox->fields['fontweight'] = ['id' => 'fontweight', 'label' => __('Font Weight', 'luk-marquee')];
	$sideMetaBox->fields['color'] = ['id' => 'color', 'label' => __('color', 'luk-marquee'), 'attr'=>'class="rwp-color-picker"'];
	$sideMetaBox->fields['hover_color'] = ['id' => 'hover_color', 'label' => __('Hover Color', 'luk-marquee'), 'attr'=>'class="rwp-color-picker"'];
	$sideMetaBox->fields['background'] = ['id' => 'background', 'label' => __('Background', 'luk-marquee'), 'attr'=>'class="rwp-color-picker"'];
	$sideMetaBox->fields['texttransform'] = [
		'id' => 'texttransform',
		'label' => __('Text Transform', 'luk-marquee'),
		'type' => 'select',
		'options' => [
			'inherit'=>'Inherit',
			'capitalize'=>'Capitalize',
			'lowercase'=>'Lowercase',
			'uppercase'=>'Uppercase'
		]
	];
	$sideMetaBox->fields['direction'] = [
		'id' => 'direction',
		'label' => __('Direction', 'luk-marquee'),
		'type' => 'select',
		'options' => [
			'ltr'=>'LTR',
			'rtl'=>'RTL'
		]
	];
	$sideMetaBox->fields['spacebetween'] = ['id' => 'spacebetween', 'label' => __('Space Between', 'luk-marquee')];
	$sideMetaBox->fields['padding_top'] = ['id' => 'padding_top', 'label' => __('Padding Top', 'luk-marquee')];
	$sideMetaBox->fields['padding_bottom'] = ['id' => 'padding_bottom', 'label' => __('Padding Bottom', 'luk-marquee')];
	$sideMetaBox->fields['extra_class'] = ['id' => 'extra_class', 'label' => __('Extra CSS Class', 'luk-marquee')];
	
	$sideMetaBox->run();
	// Add Side Meta Box
	
	
	// Add Normal Meta Box
	$normalMetaBox = new MetaBoxGenerator('luk_marquee_', 'luk_marquee_meta_box_normal', 'Items', 'luk_marquee', 'luk-marquee');
	for ($item = 1; $item <= 5; $item++)
	{
		$normalMetaBox->fields["item_headline_$item"] = ['type'=>'html', 'html'=>"<h1>".__('Item', 'luk-marquee')." $item</h1>"];
		$normalMetaBox->fields["item_title_$item"] = ['id' => "item_title_$item", 'label' => __("Title", 'luk-marquee')];
		$normalMetaBox->fields["item_url_$item"] = ['id' => "item_url_$item", 'label' => __("Url", 'luk-marquee')];
		$normalMetaBox->fields["item_divider_$item"] = ['type'=>'html', 'html'=>"<div class='divider'></div>"];
	}
	$normalMetaBox->run();
	// Add Normal Meta Box
	
	
	
	