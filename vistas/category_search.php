<div class="container is-fluid mb-6">
    <h1 class="title">Categoria</h1>
    <h2 class="subtitle">Buscar categoria</h2>
</div>

<div class="container pb-6 pt-6">
    <?php 
        require_once "php/main.php";
        if(isset($_POST['modulo_buscador'])){
            require_once "php/buscador.php"; // Incluimos el archivo buscador.php para procesar la busqueda
        }

        if(!isset($_SESSION['busqueda_categoria']) && empty($_SESSION['busqueda_categoria'])){ // Si no existe la variable de sesión de busqueda_categoria, muestra el formulario de busqueda
    ?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="categoria"> <!-- asigan a la variable de modulo_buscador el valor de categoria  (al esatr oculto no se muestra) -->
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit">Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php }else{ ?>

    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="categoria"> 
                <input type="hidden" name="eliminar_buscador" value="categoria">
                <p>Estas buscando <strong>"<?php echo $_SESSION['busqueda_categoria']?>"</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>
    <?php

    //eliminar usuario
    if(isset($_GET['category_id_del'])){
        require_once "php/categoria_eliminar.php";
    }
        
    if(!isset($_GET['page'])){
        $pagina = 1;
    }else{
        $pagina = (int) $_GET['page']; #(int) convierte a entero el valor obtenido de la URL
        if($pagina <= 0){
            $pagina = 1;
        }
    }
    
    $pagina = limpiar_cadena($pagina); // Limpiamos la cadena de la URL
    $url = "index.php?vista=category_search&page="; // Separamos la URL en partes
    $registros = 10; // Cantidad de registros por página
    $busqueda = $_SESSION['busqueda_categoria']; // Variable para buscar registros
    
    require_once "php/categoria_lista.php";
        }
    ?>
</div>