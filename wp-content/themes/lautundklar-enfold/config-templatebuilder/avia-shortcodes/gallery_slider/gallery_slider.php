<?php
/**
 * Horizontal Gallery
 *
 * Creates a horizontal scrollable gallery
 */
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( ! class_exists( 'avia_sc_gallery_slider' ) )
{
	class avia_sc_gallery_slider extends aviaShortcodeTemplate
	{
		use \aviaBuilder\traits\scSlideshowUIControls;

        /**
         * @return string
         */
        public function getContentUrlRoot(): string
        {
            return get_stylesheet_directory_uri() . '/config-templatebuilder/avia-shortcodes/gallery_slider/';
        }

		/**
		 * Create the config array for the shortcode button
		 */
		protected function shortcode_insert_button()
		{
			$this->config['version']		= '1.0';
			$this->config['is_fullwidth']	= 'yes';
			$this->config['self_closing']	= 'no';
			$this->config['base_element']	= 'yes';

			$this->config['name']			= 'Gallery Slider';
			$this->config['tab']			= 'Content Elements';
			$this->config['icon']			= $this->getContentUrlRoot() . 'sc-gallery-slider.png';
			$this->config['order']			= 100;
			$this->config['target']			= 'avia-target-insert';
			$this->config['shortcode'] 		= 'av_gallery_slider';
			$this->config['tooltip']        = 'Creates a Slider Gallery';
			$this->config['preview'] 		= false;
			$this->config['drag-level'] 	= 3;
			$this->config['disabling_allowed'] = true;
			$this->config['id_name']		= 'id';
			$this->config['id_show']		= 'always';				//	we use original code - not $meta
			$this->config['alb_desc_id']	= 'alb_description';

		}


		protected function extra_assets()
		{
            $ver = Avia_Builder()->get_theme_version();
            wp_enqueue_script('avia-gallery-slider-enfold', $this->getContentUrlRoot() . "gallery-slider.js", array('avia-shortcodes'), $ver, true);
		}


		protected function popup_elements()
		{
            $this->elements = array(
                array('type' => 'tab_container', 'nodescription' => true),

                array('type' => 'tab', 'name' => 'Content', 'nodescription' => true),
                array(
                    'type' => 'template',
                    'template_id' => 'toggle_container',
                    'templates_include' => array(
                        $this->popup_key('content_entries'),
                    ),
                    'nodescription' => true
                ),
                array('type' => 'tab_close', 'nodescription' => true),

                array('type' => 'template', 'template_id' => 'element_template_selection_tab', 'args' => array('sc' => $this)),
                array('type' => 'tab_container_close', 'nodescription' => true)
            );
		}


		protected function register_dynamic_templates()
		{
            $c = array(
                array(
                    'name'		=> 'Edit Gallery',
                    'desc'		=> 'Create a new Gallery by selecting existing or uploading new images',
                    'id'		=> 'ids',
                    'type'		=> 'gallery',
                    'title'		=> 'Add/Edit Gallery',
                    'button'	=> 'Insert Images',
                    'std'		=> '',
                    'modal_class' => 'av-show-image-custom-link',
                    'lockable'	=> true
                ),

                array(
                    'name' 	=> 'Slides Per View ',
                    'desc' 	=> 'Enter the number of the Slides Per View, the last image.',
                    'id' 	=> 'slidesperview',
                    'type' 	=> 'input',
                    'std' 	=> '4',
                    'lockable'	=> true
                ),

                array(
                    'name' 	=> 'Lightbox?',
                    'desc' 	=> 'Use Lightbox',
                    'id' 	=> 'lightbox',
                    'type' 	=> 'select',
                    'std' 	=> 'auto',
                    'lockable'	=> true,
                    'subtype'	=> array(
                       'Yes'	=> true,
                       'No'     => false
                    )
                ),

                array(
                    'name' 	=> 'Hover?',
                    'desc' 	=> 'Use Hover Effect',
                    'id' 	=> 'hover',
                    'type' 	=> 'select',
                    'std' 	=> 'auto',
                    'lockable'	=> true,
                    'subtype'	=> array(
                        'Yes'	=> true,
                        'No'     => false
                    )
                ),

                array(
                    'name' 	=> 'Image Size?',
                    'desc' 	=> '',
                    'id' 	=> 'imagesize',
                    'type' 	=> 'select',
                    'std' 	=> 'auto',
                    'lockable'	=> true,
                    'subtype'	=> array(
                        'Full'	=> 'image-full',
                        'Normal'     => 'image-normal'
                    )
                ),
            );
            $template = array(
                array(
                    'type'			=> 'template',
                    'template_id'	=> 'toggle',
                    'title'			=> 'Gallery Images',
                    'content'		=> $c
                )
            );
            AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_entries' ), $template );
		}


		public function editor_element( $params )
		{
			$params = parent::editor_element( $params );
			$params['innerHtml'] .=	AviaPopupTemplates()->get_html_template( 'alb_element_fullwidth_stretch' );

			return $params;
		}

		public function shortcode_handler( $atts, $content = '', $shortcodename = '', $meta = '' )
		{
            $output = "";

            $ids = $atts['ids'];
            $slidesperview = $atts['slidesperview'];
            $lightbox = $atts['lightbox'];
            $hover = $atts['hover'];
            $imagesize = $atts['imagesize'];

            $attachments = get_posts( array(
                    'include'			=> $ids,
                    'post_status'		=> 'inherit',
                    'post_type'			=> 'attachment',
                    'post_mime_type'	=> 'image',
                    'order'				=> 'DESC',
                    'orderby'			=> 'post__in'
                )
            );


            if( ! empty( $attachments ) && is_array( $attachments ) )
            {

                $output .='<div class="luk-gallery-slider">
                            <div class="swiper gallery-slider-swiper '.$imagesize.'" data-slidesperview="'.$slidesperview.'" data-hover="'.$hover.'" data-lightbox="'.$lightbox.'">
                                <div class="swiper-wrapper">';

                foreach( $attachments as $attachment )
                {
                    $img = wp_get_attachment_image_src( $attachment->ID, 'large' );
                    $lightbox_img_src = Av_Responsive_Images()->responsive_image_src( $attachment->ID);

                    $alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
                    $alt = ! empty( $alt ) ? esc_attr( $alt ) : '';

                    $title = trim( $attachment->post_title ) ? esc_attr( $attachment->post_title ) : '';
                    $lightbox_title = $title;

                    $img_tag = "<img class='av-horizontal-gallery-img' width='{$img[1]}' height='{$img[2]}' src='{$img[0]}' title='{$title}' alt='{$alt}' />";

                    $lightbox_attr = Av_Responsive_Images()->html_attr_image_src( $lightbox_img_src, false );
                    $output .= "<div class='swiper-slide'>";

                    if ($lightbox == 1)
                        $output .= "<a {$lightbox_attr} class='luk-gallery-slider-link'  title='{$lightbox_title}' alt='{$alt}'>";

                    $output .= $img_tag;

                    if ($hover == 1)
                    $output .= '
                    <div class="luk-gallery-slider-hover">
                        <svg width="100pt" height="100pt" viewBox="0 0 100 100" >
                            <path d="m49.445 41.789c-7.1719 0-13.004 5.832-13.004 13.004s5.832 13.004 13.004 13.004c7.1719 0 13.004-5.832 13.004-13.004s-5.8359-13.004-13.004-13.004z"/>
                            <path d="m89.824 25.555h-24.297l-1.7109-7.0547c0-1.5117-1.2266-2.9453-2.7383-2.9453h-21.898c-1.5117 0-2.7383 1.4375-2.7383 2.9453l-1.7109 7.0547h-25.664c-3.7812 0-6.8438 2.1875-6.8438 5.9688v46.539c0 3.7773 3.0625 7.4922 6.8438 7.4922h80.758c3.7773 0 6.8438-3.7148 6.8438-7.4961v-46.535c0-3.7812-3.0664-5.9688-6.8438-5.9688zm-40.379 50.453c-11.715 0-21.215-9.5-21.215-21.215 0-11.715 9.5-21.215 21.215-21.215 11.719 0 21.215 9.5 21.215 21.215 0 11.715-9.4961 21.215-21.215 21.215z"/>
                        </svg>
                    </div>
                                        ';

                    if ($lightbox == 1)
                        $output .= "</a>";


                    $output .= '</div>';
                }

                $output .='     </div>
                                <div class="swiper-button swiper-button-next"></div>
                                <div class="swiper-button swiper-button-prev"></div>
                            </div>
                         </div>';
            }



            return $output;
		}




	}
}
