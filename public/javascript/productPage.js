new MultiSelectTag('countries', {
    rounded: true,    // default true
    shadow: true      // default false
})

let buttonToTop = document.getElementById('btn-back-to-top')

//Quand l'utilisateur scroll de 20px sur le bas, affiche le bouton
window.onscroll = function () {
    scrollFunction();
};

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
// Quand l'utilisateur clique sur le bouton, scroll jusqu'en haut de la page
buttonToTop.addEventListener("click", backToTop);

function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}