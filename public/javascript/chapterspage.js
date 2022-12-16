$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});



let btns_prevent = document.getElementsByClassName("notify")

let div_error = document.getElementById("error")
let div_success = document.getElementById("success")

const urlParams = new URLSearchParams(window.location.search);
let productId = urlParams.get('id')

for (let btn of btns_prevent){
    btn.addEventListener('click', () => {
        let chapter_id = btn.id.substr(6)
        let url = '/chapterspage?id=' + productId
        let formData = new FormData();
        formData.append('method', 'notify');
        formData.append('chapterId', chapter_id);

        fetch(url, { method: 'POST', body: formData })
            .then(function (response) {
                return response.text();
            })
            .then(function (body) {
                let data = JSON.parse(body)
                if(data["response"] === "success"){
                    div_error.innerText = ""
                    div_error.style.display = "none"
                    div_success.innerText = data["msg"]
                    div_success.style.display = "block"
                } else {
                    div_error.innerText = data["msg"]
                    div_error.style.display = "block"
                    div_success.innerText = ""
                    div_success.style.display = "none"
                }
            });
    })
}

