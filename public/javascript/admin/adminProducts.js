let btns_delete = document.getElementsByClassName("delete");

for (let btn of btns_delete){
    btn.addEventListener("click", function () {
        let btn = this
        let id = btn.id.toString().substr(3);
        let url = '/admin/products';
        let formData = new FormData();
        formData.append('id', id);

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
                btn.parentNode.previousElementSibling.innerText = data["value"]
            });
    });
}