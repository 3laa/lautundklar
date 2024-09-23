<?php
defined('ABSPATH') or die("");
//Get Field-Values if the already exist in DB
$cName = get_option("luk_grating_company_name");
$placeId = get_option("luk_grating_place_id");
$apiKey = get_option("luk_grating_api");
$rating_value = get_option("luk_grating_rating_value");
$rating_amount = get_option("luk_grating_rating_amount");
function deleteOptions(){
    delete_option("luk_grating_rating_value");
    delete_option("luk_grating_rating_amount");
}
?>
<html>
<META HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
<head>
    <style>
        * {
            box-sizing: border-box;
        }
        body{
            max-width:none !important;
            max-width:initial !important;
        }
        #response{
            display:none;
        }
        input[type=text], select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        label {
            padding: 12px 12px 12px 0;
            display: inline-block;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        .container {
            border-radius: 5px;
            background-color: #f2f2f2;
            padding: 20px;
        }

        .col-25 {
            float: left;
            width: 25%;
            margin-top: 6px;
        }

        .col-75 {
            float: left;
            width: 75%;
            margin-top: 6px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Responsive layout - when the screen is less than 600px wide, make the two columns stack on top of each other instead of next to each other */
        @media screen and (max-width: 600px) {
            .col-25, .col-75, input[type=submit] {
                width: 100%;
                margin-top: 0;
            }
        }


        /* File Selector CSS */


        .js .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .inputfile + label {
            max-width: 80%;
            font-size: 1.25rem;
            /* 20px */
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            display: inline-block;
            overflow: hidden;
            padding: 0.625rem 1.25rem;
            /* 10px 20px */
        }

        .no-js .inputfile + label {
            display: none;
        }

        .inputfile:focus + label,
        .inputfile.has-focus + label {
            outline: 1px dotted #000;
            outline: -webkit-focus-ring-color auto 5px;
        }

        .inputfile + label * {
            /* pointer-events: none; */
            /* in case of FastClick lib use */
        }

        .inputfile + label svg {
            width: 1em;
            height: 1em;
            vertical-align: middle;
            fill: currentColor;
            margin-top: -0.25em;
            /* 4px */
            margin-right: 0.25em;
            /* 4px */
        }


        /* style 6 */

        .inputfile-6 + label {
            color: #000;
        }

        .inputfile-6 + label {
            border: 1px solid #8c8c8c;
            background-color: #fff;
            padding: 0;
        }

        .inputfile-6:focus + label,
        .inputfile-6.has-focus + label,
        .inputfile-6 + label:hover {
            border-color: #8c8c8c;
        }

        .inputfile-6 + label span,
        .inputfile-6 + label strong {
            padding: 0.625rem 1.25rem;
            /* 10px 20px */
        }

        .inputfile-6 + label span {
            width: 200px;
            min-height: 2em;
            display: inline-block;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            vertical-align: top;
        }

        .inputfile-6 + label strong {
            height: 100%;
            color: #000;
            background-color: #ccc;
            display: inline-block;
        }

        .inputfile-6:focus + label strong,
        .inputfile-6.has-focus + label strong,
        .inputfile-6 + label:hover strong {
            background-color: #8c8c8c;
        }

        @media screen and (max-width: 50em) {
            .inputfile-6 + label strong {
                display: block;
            }
        }



    </style>


</head>
<body>


    <div class="container">
        <p><?php _e("To insert the Rating-Block use the shortcode: [luk_rating]", "luk-google-rating");?></p>
        <div class="row">
            <div class="col-25">
                <label for="cName"><?php _e("Company", "luk-google-rating");?></label>
            </div>
            <div class="col-75">
                <input required type="text" id="cName" name="cName" placeholder="<?php _e("Company Name", "luk-google-rating");?>"
                       value="<?php if($cName)echo $cName; ?>" class="form-control login-field" disabled>
            </div>
        </div>
        <div class="row" >
            <div class="col-25">
                <label for="placeid"><?php _e("Google-Place-ID", "luk-google-rating"); ?></label>
            </div>
            <div class="col-75">
                <input type="text" id="placeid" name="placeid" placeholder="<?php _e("Company-Place-ID", "luk-google-rating");?>"
                       value="<?php if($placeId)echo $placeId; ?>" class="form-control login-field" disabled>
            </div>
        </div>

        <div class="row">
            <div class="col-25">
                <label for="apiKey"><?php _e("Google-API", "luk-google-rating");?></label>
            </div>
            <div class="col-75">
                <input type="text" id="apiKey" name="apiKey" placeholder="<?php _e("Google-API-Key", "luk-google-rating");?>"
                       value="<?php if($apiKey)echo $apiKey; ?>" class="form-control login-field" disabled>
            </div>
        </div>


        <div class="row" style="margin-top: -600px;">
            <div class="col-25">
                <label for="ratingValue"><?php _e("Current Rating", "luk-google-rating");?></label>
            </div>
            <div class="col-75">
                <input required type="text" id="ratingValue" name="ratingValue" placeholder="<?php _e("Refresh Rating to display Values", "luk-google-rating");?>"
                       value="<?php if($rating_value)echo $rating_value." / 5"; ?>" class="form-control login-field" disabled>
            </div>
        </div>
        <div class="row" style="margin-top: -600px;">
            <div class="col-25">
                <label for="ratingAmount"><?php _e("Amount of Reviews", "luk-google-rating");?></label>
            </div>
            <div class="col-75">
                <input required type="text" id="ratingAmount" name="ratingAmount" placeholder="<?php _e("Refresh Rating to display Values", "luk-google-rating");?>"
                       value="<?php if($rating_amount)echo $rating_amount; ?>" class="form-control login-field" disabled>
            </div>
        </div>




        <div class="row">
            <button type="button" id="getRating"><?php _e("Refresh Rating", "luk-google-rating");?></button>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="ratingAmount"><?php _e("Rating-Preview", "luk-google-rating");?></label>
            </div>
            <div class="col-75">
                <div id="rating_preview"><?php
                    if($rating_value){
                        echo do_shortcode("[luk_rating]");
                    }
                    else{
                        echo _e("Refresh Rating to display Preview", "luk-google-rating");
                    }
                    ?>
                </div>
            </div>

        </div>

        <div class="row">
            <div id="response"></div>
        </div>

</div>

<script src="../wp-content/plugins/luk-google-rating/includes/js/functions.js"></script>
<script>
    window.onload = function() {
        if (window.jQuery) {
            // jQuery is loaded

        } else {
            // jQuery is not loaded
            var script = document.createElement("SCRIPT");
            script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js';
            script.type = 'text/javascript';
            script.onload = function() {
                var $ = window.jQuery;
            };
            document.getElementsByTagName("head")[0].appendChild(script);
        }
    }
</script>

</body>
</html>
