<?php
/**
 * Horizontal Gallery
 *
 * Creates a horizontal scrollable gallery
 */
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( ! class_exists( 'avia_sc_gallery_fade' ) )
{
	class avia_sc_gallery_fade extends aviaShortcodeTemplate
	{
		use \aviaBuilder\traits\scSlideshowUIControls;

        /**
         * @return string
         */
        public function getContentUrlRoot(): string
        {
            return get_stylesheet_directory_uri() . '/config-templatebuilder/avia-shortcodes/gallery_fade/';
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

			$this->config['name']			= 'Gallery Fade';
			$this->config['tab']			= 'Content Elements';
			$this->config['icon']			= $this->getContentUrlRoot() . 'sc-gallery-fade.png';
			$this->config['order']			= 100;
			$this->config['target']			= 'avia-target-insert';
			$this->config['shortcode'] 		= 'av_gallery_fade';
			$this->config['tooltip']        = 'Creates a Slider Gallery';
			$this->config['preview'] 		= false;
			$this->config['drag-level'] 	= 3;
			$this->config['disabling_allowed'] = true;
			$this->config['id_name']		= 'id';
			$this->config['id_show']		= 'always';				//	we use original code - not $meta
			$this->config['alb_desc_id']	= 'alb_description';

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
                    'name' 	=> 'Delay?',
                    'desc' 	=> '',
                    'id' 	=> 'delay',
                    'type' 	=> 'select',
                    'std' 	=> '1',
                    'lockable'	=> true,
                    'subtype'	=> array(
                        '1'	=> '1',
                        '2' => '2',
                        '3' => '3'
                    )
                ),
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
            $delay = $atts['delay'];
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
                $output .='<div class="luk-gallery-fade delay-'.$delay.'">';
                $counter = 1;
                foreach( $attachments as $attachment )
                {
                    $img = "url('".wp_get_attachment_image_src( $attachment->ID, 'large' )[0]."')";
                    $output .= '<div class="gallery-fade-slide" style="background-image: '.$img.'"></div>';
                    $counter++;
                }
                $output .='</div>';
            }
            return $output;
		}
	}
}
