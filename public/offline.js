let requestURL = 'product.json';
let request = new XMLHttpRequest();
request.open('GET', requestURL);
request.responseType = 'json';
request.send();
request.onload = function() {
    let product = request.response;
    display(product)
}

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

function display(product){
    let body = document.getElementById("root")
    product.forEach(function (element){
        let container = document.createElement('div')
        container.className = "card me-2 mb-2 text-center"
        container.style.maxHeight = "45%"
        container.style.maxWidth = "40%"

        let img = document.createElement('img')
        img.className = "card-img-top"
        img.src = element.img
        img.alt = "Card image cap"
        container.appendChild(img)

        let div_header = document.createElement('div')
        div_header.className = "card-header"

        let h5_name = document.createElement('h5')
        h5_name.className = "card-title mb-2"
        h5_name.innerText = element.productName
        div_header.appendChild(h5_name)

        container.appendChild(div_header)

        let div_body = document.createElement('div')
        div_body.className = "card-body mt-2"

        let h5_resume = document.createElement('h5')
        h5_resume.className = "card-title mt-2 mb-2"
        h5_resume.innerText = 'Description'
        div_body.appendChild(h5_resume)

        let div_overflow = document.createElement('div')
        div_overflow.className = "overflow-auto mb-2 text-scrollable"
        div_overflow.style.maxHeight = "125px"

        let p_resume = document.createElement('p')
        p_resume.className = "card-text text-justify"
        p_resume.innerText = element.resume

        div_overflow.appendChild(p_resume)
        div_body.appendChild(div_overflow)
        container.appendChild(div_body)

        let div_footer = document.createElement('div')
        div_footer.className = "card-footer"

        let small = document.createElement('small')
        small.className = "text-muted"
        small.innerText = element.chapterNumber + ' chapitres'

        div_footer.appendChild(small)
        container.appendChild(div_footer)
        body.appendChild(container)
        console.log('ok')
    })
}
