<?php
if (!defined('ABSPATH')) {
    exit;
}    // Exit if accessed directly

if (!class_exists('avia_sc_swiper_slider')) {
    class avia_sc_swiper_slider extends aviaShortcodeTemplate
    {


        /**
         * @return string
         */
        public function getContentUrlRoot(): string
        {
            return get_stylesheet_directory_uri() . '/config-templatebuilder/avia-shortcodes/swiper_slider/';
        }


        protected function shortcode_insert_button()
        {
            $this->config['version'] = '1.0';
            $this->config['is_fullwidth'] = 'yes';
            $this->config['self_closing'] = 'yes';
            $this->config['base_element'] = 'yes';

            $this->config['name'] = 'Swiper Slider';
            $this->config['tab'] = 'Content Elements';
            $this->config['icon'] = $this->getContentUrlRoot() . 'sc-swiper-slider.png';
            $this->config['order'] = 100;
            $this->config['target'] = 'avia-target-insert';
            $this->config['shortcode'] = 'av_swiper_slider';
            $this->config['tooltip'] = 'Creates a Swiper Slider';
            $this->config['disabling_allowed'] = true;
            $this->config['id_name'] = 'id';
            $this->config['id_show'] = 'yes';
            $this->config['alb_desc_id'] = 'alb_description';
            $this->config['linkpickers'] = array('link');
        }

        protected function extra_assets()
        {
            $ver = Avia_Builder()->get_theme_version();

            //load css
            //wp_enqueue_style('avia-swiper-slider', $this->getContentUrlRoot() . "swiper-bundle.min.css", array('avia-layout'), $ver);

            //load js
            //wp_enqueue_script('avia-swiper-slider', $this->getContentUrlRoot() . "swiper-bundle.min.js", array('avia-shortcodes'), $ver, true);
            wp_enqueue_script('avia-swiper-slider-enfold', $this->getContentUrlRoot() . "swiper.js", array('avia-shortcodes'), $ver, true);
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
                        $this->popup_key('content_slides'),
                        $this->popup_key('content_filter'),
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
                    'name' => __('Which Entries?', 'avia_framework'),
                    'desc' => __('Select which entries should be displayed by selecting a taxonomy', 'avia_framework'),
                    'id' => 'link',
                    'type' => 'linkpicker',
                    'fetchTMPL' => true,
                    'multiple' => 6,
                    'std' => 'category',
                    'lockable' => true,
                    'subtype' => array(__('Display Entries from:', 'avia_framework') => 'taxonomy')
                )
            );
            if (current_theme_supports('add_avia_builder_post_type_option')) {
                $element = array(
                    'type' => 'template',
                    'template_id' => 'avia_builder_post_type_option',
                    'lockable' => true,
                );
                array_unshift($c, $element);
            }
            $template = array(
                array(
                    'type' => 'template',
                    'template_id' => 'toggle',
                    'title' => __('Entries', 'avia_framework'),
                    'content' => $c
                ),
            );
            AviaPopupTemplates()->register_dynamic_template($this->popup_key('content_slides'), $template);


            $c = array(
                array(
                    'type' => 'template',
                    'template_id' => 'wc_options_non_products',
                    'lockable' => true
                ),


                array(
                    'name' => __('Entry Number', 'avia_framework'),
                    'desc' => __('How many items should be displayed?', 'avia_framework'),
                    'id' => 'items',
                    'type' => 'select',
                    'std' => '9',
                    'lockable' => true,
                    'subtype' => AviaHtmlHelper::number_array(1, 100, 1, array('All' => '-1'))
                ),


            );
            $template = array(
                array(
                    'type' => 'template',
                    'template_id' => 'toggle',
                    'title' => __('Filters', 'avia_framework'),
                    'content' => $c
                ),
            );
            AviaPopupTemplates()->register_dynamic_template($this->popup_key('content_filter'), $template);

        }

        public function editor_element($params)
        {
            $params = parent::editor_element($params);
            $params['content'] = null; //remove to allow content elements

            return $params;
        }

        public function query_args($params = array())
        {
            if (isset($params['link'])) {
                $params['link'] = explode(',', $params['link'], 2);
                $params['taxonomy'] = $params['link'][0];

                if (isset($params['link'][1])) {
                    $params['categories'] = $params['link'][1];
                }
            }

            $terms 	= explode( ',', $params['categories'] );

            $args = array(
                'post_type' => array('portfolio','post'),
                'posts_per_page' => $params['items'],
                'tax_query' => array(
                    array(
                        'taxonomy' => $params['taxonomy'],
                        'field' => 'id',
                        'terms' => $terms,
                        'operator' => 'IN'
                    )
                )
            );
            return $args;
        }


        public function shortcode_handler($atts, $content = '', $shortcodename = '', $meta = '')
        {
            global $post;

            $output = '
            <div class="swiper-slider">
                <div class="swiper">
                    <div class="swiper-wrapper">
                ';
            $query = new WP_Query($this->query_args($atts));
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();

                    $the_id = $post->ID;
                    $title =  $post->post_title;
                    $link = get_post_meta($the_id, '_portfolio_custom_link', true) != '' ? get_post_meta($the_id, '_portfolio_custom_link_url', true) : get_permalink($the_id);
                    $thumbnail = get_the_post_thumbnail_url($the_id);
                    $output .= '<div class="swiper-slide">
                                    <a href="'.$link.'" class="swiper-portfolio-item" alt="'.$title.'">
                                        <div class="item-image">
                                            <img src="'.$thumbnail.'" alt="'.$title.'" />
                                        </div>
                                        <div class="item-overlay">
                                             <h4>'.$title.'</h4>
                                        </div>
                                    </a>
                                </div>';
                }
                wp_reset_postdata();
            }
            $output .= '
                    </div>
                    <div class="swiper-button swiper-button-prev"></div>
                    <div class="swiper-button swiper-button-next"></div>
                </div>
            </div>
            ';

            return $output;
        }
    }
}