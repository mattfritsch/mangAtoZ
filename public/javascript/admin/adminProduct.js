// let btns_delivered = document.getElementsByClassName("delivered");
//
// for (let btn of btns_delivered){
//     btn.addEventListener("click", function () {
//         let btn = this;
//         let url = '/admin/orders';
//         let formData = new FormData();
//         formData.append('orderId', this.id);
//
//         fetch(url, { method: 'POST', body: formData })
//             .then(function (response) {
//                 return response.text();
//             })
//             .then(function (body) {
//                 console.log(body)
//                 let data = JSON.parse(body)
//                 btn.innerText = data["btn"]
//                 if(btn.className === "btn btn-success delivered"){
//                     btn.className = "btn btn-danger delivered"
//                 } else {
//                     btn.className = "btn btn-success delivered"
//                 }
//                 btn.parentNode.previousElementSibling.innerText = data["value"]
//             });
//     });
// }