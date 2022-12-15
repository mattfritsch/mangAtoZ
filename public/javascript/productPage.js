$(document).ready(function() {
    $('.multiple-categ-select').select2();
});


//Infinite Scroll

let div_scroll = document.getElementById("scroll")
let div_categs = document.getElementById("categs")
let input_search = document.getElementById("searchBar")
let select_order = document.getElementById("order")
let radio_in_progress = document.getElementById("statusInprogress")
let radio_finished = document.getElementById("statusFinished")
let radio_censure = document.getElementById("removeCensure")
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
        container.style.maxHeight = "45%"
        container.style.maxWidth = "40%"

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
        div_scroll.appendChild(container)
        counter ++;
    }
}

function getProducts(number){
    let categs = div_categs.innerText.replaceAll(' ', '').replaceAll('\n', '').split(",")

    let url = '/store'
    let formData = new FormData();
    formData.append('method', 'getProducts');
    formData.append('min', counter.toString());
    formData.append('max', (counter + number).toString());
    formData.append('nb_categs', categs.length.toString());
    for (let i = 0; i < categs.length; i++){
        formData.append('categ' + i.toString(), categs[i].toString());
    }
    formData.append('search', input_search.value);
    formData.append('order', select_order.value);
    if(radio_in_progress.checked){
        formData.append('status', radio_in_progress.value);
    }
    if(radio_finished.checked){
        formData.append('status', radio_finished.value);
    }
    if(radio_censure.checked){
        formData.append('censure', radio_censure.value);
    }

    return fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            console.log(body)
            let data = JSON.parse(body)
            nb_results = data["nbResults"]
            delete data["nbResults"]
            return data
        });
}