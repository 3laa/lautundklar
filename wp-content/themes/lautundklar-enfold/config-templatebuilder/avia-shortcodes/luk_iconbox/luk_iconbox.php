<?php
/**
 * Luk Icon Box
 *
 * Shortcode which creates a content block with icon to the left or above
 */
if( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( ! class_exists( 'avia_sc_luk_iconbox' ) )
{
	class avia_sc_luk_iconbox extends aviaShortcodeTemplate
	{
        /**
         * @return string
         */
        public function getContentUrlRoot(): string
        {
            return get_stylesheet_directory_uri() . '/config-templatebuilder/avia-shortcodes/luk_iconbox/';
        }


		protected function shortcode_insert_button()
		{
			$this->config['version']		= '1.0';
			$this->config['self_closing']	= 'no';
			$this->config['base_element']	= 'yes';

			$this->config['name']			= 'Luk Icon Box';
			$this->config['tab']			= 'Content Elements';
			$this->config['icon']			= $this->getContentUrlRoot() . 'sc-luk-iconbox.png';
			$this->config['order']			= 110;
			$this->config['target']			= 'avia-target-insert';
			$this->config['shortcode'] 		= 'av_luk_iconbox';
			$this->config['tooltip'] 	    = 'Creates a content block with icon';
			$this->config['preview']		= 1;
			$this->config['disabling_allowed'] = true;
			$this->config['id_name']		= 'id';
			$this->config['id_show']		= 'yes';
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
                        $this->popup_key('content_iconbox'),
                        $this->popup_key('advanced_link'),
                    ),
                    'nodescription' => true
                ),

                array('type' => 'tab_close', 'nodescription' => true),
            );
		}

		protected function register_dynamic_templates()
		{
            $c = array(
                array(
                    'name' 	=> 'Title',
                    'desc' 	=> 'Add an IconBox title here',
                    'id' 	=> 'title',
                    'type' 	=> 'input',
                    'std' 	=> 'IconBox Title',
                    'lockable'	=> true
                ),

                array(
                    'name' 	=> 'Text',
                    'desc' 	=> 'Add some content for this IconBox',
                    'id' 	=> 'text',
                    'type' 	=> 'tiny_mce',
                    'lockable'	=> true
                ),

                array(
                    'name' 	=> 'SVG',
                    'desc' 	=> 'Add SVG Text for this IconBox',
                    'id' 	=> 'svg',
                    'type' 	=> 'textarea',
                    'lockable'	=> true
                ),

                array(
                    'type'			=> 'template',
                    'template_id'	=> 'linkpicker_toggle',
                    'name'			=> 'Box Link?',
                    'desc'			=> 'Do you want to apply a link to the Box?',
                    'lockable'		=> true,
                    'subtypes'		=> array( 'no', 'manually', 'single', 'taxonomy' ),
                    'no_toggle'		=> true
                ),

                array(
                    'name' 	=> 'Icon Postion?',
                    'desc' 	=> '',
                    'id' 	=> 'iconpostion',
                    'type' 	=> 'select',
                    'std' 	=> 'iconpostion-right',
                    'lockable'	=> true,
                    'subtype'	=> array(
                        'Right'	=> 'iconpostion-right',
                        'Left'     => 'iconpostion-left'
                    )
                ),

                array(
                    'name' 	=> 'Extra Class',
                    'desc' 	=> 'Add Class for this IconBox',
                    'id' 	=> 'extraclass',
                    'type' 	=> 'input',
                    'lockable'	=> true
                ),
            );
            AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_iconbox' ), $c );

		}

		public function editor_element( $params )
		{
            $default = array();
            $locked = array();
            $attr = $params['args'];
            Avia_Element_Templates()->set_locked_attributes( $attr, $this, $this->config['shortcode'], $default, $locked );

            extract( av_backend_icon(  array( 'args' => $attr ) ) );

            $inner  = '<div class="luk-iconbox iconpostion-left references-icon">';
            $inner .= '<div class="iconbox-wrapper">';
            $inner .= '<div class="iconbox-header">';
            $inner .= '<div class="iconbox-title"><h4 '. $this->update_option_lockable( 'title', $locked ) .'>'. html_entity_decode( $attr['title'] ) .'</h4></div>';
            $inner .= '<div class="iconbox-svg" '. $this->update_option_lockable( 'svg', $locked ) .'>'. html_entity_decode( $attr['svg'] ) .'</div>';
            $inner .= '</div>';
            $inner .= '</div>';
            $inner .= '</div>';

            $params['innerHtml'] = $inner;
            return $params;
		}



		public function shortcode_handler( $atts, $content = '', $shortcodename = '', $meta = '' )
		{
            $title = $atts['title'];
            $text = $atts['text'];
            $svg = $atts['svg'];
            $iconpostion = $atts['iconpostion'];
            $extraclass = $atts['extraclass'];
            $link = AviaHelper::get_url( $atts['link'] );
            //$linktarget = AviaHelper::get_link_target( $atts['linktarget'] );
            $wrapperStartTag = 'div';
            $wrapperEndTag = 'div';
            if( ! empty( $link ) ) {
                $wrapperStartTag = 'a href="'.$link.'"';
                $wrapperEndTag = 'a';
            }
            $output = '
            <div class="luk-iconbox '.$iconpostion.' '.$extraclass.'">
                <'.$wrapperStartTag.' class="iconbox-wrapper">
                    <div class="iconbox-header">
                        <div class="iconbox-title">
                            <h4>'.$title.'</h4>
                        </div>
                        <div class="iconbox-svg">'.$svg.'</div>
                    </div>
                    <div class="iconbox-content">'.$text.'</div>
                </'.$wrapperEndTag.'>
            </div>
            ';
            return $output;
		}

	}
}
