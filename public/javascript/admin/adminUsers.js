let btns_adminUsers = document.getElementsByClassName("setAdmin");
let div_error = document.getElementById("error");

for (let btn of btns_adminUsers){
    btn.addEventListener("click", function () {
        let btn = this;
        let url = '/admin/users';
        let formData = new FormData();
        formData.append('mail', this.id);

        fetch(url, { method: 'POST', body: formData })
            .then(function (response) {
                return response.text();
            })
            .then(function (body) {
                let data = JSON.parse(body)
                if (data.response === "ok"){
                    btn.innerText = data.btn
                    if(btn.className === "btn btn-success setAdmin"){
                        btn.className = "btn btn-danger setAdmin"
                    } else {
                        btn.className = "btn btn-success setAdmin"
                    }
                    btn.parentNode.previousElementSibling.innerText = data.value
                }
                else{
                    div_error.style.display = 'block';
                    div_error.innerText = data.error;
                }
            });
    });
}