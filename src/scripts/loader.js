function simulateContentLoading() {

    showLoader();
    setTimeout(function () {
        
        hideLoader();
        showContent();
    }, 1000);
}

function showLoader() {
    console.log("Showing loader.");
    document.querySelector('.loader').classList.add('visible');
}

function hideLoader() {
    console.log("Hiding loader with transition.");
    const loader = document.querySelector('.loader');
    loader.style.transition = 'opacity 0.5s ease-out';
    loader.style.opacity = '0';
    loader.addEventListener('transitionend', function (event) {
        if (event.propertyName === 'opacity') {
            loader.style.display = 'none';
        }
    });
}

function showContent() {
    console.log("Showing content.");
    const content = document.querySelector('.main-content');
    content.style.visibility = 'visible'; // Use visibility to show content
}
simulateContentLoading();