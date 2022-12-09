let btns_delete = document.getElementsByClassName('delete');


for ( i=0; i<btns_delete.length; i++){
    btns_delete[i].addEventListener("click", function () {
        let btn = this;
        let id = btn.id.substr(3);

        let url = '/admin';
        let formData = new FormData();
        formData.append('id', id);

        fetch(url, { method: 'POST', body: formData })
            .then(function (response) {
                return response.text();
            })
            .then(function (body) {
                document.location.reload(true)
            });
    });
}
