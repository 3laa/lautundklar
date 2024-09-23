<?php
if(file_exists("../../../../../wp-load.php")){
    include "../../../../../wp-load.php";
}
else{ //cronjob
    include "httpdocs/wp-load.php";
}
if(isset($_POST['url']) && !empty($_POST['url'])) {

    $response = file_get_contents($_POST['url']);
    $response = json_decode($response);
    $rating_value = $response->result->rating;
    if(strlen($rating_value) == 1){
        $rating_value .= ".0";
    }
    $rating_amount = $response->result->user_ratings_total;

    $dbRating = get_option("luk_grating_rating_value");
    $dbAmount = get_option("luk_grating_rating_amount");

    if(!$dbRating && $dbRating != ""){ //If no value exists in DB create the option and set value
        add_option("luk_grating_rating_value",$rating_value);
    }
    else{
        update_option("luk_grating_rating_value",$rating_value);
    }

    if(!$dbAmount && $dbAmount != ""){ //If no value exists in DB create the option and set value
        add_option("luk_grating_rating_amount",$rating_amount);
    }
    else{
        update_option("luk_grating_rating_amount",$rating_amount);
    }
}
else{
    //Script-argument-call for server-side cron-job
    if (isset($argv[1]) && $argv[1] != "update") {
        print " \n wrong parameter\n";
        die();
    } else {
        $debug= true;
        if(isset($argv[2]) && $argv[2] == "verbose"){
            $debug = false;
        }

        $dbRating = get_option("luk_grating_rating_value");
        $dbAmount = get_option("luk_grating_rating_amount");
        $placeId = get_option("luk_grating_place_id");
        $apiKey = get_option("luk_grating_api");

        if($placeId && $apiKey){
            ($debug) ?: print "API-Key: \n".$apiKey."\n";
            ($debug) ?: print "Place-ID: \n".$placeId."\n";
            ($debug) ?: print "-----------------------------\n";

            $apiQuery = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $placeId . '&key=' . $apiKey;
            $response = file_get_contents($apiQuery);
            $response = json_decode($response);
            $rating_value = $response->result->rating;
            $rating_amount = $response->result->user_ratings_total;

            if(!$dbRating && $dbRating != ""){ //If no value exists in DB create the option and set value
                add_option("luk_grating_rating_value",$rating_value);
            }
            else{
                ($debug) ?: print "Current rating: \n".$dbRating . "/5 | amount: " . $dbAmount."\n";
                update_option("luk_grating_rating_value",$rating_value);
            }
            ($debug) ?: print "New rating: \n".$rating_value . "/5 | amount: " . $rating_amount."\n";
            if(!$dbAmount && $dbAmount != ""){ //If no value exists in DB create the option and set value
                add_option("luk_grating_rating_amount",$rating_amount);
            }
            else{
                update_option("luk_grating_rating_amount",$rating_amount);
            }
        }
    }
}
