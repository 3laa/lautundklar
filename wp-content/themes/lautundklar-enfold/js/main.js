const $ = jQuery.noConflict();
let $pricingTable = [];
let pricingTableOffsetTop = 0;
function initPricingTable() {
    if ($('html.responsive body#top table.avia-table.pricing-table').length) {
        $pricingTable = $('html.responsive body#top table.avia-table.pricing-table');
        pricingTableOffsetTop = $pricingTable.offset().top;
    }
}



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

function pricingTable() {
    let $pricePlans = $('.pricing-box');
    let $priceTables = $('.pricing-table');
    if ($pricePlans.length && $priceTables.length) {
        //clean the services in plans
        $pricePlans.find('#rpt_pricr.rpt_plans .rpt_plan .rpt_features').slideUp().html('');

        let $pricePlansBox = $pricePlans.find('#rpt_pricr.rpt_plans .rpt_plan');

        $priceTables.find('tr:not(.avia-pricing-row)').each(function () {
            let $row = $(this);
            let $tdThs = $row.find('td,th');
            if ($row.hasClass('avia-heading-row')) {
                $($pricePlansBox).find('.rpt_features').append('<div class="rpt_feature rpt_feature_head"> '+$($tdThs[0]).text()+' </div>')
            } else {
                $($($pricePlansBox[0])).find('.rpt_features').append('<div class="rpt_feature rpt_feature_element"> <strong>'+$($tdThs[0]).text()+': </strong>'+$($tdThs[1]).html()+' </div>');
                $($($pricePlansBox[1])).find('.rpt_features').append('<div class="rpt_feature rpt_feature_element"> <strong>'+$($tdThs[0]).text()+': </strong>'+$($tdThs[2]).html()+' </div>');
                $($($pricePlansBox[2])).find('.rpt_features').append('<div class="rpt_feature rpt_feature_element"> <strong>'+$($tdThs[0]).text()+': </strong>'+$($tdThs[3]).html()+' </div>');
                $($($pricePlansBox[3])).find('.rpt_features').append('<div class="rpt_feature rpt_feature_element"> <strong>'+$($tdThs[0]).text()+': </strong>'+$($tdThs[4]).html()+' </div>');
            }
        });
        $pricePlans.find('#rpt_pricr.rpt_plans .rpt_plan .rpt_features').each(function () {
            let $rptActions = $('<div class="rpt_actions"></div>');
            let $actionCompare = $('<a class="rpt_action rpt_action_compare" href="#pricing_table">Leistungen vergleichen</a>');
            let $actionShow = $('<span class="rpt_action rpt_action_show">Leistungen anzeigen</span>');
            let $actionHide = $('<span class="rpt_action rpt_action_hide">Verbergen</span>');
            $actionShow.on('click', function () {
                $(this).parents('.rpt_plan').addClass('show-features')
                $(this).parents('.rpt_plan').find('.rpt_features').slideDown();
            })
            $actionHide.on('click', function () {
                $(this).parents('.rpt_plan').removeClass('show-features')
                $(this).parents('.rpt_plan').find('.rpt_features').slideUp();
            })
            $rptActions.append($actionCompare);
            $rptActions.append($actionShow);
            $rptActions.append($actionHide);
            $rptActions.insertBefore($(this));
        });
    }

    initPricingTable();
}

function pricingTableScroll() {
    if ($pricingTable.length) {
        if ($(window).scrollTop() > (pricingTableOffsetTop - 100)) {
            let top = ($(window).scrollTop() - pricingTableOffsetTop) + 100;
            $pricingTable.find('tr.avia-pricing-row').css('top', top)
        } else {
            $pricingTable.find('tr.avia-pricing-row').css('top', 0)
        }
    }
}

function contactForm() {
    let $contactForm = $('#contact_form');
    if ($contactForm.length) {
        let currentParams = new URLSearchParams(window.location.search);
        if (currentParams.size > 0) {
            let betreff = currentParams.get('betreff');
            let message = currentParams.get('message');

            $contactForm.find('select[name="Betreff"]').val(betreff);
            $contactForm.find('select[name="your-message"]').val(message);

            console.log(betreff, message)
            console.log($contactForm.find('select[name="Betreff"]'))
            console.log($contactForm.find('textarea[name="your-message"]'))
        }
    }
}

$(document).ready(function() {
    pricingTable();
    mmenujs();
    changeVersion();
    masonryLoadMore();
    viewPort('.av_textblock_section, .iconbox_content_container, .to-anim', 'slide-top', 500);
    viewPort('.luk-clients', 'clients-animation');
    viewPort('.avia-content-slider .slide-entry-wrap', 'clients-animation');
    viewPort('.reference', '-start-reference', -500);
    initPricingTable();
    contactForm();
});
$(window).on('scroll', function () {
    initPricingTable()
    viewPort('.av_textblock_section, .iconbox_content_container, .to-anim', 'slide-top', 500);
    viewPort('.luk-clients', 'clients-animation');
    viewPort('.avia-content-slider .slide-entry-wrap', 'clients-animation');
    viewPort('.reference', '-start-reference', -500);
    masonryLoadMore();
    pricingTableScroll();

});

