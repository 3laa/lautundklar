<?php
	$marqueeID = $shortcodeTags['id'];
	$marquee = get_post($marqueeID);
    
    $marquee_content_style = '';
    $marquee_content_style2 = '';
	
	$hover_pause = get_post_meta($marqueeID, 'luk_marquee_hover_pause', true);
	$hover_pause ? $hover_pause = 'pause="true"' : $hover_pause = 'pause="false"';
	
	$speed = get_post_meta($marqueeID, 'luk_marquee_speed', true);
	if (!$speed) $speed = '50';
	
	$fontsize = get_post_meta($marqueeID, 'luk_marquee_fontsize', true);
	if ($fontsize) $marquee_content_style2 .= "font-size:$fontsize;";
	
	$fontfamily = get_post_meta($marqueeID, 'luk_marquee_fontfamily', true);
	if ($fontfamily) $marquee_content_style2 .= "font-family:$fontfamily;";
	
	$fontweight = get_post_meta($marqueeID, 'luk_marquee_fontweight', true);
	if ($fontweight) $marquee_content_style2 .= "font-weight:$fontweight;";
	
	$color = get_post_meta($marqueeID, 'luk_marquee_color', true);
	if ($color) $marquee_content_style2 .= "color:$color;";
	
	$hover_color = get_post_meta($marqueeID, 'luk_marquee_hover_color', true);
	if (!$hover_color) $hover_color = 'inherit';
	
	$background = get_post_meta($marqueeID, 'luk_marquee_background', true);
	if ($background) $marquee_content_style .= "background:$background;";
	
	$direction = get_post_meta($marqueeID, 'luk_marquee_direction', true);
	if (!$direction) $direction = 'ltr';
	
	$texttransform = get_post_meta($marqueeID, 'luk_marquee_texttransform', true);
	if ($texttransform) $marquee_content_style2 .= "text-transform:$texttransform;";
	
	$spacebetween = get_post_meta($marqueeID, 'luk_marquee_spacebetween', true);
	if (!$spacebetween) $spacebetween = '50px';
	
	$padding_top = get_post_meta($marqueeID, 'luk_marquee_padding_top', true);
	if ($padding_top) $marquee_content_style .= "padding-top:$padding_top;";
	
	$padding_bottom = get_post_meta($marqueeID, 'luk_marquee_padding_bottom', true);
	if ($padding_bottom) $marquee_content_style .= "padding-bottom:$padding_bottom;";
	
	$extra_class = get_post_meta($marqueeID, 'luk_marquee_extra_class', true);
	
	$items = '';
	for ($counter = 1; $counter <= 5; $counter++) {
		$item_title = get_post_meta($marqueeID, 'luk_marquee_item_title_' . $counter, true);
		$item_url = get_post_meta($marqueeID, 'luk_marquee_item_url_' . $counter, true);
		if ($item_title)
        {
			if ($item_url)
            {
				$items .= '<a href="' . $item_url . '" target="_self" data-color="'.$hover_color.'" style="'.$marquee_content_style2.'"><span class="luk_marquee-text-' . $counter . '">' . $item_title . '</span></a>';
			} else
            {
				$items .= '<span class="luk_marquee-text-' . $counter . '" style="'.$marquee_content_style2.'">' . $item_title . '</span>';
			}
		}
	}
?>
<div class="luk_marquee-wrapper <?php echo $extra_class ?>" >
    <div class="luk_marquee-content" style="<?php echo $marquee_content_style ?>" <?php echo $hover_pause ?>>
			<?php
				for ($counter = 1; $counter <= 5; $counter++)
				{
					echo '<div class="luk_marquee-content-inner marquee-content-inner"  style="animation: animation-luk_marquee-'.$direction.' '.$speed.'s linear infinite;">' .
						'<div class="luk_marquee-text" style="padding-right:'.$spacebetween.'">' . $items . '</div>' .
						'</div>';
				}
			?>
    </div>
</div>
