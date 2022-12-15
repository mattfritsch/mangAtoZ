// new MultiSelectTag('categories', {
//     rounded: true,    // default true
//     shadow: true      // default false
// })
//
// let buttonToTop = document.getElementById('btn-back-to-top')
//
// //Quand l'utilisateur scroll de 20px sur le bas, affiche le bouton
// window.onscroll = function () {
//     scrollFunction();
// };
//
// function scrollFunction() {
//     if (
//         document.body.scrollTop > 20 ||
//         document.documentElement.scrollTop > 20
//     ) {
//         buttonToTop.style.display = "block";
//     } else {
//         buttonToTop.style.display = "none";
//     }
// }
// // Quand l'utilisateur clique sur le bouton, scroll jusqu'en haut de la page
// buttonToTop.addEventListener("click", backToTop);
//
// function backToTop() {
//     document.body.scrollTop = 0;
//     document.documentElement.scrollTop = 0;
// }
//
// function addCategoryToArray(){
//     let categories = []
//     let drawer = document.getElementsByClassName('drawer');
//     console.log(drawer)
//     let liste = drawer[0].lastChild;
//     let elements = liste.children;
//     let select = document.getElementsByClassName('mult-select-tag')
//
//
//     select[0].addEventListener("click", function () {
//         let url = '/store'
//         let formData = new FormData();
//         for (const child of elements) {
//             child.onclick = function (e) {
//                 categories.push(child.innerText).toString()
//
//                 formData.append('categories', JSON.stringify(categories))
//
//                 fetch(url, {method: 'POST', body: formData})
//                     .then(function (response){
//                         return response.text()
//                     })
//                     .then(function (body){
//                         console.log(body)
//                     })
//             }
//         }
//     })
// }
// addCategoryToArray()





//Infinite Scroll

let div_scroll = document.getElementById("scroll")
let counter = 0;
let counter_displayed = 0;
let nb_results = 0;

$(document).ready(async function() {
    await displayProducts(0)
    await displayProducts(5)
    counter_displayed += 5
    window.addEventListener('scroll', async () => {
        if(window.scrollY + window.innerHeight >= document.documentElement.scrollHeight-document.documentElement.scrollHeight*0.2){
            if (counter === counter_displayed){
                displayProducts(5).then(
                    counter_displayed += 5
                )
            }
        }
    })
})

async function displayProducts(number) {
    let nb_results_pending = nb_results - counter;
    if (nb_results_pending > number){
        await loadProducts(number)
    } else {
        await loadProducts(nb_results_pending)
    }
}

async function loadProducts(number){
    let products = await getProducts(number)

    for (let i = 0; i < number; i++){
        let container = document.createElement('div')
        container.className = "card me-2 mb-2 text-center"

        let img = document.createElement('img')
        img.className = "card-img-top"
        img.src = products["img" + i.toString()]
        img.alt = "Card image cap"
        container.appendChild(img)

        let div_header = document.createElement('div')
        div_header.className = "card-header"

        let h5_name = document.createElement('h5')
        h5_name.className = "card-title mb-2"
        h5_name.innerText = products["name" + i.toString()]
        div_header.appendChild(h5_name)

        let small = document.createElement('small')
        small.className = "text-muted"
        if(products["chapterNumber" + i.toString()] > 1){
            small.innerText = products["chapterNumber" + i.toString()] + " " + products["chapters"]
        } else {
            small.innerText = products["chapterNumber" + i.toString()] + " " + products["chapter"]
        }

        div_header.appendChild(small)
        container.appendChild(div_header)

        let div_body = document.createElement('div')
        div_body.className = "card-body mt-2"

        let h5_resume = document.createElement('h5')
        h5_resume.className = "card-title mt-2 mb-2"
        h5_resume.innerText = products["resume"]
        div_body.appendChild(h5_resume)

        let div_overflow = document.createElement('div')
        div_overflow.className = "overflow-auto mb-2 text-scrollable"

        let p_resume = document.createElement('p')
        p_resume.className = "card-text text-justify"
        p_resume.innerText = products["resume" + i.toString()]

        div_overflow.appendChild(p_resume)
        div_body.appendChild(div_overflow)
        container.appendChild(div_body)

        let div_footer = document.createElement('div')
        div_footer.className = "card-footer"

        let a_chapters_page = document.createElement('a')
        a_chapters_page.className = "btn btn-outline-primary"
        a_chapters_page.href = "/chapterspage?id=" + products["productId" + i.toString()]
        if(products["chapterNumber" + i.toString()] > 1){
            a_chapters_page.innerText = products["go_chapters"]
        } else {
            a_chapters_page.innerText = products["go_chapter"]
        }

        div_footer.appendChild(a_chapters_page)
        container.appendChild(div_footer)

        // div_scroll.innerHTML += "<div class=\"card me-2 mb-2 text-center\">" +
        //                             "<img class=\"card-img-top\" src=\"" + products["img" + i.toString()] + "\" alt=\"Card image cap\">" +
        //                             "<div class=\"card-header\">" +
        //                                 "<h5 class=\"card-title mb-2\">" + products["name" + i.toString()] + "</h5>"
        //     // "                                    {% if product.chapterNumber > 1 %}\n" +
        //     // "                                        <small class=\"text-muted\">{{ product.chapterNumber }} {{ lang.PRODUCT.CHAPTERS }}</small>\n" +
        //     // "                                    {% else %}\n" +
        //     // "                                        <small class=\"text-muted\">{{ product.chapterNumber }} {{ lang.PRODUCT.CHAPTER }}</small>\n" +
        //     // "                                    {% endif %}\n" +
        // div_scroll.innerHTML +=     "</div>" +
        //                             "<div class=\"card-body mt-2\">" +
        //                                 "<h5 class=\"card-title mt-2 mb-2\">" + products["resume"] + "</h5>" +
        //                                 "<div class=\"overflow-auto mb-2 text-scrollable\">" +
        //                                     "<p class=\"card-text text-justify\">" + products["resume" + i.toString()] + "</p>" +
        //                                 "</div>" +
        //                             "</div>" +
        //                             "<div class=\"card-footer\">"
        //     // "                                    {% if product.chapterNumber > 1 %}\n" +
        //     // "                                        <a href=\"/chapterspage?id={{ product.productId }}\"\n" +
        //     // "                                           class=\"btn btn-outline-primary\">{{ lang.PRODUCT.GO_CHAPTERS }}</a>\n" +
        //     // "                                    {% else %}\n" +
        //     // "                                        <a href=\"/chapterspage?id={{ product.productId }}\"\n" +
        //     // "                                           class=\"btn btn-outline-primary\">{{ lang.PRODUCT.GO_CHAPTER }}</a>\n" +
        //     // "                                    {% endif %}\n" +
        // div_scroll.innerHTML +=     "</div>" +
        //                         "</div>"








        // let p = document.createElement('p')
        // p.innerText = products["name" + i.toString()]
        // p.style.marginBottom = "200px"
        // p.style.marginTop = "200px"
        div_scroll.appendChild(container)
        counter ++;
    }
}

function getProducts(number){
    let url = '/store'
    let formData = new FormData();
    formData.append('method', 'getProducts');
    formData.append('min', counter.toString());
    formData.append('max', (counter + number).toString());

    return fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            let data = JSON.parse(body)
            nb_results = data["nbResults"]
            delete data["nbResults"]
            return data
        });
}