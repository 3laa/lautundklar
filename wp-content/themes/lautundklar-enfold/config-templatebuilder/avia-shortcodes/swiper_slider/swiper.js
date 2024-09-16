function initSwiper()
{
    if ($('.swiper-slider').length) {
        $('.swiper-slider .swiper').each(function(index, element) {
            new Swiper($(element)[0], {
                slidesPerGroup: 1,
                spaceBetween: 5,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        slidesPerGroup: 2,
                    },
                    768: {
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                    },
                    990: {
                        slidesPerView: 4,
                        slidesPerGroup: 4,
                    },
                    1200: {
                        slidesPerView: 4,
                        slidesPerGroup: 4,
                    },
                },
            });
        });
    }
}

jQuery(window).on('load', function(){
    initSwiper();
});