<?php
  var_dump($post);
    $marqueeID = $shortcodeTags['id'];
	$marquee = get_post($marqueeID);
	$hover_pause = get_post_meta($marqueeID, 'luk_marquee_hover_pause', true);
	if (!$hover_pause) $hover_pause = '0';
	
	$speed = get_post_meta($marqueeID, 'luk_marquee_speed', true);
	if (!$speed) $speed = '50';
	
	$fontsize = get_post_meta($marqueeID, 'luk_marquee_fontsize', true);
	if (!$fontsize) $fontsize = '150px';
	
	$fontfamily = get_post_meta($marqueeID, 'luk_marquee_fontfamily', true);
	if (!$fontfamily) $fontfamily = 'inherit';
	
	$fontweight = get_post_meta($marqueeID, 'luk_marquee_fontweight', true);
	if (!$fontweight) $fontweight = 'inherit';
	
	$color = get_post_meta($marqueeID, 'luk_marquee_color', true);
	if (!$color) $color = 'inherit';
	
	$hover_color = get_post_meta($marqueeID, 'luk_marquee_hover_color', true);
	if (!$hover_color) $hover_color = 'inherit';
	
	$background = get_post_meta($marqueeID, 'luk_marquee_background', true);
	if (!$background) $background = 'transparent';
	
	$direction = get_post_meta($marqueeID, 'luk_marquee_direction', true);
	if (!$direction) $direction = 'ltr';
	
	$texttransform = get_post_meta($marqueeID, 'luk_marquee_texttransform', true);
	if (!$texttransform) $texttransform = 'inherit';
	
	$spacebetween = get_post_meta($marqueeID, 'luk_marquee_spacebetween', true);
	if (!$spacebetween) $spacebetween = '50px';
	
	$padding_top = get_post_meta($marqueeID, 'luk_marquee_padding_top', true);
	if (!$padding_top) $padding_top = '50px';
	
	$padding_bottom = get_post_meta($marqueeID, 'luk_marquee_padding_bottom', true);
	if (!$padding_bottom) $padding_bottom = '50px';
    ?>
<style type="text/css">
    body {padding-top: 500px; background: orange !important;}
    .luk_marquee-content-<?php echo $marqueeID ?> {
        font-family: <?php echo $fontfamily ?>;
        font-size: <?php echo $fontsize ?>;
        line-height: 1.5;
        font-weight: <?php echo $fontweight ?>;
        text-transform: <?php echo $texttransform ?>;
        color: <?php echo $color ?>;
        background: <?php echo $background ?>;
        display: flex;
        white-space: nowrap;
        overflow: hidden;
        padding-top: <?php echo $padding_top ?>;
        padding-bottom: <?php echo $padding_bottom ?>;
        transition: all .35s ease-in-out;
    }
    .luk_marquee-content-<?php echo $marqueeID ?> a {
        color: <?php echo $color ?>;
        text-decoration: none;
    }
    .luk_marquee-content-<?php echo $marqueeID ?> a:hover {
        text-decoration: underline;
        color: <?php echo $hover_color ?>;
    }
    .luk_marquee-content-<?php echo $marqueeID ?> .luk_marquee-content-inner {
        animation: animation-luk_marquee-<?php echo $direction ?> <?php echo $speed ?>s linear infinite;
    }
    <?php if ($hover_pause == 1) {?>
    .luk_marquee-content-<?php echo $marqueeID ?>:hover .luk_marquee-content-inner {
        animation-play-state: paused;
    }
    <?php } ?>
    .luk_marquee-content-<?php echo $marqueeID ?> .luk_marquee-text {
        display: flex;
        overflow: hidden;
    }
    .luk_marquee-content-<?php echo $marqueeID ?> .luk_marquee-text span {
        padding-right: <?php echo $spacebetween ?>;
    }
</style>