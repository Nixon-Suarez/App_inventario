<div class="container is-fluid mb-6">
    <h1 class="title">Usuarios</h1>
    <h2 class="subtitle">Lista de usuarios</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        require_once "php/main.php";
        //eliminar usuario
        if(isset($_GET['user_id_del'])){
            require_once "php/usuario_eliminar.php";
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
        $url = "index.php?vista=user_list&page="; // Separamos la URL en partes
        $registros = 10; // Cantidad de registros por página
        $busqueda = ""; // Variable para buscar registros

        require_once "php/usuario_lista.php";
    ?>
</div>