<?php

/**
 * Adds new shortcode "luk-testitmonial" and registers it to
 * the WPBakery Visual Composer plugin
 *
 */


// If this file is called directly, abort

if ( ! defined( 'ABSPATH' ) ) {
    die ('Silly human what are you doing here');
}


if ( ! class_exists( 'vcLukTestitmonial' ) ) {

    class vcLukTestitmonial {


        /**
         * Main constructor
         *
         */
        public function __construct() {

            // Registers the shortcode in WordPress
            add_shortcode( 'luk-testitmonial', array( 'vcLukTestitmonial', 'output' ) );

            // Map shortcode to Visual Composer
            if ( function_exists( 'vc_lean_map' ) ) {
                vc_lean_map( 'luk-testitmonial', array( 'vcLukTestitmonial', 'map' ) );
            }

        }


        /**
         * Map shortcode to VC
         *
         * This is an array of all your settings which become the shortcode attributes ($atts)
         * for the output.
         *
         */
        public static function map() {
            return array(
                'name'        => esc_html__( 'lukTestimonial', 'text-domain' ),
                'description' => esc_html__( 'Add new lukTestimonial', 'text-domain' ),
                'base'        => 'vc_infobox',
                'category' => __('LUK Elements', 'text-domain'),
                'icon' => plugin_dir_path( __FILE__ ) . 'assets/img/note.png',
                'params' => array_merge(array(
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Style', 'thegem'),
                        'param_name' => 'style',
                        'value' => array(
                            __('Style 1', 'thegem') => 'style1',
                            __('Style 2', 'thegem') => 'style2'
                        ),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Background color', 'thegem'),
                        'param_name' => 'background_color',
                        'group' => __('Colors', 'thegem'),
                        'dependency' => array(
                            'element' => 'style',
                            'value' => array('style2')
                        ),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Name color', 'thegem'),
                        'param_name' => 'name_color',
                        'group' => __('Colors', 'thegem'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Company color', 'thegem'),
                        'param_name' => 'company_color',
                        'group' => __('Colors', 'thegem'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Position color', 'thegem'),
                        'param_name' => 'position_color',
                        'group' => __('Colors', 'thegem'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Text color', 'thegem'),
                        'param_name' => 'text_color',
                        'group' => __('Colors', 'thegem'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'heading' => __('Quote color', 'thegem'),
                        'param_name' => 'quote_color',
                        'group' => __('Colors', 'thegem'),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Testimonials Sets', 'thegem'),
                        'param_name' => 'set',
                        'value' => thegem_vc_get_terms('thegem_testimonials_sets'),
                        'group' =>__('Select Testimonials Sets', 'thegem'),
                        'edit_field_class' => 'thegem-terms-checkboxes'
                    ),
                    array(
                        'type' => 'dropdown',
                        'heading' => __('Image Size', 'thegem'),
                        'param_name' => 'image_size',
                        'value' => array(
                            __('Small', 'thegem') => 'size-small',
                            __('Medium', 'thegem') => 'size-medium',
                            __('Large', 'thegem') => 'size-large',
                            __('Xlarge', 'thegem') => 'size-xlarge'
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'heading' => __('Fullwidth', 'thegem'),
                        'param_name' => 'fullwidth',
                        'value' => array(__('Yes', 'thegem') => '1')
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __('Autoscroll', 'thegem'),
                        'description' => __('Speed in Milliseconds, example - 5000', 'thegem'),
                        'param_name' => 'autoscroll',
                    ),
                )),
            );
        }


        /**
         * Shortcode output
         *
         */
        public static function output( $atts, $content = null ) {

            extract(shortcode_atts(array(
                'set' => '',
                'fullwidth' => '',
                'style' => 'style1',
                'image_size' => 'size-small',
                'autoscroll' => 0,
                'background_color' => '',
                'name_color' => '',
                'company_color' => '',
                'position_color' => '',
                'quote_color' => '',
                'text_color' => ''
            ), $atts, '_testimonials'));

            ob_start();
            luk_testimonialss(array('testimonials_set' => $set, 'fullwidth' => $fullwidth, 'style' => $style, 'image_size' => $image_size, 'autoscroll' => $autoscroll, 'background_color' => $background_color, 'name_color' => $name_color, 'company_color' => $company_color, 'position_color' => $position_color, 'text_color' => $text_color, 'quote_color' => $quote_color));

            $return_html = trim(preg_replace('/\s\s+/', ' ', ob_get_clean()));
            return $return_html;

        }



    }

}
new vcLukTestitmonial;

function luk_testimonialss($params) {
    $params = array_merge(array('testimonials_set' => '', 'fullwidth' => '', 'autoscroll' => 0), $params);
    $args = array(
        'post_type' => 'thegem_testimonial',
        'orderby' => 'menu_order ID',
        'order' => 'ASC',
        'posts_per_page' => -1,
    );
    if($params['testimonials_set'] != '') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'thegem_testimonials_sets',
                'field' => 'slug',
                'terms' => explode(',', $params['testimonials_set'])
            )
        );
    }
    $testimonials_items = new WP_Query($args);

    if($testimonials_items->have_posts()) {
        wp_enqueue_style('thegem-testimonials');
        wp_enqueue_script('luk-testimonials-carousel');
        echo '<div class="preloader"><div class="preloader-spin"></div></div>';
        echo '<div class="'.$params['image_size'].' '.$params['style'].' gem-testimonials'.( $params['fullwidth'] ? ' fullwidth-block' : '' ).'"'.( intval($params['autoscroll']) ? ' data-autoscroll="'.intval($params['autoscroll']).'"' : '' ).' style=background-color:'.$params['background_color'].'>';
        while($testimonials_items->have_posts()) {
            $testimonials_items->the_post();

            include(locate_template('luk-testimonial-carousel.php'));
        }

        echo '<div class="testimonials_svg"><svg style="fill: '.$params['background_color'].'" width="100" height="50"><path d="M 0,-1 Q 45,5 50,50 Q 55,5 100,-1" /></svg></div>';
        echo '<div id="sliderpager"></div>';
        echo '</div>';

    }
    wp_reset_postdata();
}