const burger = document.querySelector('.burger');
const nav = document.querySelector('.nav-links');
const navLinks = document.querySelectorAll('.nav-links li');


const navSlide = () => {


    burger.addEventListener('click', () => {
        // toggle nav
        nav.classList.toggle('nav-active');


        // animate links
        navLinks.forEach((link, index) => {
            if (link.style.animation) {
                link.style.animation = ''
            } else {
                link.style.animation = `navLinkFade 0.3s ease forwards ${index / 7 + 0.1}s`
            }
        });

        // burger animation

        burger.classList.toggle('toggle');
    })
}

navSlide()

function initializeCarousel(wrapperSelector, leftButtonSelector, rightButtonSelector, perView) {
    const carouselWrapper = document.querySelector(wrapperSelector);
    const carouselItems = document.querySelectorAll(`${wrapperSelector} > *`);
    const carouselLength = carouselItems.length;
    let totalScroll = 0;

    carouselWrapper.style.setProperty('--per-view', perView);

    // Jeśli jest więcej filmów niż perView, powielamy, w przeciwnym razie NIE powielamy
    if (carouselLength > perView) {
        for (let i = 0; i < perView; i++) {
            carouselWrapper.insertAdjacentHTML('beforeend', carouselItems[i].outerHTML);
        }
    }

    function updateCarousel() {
        const widthEl = document.querySelector(`${wrapperSelector} > :first-child`).offsetWidth + 24;
        carouselWrapper.style.left = `-${totalScroll * widthEl}px`;
    }

    document.querySelector(leftButtonSelector).addEventListener('click', () => {
        totalScroll--;
        if (totalScroll < 0) {
            totalScroll = carouselLength - 1;
        }
        updateCarousel();
    });

    document.querySelector(rightButtonSelector).addEventListener('click', () => {
        totalScroll++;
        if (totalScroll >= carouselLength) {
            totalScroll = 0;
        }
        updateCarousel();
    });
}
initializeCarousel('.carousel-wrapper', '.left-button', '.right-button', 4);
initializeCarousel('.carousel-wrapper2', '.left-button2', '.right-button2', 4);
