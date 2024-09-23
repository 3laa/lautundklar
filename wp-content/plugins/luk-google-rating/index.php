<?php
/*
Plugin Name: LUK Google Rating
Description: Checks Google Rating for given Google-PlaceID or Company Name to display rating score and add structured rating data.
Version: 1.0
Author: Christoph Kern
Text Domain: luk-google-rating
Author URI: https://lautundklar.de
*/
defined('ABSPATH') or die(' ');
add_action( 'plugins_loaded', 'wpdocs_load_textdomain' );

/**
 * Load plugin textdomain.
 */
function wpdocs_load_textdomain() {
    load_plugin_textdomain( 'luk-google-rating', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
function luk_settings()
{
    include 'includes/template/settings.php';
}

function luk_output()
{
    include 'includes/template/output.php';
}

function luk_google_menu()
{
    load_plugin_textdomain("luk-google-rating");
    add_menu_page("LUK Google Rating", "LUK Google Rating", 4, "luk-google-rating", "luk_settings");
    add_submenu_page("luk-google-rating", "LUK Google Rating", __("Query Rating / Preview","luk-google-rating"), 4, "luk_rating_output", "luk_output");
}

add_action("admin_menu", "luk_google_menu");

function luk_cron_refresh()
{
    $scriptUrl = plugins_url("luk-google-rating") . "/includes/js/functions.js";
    echo '<script>
    window.onload = function() {
        if (window.jQuery) {
            // jQuery is loaded

        } else {
            // jQuery is not loaded
            var script = document.createElement("SCRIPT");
            script.src = "https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js";
            script.type = "text/javascript";
            script.onload = function() {
                var $ = window.jQuery;
            };
            document.getElementsByTagName("head")[0].appendChild(script);
        }
    }
</script>';
    echo '<script src="' . $scriptUrl . '"></script>';
    $cName = get_option("luk_grating_company_name");
    if ($cName) {
        $cName = str_replace(" ", "+", $cName);
    }
    echo '<div style="display: none;" id="response"></div>';
    echo '<script type="text/javascript">$(function() {

            loadGoogleQuery("' . $cName . '");
        });</script>';

}

add_shortcode('luk_cron', 'luk_cron_refresh');

add_shortcode('luk_rating', 'addHtmlElement');

$addStruc = get_option("luk_grating_add_struc_to_header");
if ($addStruc == 1) {
$requestUrl = $GLOBALS["_SERVER"]["REQUEST_URI"];
    if (strpos($requestUrl, 'datenschutz') !== false) {

    }
    else if (strpos($requestUrl, 'impressum') !== false) {

    }
    else{
        add_action('wp_head', 'addJsonElement');
    }

}

function addHtmlElement()
{
    wp_enqueue_style("luk_staricon", plugins_url() . '/luk-google-rating/includes/css/fontello.css');
    $cName = get_option("luk_grating_company_name");
    $placeId = get_option("luk_grating_place_id");
    $rating_value = get_option("luk_grating_rating_value");
    $rating_amount = get_option("luk_grating_rating_amount");
    $reviews_link = "https://search.google.com/local/reviews?placeid=" . $placeId;
    $queryName = str_replace(" ", "+", $cName);
    $search_link = "https://www.google.com/search?ei=0A0BXcPUAoORsAeW1qrIAQ&q=" . $queryName;
    $stars = getStars($rating_value);
    $bewertung = __("Ratings", "luk-google-rating");
    $rezensionen = __("Reviews","luk-google-rating");
    $output = "";
    $output .= '<div id="luk-google-rating">';
    $output .= '<div><span class="google">Google</span> <span>' . $bewertung . '</span></div>';
    $output .= '<div><a target="_blank" href="' . $search_link . '">' . $cName . ' ';
    $output .= '<span>' . $rating_value . ' ' . $stars . '</span></a></div>';
    $output .= '<div><a class="google" target="_blank" href="' . $reviews_link . '">' . $rating_amount . ' Google ' . $rezensionen . '</a></div>';
    $output .= '</div>';
    return $output;
}


function getStars($rating)
{
    $rating = str_replace(",", ".", $rating);
    $rating = floatval($rating);
    $rating = round($rating);
    $output = "";
    for ($i = 0; $i < 5; $i++) {
        if ($rating - $i > 0) {
            $output .= '<span style="color: #f4bc42;" class="icon-star"></span>';
        } else {
            $output .= '<span style="color: #000000;" class="icon-star"></span>';
        }
    }
    return $output;
}

add_shortcode('luk_struct_rating_data', 'addJsonElement');

function addJsonElement()
{
    $cName = get_option("luk_grating_company_name");
    $rating_value = get_option("luk_grating_rating_value");
    $rating_amount = get_option("luk_grating_rating_amount");
    $placeId = get_option("luk_grating_place_id");
    $json = ' <script type=application/ld+json>
        {
        "@context": "http://schema.org/",
        "@type": "Product",
        "id": "https://search.google.com/local/reviews?placeid='.$placeId.'",
        "name": "'.$cName.'",
        "sku": {
            "@type": "Text"
        },
        "mpn": {
            "@type": "Text"
        },
        "brand": {
            "@type": "Brand"
        },
        "review": {
            "@type": "Review",
            "author": {
                "@type": "Person",
                "name": "anonym"
            }
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "'.$rating_value.'",
            "bestRating": "5",
            "ratingCount": "'.$rating_amount.'"

        }
    }</script>';
    echo $json;
    return $json;
}