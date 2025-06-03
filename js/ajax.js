// 23_subirEnviarArchivosPHPAjax
const formulario_ajax = document.querySelectorAll(".FormularioAjax"); //selecciona todos los formularios con la clase FormularioAjax

function enviar_formulario_ajax(evento){
    evento.preventDefault(); //hace que se envia el formulario sin recargar la pagina
    // alerta que pregunta si se quiere enviar el formulario
    let enviar=confirm("Quieres enviar el formulario");

    // si se le da aceptar a la alerta 
    if(enviar==true){

        let data = new FormData(this); // crea un array en base a la informacion del formulario
        let method = this.getAttribute("method"); //obtine el method del formulario
        let action = this.getAttribute("action"); //obtine el action(URL) del formulario

        let encabezados = new Headers(); //crea un nuevo encabezado

        let config = {
            method: method,
            headers: encabezados,
            mode: 'cors',
            cache: 'no-cache',
            body: data
        };

        fetch(action, config)
        .then(respuesta => respuesta.text()) // la respuesta del archivo Carga la convierte en texto
        .then(respuesta => {  
            let contenedor = document.querySelector(".form-rest"); //selecciona el contenedor(elementoHTML) donde se mostrara la respuesta en este caso es un div que va a tener esa clase
            contenedor.innerHTML = respuesta; //muestra la respuesta en el contenedor
        });
    }
}

// cuando se envia el formulario se ejecuta la funcion enviar_formulario_ajax
formulario_ajax.forEach(formularios => {
    formularios.addEventListener('submit', enviar_formulario_ajax);
});