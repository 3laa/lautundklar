
if (window.jQuery) {
    // jQuery is loaded
    var $ = window.jQuery;
    initEvents();

} else {
    // jQuery is not loaded

    var script = document.createElement("SCRIPT");
    script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js';
    script.type = 'text/javascript';
    script.onload = function() {
        var $ = window.jQuery;
    };
    document.getElementsByTagName("head")[0].appendChild(script);
    window.setTimeout(function(){
        if(window.JQuery){
            var $ = window.jQuery;
            initEvents();
        }
        else{
            window.setTimeout(function(){
                var $ = window.jQuery;
                initEvents();
            }, 2000);

        }
    }, 2000);
}
window.onload = function() {

    if (window.jQuery) {
        // jQuery is loaded

        var $ = window.jQuery;
        initEvents();

    } else {
        // jQuery is not loaded

        var script = document.createElement("SCRIPT");
        script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js';
        script.type = 'text/javascript';
        script.onload = function() {
            var $ = window.jQuery;
        };
        document.getElementsByTagName("head")[0].appendChild(script);
        window.setTimeout(function(){
            if(window.JQuery){
                var $ = window.jQuery;
                initEvents();
            }
            else{
                window.setTimeout(function(){
                    var $ = window.jQuery;
                    initEvents();
                }, 2000);

            }
        }, 2000);
    }
}

function loadGoogleQuery(placeId, apiKey) {
    var apiQuery = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' + placeId + '&key=' + apiKey;
    var base_url = window.location.origin;
    var url = base_url +'/wp-content/plugins/luk-google-rating/includes/functions/grabber.php';

    $.ajax({ url: url,
        data: {url: apiQuery},
        type: 'post',
        success: function(output) {
            if($("body.wp-admin").length){
                location.reload();
            }
        },
        error: function(error){
        //console.log(error);
            return false;
        }
    });
}


function initEvents(){
    var $ = window.jQuery;
    if( document.querySelector('form')){
        document.querySelector('form').onkeypress = checkEnter;
    }

$("#addToForm").click(function(){
    $("#placeid").val($("#place-id").html());
    $("#cName").val($("#place-name").html());
    });

$("#getRating").click(function(){
    const placeId = $("#placeid").val().toString();
    const apiKey = $("#apiKey").val().toString();
    loadGoogleQuery(placeId,apiKey);
    });
}
function checkEnter(e){
    e = e || event;
    var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
    return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
}
function trimChar(string, charToRemove) {
    while(string.charAt(0)==charToRemove) {
        string = string.substring(1);
    }

    while(string.charAt(string.length-1)==charToRemove) {
        string = string.substring(0,string.length-1);
    }

    return string;
}