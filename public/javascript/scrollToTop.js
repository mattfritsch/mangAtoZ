let buttonToTop = document.getElementById('btn-back-to-top')

window.onscroll = function () {
    scrollFunction();
};

buttonToTop.addEventListener("click", backToTop);

function scrollFunction() {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        buttonToTop.style.display = "block";
    } else {
        buttonToTop.style.display = "none";
    }
}

function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}