let btn_add = document.getElementById("add")
let div_form_add = document.getElementById("form-add")
let btn_form_validate = document.getElementById("validate-form")
let btn_form_update = document.getElementById("update-form")
let btn_form_cancel = document.getElementById("cancel-form")

let chapter_name = document.getElementById("chapter_name")
let chapter_price = document.getElementById("chapter_price")
let chapter_stock = document.getElementById("chapter_stock")

let chapter_table_body = document.getElementById("chapter-table-body")

let btns_delete = document.getElementsByClassName("delete")
let btns_update = document.getElementsByClassName("update")

let title_form = document.getElementById("form-title")
let div_success = document.getElementById("success")

let lastChapterName = chapter_table_body.lastElementChild.firstElementChild.innerText

const urlParams = new URLSearchParams(window.location.search);
let productId = urlParams.get('id')

btn_add.addEventListener('click', function () {
    resetDivSuccess()
    div_form_add.style.display = 'block'
    let url = '/admin/chapters'
    let formData = new FormData();
    formData.append('method', 'add_title');

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            title_form.innerText = body
        });

    chapter_name.value = (parseInt(lastChapterName) + 1).toString();
    resetStockAndPrice()
})

btn_form_cancel.addEventListener('click', function () {
    div_form_add.style.display = 'none'
    btnAddOrUpdate("add")
})


btn_form_validate.addEventListener('click', function () {
    let url = '/admin/chapters?id=' + productId;
    let formData = new FormData();
    formData.append('method', 'add');
    formData.append('productId', productId);
    formData.append('name', chapter_name.value);
    formData.append('price', chapter_price.value);
    formData.append('stock', chapter_stock.value);

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            let data = JSON.parse(body)
            let tr = document.createElement("tr")
            tr.className = "text-center"
            let td_name = document.createElement("td")
            td_name.innerText = data["name"].toString()
            let td_price = document.createElement("td")
            td_price.innerText = data["price"].toString() + '€'
            let td_stock = document.createElement("td")
            td_stock.innerText = data["stock"].toString()
            chapter_name.value = "";
            resetStockAndPrice()

            let td_available = document.createElement("td")
            td_available.innerText = data["value"]

            let td_btn_delete = document.createElement("td")
            let btn_delete = document.createElement("button")
            btn_delete.id = "del" + data["id"].toString();
            btn_delete.className = "btn btn-danger delete";
            btn_delete.innerText = data["delete"]
            btn_delete.addEventListener("click", () => deleteChapter(btn_delete))
            td_btn_delete.appendChild(btn_delete)

            let td_btn_update = document.createElement("td")
            let btn_update = document.createElement("button")
            btn_update.id = "upd" + data["name"].toString();
            btn_update.className = "btn btn-warning update";
            btn_update.innerText = data["update"]
            btn_update.addEventListener("click", () => displayUpdateForm(btn_update))
            td_btn_update.appendChild(btn_update)

            tr.append(td_name, td_price, td_stock, td_available, td_btn_delete, td_btn_update)
            chapter_table_body.appendChild(tr)
            div_form_add.style.display = 'none'
            div_success.innerText = data["msg"]
            div_success.style.display = 'block'
        });
})

for (let btn of btns_delete){
    btn.addEventListener("click", () => deleteChapter(btn))
}

for (let btn of btns_update){
    btn.addEventListener("click", () => displayUpdateForm(btn))
}

btn_form_update.addEventListener("click", function () {
    let url = '/admin/chapters?id=' + productId;
    let formData = new FormData();
    formData.append('method', 'update');
    formData.append('productId', productId);
    formData.append('chapterName', chapter_name.value);
    formData.append('price', chapter_price.value);
    formData.append('stock', chapter_stock.value);

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            let data = JSON.parse(body)
            let tr = document.getElementsByTagName("tr");
            for (let item of tr) {
                if(item.firstElementChild.innerText === data["name"].toString()){
                    let td_name = item.firstElementChild;
                    let td_price = td_name.nextElementSibling;
                    let td_stock = td_price.nextElementSibling;
                    td_price.innerText = data["price"].toString() + '€'
                    td_stock.innerText = data["stock"].toString()
                }
            }

            div_success.innerText = data["msg"]
            div_success.style.display = 'block'
            div_form_add.style.display = 'none'
            btnAddOrUpdate("add")
        });
})

function resetDivSuccess(){
    div_success.innerText = ""
    div_success.style.display = 'none'
}

function resetStockAndPrice(){
    chapter_price.value = ""
    chapter_stock.value = ""
}

function btnAddOrUpdate(value){
    if (value === "add"){
        btn_form_validate.style.display = 'block'
        btn_form_update.style.display = 'none'
    } else if (value === "update"){
        btn_form_validate.style.display = 'none'
        btn_form_update.style.display = 'block'
    }

}

function displayUpdateForm(btn){
    resetDivSuccess()
    //back to Top
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;

    let url = '/admin/chapters'
    let formData = new FormData();
    formData.append('method', 'update_title');

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            title_form.innerText = body
        });

    div_form_add.style.display = 'block'
    let td_name = btn.parentNode.parentNode.firstElementChild
    let td_price = td_name.nextElementSibling;
    let td_stock = td_price.nextElementSibling;
    chapter_name.value = td_name.innerText;
    chapter_stock.value = td_stock.innerText;
    chapter_price.value = td_price.innerText.substring(0, td_price.innerText.length - 1);
    btnAddOrUpdate("update")
}

function deleteChapter(btn){
    resetDivSuccess()
    let chapterId = btn.id.substr(3)
    let url = '/admin/chapters?id=' + productId;
    let formData = new FormData();
    formData.append('method', 'delete');
    formData.append('chapterId', chapterId);

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            let data = JSON.parse(body)
            btn.innerText = data["btn"]
            if(btn.className === "btn btn-success delete"){
                btn.className = "btn btn-danger delete"
            } else {
                btn.className = "btn btn-success delete"
            }
            btn.parentNode.previousElementSibling.innerText = data.value
        });
}