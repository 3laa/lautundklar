<?php
if( ! defined( 'ABSPATH' ) )  { die(); }

// copyright entfernen
add_filter('kriesi_backlink', function () {return '';});

//debugging info entfernen:
if (!function_exists('avia_debugging_info')) {
    function avia_debugging_info(){}
}


/* apple-touch-icon */
function add_apple_touch_icons()
{
    ?>
    <link rel="icon" type="image/x-icon" href="/wp-content/uploads/icons/favicon.ico">
    <link rel="apple-touch-icon" href="/wp-content/uploads/icons/apple-touch-icon.png"/>
    <link rel="apple-touch-icon" sizes="57x57" href="/wp-content/uploads/icons/apple-touch-icon-57x57.png"/>
    <link rel="apple-touch-icon" sizes="72x72" href="/wp-content/uploads/icons/apple-touch-icon-72x72.png"/>
    <link rel="apple-touch-icon" sizes="76x76" href="/wp-content/uploads/icons/apple-touch-icon-76x76.png"/>
    <link rel="apple-touch-icon" sizes="114x114" href="/wp-content/uploads/icons/apple-touch-icon-114x114.png"/>
    <link rel="apple-touch-icon" sizes="120x120" href="/wp-content/uploads/icons/apple-touch-icon-120x120.png"/>
    <link rel="apple-touch-icon" sizes="144x144" href="/wp-content/uploads/icons/apple-touch-icon-144x144.png"/>
    <link rel="apple-touch-icon" sizes="152x152" href="/wp-content/uploads/icons/apple-touch-icon-152x152.png"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/wp-content/uploads/icons/apple-touch-icon-180x180.png"/>
    <?php
}

add_action('wp_head', 'add_apple_touch_icons');
/* apple-touch-icon */


//google_webfonts  entfernen:
add_filter("avf_output_google_webfonts_script", function () {return false;});

//Add Local Fonts
function local_fonts() {
    wp_enqueue_style( 'local_fonts', get_stylesheet_directory_uri() . '/fonts.css' );
}
add_action( 'wp_enqueue_scripts', 'local_fonts' );


function pd_avia_load_shortcodes($paths)
{
    $template_url = get_stylesheet_directory();
    array_unshift($paths, $template_url . '/config-templatebuilder/avia-shortcodes/');
    return $paths;
}

add_filter('avia_load_shortcodes', 'pd_avia_load_shortcodes', 15, 1);



// add swiper
function add_swiper_script()
{
    wp_enqueue_style('swiper-css', get_stylesheet_directory_uri() . '/vendor/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', get_stylesheet_directory_uri() . '/vendor/swiper/swiper-bundle.min.js', array(), null, true);
    wp_enqueue_script('mmenu-js', get_stylesheet_directory_uri() . '/vendor/mmenu-js/mmenu.js', array(), null, true);
    wp_enqueue_style('mmenu-js', get_stylesheet_directory_uri() . '/vendor/mmenu-js/mmenu.css');
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/js/main.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'add_swiper_script', 100);

// add fontawesome
function add_fontawesome()
{
    wp_enqueue_style('fontawesome-css', get_stylesheet_directory_uri() . '/vendor/fontawesome/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'add_fontawesome');

function generate_menu_tree($menu_id, $parent_id = 0) {
    $menu_items = wp_get_nav_menu_items($menu_id);
    $menu_html = '';
    foreach ($menu_items as $menu_item) {
        if ($menu_item->title != 'Home')
        {
            if ($menu_item->menu_item_parent == $parent_id) {
                $current_class = ($menu_item->url == get_permalink()) ? 'Selected' : '';
                $menu_html .= '<li data-id="'.$menu_item->ID.'" class="' . $current_class . '">';
                $menu_html .= '<a href="' . $menu_item->url . '">' . $menu_item->title . '</a>';
                $submenu = generate_menu_tree($menu_id, $menu_item->ID);
                if (!empty($submenu)) {
                    $menu_html .= '<ul>' . $submenu . '</ul>';
                }
                $menu_html .= '</li>';
            }
        }
    }
    return $menu_html;
}

function mmenujs()
{
    $menu_html = generate_menu_tree(165);
    echo '<nav id="menu"><ul>';
    echo $menu_html;
    echo '</ul></nav>';

}
add_action('ava_after_footer', 'mmenujs');

add_filter( 'wp_nav_menu_items', 'avia_append_burger_menu', 9998, 2 );
add_filter( 'avf_fallback_menu_items', 'avia_append_burger_menu', 9998, 2 );
function avia_append_burger_menu ( $items , $args )
{
    $items .= '<li class="menu-item-avia-special">
        <a href="#menu" id="mmenujs">
            <span class="av-hamburger av-hamburger--spin av-js-hamburger">
                <span class="av-hamburger-box">
                    <span class="av-hamburger-inner"></span>
                    <strong>Menü</strong>
                </span>
            </span>
            <span class="avia_hidden_link_text">Menü</span>
        </a>
    </li>';

    return $items;
}
