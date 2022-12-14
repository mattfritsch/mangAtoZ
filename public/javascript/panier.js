console.log('coucou')

let incrementbuttons= document.getElementsByClassName("increment");
for (var i = 0; i < incrementbuttons.length; i++) {
    console.log(incrementbuttons[i].previousElementSibling.id)
    if(incrementbuttons[i].previousElementSibling.id == 0){
        incrementbuttons[i].disabled = true;
    }
}



function increment(ele){

    stock = ele.previousElementSibling.id;
    nombre = ele.previousElementSibling.value;
    id = ele.parentElement.parentElement.id;
    console.log(id);
    console.log(parseInt(stock)+1)

    nombre = parseInt(nombre) +1;

    ele.previousElementSibling.value = nombre;
    if(parseInt(stock)+1 == nombre){
        button = document.getElementById("increment" + id);
        button.disabled = true;
    }

}


function decrement(ele){

    stock = ele.nextElementSibling.id;
    nombre = ele.nextElementSibling.value;
    id = ele.parentElement.parentElement.id;

    nombre = parseInt(nombre) -1;

    ele.nextElementSibling.value = nombre;

    if(stock >= nombre){
        button = document.getElementById("increment" + id);
        button.disabled = false;
    }

    if(nombre == 0){
        $titre = ele.parentElement.parentElement.parentElement;
        console.log($titre)
        if ($titre.children.length == 2){
            ele.parentElement.parentElement.remove();
            $titre.remove();
        }
        else{
            ele.parentElement.parentElement.remove();

        }
    }

}