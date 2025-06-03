
<p class="has-text-right pt-4 pb-4">
<a href="#" class="button is-link is-rounded btn-back"><- Regresar atrás</a>
</p>
<script type="text/javascript">
let btn_back = document.querySelector(".btn-back"); // con javascript definimos una variable la cual contiene el elemento con la clase .btn-back(es la clase del botón que queremos que regrese a la página anterior)

    btn_back.addEventListener('click', function(e){ // con javascript le decimos que cuando se haga click en el botón .btn-back se ejecute la función (un EventListener)
        e.preventDefault(); // esto es para que al hacer click no me lleve a otra pagina si esta definida la propiedad href
        window.history.back(); // esto es para que al hacer click en el botón me lleve a la página anterior
});
</script>