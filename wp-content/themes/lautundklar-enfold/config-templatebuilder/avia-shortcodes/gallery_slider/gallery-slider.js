function initGallerySliderSwiper()
{
    if ( $('.luk-gallery-slider').length) {
        $('.luk-gallery-slider .swiper').each(function(index, element) {
            const slidesperview = $($(element)[0]).data('slidesperview');
            new Swiper($(element)[0], {
                slidesPerGroup: 1,
                slidesPerView: 1,
                spaceBetween: 10,
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
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                    },
                    1200: {
                        slidesPerView: slidesperview,
                        slidesPerGroup: slidesperview,
                    },
                },
            });
        });
    }
}

jQuery(window).on('load', function(){
    initGallerySliderSwiper();
});