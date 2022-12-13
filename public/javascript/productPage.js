new MultiSelectTag('categories', {
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

function addCategoryToArray(){
    let categories = []
    let drawer = document.getElementsByClassName('drawer');
    console.log(drawer)
    let liste = drawer[0].lastChild;
    let elements = liste.children;
    let select = document.getElementsByClassName('mult-select-tag')


    select[0].addEventListener("click", function () {
        let url = '/store'
        let formData = new FormData();
        for (const child of elements) {
            child.onclick = function (e) {
                categories.push(child.innerText).toString()

                formData.append('categories', JSON.stringify(categories))

                fetch(url, {method: 'POST', body: formData})
                    .then(function (response){
                        return response.text()
                    })
                    .then(function (body){
                        //let test = JSON.parse(body)
                        console.log(body)
                       //console.log(test.test)
                    })
            }
        }
    })
}
addCategoryToArray()


