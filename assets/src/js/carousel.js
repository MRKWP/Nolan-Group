window.addEventListener('load', () => {

    if(document.querySelectorAll('.nolan-group-carousel-contents')) {
        document.querySelectorAll('.nolan-group-carousel-contents').forEach((element) => {

            let desk_loop = false;

            if(element.querySelectorAll('.swiper-slide') && element.querySelectorAll('.swiper-slide').length > 5) {
                desk_loop = true;
            }
            let carousel = new Swiper(element, {
                autoplay: {
                    delay: 3000,
                },
                pagination: {
                    el: element.querySelector(".swiper-pagination"),
                    clickable: true,
                    renderBullet: function (index, className) {
                        return `<div class=${className}>
                                    <span class="line"></span>
                                </div>`;
                    }
                },
                navigation: {
                    prevEl: '.swiper-button-prev',
                    nextEl: '.swiper-button-next'
                },
                slidesPerView: 1,
                spaceBetween: 15,
                loop: true,
                lazyLoading: true,
                keyboard: {
                    enabled: true
                },
                allowTouchMove: true,
                grabCursor: true,
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        loop: true,
                    },
                    768: {
                        slidesPerView: 3,
                        loop: true,
                    },
                    1024: {
                        slidesPerView: 5,
                        loop: desk_loop,
                    },
                },
            });
        });
    }
})