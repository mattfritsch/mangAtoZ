console.log("ok")

new MultiSelectTag('categories', {
    rounded: true,    // default true
    shadow: true      // default false
})

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
                    console.log(body)
                })
        }
    }
})