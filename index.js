function initializeCarousel(wrapperSelector, leftButtonSelector, rightButtonSelector, perView) {
    const carouselWrapper = document.querySelector(wrapperSelector);
    const carouselItems = document.querySelectorAll(`${wrapperSelector} > *`);
    const carouselLength = carouselItems.length;
    let totalScroll = 0;

    carouselWrapper.style.setProperty('--per-view', perView);
    for (let i = 0; i < perView; i++) {
        carouselWrapper.insertAdjacentHTML('beforeend', carouselItems[i].outerHTML);
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