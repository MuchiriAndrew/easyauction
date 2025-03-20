document.addEventListener('DOMContentLoaded', function() {
    const swiper = new Swiper('.auction-carousel', {
        // Optional parameters
        loop: true,
        effect: 'slide',
        // autoplay: {
        //     delay: 3000,
        //     disableOnInteraction: false,
        // },
        
        // Responsive breakpoints
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 1,
                spaceBetween: 20
            },
            // when window width is >= 480px
            480: {
                slidesPerView: 1,
                spaceBetween: 30
            },
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // Pagination
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
}); 