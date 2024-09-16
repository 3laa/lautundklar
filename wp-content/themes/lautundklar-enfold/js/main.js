const $ = jQuery.noConflict();

function viewPort(elements, animationClass, topOffset=0) {
    let $window = $(window);
    let viewPortTop = $window.scrollTop();
    let viewPortHeight = $window.height();
    viewPortTop = viewPortTop + viewPortHeight;

    let $elements = $(elements);
    $elements.addClass('not-view-port');
    $elements.not('.in-view-port').each(function () {
        let $element = $(this);
        let elementTop = $element.offset().top;
        elementTop = elementTop - topOffset;
        if (elementTop <= viewPortTop) {
            $element.addClass('in-view-port').addClass(animationClass);
        }
    });
}

function isOnScreen($elem) {
    // if the element doesn't exist, abort
    if( $elem.length == 0 ) {
        return;
    }
    var $window = $(window);
    var viewport_top = $window.scrollTop();
    var viewport_height = $window.height();
    var viewport_bottom = viewport_top + viewport_height;
    var top = $elem.offset().top;
    var height = $elem.height();
    var bottom = top + height;

    return (top >= viewport_top && top < viewport_bottom) ||
        (bottom > viewport_top && bottom <= viewport_bottom) ||
        (height > viewport_height && top <= viewport_top && bottom >= viewport_bottom)
}

function masonryLoadMore() {
    let $masonryLoadMoreButton = $('.av-masonry-pagination.av-masonry-load-more');
    if ($masonryLoadMoreButton.length) {
        if( isOnScreen( $masonryLoadMoreButton ) ) {
            $masonryLoadMoreButton.trigger('click');
        }
    }
}


function changeVersion() {
    let $versionText = $('#av_section_1 .av_textblock_section .avia_textblock p');
    if ($versionText.length) {
        $versionText.html('Version 4.2 // Digital Astronauts');
    }
}

function mmenujs() {


    new Mmenu(document.querySelector("#menu"), {
        "offCanvas": {
            "position": "right-front"
        },
        "setSelected": {
            "parent": true
        },
        "navbar": {
            "title": "<a class='logo-title' title='lautundklar' href='https://lautundklar.de/'><img src='/wp-content/uploads/2024/03/luk-website-logo-normal.png' alt='LautundKlar' title='LautundKlar'></a>",
        },
        "navbars": [
            {
                "position": "bottom",
                "content": [
                    "<div class='contact-info'><span>LautundKlar GmbH</span><span>Ludwigstraße 2</span><span>94032 Passau</span></div>",
                    "<div class='contact-info'><a href='mailto:hallo@lautundklar.de'>hallo@lautundklar.de</a><a href='tel:+4985195176855'>+49 851/95 17 68 55</a></div>",
                ]
            }
        ]
    });
}


$(document).ready(function() {

    mmenujs();
    changeVersion();
    masonryLoadMore();
    viewPort('.av_textblock_section, .iconbox_content_container, .to-anim', 'slide-top', 500);
    viewPort('.luk-clients', 'clients-animation');
    viewPort('.avia-content-slider .slide-entry-wrap', 'clients-animation');
    viewPort('.reference', '-start-reference', -500);
});
$(window).on('scroll', function () {
    viewPort('.av_textblock_section, .iconbox_content_container, .to-anim', 'slide-top', 500);
    viewPort('.luk-clients', 'clients-animation');
    viewPort('.avia-content-slider .slide-entry-wrap', 'clients-animation');
    viewPort('.reference', '-start-reference', -500);
    masonryLoadMore();
});

