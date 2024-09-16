<?php

/**
 * Creates a clients element with milestones that consist of a date, icon or image and text
 * Can be displayed in a vertical order or as a horizontal carousel slider
 *
 * @author tinabillinger
 * @since 4.3
 */

// Don't load directly
if( ! defined( 'ABSPATH' ) ) {   die('-1');   }

if( ! class_exists( 'avia_sc_clients' ) )
{
    class avia_sc_clients extends aviaShortcodeTemplate
    {


        public function getContentUrlRoot(): string
        {
            return get_stylesheet_directory_uri() . '/config-templatebuilder/avia-shortcodes/clients/';
        }


        protected function shortcode_insert_button()
        {
            $this->config['version']		= '1.0';
            $this->config['self_closing']	= 'no';
            $this->config['base_element']	= 'yes';

            $this->config['name']			= 'Clients';
            $this->config['tab']			= 'Content Elements';
            $this->config['icon']			= $this->getContentUrlRoot() . 'sc-clients.png';
            $this->config['order']			= 110;
            $this->config['target']			= 'avia-target-insert';
            $this->config['shortcode']		= 'av_clients';
            $this->config['shortcode_nested'] = array( 'av_clients_item' );
            $this->config['tooltip']		= 'Creates a clients';
            $this->config['preview']		= 'large';
            $this->config['disabling_allowed'] = true;
            $this->config['id_name']		= 'id';
            $this->config['id_show']		= 'yes';
            $this->config['alb_desc_id']	= 'alb_description';
            $this->config['name_item']		= 'clients Item';
            $this->config['tooltip_item']	= 'A clients Element Item';
        }

        protected function popup_elements()
        {
            $this->elements = array(
                array('type' => 'tab_container', 'nodescription' => true),

                array('type' => 'tab', 'name' => 'Content', 'nodescription' => true),
                array(
                    'type'			=> 'template',
                    'template_id'	=> $this->popup_key( 'content_clients' )
                ),
                array('type' => 'tab_close', 'nodescription' => true),
            );
        }

        /**
         * Create and register templates for easier maintainance
         *
         * @since 4.6.4
         */
        protected function register_dynamic_templates()
        {
            $this->register_modal_group_templates();

            $c = array(
                
                array(
                    'name'			=> 'Add/Edit clients',
                    'desc'			=> 'Here you can add, remove and edit the clients.',
                    'type'			=> 'modal_group',
                    'id'			=> 'content',
                    'modal_title'	=> 'Edit clients',
                    'editable_item'	=> true,
                    'lockable'		=> true,
                    'tmpl_set_default'	=> false,
                    'std'			=> array(
                        array(
                            'title'	=> 'clients 1',
                            'content'	=> 'Enter content here',
                        ),
                    ),
                    'subelements'	=> $this->create_modal()
                )
            );

            AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_clients' ), $c );

        }

        /**
         * Creates the modal popup for a single entry
         *
         * @since 4.6.4
         * @return array
         */
        protected function create_modal()
        {
            $elements = array(

                array('type' 	=> 'tab_container','nodescription' => true),

                array(
                    'type' 	=> 'tab',
                    'name'  => 'Content',
                    'nodescription' => true
                ),

                array(
                    'type'			=> 'template',
                    'template_id'	=> 'toggle_container',
                    'templates_include'	=> array(
                        $this->popup_key( 'modal_content_desc' ),
                    ),
                    'nodescription' => true
                ),

                array(
                    'type' 	=> 'tab_close',
                    'nodescription' => true
                ),

            );

            return $elements;
        }

        /**
         * Register all templates for the modal group popup
         *
         * @since 4.6.4
         */
        protected function register_modal_group_templates()
        {
            /**
             * Content Tab
             * ===========
             */
            $c = array(

                array(
                    'name'	=> __( 'Title', 'avia_framework' ),
                    'desc'	=> __( 'Enter the clients Title here. Use filter avf_customize_heading_settings to change the html tag.', 'avia_framework' ),
                    'id'	=> 'title',
                    'type'	=> 'input',
                    'std'	=> 'clients Title',
                    'lockable'	=> true
                ),

                array(
                    'name' =>'Choose Image',
                    'desc' => 'Either upload a new, or choose an existing image from your media library',
                    'id' => 'src',
                    'type' => 'image',
                    'title' => 'Insert Image',
                    'button' => 'Insert',
                    'std' => AviaBuilder::$path['imagesURL'] . 'placeholder.jpg',
                    'lockable' => true,
                    'locked' => array('src', 'attachment', 'attachment_size')
                ),

                array(
                    'name' =>'Choose Logo',
                    'desc' => 'Either upload a new, or choose an existing image from your media library',
                    'id' => 'logo',
                    'type' => 'image',
                    'title' => 'Insert Logo',
                    'button' => 'Insert',
                    'std' => AviaBuilder::$path['imagesURL'] . 'placeholder.jpg',
                    'lockable' => true,
                    'locked' => array('src', 'attachment', 'attachment_size')
                ),

                array(
                    'type' => 'template',
                    'template_id' => 'linkpicker_toggle',
                    'name' => 'Link?',
                    'desc' => 'Do you want to apply a link to the Hero?',
                    'lockable' => true,
                    'subtypes' => array('no', 'manually', 'single', 'taxonomy'),
                    'no_toggle' => true
                )
            );

            $template = array(
                array(
                    'type'			=> 'template',
                    'template_id'	=> 'toggle',
                    'title'			=> __( 'clients Description', 'avia_framework' ),
                    'content'		=> $c
                ),
            );

            AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'modal_content_desc' ), $template );

        }

        /**
         * Return a config array for the nested shortcde
         *
         * @since 4.6.4
         * @param string $nested_shortcode
         * @return array
         */
        protected function get_nested_developer_elements( $nested_shortcode )
        {
            $config = array();

            if( 'av_clients_item' == $nested_shortcode )
            {
                $config['id_name'] = 'custom_id';
                $config['id_show'] = 'yes';
            }

            return $config;
        }


        /**
         * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
         * Works in the same way as Editor Element
         *
         * @param array $params			holds the default values for $content and $args.
         * @return array				usually holds an innerHtml key that holds item specific markup.
         */
        public function editor_sub_element( $params )
        {
            $default = array();
            $locked = array();
            $attr = $params['args'];
            Avia_Element_Templates()->set_locked_attributes( $attr, $this, $this->config['shortcode_nested'][0], $default, $locked );

            extract( av_backend_icon( array( 'args' => $attr ) ) ); // creates $font and $display_char if the icon was passed as param 'icon' and the font as 'font'

            $params['innerHtml'] = '';
            $params['innerHtml'] .= "<div class='avia_title_container' data-update_element_template='yes'>";
            $params['innerHtml'] .=		'<span ' . $this->update_option_lockable( 'title', $locked ) . ">: {$attr['title']}</span>";
            $params['innerHtml'] .= '</div>';

            return $params;
        }


        public function has_global_attributes()
        {
            return true;
        }

        public function shortcode_handler( $atts, $content = '', $shortcodename = '', $meta = '' )
        {
            $clients_items_html = ShortcodeHelper::avia_remove_autop( $content, true );

            return '<div class="luk-clients" id="' . $atts['av_uid'] . '">
                        '.$clients_items_html.'
                    </div>';
        }


        public function av_clients_item( $atts, $content = '', $shortcodename = '' )
        {
            $title = $atts['title'];
            $link = AviaHelper::get_url( $atts['link'] );
            $image = $this->av_clients_get_img_tag($atts);
            $logo = $this->av_clients_get_img_tag($atts, 'logo');
            $tag = 'div';
            $linkTag = '';
            if ($link!='') {
                $tag = 'a';
                $linkTag = 'href="'.$link.'"';
            }
            return '<'.$tag.' '.$linkTag.' class="client" id="'.$atts['av_uid'].'">
                        <div class="client-wrapper">
                            <div class="clients-image">
                                '.$image.'
                            </div>
                            <div class="clients-logo">
                                '.$logo.'
                            </div>
                            <div class="clients-title">
                                '.$title.'
                            </div>
                        </div>
                    </'.$tag.'>';
        }


        public function av_clients_get_img_tag($atts,$type='src')
        {
            $attachment = $atts['attachment'];
            $attachment_size = $atts['attachment_size'];
            $src_original = $atts[$type];

            $posts = get_posts( array(
                    'include'			=> $attachment,
                    'post_status'		=> 'inherit',
                    'post_type'			=> 'attachment',
                    'post_mime_type'	=> 'image',
                    'order'				=> 'ASC',
                    'orderby'			=> 'post__in'
                )
            );
            $attachment_entry = $posts[0];

            $alt_attr = esc_attr( trim(get_post_meta( $attachment_entry->ID, '_wp_attachment_image_alt', true )));
            $title_attr = esc_attr( trim($attachment_entry->post_title));
            $src = wp_get_attachment_image_src( $attachment_entry->ID, $attachment_size );
            $img_h = ! empty( $src[2] ) ? $src[2] : '';
            $img_w = ! empty( $src[1] ) ? $src[1] : '';
            $hw = '';
            if( ! empty( $img_h ) )
            {
                $hw .= ' height="' . $img_h . '"';
            }

            if( ! empty( $img_w ) )
            {
                $hw .= ' width="' . $img_w . '"';
            }

            $markup_img = avia_markup_helper( array( 'context' => 'image', 'echo' => false, 'custom_markup' => '' ) );
            return "<img src='{$src_original}' alt='{$alt_attr}' title='{$title_attr}'  />";
        }

    }
}

