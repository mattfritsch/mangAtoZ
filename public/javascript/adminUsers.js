let btn_adminUsers = document.getElementsByClassName("setAdmin");
let div_error = document.getElementById("error");

for ( i=0; i<btn_adminUsers.length; i++){
    btn_adminUsers[i].addEventListener("click", function () {
        let btn = this;
        let url = '/admin/users';
        let formData = new FormData();
        formData.append('mail', this.id);

        fetch(url, { method: 'POST', body: formData })
            .then(function (response) {
                return response.text();
            })
            .then(function (body) {
                if (body === "ok"){
                    // console.log(btn.innerText)
                    document.location.reload(true)
                }
                else{
                    div_error.style.display = 'block';
                    div_error.innerText = body;
                }
            });
    });
}