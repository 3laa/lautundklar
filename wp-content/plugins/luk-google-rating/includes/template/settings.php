<?php
defined('ABSPATH') or die("");
load_plugin_textdomain("luk-google-rating");

//Get Field-Values if the already exist in DB
$cName = get_option("luk_grating_company_name");
$placeId = get_option("luk_grating_place_id");
$apiKey = get_option("luk_grating_api");
$addStruc = get_option("luk_grating_add_struc_to_header");

function deleteOptions(){
    delete_option("luk_grating_company_name");
    delete_option("luk_grating_place_id");
    delete_option("luk_grating_add_struc_to_header");
}

if(isset($_POST["cName"])){ // Check for form submission
    $cName=  trim($_POST["cName"]);
    if ($cName !== ''){ //Check if input not empty
        if(!add_option("luk_grating_company_name",$cName)){ //create entry
            update_option("luk_grating_company_name",$cName); //if entry already exixts update the value
        }
    }

    $placeId=  trim($_POST["placeid"]);
    if ($placeId !== ''){
        if(!add_option("luk_grating_place_id",$placeId)){
            update_option("luk_grating_place_id",$placeId);
        }
    }

    $apiKey=  trim($_POST["apiKey"]);
    if ($apiKey !== ''){
        if(!add_option("luk_grating_api",$apiKey)){
            update_option("luk_grating_api",$apiKey);
        }

    }
    $addStruc=  trim($_POST["struct_head"]);
    if ($addStruc == 'on'){
        if(!add_option("luk_grating_add_struc_to_header",1)){
            update_option("luk_grating_add_struc_to_header",1);
        }
    }
    else{
        if(!add_option("luk_grating_add_struc_to_header",0)){
            update_option("luk_grating_add_struc_to_header",0);
        }
    }
}

?>
<html>
<META HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
<head>
    <style>
        * {
            box-sizing: border-box;
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
            float: left;
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

        .head {

            margin: 1em 0 0.5em 0;
            color: #343434;
            font-weight: normal;
            font-family: 'Ultra', sans-serif;
            font-size: 36px;
            line-height: 42px;
            text-transform: uppercase;
            text-shadow: 0 2px white, 0 3px #777;

        }


        /* Start css for API key  */
        .box {
            width: 40%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.2);
            padding: 35px;
            border: 2px solid #fff;
            border-radius: 20px/50px;
            background-clip: padding-box;
            text-align: center;
        }

        .button {
            font-size: 1em;
            padding: 10px;
            color: #000;
            border: 2px solid #06D85F;
            border-radius: 20px/50px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease-out;
        }

        .button:hover {
            background: #06D85F;
        }

        .overlay {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            transition: opacity 500ms;
            visibility: hidden;
            opacity: 0;
        }

        .overlay:target {
            visibility: visible;
            opacity: 1;
        }

        .popup {
            margin: 70px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            width: 30%;
            position: relative;
            transition: all 5s ease-in-out;
        }

        .popup h2 {
            margin-top: 0;
            color: #333;
            font-family: Tahoma, Arial, sans-serif;
        }

        .popup .close {
            position: absolute;
            top: 20px;
            right: 30px;
            transition: all 200ms;
            font-size: 30px;
            font-weight: bold;
            text-decoration: none;
            color: #333;
        }

        .popup .close:hover {
            color: #06D85F;
        }

        .popup .content {
            max-height: 30%;
            overflow: auto;
        }

        @media screen and (max-width: 700px) {
            .box {
                width: 70%;
            }

            .popup {
                width: 70%;
            }
        }
        /*  Google map css */

        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 380px;
        }

        /* Optional: Makes the sample page fill the window. */

        .controls {
            background-color: #fff;
            border-radius: 2px;
            border: 1px solid transparent;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            box-sizing: border-box;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            height: 29px;
            margin-left: 17px;
            margin-top: 10px;
            outline: none;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;

        }

        .controls:focus {
            border-color: #4d90fe;
        }

        .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }
        input#struct_head {
            margin-top: 12px;
            margin-left: 2px;
        }
    </style>
    <script type="text/javascript">

        // This sample uses the Place Autocomplete widget to allow the user to search
        // for and select a place. The sample then displays an info window containing
        // the place ID and other information about the place that the user has
        // selected.

        // This example requires the Places library. Include the libraries=places
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 48.5693, lng: 13.4122},
                zoom: 10
            });
            console.log(map);

            var input = document.getElementById('pac-input');

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            // Specify just the place data fields that you need.
            autocomplete.setFields(['place_id', 'geometry', 'name']);

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var infowindow = new google.maps.InfoWindow();
            var infowindowContent = document.getElementById('infowindow-content');
            infowindow.setContent(infowindowContent);

            var marker = new google.maps.Marker({map: map});

            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });

            autocomplete.addListener('place_changed', function () {
                infowindow.close();

                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                // Set the position of the marker using the place ID and location.
                marker.setPlace({
                    placeId: place.place_id,
                    location: place.geometry.location
                });

                marker.setVisible(true);

                infowindowContent.children['place-name'].textContent = place.name;
                infowindowContent.children['place-id'].textContent = place.place_id;
                infowindowContent.children['place-address'].textContent =
                    place.address;
                infowindow.open(map, marker);
            });
        }

    </script>


</head>
<body>
<form method="POST" action="" enctype="multipart/form-data">

    <strong class="head"><?php _e("Google Rating Integration", "luk-google-rating"); ?></strong>

    <div class="container">

        <div class="row">
            <div class="col-25">
                <label for="cName"><?php _e("Company", "luk-google-rating");?></label>
            </div>
            <div class="col-75">
                <input type="text" id="cName" name="cName" placeholder="<?php _e("Company Name", "luk-google-rating");?>"
                       value="<?php if($cName)echo $cName; ?>" class="form-control login-field">
            </div>
        </div>

        <div class="row" >
            <div class="col-25">
                <label for="placeid"><?php _e("Google-Place-ID", "luk-google-rating"); ?></label>
            </div>
            <div class="col-75">
                <input type="text" id="placeid" name="placeid" placeholder="<?php _e("Company-Place-ID", "luk-google-rating");?>"
                       value="<?php if($placeId)echo $placeId; ?>" class="form-control login-field">
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="struct_head"><?php _e("Load Structured Data in Header", "luk-google-rating");?></label>
            </div>
            <div class="col-75">
                <input type="checkbox" id="struct_head" value="on" name="struct_head"
                      <?php if($addStruc == 1 || $addStruc == "on")echo "checked "  ?>class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-25">
                <label for="apiKey"><?php _e("Google-API", "luk-google-rating");?></label>
            </div>
            <div class="col-75">
                <input type="text" id="apiKey" name="apiKey" placeholder="<?php _e("Google-API-Key", "luk-google-rating");?>"
                       value="<?php if($apiKey)echo $apiKey; ?>" class="form-control login-field">
            </div>
        </div>

        <div class="row">
            <div class="col-25">
                <label for="place-id-picker"><?php _e("If you don't know the Place-ID of a Company you can query it here", "luk-google-rating");?></label>
            </div>
            <div class="col-75">


                <div style="display: none">
                    <input id="pac-input"
                           class="controls"
                           type="text"
                           placeholder="Enter a location">
                </div>
                <div id="map"></div>
                <div id="infowindow-content">
                    <span id="place-name" class="title"></span><br>
                    <strong>Place ID:</strong> <span id="place-id"></span><br>
                    <button type="button" id="addToForm"><?php _e("Add Name and ID", "luk-google-rating");?></button>
                    <span id="place-address"></span>
                </div>

            </div>
        </div>



        <div class="row">
            <input type="submit" value="<?php _e("Save", "luk-google-rating");?>">
        </div>
</form>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php if($apiKey)echo $apiKey; ?>&libraries=places&callback=initMap"
        async defer></script>
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
<script src="../wp-content/plugins/luk-google-rating/includes/js/functions.js"></script>


</body>
</html>
