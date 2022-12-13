let btn_add = document.getElementById("add")
let div_form_add = document.getElementById("form-add")
let btn_form_validate = document.getElementById("validate-form")
let btn_form_update = document.getElementById("update-form")
let btn_form_cancel = document.getElementById("cancel-form")

let categ_name = document.getElementById("categ_name")
let categ_resume = document.getElementById("categ_resume")

let categ_table_body = document.getElementById("categ-table-body")

let btns_update = document.getElementsByClassName("update")

let title_form = document.getElementById("form-title")
let div_error = document.getElementById("error")

let tr_update;
let actual_categId_update;
let actual_name_update;

btn_add.addEventListener('click', function () {
    div_form_add.style.display = 'block'
    if(btn_add.innerText === "Add"){
        title_form.innerText = "Add new categorie";
    } else {
        title_form.innerText = "Ajouter une nouvelle catégorie";
    }
    resetFields()
    btnAddOrUpdate('add')
    resetDivError()
})

btn_form_cancel.addEventListener('click', function () {
    div_form_add.style.display = 'none'
    btnAddOrUpdate('add')
    resetDivError()
})


btn_form_validate.addEventListener('click', function () {
    resetDivError()
    let url = '/admin/categs';
    let formData = new FormData();
    formData.append('method', 'add');
    formData.append('name', categ_name.value);
    formData.append('resume', categ_resume.value);

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            let data = JSON.parse(body)
            if(data["response"] === "impossible"){
                let p = document.createElement('p')
                div_error.innerText = data["error"]
                div_error.style.display = "block"
            } else {
                addNewTr(data["name"], data["resume"], data["categId"], data["update"])
                div_form_add.style.display = 'none'
            }
        });
})

for (let btn of btns_update){
    btn.addEventListener("click", () => displayUpdateForm(btn))
}

btn_form_update.addEventListener("click", function () {
    resetDivError()
    let url = '/admin/categs';
    let formData = new FormData();
    formData.append('method', 'update');
    formData.append('categId', actual_categId_update);
    formData.append('name', categ_name.value);
    formData.append('resume', categ_resume.value);

    fetch(url, { method: 'POST', body: formData })
        .then(function (response) {
            return response.text();
        })
        .then(function (body) {
            let data = JSON.parse(body)
            if(data["response"] === "impossible"){
                div_error.innerText = data["error"]
                div_error.style.display = "block"
            } else {
                let td = document.getElementsByTagName("tbody")[2].children;
                if (data["name"] === actual_name_update){
                    for (let item of td) {
                        if(item.firstElementChild.innerText === categ_name.value){
                            let td_name = item.firstElementChild;
                            let td_resume = td_name.nextElementSibling;
                            td_resume.innerText = data["resume"]
                            break
                        }
                    }
                } else {
                    for (let item of td) {
                        if(item.firstElementChild.innerText === actual_name_update){
                            item.remove()
                            break
                        }
                    }
                    addNewTr(data["name"], data["resume"], data["categId"], data["update"])
                    div_form_add.style.display = 'none'
                    btnAddOrUpdate('add')
                }
            }
        });
})

function getAlphabeticallyPreviousTr(name) {
    let td = document.getElementsByTagName("tbody")[2].children;
    let previousTr = "";
    for (let item of td) {
        if(name.localeCompare(item.firstElementChild.innerText) === -1){
            return previousTr;
        }
        previousTr = item
    }
    return previousTr;
}

function resetFields() {
    categ_name.value = ""
    categ_resume.value = ""
}

function resetDivError(){
    div_error.innerText = ""
    div_error.style.display = "none"
}

function btnAddOrUpdate(value){
    if(value === "add"){
        btn_form_validate.style.display = 'block'
        btn_form_update.style.display = 'none'
    } else if (value === "update") {
        btn_form_validate.style.display = 'none'
        btn_form_update.style.display = 'block'
    }
}


function displayUpdateForm(btn) {
    resetDivError()
    actual_categId_update = btn.id.substr(3)
    actual_name_update = btn.parentNode.parentNode.firstElementChild.innerText
    //remonter en haut de la page
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    if(btn.innerText === "Update"){
        title_form.innerText = "Update categorie";
    } else {
        title_form.innerText = "Modifier la catégorie";
    }
    div_form_add.style.display = 'block'
    let td_name = btn.parentNode.parentNode.firstElementChild
    let td_resume = td_name.nextElementSibling;
    categ_name.value = td_name.innerText;
    categ_resume.value = td_resume.innerText;
    btnAddOrUpdate('update')
}

function addNewTr(name, resume, categId, update){
    let tr = document.createElement("tr")
    tr.className = "text-center"
    let td_name = document.createElement("td")
    td_name.innerText = name
    let td_resume = document.createElement("td")
    if(resume === undefined){
        td_resume.innerText = ""
    } else {
        td_resume.innerText = resume
    }
    resetFields()

    let td_btn_update = document.createElement("td")
    let btn_update = document.createElement("button")
    btn_update.id = 'del' + categId;
    btn_update.className = "btn btn-warning update";
    btn_update.innerText = update
    btn_update.addEventListener("click", () => displayUpdateForm(btn_update))
    td_btn_update.appendChild(btn_update)

    let previousTr = getAlphabeticallyPreviousTr(name)

    tr.append(td_name, td_resume, td_btn_update)
    if(previousTr === ""){
        categ_table_body.prepend(tr)
    } else {
        previousTr.after(tr)
    }
}
